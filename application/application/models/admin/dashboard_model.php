<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Description of dashboard_model
 *
 * @author vaio
 */
class Dashboard_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('admin/user_model');
        $this->load->model('admin/game_model');
    }
    
    
    function getdashData(){
        $data['user_count'] = count($this->user_model->getallusers());
        $data['new_game_count'] = count($this->game_model->getAllActiveGame());
        $data['game_count'] = count($this->game_model->getAllGame());
        return $data;
    }
}
