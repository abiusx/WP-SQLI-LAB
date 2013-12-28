<?php 

require("../../../wp-config.php");

global $wpdb;

if( class_exists('MyWpDonate') ) $objDonate = new MyWpDonate();


# Send notifications to here
$send_mail_to = get_option( 'paypal_email_id' );


# Your primary PayPal e-mail address
$msg = get_option( 'paypal_email_msg' );

#the emails will be coming from


// Set the request paramaeter
$req = 'cmd=_notify-validate';

// Run through the posted array
foreach ($_REQUEST as $key => $value)
{
    // If magic quotes is enabled strip slashes
    if (get_magic_quotes_gpc())
    {
        $_REQUEST[$key] = stripslashes($value);
        $value = stripslashes($value);
    }
    $value = urlencode($value);
    // Add the value to the request parameter
    $req .= "&$key=$value";
}

//$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

$url = "https://www.paypal.com/cgi-bin/webscr";

$ch = curl_init();    // Starts the curl handler
curl_setopt($ch, CURLOPT_URL,$url); // Sets the paypal address for curl
curl_setopt($ch, CURLOPT_FAILONERROR, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // Returns result to a variable instead of echoing
curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Sets a time limit for curl in seconds (do not set too low)
curl_setopt($ch, CURLOPT_POST, 1); // Set curl to send data using post
curl_setopt($ch, CURLOPT_POSTFIELDS, $req); // Add the request parameters to the post
$result = curl_exec($ch); // run the curl process (and return the result to $result
curl_close($ch);

if (strcmp ($result, "VERIFIED") == 0) // It may seem strange but this function returns 0 if the result matches the string So you MUST check it is 0 and not just do strcmp ($result, "VERIFIED") (the if will fail as it will equate the result as false)
{


foreach ($_REQUEST as $key => $value)
{

$msg.=$key."=>".$value.'<br>';
}


	global $wpdb;

	$table_name= $wpdb->prefix.'donation';
	
	$data = array();
	
	$arrCustom=split("#",$_REQUEST["custom"]);
	
	$data["don_camp_id"]=$arrCustom[0];
	
	$data["don_amt"]=$_REQUEST["mc_gross"];
	
	if($_REQUEST["first_name"] != "" ) {
		$data["don_first_name"]= $wpdb->escape($_REQUEST["first_name"]);
	}
	
	
	if($_REQUEST["last_name"] != "" ) {
		$data["don_last_name"]= $wpdb->escape($_REQUEST["last_name"]) ;
	}

	
	
	if($_REQUEST["payer_email"] != "" ) {
		$data["don_email"]= $_REQUEST["payer_email"] ;
	}

	if($_REQUEST["address_name"] != "" ) {
		$data["don_address"]= $_REQUEST["address_name"] ;
	}



	if($_REQUEST["address_country"] != "" ) {
		$data["don_country"]= $_REQUEST["address_country"] ;
	}

	if($_REQUEST["address_city"] != "" ) {
		$data["don_city"]= $_REQUEST["address_city"] ;
	}

	if($_REQUEST["address_state"] != "" ) {
		$data["don_state"]= $_REQUEST["address_state"] ;
	}

	if($_REQUEST["address_zip"] != "" ) {
		$data["don_zip"]= $_REQUEST["address_zip"] ;
	}

	if($_REQUEST["night_phone_a"] != "" ) {
		$data["don_phone"]= $_REQUEST["night_phone_a"] ;
	}

	$data["txn_id"]= $_REQUEST["txn_id"] ;
	
	$data["don_date"]= date("Y-m-d");

	$rows_affected = $wpdb->insert( $table_name, $data );
	
	$msg = get_option( 'paypal_email_msg' );	
	
	$from=get_option( 'from_address' );
	
	$email_subject=get_option( 'email_subject' );
	
	mail( $send_mail_to , $email_subject, $msg, $from);


// mail to user

if($_REQUEST["payer_email"] != "" ) {

	$send_mail_to = $_REQUEST["payer_email"];
	
	$msg="Dear ".$_REQUEST["first_name"].",\n\n";
	
	$msg .= get_option( 'paypal_email_msg' );	
	
	$msg .="\n\n==== Donation Information ====";
	$msg .="\nConfirmation Number:".$_REQUEST["txn_id"] ;
	$msg .="\nDate: ".date("Y-m-d");
	$msg .="\nAmount: ".$_REQUEST["mc_gross"];
	$msg .="\nCampaign: ".$arrCustom[1];
	$msg .="\nDonor: ".$_REQUEST["first_name"]." ".$_REQUEST["last_name"];
	
	$from=get_option( 'from_address' );
	
	$email_subject=get_option( 'email_subject' );
	
	mail( $send_mail_to , $email_subject, $msg, $from);
	
	mail( get_option( 'paypal_email_id' ) , $email_subject, $msg, $from);
	
	
}

$eLog="/tmp/mailError.log";
//Get the size of the error log
//ensure it exists, create it if it doesn't
$fh= fopen($eLog, "a+");
fclose($fh);
$originalsize = filesize($eLog);

clearstatcache();
$finalsize = filesize($eLog);

}
else 
{

 	header("Location: index.php");
	die();
    // Log an invalid request to look into
}

 ?>