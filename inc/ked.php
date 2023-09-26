<?php
if ( !defined( 'ABSPATH' ) ) exit;

add_action('wp_footer', 'ked_scripts', 10);
function ked_scripts()
{
	wp_enqueue_script('ked_script', get_stylesheet_directory_uri() . '/assets/js/ked_addon.js');
}

add_action('wp_ajax_kedemail', 'ked_email');
add_action('wp_ajax_nopriv_kedemail', 'ked_email');

// яваскрипт в ked_addon.js
function ked_email()
{
	//var_dump($_POST); wp_die();
	$user_id = $_POST['userid'] ?? 0;
	$post_id = $_POST['post_id'] ?? 0;
	$mails = $_POST['mail'] ?? [];
	$vendors_id = $_POST['id'] ?? [];
	
	$unix_time = time();
	$format = 'd/m/y';
	$curtime = get_the_date($format, $post_id);
	$url = get_the_permalink($post_id);
	$content = 'Просим Вас предоставить экономическое предложение по новому ТЗ №' . $post_id . ': <br> <a href="' . $url . '">Посмотреть ТЗ от ' . $curtime . '</a>';
	$attachment_id = '';
	global $wpdb;
	$table = $wpdb->prefix . 'yltm_messages';
	foreach ($vendors_id as $vendor){
	$data = array('sender' => $user_id, 'receiver' => $vendor, 'work_id' => $post_id, 'unix_time' => $unix_time, 'seen' => 0, 'deleted' => '0', 'content' => $content, 'files' => '');
	$wpdb->insert($table, $data);
	}
	
	if (!$post_id or !$vendors_id) {
		$answer = "Заполните все поля";
	} else if (!$user_id) {
		$answer = 'Для отправки ТЗ поставщикам войдите или зарегистрируйтесь';
	} else {
            require get_stylesheet_directory() . '/sendemail.php';
			$mail = new SendEmail($vendors_id, $mails, $user_id, $post_id);
			$answer = $mail->send();
			if (!$answer) $answer = "Письма не отправлены";
	}
	$resp = [];
	$resp['count'] = $answer;
	$resp['answer'] = 'Отправлено ' . $answer . ' писем!';
	$resp['messages'] = $vendors_id;
	$resp['ad'] =  array('sender' => $user_id, 'receiver' => $vendor, 'work_id' => $post_id, 'unix_time' => $unix_time, 'seen' => 0, 'deleted' => '0', 'content' => $content, 'files' => '');
	echo json_encode($resp);
	wp_die();
}