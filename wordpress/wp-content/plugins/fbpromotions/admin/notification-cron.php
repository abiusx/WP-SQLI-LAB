<?php


global $wpdb;
global $fbmo;
global $notifications_obj;
global $promo_list;
global $notification_list;
global $stats_obj;

include( FBPROMODIR . '/fbstats.php' );
include( FBPROMODIR . '/facebookapi/facebook.php'); 
include( FBPROMODIR. '/twitterapi/Twitter.class.php');
return;
//try it out
$fbgraph = "";
$fbme = "";
$session = '';

//if we arent loaded roll hard
if ( !isset($wp_did_header) ) {
	$wp_did_header = true;
	require_once('../wp-load.php' );
}


$timestamp = "Message sent: " . date('l jS \of F Y h:i:s A');


$options = get_option('fbmo_options');
$fboptions=get_option('fbmo_facebook_settings');  //get current option settings if any

		
		
$fbmo->get_promo_list();




echo 'Starting promos.. <br/>';

//get the promos

foreach ($promo_list as $promo_row)
{
	
	//echo "Promo Name:" . $promo_row->promo_name."<br />";
    $notifications_obj=new bgv_notifications($promo_row->promo_id);
	$notification_list = $notifications_obj->getNotifications();  
	
	
	//lets get each notification for the promo
	foreach ($notification_list as $notification_row)	
	{
	
	$channel = $notification_row->channel;
	$milestone = $notification_row->milestone;
	$number_of_times = $notification_row->times;
	
	$notifications_obj->getNotificationEmails($notification_row->notice_id) ;
	
	
		//make sure it passed the milestone
			
			if (!checkMilestone($milestone,$number_of_times,$promoid))
			{exit;}
			
			
			switch (strtolower(trim($channel))){
			
			case "email" :
			
		
				echo "Starting to send email... <br/>";
			
			    if ($notifications_obj->user_email)
				{
                  mail($notifications_obj->user_email, 'Cron E', $notification_row->message);
			    }
			  
			  
			    if ($notifications_obj->user_email2)
				{
			
			   mail($notifications_obj->user_email2, 'Cron test', $notification_row->message);
			    }
				
			    if ($notifications_obj->user_email3)
				{
				
				mail($notifications_obj->user_email3, 'Cron test', $notification_row->message);
				 }
				 
			    if ($notifications_obj->user_email4)
				{
				
				 mail($notifications_obj->user_email4, 'Cron test', $notification_row->message);
			    }
			  
				echo 'Email Sent <br/>';
				
				break;
		       case "post" :
				
				
							
			if (!checkMilestone($milestone,$number_of_times,$promoid))
			{exit;}
			

	           // Create our Application instance.
	   			 $facebook = new Facebook(array(
	      					'appId'  => $options['fb_application_id'],
	      					'secret' => $options['fb_application_secret'],
	      					'cookie' => true,
	    									));
		
                  $session = $facebook->getSession();
				
				  echo "Posting to facebook wall <br/>";
				
                  if ($notifications_obj->user_email)
				  {$result = $facebook->api('/'.$notifications_obj->user_email.'/feed/','post', array('access_token' => $access_token, 'message' => $notification_row->message . $timestamp));}
 
 
                  if ($notifications_obj->user_email2)
				  {$result = $facebook->api('/'.$notifications_obj->user_email2 . '/feed/','post', array('access_token' => $access_token, 'message' => $notification_row->message . $timestamp));}

				  if ($notifications_obj->user_email3)
				  {$result = $facebook->api('/'.$notifications_obj->user_email3.'/feed/','post', array('access_token' => $access_token, 'message' => $notification_row->message . $timestamp));}

				  if ($notifications_obj->user_email4)
				  {$result = $facebook->api('/'.$notifications_obj->user_email4.'/feed/','post', array('access_token' => $access_token, 'message' => $notification_row->message . $timestamp));}

				  
				  echo "End posting to facebook wall <br/>";				  
				
				break;
		       case "sms" :
				//todo
				
				break;
			  case "twitter" :
				
			
			
			  			
			if (!checkMilestone($milestone,$number_of_times,$promoid))
			{exit;}
			
			
				$tw = new Twitter();
                # After creating Twitter() instance you must authenticate in proper to use resources of your twitter account
	             $tw->authenticate();
	           
			   	
				 echo "Posting to twitter <br/>";
			   
			    if ($notifications_obj->user_email)
				{$tw->setStatus("@" . $notifications_obj->user_email . $timestamp);}
				
				if ($notifications_obj->user_email2)
				{$tw->setStatus("@" . $notifications_obj->user_email2 . $timestamp);}
				
				if ($notifications_obj->user_email3)
				{$tw->setStatus("@" . $notifications_obj->user_email3 . $timestamp);}
				
				if ($notifications_obj->user_email4)
				{$tw->setStatus("@" . $notifications_obj->user_email4 . $timestamp) ;}
			
					
				  echo "Finished posting to twitter<br/>";
				
				break;
				
				 case "msg" :
				//todo
				break;
			   default :
				
				break;
		}
	
	
	}
	
}


  /**
* Uses stat objec to check if the milestone has met the required number of likes.
*
* @returns Boolean - True if the milstone is OK, false if not
*/

function checkMilestone($milestone,$number_of_times,$promoid)
{
global $stats_obj;

	$stats_obj=new fbmo_stats($promoid);
	$stats_obj->get_totals();
	
		switch (strtolower(trim($milestone))){
			case "participants" :
			
			      if ($stats_obj->total_participants >= $number_of_times)
			      {
				  echo "You have reached total number of partcipants <br/>";
				  return true;
				  }
				break;
		    case "likes" :
				if ($stats_obj->total_likes >= $number_of_times)
			    {
				  echo "You have reached total number of likes <br/>";
				return true;
				
				}
				
				break;
				
		   case "shares" :
				
				if ($stats_obj->total_shares >= $number_of_times)
			    {
				  echo "You have reached total number of shares <br/>";
				return true;
				}
				break;
				
				 case "invites" :
				if ($stats_obj->total_invites >= $number_of_times)
			     {
				   echo "You have reached total number of invites <br/>";
				 return true;
				 }
				break;
				
				 case "referal" :
				if ($stats_obj->highest_points >= $number_of_times)
			    {
				  echo "You have reached total number of refereals <br/>"; 
				return true;
				}
				break;
				
				 case "highest" :
				//todo
				if ($stats_obj->highest_points >= $number_of_times)
			    {
				  echo "You have reached highest score <br/>";
				return true;
				}
				break;
			default :
			return false;		
				break;
		}

		return false;



}




?>