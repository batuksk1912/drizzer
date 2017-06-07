<?php 

include("functions.php");

include("views/header.php");


 if ($_GET['page'] == 'timeline') {

 	if ($_SESSION['id'] > 0) {
        
        include("views/timeline.php");

    } 
 
    } else if ($_GET['page'] == 'settings') {
     
     if ($_SESSION['id'] > 0) {
        
        include("views/settings.php");

    } 
             
    } else if ($_GET['page'] == 'profile') {

    	if ($_SESSION['id'] > 0) {

    	include("views/profile.php");

    } 

    } else if ($_GET['page'] == 'search') {
        
        include("views/search.php");
        
    } else if ($_GET['page'] == 'accounts') {
        
        include("views/accounts.php");
        
    }

    else {

    	include("views/home.php");

    }

include("views/footer.php");

?>