<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Item
 *
 * @author sajesh
 */
class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
    }

    function getallusers() {
        $all = $this->common_model->selectAll('user_details',array('status !='=>3));
        return $all;
    }

    function add_user($user_data) {

        // $insert_id = $this->common_model->save('user_details', $user_data);
        $access_token = 'CAAJxoAquPckBALoZC2eWWLmmm4bAGp1tHDJT5A6XFJiNjQQjdBJSnySXdoz7VYFEzFz6PEQcCdZAefyqtizYZBYUTvMLz5aQ6yr1XZBKK7mZCxwuCi7Id1U38UqYb1LCfPXf9bVsORGeR2teTqLAmAQJVaGEXtkJXx6NqniFZBT3uo33w61waIMvsDndQBKkk66ZBsJ2aWhnVS2N1IeVXCZC3KSF9chglA8ZD';
        $user_details = "https://graph.facebook.com/me?access_token=" . $access_token;
        $response = file_get_contents($user_details);
        $response = json_decode($response);
        print_r($response);

        //return $insert_id;
    }

    function userExist($user_email) {

        $this->db->where('ud_email', $user_email);
        $this->db->select('*');
        $query = $this->db->get('user_details');
        return $query->num_rows();
    }

    function getmyfriends($my_id) {
        $this->db->select('ud.ud_f_name,ud.ud_nick_name,ud.user_id');
        $this->db->from('user_friends uf');
        $this->db->join('user_details ud', 'uf.uf_id = ud.user_id OR uf.ud_id = ud.user_id');
        $this->db->where('uf.ud_id ', $my_id);
        $this->db->or_where('uf.uf_id ', $my_id);
        $this->db->where('uf.uf_approval_status ', USER_FRIEND_ACPT);
        $this->db->where('ud.user_id != ', $my_id);
        $query = $this->db->get();
        $frnds = $query->result_array();


        return $frnds;
    }

    function getmyfriends_new($my_id) {
        $this->db->select('ud.user_id as user_id, ud.ud_f_name as first_name, ud.ud_l_name as last_name, ud.ud_nick_name as nick_name, ud.user_pic');
        $this->db->from('user_details ud');
        $this->db->join('user_friends uf', 'ud.user_id = uf.ud_id or ud.user_id = uf.uf_id');

        $this->db->where("(uf.ud_id = $my_id or uf.uf_id = $my_id)");
        //$this->db->or_where('uf.uf_id ', $my_id, true);

        $this->db->where('ud.user_id != ', $my_id);
        $this->db->where('uf.uf_approval_status', 1);
        $this->db->group_by('ud.user_id');
        $query = $this->db->get();

        $frnds = $query->result_array();

        //echo "<pre>";
        //print_r($frnds);
        //die();

        return $frnds;
    }
	
	
	function getmyrequestlist($my_id) {
        $this->db->select('ud.user_id as user_id, ud.ud_f_name as first_name, ud.ud_l_name as last_name, ud.ud_nick_name as nick_name, ud.user_pic');
        $this->db->from('user_details ud');
        $this->db->join('user_friends uf','ud.user_id = uf.ud_id or ud.user_id = uf.uf_id' );
		
		$this->db->where("uf.uf_id = $my_id" );
        //$this->db->or_where('uf.uf_id ', $my_id, true);
		
		$this->db->where('ud.user_id != ', $my_id);
		$this->db->where('uf.uf_approval_status', 0);
		$this->db->group_by('ud.user_id');
		$query = $this->db->get();
		
        $frnds = $query->result_array();
        
		 //echo "<pre>";
		//print_r($frnds);
		 //die();
   
        return $frnds;
    }

    function activate_user($user_id) {
        $this->db->update('user_details', array('status' => 1), array('user_id' => $user_id));
        if ($this->db->affected_rows() == 1) {

            return TRUE;
        } else
            return FALSE;
    }
    
     function deactivate_user($user_id) {
        $this->db->update('user_details', array('status' => 2), array('user_id' => $user_id));
        if ($this->db->affected_rows() == 1) {

            return TRUE;
        } else
            return FALSE;
    }
    
    function me($user_id){
        
        $me = $this->common_model->selectAll('user_details',array('user_id' => $user_id));
        return $me;
    }

}

?>
