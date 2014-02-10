<?php
get_header();
global $current_user;
get_currentuserinfo();
?>

    <div class="wrapper-single">
        <div class="wrapper-single-content">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            
            <article class="wrapper-single-post">
                <div class="wrapper-single-post-image"><?php echo get_the_post_thumbnail( '', 'single-content-width', '' ); ?></div>
                <div class="wrapper-single-post-title"><h1><?php the_title(); ?></h1></div>                
                <div class="wrapper-single-post-content"><?php the_content(); ?></div>

            <aside class="wrapper-single-info">
                <div class="wrapper-single-info-avatar">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></a></div>
                    <h2><?php the_author_posts_link(); ?></h2>
                <div class="wrapper-single-info-vcard">
                    <?php $url = get_the_author_meta('user_url'); ?><a href="<?php echo $url; ?>" target="_blank"><?php echo str_replace('http://', '', $url);?></a>
                    <p><a href="<?php the_author_meta('googleplus'); ?>" target="_blank"><i class="fa-google-plus-sign fa"></i></a><a href="https://twitter.com/<?php the_author_meta('twitter'); ?>" target="_blank"><i class="fa-twitter-sign fa"></i></a></p>
                    <p><?php the_author_meta('description'); ?></p>
                </div>
                <div class="wrapper-single-info-published">
                    <p>Published <br />
                    <?php the_date(); ?><br />
                </div>
            </aside>

            </article>
        
        <?php endwhile; else: ?>
        
            <p><?php _e('Sorry, this post does not exist.'); ?></p>
        
        <?php endif; ?>
        </div>
    </div>

<?php get_footer(); ?>