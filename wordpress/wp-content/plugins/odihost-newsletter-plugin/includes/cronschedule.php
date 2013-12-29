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

	$table_email_schedule = $wpdb->prefix . "newsletter_email_schedule";
	$email_schedules = $wpdb->get_results("select * from $table_email_schedule where status = 1");
	$table_users = $wpdb->prefix . "newsletter_users";
	$email_from = stripslashes(get_option('newsletter_email_from'));

	//print_r($email_schedules);
	foreach($email_schedules as $email_schedule)
	{
		//query where 
		$subscribers = $wpdb->get_results("select * from $table_users where datediff(joindate, now()) = -". $email_schedule->daysent);
		foreach($subscribers as $subscriber)
		{
			$mail  = new PHPMailer(); // defaults to using php "mail()"
			$mail->IsMail(); 
			$mail->SetFrom($adminemail, $fromname);
			$mail->AddReplyTo($adminemail,$fromname);

			$mail->AddAddress($subscriber->email, '');
			$mail->Subject   =  $email_schedule->subject ;

			$message = $email_schedule->message;
			$message = str_replace("*name*", $subscriber->name, $message);
			$message .= get_newsletter_footer($subscriber);
			$mail->MsgHTML($message);
			
			
			
			if(!$mail->Send()) {
			  echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
			   $content.= $subscriber->email . "<br>";
			}
			
			echo $content;
		}
		
		$date = date("H:i dS F"); //Get the date and time.
		$file = dirname(__FILE__)."/log/".date("dS F").".html";
		$open = fopen($file, "a+"); //open the file, (log.htm).
		fwrite($open, $date . " Newsletter schedule ID: ".$email_schedule->id ."<br>".$content); 

		fclose($open); // you must ALWAYS close the opened file once you have finished.


	}
?>