
Create a new password on <?php echo $site_name; ?>

Create a new password
Forgot your password, huh? No big deal.
To create a new password, just follow this link:
<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>

Link doesn't work? Copy the following link to your browser address bar:

<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>

You received this email, because it was requested by a <?php echo $site_name; ?> user. 
This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.

cheers!,
The <?php echo $site_name; ?> Team
