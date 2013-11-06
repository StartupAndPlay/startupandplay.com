<?php

class WPUF_Edit_Post {

    function __construct() {
        add_shortcode( 'wpuf_edit', array($this, 'shortcode') );
    }

    /**
     * Handles the edit post shortcode
     *
     * @return string generated form by the plugin
     */
    function shortcode() {

        ob_start();

        if ( is_user_logged_in() ) {
            $this->prepare_form();
        } else {
            wp_redirect( '/404/', 301 );
            exit;
        }

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Main edit post form
     *
     * @global type $wpdb
     * @global type $userdata
     */
    function prepare_form() {
        global $wpdb, $userdata;

        $post_id = isset( $_GET['pid'] ) ? intval( $_GET['pid'] ) : 0;

        //is editing enabled?
        if ( wpuf_get_option( 'enable_post_edit', 'wpuf_others', 'yes' ) != 'yes' ) {
            return __( 'Post Editing is disabled', 'wpuf' );
        }

        $curpost = get_post( $post_id );

        if ( !$curpost ) {
            return __( 'Invalid post', 'wpuf' );
        }

        //has permission?
        if ( !current_user_can( 'delete_others_posts' ) && ( $userdata->ID != $curpost->post_author ) ) {
            return __( 'You are not allowed to edit', 'wpuf' );
        }

        //perform delete attachment action
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == "del" ) {
            check_admin_referer( 'wpuf_attach_del' );
            $attach_id = intval( $_REQUEST['attach_id'] );

            if ( $attach_id ) {
                wp_delete_attachment( $attach_id );
            }
        }

        //process post
        if ( isset( $_POST['wpuf_edit_post_submit'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpuf-edit-post' ) ) {
            $this->submit_post();

            $curpost = get_post( $post_id );
        }

        //show post form
        $this->edit_form( $curpost );
    }

    function edit_form( $curpost ) {
        $post_tags = wp_get_post_tags( $curpost->ID );
        $tagsarray = array();
        foreach ($post_tags as $tag) {
            $tagsarray[] = $tag->name;
        }
        $tagslist = implode( ', ', $tagsarray );
        $categories = get_the_category( $curpost->ID );
        $featured_image = wpuf_get_option( 'enable_featured_image', 'wpuf_frontend_posting', 'no' );
        ?>

        <div class="wrapper-single-create">
            <div class="wrapper-single-create-post">
                
                <form name="wpuf_edit_post_form" id="wpuf_edit_post_form" action="" onsubmit='return editableContent()' enctype="multipart/form-data" method="POST">
                    <?php wp_nonce_field( 'wpuf-edit-post' ) ?>
                    
                    <div class="post-form">

                    <?php do_action( 'wpuf_add_post_form_top', $curpost->post_type, $curpost ); //plugin hook      ?>

                        <div id="wpuf-ft-upload-container">
                            <div id="wpuf-ft-upload-filelist">
                                <?php
                                    $style = '';
                                    if ( has_post_thumbnail( $curpost->ID ) ) {
                                    $style = ' style="display:none"';

                                    $post_thumbnail_id = get_post_thumbnail_id( $curpost->ID );
                                        echo wpuf_feat_img_html( $post_thumbnail_id );
                                    }
                                ?>
                            </div>
                            <a id="wpuf-ft-upload-pickfiles" class="btn btn-small" href="#"><i class="icon-picture fontawesome"></i>Change Image</a>
                        </div>
                        <div class="clear"></div>

                    <div style="display: none;">                    
                        <input type="hidden" class="requiredField" type="text" name="wpuf_post_title" id="new-post-title" minlength="2" maxlength="50" value="<?php echo esc_html( $curpost->post_title ); ?>">
                        <input type="hidden" class="requiredField" type="text" name="cf_Tagline" id="cf_Tagline" minlength="2" maxlength="140" value="<?php echo esc_html( $curpost->cf_tagline ); ?>">                                                             
                        <textarea type="hidden" class="requiredField" name="wpuf_post_content"  id="new-post-desc"><?php echo esc_textarea( $curpost->post_content ); ?></textarea>
                    </div>

                    <?php do_action( 'wpuf_add_post_form_tags', $curpost->post_type, $curpost ); ?>

                    <div id="submit" class="wrapper-single-create-post-submit">
                        <input class="wpuf_submit btn btn-small" type="submit" name="wpuf_edit_post_submit" value="<?php echo esc_attr( wpuf_get_option( 'update_label', 'wpuf_labels', 'Update Post' ) ); ?>">
                        <input type="hidden" name="wpuf_edit_post_submit" value="yes" />
                        <input type="hidden" name="post_id" value="<?php echo $curpost->ID; ?>">
                    </div>
                        
                </div>
            </form>
            <div>
                <h1 contenteditable="true" id="new-post-title-h1" class="" data-max-length="50"><?php echo esc_textarea( $curpost->post_title ); ?></h1>
                <h2 contenteditable="true" id="new-post-cf_Tagline-p" class="cf_Tagline" data-max-length="140"><?php echo esc_textarea( $curpost->cf_Tagline ); ?></h2>
                <article contenteditable="true" class="content" id="new-post-desc-p"><?php echo $curpost->post_content; ?></article>       
            </div>
        </div>

        <?php
    }

    function submit_post() {
        global $userdata;

        $errors = array();

        $title = trim( $_POST['wpuf_post_title'] );
        $content = trim( $_POST['wpuf_post_content'] );

        $tags = '';
        $cat = '';
        if ( isset( $_POST['wpuf_post_tags'] ) ) {
            $tags = wpuf_clean_tags( $_POST['wpuf_post_tags'] );
        }

        //if there is some attachement, validate them
        if ( !empty( $_FILES['wpuf_post_attachments'] ) ) {
            $errors = wpuf_check_upload();
        }

        if ( empty( $title ) ) {
            $errors[] = __( 'Empty post title', 'wpuf' );
        } else {
            $title = trim( strip_tags( $title ) );
        }

        //validate cat
        /*if ( wpuf_get_option( 'allow_cats', 'wpuf_frontend_posting', 'on' ) == 'on' ) {
            $cat_type = wpuf_get_option( 'cat_type', 'wpuf_frontend_posting', 'normal' );
            if ( !isset( $_POST['category'] ) ) {
                $errors[] = __( 'Please choose a category', 'wpuf' );
            } else if ( $cat_type == 'normal' && $_POST['category'][0] == '-1' ) {
                $errors[] = __( 'Please choose a category', 'wpuf' );
            } else {
                if ( count( $_POST['category'] ) < 1 ) {
                    $errors[] = __( 'Please choose a category', 'wpuf' );
                }
            }
        }*/

        if ( empty( $content ) ) {
            $errors[] = __( 'Empty post content', 'wpuf' );
        } else {
            $content = trim( $content );
        }

        if ( !empty( $tags ) ) {
            $tags = explode( ',', $tags );
        }

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
        //post attachment
        $attach_id = isset( $_POST['wpuf_featured_img'] ) ? intval( $_POST['wpuf_featured_img'] ) : 0;

        $errors = apply_filters( 'wpuf_edit_post_validation', $errors );

        if ( !$errors ) {

            //users are allowed to choose category
            if ( wpuf_get_option( 'allow_cats', 'wpuf_frontend_posting', 'on' ) == 'on' ) {
                $post_category = $_POST['category'];
            } else {
                $post_category = array( wpuf_get_option( 'default_cat', 'wpuf_frontend_posting' ) );
            }

            $post_update = array(
                'ID' => trim( $_POST['post_id'] ),
                'post_title' => $title,
                'post_content' => $content,
                'post_category' => $post_category,
                'tags_input' => $tags
            );

            //plugin API to extend the functionality
            $post_update = apply_filters( 'wpuf_edit_post_args', $post_update );

            $post_id = wp_update_post( $post_update );

            if ( $post_id ) {
                echo '<div class="success">' . __( 'Post updated succesfully.', 'wpuf' ) . '</div>';

                //upload attachment to the post
                wpuf_upload_attachment( $post_id );

                //set post thumbnail if has any
                if ( $attach_id ) {
                    set_post_thumbnail( $post_id, $attach_id );
                }

                //add the custom fields
                if ( $custom_fields ) {
                    foreach ($custom_fields as $key => $val) {
                        update_post_meta( $post_id, $key, $val, false );
                    }
                }

                do_action( 'wpuf_edit_post_after_update', $post_id );
            }
        } else {
            echo wpuf_error_msg( $errors );
        }
    }

}

$wpuf_edit = new WPUF_Edit_Post();