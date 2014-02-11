<?php 
get_header(); 

if(is_page('contact') OR is_page('apply-to-showcase')) {
  if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
    wpcf7_enqueue_scripts();
    wpcf7_enqueue_styles();
  }
}
?>
  <div class="main">
    <section class="main-page">
      <div class="page-content">

        <article class="page-post">

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                
                <?php endwhile; else: ?>
                
                    <p><?php _e('Sorry, this page does not exist.'); ?></p>
                
                <?php endif; ?>

        </article>
      </div>
    </section>
  </div>

<?php get_footer(); ?>