<?php

// Calls previous post, sets up for prerendering
$prevPost = get_previous_post();
$prevArray = (array) $prevPost;
global $prevArray;

if(isset($prevArray['ID'])) {
  $prevID = $prevArray['ID'];
}
if(isset($prevArray['post_title'])) {
  $prevTitle = $prevArray['post_title'];
}

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
  // Custom Fields (Default Post Set)
  $add_post_masthead = get_field('add_post_masthead');
  if ($add_post_masthead) {
    $masthead = get_field('post_masthead');
      $mastheadURL = $masthead['url'];
    $mastheadBlur = get_field('post_masthead_blur');
      $mastheadBlurURL = $mastheadBlur['url'];
  }
  $add_post_eventbrite = get_field('add_post_eventbrite');
  if ($add_post_eventbrite) {
    $eventbrite = get_field('post_eventbrite');
  }

  // Controls the masthead
  if ($add_post_masthead) { ?>
  <div class="masthead">
    <div class="clear" style="background-image:url('<?php echo $mastheadURL; ?>')"></div>
    <div class="blur" style="background-image:url('<?php echo $mastheadBlurURL; ?>')"></div>
  </div><?php
  } ?>

  <div class="main">      
    <section class="main-single"><?php
      if($add_post_masthead) {
        echo '<div class="blocker"></div>';
      }?>
      <div class="single-content">
        
          <article class="single-post">
            <?php if (get_field('deprecated_featured_image')) { ?>
              <div class="single-post-image">
                <?php echo get_the_post_thumbnail(); ?>
              </div>
            <?php } ?>
              <div class="single-post-title"><h1><?php the_title(); ?></h1></div>                
              <div class="single-post-content"><?php 
                the_content(); 
                if ($add_post_eventbrite) { echo $eventbrite; } ?>
              </div>
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
          </aside><?php
        
        endwhile; else: ?>
          
          <article class="single-post"
            <p><?php _e('Sorry, this post does not exist.'); ?></p>
          </article><?php

        endif;

        if ($environment === 'production') {
          comments_template();
        } ?>
        
      </div>
    </section>
  </div>

<?php get_footer(); ?>