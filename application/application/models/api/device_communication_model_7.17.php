<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of device_model
 *
 * @author vaio
 */
class Device_communication_model extends CI_Model {

    //put your code here
    /**
     * failed status string
     *
     * @var string
     */
    var $failed_string = 'failed';

    /**
     * success status string
     *
     * @var string
     */
    var $success_string = 'success';

    function __construct() {
        parent::__construct();

        $this->load->model('admin/item_model', 'item_model');
        $this->load->model('admin/user_model');
        $this->load->model('common_model');
    }

    public function getIdByAuthKey($auth_key) {
        $me = $this->common_model->selectAll('User', array('auth_key' => $auth_key));

        return $me[0]->UserId;
    }

    public function login_user($userdetails){
        $this->load->library('encrypt');
        $authKey = $this->common_model->gen_auth_key();
        if(!empty($userdetails)){
            $this->db->select('*');
            $this->db->from('User u');
            $this->db->where('u.Email', $userdetails['email']);
            $this->db->where('u.Password', str_rot13($userdetails['password']));
            $this->db->where('u.verified', 1);
            $result = $this->db->get();
            $result_set = $result->row();
            @$result_set->api_key = $authKey;
            $numrows  = $result->num_rows();
            @$user_id = $result_set->UserId;
            $data_update = array(
                'auth_key' => $authKey,
            );
            $this->db->where('UserId', $user_id);
            $this->db->update('User', $data_update);
            
            @$result_set->auth_key = '';
            if($numrows > 0){
                //$data['api_key'] = $authKey;
                $data['result'] = $result_set;
                $data['status'] = $this->success_string;
                $data['message'] = 'Logged in Successfully';
            }else{
                $data['status'] = $this->failed_string;
                $data['message'] = 'Login Failed!';
            }
        }
        return $data;
    }
    
    function getallquestion($auth_key){
        $userid = $this->getIdByAuthKey($auth_key);
        $this->db->from("question_answers qa");
        $this->db->join('User u', 'u.UserId = qa.userId');
        $this->db->where("qa.userId !=", $userid);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        if($num_rows > 0){
                $result = $query->result();
                $data['result'] = $result;
                $data['status'] = $this->success_string;
                $data['message'] = 'Questions listed successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'No questions in list';
        }
        
        return $data;
    }
    
    
    function getallnotification($auth_key){
        $userid = $this->getIdByAuthKey($auth_key);
        
        $this->db->from("Notifications n");
        $this->db->join('User u', 'u.UserId = n.notification_Sender');
        $this->db->where("n.notification_receiver", $userid);
        $this->db->limit(30);
        $query  = $this->db->get();
        $numrows = $query->num_rows();
        if($numrows > 0){
                $data['result'] = $query->result();
                $data['status'] = $this->success_string;
                $data['message'] = 'Notifications listed successfully';
            
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'No notification exists';
        } 
        
        return $data;
    }
    
    
    function changepassword($auth_key,$post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        if(!empty($post_array)){
            
            $current_password = $post_array['current_password'];
            $this->db->where("UserId", $userid);
            $this->db->where("Password",str_rot13($current_password));
            $query = $this->db->get("User");
            $numrows = $query->num_rows();
            
            if($numrows > 0)
            {
                $newpwd = $post_array['new_password']; 
                $up_data = array(
                    "Password" => str_rot13($newpwd)
                );
                $this->db->where("UserId", $userid);
                $this->db->update("User", $up_data);
                $data['status'] = $this->success_string;
                $data['message'] = 'Password changed successfully';
                
            }else{
                
                $data['status'] = $this->failed_string;
                $data['message'] = 'No such password exists';
                
            }
            
            return $data;
            
        }
    }
    
    function getmyquestion($auth_key){
        $userid = $this->getIdByAuthKey($auth_key);
        $this->db->where("userId ", $userid);
        $query = $this->db->get("question_answers");
        $num_rows = $query->num_rows();
        if($num_rows > 0){
                $result = $query->result();
                $data['result'] = $result;
                $data['status'] = $this->success_string;
                $data['message'] = 'Questions listed successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'No questions in list';
        }
        return $data;
    }
    
    function getallshares($auth_key){
        $userid = $this->getIdByAuthKey($auth_key);
        $this->db->from("sharing_heping sh");
        $this->db->join('User u', 'u.UserId = sh.UserId');
        $this->db->where("sh.UserId !=", $userid);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        if($num_rows > 0){
                $result = $query->result();
                $data['result'] = $result;
                $data['status'] = $this->success_string;
                $data['message'] = 'Share items listed successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'No items in share list';
        }
        
        return $data;
    }
    
    function postquestion($auth_key,$post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        if($post_array != ""){
            @$post_array['userId'] = $userid;
            $insert = $this->db->insert('question_answers', $post_array);
            if($insert == TRUE){
                $data['status'] = $this->success_string;
                $data['message'] = 'Question posted successfully';
            }
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Questions post failed';
        }
        return $data;
    }
    
    
    function postanswer($auth_key,$post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        if($post_array != ""){
            @$post_array['UserId'] = $userid;
            $insert = $this->db->insert('Answer', $post_array);
            if($insert == TRUE){
                $data['status'] = $this->success_string;
                $data['message'] = 'Answer posted successfully';
            }
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Answer post failed';
        }
        return $data;
    }
     
    function get_geo_users($userid){ //get geo data later
        
        if($userid != ""){
            $this->db->where("UserId !=", $userid);
            $query = $this->db->get("User");
            $result = $query->result();
            $numrows = $query->num_rows();
            if($numrows > 0){
                return $query->result();
            }else{
                return "";
            }
            
        }
        
    }
    
    function sharemytable($auth_key,$post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        if(!empty($post_array)){
           @$post_array['notification_Sender'] = $userid;
           @$post_array['Date'] = date("Y-m-d H:i:s");
           
           $get_feo_users = $this->get_geo_users($userid);
           foreach($get_feo_users as $geo){
               
               @$post_array['notification_receiver'] = $geo->UserId;
               $this->db->insert('Notifications', $post_array);
           }
             
           $data['status'] = $this->success_string;
           $data['message'] = 'Shared successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Share failed';
        }
        return $data;
    }
    
      
    function invite($auth_key,$post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        if(!empty($post_array)){
            @$post_array['notification_Sender'] = $userid;
           @$post_array['Date'] = date("Y-m-d H:i:s");
           $this->db->insert('Notifications', $post_array); 
		   // send push message
		   if(isset($post_array['notification_receiver'])){
				$networkId = $this->getAllNetworkIds($post_array['notification_receiver']);
				if(!empty($networkId)){
					$msg['ref_type']    = PUSH_MESSAGE_REF;
					$msg['type']        = PUSH_MESSAGE_REF;
					$msg['title']       = $post_array['NotificationTitle'];
					$msg['message']     = $post_array['NotificationMessage'];
					$msg['short_descr'] = PUSH_MESSAGE_INVITE;
					
					$this->load->helper('text');
					$msg['title']       = character_limiter($msg['title'], 150);
					$msg['short_descr'] = character_limiter($msg['short_descr'], 150);
					$msg['message']     = character_limiter($msg['message'], 500);
					
					$this->load->helper('push_notification_helper');									
					push_message($msg, $networkId);			
				}
		   }
		   
		    
           $data['status'] = $this->success_string;
           $data['message'] = 'Invited successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Invite failed';
        }
        return $data;
    }
    
    function getsingleuserdetails($auth_key,$post_array){
        if(!empty($post_array)){
            $userid = $this->getIdByAuthKey($auth_key);
            $getuser = $post_array['user_id'];
            $this->db->where("UserId", $getuser);
            $query = $this->db->get("User");
            $result = $query->result();
            $numrows = $query->num_rows();
            if($numrows > 0){
                $data['result'] = $result;
                $data['status'] = $this->success_string;
                $data['message'] = 'Answer posted successfully';   
            }else{
                $data['status'] = $this->failed_string;
                $data['message'] = 'Answer post failed';
            }
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Answer post failed';
        }
        
            return $data;
    }
    
    function getquestionbyid($auth_key,$post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        $question_id = $post_array['QuestionId'];
        $this->db->select("a.*, u.UserId,u.FirstName, u.LastName, u.ProfilePhoto");
        $this->db->from("Answer a");
        $this->db->join('User u', 'u.UserId = a.UserId');
        $this->db->where("a.UserId", $userid);
        $this->db->where("a.QuestionId", $question_id);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        if($num_rows > 0){
                $result = $query->result();
                $data['result'] = $result;
                $data['status'] = $this->success_string;
                $data['message'] = 'Answers listed successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'No answers in list';
        }
        
        return $data;
    
    }
    
    function getmyshares($auth_key){
        $userid = $this->getIdByAuthKey($auth_key);
        $this->db->where("UserId ", $userid);
        $query = $this->db->get("sharing_heping");
        $num_rows = $query->num_rows();
        if($num_rows > 0){
                $result = $query->result();
                $data['result'] = $result;
                $data['status'] = $this->success_string;
                $data['message'] = 'Share items listed successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'No items in share list';
        }
        
        return $data;
    }
    
    function sharemyitem($auth_key, $post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        if(!empty($post_array)){
            
            @$post_array['Date'] = date("Y-m-d H:i:s");
            @$post_array['UserId'] = $userid;
            
            $insert = $this->db->insert('sharing_heping', $post_array);
			// send push message
			
			$networkIds = $this->getAllNetworkIds($userid);
			if(!empty($networkIds)){
				$msg['ref_type']    = PUSH_MESSAGE_REF;
				$msg['type']        = PUSH_MESSAGE_REF;
				$msg['title']       = PUSH_MESSAGE_SHARE;
				$msg['message']     = PUSH_MESSAGE_SHARE;
				$msg['short_descr'] = PUSH_MESSAGE_SHARE;
				
				$this->load->helper('text');
				$msg['title']       = character_limiter($msg['title'], 150);
				$msg['short_descr'] = character_limiter($msg['short_descr'], 150);
				$msg['message']     = character_limiter($msg['message'], 500);
				
				$this->load->helper('push_notification_helper');							
				foreach($networkIds as $network){
					push_message($msg, $network->network_id);
				}				
			}
			
			
			
            $data['status'] = $this->success_string;
            $data['message'] = 'Item shared successfully';
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Item sharing failed';
        }
        return $data;
    }
    
    function send_activation_email($userdetails){
        $this->load->helper('email');
                    $to = $userdetails['Email'];
                    $subject = "Activation Email";

                    $message = "
                    <html>
                    <head>
                    <title>Account Activation</title>
                    </head>
                    <body>
                    <p>Hi ".$userdetails['FirstName']." ".$userdetails['LastName'].", </p>
                    <p>Welcome to Voyage community.</p>
                    <p>Please activate your account by clicking the link specified below:</p>
                    <p><a href='".base_url()."index.php/user/activate_account/".$userdetails['activate_code']."'>Activate My Account</a></p>
                    <p>Thanks,<br/> Voyage Team</p>
                    </body>
                    </html>
                    ";

                    // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <contact@theproductangels.com>' . "\r\n";
                    //$headers .= 'Cc: myboss@example.com' . "\r\n";

                    mail($to,$subject,$message,$headers);
                    
        
    }
    
    function signup_user($login_details){
        $this->load->library('encrypt');
        if(!empty($login_details)){
                    $uniqhash = uniqid();
                    $ins_data = array(
                        'FirstName' => $login_details['first_name'] ,
                        'LastName' => $login_details['last_name'] ,
                        'Email' => $login_details['email'],
                        'Password' => str_rot13($login_details['password']),
                        'LoginType' => $login_details['logintype'],
                        'gender' => $login_details['gender'],
                        'dateofbirth' =>$login_details['dateofbirth'],
                        'country' => $login_details['country'],
                        'activate_code' => $uniqhash
                    );
                    
                $this->db->where("Email", $login_details['email']);    
                $query = $this->db->get("User");
                $numrows = $query->num_rows(); 
                if($numrows == 0){
                    $insert =  $this->db->insert('User', $ins_data);
                    $userid = $this->db->insert_id();
                    
                    $this->send_activation_email($ins_data);
                    
                    
                $connections =  $this->db->get('connection_types');
                $c_query = $connections->result();
                if(!empty($c_query)){
                    
                    foreach($c_query as $q){
                        $co_data = array(
                        'userrid' => $userid,
                        'connection_id' => $q->ConnectionId
                     );
                    $this->db->insert('user_connections', $co_data); 
                    }
                }
                    
                    
                    if($insert == TRUE){
                         $data['status'] = $this->success_string;
                         $data['message'] = 'Registered Successfully';
                    }
                }else{
                    $data['status'] = $this->failed_string;
                    $data['message'] = 'Email already exists!';
                }
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Signup failed!';
        }
        
        return $data;
    }	
    
    function get_userdetails($userid, $auth_key){
        if($userid != ""){
            
            $this->db->where("UserId", $userid);
            $query = $this->db->get("User");
            $result = $query->row();
            @$result->api_key = $auth_key;
            return $result;  
            
        }
        
    }
    
    function get_allusers($auth_key, $post_data){
            $userid = $this->getIdByAuthKey($auth_key);
            $this->db->where("UserId !=", $userid);
            $query = $this->db->get("User");
            $result = $query->result();
            if(!empty($result)){
                    $data['result'] = $result;
                    $data['status'] = $this->success_string;
                    $data['message'] = 'All users are listed successfully'; 
            }else{
                
                    $data['status'] = $this->failed_string;
                    $data['message'] = 'No users available'; 
            }
            return $data;
    }
    
    function forgotpassword($login_details){
        $this->load->library('encrypt');
        $this->load->helper('email');
        if(!empty($login_details)){
            $email = $login_details['email'];
             
            if(valid_email($email))
            {
                $this->db->where("Email", $email);    
                $query = $this->db->get("User");
                $numrows = $query->num_rows(); 
                $result = $query->row();
                //echo $numrows; die("test");
                if($numrows > 0){
                   // echo $email; die();
                    $password = str_rot13($result->Password);
                    $to = $email;
                    $subject = "Forgot Password";

                    $message = "
                    <html>
                    <head>
                    <title>Forgot Password</title>
                    </head>
                    <body>
                    <p>The account details are as follows:</p>
                    <table>
                    <tr>
                    <th>Email</th>
                    <th>Password</th>
                    </tr>
                    <tr>
                    <td>".$email."</td>
                    <td>".$password."</td>
                    </tr>
                    </table>
                    </body>
                    </html>
                    ";

                    // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <contact@theproductangels.com>' . "\r\n";
                    //$headers .= 'Cc: myboss@example.com' . "\r\n";

                    mail($to,$subject,$message,$headers);
                    
                    $data['status'] = $this->success_string;
                    $data['message'] = 'Account details are sent to your email address'; 
                    
                    
                }else{
                    
                $data['status'] = $this->failed_string;
                $data['message'] = 'Email account not exists!';    
                    
                }
            }
            else
            {
                $data['status'] = $this->failed_string;
                $data['message'] = 'Invalid Email';
            }
            
        }else{
            $data['status'] = $this->failed_string;
            $data['message'] = 'Error in processing..';
        }
        
        
        return $data;
    }
     
    
    function getmyconnections($auth_key){
        $userid = $this->getIdByAuthKey($auth_key); 
        
        $query = $this->db->get('connection_types');
        $result = $query->result();
        $data['all_connections'] = $result;
        
        $this->db->select('connection_id');
        $this->db->where('userrid', $userid);
        $c_query = $this->db->get('user_connections');
        $c_result = $c_query->result();
        $myc = array();
        foreach($c_result as $c){
            $myc[] = (int)$c->connection_id;
        }
        
        $data['my_connections'] = $myc;
        return $data;
    }
    
    function save_message($auth_key, $post_array){
        $userid = $this->getIdByAuthKey($auth_key);
        $insert = $this->db->insert('Messages', $post_array); 
        
        if($insert == TRUE){
        $data['status'] = $this->success_string;
        $data['message'] = 'Message send successfully'; 
        return $data; 
            
        }
    }
    
    
    function getall_message($auth_key, $sender_id){
        $userid = $this->getIdByAuthKey($auth_key);
        // $this->db->where("(MessageSender = $sender_id) OR (MessageSender = $userid)");
        //$this->db->where("(MessageReceiver =$userid) OR (MessageReceiver = $sender_id)");
        $this->db->where("(MessageSender = $sender_id AND MessageReceiver = $userid) OR (MessageSender = $userid AND MessageReceiver = $sender_id)");
        $this->db->order_by("message_send_date", "desc");
        $this->db->limit("10");
        $query = $this->db->get("Messages");
        $numrows = $query->num_rows();
        if($numrows > 0){
        $result = $query->result(); 
        if(!empty($result)){
        foreach($result as $r){
            $updata = array(
               'MessageReceivedDate' => date("Y-m-d H:i:s")
            );

        $this->db->where('MessageId', $r->MessageId);
        $this->db->update('Messages', $updata); 
            
        }
        }
        $data['result'] = $result;    
        $data['status'] = $this->success_string;
        $data['message'] = 'Message listed successfully'; 
            
        }else{
        $data['status'] = $this->failed_string;
        $data['message'] = 'No messages found';     
        }
        
        
        return $data;
    }
    
    
    
    function getalllatestmessages($auth_key){
        $userid = $this->getIdByAuthKey($auth_key);
        $this->db->select('*');
        $this->db->from('Messages m');
        $this->db->join('User u', 'u.UserId = m.MessageSender');
        $this->db->where("m.MessageReceiver",$userid);
        $this->db->order_by("m.message_send_date", "desc");
        $this->db->group_by("m.MessageSender"); 
        $query = $this->db->get();
        $numrows = $query->num_rows();
        if($numrows > 0){
        $result = $query->result(); 
        
        if(!empty($result)){
        foreach($result as $r){
            $updata = array(
               'MessageReceivedDate' => date("Y-m-d H:i:s")
            );

        $this->db->where('MessageId', $r->MessageId);
        $this->db->update('Messages', $updata); 
        }}
        $data['result'] = $result;    
        $data['status'] = $this->success_string;
        $data['message'] = 'Message listed successfully'; 
            
        }else{
        $data['status'] = $this->failed_string;
        $data['message'] = 'No messages found';     
        }
        
        
        return $data;
        
    }
    
    function update_connections($auth_key, $my_connections){
        $userid = $this->getIdByAuthKey($auth_key); 
        $my_connection = explode(",",$my_connections);
        $this->db->delete('user_connections', array('userrid' => $userid)); 
        $i =0;
        $count = count($my_connection);
        for($i=0; $i< $count; $i++){
            $data_up = array(
            'userrid' => $userid ,
            'connection_id' => $my_connection[$i] 
            );
        $this->db->insert('user_connections', $data_up); 
        }
        
        $data['status'] = $this->success_string;
        $data['message'] = 'Connections updated successfully'; 
        return $data; 
    }
    
    function update_location($auth_key, $post_data){
        $userid = $this->getIdByAuthKey($auth_key); 
        $this->db->where('UserId', $userid);
        $this->db->update('User', $post_data); 
        $data['status'] = $this->success_string;
        $data['message'] = 'Locations updated successfully'; 
        return $data; 
    }
    
    function update_profile($my_id, $post_array){
        
        $this->db->where('UserId', $my_id);
        $this->db->update('User', $post_array); 
    }
    
    
    function get_property_status($p_id){
        if($p_id != ""){
                $this->db->select('otp.urgent as rush, otp.deadline, otp.a_w_l, otp.bed as beds, otp.ba as baths, otp.sf as square_foot, otp.gar as number_of_garages, otp.MLS, otp.lockbox, otp.address, otp.area, otp.zipcode, ma.area_name, otp.area, otp.lat, otp.lng');
                $this->db->from('offer_tracking_pipeline otp');
                $this->db->join('metro_area ma', 'ma.id = otp.metro_area');
                $this->db->where('otp.id', $p_id);
                $result = $this->db->get();
                $data = $result->row();
                return  $data;

        }
    }
	


	public function getAllNetworkIds($user_id) {
		$result = array();
        $userData = $this->common_model->selectAll('User', array('UserId' => $user_id));
        $userData[0]->network_id;
		
		$this->db->from("User");
		$array = array('UserId !=' => $user_id, 'network_id' => $userData[0]->network_id);
        $this->db->where($array);
        $query = $this->db->get();
        $num_rows = $query->num_rows();
        if($num_rows > 0){
                $result = $query->result();          
        }
        return $result;		
		
    }
	
	
}
?>