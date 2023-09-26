$ = jQuery;

function get_checboxes(main_id, post, single_provider = false, user_id) {
    var checkboxesCheckedID = [];
    var checkboxesCheckedemail = [];
    var currentUser = user_id | main_id;
    var post_ids = post;
	
	if (single_provider) {
		// Если отправляют запрос одному поставщику - например: через кнопку "Узнать стоимость"
		checkboxesCheckedID.push(main_id);
		checkboxesCheckedemail.push($('.vendor_mail[data-vendor="' + main_id + '"] .vendore__email-content').text());
	} else {
		$('.select-tz-vendor').each(function() {
			if (this.checked == true) {
				var vendor = $(this).attr("data-vendor");
				checkboxesCheckedID.push(vendor);
				checkboxesCheckedemail.push($(this).closest('.vendors__item').find('.vendore__email-content').text());
			}
    	})
	} 
	
	
    var data = {
        action: 'kedemail',
        id: checkboxesCheckedID,
        mail: checkboxesCheckedemail,
        userid: currentUser,
        post_id: post_ids,
    };
    // 'ajaxurl' не определена во фронте, поэтому мы добавили её аналог с помощью wp_localize_script()
   jQuery.post('https://tpktrade.ru/wp-admin/admin-ajax.php', data, function(response) {
	   response = JSON.parse(response);
	 
	   for (let id of response.messages) {
		   document.getElementById('status_' + id).innerHTML = 'Отправлено';
	   }
	   alert(response.answer);
    });
}





$('input.select-tz-vendor').on('change', function(evt) {
    if ($('input.select-tz-vendor:checked').length + $('td.sented_letter').length > 3 && $('.select-all-tz-vendors').length == 0) {
        this.checked = false;
    }
    if ($(this).parent().parent().find('td.sented_letter').length > 0) {
        this.checked = false;
    }
});

$('.select-all-tz-vendors').change(function() {
    console.log('checkeddd');
    if ($(this).prop("checked")) {
        $('.select-tz-vendor').prop("checked", true);
    } else {
        $('.select-tz-vendor').prop("checked", false);
    }
});

$('.select-tz-vendor').change(function() {
    if ($('.select-all-tz-vendors').prop("checked")) {
        $('.select-all-tz-vendors').prop("checked", false);
    }
})

$('input.edit_button').change(function() {
    let val = $(this).val();
    $('.ked_container > form input[type="hidden"]').remove();
    $('.ked_container > form').append('<input type="hidden" name="vendor_id" value="' + val + '">');
    $('.ked_container > form').submit();
})

logout_button();

function logout_button() {
    let menu = document.querySelector('.elementor-element-24e3830 h2');
    if (menu)
        menu.innerHTML += '<br><a href="https://tpktrade.ru/my-account/customer-logout/">Выйти</a>';
}


