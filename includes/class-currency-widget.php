<?php

class AJDM_Currency_Widget{
    function __construct()
    {
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);
        add_action('wp_ajax_currency', [$this, 'get_currency_rates']);
    }

    function add_dashboard_widget(){
        wp_add_dashboard_widget(
            'ajdm-daily-currency',
            'AJDM Currency Rates',
            [$this, 'render_widget']
        );
    }

    function render_widget(){
        ?>
        <style>
            #ajax-demo-currency-btn {
                background: #007cba;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 3px;
                cursor: pointer;
            }

            #ajax-demo-currency-btn:hover {
                background: #005a87;
            }

            #ajax-demo-currency-result {
                margin-top: 10px;
            }
        </style>
        <button id="ajax-demo-currency-btn">Get Currency Rates</button>
        <div id="ajax-demo-currency-result"></div>
        <?php 
    }

    function get_currency_rates(){
        $api_url = 'https://api.exchangerate-api.com/v4/latest/BDT';
        $response = wp_remote_get($api_url);
        if(!is_wp_error($response)){
            $response_data = wp_remote_retrieve_body($response);
            $data = json_decode($response_data, true);

            $result = [
                'USD' => number_format( 1/$data['rates']['USD'], 2 ),
                'EUR' => number_format( 1/$data['rates']['EUR'], 2 ),
                'GBP' => number_format( 1/$data['rates']['GBP'], 2 ),
            ];
            wp_send_json_success($result);
        }else{
            wp_send_json_error("failed to load data");
        }
    }


}