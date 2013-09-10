        <aside class="main">
            <div class="main-img"></div>
            <div class="main-body">
                <div class="main-body-content">
                    <h1 class="main-body-title">Startup and Play</h1>
                        <p class="main-body-description">A Raleigh-Durham startup social.</p>
                                                
                    <div class="main-body-register">
                            <?php
                                
                                if ( is_user_logged_in() ) { echo '<a class="btn btn-large" href="/new-post/"><i class="icon-edit fontawesome"></i>New Post</a>'; }
                        
                                else { echo '<a class="btn btn-large" href="http://startupandplay.dev/wp-login.php?loginTwitter=1&redirect=http://startupandplay.dev" onclick="window.location = \'http://startupandplay.dev/wp-login.php?loginTwitter=1&redirect=\'+window.location.href; return false;"><i class="icon-twitter fontawesome"></i>Sign in with Twitter</a>'; } 

                            ?>

                    </div>
                </div>    
                    
                <div class="main-nav">
                            <ul>
                                <?php wp_nav_menu(array('menu' => 'main-navigation','container' => 'false' )); ?>
                            </ul>
                </div>    
            </div>
        </aside>