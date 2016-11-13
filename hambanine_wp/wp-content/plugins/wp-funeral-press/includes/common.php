<?php

function wpfh_check_theme_my_login(){
	
	global $wpdb;

	 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[theme-my-login]%' and post_type = 'page'", ARRAY_A);
	 if($r[0]['ID'] == ""){
		return false;
		}else{
		return $r[0]['ID'];	
		}
	 
}
function wpfh_obit_page_id(){
	global $wpdb;

	 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[funeralpress]%' and post_type = 'page'", ARRAY_A);
							
		if($r[0]['ID'] == ""){
		return false;
		}else{
		return $r[0]['ID'];	
		}
}
function wpfh_obit_page($obitid = NULL){
	global $wpdb;
	
	
							
				
	
	if ( get_option('permalink_structure') != '' ) { 
		if($obitid != ""){
			$and ='?id='.$obitid.'';
		}
	return ''.get_permalink( wpfh_obit_page_id() ).''.$and.''	;
		
	 } else{
		 if($obitid != ""){
			$and ='&id='.$obitid.'';
		}
		 
	return 'index.php?page_id='.wpfh_obit_page_id().''.$and.'';	 
	 }
	
	
	
}
	
	

function wpfh_notice($message){
	
return $message;	
}

function get_real_image_path ($url) {
	
	global $blog_id;
	$theImageSrc = $url;
	if (isset($blog_id) && $blog_id > 0) {
		$imageParts = explode('/files/', $theImageSrc);
		if (isset($imageParts[1])) {
			$theImageSrc = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
		}
	}
	return $theImageSrc;
}

function wpfh_email($to,$subject,$content,$attach = NULL ){
	global $wpdb;
	
	
			
	
	
	  $headers = 'From: '.get_option('admin_email').' <'.get_option('admin_email').'>' . "\r\n";
  //add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
   wp_mail( $to, 
   			$subject, 
			$content, 
			$headers);
			
				
	
	
	
}


function wpfh_login(){
	global $wpdb;
	
		  if ( !is_user_logged_in() ) { 
 					
					
	
	
							if ( get_option('permalink_structure') != '' ) { 
								wpfh_redirect(''.get_site_url().'/login/?redirect_to='.urlencode($_SERVER['REQUEST_URI']).'');
								exit;
							}else{
								 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[theme-my-login]%'  and post_type = 'page'", ARRAY_A);
							
								
							wpfh_redirect('index.php?page_id='.$r[0]['ID'].'&redirect_to='.urlencode($_SERVER['REQUEST_URI']).'');
								exit;
							}
						 }else{
							 
							 	
					return true; 
					 }
	
}
function wpfh_share_fb($url,$text){
	

	$html .='<a href="http://www.facebook.com/sharer.php?u='.urlencode($url).'&t='.$text.'" class="share_fb wpfh_popup"><img src="'.content_url().'/plugins/wp-funeral-press/images/FBshare.png"></a>';
	return $html;
	
}
function wpfh_redirect($url){
	
	
	echo '<script type="text/javascript">
<!--
window.location = "'.$url.'"
//-->
</script>';exit;
}

?>