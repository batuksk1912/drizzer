
<div class="container mainContainer">


<div class="row">


  <div class="col-md-4">
      
  <h3>Account Settings</h3>
      
  <br>
  	
  <div class="list-group">
      
  <form action="" method="post">
  <button type="submit" name="display" value="a" class="list-group-item">Profile Name</button>
  <button type="submit" name="display" value="b" class="list-group-item">Change Password</button>
  <button type="submit" name="display" value="c" class="list-group-item">Profile Image</button>
  <button type="submit" name="display" value="d" class="list-group-item">Header Image</button>
  </form>
  </div>
      
  </div>
  

  <div class="col-md-8">
  	
  	<?php
      
      if ($_POST['display'] == 'a') {
          
          echo '<h3>Profile Name</h3><br>';
          
          userDisplayName();
              
      } else if ($_POST['display'] == 'b') {
          
          echo '<h3>Change Password</h3><br>';
          
          changePassword();
                
      } else if ($_POST['display'] == 'c') {
          
          uploadProfilePic();
          
      } else if ($_POST['display'] == 'd') {
          
          uploadHeaderPic();
      } 
      
      ?>

  </div>
  
</div>

</div>