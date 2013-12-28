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

$sql = "SELECT * FROM ".$wpdb->prefix."pd_transactions WHERE file_id = '".$id."' ORDER BY created DESC";
$rows = $wpdb->get_results($sql, ARRAY_A);
header('Content-Type: text/html; charset=utf-8');
?>
<table style="width: 920px;">
<tr><td style="text-align: center; font-weight: bold; font-family: arial, verdana; font-size: 14px;">Payment transactions</td></tr>
<tr><td>
<table class="paiddownloads_historytable">
<tr>
	<th>Payer</th>
	<th style="width: 100px;">Amount</th>
	<th style="width: 120px;">Status</th>
	<th style="width: 120px;">Txn type</th>
	<th style="width: 160px;">Created*</th>
</tr>
<?php
if (sizeof($rows) > 0)
{
	for ($i=0; $i<sizeof($rows); $i++)
	{
		echo '
<tr>
	<td>'.htmlspecialchars($rows[$i]["payer_email"].' ('.$rows[$i]["payer_name"].')', ENT_QUOTES).'</td>
	<td style="text-align: right;">'.$rows[$i]["gross"].' '.$rows[$i]["currency"].'</td>
	<td>'.$rows[$i]["payment_status"].'</td>
	<td>'.$rows[$i]["transaction_type"].'</td>
	<td>'.date("Y-m-d H:i:s", $rows[$i]["created"]).'</td>
</tr>
		';
	}
}
else
{
?>
<tr><td colspan="5" style="text-align: center; padding: 50px;">List is empty</td></tr>
<?php
}
?>
</table>
* - server time
</td></tr>
</table>