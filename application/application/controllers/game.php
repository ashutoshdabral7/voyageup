<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of game
 *
 * @author vaio
 */
class Game extends CI_Controller {

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
        $inputJSON = file_get_contents('php://input');
        $this->request_array = json_decode($inputJSON, TRUE);
		//print_r($this->request_headers); 
		//print_r($this->request_array);
		//die();
    }

//FIXME

	 private function _set_failes($msg = '') {
        $data['status'] = $this->failed_string;
        $data['message'] = $msg;
        return $data;
    }

    function postNewGame() { 
      
        $auth_key = @$this->request_array['api_key'];
        $item_list = @$this->request_array['game']['items_list'];
        $players_list = @$this->request_array['game']['friends_list'];
		$item_list_count = @$this->request_array['game']['number_of_items'];
		$hunt_type = @$this->request_array['game']['hunt_type'];
		//$r = print_r($item_list, true);
		//$p = print_r($players_list, true);
		//mail("vnair.abhinand@gmail.com, deepaksasindran480@gmail.com, hvishnu999@gmail.com","My subject",$r.$p);
		//die();
        $start_date  = @$this->request_array['game']['timestamp_start'];
		
		$end_date  = @$this->request_array['game']['timestamp_end'];
		//echo "startdate ".$start_date."<br/>end date".$end_date; die();
        $condition = @$this->request_array['game']['condition_id'];
        //echo $condition; die();
		//print_r($players_list ); 
		//print_r($players_list); 
		//echo "startdate: ".$start_date;
		//echo "<br>";
		//echo "end date".$end_date;
		//echo gmdate('Y-m-d', $start_date);
		//echo date("Y-m-d", $start_date); die();
		//die();  
        if ($this->common_model->isValidApi($auth_key)) {
			$data = $this->dcm->startgame($auth_key, $item_list, $players_list, $start_date, $end_date, $condition, $item_list_count, $hunt_type);
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
    }

	function getGameInvites(){
		$auth_key = @$this->request_array['api_key'];
		if ($this->common_model->isValidApi($auth_key)) {
			$result = $this->dcm->get_all_activeInvitations($auth_key);
			//print_r($result);
			//die("reached");
			if(!empty($result))
			{
				$data['status'] = $this->success_string;
				$data['message'] = "List of games invited";
				$data['data'] = $result;
				
				//print_r($data['data']); die();
				
			}
			else{
				$data['status'] = $this->failed_string;
				$data['message'] = "No invitations available";
			}
			
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
		
	
	}
	
	
	function getGame(){
		$auth_key = @$this->request_array['api_key'];
		$game_id = @$this->request_array['game_id'];
		if ($this->common_model->isValidApi($auth_key)) {
			$result = $this->dcm->get_gameData($auth_key, $game_id);
			if(!empty($result))
			{
				$data['status'] = $this->success_string;
				$data['message'] = "List of games invited";
				$data['game'] = $result;
				//print_r($data['game']); die("reached");
				
				
			}
			else{
				$data['status'] = $this->success_string;
				$data['message'] = "";
			}
			
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
	
	
	}
	
	function getItemHoldersList(){
	
		$auth_key = @$this->request_array['api_key'];
		$game_id = @$this->request_array['game_id'];
		$item_id = @$this->request_array['item_id'];
		
		if ($this->common_model->isValidApi($auth_key)) {
			$result = $this->dcm->get_itemHolder_data($auth_key, $game_id, $item_id);
			if(!empty($result))
			{
				$data['status'] = $this->success_string;
				$data['message'] = "List of item holders";
				$data['item_holders'] = $result;
			}
			else{
				$data['status'] = $this->success_string;
				$data['message'] = "No member posted this item yet!";
			}
			
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
	
	
	
	}
	
	
	function rejectGameInvitation(){
		$auth_key = @$this->request_array['api_key'];
		$game_id = @$this->request_array['game_id'];
		
		if ($this->common_model->isValidApi($auth_key)) {
			$result = $this->dcm->reject_game($auth_key, $game_id);
			if($result == TRUE)
			{
				$data['status'] = $this->success_string;
				$data['message'] = "Rejected successfully";
				$data['data'] = $result;
				
				//print_r($data['data']); die();
				
			}
			else{
				$data['status'] = $this->failed_string;
				$data['message'] = "Game not rejected due to some server issues";
			}
			
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
	
	
	
	}
	
	function snipeGameItem(){
		$auth_key = @$this->request_array['api_key'];
		$targetUser_id = @$this->request_array['target_user'];
		$game_id = @$this->request_array['game_id'];
		$item_id = @$this->request_array['item_id'];
		
		if ($this->common_model->isValidApi($auth_key)) {
			$result = $this->dcm->snipe_item($auth_key, $targetUser_id, $game_id, $item_id);
			if(is_array($result)){
				if(!empty($result))
				{
					$data['status'] = $this->success_string;
					$data['message'] = "Item sniped successfully";
					$data['data'] = $result;
				}
			}else{
				if($result == FALSE){
					$data['status'] = $this->failed_string;
					$data['is_missed'] = 1;
					$data['message'] = "Item is armoured. You have missed one sniper.";
				}
			}	
        } else {
            $data = $this->_set_failes("In valid API-KEY");
        }
        $this->common_model->render($data);
	
	
	
	}
	
    function upload_item(){ 
		
		$auth_key = @$this->request_headers['Api-key'];
		$flag = @$this->request_headers['Device-type'];
		//echo $auth_key ; die("reached");
		
		if($flag == "iphone"){
		$item_id = @$this->request_headers['Item-id'];
		$game_id = @$this->request_headers['Game-id'];
		}else{
		$item_id = $this->input->post('item_id');
		$game_id = $this->input->post('game_id');
		}
		if ($this->common_model->isValidApi($auth_key))
		{
				$my_id = $this->dcm->getIdByAuthKey($auth_key);
				$object_id = $this->dcm->get_object_id($game_id, $my_id, $item_id);
				
				$file_name = $game_id."_".$my_id."_".$object_id.".jpg";
				$config['upload_path'] = './images/items/';
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
					$update_game = $this->dcm->image_upload($game_id, $my_id, $item_id);
					//$data = array('upload_data' => $this->upload->data());
					//print_r( $this->upload->data());
					$data['status'] = $this->success_string;
					$data['message'] = "Item image uploaded successfully";
					$data['url'] = base_url()."images/items/".$file_name;
					//$this->common_model->render_http($data);
					
				}
		
		
		}else{
            $data = $this->_set_failes("In valid API-KEY");
			//$this->common_model->render($data);
        }
		
		
		if($flag == "iphone"){
		$this->common_model->render_http_iphone($data);
		}else{
		$this->common_model->render_http($data);
		}
        
		
    }

	function armourGameItem(){
		$auth_key = @$this->request_array['api_key'];
		$game_id = @$this->request_array['game_id'];
		$item_id = @$this->request_array['item_id'];
		$action =  @$this->request_array['action'];
		if ($this->common_model->isValidApi($auth_key))
		{
			$process = $this->dcm->do_armor_item($auth_key, $game_id, $item_id, $action);
			if($process == 1){
			$data['message'] = "Item armoured successfully";
			}else{
			$data['message'] = "Item armoured removed successfully";
			}
			$data['status'] = $this->success_string;
			
					
		}else{
            $data = $this->_set_failes("In valid API-KEY");
        }
			$this->common_model->render($data);
		
	}
	
	function finishGame(){
		$auth_key = @$this->request_array['api_key'];
		$game_id = @$this->request_array['game_id'];
		if ($this->common_model->isValidApi($auth_key))
		{
			$process = $this->dcm->finish_game($auth_key, $game_id);
			$data['status'] = $this->success_string;
			$data['message'] = "Game ended successfully";
		}else{
            $data = $this->_set_failes("In valid API-KEY");
        }
			$this->common_model->render($data);
	
	}
	
    function snipp_item(){
        
    }

}