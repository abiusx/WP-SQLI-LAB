<?php
/**
 * Paypal Class
 *
 * Integrate the Paypal payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package		Payment Gateway
 * @category	Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */

include_once ('PaymentGateway.php');

class Paypal extends PaymentGateway
{

	private $API_UserName;
	private $API_Password;
	private $API_Signature;
	private $API_Version = "65.1";
	
	private $API_Endpoint;
	
    /**
	 * Initialize the Paypal gateway
	 *
	 * @param none
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();
		global $wpdb;
        // Some default values of the class
		$this->gatewayUrl = 'https://www.paypal.com/cgi-bin/webscr';
		$this->API_Endpoint = "https://api.paypal.com/nvp";
		
		$this->ipnLogFile = 'paypal.ipn_results.log';

		// Populate $fields array with a few default
		$this->addField('rm', '2');           // Return method = POST
		$this->addField('cmd', '_xclick');
			// Specify the currency
		$this->addField('currency_code', 'USD');
	
		$payment_list=$wpdb->get_results(
		"SELECT * from fb_promotions WHERE promo_id=  $promo_id" );
				
/*		$this->addField('business', 'dexter_1294436912_biz@gmail.com');
		//$this->addField('business', '' );
		
		$this->API_UserName = "dexter_1294436912_biz_api1.gmail.com";
		$this->API_Password = "1294436954";
		$this->API_Signature = "AFcWxV21C7fd0v3bYYYRCpSSRl31A.2t8eXDrTXKCjktNMgcM9UPXs2m";
*/		
		if((!empty($this->API_UserName)) && (!empty($this->API_Password)) && (!empty($this->API_Signature))) 
		{
			$this->addField('nvpHeaderStr', "&PWD=".urlencode($this->API_Password).
																"&USER=".urlencode($this->API_UserName).
																"&SIGNATURE=".urlencode($this->API_Signature).
																"&VERSION=". urlencode($this->API_Version) );
		}
	}
	public function initialisePPCredentials(  $business, $userName, $password, $signature)
    {
		$this->addField('business', $business);
		$this->API_UserName = $userName;
		$this->API_Password = $password;
		$this->API_Signature = $signature;
 		if((!empty($this->API_UserName)) && (!empty($this->API_Password)) && (!empty($this->API_Signature))) 
		{
			$this->addField('nvpHeaderStr', "&PWD=".urlencode($this->API_Password).
																"&USER=".urlencode($this->API_UserName).
																"&SIGNATURE=".urlencode($this->API_Signature).
																"&VERSION=". urlencode($this->API_Version) );
		}
	}
	public function generateRefund(  $id, $note )
    {
		$this->loadRefundData( $id, $totalRefund, $amount, $note );
		
		//$nvpStr = $this->refundData[' '];
		$nvpStr="&TRANSACTIONID=".  $id ."&REFUNDTYPE=FULL".
		"&CURRENCYCODE=USD" .
		"&NOTE=". $note;		
		
		$resArray=$this->hash_call("RefundTransaction",$nvpStr);
		if( strtoupper($resArray['ACK'] ) == "FAILURE" )
		{
			return false;
		}
		return true;
	}
	
	private function hash_call($methodName,$nvpStr)
	{
		// form header string
		$nvpheader= $this->fields['nvpHeaderStr'];
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);

		$nvpStr=$nvpheader.$nvpStr;
				
		$nvpreq="METHOD=".urlencode($methodName).$nvpStr;
		
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
		
		//getting response from server
		$response = curl_exec($ch);
		echo $response . "<br/><br/><br/><pre>";
		//convrting NVPResponse to an Associative Array
		$nvpResArray=$this->deformatNVP($response);

		print_r( $nvpResArray );
		 //closing the curl
		curl_close($ch);
		
		return $nvpResArray;
	}
	
	function deformatNVP($nvpstr)
	{
		$intial=0;
		$nvpArray = array();
		
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
		
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}

	function loadRefundData( $txnId, $isTotalRefund, $amount, $note )
	{				
		$this->addRefundData( "TRANSACTIONID", $txnId );
		if( $isTotalRefund )
		{
			$this->addRefundData( "REFUNDTYPE", "Full" );	
		}
		else
		{
			$this->addRefundData( "REFUNDTYPE", 'Partial' );
			$this->addRefundData( "AMT", $amount );
		}
		$this->addRefundData( "NOTE", $note );
//		$this->addRefundData( "", $pp_row[''] );
		$this->addRefundData( "CURRENCYCODE", "USD" );
	}
    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enableTestMode()
    {
        $this->testMode = TRUE;
        $this->gatewayUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
    }

    /**
	 * Validate the IPN notification
	 *
	 * @param none
	 * @return boolean
	 */
	public function validateIpn()
	{
		
		global $wpdb;
		
		/*$wpdb->query( $wpdb->prepare( "
				insert into testTable 
				set debugLog = \"test2\""));*/
					
		// parse the paypal URL
		$urlParsed = parse_url($this->gatewayUrl);
					
		// generate the post string from the _POST vars
		$postString = '';

        $paymentSuccessful = false;
		foreach ($_POST as $field=>$value)
		{
			/*$wpdb->query( $wpdb->prepare( "
					insert into testTable 
					set debugLog = \"$i". $field . "-" . $value ."\""));*/
			if( strtoupper( $field ) == strtoupper( "payment_status" ) && strtoupper( $value ) ==  strtoupper( "Completed" ) )
			{
				$paymentSuccessful = true;
			}
			$this->ipnData["$field"] = $value;
		}
		
		if( ! $paymentSuccessful )
		{
			return $paymentSuccessful;
		}
		$this->logResults( true);
		return $paymentSuccessful;
	}
	
	
	
}
	
	global $myPaypal;

	// Create an instance of the paypal library
	$myPaypal = new Paypal();
	
	
?>
