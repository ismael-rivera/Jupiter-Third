<?php
class wpfh_adverts_admin{
	
	function menu(){
		
		global $wpfh_install;
		
		$submenu = '<a href="admin.php?page=wpfh" class="button">'.__("View","sp-wpfh").' '.get_option('wpfh_obit_name_plural').'</a> 
				    <a href="admin.php?page=wpfh&function=add" class="button">'.__("Add","sp-wpfh").' '.get_option('wpfh_obit_name').'</a>  ';
		return $wpfh_install->topMenu(__("Adverts","sp-wpfh"),$submenu);
	}
	
	 function postedOn($id,$type){
		  
		 	global $wpdb;
		 
		 	$r = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix ."wpfh_adverts  where id = ".$wpdb->escape($id)."", ARRAY_A);	
			
			
			return stripslashes($r[0][$type]);
	 }
	  function postedBy($id,$type){
		 
		 	global $wpdb;
			
			 $user_data = get_userdata($id);
			 
			 
			 return $user_data->$type;
		 
	 }
	function guestbook($id= NULL){
		
		
		global $wpdb;
		
	
	

		
			$query = "SELECT * FROM  " . $wpdb->prefix . "wpfh_posts  WHERE oid = ".$id." order by date desc";
			$pagination = new Pagination();
			if (isset($_GET['pagenum'])){   $page = (int) $_GET['pagenum'];}else{ $page = 1; }
			$pagination->setLink("admin.php?page=wpfh-guestbook&pagenum=%s");
			$pagination->setPage($page);
			$pagination->setSize(get_option('wpfh_obit_display_num'));
			$pagination->setTotalRecords(count($wpdb->get_results($query, ARRAY_A)));
		    $r = $wpdb->get_results("".$query." ".$pagination->getLimitSql()."", ARRAY_A);
		
		
		
			 
									 echo '
								 <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
<th width="50">'.__("Date","sp-wpfh").'</th>
<th width="120">'.__("Posted On","sp-wpfh").'</th>
<th width="100">'.__("Poster Name","sp-wpfh").'</th>
<th width="100">'.__("Type","sp-wpfh").'</th>
<th>'.__("Message Preview","sp-wpfh").'</th>
<th>'.__("Action","sp-wpfh").'</th>
</tr>
	</thead>';
				
				if(count($r) == 0){
					echo '	<tr>
					<td colspan="4">
					<div class="error">'.__("No posts Found","sp-wpfh").'</div>
					</td></tr>';
				}else{
				
				for($i=0; $i<count(	$r); $i++){
				
				if($r[$i]['approved'] == 1){
		$approve = '<a href="admin.php?page=wpfh-guestbook&function=approve&id='.$r[$i]['id'].'&approve=0"  class="button"  style="margin-right:15px" >'.__("Unapprove","sp-wpfh").'</a>';
		
		$style= ' ';
			
		}else{
			$style= ' style="background-color:#ffd1d1 !important" ';
		$approve = '<a href="admin.php?page=wpfh-guestbook&function=approve&id='.$r[$i]['id'].'&approve=1"  class="button"  style="margin-right:15px" >'.__("Approve","sp-wpfh").'</a>';		
		}
				echo '	<tr>
<td '.$style.'>'.date("M d", $r[$i]['date']).'</td>				
<td '.$style.'>'.$this->postedOn($r[$i]['oid'],'first_name').' '.$this->postedOn($r[$i]['oid'],'last_name').'</a></td>
<td '.$style.'><a href="mailto:'.$this->postedBy($r[$i]['oid'],'user_email').'">'.$this->postedBy($r[$i]['uid'],'display_name').'</a></td>
<td '.$style.'>'.$r[$i]['type'].'</td>
<td '.$style.'>

';

				if($r[$i]['type'] == 'guestbook'){
						echo '' . substr(stripslashes(strip_tags($r[$i]['content'])), 0, 150) . '...';
												}
												if($r[$i]['type'] == 'photo'){
												unset($photo);
												$photo = unserialize(stripslashes($r[$i]['content']));	
											echo '<a href="'.$photo['url'].'" target="_blank">'.__("View Photo","sp-wpfh").'</a><br>'.$photo['desc'].'';
											
												}
													if($r[$i]['type'] == 'youtube'){
														unset($youtube);
												$youtube = unserialize(stripslashes($r[$i]['content']));	
													echo '<a href="'.$youtube['url'].'" target="_blank">'.__("View Video","sp-wpfh").'</a><br>'.$youtube['desc'].'';	
												
													}





echo '
</td>
<td '.$style.'>
 <a href="admin.php?page=wpfh-guestbook&function=delete&id='.$r[$i]['id'].'" style="margin-right:15px" class="button">'.__("Delete","sp-wpfh").'</a>  
<a href="admin.php?page=wpfh-guestbook&function=edit&id='.$r[$i]['id'].'"  class="button"  style="margin-right:15px">'.__("View","sp-wpfh").'</a>
'.$approve.'
</td>
</tr>';	
					
				}
				}
				echo '</table>';
				echo $pagination->create_links();
		

	}
	function add(){
		global $wpdb,$wpfh_plugins;
		
		
		
		
		add_action('admin_init', 'editor_admin_init');
	add_action('admin_head', 'editor_admin_head');
		
		if($_POST['save-obit'] != ""){
		
		
		
		$insert['id'] = $_POST['id'];
		$insert['first_name'] = $_POST['first_name'];
		$insert['maiden'] = $_POST['maiden'];
		$insert['middle'] = $_POST['middle'];
		$insert['last_name'] = $_POST['last_name'];
		$insert['burial_date'] = $_POST['burial_date'];
		$insert['birth_date'] = $_POST['birth_date'];
		$insert['death_date'] = $_POST['death_date'];
		$insert['obituary'] = $_POST['obituary'];
		$insert['obit_notes'] = $_POST['obit_notes'];
		$insert['vet'] = $_POST['vet'];
		$insert['visitation_time'] = $_POST['visitation_time'];
		$insert['service_time'] = $_POST['service_time'];
		
		if($_FILES['photo']['name'] != ""){			
     	$photo = wp_upload_bits($_FILES['photo']["name"], null, file_get_contents($_FILES['photo']["tmp_name"]));		
		$insert['photo'] = 	$photo['url'];	
		}		
		
		
		if($_POST['id'] == ""){
			$wpdb->insert("".$wpdb->prefix ."wpfh_adverts", $insert);
			if(class_exists('wpfh_cem_admin')){global $wpfh_cem_admin;	 $wpfh_cem_admin->obit_save($wpdb->insert_id); }
			
		}else{			
			$where['id'] = $_POST['id'];						
			$wpdb->update("".$wpdb->prefix ."wpfh_adverts", $insert,$where);
			if(class_exists('wpfh_cem_admin')){global $wpfh_cem_admin;	$wpfh_cem_admin->obit_save($_POST['id']); }
		}
		wpfh_redirect('admin.php?page=wpfh');	
		}
		
		
		
		if($_GET['id'] != ""){
			
			$r = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix ."wpfh_adverts where id = ".$wpdb->escape($_GET['id'])."", ARRAY_A);
		$extra = '<input name="id" type="hidden" value="'.$r[0]['id'].'"/>';	
		
		if($r[0]['vet'] == 0){
			
			$vet = ''.__("No","sp-wpfh").'';
		}else{
		$vet = ''.__("Yes","sp-wpfh").'';	
		}
		}
		
		
		
		$vtype = $_GET['vtype'];
		
		
		if($vtype == ''){
			
			$vobit = 'id="current"';
		}
		if($vtype == 'qrcode'){
			
			$vq = 'id="current"';
		}
		if($vtype == 'guestbook'){
			
			$vg = 'id="current"';
		}
		echo '<div id="wpfh-header">
			<ul>
			<li  '.$vobit.'><a href="admin.php?page=wpfh&function=edit&id='.$_GET['id'].'" >'.__("Obit","sp-wpfh").'</a></li>
			<li '.$vg .'><a href="admin.php?page=wpfh&function=edit&id='.$_GET['id'].'&vtype=guestbook">'.__("Guestbook","sp-wpfh").'</a></li>';
			
			 if(class_exists('wpfh_cem_admin')){
			echo '<li '.$vq.'><a href="admin.php?page=wpfh&function=edit&id='.$_GET['id'].'&vtype=qrcode">'.__("QR Code","sp-wpfh").'</a></li>';
				}
			echo '</ul>
			<div style="clear:both"></div>
		</div>';
		switch($vtype){
			
		default:
		echo '
		
		
		
		<div id="wpfh_obit"><form method="post" action="admin.php?page=wpfh&function=add" enctype="multipart/form-data">
'.	$extra.'
 <table class="wp-list-table widefat fixed posts" cellspacing="0">

  <tr>
    <td width="148"><label for="first_name">'.__("First Name","sp-wpfh").'</label></td>
    <td><input id="first_name" name="first_name" value="'.stripslashes($r[0]['first_name']).'" title="First name of deceased"/></td>
  </tr>
  <tr>
    <td><label for="middle">'.__("Middle Initial","sp-wpfh").'</label></td>
    <td><input  id="middle" name="middle" value="'.stripslashes($r[0]['middle']).'"/></td>
  </tr>
  <tr>
    <td><label for="last_name">'.__("Last Name","sp-wpfh").'</label></td>
    <td><input id="last_name" name="last_name" value="'.stripslashes($r[0]['last_name']).'"/></td>
  </tr>
      <tr>
    <td><label for="maiden">'.__("Maiden Name","sp-wpfh").'</label></td>
    <td><input id="maiden" name="maiden" value="'.stripslashes($r[0]['maiden']).'"/></td>
  </tr>
  <tr>
    <td> <label for="birth_date">'.__("Birth Date","sp-wpfh").'</label></td>
    <td><input id="birth_date" name="birth_date" value="'.stripslashes($r[0]['birth_date']).'" class="datepicker"/></td>
  </tr>
  <tr>
    <td><label for="death_date">'.__("Death Date","sp-wpfh").'</label></td>
    <td><input id="death_date" name="death_date" value="'.stripslashes($r[0]['death_date']).'" class="datepicker"/></td>
  </tr>
    <tr>
    <td><label for="burial_date">'.__("Funeral Services Date","sp-wpfh").'</label></td>
    <td><input id="burial_date" name="burial_date" value="'.stripslashes($r[0]['burial_date']).'" class="datepicker"/></td>
  </tr>';
  

 echo '<tr>
    <td><label for="funeral_times">'.__("Visitation Notes","sp-wpfh").': </label></td>
    <td><input id="maiden" name="obit_notes" value="'.stripslashes($r[0]['obit_notes']).'" style="width:300px"/></td>
  </tr>';
  

  
   
  echo '<tr>
    <td><label for="vet">'.__("Is a US Veteran?","sp-wpfh").'</label></td>
    <td><select name="vet">
	<option value="'.$r[0]['vet'].'">'.$vet .'</option>
	<option  value="0" >'.__("No","sp-wpfh").'</option>
	<option value="1" >'.__("Yes","sp-wpfh").'</option>
	</select></td>
  </tr>
 '; 
 if(class_exists('wpfh_cem_admin')){global $wpfh_cem_admin;	echo $wpfh_cem_admin->obit_form($_GET['id']); }
 echo '
  
  <tr>
    <td >
		<label for="obituary">'.get_option('wpfh_obit_name').'</label></td><td>
		</label>';
	echo the_editor(stripslashes($r[0]['obituary']), "obituary", "", true);
		 echo ' 
	
	</td>
  </tr>
   ';
  
	 if($r[0]['photo'] != ""){
  echo '<tr>
  <td>'.__("Current Photo","sp-wpfh").'</td>
     <td >

     <div style="border:1px solid #CCC;padding:5px">

	   <p id="thephoto"><img height="150"  src="'.stripslashes($r[0]['photo']).'"/>
          <br />
      </p>
      <p>'.__("New Photo","sp-wpfh").': <input id="photo" name="photo" type="file"></td>
  </tr>';
  
	 }else{
  
  
    echo '<tr>
      <td><label for="photo">'.__("Photo","sp-wpfh").'</label></td>
      <td><input id="photo" name="photo" type="file"/></td>
    </tr>';
	
	 }

  
  
  
echo '
  <tr><td></td><td><input type="submit" value="Save" name="save-obit"/></td></tr>
</table>

		
</form></div>';

	break;
	
	case"guestbook":
	

	
	echo '<div id="wpfh_guestbook">';
	
	$this->guestbook($_GET['id']);
	
	echo '</div>';
	
	break;
	case"qrcode":
		
	 if(class_exists('wpfh_cem_admin')){global $wpfh_cem_admin;	echo $wpfh_cem_admin->qr_code(); }
		
		 
	break;
		}




	
	
	}
	function view(){
		
	global $wpdb;
	echo $this->menu(); 
	
	if($_GET['function'] == 'delete'){
		$wpdb->query("DELETE FROM  ".$wpdb->prefix ."wpfh_adverts WHERE id = ".$wpdb->escape($_GET['id'])."	");
			wpfh_redirect('admin.php?page=wpfh');				
	}
	
	
	if($_GET['id'] != "" or $_GET['function'] == 'add'){
	
	
	 $this->add();
	
	}else{
	
	
	
		if($_POST['search-adverts'] != ""){
					
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
		
		
		
		
			$query = "SELECT * FROM  " . $wpdb->prefix . "wpfh_adverts WHERE id != '' ".$search." ORDER  by death_date desc";
			$pagination = new Pagination();
			if (isset($_GET['pagenum'])){   $page = (int) $_GET['pagenum'];}else{ $page = 1; }
			$pagination->setLink("admin.php?page=wpfh&pagenum=%s");
			$pagination->setPage($page);
			$pagination->setSize(get_option('wpfh_obit_display_num'));
			$pagination->setTotalRecords(count($wpdb->get_results($query, ARRAY_A)));
		   $r = $wpdb->get_results("".$query." ".$pagination->getLimitSql()."", ARRAY_A);
		
		
		
		
		
		

									 
									 echo '
								
									 <div id="wpfh_search">
			<form action="" method="post">
				<h3>'.__("Search","sp-wpfh").' '.get_option('wpfh_obit_name_plural').'</h3>
				'.__("First Name","sp-wpfh").': <input type="text" name="first_name">   '.__("Last Name","sp-wpfh").': <input type="text" name="last_name"> '.__("Death Date","sp-wpfh").': <input type="text" name="date" class="datepicker">  <input type="submit" name="search-adverts" value="Search!">
				
					
				</form></div>
									 
							
									 <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
<th width="50">'.__("ID","sp-wpfh").'</th>
<th width="200">'.__("Name","sp-wpfh").'</th>
<th>'.__("Deceased Date","sp-wpfh").'</th>
<th>'.__("Action","sp-wpfh").'</th>
</tr>
	</thead>';
				
				if(count($r) == 0){
					echo '	<tr>
					<td colspan="4">
					<div class="error">'.sprintf(__("No %s Found","sp-wpfh"),get_option('wpfh_obit_name_plural')).' </div>
					</td></tr>';
				}else{
				
				for($i=0; $i<count(	$r); $i++){
				
			
				echo '	<tr>
<td>'.$r[$i]['id'].'</td>				
<td><a href="admin.php?page=wpfh&function=edit&id='.$r[$i]['id'].'" >'.stripslashes($r[$i]['last_name']).', '.stripslashes($r[$i]['first_name']).' '.stripslashes($r[$i]['middle']).'</a></td>
<td>'.date("F j, Y", strtotime($r[$i]['death_date'])).'</td>
<td>
 <a href="admin.php?page=wpfh&function=delete&id='.$r[$i]['id'].'" style="margin-right:15px" >'.__("Delete","sp-wpfh").'</a> 
<a href="admin.php?page=wpfh&function=edit&id='.$r[$i]['id'].'" >'.__("Modify","sp-wpfh").'</a></td>
</tr>';	
					
				}
				}
				echo '</table>';
				echo $pagination->create_links();;
		
	}
	
	}
	
	
}

?>