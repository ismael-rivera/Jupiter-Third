<?php
/*
Plugin Name: WP FuneralPress
Plugin URI: http://www.wpfuneralpress.com
Description: An Obituary Plugin For Funeral Homes and Cemeteries
Author: Anthony Brown
Version: 1.0.3
Author URI: http://www.wpfuneralpress.com
*/

load_plugin_textdomain( 'sp-wpfh', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

global $wpfh_version;
$wpfh_version = "1.0.3";

//includes
include ''.dirname(__FILE__).'/includes/common.php';
include ''.dirname(__FILE__).'/includes/pagination.php';

include ''.dirname(__FILE__).'/admin/adverts.php';
$wpfh_obits_admin = new wpfh_adverts_admin;

include ''.dirname(__FILE__).'/admin/obits.php';
$wpfh_obits_admin = new wpfh_obits_admin;

include ''.dirname(__FILE__).'/admin/guestbook.php';
$wpfh_obits_guestbook = new wpfh_obits_guestbook;

include ''.dirname(__FILE__).'/includes/install.php';
$wpfh_install = new wpfh_install;

include ''.dirname(__FILE__).'/admin/settings.php';
$wpfh_settings = new wpfh_settings;



include ''.dirname(__FILE__).'/user/shortcodes.php';
include ''.dirname(__FILE__).'/user/widgets.php';
include ''.dirname(__FILE__).'/user/obits.php';



/*if($_GET['wpfh_reinstall_db'] == 1){
	
$wpfh_notice .= $wpfh_install->install();

if(class_exists('wpfh_cem_scripts')){
	
$wpfh_notice .= $wpfh_cem_scripts->install();
	
}

echo $wpfh_notice;	
}*/



//install and hooks
register_activation_hook(__FILE__,  array($wpfh_install ,'install'));
add_action('admin_menu', array($wpfh_install ,'menu'));



// css and javascript hooks
class wpfh_scripts{
function js(){
	
				wp_enqueue_script('jquery');
	
			  wp_enqueue_script( 'jquery-ui-core' );
			   wp_enqueue_script( 'jquery-ui-dialog' );
			   wp_enqueue_script( 'jquery-ui-tabs' );
			   wp_enqueue_script( 'jquery-ui-datepicker' );
			    wp_enqueue_script('wpfh-js-scripts', plugins_url('js/scripts.js', __FILE__));
}

function css(){
	
	

	if(file_exists(''.get_template_directory().'/obits.css')){
	wp_register_style( 'wpfh-style',''.get_bloginfo( 'stylesheet_directory' ).'/obits.css');
	}else{
	wp_register_style( 'wpfh-style',plugins_url('/css/style.css', __FILE__) );
	}
	wp_register_style( 'jqueryui-smoothness',plugins_url('/css/smoothness/jquery-ui-1.9.0.custom.min.css', __FILE__) );

		wp_register_style( 'wpfh-tabs',plugins_url('/css/tabs.css', __FILE__) );
	 wp_enqueue_style( 'wpfh-style' );
	  wp_enqueue_style( 'jqueryui-smoothness' );
	  wp_enqueue_style( 'wpfh-tabs' );
}



 
function editor_admin_init() {
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
}
 
function editor_admin_head() {
  wp_tiny_mce();
}
}


// javascript and css
$wpfh_scripts = new wpfh_scripts;
add_action('wp_head', array($wpfh_scripts ,'css'));	
add_action('init', array($wpfh_scripts ,'js'));
add_action('admin_head', array($wpfh_scripts ,'css'));

//editor in admin
add_action('admin_init',  array($wpfh_scripts ,'editor_admin_init'));
add_action('admin_head',  array($wpfh_scripts ,'editor_admin_head'));

  if(function_exists('tml_register_form')){
  function tml_new_user_registered( $user_id ) {
	wp_set_auth_cookie( $user_id, false, is_ssl() );
	$referer = remove_query_arg( array( 'action', 'instance' ), wp_get_referer() );
	wp_redirect( $referer );
	exit;
}
add_action( 'tml_new_user_registered', 'tml_new_user_registered' );

function tml_register_form() {
	wp_original_referer_field( true, 'previous' );
}
add_action( 'register_form', 'tml_register_form' );
  }
  
  
?>