<script type="text/javascript" src="<?php echo get_option('siteurl') . '/wp-includes/js/tinymce/tiny_mce.js';?>"></script>
<script>
	function save_setting()
	{
		document.getElementById("newsletter_footer").value = tinyMCE.getInstanceById("newsletter_footer_area").getBody().innerHTML;
		var form = document.settingform;	
		form.submit();
		
	}
</script>
<div class="wrap">
<h2>Newsletter</h2>
<?php


// Update options if user posted new information
	global $wpdb;

	$table_users = $wpdb->prefix . "newsletter_users";
					
	// Update options if user posted new information
	if( $_POST['save'] == '1' ) {
		// Read from form
		$email_from = stripslashes($_POST['newsletter_email_from']);
		$email_from_name = stripslashes($_POST['newsletter_email_from_name']);
		$email_subject = stripslashes($_POST['newsletter_email_subject']);
		$email_message = stripslashes($_POST['newsletter_email_message']);
		$email_test = stripslashes($_POST['newsletter_email_test']);
		$newsletter_email_rate = stripslashes($_POST['newsletter_email_rate']);
		$newsletter_footer = stripslashes($_POST['newsletter_footer']);
		
		
		$email_subject_subscriber = stripslashes($_POST['newsletter_email_subject_subscriber']);
		$email_message_subscriber = stripslashes($_POST['newsletter_email_message_subscriber']);

		$msg_dup = stripslashes($_POST['newsletter_msg_dup']);
		$msg_fail = stripslashes($_POST['newsletter_msg_fail']);
		$msg_sent = stripslashes($_POST['newsletter_msg_sent']);

		$form_header = stripslashes($_POST['newsletter_form_header']);
		$form_footer = stripslashes($_POST['newsletter_form_footer']);
		$form_email = stripslashes($_POST['newsletter_form_email']);
		$form_send = stripslashes($_POST['newsletter_form_send']);

		// Save to database
		update_option('newsletter_email_from', $email_from );
		update_option('newsletter_email_from_name', $email_from_name );
		update_option('newsletter_email_subject', $email_subject);
		update_option('newsletter_email_message', $email_message);
		update_option('newsletter_email_test', $email_test);
		update_option('newsletter_email_rate', $newsletter_email_rate);
		update_option('newsletter_footer', $newsletter_footer);
		

		update_option('newsletter_email_subject_subscriber', $email_subject_subscriber);
		update_option('newsletter_email_message_subscriber', $email_message_subscriber);

		update_option('newsletter_msg_dup', $msg_dup);
		update_option('newsletter_msg_fail', $msg_fail);
		update_option('newsletter_msg_sent', $msg_sent);

		update_option('newsletter_form_header', $form_header);
		update_option('newsletter_form_footer', $form_footer);
		update_option('newsletter_form_email', $form_email);
		update_option('newsletter_form_send', $form_send);

		echo '<div id="message" class="updated fade"><p><strong>';
		_e('Settings saved.', 'newsletter_domain');
		echo '</strong></p></div>';
	}
	
	$email_from = stripslashes(get_option('newsletter_email_from'));
	$email_from_name = stripslashes(get_option('newsletter_email_from_name'));
	$email_subject = stripslashes(get_option('newsletter_email_subject'));
	$email_message = stripslashes(get_option('newsletter_email_message'));
	$email_test = stripslashes(get_option('newsletter_email_test'));
	$newsletter_email_rate = stripslashes(get_option('newsletter_email_rate'));
	
	$email_subject_subscriber = stripslashes(get_option('newsletter_email_subject_subscriber'));
	$email_message_subscriber = stripslashes(get_option('newsletter_email_message_subscriber'));
	$msg_dup = stripslashes(get_option('newsletter_msg_dup'));
	$msg_fail = stripslashes(get_option('newsletter_msg_fail'));
	$msg_sent = stripslashes(get_option('newsletter_msg_sent'));

	$form_header = stripslashes(get_option('newsletter_form_header'));
	$form_footer = stripslashes(get_option('newsletter_form_footer'));
	$form_email = stripslashes(get_option('newsletter_form_email'));
	$form_send = stripslashes(get_option('newsletter_form_send'));
	
	
?>
<form method="post" action="" name="settingform" id="settingform">
    <input type="hidden" name="process" value="edit" />
    <fieldset class="options"> <b>General</b> 
    <table widtd="100%" cellspacing="2" cellpadding="2">
      <tr valign="top"> 
        <td scope="row">Sender email:</td>
        <td> 
            <input type="text" name="newsletter_email_from" id="newsletter_email_from" value="<?php echo $email_from; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Sender name:</td>
        <td> 
            <input type="text" name="newsletter_email_from_name" id="newsletter_email_from_name" value="<?php echo $email_from_name; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Test email:</td>
        <td> 
            <input type="text" name="newsletter_email_test" id="newsletter_email_test" value="<?php echo $email_test; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Email subject to prospect subscriber:</td>
        <td> 
          <input type="text" name="newsletter_email_subject" id="newsletter_email_subject" value="<?php echo $email_subject; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Email message to prospect subscriber:</td>
        <td> 
            <textarea name="newsletter_email_message" id="newsletter_email_message" rows="4" cols="40"><?php echo $email_message; ?></textarea>
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Email subject to subscriber:</td>
        <td> 
          <input type="text" name="newsletter_email_subject_subscriber" id="newsletter_email_subject_subscriber" value="<?php echo $email_subject_subscriber; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Email message to subscriber:</td>
        <td> 
            <textarea name="newsletter_email_message_subscriber" id="newsletter_email_message_subscriber" rows="4" cols="40"><?php echo $email_message_subscriber; ?></textarea>
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Send email rate per hour:</td>
        <td> 
             <input type="text" name="newsletter_email_rate" id="newsletter_email_rate" value="<?php echo $newsletter_email_rate; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row" colspan=2>    </fieldset>
		<fieldset class="options"> <b>Newsletter</b> </td>
      </tr>
      <tr valign="top">
        <td scope="row">Newsletter footer:</td>
        <td>

		<script type="text/javascript">
				<!--
				tinyMCE.init({
				theme : "advanced",
				  theme_advanced_toolbar_align: "left",
				  theme_advanced_buttons1: "undo,redo,bold,italic,underline,strikethrough,bullist,numlist,indent,outdent,justifyleft,justifycenter,justifyright,link, code",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "top",
				mode : "exact",
				language : "en",
				elements : "newsletter_footer_area",
				width : "300",
				height : "200"
				});
				-->

				</script>
				
				<textarea id="newsletter_footer_area" id="newsletter_footer_area"><?php echo $newsletter_footer;?></textarea>
				<input type="hidden" name="newsletter_footer" id="newsletter_footer" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row" colspan=2>    </fieldset>
		<fieldset class="options"> <b>Messages</b> </td>
      </tr>
      <tr valign="top">
        <td scope="row">Duplicate e-mail address message:</td>
        <td>
          <input type="text" name="newsletter_msg_dup" id="newsletter_msg_dup" value="<?php echo $msg_dup; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Failed to send email message:</td>
        <td> 
          <input type="text" name="newsletter_msg_fail" id="newsletter_msg_fail" value="<?php echo $msg_fail; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Success send email message:</td>
        <td> 
          <input type="text" name="newsletter_msg_sent" id="newsletter_msg_sent" value="<?php echo $msg_sent; ?>" size="40" />
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row" colspan=2>     </fieldset> <fieldset class="options"> 
    <legend><b>Widget labels</b></legend>
 </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Opt-in Form header:</td>
        <td> 
          <textarea name="newsletter_form_header" id="newsletter_form_header" rows="3" cols="40"><?php echo $form_header; ?></textarea>
        </td>
      </tr>
      <tr valign="top"> 
        <td scope="row">Opt-in Form footer:</td>
        <td> 
          <textarea name="newsletter_form_footer" id="newsletter_form_footer" rows="3" cols="40"><?php echo $form_footer; ?></textarea>
        </td>
      </tr>
	  </table>
</fieldset>
<input type="hidden" name="save" id="save" value="1">
<p class="submit"><input type="button" onclick="save_setting()" name="Submit" value="Update Settings &raquo;" /></p>
</form>
<a target="_blank" href="<?php echo get_option("siteurl");?>/wp-content/plugins/odihost-newsletter-plugin/includes/log">See email sending log</a>
</div>