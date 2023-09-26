<?php


class Elementor_Filter_TZ extends \Elementor\Widget_Base
{

    public function get_name(): string
    {
        return 'tpktrade_filter_tz';
    }

    public function get_title(): string
    {
        return esc_html('Фильтр для карточек ТЗ');
    }

    public function get_icon(): string
    {
        return 'eicon-taxonomy-filter';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_style_depends()
    {
        return ['filter-widget-style'];
    }

    public function get_script_depends()
    {
        return ['filter-widget-script'];
    }

    public function register_controls()
    {
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Стиль'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_options',
            [
                'label' => esc_html__('Стили заголовка фильтра'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .filter__title'
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__('Цвет заголовка'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter__title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'item_options',
            [
                'label' => esc_html__('Настройка стилей фильтра'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typography',
                'selector' => '{{WRAPPER}} .filter__body label *'
            ]
        );

        $this->add_control(
            'filter_color',
            [
                'label' => esc_html__('Цвет пунктов'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter__body *' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $types = get_terms('lp_1_tip_odezhdi');
        $scopes = get_terms('lp_3_sferi_primeneniya');
        $regions = get_terms('territorii');
        $docs = acf_get_field('razrabotka_dokumentaczii');
        $raw = acf_get_raw_field('postavka_syrya');
        $status_order = acf_get_field('status_zakaza');

        $filters = $_GET['filter'];


        ?>
<!--        <div class="elementor-widget-button mobile_show">-->
<!--            <button class="elementor-button-link elementor-button elementor-size-sm filter-btn" id="show_filter">-->
<!--                Показать фильтр-->
<!--            </button>-->
<!--        </div>-->

        <form method="get" class="filter_form">
            <div class="filter-wrap">
                <div class="filter collapse" id="filter">
                    <div class="filter__group">
                        <div class="filter__title">Тип одежды:</div>
                        <div class="filter__body filter_height">
                            <?php foreach ($types as $item) {
                                ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filter[type][]"
                                        <?php
                                        if ($filters['type']) {
                                            if (in_array($item->term_id, $filters['type'])) {
                                                echo 'checked';
                                            }
                                        } ?>

                                           value="<?php echo $item->term_id ?>"><i></i>
                                    <span class="align-self-center"><?php echo $item->name ?></span>
                                </label>
                                <?php
                            }

                            ?>
                        </div>
                    </div>
                    <div class="filter__group">
                        <div class="filter__title">Сфера применения:</div>
                        <div class="filter__body filter_height">
                            <?php foreach ($scopes as $item) {
                                ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filter[scope][]"
                                        <?php
                                        if ($filters['scope']) {
                                            if (in_array($item->term_id, $filters['scope'])) {
                                                echo 'checked';
                                            }
                                        } ?>
                                           value="<?php echo $item->term_id ?>"><i></i>
                                    <span class="align-self-center"><?php echo $item->name ?></span>
                                </label>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class=" filter__group">
                        <div class="filter__title">Срок изготовления:</div>
                        <div class="filter__body filter_date">
                            <div class="left">
                                <label>
                                    <span>от</span>
                                    <input type="date"
                                        <?php
                                        if ($filters['dateMin']) {
                                            echo 'value="' . $filters['dateMin'] . '"';
                                        } ?>
                                           name="filter[dateMin]"/>
                                </label>
                            </div>
                            <div class="right">
                                <label>
                                    <span>до</span>
                                    <input type="date"
                                        <?php
                                        if ($filters['dateMax']) {
                                            echo 'value="' . $filters['dateMax'] . '"';
                                        } ?>
                                           name="filter[dateMax]"/>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="filter__group">
                        <div class="filter__title">Регион:</div>
                        <div class="filter__body filter_height">
                            <?php foreach ($regions as $item) {
                                ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filter[regions][]"
                                        <?php
                                        if ($filters['regions']) {
                                            if (in_array($item->term_id, $filters['regions'])) {
                                                echo 'checked';
                                            }
                                        } ?>
                                           value="<?php echo $item->term_id ?>"><i></i>
                                    <span class="align-self-center"><?php echo $item->name ?></span>
                                </label>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="filter__group">
                        <div class="filter__title">Документация:</div>
                        <div class="filter__body filter_height">
                            <?php foreach ($docs['choices'] as $doc) { ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filter[docs][]"
                                        <?php
                                        if ($filters['docs']) {
                                            if (in_array($doc, $filters['docs'])) {
                                                echo 'checked';
                                            }
                                        } ?>
                                           value="<?php echo $doc ?>"><i></i>
                                    <span class="align-self-center"><?php echo $doc ?></span>
                                </label>
                            <?php }
                            ?>
                        </div>
                    </div>

                    <div class="filter__group">
                        <div class="filter__title">Поставка сырья:</div>
                        <div class="filter__body filter_height">
                            <?php foreach ($raw['choices'] as $item) { ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filter[raw][]"
                                        <?php
                                        if ($filters['raw']) {
                                            if (in_array($item, $filters['raw'])) {
                                                echo 'checked';
                                            }
                                        } ?>
                                           value="<?php echo $item ?>"><i></i>
                                    <span class="align-self-center"><?php echo $item ?></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="filter__group">
                        <div class="filter__title">Статус заявки:</div>
                        <div class="filter__body filter_height">
                            <?php foreach ($status_order['choices'] as $status_order) { ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" name="filter[status_zakaza][]"
                                        <?php
                                        if ($filters['status_zakaza']) {
                                            if (in_array($status_order, $filters['status_zakaza'])) {
                                                echo 'checked';
                                            }
                                        } ?>
                                           value="<?php echo $status_order ?>"><i></i>
                                    <span class="align-self-center"><?php echo $status_order ?></span>
                                </label>
                            <?php }
                            ?>
                        </div>
                    </div>


                </div>

                <div class="filter__group filter_buttons">
                    <div class="elementor-widget-button">
                        <button class="elementor-button-link elementor-button elementor-size-sm filter-btn">
                            Применить фильтр
                        </button>
                    </div>
                    <button type="reset" class="show" id="reset">Сбросить фильтр</button>
                </div>
        </form>
        <?php
    }
}

//Filter
function my_query_by_filter_tz($query)
{
    $filters = $_GET['filter'];
    $tax_query = $query->get('meta_query');
    $meta_query = [
//        'relation' => 'AND'
    ];
    if (!$tax_query) {
        $tax_query = [];
    }
    $tax_query[] = [
        'relation' => 'OR',
    ];


    if ($filters) {
        if ($filters['type']) {


            $tax_query[][] = [
                'taxonomy' => 'lp_1_tip_odezhdi',
                'field' => 'term_taxonomy_id',
                'terms' => $filters['type'],
            ];
        }
        if ($filters['regions']) {
            $tax_query[][] = [
                'taxonomy' => 'territorii',
                'field' => 'term_taxonomy_id',
                'terms' => $filters['regions'],
            ];
        }

        if ($filters['scope']) {
            $tax_query[][] = [
                'taxonomy' => 'lp_3_sferi_primeneniya',
                'field' => 'term_taxonomy_id',
                'terms' => $filters['scope'],
            ];
        }

        if ($filters['docs']) {
            $meta_query[] = [
                'key' => 'razrabotka_dokumentaczii',
                'value' => $filters['docs'],
                'compare' => 'LIKE'
            ];
        }

        if ($filters['raw']) {
            $meta_query[] = [
                'key' => 'postavka_syrya',
                'value' => $filters['raw'],
                'compare' => 'LIKE'
            ];
        }

        if ($filters['status_zakaza']) {
            $meta_query[] = [
                'key' => 'status_zakaza',
                'value' => $filters['status_zakaza']
            ];
        }

        if ($filters['dateMin'] or $filters['dateMax']) {
            $newMinDate = date("Ymd", strtotime($filters['dateMin']));
            $newMaxDate = date("Ymd", strtotime($filters['dateMax']));
            $meta_query[] = [
                'key' => 'srok_postavki',
                'value' => [$newMinDate, $newMaxDate],
                'compare' => 'BETWEEN'
            ];
        }


        $query->set('tax_query', $tax_query);
        $query->set('meta_query', $meta_query);

    }
}

add_action('elementor/query/tz_filter', 'my_query_by_filter_tz');

