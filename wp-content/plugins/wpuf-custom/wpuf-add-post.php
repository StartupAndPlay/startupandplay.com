<?php

/**
 * Add Post form class
 *
 * @author Tareq Hasan
 * @package WP User Frontend
 */
class WPUF_Add_Post {

    function __construct() {
        add_shortcode( 'wpuf_addpost', array($this, 'shortcode') );
    }

    /**
     * Handles the add post shortcode
     *
     * @param $atts
     */
    function shortcode( $atts ) {

        extract( shortcode_atts( array('post_type' => 'post'), $atts ) );

        ob_start();

        if ( is_user_logged_in() ) {
            $this->post_form( $post_type );
        } else {
            printf( __( "This page is restricted. Please %s to view this page.", 'wpuf' ), wp_loginout( get_permalink(), false ) );
        }

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Add posting main form
     *
     * @param $post_type
     */
    function post_form( $post_type ) {
        global $userdata;

        $userdata = get_user_by( 'id', $userdata->ID );

        if ( isset( $_POST['wpuf_post_new_submit'] ) ) {
            $nonce = $_REQUEST['_wpnonce'];
            if ( !wp_verify_nonce( $nonce, 'wpuf-add-post' ) ) {
                wp_die( __( 'Cheating?' ) );
            }

            $this->submit_post();
        }

        $info = __( "Post It!", 'wpuf' );
        $can_post = 'yes';

        $info = apply_filters( 'wpuf_addpost_notice', $info );
        $can_post = apply_filters( 'wpuf_can_post', $can_post );
        $featured_image = wpuf_get_option( 'enable_featured_image', 'wpuf_frontend_posting', 'no' );

        $title = isset( $_POST['wpuf_post_title'] ) ? esc_attr( $_POST['wpuf_post_title'] ) : '';
        $description = isset( $_POST['wpuf_post_content'] ) ? $_POST['wpuf_post_content'] : '';

        if ( $can_post == 'yes' ) {
            ?>
        <div class="wrapper-single-create">
            <div class="wrapper-single-create-post">
                
                <form id="wpuf_new_post_form" name="wpuf_new_post_form" action="" onsubmit='return editableContent()' enctype="multipart/form-data" method="POST">
                    <?php wp_nonce_field( 'wpuf-add-post' ) ?>

                    <ul class="post-form">

                        <?php do_action( 'wpuf_add_post_form_top', $post_type ); //plugin hook   ?>

                        <?php if ( $featured_image == 'yes' ) { ?>
                            
                                <li>
                                    <div id="wpuf-ft-upload-container">
                                        <div id="wpuf-ft-upload-filelist"></div>
                                        <a id="wpuf-ft-upload-pickfiles" class="btn btn-small" href="#"><i class="icon-picture fontawesome"></i>Upload Image</a>
                                    </div>
                                    <div class="clear"></div>
                                </li>

                        <?php } ?>

                                <li style="display: none;"><input type="hidden" class="requiredField" type="text" name="wpuf_post_title" id="new-post-title" minlength="2" maxlength="50"></li>
                                <li style="display: none;"><input type="hidden" class="requiredField" type="text" name="cf_Tagline" id="cf_Tagline" minlength="2" maxlength="140"></li>                       
                                <li style="display: none;"><textarea type="hidden" name="wpuf_post_content" class="requiredField" id="new-post-desc" cols="60" rows="8"><?php echo esc_textarea( $description ); ?></textarea></li>

                        <?php do_action( 'wpuf_add_post_form_tags', $post_type ); ?>

                        <li id="submit" class="wrapper-single-create-post-submit">
                            <input class="wpuf-submit btn btn-small" type="submit" name="wpuf_new_post_submit" value="Publish">
                            <input type="hidden" name="wpuf_post_type" value="<?php echo $post_type; ?>" />
                            <input type="hidden" name="wpuf_post_new_submit" value="yes" />
                        </li>

                    </ul>
                </form>
                <ul>
                    <li><h1 contenteditable="true" id="new-post-title-h1" class="" data-max-length="50" data-placeholder="Type your title"></h1></li>
                    <li><h2 contenteditable="true" id="new-post-cf_Tagline-p" class="cf_Tagline" data-max-length="140" data-placeholder="Type your subtitle"></h2></li>
                    <li class="grande">
                        <article contenteditable="true" class="content" id="new-post-desc-p" data-placeholder="Type your article"></article>
                                                        

                    </li>
                </ul>
                
            </div>
        </div>
            <?php
        } else {
            echo '<div class="info">' . $info . '</div>';
        }
    }

    /**
     * Validate the post submit data
     *
     * @global type $userdata
     * @param type $post_type
     */
    function submit_post() {
        global $userdata;

        $errors = array();

        var_dump( $_POST );

        //if there is some attachement, validate them
        if ( !empty( $_FILES['wpuf_post_attachments'] ) ) {
            $errors = wpuf_check_upload();
        }

        $title = trim( $_POST['wpuf_post_title'] );
        $content = trim( $_POST['wpuf_post_content'] );

        //validate title
        if ( empty( $title ) ) {
            $errors[] = __( 'Empty post title', 'wpuf' );
        } else {
            $title = trim( strip_tags( $title ) );
        }

        //validate post content
        if ( empty( $content ) ) {
            $errors[] = __( 'Empty post content', 'wpuf' );
        } else {
            $content = trim( $content );
        }


        //post attachment
        $attach_id = isset( $_POST['wpuf_featured_img'] ) ? intval( $_POST['wpuf_featured_img'] ) : 0;

        //post type
        $post_type = trim( strip_tags( $_POST['wpuf_post_type'] ) );

        //process the custom fields
        $custom_fields = array();

        $fields = wpuf_get_custom_fields();
        if ( is_array( $fields ) ) {

            foreach ($fields as $cf) {
                if ( array_key_exists( $cf['field'], $_POST ) ) {

                    if ( is_array( $_POST[$cf['field']] ) ) {
                        $temp = implode(',', $_POST[$cf['field']]);
                    } else {
                        $temp = trim( strip_tags( $_POST[$cf['field']] ) );
                    }

                    //var_dump($temp, $cf);

                    if ( ( $cf['type'] == 'yes' ) && !$temp ) {
                        $errors[] = sprintf( __( '"%s" is missing', 'wpuf' ), $cf['label'] );
                    } else {
                        $custom_fields[$cf['field']] = $temp;
                    }
                } //array_key_exists
            } //foreach
        } //is_array

        $errors = apply_filters( 'wpuf_add_post_validation', $errors );


        //if not any errors, proceed
        if ( $errors ) {
            echo wpuf_error_msg( $errors );
            return;
        }

        $post_stat = wpuf_get_option( 'post_status', 'wpuf_frontend_posting' );
        $post_author = (wpuf_get_option( 'post_author', 'wpuf_frontend_posting' ) == 'original' ) ? $userdata->ID : wpuf_get_option( 'map_author', 'wpuf_frontend_posting' );

        $my_post = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => $post_stat,
            'post_author' => $post_author,
            'post_category' => $post_category,
            'post_type' => $post_type,
            'tags_input' => $tags
        );

        //plugin API to extend the functionality
        $my_post = apply_filters( 'wpuf_add_post_args', $my_post );

        //var_dump( $_POST, $my_post );die();
        //insert the post
        $post_id = wp_insert_post( $my_post );

        if ( $post_id ) {

            //upload attachment to the post
            wpuf_upload_attachment( $post_id );

            //send mail notification
            if ( wpuf_get_option( 'post_notification', 'wpuf_others', 'yes' ) == 'yes' ) {
                wpuf_notify_post_mail( $userdata, $post_id );
            }

            //add the custom fields
            if ( $custom_fields ) {
                foreach ($custom_fields as $key => $val) {
                    add_post_meta( $post_id, $key, $val, true );
                }
            }

            //set post thumbnail if has any
            if ( $attach_id ) {
                set_post_thumbnail( $post_id, $attach_id );
            }

            //plugin API to extend the functionality
            do_action( 'wpuf_add_post_after_insert', $post_id );

            if ( $post_id ) {
                $redirect = apply_filters( 'wpuf_after_post_redirect', get_permalink( $post_id ), $post_id );

                wp_redirect( $redirect );
                exit;
            }
        }
    }

}

$wpuf_postform = new WPUF_Add_Post();