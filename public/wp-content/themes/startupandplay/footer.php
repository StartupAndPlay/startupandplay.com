<?php
  global $prevArray;
  if (isset($prevArray['ID'])) {
    $prevID = $prevArray['ID'];
  } else {
    $prevID = false;
  }
  $addMasthead = get_field('add_post_masthead',$prevID);
  $masthead = get_field('post_masthead', $prevID); 

        if (is_single() && $prevID) { ?>
          <footer class="preload">
            <div class="preload-image" style="background-image: url('<?php echo $masthead['url']; ?>');"></div>
            <div class="preload-overlay"></div>
            <div class="preload-title">
              <h2><a href="<?php echo get_permalink($prevID); ?>"><?php echo get_the_title($prevID); ?></a></h2>
            </div>
          </footer><?php
        } else { ?>
          <footer class="no-preload">
            <nav>
              <ul>
                <?php wp_nav_menu(array('menu' => 'main-navigation','container' => 'false' )); ?>
              </ul>
            </nav>
          </footer><?php
        } ?>
    </div>
  </div>
<?php wp_footer(); ?>
</body>
</html>