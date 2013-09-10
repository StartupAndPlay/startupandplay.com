<?php
/*
Plugin Name: Twitter Connect
Description: Connect to Twitter using the 1.1 API. Fixed bugs from original plugin, added custom styles, and removed branding.
Author: Patrick Shampine
Version: 1.0
Author URI: http://patrickshampine.com

    //Modified this plugin for Startup And Play
    Original Plugin: Nextend Twitter Connect
    Original Author: Roland Soos
    Original Version: 1.4.60
    Original Description: Twitter connect.
    Original Author URI: http://nextendweb.com/
    Original License: GPL2

/*  Copyright 2012  Roland Soos - Nextend  (email : roland@nextendweb.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('NEW_TWITTER_LOGIN', 1);
if (!defined('NEW_TWITTER_LOGIN_PLUGIN_BASENAME')) define('NEW_TWITTER_LOGIN_PLUGIN_BASENAME', plugin_basename(__FILE__));
$new_twitter_settings = maybe_unserialize(get_option('nextend_twitter_connect'));

if(!function_exists('nextend_uniqid')){
    function nextend_uniqid(){
        if(isset($_COOKIE['nextend_uniqid'])){
            if(get_site_transient('n_'.$_COOKIE['nextend_uniqid']) !== false){
                return $_COOKIE['nextend_uniqid'];
            }
        }
        $_COOKIE['nextend_uniqid'] = uniqid('nextend', true);
        setcookie('nextend_uniqid', $_COOKIE['nextend_uniqid'], time() + 3600, '/');
        set_site_transient('n_'.$_COOKIE['nextend_uniqid'], 1, 3600);
        
        return $_COOKIE['nextend_uniqid'];
    }
}

/*
Creating the required table on installation
*/

function new_twitter_connect_install() {

  global $wpdb;
  $table_name = $wpdb->prefix . "social_users";
  $sql = "CREATE TABLE $table_name (
    `ID` int(11) NOT NULL,
    `type` varchar(20) NOT NULL,
    `identifier` varchar(100) NOT NULL,
    KEY `ID` (`ID`,`type`)
  );";
  require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
register_activation_hook(__FILE__, 'new_twitter_connect_install');

/*
Adding query vars for the WP parser
*/

function new_twitter_add_query_var() {

  global $wp;
  $wp->add_query_var('editProfileRedirect');
  $wp->add_query_var('loginTwitter');
}
add_filter('init', 'new_twitter_add_query_var');

/* -----------------------------------------------------------------------------
Main function to handle the Sign in/Register/Linking process
----------------------------------------------------------------------------- */

/*
Compatibility for older versions
*/
add_action('parse_request', 'new_twitter_login_compat');

function new_twitter_login_compat() {

  global $wp;
  if ($wp->request == 'loginTwitter' || isset($wp->query_vars['loginTwitter'])) {
    new_twitter_login_action();
  }
}

/*
For login page
*/
add_action('login_init', 'new_twitter_login');

function new_twitter_login() {

  if ($_REQUEST['loginTwitter'] == '1') {
    new_twitter_login_action();
  }
}

function new_twitter_login_action() {

  global $wp, $wpdb, $new_twitter_settings;
  if (isset($_GET['action']) && $_GET['action'] == 'unlink') {
    $user_info = wp_get_current_user();
    if ($user_info->ID) {
      $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'social_users
        WHERE ID = %d
        AND type = \'twitter\'', $user_info->ID));
      set_site_transient($user_info->ID.'_new_twitter_admin_notice', __('Your Twitter profile is successfully unlinked from your account.', 'nextend-twitter-connect'), 3600);
    }
    new_twitter_redirect();
  }
  require (dirname(__FILE__) . '/sdk/init.php');
  $here = new_twitter_login_url();
  $access_token = get_site_transient( nextend_uniqid().'_twitter_at');
  
  $oauth = get_site_transient( nextend_uniqid().'_twitter_o');
  
  if ($access_token !== false) {
    $tmhOAuth->config['user_token'] = $access_token['oauth_token'];
    $tmhOAuth->config['user_secret'] = $access_token['oauth_token_secret'];
    $code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/account/verify_credentials'));
    if ($code == 401) {
      $code = tmhUtilities::auto_fix_time_request($tmhOAuth, 'GET', $tmhOAuth->url('1.1/account/verify_credentials'));
    }
    if ($code == 200) {
      $resp = json_decode($tmhOAuth->response['response']);
      $ID = $wpdb->get_var($wpdb->prepare('
        SELECT ID FROM ' . $wpdb->prefix . 'social_users WHERE type = "twitter" AND identifier = "%d"
      ', $resp->id));
      if (!get_user_by('id', $ID)) {
        $wpdb->query($wpdb->prepare('
          DELETE FROM ' . $wpdb->prefix . 'social_users WHERE ID = "%d"
        ', $ID));
        $ID = null;
      }
      if (!is_user_logged_in()) {
        if ($ID == NULL) { // Register

          $email = new_twitter_request_email();
          if ($ID == false) { // Real register

            require_once (ABSPATH . WPINC . '/registration.php');
            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
            if (!isset($new_twitter_settings['twitter_user_prefix'])) $new_twitter_settings['twitter_user_prefix'] = 'Twitter - ';
            $sanitized_user_login = sanitize_user($new_twitter_settings['twitter_user_prefix'] . $resp->screen_name);
            if (!validate_username($sanitized_user_login)) {
              $sanitized_user_login = sanitize_user('twitter' . $user_profile['id']);
            }
            $defaul_user_name = $sanitized_user_login;
            $i = 1;
            while (username_exists($sanitized_user_login)) {
              $sanitized_user_login = $defaul_user_name . $i;
              $i++;
            }
            $ID = wp_create_user($sanitized_user_login, $random_password, $email);
            if (!is_wp_error($ID)) {
              wp_new_user_notification($ID, $random_password);
              $user_info = get_userdata($ID);
              wp_update_user(array(
                'ID' => $ID,
                'display_name' => $resp->name,
                'twitter' => $resp->screen_name,
                'description' => $resp->description 
              ));
              do_action('nextend_twitter_user_registered', $ID, $resp, $tmhOAuth);
            } else {
              return;
            }
          }
          if ($ID) {
            $wpdb->insert($wpdb->prefix . 'social_users', array(
              'ID' => $ID,
              'type' => 'twitter',
              'identifier' => $resp->id,
            ) , array(
              '%d',
              '%s',
              '%s'
            ));
          }
          if (isset($new_twitter_settings['twitter_redirect_reg']) && $new_twitter_settings['twitter_redirect_reg'] != '' && $new_twitter_settings['twitter_redirect_reg'] != 'auto') {
            set_site_transient( nextend_uniqid().'_twitter_r', $new_twitter_settings['twitter_redirect_reg'], 3600);
          }
        }
        if ($ID) { // Login

          $secure_cookie = is_ssl();
          $secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
          global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie

          $auth_secure_cookie = $secure_cookie;
          wp_set_auth_cookie($ID, true, $secure_cookie);
          $user_info = get_userdata($ID);
          do_action('wp_login', $user_info->user_login, $user_info);
                      
            // Twitter User Profile Picture -- FIXED VERSION
            $profile_img_url = $resp->profile_image_url_https; // Calls 48x48 https URL
            $original_img_short = substr($profile_img_url, 0, -11); //deletes 11 chars from end
            $original_img_final = $original_img_short .".png"; // Adds .png back to URL
            
        update_user_meta($ID, 'twitter_profile_picture', $original_img_final);

          do_action('nextend_twitter_user_logged_in', $ID, $resp, $tmhOAuth);
        }
      } else {
        if (new_twitter_is_user_connected()) {

          // It was a simple login
          
        } elseif ($ID === NULL) { // Let's connect the account to the current user!

          $current_user = wp_get_current_user();
          $wpdb->insert($wpdb->prefix . 'social_users', array(
            'ID' => $current_user->ID,
            'type' => 'twitter',
            'identifier' => $resp->id
          ) , array(
            '%d',
            '%s',
            '%s'
          ));
          do_action('nextend_twitter_user_account_linked', $ID, $resp, $tmhOAuth);
          $user_info = wp_get_current_user();
          set_site_transient($user_info->ID.'_new_twitter_admin_notice', __('Your Twitter profile is successfully linked with your account. Now you can sign in with Twitter easily.', 'nextend-twitter-connect'), 3600);
        } else {
          $user_info = wp_get_current_user();
          set_site_transient($user_info->ID.'_new_twitter_admin_notice', __('This Twitter profile is already linked with other account. Linking process failed!', 'nextend-twitter-connect'), 3600);
        }
      }
      new_twitter_redirect();
    } else {

      echo "Twitter Error 3";
      exit;
    }

    // we're being called back by Twitter
    
  } elseif ($oauth !== false && isset($_REQUEST['oauth_verifier'])) {
    $tmhOAuth->config['user_token'] = $oauth['oauth_token'];
    $tmhOAuth->config['user_secret'] = $oauth['oauth_token_secret'];
    $params = array(
      'oauth_verifier' => $_REQUEST['oauth_verifier']
    );
    $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', '') , $params);
    if ($code == 401) {
      $code = tmhUtilities::auto_fix_time_request($tmhOAuth, 'POST', $tmhOAuth->url('oauth/access_token', '') , $params);
    }
    if ($code == 200) {
      $access_token = $tmhOAuth->extract_params($tmhOAuth->response['response']);
      set_site_transient( nextend_uniqid().'_twitter_at', $access_token, 3600);
      delete_site_transient(nextend_uniqid().'_twitter_o');
      header("Location: ".$here);
      exit;
    } else {
      echo "Twitter Error 2";
      exit;
    }

    // start the OAuth dance
    
  } else {
    if (isset($new_twitter_settings['twitter_redirect']) && $new_twitter_settings['twitter_redirect'] != '' && $new_twitter_settings['twitter_redirect'] != 'auto') {
      $_GET['redirect'] = $new_twitter_settings['twitter_redirect'];
    }
    if (isset($_GET['redirect'])) {
      set_site_transient( nextend_uniqid().'_twitter_r', $_GET['redirect'], 3600);
    }
    $redirect = get_site_transient( nextend_uniqid().'_twitter_r');
    if ($redirect == '' || $redirect == new_twitter_login_url()) {
      $redirect = site_url();
      set_site_transient( nextend_uniqid().'_twitter_r', $redirect, 3600);
    }
    $callback = $here;
    $params = array(
      'oauth_callback' => $callback
    );
    if (isset($_REQUEST['force_read'])):
      $params['x_auth_access_type'] = 'read';
    endif;
    $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', '') , $params);
    if ($code == 401) {
      $code = tmhUtilities::auto_fix_time_request($tmhOAuth, 'POST', $tmhOAuth->url('oauth/request_token', '') , $params);
    }
    if ($code == 200) {
      $oauth = $tmhOAuth->extract_params($tmhOAuth->response['response']);
      set_site_transient( nextend_uniqid().'_twitter_o', $oauth, 3600);
      $method = 'authenticate';
      $force = isset($_REQUEST['force']) ? '&force_login=1' : '';
      $authurl = $tmhOAuth->url("oauth/{$method}", '') . "?oauth_token={$oauth['oauth_token']}{$force}";
      header('Location: ' . $authurl);
      exit;
    } else {

      //print_r($tmhOAuth);
      echo "Twitter Error 1";
      exit;
    }
  }
}

/*
This function request valid email from Twitter users
*/

function new_twitter_request_email() {
  $user_email = '';
  $errors = new WP_Error();
  if(isset($_POST['user_email'])){
      $user_email = $_POST['user_email'];
      if ($user_email == '') {
        $errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.'));
      } elseif (!is_email($user_email)) {
        $errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.'));
        $user_email = '';
      } elseif (email_exists($user_email)) {
        $errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.'));
      }
      if (isset($_POST['user_email']) && $errors->get_error_code() == '') {
        return $user_email;
      }
  }

  login_header(__('Registration Form') , '<p class="register">' . __('Enter your email address') . '</p>', $errors);
?>
  <form name="registerform" id="registerform" action="<?php echo esc_url(site_url('wp-login.php?loginTwitter=1', 'login_post')); ?>" method="post">
          <p>
              <input type="email" name="user_email" id="user_email" class="input" placeholder="Email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" tabindex="20" />
          </p>
          <p id="reg_passmail"><?php _e('') ?></p>
          <br class="clear" />
          <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="btn btn-small" value="<?php esc_attr_e('Create Profile'); ?>" tabindex="100" /></p>
  </form>
  <?php
  login_footer('user_login');
  exit;
}

/*
Is the current user connected the Twitter profile?
*/

function new_twitter_is_user_connected() {

  global $wpdb;
  $current_user = wp_get_current_user();
  $ID = $wpdb->get_var($wpdb->prepare('
    SELECT identifier FROM ' . $wpdb->prefix . 'social_users WHERE type = "twitter" AND ID = "%d"
  ', $current_user->ID));
  if ($ID === NULL) return false;
  return $ID;
}

function new_add_twitter_login_form() {

?>
  <script>
  if(jQuery.type(has_social_form) === "undefined"){
    var has_social_form = false;
    var socialLogins = null;
  }
  jQuery(document).ready(function(){
    (function($) {
      if(!has_social_form){
        has_social_form = true;
        var loginForm = $('#loginform,#registerform,#front-login-form');
        socialLogins = $('<div class="btn btn-large" style="text-align: center;"><div style="clear:both;"></div></div>');
        if(loginForm.find('input').length > 0)
        loginForm.prepend(socialLogins);
      }
      if(!window.twitter_added){
        socialLogins.prepend('<?php echo addslashes(preg_replace('/^\s+|\n|\r|\s+$/m', '', new_twitter_sign_button())); ?>');
        window.twitter_added = true;
      }
    }(jQuery));
  });
  </script>
  <?php
}
add_action('login_form', 'new_add_twitter_login_form');
add_action('register_form', 'new_add_twitter_login_form');
add_action('bp_sidebar_login_form', 'new_add_twitter_login_form');
add_filter('get_avatar', 'new_twitter_insert_avatar', 5, 5);

function new_twitter_insert_avatar($avatar = '', $id_or_email, $size = 96, $default = '', $alt = false) {

  $id = 0;
  if (is_numeric($id_or_email)) {
    $id = $id_or_email;
  } else if (is_string($id_or_email)) {
    $u = get_user_by('email', $id_or_email);
    $id = $u->id;
  } else if (is_object($id_or_email)) {
    $id = $id_or_email->user_id;
  }
  if ($id == 0) return $avatar;
  $pic = get_user_meta($id, 'twitter_profile_picture', true);
  if (!$pic || $pic == '') return $avatar;
  $avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);
  return $avatar;
}

add_filter('bp_core_fetch_avatar', 'new_twitter_bp_insert_avatar', 3, 5);

function new_twitter_bp_insert_avatar($avatar = '', $params, $id) {
    if(!is_numeric($id) || strpos($avatar, 'gravatar') === false) return $avatar;
    $pic = get_user_meta($id, 'twitter_profile_picture', true);
    if (!$pic || $pic == '') return $avatar;
    $avatar = preg_replace('/src=("|\').*?("|\')/i', 'src=\'' . $pic . '\'', $avatar);
    return $avatar;
}

/*
Options Page
*/
require_once (trailingslashit(dirname(__FILE__)) . "nextend-twitter-settings.php");
if (class_exists('NextendTwitterSettings')) {
  $nextendtwittersettings = new NextendTwitterSettings();
  if (isset($nextendtwittersettings)) {
    add_action('admin_menu', array(&$nextendtwittersettings,
      'NextendTwitter_Menu'
    ) , 1);
  }
}
add_filter('plugin_action_links', 'new_twitter_plugin_action_links', 10, 2);

function new_twitter_plugin_action_links($links, $file) {

  if ($file != NEW_TWITTER_LOGIN_PLUGIN_BASENAME) return $links;
  $settings_link = '<a href="' . menu_page_url('nextend-twitter-connect', false) . '">' . esc_html(__('Settings', 'nextend-twitter-connect')) . '</a>';
  array_unshift($links, $settings_link);
  return $links;
}

/* -----------------------------------------------------------------------------
Miscellaneous functions
----------------------------------------------------------------------------- */

function new_twitter_sign_button() {

  global $new_twitter_settings;
  return '<a href="' . new_twitter_login_url() . (isset($_GET['redirect_to']) ? '&redirect=' . $_GET['redirect_to'] : '') . '" class="twitter-login" rel="nofollow">' . $new_twitter_settings['twitter_login_button'] . '</a><br />';
}

function new_twitter_link_button() {

  global $new_twitter_settings;
  return '<a href="' . new_twitter_login_url() . '&redirect=' . new_twitter_curPageURL() . '">' . $new_twitter_settings['twitter_link_button'] . '</a><br />';
}

function new_twitter_unlink_button() {

  global $new_twitter_settings;
  return '<a href="' . new_twitter_login_url() . '&action=unlink&redirect=' . new_twitter_curPageURL() . '">' . $new_twitter_settings['twitter_unlink_button'] . '</a><br />';
}

function new_twitter_login_url() {

  return site_url('wp-login.php') . '?loginTwitter=1';
}

function new_twitter_curPageURL() {

  $pageURL = 'http';
  if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $pageURL.= "s";
  }
  $pageURL.= "://";
  if ($_SERVER["SERVER_PORT"] != "80") {
    $pageURL.= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  } else {
    $pageURL.= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}

function new_twitter_redirect() {
  
  $redirect = get_site_transient( nextend_uniqid().'_twitter_r');

  if (!$redirect || $redirect == '' || $redirect == new_twitter_login_url()) {
    if (isset($_GET['redirect'])) {
      $redirect = $_GET['redirect'];
    } else {
      $redirect = site_url();
    }
  }
  header('LOCATION: ' . $redirect);
  delete_site_transient( nextend_uniqid().'_twitter_r');
  exit;
}

function new_twitter_edit_profile_redirect() {

  global $wp;
  if (isset($wp->query_vars['editProfileRedirect'])) {
    if (function_exists('bp_loggedin_user_domain')) {
      header('LOCATION: ' . bp_loggedin_user_domain() . 'profile/edit/group/1/');
    } else {
      header('LOCATION: ' . self_admin_url('profile.php'));
    }
    exit;
  }
}
add_action('parse_request', 'new_twitter_edit_profile_redirect');

function new_twitter_jquery() {

  wp_enqueue_script('jquery');
}
add_action('login_form_login', 'new_twitter_jquery');
add_action('login_form_register', 'new_twitter_jquery');

/*
Session notices used in the profile settings
*/

function new_twitter_admin_notice() {

  $user_info = wp_get_current_user();
  $notice = get_site_transient($user_info->ID.'_new_twitter_admin_notice');
  if ($notice !== false) {
    echo '<div class="updated">
       <p>' . $notice . '</p>
    </div>';
    delete_site_transient($user_info->ID.'_new_twitter_admin_notice');
  }
}
add_action('admin_notices', 'new_twitter_admin_notice');



/*
  Core functions, which should be included when wp-login.php is NOT used...
*/
if(!function_exists('login_header')){
    /**
     * Outputs the header for the login page.
     *
     * @uses do_action() Calls the 'login_head' for outputting HTML in the Log In
     *		header.
     * @uses apply_filters() Calls 'login_headerurl' for the top login link.
     * @uses apply_filters() Calls 'login_headertitle' for the top login title.
     * @uses apply_filters() Calls 'login_message' on the message to display in the
     *		header.
     * @uses $error The error global, which is checked for displaying errors.
     *
     * @param string $title Optional. WordPress Log In Page title to display in
     *		<title/> element.
     * @param string $message Optional. Message to display in header.
     * @param WP_Error $wp_error Optional. WordPress Error Object
     */
    function login_header($title = 'Log In', $message = '', $wp_error = '') {
    	global $error, $interim_login, $current_site, $action;
    
    	// Don't index any of these forms
    	add_action( 'login_head', 'wp_no_robots' );
    
    	if ( empty($wp_error) )
    		$wp_error = new WP_Error();
    
    	// Shake it!
    	$shake_error_codes = array( 'empty_password', 'empty_email', 'invalid_email', 'invalidcombo', 'empty_username', 'invalid_username', 'incorrect_password' );
    	$shake_error_codes = apply_filters( 'shake_error_codes', $shake_error_codes );
    
    	if ( $shake_error_codes && $wp_error->get_error_code() && in_array( $wp_error->get_error_code(), $shake_error_codes ) )
    		add_action( 'login_head', 'wp_shake_js', 12 );
    
    	?><!DOCTYPE html>
    	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
    	<head>
    	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    	<title><?php bloginfo('name'); ?> &rsaquo; <?php echo $title; ?></title>
    	<?php
    
    	wp_admin_css( 'wp-admin', true );
    	wp_admin_css( 'colors-fresh', true );
    
    	if ( wp_is_mobile() ) { ?>
    		<meta name="viewport" content="width=320; initial-scale=0.9; maximum-scale=1.0; user-scalable=0;" /><?php
    	}
    
    	do_action( 'login_enqueue_scripts' );
    	do_action( 'login_head' );
    
    	if ( is_multisite() ) {
    		$login_header_url   = network_home_url();
    		$login_header_title = $current_site->site_name;
    	} else {
    		$login_header_url   = __( 'http://wordpress.org/' );
    		$login_header_title = __( 'Powered by WordPress' );
    	}
    
    	$login_header_url   = apply_filters( 'login_headerurl',   $login_header_url   );
    	$login_header_title = apply_filters( 'login_headertitle', $login_header_title );
    
    	// Don't allow interim logins to navigate away from the page.
    	if ( $interim_login )
    		$login_header_url = '#';
    
    	$classes = array( 'login-action-' . $action, 'wp-core-ui' );
    	if ( wp_is_mobile() )
    		$classes[] = 'mobile';
    	if ( is_rtl() )
    		$classes[] = 'rtl';
    	$classes = apply_filters( 'login_body_class', $classes, $action );
    	?>
    	</head>
    	<body class="login <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
    	<div id="login">
    		<h1><a href="<?php echo esc_url( $login_header_url ); ?>" title="<?php echo esc_attr( $login_header_title ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
    	<?php
    
    	unset( $login_header_url, $login_header_title );
    
    	$message = apply_filters('login_message', $message);
    	if ( !empty( $message ) )
    		echo $message . "\n";
    
    	// In case a plugin uses $error rather than the $wp_errors object
    	if ( !empty( $error ) ) {
    		$wp_error->add('error', $error);
    		unset($error);
    	}
    
    	if ( $wp_error->get_error_code() ) {
    		$errors = '';
    		$messages = '';
    		foreach ( $wp_error->get_error_codes() as $code ) {
    			$severity = $wp_error->get_error_data($code);
    			foreach ( $wp_error->get_error_messages($code) as $error ) {
    				if ( 'message' == $severity )
    					$messages .= '	' . $error . "<br />\n";
    				else
    					$errors .= '	' . $error . "<br />\n";
    			}
    		}
    		if ( !empty($errors) )
    			echo '<div id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
    		if ( !empty($messages) )
    			echo '<p class="message">' . apply_filters('login_messages', $messages) . "</p>\n";
    	}
    } // End of login_header()
    
    /**
     * Outputs the footer for the login page.
     *
     * @param string $input_id Which input to auto-focus
     */
    function login_footer($input_id = '') {
    	global $interim_login;
    
    	// Don't allow interim logins to navigate away from the page.
    	if ( ! $interim_login ): ?>
    	<p id="backtoblog"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Are you lost?' ); ?>"><?php printf( __( '&larr; Back to %s' ), get_bloginfo( 'title', 'display' ) ); ?></a></p>
    	<?php endif; ?>
    
    	</div>
    
    	<?php if ( !empty($input_id) ) : ?>
    	<script type="text/javascript">
    	try{document.getElementById('<?php echo $input_id; ?>').focus();}catch(e){}
    	if(typeof wpOnload=='function')wpOnload();
    	</script>
    	<?php endif; ?>
    
    	<?php do_action('login_footer'); ?>
    	<div class="clear"></div>
    	</body>
    	</html>
    	<?php
    }
}