<?php


use Elementor\Widget_Base;

class Elementor_Status_Order_Widget extends Widget_Base
{

    public function get_name(): string
    {
        return 'tpktrade_statusorder_info';
    }

    public function get_title(): string
    {
        return esc_html('Статус заказа для ТЗ');
    }

    public function get_icon(): string
    {
        return 'eicon-post-info';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_keywords(): array
    {
        return ['card', 'information', 'info', 'statusorder', 'status', 'order'];
    }

    protected function render()
    {
        global $post;

        if( get_field('status_zakaza')):
            if( get_field('status_zakaza') == 'Открыто' ) {
                echo '<span class="status__order status__order-open">' . get_field("status_zakaza") . '</span>';
            } else {
                echo '<span class="status__order status__order-close">' . get_field("status_zakaza") . '</span>';
            }
        endif;
    }

    protected function content_template()
    { ?>

        <span class="status__order status__order-close">Закрыто</span>

        <?php
    }

}