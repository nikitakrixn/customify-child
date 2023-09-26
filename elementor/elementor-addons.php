<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

final class Tpktrade_Elementor_Addons {
	const VERSION = '1.0.0';
	const MINIMUM_ELEMENTOR_VERSION = '2.5.11';
	const MINIMUM_PHP_VERSION = '7.4';

	protected static $instance = null;

	/**
	 * @return null
	 */
	public static function getInstance() {
		if ( ! isset( Tpktrade_Elementor_Addons::$instance ) ) {
			Tpktrade_Elementor_Addons::$instance = new Tpktrade_Elementor_Addons;
		}

		return Tpktrade_Elementor_Addons::$instance;
	}

	protected function __construct() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return;
		}


		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

			return;
		}

		// Register Widget Styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
		// Register Widget Script
//        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
		// Register Widget
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
//            // Register Admin Script
//            add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
	}

	public function register_widgets() {
		\Elementor\Plugin::instance()->widgets_manager->register( new \Elementor\Tpktrade_About_Info() );
	}

	public function widget_styles() {
		wp_enqueue_style( 'card-style', get_template_directory() . '/elementor/assets/css/card.css' );
	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.' ),
			'<strong>' . esc_html__( 'Elementor Tpktrade addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor' ) . '</strong>'
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.' ),
			'<strong>' . esc_html__( 'Elementor Tpktrade addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
		$message = sprintf(
		/* 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.' ),
			'<strong>' . esc_html__( 'Elementor Tpktrade addons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}


add_action( 'init', 'tpktrade_elementor_init' );
function tpktrade_elementor_init() {
	Tpktrade_Elementor_Addons::getInstance();
}