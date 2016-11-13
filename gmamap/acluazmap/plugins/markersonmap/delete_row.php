 <?php  
// Connect to the init file board here. Parser needs to come first before the header is reached.  
require "../../init.php";
?>
<?php 
// your datebase connection and delete logic goes here
//$data= $_POST['data'];
$id= $_POST['id'];
echo $id;

/*if(mysql_query("INSERT INTO test (marker) VALUES ('$name')")){
	
	echo "Successfully Inserted";
	
	} else {
		
		echo "Insertion Failed";
		
		}*/

if(mysql_query("DELETE FROM markers WHERE id='$id'")){
	
	echo "Successfully Deleted";
	
	} else {
		
		echo "Deletion Failed";
		
		}

//mysql_query("DELETE FROM markers WHERE id='data'");

// if delete successful we print this



?>