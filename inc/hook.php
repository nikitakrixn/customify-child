<?php
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Выводит на экран список всех хуков WordPress и функций к ним прикрепленных.
 *
 * @param строка $hook_name Название хука список фукнций которого нужно вывести.
 *
 * @version 2.1
 */
function hooks_list($hook_name = '')
{
	global $wp_filter;
	$wp_hooks = $wp_filter;

	if (is_object(reset($wp_hooks))) {
		foreach ($wp_hooks as &$object) $object = $object->callbacks;
		unset($object);
	}

	if ($hook_name) {
		$hooks[$hook_name] = @$wp_hooks[$hook_name];

		if (!is_array($hooks[$hook_name])) {
			trigger_error("Nothing found for '$hook_name' hook", E_USER_WARNING);
			return;
		}
	} else {
		$hooks = $wp_hooks;
		ksort($wp_hooks);
	}

	$out = '';
	foreach ($hooks as $name => $funcs_data) {
		ksort($funcs_data);
		$out .= "\nхук\t<b>$name</b>\n";
		foreach ($funcs_data as $priority => $functions) {
			$out .= "$priority";
			foreach (array_keys($functions) as $func_name) $out .= "\t$func_name\n";
		}
	}

	echo '<' . 'pre>' . $out . '</pre' . '>';
}

//hooks_list();





function slug_save_post_callback( $post_ID, $post, $update ) {
    // allow 'publish', 'draft', 'future'
    if ($post->post_type != '_' || $post->post_status == 'auto-draft')
        return;

    // only change slug when the post is created (both dates are equal)
    if ($post->post_date_gmt != $post->post_modified_gmt)
        return;

    // unhook this function to prevent infinite looping
    remove_action( 'save_post', 'slug_save_post_callback', 10, 3 );
    // update the post slug (WP handles unique post slug)
    wp_update_post( array(
        'ID' => $post_ID,
        'post_name' => $post_ID
    ));
    // re-hook this function
    add_action( 'save_post', 'slug_save_post_callback', 10, 3 );
}
add_action( 'save_post', 'slug_save_post_callback', 10, 3 );