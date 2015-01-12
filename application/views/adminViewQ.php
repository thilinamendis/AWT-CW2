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
    <body onload="doViewQ()">
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
        
    </body>
    <script type="text/javascript" lang="javascript">
        //similar to public view question-has access ot delete any reported question
        function doViewQ() {
           
            var qTitle = <?php echo '"'.$_GET['q'].'"'; ?>;
          
            qTitle = escape(qTitle);
            $.ajax({
                url: '<?php echo base_url();?>index.php/rest/resource/qstn/questionTitle/' + qTitle,
                             
                success: function(data) {
                                       
                    var jsonObj = JSON.parse(data);
                                           
                    getOriUser( decodeURIComponent(jsonObj[0].userId),function(output){
                        writeQHTML(output ,jsonObj);
                        
                    });                    
                    
                   
                },
                type: "GET"		
            });
         }
         
         
         function writeQHTML(userNameVal, jsonObj){ 
            
            var userNameObj = JSON.parse(userNameVal);
           
            var postedUser = userNameObj[0].userName;
            var resHTML = '';
            
            var arrayTags = (jsonObj[0].tags).split(',');
                        
             
            resHTML = resHTML + 
            '<tr>' +
            '<td rowspan="2" style="vertical-align:middle"><h3><span class="label label-info">' + decodeURIComponent(jsonObj[0].votes) + '</span></h3></td>'+
            '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="disabled glyphicon glyphicon-chevron-up"></span></button></td>'+
            '<td rowspan="2" style="vertical-align:middle" width="80%"><strong><a href="javascript:doViewQ()" id="qTitle">'+ decodeURIComponent(jsonObj[0].questionTitle) + '</a></strong></br>'+ decodeURIComponent(jsonObj[0].questionBody) + '</td>' + 
            '<td rowspan="2" style="vertical-align:middle"><div class="btn-group-vertical"><button type="button" class="btn btn-default" onclick="javascript:deleteQuestion()">Delete</button></div></td>'+
            '</tr>'+
            '<tr>' +
            '<td width="5"><button type="button" class="disabled btn btn-default btn-lg"><span class="disabled glyphicon glyphicon-chevron-down"></span></a></td>'+
            '</tr>'+
            '<tr>' +
            '<td></td>'+
            '<td></td>'+
            '<td><div align="left" style="width:50%; float: left;"><span class="glyphicon glyphicon-tag"></span> ';
            for (var i = 0; i < arrayTags.length; i++) {
                resHTML = resHTML + '<span class="label label-default">'+ arrayTags[i] +'</span> ';
            }

            resHTML = resHTML + '</div><div align="right" style="width:40%; float: right;"><span class="glyphicon glyphicon-cloud-upload"></span> <span class="label label-primary" id = "oriUser">'+ postedUser + '</span> <span class="label label-primary"> '+ decodeURIComponent(jsonObj[0].postDate) + '</span></div></td>'+
            '</tr>';                         
            
            document.getElementById('qres').innerHTML = resHTML;
            
        }
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
        
        function deleteQuestion(){
            
            var deleteQTitle = document.getElementById('qTitle').innerHTML;           
             
            //encode the received string so that is URI worthy
            deleteQTitle = escape(deleteQTitle);            
            $.ajax({
                //set url with data to be posted
                url: 'https://localhost/ABSOLUTE/index.php/rest/resource/deleteRQuestion/qTitle/' + deleteQTitle,
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