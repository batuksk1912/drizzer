<div class="container mainContainer">

  <div class="row">
      
  <div class="col-md-8">
      
        <?php if ($_GET['userid']) { ?>
      
      <?php showTweets($_GET['userid']); ?>
      
      <?php } else { ?> 
        
        <h2>Active Users</h2>
        
        <?php displayUsers(); ?>
      
      <?php } ?>
      
  </div>
  
  <div class="col-md-4">
    
    <br><br><br>

  	<?php showSearch(); ?>

    <hr>

    <?php trendsTopic(); ?>
        
   </div>
   
</div>
    
</div>