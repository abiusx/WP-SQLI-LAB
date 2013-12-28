<?php
include_once('../../../wp-load.php');

wp_get_current_user();
if ($current_user->ID == 0) die("You must be logged in to call this service");
if (!current_user_can('manage_options')) die("You must be logged in as administrator to call this service");
if (!isset($_GET["redirect"]) || empty($_GET["redirect"])) die("Invalid service call");
$redirect = rawurldecode($_GET["redirect"]);

unset($id);
if (isset($_GET["id"]) && !empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
	if (intval($file_details["id"]) == 0) unset($id);
}

if (isset($_GET["dosubmit"]))
{
	if (isset($_POST["submit"]))
	{		$title = trim(stripslashes($_POST["title"]));
		$price = trim(stripslashes($_POST["price"]));
		$price = number_format(floatval($price), 2, '.', '');
		$currency = trim(stripslashes($_POST["currency"]));
		
		if (empty($id) || is_uploaded_file($_FILES["file"]["tmp_name"]))
		{
			if (!is_uploaded_file($_FILES["file"]["tmp_name"]))
			{				setcookie("paiddownloads_error", "You must upload file", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
				header("Location: ".$redirect);
				exit;
			}
			else
			{				if (!empty($id))
				{
					if ($_FILES["file"]["name"] != $file_details["filename_original"])
					{
						setcookie("paiddownloads_error", "Uploaded file must have name ".$file_details["filename_original"], time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header("Location: ".$redirect);
						exit;
					}
				}
				$filename = md5($_FILES["file"]["name"].microtime()).".dat";
				if (!move_uploaded_file($_FILES["file"]["tmp_name"], ABSPATH."wp-content/uploads/paid-downloads/".$filename))
				{
					setcookie("paiddownloads_error", "Unable to save uploaded file on server", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
					header("Location: ".$redirect);
					exit;
				}
			}
			if (empty($title)) $title = $_FILES["file"]["name"];
			if (!empty($id))
			{
				$sql = "UPDATE ".$wpdb->prefix."pd_files SET 
					title = '".mysql_real_escape_string($title)."', 
					filename = '".$filename."', 
					filename_original = '".mysql_real_escape_string($_FILES["file"]["name"])."', 
					price = '".$price."',
					currency = '".$currency."',
					registered = '".time()."'
					WHERE id = '".$id."'";
				if ($wpdb->query($sql) !== false)
				{
					if (file_exists(ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"]) && is_file(ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"]))
						unlink(ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"]);
					setcookie("paiddownloads_info", "File successfully updated", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
					header("Location: ".$redirect);
					exit;
				}
				else
				{
					setcookie("paiddownloads_error", "Service is not available", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
					header("Location: ".$redirect);
					exit;
				}
			}
			else
			{
				$sql = "INSERT INTO ".$wpdb->prefix."pd_files (
					title, filename, filename_original, price, currency, registered) VALUES (
					'".mysql_real_escape_string($title)."',
					'".$filename."',
					'".mysql_real_escape_string($_FILES["file"]["name"])."',
					'".$price."',
					'".$currency."',
					'".time()."'
					)";
				if ($wpdb->query($sql) !== false)
				{					setcookie("paiddownloads_info", "File successfully added", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
					header("Location: ".$redirect);
					exit;
				}
				else
				{					setcookie("paiddownloads_error", "Service is not available", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
					header("Location: ".$redirect);
					exit;
				}
			}
		}
		else
		{
			$sql = "UPDATE ".$wpdb->prefix."pd_files SET 
				title = '".mysql_real_escape_string($title)."', 
				price = '".$price."',
				currency = '".$currency."',
				registered = '".time()."'
				WHERE id = '".$id."'";
			if ($wpdb->query($sql) !== false)
			{
				setcookie("paiddownloads_info", "File successfully updated", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
				header("Location: ".$redirect);
				exit;
			}
			else
			{
				setcookie("paiddownloads_error", "Service is not available", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
				header("Location: ".$redirect);
				exit;
			}
		}
	}
	header("Location: ".$redirect);
	exit;
}
header('Content-Type: text/html; charset=utf-8');
?>
<form enctype="multipart/form-data" method="post" style="margin: 0px; width: 480px;" action="<?php echo get_bloginfo('wpurl').'/wp-content/plugins/paid-downloads/add.php?dosubmit=1&redirect='.rawurlencode($redirect).(!empty($id) ? '&id='.$id : ''); ?>" onsubmit="return true;">
<table align="center" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; width: 100%;">
<tr><td colspan="2" style="padding: 2px 0px 6px 0px; text-align: center; font-weight: bold; font-family: arial, verdana; font-size: 14px;"><?php echo (!empty($id) ? 'Edit file details ('.$file_details['filename_original'].')' : 'Upload new file'); ?></td></tr>
<tr><td style="padding: 2px; font-family: arial, verdana; font-size: 13px; vertical-align: top;">Title:&nbsp;</td><td style="padding: 2px; text-align: left;"><input type="text" name="title" id="title" value="<?php echo htmlspecialchars($file_details['title'], ENT_QUOTES); ?>" style="width: 95%;"></td></tr>
<tr><td style="padding: 2px; font-family: arial, verdana; font-size: 13px; vertical-align: top;">File:&nbsp;</td><td style="padding: 2px; text-align: left;"><?php echo (!empty($id) ? '<strong>'.$file_details['filename_original'].'</strong><br />' : ''); ?><input type="file" name="file" id="file" style="width: 95%;"></td></tr>
<tr><td style="padding: 2px; font-family: arial, verdana; font-size: 13px; vertical-align: top;">Price:&nbsp;</td><td style="padding: 2px; text-align: left;"><input type="text" name="price" id="price" value="<?php echo (!empty($id) ? number_format($file_details['price'], 2, '.', '') : '0.00'); ?>" style="width: 80px;">
<select name="currency" id="currency" onchange="var robokassa_currencies = [<?php echo "'".implode("','", $paiddownloads->robokassa_currency_list)."'"; ?>]; if (jQuery.inArray(this.value, robokassa_currencies) == -1) {jQuery('#paiddownloads_currency_notes').text('PayPal only');} else {jQuery('#paiddownloads_currency_notes').text('PayPal and Robokassa');}"><?php 
for ($i=0; $i<sizeof($paiddownloads->currency_list); $i++)
{
echo '<option value="'.$paiddownloads->currency_list[$i].'"'.($paiddownloads->currency_list[$i] == $file_details['currency'] ? ' selected="selected"' : '').'>'.$paiddownloads->currency_list[$i].'</option>';
}
?></select> <label for="currency" id="paiddownloads_currency_notes"><?php echo ((empty($file_details['currency']) || in_array($file_details['currency'], $paiddownloads->robokassa_currency_list)) ? "PayPal and Robokassa" : "PayPal only"); ?></label>
</td></tr>
<tr><td colspan="2" style="padding-top: 14px; text-align: center;">
<input type="submit" name="submit" id="submit" value="Submit"></td></tr>
</table>
</form>