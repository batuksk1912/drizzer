<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Drizzer</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="stylesheet" href="http://localhost:8888/drizzer/styles.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="assets/ico/favicon.png">

  </head>
  <body>
  <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Drizzer</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="http://localhost:8888/drizzer/"><b>Drizzer</b></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <?php if($_SESSION['id']) { ?>
        <li><a href="?page=timeline"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a></li>
        <li><a href="?page=profile"><span class="glyphicon glyphicon-user"></span>&nbsp;Profile</a></li>
        <li><a data-toggle="modal" href="#msgModal"><span class="glyphicon glyphicon-envelope"></span>&nbsp;Messages</a></li>
        <?php } ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
       
        <?php if($_SESSION['id']) { ?>

         <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account&nbsp;<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="?page=settings">Settings</a></li>
            <li><a href="?function=logout">Logout</a></li>
          </ul>
        </li>
        <?php } else { ?>
                <div class="navbar-form navbar-right">
        <button class="btn btn-success outline" data-toggle="modal" data-target="#myModal">Sign In</button>
        <?php } ?>
      </div>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>