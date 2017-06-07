var spinner =   '<svg class="spinner" width="32px" height="32px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">' +
    '<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>' +
    '</svg>';

window.onload = function() {
    var options =
    {
        imageBox: '.imageBox',
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    }
    var cropper;
    document.querySelector('#file').addEventListener('change', function(){
        $(".doAvatarEdit").removeClass("disabled");
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = new cropbox(options);
        }
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    })
    /*document.querySelector('#btnCrop').addEventListener('click', function(){
        var img = cropper.getDataURL()
        //document.querySelector('.cropped').innerHTML = '<img src="'+img+'">';
        document.querySelector('.newPic').value = img;
        $(".doAvatarEdit").removeClass("disabled");
    })*/
        document.querySelector('.doHeaderEdit').addEventListener('click', function(){
        $(this).addClass("disabled").html(spinner);
        var img = cropper.getDataURL()
        document.querySelector('.newPic').value = img;
        $.ajax({
            method:"POST",
            cache:false,
            url:"/drizzer/actions.php?action=uploadHeaderImage",
            beforeSend:function(){
            },
            data:{ img : $("#newPic").val() },
            success:function(result){
                result = JSON.parse(result);
                if(result.error == 'ok'){
                    window.location.reload();
                }else{
                    alert('error');
                }
            }
        });
    })/**/
    document.querySelector('#btnZoomIn').addEventListener('click', function(){
        cropper.zoomIn();
    })
    document.querySelector('#btnZoomOut').addEventListener('click', function(){
        cropper.zoomOut();
    })
};
