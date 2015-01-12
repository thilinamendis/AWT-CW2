<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    class Search extends CI_Controller {
        function __construct()
        {
            parent::__construct();            
            $this->load->model('student');             
            $this->load->helper('url');
        }

        public function _remap()
        {
            // first work out which request method is being used
            $request_method = $this->input->server('REQUEST_METHOD');
            switch (strtolower($request_method)) {
                case 'get' : $this->doGet(); break;                
		default:
                    show_error('Unsupported method',404); // CI function for 404 errors
                    break;
            }
        }
        public function doGet()
        {
            $args = $this->uri->uri_to_assoc(2);
            switch ($args['questions']) {
                case 'qstnSearch' :                    
                    $res = $this->student->getTopQ($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        
                        echo json_encode($res);
                    }
                    break;
                default:
                    show_error('Unsupported resource',404);
                    break;
            
            }
        }
        
    }
?>