<?php

	include ("../../../../wp-load.php");
	global $wpdb;
	$key = $_REQUEST['key'];

	$key = odihostcheckValid($key );
	if($_REQUEST['type']=='remove')
	{
		$sql = "SELECT * FROM `" . $wpdb->prefix . "newsletter_users` WHERE MD5(CONCAT(`email`, `name`)) = '" . $key ."'";

		$results = $wpdb->get_results($sql );

		if(count($results) > 0)
		{	

			$update = "UPDATE " . $wpdb->prefix . "newsletter_users SET `status` = '3' WHERE `id` = ". $results[0]->id;
			$result =  $wpdb->query($update );

			echo("You are unsubscribed now!");
		}
		else
		{
			echo("Failed to verify your email.");
		}
	}
	else
	{
		$sql = "SELECT * FROM `". $wpdb->prefix . "newsletter_users` WHERE MD5(CONCAT(`email`, `name`)) = '" . $key ."' AND `status` = '0'";

		$results = $wpdb->get_results($sql );

		if (count($results) >0) {
			
			$update = "UPDATE " . $wpdb->prefix . "newsletter_users SET `status` = '1' WHERE `id` = ". $results[0]->id;
			$result = $wpdb->query($update );

			echo("Thank you. You are subscribed now!");

			$table_users = $wpdb->prefix . "newsletter_users";
			$email_to = $wpdb->get_var("SELECT email FROM $table_users where `id` = ". $results[0]->id);				
			
			$blogname = get_option("blogname");
			$blog_charset = get_option("blog_charset"); 
			$email_from = stripslashes(get_option('newsletter_email_from'));
			
			$headers = "MIME-Version: 1.0\n";
			$headers .= "From: $blogname <$email_from>\n";

			$subject = stripslashes(get_option('newsletter_email_subject_subscriber'));
			$message = stripslashes(get_option('newsletter_email_message_subscriber'));

			$message = str_replace("*name*", $user->name, $message);
			$subject = str_replace("*name*", $user->name, $subject);

			$url = get_option('siteurl') .'/wp-content/plugins/odihost-newsletter-plugin/includes/subscribe.php?type=remove&';
						
			$url .= "key=".md5($user->email.$user->name);

			$message .= "\n\nYou can unsubscribe at ". $url;
			if (mail($email_to,$subject,$message,$headers)) {
				//echo "Emailed to " . $user->email."<br/>";		

			}
			else
			{
				echo("Failed to verify your email.");
			}
		}
	}
?>