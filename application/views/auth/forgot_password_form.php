<head>
        <script src="/ABSOLUTE/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="/ABSOLUTE/js/bootstrap.js" type="text/javascript"></script>
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

<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login : ';
} else {
	$login_label = 'Email';
}
?>
<div class="row">
    <div class="col-lg-4 col-lg-offset-5">
        <div class="input-group">
            <?php echo form_open($this->uri->uri_string(), "class='well form-horizontal'"); ?>
                <?php echo form_label($login_label, $login['id']); ?></br>
                <?php echo form_input($login,"", 'class="form-control"'); ?>
                <font color="red"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></font>

                </br></br>
                <?php echo form_submit('reset', 'Get a new password', "class='btn btn-primary'"); ?>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>