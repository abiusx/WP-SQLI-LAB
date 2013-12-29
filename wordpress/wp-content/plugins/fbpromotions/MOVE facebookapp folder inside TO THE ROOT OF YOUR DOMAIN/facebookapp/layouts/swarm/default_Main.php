<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo($promo_row["promo_name"])?> | <?php echo $fbmo->fboptions["application_name"]?></title>
<link href="<?php echo $promo_stylesheet ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript">
	function toggle2(showHideDiv,switchTextDiv) {  
		var ele = document.getElementById(showHideDiv); 
		var text = document.getElementById(switchTextDiv)
		if(ele.style.display == "block") {
			ele.style.display = "none";
			text.innerHTML = "Display";
		}
		else {
			ele.style.display = "block";
			text.innerHTML = "Close";
		}
	}
	function toggleOff(hidediv) {
		document.getElementById(hidediv).style.display = "none";
	}
	function toggleOn(showdiv) {
		document.getElementById(showdiv).style.display = "block";
	}
</script>
</head>
<?php  
	$rulesReg =0;
	$showCoupon =0;
	$showCard=0;
	if($_REQUEST['action']=="show_rules")
    $rulesReg = 1;
	if($_REQUEST['action']=="show_gift_card")
    $showCard = 1;
	if($_REQUEST['action']=="show_coupon")
    $showCoupon = 1;
	$unitsPurchased = $fbmo->swarmAlreadyPurchased( $promo_row['promo_id'], $fbmo->fbuser ) ;
	$tippingPoint = $promo_row["promo_deals_to_tip"]; 
	$purchasedSoFar = $fbmo->getTippingPointInfo( $promo_row['promo_id'], $fbmo->fbuser );
	$totPurchasedSoFar = $fbmo->getTippingPointTotalInfo($promo_row['promo_id']);
	$toGoForTipping = $tippingPoint - $totPurchasedSoFar;
	if($toGoForTipping<0)
		$toGoForTipping =0;
	$toGo = "Tipping point already reached";
	if( $toGoForTipping> 0)
						$toGo = "Only " . $toGoForTipping . " more to go!";
	 $fbmo_is_entered=0;
     $fbmo_is_entered_array=$fbmo->check_promo_entry($get_promo_id);
	 $is_fan=$fbmo->check_entrant_fan_status();
	if(!empty($fbmo_is_entered_array)){
		$fbmo_is_entered=1;
	}
				$fbmo->options["fb_canvas_url"] = get_option('siteurl')."/facebookapp/";?>

<!-- set dynamic css -->
<style>
body	{
	color:#<?php echo($promo_row["text_color"])?>;
}
#container	{
<?php
	$promoStatus ='';
	$time= time()-(5*3600);
	$mdate = date("Y-m-d h:i A",$time);
	$d1 = new DateTime($mdate);
	$end_date = date("Y-m-d h:i A",strtotime($promo_row["promo_end"]));
	$d2 = new DateTime($end_date);
	if ($d2<$d1)   $promoStatus="closed";
	if ($promoStatus == "closed")
	{
		if(!empty($promo_row["img_canvas_closed_sweepstakes_520x560"]))
		{
			echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_closed_sweepstakes_520x560"] . ");";		
		}
		if($showCoupon==1){
					if(!empty($promo_row["img_canvas_dialogbg_sweepstakes_10x10"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_dialogbg_sweepstakes_10x10"] . ");";		
					}
			}
			else if($showCard==1){
					if(!empty($promo_row["img_canvas_gift_swarm_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_gift_swarm_520x560"] . ");";		
					}
			}
	}
	 if(  $fbmo_is_entered ){
			$purchaseStatus = $_REQUEST['purchase'];
			$promoteStatus = $_REQUEST['promote'];
			$emailStatus = $_REQUEST['email'];
			if( $rulesReg == 1 )
			{
					if(!empty($promo_row["img_canvas_rulesregs_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_rulesregs_sweepstakes_520x560"] . ");";		
					}
			}
			else if($showCoupon==1){
					if(!empty($promo_row["img_canvas_dialogbg_sweepstakes_10x10"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_dialogbg_sweepstakes_10x10"] . ");";		
					}
			}
			else if($showCard==1){
					if(!empty($promo_row["img_canvas_gift_swarm_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_gift_swarm_520x560"] . ");";		
					}
			}
			else{
					
			
			 if( isset( $purchaseStatus ) )
			{
				if( $purchaseStatus == "incomplete" )
				{
					if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";		
					}
				}
				else if( $purchaseStatus == "complete" )
				{
					if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";		
					}
				}
			}
			else if( isset( $promoteStatus ) )
			{
				if( $promoteStatus == "complete" && $_REQUEST['action']!="mail_sent")
				{
					if(!empty($promo_row["img_canvas_promote_swarm_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_promote_swarm_520x560"] . ");";		
					}
				}else if( $_REQUEST['action']=="mail_sent"){
					if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";		
					}
				
				}
			}
			else if( $unitsPurchased > 0  )
			{
					if(!empty($promo_row["img_canvas_alreadyentered_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_alreadyentered_sweepstakes_520x560"] . ");";		
					}
			}
			
			else
			{
				
				 if(!empty($promo_row["img_canvas_enter_swarm_520x560"]))
				{
					echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_swarm_520x560"] . ");";	
				}
			}
			}
			}
			if( $rulesReg == 1 )
			{
					if(!empty($promo_row["img_canvas_rulesregs_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_rulesregs_sweepstakes_520x560"] . ");";		
					}
			}
			 if($_REQUEST['action']=="show_coupon"){
					if(!empty($promo_row["img_canvas_dialogbg_sweepstakes_10x10"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_dialogbg_sweepstakes_10x10"] . ");";		
					}
			}
			 if($showCard==1){
					if(!empty($promo_row["img_canvas_gift_swarm_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_gift_swarm_520x560"] . ");";		
					}
			}
			 if( $_REQUEST['promote'] == "complete" )
				{
					if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";		
					}
				}
			
?>
	background-position:top;
	background-repeat:no-repeat;
	vertical-align:left;
}
#footer	{
<?php if(!empty($promo_row["footer_image"])): ?>
	background-image:url(<?php echo($promo_image_uploads.$promo_row["footer_image"]);?>);
	background-position:top;
	vertical-align:left;
	
<?php endif; ?>
}
#header li a{
	text-decoration:none;
	font-size:12px;
	color:#<?php echo $promo_row["link_text_color"];?>
}
#entrypopup, #systemmsg{
	    background:#<?php echo $promo_row["system_message_bg_color"];?> /*This Background Color will be set by the user in the Wordpress Admin*/

}

</style>
<body style="padding:0px; margin:0px;overflow:hidden;">

<?php include_once( ABSPATH . '/facebookapp/includes/js_init.php')?>
<div id="container">
    <div id="header">
            <ul>
                <li><a href="<?php if($promoStatus == "closed"){ echo "#";}else{ echo ($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?facebook_page=Main<?php }?>">Home</a></li>
             <li>						
<a href="<?php if($promoStatus == "closed"){ echo "#";}else{echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=show_payment&facebook_page=Main<?php }?>">
Purchase</a>
				</li>
                <li><a href="<?php if($promoStatus == "closed"){ echo "#";}else{echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?promote=complete&facebook_page=Main<?php }?>">
Promote</a></li>
                <?php
				 if(  $promoStatus == "closed" && $purchasedSoFar>0)
				{
					
					if($toGoForTipping==0){
					?>
                <li><a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=show_coupon&facebook_page=Main">Coupon</a><!--This link is conditional and will only show after promo has ended
                & if user purchased & tipping point was reached --></li>
                <?php
					}elseif($toGoForTipping>0){
				?>
                <li><a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=show_gift_card&facebook_page=Main">Gift Card</a><!--This link is conditional and will only show after promo has ended
                & if user purchased & tipping point was reached --></li>
                <?php
					}
				}
				  ?>
                <li><a href="<?php  if($promoStatus == "closed"){ echo "#";}else{echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=show_rules&facebook_page=Main<?php }?>" >Rules / Regs</a></li>
            </ul>
        </div>
    <div id="content">
    			<?php
				//if($_REQUEST['action']== "email"){
				?>
				<div id="entrypopup" style="visibility:hidden">
                    <div class="enterform">
                            <script>
							function valFormEmailFrend(){
								if(document.emailForm.email.value==""){
									alert("Please enter your email.");
									return false;
								}
								if(document.emailForm.friend_email.value==""){
									alert("Please enter your friend's email.");
									return false;
								}
								if(document.emailForm.msg.value==""){
									alert("Please enter some message.");
									return false;
								}
								return true;
							}
							</script>
                        	<form name="emailForm" method="get" onsubmit="return valFormEmailFrend()" action="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?facebook_page=Main&promote=complete">
                            <table width="100%" cellpadding="5" cellspacing="5" border="0">
                            <input type="hidden" name="promoId" value="<?php echo $promo_row["promo_id"]?>" />
                            <tr>
                            <td>
                             Your Email:
                             </td>
                             <td>
                             <input type="text" name="email" />
                             </td>
                             </tr>
                             <tr>
                             <td>
                             Friends Email:
                             </td>
                             <td>
                             <input type="text" name="friend_email" />
                             </td>
                             </tr>
                             <tr>
                             <td>
                             Message:
                             </td>
                             <td>
                             <textarea name="msg" /></textarea>
                             </td>
                             </tr>
                             <tr>
                             <td colspan="2">
                          
                             <input type="submit" name="submit" value="Send" />
                             <input type="button" name="submit" value="Close" onclick="document.getElementById('entrypopup').style.visibility='hidden';" />
                            </td>
                            </tr>
                            </table>
                            </form>
                    </div><!--End of enterform div  -->
                </div>
                <?php
				//}
				?>
                <?php
				if($_REQUEST['action']== "show_payment"){
				?>
				<div class="generic_dialog pop_dialog app_content_135607783795" tabindex="0" role="alertdialog" aria-labelledby="title_dialog_0" style="">
                    <div class="generic_dialog_popup" style="top: 125px; width: 467px;">
                    <div class="pop_container_advanced">
                    <div id="app_content_1302794790374" class="pop_content">
                    <h2 id="title_dialog_0" class="dialog_title">
                    <span>Checkout</span>
                    </h2>
                    <div class="dialog_content">
                        <div class="dialog_body">
                            <form name="giftForm" method="post">
                            <input type="hidden" name="promo_page" value="<?php echo $promo_row['promo_page']?>">
                            <p>Is this a gift? <input name="gift"  type="radio" value="yes" />Yes <input name="gift" type="radio" value="no" />No</p> 
                            <p>*If Yes, proceed below:</p>
                            <hr />
                            <?php
							$friend_arr = $fbmo->get_friend_list();
							//print_r($friend_arr);
							$friend_arr_name = array();
							for($j=0;$j<count($friend_arr);$j++){
									array_push($friend_arr_name, $friend_arr[$j]["name"]);
								}
							sort($friend_arr_name);
							?>
                            <p>Who would you like to gift this deal to:
                            <select name="card_name">
                           	 <option value="">Select Friend</option>
                            	<?php
								for($i=0;$i<count($friend_arr_name);$i++){
								?>
									<option value="<?php echo $friend_arr_name[$i]?>">
									<?php echo $friend_arr_name[$i]?>
                                    </option>
								<?php
                            	}
                            	?>
                            </select><!--Upon typing in the text field...it will populate with the user's friends list. --></p>
                            <p>Gift Recipient's email address: <textarea name="card_email" cols="25" rows="1"></textarea></p>
                            <hr />
                            <p><input name="terms" type="checkbox" value="1" />I understand the <a href="<?php echo get_option("siteurl")?>/facebookapp/facebookapp.php?promo_id=<?php echo $promo_row['promo_id']?>&action=show_rules&facebook_page=Main" >Rules & Regulations</a> <!--Hyperlink Rules and Regs to link to the rules and regs page --></p>
                            <p><input name="Purchase" type="button" value="Purchase"  onclick='checkTerms();' /></p>
                            </form>
                       		<script>
							function checkTerms(){
								var gift_value="no";
								// Loop from zero to the one minus the number of radio button selections
								for (counter = 0; counter < document.giftForm.gift.length; counter++)
								{
								if (document.giftForm.gift[counter].checked)
								 gift_value = document.giftForm.gift[counter].value;
								}
								if(gift_value == "yes"){
								if(document.giftForm.card_name.value==""){
										alert("Please select some friend from list.");
									return;
								}
								if(document.giftForm.card_email.value==""){
									alert("Email field is mandatory.");
									return;
								}
								else{
										if(document.giftForm.terms.checked){
											window.open("<?php echo get_option("siteurl")?>/facebookapp/facebookapp.php?promo_id=<?php echo $promo_row['promo_id']?>&action=save_purchase_swarm&facebook_page=Main",
								null, "height=900,width=850,top=80,left=120,status=yes,toolbar=no,menubar=no,location=no");
									  document.giftForm.submit();
										}else{
											alert("Please agree to terms and conditons before proceeding.");
											return false;
										}
								}
								}else{
									if(document.giftForm.terms.checked){
											window.open("<?php echo get_option("siteurl")?>/facebookapp/facebookapp.php?promo_id=<?php echo $promo_row['promo_id']?>&action=save_purchase_swarm&facebook_page=Main",
								null, "height=560,width=750,top=80,left=120,status=yes,toolbar=no,menubar=no,location=no");
									  document.giftForm.submit();
										}else{
											alert("Please agree to terms and conditons before proceeding.");
											return false;
										}
								}
							}
							</script>
                            <div id="app135607783795_checkoutflowDiv" fbcontext="a97eb75c27b1">
                                <form id="app135607783795_checkoutaddress" fbcontext="a97eb75c27b1" onsubmit="fbjs_sandbox.instances.a135607783795.bootstrap();return fbjs_dom.eventHandler.call([fbjs_dom.get_instance(this,135607783795),function(a135607783795_event) {a135607783795_validate_form($FBJS.ref(this)); return false;},135607783795],new fbjs_event(event));" method="post">
                                <input type="hidden" value="104838196877" name="fb_sig_profile">
                                <input type="hidden" value="0" name="fb_sig_is_admin">
                                <input type="hidden" value="PAGE" name="fb_sig_type">
                                <input type="hidden" value="1" name="fb_sig_is_fan">
                                <input type="hidden" value="en_US" name="fb_sig_locale">
                                <input type="hidden" value="1" name="fb_sig_in_new_facebook">
                                <input type="hidden" value="1302794787.6074" name="fb_sig_time">
                                <input type="hidden" value="1" name="fb_sig_added">
                                <input type="hidden" value="1292522498" name="fb_sig_profile_update_time">
                                <input type="hidden" value="1302800400" name="fb_sig_expires">
                                <input type="hidden" value="26501974" name="fb_sig_user">
                                <input type="hidden" value="2.01XGZlKT792059_orf_ulQ__.3600.1302800400.0-26501974" name="fb_sig_session_key">
                                <input type="hidden" value="us" name="fb_sig_country">
                                <input type="hidden" value="104838196877" name="fb_sig_page_id">
                                <input type="hidden" value="1" name="fb_sig_page_added">
                                <input type="hidden" value="7b05f2b518a1b80c002c9fcd437bd4b4" name="fb_sig_api_key">
                                <input type="hidden" value="135607783795" name="fb_sig_app_id">
                                <input type="hidden" value="7f4d94d62b5145ebdc2eb19f42e7bf99" name="fb_sig">
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                <?php
				}
				?>
			    <div class="universallinks">
					<ul>
						<li><!-- universal link two-->
							<a href="<?php echo $promo_row["link_universal_two"]?>" target="_blank" title="<?php echo($promo_row["link_anchortext_universal_two"])?>">
								<?php echo($promo_row["link_anchortext_universal_two"])?>
							</a>
						</li>
                        <li><!-- universal link one -->
							<a href="<?php echo($promo_row["link_universal_one"])?>" target="_blank" title="<?php echo($promo_row["link_anchortext_universal_one"])?>">
								<?php echo($promo_row["link_anchortext_universal_one"])?>
							</a>
						</li>
					</ul>
				</div> 
		<p class="enterlink3"><a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=save_entry"><img src="images/transparent.gif"/></a></p>
        <p class="sponsorlink">
					<a href="<?php echo $promo_row["link_sponsor_one"]?>" target="_blank">
                <img src="<?php echo $promo_image_uploads."transparent.gif"?>">
            </a>
        </p>
		<?php if( $userAccessDenied == true ){ ?>
        	<div class="accessdenied">
            	Can not proceed without having access to user information.
            </div>
        <?php } ?>   
   
        <?php if ($is_fan and  !$fbmo_is_entered)
		{	
		?>
        <!--    <p class="enterlink">
                <a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=validateuser">
                    Enter<img src="<?php echo $promo_image_uploads."transparent.gif"?>">
                </a>
            </p>-->
        <?php 
		} 
		
		/* if purchase has been completed/cancelled */
		$purchaseStatus = $_REQUEST['purchase'];
		$showPromote = false;
		if( isset( $purchaseStatus) )
		{
			$showPromote = true;
		}
		if( $promoteStatus == "complete"    )
		{
			$showPromote = true;
		?>
        	<!--<div class="messagearea">
        
        <?php		
		}
		if( $purchaseStatus == "incomplete" )
		{
		?>
		<?php	
		}
		else if( $purchaseStatus == "complete" )
		{
		?>
         <div class="tippingpoint">
				
					<p class="bold">Tipping Point for this Promotion: <?php echo $tippingPoint;?></p>
					<p class="bold">Purchased so far: <?php echo $totPurchasedSoFar; ?></p>
					<p class="small"><?php echo $toGo; ?></p>
				</div>	 
       		<?php	
        }
		
		if( $showPromote )
		{
		
			
		?>
        	 <div class="clickarea">
					<p class="purchase">
                    	<a href="#" onclick="publishEntry();">
<!--						<a href="<?php //echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?facebook_page=Main&action=share" class="purchasebtn">
-->					
             <div class="clickarea">		
            <p class="purchase" >
            
                                    <a href="#" onclick="publishEntry();">		
                                    <img style="border:none;" src="<?php echo $promo_image_uploads."transparent.gif";?>" width="185px" height="60px"/></a>
                                </p>
                                <p class="purchase" >
                                     <a  onclick="document.getElementById('entrypopup').style.visibility='visible';"  class="purchasebtn">
                                        <img style="border:none;" src="<?php echo $promo_image_uploads."transparent.gif";?>" width="185px" height="60px"/>
                                    </a>
                                </p>
                            </div> 
                <div class="tippingpoint">
					<p class="bold">Tipping Point for this Promotion1: <?php echo $tippingPoint;?></p>
					<p class="bold">Purchased so far: <?php echo $totPurchasedSoFar; ?></p>
					<p class="small"><?php echo $toGo; ?></p>
				</div>	
        <?php
		}
		else if( $promoStatus != "closed" &&   $fbmo_is_entered  )
		{
			if($rulesReg==1){
			?>
            <div id="rulesregs">
                <h3>Rules & Regulations</h3>
                <p><?php echo $promo_row["promo_rules"];?></p>
            </div>
            <?php
			}else{
			/* if units have already been purchased */
				if(  $unitsPurchased > 0  )
				{
				?>
				  <div class="alreadypurchased">
					<p>
						You have purchased  
						<?php 
						echo $unitsPurchased; 
						if($unitsPurchased == 1)
							echo " unit";
						else
							echo " units";
						?>.
						Click <strong>Purchase</strong> to buy additional units.
					</p>
				</div>
				<?php 
				}
			}
			/* if entered and coming to purchase / promote first time */
			if( $rulesReg!=1 )
			{
			?>
				<!-- if purchase is complete/incomplete -->
				
				<!-- -->
				<!--03 . Purchase / Promote -->
			   <div class="clickarea">
					<p class="purchase">
                   <a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=show_payment&facebook_page=Main">
                            <img src="<?php echo $promo_image_uploads."transparent.gif";?>" class="purchasebtn" border="0"/>
</a>
					</p>
					<p class="purchase">
						<a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?promote=complete&facebook_page=Main">
                            <img src="<?php echo $promo_image_uploads."transparent.gif";?>" class="purchasebtn" border="0"/>
						</a>
					</p>
				</div> 
				<div class="tippingpoint">
					<p class="bold">Tipping Point for this Promotion: <?php echo $tippingPoint;?></p>
					<p class="bold">Purchased so far: <?php echo $totPurchasedSoFar; ?></p>
					<p class="small"><?php echo $toGo; ?></p>
				</div>	
			<?php
			}
		}
		else if( $promoStatus == "closed" )
		{
			if($showCoupon==1){
			?>
				<div class="tippingpoint">
                <p><?php echo $fbmo_is_entered_array['name'];?> has purchased this deal <?php echo $unitsPurchased;?>times.</p>
                <p><?php echo $fbmo_is_entered_array['gift_card_friends_name'];?> was gifted this deal by <?php echo $fbmo_is_entered_array['name'] ?></p>
        </div>
        <?php
			}
			else if($showCard==1){
			?>
            <div class="tippingpoint">
                 <p style="padding-top:370px; padding-left:55px;">
                 <?php echo $fbmo_is_entered_array['name'];?> has $ <?php echo $unitsPurchased*$promo_row["promo_prodserv_sale_price"];?> in store credit.
                 </p></div>
            <?php
			}else{
		?>
		<div class="tippingpoint">
        		<p >This Promo has ended.</p>
                <p >The Tipping Point 
                <?php 
					echo " (". $tippingPoint. ")";
					if( $toGoForTipping <= 0 )
						echo " was reached with ";
					else 
						echo " was not reached with ";
					echo $totPurchasedSoFar . " purchases.";
				?></p>
            </div>	
        <?php	
			}
		}
		?>  
	</div><!--End of content div  -->
        <div id="footer">
        	<div class="footersharebtn">
            	<a href="#" onclick="publishEntry();"><img src="<?php echo get_option('siteurl')?>/facebookapp/ui_images/sharebtn.png" border="0" /></a>	
            </div>
          <!--  <div class="footersendbtn">
            	<fb:send href="<?php echo $fbmo->getInviteLink();?>" font="verdana"></fb:send>
            </div>-->
            <div class="footerlikebtn">
            	<?php fbm_show_like_button();?>
            </div>
            <div class="clearer"></div>
        </div><!--End of footer div  -->
	<p class="universalcopyright">NO PURCHASE NECESSARY. Void where prohibited. This promotion is in no way sponsored, endorsed or administered by, or associated with, Facebook. You understand that you are not providing information to Facebook. The information you provide will only be used to administer this promotion. </p>
	<p class="footercopyright">&copy; Copyright <?php echo $page_name = $fbmo->get_page_name();?></p>
</div><!--End of container div  -->
    <?php include_once( ABSPATH . '/facebookapp/includes/js_stream.php')?>

</body>
</html>