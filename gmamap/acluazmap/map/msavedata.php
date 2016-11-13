<?php
// Connect to the init file board here. Parser needs to come first before the header is reached.  
require "../init.php";
 
/*
* This code is stricly for testing purposes. 
* It can be deleted if necessary. I keep it because it's good to have.
$mlatdata = $_POST['mlatdata'];
$mlngdata = $_POST['mlngdata'];
$middata = $_POST['middata'];
*/

$data = array (

'mlatdata' => $_POST['mlatdata'],
'mlngdata' => $_POST['mlngdata'],
'middata'  => $_POST['middata']

);

$highestmIDquery = mysql_query("SELECT mID FROM markers ORDER BY mID DESC LIMIT 0, 1");
$highestmID = mysql_fetch_row($highestmIDquery);
$mIDcolquery = mysql_query("SELECT mID FROM markers");

$mID = array();

while($mIDcol = mysql_fetch_array($mIDcolquery)){
    $mID[] = $mIDcol['mID'];
//Edited - added semicolon at the End of line.1st and 4th(prev) line

}

$mIDs = array_map('intval', $mID);

//echo $highestmID[0] . '<br />';

// given array. 3 and 6 are missing.
$arr1 = $mIDs; 

// construct a new array:1,2....max(given array).
$arr2 = range(1,max($arr1));                                                    

// use array_diff to get the missing elements 
$missing = array_diff($arr2,$arr1); // (3,6)

//$i<$highestmID[0];
			
/*Note to self: $checkmID is not a boolean. It only goes searching for the data that is equal to $data['middata']. If $checkmID fails to select a number, then $data['middata'] does not exist in the column.*/

$checkmIDexists = mysql_query("SELECT mID FROM markers WHERE mID = '" . $data['middata'] . "'");
$checkmID = mysql_fetch_row($checkmIDexists);

if ($checkmID[0] == $data['middata']){
	if($missing){
		$data['middata'] = reset($missing);
	}else{
		$data['middata'] = $highestmID[0] + 1;
		}
	} 
else if ($highestmID[0] < $data['middata']){
	    $data['middata'] = $highestmID[0] + 1;
		}

$insertion = mysql_query("INSERT INTO markers (mID, lng, lat ) VALUES (" . $data['middata'] . ", " . $data['mlngdata'] . ", " . $data['mlatdata'] . " )");

 //else if ($checkmID == $data['middata']){
	    //for($i=$data['middata'] + 1; $i<$highestmID[0]; $i++){
			/*if($checkmID == false){
				break;
				}*/
		//return $data['middata'] + 1;			
		//}
 
/*$insertion = mysql_query("INSERT INTO markers (lat, lng, mID) VALUES (" . $data['mlatdata'] . ", " . $data['mlngdata'] . ", " . $i . " )");	*/	
		
  //}















/*$deletedata= $_POST['data'];

var_dump($data);*/



?>

