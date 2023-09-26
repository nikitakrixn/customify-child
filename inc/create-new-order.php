<?php

/*
 *
 * Создание новой формы для ТЗ
 *
 */
add_shortcode( 'create_new_tz_order', 'create_new_order');

function create_new_order() {
    $field = get_field('field_61e0321f7e2a7');
    $technical_task = new TechnicalTask();
    /*$technical_task->createTechnicalTaskProduct('Выделение объявления рамкой и закрепление вашего ТЗ вверху общего списка', 'vip_post_technical_task', '500');
    $technical_task->createTechnicalTaskProduct('Подбор рекомендуемых поставщиков и рассылка запросов на оценку', 'select_recommended_suppliers', '250');
    $technical_task->createTechnicalTaskProduct('Персональная консультация', 'personal_consultation', '850');
    $technical_task->createTechnicalTaskProduct('Отправить заявку рассылкой по базе всех фабрик', 'send_task_for_all__mail', '1500');*/
    $args = [
        'taxonomy'      => [ 'pa_dlya-kogo', 'all_pa_dlya-kogo' ]
        ];
    $fields = acf_get_field('postavka_syrya');

    ?>
        <div class="technical-task-id">Техническое задание <span>№<?php echo $technical_task->getFuturePostId();?></span></div>
    <div class="container-new-tz">
        <div class="multi-step">
            <ul class="multi-step-list">
                <li class="multi-step-item current">
                    <div class="item-wrap">
                        <p class="item-title">Вид изделия</p>
                    </div>
                </li>
                <li class="multi-step-item">
                    <div class="item-wrap">
                        <p class="item-title">Материалы</p>
                    </div>
                </li>
                <li class="multi-step-item">
                    <div class="item-wrap">
                        <p class="item-title">Услуги</p>
                    </div>
                </li>
                <li class="multi-step-item">
                    <div class="item-wrap">
                        <p class="item-title">Условия поставки</p>
                    </div>
                </li>
                <li class="multi-step-item">
                    <div class="item-wrap">
                        <p class="item-title">Публикация</p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="technical-task-form-wrapper">
            <form action="" method="POST" id="new-technical-task">
                <div class="technical-task-form active">
                    <div class="technical-task-form-image">
                        <label>Главное фото <span>*</span></label>
                        <div class="technical-task-form-image-preview">
                            <img id="file-ip-1-preview">
                        </div>
                        <div class="technical-task-form-image-drag-area">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="33" viewBox="0 0 36 33" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M27.792 5.82553C27.864 5.95139 27.99 6.04129 28.152 6.04129C32.472 6.04129 36 9.56537 36 13.8806V24.5607C36 28.8759 32.472 32.4 28.152 32.4H7.848C3.51 32.4 0 28.8759 0 24.5607V13.8806C0 9.56537 3.51 6.04129 7.848 6.04129C7.992 6.04129 8.136 5.96937 8.19 5.82553L8.298 5.60977C8.36005 5.47916 8.42374 5.34502 8.48839 5.20884C8.94887 4.2389 9.45832 3.16584 9.774 2.53518C10.602 0.916981 12.006 0.01798 13.752 0H22.23C23.976 0.01798 25.398 0.916981 26.226 2.53518C26.5095 3.10156 26.9414 4.01387 27.3577 4.89318C27.4436 5.07463 27.5289 5.25466 27.612 5.42997L27.792 5.82553ZM26.478 12.7297C26.478 13.6287 27.198 14.3479 28.098 14.3479C28.998 14.3479 29.736 13.6287 29.736 12.7297C29.736 11.8307 28.998 11.0935 28.098 11.0935C27.198 11.0935 26.478 11.8307 26.478 12.7297ZM14.886 15.5166C15.732 14.6716 16.83 14.2221 18 14.2221C19.17 14.2221 20.268 14.6716 21.096 15.4986C21.924 16.3257 22.374 17.4225 22.374 18.5912C22.356 21.0005 20.412 22.9604 18 22.9604C16.83 22.9604 15.732 22.5108 14.904 21.6838C14.076 20.8567 13.626 19.7599 13.626 18.5912V18.5732C13.608 17.4405 14.058 16.3437 14.886 15.5166ZM22.986 23.5896C21.708 24.8662 19.944 25.6573 18 25.6573C16.11 25.6573 14.346 24.9202 12.996 23.5896C11.664 22.2411 10.926 20.4791 10.926 18.5912C10.908 16.7213 11.646 14.9592 12.978 13.6107C14.328 12.2622 16.11 11.525 18 11.525C19.89 11.525 21.672 12.2622 23.004 13.5928C24.336 14.9413 25.074 16.7213 25.074 18.5912C25.056 20.551 24.264 22.3131 22.986 23.5896Z" fill="#242424"/>
                                </svg>
                            </div>
                            <span class="technical-task-form-image-drag-area-header">Переместите изображение</span>
                            <span class="technical-task-form-image-drag-area-header">или</span>
                            <label class="technical-task-form-image-drag-area-input">
                                <input type="file" name="main-photo" accept="image/*"/>
                                <span class="technical-task-form-image-drag-area-button">нажмите для загрузки</span>
                            </label>

                        </div>
                    </div>
                    <div class="technical-task-form-constructor">
                        <div>
                            <div>
                                <?php echo $technical_task->selectTaxonomy('lp_1_tip_odezhdi', 'Тип одежды', true); ?>
                            </div>
                            <div>
                                <?php echo $technical_task->selectTaxonomy('lp_3_sferi_primeneniya', 'Назначение одежды', true); ?>
                            </div>
                            <div>
                                <?php echo $technical_task->selectTaxonomy('3_vid_odezhdi', 'Вид одежды', true); ?>
                            </div>
                            <div>
                                <?php echo $technical_task->selectTaxonomy('tsenovoi_segment', 'Ценовой сегмент', true); ?>
                            </div>
                            <div>
                                <?php echo $technical_task->selectTaxonomy('pa_dlya-kogo', 'Пол и возраст ЦА', true); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="technical-task-form">
                    <div>
                        <?php echo $technical_task->selectCustomTypeFields('postavka_syrya', 'Сырьё', true); ?>
                    </div>
                    <div>
                        <?php echo $technical_task->selectTaxonomy('lp_202_vid_tkani', 'Тип ткани'); ?>
                    </div>
                    <div>
                        <label for="fabric_structure">Состав ткани</label>
                        <input type="text" name="fabric_structure" class="technical-task-form-input" placeholder="Введите необходимое">
                    </div>
                    <div>
                        <label for="fabric_density">Плотность ткани</label>
                        <input type="text" name="fabric_density" class="technical-task-form-input" placeholder="Введите необходимое">
                    </div>
                </div>
                <div class="technical-task-form">
                    <div>
                        <?php echo $technical_task->selectCustomTypeFields('patterns', 'Лекала', true); ?>
                    </div>
                    <div>

                        <?php echo $technical_task->selectCustomTypeFields('pattern_carrier', 'Носитель лекал', true); ?>
                    </div>
                    <div>
                        <?php echo $technical_task->selectCustomTypeFields('prototype', 'Опытный образец'); ?>
                    </div>
                    <div>
                        <?php echo $technical_task->selectCustomTypeFields('additional_drawing', 'Дополнительное нанесение'); ?>
                    </div>
                </div>
                <div class="technical-task-form">
                    <div class="technical-task-form-price">
                        <div>
                            <label for="number_products">Количество изделий <span>*</span></label>
                            <input class="technical-task-form-input" type="number" min="1" name="number_products" placeholder="Введите значение" required>
                        </div>
                        <div>
                            <label for="unit_price">Цена за единицу <span>*</span></label>
                            <input class="technical-task-form-input" type="number" min="1" name="unit_price" placeholder="Введите значение" required>
                        </div>
                        <div>
                            <label for="final_budget">Итоговый бюджет</label>
                            <input class="technical-task-form-input default-choose" type="text" name="final_budget" value="- - - - - - -" disabled>
                        </div>
                    </div>
                    <div class="technical-task-form-contact-info">
                        <div>
                            <label for="contact_person">Контактное лицо <span>*</span></label>
                            <input type="text" name="contact_person" class="technical-task-form-input default-choose" placeholder="Введите контактное лицо для связи" required>
                        </div>
                        <div>
                            <label>Email <span>*</span></label>
                            <input type="email" name="email_person" class="technical-task-form-input default-choose" placeholder="Введите почту для связи" required>
                        </div>
                        <div>
                            <label>Telegram</label>
                            <input type="text" name="telegram_person" class="technical-task-form-input default-choose" placeholder="Введите telegram для связи">
                        </div>
                        <div>
                            <label>WhatsApp</label>
                            <input type="text" name="whatsapp_person" class="technical-task-form-input default-choose" placeholder="Введите WhatsApp для связи">
                        </div>
                        <div>
                            <label>Телефон</label>
                            <input type="text" name="telephone_person" class="technical-task-form-input default-choose" placeholder="Введите телефон для связи">
                        </div>
                        <div>
                            <?php echo $technical_task->selectCustomTypeFields('main_choose_contact', 'Способ связи с вами', true); ?>
                        </div>
                    </div>
                    <div class="technical-task-form-order-detail">
                        <div class="technical-task-form-order-detail-first">
                            <div>
                                <label for="clothes_sizes">Размер одежды <span>*</span></label>
                                <input type="text" name="clothes_sizes" class="technical-task-form-input" placeholder="Введите нужные размеры" required>
                            </div>
                            <div>
                                <label for="delivery_time">Срок поставки</label>
                                <input type="date" name="delivery_time" class="technical-task-form-input" placeholder="Выберите дату">
                            </div>
                            <div>
                                <?php echo $technical_task->selectTaxonomy('territorii', 'Регион поставки', true); ?>
                            </div>
                        </div>
                        <div>
                            <label>Комментарий к заказу</label>
                            <textarea rows="6" name="comment_the_order" placeholder="Ваш комментарий ..." class="technical-task-form-input"></textarea>
                        </div>
                    </div>

                </div>
                <div class="technical-task-form">
                    <div class="technical-task-form-publication-all-products"><?php $technical_task->getAllProductTechnicalTask();?></div>
                    <div class="technical-task-form-publication-on-platform">
                        <h2>Публикация на платформе</h2>
                        <div class="technical-task-form-publication-on-platform-card">
                            <div class="technical-task-form-publication-on-platform-card-products">
                                <ul><li class="default">Платных опций нет.</li></ul>
                            </div>
                            <div class="technical-task-form-publication-on-platform-card-total">
                                <p>Итого: <span>0 ₽</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="technical-task-form-btn-wrapper">
                    <button type="submit" class="technical-task-form-btn technical-task-form-btn-submit" style="display:none">
                        Опубликовать бесплатно
                    </button>

                    <button class="technical-task-form-btn technical-task-form-btn-back">
                        Назад
                    </button>

                    <button class="technical-task-form-btn technical-task-form-btn-next">
                        Вперед
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            let old_price, old_total, total_price = 0;

            /*
            *
            *  Next step
            *
            */
            $('.technical-task-form-btn.technical-task-form-btn-next').on('click', function(e) {
                let current_active_step = $('.multi-step-item.current');
                let form_step = $('.technical-task-form-wrapper').find('.technical-task-form.active');
                let fail = false;

                form_step.find('select, textarea, input' ).each(function(){
                    if( ! $( this ).prop( 'required' )){

                    } else {
                        if ( ! $( this ).val() ) {
                            fail = true;
                        }

                    }
                });
                if ( ! fail ) {

                    current_active_step.removeClass('current').addClass('activated').next().addClass('current');
                    form_step.removeClass('active').next('.technical-task-form').addClass('active').fadeIn();
                    console.log(current_active_step.index())
                    if (current_active_step.index() === 3) {
                        e.preventDefault();
                        $(this).hide();
                        $('button[type="submit"]').css('display', 'flex');
                    }
                    $('.technical-task-form-btn.technical-task-form-btn-back').css('display', 'flex');
                }
            });

            /*
            *
            * Prev step
            *
            */
            $('.technical-task-form-btn.technical-task-form-btn-back').on('click', function(e) {
                e.preventDefault();
                let current_active_step = $('.multi-step-item.current');
                let form_step = $('.technical-task-form-wrapper').find('.technical-task-form.active');
                current_active_step.removeClass('current').prev().removeClass('activated').addClass('current');
                form_step.removeClass('active').prev().addClass('active');
                if (current_active_step.index() === 1) {
                    $(this).css('display', 'none');
                }
                if (current_active_step.index() === 4) {
                    $('button[type="submit"]').css('display', 'none');
                    $('.technical-task-form-btn.technical-task-form-btn-next').css('display', 'flex');
                }
            });

            $('.technical-task-form input[name="number_products"], .technical-task-form input[name="unit_price"]')
                .keyup(function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    let number_products = $('.technical-task-form input[name="number_products"]').val();
                    let unit_price = $('.technical-task-form input[name="unit_price"]').val();
                    let final_price = number_products * unit_price;
                    let final_budget = $('.technical-task-form input[name="final_budget"]');
                    jQuery({ Counter: old_price }).animate({ Counter: final_price }, {
                        duration: 1000,
                        easing: 'swing',
                        step: function (now) {
                            final_budget.val(Math.ceil(now) + ' ₽');
                        }
                    });
                    old_price = final_price;
                });

            function checkForm(val) {
                let valid = true;
                $("#" + val + " input:required").each(function () {
                    if ($(this).val() === "") {
                        $(this).addClass("is-invalid");
                        valid = false;
                    } else {
                        $(this).removeClass("is-invalid");
                    }
                });
                return valid;
            }


            $('.technical-task-form-publication-options-switch-input').on('click', function () {
                let list = $(".technical-task-form-publication-on-platform-card-products ul");
                let text = $(this).closest('.technical-task-form-publication-options-product').find('h2').text();
                let price = $(this).closest('.technical-task-form-publication-options-price').find('span').text();
                let convert = price.replace(/[^0-9]/gi, '');
                let final_number = parseInt(convert, 10);
                if ($(this).is(':checked')) {
                    if($(this).attr("name") === 'vip_post_technical_task')
                        $(this).closest('.technical-task-form-publication-options').append('<div class="vip-post-date"><div class="vip-post active">на 7 дней</div><div class="vip-post">на 31 день</div></div>')
                    list.append('<li class="'+ $(this).attr("name") + '"><div class="title">' + text + '</div><span>' + price + '</span></li>');
                    total_price += final_number;
                } else {
                    if($(this).attr("name") === 'vip_post_technical_task')
                        $(this).closest('.technical-task-form-publication-options').find('.vip-post-date').remove();
                    list.find('.' + $(this).attr("name")).remove();
                    total_price -= final_number;
                }
                checkList();
                jQuery({ Counter: old_total }).animate({ Counter: total_price }, {
                    duration: 1000,
                    easing: 'swing',
                    step: function (now) {
                        $('.technical-task-form-publication-on-platform-card-total').find('span').text(Math.ceil(now) + ' ₽');
                    }
                });
                old_total = total_price;
            });

            $(document).on('click', '.vip-post-date .vip-post', function() {
                $(this).addClass('active').siblings().removeClass('active');
            });

            const checkList = () => {
                let list = $(".technical-task-form-publication-on-platform-card-products ul");
                let button = $('.technical-task-form-btn-submit');
                if(list.has("li").length === 0) {
                    list.append('<li class="default">Платных опций нет.</li>');
                    button.text('Опубликовать бесплатно');
                }
                else {
                    list.find('.default').remove();
                    button.text('Оплатить и опубликовать');
                }
            }

        });
    </script>
    <?php
}