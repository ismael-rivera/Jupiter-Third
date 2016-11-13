<?php 
error_reporting(0);

$CONFIG["hostname"]='localhost';
$CONFIG["mysql_user"]='root';
$CONFIG["mysql_password"]='5es6rx6no8322zs';
$CONFIG["mysql_database"]='scr_habanine_db';
$CONFIG["server_path"]='/Users/ismaelrivera/Sites/demos.speggo/hambanine/';
$CONFIG["full_url"]='http://localhost19/';
$CONFIG["folder_name"]='/';
$CONFIG["admin_user"]='coralblue79';
$CONFIG["admin_pass"]='speggodesign';


//////////////////////////////////////////
////////// DO NOT CHANGE BELOW ///////////
//////////////////////////////////////////

$CONFIG["upload_folder"]='upload/';
$CONFIG["upload_thumbs"]='upload/thumbs/';

$TABLE["Obituaries"]= 'funeral_obituaries';
$TABLE["Comments"] 	= 'funeral_comments';
$TABLE["Editors"] 	= 'funeral_editors';
$TABLE["Options"] 	= 'funeral_options';

if ($installed != 'yes') {
	$conn = mysql_connect($CONFIG["hostname"], $CONFIG["mysql_user"], $CONFIG["mysql_password"]) or die ('Unable to connect to MySQL server.'.mysql_error());
	mysql_query('set names utf8', $conn);
	$db = mysql_select_db($CONFIG["mysql_database"], $conn) or die ('Unable to select database.'.mysql_error());
}

require_once('include/functions.php');
require_once('recaptchalib.php');

$configs_are_set = 1;
?>