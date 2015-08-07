<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of item
 *
 * @author vaio
 */
class Item extends CI_Controller {
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

        //get the request headers and request body
        $this->request_headers = $this->input->request_headers();
        $inputJSON = file_get_contents('php://input');
        $this->request_array = json_decode($inputJSON, TRUE);
    }

    private function _set_failes($msg = '') {
        $data['status'] = $this->failed_string;
        $data['message'] = $msg;
        return $data;
    }

    function getItemList() {
        $data = $this->dcm->getAllItemList();


        $this->common_model->render($data);
    }

}
