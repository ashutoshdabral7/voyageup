<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of game_model
 *
 * @author vaio
 */
class game_model extends CI_Model {

    //put your code here

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
    }

    function startnewgame($my_id, $item_list, $players_list, $start_date, $condition) {

        $game_list = array(
            'g_created_user_id' => $my_id,
            'g_item_list' => $item_list,
            'g_start_date' => $start_date,
            'g_end_condition' => $condition
        );
        $game_id = $this->common_model->saveData('game', $game_list);

        if ($game_id > 0) {
            //insert players id
            foreach ($players_list as $player_id) {
                $participant = array(
                    'g_id' => $game_id,
                    'player_userid' => $player_id
                );

                $participant_id = $this->common_model->saveData('game_players', $participant);

                foreach ($item_list as $item_id) {

                    $play_status = array(
                        'player_userid' => $player_id,
                        'g_id' => $game_id,
                        'item_id' => $item_id,
                        'item_upload_status' => 0,
                        'item_isarmored' => 0,
                        'item_image' => ''
                    );
                }

                $participant_id = $this->common_model->saveData('play_status', $play_status);
            }
        }

        return;
    }

    function getAllActiveGame() {

        $all = $this->common_model->selectAll('game', array('g_status' => 1));
        return $all;
    }

    function getAllGame() {

        $all = $this->common_model->selectAll('game');
        return $all;
    }

}
