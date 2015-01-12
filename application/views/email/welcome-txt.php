Welcome to <?php echo $site_name; ?>,

Thanks for joining <?php echo $site_name; ?>. We listed your signin details below. Make sure you keep them safe.
Follow this link to login to the site:

<?php echo site_url('/auth/login/'); ?>

<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Your email: <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

Cheers!
The <?php echo $site_name; ?> Team