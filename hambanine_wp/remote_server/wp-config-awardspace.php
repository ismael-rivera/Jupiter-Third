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
define('DB_NAME', '1187090_hambanin');

/** MySQL database username */
define('DB_USER', '1187090_hambanin');

/** MySQL database password */
define('DB_PASSWORD', '1Eywo2c9DZTBR');

/** MySQL hostname */
define('DB_HOST', 'pdb10.awardspace.com');

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
define('AUTH_KEY',         '*+p0-DvLsPr9U4HW<!+%`P;0+#vRXKfTmtva`s$K5,MU*+R[sv+3EKSgcL!l0%t{');
define('SECURE_AUTH_KEY',  '|*b]e&A&~A3`,P;Uoi&h2xU~V|QqfCHc57c,FlVOo+9Ej_tvG~-.Oo33x=~/i]No');
define('LOGGED_IN_KEY',    '?bV8D>=2#JJU-k$8LYD{5!t>54o5 };-FY_s{^@+|owxP:n2bK|.km@yL+C[9svz');
define('NONCE_KEY',        'IT7Z8kB?;y-j@-ke@$;s-n8mH*7VK7&-%]@l8D1$ZEy$cifw(EC<>:{-z/Z;C<zf');
define('AUTH_SALT',        'T5jmZW%Xh/+F:+GzKZ])P1b!;TeEzF#s8^u84N>DUOf=?@3THD5E/`qQe%:j! OS');
define('SECURE_AUTH_SALT', '#n q+Wo|oZC(z| |SudT?kpo^=ZD Kw.|O=9@ k]pSm^9f:l$1]6?,~lWx [-Y@|');
define('LOGGED_IN_SALT',   'Fq}uEJL3Rka7 w,36xGP,eB&z<1hT{]:-Og+3U-*<qsKD-Yyp*Y;9b=E([p 5cla');
define('NONCE_SALT',       'J+P9@PAdqIl:#E;i:Bm,%5B-8a(Cb#b3~#<^Qvw_7Ha%YDH2h@02+g;WVp&y*|9m');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
