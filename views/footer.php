<footer class="footer">
      <div class="container">
        <p class="text-muted">Drizzer Social Web Site &copy 2017 - Celal Bayar University Graduation Project By { Baturay Kayaturk - 120315024, Ibrahim Kaplan - 120315022 }</p>
      </div>
    </footer>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sign In</h4>
      </div>
      <div class="modal-body">
      <div class="alert alert-danger" id="loginAlert"></div>
<form>
<input type="hidden" name="loginDetect" id="loginDetect" value="1">
  <div class="form-group">
    <label for="exampleInputEmail1">E-mail address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="E-mail">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>  
</form>
      </div>
      <div class="modal-footer">
      <a id="loginChanger">Sign Up</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="logSigBut">Sign In</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Message -->

<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="msgModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <button type="button" id="messageButton" data-toggle="modal" data-target="#newMessageForm" class="btn btn-sm btn-primary pull-right app-new-msg js-newMsg" >New message</button>
        <h4 class="modal-title">Messages</h4>
      </div>

      <div class="modal-body p-a-0 js-modalBody" style="max-height:300px; overflow:auto;">
        <div class="modal-body-scroller">
          <div class="media-list media-list-users list-group js-msgGroup">
            
            
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- Modal Message -->
              
<!-- Modal Message Form --> 
              
<div id="newMessageForm" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">New Message</h4>
      </div>
      <div class="modal-body">
        <form id="newMessageSendForm">
  <div class="form-group">
    <label for="exampleInputEmail1">To</label>
    <input type="email" name="email" class="form-control" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Message</label>
<textarea class="form-control" name="message"></textarea> 
            </div>
      <div class="form-group">
    <label for="exampleInputEmail1">Timeout</label>
    <input type="number" name="timeouttime" class="form-control" placeholder="Time in minute">
  </div>      
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="sendMessage" class="btn btn-primary">Send</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
              
<!-- Modal Message Form -->  
              
<script>
      
    $(function(){
        $("#messageButton").click(function() {
        
        $('#msgModal').modal('hide');
        //$('#newMessageForm').modal('show');
    });
    });
    
    $("#sendMessage").click(function() {
        
        var datas = $('#newMessageSendForm').serialize();
        $.ajax({
            method:"post",
            url:"actions.php?action=newMessage",
            data: datas,
            success:function(r){
                if(r.trim() == "ok") {
                    $('#msgModal').modal('show');
                    $('#newMessageForm').modal('hide');   
                    }
            }
            
        });
        
    });
    
    function showAllMessage(){
          
        $.ajax({
            method:"post",
            url:"actions.php?action=getMessages",
            data: {i:1},
            success:function(r){
                var r = JSON.parse(r);
                $('#msgModal .media-list').html("");
                for(var i=0; i<r.length;i++)
                    {
                        $('#msgModal .media-list').append(
                        '<a class="single-message list-group-item" data-msg-usr="'+r[i].id+'" href="#">'+
              '<div class="media">'+
                '<span class="media-left">'+
                '<img height="40" width="40" class="img-circle media-object" src="'+((r[i].img == null) ?  "http://placehold.it/40x40" : "/drizzer/upload/"+r[i].img) +'">'+
                '</span>'+
                '<div class="media-body">'+
                  '<strong>Me and '+r[i].email+'</strong>'+
                '</div>'+
              '</div>'+
            '</a>'
                        );
                    }
            }
            
        });
    }
    
    $('#msgModal').on('shown.bs.modal', function() {
        
        showAllMessage();
    });
    
     $('#msgModal').on('hide.bs.modal', function() {
        $('#backButton').remove();
    });
    
    
    $(document).delegate('#backButton', 'click', function(){
        showAllMessage();
        $(this).remove();
    });
    
    $(document).delegate('.single-message', 'click', function(){
        var iam = "<?=$_SESSION['email']?>";
        var id = $(this).data('msg-usr');
        var style1="",style2="";
                $.ajax({
            method:"post",
            url:"actions.php?action=getSingleMessages",
            data: {id: id},
            success:function(r){
                var r = JSON.parse(r);
                $('#msgModal .media-list').html("");
                $('#msgModal .modal-header').append('<button type="button" id="backButton" class="btn btn-sm btn-primary pull-right app-new-msg js-newMsg" style="margin-top:-25px;margin-right:7px;">Back</button>');
                for(var i=0; i<r.length;i++)
                    {
                        if(r[i].email == iam)
                            {
                                style1="text-align:right;";
                                style2="float:right;padding-left:10px;"
                            }
                        else
                            {
                                style1 = "";
                                style2="";
                            }
                        $('#msgModal .media-list').append(
                        '<span style="display:block; margin-bottom:20px;">'+
              '<div class="media" style="'+style1+'">'+
                '<span class="media-left" style="'+style2+'">'+
                '<img height="40" width="40" class="img-circle media-object" src="'+((r[i].img == null) ?  "http://placehold.it/40x40" : "/drizzer/upload/"+r[i].img) +'">'+                
                '</span>'+
                '<div class="media-body">'+
                  '<strong>'+r[i].email+'</strong>'+
                  '<div class="media-body-secondary">'+
                    r[i].message +
                  '</div>'+
                '</div>'+
              '</div>'+
            '</span>'
                        );
                    }
            }
            
        });
        
    });


	$("#loginChanger").click(function() {

		if($("#loginDetect").val() == "1") {

			$("#loginDetect").val("0");
			$("#myModalLabel").html("Sign Up");
			$("#logSigBut").html("Sign Up");
			$("#loginChanger").html("Sign In");

		} else {

			$("#loginDetect").val("1");
			$("#myModalLabel").html("Sign In");
			$("#logSigBut").html("Sign In");
			$("#loginChanger").html("Sign Up");

		}

})

	$("#logSigBut").click(function() {

        
		$.ajax ({
			type: "POST",
			url : "actions.php?action=SignInUp",
			data: "&email=" + $("#exampleInputEmail1").val() + "&password=" + $("#exampleInputPassword1").val() + "&loginDetect=" + $("#loginDetect").val(),
			success: function(result) {

				  if (result == 1) {
                    
                    window.location.assign("http://localhost:8888/drizzer/");
                    
                } else {
                    
                    $("#loginAlert").html(result).show();
                    
                }
			}
		})		
	})

	$(document).ready(function() {

    $(".toggleFollow").click(function() {
        
        var id = $(this).attr("data-userId");
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=toggleFollow",
            data: "userId=" + id,
            success: function(result) {
                
                if (result == 1) {

        
                    $("a[data-userId='" + id + "']").html("Follow");

                    
                } else if (result == 2) {

                    $("a[data-userId='" + id + "']").html("Unfollow");

                }
            }
            
        })
        
    })

    });
    
    $(document).ready(function() {

    $(".toggleLiked").click(function() {
        
        var id = $(this).attr("data-tweetId");
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=toggleLiked",
            data: "tweetId=" + id,
            success: function(result) {
                
                if (result == 0) {

        
                    $("a[data-tweetId='" + id + "']").html("&nbsp&nbspLike");

                    
                } else if (result == 1) {

                    $("a[data-tweetId='" + id + "']").html("&nbsp&nbspLiked");

                }
            }
            
        })
        
    })

    });

     $("#postTweetButton").click(function() {
         
        $.ajax({
            type: "POST",
            url: "actions.php?action=postTweet",
            data: {tweetContent: $("#tweetContent").val(), lat: $("#lat").val(), lng: $("#lng").val()}, 
            success: function(result) {
               
                if (result == 1) {
                    
                    $("#tweetSuccess").show();
                    $("#tweetFail").hide();
                    
                } else {
                    
                    $("#tweetFail").html(result).show();
                    $("#tweetSuccess").hide();
                    
                }
            }
            
        }) 
    })
    
     $("#postDisplayName").click(function() {
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=postDisplayName",
            data: "displayName=" + $("#displayName").val(),
            success: function(result) {
            }
            
        })
        
    })
    
     $("#postnewPassword").click(function() {
        
        $.ajax({
            type: "POST",
            url: "actions.php?action=postnewPassword",
            data: "newPassword=" + $("#newPassword").val(),
            success: function(result) {
            }
            
        })
        
    })
    
</script>

</body>
</html>