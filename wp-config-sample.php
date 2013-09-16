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
define('DB_NAME', 'startupandplay_com');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Update Site URLs **/
define('WP_HOME','http://startupandplay.dev');
define('WP_SITEURL','http://startupandplay.dev');

/** Stop CF7 from loading on every page */
define('WPCF7_LOAD_JS', false);
define('WPCF7_LOAD_CSS', false);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'qR45qUr(Y? 4b B}vKCvOO]v-w@} 4pN;m/&H4>5&#!51o+!CU35^@HU9,T-Epff');
define('SECURE_AUTH_KEY',  ' ~bWyV+-Ok#,@9zHic/uv%>;L8d-zduV7ee]TCp9C-ex.|$SyPy{Z|^ZuO?je4b9');
define('LOGGED_IN_KEY',    'nB]+YWPd+{hUuMgQ_|ci[ZD|.L|P0 #:i-b!YfT]SqWRNdVBuUsD pB(+=S/+Oy#');
define('NONCE_KEY',        'DwlVM/R0pQ~c.+cm`^yHskKF!wfcIu~h4Tc(zk.1rLpX>U6?4Sgn/2KcxO-u1[C{');
define('AUTH_SALT',        ']ns&izIfgckC|qv53cm8z*-5Q`Oru}|@};-tN n+vRkG#EYIYFgqY-=Ir`Yvt1S_');
define('SECURE_AUTH_SALT', '8u<P&nX4r>bU@BiL1NB-+fu-NOZnkQPdUQJb;%>2zw#2`csoqe0VP_`mD@gS=R-$');
define('LOGGED_IN_SALT',   '1W*o82Lg?%j0IOx{QkP0]C}gLl`#+Ox)+~08b;P@%^ -P<{dR=X]W0VaIVJ36_l?');
define('NONCE_SALT',       '9o^7HO4r-qBZwXDtje_5Ul`F1aXg9?;!Edq{o:|/O8&/putCVun J|9@v,Q0D}[i');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_42qdg8_';

/**
* Limits total Post Revisions saved per Post/Page.
* Change or comment this line out if you would like to increase or remove the limit.
*/
define('WP_POST_REVISIONS',  false);

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
