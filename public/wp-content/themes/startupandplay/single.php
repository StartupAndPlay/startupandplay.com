<?php
get_header();
global $current_user;
get_currentuserinfo();
?>
  <div class="main">
    <section class="main-single">
      <div class="single-content">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            
          <article class="single-post">
              <div class="single-post-image"><?php echo get_the_post_thumbnail( '', 'single-content-width', '' ); ?></div>
              <div class="single-post-title"><h1><?php the_title(); ?></h1></div>                
              <div class="single-post-content"><?php the_content(); ?></div>
          </article>

          <aside class="single-info">
            <div class="single-info-avatar">
                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></a></div>
                <h2><?php the_author_posts_link(); ?></h2>
            <div class="single-info-vcard">
                <?php $url = get_the_author_meta('user_url'); ?><a href="<?php echo $url; ?>" target="_blank"><?php echo str_replace('http://', '', $url);?></a>
                <p><a href="<?php the_author_meta('googleplus'); ?>" target="_blank"><i class="fa-google-plus-square fa"></i></a><a href="https://twitter.com/<?php the_author_meta('twitter'); ?>" target="_blank"><i class="fa-twitter-square fa"></i></a></p>
                <p><?php the_author_meta('description'); ?></p>
            </div>
            <div class="single-info-published">
                <p>Published <br /><?php the_date(); ?><br /></p>
            </div>
          </aside>
        
        <?php endwhile; else: ?>
          
          <article class="single-post"
            <p><?php _e('Sorry, this post does not exist.'); ?></p>
          </article>

        <?php endif; ?>
        
      </div>
    </section>
  </div>

<?php get_footer(); ?>