<?php
/**
 * Plugin Name: CF7 Data Send
 * Plugin URI: https://github.com/lucasgiovanny/cf7-data-send
 * Description: Plugin that validates and send CF7 data to an URL
 * Version: 1.0
 * Author: Lucas Giovanny
 * Author URI: http://www.lucasgiovanny.com
 */

register_activation_hook( __FILE__, 'child_plugin_activate' );

function child_plugin_activate(){

    if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) and current_user_can( 'activate_plugins' ) ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires Contact Form 7 to be installed and active. <br><br> <a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }

}

function activate_cf7datasend()
{
    add_option('base_url', 'https://exemple.com/');
}

function deactive_cf7datasend()
{
    delete_option('base_url');
    delete_option('form_id');
}

function admin_init_cf7datasend()
{
    register_setting('cf7datasend', 'base_url');
    register_setting('cf7datasend', 'form_id');
    register_setting('cf7datasend', 'fieldsname');
    register_setting('cf7datasend', 'params');
    register_setting('cf7datasend', 'validations');
}

function admin_menu_cf7datasend()
{
    add_options_page('CF7 Data to URL Settings', 'CF7 Data to URL', 'manage_options', 'cf7datasend', 'options_page_cf7datasend');
}

function options_page_cf7datasend()
{
    include WP_PLUGIN_DIR . '/cf7-data-send/settings.php';
}

function cf7datasend($contact_form)
{

    $url = get_option('base_url');

    $form = get_option('form_id');

    $fields = get_option('fieldsname');
    $params = get_option('params');
    $validation = get_option('validations');

    // if form
        // foreach post
            // if post esta em fields
                // se sim, valida
                    // valido entra no objeto param = post

    // foreach no objeto montando url

    // chama url

    $title = $contact_form->title;
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $posted_data = $submission->get_posted_data();
    }

    $name = $posted_data["sede"];

    $url = "http://localhost:8888/freelancer/re.php?email=" . $name;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

}

if (is_admin()) {
    add_action('admin_init', 'admin_init_cf7datasend');
    add_action('admin_menu', 'admin_menu_cf7datasend');
}

register_activation_hook(__FILE__, 'activate_cf7datasend');
register_deactivation_hook(__FILE__, 'deactive_cf7datasend');

add_action('wpcf7_before_send_mail', 'cf7datasend');
