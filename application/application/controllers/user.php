<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of user
 *
 * @author vaio
 */
class user extends CI_Controller { 
    //put your code here

    /**
     * General Headers
     *
     * @var Array
     */
    var $request_headers = array();

    /**
     * General contents
     *
     * @var Array
     */
    var $request_array = array();

    /**
     * Failed status text
     *
     * @var string
     */
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

    /**
     * Constructor
     */
    function __construct() { 
        parent::__construct();

        $this->load->model('api/device_communication_model', 'dcm');
        $this->load->model('common_model');

        //get the request headers and request body
        $this->request_headers = $this->input->request_headers();
        //$inputJSON = file_get_contents('php://input');
        $inputJSON = $_POST;
        //$this->request_array = json_decode($inputJSON, TRUE);
        $this->request_array = $_POST;
    }
    
    
    private function _set_failes($msg = '') {
        $data['status'] = $this->failed_string;
        $data['message'] = $msg;
        return $data;
    }

    function signup(){
        //print_r($this->request_array);
        //echo $this->request_array['email'];
        //die();
        if(isset($this->request_array['email'])) {
            $login_details ['first_name'] = $this->request_array['first_name'];
            $login_details ['last_name'] = $this->request_array['last_name'];
            $login_details ['email'] = $this->request_array['email'];
            $login_details ['password'] = $this->request_array['password'];
            $login_details ['logintype'] = $this->request_array['logintype'];
            $login_details ['dateofbirth'] = $this->request_array['dateofbirth'];
            $login_details ['country'] = $this->request_array['country'];
            $login_details ['gender'] = $this->request_array['gender'];
            $data = $this->dcm->signup_user($login_details);
        } else {
            $data = $this->_set_failes("Signup Failed");
        }
        $this->common_model->render($data);
    }
    
    function login() {
	if (isset($this->request_array['email'])) {
            $login_details ['email'] = $this->request_array['email'];
            $login_details ['password'] = $this->request_array['password'];
            $data = $this->dcm->login_user($login_details);
        } else {
            $data = $this->_set_failes("Login Failed");
        }
        $this->common_model->render($data);
    }
    
    function activate_account($hashkey){
        
        if($hashkey != ""){
            
            $this->db->where('activate_code', $hashkey);
            $query = $this->db->get('User');
            $result = $query->row();
            
            $numrows = $query->num_rows();
            if($numrows > 0){
                
                if($result->verified == 0){
                $up_array = array(
                     'verified' =>1
                );
                $this->db->where('activate_code', $hashkey);
                $this->db->update('User', $up_array); 
                
                echo "<h3>Account Activated Successfully.</h3>";
                die();
                }else{
                echo "<h3>Account Already Activated.</h3>";
                die();    
                }
            
            }else{
               echo "<h3>Account Doesnot Exists !!!</h3>";
                die();
            }
            
        }
        
    }

    function forgotpassword(){
        if (isset($this->request_array['email'])) {
            $login_details ['email'] = $this->request_array['email'];
            $data = $this->dcm->forgotpassword($login_details);
        } else {
            $data = $this->_set_failes("Login Failed");
        }
        $this->common_model->render($data);
    }
   
   function getmy_connections(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getmyconnections($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
   
    function getuser_connections(){
        $auth_key = @$this->request_headers['Api-key'];
        $user_id=$this->uri->segment(3);
        if (isset($auth_key)) {
            
            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getuserconnections($user_id);
            }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
    }
    
    
    function getallnotification(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {
            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getallnotification($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
        
    }
    
    function sharemyitem(){ 
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {
            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->sharemyitem($auth_key, $this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    
    
    
    function sendmessage(){
        $auth_key = @$this->request_headers['Api-key'];
        $receiver_id = $this->request_array['receiver_id'];
        $message = $this->request_array['message'];
        
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $userid = $this->dcm->getIdByAuthKey($auth_key);
                $post_array = array(
                    'MessageText' => $message,
                    'MessageSender' => $userid,
                    'MessageReceiver' => $receiver_id,
                    'message_send_date' => date("Y-m-d H:i:s")
            
                    );
                
                $data = $this->dcm->save_message($auth_key, $post_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
        
    }
    
    function getallshares(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getallshares($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }

    function getmyshares(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getmyshares($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    function postquestion(){
        $auth_key = @$this->request_headers['Api-key'];
        
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->postquestion($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
   
        
    }
    
    
    function changepassword(){
        $auth_key = @$this->request_headers['Api-key'];
        
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->changepassword($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
    }
    
    
    function sharemytable(){
        $auth_key = @$this->request_headers['Api-key'];
        
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->sharemytable($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
        
    }
    
    function getallquestion(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getallquestion($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        }else{
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);

    }
    
    function getmyquestion(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getmyquestion($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        }else{
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    
    function postanswer(){
       $auth_key = @$this->request_headers['Api-key'];
        if(isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->postanswer($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        }else{
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data); 
    }
    
    function getquestionbyid(){
        $auth_key = @$this->request_headers['Api-key'];
        if(isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getquestionbyid($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        }else{
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    function getsingleuserdetails(){
        $auth_key = @$this->request_headers['Api-key'];
        if(isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getsingleuserdetails($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        }else{
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
       
    function invite(){
        $auth_key = @$this->request_headers['Api-key'];
        if(isset($auth_key)) {
              if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->invite($auth_key,$this->request_array);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        }else{
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    function getallmessage(){
        $auth_key = @$this->request_headers['Api-key'];
        $sender_id = @$this->request_array['user_id'];
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getall_message($auth_key, $sender_id);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    
    function getalllatestmessages(){
        $auth_key = @$this->request_headers['Api-key'];
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->getalllatestmessages($auth_key);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    function update_connections(){
        $auth_key = @$this->request_headers['Api-key'];
        $my_connections = @$this->request_array['my_connection'];
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->update_connections($auth_key, $my_connections);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    
    function locationupdate(){ 
        $auth_key = @$this->request_headers['Api-key'];
        $latitude = @$this->request_array['latitude'];
        $longitude = @$this->request_array['longitude'];
        $geofenceArea = @$this->request_array['geofenceArea'];
        $device_id = @$this->request_array['device_id'];
        $network_id = @$this->request_array['network_id'];
        $post_data = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'GeofenceArea' => $geofenceArea,
            'device_id' => $device_id,
            'network_id' => $network_id
        );
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->update_location($auth_key, $post_data);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
    }
    
    
    function profileupdate(){ 
                $auth_key = @$this->request_headers['Api-key'];
                if($this->common_model->isValidApi($auth_key))
		{
				$my_id = $this->dcm->getIdByAuthKey($auth_key);
                                $post_array = array(
                                    'FirstName' => @$this->request_array['first_name'],
                                    'LastName' => @$this->request_array['last_name'],
                                    'dateofbirth' => @$this->request_array['dateofbirth'],
                                    'country' => @$this->request_array['country'],
                                    'gender' => @$this->request_array['gender'],
                                );
                                
                                
                                if (isset($_FILES['uploaded_file']['name']) && !empty($_FILES['uploaded_file']['name'])) {
                                // do_upload

                                
                                
                                
				$file_name = uniqid().".jpg";
                                $post_array['ProfilePhoto'] = $file_name;
				$config['upload_path'] = $this->config->item('image_path');
				$config['allowed_types'] = '*'; 
				$config['max_size']	= '*';
				$config['max_width']  = '*';
				$config['max_height']  = '*';
				$config['file_name']  = $file_name;
				$this->load->library('upload', $config);

				if(!$this->upload->do_upload('uploaded_file'))
				{
                                    
                                    
					$data['status'] = $this->failed_string;
					$data['message'] =  "Item image upload failed";
					$data['url'] = "";
					//$this->common_model->render_http($data);
				}
				else
				{
					//$data = array('upload_data' => $this->upload->data());
					//print_r( $this->upload->data());
					$data['url'] = base_url()."uploaded/".$file_name;
					
				}
                                
                                }
                                
                                        $update_profile = $this->dcm->update_profile($my_id, $post_array);
                                        $data['result'] = $this->dcm->get_userdetails($my_id, $auth_key);
                                        $data['status'] = $this->success_string;
                                        $data['message'] = 'Profile updated successfully'; 
                                        
					
		
		
		}else{
            $data = $this->_set_failes("In valid API-KEY");
            }
		
		$this->common_model->render($data);
    }
    
    
   function getall_users(){
       $auth_key = @$this->request_headers['Api-key'];
        $latitude = @$this->request_array['latitude'];
        $longitude = @$this->request_array['longitude'];
        $geofenceArea = @$this->request_array['geofenceArea'];
        $post_data = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'GeofenceArea' => $geofenceArea
        );
        if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
                $data = $this->dcm->get_allusers($auth_key, $post_data);
              }else{
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
   } 
   
   
   function get_property_status(){ 
       $auth_key = @$this->request_array['api_key'];
       $user_type = @$this->request_array['user_type'];
       $property_ids = @$this->request_array['property_ids'];
       if (isset($auth_key)) {

            if($this->common_model->isValidApi($auth_key)) {
               if($user_type == "R"){ 
                $data = $this->dcm->getrecent_status($auth_key, $property_ids);
                
               }
              } else {
                $data = $this->_set_failes("In valid API-KEY");
            }
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
       $this->common_model->render($data);
       
       
       //print_r($property_ids);
       //die();
       
   } 
}