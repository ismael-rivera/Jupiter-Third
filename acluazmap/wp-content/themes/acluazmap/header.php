<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section
 *
 * @package WordPress
 * @subpackage AZMAP
 * @since Starkers HTML5 3.00
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<html>
<head>
<title>Interactive Map of The State of Arizona</title>   
<!--<link rel="stylesheet" href="../../dist/leaflet.css" />-->
<!--[if lte IE 8]><link rel="stylesheet" href="../../dist/leaflet.ie.css" /><![endif]-->
<!--<link rel="stylesheet" href="../css/screen.css" />-->
<!--<script src="../leaflet-include.js"></script>-->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url') . '/css/jquery-ui-1.10.0.custom.css'; ?>" />
<?php wp_deregister_script('jquery'); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&libraries=drawing"></script>
<script type="text/javascript" src="<?php echo bloginfo('template_url') . '/js/richmarker-compiled.js'; ?>"></script>

<!--PLUGINS-->


<!--Development Extension Files-->

<script type="text/javascript" src="<?php echo bloginfo('template_url') . '/plugins/fancybox' . '/source/jquery.fancybox.js?v=2.1.4';?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url') . '/plugins/fancybox' . '/source/jquery.fancybox.css?v=2.1.4'; ?>" media="screen" />

	<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url') . '/plugins/fancybox' .
'/source/helpers/jquery.fancybox-buttons.css?v=1.0.5' ?>" />
<script type="text/javascript" src="<?php echo bloginfo('template_url') . '/plugins/fancybox' . '/source/helpers/jquery.fancybox-buttons.js?v=1.0.5' ?>"></script>
    
<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo bloginfo('template_url') . '/plugins/fancybox' .'/source/helpers/jquery.fancybox-media.js?v=1.0.5' ?>"></script>



<!--END PLUGINS-->
   

<?php // embed the javascript file that makes the AJAX request
wp_enqueue_script('infobubble', get_template_directory_uri() . '/js/infobubble.js' );
wp_enqueue_script('interface', get_template_directory_uri() . '/js/interface.js' ); 
wp_enqueue_script( 'map', get_template_directory_uri() . '/js/map.js' );
// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
wp_localize_script( 'map', 
                    'wpAjax',
					 array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 
                            'islogin_data' => ajax_check_user_logged_in() 
						   ) 
				   );
?>

<!--Production Compiled Files-->
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
		.fancybox-buttons img{
	border: 1px solid #CCC;
	background: #fefefe;
			}
		.fancy-buttons{
	padding: 6px;
	border: 1px solid #CCC;
	display: block;
	height: auto;
	width: auto;
			}	
	</style>
      
<!--WPHEAD-->      
<?php wp_head(); ?>
</head>
<body>
    <!--<div>
      <input onclick="clearOverlays();" type=button value="Hide Overlays">
      <input onclick="showOverlays();" type=button value="Show All Overlays">
      <input onclick="deleteOverlays();" type=button value="Delete Overlays">
    </div>-->
    <div id="header_wrapper">
            <a href="<?php bloginfo('url'); ?>" class="logo"></a>
            <!--<div id="header" class="grid001">
            <h1 id="header_title">INTERACTIVE MAP OF THE STATE OF ARIZONA</h1>
            </div>-->
    </div>
   