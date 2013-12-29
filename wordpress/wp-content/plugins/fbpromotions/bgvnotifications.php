<?php
# handles notifciations for app

class bgv_notifications  {

var $notice_id = 0;
var $promo_id = 0;
var $promo_name = '';
var $channel = '';
var $milestone = '';
var $time = '';
var $message = '';
var $notification_emails = '';
var $user_email = '';
var $user_email2 = '';
var $user_email3 = '';
var $user_email4 = '';


  /**
* Constructor for noticiation object takes a promoID
*
* @returns String the string representation of the error
*/

function bgv_notifications($promo_id)	{
	
$this->promo_id = $promo_id;

	}
	


  /**
* Gets a lost of all the notifications for the promo
*
* @returns ResultSet Results set
*/
function getNotifications()
{
//weird scoping will show up locally in notification list 
global $wpdb,$notification_list;
 $notification_list=$wpdb->get_results(
        "SELECT * from bgv_notification where promo_id = $this->promo_id order by notice_id" );
return $notification_list;

}


  /**
* Notification info for a particulr row of the Notice ID you pass  in
*
* @returns Row row with notification info
*/
function getNotificationInfo($notice_id)
{

global $wpdb;

$this->notice_id = $notice_id;

$results=$wpdb->get_row(
        "SELECT * from bgv_notification where notice_id = $notice_id", ARRAY_A);
		
return $results;

}

  /**
* Sets emails for easy access

*/

function getNotificationEmails($noticeid)
{

global $wpdb;

 $email_list= $wpdb->get_row("SELECT * from bgv_notify_users where notice_id = $noticeid", ARRAY_A);

$this->user_email = $email_list['user_email'];
$this->user_email2 = $email_list['user_email2'];
$this->user_email3 = $email_list['user_email3'];
$this->user_email4 = $email_list['user_email4'];

}
function getNotificationStatus($noticeid)
{

global $wpdb;
 $email_list= $wpdb->get_row("SELECT count(*) as c from bgv_notification_mail where notice_id = $noticeid", ARRAY_A);
 if($email_list['c']>0)
 return "Sent";
 else
 return "Not sent";

}




function saveNotificationEmails($notice_id="",$data,$format="")
{

      global $wpdb;
	  if(is_array($data)){
			foreach($data as $fld=>$val)	{
				$data[$fld]=stripslashes($val);
			}
			  $user_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM bgv_notify_users where notice_id=".$data['notice_id']));
			  if($user_count==0){
				 $wpdb->insert( "bgv_notify_users", $data, $format  );
		   }
			else
			{			 if(!empty($data["user_email"] ) ||!empty($data["user_email1"] )  )
	
				$wpdb->update("bgv_notify_users", $data,array('notice_id'=>$data['notice_id']),$format,'%d');
			}
	  }
}



#function to return the promo name
function getPromoName()
{

global $wpdb;


$results=$wpdb->get_row(
        "SELECT * from fb_promotions where promo_id = $this->promo_id", ARRAY_A);
		
		
return $results["promo_name"];;


}

#save/update Notification

function saveNotification($notice_id="",$data,$format="")
{
global $wpdb;

		foreach($data as $fld=>$val)	{
			$data[$fld]=stripslashes($val);
		}
		if (empty($notice_id))	{
		$wpdb->insert( "bgv_notification", $data, $format  );
		}
		else
		{
		$wpdb->update("bgv_notification", $data,array('notice_id'=>$notice_id),$format,'%d');
		
		}
		
		if (empty($notice_id))	{
			$notice_id = $wpdb->insert_id;
		}
		for($k=0;$k<count($_POST["i"]);$k++){
			$inc = $_POST["i"][$k];
	        $time_var =  "promo_start".$inc;
			$time_val = $_POST[$time_var];
			$notice_id = $_POST['notice_id'][$k];
			if (empty($notice_id))	{
				$notice_id = $wpdb->insert_id;
			}
	        $wpdb->query("UPDATE bgv_notification SET time = '".$time_val."'  where	notice_id	=$notice_id ");
		}
        $this->setCron($notice_id,$data['time']);
}
//function to run cron march 8
function setCron($notice_id,$rec){
    $argArr = array();
	$argArr["notification_id"] = $notice_id;
	$hook = 'my_new_event_for_notification' . $notice_id;
	add_action( $hook, 'my_scheduled_function', 10, 1 );
    //cases to be run according to time periods.
	switch( $rec ){
		case "hourly":
			add_action($hook, 'my_scheduled_function', 10, 1);
			wp_schedule_event(time(), 'hourly', $hook, $argArr);
			break;
		case "daily":
			add_action($hook, 'my_scheduled_function', 10, 1);
			wp_schedule_event(time(), 'daily', $hook, $argArr);
			break;
		case "twicedaily":
			add_action($hook, 'my_scheduled_function', 10, 1);
			wp_schedule_event(time(), 'twicedaily', $hook, $argArr);
			break;
		default:
			add_action($hook, 'my_scheduled_function', 10, 1);
			wp_schedule_event(time(), 'daily', $hook, $argArr);
			break;
	}
}
//scheduled function for each task.
function my_scheduled_function( $notificationId )
{
	global $wpdb;
	$res = $this->getNotificationInfo($notificationId);
    $this->getNotificationEmails($notificationId);
	$this->getNotificationStatus($notificationId);
	$promo_res = $wpdb->get_row(
			"SELECT promo_name FROM fb_promotions
			  WHERE promo_id='".$res['promo_id']."'",
			ARRAY_A);
	$promo_name = $promo_res['promo_name'];
	$msg = "<p>Hello,</p>";
	$msg.="<p>This is an automated email for the listed promo - ".$promo_name."</p>";
	$msg_end  = " <p>Thanks </p> ";
    $msg_end.= "<p>Support Team</p> ";
	$msg_met =    $msg.$res['message'].$msg_end;
	$msg_not_met =    $msg.$res['message_if_milestone_not_met'].$msg_end;
	$subject = "Automatic notification Email for Promo-".$promo_name;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	$headers .= 'From: Support Team <support@bugsgoviral.com>' . "\r\n";
		$time= time()-(5*3600);
		$mdate = (date("Y-m-d h:i A",$time));
		$d2 = (date("Y-m-d h:i A",strtotime($res['time'])));
		$d1 =  new DateTime($mdate);
		$d2 = new DateTime($d2);
		if($d1>=$d2 && !empty($res['time'])){
			$mail_sent=1;
		}else
		    $mail_sent=0;
    		$already_sent = $this->checkNumberOfTimes($res['notice_id']);
	   if($mail_sent==1 && $already_sent==0){
		if (!$this->checkMilestone($res['milestone'],$res['notification_time'],$res['promo_id']))
		{
						 if ( strtolower(trim($res['channel'])) == 'email' )
						{
							 if($this->user_email || $this->user_email2){
								
									if ($this->user_email)
									{
										mail($this->user_email, $subject, $msg_not_met,$headers );
									}
									if ($this->user_email2)
									{
										mail($this->user_email2, $subject, $msg_not_met,$headers);
									}
									$this->updateMailSent($res['notice_id']);
								 
							}
						}
		}else{
			
						 if ( strtolower(trim($res['channel'])) == 'email' )
						{
							 if($this->user_email || $this->user_email2){
									if ($this->user_email)
									{
										mail($this->user_email, $subject, $msg_met,$headers);
									}
									if ($this->user_email2)
									{
										mail($this->user_email2, $subject, $msg_met,$headers);
									}
									$this->updateMailSent($res['notice_id']);
								}
							}
				}
	}
}
function updateMailSent($notice_id){
			global $wpdb;
			$date = date("Y-m-d H:i:s");
			$current_date = date("Y-m-d");
			$res = $wpdb->get_row(
			"SELECT count(*) as c  FROM bgv_notification_mail
			  WHERE notice_id=$notice_id ",
			ARRAY_A);
		   if($count==0){
			   $wpdb->query("INSERT INTO bgv_notification_mail SET notice_id=$notice_id,`date`='$current_date',`date_time`='$date',number_times=1");
		   }
			
}
function checkNumberOfTimes( $notice_id, $times='' ){
		global $wpdb;
		$current_date = date("Y-m-d");
		$res = $wpdb->get_row(
			"SELECT number_times as c, date_time as time  FROM bgv_notification_mail
			  WHERE notice_id=$notice_id",
			ARRAY_A);
		if($res)
		return $count = $res['c'];
		else
		return 0;
		$date1 = date("Y-m-d H:i:s",strtotime($res['time']));
		$date2 = date("Y-m-d H:i:s");
		$diff = abs(strtotime($date2) - strtotime($date1)); 
		$years   = floor($diff / (365*60*60*24)); 
		$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
		$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
	    switch ($times){
			case "daily" :
			     if($count<1 && $hours>=1){
					 return 1;
				 }
				 else
				 return 0;
			break;
			case "twicedaily" :
                 if($count <2  && $hours>=1){
					 return 1;
				 }else
					 return 0;
			break;
			case "hourly" :
          	 if($count <24  && $hours>=1){
				 return 1;
			}else
				 return 0;
		   	break;
			default:
			   return 0;
	}
}
function get_total_entries_stats(){
		 global $wpdb;
		# total participants
	$this->total_participants=$wpdb->get_var($wpdb->prepare(
		    "SELECT count(*) from fb_entries
        		WHERE promo_id=$this->promo_id"));
		# points and counts
		$ptrow=$wpdb->get_row(
		    "SELECT sum(points) as points,count(*) as count
		    	from fb_social
        		WHERE promo_id=$this->promo_id");
        if ($ptrow)	{
			$this->total_points=$ptrow->points;
			$this->total_count=$ptrow->count;
		    $prtrow=$wpdb->get_row(
		    "SELECT sum(points) as points,count(*) as count
		    	from fb_social
        		WHERE promo_id=$this->promo_id and type='ref'");
			if ($prtrow)	{
				$this->total_click_points=$prtrow->points;
				$this->total_clicks=$prtrow->count;
			}
		    $prtrow=$wpdb->get_row(
		    "SELECT sum(points) as points,count(*) as count
		    	from fb_social
        		WHERE promo_id=$this->promo_id and type='share'");
			$this->total_share_points=$prtrow->points;
			$this->total_shares=$prtrow->count;
			# by stream type
		    $this->stat_types=$wpdb->get_results(
		    "SELECT count(*) as count,stream_type,type
		    	from fb_social
        		WHERE promo_id=$this->promo_id
        			GROUP BY `stream_type`,`type`");
        }
        #likes-- get likes for promo page
        ##### HAL says "I can't do this"
        #total votes
		$this->total_votes=$wpdb->get_var($wpdb->prepare(
		    "SELECT count(*) from fb_votes
        		WHERE promo_id=$this->promo_id"));
        #highest score
		$this->highest_score=$wpdb->get_var($wpdb->prepare(
		    "SELECT max(points) as points from fb_entries
        		WHERE promo_id=$this->promo_id"));
    
}
function checkMilestone($milestone,$number_of_times,$promoid)
{
			 global $wpdb;

		$this->promo_id = $promoid;
	    $this->get_total_entries_stats();
		$totPurchasedSoFarArr=$wpdb->get_row(
					"SELECT sum( promo_units_purchased) as total from fb_entries
					WHERE promo_id=$promoid",
					ARRAY_A);
		$tippingPoint = $totPurchasedSoFarArr['total'];
		switch (strtolower(trim($milestone))){
			case "participants" :
			      if ($this->total_participants >= $number_of_times)
			      {
				  //echo "You have reached total number of partcipants <br/>";
				  return true;
				  }
				break;
			case "tipingpoint" :
			      if ($tippingPoint  >= $number_of_times)
			      {
				  //echo $number_of_times." tipping point reached for promo. <br/>";
				  return true;
				  }
				break;
		    case "likes" :
				if ($this->total_likes >= $number_of_times)
			    {
				//echo "You have reached total number of likes <br/>";
				return true;
				}
				break;
		   case "shares" :
				if ($this->total_shares >= $number_of_times)
			    {
				  echo "You have reached total number of shares <br/>";
				return true;
				}
				break;
				case "invites" :
				if ($this->total_invites >= $number_of_times)
			     {
				 //  echo "You have reached total number of invites <br/>";
				 return true;
				 }
				break;
				case "referal" :
				if ($this->highest_points >= $number_of_times)
			    {
				 // echo "You have reached total number of refereals <br/>"; 
				return true;
				}
				break;
				case "highest" :
				if ($this->highest_points >= $number_of_times)
			    {
				 // echo "You have reached highest score <br/>";
				  return true;
				}
				
			 break;
			default :
			return false;		
			break;
		}
		return false;
}

	function getnotificationfields($table)	{
		global $wpdb;
		$data=array();
		$promo_fields=$wpdb->get_results(
			"SHOW COLUMNS FROM $table" , ARRAY_A);
        foreach ($promo_fields as $row)   {
        	if (stristr($row["Type"],"varchar") or
				stristr($row["Type"],"date") or
        		stristr($row["Type"],"text")) $format="%s";
        	else  $format="%d";
            $data[$row["Field"]]=$format;
		}
		return $data;
	}
//function to enable/disable notification module
function update_notification_module_setting( $value=0)
{
	global $wpdb;
	$wpdb->query( "UPDATE bgv_notification_settings 
							SET bgv_notification_module_enable =
							 '$value'");
}
//function to get enabled/disabled value for notification  module
function get_enable_module_notification_value( ){
	global $wpdb;
	$enable_value = 0;
	$entry_row=$wpdb->get_row(
				"SELECT bgv_notification_module_enable FROM 
				bgv_notification_settings",
				ARRAY_A);
   if ($entry_row)
   		$enable_value = $entry_row["bgv_notification_module_enable"];
	return $enable_value;
}
//function to delete notifications
function delete_notifications( $notice_id = '' )
{
	global $wpdb;
	$wpdb->query("
							DELETE a,c
							FROM
								 bgv_notification AS a
							     JOIN bgv_notify_users AS c ON a.notice_id = c.notice_id
							WHERE (
								a.notice_id = '$notice_id'
								)
							");
	return;
}
function notification_selected($field,$val,$default="")	{
	if (empty($field)and
		!empty($default)) echo 'selected="selected"';
	elseif ($field==$val) echo 'selected="selected"';
}
} #end of  class

?>