<?php  
// Connect to the file above here  
require_once("init.php"); 

/*$data = array (
'mlatdata' => 23,
'mlngdata' => 62,
'middata'  => 13
);*/

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<html>
<head>
<title>Interactive Map of The State of Arizona</title>   
<!--<link rel="stylesheet" href="../../dist/leaflet.css" />-->
<!--[if lte IE 8]><link rel="stylesheet" href="../../dist/leaflet.ie.css" /><![endif]-->
<!--<link rel="stylesheet" href="../css/screen.css" />-->
<!--<script src="../leaflet-include.js"></script>-->
<link rel="stylesheet" type="text/css" href="<?php echo AZ_IDFC . '/styles.css' ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo AZ_IDLC . '/jquery-ui-1.10.0.custom.css'; ?>" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&libraries=drawing"></script>
<script type="text/javascript" src="<?php echo AZ_IDLJ . '/markerwithlabel_packed.js' ?>"></script>

<!--PLUGINS-->


<!--Development Extension Files-->

<script type="text/javascript" src="<?php echo AZ_PLUGINS . '/fancybox' . '/source/jquery.fancybox.js?v=2.1.4';?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo AZ_PLUGINS . '/fancybox' . '/source/jquery.fancybox.css?v=2.1.4'; ?>" media="screen" />

	<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo AZ_PLUGINS . '/fancybox' .
'/source/helpers/jquery.fancybox-buttons.css?v=1.0.5' ?>" />
<script type="text/javascript" src="<?php echo AZ_PLUGINS . '/fancybox' . '/source/helpers/jquery.fancybox-buttons.js?v=1.0.5' ?>"></script>
    
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo AZ_PLUGINS . '/fancybox' .'/source/helpers/jquery.fancybox-media.js?v=1.0.5' ?>"></script>


<!--END PLUGINS-->
   

<script type="text/javascript" src="<?php echo AZ_IDFJ . '/infobubble.js'; ?>"></script>
<script type="text/javascript" src="<?php echo AZ_IDFJ . '/interface.js'; ?>"></script>
<script type="text/javascript" src="<?php echo AZ_IDFJ . '/map.js'; ?>"></script>
<!--Production Compiled Files-->
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
		.fancybox-buttons img{
	border: 1px solid #CCC;
	background: #fefefe;
			}
		.fancy-buttons{
	padding: 6px;
	border: 1px solid #CCC;
	display: block;
	height: auto;
	width: auto;
			}	
	</style>

</head>
<body>
    <!--<div>
      <input onclick="clearOverlays();" type=button value="Hide Overlays">
      <input onclick="showOverlays();" type=button value="Show All Overlays">
      <input onclick="deleteOverlays();" type=button value="Delete Overlays">
    </div>-->
    <div id="header_wrapper">
            <a href="<?php echo Dir::gHUrl(); ?>" class="logo"></a>
            <!--<div id="header" class="grid001">
            <h1 id="header_title">INTERACTIVE MAP OF THE STATE OF ARIZONA</h1>
            </div>-->
    </div>
<? include('map.php'); ?>    