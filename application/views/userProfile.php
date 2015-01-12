<html>   
    <head>
        <script src="/ABSOLUTE/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="/ABSOLUTE/js/bootstrap.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/ABSOLUTE/css/bootstrap.css">
        
        <nav class="navbar navbar-inverse" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <?php echo anchor('/auth/redirect_home/', 'ABSOLUTE <span class="glyphicon glyphicon-book"> </span>', 'class="navbar-brand"'); ?>
                
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                            <li><?php echo anchor('/auth/redirect_search/', "Questions"); ?></li>
                            <li><?php echo anchor('/auth/redirect_searchTag/', "Search Tags"); ?></li>
                            <li><?php echo anchor('/auth/redirect_searchUsers/', "Search Users"); ?></li>                                
                    </ul>                
                <ul class="nav navbar-nav navbar-right">     
                    <li><?php echo anchor('/auth/redirect_askQ/', "Ask question"); ?></li> 
                    <?php 
                    
                        if ($username == '')
                        {
                            echo '<li>'.anchor('/auth/register/', "Signup").'</li>';
                            echo '<li>'.anchor('/auth/login/', "Login").'</li>';
                        }else{
                            echo '<li class="dropdown">';
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">Notifications <span class="badge"><div id="notiCount"></div></span><b class="caret"></b></a>';
                            echo '<ul class="dropdown-menu" id="notifi">';                           
                            echo '</ul>';
                            echo '</li>'; 
                            echo '<li class="dropdown">';
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'."Nice to see you : ".$username."   ".'<b class="caret"></b></a>';
                            echo    '<ul class="dropdown-menu">';
                            echo        '<li>'.anchor('/auth/redirect_profile?u='.urlencode($username), "View profile").'</li>';                             
                            echo        '<li class="divider"></li>';
                            echo        '<li>'.anchor('/auth/logout/', "Logout").'</li>';
                            echo    '</ul>';
                            echo '</li>';
                            if($usertype == 'S'){
                                echo '<a class="navbar-brand" href="#" role="button"><span class="glyphicon glyphicon-user"></span></a>';
                            }
                            if($usertype == 'T'){
                                echo anchor('/auth/redirect_addTag', '<span class="glyphicon glyphicon-briefcase"></span>', 'class="navbar-brand"');
                                //echo '<a class="navbar-brand" href="https://localhost/ABSOLUTE/index.php/auth/auth/redirect_addTag" role="button"><span class="glyphicon glyphicon-briefcase"></span></a>';
                            }
                            if($usertype == 'A'){
                                echo '<a class="navbar-brand" href="#" role="button"><span class="glyphicon glyphicon-tower"></span></a>';
                            }
                            
                        }
                        
                        
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </head>
            
        <body onload="initFunc()">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-lg-offset-3">
                        <form class="well form-horizontal">
                            <?php
                                if($username != $_GET['u'] && $username != ''){
                                    echo '<div align="right"><button type="button" class="btn btn-danger" onclick="javascript:reportUser(\''.$_GET['u'].'\')">Report User</button></div></br>';
                                }
                                
                            ?>
                            <div class="alert alert-info" id="userBasic"></div>
                            <table id="userInfo">
                                
                            </table>
                            </br>
                            <div class="alert alert-info">Activity</span></div>
                            <ul id="tabs" class="nav nav-pills" data-tabs="tabs">                            
                                <li class="active"><a href="#Questions" data-toggle="tab">Questions</a></li>
                                <li><a href="#Answers" data-toggle="tab">Answers</a></li>                                
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Questions">
                                    <div id="Qs">
                                        <div id="qres"></div>
                                        <table id="userQs" class="table table-hover">
                                            
                                        </table>
                                        <ul class="pagination" id = "pagi"></ul>
                                    </div>                                
                                </div>
                                <div class="tab-pane" id="Answers">                                    
                                    <div id="As">   
                                         <div id="ares"></div>
                                         <table id="userAns" class="table table-hover"></table>
                                         <ul class="pagination" id = "pagiAns"></ul>
                                    </div>
                                </div>                                          
                            </div>
                        </form>
                    </div>
                </div>
            </div>            
        </body>          
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
        $('#tabs').tab();
        });
    </script>
    <script type="text/javascript" lang="javascript">
        //get user posted question and answers, user information and notifications
        function initFunc(){
            
            doGetDataCount('Q');
            doGetDataCount('A');
            doGetUserInfo();
            var userName = '<?php echo $username; ?>';  
            if(userName !== ''){
                doGetQNoti();
            }
        }
        //report other users
        function reportUser(userNameVal){
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/userReport/un/' + userNameVal,         
                             
                success: function(data) {
                    location.reload();
                },
                type: "PUT"		
            }); 
        }
        //get data count for pagination BOOTSTRAP
        function doGetDataCount(reqType, userNameVal){              
            var userNameVal = encodeURIComponent('<?php echo $_GET['u']; ?>');   
            var reqVal = '';
            if(reqType === 'Q'){
                reqVal = 'getUserQuestionsCount';
            }else{
                reqVal = 'getUserAnsCounts';
            }
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/'+reqVal+'/un/' + userNameVal,         
                             
                success: function(data) {
                    var dataPerPage = 5;
                    var totalPages = Math.floor(data/dataPerPage);
                    var dataStart = 0;
                    createPagi(totalPages, dataPerPage, reqType);
                    if(reqType === 'Q'){
                        doGetUQRes(dataStart, dataPerPage);
                    }else{
                        doGetUARes(dataStart, dataPerPage);
                    }

                },
                type: "GET"		
            });                           
                        
        }
        //create pagination
        function createPagi(totalPages, dataPerPage, reqType){
            var pagiHTML = '</li><li><a href="javascript:doGetUQRes(0,5)">1</a></li>';
            var dataStart = 0;
            
            for(var i = 1; i < totalPages; i++) {
               
                dataStart = dataStart + dataPerPage;                
                var pageVal = i + 1;
                pagiHTML = pagiHTML + '<li><a href="javascript:doGetUQRes('+ dataStart +','+ dataPerPage +')">'+ pageVal +'</a></li>';
                
            }
            if(reqType === 'Q'){
                document.getElementById('pagi').innerHTML = pagiHTML; 
            }else{
                document.getElementById('pagiAns').innerHTML = pagiHTML; 
            }
               
        }
        //get user posted quesitions
        function doGetUQRes(dataStart, dataPerPage){
            var userNameVal = encodeURIComponent('<?php echo $_GET['u']; ?>');
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getUserQuestions/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage + '/un/' + userNameVal,
                             
                success: function(data) {
                    
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    
                    if(jsonObj.length === 0){
                        document.getElementById('qres').innerHTML = '<div class="alert alert-info">No questions posted</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            var qTitle = (decodeURIComponent(jsonObj[i].questionTitle));
                            var encodedQTitle = escape(jsonObj[i].questionTitle);
                            //generate html to view the posted questions                            
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q=' + encodedQTitle + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td><td style="vertical-align:middle"><' + callJS + '>'+ qTitle + '</a></td></tr>' ;
                        }  
                        document.getElementById('userQs').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
        }
        //get user posted answers
        function doGetUARes(dataStart, dataPerPage){
            var userNameVal = encodeURIComponent('<?php echo $_GET['u']; ?>');
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getUserAnsList/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage + '/un/' + userNameVal,
                             
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    
                    if(jsonObj.length === 0){
                        document.getElementById('ares').innerHTML = '<div class="alert alert-info">No answers posted</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            var qTitle = (decodeURIComponent(jsonObj[i].questionTitle));
                            var encodedQTitle = escape(jsonObj[i].questionTitle);
                            //generate html to view the posted answres
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q=' + encodedQTitle + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td><td style="vertical-align:middle"><' + callJS + '>'+ qTitle + '</a></td></tr>' ;
                        }  
                        document.getElementById('userAns').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
        }
        //get user information
        function doGetUserInfo(){
            var userNameVal = encodeURIComponent('<?php echo $_GET['u']; ?>');
            var currUser = '<?php echo $username;?>';
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getUsersInfo/un/' + userNameVal,
                             
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    var userPic = '';
                    var userTypeInfo = '';
                    var manageAccount = '';
                    //genrate html according to user type
                    if(jsonObj[0].userType === 'T'){
                        userPic = '<h1><span class="glyphicon glyphicon-briefcase"></span></h1>';
                        userTypeInfo = '<span class="label label-warning">Tutor</span>';
                    }
                    if(jsonObj[0].userType === 'S'){
                        userPic = '<h1><span class="glyphicon glyphicon-user"></span></h1>';
                        userTypeInfo = '<span class="label label-warning">Student</span>';
                    }
                    
                    var userRep = '<span class="label label-success"> REP: '+jsonObj[0].userRep+'</span>';
                    var userLp = '<span class="label label-danger"> LP: '+jsonObj[0].userLP+'</span>';
                    var userName = jsonObj[0].username;
                    var userContact = '<span class="label label-default">'+jsonObj[0].email+'</span>';
                    var lastLogin = '<span class="label label-default">'+jsonObj[0].last_login+'</span>';
                    
                    
                    
                    if(jsonObj[0].username === currUser){
                        manageAccount = '<div class="btn-group">'+
                                        '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'+
                                          '<span class="glyphicon glyphicon-cog"></span> '+ userName +' <span class="caret"></span>'+
                                        '</button>'+
                                        '<ul class="dropdown-menu" role="menu">'+
                                          '<li><a href="https://localhost/ABSOLUTE/index.php/auth/change_email">Change email</a></li>'+
                                          '<li><a href="https://localhost/ABSOLUTE/index.php/auth/change_password">Change Password</a></li>'+
                                          '<li class="divider"></li>'+
                                          '<li><a href="https://localhost/ABSOLUTE/index.php/auth/unregister">Unregister</a></li>'+                                          
                                        '</ul>'+
                                      '</div>';
                    }
                    document.getElementById('userBasic').innerHTML = manageAccount + ' ' + userRep  + ' ' + userLp;   
                    
                    resHTML = '<tr><td width="50" align="center">'+userPic+'</td><td><span class="label label-info">User Name</span></td><td width="160">'+userName+'</td><td></td><td></td><td rowspan=2></td></tr><tr><td align="center" width="30%">'+userTypeInfo+'</td><td><span class="label label-info">Contact</span></td><td>'+userContact+'</td></tr><tr><td></td><td></br><span class="label label-info">Last Login</span></td><td></br>'+lastLogin+'</td></tr>';
                    
                    document.getElementById('userInfo').innerHTML = resHTML;
                       
                    

                },
                type: "GET"		
            });
        }
        //get notifications
         function doGetQNoti(){
            var userId = '<?php echo $user_id; ?>';  
            userId = encodeURIComponent(userId);
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getQNoti/uId/' + userId,
                            
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var notiHTML = '';

                    if(jsonObj.length === 0){
                        notiHTML = '<li><a href="#">No question related updates</a></li>'                        
                        doGetANoti(notiHTML, jsonObj.length);
                    } 
                    else{
                        var loggedIn = '<?php echo $username; ?>';
                        for(var i = 0; i < jsonObj.length; i++) {
                            
                            notiHTML = notiHTML + '<li><a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q='+decodeURIComponent(jsonObj[i].questionTitle)+'">Your question ' + decodeURIComponent(jsonObj[i].questionTitle) + ' has an update</a></li>';
                         
                        }                 
                       
                        if(loggedIn !== ''){
                            
                            doGetANoti(notiHTML, jsonObj.length);
                        }                     
                    }

                },
                type: "GET"		
            }); 

         }
         
         function doGetANoti(qNotiHTML, qNotiCount){
            var userId = '<?php echo $user_id; ?>';  
            userId = encodeURIComponent(userId);
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getANoti/uId/' + userId,
                            
                success: function(data) {
                    
                    var jsonObj = JSON.parse(data);
                    var notiHTML = '';

                    if(jsonObj.length === 0){
                        document.getElementById('notiCount').innerHTML = parseInt('0') + parseInt(qNotiCount);
                        document.getElementById('notifi').innerHTML = qNotiHTML + '<li class="divider"></li><li><a href="#">No answer related updates</a></li>';
                        
                    }
                    else{
                        var loggedIn = '<?php echo $username; ?>';
                        for(var i = 0; i < jsonObj.length; i++) {
                            
                            notiHTML = notiHTML + '<li><a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q='+decodeURIComponent(jsonObj[i].questionTitle)+'">Your answer on ' + decodeURIComponent(jsonObj[i].questionTitle) + ' has an update</a></li>';
                         
                        }                 
                       
                        if(loggedIn !== ''){
                            document.getElementById('notiCount').innerHTML = parseInt(jsonObj.length) + parseInt(qNotiCount);
                            document.getElementById('notifi').innerHTML = qNotiHTML + '<li class="divider"></li><li>' + notiHTML;
                        }                     
                    }

                },
                type: "GET"		
            }); 

         }
    </script>
</html>     
        