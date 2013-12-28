<?php
include_once('../../../wp-load.php');

unset($id);
if (isset($_GET["id"]) && !empty($_GET["id"]))
{
	$id = intval($_GET["id"]);
	$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
	if (intval($file_details["id"]) == 0) unset($id);
}

if (empty($id)) die("Invalid service call");

header('Content-Type: text/html; charset=utf-8');
if (isset($_POST["robokassa_confirm"]))
{
	$transaction_id = 1;
	$price = $paiddownloads->robokassa_calc_price($file_details["price"], $file_details["currency"]);
	if ($price === false)
	{
		$button = '
		<div class="payment_selector">
		<strong>Service is not available now!</strong><br />Please try again later.
		</div>
		';
	}
	else
	{
		$button = '
		<div class="payment_selector">
		<strong>Payment confirmation</strong>
		<table class="payment_selector_summary">
			<tr>
				<td style="width: 120px;">Product: </td>
				<td><strong>'.htmlspecialchars($file_details["title"], ENT_QUOTES).'</strong></td>
			</tr>
			<tr>
				<td>Price: </td>
				<td><strong>'.$file_details["price"].' '.$file_details["currency"].'</strong></td>
			</tr>
			<tr>
				<td>Delivery&nbsp;e-mail: </td>
				<td><strong>'.$_POST["paiddownloads_delivery_email"].'</strong></td>
			</tr>
			<tr>
				<td>Payment&nbsp;type: </td>
				<td><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/robokassa.png" border="0" height="12"></td>
			</tr>
		</table>
		<form action="'.($paiddownloads->robokassa_sandbox == 'on' ? 'http://test.robokassa.ru/Index.aspx' : 'https://merchant.roboxchange.com/Index.aspx').'" method="post" style="padding: 0px; margin: 18px 0px 0px 0px;">
			<input type="hidden" name="MrchLogin" value="'.htmlspecialchars($paiddownloads->robokassa_merchant, ENT_QUOTES).'">
			<input type="hidden" name="OutSum" value="'.$price.'">
			<input type="hidden" name="InvId" value="'.$transaction_id.'">
			<input type="hidden" name="Desc" value="'.htmlspecialchars($file_details["title"], ENT_QUOTES).'">
			<input type="hidden" name="shpFileId" value="'.$id.'">
			<input type="hidden" name="shpEmail" value="'.$_POST["paiddownloads_delivery_email"].'">
			<input type="hidden" name="SignatureValue" value="'.md5(htmlspecialchars($paiddownloads->robokassa_merchant, ENT_QUOTES).':'.$price.':'.$transaction_id.':'.$paiddownloads->robokassa_password1.':shpEmail='.$_POST["paiddownloads_delivery_email"].':shpFileId='.$id).'">';
		if ($paiddownloads->buynow_type == "custom") $button .= '<input type="image" src="'.get_bloginfo("wpurl").'/wp-content/uploads/paid-downloads/'.rawurlencode($paiddownloads->buynow_image).'" border="0" name="submit" alt="Robokassa - The safer, easier way to pay online!">';
		else if ($paiddownloads->buynow_type == "paypal") $button .= '<input type="image" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/btn_buynow_LG.gif" border="0" name="submit" alt="Robokassa - The safer, easier way to pay online!">';
		else $button .= '<input type="submit" value="Buy Now">';
		$button .= '
		</form>
		</div>
		';
	}
	echo $button;
	exit;
}

if (!isset($_GET["redirect"]) || empty($_GET["redirect"])) die("Invalid service call");
$redirect = rawurldecode($_GET["redirect"]);
?>
<div class="payment_selector" style="height: 160px;">
<?php
	if ($paiddownloads->enable_paypal == "on")
	{
		$button = '
		<div class="payment_selector_button">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="padding: 0px; margin: 6px 0px 0px 0px;">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="'.$paiddownloads->paypal_id.'">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="item_name" value="'.htmlspecialchars($file_details["title"], ENT_QUOTES).'">
			<input type="hidden" name="item_number" value="'.$file_details["id"].'">
			<input type="hidden" name="amount" value="'.$file_details["price"].'">
			<input type="hidden" name="currency_code" value="'.$file_details["currency"].'">
			<input type="hidden" name="custom" value="">
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">
			<input type="hidden" name="return" value="'.rawurlencode($redirect).'">
			<input type="hidden" name="cancel_return" value="'.rawurlencode($redirect).'">
			<input type="hidden" name="notify_url" value="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/paypal_ipn.php">
			<input type="image" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/paypal.png" border="0" style="height: 48px;" alt="PayPal - The safer, easier way to pay online!">
		</form>
		</div>
		';
		echo $button;
	}
	if ($paiddownloads->enable_robokassa == "on")
	{
		$button = '
		<div class="payment_selector_button">
		<form class="nyroModal" id="paiddownloads_robokassa_form" action="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/payment_selector.php?id='.$id.'" method="post" style="padding: 0px; margin: 18px 0px 0px 0px;" >
			<input type="hidden" name="robokassa_confirm" value="true">
			<input type="image" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/robokassa.png" border="0" style="height: 23px;" alt="Robokassa - The safer, easier way to pay online!" onclick="var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/; var email = document.getElementById(\'paiddownloads_delivery_email\').value; if(reg.test(email) == false) { alert(\'Please enter valid e-mail address. We use this e-mail to deliver the product.\'); return false;}">
			<input type="text" class="paiddownloads_delivery_email" name="paiddownloads_delivery_email" id="paiddownloads_delivery_email" value="Your e-mail" onfocus="if (this.value == \'Your e-mail\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Your e-mail\';}">
		</form>
		</div>
		';
		echo $button;
	}
?>
</div>