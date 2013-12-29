<?php
error_reporting(0);
global $facebook_page,$is_application;
if(!isset($_SESSION)) 
{
	header('P3P: CP="CAO PSA OUR"'); 
	session_start();
}	
define('WP_USE_THEMES', false); //Thanks, we'll load our own.
/** Loads the WordPress Environment */
$is_application=1;
if ( !isset($wp_did_header) ) {
	$wp_did_header = true;
	
	require_once('../wp-load.php' );
}



$action=$_REQUEST["action"];
if(isset($_REQUEST['rate'])){
	$fbmo->save_rate();
	
}
$fbmo->options["fb_canvas_url"] = get_option('siteurl')."/facebookapp/";

if($action == "save_purchase_swarm"){
	$fbmo->swarmPromotion_PurchaseInit( $_REQUEST['promo_id'] );
	return;
}
/*	if($_SERVER['REMOTE_ADDR']=="75.64.162.18"){
		print_r($fbmo->session);
		echo "InProfile=".$in_profile."<br>";
		echo "Redirect=".$_REQUEST["fb_redirect"]."<br>";
		echo "IPN=".$_REQUEST["ipn"]."<br>";
		echo "url=".$fbmo->loginUrl."<br>";
	//	exit;
	}*/
# redirect to login to get permissions if not already authorized.
if (!$fbmo->session and 
	!isset($in_profile) and 
	!isset($_REQUEST["fb_redirect"]) and
	!isset($_REQUEST["ipn"]) )	{

	 echo "<script type='text/javascript'>top.location.href = '$fbmo->loginUrl';</script>";
     exit;
}
# check fb user to see if already entered in the promo
$GLOBALS["entry_id"]=$fbmo_is_entered["entry_id"];
if($_REQUEST['ref_type']=="share"){
		$_SESSION['allow_app']=1;
		$_SESSION['browse_id']=$_REQUEST['browse_id'];
		$fan_url =$fbmo->options['fb_fanpage_url'];
        //$fan_url = $fan_array[0]."//".$fan_array[2]."/".$fan_array[4];
	    $redirect_url  =  $fan_url."?sk=app_".$fbmo->options['fb_application_id'];
	    echo "<script type='text/javascript'>top.location.href = '$redirect_url';
       </script>";
		exit;
	}
else if(((!empty($_REQUEST['browse_id']) && !isset($_GET['facebook_page']))) && $in_profile!=1){
	    if($GLOBALS["entry_id"])
		$_SESSION['allow_app']=1;
		$_SESSION['browse_id']=$_REQUEST['browse_id'];
		$fan_url = $fbmo->options['fb_fanpage_url'];
        //$fan_url = $fan_array[0]."//".$fan_array[2]."/".$fan_array[4];
	    $redirect_url  =  $fan_url."?sk=app_".$fbmo->options['fb_application_id'];
	    echo "<script type='text/javascript'>top.location.href = '$redirect_url';
       </script>";
		exit;
}

# set image upload directory
$promo_image_uploads=get_option('siteurl')."/facebookapp/uploads/layout/";
if(isset($_REQUEST['friend_email'])){
			$fbmo->send_email_friend( );		
}
if(isset($_REQUEST['terms']) ){
	$fbmo->save_gift_card_details( );	
		
}
if (!isset($in_profile) and 	!isset($_REQUEST["fb_redirect"]))	{
	$is_fan=$fbmo->check_entrant_fan_status();
}
if (isset($_REQUEST["promo_id"]))	$get_promo_id=$_REQUEST["promo_id"];
if (isset($_REQUEST["promo"]))	$get_promo_id=$_REQUEST["promo"];
if (!isset($get_promo_id) and $facebook_page!="profile")	
{
	# check for single promo
	global $promo_list;
	$fbmo->get_landing_list();
	if( !empty($promo_list) )	{
		foreach( $promo_list as $promoRow ){
			$get_promo_id = $promoRow->promo_id;
			break;
		}
	}
}
$signed_request = $fbmo->parsePageSignedRequest();
if($signed_request) {
	if($signed_request->page->liked) {
	  $signed_request = true;
	} else {
	  $signed_request = false;
	}
}
if(!isset($get_promo_id) && $signed_request ==true)	{
		# get a list and display because they haven't set a specific promo id
		$fbmo->get_landing_list();
		if($promo_list)	{
			foreach($promo_list as $promo_row1)	{ 
				$get_promo_id = $promo_row1->promo_id;
			}
		}
		
}

if (isset($get_promo_id))	
{
	$fbmo->get_promo_info($get_promo_id);
	$fbmo->record_fb_referral($get_promo_id);
	$fbmo_is_entered=$fbmo->check_promo_entry($get_promo_id);
    if( $fbmo_is_entered || ($fbmo->promo_row["promo_type"]==1 && isset($fbmo_is_entered)) || $_REQUEST['page']=="Main"  || $_SESSION['allow_app']==1){
		   $GLOBALS["entry_id"]=$fbmo_is_entered["entry_id"];
		    $fb_layout=dirname (__FILE__)."/layouts/".
			fbmo_promo_type($fbmo->promo_row["promo_type"])."/".
			$fbmo->promo_row["promo_layout"]."_Main.php";
			//unset($_SESSION['allow_app']);
	}else{
			$fb_layout=dirname (__FILE__)."/layouts/profile.php";
	}
	#get the right layout and template and function
	# set stylesheet
	 $promo_stylesheet=get_option('siteurl')."/facebookapp/layouts/".
		fbmo_promo_type($fbmo->promo_row["promo_type"])."/"."styles.css";
	# check fb user to see if already entered in the promo
	if (!isset($in_profile) and 	!isset($_REQUEST["fb_redirect"]))	
		$is_fan=$fbmo->check_entrant_fan_status();
	    $promo_row=$fbmo->promo_row;
	if($_REQUEST['installed']==1 && !isset($_REQUEST['action'])){
		$_REQUEST['action']="save_entry";
	}
	if (isset($_REQUEST["action"]))	{
		$action=$_REQUEST["action"];
		switch ($action)	{
			case "save_entry":
			$fbmo->save_promo_entry($GLOBALS["get_promo_id"]);
			break;
			case "save_usermedia":
			$fbmo->save_usermedia($GLOBALS["get_promo_id"]);
			break;
			case "save_vote":
				$fbmo->save_vote($GLOBALS["get_promo_id"],$_REQUEST["media_id"]);
				$just_voted=1;
				break;
			case "vote":
				$fb_layout=dirname (__FILE__)."/layouts/".
				fbmo_promo_type($fbmo->promo_row["promo_type"])."/".
				$fbmo->promo_row["promo_layout"]."_Vote.php";
				break;
			case "validateuser":
				$code = $_REQUEST["code"];
				$error = $_REQUEST[ "error" ];
				$fbmo->getUserAuthorizationDetails( $get_promo_id, $code, $error );
				break;
			case "save_purchase_swarm":
				$fbmo->swarmPromotion_PurchaseInit( $get_promo_id );
				break;
			case "save_promote_swarm":
				$fbmo->swarmSendPromoteMessage( $get_promo_id, $fbmo->fbuser );
				break;
			case "complete_purchase_swarm":
				$fbmo->swarmPromotion_PurchaseComplete( );		
				break;
        }
	}
}	else {
	$fb_layout=dirname (__FILE__)."/layouts/profile.php";
}
if($_REQUEST['installed']==1 || $_SERVER['PHP_SELF']=="/facebookapp/index.php"){
  $fan_url = $fbmo->options['fb_fanpage_url'];
  
   $_SESSION['allow_app']=1;
   $redirect_url  =  $fan_url."?sk=app_".$fbmo->options['fb_application_id'];
   echo "<script type='text/javascript'>top.location.href = '$redirect_url';</script>";
}

############ call the layout page ##############
include_once($fb_layout);
################################################
#############################################################
#    template functions
#############################################################

function fbm_show_like_button($url="")	{
	global $fbmo,$promo_row;
	if (empty($url))	$url=urlencode($fbmo->options['fb_canvas_url']);
	elseif($url=="fanpage") $url=urlencode($fbmo->options['fb_fanpage_url']);
	if (isset($GLOBALS["get_promo_id"]))	$url.=$promo_row["promo_page"].".php";
	echo ('<iframe src="http://www.facebook.com/plugins/like.php?href='.
		$url.'%2F&amp;layout=standard&amp;show_faces=false&amp;width=500&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=60"
		scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:60px;" allowTransparency="true"></iframe>');
}

function fbm_show_like_button_entry($url="")	{
	global $fbmo,$promo_row;
	if (empty($url))	$url=urlencode($fbmo->options['fb_canvas_url']);
	elseif($url=="fanpage") $url=urlencode($fbmo->options['fb_fanpage_url']);
	if (isset($GLOBALS["get_promo_id"]))	$url.=$promo_row["promo_page"].".php";
	echo ('<iframe src="http://www.facebook.com/plugins/like.php?href='.
		$url.'%2F&amp;layout=standard&amp;show_faces=false&amp;width=150&amp;action=like&amp;font=verdana&amp;
		colorscheme=light&amp;height=60"
		scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:60px;" allowTransparency="true"></iframe>');
}
function fbm_show_like_button_entry_buzz($url="")	{
	global $fbmo,$promo_row;
	$url = urlencode($url);
	echo ('<iframe src="http://www.facebook.com/plugins/like.php?href='.
		$url.'&amp;layout=standard&amp;show_faces=false&amp;width=350&amp;action=like&amp;font=verdana&amp;
		colorscheme=light&amp;height=25"
		scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:25px;" allowTransparency="true">
		</iframe>');
}


function show_stream_image()	{
	global $fbmo,$promo_row;
	echo (get_option('siteurl')."/facebookapp/uploads/layout/".
			$fbmo->promo_row["img_streampublish_90x90"]);
}
function show_entry_image()	{
	global $fbmo,$promo_row;
	/*$media_item=$fbmo->get_promo_media_selected($promo_row["promo_id"],$_REQUEST['browse_id']);
	if($promo_row["media_type"]=="PHOTO UPLOAD"){
			$media =   $media_item["media_url"];
			$media =   (' <img src="'.
						get_option('siteurl'). '/facebookapp/uploads/contest_images/'.
						$media.
						'" border=0  width="150">');
	}
	 elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){
		$media = $media_item["media_url"];
		$media =$fbmo->get_youtube_iframe($media);
	 }
	 elseif($promo_row["media_type"]=="TEXT ENTRY")
		$media = $media_item["media_text"];
	echo ($media);*/
	echo (get_option('siteurl')."/facebookapp/uploads/layout/".
			$fbmo->promo_row["img_streampublish_90x90"]);
}

function show_promo_url()	{
	global $fbmo,$promo_row,$entry_id;
	$ref="?ref_type=invite";
	if (isset($entry_id)) $ref.='&ref_id='.$entry_id;
	$url=$fbmo->options['fb_canvas_url'].
	$url.=$promo_row["promo_page"].".php$ref";
	return $url;
}
function show_entry_function($type="")	{
	global $fbmo,$promo_row,$entry_id,$media_id;
	$name=$fbmo->fbme['name'];
	$prize=$promo_row["promo_prize"];
	$page_name=$promo_row["promo_name"];
	$app_name=$fbmo->fboptions["application_name"];
	if (strstr("14",$promo_row["promo_type"]))	{
		$message=" has just entered to win $prize from $page_name\n ";
	if (!isset($GLOBALS["stream_caption"]))	$caption="$app_name is giving away a $prize.";
		else	$caption=$GLOBALS["stream_caption"];
	}
	if ($type=="vote")	{
		$vurl=$fbmo->options['fb_canvas_url'];
		$vurl.=$promo_row["promo_page"].".php?action=vote&media_id=$media_id";
	}
	$url=$fbmo->options['fb_canvas_url'];
	$url.=$promo_row["promo_page"].".php";
	$url.="?action=browse_entries&browse_id=".$media_item_selected["media_id"];
	$linktext="Visit our Fanpage";
	$src=get_option('siteurl')."/facebookapp/uploads/layout/".
			$promo_row["img_streampublish_90x90"];
	$jstr="'".addslashes($promo_row["promo_name"])."','$caption','".
			addslashes($promo_row['promo_name'])."','$url','".
			addslashes($promo_row['promo_name'])."'";
	echo($jstr);
}
function show_share_function()	{
	global $fbmo,$promo_row,$entry_id;
	$url=$fbmo->options['fb_canvas_url'].
	$url.=$promo_row["promo_page"].".php?ref_type=share";
	if (isset($entry_id)) $url.="&ref_id=$entry_id";
	if (isset($GLOBALS["stream_caption"]))	$caption=$GLOBALS["stream_caption"];
	else $caption="Checkout this great promotion! You can win ".$promo_row["promo_prize"];
	$jstr="'".addslashes($promo_row["promo_name"])."','$caption','".
			addslashes($promo_row['promo_name'])."','$url','".
			addslashes($promo_row['promo_name'])."'";
	echo($jstr);
}
function show_share_new_function()	{
	global $fbmo,$promo_row,$entry_id;
	$url=$fbmo->options['fb_canvas_url'].
	$url.=$promo_row["promo_page"].".php?ref_type=share";
	if (isset($_REQUEST['browse_id'])) 
	$url.="&browse_id=".$_REQUEST['browse_id']."";
	$media_item_winner=$fbmo->get_promo_media_selected($promo_row["promo_id"],$_REQUEST['browse_id']);
	if (isset($fbmo->session['uid'])) $url.="&ref_id=".$fbmo->session['uid'];
	if (isset($GLOBALS["stream_caption"]))	$caption=$GLOBALS["stream_caption"];
	else $caption="Checkout this media entry! You can win ".$promo_row["promo_prize"];
	$jstr="'".addslashes($media_item_winner["media_title"])."','$caption','".
			addslashes($media_item_winner["media_title"])."','$url','".
			addslashes($media_item_winner["media_title"])."'";
	echo($jstr);
}

function display_info($fld)	{
	global $promo_row;	echo($promo_row($fld));
}
include_once( ABSPATH . '/facebookapp/includes/js_init.php');

?>