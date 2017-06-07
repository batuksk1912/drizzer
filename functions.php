<?php

   session_start();

   date_default_timezone_set('Europe/Istanbul');

   $link = mysqli_connect("localhost", "root", "root", "drizzer");

   if (mysqli_connect_errno()) {


   	   print_r(mysqli_connect_error());
   	   exit();

   }

   if ($_GET['function'] == "logout") {

   	session_unset();

   }

   function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , 'sec')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

   function showTweets($type) {

   	global $link;

   	if ($type == 'public') {

   		$whereClause = "";

   	} else if ($type == 'isFollowing') {
            
            $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']);
            $result = mysqli_query($link, $query);
            
            $whereClause = "";

            if (mysqli_num_rows($result) == 0) {
            
            echo "<h4>No tweets to display. Cause of you are not following anyone. For start using, follow someone.</h4>";
            //break;
            
            } else {  
            
            while ($row = mysqli_fetch_assoc($result)) {
                
                if ($whereClause == "") $whereClause = "WHERE";
                else $whereClause.= " OR";
                $whereClause.= " userid = ".$row['isFollowing'];

            }
        } 

    } else if ($type == 'profile') {
            
           $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $_SESSION['id']);
            
    } else if ($type == 'search') {
            
            echo '<p>Showing search results for "'.mysqli_real_escape_string($link, $_GET['q']).'":</p>';
            
           $whereClause = "WHERE tweet LIKE '%". mysqli_real_escape_string($link, $_GET['q'])."%'";
            
    } else if (is_numeric($type)) {
            
            $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
            $userQueryResult = mysqli_query($link, $userQuery);
            $user = mysqli_fetch_assoc($userQueryResult);
            
            echo "<h3>".mysqli_real_escape_string($link, $user['email'])."'s Posts</h3>";
            
            $whereClause = "WHERE userid = ". mysqli_real_escape_string($link, $type);
                   
    }

   	$query = "SELECT * FROM tweets ".$whereClause." ORDER BY `datetime` DESC LIMIT 10";
        
    $result = mysqli_query($link, $query);
        
        if (mysqli_num_rows($result) == 0) {
            
            echo "No tweets to display.";
            
        } else {
            
                $ip = "46.197.237.219";
                //$ip  = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                $url = "http://freegeoip.net/json/$ip";
                $ch  = curl_init();
    
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                $data = curl_exec($ch);
                curl_close($ch);

                if ($data) {
                    
                $location = json_decode($data);

                $lat = $location->latitude;
                $lon = $location->longitude;
                                         
                }
            
                $latArea = $lat + 1.7;
                $lonArea = $lon + 1.7;
                        
        	 while ($row = mysqli_fetch_assoc($result)) {
                
                $userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
                $userQueryResult = mysqli_query($link, $userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);

                $timeTweet = $row['datetime'] . " UTC+01:00";
                 
                if ($user['img'] == null) {
                    
                    $srcpath = "http://placehold.it/32x32";
                
                } else {
                    
                    $srcpath = "/drizzer/upload/".$user['img']."";
                }
                                 
                if (($row['lat'] >= $lat && $row['lat'] < $latArea && $row['lng'] >= $lon && $row['lng'] < $lonArea ) || $row['lat'] == 0 || $type == 'profile' ) { 
                  
                echo "<div class='well well-sm'><div id='profile-mini-avatar'><img id='time-mini-ava' src=".$srcpath."></div><p><a href='?page=accounts&userid=".$user['id']."'>".$user['email']."&nbsp;&nbsp;</a><span class='time'>".time_since(time() - strtotime($timeTweet))." ago</span></p>";

                echo "<p>".$row['tweet']."</p>";
                                  
                echo "<a class='toggleFollow' data-userId='".$row['userid']."'>";
                
                $isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
            $isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
            if (mysqli_num_rows($isFollowingQueryResult) > 0) {
                
                if ($_SESSION['id'] > 0) {

                echo "Unfollow";

            }
                
            } else {

            	if ($_SESSION['id'] > 0) {
   
                echo "Follow";

            }
                
            }
                echo "<a class='toggleLiked' data-tweetId='".$row['id']."'>";
                 
                if ($_SESSION['id'] > 0) { 
                  
                if ($row['likes'] == 1) {
                    
                    echo "&nbsp&nbspLiked";
                    
                } else {
                    
                    echo "&nbsp&nbspLike";
                }
                    
                }
                 echo "</a></a></div>";
        }
        
      }

   }

}

function showSearch() {

        
        echo '<form class="form-inline">
    <div class="form-group">
    <input type="hidden" name="page" value="search">
    <input type="text" name="q" class="form-control" id="search" placeholder="Search Post">
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
    </form>';
         
}

function showProfile() {

	global $link;

	$queryMail = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultMail = mysqli_query($link, $queryMail);
    $rowMail = mysqli_fetch_assoc($resultMail);

    $queryFollowing = "SELECT * FROM isFollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultFollowing = mysqli_query($link, $queryFollowing);
    $rowFollowing = mysqli_num_rows($resultFollowing);

    $queryFollower = "SELECT * FROM isFollowing WHERE isFollowing = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultFollower = mysqli_query($link, $queryFollower);
    $rowFollower = mysqli_num_rows($resultFollower);

    $queryTweet = "SELECT * FROM tweets WHERE userid = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultTweet = mysqli_query($link, $queryTweet);
    $rowTweet = mysqli_num_rows($resultTweet);


	  echo '<div class="profile-block">
              <div class="image-header" style="background-image: url('.(($rowMail['imgheader'] == null) ? "http://placehold.it/76x76" : "/drizzer/upload/".$rowMail['imgheader']).');">
              </div>
              <div class="user-info">
               <img height="76" width="76" src="'.(($rowMail['img'] == null) ? "http://placehold.it/76x76" : "/drizzer/upload/".$rowMail['img']).'">
               <div class="info">
               <div class="name">'.$rowMail['dname'].'</div>
               <div class="email">'.$rowMail['email'].'</div>
               </div>
               </div>
               <div class="body bg-white">
               <ul class="list-inline">
               <li><a href="#"><span class="title">Posts</span>
               <span class="amount">'.$rowTweet.'</span></li>
               <li><a href="#"><span class="title">Following</span>
               <span class="amount">'.$rowFollowing.'</span></li>
               <li><a href="#"><span class="title">Followers</span>
               <span class="amount">'.$rowFollower.'</span></li>
               </ul>
               </div>
               </div>';

}

 function showTweetArea() {
        
        if ($_SESSION['id'] > 0) {
            
            echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyBa-GcK9YZMAL7OEDCBZQwhILFL_pACc64">></script>
                  <script type="text/javascript" src="http://localhost:8888/drizzer/map.js"></script>
            <hr><div id="tweetSuccess" class="alert alert-success">Your tweet was posted successfully.</div>
            <div id="tweetFail" class="alert alert-danger"></div>
            <div class="form">
    <div class="form-group">
    <textarea class="form-control" id="tweetContent"></textarea>
    <br>
    <div id="map"></div>
     <input type="hidden" id="lat">
     <input type="hidden" id="lng"> 
    </div>
    <button id=postTweetButton class="btn btn-primary">Post</button>
    </div>';
            
            
        }    
    }

function trendsTopic() {
    
    global $link;

    $resultTags = mysqli_query($link, "SELECT DISTINCT hashtag FROM hashtags ORDER BY datetime DESC LIMIT 10");
    
     echo '<div class="widget bg-white">

          <h4>Trends Topic</h4>

          <ul class="no-type">';
    
         while ($rowTags = mysqli_fetch_assoc($resultTags)) {

   
          echo '<li><a href="#">'.$rowTags['hashtag'].'</a></li>';

         }
    
          echo '</ul>

          </div>';    
}

function userStats() {
    
    global $link;
    
    $queryFollowing = "SELECT * FROM isFollowing WHERE follower = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultFollowing = mysqli_query($link, $queryFollowing);
    $rowFollowing = mysqli_num_rows($resultFollowing);

    $queryFollower = "SELECT * FROM isFollowing WHERE isFollowing = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultFollower = mysqli_query($link, $queryFollower);
    $rowFollower = mysqli_num_rows($resultFollower);

    $queryTweet = "SELECT * FROM tweets WHERE userid = ".mysqli_real_escape_string($link, $_SESSION['id']);
    $resultTweet = mysqli_query($link, $queryTweet);
    $rowTweet = mysqli_num_rows($resultTweet);
    
    echo  '<h4>Usage Rates</h4>';
        
 if ($rowFollower >= 0 && $rowFollower < 10) {
        
     echo 'Follower Rate';
     
        echo '<div class="progress">
  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="25"
  aria-valuemin="0" aria-valuemax="100" style="width:25%">
    25% 
  </div>
</div>';
        
    } else if  ($rowFollower >= 10 && $rowFollower < 50) {
     
     echo 'Follower Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50"
  aria-valuemin="0" aria-valuemax="100" style="width:50%">
    50% 
  </div>
</div>';
        
        
    } else if  ($rowFollower >= 50 && $rowFollower < 100) {
     
     echo 'Follower Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75"
  aria-valuemin="0" aria-valuemax="100" style="width:75%">
     75% 
  </div>
</div>';
        
    } else if  ($rowFollower >= 100) {
     
     echo 'Follower Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
  aria-valuemin="0" aria-valuemax="100" style="width:100%">
    100%
  </div>
</div>';
        
    }
    
    
if ($rowFollowing >= 0 && $rowFollowing < 10) {
     
     echo 'Following Rate';
        
        echo '<div class="progress">
  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="25"
  aria-valuemin="0" aria-valuemax="100" style="width:25%">
    25% 
  </div>
</div>';
        
    } else if  ($rowFollowing >= 10 && $rowFollowing < 50) {
     
         echo 'Following Rate';
    
         echo '<div class="progress">
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50"
  aria-valuemin="0" aria-valuemax="100" style="width:50%">
    50%
  </div>
</div>';
        
        
    } else if  ($rowFollowing >= 50 && $rowFollowing < 100) {
     
     echo 'Following Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75"
  aria-valuemin="0" aria-valuemax="100" style="width:75%">
    75%
  </div>
</div>';
        
    } else if  ($rowFollowing >= 100) {
     
    echo 'Following Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
  aria-valuemin="0" aria-valuemax="100" style="width:100%">
    100%
  </div>
</div>';
        
    }           
    
    
 if ($rowTweet >= 0 && $rowTweet < 10) {
     
     echo 'Tweet Rate';
        
        echo '<div class="progress">
  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="25"
  aria-valuemin="0" aria-valuemax="100" style="width:25%">
    25% 
  </div>
</div>';
        
    } else if  ($rowTweet >= 10 && $rowTweet < 50) {
     
     echo 'Tweet Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="50"
  aria-valuemin="0" aria-valuemax="100" style="width:50%">
    50%
  </div>
</div>';
        
        
    } else if  ($rowTweet >= 50 && $rowTweet < 100) {
     
     echo 'Tweet Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="75"
  aria-valuemin="0" aria-valuemax="100" style="width:75%">
    75%
  </div>
</div>';
        
    } else if  ($rowTweet >= 100) {
     
     echo 'Tweet Rate';
        
         echo '<div class="progress">
  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
  aria-valuemin="0" aria-valuemax="100" style="width:100%">
    100%
  </div>
</div>';
        
    }        
}

function userDisplayName() {
    
 echo '<form>
  <div class="form-group">
    <label for="displayName">Choose a display name</label>
    <input type="text" class="form-control" id="displayName" placeholder="Name">
  </div>
  <button id=postDisplayName class="btn btn-primary">Change</button>
</form>';
       
}

function changePassword() {
    
 echo '<form>
  <div class="form-group">
    <label for="newPassword">New Password</label>
    <input type="password" class="form-control" id="newPassword" placeholder="New Password">
  </div>
  <button id=postnewPassword class="btn btn-primary">Change Password</button>
</form>';
       
}

function base64_to_image($base64_string, $output_file) {
	$ifp = fopen($output_file, "wb");

	$data = explode(',', $base64_string);

	fwrite($ifp, base64_decode($data[1]));
	fclose($ifp);

	return $output_file;
}

function uploadProfilePic() {
    
    echo '<link rel="stylesheet" href="http://localhost:8888/drizzer/cropBox.css" type="text/css"/>
          <script src="http://localhost:8888/drizzer/cropBox.js"></script>
          <script src="http://localhost:8888/drizzer/cropBox_2.js"></script>
<div class="panel panel-default avatar-panel">
    <div class="panel-body">
        <h2>Profil Picture</h2>
        <p class="sub-header">Choose your profile picture</p>
        <input type="hidden" id="hash" name="hash" value="<?=$hash;?>">
    </div>
    <hr>
    <div class="panel-body">
        <div class="container">
            <div class="imageBox">
                <div class="thumbBox"></div>
                <div class="spinner" style="display: none">Loading...</div>
            </div>
            <div class="action">

                <div title="Resim Ekle" data-toggle="tooltip" data-placement="left" class="btn btn-white btn-sm" style="border:1px solid #ced9e4; margin-top:0;"><input type="file" class="upload" id="file"></div>
                <button class="btn btn-info btn-sm" id="btnZoomIn" style="margin-top:0;float: none">+</button>
                <button class="btn btn-info btn-sm" id="btnZoomOut" style="margin-top:0;float: none">-</button>
                <!--<input type="button" class="btn btn-white btn-sm" id="btnZoomIn" value="+" style="margin-top:0;border:1px solid #ced9e4;float: right">-->
                <!--<input type="button" class="btn btn-white btn-sm" id="btnZoomOut" value="-" style="margin-top:0;border:1px solid #ced9e4;float: right">-->
                <button class="btn doAvatarEdit btn-danger disabled" style="margin-top:-5px; float: right;">Upload</button>
            </div>
            <div class="cropped">

            </div>
            <input type="hidden" id="newPic" class="newPic" name="newPic" value="">
        </div>
    </div>
</div>';
       
}

function uploadHeaderPic() {
    
    echo '<link rel="stylesheet" href="http://localhost:8888/drizzer/cropBox.css" type="text/css"/>
          <script src="http://localhost:8888/drizzer/cropBox.js"></script>
          <script src="http://localhost:8888/drizzer/cropBox_3.js"></script>
<div class="panel panel-default avatar-panel">
    <div class="panel-body">
        <h2>Header Picture</h2>
        <p class="sub-header">Choose your header picture</p>
        <input type="hidden" id="hash" name="hash" value="<?=$hash;?>">
    </div>
    <hr>
    <div class="panel-body">
        <div class="container">
            <div class="imageBox">
                <div class="thumbBox"></div>
                <div class="spinner" style="display: none">Loading...</div>
            </div>
            <div class="action">

                <div title="Resim Ekle" data-toggle="tooltip" data-placement="left" class="btn btn-white btn-sm" style="border:1px solid #ced9e4; margin-top:0;"><input type="file" class="upload" id="file"></div>
                <button class="btn btn-info btn-sm" id="btnZoomIn" style="margin-top:0;float: none">+</button>
                <button class="btn btn-info btn-sm" id="btnZoomOut" style="margin-top:0;float: none">-</button>
                <!--<input type="button" class="btn btn-white btn-sm" id="btnZoomIn" value="+" style="margin-top:0;border:1px solid #ced9e4;float: right">-->
                <!--<input type="button" class="btn btn-white btn-sm" id="btnZoomOut" value="-" style="margin-top:0;border:1px solid #ced9e4;float: right">-->
                <button class="btn doHeaderEdit btn-danger disabled" style="margin-top:-5px; float: right;">Upload</button>
            </div>
            <div class="cropped">

            </div>
            <input type="hidden" id="newPic" class="newPic" name="newPic" value="">
        </div>
    </div>
</div>';
       
}

function displayUsers() {
        
        global $link;
        
        $query = "SELECT * FROM users LIMIT 10";
        
        $result = mysqli_query($link, $query);
            
        while ($row = mysqli_fetch_assoc($result)) {
            
            echo "<p><a href='?page=accounts&userid=".$row['id']."'>".$row['email']."</a></p>";
            
        }   
}


?> 