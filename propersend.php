<?php
if(isset($_POST) && !empty($_POST)){
	print_r($_POST);
	if(isset($_POST['id']) && isset($_POST['mail']) && isset($_POST['userid']) && isset($_POST['post_id'])){
		$vendors_id = $_POST['id'];
		$mails = $_POST['mail'];
		$userid = is_numeric($_POST['userid']) ? $_POST['userid'] : "";
		$post_id = is_numeric($_POST['post_id']) ? $_POST['post_id'] : "";
		if($userid !== "" && $post_id !== ""){
			require_once "sendemail.php";
			$mail = new SendEmail($vendors_id, $mails, $userid, $post_id);
		}else{
			echo "Выделите галочками поставщиков, которым вы хотите отправить запрос";
		}
	}else{
		echo "Выделите галочками поставщиков, которым вы хотите отправить запрос";
	}
}