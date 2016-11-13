<?php
class wpfh_user_shortcodes{

	
	function latest_obits($atts){
		
			global $wpdb ;
	global $user_ID;
	global $current_user;
		
		
	
				if($_POST['search-obits'] != ""){
					
					if($_POST['first_name'] != ""){
						$search .=' AND first_name like "%'.$_POST['first_name'].'%" ';	
					}
					if($_POST['last_name'] != ""){
						$search .=' AND last_name like "%'.$_POST['last_name'].'%" ';	
					}
					if($_POST['date'] != ""){
					$picked_date = strtotime($_POST['date']);
					
				
						
						
						$search .=' AND YEAR(death_date) = YEAR("'.$_POST['date'].'") AND MONTH(death_date) = MONTH("'.$_POST['date'].'")  ';	
					}	
			}
			
			
			
			if($atts['howmany'] != ""){
			$limit = $atts['howmany'];
			}else{
			$limit = get_option('wpfh_obit_display_num');
			}
			
			
			$query = "SELECT * FROM  " . $wpdb->prefix . "wpfh_obits WHERE id != '' ".$search." ORDER  by death_date desc limit ".$limit."";
			$r = $wpdb->get_results($query, ARRAY_A);
            
		
		
			
          if(wpfh_obit_page_id() == false){
	return '<p style="color:red;font-weight:bold">Please add the shortcode<strong> [funeralpress] </strong>on a page for this plugin to work properly.</p>';	
	}
			
				if($atts['search'] == 1){
			$html .='<div id="wpfh_search">
			<form action="'.wpfh_obit_page().'" method="post">
				<h3>'.__("Search","sp-wpfh").' '.get_option('wpfh_obit_name_plural').'</h3>
				'.__("First Name","sp-wpfh").': <input type="text" name="first_name">   '.__("Last Name","sp-wpfh").': <input type="text" name="last_name"> '.__("Death Date","sp-wpfh").': <input type="text" name="date" class="datepicker"> 
				<div style="text-align:right;padding-top:6px;"><input type="submit" name="search-obits" value="'.__("Search","sp-wpfh").'"></div>
					
				</form></div>';
			}
            for ($i = 0; $i < count($r); $i++) {
			
				if( $r[$i]['photo']  == ""){ $r[$i]['photo'] =get_option('wpfh_obit_default_pic');}
				
                if ($r[$i]['vet'] == 1) {
                    $class = ' wpfh_obit_vet';
                    $vet   = '<br><div style="text-align:center;font-weight:bold">'.__("Veteran","sp-wpfh").'</div>';
                } else {
                    $vet   = '';
                    $class = '';
                }
                
                if ($r[$i]['maiden'] != "") {
                    $maiden = ' (' . stripslashes($r[$i]['maiden']) . ') ';
                } else {
                    $maiden = '';
                }
              
			
			   if($r[$i]['birth_date'] != '0000-00-00'){
			$birth_date = '' . date("F j, Y", strtotime($r[$i]['birth_date'])) . ' - ';
			}else{
				$birth_date = '';	
			}
			 
			  if(  $atts['style'] == 'block' or   $atts['style'] == ''){
			  //block mode
			    $html .= '<div class="wpfh_obit' . $class . '">							
										<div class="wpfh_obit_image">
										<a href="'.wpfh_obit_page($r[$i]['id']).'"><img src=" ' . content_url() . '/plugins/wp-funeral-press/thumbs.php?src=' .get_real_image_path ( $r[$i]['photo'] ). '&w=100&h=150"></a>' . $vet . '
										</div>
										<div class="wpfh_obit_obit">
										<p class="wpfh_obit_title"><a href="'.wpfh_obit_page($r[$i]['id']).'">' . stripslashes($r[$i]['first_name']) . ' ' . stripslashes($r[$i]['middle']) . ' ' . stripslashes($r[$i]['last_name']) . ' ' . $maiden . '</a></p>
										<p class="wpfh_obit_date">'.$birth_date.'' . date("F j, Y", strtotime($r[$i]['death_date'])) . '</p>
										' . substr(stripslashes(strip_tags($r[$i]['obituary'])), 0, 250) . '...
										<div class="wpfh_obit_button">
										<a href="'.wpfh_obit_page($r[$i]['id']).'">'.__("View More","sp-wpfh").'</a>
										</div>
										</div>
										<div style="clear:both"></div>
									</div>';
									
									
			//end block mode
			
			  }elseif(  $atts['style'] == 'list'){
			//list mode
			  $html .= '<div class="wpfh_obit_list' . $class . '">			
			  				<div class="wpfh_obit_list_name">
							<a href="'.wpfh_obit_page($r[$i]['id']).'">' . stripslashes($r[$i]['first_name']) . ' ' . stripslashes($r[$i]['middle']) . ' ' . stripslashes($r[$i]['last_name']) . ' ' . $maiden . '</a>
							</div>
							
							<div class="wpfh_obit_list_dates">
						<a href="'.wpfh_obit_page($r[$i]['id']).'">'.$birth_date.'' . date("F j, Y", strtotime($r[$i]['death_date'])) . '</a>
							</div>
			  			
				 <div style="clear:both"></div>
			</div>';
			
			///end list mode	
			 }elseif(  $atts['style'] == 'thumbnails'){
			///thumbnail mode
			
			  $html .= '<div class="wpfh_obit_thumbnail' . $class . '">			
			  				<div class="wpfh_obit_thumbnail_name">
							<a href="'.wpfh_obit_page($r[$i]['id']).'">' . stripslashes($r[$i]['first_name']) . ' ' . stripslashes($r[$i]['middle']) . ' ' . stripslashes($r[$i]['last_name']) . ' ' . $maiden . '</a>
							</div>
							<div class="wpfh_obit_thumbnail_image">
								<a href="'.wpfh_obit_page($r[$i]['id']).'"><img src=" ' . content_url() . '/plugins/wp-funeral-press/thumbs.php?src=' .get_real_image_path ( $r[$i]['photo'] ). '&w=100&h=120"></a>
							</div>
							<div class="wpfh_obit_thumbnail_dates">
						<a href="'.wpfh_obit_page($r[$i]['id']).'">' . date("F j, Y", strtotime($r[$i]['death_date'])) . '</a>
							</div>
			  			
				 <div style="clear:both"></div>
			</div>';
			
			
			// end thumnail mode	 
			 }
			
								
            }
			
   
		
		return $html;
	}
	
}


add_shortcode( 'fp_latest_obits',  array('wpfh_user_shortcodes', 'latest_obits') );	
?>