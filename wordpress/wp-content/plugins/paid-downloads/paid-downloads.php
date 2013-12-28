<?php
/*
Plugin Name: Paid Downloads
Plugin URI: http://www.icprojects.net/paid-downloads-plugin.html
Description: The plugin easily allows you to sell any digital content. The only actions you have do are uploading files and insert shortcode like <em>[paid-downloads id="XXX"]</em> into your posts or pages.
Version: 2.01
Author: Ivan Churakov
Author URI: http://www.freelancer.com/affiliates/ichurakov/
*/

wp_enqueue_script("jquery");
register_activation_hook(__FILE__, array("paiddownloads_class", "install"));

class paiddownloads_class
{
	var $options;
	var $error;
	var $info;
	
	var $exists;
	var $enable_paypal;
	var $paypal_id;
	var $seller_email;
	var $from_name;
	var $from_email;
	var	$success_email_subject;
	var $success_email_body;
	var $failed_email_subject;
	var $failed_email_body;
	var $buynow_type;
	var $buynow_image;
	var $link_lifetime;
	var $enable_robokassa;
	var $robokassa_merchant;
	var $robokassa_password1;
	var $robokassa_password2;
	var $robokassa_sandbox;
	
	var $currency_list = array("USD", "AUD", "EUR", "GBP", "NZD", "RUR");
	var $robokassa_currency_list = array("USD", "EUR", "RUR");
	var $buynow_buttons_list = array("html", "paypal", "custom");
	var $default_options;
	
	function __construct() {
		$this->options = array(
		"exists",
		"enable_paypal",
		"paypal_id",
		"seller_email",
		"from_name",
		"from_email",
		"success_email_subject",
		"success_email_body",
		"failed_email_subject",
		"failed_email_body",
		"buynow_type",
		"buynow_image",
		"link_lifetime",
		"enable_robokassa",
		"robokassa_merchant",
		"robokassa_password1",
		"robokassa_password2",
		"robokassa_sandbox"
		);
		$this->default_options = array (
			"exists" => 1,
			"enable_paypal" => "on",
			"paypal_id" => "sales@".str_replace("www.", "", $_SERVER["SERVER_NAME"]),
			"seller_email" => "alerts@".str_replace("www.", "", $_SERVER["SERVER_NAME"]),
			"from_name" => get_bloginfo("name"),
			"from_email" => "noreply@".str_replace("www.", "", $_SERVER["SERVER_NAME"]),
			"success_email_subject" => "Product download details",
			"success_email_body" => "Dear {first_name},\r\n\r\nThank you for purchasing of {product_title}. Click link below to download the product:\r\n{download_link}\r\nPlease remeber this link is valid {download_link_lifetime} day(s) only.\r\n\r\nThanks,\r\nAdministration of ".get_bloginfo("name"),
			"failed_email_subject" => "Payment was not completed",
			"failed_email_body" => "Dear {first_name},\r\n\r\nWe would like to inform you that we received payment for {product_title}.\r\nPayment status: {payment_status}\r\nOnce the payment is completed and cleared, we send download details to you by e-mail.\r\n\r\nThanks,\r\nAdministration of ".get_bloginfo("name"),
			"buynow_type" => "html",
			"buynow_image" => "",
			"link_lifetime" => "2",
			"enable_robokassa" => "off",
			"robokassa_merchant" => "",
			"robokassa_password1" => "",
			"robokassa_password2" => "",
			"robokassa_sandbox" => "off"
		);

		if (!empty($_COOKIE["paiddownloads_error"]))
		{
			$this->error = stripslashes($_COOKIE["paiddownloads_error"]);
			setcookie("paiddownloads_error", "", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
		}
		if (!empty($_COOKIE["paiddownloads_info"]))
		{
			$this->info = stripslashes($_COOKIE["paiddownloads_info"]);
			setcookie("paiddownloads_info", "", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
		}

		$this->get_settings();
		if (is_admin())
		{
			if ($this->check_settings() !== true) add_action('admin_notices', array(&$this, 'admin_warning'));
			if (!file_exists(ABSPATH.'wp-content/uploads/paid-downloads')) add_action('admin_notices', array(&$this, 'admin_warning_reactivate'));
			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('init', array(&$this, 'admin_request_handler'));
			add_action('admin_head', array(&$this, 'admin_header'), 15);
		}
		else
		{
			add_action("wp_head", array(&$this, "front_header"));
			add_shortcode('paid-downloads', array(&$this, "shortcode_handler"));
		}
	}

	function install ()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "pd_files";
		if($wpdb->get_var("show tables like '".$table_name."'") != $table_name)
		{
			$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL auto_increment,
				title varchar(255) collate utf8_unicode_ci NOT NULL,
				filename varchar(255) collate utf8_unicode_ci NOT NULL,
				filename_original varchar(255) collate utf8_unicode_ci NOT NULL,
				price float NOT NULL,
				currency varchar(7) collate utf8_unicode_ci NOT NULL,
				registered int(11) NOT NULL,
				UNIQUE KEY  id (id)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		$table_name = $wpdb->prefix . "pd_downloadlinks";
		if($wpdb->get_var("show tables like '".$table_name."'") != $table_name)
		{
			$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL auto_increment,
				file_id int(11) NOT NULL,
				download_key varchar(255) collate utf8_unicode_ci NOT NULL,
				owner varchar(63) collate utf8_unicode_ci NOT NULL,
				source varchar(15) collate utf8_unicode_ci NOT NULL,
				created int(11) NOT NULL,
				UNIQUE KEY  id (id)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		$table_name = $wpdb->prefix . "pd_transactions";
		if($wpdb->get_var("show tables like '".$table_name."'") != $table_name)
		{
			$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL auto_increment,
				file_id int(11) NOT NULL,
				payer_name varchar(255) collate utf8_unicode_ci NOT NULL,
				payer_email varchar(255) collate utf8_unicode_ci NOT NULL,
				gross float NOT NULL,
				currency varchar(15) collate utf8_unicode_ci NOT NULL,
				payment_status varchar(31) collate utf8_unicode_ci NOT NULL,
				transaction_type varchar(31) collate utf8_unicode_ci NOT NULL,
				details text collate utf8_unicode_ci NOT NULL,
				created int(11) NOT NULL,
				UNIQUE KEY  id (id)
			);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		if (!file_exists(ABSPATH.'wp-content/uploads/paid-downloads'))
		{
			wp_mkdir_p(ABSPATH.'wp-content/uploads/paid-downloads');
		}
		if (file_exists(ABSPATH.'wp-content/uploads/paid-downloads') && is_dir(ABSPATH.'wp-content/uploads/paid-downloads'))
		{
			if (!file_exists(ABSPATH.'wp-content/uploads/paid-downloads/index.html'))
			{
				file_put_contents(ABSPATH.'wp-content/uploads/paid-downloads/index.html', 'Silence is the gold!');
			}
			if (file_exists(dirname(__FILE__).'/uploads') && is_dir(dirname(__FILE__).'/uploads'))
			{
				$dircontent = scandir(dirname(__FILE__).'/uploads');
				for ($i=0; $i<sizeof($dircontent); $i++)
				{
					if ($dircontent[$i] != "." && $dircontent[$i] != "..")
					{
						if (is_file(dirname(__FILE__).'/uploads/'.$dircontent[$i]))
						{
							if (strtolower($dircontent[$i]) == "index.html") unlink(dirname(__FILE__).'/uploads/'.$dircontent[$i]);
							else rename(dirname(__FILE__).'/uploads/'.$dircontent[$i], ABSPATH.'wp-content/uploads/paid-downloads/'.$dircontent[$i]);
						}
					}
				}
				rmdir(dirname(__FILE__).'/uploads');
			}
		}
	}

	function get_settings() {
		$exists = get_option('paiddownloads_exists');
		if ($exists != 1)
		{
			foreach ($this->options as $option) {
				$this->$option = $this->default_options[$option];
			}
		}
		else
		{
			foreach ($this->options as $option) {
				$this->$option = get_option('paiddownloads_'.$option);
			}
		}
		if (empty($this->enable_paypal)) $this->enable_paypal = $this->default_options["enable_paypal"];
		if (empty($this->enable_robokassa)) $this->enable_robokassa = $this->default_options["enable_robokassa"];
		if (empty($this->robokassa_sandbox)) $this->robokassa_sandbox = $this->default_options["robokassa_sandbox"];
	}

	function update_settings() {
		if (current_user_can('manage_options')) {
			foreach ($this->options as $option) {
				update_option('paiddownloads_'.$option, $this->$option);
			}
		}
	}

	function populate_settings() {
		foreach ($this->options as $option) {
			if (isset($_POST['paiddownloads_'.$option])) {
				$this->$option = stripslashes($_POST['paiddownloads_'.$option]);
			}
		}
	}

	function check_settings() {
		$errors = array();
		if ($this->enable_paypal == "on")
		{
			if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $this->paypal_id) || strlen($this->paypal_id) == 0) $errors[] = "PayPal ID must be valid e-mail address";
		}
		if ($this->enable_robokassa == "on")
		{
			if (strlen($this->robokassa_merchant) < 2) $errors[] = "Login of Merchant must contains at least 2 characters";
		}
		if ($this->enable_paypal != "on" && $this->enable_robokassa != "on") $errors[] = "You must select at least one payment method";
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $this->seller_email) || strlen($this->seller_email) == 0) $errors[] = "E-mail for notifications must be valid e-mail address";
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $this->from_email) || strlen($this->from_email) == 0) $errors[] = "Sender e-mail must be valid e-mail address";
		if (strlen($this->from_name) < 3) $errors[] = "Sender name is too short";
		if (strlen($this->success_email_subject) < 3) $errors[] = "Successful purchasing e-mail subject must contain at least 3 characters";
		else if (strlen($this->success_email_subject) > 64) $errors[] = "Successful purchasing e-mail subject must contain maximum 64 characters";
		if (strlen($this->success_email_body) < 3) $errors[] = "Successful purchasing e-mail body must contain at least 3 characters";
		if (strlen($this->failed_email_subject) < 3) $errors[] = "Failed purchasing e-mail subject must contain at least 3 characters";
		else if (strlen($this->failed_email_subject) > 64) $errors[] = "Failed purchasing e-mail subject must contain maximum 64 characters";
		if (strlen($this->failed_email_body) < 3) $errors[] = "Failed purchasing e-mail body must contain at least 3 characters";
		if (intval($this->link_lifetime) != $this->link_lifetime || intval($this->link_lifetime) < 1 || intval($this->link_lifetime) > 365) $errors[] = "Download link lifetime must be valid integer value in range [1...365]";
		if (empty($errors)) return true;
		return $errors;
	}

	function admin_menu() {
		if (get_bloginfo('version') >= 3.0) {
			define("PAID_DOWNLOADS_PERMISSION", "add_users");
		}
		else{
			define("PAID_DOWNLOADS_PERMISSION", "edit_themes");
		}	
		add_menu_page(
			"Paid Downloads"
			, "Paid Downloads"
			, PAID_DOWNLOADS_PERMISSION
			, "paid-downloads"
			, array(&$this, 'admin_settings')
		);
		add_submenu_page(
			"paid-downloads"
			, "Settings"
			, "Settings"
			, PAID_DOWNLOADS_PERMISSION
			, "paid-downloads"
			, array(&$this, 'admin_settings')
		);
		add_submenu_page(
			"paid-downloads"
			, "Files"
			, "Files"
			, PAID_DOWNLOADS_PERMISSION
			, "paid-downloads-files"
			, array(&$this, 'admin_files')
		);
	}

	function admin_settings() {
		global $wpdb;
		$message = "";
		$errors = array();
		if (!empty($this->error)) $message = "<div class='error'><p><strong>ERROR</strong>: ".$this->error."</p></div>";
		else
		{
			$errors = $this->check_settings();
			if (is_array($errors)) echo "<div class='error'><p>The following error(s) exists:<br />- ".implode("<br />- ", $errors)."</p></div>";
		}
		if ($_GET["updated"] == "true")
		{
			$message = '<div class="updated"><p>Plugin settings successfully <strong>updated</strong>.</p></div>';
		}
		if (!in_array($this->buynow_type, $this->buynow_buttons_list)) $this->buynow_type = $this->buynow_buttons_list[0];
		if ($this->buynow_type == "custom")
		{
			if (empty($this->buynow_image)) $this->buynow_type = $this->buynow_buttons_list[0];
		}
		print ('
		<div class="wrap admin_paiddownloads_wrap">
			<div id="icon-options-general" class="icon32"><br /></div><h2>Paid Downloads - Settings</h2><br /> 
			'.$message.'
			<form enctype="multipart/form-data" method="post" style="margin: 0px" action="'.get_bloginfo('wpurl').'/wp-admin/admin.php">
			
			<div class="postbox-container" style="width: 100%;">
				<div class="metabox-holder">
					<div class="meta-box-sortables ui-sortable">
						<div class="postbox">
							<!--<div class="handlediv" title="Click to toggle"><br></div>-->
							<h3 class="hndle" style="cursor: default;"><span>General Settings</span></h3>
							<div class="inside">
								<table class="paiddownloads_useroptions">
									<tr>
										<th>E-mail for notifications:</th>
										<td><input type="text" id="paiddownloads_seller_email" name="paiddownloads_seller_email" value="'.htmlspecialchars($this->seller_email, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter e-mail address. All alerts about completed/failed payments are sent to this e-mail address.</em></td>
									</tr>
									<tr>
										<th>Sender name:</th>
										<td><input type="text" id="paiddownloads_from_name" name="paiddownloads_from_name" value="'.htmlspecialchars($this->from_name, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter sender name. All messages to buyers are sent using this name as "FROM:" header value.</em></td>
									</tr>
									<tr>
										<th>Sender e-mail:</th>
										<td><input type="text" id="paiddownloads_from_email" name="paiddownloads_from_email" value="'.htmlspecialchars($this->from_email, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter sender e-mail. All messages to buyers are sent using this e-mail as "FROM:" header value.</em></td>
									</tr>
									<tr>
										<th>Successful purchasing e-mail subject:</th>
										<td><input type="text" id="paiddownloads_success_email_subject" name="paiddownloads_success_email_subject" value="'.htmlspecialchars($this->success_email_subject, ENT_QUOTES).'" style="width: 95%;"><br /><em>In case of successful and cleared payment, your customers receive e-mail message about successful purchasing. This is subject field of the message.</em></td>
									</tr>
									<tr>
										<th>Successful purchasing e-mail body:</th>
										<td><textarea id="paiddownloads_success_email_body" name="paiddownloads_success_email_body" style="width: 95%; height: 120px;">'.htmlspecialchars($this->success_email_body, ENT_QUOTES).'</textarea><br /><em>This e-mail message is sent to your customers in case of successful and cleared payment. You can use the following keywords: {first_name}, {last_name}, {payer_email}, {product_title}, {product_price}, {product_currency}, {download_link}, {download_link_lifetime}.</em></td>
									</tr>
									<tr>
										<th>Failed purchasing e-mail subject:</th>
										<td><input type="text" id="paiddownloads_failed_email_subject" name="paiddownloads_failed_email_subject" value="'.htmlspecialchars($this->failed_email_subject, ENT_QUOTES).'" style="width: 95%;"><br /><em>In case of pending, non-cleared or fake payment, your customers receive e-mail message about that. This is subject field of the message.</em></td>
									</tr>
									<tr>
										<th>Failed purchasing e-mail body:</th>
										<td><textarea id="paiddownloads_failed_email_body" name="paiddownloads_failed_email_body" style="width: 95%; height: 120px;">'.htmlspecialchars($this->failed_email_body, ENT_QUOTES).'</textarea><br /><em>This e-mail message is sent to your customers in case of pending, non-cleared or fake payment. You can use the following keywords: {first_name}, {last_name}, {payer_email}, {product_title}, {product_price}, {product_currency}, {payment_status}.</em></td>
									</tr>
									<tr>
										<th>Download link lifetime:</th>
										<td><input type="text" id="paiddownloads_link_lifetime" name="paiddownloads_link_lifetime" value="'.htmlspecialchars($this->link_lifetime, ENT_QUOTES).'" style="width: 60px; text-align: right;"> days<br /><em>Please enter period of download link validity.</em></td>
									</tr>
									<tr>
										<th>"Buy Now" button:</th>
										<td>
											<table style="border: 0px; padding: 0px;">
											<tr><td style="padding-top: 8px; width: 20px;"><input type="radio" name="paiddownloads_buynow_type" value="html"'.($this->buynow_type == "html" ? ' checked="checked"' : '').'></td><td>Standard HTML-button<br /><button onclick="return false;">Buy Now</button></td></tr>
											<tr><td style="padding-top: 8px;"><input type="radio" name="paiddownloads_buynow_type" value="paypal"'.($this->buynow_type == "paypal" ? ' checked="checked"' : '').'></td><td>Standard PayPal button<br /><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/btn_buynow_LG.gif" border="0"></td></tr>
											<tr><td style="padding-top: 8px;"><input type="radio" name="paiddownloads_buynow_type" value="custom"'.($this->buynow_type == "custom" ? ' checked="checked"' : '').'></td><td>Custom "Buy Now" button'.(!empty($this->buynow_image) ? '<br /><img src="'.get_bloginfo("wpurl").'/wp-content/uploads/paid-downloads/'.rawurlencode($this->buynow_image).'" border="0">' : '').'<br /><input type="file" id="paiddownloads_buynow_image" name="paiddownloads_buynow_image" style="width: 95%;"><br /><em>Max dimensions: 300px x 300px, allowed images: JPG, GIF, PNG.</em></td></tr>
											</table>
										</td>
									</tr>
								</table>
								<div class="alignright">
								<input type="hidden" name="ak_action" value="paiddownloads_update_settings" />
								<input type="hidden" name="paiddownloads_exists" value="1" />
								<input type="submit" class="button-primary" name="submit" value="Update Settings »">
								</div>
								<br class="clear">
							</div>
						</div>
						<div class="postbox">
							<!--<div class="handlediv" title="Click to toggle"><br></div>-->
							<h3 class="hndle" style="cursor: default;"><span>PayPal Settings</span></h3>
							<div class="inside">
								<table class="paiddownloads_useroptions">
									<tr>
										<th>Enable:</th>
										<td><input type="checkbox" id="paiddownloads_enable_paypal" name="paiddownloads_enable_paypal" '.($this->enable_paypal == "on" ? 'checked="checked"' : '').'"> Accept payments via PayPal<br /><em>Please tick checkbox if you would like to accept payments via PayPal.</em></td>
									</tr>
									<tr>
										<th>PayPal ID:</th>
										<td><input type="text" id="paiddownloads_paypal_id" name="paiddownloads_paypal_id" value="'.htmlspecialchars($this->paypal_id, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter valid PayPal e-mail, all payments are sent to this account.</em></td>
									</tr>
								</table>
								<div class="alignright">
									<input type="submit" class="button-primary" name="submit" value="Update Settings »">
								</div>
								<br class="clear">
							</div>
						</div>
						<div class="postbox">
							<!--<div class="handlediv" title="Click to toggle"><br></div>-->
							<h3 class="hndle" style="cursor: default;"><span>Robokassa Settings</span></h3>
							<div class="inside">
								<table class="paiddownloads_useroptions">
									<tr>
										<td colspan="2">
											<strong>Important!</strong> Robokassa payment method is only available for the following currencies: '.implode(', ', $this->robokassa_currency_list).'.<br />You must have Merchant account within Robokassa service. At <a href="https://www.roboxchange.com/Environment/Partners/Login/Merchant/Administration.aspx" target="_blank">Merchant Administration page</a> you must set the following parameters:<br />Result URL: <strong>'.get_bloginfo('wpurl').'/wp-content/plugins/paid-downloads/robokassa_ipn.php</strong><br />Query method to Result URL: <strong>GET</strong> or <strong>POST</strong>
										</td>
									</tr>
									<tr>
										<th>Enable:</th>
										<td><input type="checkbox" id="paiddownloads_enable_robokassa" name="paiddownloads_enable_robokassa" '.($this->enable_robokassa == "on" ? 'checked="checked"' : '').'"> Accept payments via Robokassa<br /><em>Please tick checkbox if you would like to accept payments via Robokassa.</em></td>
									</tr>
									<tr>
										<th>Login of Merchant:</th>
										<td><input type="text" id="paiddownloads_robokassa_merchant" name="paiddownloads_robokassa_merchant" value="'.htmlspecialchars($this->robokassa_merchant, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter login of Merchant. This parameter is set during the registration of Merchant with Robokassa service.</em></td>
									</tr>
									<tr>
										<th>Password #1:</th>
										<td><input type="password" id="paiddownloads_robokassa_password1" name="paiddownloads_robokassa_password1" value="'.htmlspecialchars($this->robokassa_password1, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter password #1 of Merchant. This parameter is used for initiation of customer redirection to the Robokassa service. It is set at <a href="https://www.roboxchange.com/Environment/Partners/Login/Merchant/Administration.aspx" target="_blank">Merchant Administration page</a>.</em></td>
									</tr>
									<tr>
										<th>Password #2:</th>
										<td><input type="password" id="paiddownloads_robokassa_password2" name="paiddownloads_robokassa_password2" value="'.htmlspecialchars($this->robokassa_password2, ENT_QUOTES).'" style="width: 95%;"><br /><em>Please enter password #2 of Merchant. This parameter is used for used in notification procedures. It is set at <a href="https://www.roboxchange.com/Environment/Partners/Login/Merchant/Administration.aspx" target="_blank">Merchant Administration page</a>.</em></td>
									</tr>
									<tr>
										<th>Testing mode:</th>
										<td><input type="checkbox" id="paiddownloads_robokassa_sandbox" name="paiddownloads_robokassa_sandbox" '.($this->robokassa_sandbox == "on" ? 'checked="checked"' : '').'> Switch Robokassa service to testing mode<br /><em>Please tick checkbox if you would like to test Robokassa service. Testing mode is available for non-active Robokassa accounts only.</em></td>
									</tr>
								</table>
								<div class="alignright">
									<input type="submit" class="button-primary" name="submit" value="Update Settings »">
								</div>
								<br class="clear">
							</div>
						</div>
					</div>
				</div>
			</div>
			</form>
		</div>
		');
	}

	function admin_files() {
		global $wpdb;
		$sql = "SELECT * FROM ".$wpdb->prefix."pd_files ORDER BY title ASC";
		$rows = $wpdb->get_results($sql, ARRAY_A);
		if (!empty($this->error)) $message = "<div class='error'><p><strong>ERROR</strong>: ".$this->error."</p></div>";
		if (!empty($this->info)) $message = "<div class='updated'><p>".$this->info."</p></div>";
		print ('
			<div class="wrap admin_paiddownloads_wrap">
				<div id="icon-upload" class="icon32"><br /></div><h2>Paid Downloads - Files</h2><br />
				'.$message.'
				<div class="paiddownloads_buttons"><a class="button nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/add.php?redirect='.rawurlencode($_SERVER['REQUEST_URI']).'">Upload New File</a></div>
				<table class="paiddownloads_files">
				<tr>
					<th>File</th>
					<th style="width: 190px;">Short Code</th>
					<th style="width: 90px;">Price</th>
					<th style="width: 60px;">Sales</th>
					<th style="width: 130px;">Operations</th>
				</tr>
		');
		if (sizeof($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$sql = "SELECT COUNT(id) AS sales FROM ".$wpdb->prefix."pd_transactions WHERE file_id = '".$row["id"]."' AND payment_status = 'Completed' AND (transaction_type = 'web_accept' OR transaction_type = 'robokassa')";
				$sales = $wpdb->get_row($sql, ARRAY_A);
				print ('
				<tr>
					<td><strong>'.$row['title'].'</strong><br /><em style="font-size: 12px; line-height: 14px;">'.htmlspecialchars($row['filename_original'], ENT_QUOTES).'</em></td>
					<td>[paid-downloads id="'.$row['id'].'"]</td>
					<td style="text-align: right;">'.number_format($row['price'],2).' '.$row['currency'].'</td>
					<td style="text-align: right;">'.intval($sales["sales"]).'</td>
					<td style="text-align: center;">
						<a class="nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/add.php?id='.$row['id'].($_user_id > 0 ? '&user_id='.$_user_id : '').'&redirect='.rawurlencode($_SERVER['REQUEST_URI']).'" title="Edit file details"><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/edit.png" alt="Edit file details" border="0"></a>
						<a class="nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/getlink.php?id='.$row['id'].'" title="Generate temporary download link"><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/downloadlink.png" alt="Generate temporary download link" border="0"></a>
						<a class="nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/linkhistory.php?id='.$row['id'].'" title="Issued download links"><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/linkhistory.png" alt="Issued download links" border="0"></a>
						<a class="nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/transactions.php?id='.$row['id'].'" title="Payment transactions"><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/transactions.png" alt="Payment transactions" border="0"></a>
						<a href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/download.php?id='.$row['id'].'" title="Download file"><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/download01.png" alt="Download file" border="0"></a>
						<a href="'.get_bloginfo("wpurl").'/wp-admin/admin.php?ak_action=paiddownloads_delete&id='.$row['id'].'" title="Delete file" onclick="return submitOperation();"><img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/delete.png" alt="Delete file" border="0"></a>
					</td>
				</tr>
				');
			}
		}
		else
		{
			print ('
				<tr><td colspan="5" style="padding: 20px; text-align: center;">List is empty. Click <a class="nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/add.php?redirect='.rawurlencode($_SERVER['REQUEST_URI']).'">here</a> to upload new file.</td></tr>
			');
		}
		print ('
				</table>
				<div class="paiddownloads_buttons"><a class="button nyroModal_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/add.php?redirect='.rawurlencode($_SERVER['REQUEST_URI']).'">Upload New File</a></div>
			</div>
		');
	}

	function admin_request_handler() {
		global $wpdb;
		if (!empty($_POST['ak_action'])) {
			switch($_POST['ak_action']) {
				case 'paiddownloads_update_settings':
					$this->populate_settings();
					if (isset($_POST["paiddownloads_enable_paypal"])) $this->enable_paypal = "on";
					else $this->enable_paypal = "off";
					if (isset($_POST["paiddownloads_enable_robokassa"])) $this->enable_robokassa = "on";
					else $this->enable_robokassa = "off";
					if (isset($_POST["paiddownloads_robokassa_sandbox"])) $this->robokassa_sandbox = "on";
					else $this->robokassa_sandbox = "off";
					$buynow_image = "";
					$errors_info = "";
					if (is_uploaded_file($_FILES["paiddownloads_buynow_image"]["tmp_name"]))
					{
						$ext = strtolower(substr($_FILES["paiddownloads_buynow_image"]["name"], strlen($_FILES["paiddownloads_buynow_image"]["name"])-4));
						if ($ext != ".jpg" && $ext != ".gif" && $ext != ".png") $errors[] = 'Custom "Buy Now" button has invalid image type';
						else
						{
							list($width, $height, $type, $attr) = getimagesize($_FILES["paiddownloads_buynow_image"]["tmp_name"]);
							if ($width > 300 || $height > 300) $errors[] = 'Custom "Buy Now" button has invalid image dimensions';
							else
							{
								$buynow_image = "button_".md5(microtime().$_FILES["paiddownloads_buynow_image"]["tmp_name"]).$ext;
								if (!move_uploaded_file($_FILES["paiddownloads_buynow_image"]["tmp_name"], ABSPATH."wp-content/uploads/paid-downloads/".$buynow_image))
								{
									$errors[] = "Can't save uploaded image";
									$buynow_image = "";
								}
								else
								{
									if (!empty($this->buynow_image))
									{
										if (file_exists(ABSPATH."wp-content/uploads/paid-downloads/".$this->buynow_image) && is_file(ABSPATH."wp-content/uploads/paid-downloads/".$this->buynow_image))
											unlink(ABSPATH."wp-content/uploads/paid-downloads/".$this->buynow_image);
									}
								}
							}
						}
					}
					if (!empty($buynow_image)) $this->buynow_image = $buynow_image;
					if ($this->buynow_type == "custom" && empty($this->buynow_image))
					{
						$this->buynow_type = "html";
						$errors_info = 'Due to "Buy Now" image problem "Buy Now" button was set to Standard HTML button.';
					}
					$errors = $this->check_settings();
					if (empty($errors_info) && $errors === true)
					{
						$this->update_settings();
						header('Location: '.get_bloginfo('wpurl').'/wp-admin/admin.php?page=paid-downloads&updated=true');
						die();
					}
					else
					{
						$this->update_settings();
						$message = "";
						if (is_array($errors)) $message = "The following error(s) occured:<br />- ".implode("<br />- ", $errors);
						if (!empty($errors_info)) $message .= (empty($message) ? "" : "<br />").$errors_info;
						setcookie("paiddownloads_error", $message, time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.get_bloginfo('wpurl').'/wp-admin/admin.php?page=paid-downloads');
						die();
					}
					break;
			}
		}
		if (!empty($_GET['ak_action'])) {
			switch($_GET['ak_action']) {
				case 'paiddownloads_delete':
					$id = intval($_GET["id"]);
					$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
					if (intval($file_details["id"]) == 0)
					{
						setcookie("paiddownloads_error", "Invalid service call", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.get_bloginfo('wpurl').'/wp-admin/admin.php?page=paid-downloads-files');
						die();
					}
					$sql = "DELETE FROM ".$wpdb->prefix."pd_files WHERE id = '".$id."'";
					if ($wpdb->query($sql) !== false)
					{
						if (file_exists(ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"]) && is_file(ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"]))
							unlink(ABSPATH."wp-content/uploads/paid-downloads/".$file_details["filename"]);
						setcookie("paiddownloads_info", "File successfully removed", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.get_bloginfo('wpurl').'/wp-admin/admin.php?page=paid-downloads-files');
						die();
					}
					else
					{
						setcookie("paiddownloads_error", "Invalid service call", time()+30, "/", ".".str_replace("www.", "", $_SERVER["SERVER_NAME"]));
						header('Location: '.get_bloginfo('wpurl').'/wp-admin/admin.php?page=paid-downloads-files');
						die();
					}
					break;
			}
		}
	}

	function admin_warning() {
		echo '
		<div class="updated"><p><strong>Paid Downloads plugin almost ready.</strong> You must do some <a href="admin.php?page=paid-downloads">settings</a> for it to work.</p></div>
		';
	}

	function admin_warning_reactivate() {
		echo '
		<div class="error"><p><strong>Please deactivate Paid Downloads plugin and activate it again.</strong> If you already done that and see this message, please create the folder "/wp-content/uploads/paid-downloads" manually and set permission 0777 for this folder.</p></div>
		';
	}

	function admin_header()
	{
		global $wpdb;
		echo '
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
		<link rel="stylesheet" type="text/css" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/css/style.css?ver=2.01" media="screen" />
		<link rel="stylesheet" type="text/css" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/css/nyroModal.css?ver=2.01" media="screen" />
		<script type="text/javascript" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/js/jquery.nyroModal.custom.min.js?ver=2.01"></script>
		<!--[if IE 6]>
		<script type="text/javascript" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/js/jquery.nyroModal-ie6.min.js?ver=2.01"></script>
		<![endif]-->
		<style type="text/css">
			.nyroModalCloseButton {z-index: 2002;}
			.nyroModalCont {z-index: 2001;}
			.nyroModalBg {z-index: 2000;}
		</style> 
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery(".nyroModal_link").nyroModal({
				});
			});
			function submitOperation()
			{
				var answer = confirm("Do you really want to continue?")
				if (answer) return true;
				else return false;
			}
		</script>';
	}

	function front_header()
	{
		echo '
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
		<link rel="stylesheet" type="text/css" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/css/style.css?ver=2.01" media="screen" />
		<link rel="stylesheet" type="text/css" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/css/nyroModal.css?ver=2.01" media="screen" />
		<script type="text/javascript" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/js/jquery.nyroModal.custom.min.js?ver=2.01"></script>
		<!--[if IE 6]>
		<script type="text/javascript" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/js/jquery.nyroModal-ie6.min.js?ver=2.01"></script>
		<![endif]-->
		<style type="text/css">
			.nyroModalCloseButton {z-index: 2002;}
			.nyroModalCont {z-index: 2001;}
			.nyroModalBg {z-index: 2000;}
		</style> 
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery(".nyroModal_link").nyroModal();
			});
		</script>';
	}
	
	function shortcode_handler($_atts)
	{
		global $post, $wpdb, $current_user;
		if ($this->check_settings() === true)
		{
			$id = intval($_atts["id"]);
			$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "pd_files WHERE id = '".$id."'", ARRAY_A);
			if (intval($file_details["id"]) == 0) return "";
			if ($file_details["price"] == 0) return '<a href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/download.php?id='.$file_details["id"].'">Download '.htmlspecialchars($file_details["title"]).'</a>';
			if ($this->enable_paypal == "on" && ($this->enable_robokassa != "on" || !in_array($file_details["currency"], $this->robokassa_currency_list)))
			{
				$button = '
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="padding: 0px; margin: 0px;">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="'.$this->paypal_id.'">
					<input type="hidden" name="no_shipping" value="1">
					<input type="hidden" name="lc" value="US">
					<input type="hidden" name="rm" value="2">
					<input type="hidden" name="item_name" value="'.htmlspecialchars($file_details["title"], ENT_QUOTES).'">
					<input type="hidden" name="item_number" value="'.$file_details["id"].'">
					<input type="hidden" name="amount" value="'.$file_details["price"].'">
					<input type="hidden" name="currency_code" value="'.$file_details["currency"].'">
					<input type="hidden" name="custom" value="">
					<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">
					<input type="hidden" name="return" value="http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'">
					<input type="hidden" name="cancel_return" value="http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'">
					<input type="hidden" name="notify_url" value="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/paypal_ipn.php">
					';
				if ($this->buynow_type == "custom") $button .= '<input type="image" src="'.get_bloginfo("wpurl").'/wp-content/uploads/paid-downloads/'.rawurlencode($this->buynow_image).'" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
				else if ($this->buynow_type == "paypal") $button .= '<input type="image" src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
				else $button .= '<input type="submit" value="Buy Now">';
				$button .= '
				</form>';
			}
			else if ($this->enable_robokassa == "on")
			{
				$button = '<a class="nyroModal_link paiddownloads_button_link" href="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/payment_selector.php?id='.$id.'&redirect='.rawurlencode($_SERVER['REQUEST_URI']).'">';
				if ($this->buynow_type == "custom") $button .= '<img src="'.get_bloginfo("wpurl").'/wp-content/uploads/paid-downloads/'.rawurlencode($this->buynow_image).'" border="0" name="submit" alt="">';
				else if ($this->buynow_type == "paypal") $button .= '<img src="'.get_bloginfo("wpurl").'/wp-content/plugins/paid-downloads/images/btn_buynow_LG.gif" border="0" alt="">';
				else $button .= '<input type="submit" value="Buy Now">';
				$button .= '
				</a>';
			}
			else $button = "";
			return $button;
		}
		return "";
	}	

	function generate_downloadlink($_fileid, $_owner, $_source)
	{
		global $wpdb;
		$file_details = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."pd_files WHERE id = '".intval($_fileid)."'", ARRAY_A);
		if (intval($file_details["id"]) == 0) return false;
		$download_key = md5(microtime().rand(1,10000)).md5(microtime().$file_details["title"]);
		$sql = "INSERT INTO ".$wpdb->prefix."pd_downloadlinks (
			file_id, download_key, owner, source, created) VALUES (
			'".$_fileid."',
			'".$download_key."',
			'".mysql_real_escape_string($_owner)."',
			'".$_source."',
			'".time()."'
		)";
		$wpdb->query($sql);
		return get_bloginfo('wpurl').'/wp-content/plugins/paid-downloads/download.php?download_key='.$download_key;
	}

	function robokassa_calc_price($_price, $_currency)
	{
		if ($_currency == "USD") $currency = "WMZM";
		else if ($_currency == "EUR") $currency = "WMEM";
		else $currency = "WMRM";
		$request = 'MerchantLogin='.$this->robokassa_merchant.'&OutSum=1000000&IncCurrLabel='.$currency.'&Language=en';
		$url = ($this->robokassa_sandbox == 'on' ? 'http://test.robokassa.ru/Webservice/Service.asmx/GetRates' : 'http://merchant.roboxchange.com/WebService/Service.asmx/GetRates');
		try
		{
			if ($stream = fopen($url."?".$request, 'r')) 
			{
				$result = stream_get_contents($stream);
				fclose($stream);
			}
		}
		catch (Exception $e)
		{
			return false;
		}
		preg_match_all('|<Code>(.*)</Code>|Uis', $result, $code);
		if (!isset($code[1][0]) || $code[1][0] != 0) return false;
		preg_match_all('|<Rate IncSum=\"(.*)\"|Uis', $result, $value);
		if (!isset($value[1][0])) return false;
		$value = floatval($value[1][0]);
		if ($value == 0) return false;
		$price = number_format($_price*1020000/$value, 2, ".", "");
		return $price;
	}
}

$paiddownloads = new paiddownloads_class();
?>