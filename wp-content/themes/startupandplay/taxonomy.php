<?php get_header(); ?>

<?php include 'main-aside.php'; ?>

        <section class="wrapper">
            <div class="wrapper-content">
            
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
                <article class="wrapper-content-post">
                    
                    <div class="wrapper-content-title"><a href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
                        
                            <p class="wrapper-content-byline"><?php the_author_posts_link(); ?> in <?php echo get_the_term_list( $wp_query->post->ID, 'collections', '', ', ', '' ); ?></p>
                    
                    </div>
                    <div class="wrapper-content-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
                    
                    </div>
                    <div class="wrapper-content-excerpt">
                        <?php echo the_excerpt(); ?> 
                    </div>
                    
                </article>
                
                <?php endwhile; ?>
                <?php endif; wp_reset_query(); ?>
                
            </div>
        </section>

<?php get_footer(); ?>