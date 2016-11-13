<?php

/*You can change settings here for the root url*/
$home_site        = 'http://localhost22';
/*You can change settings here for which folder to use as admin*/
$cms_folder       = 'admin';
/*You can change settings here from development files to production files*/
$source_ver       = 'development';
/*This is the plug and play folder for different lightweight libraries and framework tools like fancybox*/
$plugins_folder   = 'plugins';
/*This folder needs to be secure and inaccessible through the browser*/
$applications_folder = 'application';

function product_version($build){
	if($build == 'production'){
		return '/prod';
		}
	else if($build == 'development'){	
	    return '/dev';
		}
	else { echo 'Please specify the Product Version in configuration files'; 
	}	
}

define('AZ_HOME_URL', $home_site);
define('AZ_HURL_NO_WRAP', str_replace("http://", "", $home_site));
define('AZ_BACKEND', $home_site . '/backend');
define('AZ_ADMIN', $home_site . '/backend/' . $cms_folder);
define('AZ_DASHBOARD', AZ_ADMIN . '/dashboard.php');
define('AZ_INCS_BASE', '/incs');
define('AZ_IDFC', $home_site . '/incs/dev/fe/css');
define('AZ_IDFI', $home_site . '/incs/dev/fe/img');
define('AZ_IDFJ', $home_site . '/incs/dev/fe/js');
define('AZ_IDBC', $home_site . '/incs/dev/be/css');
define('AZ_IDBI', $home_site . '/incs/dev/be/img');
define('AZ_IDBJ', $home_site . '/incs/dev/be/js');
define('AZ_IDLC', $home_site . '/incs/dev/lib/css');
define('AZ_IDLI', $home_site . '/incs/dev/lib/img');
define('AZ_IDLJ', $home_site . '/incs/dev/lib/js');
define('AZ_INC_FORMAT', product_version($source_ver));
define('AZ_INCS', AZ_HOME_URL . AZ_INCS_BASE . AZ_INC_FORMAT);
define('AZ_PLUGINS', $home_site . '/' . $plugins_folder);
define('AZ_APP', $home_site . '/' . $applications_folder);



// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database*/
define('DB_NAME', 'ariz_map_23dsu88duyi');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASS', '5es6rx6no8322zs');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


// Run the actual connection here 

$db = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die ("could not connect to mysql");
mysql_select_db(DB_NAME, $db) or die ("no database");
//(...)
if($db == false){
    //try to reconnect
	echo "<h1>Unable To Connect To Database</h1>";  
// if no success the script would have died before this success message

} else {
	
	//echo "<h1>Success in database connection! Happy Coding!</h1>";  
// if no success the script would have died before this success message

	}            
