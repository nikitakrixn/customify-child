<?php
if ( !defined( 'ABSPATH' ) ) exit;

add_filter('acfe/form/load/user_id/form=ank_postavschik', 'ank_postavschik_user_source', 10, 3);
function ank_postavschik_user_source($user_id, $form, $action)
{

	if (isset($_POST) && isset($_POST['vendor_id'])) {
		$user_id = $_POST['vendor_id'];
	}
	return $user_id;
}
add_action('acfe/form/submit/user/form=ank_postavschik', 'my_form_submit', 10, 5);
function my_form_submit($user_id, $type, $args, $form, $action)
{
	if (isset($_GET) && isset($_GET['vendor_id'])) {
		$user_id = $_GET['vendor_id'];
	}
	return $user_id;
}
add_action('acfe/form/submit/user/form=ank_postavschik', 'my_form_submit_arg', 10, 5);
function my_form_submit_arg($user_id, $type, $args, $form, $action)
{
	if (isset($_GET) && isset($_GET['vendor_id'])) {
		$args['ID'] = $_GET['vendor_id'];
	}
	return $args;
}

add_filter('acfe/form/submit/user_args/form=ank_postavschik', 'my_form_user_args', 10, 4);
function my_form_user_args($args, $type, $form, $action)
{
	if (isset($_GET) && isset($_GET['vendor_id'])) {
		$args['ID'] = $_GET['vendor_id'];
	}
	return $args;
}
add_filter('acfe/form/load/user_id/form=ank_postavschik', 'my_form_user_source', 10, 3);
function my_form_user_source($user_id, $form, $action)
{
	if (isset($_GET) && isset($_GET['vendor_id'])) {
		$user_id = $_GET['vendor_id'];
	}
	return $user_id;
}


//
/**
 * Перезапишем данные из лид формы в полную после сохранения поста с фронтенда
 */
function dublicate_from_lid_to_full_tz($post_id)
{
	// Получим количество изделий из лид-формы
	$item_count = get_field('field_616fd4bd3b983', $post_id);
	// Получим плановый бюджет из лид-формы
	$budjet = get_field('field_62190679925fe', $post_id);
	// Получим имя из лид-формы
	$name = get_field('field_6245e2fc40ac9', $post_id);
	// Получим мейл из лид-формы
	$email = get_field('field_6245e2fc40afe', $post_id);
	// Получим телефон из лид-формы
	$phone = get_field('field_6245e2fc40b33', $post_id);
	$sphera = get_field('sfera_primeneniya',$post_id);

	// Вставим все вышеполученные поля в полную форму
	update_field('field_60eaa5be6143b', $item_count, $post_id);
	update_field('field_60eaa50561439', $budjet, $post_id);
	update_field('field_6167cae024660', $name, $post_id);
	update_field('field_6167caf724661', $email, $post_id);
	update_field('field_6167cb0424662', $phone, $post_id);
	update_field('sfera_primeneniya_ank', $sphera, $post_id);

	// Черт его знает зачем, но заказчица просила.
	wp_update_post(['ID' => $post_id]);
}
add_filter('acfe/form/submit/post/form=lid_with_tz', 'dublicate_from_lid_to_full_tz', 10, 1);


add_filter('acfe/form/submit/post_args/form=lid_with_tz', 'lid_with_tz_post_args', 10, 4);
function lid_with_tz_post_args($args, $type, $form, $action){
    // Change Post Category if the Action Type is 'insert_post'
    if($type === 'insert_post'){
        $args['post_category'] = array( 17094 );
    }
    return $args;
}

/**
 * Перезапишем данные из формы поставщика в полную форму поставщика после сохранения поста с фронтенда
 */
function dublicate_from_lid_postav_to_full_postav($user_id, $type, $args, $form, $action)
{
	// Получим и сохраним внаправления деятельности из лид-формы
	$work_direction = get_field('field_621b3bb0b165c', 'user_' . $user_id);
	update_field('field_621e7358a6ab5', $work_direction, 'user_' . $user_id);
	// Получим и сохраним ИНН из лид-формы
	$inn = get_field('field_61dbe2ee48070', 'user_' . $user_id);
	update_field('field_61e02fb11b22e', $inn, 'user_' . $user_id);
	update_field('field_61ed104754acd', $inn, 'user_' . $user_id);
	// Получим и сохраним имя
	$name = get_field('field_61dbec4948071', 'user_' . $user_id);
	update_field('field_61e02fb11b420', $name, 'user_' . $user_id);
	// Получим и сохраним сайт
	$site = get_field('field_621ef5fbc4b87', 'user_' . $user_id);
	update_field('field_61e02fb11b2d5', $site, 'user_' . $user_id);
	
	// Тип одежды и Сфера применения
	$tip = get_field('Tipodezhdy_lead', 'user_' . $user_id);
	update_field('Tipodezhdy', $tip, 'user_' . $user_id);
	$sfera = get_field('sfera_primeneniya_lead', 'user_' . $user_id);
	update_field('sfera_primeneniya', $sfera, 'user_' . $user_id);

	// Черт его знает зачем, но заказчица просила.
	wp_update_user(['ID' => $user_id]);
}
add_filter('acfe/form/submit/user/form=lid-forma-postavshika', 'dublicate_from_lid_postav_to_full_postav', 10, 5);

// поля email и password обязательные для заполнения на главной
add_filter('acf/prepare_field', 'required_on_front');
function required_on_front($field) 
{
	if (!($_SERVER['REQUEST_URI'] === '/')) return $field;
	if ($field['_name'] === 'email' or $field['_name'] === 'parol')
		$field['required'] = true;
	return $field;
}




//Сортировка поля Регион доставки

//field_6232fb95b332d
//field_6232fbe272c06
//field_61e02fb11b344


add_filter('acf/prepare_field', 'sort_select_prepare_field', 10, 3);
//add_filter('acf/prepare_field/key=field_6232fb95b332d', 'my_excerpt_prepare_field', 10, 3);
//add_filter('acf/prepare_field/key=field_6232fbe272c06', 'my_excerpt_prepare_field', 10, 3);
function sort_select_prepare_field($field){

	$needle   = 'sorted-a-z';

	if ( strpos($field['wrapper']['class'], $needle) !== false && $field['type'] == 'select')
    
    asort( $field['choices'] );

 /*if( current_user_can('editor') || current_user_can('administrator') ) { 
    // Stuff here for administrators or editors
    print_r($field);
 }
*/


    return $field;
    
}





/* вывод полей в тз */
function tpk_added_page_content ( $content ) {

	$tzarray = array('tz_na_sirie', 'tz_na_napolnitel', 'tz_na_nitki', 'tz_na_volokna');

    if ( is_singular( $tzarray ) ) {

    if ( $post ) {
        $ID = $post->ID;
      } else {
        $ID = get_the_ID();
      }

    	$content = '';

    	$content .= '<div class="print-content">';

    	$groups = acf_get_field_groups(array('post_type' => get_post_type($ID)));

    	foreach ($groups as $group) {

    		//$content .= $group['title'];

    		$fields = acf_get_fields($group['key']);


    		foreach ($fields as $key => $val) {

				$field = get_field_object($val['key']);

				//var_dump($field);
				$fnum = $val['key'];
				$type = $field['type'];
				$label = $field['label'];
				$format = $field['return_format'];//"name"

				if ( get_field($fnum, $ID) ) {

					$content .= '<div class="tz-info__point-title">' . $label . '</div>';
					$content .= '<ul>';
					$content .= '<li>' . get_field($fnum, $ID) . '</li>';
					$content .= '</ul>';	

				}

    		}

    	}

    	$content .= '</div>';

//$content .= '<div class="tz-buttons"><a id="print-button" class="tzPrintBtn acf-button button button-primary button-large" href="javascript:void(0);"><span>...</span></a></div>';

}

    return $content;
}
add_filter( 'the_content', 'tpk_added_page_content');




//фильтр описаний тз в сетке

add_filter( 'get_the_excerpt', 'tpk_excerpt_filter' );

function tpk_excerpt_filter( $excerpt, $post = null ){

	if ( '_' == get_post_type($post) ) {

      if ( $post ) {
        $ID = $post->ID;
      } else {
        $ID = get_the_ID();
      }
$excerpt = array();
 
$acf_cform_fields = array (

		//'Фото изделия' => 'field_63134dc1bce5f',
		'Вид одежды' => 'field_62361a8cc8925',
		'Сфера применения' => 'field_61de9dc7e8022',
		'Регион доставки' => 'field_6232fbe272c06',
		'Количество' => 'field_60eaa5be6143b',
		'Плановый бюджет' => 'field_60eaa50561439',
		//'Срок поставки' => 'field_631764bf8c345',
		//'Дополнительно потребуется' => 'field_621bb59614711',
	
	);

foreach ($acf_cform_fields as $key => $value) {

	if ( get_field($value, $ID) ) {

		$field = get_field_object($value);
		
		$type = $field['type'];


		if ( $type == 'taxonomy' ) {

			$excerpt[] = $key . ': ' . get_term( get_field($value, $ID) )->name;

		}

		if ( $type == 'acfe_taxonomy_terms' ) {

			$excerpt[] = $key . ': ' . implode(", ", get_field($value, $ID));

		}

		if ( $type == 'text' ) {

			$excerpt[] = $key . ': ' . get_field($value, $ID);

		}

	}
}

return implode(",<br/> " , $excerpt);

} else {
	return $excerpt;
}



};