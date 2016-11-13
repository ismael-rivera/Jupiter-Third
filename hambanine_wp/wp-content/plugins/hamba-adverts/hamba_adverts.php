<?php
/*
Plugin Name: Hamba Adverts
Plugin URI: http://www.speggo.com
Description: A Supplementary Plugin For Adverts on a Funeral Home Website Using WP Funeral press and Custom Slider
Author: Ismael Rivera
Version: 1.0
Author URI: http://www.speggo.com
*/

add_action('admin_menu', 'register_custom_menu_page');

function register_custom_menu_page() {
   add_menu_page('custom menu title', 'custom menu', 'add_users', 'hamba-adverts/hamba_menu_page-index.php', '',   plugins_url('myplugin/images/icon.png'), 6);
}



/*// add the admin options page
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
add_options_page('Custom Plugin Page', 'Custom Plugin Menu', 'manage_options', 'plugin', 'plugin_options_page');
}
?>*/
