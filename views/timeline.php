
<div class="container mainContainer">

<div class="row">


  <div class="col-md-3">

   <?php showProfile(); ?>
      
   <?php trendsTopic(); ?>

</div>


  <div class="col-md-6">
  	
  	
  	<?php showTweets('isFollowing'); ?>


  </div>
  
  <div class="col-md-3">
  	
  	<?php showSearch(); ?>

  	<?php showTweetArea(); ?>
      
      <br><br>
      
    <?php userStats(); ?>

  </div>
  
</div>

</div>