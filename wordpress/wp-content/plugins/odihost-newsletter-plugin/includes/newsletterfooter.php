<?php

function get_newsletter_footer($subscriber)
{
	$message .= get_option ("newsletter_footer");

	$url = get_option('siteurl') .'/wp-content/plugins/odihost-newsletter-plugin/includes/subscribe.php?type=remove&';
	$url .= "key=".md5($subscriber->email.$subscriber->name);
	$message .= "<br><br>You can unsubscribe at ". $url;
	
	return $message;
}
?>