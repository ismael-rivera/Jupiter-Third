<?php
class wpfh_install{
	
function error($text,$type){
	
	
	
	return '<div class="wpfh_error_'.$type.'">'.$text.'</div>';
}
	
function topMenu($name,$submenu){
	
		global $wpfh_errors,$wpdb;
		
		
		$html .='<div id="topmenu">
		
		<a href="admin.php?page=wpfh" class="button">'.get_option('wpfh_obit_name_plural').'</a>
		<a href="admin.php?page=wpfh" class="button">'. __("Adverts") .'</a>
		<a href="admin.php?page=wpfh-guestbook" class="button">Guestbook</a>
		
		
		
		';
		if(class_exists('wpfh_cem_scripts')){global $wpfh_cem_scripts;	$html .= $wpfh_cem_scripts->topmenu(); }
	
		
		$html .='<a href="admin.php?page=wpfh-settings" class="button">Settings</a>
		<h1 class="topmenu-header">'.$name.'</h1>';
		
		
$r_pending = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "wpfh_posts where approved = 0", ARRAY_A);
if(count($r_pending) > 0){	
	$html .= $this->error('You have ('.count($r_pending).') pending guestbook posts waiting to be approved, <a href="admin.php?page=wpfh-guestbook">click here to approve them</a>', 'info');

}
	
	
	
			
	if(wpfh_obit_page_id() == false){
	$html .= $this->error('Please add the shortcode<strong> [funeralpress] </strong>on a page for this plugin to work properly.', 'error');	
	}
	if(!function_exists('theme_my_login')){
	$html .= $this->error('This plugin requires "Theme My Login" plugin which allows for a seamless login experience. <a href="wp-admin/plugin-install.php?tab=search&s=theme+my+login&plugin-search-input=Search+Plugins">Please install this plugin using the plugin manager.</a>', 'error');	
	}
			
		

		
		$html .='
		<div id="submenu">
		'.$submenu.'
		</div>
		
		</div>';
		return $html ;	
	
}

function menu(){
	global $wpdb,$wpfh_obits_admin,$wpfh_settings,$wpfh_obits_guestbook;
	
	
	 add_menu_page( 'wpfu', 'WP Funeral Home',  'manage_options', 'wpfh', array($wpfh_obits_admin ,'view'));
	add_submenu_page( 'wpfu', 'Settings',  'Settings',  'manage_options', 'wpfh-settings', array($wpfh_settings ,'view'));
	add_submenu_page( 'wpfu', 'Email Settings',  'Email Settings',  'manage_options', 'wpfh-settings-email', array($wpfh_settings ,'email'));
	add_submenu_page( 'wpfu', 'Custom CSS',  'Custom CSS',  'manage_options', 'wpfh-custom-css', array($wpfh_settings ,'css'));
	add_submenu_page( 'wpfu', 'Guest Book',  'Guest Book',  'manage_options', 'wpfh-guestbook', array($wpfh_obits_guestbook ,'view'));
}
	
function install(){
	
	global $wpdb,$wpfh_version;
	
	
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	$tables = $this->db_tables();
	foreach($tables as $key => $value){
		  if($value != ""){
		  dbDelta($value);
			$notice .= '<strong>Installed '.$key.'</strong><br>';
		  }
	}
	
	$alters = $this->db_alters();
	foreach( $alters as $key => $value){
		  if($value != ""){
		  $wpdb->query($value);
		  }
	}
	if(get_option('wpfh_obit_name') == ''){
	add_option("wpfh_obit_name", "Obituary");
	add_option("wpfh_obit_name_plural", "Obituaries");
	add_option("wpfh_obit_display_num", "25");
	add_option('wpfh_enable_search','1' );
	add_option('wpfh_obit_style', 'block');
	
	add_option('wpfh_email_admin', 'Dear Admin

There has been a new guestbook posting by [user] on [obit]

Click here to approve: [link]');
	add_option('wpfh_email_user', 'Dear [user],

Thank you for posting on [obit]\'s guestbook page, once your message has been approved we will email you.');
	add_option('wpfh_email_user_approved', 'Dear [user],

Your guestbook posting has been approved. Please follow the following link to view the guestbook page: [link]');
	
	
	add_option('wpfh_email_admin_subject', 'New Guest Posting');
	add_option('wpfh_email_user_subject', 'Thank you for your guestbook posting');
	add_option('wpfh_email_user_approved_subject','Your guestbook posting has been approved');
	
	}
	add_option("wpfh_version", $wpfh_version);
	return $notice;
}
	
function db_tables(){
	global $wpdb;
	$sql = array(
	
	"".$wpdb->prefix ."wpfh_obits" =>
	"CREATE TABLE  `".$wpdb->prefix ."wpfh_obits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` text NOT NULL,
  `birth_date`  date NOT NULL DEFAULT '0000-00-00',
  `death_date`  date NOT NULL DEFAULT '0000-00-00',
  `obituary` text NOT NULL,
  `visitation_time` text NOT NULL,
   `obit_notes` text NOT NULL,
  `service_time` text NOT NULL,
  `middle` varchar(255) NOT NULL DEFAULT '',
  `section` varchar(255) NOT NULL DEFAULT '',
  `lot_number` varchar(255) NOT NULL DEFAULT '',
  `grave_number` varchar(255) NOT NULL DEFAULT '',
  `burial_date` date NOT NULL DEFAULT '0000-00-00',
  `photo` text NOT NULL,
  `flowers` text NOT NULL,
  `count` int(25) NOT NULL DEFAULT '0',
  `page` varchar(100) NOT NULL DEFAULT '',
  `vet` int(1) NOT NULL DEFAULT '0',
  `maiden` text NOT NULL,
  `funeralhome` varchar(255) NOT NULL DEFAULT '',
  `placeofservice` varchar(255) NOT NULL DEFAULT '',
  `sports` text NOT NULL,
  `frat` text NOT NULL,
  `org` text NOT NULL,
  `edu` text NOT NULL,
  `disabled` int(1) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `cemid` varchar(255) NOT NULL DEFAULT '1',
  `approved` int(1) NOT NULL DEFAULT '1',
  `package` int(11) NOT NULL DEFAULT '1',
  `photosleft` int(11) NOT NULL DEFAULT '10',
  `tribpackage` int(11) NOT NULL DEFAULT '1',
  `tribleft` int(11) NOT NULL DEFAULT '1',
  `affiliations` text NOT NULL,
  `group` int(1) NOT NULL DEFAULT '0',
  `headstone` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);",
"".$wpdb->prefix ."wpfh_posts" =>
	"CREATE TABLE  `".$wpdb->prefix ."wpfh_posts` (
	 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` varchar(255) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  `oid` int(11) NOT NULL,
  `anonymous` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
	);"


	
	);	
	return 	$sql;
}
function db_alters(){
	global $wpdb;
		$sql = array(
	
	""
	
	);	
	return 	$sql;
}
}

?>