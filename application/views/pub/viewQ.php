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
    <body onload="triggerLoad()">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <form class="well form-horizontal"> 
                        <div id = "searchRes">
                            <table id="qres" class="table table-condensed">
                                
                            </table>
                        </div>       
                    </form>
                 </div>
            </div>
        </div>
        <div id="postAns">
        </div>
        <div id="gotAns">
            
            <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <form class="well form-horizontal">
                        <div id="info"></div>
                        <div id = "searchRes">
                            <div class="alert alert-info"><span class="glyphicon glyphicon-list"></span><strong> Answers</strong></div>
                            <table id="ares" class="table table-condensed">
                                
                            </table>
                        </div>  
                        
                    </form>
                 </div>
            </div>
            </div>
            
        </div>
    </body>
    <script type="text/javascript" lang="javascript">
        function triggerLoad(){
            doViewQ();
            var userName = '<?php echo $username; ?>';  
            if(userName !== ''){
                doGetQNoti();
            }
        }
        //obtain question and display
        function doViewQ() {
            
            //extract the question title passed via the url
            var qTitle = <?php echo '"'.$_GET['q'].'"'; ?>;
            //escape the string to be sent through the url
            qTitle = escape(qTitle);
            
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/qstn/questionTitle/' + qTitle,
                             
                success: function(data) {
                                  
                    var jsonObj = JSON.parse(data);
                    //synchornous method to obtain user information of the question being searched
                    getOriUser( decodeURIComponent(jsonObj[0].userId),function(output){
                        //write the data obtained on thw page
                        writeQHTML(output ,jsonObj);
                        //obtain answres posted by user for teh question
                        getAnswers();
                        //if user came to the link boia a notificiation, update notification as read
                        updateSeenQNoti();  
                        updateSeenANoti();
                    });                    
                    
                   
                },
                type: "GET"		
            });
         }
         
         //write data on the page
         function writeQHTML(userNameVal, jsonObj){ 
            
            var userNameObj = JSON.parse(userNameVal);
            var loggedInUser = '<?php echo $username; ?>';
            var postedUser = userNameObj[0].userName;
            var loggedInUserType = '<?php echo $usertype; ?>';
            var customAnsQ = '';
            var resHTML = '';
            
            var arrayTags = (jsonObj[0].tags).split(',');
            //give question management access levels to users depending on the user type. S-Student            
            if(loggedInUserType === 'S' && loggedInUser === postedUser){
                 customAnsQ = '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick="javascript:deleteQuestion()">Delete</button><button type="button" class="btn btn-default" onclick="location.href=\'https://localhost/ABSOLUTE/index.php/auth/redirect_editQ?q=' + decodeURIComponent(jsonObj[0].questionTitle) + '\';">Edit</button></div></td>';
            }
            
            if(loggedInUserType !== 'S' && loggedInUser === postedUser){
                 customAnsQ = '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick="javascript:deleteQuestion()">Delete</button><button type="button" class="btn btn-default" onclick="location.href=\'https://localhost/ABSOLUTE/index.php/auth/redirect_editQ?q=' + decodeURIComponent(jsonObj[0].questionTitle) + '\';">Edit</button><button type="button" class="btn btn-primary" onclick="javascript:doViewAnswerQuestion()">Answer</button></div></td>';
            }    
            
            if(loggedInUserType !== 'S' && loggedInUser !== postedUser){
                 customAnsQ = '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick="javascript:postQReport()">Report</button><button type="button" class="btn btn-primary" onclick="javascript:doViewAnswerQuestion()">Answer</button></div></td>';
            } 
            
            if(loggedInUserType === 'S' && loggedInUser !== postedUser){
                 customAnsQ = '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick="javascript:postQReport()">Report</button></div></td>';
            }
            //give facilities to the original poster of the question than a thirdparty viewer            
            if(loggedInUser === postedUser){
                resHTML = resHTML + 
                '<tr>' +
                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[0].votes) + '</span></h3></td>'+
                '<td width="5" height="50"><a class="navbar-brand" href="javascript:doVote(1,'+ decodeURIComponent(jsonObj[0].votes) +')" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a></td>'+
                '<td rowspan="2" style="vertical-align:middle" width="80%"><strong><a href="javascript:doViewQ()" id="qTitle">'+ decodeURIComponent(jsonObj[0].questionTitle) + '</a></strong></br>'+ decodeURIComponent(jsonObj[0].questionBody) + '</td>' + 
                customAnsQ+
                '</tr>'+
                '<tr>' +
                '<td width="5"><a class="navbar-brand" href="javascript:doVote(0,'+ decodeURIComponent(jsonObj[0].votes) +')" role="button"><span class="glyphicon glyphicon-chevron-down"></span></a></td>'+
                '</tr>'+
                '<tr>' +
                '<td></td>'+
                '<td></td>'+
                '<td><div align="left" style="width:50%; float: left;"><span class="glyphicon glyphicon-tag"></span> ';
                for (var i = 0; i < arrayTags.length; i++) {
                    resHTML = resHTML + '<span class="label label-default">'+ arrayTags[i] +'</span> ';
                }
                    
                resHTML = resHTML + '</div><div align="right" style="width:40%; float: right;"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ postedUser +' "><span class="label label-primary" id = "oriUser"> '+ postedUser + ' </span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[0].postDate) + ' </span></div></td>'+
                '</tr>';
            }
            //give lesser previleges for non registered users
            if(loggedInUser !== postedUser && loggedInUser !== ''){
                resHTML = resHTML + 
                '<tr>' +
                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[0].votes) + '</span></h3></td>'+
                '<td width="5" height="50"><a class="navbar-brand" href="javascript:doVote(1,'+ decodeURIComponent(jsonObj[0].votes) +')" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a></td>'+
                '<td rowspan="2" style="vertical-align:middle" width="80%"><strong><a href="javascript:doViewQ()" id="qTitle">'+ decodeURIComponent(jsonObj[0].questionTitle) + '</a></strong></br>'+ decodeURIComponent(jsonObj[0].questionBody) + '</td>' +
                customAnsQ+
                '</tr>'+
                '<tr>' +
                '<td width="5"><a class="navbar-brand" href="javascript:doVote(0,'+ decodeURIComponent(jsonObj[0].votes) +')" role="button"><span class="glyphicon glyphicon-chevron-down"></span></a></td>'+
                '</tr>'+
                '<tr>' +
                '<td></td>'+
                '<td></td>'+
                '<td><div align="left" style="width:50%; float: left;"><span class="glyphicon glyphicon-tag"></span> ';
                //loop through the tags string to display them interactively
                for (var i = 0; i < arrayTags.length; i++) {
                    resHTML = resHTML + ' <span class="label label-default">'+ arrayTags[i] +'</span> ';
                }
                    
                resHTML = resHTML + ' </div><div align="right" style="width:40%; float: right;"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ postedUser +'"><span class="label label-primary" id = "oriUser">'+ postedUser + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[0].postDate) + '</span></div></td>'+
                '</tr>';
            }
            if(loggedInUser === ''){
                resHTML = resHTML + 
                '<tr>' +
                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[0].votes) + '</span></h3></td>'+
                '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="disabled glyphicon glyphicon-chevron-up"></span></button></td>'+
                '<td rowspan="2" style="vertical-align:middle" width="80%"><strong><a href="javascript:doViewQ()" id="qTitle">'+ decodeURIComponent(jsonObj[0].questionTitle) + '</a></strong></br>'+ decodeURIComponent(jsonObj[0].questionBody) + '</td>'+
                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="disabled btn btn-default">Report</button><button type="button" class="disabled btn btn-primary">Answer</button></div></td>'+
                '</tr>' +
                '<tr>' +
                '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="glyphicon glyphicon-chevron-down"></span></button></td>'+
                '</tr>'+
                '<tr>' +
                '<td></td>'+
                '<td></td>'+
                '<td><div align="left" style="width:50%; float: left;"><span class="glyphicon glyphicon-tag"></span> ';
                for (var i = 0; i < arrayTags.length; i++) {
                    resHTML = resHTML + '<span class="label label-default">'+ arrayTags[i] +'</span> ';
                }
                    
                resHTML = resHTML + '</div><div align="right" style="width:40%; float: right;"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ postedUser +'"><span class="label label-primary" id = "oriUser">'+ postedUser + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[0].postDate) + '</span></div></td>'+
                '</tr>';
            }                                       
            
            document.getElementById('qres').innerHTML = resHTML;
            
        }
        //get the original poster of the question
        function getOriUser(userIdVal, handleData) {
            var idVal = encodeURIComponent(userIdVal);
            
            $.ajax({
                url:'<?php echo base_url();?>index.php/rest/resource/userName/id/' + idVal,  
                success:function(data) {
                    handleData(data); 
                },
                type: "GET"
            });
        }   
        //get view the answers of the selected quesition
        function doViewAnswerQuestion(){
            document.getElementById('postAns').innerHTML = '<div class="container"><div class="row"><div class="col-lg-10 col-lg-offset-1"><form class="well form-horizontal"><div id = "searchRes"><textarea name="aBody" id="aBody" class="form-control" rows="10" style="resize: none;"></textarea></br><input type="button" onclick="javascript:doAnswerQuestion()" value="Answer Question" class="btn btn-success"></form></div></div></div>';     
        } 
        //post answer to question
        function doAnswerQuestion(){
            //obtain user user input question tite
            var qTitle = document.getElementById('qTitle').innerHTML;
            
            //obtain user user input question body
            var aBody = (document.getElementById("aBody").value).replace(/\.$/, "").trim();
            
            //obtain user user id
            var aUserId = '<?php echo $user_id ;?>';
            
            //encode the received string so that is URI worthy
            qTitle = escape(qTitle);
            aBody = escape(aBody);
            aUserId = encodeURIComponent(aUserId);           
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/answers/uId/' + aUserId + '/qTitle/' + qTitle + '/aBody/' + aBody,              
                success: function(data) {
                    var alertObj = JSON.parse(data);
                    //provide alert depending on the success or failure
                    if (alertObj.status !== 0)
                    {
                        document.getElementById('info').innerHTML =  '<div class="alert alert-danger">Oops! :( something went wrong! Are you trying to post an existing question? if not please recheck your input/ connection.</div>';                      
                    }
                    else{
                        
                        document.getElementById('postAns').innerHTML = '<div class="alert alert-success">You answer is recorded. Thank you for your contribution :)</div>';
                       
                    }
                    location.reload();

                },
                //set action type
                type: "POST"		
            });
        } 
        //
        function doVote(vType, currVotes){
                   
            //obtain user user input question tite
            var vQTitle = document.getElementById('qTitle').innerHTML;   
            
            //obtain user user id
            var vUserId = '<?php echo $user_id ;?>';
            var oriPostUser = (document.getElementById('oriUser').innerHTML).trim(); 
            
            //encode the received string so that is URI worthy
            vQTitle = escape(vQTitle);            
            vUserId = encodeURIComponent(vUserId);            
            oriPostUser = encodeURIComponent(oriPostUser);  
            //vQTitle = vQTitle.replace(/\*/g,'%2A');
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/questionVote/uId/' + vUserId + '/qTitle/' + vQTitle + '/voteType/' + encodeURIComponent(vType) + '/cVotes/' + encodeURIComponent(currVotes) + '/OriUId/' + oriPostUser,
                        
                success: function(data) {
                   
                    location.reload();
                },
                //set action type
                type: "PUT"		
            });
        }
        //update question notification as seen if user came via a notification link
        function updateSeenQNoti(){
                   
            //obtain user user input question tite
            var seenQTitle = document.getElementById('qTitle').innerHTML;
            
            //obtain user user id
            var seenUserId = '<?php echo $user_id ;?>';
           
            //encode the received string so that is URI worthy
            seenQTitle = escape(seenQTitle);             
            seenUserId = encodeURIComponent(seenUserId);            
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/seenQNoti/uId/' + seenUserId + '/qTitle/' + seenQTitle,
                            
                success: function(data) {
                 
                },
                //set action type
                type: "PUT"		
            });
        }
        //update answer notification as seen if user came via a notification link
        function updateSeenANoti(){
                   
            //obtain user user input question tite
            var seenQTitle = document.getElementById('qTitle').innerHTML;  
            
            //obtain user user id
            var seenUserId = '<?php echo $user_id ;?>';
           
            //encode the received string so that is URI worthy
            seenQTitle = escape(seenQTitle);            
            seenUserId = encodeURIComponent(seenUserId);    
            //seenQTitle = seenQTitle.replace(/\*/g,'%2A');
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/seenANoti/uId/' + seenUserId + '/qTitle/' + seenQTitle,
                           
                success: function(data) {
                   
                },
                //set action type
                type: "PUT"		
            });
        }
        //obtain answer list for the selected question
        function getAnswers(){
            var aQTitle = document.getElementById('qTitle').innerHTML;
            var oriUser = (document.getElementById('oriUser').innerHTML).trim();
            
            aQTitle = escape(aQTitle); 
            
            $.ajax({
                url:'<?php echo base_url();?>index.php/rest/resource/getAnswers/qTitle/' + aQTitle,  
                success:function(data) {
                    
                    var ansHTML = '';
                    var jsonObj = JSON.parse(data);
                    var acceptAnsHTML = '';
                    
                    for(var i = 0; i < jsonObj.length; i++) {
                        
                        var loggedInUser = '<?php echo $username ?>';
                        //give previleges to users according to user type or login status
                        if(loggedInUser === oriUser && jsonObj[i].acceptedAns === '1'){
                            acceptAnsHTML = '<button type="button" class="disabled btn btn-success" onclick="javascript:acceptAnswer('+ decodeURIComponent(jsonObj[i].answerId) +')">Accept</button>';
                        }
                        
                        if(loggedInUser === oriUser && jsonObj[i].acceptedAns === '0'){
                            acceptAnsHTML = '<button type="button" class="btn btn-success" onclick="javascript:acceptAnswer('+ decodeURIComponent(jsonObj[i].answerId) +')">Accept</button>';
                        }
                        
                        if(loggedInUser !== oriUser){
                            acceptAnsHTML = '';
                        }
                        
                        //loop though the answer set and diplay the answers with proper previleges to each user type
                        if(jsonObj[i].acceptedAns === '1'){
                            if(loggedInUser === jsonObj[i].username){
                                ansHTML = ansHTML +                              
                                '<tr class="success">' +
                                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td>'+
                                '<td width="5" height="50"><a class="navbar-brand" href="javascript:doAVote(1,'+ decodeURIComponent(jsonObj[i].votes) +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a></td>'+
                                '<td rowspan="2" style="vertical-align:middle" width="80%">'+ decodeURIComponent(jsonObj[i].answer) + '</td>' + 
                                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="disabled btn btn-default" onclick="javascript:deleteAnswer('+ decodeURIComponent(jsonObj[i].answerId) +')">Delete</button>'+acceptAnsHTML+'</div></td>'+
                                '</tr>'+
                                '<tr class="success">' +
                                '<td width="5"><a class="navbar-brand" href="javascript:doAVote(0,'+ decodeURIComponent(jsonObj[i].votes) +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-down"></span></a></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td colspan="3" align="right"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ jsonObj[i].username +'"><span class="label label-primary">'+ jsonObj[i].username + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[i].postDate) + '</span></td>'+
                                '</tr>';
                            }
                            if(loggedInUser !== jsonObj[i].username && loggedInUser !== ''){
                                ansHTML = ansHTML + 
                                '<tr class="success">' +
                                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td>'+
                                '<td width="5" height="50"><a class="navbar-brand" href="javascript:doAVote(1,'+ decodeURIComponent(jsonObj[i].votes) +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a></td>'+
                                '<td rowspan="2" style="vertical-align:middle" width="80%">'+ decodeURIComponent(jsonObj[i].answer) + '</td>' +
                                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick = "javascript: postAReport('+ decodeURIComponent(jsonObj[i].answerId) +',"'+ decodeURIComponent(jsonObj[i].answer) +'")">Report</button>'+acceptAnsHTML+'</td>'+
                                '</tr>'+
                                '<tr class="success">' +
                                '<td width="5"><a class="navbar-brand" href="javascript:doAVote(0,'+ decodeURIComponent(jsonObj[i].votes)  +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-down"></span></a></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td colspan="3" align="right"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ jsonObj[i].username +'"><span class="label label-primary">'+ jsonObj[i].username + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[i].postDate) + '</span></td>'+
                                '</tr>';
                            }
                            if(loggedInUser === ''){
                                ansHTML = ansHTML + 
                                '<tr class="success">' +
                                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td>'+
                                '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="disabled glyphicon glyphicon-chevron-up"></span></button></td>'+
                                '<td rowspan="2" style="vertical-align:middle" width="80%">'+ decodeURIComponent(jsonObj[i].answer) + '</td>' +
                                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="disabled btn btn-default">Report</button><button type="button" class="disabled btn btn-success">Accept</button></div></td>'+
                                '</tr>' +
                                '<tr class="success">' +
                                '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="glyphicon glyphicon-chevron-down"></span></button></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td colspan="3" align="right"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ jsonObj[i].username +'"><span class="label label-primary">'+ jsonObj[i].username + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[i].postDate) + '</span></td>'+
                                '</tr>';
                            }
                        }else{
                            if(loggedInUser === jsonObj[i].username){
                                ansHTML = ansHTML +                              
                                '<tr>' +
                                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td>'+
                                '<td width="5" height="50"><a class="navbar-brand" href="javascript:doAVote(1,'+ decodeURIComponent(jsonObj[i].votes) +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a></td>'+
                                '<td rowspan="2" style="vertical-align:middle" width="80%">'+ decodeURIComponent(jsonObj[i].answer) + '</td>' + 
                                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick="javascript:deleteAnswer('+ decodeURIComponent(jsonObj[i].answerId) +')">Delete</button>'+acceptAnsHTML+'</div></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td width="5"><a class="navbar-brand" href="javascript:doAVote(0,'+ decodeURIComponent(jsonObj[i].votes)  +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-down"></span></a></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td colspan="3" align="right"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ jsonObj[i].username +'"><span class="label label-primary">'+ jsonObj[i].username + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[i].postDate) + '</span></td>'+
                                '</tr>';
                            }
                            if(loggedInUser !== jsonObj[i].username && loggedInUser !== ''){
                                ansHTML = ansHTML + 
                                '<tr>' +
                                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td>'+
                                '<td width="5" height="50"><a class="navbar-brand" href="javascript:doAVote(1,'+ decodeURIComponent(jsonObj[i].votes) +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-up"></span></a></td>'+
                                '<td rowspan="2" style="vertical-align:middle" width="80%">'+ decodeURIComponent(jsonObj[i].answer) + '</td>' +
                                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick = "javascript: postAReport('+ decodeURIComponent(jsonObj[i].answerId) +',\''+ decodeURIComponent(jsonObj[i].answer) +'\')">Report</button>'+acceptAnsHTML+'</td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td width="5"><a class="navbar-brand" href="javascript:doAVote(0,'+ decodeURIComponent(jsonObj[i].votes) +',\''+ decodeURIComponent(jsonObj[i].username) +'\','+ decodeURIComponent(jsonObj[i].answerId) +')" role="button"><span class="glyphicon glyphicon-chevron-down"></span></a></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td colspan="3" align="right"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ jsonObj[i].username +'"><span class="label label-primary">'+ jsonObj[i].username + '</span></a><span class="label label-primary"> '+ decodeURIComponent(jsonObj[i].postDate) + '</span></td>'+
                                '</tr>';
                            }
                            if(loggedInUser === ''){
                                ansHTML = ansHTML + 
                                '<tr>' +
                                '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[i].votes) + '</span></h3></td>'+
                                '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="disabled glyphicon glyphicon-chevron-up"></span></button></td>'+
                                '<td rowspan="2" style="vertical-align:middle" width="80%">'+ decodeURIComponent(jsonObj[i].answer) + '</td>' +
                                '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="disabled btn btn-default">Report</button><button type="button" class="disabled btn btn-success">Accept</button></div></td>'+
                                '</tr>' +
                                '<tr>' +
                                '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="glyphicon glyphicon-chevron-down"></span></button></td>'+
                                '</tr>'+
                                '<tr>' +
                                '<td colspan="3" align="right"><span class="glyphicon glyphicon-cloud-upload"></span> <a href="https://localhost/ABSOLUTE/index.php/auth/redirect_profile?u='+ jsonObj[i].username +'"><span class="label label-primary">'+ jsonObj[i].username + '</span></a> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[i].postDate) + '</span></td>'+
                                '</tr>';
                            }
                        
                        }
            
                        document.getElementById('ares').innerHTML = ansHTML;
                    }
                    
                },
                type: "GET"
            });
            
        }
        //accept answer cna be done only by the poster of the question
        function acceptAnswer(answerId){
            //obtain user user input question tite            
            var acceptQTitle = document.getElementById('qTitle').innerHTML;           
                    
            //encode the received string so that is URI worthy
            acceptQTitle = escape(acceptQTitle);            
            answerId = encodeURIComponent(answerId);           
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/acceptAns/ansId/' + answerId + '/qTitle/' + acceptQTitle,
                          
                success: function(data) {    
                    //refresh the page
                    location.reload();
                },
                //set action type
                type: "PUT"		
            });
        
        }
        //delete question - can only be done by the author of the question
        function deleteQuestion(){
            
            var deleteQTitle = document.getElementById('qTitle').innerHTML;           
             
            //encode the received string so that is URI worthy
            deleteQTitle = escape(deleteQTitle);
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/deleteQuestion/qTitle/' + deleteQTitle,
                //data: data,               
                success: function(data) {
                    gotoView();
                                       
                },
                //set action type
                type: "DELETE"		
            });
        }
        
        function gotoView(){
             window.location.assign('https://localhost/ABSOLUTE/index.php/auth/redirect_home');             
         }
        //delete answer -can be done by the author of the answer
        function deleteAnswer(answerId){
            
            var answerIdVal = encodeURIComponent(answerId);                 
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/deleteAnswer/aId/' + answerIdVal,
                //data: data,               
                success: function(data) {
                    location.reload();
                                     
                },
                //set action type
                type: "DELETE"		
            });
        }
        //report question - can be performed by any logged in user
        function postQReport(){
            var reportQTitle = document.getElementById('qTitle').innerHTML;
            
            reportQTitle = escape(reportQTitle);
                 
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/questionReport/qTitle/' + reportQTitle,
                          
                success: function(data) {
                    
                    document.getElementById('info').innerHTML = '<div class="alert alert-success">Your report is being reviewed. We value your concern :) </div>';
                                        
                },
                //set action type
                type: "POST"		
            });
        }
        //report answer - can be performed by any logged in user
        function postAReport(ansId, ansBody){
                        
            ansId = encodeURIComponent(ansId);
            ansBody = escape(ansBody);
            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/answerReport/aId/' + ansId + '/aBody/' + ansBody,
                   
                success: function(data) {
                   
                    document.getElementById('info').innerHTML = '<div class="alert alert-success">Your report is being reviewed. We value your concern :) </div>';
                    
                },
                //set action type
                type: "POST"		
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
                       ;
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
         //vote an answer -can be done by any logged in user
         function doAVote(vType, currVotes, oriAnsUser, ansId){
           
            //obtain user user id
            var vUserId = encodeURIComponent('<?php echo $user_id ;?>');
             
                      
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/answerVote/uId/' + vUserId + '/aId/' + encodeURIComponent(ansId) + '/voteType/' + encodeURIComponent(vType) + '/cVotes/' + encodeURIComponent(currVotes) + '/OriUId/' + encodeURIComponent(oriAnsUser),
                              
                success: function(data) {
                    location.reload();
                },
                //set action type
                type: "PUT"		
            });
        }
    </script>
        
</html>