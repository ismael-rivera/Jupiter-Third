<?php
/**
 * @KingSize 2011
 **/
?>
<?php get_header(); ?>

<!-- GOOGLE ANALYTICS -->
<?php include (TEMPLATEPATH . "/lib/google-analytics-input.php"); ?>
<!-- GOOGLE ANALYTICS -->
<!--<script type="text/javascript" src="<?php echo get_template_directory_uri();?>/js/background_slider.js"></script>-->

    <div class="scroller">
    <div class="scroller_wrap">
    <div class="marquee" id="mycrawler2">
    <?php echo do_shortcode('[fp_latest_obits style="slider"]'); ?>
    </div>
    <div class="marquee" id="mycrawler">
    <?php
	function hamba_loop_adverts(){	
    $upload_dir = wp_upload_dir();	
	$it = new DirectoryIterator( WP_CONTENT_DIR . "/uploads/slider_adverts");
	foreach($it as $file) {
		            if (!$it->isDot()) {
		            echo '<img src="'. $upload_dir['baseurl'] . '/slider_adverts/' . $file . '" />';
		            }
	}
}
 	
    ?>
    <?php hamba_loop_adverts(); ?>
            
    </div>
    </div>
    </div><!--scroller-->
    
   


		