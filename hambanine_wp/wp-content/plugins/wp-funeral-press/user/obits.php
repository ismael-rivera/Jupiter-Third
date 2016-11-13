<?php
class wpfh_user_obits
{
	 function postedOn($id,$type){
		  
		 	global $wpdb;
		 
		 	$r = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix ."wpfh_obits  where id = ".$wpdb->escape($id)."", ARRAY_A);	
			
			
			return stripslashes($r[0][$type]);
	 }
	  function postedBy($id,$type){
		 
		 	global $wpdb;
			
			 $user_data = get_userdata($id);
			 
			 
			 return $user_data->$type;
		 
	 }
	 
	function youtube($url){
		
	
    $id=0;
    // we get the unique video id from the url by matching the pattern
    preg_match("/v=([^&]+)/i", $url, $matches);
    if(isset($matches[1])) $id = $matches[1];
    if(!$id) {
        $matches = explode('/', $url);
        $id = $matches[count($matches)-1];
    }
    // this is your template for generating embed codes
    $code = '<div id="img_wrapper"><object width="640" height="458"><param name="movie" value="http://www.youtube.com/v/{id}&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/{id}&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object></div>';

    // we replace each {id} with the actual ID of the video to get embed code for this particular video
    $code = str_replace('{id}', $id, $code);

    return $code;
	}
    function obitName()
    {
        global $wpdb;
        
        
        $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "wpfh_obits where id = " . $wpdb->escape($_GET['id']) . "", ARRAY_A);
        if ($r[0]['maiden'] != "") {
            $maiden = ' (' . stripslashes($r[0]['maiden']) . ') ';
        } else {
            $maiden = '';
        }
        $title = '' . stripslashes($r[0]['first_name']) . ' ' . stripslashes($r[0]['middle']) . ' ' . stripslashes($r[0]['last_name']) . ' ' . $maiden . ' | ';
        return $title;
        
    }
    
    function search(){
		 global $wpdb, $post;
		
		return '<div id="wpfh_search">
			<form action="'.get_permalink( get_option('wpfh_display_page') ).'" method="post">
				<h3>'.__("Search","sp-wpfh").' '.get_option('wpfh_obit_name_plural').'</h3>
				'.__("First Name","sp-wpfh").': <input type="text" name="first_name">   '.__("Last Name","sp-wpfh").': <input type="text" name="last_name"> '.__("Death Date","sp-wpfh").': <input type="text" name="date" class="datepicker"> 
				<div style="text-align:right;padding-top:6px;"><input type="submit" name="search-obits" value="Search!"></div>
					
				</form></div>';
	}
    
    function view()
    {
        global $wpdb, $post;
        
        	
        
        if ($_GET['id'] != "") {
            $r                = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "wpfh_obits where id = " . $wpdb->escape($_GET['id']) . "", ARRAY_A);
            $name             = '' . stripslashes($r[0]['first_name']) . ' ' . stripslashes($r[0]['middle']) . ' ' . stripslashes($r[0]['last_name']) . ' ' . $maiden . '';
            $r[0]['fullname'] = $name;
            add_filter('wp_title', array(
                $this,
                'obitName'
            ));
            if ($r[0]['maiden'] != "") {
                $maiden = ' (' . stripslashes($r[0]['maiden']) . ') ';
            } else {
                $maiden = '';
            }
            
            if ($r[0]['vet'] == 1) {
                $class = 'wpfh_obit_vet';
                $vet   = '<div class="wpfh_veteran">U.S. Veteran</div>';
            } else {
                $vet   = '';
                $class = '';
            }
		
			
			
			if($r[0]['birth_date'] != "0000-00-00"){
			$birth_date = '' . date("F j, Y", strtotime($r[0]['birth_date'])) . ' - ';
			}else{
			$birth_date = '';	
			}
            $html .= '<div id="wpfh_main_obit">
						<div class="iro_obit_header' . $class . '">
						<img class="iro_obit_photo " style="float: right;" src="' . content_url() . '/plugins/wp-funeral-press/thumbs.php?src=' .get_real_image_path ( $r[0]['photo'] ). '&w=100&h=150" style="float:left;margin:5px">
						</div>
						<div class="iro_added_header">' .							
						'<div class="iro_reg_symb"></div>
						 <h2 class="iro_h001">' . $name . '</h2>' . 
						'<p class="wpfh_obit_date">'.	$birth_date .'' . date("F j, Y", strtotime($r[0]['death_date'])) . '</p>' . $vet . 

						'</div>';
            
            $html .= $this->tabs();
            
            
            
            
            //show obit
            if ($_GET['f'] == 'obit' or $_GET['f'] == '') {
				
				if( $r[0]['photo']  == ""){ $r[0]['photo'] =get_option('wpfh_obit_default_pic');}
			
                $html .= '										
						<p>'. stripslashes($r[0]['obituary']) . '</p>' . '<a class="iro_a001" href="'.get_permalink( get_option('wpfh_display_page') ).'?f=obits">Back To '.get_option('wpfh_obit_name_plural').'</a>';
            }
            
            if ($_GET['f'] == 'guestbook') {
                $html .= $this->posting('guestbook', $r);
            }
            if ($_GET['f'] == 'service' && class_exists('wpfh_cem_user')) {
				 global $wpfh_cem_user;
                 $html .= $wpfh_cem_user->obit_service($r[0]['placeofservice'],$r[0]['service_time']);
            }
             if ($_GET['f'] == 'location' && class_exists('wpfh_cem_user')) {
				 global $wpfh_cem_user;
                 $html .= $wpfh_cem_user->obit_location($r[0]['funeralhome'],$r[0]['visitation_time']);
            }
            
            $html .= '
						
						</div>';
            
            
            
        } else {
			
			
			
			
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
			
			
			
			
			$query = "SELECT * FROM  " . $wpdb->prefix . "wpfh_obits WHERE id != '' ".$search." ORDER  by death_date desc";
			$pagination = new Pagination();
			if (isset($_GET['pagenum'])){   $page = (int) $_GET['pagenum'];}else{ $page = 1; }
			$pagination->setLink("?f=obits&pagenum=%s");
			$pagination->setPage($page);
			$pagination->setSize(get_option('wpfh_obit_display_num'));
			$pagination->setTotalRecords(count($wpdb->get_results($query, ARRAY_A)));
	
	
			
			
            $r = $wpdb->get_results("".$query." ".$pagination->getLimitSql()."", ARRAY_A);
            
		
		
			
            $html .= '<div id="wpfh_obits">';
			
				if(get_option('wpfh_enable_search') == 1){
			$html .= $this->search();	
			}
			
			
			if(count($r) == 0){
				$html .= '<div class="wpfh_error">'.sprintf(__("No %s Found","sp-wpfh"),get_option('wpfh_obit_name_plural')).'</div>';
			}else{
			
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
			  
			  if(get_option('wpfh_obit_style') == 'block'){
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
										<a href="'.wpfh_obit_page($r[$i]['id']).'">View More</a>
										</div>
										</div>
										<div style="clear:both"></div>
									</div>';
									
									
			//end block mode
			
			  }elseif(get_option('wpfh_obit_style') == 'list'){
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
			 }elseif(get_option('wpfh_obit_style') == 'thumbnails'){
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
			}
			$html .= $pagination->create_links();
            $html .= '</div>';
            
        }
        
        return $html;
        
    }
    
    function posting($type, $obit)
    {
        global $wpdb, $current_user;
        $f = $_GET['f'];
        $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "wpfh_posts where oid =  " . $wpdb->escape($_GET['id']) . "   and approved = 1 order by date desc", ARRAY_A);
        
        
        if ($_GET['m'] == 'add') {
            //add post	
            
            //check to see if logged in
            wpfh_login();
            //continue
            
            
            
            switch ($f) {
                
                case "guestbook":
                    
                    if ($_POST['save-post'] != "") {
						
						
						$mode = $_POST['mode'];
                        
						switch($mode){
							case"guestbook":
							$insert['type'] = 'guestbook';
							$insert['content']   = $_POST['message'];
							break;
							
							case "photo":
							$insert['type'] = 'photo';
							
								if($_FILES['photo']['name'] != ""){			
								$photo = wp_upload_bits($_FILES['photo']["name"], null, file_get_contents($_FILES['photo']["tmp_name"]));		
								$photo['desc'] = $_POST['photo-message'];								
								$insert['content'] = 	serialize($photo);	
								}	
								
							break;
							
							case"youtube":
							$insert['type'] = 'youtube';
								$youtube['url'] = $_POST['youtube'];
								$youtube['desc'] = $_POST['youtube-message'];
							$insert['content']   = serialize($youtube);
							break;
						}
                        $insert['uid']       = $current_user->ID;
                        $insert['oid']       = $_GET['id'];
                       
                        $insert['date']      = time();
                        $insert['approved']  = 0;
                        $insert['anonymous'] = $_POST['anonymous'];
                        
                        $wpdb->insert("" . $wpdb->prefix . "wpfh_posts", $insert);
						
						
						
					
						 
						
						
						if(get_option('wpfh_email_user') != ""){
						$email_content = str_replace("[obit]",''.$this->postedOn($insert['oid'] ,'first_name').' '.$this->postedOn($insert['oid'],'last_name').'',stripslashes(get_option('wpfh_email_user')));
						$email_content = str_replace("[link]",''. get_permalink(get_option('wpfh_display_page') ).'?f=guestbook&id='.$insert['oid'].'', $email_content);
						$email_content = str_replace("[user]",''.$this->postedBy($insert['uid'],'display_name').'',$email_content);
						wpfh_email($current_user->user_email,stripslashes(get_option('wpfh_email_user_subject')),$email_content );
						unset($email_content);
						}
						
						if(get_option('wpfh_email_admin') != ""){
						$email_content = str_replace("[obit]",''.$this->postedOn($insert['oid'] ,'first_name').' '.$this->postedOn($insert['oid'],'last_name').'',stripslashes(get_option('wpfh_email_admin')));
						$email_content = str_replace("[link]",''. get_permalink(get_option('wpfh_display_page') ).'?f=guestbook&id='.$insert['oid'].'', $email_content);
						$email_content = str_replace("[user]",''.$this->postedBy($insert['uid'],'display_name').'',$email_content);
						$email_content = str_replace("[approve_link]",''.get_settings('siteurl').'/wp-admin/admin.php?page=wpfh-guestbook',$email_content);
						wpfh_email(get_option('admin_email'),stripslashes(get_option('wpfh_email_admin_subject')),$email_content );
						unset($email_content);
						}
						
						
						
						
                        wpfh_redirect(''.wpfh_obit_page($_GET['id']).'&f=guestbook&m=add&thankyou=1');
                    }
                    if ($_GET['thankyou'] == 1) {
                        $html .= '<div class="wpfh_modal">
								<a href="'.wpfh_obit_page($_GET['id']).'&f=guestbook">'.sprintf(__("Thank you for adding your guestbook message, your message is now awaiting approval. Once approved you will receive an email and the message will appear on %s's guestbook page. Click here to return to the guestbook.","sp-wpfh"),$obit[0]['first_name'] ).'</a>
								</div>';
                        
                    } else {
                        $html .= '<div class="wpfh_modal"><form action="" method="post"  enctype="multipart/form-data" class="wpfh_upload_form">
							
							
							<div id="wpfh_message_icons">
	<a href="javascript:wpfh_message_button(\'wpfh_message_button\');" class="wpfh_message_button selected"><img src=" ' . content_url() . '/plugins/wp-funeral-press/images/comment.png"> '.__("Message","sp-wpfh").'</a>  
	';
	if(class_exists('wpfh_cem_user')){	
	$html .='<a href="javascript:wpfh_message_button(\'wpfh_photo_button\');" class="wpfh_photo_button"><img src=" ' . content_url() . '/plugins/wp-funeral-press/images/picture.png"> '.__("Photo","sp-wpfh").'</a>  
	<a href="javascript:wpfh_message_button(\'wpfh_youtube_button\');" class="wpfh_youtube_button"><img src=" ' . content_url() . '/plugins/wp-funeral-press/images/youtube.png"> '.__("Youtube","sp-wpfh").'</a>';
	}
	
	$html .='
	<div style="clear:both"></div></div>
							
							<input type="hidden" name="mode"  id="wpfh_mode" value="guestbook">
							<div class="wpfh_message_form_fields wpfh_message_button_form">
							<p><strong>'.sprintf(__("Leave a message or tribute for %s","sp-wpfh"),$obit[0]['first_name']).'</strong></p>
							<div class="wpfh_message_form_holder">
							<textarea name="message" style="width:98%;height:170px" id="wpfh_message_textarea"></textarea>
							</div>
							</div>
							 
							<div class="wpfh_message_form_fields wpfh_photo_button_form" style="display:none">
							<p><strong>'.sprintf(__("Upload your photo  for %s","sp-wpfh"),$obit[0]['first_name']).'</strong></p>
							<div  class="wpfh_message_form_holder">
							
							'.__("Photo","sp-wpfh").': <input type="file" name="photo" id="wpfh_message_file"><br>
							<br />
'.__("Write something about this photo","sp-wpfh").'
							<textarea style="width:100%;height:70px" name="photo-message"></textarea>
							</div>
							</div>
							
							<div class="wpfh_message_form_fields wpfh_youtube_button_form"  style="display:none">
							<p><strong>'.__("Put the full youtube link below","sp-wpfh").'</strong></p>
							<div class="wpfh_message_form_holder">
							
							'.__("Youtube Link","sp-wpfh").': <em>example: http://www.youtube.com/watch?v=cEhzmhx9jt8</em><input type="text" name="youtube" style="width:95%" id="wpfh_message_youtube">			<br />
'.__("Write something about this video","sp-wpfh").'
							<textarea style="width:100%;height:70px" name="youtube-message"></textarea>

							</div>
							</div>
							
							<p style="font-size:16px"><input type="checkbox" name="anonymous" value="1"> '.__("Check to hide your name","sp-wpfh").'</p>
							<div style="text-align:right"><input type="submit" name="save-post" value="'.__("Add Message","sp-wpfh").'" style="font-size:1.0em"></div>
							
							
							</form></div>';
                    }
                    
                    break;
                    
            }
            
            
            
            
            
            
            
            
        } else {
            //list postings	
            
            if (count($r) == 0) {
                //no posts exist
				if( $obit[0]['photo']  == ""){ $obit[0]['photo'] =get_option('wpfh_obit_default_pic');}	
                $html .= '<div class="wpfh_modal">
								<img src=" ' . content_url() . '/plugins/wp-funeral-press/thumbs.php?src=' .get_real_image_path ( $obit[0]['photo'] ). '&w=120&h=180" style="float:left;margin-right:10px;"><a href="'.wpfh_obit_page($_GET['id']).'&f=guestbook&m=add">'.sprintf(__("%1$s Does not have any %2$s posts. Be the first to add one by clicking here.","sp-wpfh"),$obit[0]['first_name'],$type).'</a>
								<h1>
					
								</div>';
                
            } else {
                //display postings		
                
                
                switch ($f) {
                    
                    case "guestbook":
                        $html .='<div style="margin:5px;font-size:1.3em;">
						<a href="'.wpfh_obit_page($_GET['id']).'&f=guestbook&m=add">
						<img src="' . content_url() . '/plugins/wp-funeral-press/images/add.png"> '.sprintf(__("Add Message for %s","sp-wpfh"),$obit[0]['first_name']).'
						</a></div>';
                        for ($i = 0; $i < count($r); $i++) {
                            $user_data = get_userdata($r[$i]['uid']);
                            if ($r[$i]['anonymous'] == 1) {
                                $posting_name = ''.__("Anonymous","sp-wpfh").'';
                            } else {
                                $posting_name = '' . $user_data->user_nicename . '';
                            }
                            $html .= '<div class="wpfh_posting">
												<div class="wpfh_posting_left">
												<p><strong>'.__("Posted by","sp-wpfh").':</strong> <br />
' . $posting_name . '</p>
												<p><em><strong>'.__("Posted on","sp-wpfh").':</strong><br />
 ' . date("F j, Y", $r[$i]['date']) . '</em></p>
												</div>
												<div class="wpfh_posting_right">';
												
												if($r[$i]['type'] == 'guestbook'){
												$html .='' . stripslashes($r[$i]['content']) . '';	
												}
												if($r[$i]['type'] == 'photo'){
												unset($photo);
												$photo = unserialize(stripslashes($r[$i]['content']));	
											
												$html .='<img src="'.$photo['url'].'" /><p>'.$photo['desc'].'</p>';	
												}
													if($r[$i]['type'] == 'youtube'){
														unset($youtube);
												$youtube = unserialize(stripslashes($r[$i]['content']));	
														
														$html .= $this->youtube($youtube['url']);
														$html .='<p>'.$youtube['desc'].'</p>';
													}
												$html .='</div>
												<div style="clear:both"></div>
									
											</div>';
                            unset($user_data);
                        }
                        
                        break;
                        
                        
                        
                }
            }
            
            
        }
        
        
        
        return $html;
        
    }
    function tabs()
    {
        $id = $_GET['id'];
        $f  = $_GET['f'];
        if ($f == 'obit' or $f == "") {
            $obitselected = ' id="current" ';
        }
        if ($f == 'guestbook') {
            $guestbookselected = ' id="current" ';
        }
        if ($f == 'photoalbum') {
            $photoalbumselected = ' id="current" ';
        }
        if ($f == 'tributes') {
            $tributeselected = ' id="current" ';
        }
        $html .= '<div id="wpfh-header">
  <ul>
    <li ' . $obitselected . '><a href="'.wpfh_obit_page( $id).'">'.get_option('wpfh_obit_name').'</a></li>';
	if(get_option('wpfh_disable_guestbook') != 1){
   $html .=' <li ' . $guestbookselected . '><a href="?f=guestbook&id=' . $id . '">Guestbook</a></li>';
	}
 
 if(class_exists('wpfh_cem_user')){	
	
	global $wpfh_cem_user;	
	$html .= $wpfh_cem_user->user_menu();
	 
 }
 if(get_option('wpfh_order_flowers') != ""){
	 $html .=' <li ><a href="'.get_option('wpfh_order_flowers').'" target="_blank">'.__("Order Flowers","sp-wpfh").'</a></li>';
	 
 }
  $html .='</ul>
  <div  class="wpfh-clear"></div>
</div>';
        
        return $html;
        
    }
    
    function id(){
	    global $post;
echo $post->ID;	
	}
    function inject()
    {
        global $wpdb;
        global $post;

        
        $post_data = get_post($post->ID, ARRAY_A);
        
        
        if ($post_data['ID'] == get_option('wpfh_display_page')) {
            return $this->view();
        } else {
			$content = $post->post_content;
			
			$content = str_replace(']]>', ']]&gt;', $content);
			
            return wpautop($content);
        }
    }
    
   function remove_title(){
	 global $wpdb;
	     $post_data = get_post($post->ID, ARRAY_A);
	   if ($post_data['ID'] == get_option('wpfh_display_page')) {
          	return ' ';   
        } else {
            return $post_data['the_title'];
        }
	 

   }
   

}


$wpfh_user_obits = new wpfh_user_obits;

echo $wpfh_user_obits->id();
if ($_GET['id'] != "") {
    add_filter('wp_title', array(
        $wpfh_user_obits,
        'obitName'
    ));
}


add_shortcode( 'funeralpress',  array($wpfh_user_obits, 'view') );	

?>