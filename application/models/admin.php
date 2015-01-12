<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    class Admin extends CI_Model {

        function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        //get reported questions
        public function getReportedQ($args){    
                                
            $start = (int)$args['dataLimitStart'];            
            $dtPerPage = (int)$args['dataCount'];
            //limit the data select to display on a page
            $this->db->limit($dtPerPage, $start);
            $result = $this->db->get('questionreport');                                     
            return $result->result_array();  
        }
        //delete reported question
        public function deleteReportedQ($args){
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);  
            
            $this->db->where('questionTitle', $decodeURLQT);
            $this->db->delete('questions'); 
            $this->deleteQReport($args);
            $this->deleteReportedQA($decodeURLQT);
            return true;
        }
        //delete corresponding answers of the question
        public function deleteReportedQA($questionTitle){
            
            $this->db->where('questionTitle', $questionTitle);
            $this->db->delete('answers'); 
            
            return true;
        }
        //delete the user report
        public function deleteQReport($args){
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT); 
            
            $this->db->where('questionTitle', $decodeURLQT);
            $this->db->delete('questionreport');            
            
        }
        //promote user to admin
        public function promoteUser($args){
                                    
            $this->db->where('email', urldecode($args['uEmail']));            
            $this->db->update('users', array('userType' => 'A'));             
            return true;
        }
        //get number of question reports to generate pagination
        public function getReportQCount($args){
                
            $this->db->from('questionreport');
            $result = $this->db->count_all_results();                   
            
            // return the results as an array - in which each selected row appears as an array           
            return $result;          
        }
        //get number of user report counts to create pagnation
        public function getReportUCount($args){
                
            $this->db->from('users');
            $this->db->where('isReported', 1);
            $result = $this->db->count_all_results();                   
            
            // return the results as an array - in which each selected row appears as an array           
            return $result;          
        }
        //select reported users
        public function getReportedU($args){    
                                   
            $start = (int)$args['dataLimitStart'];            
            $dtPerPage = (int)$args['dataCount'];
            $this->db->limit($dtPerPage, $start);
            $this->db->where('isReported', 1);
            $result = $this->db->get('users');            
            return $result->result_array();  
        }
        
        //delete user report
        public function deleteUReport($args){
            $this->db->where('username', urldecode($args['un']));
            $result = json_encode($this->db->get('users')->result_array());
            $resultObj = json_decode($result);
            $userID = $resultObj[0]->id;
            
            $this->db->where('username', urldecode($args['un']));
            $this->db->delete('users');   
            $this->deleteUA($userID);
            $this->deleteUQ($userID);
            return true;
        }
        
        public function deleteUQ($userID){
            $this->db->where('userId', $userID);
            $this->db->delete('questions');            
            return true;
        }
        
        public function deleteUA($userID){
            $this->db->where('userId', $userID);
            $this->db->delete('answers');            
            return true;
        }
        
    }
?>