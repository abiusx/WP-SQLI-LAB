<?php
include_once('../../../wp-load.php');

$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$crc = strtoupper($_REQUEST["SignatureValue"]);
$id = $_REQUEST["shpFileId"];
$email = $_REQUEST["shpEmail"];

$my_crc = strtoupper(md5($out_summ.':'.$inv_id.':'.$paiddownloads->robokassa_password2.':shpEmail='.$email.':shpFileId='.$id));
if ($my_crc != $crc) die("Invalid Signature Value!");

$id = intval($id);
$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
if (intval($file_details["id"]) == 0) unset($id);
if (empty($id)) die("Invalid Product ID");

$sql = "INSERT INTO ".$wpdb->prefix."pd_transactions (
	file_id, payer_name, payer_email, gross, currency, payment_status, transaction_type, details, created) VALUES (
	'".$id."',
	'Robokassa buyer',
	'".$email."',
	'".$file_details["price"]."',
	'".$file_details["currency"]."',
	'Completed',
	'robokassa',
	'Paid amount in robokassa currency: ".$out_summ."',
	'".time()."'
)";
$wpdb->query($sql);

$download_link = $paiddownloads->generate_downloadlink($file_details["id"], $email, "purchasing");
$tags = array("{first_name}", "{last_name}", "{payer_email}", "{product_title}", "{product_price}", "{product_currency}", "{download_link}", "{download_link_lifetime}", "{transaction_date}", "{robokassa_gross}");
$vals = array("Rubokassa buyer", "", $email, $file_details["title"], $file_details["price"], $file_details["currency"], $download_link ,$paiddownloads->link_lifetime, date("Y-m-d H:i:s")." (server time)", $out_summ);

$body = str_replace($tags, $vals, $paiddownloads->success_email_body);
$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
$mail_headers .= "From: ".$paiddownloads->from_name." <".$paiddownloads->from_email.">\r\n";
$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
wp_mail($email, $paiddownloads->success_email_subject, $body, $mail_headers);

$body = str_replace($tags, $vals, "Dear Administrator,\r\n\r\nWe would like to inform you that {first_name} {last_name} ({payer_email}) purchased {product_title} on {transaction_date}.\r\nPaiment method: Robokassa\r\nPaid amount in Robokassa currency: ".$out_summ."\r\nThe buyer received the following download link:\r\n{download_link}\r\nThis link is valid {download_link_lifetime} day(s) only.\r\n\r\nThanks,\r\nPaid Downloads Plugin");
$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
$mail_headers .= "From: ".$paiddownloads->from_name." <".$paiddownloads->from_email.">\r\n";
$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
wp_mail($paiddownloads->seller_email, "Completed payment received", $body, $mail_headers);

echo "OK".$inv_id."\r\n";
?>