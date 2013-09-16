<?php
/**
 * Settings Sections
 *
 * @since 1.0
 * @return array
 */
function wpuf_settings_sections() {
    $sections = array(
        array(
            'id' => 'wpuf_frontend_posting',
            'title' => __( 'Frontend Posting', 'wpuf' )
        ),
        array(
            'id' => 'wpuf_dashboard',
            'title' => __( 'Dashboard', 'wpuf' )
        ),
        array(
            'id' => 'wpuf_others',
            'title' => __( 'Others', 'wpuf' )
        ),
    );

    return apply_filters( 'wpuf_settings_sections', $sections );
}

function wpuf_settings_fields() {
    $users = wpuf_list_users();
    $pages = wpuf_get_pages();
    
    $settings_fields = array(
        'wpuf_frontend_posting' => apply_filters( 'wpuf_options_frontend', array(
            array(
                'name' => 'post_status',
                'label' => __( 'Post Status', 'wpuf' ),
                'desc' => __( 'Default post status after user submits a post', 'wpuf' ),
                'type' => 'select',
                'default' => 'publish',
                'options' => array(
                    'publish' => 'Publish',
                    'draft' => 'Draft',
                    'pending' => 'Pending'
                )
            ),
            array(
                'name' => 'post_author',
                'label' => __( 'Post Author', 'wpuf' ),
                'desc' => __( 'Set the new post\'s post author by default', 'wpuf' ),
                'type' => 'select',
                'default' => 'original',
                'options' => array(
                    'original' => __( 'Original Author', 'wpuf' ),
                    'to_other' => __( 'Map to other user', 'wpuf' )
                )
            ),
            array(
                'name' => 'map_author',
                'label' => __( 'Map posts to poster', 'wpuf' ),
                'desc' => __( 'If <b>Map to other user</b> selected, new post\'s post author will be this user by default', 'wpuf' ),
                'type' => 'select',
                'options' => $users
            ),
            array(
                'name' => 'enable_featured_image',
                'label' => __( 'Featured Image upload', 'wpuf' ),
                'desc' => __( 'Gives ability to upload an image as featured image', 'wpuf' ),
                'type' => 'radio',
                'default' => 'no',
                'options' => array(
                    'yes' => __( 'Enable', 'wpuf' ),
                    'no' => __( 'Disable', 'wpuf' )
                )
            ),
            array(
                'name' => 'allow_attachment',
                'label' => __( 'Allow attachments', 'wpuf' ),
                'desc' => __( 'Will the users be able to add attachments on posts?', 'wpuf' ),
                'type' => 'radio',
                'default' => 'no',
                'options' => array(
                    'yes' => __( 'Enable', 'wpuf' ),
                    'no' => __( 'Disable', 'wpuf' )
                )
            ),
            array(
                'name' => 'attachment_num',
                'label' => __( 'Number of attachments', 'wpuf' ),
                'desc' => __( 'How many attachments can be attached on a post. Put <b>0</b> for unlimited attachment', 'wpuf' ),
                'type' => 'text',
                'default' => '0'
            ),
            array(
                'name' => 'attachment_max_size',
                'label' => __( 'Attachment max size', 'wpuf' ),
                'desc' => __( 'Enter the maximum file size in <b>KILOBYTE</b> that is allowed to attach', 'wpuf' ),
                'type' => 'text',
                'default' => '2048'
            ),
            array(
                'name' => 'allow_tags',
                'label' => __( 'Allow post tags', 'wpuf' ),
                'desc' => __( 'Users will be able to add post tags', 'wpuf' ),
                'type' => 'checkbox',
                'default' => 'on'
            ),
            array(
                'name' => 'enable_custom_field',
                'label' => __( 'Enable custom fields', 'wpuf' ),
                'desc' => __( 'You can use additional fields on your post submission form. Add new fields by going <b>Custom Fields</b> option page.', 'wpuf' ),
                'type' => 'checkbox'
            ),
        ) ),
        'wpuf_dashboard' => apply_filters( 'wpuf_options_dashboard', array(
            array(
                'name' => 'post_type',
                'label' => __( 'Show post type', 'wpuf' ),
                'desc' => __( 'Select the post type that the user will see', 'wpuf' ),
                'type' => 'select',
                'options' => wpuf_get_post_types()
            ),
            array(
                'name' => 'per_page',
                'label' => __( 'Posts per page', 'wpuf' ),
                'desc' => __( 'How many posts will be listed in a page', 'wpuf' ),
                'type' => 'text',
                'default' => '10'
            ),
            array(
                'name' => 'show_user_bio',
                'label' => __( 'Show user bio', 'wpuf' ),
                'desc' => __( 'Users biographical info will be shown', 'wpuf' ),
                'type' => 'checkbox',
                'default' => 'on'
            ),
            array(
                'name' => 'show_post_count',
                'label' => __( 'Show post count', 'wpuf' ),
                'desc' => __( 'Show how many posts are created by the user', 'wpuf' ),
                'type' => 'checkbox',
                'default' => 'on'
            ),
            array(
                'name' => 'show_ft_image',
                'label' => __( 'Show Featured Image', 'wpuf' ),
                'desc' => __( 'Show featured image of the post', 'wpuf' ),
                'type' => 'checkbox'
            ),
            array(
                'name' => 'ft_img_size',
                'label' => __( 'Featured Image size', 'wpuf' ),
                'type' => 'select',
                'options' => wpuf_get_image_sizes()
            ),
        ) ),
        'wpuf_others' => apply_filters( 'wpuf_options_others', array(
            array(
                'name' => 'post_notification',
                'label' => __( 'New post notification', 'wpuf' ),
                'desc' => __( 'A mail will be sent to admin when a new post is created', 'wpuf' ),
                'type' => 'select',
                'default' => 'yes',
                'options' => array(
                    'yes' => __( 'Yes', 'wpuf' ),
                    'no' => __( 'No', 'wpuf' )
                )
            ),
            array(
                'name' => 'enable_post_edit',
                'label' => __( 'Users can edit post?', 'wpuf' ),
                'desc' => __( 'Users will be able to edit their own posts', 'wpuf' ),
                'type' => 'select',
                'default' => 'yes',
                'options' => array(
                    'yes' => __( 'Yes', 'wpuf' ),
                    'no' => __( 'No', 'wpuf' )
                )
            ),
            array(
                'name' => 'enable_post_del',
                'label' => __( 'User can delete post?', 'wpuf' ),
                'desc' => __( 'Users will be able to delete their own posts', 'wpuf' ),
                'type' => 'select',
                'default' => 'yes',
                'options' => array(
                    'yes' => __( 'Yes', 'wpuf' ),
                    'no' => __( 'No', 'wpuf' )
                )
            ),
            array(
                'name' => 'edit_page_id',
                'label' => __( 'Edit Page', 'wpuf' ),
                'desc' => __( 'Select the page where [wpuf_editpost] is located', 'wpuf' ),
                'type' => 'select',
                'options' => $pages
            ),
            array(
                'name' => 'admin_access',
                'label' => __( 'Admin area access', 'wpuf' ),
                'desc' => __( 'Allow you to block specific user role to WordPress admin area.', 'wpuf' ),
                'type' => 'select',
                'default' => 'read',
                'options' => array(
                    'install_themes' => __( 'Admin Only', 'wpuf' ),
                    'edit_others_posts' => __( 'Admins, Editors', 'wpuf' ),
                    'publish_posts' => __( 'Admins, Editors, Authors', 'wpuf' ),
                    'edit_posts' => __( 'Admins, Editors, Authors, Contributors', 'wpuf' ),
                    'read' => __( 'Default', 'wpuf' )
                )
            ),
            array(
                'name' => 'cf_show_front',
                'label' => __( 'Show custom fields in the post', 'wpuf' ),
                'desc' => __( 'If you want to show the custom field data in the post added by the plugin.', 'wpuf' ),
                'type' => 'checkbox',
                'default' => 'on'
            ),
            array(
                'name' => 'override_editlink',
                'label' => __( 'Override the post edit link', 'wpuf' ),
                'desc' => __( 'Users see the edit link in post if s/he is capable to edit the post/page. Selecting <strong>Yes</strong> will override the default WordPress link', 'wpuf' ),
                'type' => 'select',
                'default' => 'no',
                'options' => array(
                    'yes' => __( 'Yes', 'wpuf' ),
                    'no' => __( 'No', 'wpuf' )
                )
            ),
        ) ),

    );

    return apply_filters( 'wpuf_settings_fields', $settings_fields );
}