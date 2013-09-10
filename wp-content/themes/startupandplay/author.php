<?php get_header(); ?>

<?php include 'main-aside.php'; ?>

        <section class="wrapper">
            <div class="wrapper-content">
                
                <H1><?php the_author(); ?></H1>
                
                <p><?php the_author_posts(); ?> posts</p>
            
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    

                
                <?php endwhile; ?>
                <?php endif; wp_reset_query(); ?>
                
            </div>
        </section>

<?php get_footer(); ?>