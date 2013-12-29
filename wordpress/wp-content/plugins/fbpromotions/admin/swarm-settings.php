<!--
TODO: 2/2011: (FOR ALL PROMOS) Use jQuery in to format date to show date and time to start and end promos. Edit db to validate and test values for time and date. CVHJ
TODO: Must add input "promo_tipping_deadline" into db and wherever else required. - CVHJ
TODO: Must add following fields into promotions database and anywere else required:
	promo_prodserv_offered,
	promo_deal_desc,
	promo_prodserv_sale_price,
	promo_prodserv_reg_price,
	promo_deals_to_tip,
TODO: determine if any additional fields need to be added to db... ie, will shipping cost be needed, addresses, etc... will know once review paypal payvment dev docs. - CVHJ
-->

<div class="wrap">
<div id="form1">
<form id="promoform" name="promoform" method="post" onsubmit="return valscreenswarm2();"  />
<input type="hidden" name="fb_edit_action" value="save_swarm_1" />
<input type="hidden" name="page" value="fbm_edit_promo" />
<input type="hidden" name="fbm_step" value="THREE" />
<input type="hidden" name="fbm_next_step" value="visuals" />
<input type="hidden" name="fbm_promo_heading" value="THE SWARM" />
<input type="hidden" name="promo_type" value="<?php echo($_REQUEST['promo_type'])?>" />
<?php if(isset($_REQUEST["promo_id"])): ?>
<input type="hidden" name="promo_id" value="<?php echo($_REQUEST['promo_id']);?>" />
<?php endif; ?>

<table width="100%" border="0" cellpadding="10" cellspacing="0" class="tbl">
      <tr>
        <td colspan="2"><img src="<?php echo($promo_image_url);?>/headergraphic.png" ></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#CCCCCC" class="pagetitle">STEP TWO &raquo; "THE SWARM" SETTINGS</td>
      </tr>
<!-- TODO: 2/2011: (FOR ALL PROMOS) Use jQuery in to format date to show date and time to start and end promos. Edit db to validate and test values for time and date. CVHJ -->
<div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">
                            <div id="sticky1" class="atip" style="width:300px">
                            The date and time you would like for the promo to start.                             
                            </div>
                            <div id="sticky2" class="atip" style="width:300px">
                                The date and time  you would like for the promo to end.                            
                                </div>
                            <div id="sticky3" class="atip" style="width:300px">
                                Give a short name for the deal (e.g.-massage for $20)							
                                </div>
                            <div id="sticky4" class="atip" style="width:300px">
                           This should include all of the details about the deal being offered.                          
                            </div>
                            <div id="sticky5" class="atip" style="width:300px">
								The sale price
                                </div>
                            <div id="sticky6" class="atip" style="width:300px">
							   The regular price of the deal being offered
                            </div>
                             <div id="sticky7" class="atip" style="width:300px">
							  Number of participants required to activate the deal.  If tipping point is not reached then all participants receive store credit for the amount of the transaction-no money is refunded.
                            </div>
                             <div id="sticky8" class="atip" style="width:300px">
							  The software tracks a participant's "influence" as the sum total of likes, shares, purchases, invitations, and friends.  A "point" is rewarded for each of these results.  You can opt for one point of numerous points; it matters not.
                            </div>
                            <div id="sticky9" class="atip" style="width:300px">
                            Can be copied from your PayPal account info.
                            </div>
                            
                     </div>
					<div class="stickystatus"></div>
                    </div>
      <tr valign="top">
        <td width="25%">Start Date and Time (TIME to be implemeted) &raquo;</td>
        <td width="75%" class="instructions">
            <link rel="stylesheet" type="text/css" href="<?php  echo $promo_date_cal?>/calendar-blue.css">
			<script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar.js"></script>
            <script type="text/javascript" src="<?php  echo $promo_date_cal?>/lang/calendar-en.js"></script>
            <script type="text/javascript" src="<?php  echo $promo_date_cal?>/calendar-setup.js"></script>
          <input name="promo_start" type="text" id="promo_start" size="25"
		  value="<?php echo($fbmo->promo_row["promo_start"]) ?>">     
          <a  data-tooltip="sticky1"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"> (yyyy/mm/dd) </a></td>
      </tr>
      
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
	  <tr valign="top">
        <td width="25%">End Date and Time (ditto) &raquo;</td>
        <td width="75%" class="instructions">
          <input name="promo_end" type="text" id="promo_end" size="25"
		  value="<?php echo($fbmo->promo_row["promo_end"]) ?>"		  >
          
          <a  data-tooltip="sticky2"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top">        (yyyy/mm/dd) </a></td>
      </tr>
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
      <tr valign="top">
        <td width="25%">Deal Title&raquo;</td>
        <td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input name="promo_prodserv_offered" type="text" id="promo_prodserv_offered"
		  		  value="<?php echo($fbmo->promo_row["promo_prodserv_offered"]) ?>" size="50" maxlength="200">
          <a  data-tooltip="sticky3"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
      </tr>

	        <tr valign="top">
        <td width="25%">Deal Description &raquo;</td>
        <td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input name="promo_deal_desc" type="text" id="promo_deal_desc"
		  		  value="<?php echo($fbmo->promo_row["promo_deal_desc"]) ?>" size="50" maxlength="200">
         <a  data-tooltip="sticky4"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
      </tr>

	  <tr valign="top">
        <td width="25%">Deal Sale Price &raquo;</td>
        <td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input name="promo_prodserv_sale_price" type="text" id="promo_prodserv_sale_price"
		  		  value="<?php echo($fbmo->promo_row["promo_prodserv_sale_price"]) ?>" size="10" maxlength="15">
          <a  data-tooltip="sticky5"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
      </tr>

	  	  <tr valign="top">
        <td width="25%">Deal Regular Price&raquo;</td>
        <td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input name="promo_prodserv_reg_price" type="text" id="promo_prodserv_reg_price"
		  		  value="<?php echo($fbmo->promo_row["promo_prodserv_reg_price"]) ?>" size="10" maxlength="15">
          <a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </font></td>
      </tr>

	 <tr valign="top">
        <td width="25%">Tipping Point  &raquo;</td>
        <td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
          <input name="promo_deals_to_tip" type="text" id="promo_deals_to_tip"
		  		  value="<?php echo($fbmo->promo_row["promo_deals_to_tip"]) ?>" size="10" maxlength="15"> Purchases
         <a  data-tooltip="sticky7"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a></font></td>
      </tr>
<input name="promo_points" type="hidden" value="1" />
      <!--<tr valign="top">
        <td width="25%">Rewards &raquo;</td>
        <td width="75%"> <input name="promo_points" type="text" id="promo_points" size="5" value="<?php //echo($fbmo->promo_row["promo_points"]) ?>" />
		points for every referral.
		<a  data-tooltip="sticky8"><img src="<?php //echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top"></a> </td>
      </tr>-->
      <tr valign="top">


      <tr valign="top">
        <td> Paypal Credentials &raquo;</td>
        <td>&nbsp;</td>
      </tr>
      	<tr valign="top">
				<td>Paypal Merchant Email id &raquo;</td>
				<td>
					<input type="text" name="paypal_merchant_account" id="paypal_merchant_account" size="50" value="<?php echo($fbmo->promo_row["paypal_merchant_account"]) ?>" />
					<a  data-tooltip="sticky9"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>

      <tr valign="top">
				<td>Paypal API User Name &raquo;</td>
				<td>
					<input type="text" name="api_user_name" id="api_user_name" size="50" value="<?php echo($fbmo->promo_row["api_user_name"]) ?>" />
					<a  data-tooltip="sticky9"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
			<tr valign="top">
				<td>Paypal API Password &raquo;</td>
				<td>
					<input type="text" name="api_user_password" id="api_user_password" size="40" value="<?php echo($fbmo->promo_row["api_user_password"]) ?>" />
					<a  data-tooltip="sticky9"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
			<tr valign="top">
				<td>Paypal API Signature &raquo;</td>
				<td>
					<input type="text" name="api_user_signature" id="api_user_signature" size="85" value="<?php echo($fbmo->promo_row["api_user_signature"]) ?>" />
					<a  data-tooltip="sticky9"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
 </td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#CCCCCC" class="pagetitle">
				<input type="button" name="Back" value="Back" onclick="window.history.back()" />
                
				<input type="submit" name="Submit" value="<?php  if($_REQUEST['fb_edit_action']=="add"){?>PROCEED TO NEXT STEP<?php }else{?>SAVE AND PROCEED<?php }?> &gt;&gt;&gt;" />
        </tr>
      <tr>
        <td colspan="2"><img src="<?php echo($promo_image_url);?>/copyright.jpg" width="657" height="56"></td>
        </tr>
    </table>
</form>
</div>
</div>
<script>
function valscreenswarm2(){
	if(document.promoform.promo_start.value==""){
		alert("Please enter start date for Promo.");
		return false;
	}
	if(document.promoform.promo_end.value==""){
		alert("Please enter end date for Promo.");
		return false;
	}
	if(document.promoform.promo_prodserv_offered.value==""){
		alert("Please enter deal title.");
		return false;
	}
	if(document.promoform.promo_deal_desc.value==""){
		alert("Please enter deal description.");
		return false;
	}
	if(document.promoform.promo_prodserv_sale_price.value=="" ||  document.promoform.promo_prodserv_sale_price.value==0){
		alert("Please enter sale price.");
		return false;
	}
	if(document.promoform.promo_prodserv_reg_price.value=="" || document.promoform.promo_prodserv_reg_price.value==0){
		alert("Please enter regular price.");
		return false;
	}
	if(document.promoform.promo_deals_to_tip.value=="" || document.promoform.promo_deals_to_tip.value==0){
		alert("Please enter tipping point.");
		return false;
	}
	if(document.promoform.api_user_name.value==""){
		alert("Please enter paypal api user name.");
		return false;
	}
	if(document.promoform.api_user_password.value==""){
		alert("Please enter paypal api password.");
		return false;
	}
	if(document.promoform.api_user_signature.value==""){
		alert("Please enter paypal api signature.");
		return false;
	}
	if(document.promoform.paypal_merchant_account.value==""){
		alert("Please enter paypal api merchant email.");
		return false;
	}

	return true;
}
</script>