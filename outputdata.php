<?php


// вывести ассортимент текущего пользователя
add_shortcode('lp_products_current_user', 'lp_products_current_user');

function lp_products_current_user($atts)
{

	$current_user = wp_get_current_user();

	//echo '<h4> Записи пользователя '. $current_user->user_login .'</h4>';
	echo '<table><tr><th>Изображение</th><th>Название</th><th>Стоимость</th><th>Статус</th><th>Действия</th></tr>';
	//<th>Действия</th></tr>';


	$user_ID = get_current_user_id();
	global $post;
	$posts = get_posts(array(
		'numberposts'	=> '10', //количество записей
		'author'		=> $user_ID,
		'orderby'		=> 'date', // Сортировка по дате
		'order'			=> 'DESC', // Сортировка по названию
		'meta_key'		=> '',
		'meta_value'	=> '',
		'post_type'		=> 'product', //Тип поста
	));



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


		$out = '
    <tr>

		<td style="width: 10%;"><strong"> ' . woocommerce_get_product_thumbnail() . ' </strong></td>

		<td style="width: 40%;"><strong><a href="' . esc_attr(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></strong> </td>

		<td class="post-status"><strong>' . $price . '</strong></td>

		<td class="post-status"><strong>' . $post_status . '</strong></td>

		<td class="post-status"><strong><a href="">Редактировать</a></strong></td>

  	</tr>';
	}

	$out .= '</table>';

	wp_reset_postdata(); // сброс
	return $out;
}
