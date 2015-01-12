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
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<div class="row">
    <div class="col-lg-4 col-lg-offset-5">
        <div class="input-group">
            <?php echo form_open($this->uri->uri_string(), "class='well form-horizontal'"); ?>
            <table>
                    <?php if ($use_username) { ?>
                    <tr>
                            <td><?php echo form_label('Username', $username['id']); ?></br>
                            <?php echo form_input($username); ?>
                            <font color="red"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></font></td>
                    </tr>
                    <?php } ?>
                    <tr>
                            <td><?php echo form_label('Email Address', $email['id']); ?></br>
                            <?php echo form_input($email); ?>
                            <font color="red"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></font></td>
                    </tr>
                    <tr>
                            <td><?php echo form_label('Password', $password['id']); ?></br>
                            <?php echo form_password($password); ?>
                            <font color="red"><?php echo form_error($password['name']); ?></font></td>
                    </tr>
                    <tr>
                            <td><?php echo form_label('Confirm Password', $confirm_password['id']); ?></br>
                            <?php echo form_password($confirm_password); ?>
                            <font color="red"><?php echo form_error($confirm_password['name']); ?></font></td>
                    </tr>

                    <?php if ($captcha_registration) {
                            if ($use_recaptcha) { ?>
                    <tr>
                            <td colspan="2">
                                    <div id="recaptcha_image"></div>
                            </td>
                            <td>
                                    <a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
                                    <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
                                    <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
                            </td>
                    </tr>
                    <tr>
                            <td>
                                    <div class="recaptcha_only_if_image">Enter the words above</div>
                                    <div class="recaptcha_only_if_audio">Enter the numbers you hear</div>
                            </td>
                            <td><input type="text" id="recaptcha_response_field" name="recaptcha_response_field" /></td>
                            <td style="color: red;"><?php echo form_error('recaptcha_response_field'); ?></td>
                            <?php echo $recaptcha_html; ?>
                    </tr>
                    <?php } else { ?>
                    <tr>
                            <td colspan="3">
                                    </br></br><p>Enter the code exactly as it appears:</p>
                                    <?php echo $captcha_html; ?>
                            </td>
                    </tr>
                    <tr>
                            <td></br><?php echo form_label('Confirmation Code', $captcha['id']); ?></br>
                            <?php echo form_input($captcha); ?>
                            <font color="red"><?php echo form_error($captcha['name']); ?></font></br></br></td>
                    </tr>
                    <?php }
                    } ?>
            </table>
            <?php echo form_submit('register', 'Register', "class='btn btn-primary'"); ?>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>