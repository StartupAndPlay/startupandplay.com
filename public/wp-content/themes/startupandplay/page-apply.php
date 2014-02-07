<?php
/**
Template Name: Apply
*/

if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
  wpcf7_enqueue_scripts();
  wpcf7_enqueue_styles();
}

get_header(); ?>

<?php include 'asides/aside-main.php'; ?>

<article class="wrapper">
  <div class="wrapper-content">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <h1><?php the_title(); ?></h1>
      <?php the_content(); ?>

    <?php endwhile; else: ?>

    <p><?php _e('Sorry, this page does not exist.'); ?></p>

  <?php endif; ?>

  <div class="wrapper-content-form">

    <?php echo do_shortcode("[contact-form-7 id=\"513\" title=\"Application to Showcase\"]"); ?>

  </div>

</div>
</article>

<?php get_footer(); ?>