<?php
/**
*
* Template Name: Team
*
**/

get_header(); ?>

<div class="main">
  <section class="main-page team">
    <div class="page-content">
      <article class="page-post">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

          <h1><?php the_title(); ?></h1><?php

          $teamRepeater = get_field('team_repeater');
          foreach ($teamRepeater as $member) {

            $avatar = $member['member_avatar'];
              $avatarURL = $avatar['url'];
              $avatarAlt = $avatar['alt'];
              $avatarTitle = $avatar['title'];

            echo '<div class="row">';
            echo '<div class="col-md-12 member">';
            echo '<img src="'.$avatarURL.'" title="'.$avatarTitle.'" alt='.$avatarAlt.'>';
            echo '<h2>'.$member['member_name'].'</h2>';
            echo '<ul>';
            if($member['member_twitter']) {
              echo '<li><a href="https://twitter.com/'.$member['member_twitter'].'" target="_blank"><i class="fa fa-twitter-square"></i></a></li>';
            }
            if($member['member_google']) {
              echo '<li><a href="https://plus.google.com/'.$member['member_google'].'" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>';
            }
            echo '</ul>';
            echo '<p>'.$member['member_bio'].'</p>';
            echo '</div></div>';
          } ?>

          <?php endwhile; else: ?>
          
              <p><?php _e('Sorry, this page does not exist.'); ?></p>
          
          <?php endif; ?>

      </article>
    </div>
  </section>
</div>

<?php get_footer(); ?>