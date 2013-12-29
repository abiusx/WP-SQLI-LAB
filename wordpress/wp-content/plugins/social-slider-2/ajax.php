<?php 
require_once(dirname(__FILE__).'/../../../wp-config.php');
global $wpdb, $table_prefix;

$SocialSliderArray 	= $_POST['rA'];

if (mysql_real_escape_string($_POST['action']) == "ZapiszPozycje")
	{
	$lC = 1;
	foreach ($SocialSliderArray as $recordIDValue)
		{
		$query = "UPDATE ".$table_prefix."socialslider SET lp = ".$lC." WHERE id = ".$recordIDValue;
		mysql_query($query);
		$lC = $lC + 1;	
		}
	}
?>