<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AJDM_Contact_Form{
    function __construct()
    {
        add_shortcode('ajdm_contact_form', [$this, 'render_form']);
        add_action('wp_ajax_contact', [$this, 'process_submission']);
        add_filter('manage_contact_submission_posts_columns', [$this, 'add_support_column']);
        add_action('manage_contact_submission_posts_custom_column', [$this, 'display_column_data'], 10, 2);
        add_filter('manage_edit-contact_submission_sortable_columns', [$this, 'sortable_column']);
        add_action('pre_get_posts', [$this, 'sort_data']);
        add_action('init', [$this, 'register_custom_taxonomy']);
    }

    function sort_data($query){
        if( !is_admin() || !$query->is_main_query() ){
            return;
        }

        $orderby = $query->get('orderby');
        if( $orderby == 'submitter_email' || $orderby == 'submitter_ip' ){
            $query->set('meta_key', $orderby);
            $query-> set('orderby', 'meta_value');
        }
    }

    function sortable_column($columns){
        $columns['submitter_email'] = "submitter_email";
        $columns['submitter_ip'] = "submitter_ip";
        $columns['taxonomy-severity'] = "severity";
        return $columns;
    }

    function add_support_column($columns){
        $columns['submitter_email'] = "Email";
        $columns['submitter_ip'] = "IP Address";
        return $columns;
    }

    function display_column_data($columns, $post_id){
        if($columns == 'submitter_email'){
            echo esc_html(get_post_meta($post_id, 'submitter_email', true));
        }

        if($columns == 'submitter_ip'){
            echo esc_html(get_post_meta($post_id, 'submitter_ip', true));
        }
    }

    function register_custom_taxonomy() {
        $labels = [
            'name' => 'Severities',
            'singular_name' => 'Severity',
            'search_items' => 'Search Severities',
            'all_items' => 'All Severities',
            'parent_item' => 'Parent Severity',
            'parent_item_colon' => 'Parent Severity:',
            'edit_item' => 'Edit Severity',
            'update_item' => 'Update Severity',
            'add_new_item' => 'Add New Severity',
            'new_item_name' => 'New Severity Name',
            'menu_name' => 'Severity',
        ];

        $args = [
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'severity'],
            'show_admin_column' => true
        ];

        register_taxonomy('severity', ['contact_submission'], $args);

        if (!term_exists('low', 'severity')) {
            wp_insert_term('Low', 'severity');
        }
        if (!term_exists('medium', 'severity')) {
            wp_insert_term('Medium', 'severity');
        }
        if (!term_exists('high', 'severity')) {
            wp_insert_term('High', 'severity');
        }
    }

    function render_form($atts){
        $defaults = [
            'style' => 'normal'
        ];
        $attributes = shortcode_atts($defaults, $atts);
        $style = $attributes['style'];
        ob_start();
        ?>
        <style>
            <?php if ($style === 'elegant'): ?>
                #ajax-demo-contact-form {
                    max-width: 500px;
                    margin: 0 auto;
                    padding: 30px;
                    border: 2px solid #e0e0e0;
                    border-radius: 10px;
                    background: linear-gradient(135deg, #f5f5f5, #ffffff);
                    font-family: 'Georgia', serif;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                #ajax-demo-contact-form label {
                    display: block;
                    margin-bottom: 8px;
                    font-weight: bold;
                    color: #333;
                    font-size: 16px;
                }

                #ajax-demo-contact-form input,
                #ajax-demo-contact-form textarea,
                #ajax-demo-contact-form select {
                    width: 100%;
                    padding: 12px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-sizing: border-box;
                    font-size: 14px;
                }

                #ajax-demo-contact-form button {
                    background: linear-gradient(135deg, #4a90e2, #357abd);
                    color: white;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background 0.3s;
                }

                #ajax-demo-contact-form button:hover {
                    background: linear-gradient(135deg, #357abd, #2a5f8f);
                }

                #ajax-demo-contact-result {
                    margin-top: 15px;
                    font-style: italic;
                }

            <?php elseif ($style === 'amazing'): ?>
                #ajax-demo-contact-form {
                    max-width: 450px;
                    margin: 0 auto;
                    padding: 25px;
                    border: 3px solid #00cec9;
                    border-radius: 15px;
                    background: linear-gradient(45deg, #a8e6cf, #dcedc8, #ffd3a5);
                    font-family: 'Arial', sans-serif;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                    animation: pulse 2s infinite;
                }

                @keyframes pulse {
                    0% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.02);
                    }

                    100% {
                        transform: scale(1);
                    }
                }

                #ajax-demo-contact-form label {
                    display: block;
                    margin-bottom: 10px;
                    font-weight: bold;
                    color: #2d3436;
                    font-size: 18px;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
                }

                #ajax-demo-contact-form input,
                #ajax-demo-contact-form textarea,
                #ajax-demo-contact-form select {
                    width: 100%;
                    padding: 15px;
                    margin-bottom: 20px;
                    border: 2px solid #00cec9;
                    border-radius: 10px;
                    box-sizing: border-box;
                    font-size: 16px;
                    background: rgba(255, 255, 255, 0.8);
                }

                #ajax-demo-contact-form button {
                    background: linear-gradient(45deg, #00cec9, #55a3ff);
                    color: white;
                    padding: 15px 25px;
                    border: none;
                    border-radius: 10px;
                    cursor: pointer;
                    font-size: 18px;
                    font-weight: bold;
                    transition: all 0.3s;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                }

                #ajax-demo-contact-form button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
                }

                #ajax-demo-contact-result {
                    margin-top: 20px;
                    color: #2d3436;
                    font-weight: bold;
                }

            <?php elseif ($style === 'modern'): ?>
                #ajax-demo-contact-form {
                    max-width: 450px;
                    margin: 0 auto;
                    padding: 25px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background: #2c3e50;
                    color: #ecf0f1;
                    font-family: 'Roboto', sans-serif;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                }

                #ajax-demo-contact-form label {
                    display: block;
                    margin-bottom: 10px;
                    font-weight: 500;
                    color: #ecf0f1;
                    font-size: 14px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }

                #ajax-demo-contact-form input,
                #ajax-demo-contact-form textarea,
                #ajax-demo-contact-form select {
                    width: 100%;
                    padding: 12px;
                    margin-bottom: 15px;
                    border: none;
                    border-bottom: 2px solid #3498db;
                    background: transparent;
                    color: #ecf0f1;
                    font-size: 16px;
                    box-sizing: border-box;
                }

                #ajax-demo-contact-form input:focus,
                #ajax-demo-contact-form textarea:focus,
                #ajax-demo-contact-form select:focus {
                    outline: none;
                    border-bottom-color: #e74c3c;
                }

                #ajax-demo-contact-form button {
                    background: #e74c3c;
                    color: white;
                    padding: 12px 24px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 16px;
                    font-weight: 600;
                    transition: background 0.3s;
                }

                #ajax-demo-contact-form button:hover {
                    background: #c0392b;
                }

                #ajax-demo-contact-result {
                    margin-top: 15px;
                    color: #ecf0f1;
                }

            <?php elseif ($style === 'minimal'): ?>
                #ajax-demo-contact-form {
                    max-width: 400px;
                    margin: 0 auto;
                    padding: 20px;
                    border: none;
                    background: #ffffff;
                    font-family: 'Helvetica', sans-serif;
                }

                #ajax-demo-contact-form label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: normal;
                    color: #666;
                    font-size: 14px;
                }

                #ajax-demo-contact-form input,
                #ajax-demo-contact-form textarea,
                #ajax-demo-contact-form select {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 15px;
                    border: 1px solid #eee;
                    border-radius: 0;
                    background: #fafafa;
                    font-size: 16px;
                    box-sizing: border-box;
                }

                #ajax-demo-contact-form input:focus,
                #ajax-demo-contact-form textarea:focus,
                #ajax-demo-contact-form select:focus {
                    outline: none;
                    border-color: #ccc;
                    background: #ffffff;
                }

                #ajax-demo-contact-form button {
                    background: #000;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    cursor: pointer;
                    font-size: 16px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }

                #ajax-demo-contact-form button:hover {
                    background: #333;
                }

                #ajax-demo-contact-result {
                    margin-top: 10px;
                    color: #666;
                }

            <?php else:  // normal ?>
                #ajax-demo-contact-form {
                    max-width: 400px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background: #f9f9f9;
                }

                #ajax-demo-contact-form label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                }

                #ajax-demo-contact-form input,
                #ajax-demo-contact-form textarea,
                #ajax-demo-contact-form select {
                    width: 100%;
                    padding: 8px;
                    margin-bottom: 10px;
                    border: 1px solid #ddd;
                    border-radius: 3px;
                    box-sizing: border-box;
                }

                #ajax-demo-contact-form button {
                    background: #007cba;
                    color: white;
                    padding: 10px;
                    border: none;
                    border-radius: 3px;
                    cursor: pointer;
                }

                #ajax-demo-contact-form button:hover {
                    background: #005a87;
                }

                #ajax-demo-contact-result {
                    margin-top: 10px;
                }

            <?php endif; ?>
        </style>
        <form id="ajax-demo-contact-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea><br>
            <label for="severity">Severity:</label>
            <select id="severity" name="severity">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select><br>
            <button type="submit">Send Message</button>
        </form>
        <div id="ajax-demo-contact-result"></div>
        <?php
        return ob_get_clean();
    }

    function process_submission(){
        // Nonce Varification Pending
        check_ajax_referer('contact', 'nonce');
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);
        $severity = sanitize_text_field($_POST['severity']);
        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error('All fields are required.');
        }

        // $to = get_option('admin_email');
        $ip = $this -> get_the_user_ip();
        $subject = 'COntact Form submission Form' . $name;
        $body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message\nIP: $ip";
        // $headers = ['Content-Type: text/plain; charset=UTF-8'];

        // if (wp_mail($to, $subject, $body, $headers)) {
        //     wp_send_json_success('Message Sent Successfully');
        // } else {
        //     wp_send_json_error('Failed');
        // }

        $post_data = [
            'post_title' => "Submission From {$name}",
            'post_content' => $body,
            'post_status' => 'private',
            'post_type' => 'contact_submission'
        ];

        $post_id = wp_insert_post($post_data);
        if($post_id){
            update_post_meta($post_id, 'submitter_name', $name);
            update_post_meta($post_id, 'submitter_email', $email);
            update_post_meta($post_id, 'submitter_ip', $ip);
            wp_set_object_terms($post_id, $severity, 'severity');
            wp_send_json_success('Message Sent Successfully');
        }else {
            wp_send_json_error('Failed');
        }

    }

    private function get_the_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}