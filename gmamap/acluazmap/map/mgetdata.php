<?php

function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

header("Content-type: text/xml");

// Connect to the init file board here. Parser needs to come first before the header is reached.  
require "../init.php";

// Select all the rows in the markers table
$query = "SELECT * FROM markers WHERE 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'location="' . $row['location'] . '" ';
  echo 'date="' . $row['date'] . '" ';
  echo 'mID="' . $row['mID'] . '" ';
  echo 'name="' . $row['name'] . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'type="' . $row['type'] . '" ';
  echo 'image="' . $row['image'] . '" ';
  echo 'thumb="' . $row['thumb'] . '" ';
  echo 'icon="' . $row['icon'] . '" ';
  echo 'infowindow="' . htmlspecialchars($row['infowindow']) . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>

