<?php 

class BootLoader extends Dir {
	
function __construct() {
    
	   
   }	

public static function load_incs($files){
	
if(is_array($files)){
	foreach($files as $file){
    require_once($file);
	}	
} else {
	require_once($file);
	}

function get_frontend_header(){
	if(gHUrl() == HOME_URL){
		include( HOME_URL . '/header.php');
		}
	}
	
	
	}


}

	








