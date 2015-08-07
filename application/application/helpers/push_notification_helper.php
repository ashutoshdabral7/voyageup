<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('push_message'))
{
    function push_message($data = array(), $network_id = null)
    {
        $CI = & get_instance();
        if(isset($data['message']) &&  $data['message'] != '' && $network_id != '')
        {
                $CI->load->library('push_msg_ios');
                $ios = new Push_msg_ios();
                $ios->set_message($data);
                $ios->set_device_token($network_id);
                $ios->send_message();                
        }
    }
}
