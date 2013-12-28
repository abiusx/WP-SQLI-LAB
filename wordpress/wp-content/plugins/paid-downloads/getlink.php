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

$generate = false;
if (isset($_POST["owner"]))
{	$generate = true;
	$owner = stripslashes($_POST["owner"]);
	$errors = array();
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $owner) || strlen($owner) == 0)
	{
		$generate = false;
		$message = "Download link owner must be valid e-mail address";
	}
}

header('Content-Type: text/html; charset=utf-8');
if ($generate)
{	$link = $paiddownloads->generate_downloadlink($file_details["id"], $owner, "manual");
	$days = intval($paiddownloads->link_lifetime);
?>
<div style="margin: 0px 10px 0px 10px; text-align: left; width: 480px;">
<table style="width: 100%;">
<tr><td colspan="2" style="text-align: center; font-weight: bold; padding-bottom: 20px; font-family: arial, verdana; font-size: 14px;">Download link details</td></tr>
<tr><td style="vertical-align: top; padding: 3px 10px 0px 0px; font-family: arial, verdana; font-size: 13px; width: 100px;">File:</td><td style="vertical-align: top; padding: 3px 0px 10px 0px; font-weight: bold; font-family: arial, verdana; font-size: 13px;"><?php echo htmlspecialchars($file_details["title"], ENT_QUOTES)." / ".htmlspecialchars($file_details["filename_original"], ENT_QUOTES); ?></td></tr>
<tr><td style="vertical-align: top; padding: 3px 10px 0px 0px; font-family: arial, verdana; font-size: 13px;">Download&nbsp;link:</td><td style="vertical-align: top; padding: 3px 0px 10px 0px; font-weight: bold; font-family: arial, verdana; font-size: 13px;"><textarea style="width: 100%; height: 80px;"><?php echo htmlspecialchars($link, ENT_QUOTES); ?></textarea></td></tr>
<tr><td style="vertical-align: top; padding: 3px 10px 0px 0px; font-family: arial, verdana; font-size: 13px;">Created:</td><td style="vertical-align: top; padding: 3px 0px 10px 0px; font-weight: bold; font-family: arial, verdana; font-size: 13px;"><?php echo date("Y-m-d H:i:s"); ?> (server time)</td></tr>
<tr><td style="vertical-align: top; padding: 3px 10px 0px 0px; font-family: arial, verdana; font-size: 13px;">Expires&nbsp;on:</td><td style="vertical-align: top; padding: 3px 0px 10px 0px; font-weight: bold; font-family: arial, verdana; font-size: 13px;"><?php echo date("Y-m-d H:i:s", time()+3600*24*$days); ?> (server time)</td></tr>
</table>
</div>
<?
}
else
{
?>
<form class="nyroModal" method="post" action="<?php echo get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/getlink.php?id='.$id; ?>" style="width: 400px;">
<div style="margin: 0px 10px 0px 10px; text-align: left;">
<table style="width: 100%;">
<tr><td colspan="2" style="text-align: center; font-weight: bold; font-family: arial, verdana; font-size: 14px;">Generate download link</td></tr>
<tr><td style="padding: 2px; font-family: arial, verdana; font-size: 13px; vertical-align: top;">Link owner:&nbsp;</td><td style="padding: 2px; text-align: left;"><input type="text" name="owner" id="owner" value="<?php echo $owner; ?>" style="width: 95%;"></td></tr>
<tr><td colspan="2" style="text-align: center; vertical-align: top; padding: 10px;"><input type="submit" name="submit" value="Get Link" style="padding: 2px 10px; cursor: pointer;"></td></tr>
</table>
<?php echo (!empty($message) ? '<div class="wplicensor_errorbox">'.$message.'</div>' : ''); ?>
</div>
</form>
<?php
}
?>