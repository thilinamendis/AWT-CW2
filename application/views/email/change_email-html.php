Your new email address on <?php echo $site_name; ?>


You have changed your email address for <?php echo $site_name; ?>.
Follow this link to confirm your new email address:

Confirm your new email
<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>

Link doesn't work? Copy the following link to your browser address bar:
<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?><?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>

Your email : <?php echo $new_email; ?>

You received this email, because it was requested by a <?php echo site_url(''); ?><?php echo $site_name; ?> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.

cheers!,
The <?php echo $site_name; ?> Team
