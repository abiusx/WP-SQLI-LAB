<?php

	include ("../../../../wp-load.php");
	$email = $_REQUEST["newsletter_email"];
	$name =  $_REQUEST["newsletter_name"];
	$email_from = stripslashes(get_option('newsletter_email_from'));
	$subject = stripslashes(get_option('newsletter_email_subject'));
	$message = stripslashes(get_option('newsletter_email_message'));
	$table_users = $wpdb->prefix . "newsletter_users";

	//activation link
	$url = get_bloginfo('wpurl') .'/wp-content/plugins/odihost-newsletter-plugin/includes/subscribe.php?';

	$newsletter_ip = odihost_getip();
	
	$url .= "key=".md5($email.$name);

	$message = str_replace('*link*', $url, $message);
		
	$blogname = get_option('blogname');
$header = "From: $blogname <$email_from>\n"
. "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
	$selectqry = "SELECT * FROM " . $table_users . " WHERE `email` = '" . $email ."'";
	if ($wpdb->query($selectqry)) {
		echo stripslashes(get_option('newsletter_msg_dup'));
	}
	else {
		if (mail($email,$subject,$message, $header)) {

				$query = "INSERT INTO " . $table_users . " 
					(joindate, ip, email, status, name) 
					VALUES (
					now(),
					'" . $newsletter_ip . "',
					'" . $email . "',0,
					'" . $name . "'	)";
				$result = $wpdb->query($query);

			echo stripslashes(get_option('newsletter_msg_sent'));
			
		} 
		else {
			echo stripslashes(get_option('newsletter_msg_fail'));
		}
	}
				
?>