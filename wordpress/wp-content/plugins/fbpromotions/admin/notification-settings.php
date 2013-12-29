<?php
global $promo_image_url,$notification;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
 global $notification_list, $notifications_obj;                           
include( FBPROMODIR . '/bgvnotifications.php' );
$notifications_obj=new bgv_notifications($_REQUEST['promo_id']);
if (isset($_REQUEST['fb_edit_action']))	{
switch ($_REQUEST['fb_edit_action'])	{
case "save":
         $notifications_obj=new bgv_notifications($_REQUEST['promo_id']);
		  $flds=$notifications_obj->getnotificationfields("bgv_notification");
		  save_notification_flds($flds); 
		  $flds=$notifications_obj->getnotificationfields("bgv_notify_users");
		  save_notification_email_flds($flds); 
          $notification_list = $notifications_obj->getNotifications();  
		  include_once ( dirname (__FILE__) . '/notification-list.php' );

break;
case "delete":
			 $notifications_obj=new bgv_notifications($_REQUEST['promo_id']);
		 	 $flds=$notifications_obj->delete_notifications( $_REQUEST['notice_id'] );
			 $notification_list = $notifications_obj->getNotifications();  
             include_once ( dirname (__FILE__) . '/notification-list.php' );
		  
break;
/*case "add":
          $notifications_obj=new bgv_notifications($_REQUEST['promo_id']);
          $notification =  $notifications_obj->getNotificationInfo($_REQUEST['notice_id']);
		  include_once(dirname (__FILE__) . '/notification-addedit.php' );
		  
break;
*/}


}

   //make sure everything is cleaned up
		  unset( $notification_list);
		  unset( $notifications_obj);


function save_notification_flds($flds)	{
  global $notifications_obj;
   //check notice_ids for delete
   //print_r($_POST);
   
   if(isset($_REQUEST['del_ids'])){
	  for($i=0;$i<count($_REQUEST['del_ids']);$i++){
		  $notifications_obj->delete_notifications($_REQUEST['del_ids'][$i]);
	  }
   }
	if(is_array($_REQUEST['milestone'])){
		for($i=0;$i<count($_REQUEST['milestone']);$i++){
			foreach($flds as $name=>$val)	{
				if(is_array($_REQUEST[$name])){
					if (!empty($_REQUEST[$name][$i]))	{
						$data[$name]=$_REQUEST[$name][$i];
						$format[]=$val;
					}else{
						$data[$name]=$_REQUEST[$name][$i];
						$format[]=$val;
					}
				}
				else{
					if (isset($_REQUEST[$name]))	{
						$data[$name]=$_REQUEST[$name];
						$format[]=$val;
					}
				}
			}
			if (empty($data["notice_id"])) 				
			{
				$notifications_obj->saveNotification("",$data,$format);
			} 
			else	
			{
				$notifications_obj->saveNotification($data["notice_id"],$data,$format);
			}
		}
	}
	else{
		foreach($flds as $name=>$val)	{
			if (isset($_REQUEST[$name]))	{
				$data[$name]=$_REQUEST[$name];
				$format[]=$val;
			}
		}
		if (!isset($_REQUEST["notice_id"])) 				
		{
			$notifications_obj->saveNotification("",$data,$format);
		} else	{
			$notifications_obj->saveNotification($_REQUEST["notice_id"],$data,$format);
		}
		
	}
}

function save_notification_email_flds($flds)	{
	global $notifications_obj,$wpdb;
    if(is_array($_REQUEST['milestone'])){
		for($i=0;$i<count($_REQUEST['milestone']);$i++){
			foreach($flds as $name=>$val)	{
				if(is_array($_REQUEST[$name])){
					if (!empty($_REQUEST[$name][$i]))	{
						$data[$name]=$_REQUEST[$name][$i];
						$format[]=$val;
					}else{
						$data[$name]=$_REQUEST[$name][$i];
						$format[]=$val;
					}
				}
				else{
					if (isset($_REQUEST[$name]))	{
						$data[$name]=$_REQUEST[$name];
						$format[]=$val;
					}
				}
			}
			if (empty($data["notice_id"])) 				
			{
				$data["notice_id"] = $wpdb->insert_id;
				$notifications_obj->saveNotificationEmails($data["notice_id"],$data,$format);
			} 
			else	 
			{
			      
						$notifications_obj->saveNotificationEmails($data["notice_id"],$data,$format);
					
				}
	 		
		}
	}
	else{
	foreach($flds as $name=>$val)	{
		if (isset($_REQUEST[$name]))	{
			$data[$name]=$_REQUEST[$name];
			$format[]=$val;
		}
	}
	
	if (!isset($_REQUEST["notice_id"])) 				{
	$notifications_obj->saveNotificationEmails("",$data,$format);
	} else	{
	$notifications_obj->saveNotificationEmails($_REQUEST["notice_id"],$data,$format);
	}
	}
}

//edit fn 
function notification_selected($field,$val,$default="")	{
	if (empty($field)and
		!empty($default)) echo 'selected="selected"';
	elseif ($field==$val) echo 'selected="selected"';
}
?>
