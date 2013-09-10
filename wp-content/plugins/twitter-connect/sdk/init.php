<?php
if ( !defined( 'NEW_TWITTER_LOGIN' ) ) {
	return;
}

require dirname(__FILE__).'/tmhOAuth.php';
require dirname(__FILE__).'/tmhUtilities.php';

$settings = maybe_unserialize(get_option('nextend_twitter_connect'));

$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => $settings['twitter_consumer_key'],
  'consumer_secret' => $settings['twitter_consumer_secret'],
));
