<?php
	include ("../../../../wp-load.php");
	global $wpdb;
	$newsletterid = $_GET["id"];
	$subscriberid = $_GET["uid"];
	$url = $_GET["url"];
	
	$wpdb->query("insert into newsletterclickstat(newsletterclickstatnewsletterid, newsletterclickstatsubscriberid, newsletterclickstaturl, newsletterclickstatdate) 
	values('$newsletterid','$subscriberid','$url',now())");

	header('Location: ' .$url);	
?>