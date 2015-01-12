<?php echo 'Welcome to'.$site_name.'!'; ?>

<?php echo 'Thanks for joining '.$site_name.'.We listed your sign in details below, make sure you keep them safe. '; ?> 
<?php echo ''?>
<?php echo 'To verify your email address, please follow this link: ' ?>
<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key).' to finish your registration..'; ?>
<?php echo ''?>
<?php echo 'Link does not work? Copy the following link to your browser address bar: ';?>
<?php echo ''?>
<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>
<?php echo ''?>
<?php echo ' Please verify your email within '.$activation_period.' hours, otherwise your registration will become invalid and you will have to register again. '?>
<?php echo ''?>
<?php echo ''?>

<?php if (strlen($username) > 0) { ?>Your username: <?php echo $username; ?><?php } ?>

<?php echo 'Your email address: '.$email. ' '; ?>
<?php if (isset($password)) { /* ?>Your password: <?php echo $password; ?><br /><?php */ } ?>
<?php echo ''?>
<?php echo ''?>

<?php echo 'Have fun!'?>

<?php echo 'The '.$site_name. ' Team'; ?> 

