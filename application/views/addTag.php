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
                    <div class="col-lg-3 col-lg-offset-5">
                        <form class="well form-horizontal">

                            <table>
                                <tr>
                                    <td>
                                        <div class="panel-body">

                                            <label>New tag</label>
                                            <input type="text" class="form-control" id="tagN" placeholder="Tag name">
                                            </br>
                                            <label>Tag description</label>
                                            <textarea name="tagDesc" id="tagD" class ="form-control" rows="2" style="resize:none;" placeholder="Description"></textarea>
                                            </br>

                                            <button type="button" class="btn btn-primary" onclick="javascript:doAddTag()">Add</button>
                                            </br>
                                            <div id="postStat"></div>

                                        </div>
                                    </td>                                
                                </tr>
                            </table> 
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
            //add tag
            function doAddTag() {
                //get user inputs and encode
                var tName = encodeURIComponent(document.getElementById('tagN').value);
                var tDesc = encodeURIComponent(document.getElementById('tagD').value);
                $.ajax({
                    //set url with data to be posted
                    url: 'https://localhost/ABSOLUTE/index.php/rest/resource/insertTag/tagN/' + tName + '/tagD/' + tDesc,

                    success: function(data) {

                        var alertObj = JSON.parse(data);
                        //perform action according to success or failure
                        if (alertObj.status !== 0)
                        {
                            document.getElementById('postStat').innerHTML =  '<div class="alert alert-danger">Oops! :( something went wrong! please recheck your input/ connection</br>or you muts be trying to input an existing tag</div>';                      
                        }
                        else{
                            location.reload();
                        }
                     },

                    type: "POST"		
                });
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
        