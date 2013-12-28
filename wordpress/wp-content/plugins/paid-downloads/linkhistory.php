<?php
set_time_limit(0);
include_once('../../../wp-load.php');

if (!current_user_can('manage_options')) die("You must be logged in to call this service");

unset($id);
if (isset($_GET["id"]) && !empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
	if (intval($file_details["id"]) == 0) unset($id);
}

if (empty($id)) die("Invalid service call");

$sql = "SELECT * FROM ".$wpdb->prefix."pd_downloadlinks WHERE file_id = '".$id."' ORDER BY created DESC";
$rows = $wpdb->get_results($sql, ARRAY_A);
$days = intval($paiddownloads->link_lifetime);
header('Content-Type: text/html; charset=utf-8');
?>
<table style="width: 920px;">
<tr><td style="text-align: center; font-weight: bold; font-family: arial, verdana; font-size: 14px;">Download link history</td></tr>
<tr><td>
<table class="paiddownloads_historytable">
<tr>
	<th>Download link</th>
	<th style="width: 200px;">Owner</th>
	<th style="width: 80px;">Source</th>
	<th style="width: 150px;">Expires on*</th>
</tr>
<?php
if (sizeof($rows) > 0)
{
	for ($i=0; $i<sizeof($rows); $i++)
	{
		echo '
<tr>
	<td><input value="'.get_bloginfo('wpurl').'/wp-content/plugins/paid-downloads/download.php?download_key='.$rows[$i]["download_key"].'" style="width: 100%;"></td>
	<td>'.$rows[$i]["owner"].'</td>
	<td>'.$rows[$i]["source"].'</td>
	<td>'.date("Y-m-d H:i:s", $rows[$i]["created"]+24*3600*$days).'</td>
</tr>
		';
	}
}
else
{
?>
<tr><td colspan="4" style="text-align: center; padding: 50px;">List is empty</td></tr>
<?php
}
?>
</table>
* - server time
</td></tr>
</table>