<?php 
if(!isset($configs_are_set)) {
	include("config.php");
}
$thisPage = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM ".$TABLE["Options"];
$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
$Options = mysql_fetch_assoc($sql_result);
$OptionsVis = unserialize($Options['visual']);
$OptionsVisC = unserialize($Options['visual_comm']);
$OptionsLang = unserialize($Options['language']);

if ($_POST["act"]=='post_comment') {
	
  if($Options['captcha']=='recap') { // if the option is set to reCaptcha
  
	$privatekey = "6Lfk9L0SAAAAAMccSmLp8kxaMQ53yJyVE0kuOSrh";
	if ($_POST["recaptcha_response_field"]) {
		$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		if ($resp->is_valid) {
			
			if ($Options["approval"]=='true') {			
				$status = 'Not approved';
			} else {
				$status = 'Approved';
			}
			
			$WordAllowed = true;
			$BannedWords = explode(",", ReadDB($Options["ban_words"]));
			if (count($BannedWords)>0) {
			  $checkComment = strtolower($_REQUEST["comment"]);
			  for($i=0;$i<count($BannedWords);$i++){
				  $banWord = trim($BannedWords[$i]);
				  if (trim($BannedWords[$i])<>'') {
					  if(preg_match("/".$banWord."/i", $checkComment)){ 
						  $WordAllowed = false;
						  break;
					  }
				  }
			  }
			}
			if($WordAllowed==false) {
				 $SysMessage =  $OptionsLang["Banned_word_used"]; 
			} else {
				
				$allowable_tags = str_replace(",", "", $Options['allowable_tags']);
				
				$sql = "INSERT INTO ".$TABLE["Comments"]."
						SET publish_date = now(),
							status = '".$status."',
							obit_id = '".SaveDB($_REQUEST["id"])."',
							name = '".SaveDB($_REQUEST["name"])."',
							email = '".SaveDB($_REQUEST["email"])."',
							comment = '".SaveDB(strip_tags($_REQUEST["comment"], $allowable_tags))."'";
				$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
				$SysMessage = $OptionsLang["Comment_Submitted"];
				if($Options['approval']=='true') {
					$SysMessage .= ". ".$OptionsLang["After_Approval_Admin"];
				}
											
				$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".mysql_real_escape_string($_REQUEST["id"])."'";
				$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
				$Obituary = mysql_fetch_assoc($sql_result);
	
				$mailheader = "From: ".ReadDB($Options["email"])."\r\n";
				$mailheader .= "Reply-To: ".ReadDB($Options["email"])."\r\n";
				$mailheader .= "Content-type: text/html; charset=UTF-8\r\n";
				$Message_body = "Name of Deceased: <strong>".ReadDB($Obituary["title"])."</strong><br /><br />";
				$Message_body .= "Comment: <br />".$_REQUEST["comment"]."<br /><br />";
				$Message_body .= "Name: ".$_REQUEST["name"]."<br />";
				$Message_body .= "Email: ".$_REQUEST["email"]."<br />";
				mail(ReadDB($Options["email"]), $OptionsLang["New_comment_posted"], $Message_body, $mailheader);
				
				$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."'";
				$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
				$Obituary = mysql_fetch_assoc($sql_result);	
				
				$sql = "SELECT * FROM ".$TABLE["Editors"]." WHERE id='".$Obituary["editor_id"]."'";
				$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
				$Editor = mysql_fetch_assoc($sql_result);	
				
				mail(ReadDB($Editor["editor_email"]), $OptionsLang["New_comment_posted"], $Message_body, $mailheader);
				
				unset($_REQUEST["name"]);
				unset($_REQUEST["email"]);
				unset($_REQUEST["comment"]);
			}
			
		} else {
			# set the error code so that we can display it
			$error = $resp->error;
			$SysMessage =  $OptionsLang["Incorrect_verification_code"]; 
			unset($_REQUEST["act"]);
		}
	} else {		
		$SysMessage =  $OptionsLang["Incorrect_verification_code"]; 
		unset($_REQUEST["act"]);
	}
	
  } else { // end if is set reCaptcha option
	
	if (eregi('^'.$_SESSION['key'].'$', $_REQUEST['string'])) {
	
		if ($Options["approval"]=='true') {			
			$status = 'Not approved';
		} else {
			$status = 'Approved';
		}
		
		$WordAllowed = true;
		$BannedWords = explode(",", ReadDB($Options["ban_words"]));
		if (count($BannedWords)>0) {
		  $checkComment = strtolower($_REQUEST["comment"]);
		  for($i=0;$i<count($BannedWords);$i++){
			  $banWord = trim($BannedWords[$i]);
			  if (trim($BannedWords[$i])<>'') {
				  if(preg_match("/".$banWord."/i", $checkComment)){ 
					  $WordAllowed = false;
					  break;
				  }
			  }
		  }
		}
		if($WordAllowed==false) {
			 $SysMessage =  $OptionsLang["Banned_word_used"]; 
		} else {
			
			$allowable_tags = str_replace(",", "", $Options['allowable_tags']);
			
			$sql = "INSERT INTO ".$TABLE["Comments"]."
					SET publish_date = now(),
					  	status = '".$status."',
					  	obit_id = '".SaveDB($_REQUEST["id"])."',
					  	name = '".SaveDB($_REQUEST["name"])."',
					  	email = '".SaveDB($_REQUEST["email"])."',
						comment = '".SaveDB(strip_tags($_REQUEST["comment"], $allowable_tags))."'";
			$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
			$SysMessage = $OptionsLang["Comment_Submitted"];
			if($Options['approval']=='true') {
				$SysMessage .= ". ".$OptionsLang["After_Approval_Admin"];
			}
										
			$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".mysql_real_escape_string($_REQUEST["id"])."'";
			$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
			$Obituary = mysql_fetch_assoc($sql_result);

			$mailheader = "From: ".ReadDB($Options["email"])."\r\n";
			$mailheader .= "Reply-To: ".ReadDB($Options["email"])."\r\n";
			$mailheader .= "Content-type: text/html; charset=UTF-8\r\n";
			$Message_body = "Name of Deceased: <strong>".ReadDB($Obituary["title"])."</strong><br /><br />";
			$Message_body .= "Comment: <br />".$_REQUEST["comment"]."<br /><br />";
			$Message_body .= "Name: ".$_REQUEST["name"]."<br />";
			$Message_body .= "Email: ".$_REQUEST["email"]."<br />";
			mail(ReadDB($Options["email"]), $OptionsLang["New_comment_posted"], $Message_body, $mailheader);
			
			$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE id='".$_REQUEST["id"]."'";
			$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
			$Obituary = mysql_fetch_assoc($sql_result);	
			
			$sql = "SELECT * FROM ".$TABLE["Editors"]." WHERE id='".$Obituary["editor_id"]."'";
			$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
			$Editor = mysql_fetch_assoc($sql_result);	
			
			mail(ReadDB($Editor["editor_email"]), $OptionsLang["New_comment_posted"], $Message_body, $mailheader);
			
			unset($_REQUEST["name"]);
			unset($_REQUEST["email"]);
			unset($_REQUEST["comment"]);
		}

	} else {		
		$SysMessage =  $OptionsLang["Incorrect_verification_code"]; 
		unset($_REQUEST["act"]);
	} 
  }
}
?>
<div style="background-color:<?php echo $OptionsVis["gen_bgr_color"];?>;">
<div style="font-family:<?php echo $OptionsVis["gen_font_family"];?>; font-size:<?php echo $OptionsVis["gen_font_size"];?>;margin:0 auto;width:<?php echo $OptionsVis["gen_width"];?>px; color:<?php echo $OptionsVis["gen_font_color"];?>;line-height:<?php echo $OptionsVis["gen_line_height"];?>;">

<div style="text-align: right;">
<form action="<?php echo $thisPage; ?>?hide_cat=<?php echo $_REQUEST["hide_cat"]; ?>" method="post" name="form" style="margin:0; padding:0;">
  <input type="text" name="search" value="<?php if(isset($_REQUEST["search"]) and $_REQUEST["search"]!='') echo htmlspecialchars(urldecode($_REQUEST["search"]), ENT_QUOTES); ?>" />
  <input name="SearchName" type="submit" value="<?php echo $OptionsLang["Search_button"]; ?>" />
</form>
</div>
<div style="clear:both;height:10px;"></div>

<?php
if ($_REQUEST["id"]>0) {	
?>
	<div style="clear: both; height:0px;"></div>
	<?php if(trim($OptionsLang["Back_to_home"])!='') { ?>
    <div style="text-align:<?php echo $OptionsVis["link_align"]; ?>"><a href="<?php echo $thisPage; ?>?p=<?php echo $_REQUEST["p"]; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>" style='font-weight:<?php echo $OptionsVis["link_font_weight"]; ?>;color:<?php echo $OptionsVis["link_color"]; ?>;font-size:<?php echo $OptionsVis["link_font_size"]; ?>;text-decoration:underline'><?php echo $OptionsLang["Back_to_home"]; ?></a></div>    
    <div style="clear:both; height:<?php echo $OptionsVis["dist_link_title"];?>;"></div>    
    <?php } ?>

	<?php 
	$sql = "SELECT * FROM ".$TABLE["Obituaries"]." WHERE status='Published' AND id='".mysql_real_escape_string($_REQUEST["id"])."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
	if(mysql_num_rows($sql_result)>0) {	
	  $Obituary = mysql_fetch_assoc($sql_result);
	?>
	
	<div style="color:<?php echo $OptionsVis["title_color"];?>;font-family:<?php echo $OptionsVis["title_font"];?>;font-size:<?php echo $OptionsVis["title_size"];?>;font-weight:<?php echo $OptionsVis["title_font_weight"];?>;font-style:<?php echo $OptionsVis["title_font_style"];?>;text-align:<?php echo $OptionsVis["title_text_align"];?>;">	  
            <?php echo ReadDB($Obituary["title"]); ?>     
    </div>
    
    <div style="clear:both; height:<?php echo $OptionsVis["dist_title_date"];?>;"></div>
        
    <?php if($OptionsVis["show_date"]=='yes') { ?>
    <div style="color:<?php echo $OptionsVis["date_color"];?>; font-family:<?php echo $OptionsVis["date_font"];?>; font-size:<?php echo $OptionsVis["date_size"];?>;font-style: <?php echo $OptionsVis["date_font_style"];?>;text-align:<?php echo $OptionsVis["date_text_align"];?>;">
		<?php echo $OptionsLang['date_word']; ?> <?php echo date($OptionsVis["date_format"],strtotime($Obituary["publish_date"])); ?> 
		<?php if($OptionsVis["showing_time"]!='') echo date($OptionsVis["showing_time"],strtotime($Obituary["publish_date"])); ?></div>
    <?php } ?>
    
    <div style="clear:both; height:<?php echo $OptionsVis["dist_date_text"];?>;"></div>
    
    <div style="color:<?php echo $OptionsVis["cont_color"];?>; font-family:<?php echo $OptionsVis["cont_font"];?>; font-size:<?php echo $OptionsVis["cont_size"];?>;font-style: <?php echo $OptionsVis["cont_font_style"];?>;text-align:<?php echo $OptionsVis["cont_text_align"];?>;line-height:<?php echo $OptionsVis["cont_line_height"];?>;">
      <?php if(ReadDB($Obituary["image"])!='') { ?>
		<?php if(ReadDB($Obituary["imgpos"])=='left') { ?>
        <div style="float:left">
        	<img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-right:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" />
            <?php if($OptionsVis["show_share_this"]=='yes') { ?>
            <div style="padding-top:10px;padding-bottom:10px;">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">            
            <span style="float:left; color:#4c7ba8; font-size:13px; padding-right:2px;">share</span> 
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f1efb0b5f92c11b"></script>
            <!-- AddThis Button END -->
            </div>
            <?php } ?>
        </div>
		<?php } ?>
        <?php if(ReadDB($Obituary["imgpos"])=='right') { ?>
        <div style="float:right">
        	<img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-left:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" />
            <?php if($OptionsVis["show_share_this"]=='yes') { ?>
            <div style="padding-top:10px;padding-bottom:10px;padding-left:14px;">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">            
            <span style="float:left; color:#4c7ba8; font-size:13px; padding-right:2px;">share</span> 
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f1efb0b5f92c11b"></script>
            <!-- AddThis Button END -->
            </div>
            <?php } ?>
        </div>
		<?php } ?>
        <?php if(ReadDB($Obituary["imgpos"])=='top') { ?>
        <div style="clear:both; text-align:center">
        	<img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" />
            <?php if($OptionsVis["show_share_this"]=='yes') { ?>
            <div style="padding-top:10px;padding-bottom:10px;">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">            
            <span style="float:left; color:#4c7ba8; font-size:13px; padding-right:2px;">share</span> 
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f1efb0b5f92c11b"></script>
            <!-- AddThis Button END -->
            </div>
            <?php } ?>
      	</div>
		<?php } ?>
      <?php } ?>
        <?php echo ReadDB($Obituary["content"]); ?> 
      <?php if(ReadDB($Obituary["image"])!='') { ?>
        <?php if(ReadDB($Obituary["imgpos"])=='bottom') { ?>
        <div style="clear:both; text-align:center">
        	<img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:10px;" width="<?php echo $Obituary["imgwidth"]; ?>" />
            <?php if($OptionsVis["show_share_this"]=='yes') { ?>
            <div style="padding-top:10px;padding-bottom:10px;">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">            
            <span style="float:left; color:#4c7ba8; font-size:13px; padding-right:2px;">share</span> 
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f1efb0b5f92c11b"></script>
            <!-- AddThis Button END -->
            </div>
            <?php } ?>
        </div>
		<?php } ?>
      <?php } ?>
    </div>
    
    <div style="clear:both;padding-top:10px;"></div>
    
    <?php 
	$sql = "UPDATE ".$TABLE["Obituaries"]." 
			SET reviews = reviews + 1 
			WHERE id='".$_REQUEST["id"]."'";
	$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());	
	?>
    	
        <?php // start comments code ?>
    	<?php if($Obituary['allow_comments']=='true') { ?>
        
        <a name="comments" id="comments"></a>
		<?php if(isset($SysMessage)) { ?>
        <div style="padding:10px;color:red;font-weight:bold;"><?php echo $SysMessage; ?></div>
        <?php } ?>
        
        <?php
        if ($Options["comments_order"]=='OnTop') {
            $commentOrder = 'DESC';
        } else {
            $commentOrder = 'ASC';
        }
        
        $sql = "SELECT * FROM ".$TABLE["Comments"]." WHERE obit_id='".$Obituary["id"]."' AND status='Approved' ORDER BY id ".$commentOrder;
        $sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql);
        $numComments = mysql_num_rows($sql_result);
        if ($numComments>0) { 
            if ($Options["comments_order"]=='OnTop') {
                $commentNum = $numComments;
            } else {
                $commentNum = 1;
            }
        ?>
        <div style="padding-bottom:6px;padding-top:8px;font-weight:bold;"><?php echo $OptionsLang["Word_Comments"];?></div>
        <?php
            while ($Comments = mysql_fetch_assoc($sql_result)) {
        ?>
        <?php // start comments wrap div ?>
        <div style="background-color:<?php echo $OptionsVisC["comm_bgr_color"];?>;padding:<?php echo $OptionsVisC["comm_padding"];?>; <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='top_bottom' or $OptionsVisC["comm_bord_sides"]=='top') {?>border-top:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?> <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='top_bottom' or $OptionsVisC["comm_bord_sides"]=='bottom') {?>border-bottom:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?> <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='right_left' or $OptionsVisC["comm_bord_sides"]=='left') {?>border-left:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?> <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='right_left' or $OptionsVisC["comm_bord_sides"]=='right') {?>border-right:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?>">
            
            <?php // comments name ?>
            <div style="float:left;padding-bottom:8px;color:<?php echo $OptionsVisC["name_font_color"];?>;font-size:<?php echo $OptionsVisC["name_font_size"];?>;font-style:<?php echo $OptionsVisC["name_font_style"];?>;font-weight:<?php echo $OptionsVisC["name_font_weight"];?>;"><?php echo ReadHTML($Comments["name"]); ?></div>
            
            <div style="float:right; padding-left:10px;"><span style="font-weight:bold;padding-right:2px;">#</span><?php echo $commentNum; ?></div>
            
            <?php // comments date ?>
            <div style="color:<?php echo $OptionsVisC["comm_date_color"];?>;font-family:<?php echo $OptionsVisC["comm_date_font"];?>;font-size:<?php echo $OptionsVisC["comm_date_size"];?>;font-style:<?php echo $OptionsVisC["comm_date_font_style"];?>;text-align:<?php echo $OptionsVisC["comm_date_text_align"];?>;float:right;">
				<?php 
					if($OptionsVisC["comm_showing_time"]!='') { 
						$show_time = " ".$OptionsVisC["comm_showing_time"]; 
					} else {
						$show_time = "";
					}
					
					if(isset($OptionsVisC["time_offset"]) and $OptionsVisC["time_offset"]!='0') { 						
						echo date($OptionsVisC["comm_date_format"].$show_time,strtotime($OptionsVisC["time_offset"], strtotime($Comments["publish_date"])));
					} else {
						echo date($OptionsVisC["comm_date_format"].$show_time,strtotime($Comments["publish_date"]));
					}
				?>
            </div>
                
            <div style="clear:both"></div>
            
            <?php // comments text ?>
            <div style="color:<?php echo $OptionsVisC["comm_font_color"];?>;font-size:<?php echo $OptionsVisC["comm_font_size"];?>;font-style:<?php echo $OptionsVisC["comm_font_style"];?>;font-weight:<?php echo $OptionsVisC["comm_font_weight"];?>;"><?php echo ReadDB($Comments["comment"]); ?></div>
        
        </div>
        <?php // end comments wrap div ?>
        
        <div style="clear:both;height:<?php echo $OptionsVisC["dist_btw_comm"];?>;"></div>
        
        <?php
                if ($Options["comments_order"]=='OnTop') {
                    $commentNum --;
                } else {
                    $commentNum ++;
                }
            }
        } else {
        ?>
        <div  style="padding-bottom:10px;padding-top:10px;font-weight:bold;"><?php echo $OptionsLang["No_comments_posted"]; ?></div>
        <?php 
        }
        ?>   
        
        
        <script type="text/javascript">
        function checkComment(form){
            var chekmail = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
        
            var name, email, comment, isOk = true;
			<?php if($Options['captcha']!='recap') { // if the option is set to reCaptcha ?>
			var string;
			<?php } ?>
            var message = "";
            
            message = "<?php echo $OptionsLang["fill_required_fields"]; ?>";
            
            name	= form.name.value;	
            email	= form.email.value;
            comment	= form.comment.value;
			<?php if($Options['captcha']!='recap') { // if the option is set to reCaptcha ?>
            string	= form.string.value;
			<?php } ?>
        
            if (name.length==0){
                form.name.focus();
                isOk=false;
            }
            else if (email.length<5){
                form.email.focus();
                isOk=false;
            }	
            else if (email.length>=5 && email.match(chekmail)==null){
                message ="<?php echo $OptionsLang["correct_email"]; ?>";
                form.email.focus();
                isOk=false;
            }
            else if (comment.length==0){
                form.comment.focus();
                isOk=false;
            }
			
			<?php if($Options['captcha']!='recap') { // if the option is set to reCaptcha ?>
            else if (string.length==0){
                message ="<?php echo $OptionsLang["field_code"]; ?>";
                form.string.focus();
                isOk=false;
            }
        	<?php } ?>
            if (!isOk){			   
                alert(message);
                return isOk;
            } else {
                return isOk;
            }
        }
        </script>
        <script type="text/javascript">
		 var RecaptchaOptions = {
			theme : '<?php echo $Options['captcha_theme']; ?>'
		 };
		</script>
        <?php // start comments form ?>
        <form action="<?php echo $thisPage; ?>?p=<?php echo $_REQUEST['p']; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>#comments" name="formComment" method="post" style="margin:0;padding:0;">
        <input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
        <input type="hidden" name="act" value="post_comment" />
        <table width="100%" border="0" cellpadding="6" cellspacing="0" bgcolor="<?php echo $OptionsVisC["tbl_bgr"];?>" style="background-color:<?php echo $OptionsVisC["comm_bgr_color"];?>; <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='top_bottom' or $OptionsVisC["comm_bord_sides"]=='top') {?>border-top:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?> <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='top_bottom' or $OptionsVisC["comm_bord_sides"]=='bottom') {?>border-bottom:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?> <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='right_left' or $OptionsVisC["comm_bord_sides"]=='left') {?>border-left:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?> <?php if($OptionsVisC["comm_bord_sides"]=='all' or $OptionsVisC["comm_bord_sides"]=='right_left' or $OptionsVisC["comm_bord_sides"]=='right') {?>border-right:<?php echo $OptionsVisC["comm_bord_style"];?> <?php echo $OptionsVisC["comm_bord_width"];?> <?php echo $OptionsVisC["comm_bord_color"];?>;<?php } ?>">
          <tr>
            <td colspan="2" style="font-weight:bold;color:#000000;"><?php echo $OptionsLang["Leave_Comment"]; ?></td>
            </tr>
          <tr>    
            <td align="right" style="padding-left:<?php echo $OptionsVisC["comm_padding"];?>;"><input type="text" name="name" style="width:100%" value="<?php echo $_REQUEST["name"]; ?>" /></td>
            <td align="left" width="55%" style="color:<?php echo $OptionsVisC["label_font_color"];?>;font-size:<?php echo $OptionsVisC["label_font_size"];?>;font-style:<?php echo $OptionsVisC["label_font_style"];?>;font-weight:<?php echo $OptionsVisC["label_font_weight"];?>;"><?php echo $OptionsLang["Comment_Name"]; ?></td>
          </tr>
          <tr>    
            <td align="right" style="padding-left:<?php echo $OptionsVisC["comm_padding"];?>;"><input type="text" name="email" style="width:100%" value="<?php echo $_REQUEST["email"]; ?>" /></td>
            <td align="left" style="color:<?php echo $OptionsVisC["label_font_color"];?>;font-size:<?php echo $OptionsVisC["label_font_size"];?>;font-style:<?php echo $OptionsVisC["label_font_style"];?>;font-weight:<?php echo $OptionsVisC["label_font_weight"];?>;"><?php echo $OptionsLang["Comment_Email"]; ?></td>
          </tr>
          <tr>    
            <td colspan="2" valign="top" style="padding-left:<?php echo $OptionsVisC["comm_padding"];?>;"><textarea name="comment" style="width:95%;display:block; float:left;" rows="8"><?php echo $_REQUEST["comment"]; ?></textarea> <div style="float:left; padding-left:5px;">*</div></td>
          </tr>
          <tr>    
            <td valign="top" align="right" style="padding-left:<?php echo $OptionsVisC["comm_padding"];?>;"> 
            <?php if($Options['captcha']=='recap') { // if the option is set to reCaptcha
					$publickey = "6Lfk9L0SAAAAACp13Wlzz6WTanYxrcLBXyn7XNSJ";
					echo recaptcha_get_html($publickey, $error);
				  } else { ?>
                	<input type="text" name="string" style="width:66px;display:block;float:right;margin-top:6px;" /> <img src="<?php echo $CONFIG["folder_name"]; ?>captcha.php" style="display:block;float:right;padding-right:10px;" />
            <?php } ?>
            </td>
            <td align="left" style="color:<?php echo $OptionsVisC["label_font_color"];?>;font-size:<?php echo $OptionsVisC["label_font_size"];?>;font-style:<?php echo $OptionsVisC["label_font_style"];?>;font-weight:<?php echo $OptionsVisC["label_font_weight"];?>;"><?php echo $OptionsLang["Enter_verification_code"]; ?></td>
          </tr>
          <tr>
            <td colspan="2" style="padding-left:<?php echo $OptionsVisC["comm_padding"];?>;padding-top:0;padding-bottom:0;color:<?php echo $OptionsVisC["label_font_color"];?>;font-style:<?php echo $OptionsVisC["label_font_style"];?>;">* - <span style="font-size:10px;"><?php echo $OptionsLang["Required_fields"]; ?></span></td>
          </tr>
          <tr>
            <td style="padding-left:<?php echo $OptionsVisC["comm_padding"];?>;">&nbsp;</td>
            <td align="left"><input type="submit" name="button" value="<?php echo $OptionsLang["Submit_Comment"]; ?>" onclick="return checkComment(this.form)" /></td>
          </tr>
        </table>
        </form>
        <?php // end comment form
        ?>
        
        <?php 
        } // end if allow_comments true
        ?>

    
	<?php 
	} // end if mysql num rows 
	?>
<?php
} else {
?>

  	<div>  	
    	<?php 
		if(isset($_REQUEST["p"]) and $_REQUEST["p"]>1) $pageNum = $_REQUEST["p"]; else $pageNum = 1;
		
		if(isset($_REQUEST["search"]) and ($_REQUEST["search"]!="")) {
			$find = mysql_real_escape_string(urldecode($_REQUEST["search"]));
			$search = " AND (title LIKE '%".$find."%' OR summary LIKE '%".$find."%' OR content LIKE '%".$find."%')";
		}  
				
		$sql   = "SELECT count(*) as total FROM ".$TABLE["Obituaries"]." WHERE status='Published'" . $search;
		$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
		$row   = mysql_fetch_array($sql_result);
		$count = $row["total"];
		$pages = ceil($count/$Options["per_page"]);
	
		$sql = "SELECT * FROM ".$TABLE["Obituaries"]."  
				WHERE status='Published' " . $search . "  
				ORDER BY publish_date DESC 
				LIMIT " . ($pageNum-1)*$Options["per_page"] . "," . $Options["per_page"];	
		$sql_result = mysql_query ($sql, $conn ) or die ('MySQL query error: '.$sql.'. Error: '.mysql_error());
		
		if (mysql_num_rows($sql_result)>0) {	
		  while ($Obituary = mysql_fetch_assoc($sql_result)) {
		?>
               
        
        <?php if($Options['showtype']=='TitleAndSummary') { // start table for TitleAndSummary image ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
           <?php if(ReadDB($Obituary["image"])!='' and $OptionsVis["summ_show_image"]=='yes') { ?>
           <td valign="top" align="left" width="<?php echo $OptionsVis["summ_img_width"];?>">
          	  		
            <div style="padding-right:10px; padding-top:4px;"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_thumbs"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" width="<?php echo $OptionsVis["summ_img_width"];?>" /></div>
                    
           </td>
           <?php } ?>
           
           <td valign="top">  
        <?php } // end if showtype == TitleAndSummary - for image ?>    
        
  			<div <?php if($Options['showtype']=='FullEntry') { ?>style="text-align:<?php echo $OptionsVis["title_text_align"];?>;"<?php } else { ?>style="text-align:<?php echo $OptionsVis["summ_title_text_align"];?>;"<?php } ?>>
             <a <?php if($Options['showtype']=='FullEntry') { ?>style="color:<?php echo $OptionsVis["title_color"];?>;font-family:<?php echo $OptionsVis["title_font"];?>;font-size:<?php echo $OptionsVis["title_size"];?>;font-weight:<?php echo $OptionsVis["title_font_weight"];?>;font-style:<?php echo $OptionsVis["title_font_style"];?>;text-decoration:none"<?php } else { ?>style="color:<?php echo $OptionsVis["summ_title_color"];?>;font-family:<?php echo $OptionsVis["summ_title_font"];?>;font-size:<?php echo $OptionsVis["summ_title_size"];?>;font-weight:<?php echo $OptionsVis["summ_title_font_weight"];?>;font-style:<?php echo $OptionsVis["summ_title_font_style"];?>;text-decoration:none"<?php } ?> onmouseover="this.style.textDecoration = 'underline'" onmouseout="this.style.textDecoration = 'none'" href="<?php echo $thisPage; ?>?id=<?php echo $Obituary['id']; ?>&p=<?php echo $_REQUEST["p"]; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>">
				<?php echo ReadDB($Obituary["title"]); ?>
             </a>
            </div>
        	
            <?php if($Options['showtype']=='FullEntry') { ?>
            <div style="clear:both; height:<?php echo $OptionsVis["dist_title_date"];?>;"></div>
            <?php } else { ?>
            <div style="clear:both; height:<?php echo $OptionsVis["summ_dist_title_date"];?>;"></div>
        	<?php } ?>
            
            <?php if($OptionsVis["summ_show_date"]=='yes') { ?>
        	<div <?php if($Options['showtype']=='FullEntry') { ?>style="color:<?php echo $OptionsVis["date_color"];?>;font-family:<?php echo $OptionsVis["date_font"];?>;font-size:<?php echo $OptionsVis["date_size"];?>;font-style:<?php echo $OptionsVis["date_font_style"];?>;text-align:<?php echo $OptionsVis["date_text_align"];?>;"<?php } else { ?>style="color:<?php echo $OptionsVis["summ_date_color"];?>;font-family:<?php echo $OptionsVis["summ_date_font"];?>;font-size:<?php echo $OptionsVis["summ_date_size"];?>;font-style:<?php echo $OptionsVis["summ_date_font_style"];?>;text-align:<?php echo $OptionsVis["summ_date_text_align"];?>;"<?php } ?>>
				<?php echo $OptionsLang['date_word']; ?> <?php echo date($OptionsVis["summ_date_format"],strtotime($Obituary["publish_date"])); ?> 
                <?php if($OptionsVis["summ_showing_time"]!='') echo date($OptionsVis["summ_showing_time"],strtotime($Obituary["publish_date"])); ?>
            </div>
        	<?php } ?>
        	
            <?php if($Options['showtype']=='FullEntry') { ?>
        	<div style="clear:both; height:<?php echo $OptionsVis["dist_date_text"];?>;"></div>
        	<?php } else { ?>
            <div style="clear:both; height:<?php echo $OptionsVis["summ_dist_date_text"];?>;"></div>
        	<?php } ?>
            
            
        	<?php if($Options['showtype']=='TitleAndSummary') { ?>
        
			<div style="color:<?php echo $OptionsVis["summ_color"];?>; font-family:<?php echo $OptionsVis["summ_font"];?>; font-size:<?php echo $OptionsVis["summ_size"];?>;font-style: <?php echo $OptionsVis["summ_font_style"];?>;text-align:<?php echo $OptionsVis["summ_text_align"];?>;line-height:<?php echo $OptionsVis["summ_line_height"];?>;">
        	
			 <?php echo nl2br(ReadDB($Obituary["summary"])); ?> &nbsp; 
             <a style="color:<?php echo $OptionsVis["summ_title_color"];?>; text-decoration: underline" href="<?php echo $thisPage; ?>?id=<?php echo $Obituary['id']; ?>&p=<?php echo $_REQUEST["p"]; ?>&search=<?php echo urlencode($_REQUEST["search"]); ?>"><?php echo $OptionsLang['Read_more']; ?></a>
        	</div>
            
          </td>
         </tr>
        </table>
        <?php // end table for TitleAndSummary image ?>
                
		<?php } elseif($Options['showtype']=='FullEntry') { ?>
         
        <div style="color:<?php echo $OptionsVis["cont_color"];?>; font-family:<?php echo $OptionsVis["cont_font"];?>; font-size:<?php echo $OptionsVis["cont_size"];?>;font-style: <?php echo $OptionsVis["cont_font_style"];?>;text-align:<?php echo $OptionsVis["cont_text_align"];?>;line-height:<?php echo $OptionsVis["cont_line_height"];?>;">
          <?php if(ReadDB($Obituary["image"])!='') { ?>
        	<?php if(ReadDB($Obituary["imgpos"])=='left') { ?><div style="float:left"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-right:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
            <?php if(ReadDB($Obituary["imgpos"])=='right') { ?><div style="float:right"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-left:14px; padding-bottom:6px; padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
            <?php if(ReadDB($Obituary["imgpos"])=='top') { ?><div style="clear:both; text-align:center"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:6px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
          <?php } ?>
			<?php echo ReadDB($Obituary["content"]); ?> 
          <?php if(ReadDB($Obituary["image"])!='') { ?>
            <?php if(ReadDB($Obituary["imgpos"])=='bottom') { ?><div style="clear:both; text-align:center"><img src="<?php echo $CONFIG["full_url"].$CONFIG["upload_folder"].ReadDB($Obituary["image"]); ?>" alt="<?php echo ReadDB($Obituary["title"]); ?>" style="padding-bottom:10px;padding-top:10px;" width="<?php echo $Obituary["imgwidth"]; ?>" /></div><?php } ?>
           <?php } ?>
        </div>
        	
            <?php 
			$sqlU = "UPDATE ".$TABLE["Obituaries"]." 
					SET reviews = reviews + 1 
					WHERE id='".$Obituary["id"]."'";
			$sql_resultU = mysql_query ($sqlU, $conn ) or die ('MySQL query error: '.$sqlU.'. Error: '.mysql_error());	
			?>
        
		<?php } else { 
				// only titles
			  }
		?>        
             
        <div style="clear:both; height:<?php echo $OptionsVis["dist_btw_entries"];?>;"></div>
        
    <?php 
		}
		?>
        
        <div style="padding-top:14px;clear:both;text-align:<?php echo $OptionsVis["pag_align"];?>;font-size:<?php echo $OptionsVis["pag_font_size"];?>;">
		<?php
        if ($pages>0) {
            echo "<span style='font-weight:".$OptionsVis["pag_font_weight"].";color:".$OptionsVis["pag_color"]."'>".$OptionsLang['Paging']." </span>";
            for($i=1;$i<=$pages;$i++){ 
                if($i == $pageNum ) echo "<strong style='font-weight:".$OptionsVis["pag_font_weight"].";color:".$OptionsVis["pag_color"]."'>" .$i. "</strong>";
                else echo "<a href='".$thisPage."?p=".$i."&search=".urlencode($_REQUEST["search"])."' style='font-weight:".$OptionsVis["pag_font_weight"].";color:".$OptionsVis["pag_color"]."'>".$i."</a>"; 
                echo "&nbsp; ";
            }
        }
        ?>    
    	</div>
              
        <?php 
        } else {
		?>
        <div style="line-height:20px; padding-bottom:20px;"><?php echo $OptionsLang['No_entry_published'] ?></div>
        <?php	
		}
		?>   
              
	</div>

<?php
}
?>
</div>
</div>