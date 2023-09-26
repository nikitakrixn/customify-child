<?php
/**
 * 
 * General goal is send mail of vendor
 * 
 */
class SendEmail{
	// максимальное кол-во писем от бесплатного пользователя
	const limit = 3;
    private $genEmail = 'oz@tpktrade.ru';
    //private $genEmail = 'khachaturovedgar01@gmail.com';
    private $vendor;
    private $vendor_email;
    private $tz;
	private $user_mail;
	
    public function __construct($vendors_id, $mails, $userid, $post_id){
        $this->vendor = $vendors_id;
        $this->vendor_email = $mails;
        $this->tz = get_post($post_id);
		$this->user_mail = get_user_meta($userid, 'email', true);
		$this->userid = $userid;
    }
	
	private function getTzUrl(){
		$post = $this->tz;
		$post_url = $post->guid;
		return $post_url;
	}
	
	private function getTzName(){
		$post = $this->tz;
		$post_name = "Техническое задание № " . $post->post_title . " от " . $post->post_date;
		return $post_name;
	}

	private function membership_plan(){
		if(wc_memberships_is_user_active_member($this->userid) == true){
			$current_user = wp_get_current_user();
			$count_memberships = count(wc_memberships_get_user_active_memberships($current_user->ID));
			if ($count_memberships > 0) {
				return 'member';
			}else{
				return 'nomember';
			}
		}else{
			return 'nomember';
		}
//return 'member'; //чтобы все работало, без учета членства Шарапова
    }
	
	private function getArray(){
		$vendor_email = $this->vendor_email;
		$user_email = $this->user_mail;
		$post_name = $this->getTzName();
		$post_url = $this->getTzUrl();
		
		$arrayMessage = array();
		$vendor_count = count($vendor_email);
		for($i = 0; $i < $vendor_count; $i++){
			$arrayMessage[$i][] = '<p>Фабрика:' . " " . $vendor_email[$i];
			$arrayMessage[$i][] = '</p>Заказчик:' . " " . $user_email;
			$arrayMessage[$i][] = 'Название ТЗ: ' . " " . $post_name;
			$arrayMessage[$i][] = 'ТЗ: ' . " " . $post_url;
		}
		return $arrayMessage;
	}
	
	private function save_mails0(){
		$ids = $this->vendor;
		$user_id = $this->userid;
		if ($this->membership_plan() !== 'member') {
			if (get_user_meta( $user_id, 'mails_' . $this->tz->ID)) {
				if (count(get_user_meta( $user_id, 'mails_' . $this->tz->ID)[0]) !== 900) {
					$array_intersect = array_intersect(get_user_meta( $user_id, 'mails_' . $this->tz->ID)[0], $ids);
					if(count($array_intersect) > 0){
						foreach ($array_intersect as $key => $value) {
							foreach ($ids as $k => $v) {
								if($value == $v){
									unset($ids[$k]);
								}
							}
						}
						$new_user_meta = array_merge(get_user_meta( $user_id, 'mails_' . $this->tz->ID)[0], $ids);
						update_user_meta($user_id, 'mails_' . $this->tz->ID, $new_user_meta);
						return 'success';
					}else{
						$new_user_meta = array_merge(get_user_meta( $user_id, 'mails_' . $this->tz->ID)[0], $ids);
						update_user_meta($user_id, 'mails_' . $this->tz->ID, $new_user_meta);
						return 'success';
					}
				}else{
					return;
				}
			}else{
				update_user_meta( $user_id, 'mails_' . $this->tz->ID, $ids);
			}
		}else{
			return 'success';
		}
	}

	private function save_mails(){
		$user_id = $this->userid;
		$vendor_id = $this->vendor;
		
		$mails = get_user_meta($vendor_id, 'tz_mails_' . $this->tz->ID, true);
		if (!$mails) $mails = [];
		$messages = [];
		foreach ($this->vendor as $id) {
			$mails[$id] = true;
			$messages[] = $id;
			update_user_meta($id, 'tz_mails_' . $this->tz->ID, true);
		}
		
		if ($this->membership_plan() === 'member')
			return $this->vendor;
		
		if (count($mails) > self::limit) return [];
		
		//update_user_meta($vendor_id, 'tz_mails_' . $this->tz->ID, true);
		return $messages;
	}
	
	public function send(){
		$messages = $this->save_mails();
		// лимиты превышены
		if (!$messages) return 'Превышены лимиты (для бесплатного пользователя не больше 3)';
		
		add_filter( 'wp_mail_from_name', function($from_name){
			return 'tpktrade.ru';
		});
		add_filter( 'wp_mail_from', function($from_name){
			return 'oz@tpktrade.ru';
		});
		add_filter('wp_mail_content_type', function ($content_type) {
			return "text/html";
		});
		
		$mails = [];
		
		
		$url = get_site_url() . '/-/' . $this->tz->ID . '-2/';
		
		foreach ($messages as $id) {
			$user = get_user_by('id',$id);
			if ($user) $mails[]= $user->user_email;
			
			//My Start
			if ($this->userid) {
				$sender_id = $this->userid;
			}
			
			/*
			global $wpdb;
$time = time();
$result = $wpdb->get_var( "SELECT `conv_id` FROM `wp6m_wc_messanger` ORDER BY `wp6m_wc_messanger`.`conv_id` DESC" );
$result_plus = $result+1;
$sms_text = 'Новый запрос на рассмотрение ТЗ: <br> <a href="'.$url.'">Посмотреть ТЗ</a>';
$wpdb->get_results( "INSERT INTO `wp6m_wc_messanger` (`id`, `sender_id`, `reciever_id`, `seen_time`, `conv_id`, `text_sms`, `create_at`)
 VALUES (NULL, '$sender_id', '$id', '0', '$result_plus', '$sms_text', '$time');" );
 */
			//My End
		}
		$mails = implode(',',$mails);
		
		$topic = 'Заявка на пошив по ТЗ №' . $this->tz->ID;
		$author = '';
		if ($this->userid) {
			$user = get_user_by('id', $this->userid);
			if ($user) $author = $user->user_email;
		}
		//$date = date('Y-m-d H:i:s', time()+3*3600);
		$date = $this->tz->post_date;
		$caption = 'Техническое задание № ' . $this->tz->ID . ' от ' . $date;
		$text = "Поставщики: $mails\nЗаказчик: $author\nНазвание ТЗ: $caption\nТЗ: $url\n";
		
		wp_mail($this->genEmail, $topic, $text);
		$n = count($messages);
		return $n;
	}
}
/**
 * 
 * В этом файле добавить мета для пользователя, если он не member
 * После этого сообщить что письмо отправлено
 * 
 */