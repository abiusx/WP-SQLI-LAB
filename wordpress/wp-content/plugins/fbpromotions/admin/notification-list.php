<?php
global $promo_image_url;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>
<link rel="stylesheet" type="text/css" href="<?php  echo $promo_date_cal?>/calendar-blue.css">
<script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar.js"></script>
<script type="text/javascript" src="<?php  echo $promo_date_cal?>/lang/calendar-en.js"></script>
<script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar-setup.js"></script>
<SCRIPT LANGUAGE="JavaScript">
function addRow(id){
    var tbody = document.getElementById(id).getElementsByTagName("TBODY")[0];
	var oRows = document.getElementById('myTable').getElementsByTagName('tr');
	var iRowCount = oRows.length;
    var row = document.createElement("TR")
    var td1 = document.createElement("TD")
	var checkbox = document.createElement('input');
	checkbox.type = "checkbox";
	checkbox.name = "ids[]";
	checkbox.value = "";
	checkbox.id = "id";
    //column2
	var td2 = document.createElement("TD")
	var input2 = document.createElement('input');
	input2.type = 'hidden';
	input2.value = '';
	input2.name = 'notice_id[]';
	td2.appendChild (input2);
	var td2 = document.createElement("TD")
	var input2 = document.createElement('input');
	input2.type = 'hidden';
	input2.value = iRowCount;
	input2.name = 'i[]';
	td2.appendChild (input2);
	var input2 = document.createElement('input');
	input2.type = 'text';
	input2.value = '';
	input2.name = 'notification_time[]';
	input2.size = '5';
    td2.appendChild (input2);
	var select2= document.createElement("select");
	select2.name = "milestone[]";
	select2.options[0] = new Option("Participants","participants");
	select2.options[1] = new Option("Likes","Likes");
	select2.options[2] = new Option("Shares","shares");
	select2.options[3] = new Option("Invites","invites");
	select2.options[4] = new Option("Referral Points","referral");
	select2.options[5] = new Option("Highest Score","highest");
	select2.options[6] = new Option("Tipping Point Reached","tippingpoint");
    td2.appendChild (select2);
	
	//column3
	var td3 = document.createElement("TD")
    var input2 = document.createElement('input');
	input2.type = 'text';
	input2.value = '';
	input2.name = 'promo_start'+iRowCount;
	input2.id = 'promo_start'+iRowCount;
    td3.appendChild (input2);
	var ss = document.createElement('script');
	var scr = 'Calendar.setup({ inputField : "promo_start' + iRowCount + '", ifFormat : "%Y-%m-%d %I:%M %p", showTime : 12, align : "Tl", singleClick    :    true, showsTime		:	true });';
	var tt = document.createTextNode(scr);
	ss.appendChild(tt);
	var hh = document.getElementsByTagName('head')[0];
	td3.appendChild(ss);
	var td4= document.createElement("TD")
	var t4 = document.createElement("textarea");
	t4.name = "message[]";
    td4.appendChild (t4)
	var lf=document.createElement('br')
    td4.appendChild (lf)
	//message2
	var t42 = document.createElement("textarea");
	t42.name = "message_if_milestone_not_met[]";
    td4.appendChild (t42)
    //column5
	var td5= document.createElement("TD")
	td5.setAttribute("align","left");
    var select2= document.createElement("select");
	select2.name = "channel[]";
	select2.onchange= function(){displayBlock(this.value,'newdiv'+iRowCount);}; 
	select2.options[0] = new Option("Email","email");
	select2.options[1] = new Option("FB Post","post");
	select2.options[2] = new Option("Twitter","twitter");
    td5.appendChild (select2);
	var newdiv = document.createElement('div');
	newdiv.setAttribute('id','newdiv'+iRowCount);
	newdiv.innerHTML = '<input type="text" name="user_email[]"><br><input type="text" name="user_email2[]">';
	td5.appendChild (newdiv);
	//row append
	row.appendChild(td1);
    row.appendChild(td2);
	row.appendChild(td3);
	row.appendChild(td4);
	row.appendChild(td5);
    tbody.appendChild(row);
  }
  function displayBlock(value,email1){
	  if(value == 'email')
	  { 
	  document.getElementById(email1).style.display='block';
	  }
	  else{
       document.getElementById(email1).style.display='none';
	  }
}
</script>
<div class="wrap">
<div id="form1">
<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="10">
				<tr>
					<td colspan="2">
							<img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" >
					</td>
				</tr>
				<tr>
					<td colspan="2" class="pagetitle">
						STEP  NOTIFICATIONS
					</td>
				</tr>
                <tr>
					<td colspan="2">
						    <p class="submit_button">
                            <input name="submit" value="Add New Notification" type="submit"  onclick="javascript:addRow('myTable');"/>
                            </p>
					</td>
				</tr>
                <tr>
                <td colspan="2">
                <form id="notificationform" name="notificationform" method="post">
<table class="overview" id="myTable">

<tr>
<td>Remove</td>
<td style="background-color:#FFFFFF">Goal
&nbsp;<a  data-tooltip="sticky1"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
</td>
<td style="background-color:#FFFFFF">
Deadline
&nbsp;<a  data-tooltip="sticky2"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
</td>
<td style="background-color:#FFFFFF">Messages
&nbsp;<a  data-tooltip="sticky3"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
</td>
<td style="background-color:#FFFFFF" >Channels
&nbsp;<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a></td>
<td style="background-color:#FFFFFF" >Notification Status</td>
</tr>
<div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">
                            <div id="sticky1" class="atip" style="width:300px">
								Specify a goal for number of participants or other given milestones (e.g.-100 participants). 
                            </div>
                            <div id="sticky2" class="atip" style="width:300px">
                                Specify the time increment at which the server should check to see if your goal has been met.     
                                </div>
                            <div id="sticky3" class="atip" style="width:300px">
                               In the first box, supply the message to send if your goal is met. In the second box, supply the message to send if your goal is not met.	    			
                                </div>
                          
                            <div id="sticky4" class="atip" style="width:300px">
								Specify the channel for this notification. 
                                </div>
                           
                            
                            
                     </div>
					<div class="stickystatus"></div>
                    </div>
<?php
//echo $notification_list ;
$i=1;
foreach ($notification_list as $notification_row)	{
	
	$notifications_obj->getNotificationEmails($notification_row->notice_id);
?>
<input name="i[]"  type="hidden" value="<?php echo $i?>" />
<tr>
<td><input type="checkbox"  name="del_ids[]" value="<?php echo($notification_row->notice_id); ?>"/></td>
<td>
<input type="hidden" name="notice_id[]"  value="<?php echo($notification_row->notice_id); ?>">
<input name="notification_time[]" type="text" id="notification_time" size="5" value="<?php echo $notification_row->notification_time; ?>" />
<select name="milestone[]" id = "milestone" >
				<option value="participants" <?php $notifications_obj->notification_selected($notification_row->milestone,"participants","default") ?>>Participants</option>
				<option value="likes" <?php $notifications_obj->notification_selected($notification_row->milestone,"likes") ?>>Likes</option>
				<option value="shares" <?php $notifications_obj->notification_selected($notification_row->milestone,"shares") ?>>Shares</option>
				<option value="invites" <?php $notifications_obj->notification_selected($notification_row->milestone,"invites") ?>>Invites</option>
				<option value="referral" <?php $notifications_obj->notification_selected($notification_row->milestone,"referral") ?>>Referral Points</option>
			    <option value="highest" <?php $notifications_obj->notification_selected($notification_row->milestone,"highest") ?>>Highest Score</option>
			    <option value="tipingpoint" <?php $notifications_obj->notification_selected($notification_row->milestone,"tipingpoint") ?>>Tipping Point Reached</option>
</select>

</td>
<td>
<input name="promo_start<?php echo $i;?>" type="text" id="promo_start<?php echo $i;?>" size="25" value="<?php echo $notification_row->time?>">
	  <script type="text/javascript">
	  	Calendar.setup({
        inputField     :    "promo_start<?php echo $i;?>",     
	  	ifFormat       :    "%Y-%m-%d %I:%M %p",     
		showTime      : 12,
        align          :    "Tl",           
        singleClick    :    true,
		showsTime		:	true
    });
      </script>
</td>
<td >
<textarea name="message[]" /><?php echo $notification_row->message;?></textarea>
<br />
<textarea name="message_if_milestone_not_met[]" /><?php echo $notification_row->message_if_milestone_not_met;?></textarea>

</td>
<td  align="left">
        <select name="channel[]" id = "channel[]"  onchange="displayBlock(this.value,'email<?php echo $notification_row->notice_id?>');">
                        <option value="email" <?php $notifications_obj->notification_selected($notification_row->channel,"email","default") ?>>Email</option>
<!--                        <option value="post" <?php $notifications_obj->notification_selected($notification_row->channel,"post") ?>>FB Post</option>
                        <option value="twitter" <?php $notifications_obj->notification_selected($notification_row->channel,"twitter") ?>>Twitter</option>
-->        </select>
        <div id="email<?php echo $notification_row->notice_id?>" 
        <?php if($notification_row->channel!="email"){?> style="display:none;"<?php }?>>
        <input type="text" name="user_email[]" value="<?php echo $notifications_obj->user_email; ?>" /><br />
        <input type="text" name="user_email2[]" value="<?php echo $notifications_obj->user_email2; ?>" />
        </div>
 </td>
 <td>
 <?php echo $notifications_obj->getNotificationStatus($notification_row->notice_id);?>
 </td>
</tr>
<?php $i++;
} //end for each?>
          </table>   
<?php
//if($notification_list){
?>
   <table width="100%">
        <tr>
            <td colspan="2" bgcolor="#CCCCCC" class="pagetitle">
            <input type="button" name="Back" value="Back" onclick="window.history.back()" /> 
            <input type="hidden" name="fb_notify_action" value="save">
            <input type="hidden" name="fb_edit_action" value="save">
             <input type="hidden" name="fbm_next_step" value="notification" />
            <input type="hidden" name="page" value="fbm_promo_notification">
            <input type="hidden" name="promo_id"  value="<?php echo($_REQUEST["promo_id"]); ?>">
            <input name="submit" value="Save" type="submit" />
            <input type="submit" name="Submit" value="Finish &raquo;" onclick="document.notificationform.page.value='fbm_edit_promo';document.notificationform.fbm_next_step.value=99;" />

        </td>
</tr>
    </table>
<?php
//}
  ?>
</form>
               </td>
               </tr>
    </table>

  	<?php do_html_admin_footer("admin_b_hive", "The B(gv) Hive") ?>
</div>
</div>
