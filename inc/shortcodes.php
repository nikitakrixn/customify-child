<?php
if ( !defined( 'ABSPATH' ) ) exit;



////шорткод информации о членстве пользователя



add_shortcode( 'membuser', 'get_user_membership_all');


function get_user_membership_all() {

$customer_memberships = wc_memberships_get_user_memberships( $user_id, $args );

global $post;


if ( ! empty( $customer_memberships ) ) : ?>

	<table class="shop_table shop_table_responsive my_account_orders my_account_memberships">

		<thead>
			<tr>
				<?php

				$my_memberships_columns = apply_filters( 'wc_memberships_my_memberships_column_names', array(
					'membership-plan'       => 'Название подписки',
					'membership-start-date' => 'Начало',
					'membership-end-date'   => 'Окончание',
					'membership-status'     => 'Статус',
					'membership-actions'    => '&nbsp;',
				), $user_id );

				foreach ( $my_memberships_columns as $column_id => $column_name ) :

					?>
					<th class="<?php echo esc_attr( $column_id ); ?>">
						<span class="nobr"><?php echo esc_html( $column_name ); ?></span>
					</th>
					<?php

				endforeach;

				?>
			</tr>
		</thead>

		<tbody>
			<?php foreach ( $customer_memberships as $customer_membership ) : ?>

				<?php if ( ! $customer_membership->get_plan() ) { continue; } ?>

				<tr class="membership">
					<?php foreach ( $my_memberships_columns as $column_id => $column_name ) : ?>

						<?php if ( 'membership-plan' === $column_id ) : ?>

							<td class="membership-plan" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php $members_area = $customer_membership->get_plan()->get_members_area_sections(); ?>
								<?php if ( ( ! empty ( $members_area ) && is_array( $members_area ) ) && ( wc_memberships_is_user_active_member( get_current_user_id(), $customer_membership->get_plan() ) || wc_memberships_is_user_delayed_member( get_current_user_id(), $customer_membership->get_plan() ) ) ) : ?>

									<?php $default_section = in_array( 'my-membership-content', $members_area, true ) ? 'my-membership-content' : current( $members_area ); ?>
									<a href="<?php echo esc_url( wc_memberships_get_members_area_url( $customer_membership->get_plan_id(), $default_section ) ); ?>"><?php echo esc_html( $customer_membership->get_plan()->get_name() ); ?></a>

								<?php else : ?>

									<?php echo esc_html( $customer_membership->get_plan()->get_name() ); ?>

								<?php endif;  ?>
							</td>

						<?php elseif ( 'membership-start-date' === $column_id ) : ?>

							<td class="membership-start-date" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php

								$order           = $customer_membership->get_order();
								$order_datetime  = $order ? $order->get_date_created( 'edit' ) : null;
								$order_timestamp = $order_datetime ? $order_datetime->getTimestamp() : null;
								$past_start_date = $order_timestamp ? ( $customer_membership->get_start_date( 'timestamp' ) < $order_timestamp ) : false;

								// show the order date instead if the start date is in the past
								if ( $past_start_date && $order && $customer_membership->get_plan()->is_access_length_type( 'fixed' ) ) {
									$start_time = $order_timestamp;
								} else {
									$start_time = $customer_membership->get_local_start_date( 'timestamp' );
								}

								?>
								<?php if ( ! empty( $start_time ) && is_numeric( $start_time ) ) : ?>
									<time datetime="<?php echo date( 'Y-m-d', $start_time ); ?>" title="<?php echo esc_attr( date_i18n( wc_date_format(), $start_time ) ); ?>"><?php echo date_i18n( wc_date_format(), $start_time ); ?></time>
								<?php else : ?>
									<?php esc_html_e( 'N/A', 'woocommerce-memberships' ); ?>
								<?php endif; ?>
							</td>

						<?php elseif ( 'membership-end-date' === $column_id ) : ?>

							<td class="membership-end-date" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php if ( $end_time = $customer_membership->get_local_end_date( 'timestamp', ! $customer_membership->is_expired() ) ) : ?>
									<time datetime="<?php echo date( 'Y-m-d', $end_time ); ?>" title="<?php echo esc_attr( date_i18n( wc_date_format(), $end_time ) ); ?>"><?php echo date_i18n( wc_date_format(), $end_time ); ?></time>
								<?php else : ?>
									<?php esc_html_e( 'N/A', 'woocommerce-memberships' ); ?>
								<?php endif; ?>
							</td>

						<?php elseif ( 'membership-status' === $column_id ) : ?>

							<td class="membership-status" style="white-space:nowrap;" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php echo esc_html( wc_memberships_get_user_membership_status_name( $customer_membership->get_status() ) ); ?>
							</td>

						<?php elseif ( 'membership-actions' === $column_id ) : ?>

							<td class="membership-actions order-actions" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php

								echo wc_memberships_get_members_area_action_links( 'my-memberships', $customer_membership, $post );

								// ask confirmation before cancelling a membership
								wc_enqueue_js( "
									jQuery( document ).ready( function() {
										$( '.membership-actions' ).on( 'click', '.button.cancel', function( e ) {
											e.stopImmediatePropagation();
											return confirm( '" . esc_html__( 'Are you sure that you want to cancel your membership?', 'woocommerce-memberships' ) . "' );
										} );
									} );
								" );
								?>
							</td>

						<?php else : ?>

							<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php

								do_action( "wc_memberships_my_memberships_column_{$column_id}", $customer_membership );

								?>
							</td>

						<?php endif; ?>

					<?php endforeach; ?>
				</tr>

			<?php endforeach; ?>
		</tbody>
	</table>
	<?php

else :

	?>
	<p>
		<?php

		echo (string) apply_filters( 'wc_memberships_my_memberships_no_memberships_text', __( "Looks like you don't have a membership yet!", 'woocommerce-memberships' ), $user_id );

		?>
	</p>
	<?php

endif;


}











//////////////////////////////////////////////////////////////////////////////////////////////////////////


//Шорткод вывода формы "Отправить задание на оценку"


// add some css

 
function wp_head_css_tz_contact_form() {
	?>
	<style>

#new_tz {
	display: flex;
	/*font-size: 1.2rem;*/
	color: #7D8489;
	flex-wrap: wrap;
	padding: 1rem;
	justify-content: center;
	max-width: 1440px;
}

#new_tz form {
	flex: 1;
	display: grid;
    grid-template-areas:
    "image fieldset"
    "image submit";
    gap: 0 1rem;
    max-width: 960px;
}

#new_tz form#tz_contact_form {
	flex: 2;
	grid-template-columns: 1fr 1fr;
}

#new_tz form .tz-image {
	grid-area: image;
	margin: 0;
    padding: 1rem;
    position: relative;
}
#new_tz form .tz-image #click-input {
	height: fit-content;
    position: absolute;
    transform: translateX(-50%);
    bottom: 5rem;
    left: 50%;
    font-size: 1.2rem;
    line-height: 1;
    background-color: #ffffff63
}


#new_tz form .tz-image #tzfile {
	display: none;
}

#new_tz form fieldset {
	grid-area: fieldset;
	flex: 1 1 400px;
	margin: 0;
    padding: 1rem;
}
#new_tz form .submit {
	grid-area: submit;
	display: flex;
    flex-flow: column;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
}

#new_tz .checkbox span {
	display: block;
}

#new_tz .notice-wrapper {
	flex: 1 1 100%;
}

#new_tz .register-toggle {
	pointer-events: none;
}

#new_tz .register-toggle.active {
	pointer-events: auto;
	cursor: pointer;
	color:#26599f;
}

#new_tz select, #new_tz input, #new_tz #tzformButton {
	/*padding: 0.6rem 1rem;*/
    border-style: solid;
    border-width: 1px 1px 1px 1px;
    border-color: #BABABA;
    border-radius: 50px 50px 50px 50px;
    margin-bottom: 0.8rem;
    background-color: white;
}

#new_tz select, #new_tz #tzformButton {
	color: #7D8489;
    background-image: none;
}

#new_tz button#tzformButton {
	background-color: #FF7100;
	color: #fff;
    font-size: 80%;
    opacity: 1;
    border: none;
    transition: all .4s linear;
}

#new_tz button#tzformButton.loading {
	opacity: .6;
	cursor: not-allowed;
	pointer-events: none;
}


button#tzformButton.loading:after{
    height: 24px;
    width: 24px;
    border: 4px solid rgb(0 0 0);
    margin-top: -13px;
    margin-left: -14px;
    border-left-color: currentColor;
}


#new_tz button#tzformButton[disabled] {
	opacity: .5
}

#new_tz button#tzformButton[disabled]:hover {
	cursor: not-allowed;
	box-shadow: none;
}

#new_tz button#tzformButton:hover {
	background-color: #ff821f;
    box-shadow: 1px 1px 3px grey;
}

#new_tz label, #new_tz h2 {
	color: #7D8489;
}

#new_tz h2 {
	font-size: 1.3rem;
	display: inline-block;
}

#new_tz .checkbox label {
	font-size: 90%;
    margin-left: 0.4rem;
}

#new_tz .register-label {
	text-align: center;
    display: block;
}

#new_tz .password_checkbox {
	margin: -1rem 0 1.5rem 1rem;
}

#new_tz .password_checkbox input {
	width: 0.6rem;
    height: 0.6rem;
    margin: 0;
}

#new_tz .checkbox.password_checkbox label {
	font-size: .8rem;
}

#new_tz .privacy-checkbox {
	line-height: 1.5rem;
}

#new_tz .privacy-checkbox input {
	margin: 0
}

#new_tz ::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
}

#new_tz .lost-link {
	font-size: 80%
}

#new_tz .result h3{
	text-align: center;
    color: #1391ff;
}

#new_tz .errors h3{
	text-align: center;
    color: #ff1313;
	
}

#new_tz .ziticomb {
    opacity: 0;
    position: absolute;
    top: 0;
    left: 0;
    height: 0;
    width: 0;
}

#new_tz .invalid {
	border: 2px solid red;
}

#new_tz .valid{
	border: 2px solid green;
	color: #222
}

	</style>
	<?php
}

add_action('wp_head', 'wp_head_css_tz_contact_form');



if (!function_exists('tz_contact_form')) {


add_shortcode( 'tzform', 'tz_contact_form');


function tz_contact_form() {

$out = '';

	$acf_cform_fields = array (

		'Фото изделия' => 'field_63134dc1bce5f',
		'Вид одежды' => 'field_62361a8cc8925',
		'Сфера применения' => 'field_61de9dc7e8022',
		'Регион доставки' => 'field_6232fbe272c06',
		'Количество' => 'field_60eaa5be6143b',
		'Плановый бюджет' => 'field_60eaa50561439',
		'Срок поставки' => 'field_631764bf8c345',
		'Дополнительно потребуется' => 'field_621bb59614711',
	
	);

	$required_field = 'field_62361a8cc8925';
?>

<section id="new_tz">

<form id="tz_contact_form" action="javascript:void(0);">

	<div class="tz-image">

	<?php
	foreach ($acf_cform_fields as $label => $field_num):

		$field = get_field_object($field_num);

		$req = ($required_field == $field_num) ? 'required' : '';
		
		$type = $field['type'];

		if ( $type == 'featured_image' ) {

			?>
			<img width="760" height="1013" src="https://tpktrade.ru/wp-content/uploads/2022/09/preview.png" class="attachment-full size-full" alt="" loading="lazy" srcset="https://tpktrade.ru/wp-content/uploads/2022/09/preview.png 760w, https://tpktrade.ru/wp-content/uploads/2022/09/preview-225x300.png 225w, https://tpktrade.ru/wp-content/uploads/2022/09/preview-64x85.png 64w" sizes="(max-width: 760px) 100vw, 760px">
			<input type="button" id="click-input" value="Добавить фото" onclick="document.getElementById('tzfile').click();" />
			<input type="file" accept=".png, .jpg, .jpeg" id="tzfile" >

			<?php
		}

	endforeach;
	?>
	</div>

	<fieldset> 
	<?php

	foreach ($acf_cform_fields as $label => $field_num):

		$field = get_field_object($field_num);
		$req = ($required_field == $field_num) ? 'required' : '';
		$type = $field['type'];

		if ( $type == 'taxonomy' ) {

			echo '<select id="' . $field_num . '" name="tztax-' . $field['taxonomy'] . '" ' . $req . '>';

		    	echo '<option class="disabled-selected" value="" disabled selected>' . $label . '</option>';

					$terms = get_terms([
					'taxonomy' => $field['taxonomy'],
					'hide_empty' => false,
					]);

					foreach ($terms as $term){
					echo '<option value="' . $term->name . '">' . $term->name . '</option>';
					}

			echo '</select>';

		}

		if ( $type == 'acfe_taxonomy_terms' ) {

			$field_terms = $field['allow_terms'];

			$hasterms = array();

			foreach ($field_terms as $f_term){

				if (strpos($f_term, 'all_') !== FALSE) { 

					$ss = get_terms([
					'taxonomy' => substr($f_term, 4),
					'hide_empty' => false,
					]);

					foreach ($ss as $s){

					$hasterms[] = $s->term_id ;

					}

				} else {

					$hasterms[] = $f_term;

				}

			}

			$hasterms = array_unique($hasterms );

			echo '<select id="' . $field_num . '" name="tztax-' . $field['taxonomy'][0] . '">';
			echo '<option value="" disabled selected>' . $label . '</option>';

			foreach ($hasterms as $hasterm) {
				$h_term = get_term( $hasterm);
				echo '<option value="' . $h_term->name . '">' . $h_term->name . '</option>';
			}

			echo '</select>';

		}

		if ( $type == 'text' ) 

			echo '<input type="text" id="' . $field_num . '" name="' . $field_num . '" placeholder="' . $label . '" >';

		if ( $type == 'date_picker' ) 

			echo '<input type="date" class="input-text " name="' . $field_num . '" id="' . $field_num . '" placeholder="" value="" min="" max="" maxlength="">';

		if ( $type == 'checkbox' ) {

				if( $field['choices'] ): 

				echo '<div class="checkbox"><h2>Дополнительно потребуется:</h2>';

				foreach( $field['choices'] as $value => $label ): 

					echo '<span><input type="checkbox" id="' . $value . '" name="' . $field_num  . '[]" value="' . $value . '">';
					echo '<label for="' . $value . '">' . $label . '</label></span>';

				endforeach; 

				echo '</div>';

				endif;

		}
		
	endforeach;

	?>

	</fieldset>

<?php

if ( !is_user_logged_in() ) {

?>


	</form>

	<form id="tz-register_form" action="javascript:void(0);">

		<fieldset> 
			<label class="register-label"><h2 class="register register-toggle">Зарегистрироваться</h2> | <h2 class="enter register-toggle active">Войти</h2></label>
			<input class="ziticomb" type="text" name="nameus" placeholder="Ваше Имя" id="nameus">
			<input class="ziticomb" type="text" name="emailus" placeholder="Телефон для связи" id="emailus">
			<input type="text" name="new_user_name" placeholder="Имя" id="new-username" required>
			<input type="text" name="new_user_phone" placeholder="Телефон" id="new-userphone" required>
			<input type="email" name="new_user_email" placeholder="Email" id="new-useremail" required>
			<input type="password" name="new_user_password" placeholder="Пароль" id="new-userpassword" required>
			<div class="checkbox password_checkbox">
				<input type="checkbox" onclick="showPassword()" id="password_checkbox"><label for="password_checkbox">Показать пароль</label>
				<a class="lost-link" style="display:none" href="<?php echo wp_lostpassword_url(); ?>">Забыли пароль?</a>
			</div>
			<div class="checkbox privacy_checkbox">
				<input type="checkbox" name="privacy_checkbox" id="privacy_checkbox" required=""><label for="privacy_checkbox">Согласен на обработку персональных данных и получение информации от поставщиков и Платформы LegpromRF</label>
			</div>
		</fieldset>

      <?php wp_nonce_field('ajax-register-nonce', 'tz-security'); 

}


      ?>

		<div class="submit">
			<button id="tzformButton" class="button" >Отправить задание на оценку</button>
		</div>

    </form> 

	<div class="notice-wrapper">
		<div class="result"></div>
		<div class="errors"></div>
	</div>

</section>



<script type="text/javascript" >

document.getElementById("tzfile").onchange = function () {
    let fileName = this.files[0].name,
        btn = document.getElementById("click-input");
    btn.value = fileName;
    btn.classList.add("valid");

 };


const userForm = document.getElementById("tz-register_form");

if (userForm) {

function showPassword() {
  var x = document.getElementById("new-userpassword");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


const privacyCheckbox = document.getElementById("privacy_checkbox");
const buttonSubmit = document.getElementById("tzformButton");

privacyCheckbox.addEventListener("change", (event) => {
  buttonSubmit.disabled = !event.target.checked;
}, false);





const userText = userForm.querySelectorAll('input[type=text]');



userForm.querySelectorAll('.register-toggle').forEach((element) => {

  element.addEventListener("click", (event) => {

  		if (element.classList.contains("enter")) {

  			userText.forEach((element) => {

  				element.type = "hidden";
  				element.required = false;
  				userForm.querySelector('.lost-link').style.display = 'block';

  			}, false);


  		} else {

  			userText.forEach((element) => {

				element.type = "text";
				element.required = true;
				userForm.querySelector('.lost-link').style.display = 'none';

  			}, false);

  		}

  		userForm.querySelectorAll('.register-toggle').forEach((element) => {
  			element.classList.toggle("active");
  		}, false);



}, false);


})




}


$j=jQuery.noConflict(); 


    $j(document).ready(function(){

    function tz_form_validate(element) {
    $j('html, body').animate({scrollTop: $(element).offset().top-100}, 150);
    //element.css("border", "1px solid red");
    element.addClass("invalid");
} 


$j('#new_tz').on('change', '.invalid, input, select', function(){    // 3rd way
    $j(this).removeClass("invalid");
    $j(this).addClass("valid");
});
 
        $j('#new_tz').on('click', '#tzformButton', function(e) {

        e.preventDefault();

	var data = new FormData();

		var validated = true;

		 $('#new_tz').find('input, select').each(function(){
		      var req = $j(this).is('[required]'),
		          sel = $j(this).is('select'),
		          chk = $j(this).is('[type="checkbox"]');


		      
		      if( chk && req && $j(this).is(":checked")==false ){
		        	tz_form_validate( $j(this).parent() );
		        	validated = false;
		      }

		      if(sel && req){
		        if ( $j(this).find(':selected').prop('disabled') ) {
		        	tz_form_validate( $j(this) );
		        	validated = false;
		        }
		      }
		      
		      if(req && $j(this).val() == '' ){
		      	tz_form_validate( $j(this) );
		        	validated = false;
		      }


		    });


		if (validated == false) {
		      	return false;
		      }


if( $('#tz-register_form').length )	{

var user_data = $j('#tz-register_form').serialize();
var security = $j('#tz-security').val();

data.append('security', security);

data.append('user', user_data);

}


data.append('action', 'tz_contact_ajax_handler');
var form_data = $j('#tz_contact_form').serialize();

data.append('tz', form_data);

data.append('photo', $j('#tzfile')[0].files[0]);


var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

$j('#tzformButton').addClass('loading');


    $j.ajax({
 
        type: 'POST',

        dataType: 'json',
 
        url: ajaxurl,

        enctype: 'multipart/form-data',

        processData: false,
    contentType: false,
    cache: false,

        data: data,
 
        success: function(data, textStatus, XMLHttpRequest) {

        	$j('#tzformButton').removeClass('loading');
          
if ( data.status == 'success' ) {

	$j('.result').html('');
    //$j('.result').append(data.text);
    $j('.result').append($j("<h3>").text(data.text));

   window.location.href = data.url + '/?action=select-vendor';


}
if ( data.status == 'error' ) {

	$j('.errors').html('');
    //$j('.errors').append(data.text);
    $j('.errors').append($j("<h3>").text(data.text));

}


        },
 
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        	$j('#tzformButton').removeClass('loading');
            alert(errorThrown);
        }
 
    });

                
        })
    });


</script>

<?php

	
//save ---- ($post_type == "_")	
	
	return $out;
	
}

}//if (!function_exists('tz_contact_form'))


// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_tz_contact_ajax_handler', 'tz_contact_ajax_handler' );
add_action( 'wp_ajax_tz_contact_ajax_handler', 'tz_contact_ajax_handler' );




function tz_contact_ajax_handler() {


	$results = array();
	$data = '';
 
 if (is_user_logged_in()) {

$user_id = get_current_user_id();

 } else {

check_ajax_referer( 'ajax-register-nonce', 'security' );

$newuser = array();
parse_str($_POST['user'], $newuser);


	if (!array_key_exists('privacy_checkbox', $newuser)) {

	$results['status'] = 'error';
    $results['text'] = 'Для регистрации необходимо предоставить согласие на обработку персональных данных и получение информации от поставщиков и Платформы LegpromRF!';

    die(json_encode($results));

}

if ( array_key_exists('nameus', $newuser) || array_key_exists('emailus', $newuser) ) {

	$results['status'] = 'error';
    $results['text'] = 'А вас случайно не Игорь зовут?';
    //die($results);
    die(json_encode($results));

}

	  $new_user_name = stripcslashes($newuser['new_user_name']);
	  $new_user_email = stripcslashes($newuser['new_user_email']);
	  $new_user_password = $newuser['new_user_password'];
	  $user_nice_name = strtolower($newuser['new_user_email']);
	  $user_data = array(
	      'user_login' => $user_nice_name,
	      'user_email' => $new_user_email,
	      'user_pass' => $new_user_password,
	      'user_nicename' => $user_nice_name,
	      'display_name' => $new_user_name,
	      'role' => 'customer'
	  	);
	  $user_id = wp_insert_user($user_data);

	  update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $newuser['new_user_phone'] ) );

	  	if (!is_wp_error($user_id)) {
	      //$results .= 'Поздравляем, '.$new_user_name.',Вы зарегистрированы! ';
	      $results['userstatus'] = 'registered';
    	  $results['usertext'] = 'Поздравляем, '.$new_user_name.',Вы зарегистрированы! ';


			$credentials = array();
			$credentials['user_login'] = $user_nice_name;
			$credentials['user_password'] = $new_user_password;
			$credentials['remember'] = true;

			$signed = wp_signon( $credentials, false );

			if ( is_wp_error($signed) ) {
			array_push($results['usertext'], ' Но возникли ошибки при авторизации пользователя. Обратитесь к администрации сайта.');
			}

	  	} else {

	  		//$results .= 'Ошибка при регистрации пользователя: <br/>';
	  		$results['userstatus'] = 'registererror';
    	    $results['usertext'] = 'Ошибка при регистрации пользователя: <br/>';

	    	if (isset($user_id->errors['empty_user_login'])) {
	          array_push($results['usertext'], 'Проверьте имя пользователя!');
	      	} elseif (isset($user_id->errors['existing_user_email'])) {
	          array_push($results['usertext'], 'Такой адрес Email уже зарегистрирован!');
	      	} else {
	          array_push($results['usertext'], 'Проверьте, пожалуйста, все поля формы!');
	      	}

	      	die(json_encode($results));
	  	}

}

$title = 'qwerty';

 
    $post_id = wp_insert_post( array(
        'post_title'        => $title,
        'post_type' => '_',
        'post_content'      => $data,
        'post_status'       => 'publish',
        'post_category' => array( 17094 ),
        'post_author'       => $user_id
    ) );

$params = array();

parse_str($_POST['tz'], $params);

foreach ($params as $key => $value) {

			if (strpos($key, 'tztax-') !== FALSE) { 

				wp_set_post_terms( $post_id, $value, substr($key, 6), false );

				$name = get_taxonomy( substr($key, 6) );
				//$name = get_term_by( 'slug', substr($key, 6), 'taxopress_taxonomy' );

				//if (!empty($value)) $data .= $name->labels->name . ': ' . $value . ' <br/>';

				$data .= '> ' . $value . ' <br/>';

			} else {

				update_field( $key, $value, $post_id );

				$fild = get_field_object($key);

				if (is_array($value)) $value = implode(', ', $value);

				if (!empty($value)) $data .= $fild['label'] . ': ' . $value . ' <br/>';

			}
	
}


wp_update_post( array(
        'ID' => $post_id,
        'post_title' => $post_id,
        'post_content'  => $data,
  
    ));


    if( isset( $_FILES['photo'] ) ){ 

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php'; 


    $attachment_id = media_handle_upload( 'photo', $post_id );

    set_post_thumbnail( $post_id , $attachment_id );
  
    
}
 
    if ( $post_id != 0 )
    {
        $results['status'] = 'success';
    	$results['text'] = 'Задание принято!';
    	$results['url'] = get_post_permalink( $post_id );

    }
    else {
        $results['status'] = 'error';
    	$results['text'] = 'Мы не смогли обработать данные. Попробуйте еще раз!';
    }
   

    die(json_encode($results));

}











//Шорткод вывода производителей "Текстильные волокна""

if (!function_exists('my_get_vendors')) {

function my_get_vendors($users, $permiss) {

	foreach ($users as $key => $value) {

		echo "<tr>";

			echo "<td>";
			$image = get_field('logotip','user_' . $value->ID);
			if( !empty($image) ) echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" />';
			echo "</td>";

			echo "<td>";
		    echo "<p>Наименование</p>";
			echo get_field('kratkoe_naimenovanie','user_' . $value->ID) ? get_field('kratkoe_naimenovanie','user_' . $value->ID) : "-";
			echo "</td>";

			echo "<td>";//ИНН
		    echo "<p>ИНН</p>";
			echo get_field('field_61ed104754acd','user_' . $value->ID) ? get_field('field_61ed104754acd','user_' . $value->ID) : "-";
			echo "</td>";

			echo "<td>";//Сайт
		    echo "<p>Сайт</p>";
			echo get_field('field_61e02fb11b2d5','user_' . $value->ID) ? get_field('field_61e02fb11b2d5','user_' . $value->ID) : "-";
			echo "</td>";


			echo "<td>";//Email
		    echo "<p>Email</p>";
			echo !empty($value->user_email) ? $value->user_email : '-';
			echo "</td>";

			echo "<td>";//phone
		    echo "<p>Телефон</p>";
			echo get_field('field_61e02fb11b3e8','user_' . $value->ID) ? get_field('field_61e02fb11b3e8','user_' . $value->ID) : "-";
			echo "</td>";
			
			echo "<td>"; //Регион
		    echo "<p>Регион</p>";
					$region = get_field('Regionproizvodstva_user','user_' . $value->ID);
					if ($region) {
						 $region = get_term_by('term_taxonomy_id', $region)->name;
					} else {
						$region = '-';
					}
					echo $region;
			echo "</td>";

			
	//		echo "<td>";
	//		echo get_field('yuridicheskij_adres','user_' . $value->ID) ? get_field('yuridicheskij_adres','user_' . $value->ID) : "-";
	//		echo "</td>";

	//		echo "<td>";//Виды сырья и материалов
	//		echo get_field('field_6310b517da28a','user_' . $value->ID) ? implode(get_field('field_6310b517da28a','user_' . $value->ID), ',') : "-";
	//		echo "</td>";

 
			echo "<td>"; // Вид одежды
		    echo "<p>Вид одежды</p>";
					$vd = get_field('field_61e035a2e79fe','user_' . $value->ID);
					if ($vd) {
						 $vd = get_term_by('term_taxonomy_id', $vd)->name;
					} else {
						$vd = '-';
					}
					echo $vd;
			echo "</td>";

			echo "<td>"; // Сфера применения
		    echo "<p>Сфера применения</p>";
					$vd = get_field('field_61e02fb11a939','user_' . $value->ID);
					if ($vd) {
						 $vd = get_term_by('term_taxonomy_id', $vd)->name;
					} else {
						$vd = '-';
					}
					echo $vd;
			echo "</td>";

			echo "<td>"; //Рейтинг
		    echo "<p>Рейтинг</p>";

					$vd = get_field('field_621f82db1c592','user_' . $value->ID);
					if ($vd) {
						$vd = "Проверен";
					} else {
						$vd = '-';
					}
					echo $vd;
			echo "</td>";
				
			echo "<td>";
			echo '<a href="https://tpktrade.ru/cp?user=' . $value->ID . '">Подробнее</a>';
			echo "</td>";

			if($permiss == 'true'){
			echo "<td><a href='https://tpktrade.ru/edit-postavshhik/?vendor_id=" . $value->ID . "' target='_blank'>Изменить</a></td>";
			}

		echo "</tr>";

	}
			
}






add_shortcode( 'ajax-vendors', function ($atts) {

$atts = shortcode_atts( array(
		'vendor' => '',
	), $atts, 'ajax-vendors' );

$vendor = esc_html( $atts['vendor'] );
$vvar = 'var vendoR = "' . ( $vendor ? $vendor : '' ) . '";';

wp_register_script( 'vendoR', '' );
wp_enqueue_script( 'vendoR' );
wp_add_inline_script( 'vendoR', $vvar );


/*add_action('wp_head', 'vendor_to_header');

function vendor_to_header($vendor) {
	?>
	<script type="text/javascript">

var vendoR = <?php $vendor;?>

</script>

<?php

}*/


ob_start();

$number = 50;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if($paged==1){
      $offset=0;  
    }else {
       $offset= ($paged-1)*$number;
    }
$args = array(
	'role'   => 'wcfm_vendor',
	'fields' => array('ID', 'user_email'),
	'offset' => $offset,
	'number' => $number,
	//'s' => esc_attr( $_POST['keyword'] )
	'meta_query' => array(
                       // 'relation' => 'OR',
                        array(
                  //          'key'     => 'vidy_syrya_i_materialov',
			            'key'     => 'Napravleniyaraboty',

                            'value'   => $vendor,
                            'compare' => 'LIKE'
                        ),
                        
                    )
	);


		$user_query = new WP_User_Query($args);
		$users = $user_query->get_results();

	//	echo '<h1 class="center vendor-table">' . $vendor . '</h1>';

if ( ! empty( $users ) ) {

		$total_users = $user_query->get_total();

		$user = wp_get_current_user();

		if ( in_array( 'administrator', (array) $user->roles ) || in_array( '_', (array) $user->roles )) {
		$permiss = 'true';
		}else{
		$permiss = 'false';
		}

  
?>


	<table id="ajax-vendor-table-search">
		<thead>
			<tr>
				<th width="10%">Лого</th>
				<th>Название</th>
				<th>ИНН</th>
				<th>Сайт</th>
				<th>Email</th>
				<th>Телефон</th>
				<th>Регион</th>
				<th>Вид одежды</th>
				<th>Сфера применения</th>
				<th>Проверен</th>
				<th>ЦП</th>
				<?php
				if($permiss == 'true'){
					?>
				<th></th>
					<?php
				}
				?>
			</tr>
		</thead>
		<tbody id="vendorlist-tbody">
			<?php my_get_vendors($users, $permiss);?>
		</tbody>
		<tfoot id="datafetch">
			
		</tfoot>
	</table>
	<div id="vendorlist-pagination">
		<?php

		if($total_users > $number){

			$pg_args = array(
			'base'	 => add_query_arg('paged','%#%'),
			'format'   => '',
			'total'	=> ceil($total_users / $number),
			'current'  => max(1, get_query_var('paged')),
			'prev_next'    => true,
			'prev_text'    => '«',
			'next_text'    => '»',
			);
		
			// for ".../page/n"
			if($GLOBALS['wp_rewrite']->using_permalinks())
			$pg_args['base'] = user_trailingslashit(trailingslashit(get_pagenum_link(1)).'page/%#%/', 'paged');
		
			echo paginate_links($pg_args);
		}

	?>
	</div>
</div>
<?php
}
else{
	?>

<h4 id="no-results" class="center">По заданным критериям ничего не найдено.</h4>
<?php

}


	return ob_get_clean();
	
	
} );




// the ajax function
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');

function data_fetch(){

$search_term = $_POST['keyword'];

$args = array(
	'role'   => 'wcfm_vendor',
	'fields' => array('ID', 'user_email'),
	//'offset' => $offset,
	//'number' => $number,
	//'s' => esc_attr( $_POST['keyword'] ),
	'search'         => '*' . esc_attr( $search_term ) . '*',
    'search_columns' => array( 'user_email' ),
	'meta_query' => array(
                       // 'relation' => 'OR',
                        array(
                            'key'     => 'vidy_syrya_i_materialov',
                            'value'   => $_POST['vendor'],
                            'compare' => 'LIKE'
                        ),
                        
                    )
	);

//$args['search_columns'] = array('user_email');
	//$args['search'] = $_POST['keyword'];


		$user_query = new WP_User_Query($args);
		$users = $user_query->get_results();

	if ( ! empty( $users ) ) {

		$user = wp_get_current_user();

		if ( in_array( 'administrator', (array) $user->roles ) || in_array( '_', (array) $user->roles )) {
		$permiss = 'true';
		}else{
		$permiss = 'false';
		}


		my_get_vendors($users, $permiss);


        wp_reset_postdata();  
    } else{
        echo '<div id="no-results">По заданным критериям ничего не найдено.</div>';
    }

    wp_die(); 
}


// add some css
add_action('wp_head', 'wp_head_css_fetch_vendors');
 
function wp_head_css_fetch_vendors() {
	?>
	<style>
	.center {text-align: center}
	table tbody#vendorlist-tbody td {
		background: #fff
	}

	table#ajax-vendor-table-search th, table#ajax-vendor-table-search td, #vendorlist-pagination > * {
		border-style: solid;
    border-width: 1px 1px 1px 1px;
    border-color: #eeeeee;
	}
	#vendorlist-pagination {
		height: 30px;
    margin: 20px 0;
	}
	#vendorlist-pagination > * {
		padding: 5px 15px 5px 15px;
	    margin: 5px 5px 0px 0px;
	    color: #666666;
	    background-color: #ffffff;
	    display: inline-block;
	}
	.ajax-vendor-table-search, h1.vendor-table {
		margin-top: 40px;
		padding: 10px 0;
	}
	#search-keyword {
		transition: all .4s linear;
		max-width: 450px;
	}
	#search-keyword.loading{
		/*background-color: grey !important;*/
		background-size: 300% 300%;
  background-image: linear-gradient(
        -45deg, 
        rgba(59,173,227,1) 0%, 
        rgba(87,111,230,1) 25%, 
        rgba(152,68,183,1) 51%, 
        rgba(255,53,127,1) 100%
  );  
  animation: AnimateBG 1.5s ease infinite;
	}



	</style>
	<?php
}


// add the ajax fetch js + sort of select el + print scripts
add_action( 'wp_footer', 'ajax_fetch_vendors' );
function ajax_fetch_vendors() {
?>
<script type="text/javascript">

var timeoutF = null;
jQuery('#search-keyword').keyup(function() {

	//console.log('vendoR' + vendoR);


  clearTimeout(timeoutF);
  timeoutF = setTimeout(() => {

  	if (jQuery(this).val().length >= 1) {

  		jQuery(this).addClass('loading');

  		 jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'post',
        data: { action: 'data_fetch', keyword: jQuery(this).val(), vendor: vendoR },
        success: function(data) {
        	jQuery('#datafetch').show();
        	jQuery('#vendorlist-tbody').hide();
        	jQuery('#vendorlist-pagination').hide();
        	jQuery('#datafetch').empty();
            jQuery('#datafetch').html( data );
            jQuery('#search-keyword').removeClass('loading');
        }
    });

    } else {
    	    jQuery('#vendorlist-tbody').show();
        	jQuery('#vendorlist-pagination').show();
        	jQuery('#datafetch').empty();
            jQuery('#datafetch').hide();
    }

  }, 600);
});

</script>

<?php
}



} //!function_exist 'my_get_vendors'

add_shortcode( 'tz_status_order', 'tz_status_order');

/**
 * Shows the status of an order in the catalog
 *
 * @return void
 */
function tz_status_order() {

    if( get_field('status_zakaza')) {
        if (get_field('status_zakaza') == 'Открыто') {
            echo '<span class="status__order status__order-open">' . get_field("status_zakaza") . '</span>';
        } else {
            echo '<span class="status__order status__order-close">' . get_field("status_zakaza") . '</span>';
        }
    }
}

add_shortcode( 'tz_date_publish', 'tz_date_publish');

function tz_date_publish() {
    return ( get_the_time('U') >= strtotime('-1 week') ) ? sprintf( esc_html__( '%s назад', 'textdomain' ), human_time_diff( get_the_time ( 'U' ), current_time( 'timestamp' ) ) ) : get_the_date();
}
