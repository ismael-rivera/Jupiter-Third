<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
 
function get_config_consts($build){

    if($build == 'development'){
	  $settings = array('wp_ariz_map', 'root', '5es6rx6no8322zs', 'localhost');
	} 
elseif($build == 'testing'){
	  $settings = array('bestprp0_wp_ariz_map', 'bestprp0_isma153', '5es6rx6no8322zs', 'localhost');
	}	
elseif($build == 'production'){
	  $settings = array('gustavo_aclu_arizmap', 'gustavo_azmap', '99xNFJZfznxgi', 'localhost');
	}  

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $settings[0]);

/** MySQL database username */
define('DB_USER', $settings[1]);

/** MySQL database password */
define('DB_PASSWORD', $settings[2]);

/** MySQL hostname */
define('DB_HOST', $settings[3]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
}

get_config_consts('development');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
 
define('AUTH_KEY',         '~^Y1~6ZYAu/BEY#|%K#w1|{0f%.beL&mx!f=Tr8*KO]mFtuL}s8-NA99dB/A TSp');
define('SECURE_AUTH_KEY',  '`?a49[OL5m|5LMB<`WK}m7%>=1v6K-&3}fI3!KKJ*FHD8j+!<b4<-W/yem6sxQaW');
define('LOGGED_IN_KEY',    'PO@Lcw_V!8Bypc4(Y(;_judw1zXW(uv{K20]xb5m:a`}jF~9)G^30)+p;!4/z$V!');
define('NONCE_KEY',        'PnLG$T)[^]?Ma9Dk{fXragwa,+V/xXgwS|z.ydNj59|NHR{~VwV-**-1J8xel@mF');
define('AUTH_SALT',        'r][;;K{[kg L[iUA%I>,4Au{aK-3h!s[RHQ~F-a5UIN;,?T*R+E3=D`jD6+UE|C5');
define('SECURE_AUTH_SALT', 'J6XMT_,D_9;}Uh1>.u$tgy8t~> !=;;sdeu=w17v]FnVJf(QsYe%#>NfX/(4u_/d');
define('LOGGED_IN_SALT',   '<bNL*4PnqQh8SP.4-s)HIM(|&Mnn%DA{^@Ou+3~C+/2<dQA0@-.*<shUom3-L( n');
define('NONCE_SALT',       'D<Y*XYLZ>G( =!pEb[S-Fi|~<jYSsATiVfd9iQm+Y/1Zt?Jrri-bVpb+J-MI}-Ie');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_yem6sxQaW_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);


define ('WP_POST_REVISIONS', 0);
define('AUTOSAVE_INTERVAL', 600); 

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
