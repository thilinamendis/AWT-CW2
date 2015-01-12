<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    class Student extends CI_Model {

        function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        //ask question
        public function askQ($args)
        {
            //properly decode the the data so that special characters input by the user may get stored properly
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            
            $decodeHTMLentityB = html_entity_decode($args['qBody']);
            $decodeURLB =  urldecode($decodeHTMLentityB);
            
            //generate timestap             
            $timeVal = date( "Y-m-d H:i:s", mktime(0, 0, 0));    
            //set default status as 1-active and votes to zero
            $qVotes = 0;
            $qStatus = 1;       
            //check  if user id, question title, question body and questio tag is provided     
            if (!isset($args['uId']) || !isset($args['qTitle']) || !isset($args['qBody']) || $args['qTag']=='null') {
			
		return false;
            }
            
            $this->db->where('questionTitle', $decodeURLQT);      
            $this->db->from('questions');
            $result = $this->db->count_all_results(); 
            //insert 
            
            if($result > 0){
                return false;
            }else{
                //insert question
                $this->db->insert('questions',array('userId' => $args['uId'],'questionTitle' => $decodeURLQT,'questionBody' =>$decodeURLB,'tags' => urldecode($args['qTag']),'postDate' => $timeVal,'status' => $qStatus,'votes' => $qVotes));
                //get authors currect loylaty points
                $this->getUserCurRep($args);
                return true;
            }
            
            
        }
        
        public function getUserCurRep($args){
            
            $result = $this->db->get_where('users', array('id' => $args['uId']));   
            //get current user information and pass it to be updated
            $this->updateRep(json_encode($result->result_array()), $args['uId']);            
        }
        //reqrd user with 5 loyalty points for posting a question
        public function updateRep($result, $userId){
            
            $resultobj = json_decode($result);
            //exctract the user loyalty points and add 5
            $prevRep = $resultobj[0]->userLP;
            $totalRep = $prevRep + 5;
            //update loyalty points
            $this->db->where('id', $userId);
            $this->db->update('users', array('userLP' => $totalRep));
        }
        //retreive all question tags
        public function getQTag($args)
        {             
            $result = $this->db->get('tags');
            
            // return the results as an array - in which each selected row appears as an array
            return $result->result_array();
        }         
        
        //view question
        public function getQ($args)
	{
            //decode the selected question title 
            $decodeHTMLentityQT = html_entity_decode($args['questionTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            //retrive the question information            
            $this->db->where('questionTitle', $decodeURLQT);           
            $result = $this->db->get('questions');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();      
                
        }     
        //search question
        public function getSearchQ($args){
            
            $decodeHTMLentityQT = html_entity_decode($args['questionTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            //perform a like search to fetch search results
            $this->db->like('questionTitle', $decodeURLQT);         
            //set limits so that each page can call display the specific questions
            $start = (int)$args['dataLimitStart'];            
            $dtPerPage = (int)$args['dataCount'];
            //order by latest received and get the data
            $this->db->limit($dtPerPage, $start);            
            $this->db->order_by('postDate', 'desc');
            $result = $this->db->get('questions');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
        }
        //get the number or questions found to generate pagination
        public function getSearchQCount($args){
            $decodeHTMLentityQT = html_entity_decode($args['questionTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            
            $this->db->like('questionTitle', $decodeURLQT);      
            $this->db->from('questions');
            $result = $this->db->count_all_results();                   
            
            // return the results as an array - in which each selected row appears as an array           
            return $result;          
        }
        //get notifications related to posted questions
        public function getQNotifications($args){
            
            $this->db->where('userId', $args['uId']);  
            //get data which has seen noti 1-not seen
            $this->db->where('seenNoti', '1'); 
            $result = $this->db->get('questions');
            
            // return the results as an array - in which each selected row appears as an array    
            return $result->result_array();  
        }
        //get answer notifications
        public function getANotifications($args){
            
            $this->db->where('userId', $args['uId']);  
            $this->db->where('seenNoti', '1'); 
            $result = $this->db->get('answers');
            
            // return the results as an array - in which each selected row appears as an array    
            return $result->result_array();  
        }
        //perform voting for questions
        public function doQVote($args){
            //forst check if the current user has already voted for the question
            $isVoted = $this->getUserVotes($args);
            //if not
            if(!$isVoted){
                //perform votes up or down required by the user
                if($args['voteType'] == '1'){           
                    $totalVotes = (int)$args['cVotes'] + 1;   
                }else{
                    $totalVotes = (int)$args['cVotes'] - 1;
                }
                //decode the question title of the voted question 
                $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
                $decodeURLQT =  urldecode($decodeHTMLentityQT);
                //update the question with the new votes
                $this->db->where('questionTitle', $decodeURLQT);
                $this->db->update('questions', array('votes' => $totalVotes));
                //delete current vote from the vote tracking table
                $this->deleteCurrVote($args);
                //insert new vote to the vote tracking table
                $this->insertUserVote($args);
                //get current loyalty points of the user
                $repResultObj = json_encode($this->getUserReputation($args));
                //update user votes
                $this->updateVoteRep($args, $repResultObj, 'Q');
                //update notification
                $this->updateNoti($args);
                return true;
            }else{
                return false;
            }
        }   
        
        public function getUserVotes($args){
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            //check if the current user has voted teh same question before    
            $this->db->where('userId', $args['uId']);  
            $this->db->where('questionTitle', $decodeURLQT); 
            $this->db->where('voteType', $args['voteType']); 
            $result = $this->db->get('questionVotes');
            
            if ($result->num_rows() > 0) {
                return true;
            } else{
                return false;
            }
        }
        
        public function insertUserVote($args){
            //check if the required fields are sent 
            if (!isset($args['uId']) || !isset($args['qTitle']) || !isset($args['voteType'])) {

		return false;
            }
            //if sent insert the question vote to the vote tracking table
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            $this->db->insert('questionVotes',array('userId' => urldecode($args['uId']),'questionTitle' => $decodeURLQT,'voteType' => urldecode($args['voteType'])));

        }
        
        //delete current user vote
        public function deleteCurrVote($args){
             $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
             $decodeURLQT =  urldecode($decodeHTMLentityQT);
             $this->db->delete('questionVotes', array('userId' => urldecode($args['uId']),'questionTitle' => $decodeURLQT)); 
        }
        //update the update to question as unseen
        public function updateNoti($args){
             $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
             $decodeURLQT =  urldecode($decodeHTMLentityQT);
             $this->db->where('questionTitle', $decodeURLQT);
            
             $this->db->update('questions', array('seenNoti' => '1'));    
        }
        //update the question notification as seeen
        public function updateSeenQNoti($args){
             $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
             $decodeURLQT =  urldecode($decodeHTMLentityQT);
             
             $this->db->where('questionTitle', $decodeURLQT);
             $this->db->where('userId', urldecode($args['uId']));
             $this->db->update('questions', array('seenNoti' => '0'));   
             
             return true;
        }
        //update answer noification as seen        
        public function updateSeenANoti($args){
             $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
             $decodeURLQT =  urldecode($decodeHTMLentityQT);
            
             $this->db->where('questionTitle', $decodeURLQT);
             $this->db->where('userId', urldecode($args['uId']));
             $this->db->update('answers', array('seenNoti' => '0'));  
             
             return true;
        }
        
        //retreive answers for a viewed question
        public function getAnswers($args){
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            //get all columns from anwsers and users where answer posted user matches with the
            //the users in the user table
            $this->db->select('*');
            $this->db->from('answers');
            $this->db->where('questionTitle', $decodeURLQT);  
            $this->db->join('users', 'users.id = answers.userId');
            $this->db->order_by('acceptedAns', 'desc'); 
            $this->db->where('questionTitle', $decodeURLQT);  
            $result = $this->db->get();
            
            // return the results as an array - in which each selected row appears as an array    
            return $result->result_array();  
        }
        //get user reputation
        public function getUserReputation($args){
            
            $result = $this->db->get_where('users', array('username' => $args['OriUId']));            
            return $result->result_array();            
        }
        //update user reputation
        public function updateVoteRep($args, $currRep, $from){
            $resultobj = json_decode($currRep); 
            //update loyalty points or reputation based on question or answer
            if($from == 'Q'){
                $prevRep = $resultobj[0]->userLP;
            }else{
                $prevRep = $resultobj[0]->userRep;
            }
            
            if($args['voteType'] == '1'){           
                $totalVotes = (int)$prevRep + 1;   
            }else{
                $totalVotes = (int)$prevRep - 1;
            }
            
           
            $this->db->where('username', $args['OriUId']);
            if($from == 'Q'){
                $this->db->update('users', array('userLP' => $totalVotes));
            }else{
                $this->db->update('users', array('userRep' => $totalVotes));
            }
        }
        //accept posted answer
        public function updateAcceptAnswer($args){
            
            $this->updateAllUnanswered($args);
            
            $this->db->where('answerId', urldecode($args['ansId']));            
            $this->db->update('answers', array('acceptedAns' => 1, 'seenNoti' => 1));
            
            $this->getAcceptAnswer($args);
            return true;
        }
        //update all answers posted for the seectd question as not answred 
        public function updateAllUnanswered($args){
                        
            $this->db->where('questionTitle', urldecode($args['qTitle']));            
            $this->db->update('answers', array('acceptedAns' => 0, 'seenNoti' => 0));            
            $this->updateQasAnswered(urldecode($args['qTitle']));
        }
        //and accept the answer the author of the question select
        public function getAcceptAnswer($args){
            
            $result = $this->db->get_where('answers', array('answerId' => $args['ansId']));            
            $resData = json_encode($result->result_array());
            $this->getAnsweredUser($resData , $args['ansId']);
        }
         //update question as answered
        public function updateQasAnswered($qTitle){
                        
            $this->db->where('questionTitle', $qTitle);
            
            $this->db->update('questions', array('answered' => '1'));            
            
        }
        //get accepted answred users info
        public function getAnsweredUser($resData, $ansId){
            $dataObj = json_decode($resData);
            $userIdVal =  $dataObj[0]->userId;
            
            $result = $this->db->get_where('users', array('id' => $userIdVal));            
            $userData = json_encode($result->result_array());   
            $this-> getAnswerRewards($userData, $userIdVal, $resData);
        }
        //and reward the author of the accepted answer with reputation
        public function getAnswerRewards($userData, $uid, $resData){
            $resDataObj = json_decode($resData);
            $qTitleVal = $resDataObj[0]->questionTitle;
            $result = $this->db->get_where('answerrep', array('userId' => $uid, 'questionTitle' => $qTitleVal));
            if ($result->num_rows() == 0) {
                $this->updateRewardAnsweredUser($userData, $uid, $qTitleVal);
            }            
        }
        //update
        public function updateRewardAnsweredUser($resData, $uid, $qTitleVal){
            $dataObj = json_decode($resData);
            $userRepVal =  $dataObj[0]->userRep;
            $newUserRep = (int)$userRepVal + (int)2;
            
            $this->db->where('id', $uid);            
            $this->db->update('users', array('userRep' => $newUserRep)); 
            
            $this->insertRewardAnsweredUser($uid, $qTitleVal);
        }
        ///keep track of accepted answer
        public function insertRewardAnsweredUser($uid, $qTitleVal){
            $this->db->insert('answerrep',array('userId' => urldecode($uid),'questionTitle' => $qTitleVal));   
            
            $this->updateQuestionAsAnswered($qTitleVal);
        }
        //ark question as answered
        public function updateQuestionAsAnswered($qTitleVal){
                        
            $this->db->where('questionTitle', $qTitleVal);            
            $this->db->update('questions', array('answered' => 1));             
            
        }
        //get top 10 qiestions
        public function getTopQ($args){
            $decodeHTMLentityQT = html_entity_decode($args['questionTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            
            //limit the data get count            
            $start = (int)$args['dataLimitStart'];            
            $dtPerPage = (int)$args['dataCount'];
            $this->db->limit($dtPerPage, $start);     
            //get data according to the required criteria, Recent, Interesting, Unaswered
            if($args['sq'] == '1'){
                $this->db->like('questionTitle', $decodeURLQT);   
            }
            if($args['getAction'] == 'R'){
                $this->db->order_by('postDate', 'desc');
                $result = $this->db->get('questions');
            }
            if($args['getAction'] == 'I'){
                $this->db->order_by('votes', 'desc');
                $result = $this->db->get('questions');
            }
            if($args['getAction'] == 'U'){
                $this->db->where('answered', 0);  
                $this->db->order_by('postDate', 'desc');                
                $result = $this->db->get('questions');
            }
            
                       
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();   
        }
        //delete question
        public function deleteQ($args){
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            
            $this->db->where('questionTitle', $decodeURLQT);
            $this->db->delete('questions'); 
            
            $this-> deleteQA($decodeURLQT);
            
            return true;
        }
        //and delete answers posted in relation to that question
        public function deleteQA($decodeURLQT){
            
            $this->db->where('questionTitle', $decodeURLQT);
            $this->db->delete('answers'); 
         
        }
        //delete answer
        public function deleteA($args){
            $this->db->where('answerId', urldecode($args['aId']));
            $this->db->delete('answers'); 
            
            return true;
        }
        //update question
        public function updateQuestion($args){
            //check if necessary data is provided
            if (!isset($args['qTitle']) || !isset($args['qBody']) || $args['qTag']=='null') {
                return false;
            }else{
                $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
                $decodeURLQT =  urldecode($decodeHTMLentityQT);
                
                $decodeHTMLentityB = html_entity_decode($args['qBody']);
                $decodeURLB =  urldecode($decodeHTMLentityB);
                //update
                $this->db->where('questionTitle', $decodeURLQT);            
                $this->db->update('questions', array('questionTitle' => $decodeURLQT, 'questionBody' => $decodeURLB,'tags' => urldecode($args['qTag'])));  

                return true;
            }
        }
        //record question report during a report
        public function insertQReport($args){
            
            $decodeHTMLentityQT = html_entity_decode($args['qTitle']);
            $decodeURLQT =  urldecode($decodeHTMLentityQT);
            
            $this->db->insert('questionreport',array('questionTitle' => $decodeURLQT));
            
            return true;
        }
        //record answer report during a report
        public function insertAReport($args){
            $decodeHTMLentityA = html_entity_decode($args['aBody']);
            $decodeURLA =  urldecode($decodeHTMLentityA);
            
            $this->db->insert('answerreport',array('answerId' => urldecode($args['aId']), 'answer' => $decodeURLA));
            
            return true;
        }
        //record user report during a report
        public function insertUReport($args){
            $this->db->insert('userreport',array('username' => urldecode($args['uName']), 'userRep' => urldecode($args['uRep'])));
            
            return true;
        }       
        //vote an answer
        public function doAVote($args){
            //check if the user has voted for the same answer
            $isVoted = $this->getUserAVotes($args);
            //if not voted
            if(!$isVoted){
                //increase or decrease votes as necessary
                if($args['voteType'] == '1'){           
                    $totalVotes = (int)$args['cVotes'] + 1;   
                }else{
                    $totalVotes = (int)$args['cVotes'] - 1;
                }
                //update the votes
                $this->db->where('answerId', $args['aId']);
                //set a notification for the answer author
                $this->db->update('answers', array('votes' => $totalVotes, 'seenNoti' => 1));
                //delete current answer vote type if available
                $this->deleteCurrAVote($args);
                //insert new vote type
                $this->insertUserAVote($args);
                //get user current reputation
                $repResultObj = json_encode($this->getUserReputation($args));
                //update user reputation
                $this->updateVoteRep($args, $repResultObj, 'A');
                //$this->updateNoti($args);
                return true;
            }else{
                return false;
            }
        } 
        //check if user has voted for the answer
        public function getUserAVotes($args){
            $this->db->where('userId', $args['uId']);  
            $this->db->where('answerId', $args['aId']); 
            $this->db->where('voteType', $args['voteType']); 
            $result = $this->db->get('answervotes');
            
            if ($result->num_rows() > 0) {
                return true;
            } else{
                return false;
            }
        }
        //delete current vote type
        public function deleteCurrAVote($args){
             $this->db->delete('answerVotes', array('userId' => urldecode($args['uId']),'answerId' => urldecode($args['aId']))); 
        }
        //insert new user vote this process is done to avoid the user from voting up or down a multiple times
        public function insertUserAVote($args){
            if (!isset($args['uId']) || !isset($args['aId']) || !isset($args['voteType'])) {

		return false;
            }
           
            $this->db->insert('answerVotes',array('userId' => urldecode($args['uId']),'answerId' => urldecode($args['aId']),'voteType' => urldecode($args['voteType'])));
       
        }        
        //get number of questions by user for pagination
        public function getUserQuestionCount($args){
            $userInfoObj = json_decode($this->getUserId($args['un']));
            $userId = $userInfoObj[0]->id;
            
            $this->db->where('userId', $userId);      
            $this->db->from('questions');
            $result = $this->db->count_all_results();                   
            
            // return the results as an array - in which each selected row appears as an array           
            return $result;          
        }
        //get the questions posted by user
        public function getUserQuestion($args){
            $userInfoObj = json_decode($this->getUserId($args['un']));
            $userId = $userInfoObj[0]->id;
            
            $this->db->where('userId', $userId);         
            
            $start = (int)$args['dataLimitStart'];            
            $dtPerPage = (int)$args['dataCount'];
            
            $this->db->limit($dtPerPage, $start);            
            $this->db->order_by('postDate', 'desc');
            $result = $this->db->get('questions');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
        }
        //get users posted answer count for pagination
        public function getUserAnsCount($args){
            $userInfoObj = json_decode($this->getUserId($args['un']));
            $userId = $userInfoObj[0]->id;    
            
            $this->db->where('userId', $userId);      
            $this->db->from('answers');
            $result = $this->db->count_all_results();                   
            
            // return the results as an array - in which each selected row appears as an array           
            return $result;          
        }
        //get user posted answers
        public function getUserAns($args){
            $userInfoObj = json_decode($this->getUserId($args['un']));
            $userId = $userInfoObj[0]->id;
            
            $this->db->where('userId', $userId);         
            
            $start = (int)$args['dataLimitStart'];            
            $dtPerPage = (int)$args['dataCount'];
            
            $this->db->limit($dtPerPage, $start);            
            $this->db->order_by('postDate', 'desc');
            $result = $this->db->get('answers');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
        }
        //get user information
        public function getUserInfo($args){
            $userInfoObj = json_decode($this->getUserId($args['un']));
            $userId = $userInfoObj[0]->id;
            
            $this->db->where('id', $userId);         
                        
            $result = $this->db->get('users');
            
            // return the results as an array - in which each selected row appears as an array           
            return $result->result_array();          
        }
        //obtain user ID
        public function getUserId($username){
            $this->db->where('username', $username);         
                        
            $result = $this->db->get('users');
            
            // return the results as an array - in which each selected row appears as an array           
            return json_encode($result->result_array()); 
        }
        //report a user, make users' reported field as 1 to indicate
        public function updateUserReport($args){
                        
            $this->db->where('username', urldecode($args['un']));            
            $this->db->update('users', array('isReported' => 1));             
            
            return true;
        }
    } 
?>