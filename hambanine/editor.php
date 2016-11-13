<?php
error_reporting(0);
session_start();
include("config.php");
include('fckeditor/fckeditor.php');

if(isset($_REQUEST["act"])) {
  if ($_REQUEST["act"]=='logout') {
			$_SESSION["ProFiAnTsEdiTorFuNeRalLOgiN"] = "";
			unset($_SESSION["ProFiAnTsEdiTorFuNeRalLOgiN"]);
			$_SESSION["EditorId"] = "";
			unset($_SESSION["EditorId"]);
 } elseif ($_REQUEST["act"]=='login') {
 	$sql = "SELECT * FROM ".$TABLE["Editors"]." WHERE editor_username='".mysql_escape_string($_REQUEST["user"])."' AND editor_password='".mysql_escape_string($_REQUEST["pass"])."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
  	if (mysql_num_rows($sql_result)==1) {
		$EditorId = mysql_fetch_assoc($sql_result);
		$_SESSION["ProFiAnTsEdiTorFuNeRalLOgiN"] = "LoggedIn";	
		$_SESSION["EditorId"] = $EditorId['id'];		
 		$_REQUEST["act"]='obituaries';
  	} else {
		$message = 'Incorrect login details.';
  	}
  }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Funeral Script PHP Editor</title>

<script language="javascript" src="include/functions.js"></script>
<script language="javascript" src="include/color_pick.js"></script>
<script type="text/javascript" src="include/datetimepicker_css.js"></script>
<link href="styles/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center>
<div class="logo">Funeral Script PHP Editor</div>
<div style="clear:both"></div>

<?php  
$Logged = false;
if ((isset($_SESSION["ProFiAnTsEdiTorFuNeRalLOgiN"])) and ($_SESSION["ProFiAnTsEdiTorFuNeRalLOgiN"]=="LoggedIn")) {
	$Logged = true;
	$EditorId = $_SESSION['EditorId'];
}
if ( $Logged ){

if ($_REQUEST["act"] == "addObituary"){
	
	if (!isset($_REQUEST["allow_comments"]) or $_REQUEST["allow_comments"]=='') $_REQUEST["allow_comments"] = 'false';
	
	$sql = "INSERT INTO ".$TABLE["Obituaries"]." 
			SET publish_date = '".SaveDB($_REQUEST["publish_date"])."',
				status = '".SaveDB($_REQUEST["status"])."',	
				editor_id = '".$EditorId."', 
				title = '".SaveDB($_REQUEST["title"])."',
				summary = '".SaveDB($_REQUEST["summary"])."',
				content = '".SaveDB($_REQUEST["content"])."',
				imgpos = '".SaveDB($_REQUEST["imgpos"])."',
				imgwidth = '".SaveDB($_REQUEST["imgwidth"])."',
				allow_comments = '".SaveDB($_REQUEST["allow_comments"])."',  
				reviews = '0'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());	
	
	$index_id = mysql_insert_id();
	
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual']);
	
	if (is_uploaded_file($_FILES["image"]['tmp_name'])) {
		
		$format = end(explode(".", $_FILES["image"]['name']));
			
		if($format=="jpg" or $format=="jpeg" or $format=="JPG") {
		
			$uploaddir = $CONFIG["upload_folder"];
			$name = $_FILES['image']['name'];
			$name = ereg_replace(" ", "_", $name); 
			$name = ereg_replace("%20", "_", $name);
			$name = $index_id . "_" . $name;

			
			$filePath = $uploaddir . $name;
			$thumbPath = $CONFIG["upload_thumbs"] . $name;
			chmod($filePath, 0777);
			
			if (move_uploaded_file($_FILES["image"]['tmp_name'], $filePath)) {
				ResizeImage(320, 320, $filePath); 
				createthumb($filePath,$thumbPath,$OptionsVis["summ_img_width"],$OptionsVis["summ_img_width"]);

				$sql = "UPDATE ".$TABLE["Obituaries"]."  
						SET image = '".$name."'  
						WHERE id='".$index_id."'";
				$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());	
				$message = '';
			} else {
				$message = 'Cannot copy uploaded file to '.$filePath.'. Try to set the right permissions (CHMOD 777) to '.$CONFIG["upload_folder"];  
			}
		} else {
			$message = 'Image file must be JPG format. ';   
		}
	} else { $message = 'Image file is not uploaded. '; }
		
	$_REQUEST["act"] = "obituaries";		
	$message .= 'Obituary created';

} elseif ($_REQUEST["act"]=='updateObituary') {
	
	if (!isset($_REQUEST["allow_comments"]) or $_REQUEST["allow_comments"]=='') $_REQUEST["allow_comments"] = 'false';

	$sql = "UPDATE ".$TABLE["Obituaries"]." 
			SET publish_date = '".SaveDB($_REQUEST["publish_date"])."',
				status = '".SaveDB($_REQUEST["status"])."',	
				editor_id = '".$EditorId."', 
                title = '".SaveDB($_REQUEST["title"])."',
				summary = '".SaveDB($_REQUEST["summary"])."',
				content = '".SaveDB($_REQUEST["content"])."',
				imgpos = '".SaveDB($_REQUEST["imgpos"])."', 
				imgwidth = '".SaveDB($_REQUEST["imgwidth"])."',
				allow_comments 	= '".SaveDB($_REQUEST["allow_comments"])."'  
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual']);	
	
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id = '".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$imageArr = mysql_fetch_assoc($sql_result);
	$image = stripslashes($imageArr["image"]);
	
	$index_id = $_REQUEST["id"];
		
	if (is_uploaded_file($_FILES["image"]['tmp_name'])) { 
	
		$format = end(explode(".", $_FILES["image"]['name']));
			
		if($format=="jpg" or $format=="jpeg" or $format=="JPG") {
		
			if($image != "") unlink($CONFIG["upload_folder"].$image);
			if($image != "") unlink($CONFIG["upload_thumbs"].$image);
			
			$name = $_FILES['image']['name'];
			$name = ereg_replace(" ", "_", $name); 
			$name = ereg_replace("%20", "_", $name);
			
			$filename = $CONFIG["upload_folder"] . $index_id . "_" . $name;
			$thumbPath = $CONFIG["upload_thumbs"] . $index_id . "_" . $name;
			
			if (move_uploaded_file($_FILES["image"]['tmp_name'], $filename)) {
				ResizeImage(320, 320, $filename); 
				createthumb($filename,$thumbPath,$OptionsVis["summ_img_width"],$OptionsVis["summ_img_width"]);
				chmod($filename,0777); 
				$sql = "UPDATE `".$TABLE["Obituaries"]."` 
						SET `image` = '".mysql_escape_string($index_id . "_" . $name) ."' 
						WHERE id = '".$index_id."'";
				$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
			} else {
				$message = 'Cannot copy uploaded file to '.$filePath.'. Try to set the right permissions (CHMOD 777) to '.$CONFIG["upload_folder"];  
			}
		} else {
			$message = 'Image file must be JPG format.';   
		}
	}
	
	if($_REQUEST["updatepreview"]=='Update and Preview') {
		$_REQUEST["act"]='viewObituary'; 		
	} else {
		$_REQUEST["act"]='obituaries'; 
	}
	$message .= 'Obituary updated.';
	
} elseif ($_REQUEST["act"]=='delObituary') {
	
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id = '".$_REQUEST["id"]."' AND editor_id = '".$EditorId."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
	$imageArr = mysql_fetch_assoc($sql_result);
	$image = stripslashes($imageArr["image"]);
	if($image != "") unlink($CONFIG["upload_folder"].$image);
	if($image != "") unlink($CONFIG["upload_thumbs"].$image);
	
	$sql = "DELETE FROM ".$TABLE["Comments"]." WHERE obit_id='".$_REQUEST["id"]."'";
   	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());

	$sql = "DELETE FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."' AND editor_id = '".$EditorId."'";
   	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
 	$_REQUEST["act"]='obituaries'; 
	$message = 'Obituary deleted.';
	
} elseif ($_REQUEST["act"]=="delImage") { 
	
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id = '".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
	$imageArr = mysql_fetch_assoc($sql_result);
	$image = stripslashes($imageArr["image"]);
	if($image != "") unlink($CONFIG["upload_folder"].$image);
	if($image != "") unlink($CONFIG["upload_thumbs"].$image);
	
	$sql = "UPDATE `".$TABLE["Obituaries"]."` SET `image` = '' WHERE id = '".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
	
	$message = "Image deleted.";
	$_REQUEST["act"] = "editObituary";
	
} elseif ($_REQUEST["act2"]=="change_status") { 
	
	$sql = "UPDATE ".$TABLE["Obituaries"]." 
			SET status = '".SaveDB($_REQUEST["status"])."' 
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	$message = "Status Updated.";
	$_REQUEST["act"] = "obituaries";

	
} elseif ($_REQUEST["act2"]=="change_status_comm") { 
	
	$sql = "UPDATE ".$TABLE["Comments"]." 
			SET status = '".SaveDB($_REQUEST["status"])."' 
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	$message = "Status Updated.";
	$_REQUEST["act"] = "comments";

} elseif ($_REQUEST["act"]=='updateComment') {

	$sql = "UPDATE ".$TABLE["Comments"]." 
			SET status		='".$_REQUEST["status"]."', 
				name	='".SaveDB($_REQUEST["name"])."', 
				email	='".SaveDB($_REQUEST["email"])."', 
				comment	='".SaveDB($_REQUEST["comment"])."' 
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql);
	$_REQUEST["act"]='comments'; 
	$message = 'Comment saved.';

}
if ($_REQUEST["act"]=='' or !isset($_REQUEST["act"])) $_REQUEST["act"]='obituaries';

$sqlEd = "SELECT * FROM ".$TABLE["Editors"]." WHERE id='".$EditorId."'";
$sql_resultEd = mysql_query ($sqlEd, $conn ) or die ('MySQL query error: '.$sqlEd.'. Error: '.mysql_error());
$Editor = mysql_fetch_assoc($sql_resultEd);	
?> 

	<div class="blue_line"></div>
    
	<div class="divMenu">	
      <div class="menuButtons">
   	  	<div class="menuButton"><a<?php if($_REQUEST['act']=='obituaries' or $_REQUEST['act']=='newObituary' or $_REQUEST['act']=='viewObituary' or $_REQUEST['act']=='editObituary' or $_REQUEST['act']=='rss') echo ' class="selected"'; ?> href="editor.php?act=obituaries">Obituaries</a></div>
        <div class="menuButton"><a<?php if($_REQUEST['act']=='comments' or $_REQUEST['act']=='editComment') echo ' class="selected"'; ?> href="editor.php?act=comments">Guest Book</a></div>
        <div class="menuButton" style="font-family:Georgia, 'Times New Roman', Times, serif; font-weight:bold; padding:16px; font-size:18px">Welcome <?php echo ReadHTML($Editor['editor_name']); ?></div>
        <div class="menuButtonLogout"><a href="editor.php?act=logout">Logout</a></div>
        <div class="clear"></div>        
      </div>
	</div>
	
    <div class="blue_line"></div>


<?php
if ($_REQUEST["act"]=='obituaries' or $_REQUEST["act"]=='newObituary' or $_REQUEST["act"]=='editObituary' or $_REQUEST["act"]=='viewObituary' or $_REQUEST["act"]=='rss') {
?>
<div class="divSubMenu">	
    <div class="menuSubButtons">
   	  <div class="menuSubButton"><a<?php if($_REQUEST['act']=='obituaries') echo ' class="selected"'; ?> href="editor.php?act=obituaries">Obituary Entry List</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='newObituary') echo ' class="selected"'; ?> href="editor.php?act=newObituary">New Obituary Entry</a></div>
      <div class="menuSubButton"><a href="preview.php" target="_blank">Obituaries Preview</a></div>
      <div class="clear"></div>        
    </div>
</div>
<?php
}
?>

<div class="wrap_body">

	<?php if(isset($message)) {?>
    <div class="message"><?php echo $message; ?></div>
    <?php } ?>
    

<?php 
if ($_REQUEST["act"]=='obituaries') {
	if(isset($_REQUEST["p"])) $pageNum = $_REQUEST["p"]; else $pageNum = 1;
	if(isset($_REQUEST["orderBy"])) $orderBy = $_REQUEST["orderBy"];
    else $orderBy = "publish_date";
    if(isset($_REQUEST["orderType"])) $orderType = $_REQUEST["orderType"];
    else $orderType = "DESC";
	if ($orderType == 'DESC') { $norderType = 'ASC'; } else { $norderType = 'DESC'; }
	
	$sqlPublished   = "SELECT id FROM ".$TABLE["Obituaries"]." WHERE status='Published' AND editor_id=".$EditorId;
	$sql_resultPublished = mysql_query ($sqlPublished, $conn ) or die ('MySQL query error: '.$sqlPublished.'. Error: '.mysql_error());
	$ObituariesPublished = mysql_num_rows($sql_resultPublished);
	
	$sqlCount   = "SELECT id FROM ".$TABLE["Obituaries"]." WHERE editor_id=".$EditorId;
	$sql_resultCount = mysql_query ($sqlCount, $conn ) or die ('MySQL query error: '.$sqlCount.'. Error: '.mysql_error());
	$ObituariesCount = mysql_num_rows($sql_resultCount);
?>
	<div class="pageDescr">Below is a list of the obituaries and you can edit and delete them. You have <strong style="font-size:16px"><?php echo $ObituariesPublished; ?></strong> obituaries published. Total number of obituaries - <strong style="font-size:16px"><?php echo $ObituariesCount; ?></strong>.</div>
    
    <div class="searchForm">
    <form action="editor.php?act=obituaries" method="post" name="form" class="formStyle">
      <input type="text" name="search" onfocus="searchdescr(this.value);" value="<?php if(isset($_REQUEST["search"])) echo $_REQUEST["search"]; else echo 'enter part of the name'; ?>" class="searchfield" />
      <input type="submit" value="Search for Obituaries" class="submitButton" />
    </form>
    </div>
    
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
  	  <tr>
        <td class="headlist"><a href="editor.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=title">Name of Deceased</a></td>
        <td width="16%" class="headlist"><a href="editor.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=publish_date">Date of Death</a></td>
        <td width="11%" class="headlist"><a href="editor.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=status">Status</a></td>
        <td width="10%" class="headlist">Guest Book</td>
        <td width="8%" class="headlist"><a href="editor.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=reviews">Views</a></td>
        <td class="headlist" colspan="3">&nbsp;</td>
  	  </tr>
      
  	<?php 
	if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
	  $findMe = mysql_escape_string($_REQUEST["search"]);
	  $search = " AND title LIKE '%".$findMe."%'";
	} else {
	  $search = '';
	}

	$sql   = "SELECT count(*) as total FROM ".$TABLE["Obituaries"]." WHERE editor_id=".$EditorId." ".$search;
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$row   = mysql_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/20);

	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE editor_id=".$EditorId." ".$search." 
			ORDER BY " . $orderBy . " " . $orderType."  
			LIMIT " . ($pageNum-1)*20 . ",20";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	if (mysql_num_rows($sql_result)>0) {
		$i=1;	
		while ($Obituary = mysql_fetch_assoc($sql_result)) {	
			$sqlC   = "SELECT count(*) as total FROM ".$TABLE["Comments"]." WHERE obit_id='".$Obituary["id"]."'";
			$sql_resultC = mysql_query ($sqlC, $conn ) or die ('MySQL query error: '.$sqlC);
			$countComm = mysql_fetch_array($sql_resultC);		
	?>
  	  <tr>
        <td class="bodylist"><?php echo ReadHTML($Obituary["title"]); ?></td>
        <td class="bodylist"><?php echo admin_date($Obituary["publish_date"]); ?></td>
        <td class="bodylist">
        	<form action="editor.php?act=obituaries" method="post" name="form<?php echo $i; ?>" class="formStyle">
            <input type="hidden" name="act2" value="change_status" />
            <input type="hidden" name="id" value="<?php echo $Obituary["id"]; ?>" />
            <select name="status" onChange="document.form<?php echo $i; ?>.submit()">
				<option value="Published" <?php if($Obituary['status']=='Published') echo "selected='selected'"; ?>>Published</option>
				<option value="Hidden" <?php if($Obituary['status']=='Hidden') echo "selected='selected'"; ?>>Hidden</option>
            </select>
            </form>
        </td>        
        <td class="bodylist"><a style="text-decoration:none" href="editor.php?act=comments&obit_id=<?php echo $Obituary["id"]; ?>"><?php echo $countComm["total"]; ?></a> <?php if($Obituary["allow_comments"]=='false') echo "<sub>(not allowed)</sub>"; ?></td>
        <td class="bodylist"><?php if($Obituary["reviews"]=='') echo "0"; else echo $Obituary["reviews"]; ?></td>
        <td class="bodylistAct"><a class="view" href='editor.php?act=viewObituary&id=<?php echo $Obituary["id"]; ?>'>Preview</a></td>
        <td class="bodylistAct"><a href='editor.php?act=editObituary&id=<?php echo $Obituary["id"]; ?>'>Edit</a></td>
        <td class="bodylistAct"><a class="delete" href="editor.php?act=delObituary&id=<?php echo $Obituary["id"]; ?>" onclick="return confirm('Are you sure you want to delete it?');">DELETE</a></td>
  	  </tr>
  	<?php 
			$i++;
		}
	} else {
	?>
      <tr>
      	<td colspan="9" style="border-bottom:1px solid #CCCCCC">No obituaries to list!</td>
      </tr>
    <?php	
	}
	?>
    
	<?php
    if ($pages>0) {
    ?>
  	  <tr>
      	<td colspan="9" class="bottomlist"><div class='paging'>Page: </div>
		<?php
        for($i=1;$i<=$pages;$i++){ 
            if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
            else echo "<a href='editor.php?act=obituaries&p=".$i."&search=".$_REQUEST["search"]."' class='paging'>".$i."</a>"; 
            echo "&nbsp; ";
        }
        ?>
      	</td>
      </tr>
	<?php
    }
    ?>
	</table>

<?php 
} elseif ($_REQUEST["act"]=='newObituary') { 
?>
	<form action="editor.php" method="post" name="form" enctype="multipart/form-data">
  	<input type="hidden" name="act" value="addObituary" />
  	<div class="pageDescr">To create obituaries please fill all the fields below and click on 'Create Obituary' button.</div>
	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
      <tr>
      	<td colspan="2" valign="top" class="headlist">Create Obituary</td>
      </tr>
      <tr>
      	<td class="formLeft">Status:</td>
      	<td>
            <select name="status">
              <option value="Published">Published</option>
              <option value="Hidden">Hidden</option>
            </select>
      	</td>
      </tr>     
      <tr>
        <td class="formLeft">Name of Deceased:</td>
        <td><input type="text" name="title" size="100" maxlength="250" /></td>
      </tr>
      
      <tr>
        <td class="formLeft" valign="top">Summary:</td>
        <td><textarea name="summary" cols="80" rows="3"></textarea></td>
      </tr>
      
      <tr>
        <td class="formLeft" valign="top">Content:</td>
        <td>
        	<?php
			$bFCKeditor = new FCKeditor('content') ;
			$bFCKeditor->BasePath = 'fckeditor/' ;
			$bFCKeditor->Height   = 400;
			$bFCKeditor->Width   = 700;
			$bFCKeditor->Value = '' ;
			$bFCKeditor->Create() ;
			?>
        </td>
      </tr>
      
      <tr>
        <td class="formLeft">Photo:</td>
        <td><input type="file" name="image" size="80" /> <sub>*.jpg format only </sub></td>
      </tr> 
      
      <tr>
        <td class="formLeft">Image location in the text:</td>
        <td>
        	<select name="imgpos">
            	<option value="left">left</option>
                <option value="right">right</option>
                <option value="top">top</option>
                <option value="bottom">bottom</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="formLeft">Image width:</td>
        <td>
        	<select name="imgwidth">
            	<option value="320px">320px</option>
                <option value="300px">300px</option>
                <option value="280px">280px</option>
                <option value="260px">260px</option>
                <option value="240px">240px</option>
                <option value="220px">220px</option>
                <option value="200px">200px</option>
                <option value="180px">180px</option>
                <option value="160px">160px</option>
                <option value="140px">140px</option>
                <option value="120px">120px</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="formLeft">Date of Death:</td>
        <td>
      		<input type="text" name="publish_date" id="publish_date" maxlength="25" size="25" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly="readonly" /> <a href="javascript:NewCssCal('publish_date','yyyymmdd','dropdown',true,24,false)"><img src="images/cal.gif" width="16" height="16" alt="Pick a date" border="0" /></a>
        </td>
      </tr>
      <tr>
      	<td class="formLeft">Allow comments on the Guest Book:</td>
      	<td><input name="allow_comments" type="checkbox" value="true" checked="checked" /></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" value="Create Obituary" class="submitButton" /></td>
      </tr>
  	</table>
	</form>
    

<?php 
} elseif ($_REQUEST["act"]=='editObituary') {
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."' AND editor_id=".$EditorId;
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$IsEditor = mysql_num_rows($sql_result);
	$Obituary = mysql_fetch_assoc($sql_result);
	
	$sqlC   = "SELECT count(*) FROM ".$TABLE["Comments"]." WHERE obit_id='".$Obituary["id"]."'";
	$sql_resultC = mysql_query ($sqlC, $conn ) or die ('MySQL query error: '.$sqlC.'. Error: '.mysql_error());
	$count = mysql_fetch_array($sql_resultC);
	
	if($IsEditor==1) {
?>
	<form action="editor.php" method="post" name="form" enctype="multipart/form-data">
  	<input type="hidden" name="act" value="updateObituary" />
  	<input type="hidden" name="id" value="<?php echo $Obituary["id"]; ?>" />
  	<div class="pageDescr">To edit obituary change details below and click on 'Update Obituary' button.</div>
	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
      <tr>
      	<td colspan="2" valign="top" class="headlist">Edit Obituary</td>
      </tr>
      <tr>
      	<td class="formLeft">Status:</td>
      	<td>
        <select name="status">
          <option value="Published"<?php if ($Obituary["status"]=='Published') echo ' selected="selected"'; ?>>Published</option>
          <option value="Hidden"<?php if ($Obituary["status"]=='Hidden') echo ' selected="selected"'; ?>>Hidden</option>
        </select>
      	</td>
      </tr>      
      <tr>
        <td class="formLeft">Name of Deceased:</td>
        <td><input type="text" name="title" size="100" maxlength="250" value="<?php echo ReadHTML($Obituary["title"]); ?>" /></td>
      </tr>
      
      <tr>
        <td class="formLeft" valign="top">Summary:</td>
        <td><textarea name="summary" cols="80" rows="4"><?php echo ReadHTML($Obituary["summary"]); ?></textarea></td>
      </tr>
      
      <tr>
        <td class="formLeft" valign="top">Content:</td>
        <td>
        	<?php
			$bFCKeditor = new FCKeditor('content') ;
			$bFCKeditor->BasePath = 'fckeditor/' ;
			$bFCKeditor->Height   = 400;
			$bFCKeditor->Width   = 700;
			$bFCKeditor->Value = ReadDB($Obituary["content"]);
			$bFCKeditor->Create() ;
			?>        
        </td>
      </tr>
      
      <tr>
        <td class="formLeft">Image:</td>
        <td>
        <?php if(stripslashes($Obituary["image"]) != "") { ?>
			<img src="<?php echo $CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" border="0" width="160" /> 			&nbsp;&nbsp;<a href="<?php $_SERVER["PHP_SELF"]; ?>?act=delImage&id=<?php echo $Obituary["id"]; ?>">delete</a><br /> 
            If you upload new image the old one will be deleted <br />
            <?php } ?>
          	<input type="file" name="image" size="70" /> <sub>*.jpg format only </sub>
        </td>
      </tr> 
      
      <tr>
        <td class="formLeft">Image location in the text:</td>
        <td>
        	<select name="imgpos">
            	<option value="left"<?php if($Obituary["imgpos"]=='left') echo ' selected="selected"' ?>>left</option>
                <option value="right"<?php if($Obituary["imgpos"]=='right') echo ' selected="selected"' ?>>right</option>
                <option value="top"<?php if($Obituary["imgpos"]=='top') echo ' selected="selected"' ?>>top</option>
                <option value="bottom"<?php if($Obituary["imgpos"]=='bottom') echo ' selected="selected"' ?>>bottom</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="formLeft">Image width:</td>
        <td>
        	<select name="imgwidth">
            	<option value="320px"<?php if($Obituary["imgwidth"]=='320px') echo ' selected="selected"' ?>>320px</option>
                <option value="300px"<?php if($Obituary["imgwidth"]=='300px') echo ' selected="selected"' ?>>300px</option>
                <option value="280px"<?php if($Obituary["imgwidth"]=='280px') echo ' selected="selected"' ?>>280px</option>
                <option value="260px"<?php if($Obituary["imgwidth"]=='260px') echo ' selected="selected"' ?>>260px</option>
                <option value="240px"<?php if($Obituary["imgwidth"]=='240px') echo ' selected="selected"' ?>>240px</option>
                <option value="220px"<?php if($Obituary["imgwidth"]=='220px') echo ' selected="selected"' ?>>220px</option>
                <option value="200px"<?php if($Obituary["imgwidth"]=='200px') echo ' selected="selected"' ?>>200px</option>
                <option value="180px"<?php if($Obituary["imgwidth"]=='180px') echo ' selected="selected"' ?>>180px</option>
                <option value="160px"<?php if($Obituary["imgwidth"]=='160px') echo ' selected="selected"' ?>>160px</option>
                <option value="140px"<?php if($Obituary["imgwidth"]=='140px') echo ' selected="selected"' ?>>140px</option>
                <option value="120px"<?php if($Obituary["imgwidth"]=='120px') echo ' selected="selected"' ?>>120px</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="formLeft">Date of Death:</td>
        <td>
      		<input type="text" name="publish_date" id="publish_date" maxlength="25" size="25" value="<?php echo $Obituary["publish_date"]; ?>" readonly="readonly" /> <a href="javascript:NewCssCal('publish_date','yyyymmdd','dropdown',true,24,false)"><img src="images/cal.gif" width="16" height="16" alt="Pick a date" border="0" ></a>
        </td>
      </tr>
      <tr>
      	<td class="formLeft">Allow comments on the Guest Book:</td>
      	<td><input name="allow_comments" type="checkbox" value="true"<?php if($Obituary["allow_comments"]=='true') echo ' checked="checked"'; ?> /></td>
      </tr>
      <tr>
      	<td class="formLeft">Comments:</td>
      	<td><?php echo $count["count(*)"]; ?> (<a href="editor.php?act=comments&obit_id=<?php echo $Obituary["id"]; ?>">view</a>)</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td>
        	<input name="submit" type="submit" value="Update Obituary" class="submitButton" /> &nbsp; &nbsp; &nbsp; &nbsp; 
        	<input name="updatepreview" type="submit" value="Update and Preview" class="submitButton" />
        </td>
      </tr>
  	</table>
	</form>
    <?php 
	} else {
	?>
    <div style="color:#FF0000;font-weight:bold;clear:both; padding:20px">You don't have access to this Obituary. If you have more questions just call to administrator.</div>
    <?php 
	}
	?>
    
<?php 
} elseif ($_REQUEST["act"]=='viewObituary') {
	
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual']);
	$OptionsLang = unserialize($Options['language']);
	
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Obituary = mysql_fetch_assoc($sql_result);
?>
	<div style="clear:both;padding-left:40px;padding-top:10px;padding-bottom:10px;"><a href="editor.php?act=editObituary&id=<?php echo ReadDB($Obituary['id']); ?>">Edit Obituary</a></div>
    
	<div style="font-family:<?php echo $OptionsVis["gen_font_family"];?>; font-size:<?php echo $OptionsVis["gen_font_size"];?>;margin:0 auto;width:<?php echo $OptionsVis["gen_width"];?>px; color:<?php echo $OptionsVis["gen_font_color"];?>;line-height:<?php echo $OptionsVis["gen_line_height"];?>;">
    
    
	<?php if($OptionsLang["Back_to_home"]!='') { ?>
    <div style="text-align:<?php echo $OptionsVis["link_align"]; ?>">
    	<a href="editor.php?act=obituaries" style='font-weight:<?php echo $OptionsVis["link_font_weight"]; ?>;color:<?php echo $OptionsVis["link_color"]; ?>;font-size:<?php echo $OptionsVis["link_font_size"]; ?>;text-decoration:underline'><?php echo $OptionsLang["Back_to_home"]; ?></a>
    </div>    
    <div style="clear:both; height:<?php echo $OptionsVis["dist_link_title"];?>;"></div>    
    <?php } ?>
    
	<div style="color:<?php echo $OptionsVis["title_color"];?>;font-family:<?php echo $OptionsVis["title_font"];?>;font-size:<?php echo $OptionsVis["title_size"];?>;font-weight:<?php echo $OptionsVis["title_font_weight"];?>;font-style:<?php echo $OptionsVis["title_font_style"];?>;text-align:<?php echo $OptionsVis["title_text_align"];?>;">	  
            <?php echo ReadHTML($Obituary["title"]); ?>     
    </div>
    
    <div style="clear:both; height:<?php echo $OptionsVis["dist_title_date"];?>;"></div>
    
    <?php if($OptionsVis["show_date"]=='yes') { ?>
    <div style="color:<?php echo $OptionsVis["date_color"];?>; font-family:<?php echo $OptionsVis["date_font"];?>; font-size:<?php echo $OptionsVis["date_size"];?>;font-style: <?php echo $OptionsVis["date_font_style"];?>;text-align:<?php echo $OptionsVis["date_text_align"];?>;"><?php echo date($OptionsVis["date_format"],strtotime($Obituary["publish_date"])); ?> <?php if($OptionsVis["showing_time"]!='') echo date($OptionsVis["showing_time"],strtotime($Obituary["publish_date"])); ?></div>
    <?php } ?>
    
    <div style="clear:both; height:<?php echo $OptionsVis["dist_date_text"];?>;"></div>
    
    <div style="color:<?php echo $OptionsVis["summ_color"];?>; font-family:<?php echo $OptionsVis["summ_font"];?>; font-size:<?php echo $OptionsVis["summ_size"];?>;font-style: <?php echo $OptionsVis["summ_font_style"];?>;text-align:<?php echo $OptionsVis["summ_text_align"];?>;line-height:<?php echo $OptionsVis["summ_line_height"];?>;">
      <?php if(ReadDB($Obituary["image"])!='') { ?>
		<?php if(ReadDB($Obituary["imgpos"])=='left') { ?><div style="float:left"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-right:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
        <?php if(ReadDB($Obituary["imgpos"])=='right') { ?><div style="float:right"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-left:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
        <?php if(ReadDB($Obituary["imgpos"])=='top') { ?><div style="clear:both; text-align:center"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
      <?php } ?>
        <?php echo ReadDB($Obituary["content"]); ?> 
      <?php if(ReadDB($Obituary["image"])!='') { ?>
        <?php if(ReadDB($Obituary["imgpos"])=='bottom') { ?><div style="clear:both; text-align:center"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:10px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
      <?php } ?>
    </div>
    
    <div style="clear:both; height: 12px;"></div>
    </div>


<?php 
} elseif ($_REQUEST["act"]=='comments') {
    if(isset($_REQUEST["p"])) $pageNum = $_REQUEST["p"];
    else $pageNum = 1;
    if(isset($_REQUEST["orderBy"])) $orderBy = $_REQUEST["orderBy"];
    else $orderBy = "publish_date";
    if(isset($_REQUEST["orderType"])) $orderType = $_REQUEST["orderType"];
    else $orderType = "DESC";
	if ($orderType == 'DESC') { $norderType = 'ASC'; } else { $norderType = 'DESC'; }
?>
	<div class="pageDescr">Below are the obituary comments and you can edit and delete any of them. </div>
    
    <div class="searchForm">
    <form action="editor.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>" method="post" name="form" class="formStyle">
      <input type="text" name="search" onfocus="searchdescr1(this.value);" value="<?php if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') echo $_REQUEST["search"]; else echo 'enter poster Name or Email'; ?>" class="searchfield" />
      <input type="submit" value="Search" class="submitButton" />
    </form>
	</div>
    
	<?php
	if ($_REQUEST["obit_id"]>0) {
	  $sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["obit_id"]."'";
	  $sql_resultP = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	  $Obituary = mysql_fetch_assoc($sql_resultP);
	?>
	<div class="pageDescr">This is a list of comments only for deceased: "<?php echo ReadDB($Obituary["title"]); ?>". <a href="editor.php?act=comments">Click here to view all comments</a>.</div>
	<?php	
    }
    ?>
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
      	<td width="20%" class="headlist"><a href="editor.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=publish_date">Date posted</a></td>
      	<td width="18%" class="headlist"><a href="editor.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=name">Name</a></td>
      	<td width="12%" class="headlist"><a href="editor.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=status">Status</a></td>
      	<td class="headlist">Comment on deceased</td>
      	<td colspan="2" class="headlist">&nbsp;</td>
      </tr>
      
    <?php 
	if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
		$find = mysql_escape_string($_REQUEST["search"]);
		$search = " AND (c.name LIKE '%".$find."%' OR c.email LIKE '%".$find."%')";
		if ($_REQUEST["obit_id"]>0) $search .= " AND obit_id='".$_REQUEST["obit_id"]."'";
	} else {
		if ($_REQUEST["obit_id"]>0) {
			$search .= " AND c.obit_id='".$_REQUEST["obit_id"]."'";
		} else {
			$search = '';
		}
	}
	
	$sql = "SELECT count(*) as total 
			FROM ".$TABLE["Comments"]." c, ".$TABLE["Obituaries"]." n 
			WHERE c.obit_id=n.id AND n.editor_id=".$EditorId." ".$search;
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$row   = mysql_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/30);

	$sql = "SELECT c.* FROM ".$TABLE["Comments"]." c, ".$TABLE["Obituaries"]." n 
			WHERE c.obit_id=n.id AND n.editor_id=".$EditorId." ".$search." 
			ORDER BY c." . $orderBy . " " . $orderType."  
			LIMIT " . ($pageNum-1)*30 . ",30";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	if (mysql_num_rows($sql_result)>0) {
		$i=1;
		while ($Comments = mysql_fetch_assoc($sql_result)) {
			$sqlC = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$Comments["obit_id"]."'";
			$sql_resultC = mysql_query ($sqlC, $conn ) or die ('MySQL query error: '.$sqlC.'. Error: '.mysql_error());
			$Obituary = mysql_fetch_assoc($sql_resultC);
	?>
      <tr>
        <td class="bodylist"><?php echo admin_date($Comments["publish_date"]); ?></td>
        <td class="bodylist"><?php echo ReadHTML($Comments["name"]); ?></td>
        <td class="bodylist">
        	<form action="editor.php?act=comments" method="post" name="form<?php echo $i; ?>" class="formStyle">
            <input type="hidden" name="act2" value="change_status_comm" />
            <input type="hidden" name="id" value="<?php echo $Comments["id"]; ?>" />
            <select name="status" onChange="document.form<?php echo $i; ?>.submit()">
				<option value="Approved" <?php if($Comments['status']=='Approved') echo "selected='selected'"; ?>>Approved</option>
				<option value="Not approved" <?php if($Comments['status']=='Not approved') echo "selected='selected'"; ?>>Not approved</option>
            </select>
            </form>			
        </td>
        <td class="bodylist"><?php echo cutText(ReadDB($Obituary["title"]),70); ?></td>
        <td class="bodylistAct"><a href='editor.php?act=editComment&id=<?php echo $Comments["id"]; ?>&search=<?php echo $_REQUEST["search"]; ?>&obit_id=<?php echo $_REQUEST["obit_id"]; ?>'>Edit</a></td>
        <td class="bodylistAct"><a class="delete" href="editor.php?act=delComment&id=<?php echo $Comments["id"]; ?>&search=<?php echo $_REQUEST["search"]; ?>&obit_id=<?php echo $_REQUEST["obit_id"]; ?>" onclick="return confirm('Are you sure you want to delete it?');">DELETE</a></td>
      </tr>
    <?php 
			$i++;
		}
	} else {
	?>
      <tr>
      	<td colspan="6" style="border-bottom:1px solid #CCCCCC">No comments on the Guest Book!</td>
      </tr>
    <?php	
	}
	?>

	<?php
    if ($pages>0) {
    ?>
      <tr>
    	<td colspan="6" class="bottomlist"><div class='paging'>Page: </div> 
		<?php
        for($i=1;$i<=$pages;$i++){ 
            if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
            else echo "<a href='editor.php?act=comments&p=".$i."&search=".$_REQUEST["search"]."&obit_id=".$_REQUEST["obit_id"]."' class='paging'>".$i."</a>"; 
            echo "&nbsp; ";
        } 
        ?>
		</td>
  	  </tr>
	<?php
    }
    ?>
  </table>

<?php 
} elseif ($_REQUEST["act"]=='editComment') {
	$sql = "SELECT * FROM ".$TABLE["Comments"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Comments = mysql_fetch_assoc($sql_result);
?>


    <form action="editor.php" method="post" style="margin:0px; padding:0px" name="form">
    <input type="hidden" name="act" value="updateComment" />
    <input type="hidden" name="id" value="<?php echo $Comments["id"]; ?>" />
    
    <div class="pageDescr"><a href="editor.php?act=comments&search=<?php echo $_REQUEST["search"]; ?>&obit_id=<?php echo $_REQUEST["obit_id"]; ?>">back to comments</a></div>    

	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
  	  <tr>
      	<td colspan="2" valign="top" class="headlist">Edit comment</td>
      </tr>
      <tr>
        <td class="formLeft">Posted on:</td>
        <td><?php echo admin_date($Comments["publish_date"]); ?></td>
      </tr>
      <tr>
      	<td class="formLeft">Status:</td>
      	<td>
        <select name="status" id="status">
          <option value="Not approved"<?php if ($Comments["status"]=='Not approved') echo ' selected="selected"'; ?>>Not approved</option>
          <option value="Approved"<?php if ($Comments["status"]=='Approved') echo ' selected="selected"'; ?>>Approved</option>
        </select>
      	</td>
      </tr>
      <tr>
        <td class="formLeft">Name:</td>
        <td><input name="name" type="text" size="40" maxlength="250" value="<?php echo ReadHTML($Comments["name"]); ?>" /></td>
	  </tr>
  	  <tr>
        <td class="formLeft">Email:</td>
        <td><input name="email" type="text" size="40" maxlength="250" value="<?php echo ReadHTML($Comments["email"]); ?>" /></td>
      </tr>
  	  <tr>
    	<td class="formLeft" valign="top">Comment:</td>
    	<td><textarea name="comment" cols="80" rows="10"><?php echo ReadDB($Comments["comment"]); ?></textarea></td>
  	  </tr>
  	  <tr>
        <td class="formLeft" align="left">&nbsp;</td>
        <td>
          <input type="submit" name="button2" id="button2" value="Update" class="submitButton" />
        </td>
  	  </tr>
    </table>
    </form>

    
<?php 
}
?>
</div>

<div class="clearfooter"></div>
<div class="blue_line"></div>
<div class="divProfiAnts"> <a class="footerlink" href="http://funeralscriptphp.com" target="_blank">Product of ProfiAnts</a> </div>


<?php 
} else { ////// Login Form //////
?>
	<div class="loginDiv">
	<?php if(isset($message)) {?>
    <div class="message"><?php echo $message; ?></div>
    <?php } ?>
    <form action="editor.php" method="post">
    <input type="hidden" name="act" value="login">
    <table border="0" cellspacing="0" cellpadding="0" class="loginTable">
      <tr>
        <td class="loginhead" height="57" valign="middle">EDITOR LOGIN</td>
      </tr>
      <tr>
        <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="8">
          <tr>
            <td class="userpass">Username: </td>
            <td class="userpassfield"><input name="user" type="text" class="loginfield" /></td>
          </tr>
          <tr>
            <td class="userpass">Password: </td>
            <td class="userpassfield"><input name="pass" type="password" class="loginfield" /></td>
          </tr>
          <tr>
            <td class="userpass">&nbsp;</td>
            <td class="userpassfield"><input type="submit" name="button" value="Login" class="loginButon" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="63" valign="bottom">&nbsp;</td>
      </tr>
    </table>
    </form>
    </div>
<?php 
}
?>
</center>
</body>
</html>