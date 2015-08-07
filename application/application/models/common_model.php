<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function safe_html($input_field) {
        return htmlspecialchars(trim(strip_tags($input_field)));
    }

    public function response($output) {
        header('content-type:application/json');
        $output_string = json_encode($output);
        echo $output_string;
    }

    public function render($view_data, $page = 'mobile/display_json') {
        $content['data'] = json_encode($view_data);
		//print_r($content['data']); die("reached");
        $this->load->view($page, $content);
    }
	
	public function render_http($view_data) {
        $content['data'] = json_encode($view_data);
        $this->load->view('mobile/httpreq', $content);
    }
	
	public function render_http_iphone($view_data) {
        $content['data'] = json_encode($view_data);
        $this->load->view('mobile/httpreq_iphone', $content);
    }
	

    // Common save
    function saveData($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    //Select
    function selectAll($table, $where = array()) {
        $this->db->where($where);
        $this->db->select('*');
        $query = $this->db->get($table);
        return $query->result();
    }
	
	
	function selectAll_itemrand($table, $where = array(), $limit) {
        $this->db->where($where);
        $this->db->select('item_id');
        if($limit != 0){
		$this->db->limit($limit);
		}
		$this->db->order_by('item_id', 'RANDOM');
		$query = $this->db->get($table);
        return $query->result();
    }
	
	 function selectAll_custom($table, $where) {
        $this->db->where($where);
        $this->db->select('*');
        $query = $this->db->get($table);
        return $query->result();
    }

    /**
     * update_auth_key
     *
     * @return authKey
     * @author Sajesh
     * */
    public function gen_auth_key() {

        $string = "ABcdEfgHIJkLMnopqRStuvwxyz123456789";
        $str = "";
        for ($i = 0; $i < 25; $i++) {
            $pos = rand(0, 34);
            $str.= $string[$pos];
        }

        return $str;
    }

    /*
     * 
     */

    function isValidApi($api){
        $this->db->where('auth_key', $api);
        $this->db->select('*');
        $query = $this->db->get('User');
        return $query->num_rows();
    }

}

?>
