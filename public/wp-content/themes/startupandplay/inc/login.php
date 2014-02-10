<?php

// Loads CSS & Webfonts for WP login/registration pages
function login_scripts() {
  wp_enqueue_script('jquery-api', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, null, true);
  wp_enqueue_script('login', get_bloginfo('template_directory').'/login/login.js', array('jquery'), null, true);
}
function login_css() {
	wp_enqueue_style('font-awesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
	wp_enqueue_style('fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600');
	wp_enqueue_style('login', get_bloginfo('template_directory').'/login/login.css');
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