<?php get_header(); ?>
  <div class="masthead">
    <div class="clear"></div>
    <div class="blur"></div>
  </div>
  <div class="main">
    <section class="container">
      <div class="meta"></div>
      <div class="brand">
        <h1>Startup and Play</h1>
        <p>A Raleigh-Durham startup social.</p>
      </div>
      <nav class="metanav"></nav>
      <div class="main-content">
                        
        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 30,
            'order'          => 'DESC',
            'orderby'        => 'date',
        );
                  
        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) :
      
          while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
                      
          <article class="wrapper-content-post">
            <div class="wrapper-content-title">
              <a href="<?php the_permalink(); ?>">
                <h2><?php the_title(); ?></h2>
              </a>
                          
              <p class="wrapper-content-byline"><?php the_author_posts_link(); ?> in</p>
                      
            </div>
            <div class="wrapper-content-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>                    
              <p>
                <?php if ( is_sticky() ) : ?>
                <span class="sticky-featured"><i class="icon-bookmark fontawesome"></i>Featured</span>
                <?php endif;?>
              </p>
            </div>
            <div class="wrapper-content-excerpt">
                <?php echo the_excerpt(); ?> 
            </div>
                      
          </article>
                  
        <?php
        endwhile;
        endif; wp_reset_query(); 
        ?>
                  
      </div>
    </section>
  </div>

<?php get_footer(); ?>