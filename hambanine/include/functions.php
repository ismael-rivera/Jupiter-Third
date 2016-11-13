<?php 
function SaveDB($str) {
	if (!get_magic_quotes_gpc()) {	
		return addslashes($str); 
	} else {
		return $str;
	}
}
function ReadDB($str) {
	return stripslashes($str);
}
function ReadHTML($str) {
	return htmlspecialchars(stripslashes($str), ENT_QUOTES);
}

function admin_date($db_date) {
	return date("D - M j, Y",strtotime($db_date));
}

function cutText($strMy, $maxLength)
{
	$ret = substr($strMy, 0, $maxLength);
	if (substr($ret, strlen($ret)-1,1) != " " && strlen($strMy) > $maxLength) {
		$ret1 = substr($ret, 0, strrpos($ret," "))." ...";
	} elseif(substr($ret, strlen($ret)-1,1) == " " && strlen($strMy) > $maxLength) {
		$ret1 = $ret." ...";
	} else {
		$ret1 = $ret;
	}
	return $ret1;
}

function invert_colour($start_colour) {
	if($start_colour!='') {
		$colour_red = hexdec(substr($start_colour, 1, 2));
		$colour_green = hexdec(substr($start_colour, 3, 2));
		$colour_blue = hexdec(substr($start_colour, 5, 2));
		
		$new_red = dechex(255 - $colour_red);
		$new_green = dechex(255 - $colour_green);
		$new_blue = dechex(255 - $colour_blue);
		
		if (strlen($new_red) == 1) {$new_red .= '0';}
		if (strlen($new_green) == 1) {$new_green .= '0';}
		if (strlen($new_blue) == 1) {$new_blue .= '0';}
		
		$new_colour = '#'.$new_red.$new_green.$new_blue;
	} else {
		$new_colour = '#000000';
	}
	return $new_colour;
} 

function ResizeImage($max_width, $max_height, $IMAGE ){
	$size = getimagesize($IMAGE);
	$width = $size[0];
	$height = $size[1];
	
	$x_ratio = $max_width / $width;
	$y_ratio = $max_height / $height;
	
	if( ($width <= $max_width) && ($height <= $max_height) )
	{
		$tn_width = $width;
		$tn_height = $height;
	}
	elseif (($x_ratio * $height) < $max_height)
	{
		$tn_height = ceil($x_ratio * $height);
		$tn_width = $max_width;
	}
	else
	{
		$tn_width = ceil($y_ratio * $width);
		$tn_height = $max_height;
	}
	
	$src = imagecreatefromjpeg($IMAGE);
	$dst = imagecreatetruecolor($tn_width, $tn_height);
	imagecopyresized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
	imagejpeg($dst, $IMAGE, 95);
}

function createthumb($name,$filename,$new_w,$new_h){
	$system=explode('.',$name);
	if (preg_match('/jpg|jpeg|JPG/',$system[1])){
		$src_img=imagecreatefromjpeg($name);
	}
	if (preg_match('/png/',$system[1])){
		$src_img=imagecreatefrompng($name);
	}
	$old_x=imagesx($src_img);
	$old_y=imagesy($src_img);
	if (($old_x > $old_y) or ($old_x < $old_y)) {
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	//if ($old_x < $old_y) {
	//	$thumb_w=$old_x*($new_w/$old_y);
	//	$thumb_h=$new_h;
	//}
	if ($old_x == $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	if (preg_match("/png/",$system[1]))
	{
		imagepng($dst_img,$filename); 
	} else {
		imagejpeg($dst_img,$filename,95); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}

?>