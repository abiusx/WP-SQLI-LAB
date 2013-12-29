<?php
/**
*	BugsGoViral Plugin for Worpress
*	Admin Functions
*
*	@filesource 			admin.php
*	@description			Functions for integrating Plugin Admin with WordPress
* */
include("version.php");


/**
* Start the menu
*/

$fbm_panel=new fbm_admin_Panel();

/**
* Admin Panel / Menu Functions
*/
class fbm_admin_Panel	{

	var $fbm_icon_url= "/fbpromotions/admin/images/bugbw.gif";
	var $capability="administrator";
	var $main_menu_order=10;
	var $show_menu=1;

	function fbm_admin_Panel()	{
		// draw the menu links
		$this->fbm_icon_url= WP_PLUGIN_URL . $this->fbm_icon_url;
		global $fbmo;
		if (isset($fbmo->options["capability"]))
			$this->capability=$fbmo->options["capability"];
		if (isset($fbmo->options["menu_order"]))
			$this->main_menu_order=$fbmo->options["menu_order"];
		// Add the admin hooks
		add_action('admin_init', array (&$this,'admin_init') );
		add_action( 'admin_menu', array (&$this, 'add_menu') );
	}
	// integrate admin style sheets and javascript (working on javascript - cvhj)
	function admin_init()	{
		// Register the stylesheet.
		wp_register_style('fbmostyles', WP_PLUGIN_URL . '/fbpromotions/admin/css/admin_styles.css');
		// Enqueue Javascript
		wp_enqueue_script("jquery");
		wp_enqueue_script('bgv_admin_javascript', WP_PLUGIN_URL . '/fbpromotions/admin/js/bgv_admin_javascript.js');
	}
	// integrate the menu
	function add_menu()  {		$icon=$this->fbm_icon_url;
		add_action('admin_print_styles', array(&$this,'admin_styles') );
		// Main Dashboard Menu Item - Bugs Go Viral
		add_menu_page("Bugs Go Viral", "Bug's Dashboard",
		$this->capability, "fbpromotion", array( &$this, 'fbm_show_menu'), $icon,$this->main_menu_order);
		 //draw sub menu to mange account on Bugs Go viral server
		 add_submenu_page("fbpromotion", "Bugs Go Viral | Beta Notice","Beta Notice",
		$this->capability, "fbmo_beta", array($this, 'fbm_show_menu'));

	     add_submenu_page("fbpromotion", "Account Settings","Account Settings",
		$this->capability, "fbm_account_settings", array($this, 'fbm_show_menu'));
		// draw the sub menus - Settings, Add New, Edit/Activate, Analytics (working on this section - not finished)
		add_submenu_page("fbpromotion", "Bugs Go Viral | Plugin Settings", "Facebook App.", $this->capability,
			"fbm_options", array(&$this, 'fbm_show_menu'));

		add_submenu_page("fbpromotion", "Bugs Go Viral | Add New Promotion","Add Promo",
		$this->capability,"fbm_create_promo", array($this, 'fbm_show_menu'));

		add_submenu_page("fbpromotion", "Bugs Go Viral | Promotions","Edit/Analyze Promo",
		$this->capability, "fbm_edit_promo", array($this, 'fbm_show_menu'));
		
		add_submenu_page("fbpromotion", "Bugs Go Viral | The B(GV) Hive ","The Hive",
		$this->capability, "fbm_admin_b_hive", array($this, 'fbm_show_menu'));
		
	}
	function admin_styles()	{
		if (substr ($_REQUEST['page'],0,2)=="fb")
			wp_enqueue_style('fbmostyles');  
	}
	function fbm_show_menu()	{
		global $fbmo;
			if(($_REQUEST['page']=="fbm_create_promo" || $_REQUEST['page']=="fbm_edit_promo") && empty($fbmo->options["fb_application_secret"])){
				$_REQUEST['page'] = "fbm_options";
			}
			
		switch ($_REQUEST['page']){
			
			case "fbm_account_settings" :
				$this->fbm_account_settings();
				break;
			case "fbm_admin_b_hive" :
				$this->fbm_admin_b_hive();
				break;
			case "fbm_create_promo" :
			   $_REQUEST['fb_edit_action']="add";
				$this->fbm_edit_promo();
				break;
			case "fbm_edit_promo" :
				$this->fbm_edit_promo();
				break;
			case "fbm_show_media_entry_info" :
				include_once ( dirname (__FILE__) . '/view_media_entry_info.php' );
				break;
			case "fbm_analytics":
				$this->fbm_edit_promo();
				break;
			/*case "fbm_analytics_view":
				$res = $fbmo->get_promo_analytics_dashboard();
				$res_top_inf = $fbmo->get_top_influencers_dashboard();
				include_once ( dirname (__FILE__) . '/admin-analytics.php' );
				break;*/
			case "fbm_promo_detail":
				$this->fbm_promo_detail();
				break;
			case "fbm_options" :
				$this->fbm_options();
				break;
			case "fbm_promo_notification" :
				$this->fbm_notifications();
				break;
			case "fbm_cron" :
				// otherwise call promo settings script.
				include_once ( dirname (__FILE__) . '/notification-cron.php' );
				break;
			case "fbpromotion":
				$this->fbm_admin_dashboard();
				break;
			case "fbmo_beta":
			    $res = $fbmo->get_promo_analytics_dashboard();
				$res_top_inf = $fbmo->get_top_influencers_dashboard();
				include_once ( dirname (__FILE__) . '/beta-notice.php' );
				break;
			default :
				# $this->fbm_edit_promo();
				$this->fbm_admin_dashboard();
				break;
		}
	}
	//fn to show the a/c settings
	function fbm_account_settings(){
			global $fbmo;
			$serverurl =  get_option("serverurl");
			$key = trim(get_option("key"));
			$data = file_get_contents($serverurl."index.php?product_id=".$key."&fb_edit_action=credits_info");
			$arr = explode("?",$data);
		    $agency = $arr[4];
			include_once ( dirname (__FILE__) . '/account_setup.php' );
	}
    function fbm_show_media_entry_info(){
		global $fbmo;
		include_once ( dirname (__FILE__) . '/view_media_entry_info.php' );
	}
	function fbm_edit_promo()	{
		global $fbmo;
		if (isset($_REQUEST["promo_id"]))	{
			// get promo stuff
			$fbmo->get_promo_info($_REQUEST["promo_id"]);
		}
		if (!isset($_REQUEST["fb_edit_action"]) and !isset($_REQUEST["promo_id"]))   {
			// draw the promo list if available
			global $promo_list;
			$fbmo->get_promo_list();
			global $notification_list, $notifications_obj;
			   //code ends  for making functinality for enable/disable for notification module
			   #if ($promo_list)	include_once ( dirname (__FILE__) . '/promo-list.php' );
				include_once ( dirname (__FILE__) . '/promo-list.php' );
		} 
		else	{
			// otherwise call promo settings script.
			include_once ( dirname (__FILE__) . '/promo-settings.php' );
		}
	}
	// draw detail page
	function fbm_promo_detail()	{
		global $stats_obj,$fbmo,$promo_row;
		if (isset($_REQUEST['fb_edit_action']) and
			$_REQUEST['fb_edit_action']=="delete_media")	{
			$fbmo->delete_media_rec($_REQUEST["media_id"]);
		}
		// get promo stuff
		$fbmo->get_promo_info($_REQUEST["promo_id"]);
		$promo_row=$fbmo->promo_row;
		include_once ( FBPROMODIR . '/fbstats.php' );
		$stats_obj=new fbmo_stats($_REQUEST["promo_id"]);
		$stats_obj->get_totals();
		include_once ( dirname (__FILE__) . '/promo-details.php' );
	}
	function fbm_options()	{
		fbmo_admin_options();
	}
	function fbm_admin_dashboard() {
		global $fbmo;
			//get_credits();
		if(!get_option("key")){
				$remote =  get_option("siteurl");
				$serverurl =  get_option("serverurl");
				if(isset($serverurl)){
				$key = file_get_contents($serverurl."/index.php?fb_edit_action=install&remote=".$remote);
				add_option("key",$key,"","NO");
				}
			   include_once ( FBPROMODIR . '/admin/account_setup.php' );
				exit;
			}else{
				$res = $fbmo->get_promo_analytics_dashboard();
				$res_top_inf = $fbmo->get_top_influencers_dashboard();
				include_once ( dirname (__FILE__) . '/admin_dashboard.php' );
			}
		
	}

	function fbm_admin_b_hive() {
		global $fbmo;
		$data = $fbmo->getHiveResults();
		$hive_row_referals = $fbmo->getHiveResultsReferals();
		include_once ( dirname (__FILE__) . '/admin_b_hive.php' );
	}
	//handle notifications
	function fbm_notifications()	{
		//list your notifcations
		if (isset($_REQUEST['fb_notification']))	  {
			global $notification_list, $notifications_obj;
		   include_once ( FBPROMODIR . '/admin/notification-settings.php' );
	   	   $notifications_obj=new bgv_notifications($_REQUEST["promo_id"]);
            $notification_list = $notifications_obj->getNotifications();
			include_once ( dirname (__FILE__) . '/notification-list.php' );
			//make sure everything is cleaned up
			unset( $notification_list);
			unset( $notifications_obj);
			return;
		}
		//save/addedit notifications
		if (isset($_REQUEST['fb_notify_action']))	{
			include_once ( dirname (__FILE__) . '/notification-settings.php' );
			return;
		}
	}
}  // end of admin menu class

// include functions for writing html into view files. Determine if all admin views will have access if included here.
require_once ( dirname (__FILE__) . '/admin_html_functions.php' );
require_once ( dirname (__FILE__) . '/promo_list_html_functions.php' );

//  filter/hook functions
function fbm_show_fb_pages($wp_query) {
	global $fbmo,$wp_query,$wpdb;
	$wp_query->query_vars['post_type']=array("page");
	$wp_query->query_vars['post_parent']=$fbmo->facebook_pages["Main"];
}
function fbmo_admin_options()  {
	global $wpdb, $fbmo, $fboptions;
	$fboptions=get_option("fbmo_facebook_settings");
	$err_flag=0;
	$enable_not_module = 0;
	if ( isset($_POST['updateoption']) ) {
		check_admin_referer("fbm_settings");
		# required options /options not sent to fb
		$options=array("fb_application_key","fb_application_secret","fb_application_id","fb_canvas_url","fb_fanpage_url");
		foreach ($options as $option) {
			$option = trim($option);
			$value = trim($_POST[$option]);
			if (empty($value))	{
				$fbmo->admin_messages[$option]="Required Field!<br />";
				$err_flag=1;
			}
			$fbmo->options[$option] = $value;
		}
		# check any options here before saving
		if (!preg_match("/^http:\/\/apps.facebook.com\//",$fbmo->options["fb_canvas_url"])) {
			$fbmo->admin_messages["fb_fanpage_url"]="Invalid Canvas URL!<br />";
			//$err_flag=1;
		}
		if (!preg_match("/\/?/",$fbmo->options["fb_canvas_url"]))
			$fbmo_options["fb_canvas_url"].='/';
		if (preg_match("/\?/",$fbmo->options["fb_fanpage_url"]) or ! preg_match("/\d{6,}$/",$fbmo->options["fb_fanpage_url"]))  {
			$fbmo->admin_messages["fb_fanpage_url"]="Invalid Fan Page URL!<br />";
			$err_flag=1;
		}
		// facebook settings
		$fboptnames=array("application_name","description","contact_email","publish_action","publish_self_action","tab_default_name","message_action");
		foreach ($fboptnames as $option) {
			$option = trim($option);
			$value = trim($_POST[$option]);
			$fboptions[$option] = $value;
		}
		// Save options
		if (!$err_flag)		{
			update_option('fbmo_options', $fbmo->options);
			update_option('fbmo_facebook_settings', $fboptions);
			add_option('fbmo_facebook_opt_update',1);
			$fbmo->admin_messages["message"]="Facebook Options Updated";
		}	else $fbmo->admin_messages["message"]="Errors occurred";
	}
	if ( isset($_POST['updateoption']) && 	 (!$err_flag)	 ) {
	$res = $fbmo->get_promo_analytics_dashboard();
	$res_top_inf = $fbmo->get_top_influencers_dashboard();
	include_once ( dirname (__FILE__) . '/admin_dashboard.php' );
   }else
	include_once ( dirname (__FILE__) . '/plugin-settings.php' );
}
function runCron(){
	if($_REQUEST['page']=="fbpromotion"){
		global $wpdb,$promo_list,$fbmo,$notifications_obj;
		$fbmo->get_promo_list();
		foreach ($promo_list as $promo_row)
		{
			require_once( FBPROMODIR . '/bgvnotifications.php' );
			$notifications_obj=new bgv_notifications($promo_row->promo_id);
			$notification_list = $notifications_obj->getNotifications();
			foreach ($notification_list as $notification_row)	{
				$notifications_obj->my_scheduled_function($notification_row->notice_id);
			}
		}
	}
}
runCron();
?>
