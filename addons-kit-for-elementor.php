<?php
/*
 * Plugin Name: Addons Kit
 * Plugin URI: https://bdwebninja.com
 * Description: Addons Kit For Elementor widget startup
 * Version: 1.0.0
 * Author: TALIB
 * Author URI: https://talib.netlify.com
 * License: GPLv2 or later
 * Text Domain: addons-kit
 * Domain Path: /languages/
 */

namespace Elementor;

if ( !defined( 'ABSPATH' ) ) {
    exit( __( 'Direct Access is not allowed', 'addons-kit' ) );
}

final class AddonsKit {

    const VERSION                   = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION       = '7.0';

    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    public function __construct() {
        add_action( 'plugins_loaded', [$this, 'init'] );
    }

    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'addons-kit' ),
            '<strong>' . esc_html__( 'Addons Kit', 'addons-kit' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'addons-kit' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'addons-kit' ),
            '<strong>' . esc_html__( 'Addons Kit', 'addons-kit' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'addons-kit' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'addons-kit' ),
            '<strong>' . esc_html__( 'Addons Kit', 'addons-kit' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'addons-kit' ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function init() {
        load_plugin_textdomain( 'addons-kit', false, plugin_dir_path( __FILE__ ) . '/languages' );

// Check if Elementor installed and activated
        if ( !did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_missing_main_plugin'] );
            return;
        }

// Check for required Elementor version
        if ( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_minimum_elementor_version'] );
            return;
        }

// Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [$this, 'admin_notice_minimum_php_version'] );
            return;
        }

        // Register Widget
        add_action( 'elementor/widgets/widgets_registered', [$this, 'init_widgets'] );
        add_action( 'elementor/elements/categories_registered', [$this, 'register_new_category'] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [$this, 'widget_assets_enqueue'] );
    }

    /**
     * !Register Categories
     */
    public function register_new_category( $elements_manager ) {
        $elements_manager->add_category(
            'addons-kit',
            [
                'title' => __( 'Addons Kit', 'addons-kit' ),
            ]
        );
    }

    /**
     * !enqueue assets
     */
    public function widget_assets_enqueue() {
        // demo widget
        wp_enqueue_style( 'demo-css', plugin_dir_url( __FILE__ ) . 'widgets/demo/assets/css/style.css', null, time(), 'all' );
        wp_enqueue_script( 'demo-js', plugin_dir_url( __FILE__ ) . 'widgets/demo/assets/js/script.js', ['jquery'], time(), true );
    }

    /**
     * ! Widgets Init
     */
    public function init_widgets() {

        require_once __DIR__ . '/widgets/demo/widget.php';
        Plugin::instance()->widgets_manager->register_widget_type( new Demo_Widget() );
    }

}

AddonsKit::instance();
