
<div class="wrap">

	<div id="form1">
		<form id="promoform" name="promoform" method="post" onsubmit="return valscreenbuzz2();" />
		<input type="hidden" name="fb_edit_action" value="save_sweepstakes_1" />
		<input type="hidden" name="page" value="fbm_edit_promo" />
		<input type="hidden" name="fbm_step" value="THREE" />
		<input type="hidden" name="fbm_next_step" value="visuals" />
		<input type="hidden" name="fbm_promo_heading" value="BUZZ" />
		<input type="hidden" name="promo_type" value="<?php echo($_REQUEST['promo_type'])?>" />
		<?php if(isset($_REQUEST["promo_id"])): ?>
			<input type="hidden" name="promo_id" value="<?php echo($_REQUEST['promo_id']);?>" />
			<?php endif; ?>
		<table width="100%" border="0" cellpadding="10" cellspacing="0" class="tbl">
			<tr>
				<td colspan="2"><h1><em>Bugs Go Viral &raquo; Promotion Settings</em></h1>
					<hr width="100%" size="1" noshade></td>
			</tr>
			<tr>
				<td colspan="2"><img src="<?php echo($promo_image_url);?>/headergraphic.png" ></td>
			</tr>
			<tr>
				<td colspan="2" class="pagetitle">STEP TWO &raquo; BUZZ SETTINGS</td>
			</tr>
			<tr valign="top">
			  <td>How-to-enter Video URL </td>
			  <td class="instructions"><input name="how_to_video_url" type="text" id="how_to_video_url" size="50" value="<?php echo($fbmo->promo_row["how_to_video_url"])?>" />
		 <a  data-tooltip="stickyhow"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></td>

          </tr>
			<tr valign="top">
				<td width="25%"> Submission Period Start Date  &raquo;</td>
				<td width="75%" class="instructions">
            <link rel="stylesheet" type="text/css" href="<?php  echo $promo_date_cal?>/calendar-blue.css">
			<script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar.js"></script>
            <script type="text/javascript" src="<?php  echo $promo_date_cal?>/lang/calendar-en.js"></script>
            <script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar-setup.js"></script>

<!--<input name="pick_start" type="text" id="pick_start" size="25" value="<?php echo($fbmo->promo_row["promo_start"])?>">
--><input name="promo_start" type="text" id="promo_start" size="25" value="<?php echo($fbmo->promo_row["promo_start"])?>">
	  <script type="text/javascript">
	  	Calendar.setup({
        inputField     :    "promo_start",     // id of the input field
	  	ifFormat       :    "%Y-%m-%d %I:%M %p",      // format of the input field
		showTime      : 12,
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		showsTime		:	true
    });

</script>
<div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">
                            <div id="sticky1" class="atip" style="width:300px">
                            The date and time you would like for the promo to start.                             
                            </div>
                            <div id="sticky2" class="atip" style="width:300px">
                                The date and time  you would like for the promo to end.                            
                                </div>
                              <div id="stickyvs" class="atip" style="width:300px">
                                The date and time  you would like for the voting to start.                            
                                </div>
                                 <div id="stickyve" class="atip" style="width:300px">
                                The date and time  you would like for the voting to end.                            
                                </div>
                            <div id="sticky3" class="atip" style="width:300px">
								Describe your prize using as few words as possible.  Because this description is used by the Facebook news stream, it is important to use proper grammatical article (e.g.- "a" gift card or "an" evening on the town).
							</div>
                            <div id="sticky4" class="atip" style="width:300px">
                            Select your value from drop down.                              
                            </div>
                            <div id="sticky5" class="atip" style="width:300px">
								For Buzz, you can select to have a video contest, photo contest, or a text/story contest.  For video contest, users will enter a YouTube URL, in a photo contest, users will upload a photo and for a story contest, users will enter up to 250 words of text.  Designate the media type with this field.                            </div>
                            <div id="sticky6" class="atip" style="width:300px">
							   Choose a voting system for your contest.  1 to 5 stars assigns an average rating, while "like" equals a 1 vote per person system.
                            </div>
                            <div id="stickyhow" class="atip" style="width:300px">
							   For Buzz, enter here youtube video uel for how to enter video.
                            </div>
                     </div>
					<div class="stickystatus"></div>
                    </div>
<a  data-tooltip="sticky1"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></td>
</tr>

<tr valign="top">

<td width="25%"> Submission Period End Date  &raquo;</td>

<td width="75%" class="instructions">
<input name="promo_end" type="text" id="promo_end" size="25"  value="<?php echo($fbmo->promo_row["promo_end"])?>">
	  <script type="text/javascript">
	  	Calendar.setup({
        inputField     :    "promo_end",     // id of the input field
	  	ifFormat       :    "%Y-%m-%d %I:%M %p",      // format of the input field
		showTime      : 12,
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		showsTime		:	true
    });

</script>
<a  data-tooltip="sticky2"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></td>


			</tr>
            <tr valign="top">

<td width="25%"> Voting Period Start Date  &raquo;</td>

<td width="75%" class="instructions">
<input name="vote_start" type="text" id="vote_start" size="25"  value="<?php echo($fbmo->promo_row["vote_start"])?>">
	  <script type="text/javascript">
	  	Calendar.setup({
        inputField     :    "vote_start",     // id of the input field
	  	ifFormat       :    "%Y-%m-%d %I:%M %p",      // format of the input field
		showTime      : 12,
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		showsTime		:	true
    });

</script>
<a  data-tooltip="stickyvs"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></td>


			</tr>
<tr valign="top">

<td width="25%"> Voting Period End Date  &raquo;</td>

<td width="75%" class="instructions">
<input name="vote_end" type="text" id="vote_end" size="25"  value="<?php echo($fbmo->promo_row["vote_end"])?>">
	  <script type="text/javascript">
	  	Calendar.setup({
        inputField     :    "vote_end",     // id of the input field
	  	ifFormat       :    "%Y-%m-%d %I:%M %p",      // format of the input field
		showTime      : 12,
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		showsTime		:	true
    });

</script>
<a  data-tooltip="stickyve"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></td>


			</tr>

			<tr valign="top">
				<td width="25%"> Prize &raquo;</td>
				<td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<input name="promo_prize" type="text" id="promo_prize"
							value="<?php echo($fbmo->promo_row["promo_prize"]) ?>" size="50" maxlength="200">
					<a  data-tooltip="sticky3"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
			</tr>
			<tr valign="top">
				<td width="25%">Prize Value &raquo;</td>
				<td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<select name="prize_value" size="1" id="prize_value">
							<option value="25" <?php fbmo_selected("prize_value","25") ?>>$25 or less</option> 
                            <option value="26" <?php fbmo_selected("prize_value","26") ?>>$26-$50</option> 
                            <option value="51" <?php fbmo_selected("prize_value","51") ?>>$51-$100</option> 
                            <option value="101" <?php fbmo_selected("prize_value","101") ?>>$101-$200</option> 
                            <option value="201" <?php fbmo_selected("prize_value","201") ?>>$201-$300</option> 
                            <option value="301" <?php fbmo_selected("prize_value","301") ?>>$301-$400</option> 
                            <option value="401" <?php fbmo_selected("prize_value","401") ?>>$401-$500</option> 
                            <option value="501" <?php fbmo_selected("prize_value","501") ?>>$501-$1000</option> 
                            <option value="1001" <?php fbmo_selected("prize_value","1001") ?>>$1,001-$1,500</option> 
                            <option value="1501" <?php fbmo_selected("prize_value","1501") ?>>$1,501-$2,000</option> 
                            <option value="2001" <?php fbmo_selected("prize_value","2001") ?>>$2,001-$2,500</option> 
                            <option value="2501" <?php fbmo_selected("prize_value","2501") ?>>$2,501-$5,000</option> 
                            <option value="10000" <?php fbmo_selected("prize_value","10000") ?>>More than $10,000</option> 
						</select>
					<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
			</tr>
            <tr valign="top">
				<td width="25%">Media Type &raquo;</td>
				<td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<select name="media_type"> 
                      <option value="TEXT ENTRY" <?php fbmo_selected("media_type","TEXT ENTRY") ?>>TEXT ENTRY</option>
                      <option value="PHOTO UPLOAD" <?php fbmo_selected("media_type","PHOTO UPLOAD") ?>>PHOTO UPLOAD</option>
                      <option value="YOUTUBE VIDEO LINK" <?php fbmo_selected("media_type","YOUTUBE VIDEO LINK") ?>>YOUTUBE VIDEO LINK </option>         
                                </option>
                                </select> 
					<a  data-tooltip="sticky5"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
			</tr>
           <!-- <tr valign="top">
				<td width="25%">Response Type &raquo;</td>
				<td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
					<select name="response_type"> 
                   <!-- <option value="1-5 STARS" <?php //fbmo_selected("response_type","1-5 STARS") ?>>1-5 STARS</option>-->
                 <!--   <option  value="LIKE" <?php //fbmo_selected("response_type","LIKE") ?>>LIKE
                    </option></select> 
					<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
			</tr>-->
			
			<tr>
            	<input type="hidden" name="response_type" value="LIKE" />
				<td colspan="2" class="pagetitle"><input type="submit" name="Submit" value="<?php  if($_REQUEST['fb_edit_action']=="add"){?>PROCEED TO NEXT STEP<?php }else{?>SAVE AND PROCEED<?php }?> &raquo;"></td>
			</tr>
			<tr>
				<td colspan="2"><img src="<?php echo($promo_image_url);?>/copyright.jpg" width="657" height="56"></td>
			</tr>
		</table>
		</form>
	</div>
</div>
<script>
function valscreenbuzz2(){
	if(document.promoform.promo_start.value==""){
		alert("Please enter start date for Promo.");
		return false;
	}
	if(document.promoform.promo_end.value==""){
		alert("Please enter end date for Promo.");
		return false;
	}

	return true;
}
</script>