<?php

	$email_from = stripslashes(get_option('newsletter_email_from'));
		
	$subject = stripslashes($_REQUEST['newsletter_subject']);
	$message = stripslashes($_REQUEST['newsletter_message']);
	
	$blogname = get_option('blogname');
	
	global $wpdb;
	$table_users = $wpdb->prefix . "newsletter_users";
	$users = $wpdb->get_results("SELECT * FROM $table_users where status=1 ORDER BY `id` DESC");
	
	foreach ($users as $user) {
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= "From: $blogname <$email_from>\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	$headers .= 'From: '. $email_from . "\r\n";
	$to  = $user->email;
		$subject = stripslashes($_REQUEST['newsletter_subject']);
		$message = stripslashes($_REQUEST['newsletter_message']);

		$message = str_replace("*name*", $user->name, $message);
		$subject = str_replace("*name*", $user->name, $subject);

		$url = get_option('siteurl') .'/wp-content/plugins/odihost-newsletter-plugin/includes/subscribe.php?type=remove&';
	
		$newsletter_ip = odihost_getip();
		
		$url .= "key=".md5($user->email.$user->name);

		$message .= "\n\nYou can unsubscribe at ". $url;
		$message  = nl2br($message);
		if (mail($user->email,$subject,$message,$headers)) {
			echo "Emailed to " . $user->email."<br/>";		
		}
		else
		{
			echo("failed email " . $user->email);
		}
	}
?>