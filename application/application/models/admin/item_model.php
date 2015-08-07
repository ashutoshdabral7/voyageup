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
class Item_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
    }

    function getallitems() {
        $all = $this->common_model->selectAll('master_game_item');
        return $all;
    }
	
	function getallitems_active() {
        $all = $this->common_model->selectAll('master_game_item', array('item_status' => 1));
        return $all;
    }
	
	function getallitems_active_rand($count =0){
        $all = $this->common_model->selectAll_itemrand('master_game_item', array('item_status' => 1), $count);
        return $all;
    }

    function getitembyid($item_id) {
        $all = $this->common_model->selectAll('master_game_item', array('item_id' => $item_id));
        return $all;
    }

    function add_item($item_data) {
        $insert_id = $this->common_model->saveData('master_game_item', $item_data);
        return $insert_id;
    }

    function delete_item($item_id) {

        $this->db->delete('master_game_item', array('item_id' => $item_id));
    }

    function update_item($updata) {
        if (!isset($updata['item_image']))
            $this->db->update('master_game_item', array('item_name' => $updata['item_name'], 'item_desc' => $updata['item_desc']), array('item_id' => $updata['item_id']));
        else
            $this->db->update('master_game_item', array('item_name' => $updata['item_name'], 'item_desc' => $updata['item_desc'], 'item_image' => $updata['item_image']), array('item_id' => $updata['item_id']));

        if ($this->db->affected_rows() == 1) {

            return TRUE;
        } else
            return FALSE;
    }
	
	function update_status($itemid, $data){
	     //print_r($data); die();
		if(($itemid != "" )&& (!empty($data)))
		{
			$this->db->where('item_id', $itemid);
		    $this->db->update('master_game_item', $data); 
		}
	}
	
	function getallconditions_active(){
		$all = $this->common_model->selectAll('game_conditions', array('status' =>1));
        return $all;
	
	}
        
        function getItemBygameId($game_id){
            $this->db->select('mgi.item_name as item_name');
            $this->db->from('game_items_list gil')
                     ->where('game_id',$game_id);
            $this->db->join('master_game_item mgi','mgi.item_id = gil.item_id');
            $query = $this->db->get();
            
           return $query->result_array();
            
        }

}

?>
