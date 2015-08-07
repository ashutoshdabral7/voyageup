<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * push message abstract class
 * @drsJohn
 */
abstract class Push_msg
{
    private $_api_key;
    private $_device_token;
    private $_msg_data;
    private $_post_url;

    public function __construct() 
    {
        $this->_api_key      = '';
        $this->_post_url     = '';
        $this->_device_token = '';   // array();
        $this->_msg_data     = ''; //array();
    }
    
    function set_api_key($key = '') 
    {
        $this->_api_key  = $key;
    }
    
    function set_device_token($token = '') 
    {
        $this->_device_token = $token;
    }
    
    function set_message($data = array()) 
    {
        $this->_msg_data = $data;
    }
    
    function set_post_url($url = '') 
    {
        $this->_post_url = $url;
    }
    
    function get_api_key() 
    {
        return $this->_api_key;
    }
    
    function get_device_token() 
    {
        return $this->_device_token;
    }
    
    function get_post_url() 
    {
        return $this->_post_url;
    }
            
    function get_message() 
    {
        return $this->_msg_data;
    }
    
    abstract function send_message();
}
?>