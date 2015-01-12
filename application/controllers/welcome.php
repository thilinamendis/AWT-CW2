<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
                $this->load->model('student');
                $this->load->model('user');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
                        $data['user_id']	= '';
			$data['username']	= '';                        
                        $data['usertype']	= '';
			$this->load->view('/pub/home', $data);
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
                        $userTypeVal = json_decode($this->user->get_UserType($data['username']));
                        $data['usertype']	= $userTypeVal[0]->userType;
                        
                        if($data['usertype'] == 'A'){
                            $this->load->view('admin', $data);
                        }else{
                            $this->load->view('/pub/home', $data);
                        }
                        //$this->load->view('/pub/searchq', $data);
		}
	}
        
        
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */