<?php

namespace Elementor;

class Tpktrade_About_Info extends Widget_Base {

	public function get_name() {
		return 'tpktrade_cart_info';
	}

	public function get_title() {
		return esc_html( 'Данные для карточки ТЗ' );
	}

	public function get_icon(): string {
		return 'eicon-post-info';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	protected function render() {
		?>
        <ul class="card_list">
			<?php
			if ( get_field( "Tipodezhdy_lead" ) ):
				if ( is_numeric( get_field( 'Tipodezhdy_lead' ) ) ) {
					echo '<li class="card_item"><span>Тип одежды: </span> ' . get_term( get_field( "Tipodezhdy_lead" ) )->name . '</li>';
				} else {
					echo '<li class="card_item"><span>Тип одежды: </span> ' . implode( ',', get_field( 'Tipodezhdy_lead' ) ) . '</li>';
				}
			endif;
			if ( get_field( "sfera_primeneniya_lead" ) ):
				if ( is_numeric( get_field( "sfera_primeneniya_lead" ) ) ) {
					echo '<li class="card_item"><span>Сфера применения: </span> ' . get_term( get_field( "sfera_primeneniya_lead" ) )->name . '</li>';
				} else {
					echo '<li class="card_item"><span>Сфера применения: </span> ' . implode( ',', get_field( "sfera_primeneniya_lead" ) ) . '</li>';
				}
			endif;
			if ( get_field( "srok_postavki" ) ):
				echo '<li class="card_item"><span>Срок изготовления: </span> ' . get_field( 'srok_postavki' ) . '</li>';
			endif;
			if ( get_field( "region_dostavki" ) ):
				echo '<li class="card_item"><span>Регион: </span> ' . implode( ',', get_field( "region_dostavki" ) ) . '</li>';
			endif;
			if ( get_field( "razrabotka_dokumentaczii" ) ):
				echo '<li class="card_item"><span>Документация: </span> ' . implode( ',', get_field( 'razrabotka_dokumentaczii' ) ) . '</li>';
			endif;
			if ( get_field( "postavka_syrya" ) ):
				echo '<li class="card_item"><span>Сырье: </span> ' . implode( ',', get_field( 'postavka_syrya' ) ) . '</li>';
			endif;
			if ( get_field( "kolichestvo_izdelij_order" ) ):
				echo '<li class="card_item"><span>Количество: </span> ' . get_field( 'kolichestvo_izdelij_order' ) . '</li>';
			endif;
			if ( get_field( "kolichestvo_izdelij_order" ) and get_field( "planovyj_byudzhet_order" ) ):
				echo '<li class="card_item"><span>Цена за единицу: </span> ' . round( ( preg_replace( '/[^0-9.]+/', '', explode( '-', get_field( "planovyj_byudzhet_order" ) )[0] ) / preg_replace( '/[^0-9.]+/', '', get_field( "kolichestvo_izdelij_order" ) ) ), 2 ) . ' руб.</li>';
			endif;
			if ( get_field( "planovyj_byudzhet_order" ) ):
				echo '<li class="card_item"><span>Сумма: </span> ' . get_field( 'planovyj_byudzhet_order' ) . ' руб.</li>';
			endif;
			?>
        </ul>
		<?php
	}
}