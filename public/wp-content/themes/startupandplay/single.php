<?php get_header(); ?>

<?php
global $current_user;
get_currentuserinfo();
?>

    <div class="wrapper-single">
        <div class="wrapper-single-content">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        
            <aside class="wrapper-single-info hidden-tablet">
                <div class="wrapper-single-info-avatar">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></a></div>
                    <h2><?php the_author_posts_link(); ?></h2>
                <div class="wrapper-single-info-vcard">
                <?php $url = get_the_author_meta('user_url', $author_id); ?><a href="<?php echo $url; ?>" target="_blank"><?php echo str_replace('http://', '', $url);?></a>
                <p><a href="<?php the_author_meta('googleplus'); ?>" target="_blank"><i class="icon-google-plus-sign fontawesome"></i></a><a href="https://twitter.com/<?php the_author_meta('twitter'); ?>" target="_blank"><i class="icon-twitter-sign fontawesome"></i></a></p>
                <p><?php the_author_meta('description'); ?></p>
                </div>
                <div class="wrapper-single-info-published">
                    <p>Published <br />
                    <?php the_date(); ?><br />
                    in <br />
                    <?php echo get_the_term_list( $wp_query->post->ID, 'collections', '', ', ', '' ); ?></p>
                </div>
                <ul class="wrapper-single-info-edit">
                <?php 
                    if ($post->post_author == $current_user->ID) {
                    if ( wpuf_get_option( 'enable_post_edit', 'wpuf_others', 'yes' ) == 'yes' ) { ?>
                                    <?php
                                    $edit_page = (int) wpuf_get_option( 'edit_page_id', 'wpuf_others' );
                                    $url = get_permalink( $edit_page );
                                    ?>
                        <li><a href="<?php echo wp_nonce_url( $url . '?pid=' . $post->ID, 'wpuf_edit' ); ?>"><?php _e( '<i class="icon-edit fontawesome"></i>Edit', 'wpuf' ); ?></a></li>
                    <?php } else { ?>
                                    &nbsp;
                    <?php } ?>

                    <?php if ( wpuf_get_option( 'enable_post_del', 'wpuf_others', 'yes' ) == 'yes' ) { ?>
                        <!--<li><a href="<?php //wp_trash_post($post_id); ?>" onclick="return confirm('Are you sure?');"><span style="color: red;"><?php //_e( '<i class="icon-trash fontawesome"></i>Delete', 'wpuf' ); ?></span></a></li>-->
                    <?php } ?>
                    <?php } ?>
                </ul>
            </aside>
            
            <article class="wrapper-single-post">
                <div class="wrapper-single-post-image"><?php echo get_the_post_thumbnail( $post_id, 'single-content-width', '' ); ?></div>
                <div class="wrapper-single-post-title"><h1><?php the_title(); ?></h1></div>
                <div class="wrapper-single-post-title-link"><a href="<?php comments_link(); ?>"><?php comments_number( '<i class="icon-comments fontawesome"></i>0', '<i class="icon-comments fontawesome"></i>1', '<i class="icon-comments fontawesome"></i>%' ); ?></a></div>
                
                <div class="wrapper-single-post-content"><?php the_content(); ?></div>
                <div class="wrapper-single-post-tags">
                    <p>Also found in <?php the_category(', '); ?></p>
                    <p><?php the_tags('<i class="icon-tags fontawesome" style="color: #68c6c5;"></i>', ', ', '<br />'); ?></p>
                </div>
                
                
            <aside class="wrapper-single-info hidden-desktop">
                <div class="wrapper-single-info-avatar">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></a></div>
                    <h2><?php the_author_posts_link(); ?></h2>
                <div class="wrapper-single-info-vcard">
                    <?php $url = get_the_author_meta('user_url', $author_id); ?><a href="<?php echo $url; ?>" target="_blank"><?php echo str_replace('http://', '', $url);?></a>
                    <p><a href="<?php the_author_meta('googleplus'); ?>" target="_blank"><i class="icon-google-plus-sign fontawesome"></i></a><a href="https://twitter.com/<?php the_author_meta('twitter'); ?>" target="_blank"><i class="icon-twitter-sign fontawesome"></i></a></p>
                    <p><?php the_author_meta('description'); ?></p>
                </div>
                <div class="wrapper-single-info-published">
                    <p>Published <br />
                    <?php the_date(); ?><br />
                    in <br />
                    <?php echo get_the_term_list( $wp_query->post->ID, 'collections', '', ', ', '' ); ?></p>
                </div>
                    <ul class="wrapper-single-info-edit">
                        <?php 
                            if ($post->post_author == $current_user->ID) {
                            if ( wpuf_get_option( 'enable_post_edit', 'wpuf_others', 'yes' ) == 'yes' ) { ?>
                                    <?php
                                    $edit_page = (int) wpuf_get_option( 'edit_page_id', 'wpuf_others' );
                                    $url = get_permalink( $edit_page );
                                    ?>
                        <li><a href="<?php echo wp_nonce_url( $url . '?pid=' . $post->ID, 'wpuf_edit' ); ?>"><?php _e( '<i class="icon-edit fontawesome"></i>Edit', 'wpuf' ); ?></a></li>
                            <?php } else { ?>
                                    &nbsp;
                            <?php } ?>

                        <?php if ( wpuf_get_option( 'enable_post_del', 'wpuf_others', 'yes' ) == 'yes' ) { ?>
                        <!--<li><a href="<?php // wp_trash_post($post_id); ?>" onclick="return confirm('Are you sure?');"><span style="color: red;"><?php // _e( '<i class="icon-trash fontawesome"></i>Delete', 'wpuf' ); ?></span></a></li>-->
                    <?php } ?>
                    <?php } ?>
                </ul>
            </aside>
                
                
                
                
                <?php comments_template(); ?>            
            </article>
        
        <?php endwhile; else: ?>
        
            <p><?php _e('Sorry, this post does not exist.'); ?></p>
        
        <?php endif; ?>
        </div>
    </div>

<?php get_footer(); ?>