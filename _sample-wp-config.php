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

$default = array(
        'name'     => 'default',
        'hostname' => 'http://startupandplay.com',
        'debug'    => false,
        'db_name'  => 'not_versioned',
        'db_user'  => 'not_versioned',
        'db_pass'  => 'not_versioned', 
        'db_host'  => 'localhost',
        'password_protect' => false
);

$local = array_merge($default, array(
        'name'     => 'local',
        'hostname' => 'http://startupandplay.dev',
        'debug'    => true,
				'db_name'  => 'startupandplay_dev',
        'db_user'  => 'root',
        'db_pass'  => 'password'
));

$staging = array_merge($default, array(
        'name'     => 'staging',
        'hostname' => 'not_versioned',
        'debug'    => true,
				'db_name'  => 'not_versioned',
				'password_protect'  => true
		
));

$production = array_merge($default, array(
        'name'     => 'production',
));


if ( file_exists( dirname( __FILE__ ) . '/env_local' ) ) {        

        // Local Environment
        $environment = $local;

} elseif ( file_exists( dirname( __FILE__ ) . '/env_staging' ) ) {        
        
        // Staging Environment
        $environment = $staging;
	
				define('FS_METHOD', 'direct');

} else {        
        
        // Production Environment
        $environment = $production;
	
				define('FS_METHOD', 'direct');
				define('WP_CACHE', true);
				define('WPCACHEHOME', 'not_versioned');

}

define('WP_ENV',   $environment['name']);
define('WP_DEBUG', $environment['debug']);
define('WP_PASSWORD_PROTECT', $environment['password_protect']);

define('WP_HOME',    $environment['hostname']);
define('WP_SITEURL', $environment['hostname']);

/** Stop CF7 from loading on every page */
define('WPCF7_LOAD_JS', false);
define('WPCF7_LOAD_CSS', false);

define('DB_NAME',  $environment['db_name']);
define('DB_USER',  $environment['db_user']);
define('DB_HOST',  $environment['db_host']);
define('DB_PASSWORD', $environment['db_pass']);

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
define('AUTH_KEY',         'not_versioned');
define('SECURE_AUTH_KEY',  'not_versioned');
define('LOGGED_IN_KEY',    'not_versioned');
define('NONCE_KEY',        'not_versioned');
define('AUTH_SALT',        'not_versioned');
define('SECURE_AUTH_SALT', 'not_versioned');
define('LOGGED_IN_SALT',   'not_versioned');
define('NONCE_SALT',       'not_versioned');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'not_versioned';

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


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
        define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');