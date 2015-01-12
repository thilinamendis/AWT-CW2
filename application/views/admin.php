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
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Promote to admin" id="promoteUser">
                    </div>
                    
                    <input type="button" onclick="javascript:promoteUserToAdmin()" value="Promote" class="btn btn-primary">
                </form>
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
                            echo        '<li>'.anchor('/auth/change_password/', "Change Passowrd").'</li>';
                            echo        '<li>'.anchor('/auth/logout/', "Logout").'</li>';
                            echo    '</ul>';
                            echo '</li>'; 
                            
                        }                        
                        
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
        
    </head>
    <body onload="doStartFunc()">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    
                        <table class="table">
                            <tr>
                                <td width="100%">
                                    <div class="panel-default">
                                        <div class="panel-heading">Reported Questions</div>
                                        <div class="panel-body">
                                            <table id="qres" class="table table-condensed"></table>
                                            <ul class="pagination" id = "pagi"></ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr >
                                <td width="100%">
                                    <div class="panel-default">
                                        <div class="panel-heading">Reported Users</div>
                                        <div class="panel-body">
                                            <table id="ures" class="table table-condensed"></table>
                                            <ul class="pagination" id = "pagiU"></ul>
                                        </div>
                                        
                                    </div>
                                </td>
                            </tr>
                        </table>
                    
                </div>
            </div> 
         </div>
    </body>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
        $('#tabs').tab();
        });
    </script>
    <script type="text/javascript">
        //get reported questions and answers
        function doStartFunc(){
            doGetDataCount('Q');
            doGetDataCount('U');
        }
         
        //delete reported question
        function deleteReportedQ(qTitleVal){
            //encode the received string so that is URI worthy
            var deleteQTitle = encodeURIComponent(qTitleVal);            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/deleteRQuestion/qTitle/' + deleteQTitle,
                               
                success: function(data) {
                    location.reload();                   
                    
                },
                //set action type
                type: "DELETE"		
            });
        }
        //selete rported users
        function deleteReportedU(userNameVal){
            //encode the received string so that is URI worthy
            var deleteUn = encodeURIComponent(userNameVal);            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/deleteRUser/un/' + deleteUn,
                //data: data,               
                success: function(data) {
                    location.reload();                    
                    
                },
                //set action type
                type: "DELETE"		
            });
        }
        //promote user to admin by rpoviding the email address
        function promoteUserToAdmin(){
            //encode the received string so that is URI worthy
            var pUser = encodeURIComponent(document.getElementById('promoteUser').value);            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/promoteToAdmin/uEmail/' + pUser,
                //data: data,               
                success: function(data) {
                    var alertObj = JSON.parse(data);
                    
                    if (alertObj.status !== 0)
                    {
                        alert ("You have successfull promoted the user");
                        document.getElementById('promoteUser').value = "";
                        location.reload();
                    }
                    else{
                        alert ("User was not promoted! Please verify the email and try again :)");                      
                       
                    }
                    
                                      
                },
                
                //set action type
                type: "PUT"		
            });
        }
        //get data count to create pagination. similar to search fiunctionalies in the project
        function doGetDataCount(reqType){ 
            var reqVal = '';
            if(reqType === 'Q'){
                reqVal = 'getReportedQuestionsCount';
            }else{
                reqVal = 'getReportedUsersCount';
            }
               
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/'+reqVal,         
                             
                success: function(data) {
                    var dataPerPage = 5;
                    var totalPages = Math.floor(data/dataPerPage);
                    var dataStart = 0;
                    //generate pagination according to the requirement. displaying questions or users
                    if(reqType === 'Q'){
                        createPagi(totalPages, dataPerPage, reqType);
                        getReportedQuestions(dataStart, dataPerPage);
                    }else{
                        createPagiU(totalPages, dataPerPage, reqType);
                        getReportedU(dataStart, dataPerPage);
                    }

                },
                type: "GET"		
            });
                            
                        
        }
        //create paginations for reported  questions
        function createPagi(totalPages, dataPerPage){
            var pagiHTML = '<li><a href="javascript:getReportedQuestions(0,5)">1</a></li>';
            var dataStart = 0;
            
            for(var i = 1; i < totalPages; i++) {
               
                dataStart = dataStart + dataPerPage;                
                var pageVal = i + 1;
                pagiHTML = pagiHTML + '<li><a href="javascript:getReportedQuestions('+ dataStart +','+ dataPerPage +')">'+ pageVal +'</a></li>';
                
            }
            document.getElementById('pagi').innerHTML = pagiHTML;    
        }
        //create paginations for  reported users
        function createPagiU(totalPages, dataPerPage){
            var pagiHTML = '<li><a href="javascript:getReportedU(0,5)">1</a></li>';
            var dataStart = 0;
            
            for(var i = 1; i < totalPages; i++) {
               
                dataStart = dataStart + dataPerPage;                
                var pageVal = i + 1;
                pagiHTML = pagiHTML + '<li><a href="javascript:getReportedU('+ dataStart +','+ dataPerPage +')">'+ pageVal +'</a></li>';
                
            }
            document.getElementById('pagiU').innerHTML = pagiHTML;    
        }
        //get reported users list
        function getReportedU(dataStart, dataPerPage){
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getReportedUsersList/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage,
                               
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    
                    if(jsonObj.length === 0){
                        document.getElementById('ures').innerHTML = '<div class="alert alert-info">No reports today! seems everyone is following our guidelines ;)</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            //genarte html to display the users
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_AdminViewU?u=' + encodeURIComponent(jsonObj[i].username) + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="glyphicon glyphicon-info-sign"></span></h3></td><td width = "100%" style="vertical-align:middle"><strong><' + callJS + '>'+ jsonObj[i].username + '</a></strong></td><td style="vertical-align:middle"><a class="navbar-brand" href="javascript:deleteReportedU(\''+ jsonObj[i].username  +'\')" role="button"><h3><span class="glyphicon glyphicon-remove-circle"></span></h3></a></td></tr>' ;
                        } 
                        document.getElementById('ures').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
        }
        //get reported questions
        function getReportedQuestions(dataStart, dataPerPage){
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getReportedQuestions/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage,
                               
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    
                    if(jsonObj.length === 0){
                        document.getElementById('qres').innerHTML = '<div class="alert alert-info">No reports today! seems everyone is following our guidelines ;)</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            var qTitle = (decodeURIComponent(jsonObj[i].questionTitle));
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_AdminViewQ?q=' + encodeURIComponent(jsonObj[i].questionTitle) + '"';  
                            //genrate html to display the questions
                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="glyphicon glyphicon-info-sign"></span></h3></td><td width = "100%" style="vertical-align:middle"><strong><' + callJS + '>'+ qTitle + '</a></strong></td><td style="vertical-align:middle"><a class="navbar-brand" href="javascript:deleteReportedQ(\''+ qTitle  +'\')" role="button"><h3><span class="glyphicon glyphicon-remove-circle"></span></h3></a></td></tr>' ;
                        } 
                        document.getElementById('qres').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
        }
    </script>
    
</html>