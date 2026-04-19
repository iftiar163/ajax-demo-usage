<?php

class AJDM_Contact_Form{
    function __construct()
    {
        add_shortcode('ajdm_contact_form', [$this, 'render_form']);
        add_action('wp_ajax_contact', [$this, 'process_submission']);
    }

    function render_form(){
        ob_start();
        ?>
        <style>
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
            #ajax-demo-contact-form textarea {
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
        </style>
        <form id="ajax-demo-contact-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea><br>
            <button type="submit">Send Message</button>
        </form>
        <div id="ajax-demo-contact-result"></div>
        <?php
        return ob_get_clean();
    }

    function process_submission(){
        // Nonce Varification Pending
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error('All fields are required.');
        }

        $to = get_option('admin_email');
        $subject = 'COntact Form submission Form' . $name;
        $body = "Name : $name\nEmail : $email\nMessage: $message";
        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        if (wp_mail($to, $subject, $body, $headers)) {
            wp_send_json_success('Message Sent Successfully');
        } else {
            wp_send_json_error('Failed');
        }

    }
}