<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width" initial-scale="1.0" />

    <title><?php wp_title('|'); ?></title>

    <?php
    // Cache buster for stylesheet
    $stylesheet = '/css/screen.css';
    $style_path = get_template_directory() . $stylesheet;
    $style_uri  = get_bloginfo('template_url') . $stylesheet . '?' . filemtime($style_path);
    ?>
    <link href='http://fonts.googleapis.com/css?family=Bitter:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $style_uri; ?>" />

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <div class="wrapper"><?php
    if (!is_page('home')) { ?>
      <header>
        <nav>
          <div class="container">
          </div>
        </nav>
      </header><?php
    } ?>