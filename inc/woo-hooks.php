<?php
if ( !defined( 'ABSPATH' ) ) exit;



//меняем текст кнопки в корзину в списке товаров
function filter_woocommerce_product_add_to_cart_text($text, $instance)
{
	$text = 'Купить товар';
	return $text;
};
add_filter('woocommerce_product_add_to_cart_text', 'filter_woocommerce_product_add_to_cart_text', 10, 2);


//меняем текст кнопки в корзину в одном товаре
function filter_woocommerce_product_single_add_to_cart_text($text, $instance)
{
	global $product, $post;
	if (is_object($product) &&  method_exists($product, 'get_type') && $product->get_type() == 'groupbuy') {
		$text = 'Купить товар <span class="atct-price" data-price="' . $instance->get_price() . '" data-id="' . $instance->get_id() . '"><span class="woocommerce-Price-amount amount">' . $instance->get_price() . '&nbsp;<span class="woocommerce-Price-currencySymbol"><span class="rur">р<span>уб.</span></span></span></span></span>';
	}
	return $text;
};

add_filter('woocommerce_product_single_add_to_cart_text', 'filter_woocommerce_product_single_add_to_cart_text', 10, 2);




/*вывод полей в табах товара*/

// ADD ATTRIBUTE DESCRIPTION TAB

add_filter( 'woocommerce_product_tabs', 'tpk_attrib_desc_tab' );

function tpk_attrib_desc_tab( $tabs ) {

    // Adds the Attribute Description tab



    if( has_term( array( 'shvejnye-izdeliya', 'tekstilnye-volokna', 'nitki-pryazha', 'tkani', 'napolniteli' ), 'product_cat' ) ) {

	    $tabs['attrib_desc_tab'] = array(

        'title'     => __( 'Характеристики изделия', 'woocommerce' ),

        'priority'  => 1,

        'callback'  => 'tpk_attrib_desc_tab_content'

    	);
	}



    return $tabs;

}




// ADD CUSTOM TAB DESCRIPTIONS

function tpk_attrib_desc_tab_content() {

    if ( $post ) {
        $ID = $post->ID;
      } else {
        $ID = get_the_ID();
      }


$content = '<div class="print-content">';

$content .= '<ul>';

$groups = acf_get_field_groups(array('post_id' => $ID));

    	foreach ($groups as $group) {

    		//if ($group['key'] == 'group_622b91b6c2825') {

    		//$content .= $group['title'] . '//' . $group['key'] ;

    		$fields = acf_get_fields($group['key']);

    		foreach ($fields as $key => $val) {

				$field = get_field_object($val['key']);
				$fnum = $val['key'];
				$type = $field['type'];
				$label = $field['label'];
				$format = $field['return_format'];//"name"
				$fdata = get_field($fnum, $ID);

				if ($fnum == 'field_627a53eb832f2') continue;

				if ( get_field($fnum, $ID) ) {
					$content .= '<li>';

					$content .= '<b>' . $label .  ':</b> ';

					if ($type == 'taxonomy' ) {

						if (!is_array($fdata)) $fdata = [$fdata];

						$arterm = array();

						foreach ($fdata as $key => $value) {
							//$content .= '<span>' . get_term( $value )->name . '</span>';
							$arterm[] = get_term( $value )->name;
						}

						$content .= '<span>' . implode(', ', $arterm) . '</span>';

					} else {

						if ($type == 'color_picker') {
							$content .= '<div class="coloredbg" style ="background-color:'. $fdata. ';display:inline-block"><span>' . $fdata . '</span></div>';

						} else {
							$content .= '<span>' . $fdata . '</span>';

						}

					}

					$content .= '</li>';	

				}

    		}

    	//}//if group key
    	}
$content .= '</ul>';
$content .= '</div>';

echo $content;

}

