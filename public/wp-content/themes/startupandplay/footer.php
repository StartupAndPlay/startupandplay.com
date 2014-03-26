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
              <div class="row">
                <div class="col-sm-6">
                  <ul class="menu">
                    <?php wp_nav_menu(array('menu' => 'main-navigation','container' => 'false' )); ?>
                  </ul>
                </div>
                <div class="col-sm-6">
                  <ul class="social">
                    <li><a href="https://twitter.com/startupandplay" target="_blank"><i class="fa fa-twitter-square"></i></a></li>
                    <li><a href="https://www.facebook.com/StartupAndPlay" target="_blank"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="https://plus.google.com/+Startupandplay/" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>
                    <li><a href="https://github.com/StartupAndPlay" target="_blank"><i class="fa fa-github-square"></i></a></li>
                  </ul>
                </div>
              </div>
            </nav>
          </footer><?php
        } ?>
    </div>
  </div>
<?php wp_footer(); ?>
</body>
</html>