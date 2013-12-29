<?php
/**
 * Asynchronous handler for Facebook application Canvas Page.
 * This page handles AJAX requests following stream publishing
 * Loads the wp-blog-header.php which tells WordPress to load up

 *
 * @package WordPress
 */
global $facebook_page,$is_ajax;
define('WP_USE_THEMES', false); //Thanks, we won't need any
/** Loads the WordPress Environment */
$is_ajax=1;
if ( !isset($wp_did_header) ) {
	$wp_did_header = true;
	require_once('../wp-load.php' );
}
if(isset($_REQUEST['ref_id'])){
	$ref_id= $_REQUEST['ref_id'];
}else if($_REQUEST["uid"]){
		$ref_id= $_REQUEST["uid"];

}
$fbmo->record_fb_social($ref_id,$_REQUEST["promo_id"],$_REQUEST["type"]);

echo("record sucessful");
exit;
?>