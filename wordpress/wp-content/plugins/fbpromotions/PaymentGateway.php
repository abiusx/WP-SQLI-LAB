<?php
/**
 * Payment Gateway
 *
 * This library provides generic payment gateway handling functionlity
 * to the other payment gateway classes in an uniform way. Please have
 * a look on them for the implementation details.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
*/
abstract class PaymentGateway
{
    /**
     * Holds the last error encountered
     *
     * @var string
     */
    public $lastError;

    /**
     * Do we need to log IPN results ?
     *
     * @var boolean
     */
    public $logIpn;

    /**
     * File to log IPN results
     *
     * @var string
     */
    public $ipnLogFile;

    /**
     * Payment gateway IPN response
     *
     * @var string
     */
    public $ipnResponse;

    /**
     * Are we in test mode ?
     *
     * @var boolean
     */
    public $testMode;

    /**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();
	
	 /**
     * Field array to generate refund request
     *
     * @var array
     */
    public $refundData = array();

    /**
     * IPN post values as array
     *
     * @var array
     */
    public $ipnData = array();

    /**
     * Payment gateway URL
     *
     * @var string
     */
    public $gatewayUrl;

    /**
     * Initialization constructor
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        // Some default values of the class
        $this->lastError = '';
        $this->logIpn = TRUE;
        $this->ipnResponse = '';
        $this->testMode = FALSE;
    }

	 /**
     * Adds a key=>value pair to the refund data array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    public function addRefundData($field, $value)
    {
        $this->refundData["$field"] = $value;
    }
	
	/**
     *  Clears the refund data from array
     *
     * @param
     * @return
     */
    public function clearRefundData()
    {
        $this->refundData = array();
    }
	
    /**
     * Adds a key=>value pair to the fields array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    public function addField($field, $value)
    {
        $this->fields["$field"] = $value;
    }

    /**
     * Submit Payment Request
     *
     * Generates a form with hidden elements from the fields array
     * and submits it to the payment gateway URL. The user is presented
     * a redirecting message along with a button to click.
     *
     * @param none
     * @return void
     */
    public function submitPayment()
    {

        $this->prepareSubmit();
        echo "<html>\n";
        echo "<head><title>Processing Payment...</title></head>\n";
        echo "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
        echo "<form method=\"POST\" name=\"gateway_form\" ";
        echo "action=\"" . $this->gatewayUrl . "\" >\n";
        foreach ($this->fields as $name => $value)
        {
             echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
        echo "</form>\n";
        echo "</body></html>\n";
		exit;
    }

    /**
     * Perform any pre-posting actions
     *
     * @param none
     * @return none
     */
    protected function prepareSubmit()
    {
        // Fill if needed
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    abstract protected function enableTestMode();

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    abstract protected function validateIpn();
	
    /**
     * Logs the IPN results
     *
     * @param boolean IPN result
     * @return void
     */
    public function logResults($success)
    {
		global $wpdb;
		$paypalTxnFields = array( "payer_email", "last_name", "first_name", "payment_fee", "shipping", "mc_fee", "address_country", "address_state", "address_zip", "address_city", "address_street", "address_name", "item_number", "payment_gross", "quantity", "payment_date", "payment_status", "payer_status", "txn_id", "payer_id" );
		
		$paypalTxnData = array(  "payer_email"=>"", "last_name"=>"", "first_name"=>"", "payment_fee"=>"", "shipping"=>"", "mc_fee"=>"", "address_country"=>"", "address_state"=>"", "address_zip"=>"", "address_city"=>"", "address_street"=>"", "address_name"=>"", "item_number"=>"", "payment_gross"=>"", "quantity"=>"", "payment_date"=>"", "payment_status"=>"", "payer_status"=>"", "txn_id"=>"", "payer_id"=>"" );
						
        if (!$this->logIpn) {
			return;
		
		}

        foreach ($this->ipnData as $key=>$value)
        {
		
			if( in_array( $key, $paypalTxnFields, true ) ){
					
				$paypalTxnData[$key]=$value;
			}
        }
		
	    $wpdb->insert( "fb_paypal_transaction",$paypalTxnData,
				array(
					'payer_email' => "%s",
					'last_name' => "%s",
					'first_name' => "%s",
					'payment_fee' => "%f",
					'shipping' => "%f",
					'mc_fee' => "%f",
					'address_country' => "%s",
					'address_state' => "%s",
					'address_zip' => "%s",
					'address_city' => "%s",
					'address_street' => "%s",
					'address_name' => "%s",
					'item_number' => "%d",
					'payment_gross' => "%f",
					'quantity' => "%d",
					'payment_date' => "%s",
					'payment_status' => "%s",
					'payer_status' => "%s",
					'txn_id' => "%s",
					'payer_id' => "%s"
				)
		);
    }
	
function enableLogIpn(){
		$this->logIpn = TRUE;
	}
}
