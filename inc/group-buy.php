<?php
if ( !defined( 'ABSPATH' ) ) exit;

add_shortcode('my_group_buy', 'my_group_buy_func');

function my_group_buy_func($atts)
{
	if (!defined('ABSPATH')) exit;

	ob_start();

	get_template_part('woocommerce/single-product/groupbuy-participate');

	return ob_get_clean();
}