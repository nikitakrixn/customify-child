<?php
if ( !defined( 'ABSPATH' ) ) exit;


add_action('wp_logout', 'njengah_homepage_logout_redirect');

function njengah_homepage_logout_redirect()
{

	wp_redirect(home_url());

	exit;
}