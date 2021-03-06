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
                            <input type="text" class="form-control" placeholder="Search question" id="query">
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
    <body onload="triggerLoad()">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <form class="well form-horizontal"> 
                        <div id="alertVal"></div>
                        <div id = "searchRes">
                            <ul id="tabs" class="nav nav-pills" data-tabs="tabs">                            
                            <li class="active"><a href="#Recent" data-toggle="tab">Recent</a></li>
                            <li><a href="#Interesting" data-toggle="tab">Interesting</a></li>
                            <li><a href="#Unanswered" data-toggle="tab">Unanswered</a></li>      
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="Recent">
                                    <div id="recentQs">
                                        <table id="recentQ" class="table table-striped"></table>
                                    </div> 
                                    <ul class="pagination" id = "pagiR"></ul>
                                </div>
                                <div class="tab-pane" id="Interesting">
                                    <div id="interstingQs">   
                                         <table id="interstingQ" class="table table-striped"></table>
                                    </div>
                                    <ul class="pagination" id = "pagiI"></ul>
                                </div>
                                <div class="tab-pane" id="Unanswered">
                                    <div id="unansweredQs"> 
                                         <table id="unansweredQ" class="table table-striped"></table>
                                    </div>
                                    <ul class="pagination" id = "pagiU"></ul>
                                </div>               
                            </div>
                            
                        </div>       
                    </form>
                 </div>
            </div>
        </div>
            
    </body>
        
    <script type="text/javascript" lang="javascript">
        function triggerLoad(){
            var userName = '<?php echo $username; ?>';  
            if(userName !== ''){
                doGetQNoti();
            }
        }
        //BOOTSTRAP PAGINATION - first get data count and the data per page to determine the number of pages
        function doGetDataCount(){              
             
            var searchQ = (document.getElementById("query").value).replace(/\.$/, "");
            searchQ = escape(searchQ);            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/qstnCount/questionTitle/' + searchQ,         
                             
                success: function(data) {
                    var dataPerPage = 5;
                    var totalPages = Math.round(data/dataPerPage);
                    var dataStart = 0;
                    //create pagination
                    createPagi(totalPages, dataPerPage, 'R');
                    createPagi(totalPages, dataPerPage, 'I');
                    createPagi(totalPages, dataPerPage, 'U');
                    //call data accordiling when first page loads
                    doGetQRes(dataStart, dataPerPage, '1', 'R');
                    doGetQRes(dataStart, dataPerPage, '1', 'I');
                    doGetQRes(dataStart, dataPerPage, '1', 'U');

                },
                type: "GET"		
            });
                            
                        
        }
        
        function createPagi(totalPages, dataPerPage, getAct){
            //create pagination where every button will have a link indicating the data set it fetches
            var pagiHTML = '</li><li><a href="javascript:doGetQRes(0,5,1,\''+ getAct +'\')">1</a></li>';
            var dataStart = 0;
            
            for(var i = 1; i < totalPages; i++) {
               
                dataStart = dataStart + dataPerPage;                
                var pageVal = i + 1;
                pagiHTML = pagiHTML + '<li><a href="javascript:doGetQRes('+ dataStart +','+ dataPerPage +',1,\'' + getAct + '\')">'+ pageVal +'</a></li>';
                
            }
                    if(getAct === 'R'){
                        document.getElementById('pagiR').innerHTML = pagiHTML;
                    }
                    if(getAct === 'I'){
                        document.getElementById('pagiI').innerHTML = pagiHTML;
                    }
                    if(getAct === 'U'){
                        document.getElementById('pagiU').innerHTML = pagiHTML;
                    }
                
        }
        
        function doGetQRes(dataStart, dataPerPage, searchType, getAct) {
            //display the results according to the pagination clicks
            var searchQ = (document.getElementById("query").value).replace(/\.$/, "");
            //escape the searcg value so that it can be inserted into the url without generating bad url error
            searchQ = escape(searchQ);
            
            dataStart = encodeURIComponent(dataStart);
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/search/questions/qstnSearch/questionTitle/' + searchQ + '/dataLimitStart/' + dataStart + '/dataCount/' + dataPerPage + '/getAction/' + getAct+ '/sq/'+ searchType,
                               
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var resHTML = '';      
                    //inform the user if no data is returned
                    if(jsonObj.length === 0){
                        document.getElementById('alertVal').innerHTML = '<div class="alert alert-info">Seems your query is beyond our knowledge base. sorry :(</div>';
                        
                    }
                    else{
                        //loop though the returned data set to display the search result
                        for(var i = 0; i < jsonObj.length; i++) {
                            var qTitle = (decodeURIComponent(jsonObj[i].questionTitle));
                            var encodedQTitle = encodeURIComponent(jsonObj[i].questionTitle);                            
                            
                            var callJS = 'a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q=' + encodedQTitle + '"';  

                            resHTML = resHTML + '<tr><td width="10%" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td><td><strong><' + callJS + '>'+ qTitle + '</a></strong></br>'+ decodeURIComponent(jsonObj[i].questionBody) + '</td></tr>' ;
                        } 
                        if(getAct === 'R'){
                            document.getElementById('recentQ').innerHTML = resHTML;
                        }
                        if(getAct === 'I'){
                            document.getElementById('interstingQ').innerHTML = resHTML;
                        }
                        if(getAct === 'U'){
                            document.getElementById('unansweredQ').innerHTML = resHTML;
                        }
                       
                    }

                },
                type: "GET"		
            });
         }	
         //fetches updates ton questions posted by the current logged in user
         function doGetQNoti(){
            //fetch the user id returned by the controller
            var userId = '<?php echo $user_id; ?>';  
            userId = encodeURIComponent(userId);
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/getQNoti/uId/' + userId,
                            
                success: function(data) {
   
                    var jsonObj = JSON.parse(data);
                    var notiHTML = '';
                    //inform user if no updates are found
                    if(jsonObj.length === 0){
                        notiHTML = '<li><a href="#">No question related updates</a></li>';
                        //move on to look for updates related to users answers
                        doGetANoti(notiHTML, jsonObj.length);
                    } 
                    else{
                        //if there are uopdates display accordingly
                        var loggedIn = '<?php echo $username; ?>';
                        for(var i = 0; i < jsonObj.length; i++) {
                            
                            notiHTML = notiHTML + '<li><a href="https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q='+decodeURIComponent(jsonObj[i].questionTitle)+'">Your question ' + decodeURIComponent(jsonObj[i].questionTitle) + ' has an update</a></li>';
                         
                        }                 
                       
                        if(loggedIn !== ''){
                            //check for updates related to users ansers
                            doGetANoti(notiHTML, jsonObj.length);
                        }                     
                    }
                },
                type: "GET"		
            });
         }
         //fetches updates related to ansers posted by the current logged in user- similar to question updates
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
