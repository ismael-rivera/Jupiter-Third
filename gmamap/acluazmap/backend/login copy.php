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

/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
if($session->logged_in){
   //header('Location:' . $dir->gHUrl() . '/cms/admin/dashboard.php');		
   /*echo "<h1>Logged In</h1>";
   echo "Welcome <b>$session->username</b>, you are logged in. <br><br>"
       ."[<a href=\"userinfo.php?user=$session->username\">My Account</a>] &nbsp;&nbsp;"
       ."[<a href=\"useredit.php\">Edit Account</a>] &nbsp;&nbsp;";*/
if($session->isAdmin()){
      echo "[<a href=\"admin/admin.php\">Admin Center</a>] &nbsp;&nbsp;";
   }
   echo "[<a href=\"process.php\">Logout</a>]";
}
else{
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in &middot; Twitter Bootstrap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
	max-width: 300px;
	padding: 19px 29px 29px;
	margin: 0 auto 20px;
	background-color: #fff;
	border: 1px solid #e5e5e5;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	box-shadow: 0 1px 2px rgba(0,0,0,.05);
	overflow: auto;
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
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
  </head>

  <body>

<table>
<tr><td>



<?
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
}
?>


<div class="container">

      <form action="process.php" id="login_form" class="form-signin" method="POST">
        <h2 class="form-signin-heading">Please sign in</h2>
<table align="left" border="0" cellspacing="0" cellpadding="3">        
        <tr>
            <td><input type="text" name="user" maxlength="30" class="input-block-level" placeholder="Username" value="<? echo $form->value("user"); ?>"></td>
            <td><? echo $form->error("user"); ?></td>
        </tr>
        <tr>
            <td><input type="password" name="pass" class="input-block-level" maxlength="30" value="<? echo $form->value("pass"); ?>" placeholder="Password"></td>
            <td><? echo $form->error("pass"); ?></td>
        </tr>
        <tr>
        <td colspan="2" align="left">
        <label class="checkbox">
          <input type="checkbox" value="remember-me" name="remember" <? if($form->value("remember") != ""){ echo "checked"; } ?>>Remember me
        </label>
        <input type="hidden" name="sublogin" value="1">
        <input class="btn btn-large btn-primary" type="submit" value="Sign in">
        </td>
        </tr>
        <tr>
        <td colspan="2" align="left">
        <p><font size="2">[<a href="forgotpass.php">Forgot Password?</a>]</font></p>
        </td>
        </tr> 
        <tr><td colspan="2" align="left"><br>Not registered? <a href="register.php">Sign-Up!</a>
        </td></tr>
        </table>
      </form>

    </div> <!-- /container -->
<?
}

/**
 * Just a little page footer, tells how many registered members
 * there are, how many users currently logged in and viewing site,
 * and how many guests viewing site. Active users are displayed,
 * with link to their user information.
 */
/*echo "</td></tr><tr><td align=\"center\"><br><br>";
echo "<b>Member Total:</b> ".$database->getNumMembers()."<br>";
echo "There are $database->num_active_users registered members and ";
echo "$database->num_active_guests guests viewing the site.<br><br>";

include("include/view_active.php");*/

?>


</td></tr>
</table>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/jquery-1.9.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-button.js"></script>

</body>
</html>
