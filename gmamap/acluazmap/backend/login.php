<?php
require_once('../init.php');	
function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
 
 
if(!$session->logged_in){
   include('backend_header.php');	
   include('login_form.php');
}
else
{
   header('Location: http://localhost22/backend/admin/dashboard.php');	
	}
/*if($session->isAdmin()){
      echo "[<a href=\"admin/admin.php\">Admin Center</a>] &nbsp;&nbsp;";
   }
   echo "[<a href=\"process.php\">Logout</a>]";
}*/
?>

