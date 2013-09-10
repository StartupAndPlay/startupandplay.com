<?php 

// Loads CSS
function styles() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_register_style('mmenu', ( get_bloginfo('template_url') . '/css/mmenu.css'));
    wp_enqueue_style('mmenu');
    wp_register_style('font-awesome', ( get_bloginfo('template_url') . '/css/font-awesome.min.css'));
    wp_enqueue_style('font-awesome');
}
add_action( 'wp_enqueue_scripts', 'styles' );

// Loads Javascript
function startupandplay_scripts() {
if ( !is_admin() ) { // keeps scripts from loading to admin panel
	// Custom Scripts
		// wp_register_script('custom', ( get_bloginfo('template_url') . '/js/custom.js'), array('jquery'));
		// wp_enqueue_script('custom');
        wp_register_script('mmenu', ( get_bloginfo('template_url') . '/js/jquery.mmenu.min.js'), array('jquery'));
		wp_enqueue_script('mmenu');
		wp_register_script('youtube-resize', ( get_bloginfo('template_url') . '/js/youtube.resize.js'), array('jquery')); 
		wp_enqueue_script('youtube-resize');

		/* if ( is_page('contact') ) { // keeps script(s) to load only on specific page
		wp_register_script('gmap', ( get_bloginfo('template_url') . '/js/gmap.js'), array('jquery')); 
		wp_enqueue_script('gmap'); 
		} */
	}
}
add_action( 'wp_print_scripts', 'startupandplay_scripts');

// Loads Google Webfonts
function webfonts() {
    wp_register_style('webfonts', 'http://fonts.googleapis.com/css?family=Open+Sans:300,400,600');
    wp_enqueue_style( 'webfonts');
}
add_action('wp_print_styles', 'webfonts');


// Loads CSS & Webfonts for WP login/registration pages
function login_scripts() {
    $scripts =  '<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet" type="text/css">
                <link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/login/font-awesome.min.css" />
                <script src="'.get_bloginfo('template_directory').'/login/jquery.min.js"></script>
                <script src="'.get_bloginfo('template_directory').'/login/login.js"></script>';
    echo $scripts;
}
add_action('login_head', 'login_scripts');
add_action('registration_head', 'login_webfonts');
function login_css() {
echo '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/login/login.css" />';
}
add_action('login_head', 'login_css');
add_action('registration_head', 'login_css');
function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Startup and Play';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

// Removes ul class from wp_nav_menu
function remove_ul ( $menu ){
    return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}
add_filter( 'wp_nav_menu', 'remove_ul' );

// Adds post thumbnails to theme
add_theme_support ('post-thumbnails');
add_image_size ('dashboard-thumbnail', 200, 200, true);
add_image_size ('single-content-width', 970, 300, true);
add_image_size ('single-full-width', 1680, 600, true);

// Removes manifest from wp_head
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

// Removes shortlink from header
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Disables admin bar everywhere for ALL users
if (!function_exists('df_disable_admin_bar')) {

	function df_disable_admin_bar() {
		
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
add_action('init','df_disable_admin_bar');

// Registers Main Navigation Menu
register_nav_menu( 'secondary', 'main-navigation' );

// Changes default contact fields
function wd_edit_fields( $contactmethods ) {
        //new fields
        $contactmethods['twitter'] = 'Twitter (without @)';
        $contactmethods['googleplus'] = 'Google+ (entire url)';

        unset($contactmethods['aim']);
        unset($contactmethods['jabber']);
        unset($contactmethods['yim']);

        return $contactmethods;
    }
add_filter('user_contactmethods','wd_edit_fields',10,1);

// Get custom field template values
function getCustomField($theField) {
	global $post;
	$block = get_post_meta($post->ID, $theField);
	if($block){
		foreach(($block) as $blocks) {
			echo $blocks;
		}
	}
}

// Add action for collections
add_action( 'init', 'create_collections_hierarchical_taxonomy', 0 );

// Creating the custom taxonomy
function create_collections_hierarchical_taxonomy() {

// GUI labels
  $labels = array(
    'name' => _x( 'Collections', 'taxonomy general name' ),
    'singular_name' => _x( 'Collections', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Collections' ),
    'all_items' => __( 'All Collections' ),
    'parent_item' => __( 'Parent Collection' ),
    'parent_item_colon' => __( 'Parent Collection:' ),
    'edit_item' => __( 'Edit Collection' ), 
    'update_item' => __( 'Update Collection' ),
    'add_new_item' => __( 'Add New Collection' ),
    'new_item_name' => __( 'New Collection Name' ),
    'menu_name' => __( 'Collections' ),
  ); 	

// Registering the taxonomy
  register_taxonomy('collections',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'collections' ),
  ));

}

// Saving cf_Collection custom field data to collections taxonomy
function save_taxonomy_data($post_id) {

    if ( !wp_verify_nonce( $_POST['_wpnonce'], 'wpuf-add-post' )) {
        return $post_id;
    }

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;


    if ( 'page' == $_POST['wpuf_post_type'] ) { // post type is a hidden field in wpuf_
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }

    if ( isset( $_POST['cf_Collection'] ) ) 
        wp_set_object_terms( $post_id, $_POST['cf_Collection'], 'collections' );

}

// Add actions for both create & edit posts to save taxonomy
add_action('wpuf_add_post_after_insert', 'save_taxonomy_data');
add_action('wpuf_edit_post_after_insert', 'save_taxonomy_data');

add_filter( 'comment_form_defaults', 'my_comment_defaults');

// Comment form default changes
function my_comment_defaults($defaults) {
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
 
	$defaults = array(
		
        'comment_field'        => '<div class="comment-box">' . '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="Comment"></textarea></div>',
 
        'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="/wp-login.php?loginTwitter=1&redirect=http://startupandplay.dev" onclick="window.location = \'/wp-login.php?loginTwitter=1&redirect=\'+window.location.href; return false;">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
 
        'logged_in_as'         => '',
 
		'comment_notes_before' => '<fieldset>',
 
		'comment_notes_after'  => '</fieldset>',
 
		'id_form'              => 'commentform',
 
		'id_submit'            => 'submit',
 
		'title_reply'          => __( '' ),
 
		'title_reply_to'       => __( '' ),
 
		'cancel_reply_link'    => __( 'Cancel Reply' ),
 
		'label_submit'         => __( 'Comment' ),
 
        );
 
    return $defaults;
}

// Remove [...]/Read More from the_excerpt
function sbt_auto_excerpt_more( $more ) {
    return '..';
}
add_filter( 'excerpt_more', 'sbt_auto_excerpt_more', 20 );
function sbt_custom_excerpt_more( $output ) {
    return preg_replace('/<a[^>]+>Continue reading.*?<\/a>/i','',$output);
}
add_filter( 'get_the_excerpt', 'sbt_custom_excerpt_more', 20 );

// Modify users table
function users_table_modify( $column ) {
    $column['lock_post'] = 'Locked';
 
    return $column;
}
add_filter( 'manage_users_columns', 'users_table_modify' );
function users_table_modify_row( $val, $column_name, $user_id ) {
    $user = get_userdata( $user_id );
 
    switch ($column_name) {
        case 'lock_post' :
            return $user->wpuf_postlock;
            break;
        default:
    }
 
    return $return;
}
 
add_filter( 'manage_users_custom_column', 'users_table_modify_row', 10, 3 );

?>