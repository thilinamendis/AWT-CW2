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
                            <input type="text" class="form-control" placeholder="Question search-TAGS" id="searchQVal">
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
    <body onload="triggerload()">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
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
        function triggerload(){
            var userName = '<?php echo $username; ?>';  
            if(userName !== ''){
                doGetQNoti();
            }
        }
        //get data to create pagination
        function doGetDataCount(){              
               
            var searchQ = document.getElementById("searchQVal").value;
            searchQ = encodeURIComponent(searchQ);
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getTagSearch/tags/' + searchQ,         
                             
                success: function(data) {
                    var dataPerPage = 10;
                    var totalPages = Math.round(data/dataPerPage);
                    var dataStart = 0;
                    createPagi(totalPages, dataPerPage);
                    doGetQRes(dataStart, dataPerPage);

                },
                type: "GET"		
            });
                            
                        
        }
        //create pagination BOOSTSTRAP
        function createPagi(totalPages, dataPerPage){
            var pagiHTML = '</li><li><a href="javascript:doGetQRes(0,10)">1</a></li>';
            var dataStart = 0;
            
            for(var i = 1; i < totalPages; i++) {
               
                dataStart = dataStart + dataPerPage;                
                var pageVal = i + 1;
                pagiHTML = pagiHTML + '<li><a href="javascript:doGetQRes('+ dataStart +','+ dataPerPage +')">'+ pageVal +'</a></li>';
                
            }
            document.getElementById('pagi').innerHTML = pagiHTML;    
        }
        //get question search by tags
        function doGetQRes(dataStart, dataPerPage) {
            
            var searchQ = document.getElementById("searchQVal").value;
            
           
            searchQ = encodeURIComponent(searchQ);
                        
            dataStart = encodeURIComponent(dataStart);
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getTagSearch/tags/' + searchQ + '/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage,
                               
                success: function(data) {
                   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';
                    
                    if(jsonObj.length === 0){
                        document.getElementById('qres').innerHTML = '<div class="alert alert-info">Seems your query is beyond our knowledge base. sorry :(</div>';
                        
                    }
                    else{
                        for(var i = 0; i < jsonObj.length; i++) {
                            var qTitle = (decodeURIComponent(jsonObj[i].questionTitle));
                            var encodedQTitle = encodeURIComponent(jsonObj[i].questionTitle);
                                                        
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q=' + encodedQTitle + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td><td><strong><' + callJS + '>'+ qTitle + '</a></strong></br>'+ decodeURIComponent(jsonObj[i].questionBody) + '</td></tr>' ;
                        } 
                        document.getElementById('qres').innerHTML = resHTML;
                       
                    }

                },
                type: "GET"		
            });
         }	
         //get question notifications
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
                        //document.getElementById('notiCount').innerHTML = '0';
                        //document.getElementById('notifi').innerHTML = notiHTML;
                        doGetANoti(notiHTML, jsonObj.length);
                    } 
                    else{
                        var loggedIn = '<?php echo $username; ?>';
                        for(var i = 0; i < jsonObj.length; i++) {
                            
                            notiHTML = notiHTML + '<li><a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q='+decodeURIComponent(jsonObj[i].questionTitle)+'">Your question ' + decodeURIComponent(jsonObj[i].questionTitle) + ' has an update</a></li>';
                         
                        }                 
                       
                        if(loggedIn !== ''){
                            //document.getElementById('notiCount').innerHTML = jsonObj.length;
                            //document.getElementById('notifi').innerHTML = notiHTML;
                            doGetANoti(notiHTML, jsonObj.length);
                        }                     
                    }

                },
                type: "GET"		
            }); 

         }
         //get answer notifications
         function doGetANoti(qNotiHTML, qNotiCount){
            var userId = '<?php echo $user_id; ?>';  
            userId = encodeURIComponent(userId);
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getANoti/uId/' + userId,
                            
                success: function(data) {
                    //alert(data);
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
