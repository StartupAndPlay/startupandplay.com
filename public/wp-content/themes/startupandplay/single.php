<?php

// Calls previous post, sets up for prerendering
$prevPost = get_previous_post();
 $prevArray = (array) $prevPost;
 global $prevArray;
 //var_dump($prevArray);
 $prevID = $prevArray['ID'];
 $prevTitle = $prevArray['post_title'];

get_header();

?>

  <div class="main">      
    <section class="main-single">
      <div class="single-content">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

          <article class="single-post">
            <?php if (get_field('deprecated_featured_image')) { ?>
              <div class="single-post-image">
                <?php echo get_the_post_thumbnail(); ?>
              </div>
            <?php } ?>
              <div class="single-post-title"><h1><?php the_title(); ?></h1></div>                
              <div class="single-post-content"><?php the_content(); ?></div>
          </article>

          <aside class="single-info">
            <div class="single-info-avatar">
              <?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
            </div>
            <div class="single-info-vcard">
              <h2><?php the_author(); ?></h2>
              <?php $url = get_the_author_meta('user_url'); ?><a href="<?php echo $url; ?>" target="_blank"><?php echo str_replace('http://', '', $url);?></a>
              <p><a href="<?php the_author_meta('googleplus'); ?>" target="_blank"><i class="fa-google-plus-square fa"></i></a><a href="https://twitter.com/<?php the_author_meta('twitter'); ?>" target="_blank"><i class="fa-twitter-square fa"></i></a></p>
            </div>
            <div class="single-info-description">
                <p><?php the_author_meta('description'); ?></p>
                <p>Published <?php the_date(); ?></p>
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