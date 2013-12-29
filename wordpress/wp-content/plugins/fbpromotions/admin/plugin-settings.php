<?php
/**
*	BugsGoViral Plugin for Worpress
*	Plugin Settings View
*
* 	@filesource 			plugin-settings.php
*	@description			HTML to display view for input of Core Settings
* */
include("version.php");

/**
* 	@todo 					style now that tables changed to divs etc
* 	@todo 					Style similar to fb dev app page to ease copy and paste input
* 	@todo 					should be displayed in Root menu - look into making following structure:
* 									BGV Menu Link
* 									Settings
* 										Facebook App
* 										BGV Account
* 									Promotions
* 										Promotions Overview
* 										Create/Review/Update/Delete
*
* 	@todo 					consider renaming db field 'fb_canvas_url'. Confusing when considering facebook app use of name
*
* */

global $notification_list, $notifications_obj;
include_once ( FBPROMODIR . '/bgvnotifications.php' );
$notifications_obj=new bgv_notifications($_REQUEST["promo_id"]);

/**
* Start display code
*/
?>

<div  id="plugin_settings_container" class="wrap">
<?php
      if(!get_option("key")){
	   ?>
		<div class='update-nag'>Account setup is mandatory before configuring any promo.</div>
      <?php
	  }elseif(empty($fbmo->options["fb_application_secret"])){
        ?>
        <div class='update-nag'>Facebook Settings needs to be updated.</div>
        <?php
        }
        ?>
	<?php do_html_admin_masthead ("plugin_settings","masthead","Plugin Settings","Core information required",0) ?>
     <p>Please enter the details about your Facebook application below. This is required in order to launch a promo. For a brief, step-by-step explanation of this process, <a href="http://bugsgoviral.com/support/manuals/" target="_blank">click here</a>.</p>
	<form name="generaloptions" method="post">
			<input type="hidden" name="updateoption" value="1">
			<?php wp_nonce_field('fbm_settings') ?>
			<div id="fb_app_core_settings">
<table>
				
<tr>
							<td width="25%">
								App ID
							</td>
							<td>
<input type="text" size="35" name="fb_application_id" value="<?php echo $fbmo->options['fb_application_id']?>" />
<?php
if (isset($fbmo->admin_messages["fb_application_id"]))	{
echo '<span class="error">'  .  $fbmo->admin_messages["fb_application_id"]  .  '</span>';	}
								?>
							</td>
</tr>
<tr>
							<td></td><td align="left">
								<span class="setting-description">
									The "Application ID" for your Facebook application.
								</span>
								</td>
</tr>
<tr>
							<td width="25%">
								API Key
							</td>
							<td>
							<input type="text" size="55" name="fb_application_key" value="<?php echo $fbmo->options['fb_application_key']?>" />
<?php
if (isset($fbmo->admin_messages["fb_application_key"])) {
echo '<span class="error">'  .  $fbmo->admin_messages["fb_application_key"]  .  '</span>'; }
								?>
							</td>
<tr>
							<td></td><td align="left">
							<span class="setting-description">
									The "API Key" for your Facebook application.
							</span>
							</td>
</tr>
<tr>
							<td width="25%">
								App Secret
							</td>
							<td>
								<input type="text" size="55" name="fb_application_secret" value="<?php echo $fbmo->options['fb_application_secret']?>" />
<?php
if (isset($fbmo->admin_messages["fb_application_secret"]))	{
echo '<span class="error">'  .  $fbmo->admin_messages["fb_application_secret"]  .  '</span>';	}
								?>
							</td>
</tr>
<tr>							<td></td><td align="left">
							<span class="setting-description">
								The "App Secret" for your Facebook application.
							</span>
							</td>
</tr>
<tr>
							<td width="25%">
								Canvas Page
							</td>
							<td>
								<input type="text" size="55" name="fb_canvas_url" value="<?php echo $fbmo->options['fb_canvas_url']?>" />
<?php
if (isset($fbmo->admin_messages["fb_canvas_url"]))	{
echo '<span class="error">'  .  $fbmo->admin_messages["fb_canvas_url"]  .  '</span>';	}
								?>
							</td>
</tr>
<tr>
														<td></td><td align="left"><span class="setting-description">
							Canvas Page Example - http://apps.facebook.com/your_app/ <br>
							The "Canvas Page" defined in your Facebook application.</td>
							</span>
</tr>
<tr>
							<td width="25%">
								Fan Page URL
							</td>
							<td>
								<input type="text" size="55" name="fb_fanpage_url" value="<?php echo $fbmo->options['fb_fanpage_url']?>" />
								<?php
									if (isset($fbmo->admin_messages["fb_fanpage_url"]))	{
										echo '<span class="error">'  .  $fbmo->admin_messages["fb_fanpage_url"]  .  '</span>';	}
								?>
							</td>
</tr>
<tr>
							<td></td><td align="left"><span class="setting-description">
							Fan Page URL Example -  http://www.facebook.com/pages/yourpage/22247778888<br>
							Important! Do not include any url parameters here. The URL to your Fan Page. The URL contain the page's Facebook id, not the vanity URL.</span></td><tr></tr>
</tr>
<tr>
							<td width="25%">
								App Admin Email
							</td>
							<td>
								<input type="text" size="55" name="contact_email" value="<?php echo $fboptions['contact_email']?>" />
							</td>
</tr>
<tr>
							<td></td><td align="left"><span class="setting-description">
									Email address for individual with Administration Authority for your application.
									Facebook uses this address for official contact regarding your application.
								</span></td><tr></tr>
						</tr>
						<tr>
							<td width="25%">
								Developer Email
							</td>
							<td>
								<input type="text" size="55" name="fbapp_dev_email" value="<?php echo $fboptions['fbapp_dev_email']?>" />
							</td>
							<td>
								</tr>
								<tr>
															<td></td><td align="left">
															<span class="setting-description">
										Email address for Developer listed in your application. Note - the Admin and Developer email addresses may be the same.</span></td><tr></tr>
									</tr>
				</table>
			</div>
			<div id="fb_app_appearance_settings" style="display:none">
			<table>
				<tr>
					<td>
						<tr>
							<td width="25%">
								Application Name
							</td>
							<td>
								<input type="text" size="55" name="application_name" value="<?php echo $fboptions['application_name']?>" />
							</td>
							<td>
								<span class="setting-description">
									Used in Facebook Directory
								</span>
							</td>
						</tr>
					</td>
					<td>
						<tr>
							<td width="25%">
								Application Description
							</td>
							<td>
								<textarea cols="35" rows="3" name="description"><?php echo $fboptions['description']?></textarea>
							</td>
							<td>
								<span class="setting-description">
									Used in Facebook Directory
								</span>
							</td>
						</tr>
					</td>
					<td>
						<tr>
							<td width="25%">
								Menu/Tab Text
							</td>
							<td>
								<input type="text" size="55" name="tab_default_name" value="<?php echo $fboptions['tab_default_name']?>" />
							</td>
							<td>
								<span class="setting-description">
									The default label for your application menu/tab when it's added to a page.
								</span>
							</td>
						</tr>
					</td>
					<td>
						<tr>
							<td width="25%">
								Application Logo
							</td>
							<td>
								<input type="file" size="35" name="fbapp_logo" value="<?php echo $fboptions['fbapp_logo']?>" />
							</td>
							<td></td><td align="left">
								<span class="setting-description">
									The logo Facebook displays with permissions requests to users.
								</span>
							</td>
						</tr>
					</td>
					<td>
						<tr>
							<td>
								Application Icon
							</td>
							<td>
								<input type="file" size="35" name="fbapp_icon" value="<?php echo $fboptions['fbapp_icon']?>" />
							</td>
							<td>
								<span class="setting-description">
									The icon which appears beside bookmark links to your Facebook Application.
								</span>
							</td>
						</tr>
					</td>
					<td>
						<tr>
							<td>
								Application Profile Image
							</td>
							<td>
								<input type="file" size="35" name="fbapp_profile_image" value="<?php echo $fboptions['fbapp_profile_image']?>" />
							</td>
							<td>
								<span class="setting-description">
									The image appearing on your Facebook Application's profile page.
								</span>
							</td>
						</tr>
					</td>
				</tr>
			</table>
		</div>

			<div class="submit">
				<p  bgcolor="#CCCCCC" class="pagetitle">
					<input type="submit" name="Submit" value="SAVE SETTINGS &gt;&gt;&gt;">
				</p>
			</div>

</form>
</div>
			<?php do_html_admin_footer("plugin_settings","footer") ?>


<div class="help_content_container">
	<div class="help_block" id="bgv_fbapp_integration_help" style= "display:none">
		<h1 class="help_title" id="bgv_fbapp_integration_help_title" >NOT FINISHED: How to integrate your BugsGoViral powered promotion with the Facebook platform.</h1>
		<table>
		<tr class="help_list" id="bgv_fbapp_integration_help_list">
			<td>
				<a href="http://www.facebook.com/developers/createapp.php" target="_blank" class="help_url_link" id="facebook_create_fbapp_link" >Create App</a>
			</td>
			<td>
				<a href="http://www.facebook.com/developers" target="_blank" class="help_url_link" id="facebook_developers_link">Facebook Developer Tool</a>
			</td>
			<td>
				<a href="http://developers.facebook.com/docs/guides/canvas/" target="_blank" class="help_url_link" id="facebook_developers_link" >Guides by Facebook</a>
			</td>
			<td>
				<a href="http://www.facebook.com/developers/apps.php?"target="_blank" class="help_url_link" id="facebook_urlbase_fbapp_link" >URLBASE: App Overview</a> Appending "app_id=(Place App ID here)" to base url will link directly to the overview of Application
			</td>
			<td>
				<span class="help_tip" id="copypaste_suggestion" >It's helpful to open a separate browser window or tab to easily copy and paste information between them as needed</span>
			</td>
			<td>
				CREATE THIS: Our super simple<a href="#" target="_blank" class="help_url_link" id="bgv_fbapp_checklist">Facebook App Checklist</a> help you set and note important items required by Facebook for applications
			</td>
		</table>

	</div><!-- DIV END "bgv_fbapp_integration_help" -->
</div><!-- DIV END "help_content_container" -->