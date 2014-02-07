<?php

// Password Protect
if( WP_PASSWORD_PROTECT == true ){     
	function password_protect() {
  	if ( !is_user_logged_in() ) {
    	auth_redirect();
    }
  }
	add_action ('template_redirect', 'password_protect');
}

// Loads CSS
function stylesheet() {
if ( !is_admin() ) {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_register_style('mmenu', ( get_bloginfo('template_url') . '/css/mmenu.css'));
    wp_enqueue_style('mmenu');
    wp_register_style('font-awesome', ( get_bloginfo('template_url') . '/css/font-awesome.min.css'));
    wp_enqueue_style('font-awesome');
    }
}
add_action('wp_enqueue_scripts','stylesheet');

// Loads Javascript
function javascript() {
if ( !is_admin() ) {
	wp_register_script('modernizr', ( get_bloginfo('template_url') . '/js/modernizr.min.js'));
	wp_enqueue_script('modernizr');
  wp_register_script('youtube-resize', ( get_bloginfo('template_url') . '/js/youtube.resize.js'), array('jquery')); 
  wp_enqueue_script('youtube-resize');
	}
}
add_action('wp_print_scripts','javascript');

// Loads Google Webfonts
function webfonts() {
if ( !is_admin() ) {
    wp_register_style('webfonts', 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600');
    wp_enqueue_style( 'webfonts');
    }
}
add_action('wp_print_styles','webfonts');

// Loads CSS & Webfonts for WP login/registration pages
function login_scripts() {
  $scripts =  '<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/login/font-awesome.min.css" />
    <script src="'.get_bloginfo('template_directory').'/login/jquery.min.js"></script>
    <script src="'.get_bloginfo('template_directory').'/login/login.js"></script>';
  echo $scripts;
}
function login_css() {
echo '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/login/login.css" />';
}
function login_logo_url() {
    return get_bloginfo( 'url' );
}
function login_logo_url_title() {
    return 'Startup and Play';
}
add_action('login_head', 'login_scripts');
add_action('registration_head', 'login_webfonts');
add_action('login_head', 'login_css');
add_action('registration_head', 'login_css');
add_filter( 'login_headerurl', 'login_logo_url' );
add_filter( 'login_headertitle', 'login_logo_url_title' );

// Removes ul class from wp_nav_menu
function remove_ul ( $menu ){
    return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}
add_filter( 'wp_nav_menu', 'remove_ul' );

// Cleanup wp_head
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Disables admin bar everywhere for ALL users
if (!function_exists('disable_admin_bar')) {

	function disable_admin_bar() {
		
		// for the admin page
		remove_action('admin_footer', 'wp_admin_bar_render', 1000);
		// for the front-end
		remove_action('wp_footer', 'wp_admin_bar_render', 1000);
	  	
		// css override for the admin page
		function remove_admin_bar_style_backend() { 
			echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
		}	  
		add_filter('admin_head','remove_admin_bar_style_backend');
		
		// css override for the frontend
		function remove_admin_bar_style_frontend() {
			echo '<style type="text/css" media="screen">
			html { margin-top: 0px !important; }
			* html body { margin-top: 0px !important; }
			</style>';
		}
		add_filter('wp_head','remove_admin_bar_style_frontend', 99);
  	}
}
add_action('init','disable_admin_bar');

// Changes default contact fields
function edit_user_fields( $contactmethods ) {
  $contactmethods['twitter'] = 'Twitter (without @)';
  $contactmethods['googleplus'] = 'Google+ (entire url)';
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  unset($contactmethods['yim']);
  return $contactmethods;
}
add_filter('user_contactmethods','edit_user_fields',10,1);

// Registers Main Navigation Menu
register_nav_menu( 'secondary', 'main-navigation' );

// Adds post thumbnails to theme
add_theme_support ('post-thumbnails');

?>