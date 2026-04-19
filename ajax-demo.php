<?php
/**
 * Plugin Name:       Ajax Demo
 * Plugin URI:        https://yourwebsite.com
 * Description:       Load Ajax For Front End and Backend
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            Iftiar Hossain
 * Author URI:        https://yourwebsite.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ajax-demo
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'AJDM_VERSION', '1.0.0' );
define( 'AJDM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AJDM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class Ajax_Demo {
    function __construct()
    {
        $this -> includes_resources();
        $this -> init();
        add_action('wp_enqueue_scripts', [$this, 'load_assests']);
        add_action('admin_enqueue_scripts', [$this, 'load_assests']);
    }

    function includes_resources(){
        require_once(AJDM_PLUGIN_DIR . "includes/class-shortcode-button.php");
        require_once(AJDM_PLUGIN_DIR . "includes/class-currency-widget.php");
        require_once(AJDM_PLUGIN_DIR . "includes/class-contact-form.php");
    }

    function init(){
        new Ajax_Demo_Shortcode_Button();
        new AJDM_Currency_Widget();
        new AJDM_Contact_Form();
    }

    function load_assests(){
        wp_enqueue_script('ajdm-main', AJDM_PLUGIN_URL . "assets/js/ajax-demo.js", [], time(), true);
        $admin_ajax_url = admin_url('admin-ajax.php');
        wp_localize_script('ajdm-main', 'ajdm', ['ajax_url' => $admin_ajax_url]);
    }
}

new Ajax_Demo();