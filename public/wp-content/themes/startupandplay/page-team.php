<?php
/**
*
* Template Name: Team
*
**/

get_header(); ?>

<div class="main">
  <section class="main-page">
    <div class="page-content">
      <article class="page-post">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
          <h1><?php the_title(); ?></h1><?php

          $teamRepeater = get_field('team_repeater');
          foreach ($teamRepeater as $member) {
            $name = $member['member_name'];
            $avatar = $member['member_avatar'];
              $avatarID = $avatar['id'];
            $bio = $member['member_bio'];

            echo $name.$bio;

          }
          ?>
                    
                
          <?php endwhile; else: ?>
          
              <p><?php _e('Sorry, this page does not exist.'); ?></p>
          
          <?php endif; ?>

      </article>
    </div>
  </section>
</div>

<?php get_footer(); ?>