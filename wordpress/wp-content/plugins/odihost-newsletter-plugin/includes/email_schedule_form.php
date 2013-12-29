<div class="wrap">
<?php

	global $wpdb;
	$table_email_schedule = $wpdb->prefix . "newsletter_email_schedule";
	if($_REQUEST["save"] == 1)
	{
		$subject = stripslashes($_REQUEST['newsletter_subject']);
		$message = stripslashes($_REQUEST['newsletter_message']);
		$day_sent = stripslashes($_REQUEST['newsletter_day_sent']);
		$status = stripslashes($_REQUEST['newsletter_status']);

		if($_REQUEST["id"] == "")
		{			
			$query = "INSERT INTO " . $table_email_schedule . " 
					(subject, message, daysent, status) 
					VALUES ('" . $subject . "',
					'" . $message . "',
					'" . $day_sent . "',
					'" . $status  . "'	)";
				$result = $wpdb->query($query);
		}
		else
		{
			$query = "update " . $table_email_schedule . " set subject = '$subject', message = '$message', daysent = '$day_sent', status='$status' where id = ".  $_REQUEST["id"];
			$result = $wpdb->query($query);
		}
	}
	
	$row = $wpdb->get_results("select * from $table_email_schedule where id = ". $_REQUEST["email_schedule_id"]);
	$subject = $row[0]->subject;
	$message = $row[0]->message;
	$day_sent = $row[0]->daysent;
	$status = $row[0]->status;
		
?>
<script type="text/javascript" src="<?php echo get_option('siteurl') . '/wp-includes/js/tinymce/tiny_mce.js';?>"></script>
<script>
	function save_newsletter()
	{
		document.getElementById("newsletter_message").value = tinyMCE.getInstanceById("newsletter_message_area").getBody().innerHTML;
		var form = document.newsletterform;	
		form.submit();
		
	}
</script>
<h2>Email schedule</h2>
<a href="<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter_email_schedule";?>">Back to list</a>
	<form action="" method="post"  name="newsletterform">
    <input type="hidden" name="save" value="1" />
	<table width="100%"><tr><td>Email Subject:</td><td><input type="text" name="newsletter_subject" id="newsletter_subject" size="100" value="<?php echo $subject;?>"/></td></tr>
	<tr><td>Message: <br/>Type <b>*name*</b> to set the username</td><td><script type="text/javascript">
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
				elements : "newsletter_message_area",
				width : "500",
				height : "400"
				});
				-->

				</script>
				<textarea id="newsletter_message_area" id="newsletter_message_area"><?php echo $message;?></textarea>
				<input type="hidden" name="newsletter_message" id="newsletter_message" />
				</td></tr>
	<tr><td>Day sent:</td><td><input type="text" name="newsletter_day_sent" id="newsletter_day_sent" size="100" value="<?php echo $day_sent;?>"/></td></tr>
	<tr><td>Status:</td><td><select name="newsletter_status" id="newsletter_status"><option value="1" <?php if($status== 1) echo "selected";?>>Active</option><option value="0" <?php if($status== 0) echo "selected";?>>Inactive</option></select></td></tr>
	</table>
	<input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["email_schedule_id"];?>">
	<p class="submit"><input  type="button" onclick="save_newsletter()"  value="Save"/></p></form>	
	
	
</div>
