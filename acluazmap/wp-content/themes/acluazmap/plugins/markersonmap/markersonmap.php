
<?php 
	$table="markers";
	$columns = "mID AS id, name";
	$query = "SELECT {$columns} FROM {$table} ORDER BY id";
    $result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());
    $fields_num = mysql_num_fields($result);
	
/*function getMarkerID(){	
	$result2 = mysql_query("SELECT {$columns} FROM {$table}");
	while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)){
	echo($row2['id']);	
	}
}*/

	echo "<table align=\"center\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" id=\"delTable\" bgcolor=\"#f6f6f6\" style=\"border:1px solid #cccccc;\"><col align=\"left\"><col align=\"left\"><col align=\"right\"><col align=\"left\"><col align=\"left\"><col align=\"right\">
	<tr>";
	// printing table headers
	
	for($i=0; $i<$fields_num; $i++)
	{
		$field = mysql_fetch_field($result);
		echo "<th style=\"border-bottom:1px solid #cccccc;\">{$field->name}</th>";
	}
	echo "<th align=\"center\" style=\"border-bottom:1px solid #cccccc;\">delete</th></tr>\n";
	// printing table rows
	
	$r=0;
	
	while($row = mysql_fetch_row($result))
	{
	$r++;
	if ($r % 2 == 0 ){	
	echo "<tr class=\"even\" id='". $row[0] ."'>";
	} else {
	echo "<tr class=\"odd\" id='". $row[0] ."'>";
	}
		// $row is array... foreach( .. ) puts every element
		// of $row to $cell variable
	foreach($row as $cell){
		if(is_numeric($cell)){
		   echo "<td>" . intval($cell) . "</td>";
			}else{
		   echo "<td>" . $cell . "</td>";}
		}
		echo "<td class=\"delet\" align=\"center\"><a href=\"#\" class=\"delete\" style=\"color:#FF0000;\"></a></td></tr>\n";
	}
	mysql_free_result($result);
	echo "</table>";
?>
