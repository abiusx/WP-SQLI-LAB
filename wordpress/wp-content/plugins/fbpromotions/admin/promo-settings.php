<?php
global $promo_image_url,$promo_row;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
$promo_date_cal=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/jscal";
############################
##  edit action switch
############################
if (isset($_REQUEST['fb_edit_action']))	{

	switch ($_REQUEST['fb_edit_action'])	{
 			case "delete":
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				# draw a confirmation screen
				include_once(dirname (__FILE__) . '/promo-delete.php' );
				return;
				break;
 			case "delete_step_2":
				$fbmo->delete_promo_info($_REQUEST["promo_id"]);
    			global $promo_list;
				$fbmo->get_promo_list();
				if ($promo_list)	include_once ( dirname (__FILE__) . '/promo-list.php' );
				return;
				break;
			case "save_step_1":
				$flds=$fbmo->fbpromofields("fb_promotions");
				fbmo_save_promo_flds($flds);
				break;
			case "save_sweepstakes_1":
				$flds=$fbmo->fbpromofields("fb_promotions");
				fbmo_save_promo_flds($flds);
				break;
			case "save_visuals":
				fbmo_save_visual_flds();
				$flds=$fbmo->fbpromofields("fb_promotions");
				fbmo_save_promo_flds($flds);
				break;
			case "enter_winners":
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				$fbmo->get_promo_entries($_REQUEST["promo_id"]);
				
				$res = $fbmo->get_promo_analytics_dashboard($_REQUEST["promo_id"]);
				$res_top_inf = $fbmo->get_top_influencers_dashboard($_REQUEST["promo_id"]);
				include_once ( dirname (__FILE__) . '/enter_winners.php' );
                return;
                break;
			case "generate_winner":
			   if($_POST['submit']=="Generate Winner")
			    $fbmo->generate_sweeps_winner($_REQUEST["promo_id"]);
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				$fbmo->get_promo_entries($_REQUEST["promo_id"]);
				$res = $fbmo->get_promo_analytics_dashboard($_REQUEST["promo_id"]);
				$res_top_inf = $fbmo->get_top_influencers_dashboard($_REQUEST["promo_id"]);
				include_once ( dirname (__FILE__) . '/generate_winner.php' );
                return;
                break;
		
		case "view_buzz_winner":
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				$fbmo->get_promo_entries($_REQUEST["promo_id"]);
				include_once ( dirname (__FILE__) . '/view_winner.php' );
                return;
                break;
		case "send_email_media_entry":
		$fbmo->get_promo_info($_REQUEST["promo_id"]);
		$promo_row=$fbmo->promo_row;
		$fbmo->get_promo_entries($_REQUEST["promo_id"]);
		$media_items=$fbmo->get_promo_media($_REQUEST["promo_id"]);
		$fbmo->send_email_media_entries();
		include_once ( dirname (__FILE__) . '/view_media_entry.php' );
		return;
		break;
		case "view_media_entry":
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				$fbmo->get_promo_entries($_REQUEST["promo_id"]);
				$media_items=$fbmo->get_promo_media($_REQUEST["promo_id"]);
				include_once ( dirname (__FILE__) . '/view_media_entry.php' );
                return;
                break;
			case "delete_media_entry":
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				$fbmo->get_promo_entries($_REQUEST["promo_id"]);
				$media_items=$fbmo->delete_media_rec($_REQUEST["media_id"]);
				$media_items=$fbmo->get_promo_media($_REQUEST["promo_id"]);
				include_once ( dirname (__FILE__) . '/view_media_entry.php' );
                return;
                break;
			case "save_entries":
				$fbmo->get_promo_info($_REQUEST["promo_id"]);
				$promo_row=$fbmo->promo_row;
				$fbmo->get_promo_entries($_REQUEST["promo_id"]);
				$res = $fbmo->get_promo_analytics_dashboard($_REQUEST["promo_id"]);
				$res_top_inf = $fbmo->get_top_influencers_dashboard($_REQUEST["promo_id"]);
				$fbmo->save_reedemed_units();
				include_once ( dirname (__FILE__) . '/enter_winners.php' );
                return;
                break;
			case "save_winners":
				$flds=$fbmo->fbpromofields("fb_promotions");
				fbmo_save_promo_flds($flds);
                break;
			case "delete_media":
				$fbmo->delete_media_rec($_REQUEST["media_id"]);
                break;
			case "save_swarm_1":
				$flds=$fbmo->fbpromofields("fb_promotions");
				fbmo_save_promo_flds($flds);
				break;
			case "save_notification":
			  include_once ( dirname (__FILE__) . '/notification-settings.php' );
			  $notifications_obj=new bgv_notifications($_REQUEST['promo_id']);
			  $flds=$notifications_obj->getnotificationfields("bgv_notification");
			  save_notification_flds($flds); 
			  $flds=$notifications_obj->getnotificationfields("bgv_notify_users");
			  save_notification_email_flds($flds); 
			  $notification_list = $notifications_obj->getNotifications();  
			break;
			
			default:
                # invalid action code
                # finish this
                #die("invalid action code");
	} // end of switch
}

############################
# check which step we're on and promo type then retreive appropriate screen.
if (! isset($_REQUEST["fbm_next_step"]))	{
	# starting out with step 1
	include_once(dirname (__FILE__) . '/promo-settings-1.php' );
	return;
} else	{
	if ($_REQUEST["fbm_next_step"]=="visuals")	{
		include_once(dirname (__FILE__) . '/visual-settings.php' );
		return;
	}
	if ($_REQUEST["fbm_next_step"]=="notification")	{
		global $notification_list, $notifications_obj;    
		if($_REQUEST['fb_edit_action']=="save_visuals")                       
        include( FBPROMODIR . '/bgvnotifications.php' );
		$notifications_obj=new bgv_notifications($_REQUEST['promo_id']);
		$notification_list = $notifications_obj->getNotifications();
		include_once(dirname (__FILE__) . '/notification-list.php' );
		return;
	}
	if ($_REQUEST["fbm_next_step"]=="99")	{
		# done, draw promo_list
		global $promo_list;
		$fbmo->get_promo_list();
		if ($promo_list)	include_once ( dirname (__FILE__) . '/promo-list.php' );
		return;
	}


}
# catch the rest of the possibilities
	switch ($_REQUEST['promo_type'])	{
  			case "1" :
                # sweepstakes
				include_once(dirname (__FILE__) . '/sweepstake-settings.php' );
                break;
			case "2":
                # the buzz
				include_once(dirname (__FILE__) . '/buzz-settings.php' );
                break;
            case "3":
                # fan faves
                fbmo_fanfaves_setup();
                break;
            case "4":
                # talk back
                fbmo_talkback_setup();
                break;
            case "5":
                # buy it
				include_once(dirname (__FILE__) . '/buyit-settings.php' );
                break;
            case "6":
                # check it
                fbmo_checkit_setup();
                break;
            case "7":
                # elite
                fbmo_elite_setup();
                break;
		   case "8":
                # swarm
			 
               include_once(dirname (__FILE__) . '/swarm-settings.php' );
                break;
			default:
	               fbmo_sweepstakes_setup();
}
function fbmo_save_visual_flds()		{
	# check for uploaded graphics
	# TODO: cull images not used now and rename these in code to rep PSD layers - CVHJ 2/23
	$uploads=array(
	"img_profiletab_promobanner",
	"img_profiletab_requirelike",
	// "footer_image",
	"img_canvas_enter_sweepstakes_520x560",
	 "img_canvas_gift_swarm_520x560",
	"img_canvas_browse_buzz_520x560",
	"img_canvas_enter_swarm_520x560",
	"img_canvas_alreadyentered_sweepstakes_520x560",
	"img_canvas_closed_sweepstakes_520x560",
	"img_canvas_rulesregs_sweepstakes_520x560",
	"img_canvas_blank_sweepstakes_520x560",
	"img_canvas_dialogbg_sweepstakes_10x10",
	"img_streampublish_90x90",
	"img_sponsor_graphic",
	"promo_beneficiary_logo",
	// "button_image",
	// "img_streampublish_90x90",
	"layout_logo",
	"bkg_image",
	"profile_image",
	"publish_image",
	"sponsor_logo"
	);
	$directory= ABSPATH . 'facebookapp/uploads/layout/';
    foreach ($uploads as $ufld)        {
            if (empty($_FILES[$ufld]["name"]))	continue;
            $file_name=fbmo_upload_file($ufld,$directory,512000);
           	$_REQUEST[$ufld]=$file_name;
    }
    if (isset($_REQUEST["fbm_file_remove"]))	{
		foreach ($_REQUEST["fbm_file_remove"] as $ffld)	{
				$_REQUEST[$ffld]="";
		}
	}
}
function fbmo_save_promo_flds($flds)	{
	global $fbmo;
	
	foreach($flds as $name=>$val)	{
		
		if (isset($_REQUEST[$name]))	{
			
			$data[$name]=$_REQUEST[$name];
			$format[]=$val;
		}
	}
	if (!isset($_REQUEST["promo_id"])) 				{
		$fbmo->save_promo_info("",$data,$format);
	} else	{
		$fbmo->save_promo_info($_REQUEST["promo_id"],$data,$format);
	}
}

function fbmo_talkback_setup()	{
		switch ($_REQUEST['fbm_next_step'])	{
  			case "2" :
				#include_once(dirname (__FILE__) . '/sweepstake-settings.php' );
		}
}
function fbmo_upload_file($upload_file,$directory,$size=512000)        {
	global $fbm_settings_error;
    if (! empty($_FILES[$upload_file]["error"]))	{    	$fbm_settings_error="Error In File Upload--$upload_file";
        return 0;
    }
    if (isset($_FILES[$upload_file]) and
        is_uploaded_file($_FILES[$upload_file]['tmp_name']))         {
        $file_base=basename($_FILES[$upload_file]['name']);
        if($_FILES[$upload_file]['size']==0 or
            $_FILES[$upload_file]['size']>$size)        {
            $fbm_settings_error="Upload exceeds max size--$file_base";
			return 0;
        }
        $file_name=$directory.$file_base;
        if (move_uploaded_file($_FILES[$upload_file]['tmp_name'],$file_name))  {
	          chmod($file_name,0755);
              return $file_base;
        }	else    {
            $fbm_settings_error="Error in File Upload --$file_base";
            return 0;
        }
    }   elseif (isset($_FILES[$upload_file]['name']))        {
       $file_name=basename($_FILES[$upload_file]['name']);
       $fbm_settings_error="Error in File Upload --$file_base";
       return 0;
    }
    else     return 0;
}

function fbmo_selected($field,$val,$default="")	{
	global $fbmo;
	if (empty($fbmo->promo_row[$field]) and
		!empty($default)) echo 'selected="selected"';
	elseif ($fbmo->promo_row[$field]==$val) echo 'selected="selected"';
}
function fbmo_checked($field,$val,$default="")	{
	global $fbmo;
	if (empty($fbmo->promo_row[$field]) and
		!empty($default)) echo 'checked="checked"';
	elseif ($fbmo->promo_row[$field]==$val) echo 'checked="checked"';
}
?>
