<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


//Запрет обновления тем

//remove_action('load-update-core.php', 'wp_update_themes');
//add_filter('pre_site_transient_update_themes', '__return_null');
//wp_clear_scheduled_hook('wp_update_themes');

//Запрет обновления плагинов

//remove_action( 'load-update-core.php', 'wp_update_plugins' );
//add_filter( 'pre_site_transient_update_plugins', '__return_null' );
//wp_clear_scheduled_hook( 'wp_update_plugins' );


//Отключение обновлений движка WordPress

//add_filter('pre_site_transient_update_core', '__return_null');
//wp_clear_scheduled_hook('wp_version_check');



// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter('gutenberg_use_widgets_block_editor', '__return_false');

// Disables the block editor from managing widgets.
add_filter('use_widgets_block_editor', '__return_false');





/* Disable WordPress Admin Bar for all users */
//add_filter( 'show_admin_bar', '__return_false' );

/**
 * Disable the emoji's***********************************************
 */
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
	
 remove_action( 'wp_head', 'wp_resource_hints', 2, 99 );//Remove S.W.org DNS
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param array $plugins 
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
 if ( 'dns-prefetch' == $relation_type ) {
 /** This filter is documented in wp-includes/formatting.php */
 $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

$urls = array_diff( $urls, array( $emoji_svg_url ) );
 }

return $urls;
}



/*Disable author page*****************************************/
function disable_author_page() {
    global $wp_query;

    // If an author page is requested, redirects to the home page
    if ( $wp_query->is_author ) {
        wp_safe_redirect( get_bloginfo( 'url' ), 301 );
        exit;
    }
}
add_action( 'wp', 'disable_author_page' );


//Disable Self Pingback***************************************
function disable_pingback( &$links ) {
 foreach ( $links as $l => $link )
 if ( 0 === strpos( $link, get_option( 'home' ) ) )
 unset($links[$l]);
}
add_action( 'pre_ping', 'disable_pingback' );

//stop heartbeat*********************************************
add_action( 'init', 'stop_heartbeat', 1 );
function stop_heartbeat() {
wp_deregister_script('heartbeat');
}

remove_action( 'admin_enqueue_scripts', 'wp_auth_check_load' );

add_action( 'admin_init', 'disable_autosave' );
function disable_autosave() {
wp_deregister_script( 'autosave' );
}


//Gutenberg*********************************************
add_filter('use_block_editor_for_post', '__return_false', 10);
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );

//add_filter( 'render_block', '__return_false', 100 );

// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
add_filter( 'use_widgets_block_editor', '__return_false' );

/*Removes RSD, XMLRPC, WLW, WP Generator, ShortLink and Comment Feed links*******************************/

add_filter('xmlrpc_enabled', '__return_false'); 

function whtop_setup()
{
    remove_action( 'wp_head', 'feed_links_extra', 3); // Remove category feeds
    remove_action( 'wp_head', 'feed_links', 2); // Remove Post and Comment Feeds
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    add_filter( 'the_generator', '__return_null' );
    remove_action('wp_head', 'wp_shortlink_wp_head');

}
add_action( 'after_setup_theme', 'whtop_setup' );


///Disable Embeds in WordPress With Code********************

function disable_embeds_code_init() {

 // Remove the REST API endpoint.
 remove_action( 'rest_api_init', 'wp_oembed_register_route' );

 // Turn off oEmbed auto discovery.
 add_filter( 'embed_oembed_discover', '__return_false' );

 // Don't filter oEmbed results.
 remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

 // Remove oEmbed discovery links.
 remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

 // Remove oEmbed-specific JavaScript from the front-end and back-end.
 remove_action( 'wp_head', 'wp_oembed_add_host_js' );
 add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

 // Remove all embeds rewrite rules.
 add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

 // Remove filter of the oEmbed result before any HTTP requests are made.
 remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

add_action( 'init', 'disable_embeds_code_init', 9999 );

function disable_embeds_tiny_mce_plugin($plugins) {
    return array_diff($plugins, array('wpembed'));
}

function disable_embeds_rewrites($rules) {
    foreach($rules as $rule => $rewrite) {
        if(false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}

function my_deregister_embed_scripts(){
 wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_embed_scripts' );


//Отключаем тесты!*******************

add_filter( 'site_status_tests', function( $tests ) {
 
    // асинхронные запросы находятся в массиве 'async'
    unset( $tests['async']['dotorg_communication'] );// Соединение с WordPress.org
    unset( $tests['async']['background_updates'] );//Фоновые обновления
    unset( $tests['async']['loopback_requests'] );//Петлевой запрос
 
    // синхронные – в массиве 'direct'
    unset( $tests['direct']['rest_availability'] );//Доступность REST API
    
    /*wordpress_version – Версия WordPress,
plugin_version – Версии плагинов,
theme_version – Версии тем,
php_version – Версия PHP,
php_extensions – PHP расширения,
php_default_timezone – Часовой пояс PHP по умолчанию,
sql_server – Версия ПО СУБД,
utf8mb4_support – MySQL поддержка кодировки utf8mb4,
https_status – Статус HTTPS,
ssl_support – Безопасное подключение,
scheduled_events – Запланированные события WP_Cron,
http_requests – HTTP запросы,
debug_enabled – Включена отладка (имеется ввиду WP_DEBUG в wp-config.php),*/
 
    return $tests;
 
} );




//Jquery migrate disable

function wpschool_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_action( 'wp_default_scripts', 'wpschool_remove_jquery_migrate' );