<?php
/*
Returns the bio images from the DB
Future upgrades will include auto sizing and cropping
*/
?>
<?php
require_once('config.php');
header("Content-Type: image/jpeg");
error_reporting(0);
global $wpdb;
$query = "SELECT * FROM ".$wpdb->prefix."bios WHERE id='$_GET[id]'";
if (isset($_GET['uid'])) {
	$query = "SELECT * FROM ".$wpdb->prefix."bios WHERE uid='$_GET[uid]'";
}
$result = mysql_query($query);
if ($row = mysql_fetch_array($result)) {
	if (isset($row['photo'])) {
		echo $row['photo'];
	}
	else {
		echo file_get_contents('../images/unknown.jpg');
	}
}
else {
	echo file_get_contents('../images/unknown.jpg');
}
?>