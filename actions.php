<?php

include("functions.php");

if ($_GET['action'] == "SignInUp") {

	$error = "";

	if(!$_POST['email']) {

		$error = "An e-mail adress is required.";

	} else if(!$_POST['password']) {

    	$error = "A password is required.";

    } else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
  
        $error = "Please enter valid e-mail.";

    }

    if($error != "") {

    	echo $error;
    	exit();
    }


	if($_POST['loginDetect'] == "0") {

		$query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
		$result = mysqli_query($link, $query);

		if (mysqli_num_rows($result) > 0) {

			$error = "Please enter different e-mail adress. This one already taken.";

		} else {

			$query = "INSERT INTO users (`email`, `password`) VALUES ('". mysqli_real_escape_string($link, $_POST['email'])."', '". mysqli_real_escape_string($link, $_POST['password'])."')";

			if(mysqli_query($link, $query)) {

				$_SESSION['id'] = mysqli_insert_id($link);
                $_SESSION['email'] = $_POST['email'];

				$query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['password']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
                    mysqli_query($link, $query);

				echo 1;

			} else {

				$error = "User did not create.";

			}

		}
	} else {

		$query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

		$result = mysqli_query($link, $query);

		$row = mysqli_fetch_assoc($result);
                
                if ($row['password'] == md5(md5($row['id']).$_POST['password'])) {
                    
                    echo 1;

                    $_SESSION['id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                     
                } else {
                    
                    $error = "Not found username/password combination. Please try again.";
                    
                }

	}

		if($error != "") {

    	echo $error;

        exit();
        
    }

}

if($_GET['action'] == "toggleFollow") {

	$query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";
            $result = mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                
                $row = mysqli_fetch_assoc($result);
                
                mysqli_query($link, "DELETE FROM isFollowing WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1");
                
                echo 1;
                  
            } else {
                
                mysqli_query($link, "INSERT INTO isFollowing (follower, isFollowing) VALUES (". mysqli_real_escape_string($link, $_SESSION['id']).", ". mysqli_real_escape_string($link, $_POST['userId']).")");
                
                echo 2;
                
            }

}


if($_GET['action'] == "toggleLiked") {
    
   $query = "SELECT * FROM tweets WHERE id = '". mysqli_real_escape_string($link, $_POST['tweetId'])."'";
   $result = mysqli_query($link, $query);
    
   if (mysqli_num_rows($result) > 0) {
       
       $row = mysqli_fetch_assoc($result);
       
       if ($row['likes'] == 1) {
           
           mysqli_query($link, "UPDATE tweets SET likes='0' WHERE id = '". mysqli_real_escape_string($link, $_POST['tweetId'])."'");
           
           echo 0;
           
       } else if ($row['likes'] == 0) {
           
           mysqli_query($link, "UPDATE tweets SET likes='1' WHERE id = '". mysqli_real_escape_string($link, $_POST['tweetId'])."'");
           
           echo 1;
           
           
       }
       
   }
       
}

if ($_GET['action'] == 'uploadHeaderImage') {
    
    $filename = 'header' . $_SESSION['id'] . ".jpg";
    
    $path = "upload/" . $filename;
        
        if(base64_to_image($_POST['img'], $path)){
            mysqli_query($link, "UPDATE users SET imgheader='".$filename."' WHERE id = ". mysqli_real_escape_string($link, $_SESSION['id'])."");
        }
        echo json_encode($result);
    
}

if ($_GET['action'] == 'uploadImage') {
    
    $filename = 'user' . $_SESSION['id'] . ".jpg";
    
    $path = "upload/" . $filename;
        
        if(base64_to_image($_POST['img'], $path)){
            mysqli_query($link, "UPDATE users SET img='".$filename."' WHERE id = ". mysqli_real_escape_string($link, $_SESSION['id'])."");
        }
        echo json_encode($result);
    
}

if($_GET['action'] == "newMessage") {
    
    $query = "SELECT * FROM users WHERE email = '". mysqli_real_escape_string($link, $_POST['email'])."'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_assoc($result);
      
    
    if($_POST['timeouttime'] == '') {
        $newdate='';
        
    } else {
        $today = date("Y-m-d H:i:s"); 
        $newdate= strtotime($_POST['timeouttime'].' minute',strtotime($today)); 
        $newdate = date("Y-m-d H:i:s" , $newdate );    
    }
    
    mysqli_query($link, "INSERT INTO messages (`fromid`, `toid`, `message`, `timeout`) VALUES (".$_SESSION['id'].", ".  $row['id'].", '".mysqli_real_escape_string($link,$_POST['message'])."', '".$newdate."')");
            
    echo "ok";
}


if($_GET['action'] == "getMessages") {
    
    $query = "SELECT u.id,u.email,u.img FROM users as u INNER JOIN messages as m ON u.id = m.toid WHERE m.fromid = ".$_SESSION['id']." UNION SELECT u.id, u.email,u.img FROM users as u INNER JOIN messages as m ON u.id = m.fromid WHERE m.toid = " . $_SESSION['id'];
    
    /*$query = "SELECT DISTINCT email FROM users WHERE id = messages.toid OR id = messages WHERE messages.fromid = ". mysqli_real_escape_string($link, $_SESSION['id'])."";*/
    $result = mysqli_query($link, $query);
    
    $res = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
    $res[] = $row;
    }
      
    echo json_encode($res);
}


if($_GET['action'] == "getSingleMessages") {
    
    $query = "SELECT u.id,u.email,u.img,m.id as msgid,m.message,m.timeout FROM users as u INNER JOIN messages as m ON u.id = m.fromid WHERE m.fromid = ".$_SESSION['id']." AND m.toid=".$_POST['id']." UNION SELECT u.id, u.email, u.img ,m.id as msgid,m.message,m.timeout FROM users as u INNER JOIN messages as m ON u.id = m.fromid WHERE m.toid = " . $_SESSION['id']. " AND m.fromid =" . $_POST['id'];
    
    $currenttime = date('Y-m-d H:i:s');
    
    
    $result = mysqli_query($link, $query);
    
    $res = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        if($row['timeout'] == '0000-00-00 00:00:00' || strtotime($row['timeout']) > strtotime($currenttime))
            $res[] = $row; 
        else
        {
           mysqli_query($link, "DELETE FROM messages WHERE id = ". mysqli_real_escape_string($link, $row['msgid']).""); 
        }
    }
      
    echo json_encode($res);
    
}

if ($_GET['action'] == 'postDisplayName') {
    
    mysqli_query($link, "UPDATE users SET dname='".$_POST['displayName']."' WHERE id = ". mysqli_real_escape_string($link, $_SESSION['id'])."");
    
}

if ($_GET['action'] == 'postnewPassword') {
    
    $query = "UPDATE users SET password = '". md5(md5($_SESSION['id']).$_POST['newPassword']) ."' WHERE id = ".$_SESSION['id']." LIMIT 1";
    mysqli_query($link, $query);
    
}

if ($_GET['action'] == 'postTweet') {
        
        if (!$_POST['tweetContent']) {
                    
            echo "Your tweet is empty!";
                    
            } else if (strlen($_POST['tweetContent']) > 140) {
            
            echo "Your tweet is too long!";
            
        } else {
            
            if ($_POST['lat'] == null) {
                
                $_POST['lng'] = 0;
                $_POST['lat'] = 0;
                
            }
            
            mysqli_query($link, "INSERT INTO tweets (`tweet`, `userid`, `datetime`, `lat`, `lng`) VALUES ('". mysqli_real_escape_string($link, $_POST['tweetContent'])."', ". mysqli_real_escape_string($link, $_SESSION['id']).", CURRENT_TIMESTAMP(), ". mysqli_real_escape_string($link, $_POST['lat']).", ". mysqli_real_escape_string($link, $_POST['lng']).")");
            
            echo 1;
            
            $pattern = '/\B#\w\w+/';
            
            $subject = $_POST['tweetContent'];
            
            $regmatchSuccess = preg_match($pattern, $subject, $match);
            
            if ($regmatchSuccess) {
                   
                 mysqli_query($link, "INSERT INTO hashtags (`hashtag`, `datetime`) VALUES ('". mysqli_real_escape_string($link, $match[0])."', CURRENT_TIMESTAMP())");
                       
            }
            
        }
        
    }

?>