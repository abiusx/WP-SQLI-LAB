<?php
/**
*	BugsGoViral Plugin for Worpress
*	Promotion Settings View
*
*	@filesource 			promo-settings-1.php
*	@description			HTML to display view for input of Promotion Settings
* */
include("version.php");
/**
* 	@todo 					style now that tables changed to divs etc
* 	@todo 					Need to strip/trim input data on textarea and validate input for security
*
* */

/**
* Start display code
*/
?>
<script>
function valscreen1(){
	if(document.promo_form.promo_name.value==""){
		alert("Please enter some name for Promo.");
		return false;
	}
	
	
	if(document.promo_form.link_universal_one.value==""){
		alert("Please enter website link.");
		return false;
	}
	if(document.promo_form.link_universal_two.value==""){
		alert("Please enter fan page  link.");
		return false;
	}
	return true;
}
</script>
<div id="promo_settings_container" class="wrap">
<?php
      if(!get_option("key")){
	   ?>
		<div class='update-nag'>Account setup is mandatory before configuring any promo.</div>
      <?php
	  }elseif(empty($fbmo->options["fb_application_secret"])){
        ?>
        <div class='update-nag'>Facebook Settings needs to be updated.</div>
        <?php
        }
        ?>
		<div id="promo_settings_control_form" class="control_data">
		<form id="promo_form" name="promo_form" method="post"  onsubmit="return valscreen1();">
		<input type="hidden" name="fb_edit_action" value="save_step_1" />
		<input type="hidden" name="page" value="fbm_edit_promo" />
		<input type="hidden" name="fbm_next_step" value="2" />

		<?php if(isset($_REQUEST["promo_id"])): ?>

		<input type="hidden" name="promo_id" value="<?php echo($_REQUEST["promo_id"]); ?>" />

		<?php endif; ?>

		<table width="100%" border="0" cellpadding="10" cellspacing="0" class="tbl">

		<?php do_html_admin_masthead ("promo_settings","masthead","Promo Settings","STEP ONE &raquo; PROMOTION TYPE",1,1) ?>
		<tr valign="top">
		<td width="25%">Select a Promotion Type to Configure: &raquo;</td>
		</table>
<table width="700" border="1" cellpadding="10" cellspacing="0" class="tbl">
			<tr>
				<td align="center"><img src="<?php echo($promo_image_url);?>/Icons.png" width="130" height="168" alt="<?php echo($promo_image_url);?>"></font></td>
				<td align="center"><img src="<?php echo($promo_image_url);?>/Icons-02.png" alt="" width="130" height="168" /></font></td>
<!--				<td align="center"><img src="<?php echo($promo_image_url);?>/Icons-03.png" alt="" width="130" height="168" /></font></td>
-->			</tr>
			<!--
			<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="<//?php echo($promo_image_url);?>/icon_buzz.jpg" width="150" height="150"></font></td>
			-->
			<tr>
				<td width="25%" class="pagetitle" align="center">
					<input type="radio" name="promo_type" value="1" <?php fbmo_checked("promo_type","1","default") ?>/>
					<span class = "promo_name">Sweepstakes</span>
                  <a  data-tooltip="sticky11"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" border="0" /></a>
				</td>
				<td width="25%" class="pagetitle" align="center">
					<input type="radio" name="promo_type" value="2" <?php fbmo_checked("promo_type","2") ?>/>
					<span class="promo_name">Buzz</span>
                  <a  data-tooltip="sticky12"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" border="0" /></a>
				</td>
				<!--<td width="25%" class="pagetitle" align="center">
					<input type="radio" name="promo_type" value="8" <?php fbmo_checked("promo_type","8") ?>/>
					<span class="promo_name">Swarm</span>
                  <a  data-tooltip="sticky13"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" border="0" /></a>
				</td>-->
			</tr>
					</table>
					<table width="75%" border="0" cellpadding="10" cellspacing="0" class="tbl">
			<tr>
			<tr valign="top">
				<td width="25%">Give the Promotion a Promotion Name&raquo;</td>
				<td width="75%">
					<input type="text" name="promo_name" id="promo_name" size="40" value="<?php echo($fbmo->promo_row["promo_name"]);?>" />
                    <a  data-tooltip="sticky1"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" border="0" /></a>
                    <!--HTML for the tooltips-->
                   <div id="mystickytooltip" class="stickytooltip">
                    <div style="padding:5px">
                    <div id="sticky11" class="atip" style="width:300px">
                    	Sweepstakes- a simple, enter-to-win promotion. Specify a prize and allow users to enter to win. Winner selected at random.
                     </div>
                    <div id="sticky12" class="atip" style="width:300px">
                    	Buzz- a user contest. Specify photo, video, or story, and allow users to upload their entry and ask their friends to vote. The entry with the most votes wins.
                     </div>
                    <div id="sticky13" class="atip" style="width:300px">
                    	Swarm- a tipping-point promotion. Specify a "deal" and allow users to purchase. If the tipping point is reached, all purchasers get the deal. If the tipping point is NOT reached, all purchasers get store credit for the amount of purchase.
                     </div>

                    <div id="sticky1" class="atip" style="width:300px">
							Assign this promotion a name. This name will be used by the admin as well as in some features of Facebook to refer to this promotion                    </div>
                    <div id="sticky2" class="atip"  style="width:300px">
							Describe this promotions. This should not be a technical description but more of a marketing description.  Describe the promotion as you would if you were submitting to a media outlet.  Use catchy language.                     </div>
                    <div id="sticky3" class="atip"  style="width:300px">
                    		Use this space to provide general terms and conditions.  This field is limited to 1000 words, so if your terms exceed this, provide a teaser with link to full disclosure.
                    </div>
                    <div id="sticky4" class="atip"  style="width:300px">
						For each promo links to a web site and a Facebook fan page are provided at the top of each screen. You can specify your own site and Facebook page.               
                    </div>
                     <div id="sticky5" class="atip"  style="width:300px">
						For each promo links to a web site and a Facebook fan page are provided at the top of each screen. You can specify your own site and Facebook page.                    </div>
                    <div id="sticky6" class="atip"  style="width:300px">
                            The business name of the sponsor for this promo,  If there is no sponsor, leave blank.                    
                   </div>
                   <div id="sticky7" class="atip"  style="width:300px">
                        The URL of the sponsor's site for this promo,  If there is no sponsor, leave blank.                  
                    </div>
                     <div id="sticky8" class="atip"  style="width:300px">
						Landing order is the order for displaying promo in Facebook page like if its 1 and is active, it will be displayed otherwise if its 0, it will not be displayed. So, it should be 1, to display the promo.                    </div>
                        </div>
					<div class="stickystatus"></div>
                    </div>
				</td>
			</tr>
			<tr valign="top">
				<td>Promotion Description&raquo;</td>
				<td>
					<textarea name="promo_description" cols="40" rows="10"><?php echo($fbmo->promo_row["promo_description"]); ?></textarea>
					<a  data-tooltip="sticky2"> <img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" border="0" /></a>
				</td>
			</tr>
            <script language="javascript" type="text/javascript">
				function limitText(limitField, limitCount, limitNum) {
					if (limitField.value.length > limitNum) {
						limitField.value = limitField.value.substring(0, limitNum);
					} else {
						limitCount.value = limitNum - limitField.value.length;
					}
				}
			</script>
			<tr valign="top">
				<td>Enter the Promo Rules &amp; Regulations &raquo;</td>
				<td>
<!--					<textarea name="promo_rules" cols="40" rows="10" onKeyDown="limitText(this.form.promo_rules,this.form.countdown,1000);" 
onKeyUp="limitText(this.form.promo_rules,this.form.countdown,1000);"><?php echo($fbmo->promo_row["promo_rules"]); ?> </textarea>-->
				<textarea name="promo_rules" cols="40" rows="10"><?php echo($fbmo->promo_row["promo_rules"]); ?> </textarea>

					<a  data-tooltip="sticky3"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
<!--                    <br>
                    <font size="1">(Maximum characters: 1000)<br>
                    You have <input readonly type="text" name="countdown" size="3" value="1000"> characters left.</font>
-->				</td>
			</tr>
			<tr valign="top">
				<td>Fan Page URL &raquo;</td>
				<td>
					<input name="link_universal_two" type="text" id="link_universal_two" size="40" value="<?php echo($fbmo->promo_row["link_universal_two"]) ?>" />
					<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
            <tr valign="top">
				<td>Web Site URL &raquo;</td>
				<td>
					<input name="link_universal_one" type="text" id="link_universal_one" size="40" value="<?php echo($fbmo->promo_row["link_universal_one"]) ?>" />
					<a  data-tooltip="sticky5"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
			<tr valign="top">
				<td>Promo Sponsor Name &raquo;</td>
				<td>
					<input name="text_sponsor" type="text" id="text_sponsor" size="40" value="<?php echo($fbmo->promo_row["text_sponsor"]) ?>" />
					<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
			<tr valign="top">
				<td>Promo Sponsor URL &raquo;</td>
				<td>
					<input name="link_sponsor_one" type="text" id="link_sponsor_one" size="40" value="<?php echo($fbmo->promo_row["link_sponsor_one"]) ?>" />
					<a  data-tooltip="sticky7"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>
			<input type="hidden" name="landing_order" value="1" />
			<!--<tr valign="top">
				<td>Landing Page Order &raquo;</td>
				<td>
					<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
					<input type="text" name="landing_order" id="landing_order" size="40" value="<?php echo($fbmo->promo_row["landing_order"]) ?>" />
					<a  data-tooltip="sticky8"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
				</td>
			</tr>-->
			<tr>
				<td colspan="2" bgcolor="#CCCCCC" class="pagetitle">
				<input type="button" name="Back" value="Back" onclick="window.history.back()" />
				<input type="submit" name="Submit" value="<?php  if($_REQUEST['fb_edit_action']=="add"){?>PROCEED TO NEXT STEP<?php }else{?>SAVE AND PROCEED<?php }?> &raquo;" />
				</td>
			</tr>
		</table>




		</form>
	</div>
</div>
<?php do_html_admin_footer ("promo_settings","Promo Settings") ?>

