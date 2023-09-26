<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( class_exists( 'WCFMem_Dependencies' ) ) {

	function change_heading_widget_content( $widget_content, $widget ) {
		$id = $widget->get_id();
		global $post;
		if ( ! wc_memberships_is_user_active_member( $post->post_author ) && ! wcfm_is_vendor( get_current_user_id() ) ) {
			if ( $id == '28f7a9f' || $id == 'd05aa39' ) {
				if ( ! current_user_can( 'administrator' ) && wc_memberships_is_user_active_member( get_current_user_id() ) !== true && ! isset( $_GET['post'] ) ) {
					$widget_content = "";
				}
			} else if ( $id == '2ac26a6' ) {
				if ( ! current_user_can( 'administrator' ) && wc_memberships_is_user_active_member( get_current_user_id() ) !== true && ! isset( $_GET['post'] ) ) {
					//$widget_content = '<span style="text-align: center; disaply: block;">Чтобы любые другие поставщики могли откликаться - <a target="_blank" href="https://tpktrade.ru/tarif-pro-dostup/ ">перейдите на Pro</a></span>';
				}
			}

		} else if ( wc_memberships_is_user_active_member( $post->post_author ) && ! wcfm_is_vendor( get_current_user_id() ) ) {
			if ( $id == '28f7a9f' || $id == 'd05aa39' || $id == '2ac26a6' ) {
				if ( ! current_user_can( 'administrator' ) ) {
					$widget_content = "";
				}
			}
		}

		return $widget_content;
	}

	add_filter( 'elementor/widget/render_content', 'change_heading_widget_content', 10, 2 );

}


// add_action('elementor/widget/before_render_content', 'remove_form');

// function remove_form()
// {
// 	$current_user = wp_get_current_user();

// 	$current_user_roles = $current_user->roles;
// 	$is_admin = $is_author = 0;
// 	foreach ($current_user_roles as $current_user_role) {

// 		if ($current_user_role == 'administrator') {

// 			$is_admin = 1;
// 		}
// 		if ($current_user_role == 'author')
// 			$is_author = 1;
// 	}

// 	$user_id = get_current_user_id();

// 	if (!$is_admin) {
// 		if (wc_memberships_is_user_active_member($user_id)) {
// 			$result = "jQuery('.elementor-element-88f198a, .elementor-element-71356d7').remove();";
// 		} else {
// 			$result = "jQuery('.elementor-element-0693d3e').remove();";
// 		}

// 		return $result;
// 	}

// return;

// }


//Register Style and Script for Elementor Widget
add_action( 'wp_enqueue_scripts', 'elementor_widget_dependencies' );

function elementor_widget_dependencies() {
    wp_enqueue_script( 'filter-widget-script', get_stylesheet_directory_uri() . '/inc/widgets/assets/js/filter.js', ['jquery'] );

    wp_register_style( 'filter-widget-style', get_stylesheet_directory_uri() . '/inc/widgets/assets/css/filter.css' );

}

//Register New Widgets
add_action( 'elementor/widgets/register', 'register_tpktrade_widgets' );
function register_tpktrade_widgets( $widgets_manager ) {
	require_once( __DIR__ . '/widgets/cart_info.php' );
	require_once( __DIR__ . '/widgets/tz_filter.php' );

	$widgets_manager->register( new \Elementor_Cart_Info_Widget );
	$widgets_manager->register( new \Elementor_Filter_TZ );
}






