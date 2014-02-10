<?php get_header(); ?>

        <article class="wrapper">
            <div class="wrapper-content">

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                
                <?php endwhile; else: ?>
                
                    <p><?php _e('Sorry, this page does not exist.'); ?></p>
                
                <?php endif; ?>

            </div>
        </article>

<?php get_footer(); ?>