<html>
    <head>
        <script src="/ABSOLUTE/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="/ABSOLUTE/js/bootstrap.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/ABSOLUTE/css/bootstrap.css">
        
        <nav class="navbar" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <?php echo anchor('/auth/redirect_admin/', 'ABSOLUTE admin panel <span class="glyphicon glyphicon-tower"></span>', 'class="navbar-brand"'); ?>
                
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">     
                 
                <ul class="nav navbar-nav navbar-right">                         
                    <?php 
                    
                        if ($username == '')
                        {
                            echo '<li>'.anchor('/auth/register/', "Signup").'</li>';
                            echo '<li>'.anchor('/auth/login/', "Login").'</li>';
                        }else{                            
                            echo '<li class="dropdown">';
                            echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'."Nice to see you : ".$username."   ".'<b class="caret"></b></a>';
                            echo    '<ul class="dropdown-menu">';                          
                            echo        '<li>'.anchor('/auth/logout/', "Logout").'</li>';
                            echo    '</ul>';
                            echo '</li>'; 
                            
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
                                
                                 echo '<div align="right"><button type="button" class="btn btn-danger" onclick="javascript:deleteReportedU(\''.$_GET['u'].'\')">Delete User</button></div></br>';
                                
                                
                            ?>
                            <div class="alert alert-info">User Details</div>
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
        //simialar to public view profile-but has access to delete the account
        function initFunc(){
            
            doGetDataCount('Q');
            doGetDataCount('A');
            doGetUserInfo();
        }
        function reportUser(userNameVal){
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/userReport/un/' + userNameVal,         
                             
                success: function(data) {
                    location.reload();
                },
                type: "PUT"		
            }); 
        }
        
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
                            //encodedQTitle = encodedQTitle.replace(/\*/g,'%2A');
                            
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_AdminViewQ?q=' + encodedQTitle + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td><td style="vertical-align:middle"><' + callJS + '>'+ qTitle + '</a></td></tr>' ;
                        }  
                        document.getElementById('userQs').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
        }
        
        function doGetUARes(dataStart, dataPerPage){
            var userNameVal = encodeURIComponent('<?php echo $_GET['u']; ?>');
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getUserAnsList/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage + '/un/' + userNameVal,
                             
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    
                    if(jsonObj.length === 0){
                        document.getElementById('ares').innerHTML = '<div class="alert alert-info">No questions posted</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            var qTitle = (decodeURIComponent(jsonObj[i].questionTitle));
                            var encodedQTitle = escape(jsonObj[i].questionTitle);
                            //encodedQTitle = encodedQTitle.replace(/\*/g,'%2A');
                            
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_AdminViewQ?q=' + encodedQTitle + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td><td style="vertical-align:middle"><' + callJS + '>'+ qTitle + '</a></td></tr>' ;
                        }  
                        document.getElementById('userAns').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
        }
        
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
                    if(jsonObj[0].userType === 'T'){
                        userPic = '<h3><span class="glyphicon glyphicon-briefcase"></span></h3>';
                        userTypeInfo = '<span class="label label-warning">Tutor</span>';
                    }
                    if(jsonObj[0].userType === 'S'){
                        userPic = '<h3><span class="glyphicon glyphicon-user"></span></h3>';
                        userTypeInfo = '<span class="label label-warning">Student</span>';
                    }
                    
                    var userRep = '<span class="label label-danger">'+jsonObj[0].userRep+'</span>';
                    var userName = '<span class="label label-default">'+jsonObj[0].username+'</span>';
                    var userContact = '<span class="label label-default">'+jsonObj[0].email+'</span>';
                    var lastLogin = '<span class="label label-default">'+jsonObj[0].last_login+'</span>';
                    var lastIp = '<span class="label label-default">'+jsonObj[0].last_ip+'</span>';
                    
                    if(jsonObj[0].username === currUser){
                        manageAccount = '<div class="btn-group">'+
                                        '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">'+
                                          'Manage Account <span class="caret"></span>'+
                                        '</button>'+
                                        '<ul class="dropdown-menu" role="menu">'+
                                          '<li><a href="https://localhost/ABSOLUTE/index.php/auth/change_email">Chnage email</a></li>'+
                                          '<li><a href="https://localhost/ABSOLUTE/index.php/auth/change_password">Change Passowrd</a></li>'+
                                          '<li class="divider"></li>'+
                                          '<li><a href="https://localhost/ABSOLUTE/index.php/auth/unregister">Unregister</a></li>'+                                          
                                        '</ul>'+
                                      '</div>';
                          }
                              
                    resHTML = '<tr><td width="50">'+userPic+'</td><td><span class="label label-info">User Name</span></td><td width="160">'+userName+'</td><td></td><td></td><td rowspan=2>'+manageAccount+'</td></tr><tr><td>'+userRep+'</td><td><span class="label label-info">Contact</span></td><td>'+userContact+'</td><td><span class="label label-info">User Type</span></td><td width = "300">'+userTypeInfo+'</td></tr><tr><td></td><td></br><span class="label label-info">Last Login</span></td><td></br>'+lastLogin+'</td></tr><tr><td></td><td></br><span class="label label-info">IP</span></td><td></br>'+lastIp+'</td></tr>';
                    
                    document.getElementById('userInfo').innerHTML = resHTML;
                       
                    

                },
                type: "GET"		
            });
        }
        
        function deleteReportedU(userNameVal){
            //encode the received string so that is URI worthy
            var deleteUn = encodeURIComponent(userNameVal);            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/deleteRUser/un/' + deleteUn,
                //data: data,               
                success: function(data) {
                    window.location = "https://localhost/ABSOLUTE/index.php/auth/redirect_admin";
                    
                    
                },
                //set action type
                type: "DELETE"		
            });
        }
    </script>
</html>     