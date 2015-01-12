<?php

class User extends CI_Model {

    function __construct() {
	parent::__construct();
	$this->load->database();
    }

    function register($name, $username, $pwd) {
	// is username unique?
	$res = $this->db->get_where('users', array('username' => $username));
	if ($res->num_rows() > 0) {
	    return 'Username already exists';
	}
	// username is unique
	$hashpwd = sha1($pwd);
	$data = array('name' => $name, 'username' => $username,
	    'password' => $hashpwd);
	$this->db->insert('users', $data);
	return null; // no error message because all is ok
    }

    function login($username, $pwd) {
	$this->db->where(array('username' => $username, 'password' => sha1($pwd)));
	$res = $this->db->get('users', array('name'));
	if ($res->num_rows() != 1) { // should be only ONE matching row!!
	    return false;
	}
	// remember login
	$session_id = $this->session->userdata('session_id');
	// remember current login
	$row = $res->row_array();
	$this->db->insert('logins', array('name' => $row['name'], 'session_id' => $session_id));


	return $res->row_array();
    }

    function is_loggedin() {
	$session_id = $this->session->userdata('session_id');
	$res = $this->db->get_where('logins', array('session_id' => $session_id));
	if ($res->num_rows() == 1) {
	    $row = $res->row_array();
	    return $row['name'];
	} else {
	    return false;
	}
    }
    
    public function changePassword($username, $oldpassword, $newpass) {
    
	$res = $this->db->get_where('users', array('username' => $username ));
	if ($res->num_rows() == 1) {
	    $row = $res->row_array();
	    if ($row['password'] == sha1($oldpassword)) {
		$row['password'] = sha1($newpass);
		$this->db->where('username', $username);
		$this->db->update('users', $row);
		return true;
	    }
	    return false;
	} else {
	    return false;
	}
        return false; 
    }
    //obtain user type of a given user
    public function get_UserType($usernameVal) {
        $this->db->select('userType');
	$res = $this->db->get_where('users', array('username' => $usernameVal ));
        //if there are 1 rows retuned, get the result and encode to json
	if ($res->num_rows() == 1) {
	    return json_encode($res->result());
	} else {
	    return false;
	}
        return false; 
    }
    //get user name of the selected user
    public function getUserName($args) {
        
        $this->db->select('userName');
	$res = $this->db->get_where('users', array('id' => $args['id'] ));
	if ($res->num_rows() == 1) {
            //if there are 1 rows retuned, get the result and encode to json
	    return $res->result_array();      
	} else {
	    return false;
	}
        return false;
    }
    //obtain 1 user
    public function searchUser($args){
            //get user info
            $userInfoObj = json_decode($this->getUserId($args['un']));
            //extract user id
            $userId = $userInfoObj[0]->id;
            
            $this->db->where('id', $userId);
            $result = $this->db->get('users');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
    }
    //search questions by tag
    public function searchTag($args){
                        
            $this->db->like('tags', urldecode($args['tags']));
            $result = $this->db->get('questions');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
    }
    //search users via a like clause
    public function searchUserList($args){
                        
            $this->db->like('username', urldecode($args['un'])); 
            $result = $this->db->get('users');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
    }
    
}

?>