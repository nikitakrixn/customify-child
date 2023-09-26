<?php

class TechnicalTask
{

    protected $post_title = 'Техническое задание';
    protected $post_content = 'Not null';
    protected $post_type = '_';
    protected $post_status = 'publish';

    private $message = [];

    /* Message Set Method */
    public function setMessage($key,$val)
    {
        $this->message[$key] = $val;
    }

    /* Message Get Method */
    public function getMessage()
    {
        return $this->message['msg'];
    }

    /**
     * @return mixed
     */
    public function getFuturePostId()
    {
        global $wpdb;

        $query = "SELECT ID FROM $wpdb->posts WHERE post_type = '_' ORDER BY ID DESC LIMIT 1,1";

        $result = $wpdb->get_results($query);
        $row = $result[0];
        $id = $row->ID;

        return $id;
    }

    /**
     * @param $taxonomy
     * @param $name
     * @param bool $required
     * @return string
     */
    public function selectTaxonomy ($taxonomy, $name, bool $required = false): string
    {
        $terms = get_terms($taxonomy, [
            'hide_empty' => false,
        ] );
        $render = $this->getStr($required, $taxonomy, $name);
        foreach ($terms as $term) {
            $render .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
        }
        $render .= '</select>';
        return $render;
    }

    public function selectCustomTypeFields ($field, $name, $required = false): string
    {
        $raw = acf_get_raw_field($field);
        $render = $this->getStr($required, $field, $name);
        foreach ($raw['choices'] as $term) {
            $render .= '<option value="' . $term . '">' . $term . '</option>';
        }
        $render .= '</select>';
        return $render;
    }

    /**
     * @param $required
     * @param $field
     * @param $name
     * @return string
     */
    public function getStr($required, $field, $name): string
    {
        $span = '';
        $req = '';
        if ($required) {
            $span = ' <span>*</span>';
            $req = 'required';
        }

        $render = '<label for="' . $field . '">' . $name . $span . '</label>';
        $render .= '<select name="' . $field . '" ' . $req . '>';
        $render .= '<option value="" hidden="">Нажмите для выбора</option>';
        return $render;
    }

    /**
     * @param $name
     * @param $slug
     * @param $price
     * @return void
     */
    public function createTechnicalTaskProduct($name, $slug, $price){
        $product = new WC_Product_Simple();

        $product->set_name( $name );

        $product->set_slug( $slug );

        $product->set_virtual( true );

        $product->set_regular_price( $price );

        $product->set_category_ids( array( 46263 ) );

        $product->set_short_description( '<p>Товар для нового технического задания</p>' );

        $product->set_sold_individually( true );

        $product->save();
    }

    public function renderProduct($product_id): string
    {
        $product = wc_get_product( $product_id );
        $product_price = $product->get_price() . ' ' . get_woocommerce_currency_symbol();
        $product->get_slug();

        $render = '<div class="technical-task-form-publication-options"><div class="technical-task-form-publication-options-product">';
        $render .= '<h2>' . $product->get_title() . '</h2>';
        $render .= '<div class="technical-task-form-publication-options-price">';
        $render .= '<span class="technical-task-form-publication-options-switch-label">' . $product_price . '</span>';
        $render .= '<label class="technical-task-form-publication-options-switch">';
        $render .= '<input type="checkbox" class="technical-task-form-publication-options-switch-input" data-product-id="' . $product->get_id() . '" name="'. $product->get_slug() . '">';
        $render .= '<div class="technical-task-form-publication-options-switch-slider"></div></label></div></div></div>';
        return $render;
    }

    public function getAllProductTechnicalTask()
    {
        $args = array(
            'limit' => -1,
            'status' => 'publish',
            'return' => 'ids',
            'category' => array( 'new_technical_task' ),
        );
        $products = wc_get_products( $args );

        foreach ($products as $product) {
            echo $this->renderProduct($product);
        }
    }

    public function firstCreating(){

        $this->createTechnicalTaskProduct('Выделение объявления рамкой и закрепление вашего ТЗ вверху общего списка', 'vip_post_technical_task', '500');

        $this->createTechnicalTaskProduct('Подбор рекомендуемых поставщиков и рассылка запросов на оценку', 'select_recommended_suppliers', '250');

        $this->createTechnicalTaskProduct('Персональная консультация', 'personal_consultation', '850');
        
        $this->createTechnicalTaskProduct('Отправить заявку рассылкой по базе всех фабрик', 'send_task_for_all__mail', '1500');
    }

}

if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_843f3dfc',
        'title' => 'Новое Техническое задание',
        'fields' => array(
            array(
                'key' => 'field_24c68c82',
                'label' => 'Фотографии',
                'name' => 'product_photo',
                'type' => 'gallery',
                'required' => 1,
                'return_format' => 'array',
                'preview_size' => 'medium_large',
                'insert' => 'append',
                'library' => 'uploadedTo'
            ),
            array(
                'key' => 'field_8fc4813e',
                'label' => 'Тип одежды',
                'name' => 'Clothing_type',
                'type' => 'taxonomy',
                'instructions' => '',
                'required' => 1,
                'admin_column_enabled' => 0,
                'admin_column_post_types' => '',
                'admin_column_taxonomies' => '',
                'taxonomy' => 'lp_1_tip_odezhdi',
                'field_type' => 'select',
                'return_format' => 'id',
                'default_value' => false,
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_6abd8e9a',
                'label' => 'Назначения одежды',
                'name' => 'appointments',
                'type' => 'taxonomy',
                'required' => 1,
                'taxonomy' => 'lp_3_sferi_primeneniya',
                'field_type' => 'select',
                'add_term' => 0,
                'save_terms' => 1,
                'load_terms' => 1,
                'return_format' => 'id',
                'multiple' => 0,
                'default_value' => false,
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_eb3001da',
                'label' => 'Пол и возраст ЦА',
                'name' => 'gender_and_age_ca',
                'type' => 'acfe_taxonomy_terms',
                'required' => 1,
                'taxonomy' => array(
                    0 => 'pa_dlya-kogo',
                ),
                'allow_terms' => array(
                    0 => 'all_pa_dlya-kogo',
                ),
                'field_type' => 'select',
                'return_format' => 'name',
                'default_value' => false,
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_6285c90e',
                'label' => 'Ценовой сегмент',
                'name' => 'price_segment',
                'type' => 'taxonomy',
                'required' => 1,
                'taxonomy' => 'tsenovoi_segment',
                'field_type' => 'select',
                'return_format' => 'object',
                'default_value' => false,
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_42a1c82d',
                'label' => 'Есть ли Сырье?',
                'name' => 'raw_material',
                'type' => 'true_false',
                'required' => 1,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_d7bae9d6',
                'label' => 'Тип ткани',
                'name' => 'fabric_type',
                'type' => 'text',
                'required' => 0,
                'placeholder' => 'Введите тип ткани',
            ),
            array(
                'key' => 'field_ddddb1a9',
                'label' => 'Состав ткани',
                'name' => 'fabric_structure',
                'type' => 'text',
                'required' => 0,
                'placeholder' => 'Введите состав ткани',
            ),
            array(
                'key' => 'field_1155c2fd',
                'label' => 'Плотность ткани',
                'name' => 'fabric_density',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => 0,
                'placeholder' => 'Введите плотность',
            ),
            array(
                'key' => 'field_223c79ce',
                'label' => 'Поставка сырья',
                'name' => 'supply_raw_materials',
                'aria-label' => '',
                'type' => 'checkbox',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_42a1c82d',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'choices' => array(
                    'Давальческое сырье' => 'Давальческое сырье',
                    'Требуется подбор и закупка тканей и фурнитуры' => 'Требуется подбор и закупка тканей и фурнитуры',
                ),
                'return_format' => 'value',
                'allow_custom' => 0,
                'layout' => 'vertical',
                'toggle' => 0,
                'save_custom' => 0,
            ),
            array(
                'key' => 'field_f77b47c8',
                'label' => 'Лекала',
                'name' => 'patterns',
                'type' => 'select',
                'required' => 1,
                'invisible' => 0,
                'only_front' => 0,
                'choices' => array(
                    'Лекале есть' => 'Лекале есть',
                    'Требуется разработка лекал' => 'Требуется разработка лекал',
                    'Требуется градация лекал по размерам' => 'Требуется градация лекал по размерам',
                    'Требуется раскладка лекал' => 'Требуется раскладка лекал',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_3aa69bbd',
                'label' => 'Носитель Лекал',
                'name' => 'pattern_carrier',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_f77b47c8',
                            'operator' => '==',
                            'value' => 'Лекале есть',
                        ),
                    ),
                ),
                'choices' => array(
                    'В цифровом виде' => 'В цифровом виде',
                    'В картонном виде' => 'В картонном виде',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_9309e3986',
                'label' => 'Опытный образец',
                'name' => 'prototype',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'choices' => array(
                    'Требуется пошив образца' => 'Требуется пошив образца',
                    'Предоставить схожий образец' => 'Предоставить схожий образец',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_9309e398',
                'label' => 'Доп. нанесение',
                'name' => 'additional_drawing',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'choices' => array(
                    'Нанесение логотипа' => 'Нанесение логотипа',
                    'Нанесение принта на изделие' => 'Нанесение принта на изделие',
                    'Окрашивание ткани' => 'Окрашивание ткани',
                    'Нанесение принта на ткань' => 'Нанесение принта на ткань',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_094c508e',
                'label' => 'Размеры одежды',
                'name' => 'clothes_sizes',
                'type' => 'text',
                'required' => 1,
                'conditional_logic' => 0,
                'placeholder' => 'Введите нужные размеры',
            ),
            array(
                'key' => 'field_ee7827c6',
                'label' => 'Регион поставки',
                'name' => 'delivery_region',
                'type' => 'acfe_taxonomy_terms',
                'required' => 1,
                'conditional_logic' => 0,
                'taxonomy' => array(
                    0 => 'territorii',
                ),
                'allow_terms' => array(
                    0 => 'all_territorii',
                ),
                'field_type' => 'select',
                'return_format' => 'name',
                'allow_null' => 1,
                'placeholder' => 'Выберите регион доставки',
                'multiple' => 0,
                'save_terms' => 1,
                'load_terms' => 1,
            ),
            array(
                'key' => 'field_92f723ab',
                'label' => 'Срок поставки',
                'name' => 'delivery_time',
                'type' => 'date_picker',
                'required' => 0,
                'conditional_logic' => 0,
                'display_format' => 'd/m/Y',
                'return_format' => 'd/m/Y',
                'first_day' => 1,
                'placeholder' => 'Выберите дату поставки',
                'no_weekends' => 0,
            ),
            array(
                'key' => 'field_e9ec9f3d',
                'label' => 'Комментарий к заказу',
                'name' => 'comment_the_order',
                'aria-label' => '',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'default_value' => '',
                'placeholder' => 'Введите ваш комментарий к заказу',
            ),
            array(
                'key' => 'field_c5f7d6f4',
                'label' => 'Документация',
                'name' => 'documentation',
                'type' => 'file',
                'instructions' => 'Загрузите (если есть) техническую документацию по изделию, лекала (архив 1 файл)',
                'required' => 0,
                'invisible' => 0,
                'only_front' => 0,
                'uploader' => '',
                'admin_column_enabled' => 0,
                'admin_column_post_types' => '',
                'admin_column_taxonomies' => '',
                'return_format' => 'array',
                'button_text' => 123123,
                'upload_folder' => 'documentation/{year}/{month}/',
                'button_label' => 'Добавить файл',
                'multiple' => 1,
                'min' => '',
                'max' => '',
                'min_size' => '',
                'max_size' => '',
                'mime_types' => '',
                'library' => 'all',
            ),
            array(
                'key' => 'field_a9c65d1c',
                'label' => 'Контактное лицо',
                'name' => 'contact_person',
                'type' => 'text',
                'required' => 1,
                'conditional_logic' => 0,
                'placeholder' => 'Введите контактное лицо',
            ),
            array(
                'key' => 'field_cd78484e',
                'label' => 'Email',
                'name' => 'email_person',
                'type' => 'email',
                'required' => 1,
                'conditional_logic' => 0,
                'placeholder' => 'Введите email для связи',
            ),
            array(
                'key' => 'field_da694ede',
                'label' => 'Telegram',
                'name' => 'telegram_person',
                'type' => 'text',
                'required' => 1,
                'conditional_logic' => 0,
                'placeholder' => 'Введите telegram для связи',
            ),
            array(
                'key' => 'field_a34b066b',
                'label' => 'Телефон',
                'name' => 'telephone_person',
                'type' => 'acfe_phone_number',
                'required' => 0,
                'conditional_logic' => 0,
                'countries' => array(
                    0 => 'ru',
                ),
                'preferred_countries' => '',
                'default_country' => 'ru',
                'geolocation' => 0,
                'native' => 0,
                'national' => 0,
                'dropdown' => 0,
                'dial_code' => 0,
                'default_value' => '',
                'placeholder' => '',
                'return_format' => 'array',
            ),
            array(
                'key' => 'field_89982892',
                'label' => 'WhatsApp',
                'name' => 'whatsapp_person',
                'type' => 'text',
                'required' => 1,
                'conditional_logic' => 0,
                'placeholder' => 'Введите WhatsApp для связи',
            ),
            array(
                'key' => 'field_7c200e51',
                'label' => 'Способ связи с вами',
                'name' => 'main_choose_contact',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'choices' => array(
                    'Звонок' => 'Звонок',
                    'Почта' => 'Почта',
                    'Telegram' => 'Telegram',
                    'WhatsApp' => 'WhatsApp',
                ),
                'default_value' => false,
                'return_format' => 'value',
                'placeholder' => 'Нажмите для выбора',
            ),
            array(
                'key' => 'field_2ee97b34',
                'label' => 'Количество изделий',
                'name' => 'number_products',
                'type' => 'number',
                'required' => 1,
                'conditional_logic' => 0,
                'invisible' => 0,
                'only_front' => 0,
                'readonly' => 0,
                'hide_admin' => 0,
                'acfe_field_group_condition' => 0,
                'admin_column_enabled' => 0,
                'admin_column_post_types' => '',
                'admin_column_taxonomies' => '',
                'default_value' => '',
                'min' => '',
                'max' => '',
                'placeholder' => 'Введите значение',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_29bac169',
                'label' => 'Цена за единицу',
                'name' => 'unit_price',
                'type' => 'number',
                'required' => 1,
                'conditional_logic' => 0,
                'invisible' => 0,
                'only_front' => 0,
                'readonly' => 0,
                'hide_admin' => 0,
                'acfe_field_group_condition' => 0,
                'admin_column_enabled' => 0,
                'admin_column_post_types' => '',
                'admin_column_taxonomies' => '',
                'default_value' => '',
                'min' => '',
                'max' => '',
                'placeholder' => 'Введите значение',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
            array(
                'key' => 'field_12205f80',
                'label' => 'Итоговый бюджет',
                'name' => 'final_budget',
                'type' => 'number',
                'required' => 0,
                'conditional_logic' => 0,
                'invisible' => 0,
                'only_front' => 0,
                'readonly' => 1,
                'hide_admin' => 0,
                'acfe_field_group_condition' => 0,
                'admin_column_enabled' => 0,
                'admin_column_post_types' => '',
                'admin_column_taxonomies' => '',
                'default_value' => '',
                'min' => '',
                'max' => '',
                'placeholder' => '',
                'step' => '',
                'prepend' => '',
                'append' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => '_',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
    ));
endif;