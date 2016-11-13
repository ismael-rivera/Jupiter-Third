<?php
// Connect to the init file board here. Parser needs to come first before the header is reached.  
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
 
/*
* This code is stricly for testing purposes. 
* It can be deleted if necessary. I keep it because it's good to have.
*/


$data = array (

'mlatdata' => $_POST['mlatdata'],
'mlngdata' => $_POST['mlngdata'],
'updtmlatdata' => $_POST['updtmlatdata'],
'updtmlngdata' => $_POST['updtmlngdata']
);


mSaveData($data);





?>

