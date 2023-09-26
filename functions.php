<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


//WORDPRESS DEBLOAT

//require get_stylesheet_directory() . '/inc/wp-debloat.php';


//VENDOR FILTER

require get_stylesheet_directory() . '/inc/ajax-filter.php';


//WORDPRESS REDIRECTS

require get_stylesheet_directory() . '/inc/wp-redirect.php';


//WOOCOMMERCE HOOKS

require get_stylesheet_directory() . '/inc/woo-hooks.php';


//WOOCOMMERCE GROUP BUY

require get_stylesheet_directory() . '/inc/group-buy.php';


//VENDORS

require get_stylesheet_directory() . '/inc/vendors.php';

//ELEMENTOR

require get_stylesheet_directory() . '/inc/elementor.php';

//ACF

require get_stylesheet_directory() . '/inc/acf.php';


//Send mail

require get_stylesheet_directory() . '/inc/ked.php';


//HOOKSsss

require get_stylesheet_directory() . '/inc/hook.php';


//WP head

//require get_stylesheet_directory() . '/inc/wp-head.php';


//Print script

require get_stylesheet_directory() . '/inc/print.php';


//Shortcodes

require get_stylesheet_directory() . '/inc/shortcodes.php';


// Provider registration

require get_stylesheet_directory() . '/inc/provider-reg.php';

require get_stylesheet_directory() . '/inc/TechnicalTask.php';

require get_stylesheet_directory() . '/inc/WC_Product_TechnicalTask.php';

require get_stylesheet_directory() . '/inc/create-new-order.php';

function tpk_script_style() {
	//wp_register_style('dop_style', '/wp-content/themes/customify/dop.css');
	//wp_enqueue_style('dop_style');

// Use minified libraries if SCRIPT_DEBUG is turned off
$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

//wp_enqueue_script( 'my-script-handle', plugin_dir_url( __FILE__ ) . 'assets/my-file' . $suffix . '.js', array( 'jquery' ) );

	//wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style' . $suffix . '.css');
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css');

	    wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );


}
add_action('wp_enqueue_scripts', 'tpk_script_style');

function themesharbor_disable_woocommerce_block_editor_styles() {
  wp_deregister_style( 'wc-block-editor' );
  wp_deregister_style( 'wc-blocks-style' );
}
add_action( 'enqueue_block_assets', 'themesharbor_disable_woocommerce_block_editor_styles', 1, 1 );






// Этот код дерегистрирует стиль "dashicons" для всех пользователей, кроме тех, у которых есть разрешение на обновление ядра сайта (т.е. администраторы)
// ускоряет работу сайта, но после него слетают стили форм ACF надо разбираться отдельно
// remove dashicons
//function wpdocs_dequeue_dashicon()
//{
//    if (current_user_can( 'update_core' ))
//	{
//        return;
//    }
//    wp_deregister_style('dashicons');
//}
// add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_dashicon' );




//скрытие панели для всех кроме админов start
function wph_del_toolbar($content) {
    return (current_user_can("administrator")) ? $content : false;
}
add_filter('show_admin_bar' , 'wph_del_toolbar');
//скрытие панели для всех кроме админов end


//запрет доступа к админке start
function wph_noadmin() {
    if (is_admin() && !current_user_can('administrator')) {
        wp_redirect(home_url());
        exit;
    } }
//add_action('init', 'wph_noadmin');
//запрет доступа к админке end
//


// открыть страницу моего магазина - для ЛК поставщика
add_shortcode('see_my_shop', 'see_my_shop');

function see_my_shop() {
    return '<a style="color:#1629B1"  font-size="13px" href="https://tpktrade.ru/cp/?user=' . get_current_user_id() . '">Как видят мой профиль клиенты</a>';
}

add_action('init', function() {
    add_rewrite_rule('user/([0-9]+)/products_page/([0-9]+)[/]?$', 'index.php?pagename=lichnyj-kabinet/cp&user=$matches[1]&products_page=$matches[2]', 'top' );	
    add_rewrite_rule('user/([0-9]+)/news_page/([0-9]+)[/]?$', 'index.php?pagename=lichnyj-kabinet/cp&user=$matches[1]&news_page=$matches[2]', 'top' );		
    add_rewrite_rule('user/([0-9]+)[/]?$', 'index.php?pagename=lichnyj-kabinet/cp&user=$matches[1]', 'top' );
	add_rewrite_tag('%user%','([^&]+)');
	add_rewrite_tag('%products_page%','([^&]+)');
	add_rewrite_tag('%news_page%','([^&]+)');
	
	if (preg_match('{user\/([0-9]+)\/(products_page|news_page)\/([0-9]+)}is',$_SERVER['REQUEST_URI'],$match)) {
		$_GET['user']=$match[1];
		$_GET[$match[2]]=$match[3];
	}
	
	if (preg_match('{user\/([0-9]+)}is',$_SERVER['REQUEST_URI'],$match)) {
		$_GET['user']=$match[1];
	}
});

function getPaginationByPostsType($page,$user_ID,$type,$uri) {
	global $wpdb;
	$content='';
	$sql="SELECT COUNT(p.ID) AS posts FROM wp6m_posts AS p
	WHERE p.post_author=$user_ID AND p.post_type='$type' AND p.post_status='publish'";
	$result=$wpdb->get_results($sql);
	$pages=isset($result[0]->posts) && is_numeric($result[0]->posts) && $result[0]->posts>30 ? ceil(intval($result[0]->posts)/30) : 1;
	if ($pages>1) {
		$from=($page-3)>=1 ? ($page-3) : 1;
		$to=($page+3)<=$pages ? ($page+3) : $pages;
		
		$content='<nav class="custom-pagination">';
		if ($page>1) {
			$content.='<a class="custom-pagination__page" href="/user/'.$user_ID.'/'.$uri.'/'.($page-1).'">&laquo; Назад</a>';	
			if ($page>=3) {
				$content.='<span class="custom-pagination__dots">&hellip;</span>';				
			}
		}
		
		for($i=$from;$i<=$to;$i++) {
			if ($i==$page) {
				$content.='<span class="custom-pagination__page custom-pagination__current">'.$i.'</span>';
			} else {
				$content.='<a class="custom-pagination__page" href="/user/'.$user_ID.'/'.$uri.'/'.$i.'">'.$i.'</a>';
			}
			
		}
		
		if ($page<$pages) {
			if ($page+3<=$pages) {
				$content.='<span class="custom-pagination__dots">&hellip;</span>';			
			}
			$content.='<a class="custom-pagination__page" href="/user/'.$user_ID.'/'.$uri.'/'.$pages.'">'.$pages.'</a>';
			$content.='<a class="custom-pagination__page" href="/user/'.$user_ID.'/'.$uri.'/'.($page+1).'">Далее &raquo;</a>';		
		}
		$content.='</nav>';
	}
	return $content;
}

// вывести ассортимент текущего пользователя
add_shortcode('products_current_user', 'products_current_user');

function products_current_user($atts)
{
	$current_user = wp_get_current_user();

	//echo '<h4> Записи пользователя '. $current_user->user_login .'</h4>';
	//echo '<table><tr><th>Изображение</th><th>Название</th><th>Стоимость</th><th>Статус</th><th>Действия</th></tr>';
	//<th>Действия</th></tr>';

	$user_ID = get_query_var('user') ? intval(get_query_var('user')) : false;
	if (!$user_ID) {
		$user_ID = !empty($_GET['user']) && is_numeric($_GET['user']) ? intval($_GET['user']) : get_current_user_id();	
	}
	$page=isset($_GET['products_page']) && is_numeric($_GET['products_page']) && $_GET['products_page']>1 ? intval($_GET['products_page']) : 1;
	$offset=($page-1)*30;
	global $post;
	$posts = get_posts(array(
		'numberposts' => '30', //количество записей
		'author' => $user_ID,
		'orderby'     => 'date', // Сортировка по дате
		'order'       => 'DESC', // Сортировка по названию
		'meta_key'    => '',
		'meta_value'  => '',
		'post_type'   => 'product', //Тип поста
		'offset'=>$offset
	));
	
	$out='<table>';
	
	if (count($posts)) {
		if(!wp_doing_ajax()) {			
			$out='<table><tr><th>Изображение</th><th>Название</th><th>Цена</th><th>Со скидкой</th><th>Минимальная партия</th></tr>';
		}
	} else {	
		if (!wp_doing_ajax()) {
			$out='Поставщик пока не загрузил ни одного товара';
		} else {
			$out='';
		}
		return $out;
	}


	foreach ($posts as $post) {
		setup_postdata($post);
		$post_status = get_post_status($post->ID);
		$price = get_post_meta(get_the_ID(), '_regular_price', true);
		$sale = get_post_meta(get_the_ID(), '_sale_price', true);
		$sale = floatval($sale);
		$sale = $sale ? $sale : $price;
		$min = get_post_meta(get_the_ID(), '_groupbuy_min_deals', true);
		$min = intval($min);
		$min = $min ? $min : 1;

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

		<td style="width: 10%;" class="cover"><strong"> ' . woocommerce_get_product_thumbnail() . ' </strong></td>

		<td style="width: 40%;"><strong><a href="' . esc_attr(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></strong> </td>

		<td class="post-status"><strong>' . $price . '</strong></td>

		<td class="post-status"><strong>' . $sale . '</strong></td>

		<td class="post-status"><strong>' . $min . '</strong></td>

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
	
	$out.=getPaginationByPostsType($page,$user_ID,'product','products_page');
	/*
	if(count($posts)>=30 && !wp_doing_ajax()) {
		$out.='<div class="ajax-wrapper au"></div>
		<a href="javascript:void(0)" class="ajax-more au" data-user="'.$user_ID.'">Загрузить еще</a>';
	}
	*/
	wp_reset_postdata(); // сброс
	return $out;
}

if(wp_doing_ajax()) {
	add_action('wp_ajax_pcu','action_pcu');
	add_action('wp_ajax_nopriv_pcu','action_pcu');
}

function action_pcu() {
	$user=!empty($_POST['user']) && is_numeric($_POST['user']) ? intval($_POST['user']) : false;
	$page=!empty($_POST['page']) && is_numeric($_POST['page']) && $_POST['page']>1 ? intval($_POST['page']) : 1;
	$_GET['user']=$user;
	$_GET['page']=$page;
	header('Content-Type: text/html; charset=utf-8');
	echo products_current_user(1);
	wp_die();
}

add_action('wp_enqueue_scripts','wp_enqueue_scripts1');

function wp_enqueue_scripts1() {
    wp_enqueue_style('user','/wp-content/themes/customify-child/assets/css/style.css',array(),false,'all');
    wp_enqueue_script('user','/wp-content/themes/customify-child/assets/js/page.js',array(),false,false);
}

// вывести новостей текущего пользователя
add_shortcode('news_current_user', 'news_current_user');

function news_current_user($atts)
{

	$current_user = wp_get_current_user();

	//echo '<h4> Записи пользователя '. $current_user->user_login .'</h4>';
	//echo '<table><tr><th>Изображение</th><th>Название</th><th>Стоимость</th><th>Статус</th><th>Действия</th></tr>';
	//<th>Действия</th></tr>';

	$user_ID = get_query_var('user') ? intval(get_query_var('user')) : false;
	if (!$user_ID) {
		$user_ID = !empty($_GET['user']) && is_numeric($_GET['user']) ? intval($_GET['user']) : get_current_user_id();	
	}
	$page=isset($_GET['news_page']) && is_numeric($_GET['news_page']) && $_GET['news_page']>1 ? intval($_GET['news_page']) : 1;
	$offset=($page-1)*30;
	global $post;
	$posts = get_posts(array(
		'numberposts' => '30', //количество записей
		'author' => $user_ID,
		'orderby'     => 'date', // Сортировка по дате
		'order'       => 'DESC', // Сортировка по названию
		'meta_key'    => '',
		'meta_value'  => '',
		'post_type'   => 'post', //Тип поста
		'post_status'=>'publish',
		'offset'=>$offset
	));
	
	$out='<table>';
	
	if (count($posts)) {
		if(!wp_doing_ajax()) {
			$out='<table><tr><th>Изображение</th><th>Название</th><th>Дата</th></tr>';
		}
	} else {	
		if (!wp_doing_ajax()) {
			$out='Поставщик пока не опубликовал ни одной новости';
		} else {
			$out='';
		}
		return $out;
	}


	foreach ($posts as $post) {
		setup_postdata($post);

		$out .= '
    <tr>

		<td style="width: 10%;" class="cover"><strong">' . get_the_post_thumbnail() . '</strong></td>

		<td><strong><a href="' . esc_attr(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></strong> </td>

		<td style="width: 20%;" class="post-status"><strong>'. get_the_date() . '</strong></td>


  	</tr>';
	}

	$out .= '</table>';
	
	
	$out.=getPaginationByPostsType($page,$user_ID,'post','news_page');
	/*
	if(count($posts)>=30 && !wp_doing_ajax()) {
		$out.='<div class="ajax-wrapper au2"></div>
		<a href="javascript:void(0)" class="ajax-more au2" data-user="'.$user_ID.'">Загрузить еще</a>';
	}
	*/
	wp_reset_postdata(); // сброс
	return $out;
}

if(wp_doing_ajax()) {
	add_action('wp_ajax_ncu','action_ncu');
	add_action('wp_ajax_nopriv_ncu','action_ncu');
}

function action_ncu() {
	$user=!empty($_POST['user']) && is_numeric($_POST['user']) ? intval($_POST['user']) : false;
	$page=!empty($_POST['page']) && is_numeric($_POST['page']) && $_POST['page']>1 ? intval($_POST['page']) : 1;
	$_GET['user']=$user;
	$_GET['page']=$page;
	header('Content-Type: text/html; charset=utf-8');
	echo news_current_user(1);
	wp_die();
}

function elementor_form_email_field_validation( $field, $record, $ajax_handler ) {
	if (!is_email($field['value'])) {
		$ajax_handler->add_error($field['id'],'Некорректный EMail.');
		return;
	}
	
	if (get_user_by('login',$field['value'])) {
		$ajax_handler->add_error($field['id'],'Пользователь с таким EMail уже существует.');
		return;		
	}
}
add_action( 'elementor_pro/forms/validation/email', 'elementor_form_email_field_validation', 10, 3 );

add_action('acf/validate_save_post', 'my_acf_validate_save_post',1);
function my_acf_validate_save_post() {
	$post_id=!empty($_POST['post_id']) && is_numeric($_POST['post_id']) ? intval($_POST['post_id']) : false;
	$post_id=!empty($_POST['_acf_post_id']) && is_numeric($_POST['_acf_post_id']) ? intval($_POST['_acf_post_id']) : false;
	
	if ($post_id==139045) {
		$email=!empty($_POST['acf']['field_6219cf78ae096']) ? $_POST['acf']['field_6219cf78ae096'] : false;
		$password=!empty($_POST['acf']['field_6219cf5fae095']) ? $_POST['acf']['field_6219cf5fae095'] : false;
		$user=wp_authenticate($email,$password);			
		if (isset($user->errors)) {
			acf_add_validation_error($_POST['acf']['field-600e609de8ab8'], 'Неправильно указан логин и/или пароль');
		}
	}
}

function rt_connections_id() {
	global $wpdb;
	$user_id=0;
	$result=$wpdb->get_results('SELECT AUTO_INCREMENT
	FROM information_schema.tables
	WHERE table_name = \'wp6m_users\'
	AND table_schema = DATABASE()');	
	if (isset($result[0])) {
		$user_id=$result[0]->AUTO_INCREMENT;
	}
    return $user_id;
}
add_shortcode('current-user-connections','rt_connections_id');

add_filter('the_content','filter_the_content_in_the_main_loop',1);
function filter_the_content_in_the_main_loop($content) {
	if (preg_match('{user\/([0-9]+)}is',$_SERVER['REQUEST_URI'])) {
		$xml=new DOMDocument();
		@$xml->loadHTML($content);
		$xpath=new DOMXpath($xml);
		$tmp=$xpath->query('//div[contains(@class,"eael-tabs-nav")]//li[contains(@class,"eael-tab-item-trigger")]');
		
		if (preg_match('{(products_page|news_page)}is',$_SERVER['REQUEST_URI'],$match)) {
		
		}
	}
    return $content;
}

// WC
function WC_shortcode_logout_user() {
    $wp_logout_url = wp_logout_url();
   
    return '
	<div style="top: -20px;" class="elementor-element elementor-element-7719eb8 hfe-nav-menu__align-justify hfe-submenu-icon-arrow hfe-link-redirect-child hfe-nav-menu__breakpoint-tablet elementor-widget elementor-widget-navigation-menu" data-id="7719eb8" data-element_type="widget" data-settings="{&quot;padding_horizontal_menu_item&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:8,&quot;sizes&quot;:[]},&quot;padding_vertical_menu_item&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:18,&quot;sizes&quot;:[]},&quot;padding_horizontal_menu_item_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_horizontal_menu_item_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_vertical_menu_item_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_vertical_menu_item_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;menu_space_between&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;menu_space_between_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;menu_space_between_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;dropdown_border_radius&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;top&quot;:&quot;&quot;,&quot;right&quot;:&quot;&quot;,&quot;bottom&quot;:&quot;&quot;,&quot;left&quot;:&quot;&quot;,&quot;isLinked&quot;:true},&quot;dropdown_border_radius_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;top&quot;:&quot;&quot;,&quot;right&quot;:&quot;&quot;,&quot;bottom&quot;:&quot;&quot;,&quot;left&quot;:&quot;&quot;,&quot;isLinked&quot;:true},&quot;dropdown_border_radius_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;top&quot;:&quot;&quot;,&quot;right&quot;:&quot;&quot;,&quot;bottom&quot;:&quot;&quot;,&quot;left&quot;:&quot;&quot;,&quot;isLinked&quot;:true},&quot;padding_horizontal_dropdown_item&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_horizontal_dropdown_item_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_horizontal_dropdown_item_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_vertical_dropdown_item&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:15,&quot;sizes&quot;:[]},&quot;padding_vertical_dropdown_item_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;padding_vertical_dropdown_item_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;distance_from_menu&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;distance_from_menu_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;distance_from_menu_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_size&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_size_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_size_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_border_width&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_border_width_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_border_width_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_border_radius&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_border_radius_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;toggle_border_radius_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="navigation-menu.default">
  <div class="elementor-widget-container">
    <div class="hfe-nav-menu hfe-layout-vertical hfe-nav-menu-layout vertical" data-layout="vertical">
      <div class="hfe-nav-menu__toggle elementor-clickable" aria-haspopup="true" aria-expanded="false">
        <div class="hfe-nav-menu-icon">
          <i aria-hidden="true" tabindex="0" class="fas fa-align-justify"></i>
        </div>
      </div>
      <nav class="hfe-nav-menu__layout-vertical hfe-nav-menu__submenu-arrow" data-toggle-icon="
				<i aria-hidden=&quot;true&quot; tabindex=&quot;0&quot; class=&quot;fas fa-align-justify&quot;></i>" data-close-icon="
				<i aria-hidden=&quot;true&quot; tabindex=&quot;0&quot; class=&quot;far fa-window-close&quot;></i>" data-full-width="">
        <ul id="menu-1-7719eb8" class="hfe-nav-menu">        
          <li id="menu-item-335299" class="menu-item menu-item-type-post_type menu-item-object-page parent hfe-creative-menu">
            <a href="'.$wp_logout_url.'" class="hfe-menu-item">Выйти</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
';
}

add_shortcode('WC_logout_user', 'WC_shortcode_logout_user');

add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    $order = wc_get_order( $order_id );
    if( $order->has_status( 'processing' ) ) {
        $order->update_status( 'completed' );
    }
}

function custom_enqueue_scripts() {

    wp_enqueue_script( 'lightbox-js', get_template_directory_uri() . '/assets/js/lightbox.js', array('jquery'));

    wp_enqueue_style( 'lightbox-css', get_template_directory_uri() . '/assets/css/lightbox.css');

}

add_action( 'wp_enqueue_scripts', 'custom_enqueue_scripts' );

