<?php
class wpfh_settings{
	
		
	function menu(){
		
		global $wpfh_install;
		
		$submenu = '<a href="admin.php?page=wpfh-settings" class="button">'.__("View Settings","sp-wpfh").'</a> 
					<a href="admin.php?page=wpfh-settings-email" class="button">'.__("Email Text","sp-wpfh").'</a> 	
					<a href="admin.php?page=wpfh-custom-css" class="button">'.__("Custom CSS","sp-wpfh").'</a> 	
					
				 ';
		return $wpfh_install->topMenu(__("Settings","sp-wpfh"),$submenu);
	}
	
	function css(){
		global $wpdb;
		
			echo $this->menu();
		
		if(file_exists(''.get_template_directory().'/obits.css')){
		echo  '<p style="color:red;padding:10px;background-color:EFEFEF;border:1px dotted red;margin:10px">
		'.__("You are currently using custom css! If you wish to reset back to default css please delete the obits.css in your template directory","sp-wpfh").'
		</p>';	
		}
		
		 echo '<p style="color:red;padding:10px;background-color:EFEFEF;border:1px dotted red;margin:10px">'.__("If you would like to create custom css, copy the css code below. Open your template folder and create a file called obits.css. Paste the css in the file and upload. The plugin will detect the custom css file exists and will use that file instead.","sp-wpfh").'</p>';
		
		echo '
	
		
		
		<strong>'.__("Create this custom css file","sp-wpfh").': '.get_bloginfo( 'stylesheet_directory' ).'/obits.css</strong><br><textarea style="width:100%;height:600px">';
	include ''.ABSPATH.'wp-content/plugins/wp-funeral-press/css/style.css';
		echo '</textarea>';
	}
	
		function email(){
				global $wpdb;
				echo $this->menu();
			
				if($_POST['save-emails'] != ""){
		
	update_option('wpfh_email_admin', $_POST['wpfh_email_admin']);
	update_option('wpfh_email_user', $_POST['wpfh_email_user']);
	update_option('wpfh_email_user_approved', $_POST['wpfh_email_user_approved']);
	
	
	update_option('wpfh_email_admin_subject', $_POST['wpfh_email_admin_subject']);
	update_option('wpfh_email_user_subject', $_POST['wpfh_email_user_subject']);
	update_option('wpfh_email_user_approved_subject', $_POST['wpfh_email_user_approved_subject']);
	//end checkboxes
	
	echo '<div class="wpfh_message">'.__("Saved Your Settings","sp-wpfh").'!</div>';
	}
	
	
	
	echo ' <form method="post" action="admin.php?page=wpfh-settings-email" enctype="multipart/form-data">
	<table class="wp-list-table widefat fixed posts" cellspacing="0">

	 

    <tr>
    <td width="300"><strong>'.__("Admin posting email","sp-wpfh").'</strong><br><em>'.__("This is the admin email when a new posting has been entered.","sp-wpfh").'</em><br />
<br />
[obit] = '.__("Obit Name","sp-wpfh").'<br>
[link] = '.__("Obit Page","sp-wpfh").'<br>
[user] = '.__("Users Name","sp-wpfh").'<br>
[approve_link] = '.__("Approval Page","sp-wpfh").'</td>
    <td>'.__("Subject","sp-wpfh").': <input type="text" name="wpfh_email_admin_subject"" value="'.stripslashes(get_option('wpfh_email_admin_subject')).'" style="width:400px"><br><textarea name="wpfh_email_admin"  style="width:100%;height:200px">'.stripslashes(get_option('wpfh_email_admin')).'</textarea></td>
  </tr>
  <tr>
    <td width="300"><strong>'.__("User posting email","sp-wpfh").'</strong><br><em>'.__("This is the email which is sent to the user after they enter a posting","sp-wpfh").'.</em><br />
<br />
[obit] = '.__("Obit Name","sp-wpfh").'<br>
[link] = '.__("Obit Page","sp-wpfh").'<br>
[user] = '.__("Users Name","sp-wpfh").'<br>
<br>

</td>
    <td>'.__("Subject","sp-wpfh").': <input type="text" name="wpfh_email_user_subject"" value="'.stripslashes(get_option('wpfh_email_user_subject')).'"  style="width:400px"><br><textarea name="wpfh_email_user"  style="width:100%;height:200px">'.stripslashes(get_option('wpfh_email_user')).'</textarea></td>
  </tr>
 <tr>
    <td width="300"><strong>'.__("User approved email","sp-wpfh").'</strong><br><em>'.__("This is the email that is sent to the user after their posting has been approved","sp-wpfh").'.</em><br />
<br />
[obit] = '.__("Obit Name","sp-wpfh").'<br>
[link] = '.__("Obit Page","sp-wpfh").'<br>
[user] = '.__("Users Name","sp-wpfh").'<br>
<br></td>
    <td>'.__("Subject","sp-wpfh").': <input type="text" name="wpfh_email_user_approved_subject"" value="'.stripslashes(get_option('wpfh_email_user_approved_subject')).'"  style="width:400px"><br><textarea name="wpfh_email_user_approved"  style="width:100%;height:200px">'.stripslashes(get_option('wpfh_email_user_approved')).'</textarea></td>
  </tr>
   
  <tr><td></td><td><input type="submit" value="Save" name="save-emails"/></td></tr>
</table></form>';	
	
				
			
		}
	
function view(){
	
	global $wpdb;
	echo $this->menu();
	
	
	if($_POST['save-settings'] != ""){
		
	update_option('wpfh_display_page', $_POST['wpfh_display_page']);
	update_option('wpfh_order_flowers', $_POST['wpfh_order_flowers']);
	update_option('wpfh_obit_name', $_POST['wpfh_obit_name']);
	update_option('wpfh_obit_name_plural', $_POST['wpfh_obit_name_plural']);
	update_option('wpfh_obit_display_num', $_POST['wpfh_obit_display_num']);
	update_option('wpfh_obit_style', $_POST['wpfh_obit_style']);
		
		if($_FILES['wpfh_obit_default_pic']['name'] != ""){			
     	$wpfh_obit_default_pic = wp_upload_bits($_FILES['wpfh_obit_default_pic']["name"], null, file_get_contents($_FILES['wpfh_obit_default_pic']["tmp_name"]));		
		 update_option('wpfh_obit_default_pic',$wpfh_obit_default_pic['url']);
		}
	
	
	
	//checkboxes
	if($_POST['wpfh_enable_search'] == "1"){update_option('wpfh_enable_search','1' ); }else{update_option('wpfh_enable_search','0' );	}		
	//end checkboxes
	
	echo '<div class="wpfh_message">Saved Your Settings!</div>';
	}
	
	//get checkbox
	if(get_option('wpfh_enable_search') == 1){ $wpfh_enable_search = ' checked="checked" ';	}else{ $wpfh_enable_search = '  '; }
	
	echo ' <form method="post" action="admin.php?page=wpfh-settings" enctype="multipart/form-data">
	<table class="wp-list-table widefat fixed posts" cellspacing="0">
 
	 
	  <tr>
    <td width="300"><strong>'.__("Obit Display type","sp-wpfh").'</strong><br><em>'.__("There are 3 different styles to display your obits. Thumbnail mode, block mode and list mode. Check out each style and choose the best one for your website","sp-wpfh").'.</em></td>
    <td><select name="wpfh_obit_style">
		<option value="'.get_option('wpfh_obit_style').'" selected="selected">'.get_option('wpfh_obit_style').'</option>
		<option value="thumbnails">'.__("Thumbnails","sp-wpfh").'</option>
		<option value="block">'.__("Block","sp-wpfh").'</option>
		<option value="list">'.__("List","sp-wpfh").'</option>
		</select> </td>
  </tr>
 
    <tr>
    <td width="300"><strong>'.__("Enable Search?","sp-wpfh").'</strong><br><em>'.__("Should we add a search form to the top of the obits page?","sp-wpfh").'</em></td>
    <td><input type="checkbox" name="wpfh_enable_search"   value="1" '. $wpfh_enable_search.'>  </td>
  </tr>

    <tr>
    <td width="300"><strong>'.__("How many Obituaries to display","sp-wpfh").'</strong><br><em>'.__("How many obits should we display on each page?","sp-wpfh").'</em></td>
    <td><input type="text" name="wpfh_obit_display_num"  value="'.get_option('wpfh_obit_display_num').'"  size=80"> </td>
  </tr>
    <tr>
    <td width="300"><strong>'.__("Word for Obituaries","sp-wpfh").'</strong><br><em>'.__("What should we call Obituaries?","sp-wpfh").'</em></td>
    <td><input type="text" name="wpfh_obit_name"  value="'.get_option('wpfh_obit_name').'"  size=80"> </td>
  </tr>
    <tr>
    <td width="300"><strong>'.__("Plural Word for Obituaries","sp-wpfh").'</strong><br><em>'.__("The plural word for the word you chose for obituaries. Example: If you chose Obituary then you would write Obituaries here.","sp-wpfh").'</em></td>
    <td><input type="text" name="wpfh_obit_name_plural"  value="'.get_option('wpfh_obit_name_plural').'"  size=80"> </td>
  </tr>
   <tr>
    <td width="300"><strong>'.__("Order Flowers link","sp-wpfh").'</strong><br><em>'.__("There are many different flower affiliate programs available online. If you would like your users to order flowers put the full url for your flowers affiliate program. If you leave it blank the order flowers link will not show.","sp-wpfh").'</em></td>
    <td><input type="text" name="wpfh_order_flowers"  value="'.get_option('wpfh_order_flowers').'"  size=80"> </td>
  </tr>
   <tr>
    <td width="300"><strong>'.__("No pic image","sp-wpfh").'</strong><br><em>'.__("Upload a default picture we should use incase the deceased does not have a picture.","sp-wpfh").'</em></td>
    <td><input type="file" name="wpfh_obit_default_pic"  ><div>';
	
	if(get_option('wpfh_obit_default_pic') != ''){
		echo '<img src="../wp-content/plugins/wp-funeral-press/thumbs.php?src='.get_option('wpfh_obit_default_pic').'&w=150&h=150">';
	}else{
	echo '<span style="color:red">'.__("No default pictured loaded, please upload one.","sp-wpfh").'</div>';
	}
	echo '</div> </td>
  </tr>
';
	
		if(class_exists('wpfh_cem_settings')){
	
			global $wpfh_cem_settings;
			
			echo $wpfh_cem_settings-> settings();	
		}
echo '
  <tr><td></td><td><input type="submit" value="Save" name="save-settings"/></td></tr>
</table>
	
';

	
		
echo '


</form>';	
	
	
}
	
}

?>