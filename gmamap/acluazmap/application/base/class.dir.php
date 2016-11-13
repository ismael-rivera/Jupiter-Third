<?php 

class Dir{
	
function __construct() {
       
   }
  
function incdir_frontback(){
	$string = self::curPageURL();
	 if(substr($string, 0, -1) == AZ_HOME_URL){
		 return '/fe';
		 } else {
		 return '/be';	 
	     }
	}   
   
function incdir_echo_return($e, $output){
if($e == 'echo'){
	echo $output;
	} else {
	return $output;	
		}   
}
	
public static function gHUrl($echo = NULL){
	$home = AZ_HOME_URL;
	self::incdir_echo_return($echo, $home);	
	}
	
public static function gSCUrl(){
	$mapcore = self::filterDomainUrl() . '/sitecore';
	return  $mapcore;
	}

public static function gIncs($type, $file, $callback_type, $lib=NULL){
	if(empty($lib)){
     $dir = AZ_INCS . self::incdir_frontback() . $type . $file;
	  } else {
	 $dir = AZ_INCS . $lib . $type . $file;  
	}
	  return self::incdir_echo_return($callback_type, $dir);
	}		

protected function filterDomainUrl(){
	
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF'];
     
    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index )
    $pathInfo = pathinfo($currentPath);
     
    // output: localhost
    $hostName = $_SERVER['HTTP_HOST'];
     
    // output: http://
    $protocol1 = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5));
	$protocol1!=='http/'?$protocol2 ='https://':$protocol2='http://';
	
     // return: http://localhost
    return $protocol2.$hostName;
	
}

public static function curPageURL() {
 $pageURL = 'http';
 if ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
























}
	
	








