<?php get_header(); ?>

  <div class="main">
    <section class="main-page">
      <div class="page-content">
        <article class="page-post">

          <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

              <h1><?php the_title(); ?></h1><?php 

              the_content(); 

              if(is_page('contact')) {

                include_once(get_template_directory().'/partials/form-contact.php');

              } else if (is_page('apply-to-showcase')) { 

                include_once(get_template_directory().'/partials/form-apply.php');

              } ?>
          
          <?php endwhile; else: ?>
          
              <p><?php _e('Sorry, this page does not exist.'); ?></p>
          
          <?php endif; ?>

        </article>
      </div>
    </section>
  </div>

<?php get_footer(); ?>