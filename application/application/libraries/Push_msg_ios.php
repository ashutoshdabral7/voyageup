<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * push message service
 */
include_once APPPATH.'/libraries/push_msg.php' ;
class Push_msg_ios  extends Push_msg
{
    private $certificate;
    public function __construct() 
    {
        parent::__construct();
        $this->set_api_key(IOS_PUSH_APIKEY);
        $this->set_post_url(IOS_PUSH_GATEWAY);
        $this->certificate  = IOS_PUSH_CERTIFICATE;
         
    }
    
    function send_message() 
    {
        // Put your device token here (without spaces):
        $device_token = $this->get_device_token();

        // Put your private key's passphrase here:
        $passphrase = $this->get_api_key();

        // Put your alert message here:
        $msg_data   = $this->get_message();

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificate);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client($this->get_post_url(), $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

        // Create the payload body
        $body['aps']    = array(
                            'alert' => $msg_data['message'],
                            'badge' => 1,
                            'sound' => 'default',
                            );
        $body['message'] = array(
                            'message_ID'    => isset($msg_data['message_id']) ? $msg_data['message_id'] : '',
                            'message_title' => $msg_data['title'],
                            'message_type'  => isset($msg_data['type']) ? $msg_data['type'] : '',
                            'message_shortDescription' => isset($msg_data['short_descr']) ? $msg_data['short_descr'] : '',
                            'message_date'  => date('d/m/Y'));
        
        //  "message":{"message_ID":"1012","message_title":"push message","message_type":"push","message_shortDescription":"sample message","message_date": "12/05/2014"}}

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

       /* if (!$result)
                echo 'Message not delivered' . PHP_EOL;
        else
                echo 'Message successfully delivered' . PHP_EOL;*/

        // Close the connection to the server
        fclose($fp);
    }
            
    
}
?>
