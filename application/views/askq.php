<html>   
    <head>
        <script src="/ABSOLUTE/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="/ABSOLUTE/js/bootstrap.js" type="text/javascript"></script>
        <script src="/ABSOLUTE/js/chosen.jquery.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/ABSOLUTE/css/bootstrap.css">
        <link rel="stylesheet" href="/ABSOLUTE/css/chosen.css">
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
                    <div class="offset4 span4">
                        <form class="well form-horizontal">   
                            <label>Question Title:</label>
                                <input type="text" name='qTitle' id="qTitle" class="form-control" > </br></br>
                            <label>Question Body:</label>
                                <textarea name="qBody" id="qBody" class="form-control" rows="10" style="resize: none;"></textarea></br></br>
                            <label>Tags:</label></br>
                                    <select data-placeholder="Tag your question here" style="width:600px;" multiple class="chosen-select" id="tags">
                                    <option value="" ></option>

                                    <?php 
                                        //set url to the rest api
                                        $url = "https://localhost/ABSOLUTE/index.php/rest/resource/qtag";
                                        //obtain respnse from the rest api (JSON string)
                                        $response = file_get_contents($url);
                                        //decode the json string
                                        $arr = json_decode($response, true);
                                        //loop through the json string
                                        foreach($arr as $item) {
                                            //echo out the tag name element from the json string into the multi select chosen plugin
                                            echo "<option>".$item['tagName']."</option></br>";
                                        } 
                                    ?>                        
                                    </select></br></br>
                            <input type="button" onclick="javascript:doAskQ()" value="Ask Question" class="btn btn-primary"/>
                            </br></br>
                            <div id="info">
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
        function doAskQ() {
            //obtain user user input question tite
            var qTitle = (document.getElementById("qTitle").value).replace(/\.$/, "").trim();
            //qTitle = qTitle.replace(/\*/g,'%2A');
           
            //obtain user user input question body
            var qBody = (document.getElementById("qBody").value).replace(/\.$/, "").trim();
            //qBody = qBody.replace(/\*/g,'%2A');
            //obtain user user id
            var qUserId = '<?php echo $user_id ;?>';
            //obtain user user input question tags
            var qTags = $(".chosen-select").val();
                 
            //encode the received string so that is URI worthy
            qTitle = escape(qTitle);
            qBody = escape(qBody);
            qUserId = encodeURIComponent(qUserId);
            qTags = encodeURIComponent(qTags);
            
            if(qTitle !== '' && qBody !== '' && qUserId !== '' && qTags !== ''){
                $.ajax({
                    //set url with data to be posted
                    url: 'https://localhost/ABSOLUTE/index.php/rest/resource/questions/uId/' + qUserId + '/qTitle/' + qTitle + '/qBody/' + qBody + '/qTag/' + qTags,
                    //data: data,               
                    success: function(data) {
                          
                        var alertObj = JSON.parse(data);

                        if (alertObj.status !== 0)
                        {
                            document.getElementById('info').innerHTML =  '<div class="alert alert-danger">Oops! :( something went wrong! please recheck your input/ connection</br>or maybe you are trying to duplicate a question </div>';                      
                        }
                        else{
                            gotoView(qTitle);
                        }
                    },
                    //set action type
                    type: "POST"		
                });
            }else{
                document.getElementById('info').innerHTML =  '<div class="alert alert-danger">Oops! :( something went wrong! please recheck your input/ connection.</div>';                      
                        
            }
	 }	 
         
         function gotoView(qsTitle){
             window.location.assign('https://localhost/ABSOLUTE/index.php/auth/redirect_viewQ?q=' + qsTitle);             
         }
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
     
     
    <script type="text/javascript">
        //chosen plugin related code - used for setting up the tag box
        var config = {
            '.chosen-select'           : {},
            '.chosen-select-deselect'  : {allow_single_deselect:true},
            '.chosen-select-no-single' : {disable_search_threshold:10},
            '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
            '.chosen-select-width'     : {width:"100%"}
        };
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }
    </script>
    
    
</html>
        