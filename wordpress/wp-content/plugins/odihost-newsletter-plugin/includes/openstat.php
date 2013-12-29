<?php

	include ("../../../../wp-load.php");
	global $wpdb;
	$newsletterid = $_GET["id"];
	$subscriberid = $_GET["uid"];
	$wpdb->query("update newsletterstat set newsletterstatopened = CONCAT(newsletterstatopened ,'". $subscriberid . ",') where newsletterstatnewsletterid= ". $newsletterid);

?>