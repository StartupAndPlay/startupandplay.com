<?php
include('inc/init.php');
include('inc/assets.php');
include('inc/login.php');
include('inc/admin.php');

$google_analytics_id = 'UA-47970028-1';


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

// Navigation Menu Array
register_nav_menus( array(
  'main' => 'Main Navigation'
) );

function get_excerpt($count){
	$excerpt = get_the_content();
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $count);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = $excerpt.'...';
	return $excerpt;
}

?>