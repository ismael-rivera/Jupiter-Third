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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'bestprp0_hambanine_wp_db');

/** MySQL database username */
define('DB_USER', 'bestprp0_isma153');

/** MySQL database password */
define('DB_PASSWORD', '5es6rx6no8322zs');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Ikbdr}ej]*6z3P,rh-:6W+&%r8S^A_@Ec[&)+8[5?ba&56)X5fiFrKI;$4(*;aLP');
define('SECURE_AUTH_KEY',  '=#QW-,S%dKpiRO]2]B{vqT]rs+gSat<?UI_q8%l%3cI|^WftSu8~i|&sfNdVl,+]');
define('LOGGED_IN_KEY',    'M+(1(VWIQCZ+CeMW<?9~LaOZl%E ++4r}G6Bd>yp6,Dw~GbWCt%zW;od>HrCeHNj');
define('NONCE_KEY',        '&DqnLP3Bs-n$eA>ShCdZNN_4O~~hunxAPHs=QP=QEDyI247E*A@{+pH8MjCyB>,Y');
define('AUTH_SALT',        'H/U(Pu]hpc|g]T&}w~D|T]>zJ|pw2R,G|VDHgR#^k`|bp!;QQ0u`c+-LOTt+wm-H');
define('SECURE_AUTH_SALT', 'jfQ%yjD<7Na> _,ntnq,2@j~l&+U-|5}A#eq>eDpQ=xve0]HD(@glhpRF~O=|7AN');
define('LOGGED_IN_SALT',   '>4z0[|8>F&./K%7G^49iu3[V|%7Jlm;DTxN,Sp8M/*GO0Kmm2`|>MHQcK[4Ex*}D');
define('NONCE_SALT',       'cn:|0ym,Tb?lfe~+1s#!#Tu:w7Y0ny+bQK$2KG/|X(2||&Bq.kox{iK7spw=&J:2');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_jk79td5hko42_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
