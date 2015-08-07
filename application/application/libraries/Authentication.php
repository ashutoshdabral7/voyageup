<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authentication
 *
 * @author vaio
 */
class Authentication {

    //put your code here

    function Authentication() {
        $this->CI = & get_instance();
    }

    /**
     * To avoid the unauthorized access
     * 
     * @package		CodeIgniter
     * @author		
     * @link		http://crashcourseonline.com
     * @return unknown
     */
    function UserHasAccess($required_role) {

        if ($required_role == 'admin') {
            if ($this->CI->session->userdata('USERTYPE') != 'A')
                return FALSE;
        }
        if ($required_role == 'normal') {
            if ($this->CI->session->userdata('USERTYPE') != 'N')
                return FALSE;
        }
        return TRUE;
    }

    
    /*
     * check is user logged in and in admin or normal user
     * 
     */
    function check_logged_in($user_type = "normal", $status = 1) {
        switch ($user_type) {
            case "normal":
                break;

            case "admin":
                if (!$this->CI->session->userdata('USERID'))
                    return FALSE;
                else if (!$this->UserHasAccess('admin'))
                    return FALSE;

                return TRUE;
                break;

            default:
                return FALSE;
        }
    }

    function process_admin_login($login = NULL, $request = '') {

        if (!is_array($login) || 0 >= count($login)) {
            return FALSE;
        }

        $username = $login['username'];
        $password = $login['password'];

        $this->CI->db->select("user_id AS USERID, ud_f_name AS FIRST_NAME,ud_nick_name AS NICKNAME, ud_email AS EMAIL,");
        $this->CI->db->where('ud_email', $username);
        $this->CI->db->where('passcode', md5($password));
        $select_query = $this->CI->db->get('user_details');

        if (0 < $select_query->num_rows()) {
            $row = $select_query->row();
            $session_data = array(
                'USERID' => $row->USERID,
                'NICKNAME' => $row->NICKNAME,
                'FIRST_NAME' => $row->FIRST_NAME,
                'EMAIL' => $row->EMAIL,
                'USERTYPE' => 'A'
            );
            $this->CI->session->set_userdata($session_data);
            return true;
        } else {
            return FALSE;
        }
    }
    
    function process_logout(){
        $session_data = array(
                'USERID' =>'',
                'NICKNAME' => '',
                'FIRST_NAME' => '',
                'EMAIL' => '',
                'USERTYPE' => ''
            );
        $this->session->unset_userdata($session_data);

        return TRUE;
    }

}

?>
