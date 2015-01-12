<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    class Rest extends CI_Controller {
        function __construct()
        {
            parent::__construct();
            $this->load->model('student'); 
            $this->load->model('tutor'); 
            $this->load->model('user'); 
            $this->load->model('admin'); 
            $this->load->helper('url');
        }

        // we'll explain this in a couple of slides time
        public function _remap()
        {
            // first work out which request method is being used
            $request_method = $this->input->server('REQUEST_METHOD');
            switch (strtolower($request_method)) {
                case 'get' : $this->doGet(); break;
                case 'post' : $this->doPost(); break;
                case 'put' : $this->doPut(); break;
                case 'delete' : $this->doDelete(); break;
		default:
                    show_error('Unsupported method',404); // CI function for 404 errors
                    break;
            }
        }
        public function doGet()
        {
           // we assume the URL is constructed using name/value pairs
            $args = $this->uri->uri_to_assoc(2);
            
            switch ($args['resource']) {
                case 'qtag' :
                    $res = $this->student->getQTag($args);
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        echo json_encode($res);
                    }
                    break;
                case 'qstn' :
                    
                    $res = $this->student->getQ($args);
                    //var_dump($res);
                    //exit();
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        echo json_encode($res);
                        //redirect('/auth/redirect_viewQ/', $res);
                        //echo anchor('/auth/redirect_search/', "Questions");
                        //$this->load->view('viewQ', $res);                       
                    }
                    break;                   
                
                case 'qstnCount' :                    
                    $res = $this->student->getSearchQCount($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        //var_dump($res);
                        //exit();
                        
                        echo json_encode($res);
                    }
                    break;
                case 'userName' :                    
                    $res = $this->user->getUserName($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        //var_dump($res);
                        //exit();
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getQNoti' :                    
                    $res = $this->student->getQNotifications($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        //var_dump($res);
                        //exit();
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getANoti' :                    
                    $res = $this->student->getANotifications($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        //var_dump($res);
                        //exit();
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getAnswers' :                    
                    $res = $this->student->getAnswers($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getTopQuestions' :                    
                    $res = $this->student->getTopQ($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getReportedQuestions' :                    
                    $res = $this->admin->getReportedQ($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getReportedQuestionsCount' :                    
                    $res = $this->admin->getReportQCount($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getUserQuestionsCount' :                    
                    $res = $this->student->getUserQuestionCount($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getUserQuestions' :                    
                    $res = $this->student->getUserQuestion($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getUserAnsCounts' :                    
                    $res = $this->student->getUserAnsCount($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getUserAnsList' :                    
                    $res = $this->student->getUserAns($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getUsersInfo' :                    
                    $res = $this->student->getUserInfo($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getReportedUsersCount' :                    
                    $res = $this->admin->getReportUCount($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getReportedUsersList' :                    
                    $res = $this->admin->getReportedU($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getTagSearch' :                    
                    $res = $this->user->searchTag($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                case 'getUsers' :                    
                    $res = $this->user->searchUserList($args);  
                    if ($res === false) {
                        show_error('Unsupported request',404);
                    }
                    else {
                        // assume we get back an array of data - now echo it as JSON
                        
                        echo json_encode($res);
                    }
                    break;
                default:
                    show_error('Unsupported resource',404);
                    break;
            }
        }
        public function doPost(){
            
            $args = $this->uri->uri_to_assoc(2);
            
            switch ($args['resource']) {
		case 'questions' :             
                    $res = $this->student->askQ($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to post','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                case 'answers' :             
                    $res = $this->tutor->answerQ($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to post','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                case 'questionReport' :             
                    $res = $this->student->insertQReport($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to post','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                case 'answerReport' :             
                    $res = $this->student->insertAReport($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to post','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                case 'userReport' :             
                    $res = $this->student->insertUReport($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to post','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                case 'insertTag' :             
                    $res = $this->tutor->addTag($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to post','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                default:
                    show_error('Unsupported resource',404);
            }			
            
	} 
        
        public function doPut(){
            $args = $this->uri->uri_to_assoc(2);
            
            switch ($args['resource']) {
		case 'questionVote' :             
                    $res = $this->student->doQVote($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to vote','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break;
                case 'seenQNoti' :             
                    $res = $this->student->updateSeenQNoti($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break; 
                case 'seenANoti' :             
                    $res = $this->student->updateSeenANoti($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break;
                case 'acceptAns' :             
                    $res = $this->student->updateAcceptAnswer($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break; 
                case 'updateQ' :             
                    $res = $this->student->updateQuestion($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break; 
                case 'promoteToAdmin' :             
                    $res = $this->admin->promoteUser($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break; 
                case 'answerVote' :             
                    $res = $this->student->doAVote($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break;
                case 'userReport' :             
                    $res = $this->student->updateUserReport($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to perform action','status' => 1));
                    }
                    else {                        
                        echo json_encode(array('status' => 0));                        
                    }
                    break;
                default:
                    show_error('Unsupported resource',404);
            }
            
        }
        public function doDelete(){
            
            $args = $this->uri->uri_to_assoc(2);
            
            switch ($args['resource']) {
		case 'deleteQuestion' :             
                    $res = $this->student->deleteQ($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to delete','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;   
                case 'deleteAnswer' :             
                    $res = $this->student->deleteA($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to delete','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                case 'deleteRQuestion' :             
                    $res = $this->admin->deleteReportedQ($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to delete','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                    
                case 'deleteRUser' :             
                    $res = $this->admin->deleteUReport($args);
                    
                    if ($res === false) {
                        echo json_encode(array('error' => 'unable to delete','status' => 1));
                    }
                    else {
                        
                        echo json_encode(array('status' => 0));
                        
                    }
                    break;
                default:
                    show_error('Unsupported resource',404);
            }			
            
	} 
    }    
?>