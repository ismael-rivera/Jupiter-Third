<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file 
 *
 * Please see /external/starkers-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php get_header(); ?>

<div id="content">
    
    <div id="map_canvas"></div>
    <div id="map_console">
    <div id="relative_wrap">
    <span unselectable="on" href="" class="activate_console_btn" id="markers_tab"></span>
    <div class="slideout">
        <h3 unselectable="on">Toolbar</h3>
            <div class="tabcontent">
              <table id="toolbar_tbl">
                 <tr>
                 <td>
                 <ul class="tool_btns">
                  <li><div id="marker" class="tool_btn" onclick="markerButtonFunctions('marker');"></div></li>
                  <!--<li><?php //echo ajax_check_user_logged_in(); ?></li>-->
                  <li><a href="<?php echo admin_url(); ?>" id="moreicons" class="tool_btn"></a></li>
                  <!--<li><div id="marker" class="tool_btn" onclick="markerButtonFunctions('move');"></div></li>-->
                  </ul>
                  </td>
                 </tr>
              </table>
            </div>
            </div>         
    </div><!--End .slideout-->
    </div><!--End #relative_wrap-->
    </div><!--End #rmap_console-->
    

<?php get_footer(); ?>