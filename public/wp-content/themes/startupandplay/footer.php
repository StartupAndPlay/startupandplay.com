<?php
	global $prevArray;
	$prevID = $prevArray['ID'];
	var_dump($prevID);
?>
  
			<footer><?php
				if (is_single()) { ?>
					<div class="preload-post">
						<a href="<?php echo get_permalink($prevID); ?>">Click Me!</a>
					</div><?php
				} else { ?>
					<nav>
						<ul>
				      <?php wp_nav_menu(array('menu' => 'main-navigation','container' => 'false' )); ?>
				    </ul>
					</nav><?php
				} ?>
			</footer>
		</div>
	</div>
<?php wp_footer(); ?>
</body>
</html>