<?php

	include ("../../../../wp-load.php");
	require_once('class.phpmailer.php');
	include("newsletterfooter.php");

	global $wpdb;
	$fromname =  stripslashes(get_option('newsletter_email_from_name'));
	$adminemail = get_option('newsletter_email_from');

	function getuserkey($id)
	{
		$secretkey = "jwkerusler";
		return md5($id . $secretkey);
	}
	$limitsend =  get_option("newsletter_email_rate");
	if( $limitsend  =="")  $limitsend =250;
	
	$totalsent = 0;
	
	$newsletters = $wpdb->get_results("select * from odinewslettercron");
	foreach($newsletters as $newsletter)
	{
		$newsletterid = $newsletter->newslettercronnewsletterid;
		$date = $wpdb->get_var("select newsletterdate from newsletter where id = ".$newsletterid);
		$subject = $wpdb->get_var("select newslettertitle from newsletter where id = ".$newsletterid);

		$table_subscriber = $wpdb->prefix . "newsletter_users";
		$subscribers = $wpdb->get_results("select * from  $table_subscriber where id in (".$newsletter->newslettercronsubscriberid.")");
		$counter =0;

		foreach($subscribers as $subscriber)
		{
			if($limitsend > $totalsent)
			{
				$mail  = new PHPMailer(); // defaults to using php "mail()"
				$mail->IsMail(); 
				$mail->SetFrom($adminemail, $fromname);
				$mail->AddReplyTo($adminemail,$fromname);

				$mail->AddAddress($subscriber->email, '');
				$mail->Subject   =  $row->subject ;

				$message = $row->message;
				$message = str_replace("*name*", $subscriber->name, $message);
				
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
				
				$counter++;	
				$totalsent++;		
			}
			else
			{
				$nextemailuserid .= $subscriber->id .","; 			
			}
		}
					
		if($nextemailuserid != "")
		{
			$nextemailuserid = substr($nextemailuserid, 0, strlen($nextemailuserid) - 1);
			$wpdb->query("update odinewslettercron set  newslettercronsubscriberid = '$nextemailuserid' where id = '$newsletterid'");
		}
		else
		{
			$wpdb->query("delete from odinewslettercron where id = " . $newsletter->id);	
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
		/*newsletter stat*/
	}

	
?>