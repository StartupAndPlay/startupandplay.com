<?php get_header(); ?>
  <div class="masthead">
    <div class="clear"></div>
    <div class="blur"></div>
  </div>
  <div class="main">
    <section class="main-front">
      <div class="meta">
        <h1><a href="/"><img src="/wp-content/themes/startupandplay/img/startup-and-play-square.png" alt="Startup and Play" /></a></h1>
      </div>
      <div class="brand">
        <div class="brand-wrapper">
          <h1>Startup and Play</h1>
          <p>A Raleigh-Durham startup social.</p>
          <div class="btn"><a href="/">View Event</a></div>
        </div>
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
                      
          <article>
            <div class="content-title">
              <a href="<?php the_permalink(); ?>">
                <h2><?php the_title(); ?></h2>
              </a>
                          
              <p class="content-byline"><?php the_author(); ?></p>
                      
            </div>
            <div class="content-avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>                    
              <p>
                <?php if ( is_sticky() ) : ?>
                <span class="sticky-featured"><i class="fa fa-bookmark"></i>Featured</span>
                <?php endif;?>
              </p>
            </div>
            <div class="content-excerpt">
                <p><?php echo get_excerpt(210) ?></p>
            </div>
                      
          </article>
                  
        <?php
        endwhile;
        endif; wp_reset_query(); 
        ?>
                  
      </div>
    </section>

<?php get_footer(); ?>