<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists("user_log_message")) {

    function user_log_message($msg = '', $type = 'remove') {
        $ci = &get_instance();

        $today = date("Ymd");
        $log_file = $ci->config->item("log_path") . "log_{$type}_{$today}.txt";
        if ($msg != '') {
            //$date		=	date('l jS \of F Y h:i:s A');
            $history = "\n****************************************************************************************************\n";
            $history .= "		$msg \n";
            $history .= "****************************************************************************************************\n";

            file_put_contents($log_file, $history, FILE_APPEND);
        }
    }

}

/**
 * Function to display common errors
 *
 * @param string $error
 * @param string $success
 * @return message
 */
if (!function_exists('display_common_errors')) {

    function display_common_errors($error = NULL, $success = NULL) {
        $CI = &get_instance();
        $data['message'] = '';
        $error_message = $CI->session->flashdata('error_message');
        $success_message = $CI->session->flashdata('success_message');
        $warning_message = $CI->session->flashdata('warning_message');
        $validation_errors = validation_errors();
        $validation_errors = ('' == trim($validation_errors)) ? @$CI->merror : $validation_errors;
        $data['message_success'] = true;
        if ('' != trim($success_message)) {
            $data['message'] .= $success_message;
            $CI->load->view('common/common_messages', $data);
            $data['message'] = '';
        } else if ('' != trim($success)) {
            $data['message'] .= $success;
            $CI->load->view('common/common_messages', $data);
            $data['message'] = '';
            return false;
        }

        if ('' != trim($error_message)) {
            $data['message_success'] = false;
            $data['message'] .= $error_message;
        } else if ('' != trim($validation_errors)) {
            $data['message_success'] = false;
            $data['message'] .= $validation_errors;
        } else if ('' != trim($error)) {
            $data['message_success'] = false;
            $data['message'] .= $error;
        }

        /* Warning message */
        if ('' != trim($warning_message)) {
            $data['$warning_message'] = true;
            $data['message'] .= $warning_message;
        }

        //if($data['message'] == NULL || $data['message'] =='') return;
        $CI->load->view('common/common_messages', $data);
    }

}

function ddmmyyyyToDBFormat($date = '') {
    $db_date = '';
    if ($date) {
        $date_arr = explode('-', $date);
        $db_date = $date_arr[2] . '-' . $date_arr[1] . '-' . $date_arr[0];
    }
    return $db_date;
}

function safe_html($input_field) {
    return trim(htmlspecialchars(trim(strip_tags($input_field))));
}

/**
 * get current date time
 *
 * @return current date time for gmt + 5:30
 */
function get_cur_date_time($time = true) {
    if ($time)
        return date('Y-m-d H:i:s', (mktime(gmdate('H') + 5, gmdate('i') + 30, gmdate('s'), gmdate('m'), gmdate('d'), gmdate('Y'))));
    else
        return date('Y-m-d', (mktime(gmdate('H') + 5, gmdate('i') + 30, gmdate('s'), gmdate('m'), gmdate('d'), gmdate('Y'))));
}

?>
