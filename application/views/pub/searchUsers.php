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
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search users" id="searchUVal">
                        </div>
                        <button type="button" class="btn btn-success" onclick="javascript:doGetDataCount()">Submit</button>
                    </form>
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
    <body onload="tiggerLoad()">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <form class="well form-horizontal"> 
                        <div id = "searchRes">
                            <table id="qres" class="table table-striped"></table>
                            <ul class="pagination" id = "pagi"></ul>
                        </div>       
                    </form>
                 </div>
            </div>
        </div>
            
    </body>
        
    <script type="text/javascript" lang="javascript">
        function tiggerLoad(){
            var userName = '<?php echo $username; ?>';  
            if(userName !== ''){
                doGetQNoti();
            }
        }
        //obtain data count of the search result
        //same process as performed in seearchq
        function doGetDataCount(){              
               
            var searchU = document.getElementById("searchUVal").value;
            searchU = encodeURIComponent(searchU);
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getUsers/un/' + searchU,         
                             
                success: function(data) {
                    var dataPerPage = 10;
                    var totalPages = Math.round(data/dataPerPage);
                    var dataStart = 0;
                    createPagi(totalPages, dataPerPage);
                    doGetURes(dataStart, dataPerPage);

                },
                type: "GET"		
            });
                            
                        
        }
        //create pagination for users
        function createPagi(totalPages, dataPerPage){
            var pagiHTML = '</li><li><a href="javascript:doGetURes(0,10)">1</a></li>';
            var dataStart = 0;
            
            for(var i = 1; i < totalPages; i++) {
               
                dataStart = dataStart + dataPerPage;                
                var pageVal = i + 1;
                pagiHTML = pagiHTML + '<li><a href="javascript:doGetURes('+ dataStart +','+ dataPerPage +')">'+ pageVal +'</a></li>';
                
            }
            document.getElementById('pagi').innerHTML = pagiHTML;    
        }
        //obtain user search results - search functions behavious is the same
        //create pagination
        //get data 
        function doGetURes(dataStart, dataPerPage) {
            
            var searchU = document.getElementById("searchUVal").value;
            searchU = encodeURIComponent(searchU);
            dataStart = encodeURIComponent(dataStart);
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getUsers/un/' + searchU + '/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage,
                               
                success: function(data) {
                    //alert(data);
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    var userType = '';
                    if(jsonObj.length === 0){
                        document.getElementById('qres').innerHTML = '<div class="alert alert-info">Seems the member you search doesnt exist. sorry :(</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            var uName = (decodeURIComponent(jsonObj[i].username));
                            var encodedU = encodeURIComponent(jsonObj[i].username);
                                                        
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u=' + encodedU + '"';  
                            //customize displayed information according to user type. only shows tutors and students
                            if(jsonObj[i].userType === 'S'){
                                userType = 'Student';
                                resHTML = resHTML + '<tr><td width="20%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].userRep) + '</span> <h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].userLP) + '</span></h3></td><td  width="70%" style="vertical-align:middle"><strong><' + callJS + '>'+ uName + '</a></td><td><h3><span class="label label-default">'+ userType +'</span></h3></td></tr>' ;
                            }
                            if(jsonObj[i].userType === 'T'){
                                userType = 'Tutor';
                                resHTML = resHTML + '<tr><td width="20%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].userRep) + '</span> <h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].userLP) + '</span></h3></td><td  width="70%" style="vertical-align:middle"><strong><' + callJS + '>'+ uName + '</a></td><td><h3><span class="label label-warning">'+ userType +'</span></h3></td></tr>' ;
                            }

                            
                        } 
                        document.getElementById('qres').innerHTML = resHTML;
                       
                    }

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
