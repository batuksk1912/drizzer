
<div class="container mainContainer">


<div class="row">


  <div class="col-md-8" id="timeline">

    <h3>Your Posts</h3>

    <?php showTweets('profile'); ?>


  </div>
  

  <div class="col-md-4">
    
    <br><br><br>
      
       <?php showSearch(); ?>
      
      <hr>
      
       <?php showProfile(); ?>
      
       <?php trendsTopic(); ?>

  </div>
  
</div>

</div>