<?php
if (!defined('ABSPATH'))
{
	set_time_limit(0);
	include_once('../../../wp-load.php');

	if (isset($_GET["id"]))
	{
		$id = intval($_GET["id"]);
		$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
		if (intval($file_details["id"]) == 0) die("Invalid download link");
		if ($file_details["price"] != 0 && !current_user_can('manage_options')) die("Invalid download link");
	}
	else
	{
		if (!isset($_GET["download_key"])) die("Invalid download link");
		$download_key = $_GET["download_key"];
		$sql = "SELECT * FROM ".$wpdb->prefix."pd_downloadlinks WHERE download_key = '".$download_key."'";
		$link_details = $wpdb->get_row($sql, ARRAY_A);
		if (intval($link_details["id"]) == 0) die("Invalid download link");
		$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$link_details["file_id"]."'", ARRAY_A);
		if (intval($file_details["id"]) == 0) die("Invalid download link");
		if ($link_details["created"]+24*3600*intval($paiddownloads->link_lifetime) < time()) die("Download link was expired");
	}
	$filename = ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"];
	$filename_original = $file_details["filename_original"];

	if (!file_exists($filename) || !is_file($filename)) die("File not found");

	$length = filesize($filename);

	if (strstr($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: application-download");
		header("Content-Length: ".$length);
		header("Content-Disposition: attachment; filename=\"".$filename_original."\"");
		header("Content-Transfer-Encoding: binary");
	}
	else
	{
		header("Content-type: application-download");
		header("Content-Length: ".$length);
		header("Content-Disposition: attachment; filename=\"".$filename_original."\"");
	}

	$handle_read = fopen($filename, "rb");
	while (!feof($handle_read) && $length > 0)
	{
		$content = fread($handle_read, 1024);
		echo substr($content, 0, min($length, 1024));
		$length = $length - strlen($content);
		if ($length < 0) $length = 0;
	}
	fclose($handle_read);
}
?>