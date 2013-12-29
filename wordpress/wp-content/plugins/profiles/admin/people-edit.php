<?php
/*
Submits updates to the bios
*/
?>
<?php
// Setup
require_once('../library/config.php');

if (!isset($_POST['new']) && !isset($_POST['delete']) ) {
	global $wpdb;
	mysql_query("UPDATE ".$wpdb->prefix."bios SET text='$_GET[text]' WHERE id='$_GET[id]'");
}
else if (isset($_POST['new'])) {
	global $wpdb;
	$first = $_POST['first'];
	$last = $_POST['last'];
	$slug = str_replace("'","",str_replace(" ","-",$first)."-".str_replace(" ","-",$last));
	if (isset($_POST['slug'])) {
		if($_POST['slug'] != "") {
			$slug = $_POST['slug'];
		}
	}
	$query = "INSERT INTO `".$wpdb->prefix."people` (`first_name`, `last_name`,`slug`) VALUES ('$first','$last','$slug')";
	mysql_query($query) or die("Error creating new user\n Query: $query\n ".mysql_error());
	$result = mysql_query("SELECT id FROM `".$wpdb->prefix."people` WHERE `first_name` LIKE '$first' and `last_name` LIKE '$last'") or die("Error retrieving new user id\n $query\n".mysql_error());
	$row = mysql_fetch_array($result);
	$query = "INSERT INTO `".$wpdb->prefix."bios` (`uid`,`text`) VALUES ('$row[id]','<p>$_POST[first] $_POST[last] ".get_option('profiles_default_text')."</p>')";
	mysql_query($query) or die("Error creating bio\n$query\n".mysql_error());
	die($query);
}
else if (isset($_POST['delete'])) {
	global $wpdb;
	$id = $_POST['id'];
	$bid = $_POST['bid'];
	mysql_query("DELETE FROM ".$wpdb->prefix."bios WHERE id='$bid'") or die ("Error deleting bio $bid\n".mysql_error());
	mysql_query("DELETE FROM ".$wpdb->prefix."people WHERE id='$id'") or die ("Error deleting person $id\n".mysql_error());
	die("Deleted Person $id, with bio $bid");
	}
?>