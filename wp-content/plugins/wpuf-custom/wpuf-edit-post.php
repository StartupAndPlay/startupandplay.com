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
        <div id="wpuf-post-area">
            <form name="wpuf_edit_post_form" id="wpuf_edit_post_form" action="" enctype="multipart/form-data" method="POST">
                <?php wp_nonce_field( 'wpuf-edit-post' ) ?>
                <ul class="wpuf-post-form">

                    <?php do_action( 'wpuf_add_post_form_top', $curpost->post_type, $curpost ); //plugin hook      ?>
                    <?php wpuf_build_custom_field_form( 'top', true, $curpost->ID ); ?>

                    <?php if ( $featured_image == 'yes' ) { ?>
                        <?php if ( current_theme_supports( 'post-thumbnails' ) ) { ?>
                            <li>
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
                            </li>
                        <?php } else { ?>
                            <div class="info"><?php _e( 'Your theme doesn\'t support featured image', 'wpuf' ) ?></div>
                        <?php } ?>
                    <?php } ?>

                    <li>
                        <input type="text" name="wpuf_post_title" id="new-post-title" minlength="2" placeholder="Title" value="<?php echo esc_html( $curpost->post_title ); ?>">
                        <div class="clear"></div>
                        <p class="description"><?php echo stripslashes( wpuf_get_option( 'title_help', 'wpuf_labels' ) ); ?></p>
                    </li>

                    <?php if ( wpuf_get_option( 'allow_cats', 'wpuf_frontend_posting', 'on' ) == 'on' ) { ?>
                        <li>
                            <label for="new-post-cat">
                                <?php echo wpuf_get_option( 'cat_label', 'wpuf_labels', 'Category' ); ?> <span class="required">*</span>
                            </label>

                            <?php
                            $exclude = wpuf_get_option( 'exclude_cats', 'wpuf_frontend_posting' );
                            $cat_type = wpuf_get_option( 'cat_type', 'wpuf_frontend_posting' );

                            $cats = get_the_category( $curpost->ID );
                            $selected = 0;
                            if ( $cats ) {
                                $selected = $cats[0]->term_id;
                            }
                            //var_dump( $cats );
                            //var_dump( $selected );
                            ?>
                            <div class="category-wrap" style="float:left;">
                                <div id="lvl0">
                                    <?php
                                    if ( $cat_type == 'normal' ) {
                                        wp_dropdown_categories( 'show_option_none=' . __( '-- Select --', 'wpuf' ) . '&hierarchical=1&hide_empty=0&orderby=name&name=category[]&id=cat&show_count=0&title_li=&use_desc_for_title=1&class=cat requiredField&exclude=' . $exclude . '&selected=' . $selected );
                                    } else if ( $cat_type == 'ajax' ) {
                                        wp_dropdown_categories( 'show_option_none=' . __( '-- Select --', 'wpuf' ) . '&hierarchical=1&hide_empty=0&orderby=name&name=category[]&id=cat-ajax&show_count=0&title_li=&use_desc_for_title=1&class=cat requiredField&depth=1&exclude=' . $exclude . '&selected=' . $selected );
                                    } else {
                                        wpuf_category_checklist( $curpost->ID, false, 'category', $exclude);
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="loading"></div>
                            <div class="clear"></div>
                            <p class="description"><?php echo stripslashes( wpuf_get_option( 'cat_help', 'wpuf_labels' ) ); ?></p>
                        </li>
                    <?php } ?>

                    <?php do_action( 'wpuf_add_post_form_description', $curpost->post_type, $curpost ); ?>
                    <?php // wpuf_build_custom_field_form( 'description', true, $curpost->ID ); ?>

                    <li>

                        <?php
                        $editor = wpuf_get_option( 'editor_type', 'wpuf_frontend_posting', 'normal' );
                        if ( $editor == 'full' ) {
                            ?>
                            <div style="float:left;">
                                <?php wp_editor( $curpost->post_content, 'new-post-desc', array('textarea_name' => 'wpuf_post_content', 'editor_class' => 'requiredField', 'teeny' => false, 'textarea_rows' => 8) ); ?>
                            </div>
                        <?php } else if ( $editor == 'rich' ) { ?>
                            <div style="float:left;">
                                <?php wp_editor( $curpost->post_content, 'new-post-desc', array('textarea_name' => 'wpuf_post_content', 'editor_class' => 'requiredField', 'teeny' => true, 'textarea_rows' => 8) ); ?>
                            </div>

                        <?php } else { ?>
                            <textarea name="wpuf_post_content" class="requiredField" id="new-post-desc" cols="60" rows="8" placeholder="Post"><?php echo esc_textarea( $curpost->post_content ); ?></textarea>
                        <?php } ?>

                        <div class="clear"></div>
                        <p class="description"><?php echo stripslashes( wpuf_get_option( 'desc_help', 'wpuf_labels' ) ); ?></p>
                    </li>

                    <?php do_action( 'wpuf_add_post_form_after_description', $curpost->post_type, $curpost ); ?>
                    <?php wpuf_build_custom_field_form( 'tag', true, $curpost->ID ); ?>

                    <?php if ( wpuf_get_option( 'allow_tags', 'wpuf_frontend_posting' ) == 'on' ) { ?>
                        <li>
                            <input type="text" name="wpuf_post_tags" id="new-post-tags" placeholder="Tags" value="<?php echo $tagslist; ?>">
                            <p class="description"><?php echo stripslashes( wpuf_get_option( 'tag_help', 'wpuf_labels' ) ); ?></p>
                            <div class="clear"></div>
                        </li>
                    <?php } ?>

                    <?php do_action( 'wpuf_add_post_form_tags', $curpost->post_type, $curpost ); ?>
                    <?php wpuf_build_custom_field_form( 'bottom', true, $curpost->ID ); ?>

                    <li id="submit">
                        <input class="wpuf_submit btn btn-small" type="submit" name="wpuf_edit_post_submit" value="<?php echo esc_attr( wpuf_get_option( 'update_label', 'wpuf_labels', 'Update Post' ) ); ?>">
                        <input type="hidden" name="wpuf_edit_post_submit" value="yes" />
                        <input type="hidden" name="post_id" value="<?php echo $curpost->ID; ?>">
                    </li>
                </ul>
            </form>
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
        if ( wpuf_get_option( 'allow_cats', 'wpuf_frontend_posting', 'on' ) == 'on' ) {
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
        }

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