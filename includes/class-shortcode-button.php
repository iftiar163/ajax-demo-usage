<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Ajax_Demo_Shortcode_Button{
    function __construct()
    {
        add_shortcode('ajax_demo_button', [$this, 'render_button']);
        add_action('wp_ajax_demo', [$this, 'demo_call']);
        add_action('wp_ajax_nopriv_demo', [$this, 'demo_call']);
    }

    function render_button(){
        ob_start();
        ?>
        <style>
            #ajax-demo-btn { background: #007cba; color: white; padding: 10px 15px; border: none; border-radius: 3px; cursor: pointer; }
            #ajax-demo-btn:hover { background: #005a87; }
            #ajax-demo-result { margin-top: 10px; }
        </style>
        <p><button id="ajax-demo-btn">Click Me</button></p>
        <div id="ajax-demo-result"></div>
        <?php
        return ob_get_clean();
    }

    function demo_call(){
        wp_send_json_success("received ajax call");
    }


}