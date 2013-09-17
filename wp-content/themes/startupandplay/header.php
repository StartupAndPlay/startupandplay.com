<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php wp_title(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>    
<body <?php body_class(); ?>>
    <div class="container">
            <a class="nav-img" href="#menu"></a>
        <nav id="menu" class="hidden">
            <ul>
                <?php get_search_form( true ); ?>
                    
                <li><a href="/" title="Home"><i class="icon-home fontawesome"></i>Home</a></li>
                
                <?php if ( is_user_logged_in() ) { ?>
                    
                    <?php global $userdata; get_currentuserinfo(); ?>
                
                <li>
                    
                    <a href="/author/<?php echo $userdata->user_login; ?>" title="Public Profile">
                    <span class="mmenu-avatar"><?php echo get_avatar( $userdata->ID, 50 ); ?></span>
                    <span class="mmenu-author"><?php echo $userdata->user_firstname . ' ' . $userdata->user_lastname; ?></span>
                    </a>
                    
                    <a class="mmenu-settings" href="/edit-profile" title="Edit Profile"><i class="icon-cog fontawesome"></i></a>
                    
                </li>
                <li><a href="/new-post/" title="New Post"><i class="icon-edit fontawesome"></i>Create New Post</a></li>
                <li><a href="/dashboard/" title="Post Dashboard"><i class="icon-dashboard fontawesome"></i>Dashboard</a></li>

                <?php if ( current_user_can( 'manage_options' ) ) { ?>
                <li><a href="/wp-admin/" title="Admin Panel"><i class="icon-beaker fontawesome"></i>Admin Panel</a></li>
                <li><a href="/wp-admin/edit.php" title="Edit Posts"><i class="icon-tasks fontawesome"></i>Edit Posts</a></li>
                <li><a href="/wp-admin/users.php" title="Edit Users"><i class="icon-group fontawesome"></i>Edit Users</a></li>
                <?php } ?>
                
                
                <li><a href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout"><i class="icon-signout fontawesome"></i>Logout</a></li>

                
                <?php } else { ?>
                                
                <li><a href="<?php echo get_bloginfo('url'); ?>/wp-login.php?loginTwitter=1&redirect=<?php echo get_bloginfo('url'); ?>" onclick="window.location = '<?php echo get_bloginfo('url'); ?>/wp-login.php?loginTwitter=1&redirect='+window.location.href; return false;"><i class="icon-twitter fontawesome"></i>Sign in with Twitter</a></li>
                                
                <?php } ?>
                
            </ul>
        </nav>
        <div class="container-screen">
            <div class="container-screen-content">