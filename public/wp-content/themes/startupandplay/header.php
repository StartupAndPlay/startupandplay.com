<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width" initial-scale="1.0" />
    <link rel="icon" href="/wp-content/themes/startupandplay/img/favicon.png" />
    <title><?php wp_title('|'); ?></title>

    <?php
    // Cache buster for stylesheet
    $stylesheet = '/css/screen.css';
    $style_path = get_template_directory() . $stylesheet;
    $style_uri  = get_bloginfo('template_url') . $stylesheet . '?' . filemtime($style_path);
    ?>
    <link href='http://fonts.googleapis.com/css?family=Bitter:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $style_uri; ?>" /><?php

    if (is_single()) {
      global $prevArray;
      if(isset($prevArray['ID'])) {
        $prevID = $prevArray['ID'];
        $prevID = get_permalink($prevID);
        echo '<link rel="prefetch" href="'.$prevID.'">';
        echo '<link rel="prerender" href="'.$prevID.'">';
      }
    }
    wp_head(); ?>

</head>
<body <?php body_class(); ?>>
  <div class="wrapper">
    <h1 class="logo"><a href="/"><img src="/wp-content/themes/startupandplay/img/startup-and-play-square.png" alt="Startup and Play" /></a></h1>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse" id="main-nav">
          <ul class="nav navbar-nav navbar-right">
             <?php wp_nav_menu(array('container' => 'false' )); ?>
          </ul>
        </div>
      </div>
    </nav>
