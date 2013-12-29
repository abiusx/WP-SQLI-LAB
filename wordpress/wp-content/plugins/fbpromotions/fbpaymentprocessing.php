<?php


// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

class fbmo_payment_processing 
{
	function doPaypalExpressCheckout(){
		
	}
} 



## start it here
global $fbmo;
$fbmo = new fbmo_plugin();
$fbmo->fbmo_load();
?>