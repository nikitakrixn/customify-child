<?php
if ( !defined( 'ABSPATH' ) ) exit;


add_action('wp_ajax_providerreg', 'provider_reg');
add_action('wp_ajax_nopriv_providerreg', 'provider_reg');

function provider_reg()
{   
	$args = array(
		'role'         => 'Магазин продавца', // authors only
		'orderby'      => 'registered', // registered date
		'order'        => 'DESC', // last registered goes first
		'number'       => 2 // limit to the last one, not required
	);

	$users = get_users( $args );

	$last_user_registered = $users[1]; // the first user from the list
	
	
    $to     = 'sashadoneze@gmail.com';
    $title  = 'Новый поставщик';
    $text   = 'Новый созданный поставщик. Название компании(бренд): ' . $last_user_registered;
		//$_POST['companyName'] . '.&nbsp;Email: ' . $_POST['userEmail'] . '.&nbsp;ID: ' . $_POST['userId'];

    wp_mail($to, $title, $text);
    return 'Письмо отправлено';
    die();
}
