<?php
// Connect to the init file board here. Parser needs to come first before the header is reached.  
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
/**
 * Template Name: Debug Log
 *
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Ishygrids 1.0
 */ 
/*
$data = array (
'mlatdata' => 23,
'mlngdata' => 62,
'middata'  => 13
);

$iconquery = mysql_query("SELECT mID FROM markers ORDER BY mID DESC LIMIT 0, 1");

$highestmIDquery = mysql_query("SELECT mID FROM markers ORDER BY mID DESC LIMIT 0, 1");
$highestmID = mysql_fetch_row($highestmIDquery);

//echo $highestmID[0] . '<br />';
 

$mIDcolquery = mysql_query("SELECT mID FROM markers");
//$mIDcol = mysql_fetch_array($mIDcolquery);

$mID = array();

while($mIDcol = mysql_fetch_array($mIDcolquery)){
    $mID[] = $mIDcol['mID'];
//Edited - added semicolon at the End of line.1st and 4th(prev) line

}
$mIDs = array_map('intval', $mID);

//echo $highestmID[0] . '<br />';

// given array. 3 and 6 are missing.
$arr1 = $mIDs; 

if(count($arr1) > 0){
// construct a new array:1,2....max(given array).
$arr2 = range(1,max($arr1));                                                    

// use array_diff to get the missing elements 
$missing = array_diff($arr2,$arr1); // (3,6)
}
*/

/*$data = array (

'mlatdata' => $_POST['mlatdata'],
'mlngdata' => $_POST['mlngdata'],
'middata'  => $_POST['middata']

);*/

$data = array(
        'lat' => $_POST['mlatdata'],
        'lng' => $_POST['mlngdata'],
    );

$wpdb->insert('markers', $data);






