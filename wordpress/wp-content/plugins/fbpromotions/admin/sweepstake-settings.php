<?php
/**
*
* FIX THIS JAVASCRIPT@
*
*/
/*
<script type="text/javascript">
	$.datetimeEntry.setDefaults({spinnerBigImage:'images/spinnerGreenBig.png'}).
	$('#pick_start').datetimeEntry({datetimeFormat:'$D/n/Y H:M',
	altField: '#promo_start', altFormat:'Y-O-D H:M:S'}).
	$('#pick_end').datetimeEntry({datetimeFormat:'$D/n/Y H:M',
	altField:'#promo_end', altFormat:'Y-O-D H:M:S'});
</script>
*/
?>
<div class="wrap">

	<div id="form1">
		<form id="promoform" name="promoform" method="post" onsubmit="return valscreensweep2();" />
		<input type="hidden" name="fb_edit_action" value="save_sweepstakes_1" />
		<input type="hidden" name="page" value="fbm_edit_promo" />
		<input type="hidden" name="fbm_step" value="THREE" />
		<input type="hidden" name="fbm_next_step" value="visuals" />
		<input type="hidden" name="fbm_promo_heading" value="SWEEPSTAKES" />
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
				<td colspan="2" class="pagetitle">STEP TWO &raquo; SWEEPSTAKES SETTINGS</td>
			</tr>
			<tr valign="top">
				<td width="25%">Start Date &raquo;</td>
				<td width="75%" class="instructions">
            <link rel="stylesheet" type="text/css" href="<?php  echo $promo_date_cal?>/calendar-blue.css">
			<script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar.js"></script>
            <script type="text/javascript" src="<?php  echo $promo_date_cal?>/lang/calendar-en.js"></script>
            <script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar-setup.js"></script>

<input name="promo_start" type="text" id="promo_start" size="25" value="<?php if($fbmo->promo_row["promo_start"]!="0000-00-00")echo($fbmo->promo_row["promo_start"])?>" >
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
                            <div id="sticky3" class="atip" style="width:300px">
							Describe your prize using as few words as possible.  Because this description is used by the Facebook news stream, it is important to use proper grammatical article (e.g.- "a" gift card or "an" evening on the town).                            </div>
						    <div id="sticky4" class="atip" style="width:300px">
                            Select your value from drop down.                              
                            </div>
                            <div id="sticky5" class="atip" style="width:300px">
							The software tracks a participant's "influence" as the sum total of likes, shares, purchases, invitations, and friends.  A "point" is rewarded for each of these results.  You can opt for one point of numerous points; it matters not.                            </div>

                     </div>
					<div class="stickystatus"></div>
                    </div>
					<a  data-tooltip="sticky1"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></td>
</tr>

<tr valign="top">

<td width="25%">End Date &raquo;</td>

<td width="75%" class="instructions">
<input name="promo_end" type="text" id="promo_end" size="25"  value="<?php if($fbmo->promo_row["promo_end"]!="0000-00-00")echo($fbmo->promo_row["promo_end"])?>">
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
				<td width="25%">Prize &raquo;</td>
				<td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<input name="promo_prize" type="text" id="promo_prize"
							value="<?php echo($fbmo->promo_row["promo_prize"]) ?>" size="50" maxlength="200">
						<a  data-tooltip="sticky3"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"> </a></font></td>
			</tr>
			<tr valign="top">
				<td width="25%">Prize Value &raquo;</td>
				<td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<select name="prize_value" size="1" id="prize_value">
							<option value="25" <?php fbmo_selected("prize_value","25") ?>>$25 or less</option>
							<option  value="50" <?php fbmo_selected("prize_value","50") ?>>$26-$50</option>
							<option  value="100" <?php fbmo_selected("prize_value","100") ?>>$51-$100</option>
							<option value="200" <?php fbmo_selected("prize_value","200") ?>>$101-$200</option>
							<option value="300" <?php fbmo_selected("prize_value","300") ?>>$201-$300</option>
							<option value="400" <?php fbmo_selected("prize_value","400") ?>>$301-$400</option>
							<option value="500" <?php fbmo_selected("prize_value","500") ?>>$401-$500</option>
							<option value="1000" <?php fbmo_selected("prize_value","1000") ?>>$501-$1000</option>
							<option value="1500" <?php fbmo_selected("prize_value","1500") ?>>$501-$1500</option>
							<option value="2000" <?php fbmo_selected("prize_value","2000") ?>>$1501-$2,000</option>
							<option value="2500" <?php fbmo_selected("prize_value","2500") ?>>$2,001-$2,500</option>
							<option value="5000" <?php fbmo_selected("prize_value","5000") ?>>$2,501-$5,000</option>
							<option value="10000" <?php fbmo_selected("prize_value","10000") ?>>$5,001-$10,000</option>
							<option value="999999" <?php fbmo_selected("prize_value","999999") ?>>More than $10,000</option>
						</select>
					<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
			</tr>
            <input name="promo_points" type="hidden" value="1" />

			<!--<tr valign="top">
				<td width="25%">
					Rewards &raquo;</td>
				<td width="75%">
					<input name="promo_points" type="text" id="promo_points" size="5"
						value="<?php //echo($fbmo->promo_row["promo_points"]) ?>" />
					     points for every referral. 
                         <a  data-tooltip="sticky5"><img src="<?php //echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </td>
			</tr>-->
			<tr>
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
function valscreensweep2(){
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