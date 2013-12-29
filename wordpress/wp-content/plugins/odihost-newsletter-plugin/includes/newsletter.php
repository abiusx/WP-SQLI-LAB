<div class="wrap">
<?php

	include("newsletterfooter.php");
	if($_REQUEST["save"] == 1)
	{
		global $wpdb;
		$table_newsletter = $wpdb->prefix . "odi_newsletter";

		if($_GET["id"] == "")
		{
			$wpdb->query("insert into $table_newsletter (created, subject, message) values (now(),'".$_REQUEST["newsletter_subject"]."','".$_REQUEST["newsletter_message"]."')");
			echo "<b>Newsletter added</b>";
		}
		else
		{
			$wpdb->query("update $table_newsletter set subject = '".$_REQUEST["newsletter_subject"]."', message = '".$_REQUEST["newsletter_message"]."' where id = ".$_GET["id"]);
			echo "<b>Newsletter update</b>";
		
		}
		add_edit_newsletter($_REQUEST["id"]);
	}
	else if($_REQUEST["task"] == "edit")
	{
		add_edit_newsletter($_REQUEST["id"]);
	}
	else if($_REQUEST["task"] == "new")
	{
		add_edit_newsletter(0);
	}
	else if($_REQUEST["task"] == "test")
	{
		send_test_email();
	}
	else if($_REQUEST["task"] == "stat")
	{
		include("stat.php");
	}
	else if($_REQUEST["task"] == "send")
	{
		send_email();
	}
	else if($_REQUEST["task"] == "delete")
	{
		global $wpdb;
		$table_newsletter = $wpdb->prefix . "odi_newsletter";
		$wpdb->query("delete from $table_newsletter where id =".$_GET["id"]);
		echo "<b>Deleted</b>";
		show_newsletter_list();
	}
	else
	{
		show_newsletter_list();
	}
	
	function show_newsletter_list()
	{
		global $wpdb;
		include ("news.php");
		$table_newsletter = $wpdb->prefix . "odi_newsletter";
		$rows = $wpdb->get_results("select * from $table_newsletter order by id desc");
		echo '<h2>Newsletter List</h2>
		<a href="'. get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=new" .'">Add new</a>
		<table class="widefat"><tr><td>Created</td><td>Subject</td><td></td></tr>';
		foreach($rows as $row)
		{		
	?>
			<tr><td><?php echo $row->created;?></td><td><?php echo $row->subject;?></td><td><a href='<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=edit&id=".$row->id; ?>'>Edit</a> | <a href='<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=test&id=".$row->id; ?>'>Send test email</a> | <a href='<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=send&to=optin&id=".$row->id; ?>'>Send to opt-in subscribers</a> | <a href='<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=send&to=all&id=".$row->id; ?>'>Send to all subscriber</a> | <a href='<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=stat&id=".$row->id; ?>'>Stats</a> | <a href='<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter&task=delete&id=".$row->id; ?>'>Delete</a></td></tr>
		<?php
		}
		?>
		</table>
		<?php
	}
	
	function send_test_email()
	{
		global $wpdb;
		 require_once('class.phpmailer.php');
		$testemail = get_option('newsletter_email_test');
		$adminemail =  get_option('newsletter_email_from');
		$fromname =  stripslashes(get_option('newsletter_email_from_name'));
		$mail  = new PHPMailer(); // defaults to using php "mail()"

		$mail->IsMail(); 
		
		$table_newsletter = $wpdb->prefix . "odi_newsletter";
		$row = $wpdb->get_row("select * from $table_newsletter where id =".$_GET["id"]);
		
		$mail->SetFrom($adminemail, $fromname);
		$mail->AddReplyTo($adminemail,$fromname);

		$mail->AddAddress($testemail);
		$mail->Subject   =  $row->subject ;

		$message = $row->message;
		$message .= get_newsletter_footer($subscriber);

		$mail->MsgHTML($message);

		if(!$mail->Send()) {
		  echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		   echo "The email was sent.";
		}
	}
	
	function send_email()
	{
		$counter =0;
		global $wpdb;
		 require_once('class.phpmailer.php');

		 $limitsend = get_option("newsletter_email_rate");
		 if( $limitsend  =="")  $limitsend =250;
		$table_subscriber = $wpdb->prefix . "newsletter_users";
		
		$fromname =  stripslashes(get_option('newsletter_email_from_name'));
		$adminemail = get_option('newsletter_email_from');

		$table_newsletter = $wpdb->prefix . "odi_newsletter";
		$newsletterid = $_GET["id"];
		$row = $wpdb->get_row("select * from $table_newsletter where id =".$newsletterid);
		
		if($_REQUEST["to"] == "all")
			$subscribers = $wpdb->get_results("select * from  $table_subscriber where status != 3");
		else
			$subscribers = $wpdb->get_results("select * from  $table_subscriber where status = 1");
		
		foreach($subscribers as $subscriber)
		{
			if($limitsend > $counter)
			{				
				$mail  = new PHPMailer(); // defaults to using php "mail()"
				$mail->IsMail(); 
				$mail->SetFrom($adminemail, $fromname);
				$mail->AddReplyTo($adminemail,$fromname);

				$mail->AddAddress($subscriber->email, '');
				$mail->Subject   =  $row->subject ;

				$message = $row->message;
			
				//replace the links
				$pattern = "/href=['\"]?([^'\">]+)['\"]?/i";
				$replacement = 'href="'.get_option("siteurl")."/wp-content/plugins/odihost-newsletter-plugin/includes/clickstat.php?id=".$newsletterid . "&uid=" . $subscriber->id ."&url=$1".'"';
				$template = preg_replace($pattern, $replacement, $template);

				//add open email tracking
				$template .= "<img src='".get_option("siteurl")."/wp-content/plugins/odihost-newsletter-plugin/includes/openstat.php?id=".$newsletterid . "&uid=" . $subscriber->id . "' width='1px' height='1px'>";

				$message .= get_newsletter_footer($subscriber);
				
				$mail->MsgHTML($message);

				if(!$mail->Send()) {
				  echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
				   $content.= $subscriber->email . "<br>";
				}
				
				echo "Sending to " . $subscriber->email ."<br>";
			}
			else
			{
				$nextemailuserid .= $subscriber->id .",";
			}
			$counter++;
		}
		
		if($nextemailuserid != "")
		{
			$nextemailuserid = substr($nextemailuserid, 0, strlen($nextemailuserid) - 1);
			$wpdb->query("insert into odinewslettercron (newslettercronnewsletterid, newslettercronsubscriberid) values ( $newsletterid, '$nextemailuserid')");
		}
		
		$date = date("H:i dS F"); //Get the date and time.
		$file = dirname(__FILE__)."/log/".date("dS F").".html";

		$open = fopen($file, "a+"); //open the file, (log.htm).
		fwrite($open, $date . " Newsletter ID: $newsletterid<br>".$content); 

		fclose($open); // you must ALWAYS close the opened file once you have finished.
		
		/*newsletter stat*/
		$rows = $wpdb->get_results("select * from newsletterstat where newsletterstatnewsletterid= ". $newsletterid);
		if(count($rows) > 0)
		{
			//update
			$wpdb->query("update newsletterstat set newsletterstatsent = ". $counter . " where newsletterstatnewsletterid= ". $newsletterid);
		}
		else
		{
			$wpdb->query("insert into newsletterstat(newsletterstatnewsletterid, newsletterstatsent, newsletterstatsenddate) values ($newsletterid,$counter,now() )");
		}
		/*newsletter stat*/
	}

	function add_edit_newsletter($id)
	{
		global $wpdb;
		$table_newsletter = $wpdb->prefix . "odi_newsletter";
		$row = $wpdb->get_row("select * from $table_newsletter where id =".$id);
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
<h2>Newsletter</h2>
	<form action="" method="post" name="newsletterform">
    <input type="hidden" name="process" value="email" />
	<table width="100%"><tr><td>Email Subject:</td><td><input type="text" name="newsletter_subject" id="newsletter_subject" size="100" value="<?php echo $row->subject;?>"/></td></tr>
	<tr><td>Message: <br/>Type <b>*name*</b> to set the username</td><td>	<script type="text/javascript">
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
				
				<textarea id="newsletter_message_area" id="newsletter_message_area"><?php echo $row->message;?></textarea>
				<input type="hidden" name="newsletter_message" id="newsletter_message" />
				</td></tr></table>
				<input type="hidden" name="save" id="save" value="1"><p class="submit"><input type="button" onclick="save_newsletter()" value="Save Newsletter"/></p></form>	

<?php
	}
?></div>
