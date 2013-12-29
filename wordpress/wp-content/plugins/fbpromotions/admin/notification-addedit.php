<?php
$notifications_obj->getNotificationEmails($_REQUEST["notice_id"]) ;

?>
<div class="wrap">
<div id="form1">
<form id="notificationform" name="notificationform" method="post" />
<input type="hidden" name="fb_notify_action" value="save">
<input type="hidden" name="page" value="fbm_promo_notification">
<input type="hidden" name="fb_edit_action" value="save">
<input type="hidden" name="promo_id"  value="<?php echo($_REQUEST["promo_id"]); ?>">
<?php if(isset($_REQUEST["notice_id"])): ?>
<input type="hidden" name="notice_id" value="<?php echo($_REQUEST["notice_id"]); ?>" />
<?php endif; ?>
<table width="100%" border="0" cellpadding="10" cellspacing="0" class="tbl">
      <tr>
        <td colspan="100%"><h1><em>Bugs Go Viral &raquo; Notification settings</em></h1>
          <hr width="100%" size="1" noshade></td>
      </tr>
      <tr>
        <td colspan="100%"><img src="<?php echo($promo_image_url);?>/headergraphic.png" /></td>
      </tr>
      <tr>
        <td colspan="100%" bgcolor="#CCCCCC" class="pagetitle">Notification Settings</td>
      </tr>
  
	  
        <td>Channel &raquo;</td>

        <td>
		
			<select name="channel" id = "channel" >
				<option value="email" <?php notification_selected("channel","email","default") ?>>Email</option>
				<option value="post" <?php notification_selected("channel","post") ?>>FB Post</option>
				<option value="twitter" <?php notification_selected("channel","twitter") ?>>Twitter</option>
			</select>
			
			
			</td>
      </tr>
      <tr valign="top">
        <td>Message &raquo;</td>
        <td>
			<textarea name="message" cols="50">
				<?php echo(trim($notification["message"])) ?>
			</textarea>
			<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" />
		 </td>
      </tr>
		<tr valign="top">

        <td>Milestone&raquo;</td>
        <td>

			<select name="milestone" id = "milestone" >
				<option value="participants" <?php notification_selected("milestone","participants","default") ?>>Participants</option>
				<option value="likes" <?php notification_selected("milestone","likes") ?>>Likes</option>
				<option value="shares" <?php notification_selected("milestone","shares") ?>>Shares</option>
				<option value="invites" <?php notification_selected("milestone","invites") ?>>Invites</option>
				<option value="referral" <?php notification_selected("milestone","referral") ?>>Referral Points</option>
			    <option value="highest" <?php notification_selected("milestone","highest") ?>>Highest Score</option>
			    <option value="tipingpoint" <?php notification_selected("milestone","tipingpoint") ?>>Tipping Point Reached</option>

			</select>
			
			</td>
		</tr>
      <tr valign="top">
			<td>Number of time milestone must occur&raquo;</td>
			<td>
				<input name="notification_time" type="text" id="notification_time" size="50" value="<?php echo($notification["notification_time"]) ?>" /> 
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
			</td>
      </tr>	
	  
	        <tr valign="top">
        <td width="25%">Time to recieve notification &raquo;</td>
        <td width="75%" class="instructions">
          <input name="promo_start" type="text" id="promo_start" size="25" 
		  value="<?php echo($notification["promo_start"]) ?>">  (yyyy/mm/dd)  

      <select name="time" id="time">
  
      <option value="5:00 AM" <?php notification_selected("time","5:00 AM","default") ?>>5:00 AM</option>
      <?php
	  $hours = 5;
	  $hour_str = '';
	  $min_str = '';
	  $am_pm = 'AM';
	  for( $mins = 15; $hours<24; $mins+=15 )
	  {
		  if( $mins == 60 )
		  {
			    if( $hours == 23 )
				{
					break;
				}
		   		$hours++;
				$mins = 0;	
				$min_str = '00';
				
		  }  
		  else
		  {
			  	$min_str =  $mins;
		  }
		   if( $hours > 12 )
		  {
		   		$hour_str = ( $hours % 12 );
				$am_pm = 'PM';
		  }
		  else
		  {
			  	$hour_str =  $hours;
		  }
			  
		  $time = $hour_str.":".$min_str." ".$am_pm;
	  ?>
      <option value="<?php echo  $time?>" <?php notification_selected("time", $time) ?>><?php echo  $time?></option>
      <?php
	  }
	  ?>
      <!--<option value="5:15 AM" <?php notification_selected("time","5:15 AM") ?>>5:15 AM</option>

      <option value="5:30 AM" <?php notification_selected("time","5:30 AM") ?>>5:30 AM</option>

      <option value="5:45 AM" <?php notification_selected("time","5:45 AM") ?>>5:45 AM</option>
 
      <option value="6:00 AM" <?php notification_selected("time","6:00 AM") ?>>6:00 AM</option>
   
      <option value="6:15 AM" <?php notification_selected("time","6:00 AM") ?>>6:15 AM</option>

      <option value="6:30 AM" <?php notification_selected("time","6:00 AM") ?>>6:30 AM</option>

      <option value="6:45 AM" <?php notification_selected("time","6:00 AM") ?>>6:45 AM</option>

      <option value="7:00 AM" <?php notification_selected("time","6:00 AM") ?>>7:00 AM</option>

      <option value="7:15 AM" <?php notification_selected("time","6:00 AM") ?>>7:15 AM</option>

      <option value="7:30 AM" <?php notification_selected("time","6:00 AM") ?>>7:30 AM</option>

      <option value="7:45 AM" <?php notification_selected("time","6:00 AM") ?>>7:45 AM</option>

       

      <option value="8:00 AM" <?php notification_selected("time","6:00 AM") ?>>8:00 AM</option>

      <option value="8:15 AM" <?php notification_selected("time","6:00 AM") ?>>8:15 AM</option>

      <option value="8:30 AM" <?php notification_selected("time","6:00 AM") ?>>8:30 AM</option>

      <option value="8:45 AM" <?php notification_selected("time","6:00 AM") ?>>8:45 AM</option>
 

      <option value="9:00 AM" <?php notification_selected("time","6:00 AM") ?>>9:00 AM</option>

      <option value="9:15 AM" <?php notification_selected("time","6:00 AM") ?>>9:15 AM</option>

      <option value="9:30 AM" <?php notification_selected("time","6:00 AM") ?>>9:30 AM</option>

      <option value="9:45 AM" <?php notification_selected("time","6:00 AM") ?>>9:45 AM</option>


      <option value="10:00 AM" <?php notification_selected("time","6:00 AM") ?>>10:00 AM</option>

      <option value="10:15 AM" <?php notification_selected("time","6:00 AM") ?>>10:15 AM</option>

      <option value="10:30 AM" <?php notification_selected("time","6:00 AM") ?>>10:30 AM</option>

      <option value="10:45 AM" <?php notification_selected("time","6:00 AM") ?>>10:45 AM</option>

      <option value="11:00 AM" <?php notification_selected("time","6:00 AM") ?>>11:00 AM</option>

      <option value="11:15 AM" <?php notification_selected("time","6:00 AM") ?>>11:15 AM</option>

      <option value="11:30 AM" <?php notification_selected("time","6:00 AM") ?>>11:30 AM</option>

      <option value="11:45 AM" <?php notification_selected("time","6:00 AM") ?>>11:45 AM</option>

      <option value="12:00 PM" <?php notification_selected("time","6:00 AM") ?>>12:00 PM</option>

      <option value="12:15 PM" <?php notification_selected("time","6:00 AM") ?>>12:15 PM</option>

      <option value="12:30 PM" <?php notification_selected("time","6:00 AM") ?>>12:30 PM</option>

      <option value="12:45 PM" <?php notification_selected("time","6:00 AM") ?>>12:45 PM</option>

      <option value="1:00 PM" <?php notification_selected("time","6:00 AM") ?>>1:00 PM</option>

      <option value="1:15 PM" <?php notification_selected("time","6:00 AM") ?>>1:15 PM</option>

      <option value="1:30 PM" <?php notification_selected("time","6:00 AM") ?>>1:30 PM</option>

      <option value="1:45 PM" <?php notification_selected("time","6:00 AM") ?>>1:45 PM</option>
    <option value="2:00 PM" <?php notification_selected("time","6:00 AM") ?>>2:00 PM</option>

      <option value="2:15 PM" <?php notification_selected("time","6:00 AM") ?>>2:15 PM</option>

      <option value="2:30 PM" <?php notification_selected("time","6:00 AM") ?>>2:30 PM</option>

      <option value="2:45 PM" <?php notification_selected("time","6:00 AM") ?>>2:45 PM</option>

       

      <option value="3:00 PM" <?php notification_selected("time","6:00 AM") ?>>3:00 PM</option>

      <option value="3:15 PM" <?php notification_selected("time","6:00 AM") ?>>3:15 PM</option>

      <option value="3:30 PM" <?php notification_selected("time","6:00 AM") ?>>3:30 PM</option>

      <option value="3:45 PM" <?php notification_selected("time","6:00 AM") ?>>3:45 PM</option>

      <option value="4:00 PM" <?php notification_selected("time","6:00 AM") ?>>4:00 PM</option>

      <option value="4:15 PM" <?php notification_selected("time","6:00 AM") ?>>4:15 PM</option>

      <option value="4:30 PM" <?php notification_selected("time","6:00 AM") ?>>4:30 PM</option>

      <option value="4:45 PM" <?php notification_selected("time","6:00 AM") ?>>4:45 PM</option>


      <option value="5:00 PM" <?php notification_selected("time","6:00 AM") ?>>5:00 PM</option>

      <option value="5:15 PM" <?php notification_selected("time","6:00 AM") ?>>5:15 PM</option>

      <option value="5:30 PM" <?php notification_selected("time","6:00 AM") ?>>5:30 PM</option>

      <option value="5:45 PM" <?php notification_selected("time","6:00 AM") ?>>5:45 PM</option>

      <option value="6:00 PM" <?php notification_selected("time","6:00 AM") ?>>6:00 PM</option>

      <option value="6:15 PM" <?php notification_selected("time","6:00 AM") ?>>6:15 PM</option>

      <option value="6:30 PM" <?php notification_selected("time","6:00 AM") ?>>6:30 PM</option>

      <option value="6:45 PM" <?php notification_selected("time","6:00 AM") ?>>6:45 PM</option>

      <option value="7:00 PM" <?php notification_selected("time","6:00 AM") ?>>7:00 PM</option>

      <option value="7:15 PM" <?php notification_selected("time","6:00 AM") ?>>7:15 PM</option>

      <option value="7:30 PM" <?php notification_selected("time","6:00 AM") ?>>7:30 PM</option>

      <option value="7:45 PM" <?php notification_selected("time","7:45 PM") ?>>7:45 PM</option>

      <option value="8:00 PM" <?php notification_selected("time","8:00 PM") ?>>8:00 PM</option>

      <option value="8:15 PM" <?php notification_selected("time","8:15 PM") ?>>8:15 PM</option>

      <option value="8:30 PM" <?php notification_selected("time","8:30 PM") ?>>8:30 PM</option>

      <option value="8:45 PM" <?php notification_selected("time","8:45 PM") ?>>8:45 PM</option>

      <option value="9:00 PM" <?php notification_selected("time","9:00 PM") ?>>9:00 PM</option>

      <option value="9:15 PM" <?php notification_selected("time","9:15 PM") ?>>9:15 PM</option>

      <option value="9:30 PM" <?php notification_selected("time","9:30 PM") ?>>9:30 PM</option>

      <option value="9:45 PM" <?php notification_selected("time","9:45 PM") ?>>9:45 PM</option>


      <option value="10:00 PM" <?php notification_selected("time","10:00 PM") ?>>10:00 PM</option>

      <option value="10:15 PM" <?php notification_selected("time","10:15 PM") ?>>10:15 PM</option>

      <option value="10:30 PM" <?php notification_selected("time","10:30 PM") ?>>10:30 PM</option>

      <option value="10:45 PM"  <?php notification_selected("time","10:45 PM") ?>>10:45 PM</option>


      <option value="11:00 PM" <?php notification_selected("time","11:00 PM") ?>>11:00 PM</option>

      <option value="11:15 PM" <?php notification_selected("time","11:15 PM") ?>>11:15 PM</option>

      <option value="11:30 PM" <?php notification_selected("time","11:30 PM") ?>>11:30 PM</option>

      <option value="11:45 PM" <?php notification_selected("time","11:45 PM") ?>>11:45 PM</option>-->

      </select>
		  
		  </td>
      </tr>	


      <tr valign="top">
			<td>Notification Email  1&raquo;</td>
			<td>
		
	<input name="user_email" type="text" id="user_email" size="50" value="<?php echo $notifications_obj->user_email; ?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
			</td>
      </tr>	
	  
	       <tr valign="top">
			<td>Notification Email 2&raquo;</td>
			<td>
		
	<input name="user_email2" type="text" id="user_email2" size="50" value="<?php echo $notifications_obj->user_email2; ?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
			</td>
      </tr>	
	  
	       <tr valign="top">
			<td>Notification Email 3&raquo;</td>
			<td>
		
	<input name="user_email3" type="text" id="user_email3" size="50" value="<?php echo $notifications_obj->user_email3; ?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
			</td>
      </tr>	
	  
	  
	       <tr valign="top">
			<td>Notification Email 4&raquo;</td>
			<td>
		
	<input name="user_email4" type="text" id="user_email4" size="50" value="<?php echo $notifications_obj->user_email4; ?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
			</td>
      </tr>	
      <tr valign="top">
			<td>Notification Address  1&raquo;</td>
			<td>
				<input name="user_address1" type="text" id="user_address1" size="50" value="<?php echo $notification["user_address1"];?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
             </td>
     </tr>	
     <tr valign="top">
			<td>Notification Address 2&raquo;</td>
			<td>
				<input name="user_address2" type="text" id="user_address2" size="50" value="<?php echo $notification["user_address2"];?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
             </td>
     </tr>	
     <tr valign="top">
			<td>City&raquo;</td>
			<td>
                <input name="user_city" type="text" id="user_city" size="50" value="<?php echo $notification["user_city"];?>" />  
				<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
            </td>
     </tr>
     <tr valign="top">
			<td>State&raquo;</td>	
            <td>
      				<input name="user_state" type="text" id="user_state" size="50" value="<?php echo $notification["user_state"];?>" />  
					<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
              </td>
     </tr>       
    <tr valign="top">
			<td>Zip Code&raquo;</td>	
            <td>
      				<input name="user_zip_code" type="text" id="user_zip_code" size="50" value="<?php echo $notification["user_zip_code"];?>" />  
					<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
              </td>
     </tr>        
     <tr valign="top">
			<td>Phone&raquo;</td>	
            <td>
      				<input name="user_phone" type="text" id="user_phone" size="50" value="<?php echo $notification["user_phone"];?>" />  
					<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
              </td>
     </tr>
     <tr valign="top">
			<td>Country&raquo;</td>	
            <td>
      				<input name="user_country" type="text" id="user_country" size="50" value="<?php echo $notification["user_country"];?>" />  
					<img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /> 
              </td>
     </tr>
	  <tr>
        <td colspan="2" bgcolor="#CCCCCC" class="pagetitle"><input type="submit" name="Submit" value="SAVE NOTIFICATION" /></td>
        </tr>
      <tr>
        <td colspan="2"><img src="<?php echo($promo_image_url);?>/copyright.jpg" width="657" height="56" /></td>
        </tr>
 </table>




</form>
</div>
</div>

