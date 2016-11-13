<div class="backend_menu">
<?php
/*function get_mymenu_list(){
	$pages = array(
	Dir::gHUrl() . "/cms/admin/dashboard.php",
	Dir::gHUrl() . "/cms/admin/markerman.php"
	);
	
	$link = array(
	'Dashboard',
	'Marker Manager'
	);
	
$a = array('a', 'b', 'c');
$b = array('A', 'B', 'C');
$c = array('1', '2', '3'); //These don't *have* to be strings, but it saves PHP from casting them later
 
if (sizeOf($pages) !== sizeOf($link)){
  throw new Exception('These 2 arrays must be the same length');
}
foreach ($pages as $key => $value){
  echo "{$a[$key]}{$b[$key]}{$c[$key]}\n";
}







	foreach($pages as $page){
		return '<li><a href="'.$pages.'"></a></li>';
		}
}
*/
 ?>
<div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Sidebar</li>
              <li><a href="<?php echo AZ_ADMIN . '/dashboard.php'; ?>">Dashboard Home</a></li>
              <li class="active"><a href="<?php echo AZ_ADMIN . '/markerman.php'; ?>">Manage Markers</a></li>
              <!--<li><a href="<?php //echo $dir->gHUrl() . "/cms/userinfo.php?user=$session->username" ?>">My Account</a></li>-->
              <!--<li><a href="<?php //echo $dir->gHUrl() . "/cms/useredit.php" ?>">Edit Account</a></li>-->
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
</div>        