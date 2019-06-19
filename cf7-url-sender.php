<?php
/**
 * Plugin Name: CF7 Data Send
 * Plugin URI: https://github.com/lucasgiovanny/cf7-data-send
 * Description: Plugin that validates and send CF7 data to an URL
 * Version: 1.0
 * Author: Lucas Giovanny
 * Author URI: http://www.lucasgiovanny.com
 */

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
}

function admin_menu_cf7datasend()
{
    add_options_page('CF7 Data to URL Settings', 'CF7 Data to URL', 'manage_options', 'cf7datasend', 'options_page_cf7datasend');
}

function options_page_cf7datasend()
{
    include WP_PLUGIN_DIR . '/cf7-data-send/options.php';
}

function cf7datasend($contact_form)
{

    $url = get_option('base_url');

    // confere_se é o form
    // seta variavel com url das configs
    // seta os campos
    //  valida campos criticos
    // monta url final
    //  converte para url
    // chama curl

    $title = $contact_form->title;
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $posted_data = $submission->get_posted_data();
    }

    // Seta todos os campos
    $name = $posted_data["sede"];
    // $mobile = $posted_data["email"];
    // $car = $posted_data["email"];
    // $contactDate = $posted_data["email"];
    // $email = $posted_data["email"];
    // $observation = $posted_data["email"];
    // $marca = $posted_data["email"];
    // $headquarters = $posted_data["email"];
    // $source = $posted_data["email"];
    // $medio = $posted_data["email"];
    // $email = $posted_data["email"];

    // Valida os dados
    // Trata os dados
    // Monta URL (Checa a barra no final)
    // Envia a URL

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
