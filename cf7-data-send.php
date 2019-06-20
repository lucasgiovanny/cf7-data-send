<?php
/**
 * Plugin Name: CF7 Data Send
 * Plugin URI: https://github.com/lucasgiovanny/cf7-data-send
 * Description: Plugin that validates and send CF7 data to an URL
 * Version: 1.0
 * Author: Lucas Giovanny
 * Author URI: http://www.lucasgiovanny.com
 */

function child_plugin_activate()
{

    if (!is_plugin_active('contact-form-7/wp-contact-form-7.php') and current_user_can('activate_plugins')) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires Contact Form 7 to be installed and active. <br><br> <a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
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

function validatefield($value, $type)
{
    if ($type == 1) {
        return (bool) ctype_digit($value);
    } elseif ($type == 2) {
        return (bool) preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $value);
    } elseif ($type == 3) {
        return (bool) strtotime($value);
    } else {
        return false;
    }

}

if (is_admin()) {
    add_action('admin_init', 'admin_init_cf7datasend');
    add_action('admin_menu', 'admin_menu_cf7datasend');
}

register_activation_hook(__FILE__, 'child_plugin_activate');
register_activation_hook(__FILE__, 'activate_cf7datasend');
register_deactivation_hook(__FILE__, 'deactive_cf7datasend');

function logerror($log)
{
    $date = date('Y-m-d H:i:s');
    $logfinal = "[{$date}]: " . $log;
    $file = WP_PLUGIN_DIR . "/cf7-data-send/log.log";
    file_put_contents($file, $logfinal, FILE_APPEND);
}

add_action('wpcf7_before_send_mail', function ($contact_form) {

    $submission = WPCF7_Submission::get_instance();

    if ($submission) {
        $posted_data = $submission->get_posted_data();
    }

    $url = get_option('base_url');

    $form = get_option('form_id');

    $fields = get_option('fieldsname');
    $params = get_option('params');
    $validation = get_option('validations');

    $trava = false;

    if ($contact_form->id == $form) {

        foreach ($posted_data as $key => $value) {

            if (in_array($key, $fields)) {

                $fieldKey = array_search($key, $fields);

                if ($validation[$fieldKey] != 0) {

                    if (validatefield($value, $validation[$fieldKey])) {
                        $data[] = array(
                            'param' => $params[$fieldKey],
                            'data' => $value,
                        );
                    } else {
                        $trava = true;
                        logerror("ABORTING: Field {$key} with value {$value} has an error of validation. \n");
                    }
                } else {
                    $data[] = array(
                        'param' => $params[$fieldKey],
                        'data' => $value,
                    );
                }
            }
        }

        if (!$trava) {
            if (isset($data) && !empty($data)) {

                $urlparams = "";

                foreach ($data as $datas) {
                    $urlparams .= $datas['param'] . "=" . $datas['data'] . "&";
                }

                $sendurl = $url . "?" . $urlparams;
                urlencode($sendurl);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $sendurl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $result = curl_exec($ch);

                if (curl_errno($ch)) {
                    $error = curl_error($ch);
                    logerror("ERROR: {$error} \n");
                }
                curl_close($ch);

            }
        }

    } else {
        logerror("ABORTING: Form submitted is not watched. \n");
    }

});
