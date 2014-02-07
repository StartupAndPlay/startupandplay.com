<?php

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