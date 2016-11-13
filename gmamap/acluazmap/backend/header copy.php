<?
/**
 * login.php
 *
 * This is an example of the login page of a website. Here
 * users will be able to login. However, like on most sites
 * the login form doesn't just have to be on the login page,
 * but re-appear on subsequent pages, depending on whether
 * the user has logged in or not.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 26, 2004
 */
require_once("../config/class.dir.php");
$dir=new Dir; 

include("include/session.php");
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
    <link href="../css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

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
  <script type="text/javascript" src="<?php echo $dir->gHUrl() ?>/js/jquery-1.9.0.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="<?php echo $dir->gHUrl() ?>/cms/css/flexigrid.css" media="all" />
  <script type="text/javascript" src="<?php echo $dir->gHUrl() ?>/cms/js/flexigrid.pack.js"></script>   <script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#flexme1').flexigrid();
		});
		</script>                               
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="<?php echo $dir->gHUrl() ?>">Back to Arizona Map</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="<?php echo $dir->gHUrl() ?>" class="navbar-link"><?php echo $session->username ?></a> | <a href="<?php echo $dir->gHUrl() ?>" class="navbar-link">Logout</a>
            </p>
            <ul class="nav">
              <li class="active"><a href="<?php echo $dir->gHUrl() ?>">Dashboard Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>