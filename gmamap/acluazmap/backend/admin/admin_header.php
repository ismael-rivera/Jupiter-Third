<?
require_once("../../init.php");
ob_start();
if(!$session->logged_in){
   header('Location: http://localhost22/backend/login.php');
}  
else {	
/**
 * 
 *The CMS Main header 
 *
 * Holds the Session and tokens for the entire backend.
 *
 */
// Connect to the file above here  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Ariz Map Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php Dir::gIncs('/css', '/bootstrap.css', 'echo', '/lib'); ?>" rel="stylesheet">
    <link href="<?php Dir::gIncs('/css', '/admin.css', 'echo'); ?>" rel="stylesheet">
    <style type="text/css">
      
    </style>
    <link href="<?php Dir::gIncs('/css', '/bootstrap-responsive.css', 'echo', '/lib'); ?>" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="../assets/ico/favicon.png">
  <script type="text/javascript" src="<?php echo Dir::gIncs('/js', '/jquery-1.9.0.min.js', 'echo', '/lib'); ?>"></script> 
  </head>
  
  <body>
  <div class="wrap">
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?php echo AZ_HOME_URL; ?>">Back to Arizona Map</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
            <?php
			var_dump($session->checkLogin());
			?>
            <?php if($session->logged_in){ ?>
              Logged in as <a href="<?php echo Dir::gHUrl(); ?>" class="navbar-link"><?php echo $session->username ?></a> | <a href="<?php $session->logout(); ?>" class="navbar-link">Logout</a>
            <?php  } else { echo 'User not logged in';}?>
             </p>
            <ul class="nav">
            <?php if($session->logged_in){ ?>
              <li class="active"><a href="<?php echo Dir::gHUrl() ?>/backend/login.php">Login</a></li>
            <?php  } else { ?>
              <li class="active"><a href="<?php echo Dir::gHUrl() ?>/backend/register.php">Register New User</a></li>
            <?php  } ?>  
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<?php } ?>    

