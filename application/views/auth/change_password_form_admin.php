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
<?php
$old_password = array(
	'name'	=> 'old_password',
	'id'	=> 'old_password',
	'value' => set_value('old_password'),
	'size' 	=> 30,
);
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="input-group">
            <?php echo form_open($this->uri->uri_string(), "class='well form-horizontal'"); ?>
            <table>
                    <tr>
                            <td><?php echo form_label('Email Address'); ?></td>
                            <td><?php echo form_password($email); ?></td>
                            <td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>

                    </tr>
                    <tr>
                            <td><?php echo form_label('Old Password', $old_password['id']); ?></td>
                            <td><?php echo form_password($old_password); ?></td>
                            <td style="color: red;"><?php echo form_error($old_password['name']); ?><?php echo isset($errors[$old_password['name']])?$errors[$old_password['name']]:''; ?></td>
                    </tr>
                    <tr>
                            <td><?php echo form_label('New Password', $new_password['id']); ?></td>
                            <td><?php echo form_password($new_password); ?></td>
                            <td style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></td>
                    </tr>
                    <tr>
                            <td><?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?></td>
                            <td><?php echo form_password($confirm_new_password); ?></td>
                            <td style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></td>
                    </tr>
                    </br>
            </table>
            <?php echo form_submit('change', 'Change Password', "class='btn btn-primary'"); ?>
            <?php echo form_close(); ?>
        </div>
   </div>
</div>