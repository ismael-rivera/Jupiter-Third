<?php
class wpfh_user_widgets{

	function latest_obits($args){
			global $wpdb ;
	global $user_ID;
	global $current_user;
		$data = get_option('wpfh_latest_obits_widget');
	

		$atts['style'] = $data['option2'];
		$atts['howmany'] = $data['option3'];
		
		
		if($data['option1'] != ""){
			
		echo '<h2 class="widget-title">	'.$data['option1'].'</h2>';
		}
		if($atts['howmany'] != ""){
			$limit = $atts['howmany'];
			}else{
			$limit = get_option('wpfh_obit_display_num');
			}
			
			
			$query = "SELECT * FROM  " . $wpdb->prefix . "wpfh_obits WHERE id != '' ".$search." ORDER  by death_date desc limit ".$limit."";
			$r = $wpdb->get_results($query, ARRAY_A);
            
		         for ($i = 0; $i < count($r); $i++) {
					 
					 	if( $r[$i]['photo']  == ""){ $r[$i]['photo'] =get_option('wpfh_obit_default_pic');}
				
                if ($r[$i]['vet'] == 1) {
                    $class = ' wpfh_obit_vet';
                    $vet   = '<br><div style="text-align:center;font-weight:bold">Veteran</div>';
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
										<a href="'.wpfh_obit_page($r[$i]['id']).'""><img src=" ' . content_url() . '/plugins/wp-funeral-press/thumbs.php?src=' .get_real_image_path ( $r[$i]['photo'] ). '&w=100&h=150"></a>' . $vet . '
										</div>
										<div class="wpfh_obit_obit">
										<p class="wpfh_obit_title"><a href="'.wpfh_obit_page($r[$i]['id']).'"">' . stripslashes($r[$i]['first_name']) . ' ' . stripslashes($r[$i]['middle']) . ' ' . stripslashes($r[$i]['last_name']) . ' ' . $maiden . '</a></p>
										<p class="wpfh_obit_date">'.$birth_date.'' . date("F j, Y", strtotime($r[$i]['death_date'])) . '</p>
										' . substr(stripslashes(strip_tags($r[$i]['obituary'])), 0, 250) . '...
										<div class="wpfh_obit_button">
										<a href="'.wpfh_obit_page($r[$i]['id']).'"">View More</a>
										</div>
										</div>
										<div style="clear:both"></div>
									</div>';
									
									
			//end block mode
			
			  }elseif(  $atts['style'] == 'list'){
			//list mode
			  $html .= '<div class="wpfh_obit_list' . $class . '">			
			  				<div class="wpfh_obit_list_name">
							<strong><a href="'.wpfh_obit_page($r[$i]['id']).'"">' . stripslashes($r[$i]['first_name']) . ' ' . stripslashes($r[$i]['middle']) . ' ' . stripslashes($r[$i]['last_name']) . ' ' . $maiden . '</a></strong>
							</div>
							
							<div class="wpfh_obit_list_dates">
						<a href="'.wpfh_obit_page($r[$i]['id']).'"">'.$birth_date.'' . date("F j, Y", strtotime($r[$i]['death_date'])) . '</a>
							</div>
			  			
				 <div style="clear:both"></div>
			</div>';
			
			///end list mode	
			 }elseif(  $atts['style'] == 'thumbnails'){
			///thumbnail mode
			
			  $html .= '<div class="wpfh_obit_thumbnail' . $class . '">			
			  				<div class="wpfh_obit_thumbnail_name">
							<strong><a href="'.wpfh_obit_page($r[$i]['id']).'"">' . stripslashes($r[$i]['first_name']) . ' ' . stripslashes($r[$i]['middle']) . ' ' . stripslashes($r[$i]['last_name']) . ' ' . $maiden . '</a></strong>
							</div>
							<div class="wpfh_obit_thumbnail_image">
								<a href="'.wpfh_obit_page($r[$i]['id']).'""><img src=" ' . content_url() . '/plugins/wp-funeral-press/thumbs.php?src=' .get_real_image_path ( $r[$i]['photo'] ). '&w=100&h=120"></a>
							</div>
							<div class="wpfh_obit_thumbnail_dates">
						<a href="'.wpfh_obit_page($r[$i]['id']).'"">' . date("F j, Y", strtotime($r[$i]['death_date'])) . '</a>
							</div>
			  			
				 <div style="clear:both"></div>
			</div>';
			
			
			// end thumnail mode	 
			
			
			 }
			
								
            }
			
            $html .= '</div>';
		
		echo  $html;
		
	}
	
	
    function register_latest_obits_control(){
		
			$data = get_option('wpfh_latest_obits_widget');
		 
		  echo '<p><label>'.__("Title","sp-wpfh").' <input name="wpfh_latest_name"
		type="text" value="'.$data['option1'].'" /></label></p>
		  <p><label>'.__("Style","sp-wpfh").' <select name="wpfh_latest_style">';
		  
		  if($data['option2'] != ""){
			  echo '<option value="'.$data['option2'].'" selected>'.$data['option2'].'</option>';
		  }
		  echo '<option value="list">'.__("List","sp-wpfh").'</option><option value="thumbnails">'.__("Thumbnails","sp-wpfh").'</option><option value="block">'.__("Blocks","sp-wpfh").'</option></select></label></p>
		  <p><label>'.__("How many to display?","sp-wpfh").' <input name="wpfh_latest_display"
		type="text" value="'.$data['option3'].'" /></label></p>
		  ';
		 
		   if (isset($_POST['wpfh_latest_name'])){
			$data['option1'] = attribute_escape($_POST['wpfh_latest_name']);
			$data['option2'] = attribute_escape($_POST['wpfh_latest_style']);
			$data['option3'] = attribute_escape($_POST['wpfh_latest_display']);
			update_option('wpfh_latest_obits_widget', $data);
		  }
  }
  
    function search_control(){
		
			$data = get_option('wpfh_search_widget');
		 
		 
		 if($data['option3'] == 1){ $checked1 = 'checked="checked"'; }
		  if($data['option4'] == 1){ $checked2 = 'checked="checked"'; }
		   if($data['option5'] == 1){ $checked3 = 'checked="checked"'; }
		 
		  echo '<p><label>'.__("Title","sp-wpfh").' <input name="wpfh_search_title" type="text" value="'.$data['option1'].'" /></label></p>
		  		<p><label>'.__("Text before the form","sp-wpfh").' <input name="wpfh_search_text" type="text" value="'.$data['option2'].'" /></label></p>
				<p><label>'.__("Enable First Name Field?","sp-wpfh").' <input name="wpfh_search_fn" type="checkbox" value="1" '.$checked1.'  /></label></p>
				<p><label>'.__("Enable Last Name Field?","sp-wpfh").' <input name="wpfh_search_ln" type="checkbox" value="1"'.$checked2.' /></label></p>
				<p><label>'.__("Enable Burial Date Field?","sp-wpfh").' <input name="wpfh_search_bd" type="checkbox" value="1"  '.$checked3.'/></label></p>
		  		';
		  
		 
		   if (isset($_POST['wpfh_search_title'])){
			$data['option1'] = attribute_escape($_POST['wpfh_search_title']);
			$data['option2'] = attribute_escape($_POST['wpfh_search_text']);
			if($_POST['wpfh_search_fn'] == ""){ $data['option3'] = 0; }else{ $data['option3'] = 1;}
			if($_POST['wpfh_search_ln'] == ""){ $data['option4'] = 0; }else{ $data['option4'] = 1;}
			if($_POST['wpfh_search_bd'] == ""){ $data['option5'] = 0; }else{ $data['option5'] = 1;}
			update_option('wpfh_search_widget', $data);
		  }
  }
  
  function search_widget(){
	  
		global $wpdb ;
	global $user_ID;
	global $current_user;
		$data = get_option('wpfh_search_widget');
		
		echo '<h2 class="widget-title">	'.$data['option1'].'</h2>';
		if($data['option2'] != ""){echo '<p>'.$data['option2'].'</p>';}
		
echo'<div id="wpfh_search_widget"><form action="'.wpfh_obit_page().'" method="post">';
	
				if($data['option3'] == 1){
				echo '<label>'.__("First Name","sp-wpfh").':</label> <input type="text" name="first_name">';	
				}
				if($data['option4'] == 1){
				echo '<label>'.__("Last Name","sp-wpfh").':</label> <input type="text" name="last_name">';	
				}
				if($data['option5'] == 1){
				echo '<label>'.__("Death Date","sp-wpfh").':</label> <input type="text" name="date" class="datepicker">';	
				}
				     
				echo '<div style="text-align:right;padding-top:6px;clear:both"><input type="submit" name="search-obits" value="'.__("Search","sp-wpfh").'"></div>
					
				</form></div>';
			
	  
  }
	function register_latest_obits(){
    register_sidebar_widget('FuneralPress Latest Obits', array('wpfh_user_widgets', 'latest_obits'));
	register_widget_control('FuneralPress Latest Obits', array('wpfh_user_widgets', 'register_latest_obits_control'));
	
	 register_sidebar_widget('FuneralPress Search Obits', array('wpfh_user_widgets', 'search_widget'));
	register_widget_control('FuneralPress Search Obits', array('wpfh_user_widgets', 'search_control'));
  }

}

add_action("widgets_init", array('wpfh_user_widgets', 'register_latest_obits'));
?>