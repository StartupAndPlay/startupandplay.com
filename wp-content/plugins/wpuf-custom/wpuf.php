<?php
/*
Plugin Name: WPUF Custom
Description: Post, Edit, Delete posts and edit profile without coming to backend
Author: Patrick Shampine
Version: 1.0
Author URI: http://patrickshampine.com

    //Modified this plugin for Startup And Play
    Original Plugin: WP User Frontend
    Original Author: Tareq Hasan
    Original Version: 1.2.3
    Original Author URI: http://tareq.weDevs.com
*/

if ( !class_exists( 'WeDevs_Settings_API' ) ) {
    require_once dirname( __FILE__ ) . '/class.settings-api.php';
}

require_once 'wpuf-functions.php';
require_once 'admin/settings-options.php';
require_once 'admin/form-builder.php';

if ( is_admin() ) {
    require_once 'admin/settings.php';
    require_once 'admin/custom-fields.php';
}

require_once 'wpuf-dashboard.php';
require_once 'wpuf-add-post.php';
require_once 'wpuf-edit-post.php';
require_once 'wpuf-editprofile.php';
require_once 'wpuf-ajax.php';
require_once 'attachment.php';

class WPUF_Main {

    function __construct() {
        register_activation_hook( __FILE__, array($this, 'install') );
        register_deactivation_hook( __FILE__, array($this, 'uninstall') );

        add_action( 'admin_init', array($this, 'block_admin_access') );

        //add_action( 'init', array($this, 'load_textdomain') );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
    }

    /**
     * Create tables on plugin activation
     *
     * @global object $wpdb
     */
    function install() {
        global $wpdb;

        flush_rewrite_rules( false );

        $sql_custom = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpuf_customfields (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `field` varchar(30) NOT NULL,
         `type` varchar(20) NOT NULL,
         `values` text NOT NULL,
         `label` varchar(200) NOT NULL,
         `desc` varchar(200) NOT NULL,
         `required` varchar(5) NOT NULL,
         `region` varchar(20) NOT NULL DEFAULT 'top',
         `order` int(1) NOT NULL,
         PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

        $wpdb->query( $sql_custom );
    }

    function uninstall() {

    }

    /**
     * Enqueues Styles and Scripts when the shortcodes are used only
     *
     * @uses has_shortcode()
     * @since 0.2
     */
    function enqueue_scripts() {
        $path = plugins_url('', __FILE__ );

        //for multisite upload limit filter
        if ( is_multisite() ) {
            require_once ABSPATH . '/wp-admin/includes/ms.php';
        }

        require_once ABSPATH . '/wp-admin/includes/template.php';
    
        if ( is_page('New Post') ) {
            wp_enqueue_script( 'plupload-handlers' );
            wp_enqueue_script( 'placeholder', $path . '/js/placeholder.js', array('jquery') );
            wp_enqueue_script( 'editable', $path . '/js/editable.js' );
            wp_enqueue_script( 'grande', $path . '/js/grande.js', array(), false, false );
            wp_enqueue_style( 'grande_menu', $path . '/css/menu.css' );
            wp_enqueue_style( 'grande_editor', $path . '/css/editor.css' );
            function grande_bind() {
                echo '<script type="text/javascript"> grande.bind(); </script>';
            }
            add_action('wp_footer','grande_bind');
        }
        
        if ( is_page('Edit Post') ) {
            wp_enqueue_script( 'plupload-handlers' );
            wp_enqueue_script( 'editable', $path . '/js/editable.js' );
            wp_enqueue_script( 'grande', $path . '/js/grande.js', array(), false, false );
            wp_enqueue_style( 'grande_menu', $path . '/css/menu.css' );
            wp_enqueue_style( 'grande_editor', $path . '/css/editor.css' );
            function grande_bind() {
                echo '<script type="text/javascript"> grande.bind(); </script>';
            }
            add_action('wp_footer','grande_bind');
        }
        
        wp_enqueue_script( 'wpuf', $path . '/js/wpuf.js', array('jquery') );

        $posting_msg = wpuf_get_option( 'updating_label', 'wpuf_labels' );
        $feat_img_enabled = ( wpuf_get_option( 'enable_featured_image', 'wpuf_frontend_posting' ) == 'yes') ? true : false;
        wp_localize_script( 'wpuf', 'wpuf', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'postingMsg' => $posting_msg,
            'confirmMsg' => __( 'Are you sure?', 'wpuf' ),
            'nonce' => wp_create_nonce( 'wpuf_nonce' ),
            'featEnabled' => $feat_img_enabled,
            'plupload' => array(
                'runtimes' => 'html5,silverlight,flash,html4',
                'browse_button' => 'wpuf-ft-upload-pickfiles',
                'container' => 'wpuf-ft-upload-container',
                'file_data_name' => 'wpuf_featured_img',
                'max_file_size' => wp_max_upload_size() . 'b',
                'url' => admin_url( 'admin-ajax.php' ) . '?action=wpuf_featured_img&nonce=' . wp_create_nonce( 'wpuf_featured_img' ),
                'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
                'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
                'filters' => array(array('title' => __( 'Allowed Files' ), 'extensions' => '*')),
                'multipart' => true,
                'urlstream_upload' => true,
            )
        ) );
    }

    /**
     * Block user access to admin panel for specific roles
     *
     * @global string $pagenow
     */
    function block_admin_access() {
        global $pagenow;

        // bail out if we are from WP Cli
        if ( defined( 'WP_CLI' ) ) {
            return;
        }

        $access_level = wpuf_get_option( 'admin_access', 'wpuf_others', 'read' );
        $valid_pages = array('admin-ajax.php', 'async-upload.php', 'media-upload.php');

        if ( !current_user_can( $access_level ) && !in_array( $pagenow, $valid_pages ) ) {
            wp_die( __( 'Access Denied. Your site administrator has blocked your access to the WordPress back-office.', 'wpuf' ) );
        }
    }

    /**
     * The main logging function
     *
     * @uses error_log
     * @param string $type type of the error. e.g: debug, error, info
     * @param string $msg
     */
    public static function log( $type = '', $msg = '' ) {
        if ( WP_DEBUG == true ) {
            $msg = sprintf( "[%s][%s] %s\n", date( 'd.m.Y h:i:s' ), $type, $msg );
            error_log( $msg, 3, dirname( __FILE__ ) . '/log.txt' );
        }
    }

}

$wpuf = new WPUF_Main();
