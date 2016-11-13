<?php

function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );
function my_login_logo() { ?>
    <style type="text/css">
	    #loginform{
	border: 1px solid #666;
	-webkit-box-shadow: 0px 0px 10px rgba(50, 50, 50, 0.49);
    -moz-box-shadow:    0px 0px 10px rgba(50, 50, 50, 0.49);
     box-shadow:         0px 0px 10px rgba(50, 50, 50, 0.49);			
			}
	    body.login {
	background: #F3F8FB;
			}
        body.login div#login h1 a {
			width: 256px;
			height: 124px;
			margin:auto;
            padding-bottom: 30px;
			background: #F3F8FB url(<?php echo get_bloginfo( 'template_directory' ) ?>/img/ari_aclu_logo.png) no-repeat;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );



 
function ajax_check_user_logged_in() {
    return is_user_logged_in()?'yes':'no';
    die();
}
add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');
add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');



function hide_admin_bar_from_front_end(){
  if (is_blog_admin()) {
    return true;
  }
    return false;
}
add_filter( 'show_admin_bar', 'hide_admin_bar_from_front_end' );

	/**
	 * Starkers functions and definitions
	 *
	 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
	 *
 	 * @package 	WordPress
 	 * @subpackage 	Starkers
 	 * @since 		Starkers 4.0
	 */

	/* ========================================================================================================================
	
	Required external files
	
	======================================================================================================================== */

	require_once( 'external/starkers-utilities.php' );

	/* ========================================================================================================================
	
	Theme specific settings

	Uncomment register_nav_menus to enable a single menu with the title of "Primary Navigation" in your theme
	
	======================================================================================================================== */

	add_theme_support('post-thumbnails');
	
	// register_nav_menus(array('primary' => 'Primary Navigation'));

	/* ========================================================================================================================
	
	Actions and Filters
	
	======================================================================================================================== */

	add_action( 'wp_enqueue_scripts', 'starkers_script_enqueuer' );

	add_filter( 'body_class', array( 'Starkers_Utilities', 'add_slug_to_body_class' ) );

	/* ========================================================================================================================
	
	Custom Post Types - include custom post types and taxonimies here e.g.

	e.g. require_once( 'custom-post-types/your-custom-post-type.php' );
	
	======================================================================================================================== */



	/* ========================================================================================================================
	
	Scripts
	
	======================================================================================================================== */

	/**
	 * Add scripts via wp_head()
	 *
	 * @return void
	 * @author Keir Whitaker
	 */

	function starkers_script_enqueuer() {
		wp_register_script( 'site', get_template_directory_uri().'/js/site.js', array( 'jquery' ) );
		wp_enqueue_script( 'site' );

		wp_register_style( 'screen', get_stylesheet_directory_uri().'/style.css', '', '', 'screen' );
        wp_enqueue_style( 'screen' );
	}	

	/* ========================================================================================================================
	
	Comments
	
	======================================================================================================================== */

	/**
	 * Custom callback for outputting comments 
	 *
	 * @return void
	 * @author Keir Whitaker
	 */
	function starkers_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>	
		<li>
			<article id="comment-<?php comment_ID() ?>">
				<?php echo get_avatar( $comment ); ?>
				<h4><?php comment_author_link() ?></h4>
				<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
				<?php comment_text() ?>
			</article>
		<?php endif;
	}

/* ========================================================================================================================
	
	custom post type for map
	
	======================================================================================================================== */	
	

	
/* 
========================================================================================================================
	
	msavedata - Precursor app to a following plugin to save each marker point added on click to the database.
	
	======================================================================================================================== */


function mSaveData($data){
	
	global $wpdb;
	global $user_ID;
	
$new_post = array(
    'post_title' => '',
    'post_content' => '',
    'post_status' => 'publish',
    'post_date' => date('Y-m-d H:i:s'),
    'post_author' => $user_ID,
    'post_type' => 'markers',
	'comment_status' => 'closed'
);
$post_id = wp_insert_post($new_post);	
	
$table = 'wp_yem6sxQaW_pods_markers';

$wpdb->insert( 
	$table, 
	array(
	    'id'     => $post_id, 
		'maplat' => $data['mlatdata'], 
		'maplng' => $data['mlngdata'],
		'cat' => $data['catdata'] 
	)
);
if($data['mlatdata'] !== $data['updtmlatdata'] || $data['mlngdata'] !== $data['updtmlngdata']){
$wpdb->update(
     $table, 
     array(
	    'maplat' => $data['updtmlatdata'], 
		'maplng' => $data['updtmlngdata']
	 )
);

}
	
    //Use the following debug code to see what information is available to you

  }




	/* ========================================================================================================================
	
	mgetdata - Precursor app to a following plugin to grab the marker point from database and apply them to the map
	
	======================================================================================================================== */



function mGetData(){
	
	global $wpdb;

function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

header("Content-type: application/xhtml+xml");

//$query = "SELECT * FROM markers";

$query = "SELECT  posts.ID,
                  posts.post_content, 
				  posts.post_title,
				  posts.post_type,
				  posts.post_status,
				  markers.id,
				  markers.maplat,
				  markers.maplng,
				  DATE_FORMAT(markers.mapdate, '%M %d, %Y') AS mapdate,
				  markers.maplocation,
				  markers.infowin_left,
				  markers.infowin_right,
				  markers.cat,
				  markers.marker_number
           FROM   wp_yem6sxQaW_posts posts, wp_yem6sxQaW_pods_markers markers 
		   WHERE  posts.ID = markers.id 
		   AND    posts.post_type = 'markers' 
		   AND    posts.post_status = 'publish'";
		   
				   		   

$output_type     = ARRAY_A;


$results   = $wpdb->get_results($query, $output_type);


/*if (!$results) {
  echo 'Invalid Query';
}*/

/*echo '<pre>';
var_dump($results);
echo '</pre>';*/

//echo $results2[0]['meta_value'];

$output = '<markers>';

// Start XML file, echo parent node

// Iterate through the rows, printing XML nodes for each
foreach($results as $row){
   	/*$marker_latlng[] = get_post_meta( $row['ID'], 'aclu_az_marker_coords', true );    
foreach($marker_latlng as $latlng){}*/
	if($row['ID'] == $row['id']){
  // ADD TO XML DOCUMENT NODE
	//$marker_latlng[] = get_post_meta( $row['ID'], 'aclu_az_marker_coords', true );
    $output .= '<marker name="' . parseToXML($row['post_title']) . '" '; /*wp_posts title*/
	$output .= 'postid="' . $row['id'] . '" ';	
	$output .= 'location="' . htmlspecialchars($row['maplocation']) . '" ';
	$output .= 'date="' . $row['mapdate'] . '" ';
    $output .= 'lat="' . $row['maplat'] . '" ';         /*postmeta - aclu_az_marker_coords*/
	$output .= 'marker_number="' . $row['marker_number'] . '" ';         /*postmeta - aclu_az_marker_coords*/
    $output .= 'lng="' . $row['maplng'] . '" ';         /*postmeta - aclu_az_marker_coords*/
    $output .= 'type="' . $row['post_type'] . '" '; 	/*postmeta - aclu_az_marker_coords*/
	$output .= 'cat="' . $row['cat'] . '" '; 	/*postmeta - aclu_az_marker_coords*/
    //$output .= 'image="' . $row['image'] . '" '; 				   /*not sure*/
    //$output .= 'thumb="' . $row['thumb'] . '" '; 				   /*not sure*/
    //$output .= 'icon="' . $row['icon'] . '" '; 					   /*possibly deprecate*/
    $output .= 'infowin_left="' . htmlspecialchars($row['infowin_left']) . '" ';
	$output .= 'infowin_right="' . htmlspecialchars($row['infowin_right']) . '" ';		   
    $output .= '/>';
	}
      
}


// End XML file
    $output .= '</markers>';

    echo $output;

// Start XML file, echo parent node
    //$output = '<markers>';
	
	

}









	
	