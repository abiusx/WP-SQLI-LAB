<?php
include_once('../../../wp-load.php');

$paypalurl = parse_url("https://www.paypal.com/cgi-bin/webscr");
$request = "cmd=_notify-validate";
foreach ($_POST as $key => $value)
{
	$value = urlencode(stripslashes($value));
	$request .= "&".$key."=".$value;
}
$header = "POST ".$paypalurl["path"]." HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: ".strlen($request)."\r\n\r\n";
$handle = fsockopen("ssl://".$paypalurl["host"], 443, $errno, $errstr, 30);
if ($handle)
{
	fputs ($handle, $header.$request);
	while (!feof($handle))
	{
		$result = fgets ($handle, 1024);
	}
	if (substr(trim($result), 0, 8) == "VERIFIED")
	{		$item_number = stripslashes($_POST['item_number']);
		$item_name = stripslashes($_POST['item_name']);
		$payment_status = stripslashes($_POST['payment_status']);
		$transaction_type = stripslashes($_POST['txn_type']);
		$seller_paypal = stripslashes($_POST['business']);
		$payer_paypal = stripslashes($_POST['payer_email']);
		$gross_total = stripslashes($_POST['mc_gross']);
		$mc_currency = stripslashes($_POST['mc_currency']);
		$first_name = stripslashes($_POST['first_name']);
		$last_name = stripslashes($_POST['last_name']);
		
		if ($transaction_type == "web_accept" && $payment_status == "Completed")
		{
			$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pd_files WHERE id = '".intval($item_number)."'", ARRAY_A);
			if (intval($file_details["id"]) == 0) $payment_status = "Unrecognized";
			else
			{
				if (strtolower($seller_paypal) != strtolower($paiddownloads->paypal_id)) $payment_status = "Unrecognized";
				else
				{
					if (floatval($gross_total) < floatval($file_details["price"]) || $mc_currency != $file_details["currency"]) $payment_status = "Unrecognized";
				}
			}
		}
		$sql = "INSERT INTO ".$wpdb->prefix."pd_transactions (
			file_id, payer_name, payer_email, gross, currency, payment_status, transaction_type, details, created) VALUES (
			'".intval($item_number)."',
			'".mysql_real_escape_string($first_name).' '.mysql_real_escape_string($last_name)."',
			'".mysql_real_escape_string($payer_paypal)."',
			'".floatval($gross_total)."',
			'".$mc_currency."',
			'".$payment_status."',
			'".$transaction_type."',
			'".mysql_real_escape_string($request)."',
			'".time()."'
		)";
		$wpdb->query($sql);
		if ($transaction_type == "web_accept")
		{
			if ($payment_status == "Completed")
			{
				$download_link = $paiddownloads->generate_downloadlink($file_details["id"], $payer_paypal, "purchasing");
				$tags = array("{first_name}", "{last_name}", "{payer_email}", "{product_title}", "{product_price}", "{product_currency}", "{download_link}", "{download_link_lifetime}", "{transaction_date}");
				$vals = array($first_name, $last_name, $payer_paypal, $file_details["title"], $gross_total, $mc_currency, $download_link ,$paiddownloads->link_lifetime, date("Y-m-d H:i:s")." (server time)");

				$body = str_replace($tags, $vals, $paiddownloads->success_email_body);
				$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
				$mail_headers .= "From: ".$paiddownloads->from_name." <".$paiddownloads->from_email.">\r\n";
				$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
				wp_mail($payer_paypal, $paiddownloads->success_email_subject, $body, $mail_headers);

				$body = str_replace($tags, $vals, "Dear Administrator,\r\n\r\nWe would like to inform you that {first_name} {last_name} ({payer_email}) purchased {product_title} on {transaction_date}. The buyer received the following download link:\r\n{download_link}\r\nThis link is valid {download_link_lifetime} day(s) only.\r\n\r\nThanks,\r\nPaid Downloads Plugin");
				$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
				$mail_headers .= "From: ".$paiddownloads->from_name." <".$paiddownloads->from_email.">\r\n";
				$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
				wp_mail($paiddownloads->seller_email, "Completed payment received", $body, $mail_headers);
			}
			else if ($payment_status == "Failed" || $payment_status == "Pending" || $payment_status == "Processed" || $payment_status == "Unrecognized")
			{
				$tags = array("{first_name}", "{last_name}", "{payer_email}", "{product_title}", "{product_price}", "{product_currency}", "{payment_status}", "{transaction_date}");
				$vals = array($first_name, $last_name, $payer_paypal, $file_details["title"], $gross_total, $mc_currency, $payment_status, date("Y-m-d H:i:s")." (server time)");

				$body = str_replace($tags, $vals, $paiddownloads->failed_email_body);
				$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
				$mail_headers .= "From: ".$paiddownloads->from_name." <".$paiddownloads->from_email.">\r\n";
				$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
				wp_mail($payer_paypal, $paiddownloads->failed_email_subject, $body, $mail_headers);

				$body = str_replace($tags, $vals, "Dear Administrator,\r\n\r\nWe would like to inform you that {first_name} {last_name} ({payer_email}) paid for {product_title} on {transaction_date}. This is non-completed payment.\r\nPayment ststus: {payment_status}\r\n\r\nDownload link was not generated.\r\n\r\nThanks,\r\nPaid Downloads Plugin");
				$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
				$mail_headers .= "From: ".$paiddownloads->from_name." <".$paiddownloads->from_email.">\r\n";
				$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
				wp_mail($paiddownloads->seller_email, "Non-completed payment received", $body, $mail_headers);
			}
		}
	}
}
?>