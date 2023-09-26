<?php
if (!defined('ABSPATH')) exit;



// страница "Список пользователей"
add_shortcode('vendorlist', 'vendorlist');
function vendorlist()
{
	ob_start();
	require get_stylesheet_directory() . '/vendorlist.php';
	return ob_get_clean();
}



// страница "Сравнить анкету поставщика с ТЗ"
add_shortcode('compare_ank', 'compare_ank');
function compare_ank()
{
	$htm = '<h1>Сравнить анкету поставщика с ТЗ</h1>';
	$htm .= '<table><tr><td>Название<td>ТЗ<td>Поставщик';
	$post_id = (int) $_GET['post'];
	$user_id = (int) $_GET['user'];
	if (!$post_id or !$user_id) return '';
	//$htm.= $post_id;
	//$acfs = acf_get_fields(122536);
	require get_stylesheet_directory() . '/getvendors.php';
	$vendors = new GetVendors($post_id, null, null);

	foreach ($vendors->main_acf as $value) {
		$tz = $value['fields']['name1'];
		//if ($tz === 'Gruppy_produkczii_legproma') $tz = 'Napravleniyaraboty';
		$ank = $value['fields']['name2'];
		if (is_array($ank)) $ank = $ank['delivery'];

		$tz_acf = get_field($tz, $post_id);
		if ($value['type'] === 'number')
			settype($tz_acf, 'int');
		else if (!$tz_acf) $tz_acf = [];
		else if (!is_array($tz_acf)) $tz_acf = [$tz_acf];
		if (is_array($tz_acf)) {
			foreach ($tz_acf as &$item)
				if (is_int($item))
					$item = get_term_by('term_taxonomy_id', $item)->name;
			unset($item);
		}

		$ank_acf = get_field($ank, "user_$user_id");
		if ($value['type'] === 'number') {
			settype($ank_acf, 'int');
		} else {
			if (!$ank_acf) $ank_acf = [];
			if (!is_array($ank_acf)) $ank_acf = [$ank_acf];
			$list = [];
			foreach ($ank_acf as $item) {
				if (is_int($item))
					$item = get_term_by('term_taxonomy_id', $item)->name;
				else if (is_object($item))
					$item = $item->name;
				if ($item) $list[] = $item;
			}
			$ank_acf = $list;
		}

		if (!$tz_acf) continue;

		$label = get_field_object($tz, $post_id)['label'];
		//$label = print_r($label,true) . " $tz";
		if (is_array($tz_acf)) {
			$tz_text = '<ul>';
			foreach ($tz_acf as $row)
				$tz_text .= '<li>' . $row . '</li>';
			$tz_text .= '</ul>';
		} else {
			$tz_text = $tz_acf;
		}

		if (is_array($ank_acf)) {
			$ank_text = '<ul>';
			foreach ($ank_acf as $row)
				$ank_text .= "<li>$row</li>";
			$ank_text .= '</ul>';
		} else {
			$ank_text = $ank_acf;
		}

		$htm .= "<tr><td>$label<td>$tz_text<td>$ank_text";
		//$htm.= print_r($obj,true) . ' ... ';
	}

	$htm .= '</table>';
	return $htm;
}



// страница "Цифровой паспорт"
add_shortcode('cp', 'cp');
function cp()
{
	add_action('wp_footer', 'tpk_print_button_script');
	// $htm = '<h1>Цифровой паспорт</h1>';
	$user_id = (int) $_GET['user'];
	if (!wcfm_is_vendor($user_id)) return $htm;
	//$htm = '<a id="print-button" class="tzPrintBtn acf-button button button-primary button-large" href="javascript:void(0);"><span>...</span></a>';




	$acfs = acf_get_fields(124665);
	//var_dump($acfs); die();
	$htm .= '<div class="tz-info-flex">';
	$htm .= '<div class="print-content tz-info">';
	$htm .= '<div class="tz-info__section">';
	//$htm.= '<h3 class="tz-info__title">Основная информация</h3>';
	$htm .= '<h3 class="tz-info__title">Цифровой паспорт</h3>';

	foreach ($acfs as $field) {
		$text = "<div class='tz-info__point-title'>$field[label]</div>";
		$ank = get_field($field['name'], "user_$user_id");
		if (!is_array($ank)) $ank = [$ank];
		$n = 0;
		$text .= '<ul style="margin-top: 5px">';
		foreach ($ank as $item) {
			if (is_object($item))
				$item = $item->name;
			else if (is_int($item) or $item and ((string)(int) $item) === $item)
				$item = get_term_by('term_taxonomy_id', $item)->name;
			if ($item) {
				$text .= "<li>$item</li>";
				$n++;
			}
		}
		$text .= '</ul>';
		if ($n) $htm .= $text;
	}
	$htm .= '</div></div></div>';
	return $htm;
}




// страница "Юридические данные"
add_shortcode('ud', 'ud');
function ud()
{

	$user_id = (int) $_GET['user'];
	if (!wcfm_is_vendor($user_id)) return $htm;
	//$htm = '<a class="tzPrintBtn acf-button button button-primary button-large" href="#" onclick="window.print()">Печать</a>';
	$acfs = acf_get_fields(125467);
	//var_dump($acfs); die();
	$htm .= '<div class="tz-info-flex">';
	$htm .= '<div class="print-content tz-info">';
	$htm .= '<div class="tz-info__section">';
	$htm .= '<h3 class="tz-info__title">Юридические данные</h3>';

	foreach ($acfs as $field) {
		$text = "<div class='tz-info__point-title'>$field[label]</div>";
		$ank = get_field($field['name'], "user_$user_id");
		if (!is_array($ank)) $ank = [$ank];
		$n = 0;
		$text .= '<ul style="margin-top: 5px">';
		foreach ($ank as $item) {
			if (is_object($item))
				$item = $item->name;
			else if (is_int($item) or $item and ((string)(int) $item) === $item)
				$item = get_term_by('term_taxonomy_id', $item)->name;
			if ($item) {
				$text .= "<li>$item</li>";
				$n++;
			}
		}
		$text .= '</ul>';
		if ($n) $htm .= $text;
	}
	$htm .= '</div></div></div>';
	return $htm;
}

// страница "О компании"
add_shortcode('ab_company', 'ab_company');
function ab_company()
{

	$user_id = (int) $_GET['user'];
	if (!wcfm_is_vendor($user_id)) return $htm;
	//$htm = '<a class="tzPrintBtn acf-button button button-primary button-large" href="#" onclick="window.print()">Печать</a>';
	$acfs = acf_get_fields(294551);
	//var_dump($acfs); die();
	$htm .= '<div class="tz-info-flex about">';
	$htm .= '<div class="print-content tz-info">';
	$htm .= '<div class="tz-info__section">';
	$htm .= '<h3 class="tz-info__title">О Компании</h3>';

	foreach ($acfs as $field) {
		$text = "<div class='tz-info__point-title'>$field[label]</div>";
		$ank = get_field($field['name'], "user_$user_id");
		if (!is_array($ank)) {
			$ank = [$ank];
		}
		$n = 0;
		$text .= '<ul style="margin-top: 5px">';
		foreach ($ank as $item) {
			if (is_object($item)) {
				$item = $item->name;
			} else if (is_int($item) or $item and ((string)(int) $item) === $item) {
				$item = get_term_by('term_taxonomy_id', $item)->name;
			}
			if ($item) {
				if ($field['type'] == 'image') {
					$text .= "<li class='pic mypiv'><img src='$item' width='120' height='120' /></li>";
				} else {
					$text .= "<li>$item</li>";
				}
				$n++;
			}
		}
		$text .= '</ul>';
		if ($n) {
			$htm .= $text;
		}
	}

	$htm .= '</div></div></div>';
	return $htm;
}


// страница "Рейтинг поставщика"
add_shortcode('vendor_reiting', 'vendor_reiting');
function vendor_reiting()
{

	$user_id = (int) $_GET['user'];
	if (!wcfm_is_vendor($user_id)) return $htm;
	//$htm = '<a class="tzPrintBtn acf-button button button-primary button-large" href="#" onclick="window.print()">Печать</a>';
	$acfs = acf_get_fields(127175);
	//var_dump($acfs); die();
	$htm .= '<div class="tz-info-flex">';
	$htm .= '<div class="print-content tz-info">';
	$htm .= '<div class="tz-info__section">';
	$htm .= '<h3 class="tz-info__title">Рейтинг поставщика</h3>';

	foreach ($acfs as $field) {
		$text = "<div class='tz-info__point-title'>$field[label]</div>";
		$ank = get_field($field['name'], "user_$user_id");
		if (!is_array($ank)) $ank = [$ank];
		$n = 0;
		$text .= '<ul style="margin-top: 5px">';
		foreach ($ank as $item) {
			if (is_object($item))
				$item = $item->name;
			else if (is_int($item) or $item and ((string)(int) $item) === $item)
				$item = get_term_by('term_taxonomy_id', $item)->name;
			if ($item) {
				$text .= "<li>$item</li>";
				$n++;
			}
		}
		$text .= '</ul>';
		if ($n) $htm .= $text;
	}
	$htm .= '</div></div></div>';
	return $htm;
}


// вывести техзадания на пошив текущего пользователя
add_shortcode('list_tz', 'list_func');

function list_func($atts)
{
	$current_user = wp_get_current_user();

	//echo '<h4> Записи пользователя '. $current_user->user_login .'</h4>';
	$out = '<table><tr><th>Номер тз</th><th>Дата создания</th><th>Статус</th><th>Действия</th></tr>';


	$user_ID = get_current_user_id();
	global $post;
	$posts = get_posts(array(
		'numberposts' => '10', //количество записей
		'author' => $user_ID,
		'orderby'     => 'date', // Сортировка по дате
		'order'       => 'DESC', // Сортировка по названию
		'meta_key'    => '',
		'meta_value'  => '',
		'post_type'   => '_', //Тип поста
	));



	foreach ($posts as $post) {
		setup_postdata($post);
		$post_status = get_post_status($post->ID);

		switch ($post_status) {
			case 'publish':
				$post_status = "идет сбор предложений от фабрик...";
				break;
			case 'pending':
				$post_status = "ожидает проверки";
				break;
			case 'draft':
				$post_status = "черновик";
				break;
			case 'auto-draft':
				$post_status = "автоматически созданный чероновик";
				break;
			case 'future':
				$post_status = "пост запланирован на публикацию";
				break;
			case 'private':
				$post_status = "невидим для незарегистрированных пользователей";
				break;
			case 'inherit':
				$post_status = "статус вложений и редакций постов";
				break;
			case 'trash':
				$post_status = "в корзине";
				break;
		}


		$out .= '
    <tr>

		<td class="number"><strong>' . esc_html(get_the_title()) . '</strong></td>

		<td class="date"><strong>' . esc_html(get_the_date()) . '</strong> </td>

		<td class="post-status"><strong>' . $post_status . '</strong></td>

		<td class="post-action"><strong><a href="' . esc_attr(get_permalink()) . '">ПРОСМОТР</a></strong></td>

  	</tr>';
	}

	$out .= '</table>';

	wp_reset_postdata(); // сброс
	return $out;
}



// вывести ассортимент текущего пользователя
add_shortcode('lp_products_current_user', 'lp_products_current_user');

function lp_products_current_user($atts)
{

	$current_user = wp_get_current_user();

	//echo '<h4> Записи пользователя '. $current_user->user_login .'</h4>';
	//echo '<table><tr><th>Изображение</th><th>Название</th><th>Стоимость</th><th>Статус</th><th>Действия</th></tr>';
	//<th>Действия</th></tr>';


	$user_ID = get_current_user_id();
	$user_ID = get_query_var('user') ? intval(get_query_var('user')) : false;
	if (!$user_ID) {
		$user_ID = !empty($_GET['user']) && is_numeric($_GET['user']) ? intval($_GET['user']) : get_current_user_id();	
	}
	global $post;
	$posts = get_posts(array(
		'numberposts' => '10', //количество записей
		'author' => $user_ID,
		'orderby'     => 'date', // Сортировка по дате
		'order'       => 'DESC', // Сортировка по названию
		'meta_key'    => '',
		'meta_value'  => '',
		'post_type'   => 'product', //Тип поста
	));
	
	if (count($posts)) {
		$out='<table><tr><th>Изображение</th><th>Название</th><th>Стоимость</th><th>Статус</th><th>Действия</th></tr>';
	} else {	
		$out='Поставщик пока не добавил товары';
		return $out;
	}


	foreach ($posts as $post) {
		setup_postdata($post);
		$post_status = get_post_status($post->ID);
		$price = get_post_meta(get_the_ID(), '_regular_price', true);

		switch ($post_status) {
			case 'publish':
				$post_status = "Опубликовано";
				break;
			case 'pending':
				$post_status = "-";
				break;
			case 'draft':
				$post_status = "-";
				break;
			case 'auto-draft':
				$post_status = "-";
				break;
			case 'future':
				$post_status = "-";
				break;
			case 'private':
				$post_status = "-";
				break;
			case 'inherit':
				$post_status = "-";
				break;
			case 'trash':
				$post_status = "-";
				break;
		}

		$out .= '
    <tr>

		<td style="width: 10%;"><strong"> ' . woocommerce_get_product_thumbnail() . ' </strong></td>

		<td style="width: 40%;"><strong><a href="' . esc_attr(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></strong> </td>

		<td class="post-status"><strong>' . $price . '</strong></td>

		<td class="post-status"><strong>' . $post_status . '</strong></td>

		<td class="post-status"><strong><a class="edit_product" href="?edit_product=' . $post->ID . '">Редактировать</a></strong></td>

  	</tr>';
	}

	$out .= '
	<script>
	if (window.location.href.indexOf("edit_product") > -1 && window.location.href.indexOf("none") === -1) {
  setTimeout(function(){
    var edit_product = document.getElementById("edit_product");
    if (edit_product !== null && edit_product !== "") {
      var form = document.getElementById("product_edit_form");
      if (form !== null) {
        form.style.display = "block";
        window.location.href = "#edit_product";
      }
    }
  }, 2000);
}
	</script>
	</table>
	';

	wp_reset_postdata(); // сброс
	return $out;
}





add_action('select_vendors', 'select_vendors_callback');
function select_vendors_callback($btns)
{
	$send_tz_btn = '<button style="margin-top:10px; margin-bottom:10px;" id="sendVendorsEmails" class="acf-button button button-primary button-large" onclick="get_checboxes()">Отправить ТЗ поставщикам</button>';

	require get_stylesheet_directory() . '/getvendors.php';
	global $post;
	if (current_user_can('administrator')) {
		$count_memberships = 'full';
	} else if (wc_memberships_is_user_active_member(get_current_user_id()) == true /*&& !wcfm_is_vendor($current_user->ID)*/) {
		$count_memberships = count(wc_memberships_get_user_active_memberships(get_current_user_id()));
	} else {
		$count_memberships = 'str';
	}

	$get_vendors = new GetVendors($post->ID, $count_memberships, $btns);
	if ($get_vendors->have_posts()) {
		$get_vendors->result();
	}
}


function tz_section($acf_id, $section_label, $post_id)
{
	$htm .= '<div class="tz-info-flex">';
	$htm .= '<div class="print-content tz-info">';
	$htm .= '<div class="tz-info__section">';
	$htm .= '<h3 class="tz-info__title">' . $section_label . '</h3>';

	$acfs = acf_get_fields($acf_id);
	//echo '<pre>';	var_dump($acfs);
	foreach ($acfs as $field) {
		$text = "<div class='tz-info__point-title'>$field[label]</div>";
		$ank = get_field($field['name'], $post_id);
		if (!is_array($ank)) $ank = [$ank];
		$n = 0;
		$text .= '<ul style="margin-top: 5px">';
		foreach ($ank as $item) {
			if (is_object($item))
				$item = $item->name;
			else if (is_int($item) or $item and ((string)(int) $item) === $item)
				$item = get_term_by('term_taxonomy_id', $item)->name;
			if ($item) {
				$text .= "<li>$item</li>";
				$n++;
			}
		}
		$text .= '</ul>';
		if ($n) $htm .= $text;
	}
	$htm .= '</div></div></div>';
	return $htm;
}

function get_tz_data()
{

	$id = get_the_ID();
	//return tz_section(209976, 'Основная информация', $id);

	$acfs = acf_get_fields(94185);
	$acf_fields = [];
	$acf_label = 'Основная информация';
	$admin_list = ['fio_kontaktnogo_licza_po_zayavke', 'email_kontaktnogo_licza', 'telefon_kontaktnogo_licza', 'telefon_kontaktnogo_licza'];
	$gallery_array = [];
	foreach ($acfs as $acfs_fields) {
		if ($acfs_fields['type'] !== 'tab') {
			if ($acfs_fields['name'] !== "" && get_field($acfs_fields['name'])) {
				if ($acfs_fields['type'] !== 'gallery' && $acfs_fields['type'] !== 'image') {
					$acf_fields[$acf_label][]		= array(
						'name'	=> $acfs_fields['name'],
						'label'	=> $acfs_fields['label'],
						'type'	=> $acfs_fields['type'],
						'key'	=> $acfs_fields['key'], ///////////***********
						'value'	=> get_field($acfs_fields['name']),
					);
				} else {
					$gallery_array[] = array(
						'name'	=> $acfs_fields['name'],
						'label'	=> $acfs_fields['label'],
						'type'	=> $acfs_fields['type'],
						'value'	=> get_field($acfs_fields['name']),
					);
				}
			}
		} else {
			$acf_label = $acfs_fields['label'];
			// $acf_fields[$acf_label][]		= array(
			// 	'name'	=> $acfs_fields['name'],
			// 	'label'	=> $acfs_fields['label'],
			// 	'type'	=> $acfs_fields['type'],
			// 	'value'	=> get_field($acfs_fields['name']),
			// );
		}
	}

	// if (current_user_can('manage_options')) :
	// 	echo '<pre>';
	// 	echo print_r(get_fields($id), 1);
	// 	echo '</pre>';
	// endif;

	$html_result = '
		<div class="tz-header">
			<div class="tz-header__left">
				<div class="tz-header__author">Заказчик: ' . get_the_author() . '</div>
				<div class="tz-header__date">Дата создания: ' . get_the_date() . '</div>
				<div class="tz-header-label">Техническое задание<span class="tz-header-label__small">на разработку и пошив швейного изделия</span></div>
			</div>
			<div class="tz-header__right">
				<img class="print-logo" width="211" height="113" src="https://tpktrade.ru/wp-content/uploads/2021/11/legpromrf-copy.jpg" class="attachment-medium size-medium" alt="legpromrf" srcset="https://tpktrade.ru/wp-content/uploads/2021/11/legpromrf-copy.jpg 211w, https://tpktrade.ru/wp-content/uploads/2021/11/legpromrf-copy-64x34.jpg 64w" sizes="(max-width: 211px) 100vw, 211px">
				<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&choe=UTF-8&chld=H&chl=' . get_the_permalink() . '">
			</div>
		</div>';

	$html_result .= '<div class="tz-info-flex">';

	$html_result .= '<div class="print-content tz-info">';

	$admin_result = '';
	foreach ($acf_fields as $name => $fields) {
		$html_result .= '<div class="tz-info__section">';
		$html_result .= '<h3 class="tz-info__title">' . $name . '</h3>';
		foreach ($fields as $field) {
			if ($field['key'] != 'field_620e3a20fb1c6') { //исключаем поле показывать контакты да-нет
				// инициализация массива для одиночных значений
				if ($field['type'] == 'taxonomy' and !is_array($field['value']))
					$field['value'] = [$field['value']];


				$inner_html_result .= '<div class="this tz-info__point-title">' . $field['label'] . '</div>';
				$inner_html_result .= '<ul>';
				if (is_string($field['value'])) {
					// Strings
					$inner_html_result .= "<li>" . $field['value'] . "</li>";
				} else if ($field['type'] == 'file') {

					$inner_html_result .= '<li><a href=' . esc_attr($field['value']['url']) . '><svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="19.79" height="20">
  <g fill="none" stroke-miterlimit="10" stroke-width="0" transform="translate(2.277) scale(.22222)">
    <path fill="#000" d="M77.474 17.28 61.526 1.332A4.516 4.516 0 0 0 58.311 0H15.742a4.553 4.553 0 0 0-4.548 4.548v80.904A4.553 4.553 0 0 0 15.742 90h58.516a4.554 4.554 0 0 0 4.549-4.548V20.496a4.517 4.517 0 0 0-1.333-3.216zM61.073 5.121l12.611 12.612H62.35a1.278 1.278 0 0 1-1.276-1.277V5.121ZM74.258 87H15.742a1.55 1.55 0 0 1-1.548-1.548V4.548A1.55 1.55 0 0 1 15.742 3h42.332v13.456a4.281 4.281 0 0 0 4.276 4.277h13.457v64.719A1.55 1.55 0 0 1 74.258 87z"/>
    <path fill="#000" d="M68.193 33.319H41.808a1.5 1.5 0 1 1 0-3h26.385a1.5 1.5 0 1 1 0 3zM34.456 33.319H21.807a1.5 1.5 0 1 1 0-3h12.649a1.5 1.5 0 1 1 0 3z"/>
    <linearGradient id="a" x1="21.806" x2="42.298" y1="19.233" y2="19.233" gradientUnits="userSpaceOnUse">
      <stop offset="0%" stop-color="#fff"/>
      <stop offset="100%"/>
    </linearGradient>
    <path fill="url(#a)" d="M-10.246 0h20.492"/>
    <path fill="#000" d="M42.298 20.733H21.807a1.5 1.5 0 1 1 0-3h20.492a1.5 1.5 0 1 1-.001 3zM68.193 44.319H21.807a1.5 1.5 0 1 1 0-3h46.387a1.5 1.5 0 1 1-.001 3zM48.191 55.319H21.807a1.5 1.5 0 1 1 0-3h26.385a1.5 1.5 0 0 1-.001 3zM68.193 55.319H55.544a1.5 1.5 0 0 1 0-3h12.649a1.5 1.5 0 0 1 0 3zM68.193 66.319H21.807a1.5 1.5 0 1 1 0-3h46.387a1.5 1.5 0 0 1-.001 3zM68.193 77.319H55.544a1.5 1.5 0 0 1 0-3h12.649a1.5 1.5 0 0 1 0 3z"/>
  </g>
</svg> ' . esc_attr($field['value']['filename']) . '</a></li>';
				} else if (is_array($field['value']) && is_numeric($field['value'][0])) {
					// ID of WP_Term
					$array_term = count($field['value']);
					if ($array_term == 1) {
						$term = get_term($field['value'][0]);
						$inner_html_result .= "<li>" . $term->name . "</li>";
					} else {
						foreach ($field['value'] as $term_id) {
							$term = get_term($term_id);
							$inner_html_result .= "<li>" . $term->name . "</li>";
						}
					}
				} else if (is_array($field['value']) && is_object($field['value'][0])) {
					$array_term = count($field['value']);
					if ($array_term == 1) {
						$inner_html_result .= "<li>" . $field['value'][0]->name . "</li>";
					} else {
						foreach ($field['value'] as $term_object) {
							$inner_html_result .= "<li>" . $term_object->name . "</li>";
						}
					}
				} else {
					if (is_array($field['value']) && count($field['value']) == 1) {
						if (is_object($field['value'][0])) {
							$inner_html_result .= "<li>" . $field['value'][0]->name . "</li>";
						} else {
							$inner_html_result .= "<li>" . $field['value'][0] . "</li>";
						}
					} else if (is_array($field['value'])) {

						foreach ($field['value'] as $field_key => $text) {

							if (is_object($field['value'][$field_key])) {
								$inner_html_result .= "<li>" . $field['value'][$field_key]->name . "</li>";
							} else {
								$inner_html_result .= "<li>" . $field['value'][$field_key] . "</li>";
							}
						}
					}
				}
				$inner_html_result .= '</ul>';



				if (!in_array($field['name'], $admin_list)) {
					$html_result .= $inner_html_result;
				} else {
					$admin_result .= $inner_html_result;
				}
				$inner_html_result = "";
			}
		}
		$html_result .= "</div>";
		$html_result .= '<div class="modal__get-member modal__vendor hidden" id="model__get-member"><h4>Чтобы получить доступ к информации о компании и контактам - перейдите на PRO</h4><a class="member__pro-btn" href="https://tpktrade.ru/lichnyj-kabinet/tarif-pro-dostup/" target="blank">Посмотреть тарифы</a><button id="btn__close-modal_member" onClick={document.getElementById("model__get-member").classList.add("hidden")}>Закрыть</button></div>';	
	}

	if (is_array($gallery_array) && count($gallery_array) > 0) {
		$html_result .= '<div class="tz-info__section">';
		$html_result .= '<h3 class="tz-info__title">Галлерея</h3>';

		foreach ($gallery_array as $gallery_item) {
			if (isset($gallery_item['value'][0]) && is_array($gallery_item['value'][0])) {
				foreach ($gallery_item['value'] as $gal_item) {
					$url = $gal_item['url'];
					if (str_contains($url, 'doc')) {
						$html_result .= '<a href="' . $url . '" target="blank"><img src="https://tpktrade.ru/wp-content/uploads/2023/06/docx.png" width="50px" alt="Прикрепленный документ" /></a>';
					} else {
						$html_result .= '<a href="' . $url . '" data-lightbox="gallery"><img src="' . $url . '" alt="' . $gal_item['title'] . '"></a>';
					}
				}
			} else {
				$url = $gallery_item['value']['url'];
				$html_result .= '<img src="' . $url . '" alt="' . $gallery_item['value']['title'] . '">';
			}
		}
		$html_result .= '</div>';
	}

	global $post;
	$current_user = wp_get_current_user();
	if (($post->post_author == $current_user->ID) || current_user_can('administrator') || (wcfm_is_vendor($current_user->ID) && get_field('pokazat_moi_kontakty_postavshhikam')[0] == 'yes')) {
		$html_result .= '<div class="tz-info__section">';
		$html_result .= '<h3 class="tz-info__title">Контактная информация</h3>';
		if (wc_memberships_is_user_active_member($current_user->ID) || current_user_can('administrator')) {
			$html_result .= $admin_result;
		} else {
			$html_result .= '<a href="https://tpktrade.ru/lichnyj-kabinet/tarif-pro-dostup/" class="elementor-button-link elementor-button elementor-size-sm orange-btn" role="button"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Купить PRO</span></span></a>';
		}
		$html_result .= '</div>';
	}
	/*else{

		$html_result .= '<div class="tz-info__section">';

		$html_result .= '<h3 class="tz-info__title">Контактная информация</h3>';

		$html_result .= '<a class="buyTzContacts acf-button button button-primary button-large" href="https://tpktrade.ru/pakety-uslug/">Купить контакты</a>';

		$html_result .= '</div>';
	}*/

	$html_result .= '</div>';

	$get_feat_img = get_field('eskizy_izdelij')[0];


	$set_feat_img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];

	//$set_feat_img = $get_feat_img['url'];
	if ($set_feat_img) {
		$html_result .= '<div class="tz-feat-img main-print-img">';
		$html_result .= '<a href="' . $set_feat_img . '" data-lightbox="image-1" data-title="Основное фото"><img src ="' . $set_feat_img . '"></a>';
		$html_result .= '</div>';
	}
	$html_result .= '</div>';
	return $html_result;
}


add_shortcode('new_tz_shortcode', 'new_tz_shortcode_callback');

// формы на странице /razmestit-novoe-tz
function new_tz_shortcode_callback($atts)
{
	$current_user = wp_get_current_user();

	$current_user_roles = $current_user->roles;
	$is_admin = $is_author = 0;
	foreach ($current_user_roles as $current_user_role) {

		if ($current_user_role == 'administrator') {

			$is_admin = 1;
		}
		if ($current_user_role == 'author')
			$is_author = 1;
	}
	$result_html = '';
	$user_id = get_current_user_id();

	//    if($is_admin || wc_memberships_is_user_active_member( $user_id, 'tarif-demo-razmeshhenie' ) || wc_memberships_is_user_active_member( $user_id, 'luxe-razmeshhenie-tz' ) || wc_memberships_is_user_active_member( $user_id, 'tarif-pro-razmeshhenie' ))
	if ($user_id) {
		if (wc_memberships_is_user_active_member($user_id)) {

			//~ $result_html = "jQuery('.elementor-element-88f198a, .elementor-element-71356d7').remove()";
			$result_html .= '<style>
    .acfce_form--new-tz .elementor-widget-wrap{
	padding: 0px !important;
}
    .acfce_form--new-tz{
    //~ width:50% !important;
    //~ background: white;
    margin-top: 10px;
}
	.acfce_form--new-tz div.form_tz_sport-view--div{
	background: white;
	width: 100%;
	padding:10px;
}
	.go-to-pro{
	display:none;
}
    </style>
    <div class="form_tz_sport-view--div">';
			$result_html .= do_shortcode('[acfe_form name="techzadanie_new"]');
			//	$result_html .= do_shortcode('[acfe_form name="lid_with_tz_new_post"]');
			$result_html .= '</div>';
		} else {
			// создать новый пост с коротким ТЗ
			$result_html .= '<style>
    .acfce_form--new-tz .elementor-widget-wrap{
	padding: 0px !important;
}
    .acfce_form--new-tz{
    //~ width:50% !important;
    //~ background: white;
    margin-top: 10px;
}
.acfce_form--new-tz div.lid_with_tz-div{
	background: white;
	width: 100%;
	padding: 10px;
}
    </style>
    <div class="lid_with_tz-div">';
			//	$result_html .= do_shortcode('[acfe_form name="lid_with_tz_new_post"]'); //короткое ТЗ для пользователя без членства
			$result_html .= do_shortcode('[acfe_form name="techzadanie_new"]');  // Всем пользователям, в т.ч. без членства показывать расширенную форму ТЗ
			//~ $result_html = "jQuery('.elementor-element-0693d3e').remove();";
			$result_html .= '</div>';
		}
	} else {
		// незарегистрированный пользователь
		$result_html .= 'Для входа в личный кабинет <a href="https://tpktrade.ru/vhod-registracziya/">войдите</a> или <a href="https://tpktrade.ru/vhod-registracziya/">зарегистрируйтесь</a>';
	}


	return $result_html;
}

add_shortcode('show_tz_shortcode', 'show_tz_shortcode_callback');

// формы на странице "Редактировать заказ"
function show_tz_shortcode_callback($atts)
{

	global $post;
	$current_user = wp_get_current_user();

	$goToPro = false;
	if (wcfm_is_vendor($current_user->ID)) {
		$count_memberships = 'vendor';
	} else if (wc_memberships_is_user_active_member(get_current_user_id()) == true && !wcfm_is_vendor($current_user->ID) || current_user_can('administrator') || ($post->post_author == $current_user->ID)) {
		$count_memberships = count(wc_memberships_get_user_active_memberships(get_current_user_id()));
	} else {
		$count_memberships = 'nomember';
		$goToPro = true;
	}

	$current_user_roles = $current_user->roles;

	$is_customer = 0;

	foreach ($current_user_roles as $current_user_role) {

		if ($current_user_role == 'customer') {

			$is_customer = 1;
		}
	}

	#var_dump($current_user_roles);

	$view_mode_btn = '<a class="acf-button button button-primary button-large" href="' . get_the_permalink() . '">Просмотреть заявку</a>';

	//if ($count_memberships == 'nomember' && !current_user_can('administrator')) {
	//	$print_btn = '<a target="_blank" class="acf-button button button-primary button-large" href="https://tpktrade.ru/tarif-pro-dostup"">Печать PRO</a>';
	//}
	//else {
	//	add_action( 'wp_footer', 'tpk_print_button_script' );
	//	$print_btn = '<a id="print-button" class="tzPrintBtn acf-button button button-primary button-large" href="javascript:void(0);"><span>...</span></a>';
	//}

	if (!wcfm_is_vendor($current_user->ID) or $post->post_author == $current_user->ID) {
		$select_vendor = '<a style="margin-left:10px;" class="selectVendorBtn acf-button button button-primary button-large" href="' . get_the_permalink() . '/?action=select-vendor">Список подходящих поставщиков</a>';
		$edit_btn = '<a style="margin-right: 10px;" class="tzEditBtn acf-button button button-primary button-large" href="' . get_the_permalink() . '/?action=edit">Редактировать заявку</a>';
	} else {
		$select_vendor = '';
		$edit_btn = '';
	}

	if ($count_memberships == 'nomember' && $is_customer == 1) {

		$all_perspectives = '<a target="_blank" class="acf-button button gopro button-primary button-large" style="background: orange;margin-left: auto;" href="https://tpktrade.ru/lichnyj-kabinet/kak-eto-rabotaet/">Все возможности PRO</a>';
	} else {

		$all_perspectives = '';
	}

	$order_on_edit_title = '';
	//<h3 class="elementor-heading-title elementor-size-default">Ваше техзадание всегда доступно в <i><a href="https://tpktrade.ru/razmeshhennye-zayavki/"> личном кабинете </a></i></h3>';

	$result_html = '
	<style>
		@media print {
			header, footer, #cxecrt-save-share-cart-modal, .elementor-element-8f4c012, .elementor-element-d19e9d2, .tzPrintBtn, #print-button, .tzEditBtn, .selectVendorBtn{
				display: none !important;
			}
			.tz-header {
    			display: flex;
				width: 280mm;
			}

		}';
	if (!$goToPro) {
		$result_html .= '.go-to-pro{
		display:none;
		}';
	}
	if ($is_customer) {
		$result_html .= '.n-print{
		display:none;
		}';
	}
	$result_html .= '</style>';
	//$result_html .= '<script>jQuery(function(jQuery){jQuery(".tzPrintBtn").click(function(){window.print();return false;});});</script>';

	if ($_GET['action'] == 'select-vendor' && !wcfm_is_vendor($current_user->ID)) {
		$result_html .= '<div class="tz-buttons">';
		/*$print_btn = '<a class="acf-button button button-primary button-large" href="#" onclick="window.print()"><span>Печать</span></a>';*/
		$result_html .= do_action('select_vendors', array($all_perspectives, $print_btn));
		$result_html .= '</div>';
		return $result_html;
	}

	if ($_GET['action'] == 'edit' && current_user_can('administrator') || ($_GET['action'] == 'edit' && ($post->post_author == $current_user->ID) && wc_memberships_is_user_active_member(get_current_user_id()))) {
		$result_html .= $order_on_edit_title;
		//~ $result_html .= '
		//~ <script>
		//~ jQuery(".elementor-element-b8fb6cf").remove();
		//~ </script>
		//~ ';

		/*$result_html .= '<div class="tz-buttons">';
		$result_html .= $view_mode_btn;
		$result_html .= $select_vendor;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';*/

		$result_html .= do_shortcode('[acfe_form name="form_tz_sport-view"]');

		/*$result_html .= '<div class="tz-buttons">';
		$result_html .= $view_mode_btn;
		$result_html .= $select_vendor;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';*/

		/*$ked_args = array(
			'post_type'	=> "_",
			'post_status' => 'publish',
		);
		$ked_query = new WP_Query($ked_args);
		if($ked_query->have_posts()){
			while($ked_query->have_posts()){
				$ked_query->the_post();
				ked_update_excerpt($post->ID);
			}
		}else{
			echo "no";
		}*/
		return $result_html;
	} else if ($_GET['action'] == 'edit' && current_user_can('administrator') || ($_GET['action'] == 'edit' && ($post->post_author == $current_user->ID) && !wc_memberships_get_user_active_memberships(get_current_user_id()))) {
		$result_html .= $order_on_edit_title;

		/*$result_html .= '<div class="tz-buttons">';
		$result_html .= $view_mode_btn;
		$result_html .= $select_vendor;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';*/

		//$result_html .= do_shortcode('[acfe_form name="edit_lid_tz"]'); //краткая форма для пользователя без членства
		$result_html .= do_shortcode('[acfe_form name="form_tz_sport-view"]'); //показывать полную форму ТЗ пользователям без членства

		/*$result_html .= '<div class="tz-buttons">';
		$result_html .= $view_mode_btn;
		$result_html .= $select_vendor;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';*/
		return $result_html;
	} else if (($_GET['action'] != 'edit' && ($post->post_author == $current_user->ID))) {
		$result_html .= '<div class="tz-buttons">';
		$result_html .= $edit_btn . $print_btn;
		$result_html .= $select_vendor;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';
	} /*else if (($_GET['action'] != 'edit' && ($post->post_author != $current_user->ID)) && !current_user_can('administrator')) {
		$result_html .= '<div class="tz-buttons">';
		$result_html .= $print_btn;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';
	} else if ($_GET['action'] == 'edit' && (!current_user_can('edit_others_posts', $post->ID) || !($post->post_author == $current_user->ID))) {
		$result_html .= '<div class="tz-buttons">';
		$result_html .= $print_btn;
		$result_html .= $all_perspectives;
		$result_html .= '</div>';
	}*/

	if (current_user_can('administrator') || current_user_can('edit_others_posts', $post->ID) && ($post->post_author == $current_user->ID)) {

		/*if (!($_GET['action'] != 'edit' && ($post->post_author == $current_user->ID))) {
			$result_html .= '<div class="tz-buttons">';
			$result_html .= $edit_btn . $print_btn;
			$result_html .= $select_vendor;
			$result_html .= '</div>';
		}*/
		$result_html .= get_tz_data();
		//$result_html .= '<div class="tz-buttons">';
		// $result_html .= $edit_btn . $print_btn;
		//if(current_user_can('administrator')){
		//$result_html .= $select_vendor;
		//$result_html .= '</div>';
		//}
		return $result_html;
	} else {
		//~ $result_html .= '<div style="display: flex; flex-wrap: wrap;">';
		//~ $result_html .= $edit_btn . $print_btn;
		//if(current_user_can('administrator')){
		//~ $result_html .= $select_vendor;
		//}
		//~ $result_html .= $all_perspectives;
		//~ $result_html .= '</div>';

		$result_html .= get_tz_data();

		//$result_html .= '<div class="tz-buttons">';
		/*if (current_user_can('administrator') || current_user_can('edit_others_posts', $post->ID) || ($post->post_author == $current_user->ID))
			$result_html .= $edit_btn;
		$result_html .= $print_btn;*/
		//if(current_user_can('administrator')){
		//if (current_user_can('administrator') || current_user_can('edit_others_posts', $post->ID) || ($post->post_author == $current_user->ID))
			//$result_html .= $select_vendor;
		//}
		//$result_html .= $all_perspectives;
		//$result_html .= '</div>';

		return $result_html;
	}
}

add_action('personal_options_update', 'vendor_accreditation_save');
add_action('edit_user_profile_update', 'vendor_accreditation_save');
function vendor_accreditation_save($user_id)
{
	if (isset($_POST['vendor_accreditation'])) {
		update_user_meta($user_id, 'vendor_accreditation', $_POST['vendor_accreditation']);
	} else {
		update_user_meta($user_id, 'vendor_accreditation', 'off');
	}
}

add_action('show_user_profile', 'vendor_accreditation_profile_field');
add_action('edit_user_profile', 'vendor_accreditation_profile_field');
function vendor_accreditation_profile_field($user)
{
?>
	<h3>Поставщик аккредитован?</h3>
	<table class="form-table">
		<tr>
			<td>
				<input type="checkbox" name="vendor_accreditation" id="vendor_accreditation" class="checkbox" <?php
																												if (get_the_author_meta('vendor_accreditation', $user->ID) == 'on') {
																													echo "checked";
																												}
																												?> />
				<label for="vendor_accreditation">Да</label>
			</td>
		</tr>
	</table>
<?php
}

function get_post_meta_acf_data($field, $type, $title)
{

	if (!empty($field)) {
		if ($type == 'single-meta') {
			$html_result .= '<div class="tz-info__point-title">' . $title . '</div>';
			$html_result .= '<ul>';
			$html_result .= '<li>' . $field . '</li>';
			$html_result .= '</ul>';
		}
		if ($type == 'checkbox-meta') {
			$html_result .= '<div class="tz-info__point-title">' . $title . '</div>';
			$html_result .= '<ul>';
			foreach ($field as $value) {
				$html_result .= '<li>' . $value . '</li>';
			}
			$html_result .= '</ul>';
		}
		if ($type == 'tax-id') {
			$html_result .= '<div class="tz-info__point-title">' . $title . '</div>';
			$html_result .= '<ul>';
			foreach ($field as $term_id) {
				$html_result .= '<li>' . get_term($term_id)->name . '</li>';
			}
			$html_result .= '</ul>';
		}
		return $html_result;
	}
}

add_action('get_vendor_info', 'get_vendor_info_callback');
function get_vendor_info_callback($vendor_id)
{

	$osnovnoj_vid_deyatelnosti = get_field('osnovnoj_vid_deyatelnosti', 'user_' . $vendor_id);
	$ponaznacheniyuisferamdeyatelnosti = get_field('Ponaznacheniyuisferamdeyatelnosti', 'user_' . $vendor_id);
	$vidy_izdelij = get_field('vidy_izdelij', 'user_' . $vendor_id);
	$region = get_field('region', 'user_' . $vendor_id);

	$forma_raboty = get_field('forma_raboty', 'user_' . $vendor_id);
	$nalichii_v_reestre_minpromtorga = get_field('nalichii_v_reestre_minpromtorga', 'user_' . $vendor_id);
	$polnoe_nazvanie_kompanii = get_field('polnoe_nazvanie_kompanii', 'user_' . $vendor_id);
	$kratkoe_naimenovanie = get_field('kratkoe_naimenovanie', 'user_' . $vendor_id);
	$inn = get_field('inn', 'user_' . $vendor_id);
	$adres_registraczii = get_field('adres_registraczii', 'user_' . $vendor_id);
	$regionproizvodstva = get_field('Regionproizvodstva', 'user_' . $vendor_id);
	$fakticheskiiaddress = get_field('faktiches', 'user_' . $vendor_id);
	$sajt = get_field('sajt', 'user_' . $vendor_id);
	$email = get_field('email', 'user_' . $vendor_id);
	$telefon = get_field('telefon', 'user_' . $vendor_id);
	$imya_otchestvo_kontaktnogo_licza = get_field('imya_otchestvo_kontaktnogo_licza', 'user_' . $vendor_id);
	$familiya_kontaktnogo_licza = get_field('familiya_kontaktnogo_licza', 'user_' . $vendor_id);
	$dopolnitelnaya_informacziya = get_field('dopolnitelnaya_informacziya', 'user_' . $vendor_id);

	$planirovanie_proizvodstva = get_field('planirovanie_proizvodstva', 'user_' . $vendor_id);
	$format_raboty = get_field('format_raboty', 'user_' . $vendor_id);
	$otsrochka_platezha = get_field('otsrochka_platezha', 'user_' . $vendor_id);
	$czenovye_segmenty = get_field('czenovye_segmenty', 'user_' . $vendor_id);
	$minimalnaya_partiya_rub = get_field('minimalnaya_partiya_rub', 'user_' . $vendor_id);
	$minimalnaya_partiya_sht = get_field('minimalnaya_partiya_sht', 'user_' . $vendor_id);
	$vozmozhnost_besplatnogo_predostavleniya_obrazczov = get_field('vozmozhnost_besplatnogo_predostavleniya_obrazczov', 'user_' . $vendor_id);




	$html_result .= '<div class="print-content tz-info" style="padding-top: 0px;">';

	$html_result .= '<div class="tz-info__section">';

	$html_result .= '<h3 class="tz-info__title">Входные данные</h3>';

	$html_result .= get_post_meta_acf_data($osnovnoj_vid_deyatelnosti, 'checkbox-meta', 'Основной вид деятельности:');
	$html_result .= get_post_meta_acf_data($ponaznacheniyuisferamdeyatelnosti, 'tax-id', 'По назначению и сферам деятельности:');
	$html_result .= get_post_meta_acf_data($vidy_izdelij, 'tax-id', 'Виды изделий:');
	$html_result .= get_post_meta_acf_data($region, 'tax-id', 'Регион:');

	$html_result .= '</div>';

	$html_result .= '<div class="tz-info__section">';

	$html_result .= '<h3 class="tz-info__title">Основная информация</h3>';

	$html_result .= get_post_meta_acf_data($forma_raboty, 'single-meta', 'Форма работы:');
	$html_result .= get_post_meta_acf_data($nalichii_v_reestre_minpromtorga, 'single-meta', 'Наличие в реестре Минпромторга:');
	$html_result .= get_post_meta_acf_data($polnoe_nazvanie_kompanii, 'single-meta', 'Полное название компании:');
	$html_result .= get_post_meta_acf_data($kratkoe_naimenovanie, 'single-meta', 'Краткое наименование:');
	$html_result .= get_post_meta_acf_data($inn, 'single-meta', 'ИНН:');
	$html_result .= get_post_meta_acf_data($adres_registraczii, 'single-meta', 'Юридический адрес:');
	$html_result .= get_post_meta_acf_data($regionproizvodstva, 'tax-id', 'Регион производства:');
	$html_result .= get_post_meta_acf_data($fakticheskiiaddress, 'single-meta', 'Фактический (Почтовый) адрес:');
	$html_result .= get_post_meta_acf_data($sajt, 'single-meta', 'Сайт:');
	$html_result .= get_post_meta_acf_data($email, 'single-meta', 'Email:');
	$html_result .= get_post_meta_acf_data($telefon, 'single-meta', 'Телефон:');
	$html_result .= get_post_meta_acf_data($imya_otchestvo_kontaktnogo_licza, 'single-meta', 'Имя Отчество контактного лица:');
	$html_result .= get_post_meta_acf_data($familiya_kontaktnogo_licza, 'single-meta', 'Фамилия контактного лица:');
	$html_result .= get_post_meta_acf_data($dopolnitelnaya_informacziya, 'single-meta', 'Дополнительная информация:');

	$html_result .= '</div>';

	$html_result .= '<div class="tz-info__section">';

	$html_result .= '<h3 class="tz-info__title">Условия работы</h3>';

	$html_result .= get_post_meta_acf_data($planirovanie_proizvodstva, 'single-meta', 'Загруженность производства:');
	$html_result .= get_post_meta_acf_data($format_raboty, 'checkbox-meta', 'Формат работы:');
	$html_result .= get_post_meta_acf_data($otsrochka_platezha, 'tax-id', 'Условия оплаты:');
	$html_result .= get_post_meta_acf_data($czenovye_segmenty, 'tax-id', 'Ценовые сегменты:');
	$html_result .= get_post_meta_acf_data($minimalnaya_partiya_rub, 'single-meta', 'Минимальная партия, руб:');
	$html_result .= get_post_meta_acf_data($minimalnaya_partiya_sht, 'single-meta', 'Минимальная партия, шт:');
	$html_result .= get_post_meta_acf_data($vozmozhnost_besplatnogo_predostavleniya_obrazczov, 'single-meta', 'Возможность бесплатного предоставления образцов:');

	$html_result .= '</div>';

	$html_result .= '</div></div>';

	echo $html_result;
}

/*
 *
 * Обновлении описания при простом создании ТЗ
 *
 * */
add_filter('acf/save_post', 'ked_update_excerpt');
function ked_update_excerpt($post_ID)
{
	/* убираем автозаполнение Цитаты для Технического задания
	$post_type = get_post_type($post_id);
	if ($post_type == "_") {
		$fields = array(
			'field1' => 'field_61de9d8ee8021',
			'field2' => 'field_61de9dc7e8022',
			'field3' => 'field_611bc68d51131',
			'field4' => 'field_60eaa5be6143b',
			'field5' => 'field_60eaa50561439',
		);
		//$message = "";
		foreach ($fields as $field) {
			$object = get_field_object($field);
			$label = $object['label'];
			$value = $object['value'];
			$val = "";

			if (is_array($value) || (is_string($value) && $value !== "") || is_object($value)) {
				if (is_array($value) && !empty($value)) {
					for ($i = 0; $i < count($value); $i++) {
						$term = get_term($value[$i]);
						if ($i !== count($value) - 1) {
							$val .= $term->name . ", ";
						} else {
							$val .= $term->name;
						}
					}
				} else if (is_object($value)) {
					$val .= $value->name;
					//$message .= "$key :" . $field->name . "<br>";
				} else if (!is_object($value) && !is_array($value)) {
					$val .= $value;
					//$message .= "$key :" . $field . "<br>";
				}
				$excerpt .= "$label: $val. ";
			}
		}
		if ($excerpt !== "") {
			$the_post = array(
				'ID'           => $post_ID, //the ID of the Post
				'post_excerpt' => $excerpt,
			);
			wp_update_post($the_post);
		}
	}*/
}
add_filter('acfe/form/submit/post/form=techzadanie_new', 'ked_front_submit', 10, 5);
function ked_front_submit($post_id, $type, $args, $form, $action)
{/* убираем автозаполнение Цитаты для Технического задания
	$fields = array(
		'field1' => 'field_61de9d8ee8021',
		'field2' => 'field_61de9dc7e8022',
		'field3' => 'field_611bc68d51131',
		'field4' => 'field_60eaa5be6143b',
		'field5' => 'field_60eaa50561439',
	);
	foreach ($fields as $field) {
		$object = get_field_object($field);
		$label = $object['label'];
		$value = $object['value'];
		$val = "";

		if (is_array($value) || (is_string($value) && $value !== "") || is_object($value)) {
			if (is_array($value) && !empty($value)) {
				for ($i = 0; $i < count($value); $i++) {
					$term = get_term($value[$i]);
					if ($i !== count($value) - 1) {
						$val .= $term->name . ", ";
					} else {
						$val .= $term->name;
					}
				}
			} else if (is_object($value)) {
				$val .= $value->name;
				//$message .= "$key :" . $field->name . "<br>";
			} else if (!is_object($value) && !is_array($value)) {
				$val .= $value;
				//$message .= "$key :" . $field . "<br>";
			}
			$excerpt .= "$label: $val. ";
		}
	}
	if ($excerpt !== "") {
		$the_post = array(
			'ID'           => $post_id, //the ID of the Post
			'post_excerpt' => $excerpt,
		);
		wp_update_post($the_post);
	}*/
}


add_action('show_user_profile', 'verified__user_vendor_profile_fields');
add_action('edit_user_profile', 'verified__user_vendor_profile_fields');
function verified__user_vendor_profile_fields($user)
{ ?>
	<h3>Проверка продавца</h3>

	<table class="form-table">
		<tr>
			<th><label for="verified__user_vendor">Проверен ли продавец</label></th>
			<td>
				<input id="verified__user_vendor" name="verified__user_vendor" type="checkbox" value="1" <?php if (get_the_author_meta('verified__user_vendor', $user->ID) == 1) {
																												echo ' checked="checked"';
																											} ?> />
			</td>
		</tr>
	</table>
<?php }

add_action('personal_options_update', 'save_verified__user_vendor_profile_fields');
add_action('edit_user_profile_update', 'save_verified__user_vendor_profile_fields');

function save_verified__user_vendor_profile_fields($user_id)
{
	if (!current_user_can('edit_user', $user_id)) {
		return false;
	} else {
		if (isset($_POST['verified__user_vendor']) && $_POST['verified__user_vendor'] > 0) {
			update_usermeta($user_id, 'verified__user_vendor', $_POST['verified__user_vendor']);
		} else {
			delete_usermeta($user_id, 'verified__user_vendor');
		}
	}
}


/**
 * При добавлении блоку класса ".role-hidden" - блок отображается только для авторов без членства и админа
 * При добавлении блоку класса ".notauthor-hidden" - блок отображается только для авторов и админа
 * При добавлении блоку класса ".user-role-hidden" - блок отображается только для юзеров без членства и админа
 * При добавлении блоку класса ".member-role-hidden" - блок отображается только для юзеров с членством (любым) и админов
 */

add_action('wp_enqueue_scripts', 'role_hidden');

function role_hidden()
{


	if (!current_user_can('administrator')) {

		global $post;

		$user_id = get_current_user_id();
		$args = array('status' => array('active'));
		$plans = wc_memberships_get_user_memberships($user_id, $args);

		$style = '<style>';

		$css = '{display:none !important;visibility:hidden !important}';

		if (get_current_user_id() == $post->post_author) { //автор

			if (!empty($plans)) { //с членством

				$style .= '.role-hidden' . $css;
			}
		} else {
			$style .= '.notauthor-hidden' . $css;
			$style .= '.role-hidden' . $css;
		}


		if (!empty($plans)) { //пользователь с подпиской

			$style .= '.user-role-hidden' . $css;
		} else { //пользователь без подписки

			$style .= '.member-role-hidden' . $css;
		}


		$style .= '</style>';

		echo $style;
	}
}

add_shortcode('show_logo_in_cp', 'show_logo_in_cp');

// отображение логотипа "Цифровой паспорт"
function show_logo_in_cp() {
	$user_id = (int) $_GET['user'];
	$htm = '';
	if (strlen(get_field('ab_logo', 'user_' . $user_id))) {
		// Если у пользователя есть логотип
		$htm .= '<img src="' . get_field('ab_logo', 'user_' . $user_id) . '" alt="User-logo" />';
	} else {
		// Если логотипа нет, то выводить заглушку
		$htm .= '<img src="https://tpktrade.ru/wp-content/uploads/2022/10/net-logo-3097.jpg" alt="User-logo" />';
	}
	
	return $htm;
}

add_shortcode('send_vendors_tz', 'send_vendors_tz');

// отображение кнопки "Отправить заявку всем на оценку"
function send_vendors_tz() {
	$user_id = get_current_user_id();
	$post_id = get_the_ID();
	$htm = '<button id="sendVendorsTZEmails" class="acf-button button button-primary button-large send-vendors-tz-button" onclick="get_checboxes(' . $user_id . ',' . $post_id . ')">Отправить заявку всем на оценку</button>';
	return $htm;
}

add_shortcode('show_goods', 'show_goods');

function show_goods() {
	$user_id = get_current_user_id();
	$content='123';
	return $html;
}
