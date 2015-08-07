<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    var $gen_contents = array();
    var $username = "";
    var $password = "";

    public function __construct() { //die("test");
        parent::__construct();
        $this->load->helper("form");
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function login() {
        if (!empty($_POST)) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Email', 'required|max_length[50]|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');


            if ($this->form_validation->run() == FALSE) {// form validation
            } else {
                $this->_init_user_details();
                $login_details['username'] = $this->username;
                $login_details['password'] = $this->password;
                $this->session->set_flashdata('success', "Login successfully");
                if ($this->authentication->process_admin_login($login_details)) {
                    redirect("admin/dashboard");
                } else {
                    $this->session->set_flashdata('error', "Login failed");
                    redirect("welcome");
                }
            }
        }
        $this->load->view('welcome_message');
    }

    function logout() {
        if ($this->authentication->process_logout()) {
            redirect("welcome");
        }
    }

    function _init_user_details() {
        $this->username = $this->common_model->safe_html($this->input->post("username"));
        $this->password = $this->common_model->safe_html($this->input->post("password"));
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */