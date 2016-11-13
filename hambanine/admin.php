<?php
error_reporting(0);
session_start();
include("config.php");
include('fckeditor/fckeditor.php');

if(isset($_REQUEST["act"])) {
  if ($_REQUEST["act"]=='logout') {
			$_SESSION["FuNeRaLScRiPtPhPLOgiN"] = "";
			unset($_SESSION["FuNeRaLScRiPtPhPLOgiN"]);
 } elseif ($_REQUEST["act"]=='login') {
  	if ($_REQUEST["user"] == $CONFIG["admin_user"] and $_REQUEST["pass"] == $CONFIG["admin_pass"]) {
		$md_sum=md5($CONFIG["admin_user"].$CONFIG["admin_pass"]);
		$sess_id=$md_sum.strtotime("+3 hours");
		$_SESSION["FuNeRaLScRiPtPhPLOgiN"] = $sess_id;		
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
<title>Funeral Script PHP - Administration</title>

<script language="javascript" src="include/functions.js"></script>
<script language="javascript" src="include/color_pick.js"></script>
<script type="text/javascript" src="include/datetimepicker_css.js"></script>
<link href="styles/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center>
<div class="logo">Funeral Script PHP Administration</div>
<div style="clear:both"></div>

<?php  
$Logged = false;
if (isset($_SESSION["FuNeRaLScRiPtPhPLOgiN"])) $temp_sid=$_SESSION["FuNeRaLScRiPtPhPLOgiN"];
$md_sum=md5($CONFIG["admin_user"].$CONFIG["admin_pass"]);
$md_res=substr($temp_sid,0,strlen($md_sum));
if (strcmp($md_sum,$md_res)==0) {
	$ts=substr($temp_sid,strlen($md_sum));
	if ($ts>time()) $Logged = true;
}
if ( $Logged ){

if ($_REQUEST["act"]=='updateOptionsObituary') {
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET per_page	='".SaveDB($_REQUEST["per_page"])."',
				showtype	='".SaveDB($_REQUEST["showtype"])."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$_REQUEST["act"]='obit_options'; 
  	$message = 'Obituary options saved.';
	
} elseif ($_REQUEST["act"]=='updateOptionsComments') {
	
	$allowable_tags = '';
	$i=1;
	foreach($_REQUEST["allowable_tags"] as $value) {
		if($i!=1) {
			$allowable_tags .= ',';
		}
		$allowable_tags .= $value;		
		$i++;
	}

	if (!isset($_REQUEST["approval"]) or $_REQUEST["approval"]=='') $_REQUEST["approval"] = 'false';
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET email			='".SaveDB($_REQUEST["email"])."',
				approval		='".SaveDB($_REQUEST["approval"])."',
				ban_words		='".SaveDB($_REQUEST["ban_words"])."',
				comments_order	='".SaveDB($_REQUEST["comments_order"])."',
				captcha			='".SaveDB($_REQUEST["captcha"])."', 
				captcha_theme	='".SaveDB($_REQUEST["captcha_theme"])."',
				allowable_tags	='".SaveDB($allowable_tags)."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$_REQUEST["act"]='comments_options'; 
  	$message = 'Comments options saved.';

} elseif ($_REQUEST["act"]=='updateOptionsVisual') {
	
	// general style of the obituary
	$visual['gen_font_family'] = $_REQUEST['gen_font_family']; 
	$visual['gen_font_size'] = $_REQUEST['gen_font_size']; 
	$visual['gen_font_color'] = $_REQUEST['gen_font_color'];
	$visual['gen_bgr_color'] = $_REQUEST['gen_bgr_color'];
	$visual['gen_line_height'] = $_REQUEST['gen_line_height'];
	$visual['gen_width'] = $_REQUEST['gen_width'];
	
	// name of deceased in the obituary
	$visual['title_color'] = $_REQUEST['title_color']; 
	$visual['title_font'] = $_REQUEST['title_font']; 
	$visual['title_size'] = $_REQUEST['title_size']; 
	$visual['title_font_weight'] = $_REQUEST['title_font_weight']; 
	$visual['title_font_style'] = $_REQUEST['title_font_style']; 
	$visual['title_text_align'] = $_REQUEST['title_text_align']; 
	
	// summary name of deceased
	$visual['summ_title_color'] = $_REQUEST['summ_title_color']; 
	$visual['summ_title_font'] = $_REQUEST['summ_title_font']; 
	$visual['summ_title_size'] = $_REQUEST['summ_title_size']; 
	$visual['summ_title_font_weight'] = $_REQUEST['summ_title_font_weight']; 
	$visual['summ_title_font_style'] = $_REQUEST['summ_title_font_style']; 
	$visual['summ_title_text_align'] = $_REQUEST['summ_title_text_align']; 
	
	// summary date style
	$visual['summ_show_date'] = $_REQUEST['summ_show_date'];
	$visual['summ_date_color'] = $_REQUEST['summ_date_color']; 
	$visual['summ_date_font'] = $_REQUEST['summ_date_font']; 
	$visual['summ_date_size'] = $_REQUEST['summ_date_size']; 
	$visual['summ_date_font_style'] = $_REQUEST['summ_date_font_style']; 
	$visual['summ_date_text_align'] = $_REQUEST['summ_date_text_align']; 
	$visual['summ_date_format'] = $_REQUEST['summ_date_format']; 
	$visual['summ_showing_time'] = $_REQUEST['summ_showing_time'];
	
	// obituary content date style
	$visual['show_date'] = $_REQUEST['show_date'];
	$visual['date_color'] = $_REQUEST['date_color']; 
	$visual['date_font'] = $_REQUEST['date_font']; 
	$visual['date_size'] = $_REQUEST['date_size']; 
	$visual['date_font_style'] = $_REQUEST['date_font_style']; 
	$visual['date_text_align'] = $_REQUEST['date_text_align']; 
	$visual['date_format'] = $_REQUEST['date_format']; 
	$visual['showing_time'] = $_REQUEST['showing_time']; 
	
	// visual options for the obituary content 
	$visual['cont_color'] = $_REQUEST['cont_color']; 
	$visual['cont_font'] = $_REQUEST['cont_font']; 
	$visual['cont_size'] = $_REQUEST['cont_size']; 
	$visual['cont_font_style'] = $_REQUEST['cont_font_style']; 
	$visual['cont_text_align'] = $_REQUEST['cont_text_align']; 
	$visual['cont_line_height'] = $_REQUEST['cont_line_height'];
	
	// visual options for the obituary summary 
	$visual['summ_color'] = $_REQUEST['summ_color']; 
	$visual['summ_font'] = $_REQUEST['summ_font']; 
	$visual['summ_size'] = $_REQUEST['summ_size']; 
	$visual['summ_font_style'] = $_REQUEST['summ_font_style']; 
	$visual['summ_text_align'] = $_REQUEST['summ_text_align']; 
	$visual['summ_line_height'] = $_REQUEST['summ_line_height']; 
	$visual['summ_show_image'] = $_REQUEST['summ_show_image'];
	$visual['summ_img_width'] = $_REQUEST['summ_img_width']; 
	
	// paging style
	$visual['pag_font_size'] = $_REQUEST['pag_font_size']; 
	$visual['pag_color'] = $_REQUEST['pag_color']; 
	$visual['pag_font_weight'] = $_REQUEST['pag_font_weight']; 
	$visual['pag_align'] = $_REQUEST['pag_align'];
	
	// links style
	$visual['link_font_size'] = $_REQUEST['link_font_size']; 
	$visual['link_color'] = $_REQUEST['link_color']; 
	$visual['link_font_weight'] = $_REQUEST['link_font_weight']; 
	$visual['link_align'] = $_REQUEST['link_align'];
	
	// share this button style
	$visual['show_share_this'] = $_REQUEST['show_share_this'];
	
	// distances
	$visual['dist_title_date'] = $_REQUEST['dist_title_date'];
	$visual['summ_dist_title_date'] = $_REQUEST['summ_dist_title_date'];
	$visual['dist_date_text'] = $_REQUEST['dist_date_text'];
	$visual['summ_dist_date_text'] = $_REQUEST['summ_dist_date_text'];
	$visual['dist_btw_entries'] = $_REQUEST['dist_btw_entries'];	
	$visual['dist_link_title'] = $_REQUEST['dist_link_title'];
		
	$visual = serialize($visual);
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET visual='".mysql_escape_string($visual)."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$_REQUEST["act"]='visual_options'; 
  	$message = 'Visual options saved.'; 

} elseif ($_REQUEST["act"]=='updateOptionsComm') {
		
	// comments visual options
	$visual['comm_bord_sides'] = $_REQUEST['comm_bord_sides'];
	$visual['comm_bord_style'] = $_REQUEST['comm_bord_style'];
	$visual['comm_bord_width'] = $_REQUEST['comm_bord_width'];
	$visual['comm_bord_color'] = $_REQUEST['comm_bord_color'];
	$visual['comm_padding'] = $_REQUEST['comm_padding'];
	$visual['comm_bgr_color'] = $_REQUEST['comm_bgr_color'];
	
	// comments name style
	$visual['name_font_color'] = $_REQUEST['name_font_color'];
	$visual['name_font_size'] = $_REQUEST['name_font_size']; 	 
	$visual['name_font_style'] = $_REQUEST['name_font_style'];
	$visual['name_font_weight'] = $_REQUEST['name_font_weight']; 
	
	// comments date style
	$visual['comm_date_font'] = $_REQUEST['comm_date_font']; 
	$visual['comm_date_color'] = $_REQUEST['comm_date_color']; 
	$visual['comm_date_size'] = $_REQUEST['comm_date_size']; 
	$visual['comm_date_font_style'] = $_REQUEST['comm_date_font_style'];
	$visual['comm_date_format'] = $_REQUEST['comm_date_format']; 
	$visual['comm_showing_time'] = $_REQUEST['comm_showing_time'];
	$visual['time_offset'] = $_REQUEST['time_offset'];
	
	// the comment style
	$visual['comm_font_color'] = $_REQUEST['comm_font_color'];
	$visual['comm_font_size'] = $_REQUEST['comm_font_size']; 	 
	$visual['comm_font_style'] = $_REQUEST['comm_font_style'];
	$visual['comm_font_weight'] = $_REQUEST['comm_font_weight']; 
	
	// comment form label style
	$visual['label_font_color'] = $_REQUEST['label_font_color'];
	$visual['label_font_size'] = $_REQUEST['label_font_size']; 	 
	$visual['label_font_style'] = $_REQUEST['label_font_style'];
	$visual['label_font_weight'] = $_REQUEST['label_font_weight']; 
	
	$visual['dist_btw_comm'] = $_REQUEST['dist_btw_comm'];
	
		
	$visual_comm = serialize($visual);
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET visual_comm='".mysql_escape_string($visual_comm)."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$_REQUEST["act"]='visual_options_comm'; 
  	$message = 'Visual options for comments saved.'; 
	
 
} elseif ($_REQUEST["act"]=='updateOptionsLanguage') {
	
	// main words
	$language['Back_to_home'] = $_REQUEST['Back_to_home']; 
	$language['Read_more'] = $_REQUEST['Read_more'];
	$language['Paging'] = $_REQUEST['Paging']; 
	$language['Search_button'] = $_REQUEST['Search_button'];
	$language['No_entry_published'] = $_REQUEST['No_entry_published']; 
	$language['date_word'] = $_REQUEST['date_word'];
	
	// comments words
	$language['Word_Comments'] = $_REQUEST['Word_Comments'];
	$language['No_comments_posted'] = $_REQUEST['No_comments_posted'];
	$language['Leave_Comment'] = $_REQUEST['Leave_Comment'];
	$language['Comment_Name'] = $_REQUEST['Comment_Name'];	
	$language['Comment_Email'] = $_REQUEST['Comment_Email']; 
	$language['Enter_verification_code'] = $_REQUEST['Enter_verification_code']; 
	$language['Required_fields'] = $_REQUEST['Required_fields']; 
	$language['Submit_Comment'] = $_REQUEST['Submit_Comment'];
	
	// system messages
	$language['Banned_word_used'] = $_REQUEST['Banned_word_used'];
	$language['Incorrect_verification_code'] = $_REQUEST['Incorrect_verification_code']; 
	$language['Post_Submitted'] = $_REQUEST['Post_Submitted']; 
	$language['Comment_Submitted'] = $_REQUEST['Comment_Submitted'];
	$language['After_Approval_Admin'] = $_REQUEST['After_Approval_Admin'];
	
	// popup javascript message
	$language['fill_required_fields'] = $_REQUEST['fill_required_fields']; 
	$language['correct_email'] = $_REQUEST['correct_email']; 
	$language['field_code'] = $_REQUEST['field_code']; 	
	
	// email subjects
	$language['New_post_posted'] = $_REQUEST['New_post_posted']; 
	$language['New_comment_posted'] = $_REQUEST['New_comment_posted'];
	
	
	$language = serialize($language);
	
	$sql = "UPDATE ".$TABLE["Options"]." 
			SET language='".mysql_escape_string($language)."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$_REQUEST["act"]='language_options'; 
  	$message = 'Language options saved.'; 
 

} elseif ($_REQUEST["act"] == "addObituary"){
	
	if (!isset($_REQUEST["allow_comments"]) or $_REQUEST["allow_comments"]=='') $_REQUEST["allow_comments"] = 'false';
	
	$sql = "INSERT INTO ".$TABLE["Obituaries"]." 
			SET publish_date = '".SaveDB($_REQUEST["publish_date"])."',
				status = '".SaveDB($_REQUEST["status"])."',	
				editor_id = '".SaveDB($_REQUEST["editor_id"])."', 
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
				editor_id = '".SaveDB($_REQUEST["editor_id"])."',
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
	
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id = '".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
	$imageArr = mysql_fetch_assoc($sql_result);
	$image = stripslashes($imageArr["image"]);
	if($image != "") unlink($CONFIG["upload_folder"].$image);
	if($image != "") unlink($CONFIG["upload_thumbs"].$image);
	
	$sql = "DELETE FROM ".$TABLE["Comments"]." WHERE obit_id='".$_REQUEST["id"]."'";
   	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());

	$sql = "DELETE FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."'";
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
	
} elseif ($_REQUEST["act"]=='delComment') {
	
	$sql = "DELETE FROM ".$TABLE["Comments"]." WHERE id='".$_REQUEST["id"]."'";
   	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
 	$_REQUEST["act"]='comments'; 
	$message = 'Comment deleted.';


} elseif ($_REQUEST["act"] == "addEditor"){
	
	$sql = "INSERT INTO ".$TABLE["Editors"]." 
			SET editor_name = '".SaveDB($_REQUEST["editor_name"])."',
				editor_email = '".SaveDB($_REQUEST["editor_email"])."',
				editor_username = '".SaveDB($_REQUEST["editor_username"])."',
				editor_password = '".SaveDB($_REQUEST["editor_password"])."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());	
		
	$_REQUEST["act"] = "editors";		
	$message .= 'Editor created';
	
	
} elseif ($_REQUEST["act"] == "updateEditor"){
	
	$sql = "UPDATE ".$TABLE["Editors"]." 
			SET editor_name = '".SaveDB($_REQUEST["editor_name"])."',
				editor_email = '".SaveDB($_REQUEST["editor_email"])."',
				editor_username = '".SaveDB($_REQUEST["editor_username"])."',
				editor_password = '".SaveDB($_REQUEST["editor_password"])."' 
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());	
		
	$_REQUEST["act"] = "editors";		
	$message .= 'Editor updated';

} elseif ($_REQUEST["act"]=='delEditor') {
	
	$sql = "DELETE FROM ".$TABLE["Editors"]." WHERE id='".$_REQUEST["id"]."'";
   	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql." ".mysql_error());
 	$_REQUEST["act"]='editors'; 
	$message = 'Editor deleted.';

}

if ($_REQUEST["act"]=='' or !isset($_REQUEST["act"])) $_REQUEST["act"]='obituaries';
?> 

	<div class="blue_line"></div>
    
	<div class="divMenu">	
      <div class="menuButtons">
   	  	<div class="menuButton"><a<?php if($_REQUEST['act']=='obituaries' or $_REQUEST['act']=='newObituary' or $_REQUEST['act']=='viewObituary' or $_REQUEST['act']=='editObituary' or $_REQUEST['act']=='rss') echo ' class="selected"'; ?> href="admin.php?act=obituaries">Obituaries</a></div>
        <div class="menuButton"><a<?php if($_REQUEST['act']=='comments' or $_REQUEST['act']=='editComment') echo ' class="selected"'; ?> href="admin.php?act=comments">Guest Book</a></div>
        <div class="menuButton"><a<?php if($_REQUEST['act']=='editors' or $_REQUEST['act']=='newEditor' or $_REQUEST['act']=='editEditor') echo ' class="selected"'; ?> href="admin.php?act=editors">Editors</a></div>
        <div class="menuButton"><a<?php if($_REQUEST['act']=='obit_options' or $_REQUEST['act']=='comments_options' or $_REQUEST['act']=='visual_options' or $_REQUEST['act']=='visual_options_top' or $_REQUEST['act']=='visual_options_comm' or $_REQUEST['act']=='language_options') echo ' class="selected"'; ?> href="admin.php?act=obit_options">Options</a></div>
        <div class="menuButton"><a<?php if($_REQUEST['act']=='html') echo ' class="selected"'; ?> href="admin.php?act=html">Put on WebPage</a></div>
        <div class="menuButtonLogout"><a href="admin.php?act=logout">Logout</a></div>
        <div class="clear"></div>        
      </div>
	</div>
	
    <div class="blue_line"></div>


<?php
if ($_REQUEST["act"]=='obituaries' or $_REQUEST["act"]=='newObituary' or $_REQUEST["act"]=='editObituary' or $_REQUEST["act"]=='viewObituary' or $_REQUEST["act"]=='rss') {
?>
<div class="divSubMenu">	
    <div class="menuSubButtons">
   	  <div class="menuSubButton"><a<?php if($_REQUEST['act']=='obituaries' or $_REQUEST['act']=='editObituary' or $_REQUEST["act"]=='viewObituary') echo ' class="selected"'; ?> href="admin.php?act=obituaries">Obituary Entry List</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='newNObituary') echo ' class="selected"'; ?> href="admin.php?act=newObituary">New Obituary Entry</a></div>
      <div class="menuSubButton"><a href="preview.php" target="_blank">Obituaries Preview</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='rss') echo ' class="selected"'; ?> href="admin.php?act=rss">RSS feed</a></div>
      <div class="clear"></div>        
    </div>
</div>
<?php
} elseif ($_REQUEST["act"]=='editors' or $_REQUEST["act"]=='newEditor' or $_REQUEST["act"]=='editEditor') {
?>
<div class="divSubMenu">	
    <div class="menuSubButtons">
   	  <div class="menuSubButton"><a<?php if($_REQUEST['act']=='editors') echo ' class="selected"'; ?> href="admin.php?act=editors">Editors List</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='newEditor') echo ' class="selected"'; ?> href="admin.php?act=newEditor">Create Editor</a></div>
      <div class="menuSubButton"><a href="editor.php" target="_blank">Editor Login</a></div>
      <div class="clear"></div>        
    </div>
</div>
<?php 
} elseif ($_REQUEST["act"]=='obit_options' or $_REQUEST["act"]=='comments_options' or $_REQUEST["act"]=='visual_options' or $_REQUEST["act"]=='visual_options_comm' or $_REQUEST["act"]=='language_options') { 
?>
<div class="divSubMenu">	
    <div class="menuSubButtons">
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='obit_options') echo ' class="selected"'; ?> href="admin.php?act=obit_options">Obituary options</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='comments_options') echo ' class="selected"'; ?> href="admin.php?act=comments_options">Guest Book options</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='visual_options') echo ' class="selected"'; ?> href="admin.php?act=visual_options">Visual options</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='visual_options_comm') echo ' class="selected"'; ?> href="admin.php?act=visual_options_comm">Visual options Guest Book</a></div>
      <div class="menuSubButton"><a<?php if($_REQUEST['act']=='language_options') echo ' class="selected"'; ?> href="admin.php?act=language_options">Language options</a></div>
      <div class="clear"></div>        
    </div>
</div>
<?php } ?>

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
	
	$sqlPublished   = "SELECT id FROM ".$TABLE["Obituaries"]." WHERE status='Published'";
	$sql_resultPublished = mysql_query ($sqlPublished, $conn ) or die ('MySQL query error: '.$sqlPublished.'. Error: '.mysql_error());
	$ObituariesPublished = mysql_num_rows($sql_resultPublished);
	
	$sqlCount   = "SELECT id FROM ".$TABLE["Obituaries"];
	$sql_resultCount = mysql_query ($sqlCount, $conn ) or die ('MySQL query error: '.$sqlCount.'. Error: '.mysql_error());
	$ObituariesCount = mysql_num_rows($sql_resultCount);
?>
	<div class="pageDescr">Below is a list of the obituaries and you can edit and delete them. You have <strong style="font-size:16px"><?php echo $ObituariesPublished; ?></strong> obituaries published. Total number of obituaries - <strong style="font-size:16px"><?php echo $ObituariesCount; ?></strong>.</div>
    
    <div class="searchForm">
    <form action="admin.php?act=obituaries" method="post" name="form" class="formStyle">
      <input type="text" name="search" onfocus="searchdescr(this.value);" value="<?php if(isset($_REQUEST["search"])) echo $_REQUEST["search"]; else echo 'enter part of the name'; ?>" class="searchfield" />
      <input type="submit" value="Search for Obituaries" class="submitButton" />
    </form>
    </div>
    
    <form action="admin.php" method="post" name="form" class="formStyle">
    <input type="hidden" name="act" value="toArchive" />
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
  	  <tr>
        <td class="headlist"><a href="admin.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=title">Name of Deceased</a></td>
        <td width="16%" class="headlist"><a href="admin.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=publish_date">Date of Death</a></td>
        <td width="9%" class="headlist"><a href="admin.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=status">Status</a></td>
        <td width="10%" class="headlist"><a href="admin.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=editor_id">Editor</a></td>
        <td width="10%" class="headlist">Guest Book</td>
        <td width="8%" class="headlist"><a href="admin.php?act=obituaries&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=reviews">Views</a></td>
        <td class="headlist" colspan="3" width="15%">&nbsp;</td>
  	  </tr>
      
  	<?php 
	if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
	  $findMe = mysql_escape_string($_REQUEST["search"]);
	  $search = "WHERE title LIKE '%".$findMe."%'";
	} else {
	  $search = '';
	}

	$sql   = "SELECT count(*) as total FROM ".$TABLE["Obituaries"]." ".$search;
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$row   = mysql_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/20);

	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." ".$search." 
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
        <td class="bodylist"><?php echo ReadDB($Obituary["status"]); ?></td>        
        <td class="bodylist">
        	<?php 
			$sqlEd = "SELECT * FROM ".$TABLE["Editors"]." WHERE id='".$Obituary["editor_id"]."'";
			$sql_resultEd = mysql_query ($sqlEd, $conn ) or die ('MySQL query error: '.$sqlEd.'. Error: '.mysql_error());
			$Editor = mysql_fetch_assoc($sql_resultEd);	
			if($Editor["id"]>0) echo ReadHTML($Editor["editor_name"]); else echo "------"; ?>
        </td>
        <td class="bodylist"><a style="text-decoration:none" href="admin.php?act=comments&obit_id=<?php echo $Obituary["id"]; ?>"><?php echo $countComm["total"]; ?></a> <?php if($Obituary["allow_comments"]=='false') echo "<sub>(not allowed)</sub>"; ?></td>
        <td class="bodylist"><?php if($Obituary["reviews"]=='') echo "0"; else echo $Obituary["reviews"]; ?></td>
        <td class="bodylistAct"><a class="view" href='admin.php?act=viewObituary&id=<?php echo $Obituary["id"]; ?>'>Preview</a></td>
        <td class="bodylistAct"><a href='admin.php?act=editObituary&id=<?php echo $Obituary["id"]; ?>'>Edit</a></td>
        <td class="bodylistAct"><a class="delete" href="admin.php?act=delObituary&id=<?php echo $Obituary["id"]; ?>" onclick="return confirm('Are you sure you want to delete it?');">DELETE</a></td>
  	  </tr>
  	<?php 
			$i++;
		}
	} else {
	?>
      <tr>
      	<td colspan="11" style="border-bottom:1px solid #CCCCCC">No obituaries to list!</td>
      </tr>
    <?php	
	}
	?>
    
	<?php
    if ($pages>0) {
    ?>
  	  <tr>
      	<td colspan="10" class="bottomlist"><div class='paging'>Page: </div>
		<?php
        for($i=1;$i<=$pages;$i++){ 
            if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
            else echo "<a href='admin.php?act=obituaries&p=".$i."&search=".$_REQUEST["search"]."' class='paging'>".$i."</a>"; 
            echo "&nbsp; ";
        }
        ?>
      	</td>
      </tr>
	<?php
    }
    ?>
	</table>
    </form>

<?php 
} elseif ($_REQUEST["act"]=='newObituary') { 
?>
	<form action="admin.php" method="post" name="form" enctype="multipart/form-data">
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
      <?php
		$sql = "SELECT * FROM ".$TABLE["Editors"]." ORDER BY editor_name ASC";
		$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
		if (mysql_num_rows($sql_result)>0) {
	  ?> 
      <tr>
      	<td>Editor: </td>
      	<td>
        	<select name="editor_id">
            	<option value="0">---------</option>
			<?php
            while ($Editor = mysql_fetch_assoc($sql_result)) {
            ?>
         		<option value="<?php echo $Editor["id"]; ?>"><?php echo ReadDB($Editor["editor_name"]); ?></option>
            <?php
			} 
			?>
      		</select>
		</td>
      </tr> 
      <?php 
	    } else {
	  ?>    
      		<input name="editor_id" type="hidden" value="0" />
      <?php 
	    }
	  ?>     
      <tr>
        <td class="formLeft">Name of Deceased:</td>
        <td><input type="text" name="title" size="50" maxlength="250" /></td>
      </tr>
      
      <tr>
        <td class="formLeft" valign="top">Summary:</td>
        <td><textarea name="summary" cols="80" rows="4"></textarea></td>
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
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Obituary = mysql_fetch_assoc($sql_result);
	
	$sqlC   = "SELECT count(*) FROM ".$TABLE["Comments"]." WHERE obit_id='".$Obituary["id"]."'";
	$sql_resultC = mysql_query ($sqlC, $conn ) or die ('MySQL query error: '.$sqlC.'. Error: '.mysql_error());
	$count = mysql_fetch_array($sql_resultC);
?>
	<form action="admin.php" method="post" name="form" enctype="multipart/form-data">
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
      <?php
		$sql = "SELECT * FROM ".$TABLE["Editors"]." ORDER BY editor_name ASC";
		$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
		if (mysql_num_rows($sql_result)>0) {
	  ?>     
      <tr>
      	<td>Editor: </td>
      	<td>
        	<select name="editor_id">
            	<option value="0">---------</option>
			<?php 
            while ($Editor = mysql_fetch_assoc($sql_result)) {
            ?>
         		<option value="<?php echo $Editor["id"]; ?>"<?php if($Editor["id"]==$Obituary["editor_id"]) echo ' selected="selected"'; ?>><?php echo ReadDB($Editor["editor_name"]); ?></option>
            <?php 
			} 
			?>
      		</select>
		</td>
      </tr>     
      <?php 
	    } else {
	  ?>    
      		<input name="editor_id" type="hidden" value="0" />
      <?php 
	    }
	  ?>   
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
      	<td><?php echo $count["count(*)"]; ?> (<a href="admin.php?act=comments&obit_id=<?php echo $Obituary["id"]; ?>">view</a>)</td>
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
	<div style="clear:both;padding-left:40px;padding-top:10px;padding-bottom:10px;"><a href="admin.php?act=editObituary&id=<?php echo ReadDB($Obituary['id']); ?>">Edit Obituary</a></div>
    
	<div style="font-family:<?php echo $OptionsVis["gen_font_family"];?>; font-size:<?php echo $OptionsVis["gen_font_size"];?>;margin:0 auto;width:<?php echo $OptionsVis["gen_width"];?>px; color:<?php echo $OptionsVis["gen_font_color"];?>;line-height:<?php echo $OptionsVis["gen_line_height"];?>;">
    
    
	<?php if($OptionsLang["Back_to_home"]!='') { ?>
    <div style="text-align:<?php echo $OptionsVis["link_align"]; ?>">
    	<a href="admin.php?act=obituaries" style='font-weight:<?php echo $OptionsVis["link_font_weight"]; ?>;color:<?php echo $OptionsVis["link_color"]; ?>;font-size:<?php echo $OptionsVis["link_font_size"]; ?>;text-decoration:underline'><?php echo $OptionsLang["Back_to_home"]; ?></a>
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
    
    <div style="color:<?php echo $OptionsVis["cont_color"];?>; font-family:<?php echo $OptionsVis["cont_font"];?>;font-size:<?php echo $OptionsVis["cont_size"];?>;font-style:<?php echo $OptionsVis["cont_font_style"];?>;text-align:<?php echo $OptionsVis["cont_text_align"];?>;line-height:<?php echo $OptionsVis["cont_line_height"];?>;">
      <?php if(ReadDB($Obituary["image"])!='') { ?>
		<?php if(ReadDB($Obituary["imgpos"])=='left') { ?><div style="float:left"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadHTML($Obituary["title"]); ?>" style="padding-right:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
        <?php if(ReadDB($Obituary["imgpos"])=='right') { ?><div style="float:right"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadHTML($Obituary["title"]); ?>" style="padding-left:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
        <?php if(ReadDB($Obituary["imgpos"])=='top') { ?><div style="clear:both; text-align:center"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadHTML($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
      <?php } ?>
        <?php echo ReadDB($Obituary["content"]); ?> 
      <?php if(ReadDB($Obituary["image"])!='') { ?>
        <?php if(ReadDB($Obituary["imgpos"])=='bottom') { ?><div style="clear:both; text-align:center"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadHTML($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:10px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
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
	<div class="pageDescr">Below are the obituaries comments and you can edit and delete any of them. </div>
    
    <div class="searchForm">
    <form action="admin.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>" method="post" name="form" class="formStyle">
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
	<div class="pageDescr">This is a list of comments only for deceased: <em>"<?php echo ReadHTML($Obituary["title"]); ?>"</em>. <a href="admin.php?act=comments">Click here to view all comments</a>.</div>
	<?php	
    }
    ?>
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
      	<td width="20%" class="headlist"><a href="admin.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=publish_date">Date posted</a></td>
      	<td width="18%" class="headlist"><a href="admin.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=name">Name</a></td>
      	<td width="12%" class="headlist"><a href="admin.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>&orderType=<?php echo $norderType; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>&orderBy=status">Status</a></td>
      	<td class="headlist">Comment on deceased</td>
      	<td colspan="2" class="headlist">&nbsp;</td>
      </tr>
      
    <?php 
	if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
		$find = mysql_escape_string($_REQUEST["search"]);
		$search = "WHERE (name LIKE '%".$find."%' OR email LIKE '%".$find."%')";
		if ($_REQUEST["obit_id"]>0) {
			$search .= " AND obit_id='".$_REQUEST["obit_id"]."'";
		}
	} else {
		if ($_REQUEST["obit_id"]>0) {
			$search .= "WHERE obit_id='".$_REQUEST["obit_id"]."'";
		} else {
			$search = '';
		}
	}
	
	$sql   = "SELECT count(*) as total FROM ".$TABLE["Comments"]." ".$search;
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$row   = mysql_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/30);

	$sql = "SELECT * FROM ".$TABLE["Comments"]." ".$search." 
			ORDER BY " . $orderBy . " " . $orderType."  
			LIMIT " . ($pageNum-1)*30 . ",30";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	if (mysql_num_rows($sql_result)>0) {
		$i=1;
		while ($Comments = mysql_fetch_assoc($sql_result)) {
			$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$Comments["obit_id"]."'";
			$sql_resultP = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
			$Obituary = mysql_fetch_assoc($sql_resultP);
	?>
      <tr>
        <td class="bodylist"><?php echo admin_date($Comments["publish_date"]); ?></td>
        <td class="bodylist"><?php echo ReadHTML($Comments["name"]); ?></td>
        <td class="bodylist">
        	<form action="admin.php?act=comments&obit_id=<?php echo $_REQUEST["obit_id"]; ?>" method="post" name="form<?php echo $i; ?>" class="formStyle">
            <input type="hidden" name="act2" value="change_status_comm" />
            <input type="hidden" name="id" value="<?php echo $Comments["id"]; ?>" />
            <select name="status" onChange="document.form<?php echo $i; ?>.submit()">
				<option value="Approved" <?php if($Comments['status']=='Approved') echo "selected='selected'"; ?>>Approved</option>
				<option value="Not approved" <?php if($Comments['status']=='Not approved') echo "selected='selected'"; ?>>Not approved</option>
            </select>
            </form>			
        </td>
        <td class="bodylist"><?php echo cutText(ReadHTML($Obituary["title"]),70); ?></td>
        <td class="bodylistAct"><a href='admin.php?act=editComment&id=<?php echo $Comments["id"]; ?>&search=<?php echo $_REQUEST["search"]; ?>&obit_id=<?php echo $_REQUEST["obit_id"]; ?>'>Edit</a></td>
        <td class="bodylistAct"><a class="delete" href="admin.php?act=delComment&id=<?php echo $Comments["id"]; ?>&search=<?php echo $_REQUEST["search"]; ?>&obit_id=<?php echo $_REQUEST["obit_id"]; ?>" onclick="return confirm('Are you sure you want to delete it?');">DELETE</a></td>
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
            else echo "<a href='admin.php?act=comments&p=".$i."&search=".$_REQUEST["search"]."&obit_id=".$_REQUEST["obit_id"]."' class='paging'>".$i."</a>"; 
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


    <form action="admin.php" method="post" style="margin:0px; padding:0px" name="form">
    <input type="hidden" name="act" value="updateComment" />
    <input type="hidden" name="id" value="<?php echo $Comments["id"]; ?>" />
    
    <div class="pageDescr"><a href="admin.php?act=comments&search=<?php echo $_REQUEST["search"]; ?>&obit_id=<?php echo $_REQUEST["obit_id"]; ?>">back to comments</a></div>    

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
} elseif ($_REQUEST["act"]=='editors') {
	if(isset($_REQUEST["p"])) $pageNum = $_REQUEST["p"]; else $pageNum = 1;
	if(isset($_REQUEST["orderBy"])) $orderBy = $_REQUEST["orderBy"];
    else $orderBy = "editor_name";
    if(isset($_REQUEST["orderType"])) $orderType = $_REQUEST["orderType"];
    else $orderType = "ASC";
	if ($orderType == 'DESC') { $norderType = 'ASC'; } else { $norderType = 'DESC'; }
?>
	<div class="pageDescr">Below is a list of the editors and you can edit and delete them.</div>
        
	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
  	  <tr>
        <td width="20%" class="headlist"><a href="admin.php?act=editors&orderType=<?php echo $norderType; ?>&orderBy=editor_name">Name</a></td>
        <td width="20%" class="headlist"><a href="admin.php?act=editors&orderType=<?php echo $norderType; ?>&orderBy=editor_email">Email</a></td>
        <td width="20%" class="headlist"><a href="admin.php?act=editors&orderType=<?php echo $norderType; ?>&orderBy=editor_username">Username</a></td>
        <td width="20%" class="headlist">Password</td>
        <td class="headlist" colspan="2">&nbsp;</td>
  	  </tr>
      
  	<?php 
	$sql   = "SELECT count(*) as total FROM ".$TABLE["Editors"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$row   = mysql_fetch_array($sql_result);
	$count = $row["total"];
	$pages = ceil($count/20);

	$sql = "SELECT * FROM ".$TABLE["Editors"]."   
			ORDER BY " . $orderBy . " " . $orderType."  
			LIMIT " . ($pageNum-1)*20 . ",20";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	
	if (mysql_num_rows($sql_result)>0) {	
		while ($Editor = mysql_fetch_assoc($sql_result)) {			
	?>
  	  <tr>
        <td class="bodylist"><?php echo ReadDB($Editor["editor_name"]); ?></td>
        <td class="bodylist"><?php echo ReadDB($Editor["editor_email"]); ?></td>
        <td class="bodylist"><?php echo ReadDB($Editor["editor_username"]); ?></td>
        <td class="bodylist"><?php echo ReadDB($Editor["editor_password"]); ?></td>
        <td class="bodylistAct"><a href='admin.php?act=editEditor&id=<?php echo $Editor["id"]; ?>'>Edit</a></td>
        <td class="bodylistAct"><a class="delete" href="admin.php?act=delEditor&id=<?php echo $Editor["id"]; ?>" onclick="return confirm('Are you sure you want to delete it?');">DELETE</a></td>
  	  </tr>
  	<?php 
			$i++;
		}
	} else {
	?>
      <tr>
      	<td colspan="8" style="border-bottom:1px solid #CCCCCC">No Editors!</td>
      </tr>
    <?php	
	}
	?>
    
	<?php
    if ($pages>0) {
    ?>
  	  <tr>
      	<td colspan="8" class="bottomlist"><div class='paging'>Page: </div>
		<?php
        for($i=1;$i<=$pages;$i++){ 
            if($i == $pageNum ) echo "<div class='paging'>" .$i. "</div>";
            else echo "<a href='admin.php?act=editors&p=".$i."' class='paging'>".$i."</a>"; 
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
} elseif ($_REQUEST["act"]=='newEditor') { 
?>
	<form action="admin.php" method="post" name="form">
  	<input type="hidden" name="act" value="addEditor" />
  	<div class="pageDescr">To create Editor please fill all the details below and click on 'Create Editor' button.</div>
	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
      <tr>
      	<td colspan="2" valign="top" class="headlist">Create Editor</td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor name:</td>
        <td><input type="text" name="editor_name" size="40" maxlength="250" /></td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor email:</td>
        <td><input type="text" name="editor_email" size="40" maxlength="250" /></td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor Username:</td>
        <td><input type="text" name="editor_username" size="40" maxlength="250" /></td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor Password:</td>
        <td><input type="text" name="editor_password" size="40" maxlength="250" /></td>
      </tr>     
            
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" value="Create Editor" class="submitButton" /></td>
      </tr>
  	</table>
	</form>
    
<?php 
} elseif ($_REQUEST["act"]=='editEditor') {
	$sql = "SELECT * FROM ".$TABLE["Editors"]." WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Editor = mysql_fetch_assoc($sql_result);	
?>
	<form action="admin.php" method="post" name="form">
  	<input type="hidden" name="act" value="updateEditor" />
  	<input type="hidden" name="id" value="<?php echo $Editor["id"]; ?>" />
  	<div class="pageDescr">To update Editor change details below and click on 'Update Editor' button.</div>
	<table border="0" cellspacing="0" cellpadding="8" class="fieldTables">
      <tr>
      	<td colspan="2" valign="top" class="headlist">Update Editor</td>
      </tr>
       
      <tr>
        <td class="formLeft">Editor name:</td>
        <td><input type="text" name="editor_name" size="40" maxlength="250" value="<?php echo ReadDB($Editor["editor_name"]); ?>" /></td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor email:</td>
        <td><input type="text" name="editor_email" size="40" maxlength="250" value="<?php echo ReadDB($Editor["editor_email"]); ?>" /></td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor Username:</td>
        <td><input type="text" name="editor_username" size="40" maxlength="250" value="<?php echo ReadDB($Editor["editor_username"]); ?>" /></td>
      </tr>
      
      <tr>
        <td class="formLeft">Editor Password:</td>
        <td><input type="text" name="editor_password" size="40" maxlength="250" value="<?php echo ReadDB($Editor["editor_password"]); ?>" /></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td>
        	<input name="submit" type="submit" value="Update Editor" class="submitButton" />
        </td>
      </tr>
  	</table>
	</form>

    
<?php 
} elseif ($_REQUEST["act"]=='obit_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="form">
	<input type="hidden" name="act" value="updateOptionsObituary" />
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Obituary options</td>
      </tr>
      
      <tr>
        <td valign="left" width="33%">Number of obituaries per page: </td>
        <td valign="left"><input name="per_page" type="text" size="3" value="<?php echo ReadDB($Options["per_page"]); ?>" /></td>
      </tr>
      
      <tr>
        <td valign="left">Show obituaries:<br />
          <span style="font-size:11px">Choose how to display in the obituaries listing</span></td>
        <td valign="left">
          <select name="showtype"> 
          <option value="OnlyNames"<?php if ($Options["showtype"]=='OnlyNames') echo ' selected="selected"'; ?>>Only Names</option>       
          <option value="TitleAndSummary"<?php if ($Options["showtype"]=='TitleAndSummary') echo ' selected="selected"'; ?>>Names and Summary</option>
          <option value="FullEntry"<?php if ($Options["showtype"]=='FullEntry') echo ' selected="selected"'; ?>>Full Entry</option>
        </select></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>    
	</form>


<?php
} elseif ($_REQUEST["act"]=='comments_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="frm">
	<input type="hidden" name="act" value="updateOptionsComments" />
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Comments options</td>
      </tr>
      <tr>
        <td width="45%" valign="top">Administrator email:<br />
          <em>all new comments notifications will be sent to this email address</em></td>
        <td valign="top">
          <input name="email" type="text" size="50" value="<?php echo ReadDB($Options["email"]); ?>" />
        </td>
      </tr>
      <tr>
        <td width="45%" valign="top">Approval:<br />
          <span style="font-size:11px">check if you want to approve comments before having them posted on the article</span></td>
        <td valign="top"><input name="approval" type="checkbox" value="true"<?php if ($Options["approval"]=='true') echo ' checked="checked"'; ?> /></td>
      </tr>
      <tr>
        <td valign="top">Comments order:<br />
          <span style="font-size:11px">If you set 'New at the bottom', new comment will appear at the bottom of all comments.<br /> 
          If you set 'New on top', new comment will appear on top of all comments.</span></td>
        <td valign="top">
          <select name="comments_order">          
          <option value="AtBottom"<?php if ($Options["comments_order"]=='AtBottom') echo ' selected="selected"'; ?>>New at the bottom</option>
          <option value="OnTop"<?php if ($Options["comments_order"]=='OnTop') echo ' selected="selected"'; ?>>New on top</option>
        </select></td>
      </tr>
      <tr>
        <td valign="top">Type of the Captcha Verification Code:</td>
        <td valign="top">
          <select name="captcha">          
          <option value="recap"<?php if ($Options["captcha"]=='recap') echo ' selected="selected"'; ?>>reCaptcha (recommended)</option>
          <option value="cap"<?php if ($Options["captcha"]=='cap') echo ' selected="selected"'; ?>>Simple Captcha</option>
        </select></td>
      </tr>
      <tr>
        <td valign="top">If you use reCaptcha Verification, please choose the theme:</td>
        <td valign="top">
          <select name="captcha_theme">
          	  <option value="clean"<?php if ($Options["captcha_theme"]=='clean') echo ' selected="selected"'; ?>>Clean theme</option>         
              <option value="red"<?php if ($Options["captcha_theme"]=='red') echo ' selected="selected"'; ?>>Red theme</option>
              <option value="white"<?php if ($Options["captcha_theme"]=='white') echo ' selected="selected"'; ?>>White theme</option>
              <option value="blackglass"<?php if ($Options["captcha_theme"]=='blackglass') echo ' selected="selected"'; ?>>Blackglass theme</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
    
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">  
      <tr>
        <td colspan="3" class="headlist">Allowable HTML tags</td>
      </tr>      
      <tr>
        <td width="45%" valign="top">Check the html tags that you allow to be posted in the comments</td>
        <td valign="top">
        	<?php 
			$allowable_tags = explode(",",$Options["allowable_tags"])	
			?>
        	<table width="100%" border="0" cellpadding="6" cellspacing="0">
              <tr>
                <td><input name="allowable_tags[]" type="checkbox" value="<a>"<?php if(in_array("<a>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;a&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<img>"<?php if(in_array("<img>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;img&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<p>"<?php if(in_array("<p>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;p&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<strong>"<?php if(in_array("<strong>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;strong&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<em>"<?php if(in_array("<em>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;em&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<u>"<?php if(in_array("<u>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;u&gt;</td>
              </tr>
              <tr>
                <td><input name="allowable_tags[]" type="checkbox" value="<center>"<?php if(in_array("<center>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;center&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<font>"<?php if(in_array("<font>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;font&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<hr>"<?php if(in_array("<hr>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;hr&gt; </td>
                <td><input name="allowable_tags[]" type="checkbox" value="<span>"<?php if(in_array("<span>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;span&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<pre>"<?php if(in_array("<pre>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;pre&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<div>"<?php if(in_array("<div>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;div&gt;</td>
              </tr>
              <tr>
                <td><input name="allowable_tags[]" type="checkbox" value="<h1>"<?php if(in_array("<h1>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;h1&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<h2>"<?php if(in_array("<h2>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;h2&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<h3>"<?php if(in_array("<h3>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;h3&gt; </td>
                <td><input name="allowable_tags[]" type="checkbox" value="<h4>"<?php if(in_array("<h4>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;h4&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<h5>"<?php if(in_array("<h5>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;h5&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<h6>"<?php if(in_array("<h6>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;h6&gt;</td>
              </tr>
              <tr>
                <td><input name="allowable_tags[]" type="checkbox" value="<object>"<?php if(in_array("<object>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;object&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<param>"<?php if(in_array("<param>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;param&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<embed>"<?php if(in_array("<embed>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;embed&gt;</td>
                <td><input name="allowable_tags[]" type="checkbox" value="<iframe>"<?php if(in_array("<iframe>", $allowable_tags)) echo ' checked="checked"'; ?> />&lt;iframe&gt;</td>
                <td colspan="2">- video embed tags</td>
              </tr>
            </table>
			
        </td>
        
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Create a list with banned words</td>
      </tr>
      <tr>
        <td width="45%" valign="top">Make a list of words and comments containing any of these words can not be posted.<br />
          <br />
          For example: word1,word2, word3<br />
          <br />
          <span style="font-size:11px">Note that the words are not case sensitive. Does not matter if you type 'Word' or 'word'.</span></td>
        <td valign="top"><textarea name="ban_words" id="ban_words" cols="60" rows="5"><?php echo ReadDB($Options["ban_words"]); ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
	</form>
 

<?php
} elseif ($_REQUEST["act"]=='visual_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual']);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="form">
	<input type="hidden" name="act" value="updateOptionsVisual" />

    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Set Obituary front-end visual style.</td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo" style="font-family: "Times New Roman", Times, serif">General style: </td>
      </tr>
      <tr>
        <td class="langLeft">General font-family:</td>
        <td valign="top">
        	<select name="gen_font_family">
            	<option value="Arial"<?php if($OptionsVis['gen_font_family']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['gen_font_family']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['gen_font_family']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['gen_font_family']=='Times New Roman') echo ' selected="selected"'; ?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['gen_font_family']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['gen_font_family']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['gen_font_family']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">General font-size:</td>
        <td valign="top">
        	<select name="gen_font_size">
            	<option value="inherit"<?php if($OptionsVis['gen_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="10px"<?php if($OptionsVis['gen_font_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['gen_font_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['gen_font_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['gen_font_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['gen_font_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['gen_font_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
            </select>
        </td>
      </tr> 
      
      <tr>
        <td class="langLeft">General font-color:</td>
        <td valign="top"><input name="gen_font_color" type="text" size="7" value="<?php echo $OptionsVis["gen_font_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["gen_font_color"]); ?>;background-color:<?php echo $OptionsVis["gen_font_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.gen_font_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="pick color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>   
      <tr>
        <td class="langLeft">General background-color:</td>
        <td valign="top"><input name="gen_bgr_color" type="text" size="7" value="<?php echo $OptionsVis["gen_bgr_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["gen_bgr_color"]); ?>;background-color:<?php echo $OptionsVis["gen_bgr_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.gen_bgr_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="pick color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>  
      <tr>
        <td class="langLeft">General line-height:</td>
        <td valign="top">
        	<select name="gen_line_height">
            	<option value="inherit"<?php if($OptionsVis['gen_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="12px"<?php if($OptionsVis['gen_line_height']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['gen_line_height']=='13px') echo ' selected="selected"'; ?>>13px</option>
            	<option value="14px"<?php if($OptionsVis['gen_line_height']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['gen_line_height']=='15px') echo ' selected="selected"'; ?>>15px</option>
                <option value="16px"<?php if($OptionsVis['gen_line_height']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['gen_line_height']=='18px') echo ' selected="selected"'; ?>>18px</option>
            	<option value="20px"<?php if($OptionsVis['gen_line_height']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['gen_line_height']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="24px"<?php if($OptionsVis['gen_line_height']=='24px') echo ' selected="selected"'; ?>>24px</option>
                <option value="26px"<?php if($OptionsVis['gen_line_height']=='26px') echo ' selected="selected"'; ?>>26px</option>
            </select>
        </td>
      </tr>         
      <tr>
        <td class="langLeft">General obituary width:</td>
        <td valign="top"><input name="gen_width" type="text" size="4" value="<?php echo ReadDB($OptionsVis["gen_width"]); ?>" />px</td>
      </tr>  
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit1" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      <tr>
        <td colspan="3" class="subinfo">Name of deceased style: </td>
      </tr>
      <tr>
        <td class="langLeft">Title color:</td>
        <td valign="top"><input name="title_color" type="text" size="7" value="<?php echo $OptionsVis["title_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["title_color"]); ?>;background-color:<?php echo $OptionsVis["title_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.title_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Title font-family:</td>
        <td valign="top">
        	<select name="title_font">
            	<option value="Arial"<?php if($OptionsVis['title_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['title_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['title_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['title_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['title_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['title_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['title_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Title font-size:</td>
        <td valign="top">
        	<select name="title_size">
            	<option value="inherit"<?php if($OptionsVis['title_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="9px"<?php if($OptionsVis['title_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['title_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['title_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['title_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['title_size']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['title_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['title_size']=='15px') echo ' selected="selected"'; ?>>15px</option>
            	<option value="16px"<?php if($OptionsVis['title_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="17px"<?php if($OptionsVis['title_size']=='17px') echo ' selected="selected"'; ?>>17px</option>
                <option value="18px"<?php if($OptionsVis['title_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['title_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['title_size']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="24px"<?php if($OptionsVis['title_size']=='24px') echo ' selected="selected"'; ?>>24px</option>
                <option value="26px"<?php if($OptionsVis['title_size']=='26px') echo ' selected="selected"'; ?>>26px</option>
                <option value="28px"<?php if($OptionsVis['title_size']=='28px') echo ' selected="selected"'; ?>>28px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Title font-weight:</td>
        <td valign="top">
        	<select name="title_font_weight">
            	<option value="normal"<?php if($OptionsVis['title_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['title_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['title_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Title font-style:</td>
        <td valign="top">
        	<select name="title_font_style">
            	<option value="normal"<?php if($OptionsVis['title_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['title_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['title_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['title_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Title text-align:</td>
        <td valign="top">
        	<select name="title_text_align">
            	<option value="center"<?php if($OptionsVis['title_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['title_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['title_text_align']=='left') echo ' selected="selected"'; ?>>left</option>right
                <option value="right"<?php if($OptionsVis['title_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['title_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      <tr>
        <td colspan="3" class="subinfo">Summaries name of deceased style: </td>
      </tr>
      <tr>
        <td class="langLeft">Summaries name of deceased - color:</td>
        <td valign="top"><input name="summ_title_color" type="text" size="7" value="<?php echo $OptionsVis["summ_title_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["summ_title_color"]); ?>;background-color:<?php echo $OptionsVis["summ_title_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.summ_title_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Summaries name of deceased - font-family:</td>
        <td valign="top">
        	<select name="summ_title_font">
            	<option value="Arial"<?php if($OptionsVis['summ_title_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['summ_title_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['summ_title_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['summ_title_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['summ_title_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['summ_title_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['summ_title_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summaries name of deceased font-size:</td>
        <td valign="top">
        	<select name="summ_title_size">
            	<option value="inherit"<?php if($OptionsVis['summ_title_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>            	
                <option value="9px"<?php if($OptionsVis['summ_title_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['summ_title_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['summ_title_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['summ_title_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['summ_title_size']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['summ_title_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['summ_title_size']=='15px') echo ' selected="selected"'; ?>>15px</option>
            	<option value="16px"<?php if($OptionsVis['summ_title_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="17px"<?php if($OptionsVis['summ_title_size']=='17px') echo ' selected="selected"'; ?>>17px</option>
                <option value="18px"<?php if($OptionsVis['summ_title_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['summ_title_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['summ_title_size']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="24px"<?php if($OptionsVis['summ_title_size']=='24px') echo ' selected="selected"'; ?>>24px</option>
                <option value="26px"<?php if($OptionsVis['summ_title_size']=='26px') echo ' selected="selected"'; ?>>26px</option>
                <option value="28px"<?php if($OptionsVis['summ_title_size']=='28px') echo ' selected="selected"'; ?>>28px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summaries name of deceased font-weight:</td>
        <td valign="top">
        	<select name="summ_title_font_weight">
            	<option value="normal"<?php if($OptionsVis['summ_title_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['summ_title_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['summ_title_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summaries name of deceased font-style:</td>
        <td valign="top">
        	<select name="summ_title_font_style">
            	<option value="normal"<?php if($OptionsVis['summ_title_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['summ_title_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['summ_title_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['summ_title_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summaries name of deceased text-align:</td>
        <td valign="top">
        	<select name="summ_title_text_align">
            	<option value="center"<?php if($OptionsVis['summ_title_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['summ_title_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['summ_title_text_align']=='left') echo ' selected="selected"'; ?>>left</option>right
                <option value="right"<?php if($OptionsVis['summ_title_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['summ_title_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      <tr>
        <td colspan="3" class="subinfo">Date of Death style: </td>
      </tr>
      <tr>
        <td class="langLeft">Show Date of Death on summaries listing:</td>
        <td valign="top">
        	<select name="show_date">
            	<option value="yes"<?php if($OptionsVis['show_date']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['show_date']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date color:</td>
        <td valign="top"><input name="date_color" type="text" size="7" value="<?php echo $OptionsVis["date_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["date_color"]); ?>;background-color:<?php echo $OptionsVis["date_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.date_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Date font-family:</td>
        <td valign="top">
        	<select name="date_font">
            	<option value="Arial"<?php if($OptionsVis['date_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['date_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['date_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['date_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['date_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['date_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['date_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date font-size:</td>
        <td valign="top">
        	<select name="date_size">
            	<option value="inherit"<?php if($OptionsVis['date_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="9px"<?php if($OptionsVis['date_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['date_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['date_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['date_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['date_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['date_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['date_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['date_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date font-style:</td>
        <td valign="top">
        	<select name="date_font_style">
            	<option value="normal"<?php if($OptionsVis['date_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['date_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['date_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['date_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date text-align:</td>
        <td valign="top">
        	<select name="date_text_align">
            	<option value="center"<?php if($OptionsVis['date_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['date_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['date_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['date_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['date_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Date format:</td>
        <td valign="top">
        	<select name="date_format">
            	<option value="l - M j, Y"<?php if($OptionsVis['date_format']=='l - M j, Y') echo ' selected="selected"'; ?>>Monday - Jan 18, 2010</option>
                <option value="l, M j, Y"<?php if($OptionsVis['date_format']=='l, M j, Y') echo ' selected="selected"'; ?>>Monday, Jan 18, 2010</option>
                <option value="l - F j, Y"<?php if($OptionsVis['date_format']=='l - F j, Y') echo ' selected="selected"'; ?>>Monday - January 18, 2010</option>
            	<option value="l, F j, Y"<?php if($OptionsVis['date_format']=='l, F j, Y') echo ' selected="selected"'; ?>>Monday, January 18, 2010</option>
                <option value="F j, Y"<?php if($OptionsVis['date_format']=='F j, Y') echo ' selected="selected"'; ?>>January 18, 2010</option>
                <option value="M j, Y"<?php if($OptionsVis['date_format']=='M j, Y') echo ' selected="selected"'; ?>>Jan 18, 2010</option>
                <option value="m-d-Y"<?php if($OptionsVis['date_format']=='m-d-Y') echo ' selected="selected"'; ?>>MM-DD-YYYY</option>
                <option value="m.d.Y"<?php if($OptionsVis['date_format']=='m.d.Y') echo ' selected="selected"'; ?>>MM.DD.YYYY</option>
                <option value="m.d.y"<?php if($OptionsVis['date_format']=='m.d.y') echo ' selected="selected"'; ?>>MM.DD.YY</option>
                <option value="l - j M Y"<?php if($OptionsVis['date_format']=='l - j M Y') echo ' selected="selected"'; ?>>Monday - 18 Jan 2010</option>
                <option value="l, j M Y"<?php if($OptionsVis['date_format']=='l, j M Y') echo ' selected="selected"'; ?>>Monday, 18 Jan 2010</option>
                <option value="l - j F Y"<?php if($OptionsVis['date_format']=='l - j F Y') echo ' selected="selected"'; ?>>Monday - 18 January 2010</option>
                <option value="l, j F Y"<?php if($OptionsVis['date_format']=='l, j F Y') echo ' selected="selected"'; ?>>Monday, 18 January 2010</option>
                <option value="d M Y"<?php if($OptionsVis['date_format']=='d M Y') echo ' selected="selected"'; ?>>18 Jan 2010</option>
                <option value="d F Y"<?php if($OptionsVis['date_format']=='d F Y') echo ' selected="selected"'; ?>>18 January 2010</option>
                <option value="d-m-Y"<?php if($OptionsVis['date_format']=='d-m-Y') echo ' selected="selected"'; ?>>DD-MM-YYYY</option>
                <option value="d/m/Y"<?php if($OptionsVis['date_format']=='d/m/Y') echo ' selected="selected"'; ?>>DD/MM/YYYY</option>
                <option value="d.m.Y"<?php if($OptionsVis['date_format']=='d.m.Y') echo ' selected="selected"'; ?>>DD.MM.YYYY</option>
                <option value="d.m.y"<?php if($OptionsVis['date_format']=='d.m.y') echo ' selected="selected"'; ?>>DD.MM.YY</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Showing the time:</td>
        <td valign="top">
        	<select name="showing_time">
            	<option value=""<?php if($OptionsVis['showing_time']=='') echo ' selected="selected"'; ?>>without time</option>
            	<option value="G:i"<?php if($OptionsVis['showing_time']=='G:i') echo ' selected="selected"'; ?>>24h format</option>
            	<option value="g:i a"<?php if($OptionsVis['showing_time']=='g:i a') echo ' selected="selected"'; ?>>12h format</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      
      <tr>
        <td colspan="3" class="subinfo">Summaries date style: </td>
      </tr>
      <tr>
        <td class="langLeft">Show Date of Death on the summary list:</td>
        <td valign="top">
        	<select name="summ_show_date">
            	<option value="yes"<?php if($OptionsVis['summ_show_date']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['summ_show_date']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summary date color:</td>
        <td valign="top"><input name="summ_date_color" type="text" size="7" value="<?php echo $OptionsVis["summ_date_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["summ_date_color"]); ?>;background-color:<?php echo $OptionsVis["summ_date_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.summ_date_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Summary list Date of Death font-family:</td>
        <td valign="top">
        	<select name="summ_date_font">
            	<option value="Arial"<?php if($OptionsVis['summ_date_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['summ_date_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['summ_date_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['summ_date_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['summ_date_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['summ_date_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['summ_date_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summary list Date of Death font-size:</td>
        <td valign="top">
        	<select name="summ_date_size">
            	<option value="inherit"<?php if($OptionsVis['summ_date_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="9px"<?php if($OptionsVis['summ_date_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['summ_date_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['summ_date_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['summ_date_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['summ_date_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['summ_date_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['summ_date_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['summ_date_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summary list Date of Death font-style:</td>
        <td valign="top">
        	<select name="summ_date_font_style">
            	<option value="normal"<?php if($OptionsVis['summ_date_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['summ_date_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['summ_date_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['summ_date_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summary list Date of Death text-align:</td>
        <td valign="top">
        	<select name="summ_date_text_align">
            	<option value="center"<?php if($OptionsVis['summ_date_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['summ_date_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['summ_date_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['summ_date_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['summ_date_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Summary list Date of Death format:</td>
        <td valign="top">
        	<select name="summ_date_format">
            	<option value="l - M j, Y"<?php if($OptionsVis['summ_date_format']=='l - M j, Y') echo ' selected="selected"'; ?>>Monday - Jan 18, 2010</option>
                <option value="l, M j, Y"<?php if($OptionsVis['summ_date_format']=='l, M j, Y') echo ' selected="selected"'; ?>>Monday, Jan 18, 2010</option>
                <option value="l - F j, Y"<?php if($OptionsVis['summ_date_format']=='l - F j, Y') echo ' selected="selected"'; ?>>Monday - January 18, 2010</option>
            	<option value="l, F j, Y"<?php if($OptionsVis['summ_date_format']=='l, F j, Y') echo ' selected="selected"'; ?>>Monday, January 18, 2010</option>
                <option value="F j, Y"<?php if($OptionsVis['summ_date_format']=='F j, Y') echo ' selected="selected"'; ?>>January 18, 2010</option>
                <option value="M j, Y"<?php if($OptionsVis['summ_date_format']=='M j, Y') echo ' selected="selected"'; ?>>Jan 18, 2010</option>
                <option value="m-d-Y"<?php if($OptionsVis['summ_date_format']=='m-d-Y') echo ' selected="selected"'; ?>>MM-DD-YYYY</option>
                <option value="m.d.Y"<?php if($OptionsVis['summ_date_format']=='m.d.Y') echo ' selected="selected"'; ?>>MM.DD.YYYY</option>
                <option value="m.d.y"<?php if($OptionsVis['summ_date_format']=='m.d.y') echo ' selected="selected"'; ?>>MM.DD.YY</option>
                <option value="l - j M Y"<?php if($OptionsVis['summ_date_format']=='l - j M Y') echo ' selected="selected"'; ?>>Monday - 18 Jan 2010</option>
                <option value="l, j M Y"<?php if($OptionsVis['summ_date_format']=='l, j M Y') echo ' selected="selected"'; ?>>Monday, 18 Jan 2010</option>
                <option value="l - j F Y"<?php if($OptionsVis['summ_date_format']=='l - j F Y') echo ' selected="selected"'; ?>>Monday - 18 January 2010</option>
                <option value="l, j F Y"<?php if($OptionsVis['summ_date_format']=='l, j F Y') echo ' selected="selected"'; ?>>Monday, 18 January 2010</option>
                <option value="d M Y"<?php if($OptionsVis['summ_date_format']=='d M Y') echo ' selected="selected"'; ?>>18 Jan 2010</option>
                <option value="d F Y"<?php if($OptionsVis['summ_date_format']=='d F Y') echo ' selected="selected"'; ?>>18 January 2010</option>
                <option value="d-m-Y"<?php if($OptionsVis['summ_date_format']=='d-m-Y') echo ' selected="selected"'; ?>>DD-MM-YYYY</option>
                <option value="d/m/Y"<?php if($OptionsVis['summ_date_format']=='d/m/Y') echo ' selected="selected"'; ?>>DD/MM/YYYY</option>
                <option value="d.m.Y"<?php if($OptionsVis['summ_date_format']=='d.m.Y') echo ' selected="selected"'; ?>>DD.MM.YYYY</option>
                <option value="d.m.y"<?php if($OptionsVis['summ_date_format']=='d.m.y') echo ' selected="selected"'; ?>>DD.MM.YY</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td class="langLeft">Summary list Date of Death showing the time:</td>
        <td valign="top">
        	<select name="summ_showing_time">
            	<option value=""<?php if($OptionsVis['summ_showing_time']=='') echo ' selected="selected"'; ?>>without time</option>
            	<option value="G:i"<?php if($OptionsVis['summ_showing_time']=='G:i') echo ' selected="selected"'; ?>>24h format</option>
            	<option value="g:i a"<?php if($OptionsVis['summ_showing_time']=='g:i a') echo ' selected="selected"'; ?>>12h format</option>
            </select>
        </td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      
      <tr>
        <td colspan="3" class="subinfo">Obituary content style: </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary content color:</td>
        <td valign="top"><input name="cont_color" type="text" size="7" value="<?php echo $OptionsVis["cont_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["cont_color"]); ?>;background-color:<?php echo $OptionsVis["cont_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.cont_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Obituary content font-family:</td>
        <td valign="top">
        	<select name="cont_font">
            	<option value="Arial"<?php if($OptionsVis['cont_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['cont_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['cont_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['cont_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['cont_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['cont_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['cont_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary content font-size:</td>
        <td valign="top">
        	<select name="cont_size">
            	<option value="inherit"<?php if($OptionsVis['cont_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="9px"<?php if($OptionsVis['cont_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['cont_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['cont_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['cont_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['cont_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['cont_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['cont_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['cont_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary content font-style:</td>
        <td valign="top">
        	<select name="cont_font_style">
            	<option value="normal"<?php if($OptionsVis['cont_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['cont_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['cont_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['cont_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary content text-align:</td>
        <td valign="top">
        	<select name="cont_text_align">
            	<option value="center"<?php if($OptionsVis['cont_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['cont_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['cont_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['cont_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['cont_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary content line-height:</td>
        <td valign="top">
        	<select name="cont_line_height">
            	<option value="inherit"<?php if($OptionsVis['cont_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="12px"<?php if($OptionsVis['cont_line_height']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['cont_line_height']=='13px') echo ' selected="selected"'; ?>>13px</option>
            	<option value="14px"<?php if($OptionsVis['cont_line_height']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['cont_line_height']=='15px') echo ' selected="selected"'; ?>>15px</option>
                <option value="16px"<?php if($OptionsVis['cont_line_height']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['cont_line_height']=='18px') echo ' selected="selected"'; ?>>18px</option>
            	<option value="20px"<?php if($OptionsVis['cont_line_height']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['cont_line_height']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="24px"<?php if($OptionsVis['cont_line_height']=='24px') echo ' selected="selected"'; ?>>24px</option>
                <option value="26px"<?php if($OptionsVis['cont_line_height']=='26px') echo ' selected="selected"'; ?>>26px</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr>
       
      
      <tr>
        <td colspan="3" class="subinfo">Obituary summary style: </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary summary color:</td>
        <td valign="top"><input name="summ_color" type="text" size="7" value="<?php echo $OptionsVis["summ_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["summ_color"]); ?>;background-color:<?php echo $OptionsVis["summ_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.summ_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Obituary summary font-family:</td>
        <td valign="top">
        	<select name="summ_font">
            	<option value="Arial"<?php if($OptionsVis['summ_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['summ_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['summ_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['summ_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['summ_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['summ_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['summ_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary summary font-size:</td>
        <td valign="top">
        	<select name="summ_size">
            	<option value="inherit"<?php if($OptionsVis['summ_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="9px"<?php if($OptionsVis['summ_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['summ_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['summ_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['summ_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['summ_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['summ_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['summ_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['summ_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary summary font-style:</td>
        <td valign="top">
        	<select name="summ_font_style">
            	<option value="normal"<?php if($OptionsVis['summ_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['summ_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['summ_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['summ_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary summary text-align:</td>
        <td valign="top">
        	<select name="summ_text_align">
            	<option value="center"<?php if($OptionsVis['summ_text_align']=='center') echo ' selected="selected"'; ?>>center</option>
            	<option value="justify"<?php if($OptionsVis['summ_text_align']=='justify') echo ' selected="selected"'; ?>>justify</option>
                <option value="left"<?php if($OptionsVis['summ_text_align']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right"<?php if($OptionsVis['summ_text_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['summ_text_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Obituary summary line-height:</td>
        <td valign="top">
        	<select name="summ_line_height">
            	<option value="inherit"<?php if($OptionsVis['summ_line_height']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="12px"<?php if($OptionsVis['summ_line_height']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['summ_line_height']=='13px') echo ' selected="selected"'; ?>>13px</option>
            	<option value="14px"<?php if($OptionsVis['summ_line_height']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['summ_line_height']=='15px') echo ' selected="selected"'; ?>>15px</option>
                <option value="16px"<?php if($OptionsVis['summ_line_height']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['summ_line_height']=='18px') echo ' selected="selected"'; ?>>18px</option>
            	<option value="20px"<?php if($OptionsVis['summ_line_height']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['summ_line_height']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="24px"<?php if($OptionsVis['summ_line_height']=='24px') echo ' selected="selected"'; ?>>24px</option>
                <option value="26px"<?php if($OptionsVis['summ_line_height']=='26px') echo ' selected="selected"'; ?>>26px</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      
      <tr>
        <td colspan="3" class="subinfo">Image style in Obituary summary: </td>
      </tr>
      <tr>
        <td class="langLeft">Show image in the obituary listing(summary):</td>
        <td valign="top">
        	<select name="summ_show_image">
            	<option value="yes"<?php if($OptionsVis['summ_show_image']=='yes') echo ' selected="selected"'; ?>>yes</option>
            	<option value="no"<?php if($OptionsVis['summ_show_image']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Obituary summary image width:</td>
        <td valign="top"><input name="summ_img_width" type="text" size="4" value="<?php echo ReadDB($OptionsVis["summ_img_width"]); ?>" />px</td>
      </tr>  
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr>
           
        
      <tr>
        <td colspan="3" class="subinfo">Obituary paging: </td>
      </tr>
      
      <tr>
        <td class="langLeft">Paging font-size:</td>
        <td valign="top">
        	<select name="pag_font_size">
            	<option value="inherit"<?php if($OptionsVis['pag_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="10px"<?php if($OptionsVis['pag_font_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['pag_font_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['pag_font_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['pag_font_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['pag_font_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['pag_font_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td class="langLeft">Paging font color:</td>
        <td valign="top"><input name="pag_color" type="text" size="7" value="<?php echo $OptionsVis["pag_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["pag_color"]); ?>;background-color:<?php echo $OptionsVis["pag_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.pag_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr> 
      <tr>
        <td class="langLeft">Paging font-weight:</td>
        <td valign="top">
        	<select name="pag_font_weight">
            	<option value="normal"<?php if($OptionsVis['pag_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['pag_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['pag_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Paging alignment:</td>
        <td valign="top">
        	<select name="pag_align">
            	<option value="left"<?php if($OptionsVis['pag_align']=='left') echo ' selected="selected"'; ?>>left</option>
            	<option value="center"<?php if($OptionsVis['pag_align']=='center') echo ' selected="selected"'; ?>>center</option>
                <option value="right"<?php if($OptionsVis['pag_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['pag_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>            
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit7" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">Obituary 'Back' link: </td>
      </tr>
      
      <tr>
        <td class="langLeft">Back link font-size:</td>
        <td valign="top">
        	<select name="link_font_size">
            	<option value="inherit"<?php if($OptionsVis['link_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <option value="10px"<?php if($OptionsVis['link_font_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['link_font_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['link_font_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['link_font_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
            	<option value="16px"<?php if($OptionsVis['link_font_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['link_font_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td class="langLeft">Back link font color:</td>
        <td valign="top"><input name="link_color" type="text" size="7" value="<?php echo $OptionsVis["link_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["link_color"]); ?>;background-color:<?php echo $OptionsVis["link_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.link_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr> 
      <tr>
        <td class="langLeft">Back link font-weight:</td>
        <td valign="top">
        	<select name="link_font_weight">
            	<option value="normal"<?php if($OptionsVis['link_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['link_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['link_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Back link alignment:</td>
        <td valign="top">
        	<select name="link_align">
            	<option value="left"<?php if($OptionsVis['link_align']=='left') echo ' selected="selected"'; ?>>left</option>
            	<option value="center"<?php if($OptionsVis['link_align']=='center') echo ' selected="selected"'; ?>>center</option>
                <option value="right"<?php if($OptionsVis['link_align']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="inherit"<?php if($OptionsVis['link_align']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>            
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit8" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">'Share This' button below the obituary: </td>
      </tr>
      <tr>
        <td class="langLeft">Show 'Share This' buttons:</td>
        <td valign="top">
        	<select name="show_share_this">
            	<option value="yes"<?php if($OptionsVis['show_share_this']=='yes') echo ' selected="selected"'; ?>>yes</option>
                <option value="no"<?php if($OptionsVis['show_share_this']=='no') echo ' selected="selected"'; ?>>no</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit9" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">Distances: </td>
      </tr>
      
      <tr>
        <td class="langLeft">Distance between 'Name of Deceased' and 'Date of Death':</td>
        <td valign="top">
        	<select name="dist_title_date">
            	<option value="0px"<?php if($OptionsVis['dist_title_date']=='0px') echo ' selected="selected"'; ?>>0px</option>
            	<option value="1px"<?php if($OptionsVis['dist_title_date']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['dist_title_date']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['dist_title_date']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['dist_title_date']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['dist_title_date']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['dist_title_date']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['dist_title_date']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['dist_title_date']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['dist_title_date']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['dist_title_date']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['dist_title_date']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['dist_title_date']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['dist_title_date']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['dist_title_date']=='16px') echo ' selected="selected"'; ?>>16px</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Distance between 'Name of Deceased' and 'Date of Death' in the summary:</td>
        <td valign="top">
        	<select name="summ_dist_title_date">
            	<option value="0px"<?php if($OptionsVis['summ_dist_title_date']=='0px') echo ' selected="selected"'; ?>>0px</option>
            	<option value="1px"<?php if($OptionsVis['summ_dist_title_date']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['summ_dist_title_date']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['summ_dist_title_date']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['summ_dist_title_date']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['summ_dist_title_date']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['summ_dist_title_date']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['summ_dist_title_date']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['summ_dist_title_date']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['summ_dist_title_date']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['summ_dist_title_date']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['summ_dist_title_date']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['summ_dist_title_date']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['summ_dist_title_date']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['summ_dist_title_date']=='16px') echo ' selected="selected"'; ?>>16px</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Distance between 'Date of Death' and obituary content:</td>
        <td valign="top">
        	<select name="dist_date_text">
            	<option value="0px"<?php if($OptionsVis['dist_date_text']=='0px') echo ' selected="selected"'; ?>>0px</option>
            	<option value="1px"<?php if($OptionsVis['dist_date_text']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['dist_date_text']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['dist_date_text']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['dist_date_text']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['dist_date_text']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['dist_date_text']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['dist_date_text']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['dist_date_text']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['dist_date_text']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['dist_date_text']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['dist_date_text']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['dist_date_text']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['dist_date_text']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['dist_date_text']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['dist_date_text']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['dist_date_text']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Distance between 'Date of Death' and obituary summary:</td>
        <td valign="top">
        	<select name="summ_dist_date_text">
            	<option value="0px"<?php if($OptionsVis['summ_dist_date_text']=='0px') echo ' selected="selected"'; ?>>0px</option>
                <option value="1px"<?php if($OptionsVis['summ_dist_date_text']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['summ_dist_date_text']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['summ_dist_date_text']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['summ_dist_date_text']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['summ_dist_date_text']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['summ_dist_date_text']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['summ_dist_date_text']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['summ_dist_date_text']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['summ_dist_date_text']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['summ_dist_date_text']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['summ_dist_date_text']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['summ_dist_date_text']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['summ_dist_date_text']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['summ_dist_date_text']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['summ_dist_date_text']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['summ_dist_date_text']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Distance between obituaries in the obituary listing(summaries):</td>
        <td valign="top">
        	<select name="dist_btw_entries">
            	<option value="2px"<?php if($OptionsVis['dist_btw_entries']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['dist_btw_entries']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['dist_btw_entries']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['dist_btw_entries']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['dist_btw_entries']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['dist_btw_entries']=='7px') echo ' selected="selected"'; ?>>7px</option>
                <option value="8px"<?php if($OptionsVis['dist_btw_entries']=='8px') echo ' selected="selected"'; ?>>8px</option>
                <option value="9px"<?php if($OptionsVis['dist_btw_entries']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['dist_btw_entries']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="12px"<?php if($OptionsVis['dist_btw_entries']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['dist_btw_entries']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['dist_btw_entries']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['dist_btw_entries']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['dist_btw_entries']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['dist_btw_entries']=='22px') echo ' selected="selected"'; ?>>22px</option>
            	<option value="24px"<?php if($OptionsVis['dist_btw_entries']=='24px') echo ' selected="selected"'; ?>>24px</option>
            	<option value="26px"<?php if($OptionsVis['dist_btw_entries']=='26px') echo ' selected="selected"'; ?>>26px</option>
                <option value="28px"<?php if($OptionsVis['dist_btw_entries']=='28px') echo ' selected="selected"'; ?>>28px</option>
                <option value="30px"<?php if($OptionsVis['dist_btw_entries']=='30px') echo ' selected="selected"'; ?>>30px</option>
            	<option value="32px"<?php if($OptionsVis['dist_btw_entries']=='32px') echo ' selected="selected"'; ?>>32px</option>
                <option value="36px"<?php if($OptionsVis['dist_btw_entries']=='36px') echo ' selected="selected"'; ?>>36px</option>
                <option value="40px"<?php if($OptionsVis['dist_btw_entries']=='40px') echo ' selected="selected"'; ?>>40px</option>
                <option value="44px"<?php if($OptionsVis['dist_btw_entries']=='44px') echo ' selected="selected"'; ?>>44px</option>
                <option value="48px"<?php if($OptionsVis['dist_btw_entries']=='48px') echo ' selected="selected"'; ?>>48px</option>
                <option value="50px"<?php if($OptionsVis['dist_btw_entries']=='50px') echo ' selected="selected"'; ?>>50px</option>
                <option value="55px"<?php if($OptionsVis['dist_btw_entries']=='55px') echo ' selected="selected"'; ?>>55px</option>
                <option value="60px"<?php if($OptionsVis['dist_btw_entries']=='60px') echo ' selected="selected"'; ?>>60px</option>
                <option value="65px"<?php if($OptionsVis['dist_btw_entries']=='65px') echo ' selected="selected"'; ?>>65px</option>
                <option value="70px"<?php if($OptionsVis['dist_btw_entries']=='70px') echo ' selected="selected"'; ?>>70px</option>
                <option value="80px"<?php if($OptionsVis['dist_btw_entries']=='80px') echo ' selected="selected"'; ?>>80px</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Distance between 'Back' link and 'Name of Deceased':</td>
        <td valign="top">
        	<select name="dist_link_title">
            	<option value="1px"<?php if($OptionsVis['dist_link_title']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['dist_link_title']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['dist_link_title']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['dist_link_title']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['dist_link_title']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['dist_link_title']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['dist_link_title']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['dist_link_title']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['dist_link_title']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['dist_link_title']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['dist_link_title']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['dist_link_title']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['dist_link_title']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['dist_link_title']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['dist_link_title']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['dist_link_title']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit10" type="submit" value="Save" class="submitButton" /></td>
      </tr>
       
    </table>
	</form> 
    


<?php
} elseif ($_REQUEST["act"]=='visual_options_comm') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql);
	$Options = mysql_fetch_assoc($sql_result);
	$OptionsVis = unserialize($Options['visual_comm']);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="form">
	<input type="hidden" name="act" value="updateOptionsComm" />

    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Set Guest Book front-end visual style.</td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">Comments listing visual style: </td>
      </tr>
      <tr>
        <td class="langLeft">Comments listing Borders:</td>
        <td valign="top">
        	<select name="comm_bord_sides">
            	<option value="all"<?php if($OptionsVis['comm_bord_sides']=='all') echo ' selected="selected"'; ?>>all sides</option>            	
            	<option value="top"<?php if($OptionsVis['comm_bord_sides']=='top') echo ' selected="selected"'; ?>>top</option>
                <option value="bottom"<?php if($OptionsVis['comm_bord_sides']=='bottom') echo ' selected="selected"'; ?>>bottom</option>
                <option value="top_bottom"<?php if($OptionsVis['comm_bord_sides']=='top_bottom') echo ' selected="selected"'; ?>>top and bottom</option>
                <option value="right"<?php if($OptionsVis['comm_bord_sides']=='right') echo ' selected="selected"'; ?>>right</option>
                <option value="left"<?php if($OptionsVis['comm_bord_sides']=='left') echo ' selected="selected"'; ?>>left</option>
                <option value="right_left"<?php if($OptionsVis['comm_bord_sides']=='right_left') echo ' selected="selected"'; ?>>right and left</option>                <option value="none"<?php if($OptionsVis['comm_bord_sides']=='none') echo ' selected="selected"'; ?>>none</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments listing Border-style:</td>
        <td valign="top">
        	<select name="comm_bord_style">
            	<option value="solid"<?php if($OptionsVis['comm_bord_style']=='solid') echo ' selected="selected"'; ?>>solid</option>
            	<option value="double"<?php if($OptionsVis['comm_bord_style']=='double') echo ' selected="selected"'; ?>>double</option>
                <option value="dashed"<?php if($OptionsVis['comm_bord_style']=='dashed') echo ' selected="selected"'; ?>>dashed</option>
                <option value="dotted"<?php if($OptionsVis['comm_bord_style']=='dotted') echo ' selected="selected"'; ?>>dotted</option>
                <option value="outset"<?php if($OptionsVis['comm_bord_style']=='outset') echo ' selected="selected"'; ?>>outset</option>
                <option value="inset"<?php if($OptionsVis['comm_bord_style']=='inset') echo ' selected="selected"'; ?>>inset</option>
                <option value="groove"<?php if($OptionsVis['comm_bord_style']=='groove') echo ' selected="selected"'; ?>>groove</option>
                <option value="ridge"<?php if($OptionsVis['comm_bord_style']=='ridge') echo ' selected="selected"'; ?>>ridge</option>
                <option value="none"<?php if($OptionsVis['comm_bord_style']=='none') echo ' selected="selected"'; ?>>none</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments listing Border-width:</td>
        <td valign="top">
        	<select name="comm_bord_width">
            	<option value="0px"<?php if($OptionsVis['comm_bord_width']=='0px') echo ' selected="selected"'; ?>>0px</option>
            	<option value="1px"<?php if($OptionsVis['comm_bord_width']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['comm_bord_width']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['comm_bord_width']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['comm_bord_width']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['comm_bord_width']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['comm_bord_width']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['comm_bord_width']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['comm_bord_width']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['comm_bord_width']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['comm_bord_width']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['comm_bord_width']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['comm_bord_width']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['comm_bord_width']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['comm_bord_width']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['comm_bord_width']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['comm_bord_width']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments listing Border-color:</td>
        <td valign="top"><input name="comm_bord_color" type="text" size="7" value="<?php echo $OptionsVis["comm_bord_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["comm_bord_color"]); ?>;background-color:<?php echo $OptionsVis["comm_bord_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.comm_bord_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy.</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Comments listing padding:</td>
        <td valign="top">
        	<select name="comm_padding">
            	<option value="0px"<?php if($OptionsVis['comm_padding']=='0px') echo ' selected="selected"'; ?>>0px</option>
            	<option value="1px"<?php if($OptionsVis['comm_padding']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['comm_padding']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['comm_padding']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['comm_padding']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['comm_padding']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['comm_padding']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['comm_padding']=='7px') echo ' selected="selected"'; ?>>7px</option>
            	<option value="8px"<?php if($OptionsVis['comm_padding']=='8px') echo ' selected="selected"'; ?>>8px</option>
            	<option value="9px"<?php if($OptionsVis['comm_padding']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['comm_padding']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['comm_padding']=='11px') echo ' selected="selected"'; ?>>11px</option>
            	<option value="12px"<?php if($OptionsVis['comm_padding']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="14px"<?php if($OptionsVis['comm_padding']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="16px"<?php if($OptionsVis['comm_padding']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['comm_padding']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['comm_padding']=='20px') echo ' selected="selected"'; ?>>20px</option>
                <option value="22px"<?php if($OptionsVis['comm_padding']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="24px"<?php if($OptionsVis['comm_padding']=='24px') echo ' selected="selected"'; ?>>24px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments listing background color:</td>
        <td valign="top"><input name="comm_bgr_color" type="text" size="7" value="<?php echo $OptionsVis["comm_bgr_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["comm_bgr_color"]); ?>;background-color:<?php echo $OptionsVis["comm_bgr_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.comm_bgr_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy. Leave blank if you don't want this option</sub></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit1" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      
      <tr>
        <td colspan="3" class="subinfo">Comment name style: </td>
      </tr>
      <tr>
        <td class="langLeft">Comment name font color:</td>
        <td valign="top"><input name="name_font_color" type="text" size="7" value="<?php echo $OptionsVis["name_font_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["name_font_color"]); ?>;background-color:<?php echo $OptionsVis["name_font_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.name_font_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>      
      <tr>
        <td class="langLeft">Comment name font-size:</td>
        <td valign="top">
        	<select name="name_font_size">
            	<option value="inherit"<?php if($OptionsVis['name_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <option value="9px"<?php if($OptionsVis['name_font_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['name_font_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['name_font_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['name_font_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['name_font_size']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['name_font_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['name_font_size']=='15px') echo ' selected="selected"'; ?>>15px</option>
            	<option value="16px"<?php if($OptionsVis['name_font_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['name_font_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['name_font_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Comment name font-style:</td>
        <td valign="top">
        	<select name="name_font_style">
            	<option value="normal"<?php if($OptionsVis['name_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['name_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['name_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comment name font-weight:</td>
        <td valign="top">
        	<select name="name_font_weight">
            	<option value="normal"<?php if($OptionsVis['name_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['name_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['name_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      
      <tr>
        <td colspan="3" class="subinfo">Comments date style: </td>
      </tr>
      <tr>
        <td class="langLeft">Comments date color:</td>
        <td valign="top"><input name="comm_date_color" type="text" size="7" value="<?php echo $OptionsVis["comm_date_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["comm_date_color"]); ?>;background-color:<?php echo $OptionsVis["comm_date_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.comm_date_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>
      <tr>
        <td class="langLeft">Comments date font-family:</td>
        <td valign="top">
        	<select name="comm_date_font">
            	<option value="Arial"<?php if($OptionsVis['comm_date_font']=='Arial') echo ' selected="selected"'; ?>>Arial</option>
                <option value="Courier New"<?php if($OptionsVis['comm_date_font']=='Courier New') echo ' selected="selected"'; ?>>Courier New</option>
            	<option value="Georgia"<?php if($OptionsVis['comm_date_font']=='Georgia') echo ' selected="selected"'; ?>>Georgia</option>
                <option value="Times New Roman"<?php if($OptionsVis['comm_date_font']=='Times New Roman') echo ' selected="selected"';?>>Times New Roman</option>
                <option value="Trebuchet MS"<?php if($OptionsVis['comm_date_font']=='Trebuchet MS') echo ' selected="selected"'; ?>>Trebuchet MS</option>
                <option value="Verdana"<?php if($OptionsVis['comm_date_font']=='Verdana') echo ' selected="selected"'; ?>>Verdana</option>
                <option value="inherit"<?php if($OptionsVis['comm_date_font']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments date font-size:</td>
        <td valign="top">
        	<select name="comm_date_size">
            	<option value="inherit"<?php if($OptionsVis['comm_date_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            	<option value="9px"<?php if($OptionsVis['comm_date_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['comm_date_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['comm_date_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['comm_date_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['comm_date_size']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['comm_date_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['comm_date_size']=='15px') echo ' selected="selected"'; ?>>15px</option>
            	<option value="16px"<?php if($OptionsVis['comm_date_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['comm_date_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['comm_date_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comments date font-style:</td>
        <td valign="top">
        	<select name="comm_date_font_style">
            	<option value="normal"<?php if($OptionsVis['comm_date_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['comm_date_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="oblique"<?php if($OptionsVis['comm_date_font_style']=='oblique') echo ' selected="selected"'; ?>>oblique</option>
                <option value="inherit"<?php if($OptionsVis['comm_date_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Comments date format:</td>
        <td valign="top">
        	<select name="comm_date_format">
            	<option value="l - M j, Y"<?php if($OptionsVis['comm_date_format']=='l - M j, Y') echo ' selected="selected"'; ?>>Monday - Jan 18, 2010</option>
                <option value="l, M j, Y"<?php if($OptionsVis['comm_date_format']=='l, M j, Y') echo ' selected="selected"'; ?>>Monday, Jan 18, 2010</option>
                <option value="l - F j, Y"<?php if($OptionsVis['comm_date_format']=='l - F j, Y') echo ' selected="selected"'; ?>>Monday - January 18, 2010</option>
            	<option value="l, F j, Y"<?php if($OptionsVis['comm_date_format']=='l, F j, Y') echo ' selected="selected"'; ?>>Monday, January 18, 2010</option>
                <option value="F j, Y"<?php if($OptionsVis['comm_date_format']=='F j, Y') echo ' selected="selected"'; ?>>January 18, 2010</option>
                <option value="F jS, Y"<?php if($OptionsVis['comm_date_format']=='F jS, Y') echo ' selected="selected"'; ?>>January 4th, 2010</option>
                <option value="M j, Y"<?php if($OptionsVis['comm_date_format']=='M j, Y') echo ' selected="selected"'; ?>>Jan 18, 2010</option>
                <option value="m-d-Y"<?php if($OptionsVis['comm_date_format']=='m-d-Y') echo ' selected="selected"'; ?>>MM-DD-YYYY</option>
                <option value="m.d.Y"<?php if($OptionsVis['comm_date_format']=='m.d.Y') echo ' selected="selected"'; ?>>MM.DD.YYYY</option>
                <option value="m.d.y"<?php if($OptionsVis['comm_date_format']=='m.d.y') echo ' selected="selected"'; ?>>MM.DD.YY</option>
                <option value="l - j M Y"<?php if($OptionsVis['comm_date_format']=='l - j M Y') echo ' selected="selected"'; ?>>Monday - 18 Jan 2010</option>
                <option value="l, j M Y"<?php if($OptionsVis['comm_date_format']=='l, j M Y') echo ' selected="selected"'; ?>>Monday, 18 Jan 2010</option>
                <option value="l - j F Y"<?php if($OptionsVis['comm_date_format']=='l - j F Y') echo ' selected="selected"'; ?>>Monday - 18 January 2010</option>
                <option value="l, j F Y"<?php if($OptionsVis['comm_date_format']=='l, j F Y') echo ' selected="selected"'; ?>>Monday, 18 January 2010</option>
                <option value="d M Y"<?php if($OptionsVis['comm_date_format']=='d M Y') echo ' selected="selected"'; ?>>18 Jan 2010</option>
                <option value="d F Y"<?php if($OptionsVis['comm_date_format']=='d F Y') echo ' selected="selected"'; ?>>18 January 2010</option>
                <option value="d-m-Y"<?php if($OptionsVis['comm_date_format']=='d-m-Y') echo ' selected="selected"'; ?>>DD-MM-YYYY</option>
                <option value="d/m/Y"<?php if($OptionsVis['comm_date_format']=='d/m/Y') echo ' selected="selected"'; ?>>DD/MM/YYYY</option>
                <option value="d.m.Y"<?php if($OptionsVis['comm_date_format']=='d.m.Y') echo ' selected="selected"'; ?>>DD.MM.YYYY</option>
                <option value="d.m.y"<?php if($OptionsVis['comm_date_format']=='d.m.y') echo ' selected="selected"'; ?>>DD.MM.YY</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td class="langLeft">Showing comment time:</td>
        <td valign="top">
        	<select name="comm_showing_time">
            	<option value=""<?php if($OptionsVis['comm_showing_time']=='') echo ' selected="selected"'; ?>>without time</option>
            	<option value="G:i"<?php if($OptionsVis['comm_showing_time']=='G:i') echo ' selected="selected"'; ?>>24h format</option>
            	<option value="g:i a"<?php if($OptionsVis['comm_showing_time']=='g:i a') echo ' selected="selected"'; ?>>12h format</option>
            </select>
        </td>
      </tr>   
      <tr>
        <td class="langLeft">Comments time offset:</td>
        <td valign="top">
        	<select name="time_offset">
            	<option value="-11 hours"<?php if($OptionsVis['time_offset']=='-11 hours') echo ' selected="selected"'; ?>>-11 hours</option>
            	<option value="-10 hours"<?php if($OptionsVis['time_offset']=='-10') echo ' selected="selected"'; ?>>-10 hours</option>
                <option value="-9 hours"<?php if($OptionsVis['time_offset']=='-9 hours') echo ' selected="selected"'; ?>>-9 hours</option>
                <option value="-8 hours"<?php if($OptionsVis['time_offset']=='-8 hours') echo ' selected="selected"'; ?>>-8 hours</option>
                <option value="-7 hours"<?php if($OptionsVis['time_offset']=='-7 hours') echo ' selected="selected"'; ?>>-7 hours</option>
                <option value="-6 hours"<?php if($OptionsVis['time_offset']=='-6 hours') echo ' selected="selected"'; ?>>-6 hours</option>
                <option value="-5 hours"<?php if($OptionsVis['time_offset']=='-5 hours') echo ' selected="selected"'; ?>>-5 hours</option>
                <option value="-4 hours"<?php if($OptionsVis['time_offset']=='-4 hours') echo ' selected="selected"'; ?>>-4 hours</option>
                <option value="-3 hours"<?php if($OptionsVis['time_offset']=='-3 hours') echo ' selected="selected"'; ?>>-3 hours</option>
                <option value="-2 hours"<?php if($OptionsVis['time_offset']=='-2 hours') echo ' selected="selected"'; ?>>-2 hours</option>
                <option value="-1 hour"<?php if($OptionsVis['time_offset']=='-1 hour') echo ' selected="selected"'; ?>>-1 hour</option>
            	<option value="0"<?php if($OptionsVis['time_offset']=='0') echo ' selected="selected"'; ?>>no offset</option>
            	<option value="+1 hour"<?php if($OptionsVis['time_offset']=='+1 hour') echo ' selected="selected"'; ?>>+1 hour</option>
            	<option value="+2 hours"<?php if($OptionsVis['time_offset']=='+2 hours') echo ' selected="selected"'; ?>>+2 hours</option>
                <option value="+3 hours"<?php if($OptionsVis['time_offset']=='+3 hours') echo ' selected="selected"'; ?>>+3 hours</option>
                <option value="+4 hours"<?php if($OptionsVis['time_offset']=='+4 hours') echo ' selected="selected"'; ?>>+4 hours</option>
                <option value="+5 hours"<?php if($OptionsVis['time_offset']=='+5 hours') echo ' selected="selected"'; ?>>+5 hours</option>
                <option value="+6 hours"<?php if($OptionsVis['time_offset']=='+6 hours') echo ' selected="selected"'; ?>>+6 hours</option>
                <option value="+7 hours"<?php if($OptionsVis['time_offset']=='+7 hours') echo ' selected="selected"'; ?>>+7 hours</option>
                <option value="+8 hours"<?php if($OptionsVis['time_offset']=='+8 hours') echo ' selected="selected"'; ?>>+8 hours</option>
                <option value="+9 hours"<?php if($OptionsVis['time_offset']=='+9 hours') echo ' selected="selected"'; ?>>+9 hours</option>
                <option value="+10 hours"<?php if($OptionsVis['time_offset']=='+10 hours') echo ' selected="selected"'; ?>>+10 hours</option>
                <option value="+11 hours"<?php if($OptionsVis['time_offset']=='+11 hours') echo ' selected="selected"'; ?>>+11 hours</option>
            </select>
        </td>
      </tr>      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      <tr>
        <td colspan="3" class="subinfo">Comment text style: </td>
      </tr>
      <tr>
        <td class="langLeft">Comment name font color:</td>
        <td valign="top"><input name="comm_font_color" type="text" size="7" value="<?php echo $OptionsVis["comm_font_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["comm_font_color"]); ?>;background-color:<?php echo $OptionsVis["comm_font_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.comm_font_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>      
      <tr>
        <td class="langLeft">Comment text font-size:</td>
        <td valign="top">
        	<select name="comm_font_size">
            	<option value="inherit"<?php if($OptionsVis['comm_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <option value="9px"<?php if($OptionsVis['comm_font_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['comm_font_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['comm_font_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['comm_font_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['comm_font_size']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['comm_font_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['comm_font_size']=='15px') echo ' selected="selected"'; ?>>15px</option>
            	<option value="16px"<?php if($OptionsVis['comm_font_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['comm_font_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['comm_font_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Comment text font-style:</td>
        <td valign="top">
        	<select name="comm_font_style">
            	<option value="normal"<?php if($OptionsVis['comm_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['comm_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['comm_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comment text font-weight:</td>
        <td valign="top">
        	<select name="comm_font_weight">
            	<option value="normal"<?php if($OptionsVis['comm_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['comm_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['comm_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      
      <tr>
        <td colspan="3" class="subinfo">Comment form labels style: </td>
      </tr>
      <tr>
        <td class="langLeft">Comment form labels font color:</td>
        <td valign="top"><input name="label_font_color" type="text" size="7" value="<?php echo $OptionsVis["label_font_color"]; ?>" style="color:<?php echo invert_colour($OptionsVis["label_font_color"]); ?>;background-color:<?php echo $OptionsVis["label_font_color"]; ?>" /><a href="javascript:void(0)" onClick="cp.select(form.label_font_color,'pickcolor');return false;" id="pickcolor"><img src="images/color_picker.jpg" alt="select color" width="20" height="20" border="0" align="absmiddle" /></a> &nbsp; <sub> - you can pick the color from pallette or you can put it manualy</sub></td>
      </tr>      
      <tr>
        <td class="langLeft">Comment form labels font-size:</td>
        <td valign="top">
        	<select name="label_font_size">
            	<option value="inherit"<?php if($OptionsVis['label_font_size']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
                <option value="9px"<?php if($OptionsVis['label_font_size']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['label_font_size']=='10px') echo ' selected="selected"'; ?>>10px</option>
            	<option value="11px"<?php if($OptionsVis['label_font_size']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['label_font_size']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['label_font_size']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['label_font_size']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['label_font_size']=='15px') echo ' selected="selected"'; ?>>15px</option>
            	<option value="16px"<?php if($OptionsVis['label_font_size']=='16px') echo ' selected="selected"'; ?>>16px</option>
                <option value="18px"<?php if($OptionsVis['label_font_size']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="20px"<?php if($OptionsVis['label_font_size']=='20px') echo ' selected="selected"'; ?>>20px</option>
            </select>
        </td>
      </tr>  
      <tr>
        <td class="langLeft">Comment form labels font-style:</td>
        <td valign="top">
        	<select name="label_font_style">
            	<option value="normal"<?php if($OptionsVis['label_font_style']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="italic"<?php if($OptionsVis['label_font_style']=='italic') echo ' selected="selected"'; ?>>italic</option>
                <option value="inherit"<?php if($OptionsVis['label_font_style']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="langLeft">Comment form labels font-weight:</td>
        <td valign="top">
        	<select name="label_font_weight">
            	<option value="normal"<?php if($OptionsVis['label_font_weight']=='normal') echo ' selected="selected"'; ?>>normal</option>
            	<option value="bold"<?php if($OptionsVis['label_font_weight']=='bold') echo ' selected="selected"'; ?>>bold</option>
                <option value="inherit"<?php if($OptionsVis['label_font_weight']=='inherit') echo ' selected="selected"'; ?>>inherit</option>
            </select>
        </td>
      </tr>           
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr> 
      
      
      <tr>
        <td colspan="3" class="subinfo">Distances: </td>
      </tr>
      <tr>
        <td class="langLeft">Distance between comments:</td>
        <td valign="top">
        	<select name="dist_btw_comm">
            	<option value="0px"<?php if($OptionsVis['dist_btw_comm']=='0px') echo ' selected="selected"'; ?>>0px</option>
                <option value="1px"<?php if($OptionsVis['dist_btw_comm']=='1px') echo ' selected="selected"'; ?>>1px</option>
                <option value="2px"<?php if($OptionsVis['dist_btw_comm']=='2px') echo ' selected="selected"'; ?>>2px</option>
                <option value="3px"<?php if($OptionsVis['dist_btw_comm']=='3px') echo ' selected="selected"'; ?>>3px</option>
                <option value="4px"<?php if($OptionsVis['dist_btw_comm']=='4px') echo ' selected="selected"'; ?>>4px</option>
                <option value="5px"<?php if($OptionsVis['dist_btw_comm']=='5px') echo ' selected="selected"'; ?>>5px</option>
                <option value="6px"<?php if($OptionsVis['dist_btw_comm']=='6px') echo ' selected="selected"'; ?>>6px</option>
                <option value="7px"<?php if($OptionsVis['dist_btw_comm']=='7px') echo ' selected="selected"'; ?>>7px</option>
                <option value="8px"<?php if($OptionsVis['dist_btw_comm']=='8px') echo ' selected="selected"'; ?>>8px</option>
                <option value="9px"<?php if($OptionsVis['dist_btw_comm']=='9px') echo ' selected="selected"'; ?>>9px</option>
                <option value="10px"<?php if($OptionsVis['dist_btw_comm']=='10px') echo ' selected="selected"'; ?>>10px</option>
                <option value="11px"<?php if($OptionsVis['dist_btw_comm']=='11px') echo ' selected="selected"'; ?>>11px</option>
                <option value="12px"<?php if($OptionsVis['dist_btw_comm']=='12px') echo ' selected="selected"'; ?>>12px</option>
                <option value="13px"<?php if($OptionsVis['dist_btw_comm']=='13px') echo ' selected="selected"'; ?>>13px</option>
                <option value="14px"<?php if($OptionsVis['dist_btw_comm']=='14px') echo ' selected="selected"'; ?>>14px</option>
                <option value="15px"<?php if($OptionsVis['dist_btw_comm']=='15px') echo ' selected="selected"'; ?>>15px</option>
                <option value="16px"<?php if($OptionsVis['dist_btw_comm']=='16px') echo ' selected="selected"'; ?>>16px</option>
            	<option value="17px"<?php if($OptionsVis['dist_btw_comm']=='17px') echo ' selected="selected"'; ?>>17px</option>
            	<option value="18px"<?php if($OptionsVis['dist_btw_comm']=='18px') echo ' selected="selected"'; ?>>18px</option>
                <option value="19px"<?php if($OptionsVis['dist_btw_comm']=='19px') echo ' selected="selected"'; ?>>19px</option>
                <option value="20px"<?php if($OptionsVis['dist_btw_comm']=='20px') echo ' selected="selected"'; ?>>20px</option>
            	<option value="21px"<?php if($OptionsVis['dist_btw_comm']=='21px') echo ' selected="selected"'; ?>>21px</option>
                <option value="22px"<?php if($OptionsVis['dist_btw_comm']=='22px') echo ' selected="selected"'; ?>>22px</option>
                <option value="23px"<?php if($OptionsVis['dist_btw_comm']=='23px') echo ' selected="selected"'; ?>>23px</option>
                <option value="24px"<?php if($OptionsVis['dist_btw_comm']=='24px') echo ' selected="selected"'; ?>>24px</option>
                <option value="25px"<?php if($OptionsVis['dist_btw_comm']=='25px') echo ' selected="selected"'; ?>>25px</option>
                <option value="26px"<?php if($OptionsVis['dist_btw_comm']=='26px') echo ' selected="selected"'; ?>>26px</option>
                <option value="27px"<?php if($OptionsVis['dist_btw_comm']=='27px') echo ' selected="selected"'; ?>>27px</option>
                <option value="28px"<?php if($OptionsVis['dist_btw_comm']=='28px') echo ' selected="selected"'; ?>>28px</option>
                <option value="29px"<?php if($OptionsVis['dist_btw_comm']=='29px') echo ' selected="selected"'; ?>>29px</option>
                <option value="30px"<?php if($OptionsVis['dist_btw_comm']=='30px') echo ' selected="selected"'; ?>>30px</option>
                <option value="31px"<?php if($OptionsVis['dist_btw_comm']=='31px') echo ' selected="selected"'; ?>>31px</option>
                <option value="32px"<?php if($OptionsVis['dist_btw_comm']=='32px') echo ' selected="selected"'; ?>>32px</option>
            </select>
        </td>
      </tr>    
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit6" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
    </table>
	</form> 
  
    
<?php
} elseif ($_REQUEST["act"]=='language_options') {
	$sql = "SELECT * FROM ".$TABLE["Options"];
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	$Options = mysql_fetch_assoc($sql_result);
	$OptionsLang = unserialize($Options['language']);
?>
	
    <div class="paddingtop"></div>
    
    <form action="admin.php" method="post" name="frm">
	<input type="hidden" name="act" value="updateOptionsLanguage" />

    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td colspan="3" class="headlist">Translate front-end of the obituaries in your own language</td>
      </tr>
      <tr>
        <td colspan="3" class="subinfo">Obituaries navigation and paging: </td>
      </tr>
      
      <tr>
        <td class="langLeft">'Back' link:</td>
        <td valign="top"><input name="Back_to_home" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Back_to_home"]); ?>" />  &nbsp; <sub> - leave blank if you do not want 'Back' link </sub></td>
      </tr> 
      <tr>
        <td class="langLeft">Words before 'Date of Death':</td>
        <td valign="top"><input name="date_word" type="text" size="50" value="<?php echo ReadDB($OptionsLang["date_word"]); ?>" /></td>
      </tr>    
      <tr>
        <td class="langLeft">'Read more' link:</td>
        <td valign="top"><input name="Read_more" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Read_more"]); ?>" /></td>
      </tr>  
      <tr>
        <td class="langLeft">Pages:</td>
        <td valign="top"><input name="Paging" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Paging"]); ?>" /></td>
      </tr>    
      <tr>
        <td class="langLeft">'Search' button:</td>
        <td valign="top"><input name="Search_button" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Search_button"]); ?>" /></td>
      </tr> 
      <tr>
        <td class="langLeft">No obituaries to list:</td>
        <td valign="top"><input name="No_entry_published" type="text" size="50" value="<?php echo ReadDB($OptionsLang["No_entry_published"]); ?>" /></td>
      </tr> 
            
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">Obituary with comments page: </td>
      </tr>
      <tr>
        <td class="langLeft">Word 'Comments' under each obituary:</td>
        <td valign="top">
          <input name="Word_Comments" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Word_Comments"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">No comments posted:</td>
        <td valign="top">
          <input name="No_comments_posted" type="text" size="50" value="<?php echo ReadDB($OptionsLang["No_comments_posted"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Leave a Comment:</td>
        <td valign="top">
          <input name="Leave_Comment" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Leave_Comment"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Name:</td>
        <td valign="top"><input name="Comment_Name" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Comment_Name"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Email:</td>
        <td valign="top"><input name="Comment_Email" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Comment_Email"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Enter verification code:</td>
        <td valign="top"> <input name="Enter_verification_code" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Enter_verification_code"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Required fields:</td>
        <td valign="top"><input name="Required_fields" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Required_fields"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Button 'Submit Comment':</td>
        <td valign="top"><input name="Submit_Comment" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Submit_Comment"]); ?>" /> </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit2" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">System messages: </td>
      </tr>
      <tr>
        <td class="langLeft">Incorrect verification code:</td>
        <td valign="top">
          <input name="Incorrect_verification_code" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Incorrect_verification_code"]); ?>" />        </td>
      </tr>
      <tr>
        <td class="langLeft">Banned word used:</td>
        <td valign="top">
          <input name="Banned_word_used" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Banned_word_used"]); ?>" />        </td>
      </tr>          
      <tr>
        <td class="langLeft">Your comment has been submitted:</td>
        <td valign="top"><input name="Comment_Submitted" type="text" size="50" value="<?php echo ReadDB($OptionsLang["Comment_Submitted"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">After approval of the administrator will be published:<br />
        <sub>/this message will appear if the option of approving comment is checked/</sub></td>
        <td valign="top"><input name="After_Approval_Admin" type="text" size="50" value="<?php echo ReadDB($OptionsLang["After_Approval_Admin"]); ?>" /></td>
      </tr> 
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit3" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">Popup messages when check the required fields: </td>
      </tr>
      <tr>
        <td class="langLeft">Please, fill all required fields:</td>
        <td valign="top"><input name="fill_required_fields" type="text" size="50" value="<?php echo ReadDB($OptionsLang["fill_required_fields"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Please, fill correct email address:</td>
        <td valign="top"><input name="correct_email" type="text" size="50" value="<?php echo ReadDB($OptionsLang["correct_email"]); ?>" /></td>
      </tr>
      <tr>
        <td class="langLeft">Please, enter verification code:</td>
        <td valign="top"><input name="field_code" type="text" size="50" value="<?php echo ReadDB($OptionsLang["field_code"]); ?>" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit4" type="submit" value="Save" class="submitButton" /></td>
      </tr>
      
      <tr>
        <td colspan="3" class="subinfo">Admin email subjects: </td>
      </tr>      
       <tr>
        <td class="langLeft">Email subject when new comment posted:</td>
        <td valign="top"><input name="New_comment_posted" type="text" size="50" value="<?php echo ReadDB($OptionsLang["New_comment_posted"]); ?>" /></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit5" type="submit" value="Save" class="submitButton" /></td>
      </tr>
    </table>
	</form>

<?php
} elseif ($_REQUEST["act"]=='html') {
?>
	<div class="pageDescr">There are two easy ways to put the Funeral Script PHP on your website.</div>

	<table border="0" cellspacing="0" cellpadding="8" class="allTables">
      <tr>
        <td class="copycode">1) <strong>Using iframe code</strong> - just copy the code below and put it on your web page where you want the Obituaries to appear.</td>
      </tr>
      <tr>
      	<td class="putonwebpage">        	
        	<div class="divCode">&lt;iframe src=&quot;<?php echo $CONFIG["full_url"]; ?>preview.php&quot; width=&quot;100%&quot; height=&quot;700px&quot; frameborder=&quot;0&quot; scrolling=&quot;auto&quot;&gt;&lt;/iframe&gt;   </div>     
        </td>
      </tr>
    </table>
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
        <td class="copycode">2) <strong>Using PHP include()</strong> - you can use a PHP include() in any of your PHP pages. Edit your .php page and put the code below where you want the Obituaries to be.</td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php include(&quot;<?php echo $CONFIG["server_path"]; ?>obituaries.php&quot;); ?&gt; </div>     
        </td>
      </tr>
      
      <tr>
      	<td>
        	At the top of the php page (first line) you should put this line of code too so captcha image verification can work on the comment form.
        </td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;?php session_start(); ?&gt;</div>     
        </td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div>If you have any problems, please do not hesitate to contact us at info@funeralscriptphp.com</div>     
        </td>
      </tr>
            
    </table>
    


<?php
} elseif ($_REQUEST["act"]=='rss') {
?>
    
    <div class="pageDescr">The RSS feed allows other people to keep track of your obituaries using rss readers and to use your obituaries on their websites. <br />
Every time you publish a new obituary it will appear on your RSS feed and every one using it will be informed about it.</div>
    
    <table border="0" cellspacing="0" cellpadding="8" class="allTables">
    
      <tr>
        <td class="copycode">You can view the RSS feed <a href="rss.php" target="_blank">here</a> or use the code below to place it on your website as RSS link.</td>
      </tr>
      
      <tr>
        <td class="putonwebpage">        	
        	<div class="divCode">&lt;a href=&quot;<?php echo $CONFIG["full_url"]; ?>rss.php&quot; target=&quot;_blank&quot;&gt;RSS feed&lt;/a&gt;</div>     
        </td>
      </tr>
            
    </table>
    
<?php
}
?>
</div>

<div class="clearfooter"></div>
<div class="blue_line"></div>
<div class="divProfiAnts"> <a class="footerlink" href="http://funeralscriptphp.com" target="_blank">Product of ProfiAnts</a> </div>


<?php 
} else { ////// Login Box //////
?>
	<div class="loginDiv">
	<?php if(isset($message)) {?>
    <div class="message"><?php echo $message; ?></div>
    <?php } ?>
    <form action="admin.php" method="post">
    <input type="hidden" name="act" value="login">
    <table border="0" cellspacing="0" cellpadding="0" class="loginTable">
      <tr>
        <td class="loginhead" height="57" valign="middle">ADMIN LOGIN</td>
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