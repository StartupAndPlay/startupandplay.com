<?php

// Password protect staging environments
if( WP_PASSWORD_PROTECT === true ){
	function password_protect() {
		if ( !is_user_logged_in() ) {
			auth_redirect();
		}
	}
	add_action ('template_redirect', 'password_protect');
}

// Loads Google Analytics
$google_analytics_id = 'UA-XXXXXXXX-X'; // override this value in functions.php
function google_analytics() {
		global $env_default, $google_analytics_id, $environment;
		$environment = $environment['name'];
		$default_hostname = preg_replace('/^https?:\/\//', '', $env_default['hostname']);
		if (WP_ENV === 'production') { ?>
		<!-- Google Analytics -->
			<script type="text/javascript">
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', '<?php echo $google_analytics_id ?>', '<?php echo $default_hostname ?>');
		  ga('send', 'pageview');
			</script><?php
		}
} // google_analytics
add_action('wp_head','google_analytics');

// Removes manifest/rsd/shortlink from wp_head
remove_action( 'wp_head', 'wlwmanifest_link');
remove_action( 'wp_head', 'rsd_link');
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_generator');
remove_action( 'wp_head', 'feed_links' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

// Adds post thumbnails to theme
add_theme_support( 'post-thumbnails' );

// Removes ul class from wp_nav_menu
function remove_ul ( $menu ){
    return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}
add_filter( 'wp_nav_menu', 'remove_ul' );