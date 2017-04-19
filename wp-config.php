<?php
@ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link    https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bankofproperties');

/** MySQL database username */
define( 'DB_USER', 'bankofproperties');

/** MySQL database password */
define( 'DB_PASSWORD', 'calendar9shape73');

/** MySQL hostname */
define( 'DB_HOST', 'mysqlh.studiocoast.com.au');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WP_HOME','https://bankofproperties.co/');
define('WP_SITEURL','https://bankofproperties.co/');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', '!>O_C(e2ooCyjxa}d_ANsdjd{Qj|kixT#ca4U|JWar9}Ni{-Eo|C,m*%ro|Te3uI' );
define( 'SECURE_AUTH_KEY', 'yLAtk!xZ-Z0Y>HqSprX$G^[Bh-}IzeO|^l!JY/OJ&Op|c >%ZO4]kK+< vX,n{( ' );
define( 'LOGGED_IN_KEY', '-mV;?[;!#b4Cc9:}.e(WQ;ge,eHs..W`2st5[_ Q2%*%z+:cM5P#~;47fU0|.2p6' );
define( 'NONCE_KEY', '<nd8C6lSM-sk0]?jOa0NENrp2!jgb^1CNkH0K=xk[t,N-g6uWCF11H<nw)Q6EuYe' );
define( 'AUTH_SALT', '(5Ou$&x P,^4tGbdYAev7;H2!te 6_qCV[!><]OT?yoUVt%6f{)9s|Xx%Cu2P2QN' );
define( 'SECURE_AUTH_SALT', '|!ufXk:o&9NMof8eia=Q7p[l-t7Hku-^&>)& +/2YuA_S(h9j,G(s+-Z+AHU:Aca' );
define( 'LOGGED_IN_SALT', 'Xvft0D_|RT|g~^!b/#B?0v.>?bS*5#>/Wwh/d`oHBmS{4<UVJSe26OF|?Xk`D&#D' );
define( 'NONCE_SALT', 'gF{N+li!1Jy[qU5CbI(>1D.A)OYV_{jA;Bd/kTPjj15I.s8fgs)dCfb5[ed-G#aB' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );


define('WP_MEMORY_LIMIT', '256M');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
