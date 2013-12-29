<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
class fbmo_install {
var $install_error;
var $options;
	function fbmo_activate()	{
		# check for facebookapp directory
        $fbdir=dirname($_SERVER['SCRIPT_FILENAME'])."/facebookapp";
        if (!file_exists($fbdir))	{        	# didn't get installed right
        	$this->install_error("Facebook directory $fbdir does not exist!<br />
        		Please refer to plugin install instructions");
        	fbmo_admin_error();
         }
         $this->options["fbdir"]=$fbdir;
		#set the default options
		$this->add_options();
		#set the default facebook settings
		$this->add_facebook_settings();
		# create the promo databases and possibly a facebook session database
		$this->create_tables();
		# create a user id for main app and email it or post it to main site
		# to be finished
	}
	function fbmo_deactivate()	{
		# put this stuff in the uninstall instead
		# delete_option('fbmo_options');
		# delete_option('fbmo_facebook_settings');
		# delete_option('fbmo_facebook_opt_update');
	}
	function fbmo_uninstall()	{
		# remove the tables
		global $wpdb;
      	$wpdb->query(
        "DROP TABLE IF EXISTS
        	fb_users,
        	fb_promotions,
        	fb_entries,
        	fb_media,
        	fb_votes,
			bvg_notifications,
			bvg_notify_users,
			bgv_notification_settings,
			fb_paypal_transaction,
			fb_ratings,
        	fb_social");
		# delete the options
		# consider renaming db table to include more unique prefix "bgv4wp_"
		delete_option('fbmo_options');
		delete_option('fbmo_facebook_settings');
		delete_option('fbmo_facebook_opt_update');
	}
	function create_tables()	{
		# create the promo table
		global $wpdb;
		if (! $this->table_exists("fb_promotions"))	$this->create_fb_promo();
		else  $this->update_fb_promo();
		if (! $this->table_exists("fb_entries"))	$this->create_fb_entries();
		else  $this->update_fb_entries();
		if (! $this->table_exists("fb_media"))	$this->create_fb_media();
		else  $this->update_fb_media();
		if (! $this->table_exists("fb_votes"))	$this->create_fb_votes();
		else  $this->update_fb_media();
		if (! $this->table_exists("fb_social"))	$this->create_fb_social();
		else  $this->update_fb_social();
		if (! $this->table_exists("fb_users"))	$this->create_fb_users();
		else  $this->update_fb_users();
		if (! $this->table_exists("bgv_notices"))	$this->create_fb_notices();
		else  $this->update_fb_notices();
		if (! $this->table_exists("bgv_notify_users"))	$this->create_fb_notify_users();
		else  $this->update_fb_notify_users();
		if (! $this->table_exists("bgv_notify_settings"))	$this->create_fb_notify_settings();
		else  $this->update_fb_notify_settings();
		if (! $this->table_exists("fb_paypal_transaction"))	$this->create_fb_paypal();
		else  $this->update_fb_paypal();
		if (! $this->table_exists("fb_ratings"))	$this->create_fb_ratings();
		else  $this->update_fb_ratings();
	}
	function create_fb_ratings(){
		global $wpdb;
		$wpdb->query(	"
		   CREATE TABLE IF NOT EXISTS `fb_ratings` (
		  `media_id` bigint(20) NOT NULL,
		  `uid_voted` varchar(20) NOT NULL,
		  `date_voted` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  `promo_id` smallint(6) NOT NULL,
		  `total_votes` int(11) NOT NULL,
		  KEY `media_id` (`media_id`,`uid_voted`,`date_voted`,`promo_id`)
		 )");
	}
	function update_fb_paypal() { }
	function create_fb_paypal()	{
		global $wpdb;
		$wpdb->query(	
		"CREATE TABLE IF NOT EXISTS `fb_paypal_transaction` (
		  `id` int(11) NOT NULL auto_increment,
		  `payer_id` varchar(50) NOT NULL,
		  `txn_id` varchar(50) NOT NULL,
		  `payer_status` varchar(20) NOT NULL,
		  `payment_status` varchar(20) NOT NULL,
		  `payment_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
		  `quantity` int(11) NOT NULL,
		  `payment_gross` decimal(10,0) NOT NULL,
		  `item_number` int(11) NOT NULL,
		  `address_name` varchar(20) NOT NULL,
		  `address_street` varchar(20) NOT NULL,
		  `address_city` varchar(20) NOT NULL,
		  `address_zip` varchar(10) NOT NULL,
		  `address_state` varchar(20) NOT NULL,
		  `address_country` varchar(20) NOT NULL,
		  `mc_fee` decimal(10,0) NOT NULL,
		  `shipping` decimal(10,0) NOT NULL,
		  `payment_fee` decimal(10,0) NOT NULL,
		  `first_name` varchar(20) NOT NULL,
		  `last_name` varchar(20) NOT NULL,
		  `payer_email` varchar(50) NOT NULL,
		  `refunded` tinyint(1) NOT NULL,
		  PRIMARY KEY  (`id`)
			)"
		);
	}	
	
	
	function create_fb_promo()	{
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `fb_promotions` (
			`promo_id` smallint(6) NOT NULL auto_increment,
			`promo_page` varchar(20) NOT NULL,
			`promo_name` varchar(64) NOT NULL,
			`promo_description` text,
			`promo_rules` text,
			`promo_entry` text NOT NULL,
			`promo_start` varchar(255) default NULL,
			`promo_end` varchar(255) default NULL,
			`vote_start` varchar(255) default NULL,
			`vote_end` varchar(255) default NULL,
			 `how_to_video_url` text NOT NULL,
			`promo_tipping_deadline` date NOT NULL,
			`promo_type` varchar(3) default NULL,
			`promo_prodserv_offered` varchar(200) NOT NULL,
			`promo_deal_desc` varchar(200) NOT NULL,
			`promo_prodserv_sale_price` float NOT NULL,
            `promo_prodserv_reg_price` float NOT NULL,
			`promo_deals_to_tip` int(11) NOT NULL,
			`promo_prize` varchar(200) default NULL,
			`prize_value` smallint(6) default NULL,
			`promo_points` smallint(6) default NULL,
			`media_type` varchar(50) NOT NULL,
  			`response_type` varchar(50) NOT NULL,
			`promo_layout` varchar(20) default NULL,
			`text_color` varchar(20) default NULL,
			`link_text_color` varchar(20) default NULL,
			`system_message_bg_color` varchar(20) default NULL,
			`layout_logo` varchar(100) default NULL,
			`bkg_image` varchar(60) default NULL,
			`header_image` varchar(60) default NULL,
			`button_image` varchar(60) default NULL,
			`sidebar_image` varchar(60) default NULL,
			`footer_image` varchar(60) default NULL,
			`profile_image` varchar(60) default NULL,
			`publish_image` varchar(60) default NULL,
			`sponsor` varchar(100) default NULL,
			`sponsor_logo` varchar(100) default NULL,
			`sponsor_url` varchar(100) default NULL,
			`entry_text` text NOT NULL,
			`winner_text` text NOT NULL,
			`landing_order` tinyint(4) NOT NULL,
			`activation_key` varchar(15) NOT NULL,
			`img_profiletab_promobanner` varchar(60) default NULL,
			`img_profiletab_requirelike` varchar(60) NOT NULL,
			`img_canvas_enter_sweepstakes_520x560` varchar(60) NOT NULL,
			 `img_canvas_gift_swarm_520x560` varchar(255) NOT NULL,
			`img_canvas_browse_buzz_520x560` varchar(60) NOT NULL,
			`img_canvas_alreadyentered_sweepstakes_520x560` varchar(60) NOT NULL,
			`img_canvas_closed_sweepstakes_520x560` varchar(60) NOT NULL,
			`img_canvas_rulesregs_sweepstakes_520x560` varchar(60) NOT NULL,
			`img_canvas_blank_sweepstakes_520x560` varchar(60) NOT NULL,
			`img_canvas_dialogbg_sweepstakes_10x10` varchar(60) NOT NULL,
			`img_canvas_enter_swarm_520x560` varchar(60) NOT NULL,
			`img_canvas_promote_purchase_swarm_520x560` varchar(60) NOT NULL,
			`img_canvas_purchase_swarm_520x560` varchar(60) NOT NULL,
			`img_canvas_promote_swarm_520x560` varchar(60) NOT NULL,
			`img_canvas_end_swarm_520x560` varchar(60) NOT NULL,
			`img_streampublish_90x90` varchar(60) NOT NULL,
			`text_sponsor` varchar(100) default NULL,
			`img_sponsor_graphic` varchar(100) default NULL,
			`link_sponsor_one` varchar(100) default NULL,
			`link_anchortext_sponsor_one` varchar(100) NOT NULL,
			`link_sponsor_two` varchar(100) default NULL,
			`link_anchortext_sponsor_two` varchar(100) NOT NULL,
			`fbid_profile_to_like` varchar(30) NOT NULL,
			`link_universal_one` varchar(500) NOT NULL,
			`link_anchortext_universal_one` varchar(100) NOT NULL,
			`link_universal_two` varchar(500) NOT NULL,
			`link_anchortext_universal_two` varchar(100) NOT NULL,
			`link_universal_three` varchar(200) NOT NULL,
			`link_anchortext_universal_three` varchar(100) NOT NULL,
			`link_universal_four` varchar(200) NOT NULL,
			`link_anchortext_universal_four` varchar(100) NOT NULL,
			`link_universal_five` varchar(200) NOT NULL,
			`link_anchortext_universal_five` varchar(100) NOT NULL,
			`link_universal_six` varchar(200) NOT NULL,
			`link_anchortext_universal_six` varchar(100) NOT NULL,
			`link_universal_seven` varchar(200) NOT NULL,
			`link_anchortext_universal_seven` varchar(100) NOT NULL,
			`promo_beneficiary_logo` varchar(100) NOT NULL,
			`text_promo_beneficiary` varchar(100) NOT NULL,
			`promo_beneficiary_email` varchar(100) NOT NULL,
			`promo_beneficiary_phone` varchar(100) NOT NULL,
			`api_user_name` varchar(255) NOT NULL,
			`api_user_password` varchar(255) NOT NULL,
			`api_user_signature` varchar(255) NOT NULL,
			`paypal_merchant_account` varchar(255) NOT NULL,
			PRIMARY KEY  (`promo_id`),
			KEY `landing_order` (`landing_order`))"
			);
		}
		function update_fb_promo()		{
			# add entry text field
			global $wpdb;
			$wpdb->query(
			"ALTER TABLE `fb_promotions`
				ADD entry_text TEXT NOT NULL,
				ADD `img_profiletab_promobanner` varchar(60) DEFAULT NULL,
				ADD `img_profiletab_requirelike` varchar(60) NOT NULL,
				ADD `img_canvas_enter_sweepstakes_520x560` varchar(60) NOT NULL,
				ADD `img_canvas_alreadyentered_sweepstakes_520x560` varchar(60) NOT NULL,
				ADD `img_canvas_closed_sweepstakes_520x560` varchar(60) NOT NULL,
				ADD `img_canvas_rulesregs_sweepstakes_520x560` varchar(60) NOT NULL,
				ADD `img_canvas_blank_sweepstakes_520x560` varchar(60) NOT NULL,
				ADD `img_canvas_dialogbg_sweepstakes_10x10` varchar(60) NOT NULL,
				ADD `img_streampublish_90x90` varchar(60) NOT NULL,
				ADD `text_sponsor` varchar(100) DEFAULT NULL,
				ADD `img_sponsor_graphic` varchar(100) DEFAULT NULL,
				ADD `link_sponsor_one` varchar(100) DEFAULT NULL,
				ADD `link_anchortext_sponsor_one` varchar(100) NOT NULL,
				ADD `link_sponsor_two` varchar(100) DEFAULT NULL,
				ADD `link_anchortext_sponsor_two` varchar(100) NOT NULL,
				ADD `fbid_profile_to_like` int(30) NOT NULL,
				ADD `link_universal_one` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_one` varchar(100) NOT NULL,
				ADD `link_universal_two` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_two` varchar(100) NOT NULL,
				ADD `link_universal_three` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_three` varchar(100) NOT NULL,
				ADD `link_universal_four` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_four` varchar(100) NOT NULL,
				ADD `link_universal_five` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_five` varchar(100) NOT NULL,
				ADD `link_universal_six` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_six` varchar(100) NOT NULL,
				ADD `link_universal_seven` varchar(200) NOT NULL,
				ADD `link_anchortext_universal_seven` varchar(100) NOT NULL,
				ADD `promo_beneficiary_logo` varchar(100) NOT NULL,
				ADD `text_promo_beneficiary` varchar(100) NOT NULL,
				ADD `promo_beneficiary_email` varchar(100) NOT NULL,
				ADD `promo_beneficiary_phone` varchar(100) NOT NULL
				");
  		}
		function create_fb_entries()	{
			global $wpdb;
			$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `fb_entries` (  
			`entry_id` bigint(20) NOT NULL auto_increment,
			`uid` varchar(20) NOT NULL,
			`name` varchar(40) NOT NULL,
			`promo_id` smallint(6) NOT NULL default '0',
			`likes` int(11) NOT NULL,
			`invites` int(11) NOT NULL,
			`shares` int(11) NOT NULL,
			`referrals` int(11) NOT NULL,
			`points` int(11) NOT NULL,
			`votes` int(11) NOT NULL,
			`location` varchar(50) NOT NULL,
			`email` varchar(40) NOT NULL,
			`timezone` varchar(10) NOT NULL,
			`gender` varchar(10) NOT NULL,
			`hometown_location` varchar(60) NOT NULL,
			`birthdate` varchar(12) NOT NULL,
			`promo_units_purchased` int(21) NOT NULL,
			`entry_date` date default NULL,
			`authToken` varchar(50) NOT NULL,
			`friends_count` bigint(20) NOT NULL,
			`gift_card_friends_name` varchar(200) NOT NULL,
  			`gift_card_friends_email` text NOT NULL,
			`reedeemed_units` int(21) NOT NULL,
			`UPDATED_TS` timestamp NULL default CURRENT_TIMESTAMP 
			on update CURRENT_TIMESTAMP COMMENT 
			'the time this entry is updated or created',
			PRIMARY KEY  (`entry_id`),
			KEY `promo_id` (`promo_id`),
			KEY `votes` (`votes`))");
			}
	
		function update_fb_entries()	{
			# add entry column
			global $wpdb;
			$wpdb->query(
			"ALTER IGNORE TABLE `fb_entries`
				DROP PRIMARY KEY,
				ADD entry_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			    ADD `likes` BIGINT NOT NULL,
			    ADD `invites` BIGINT NOT NULL,
			    ADD `shares` BIGINT NOT NULL,
			    ADD `referrals` BIGINT NOT NULL,
			    ADD `points` BIGINT NOT NULL,
			    ADD `votes` BIGINT NOT NULL,
			    ADD `location` varchar(50) NOT NULL,
			    ADD `email` varchar(40) NOT NULL,
			    ADD `timezone` varchar(10) NOT NULL,
			    ADD  `gender` varchar(10) NOT NULL,
			    ADD `hometown_location` varchar(60) NOT NULL,
			    ADD `birthdate` varchar(12) NOT NULL
				");
		}
		function create_fb_users()	{
			global $wpdb;
        	$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `fb_users` (
			  `uid` varchar(20) NOT NULL,
			  `sessionkey` varchar(128) DEFAULT NULL,
			  `permissions` varchar(150) NOT NULL,
			  `active` char(1) DEFAULT '1',
			  `name` varchar(100) NOT NULL,
			  `email` varchar(100) NOT NULL,
			  PRIMARY KEY (`uid`),
			  KEY `active` (`active`)
			)");
		}
		function update_fb_users()	{ return; }
function update_fb_ratings(){
	return;
}
		function create_fb_media()	{
			
			global $wpdb;
	        $wpdb->query(
			"CREATE TABLE IF NOT EXISTS `fb_media` (
			  `media_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `entry_id` bigint(20) NOT NULL,
			  `promo_id` smallint(6) NOT NULL,
			  `uid` varchar(20) NOT NULL,
			  `uid_name` varchar(100) NOT NULL,
			  `user_birth_date` varchar(100) NOT NULL,
			  `user_email` text NOT NULL,
			  `media_url` text NOT NULL,
			  `media_title` varchar(60) NOT NULL,
			  `media_text` text NOT NULL,
			  `vote_counter` bigint(20) NOT NULL,
			  `rate_counter` bigint(20) NOT NULL,
			  `like_counter` bigint(20) NOT NULL,
			   `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`media_id`),
			  KEY `promo_id` (`promo_id`,`uid`)
			)");
		}
		function update_fb_media()	{ return; }

		function create_fb_votes()	{
			global $wpdb;
	        $wpdb->query(
			"CREATE TABLE IF NOT EXISTS `fb_votes` (
			  `media_id` bigint(20) NOT NULL,
			  `uid_voted` varchar(20) NOT NULL,
			  `comment` varchar(256) NOT NULL,
			  `date_voted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `promo_id` smallint(6) NOT NULL,
			  `voter_name` varchar(50) NOT NULL,
			  KEY `media_id` (`media_id`,`uid_voted`,`date_voted`,`promo_id`)
			)");
	}
	function update_fb_votes()	{ return; }
	
	function create_fb_social()	{
			global $wpdb;
	        $wpdb->query(
			"CREATE TABLE IF NOT EXISTS `fb_social` (
			  `uid` varchar(20) NOT NULL,
			  `referred_id` varchar(20) NOT NULL,
			  `promo_id` smallint(6) NOT NULL,
			  `type` varchar(10) NOT NULL,
			  `points` smallint(6) NOT NULL DEFAULT '0',
			  `ref_date` date DEFAULT NULL,
			  `stream_type` varchar(20) NOT NULL,
			  KEY `referrer_id` (`referred_id`),
			  KEY `uid` (`uid`),
			  KEY `promo_id` (`promo_id`),
			  KEY `stream_type` (`stream_type`)
			)");
    }
	function update_fb_social()	{ return; }
	
	function create_fb_notices()	{
			global $wpdb;
	        $wpdb->query(
			"CREATE TABLE IF NOT EXISTS `bgv_notification` (
			  `notice_id` bigint(20) NOT NULL auto_increment,
			  `promo_id` bigint(20) NOT NULL,
			  `channel` varchar(100) NOT NULL,
			  `milestone` varchar(100) NOT NULL,
			  `notification_time` int(11) NOT NULL default '0',
			  `promo_start` varchar(10) NOT NULL,
			  `time` varchar(255) NOT NULL,
			  `message` text NOT NULL,
			   `message_if_milestone_not_met` text NOT NULL,
			  `user_address1` text NOT NULL,
			  `user_address2` text NOT NULL,
			  `user_city` text NOT NULL,
			  `user_state` text NOT NULL,
			  `user_zip_code` text NOT NULL,
			  `user_phone` text NOT NULL,
			  `user_country` text NOT NULL,
			  `updated_ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
			  PRIMARY KEY  (`notice_id`));");
		}
		
	function update_fb_notices()	{ return; }
	
	function create_fb_notify_users()	{
			global $wpdb;
			$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `bgv_notify_users` (
			  `notify_user_id` bigint(20) NOT NULL auto_increment,
			  `notice_id` bigint(20) NOT NULL,
			  `user_email` varchar(50) NOT NULL,
			  `user_email2` varchar(50) NOT NULL,
			  `user_email3` varchar(50) NOT NULL,
			  `user_email4` varchar(50) NOT NULL,
			  PRIMARY KEY  (`notify_user_id`));" );  
		}
	function update_fb_notify_users()	{ return; }

	function create_fb_notify_settings()	{
			global $wpdb;

			$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `bgv_notification_mail` (
			  `notice_id` int(21) NOT NULL,
			  `number_times` int(21) NOT NULL,
			  `date_time` datetime NOT NULL,
			  `date` date NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=latin1;");  
		}
	function update_fb_notify_settings()	{ return; }


	function add_options()	{
		if (!get_option('fbmo_options'))	{
			$this->options["capability"]="administrator";
			$this->options["menu_order"]=10;
			$this->options["icon_url"]="/wp-content/plugins/fbpromotions/admin/bugbw.gif";
			$this->options["canvas_url"]=get_option("siteurl")."/facebookapp/"; // actual url
			add_option("fbmo_options",$this->options,"","NO");
		}
		if (!get_option('fbpromo_version'))	{
			$str = file_get_contents(get_option("siteurl").'/wp-content/plugins/fbpromotions/readme.txt');
			$arr = explode("Stable tag:",$str);
			$version_fbpromo = trim(substr($arr[1],0,8));
		     add_option("fbpromo_version",$version_fbpromo,"","NO");
		}
			//setting serverurl
			if (!get_option('serverurl'))	{
			
			 $serverurl="http://account.bugsgoviral.com/"; // actual url
			
		     add_option("serverurl",$serverurl,"","NO");
			 if(!get_option("key")){
				$remote =  get_option("siteurl");
				$serverurl =  get_option("serverurl");
				$key = file_get_contents($serverurl."/index.php?fb_edit_action=install&remote=".$remote);
				add_option("key",$key,"","NO");
				}
		}
	}
	function add_facebook_settings()	{
		# these are settings that need to reside on facebook and will be updated
		# from the facebook side
		# this first batch doesn't change
		$dir=$this->options["canvas_url"];
        $properties["post_authorize_callback_url"]=$dir."authorize-callback.php";
		$properties["privacy_url"]=$dir."privacy.php";
		$properties["profile_tab_url"]="profile-tab.php"; //needs to be relative to canvas
        $properties["remove_url"]=$dir."remove.php";
        $properties["use_iframe"]=1;
		#TODO: these we'll set for now, but they can be changed by user  TODO: clean this up 
		$properties["application_name"]=get_option("blogname");
		$properties["contact_email"]=get_option("admin_email");
		$properties["description"]=get_option("blogdescription");
        $properties["tab_default_name"]="16 Characters Maximum"; // get_option("blogname");
		$properties["fbapp_logo"]="Not Implemented"; // get_option("blogname");
		$properties["fbapp_icon"]="Not Implemented"; // get_option("blogname");
		$properties["fbapp_profile_image"]="Not Implemented"; // get_option("blogname");
		$properties["fbapp_dev_email"]=get_option("admin_email");
		add_option("fbmo_facebook_settings",$properties,"","NO");
		add_option("fbmo_facebook_opt_update","1","NO");
	}
	function install_error($error)	{
		if (get_option("fbmo_admin_error"))
			update_option("fbmo_admin_error",$error);
		else add_option("fbmo_admin_error",$error);
	}
	function table_exists($table)	{
    	# checks for table
    	global $wpdb;
		$exists=$wpdb->query("select * from $table where 1=0");
		if ($exists===0) return 1;
		else return 0;
 	}
}   // end of install class

?>