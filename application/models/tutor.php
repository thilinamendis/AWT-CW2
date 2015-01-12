<?php

class Tutor extends CI_Model {

        function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
        //answer question
        public function answerQ($args)
        {
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);  
            
            $decodeHTMLentityB= html_entity_decode($args['aBody']);
            $decodeURLB =  urldecode($decodeHTMLentityB); 
            //set date
            $timeVal = date( "Y-m-d H:i:s", mktime(0, 0, 0));            
            $aVotes = 0;
            $aStatus = 1;       
                   
            //same conditions as posting a question
            if (!isset($args['uId']) || !isset($args['qTitle']) || !isset($args['aBody'])) {
			
		return false;
            }
           
            $this->db->insert('answers',array('questionTitle' => $decodeURLQT, 'userId' => urldecode($args['uId']), 'answer' => $decodeURLB,'status' => $aStatus,'postDate' => $timeVal,'status' => $aStatus,'votes' => $aVotes));
            //update auther of the queation
            $this->updateQNoti($decodeURLQT);            
            
            return true;
        }
        
        //update notification
        public function updateQNoti($qTitle){
                        
            $this->db->where('questionTitle', $qTitle);
            
            $this->db->update('questions', array('seenNoti' => '1'));            
            
        }
        //add a new tag
        public function addTag($args)
        {                       
            //check if all the necessary data is given
            if (!isset($args['tagD']) || !isset($args['tagN'])) {
			
		return false;
            }
            
            $this->db->where('tagName', urldecode($args['tagN']));      
            $this->db->from('tags');
            $result = $this->db->count_all_results(); 
            //insert 
            
            if($result > 0){
                return false;
            }else{
                $this->db->insert('tags',array('tagName' => urldecode($args['tagN']), 'tagDesc' => urldecode($args['tagD'])));
                return true;
            }
            
        }
}

