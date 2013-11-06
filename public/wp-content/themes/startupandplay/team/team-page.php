<?php 
/**
Template Name: Team
 */

get_header();

the_post();

// Get 'team' posts
$team_posts = get_posts( array(
	'post_type' => 'team',
	'posts_per_page' => -1, // Unlimited posts
	'orderby' => 'title', // Order alphabetically by name
) );

if ( $team_posts ):
?>

<?php (include dirname(__FILE__)."/../asides/aside-main.php"); ?>

		<section class="wrapper">
			<div class="wrapper-content">

				<?php 
				foreach ( $team_posts as $post ): 
				setup_postdata($post); ?>
				
				<article class="wrapper-content-post profile">
					
					<div class="profile-header">
						
						<img src="<?php the_field('team_picture'); ?>" alt="<?php the_title(); ?>, <?php the_field('team_role'); ?>" class="img-circle">

					</div>
		
					<div class="profile-content">
						<h3><?php the_title(); ?></h3>
						<p class="lead position"><?php the_field('team_role'); ?></p>
						<?php the_content(); ?>
					</div>
		
					<div class="profile-footer">
						
						<?php if ( $googleplus = get_field('team_googleplus') ): ?>
							<a href="<?php echo $googleplus; ?>"><i class="icon-google-plus-sign"></i></a>
						<?php endif; ?>
						
						<?php if ( $twitter = get_field('team_twitter') ): ?>
							<a href="https://twitter.com/<?php echo $twitter; ?>"><i class="icon-twitter-sign"></i></a>
						<?php endif; ?>
						
						
					</div>
			</article>
				
				<?php endforeach; ?>
			</div>
		</section>

<?php endif; ?>

<?php get_footer(); ?>