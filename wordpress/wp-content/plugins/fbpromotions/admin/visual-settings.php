<div class="wrap">
	<div id="form1" >
		<form id="promoform" name="promoform" method="post" enctype="multipart/form-data" onsubmit="return valscreenvisual();">
			<input type="hidden" name="fb_edit_action" value="save_visuals" />
			<input type="hidden" name="page" value="fbm_edit_promo" />
			<input type="hidden" name="fbm_promo_heading" value="<?php echo($_REQUEST["fbm_promo_type"])?>" />
			<input type="hidden" name="fbm_next_step" value="notification" />
			<input type="hidden" name="promo_layout" value="default" />
			<?php if(isset($_REQUEST["promo_id"])): ?>
			<input type="hidden" name="promo_id" value="<?php echo($_REQUEST["promo_id"]); ?>"/>
			<?php endif; ?>
			
			<table class="tbl" width="100%" border="0" cellspacing="0" cellpadding="10">
				<tr>
					<td colspan="2">
							<img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" >
					</td>
				</tr>
				<tr>
					<td colspan="2" class="pagetitle">
						STEP <?php echo($_REQUEST["fbm_step"])?> &raquo;<?php echo($_REQUEST["fbm_promo_heading"])?> VISUALS
					</td>
				</tr>
				<tr> 
					<td colspan=2>
						<div id="branded_visuals" style="visibility:visible; overflow:hidden; ">
                            <table width="100%">
								<tr valign="top">
									<td width="25%">
									Header & Footer Text Color &raquo;
									</td>
									<td width="75%" >
                                        <input type="text" name="text_color" class="color" value="<?php if(!empty($fbmo->promo_row["text_color"])){ echo $fbmo->promo_row["text_color"]; }else{?>000000<?php } ?>" />
										<a  data-tooltip="sticky1"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <div id="mystickytooltip" class="stickytooltip">
                                            <div style="padding:5px">
                                                    <div id="sticky1" class="atip" style="width:300px">
														This text is system text that will be placed over your graphical template.  Be sure to specify a color that works well with your graphics.                                                      </div>
                                                    <div id="sticky2" class="atip" style="width:300px">
														This is the color of links, that will be placed over your graphical template.  Be sure to specify a color that works well with your graphics.                                                          </div>
                                                    <div id="sticky3" class="atip" style="width:300px">
															This background color will be used at a 97% transparency for all system messages.   
                                                     </div>
                                                    <div id="sticky4" class="atip" style="width:300px">
														Corresponds to layer in Photoshop template.  All users are required to "like" this page before participation. This screen serves as the like-gate.                                       </div>   
                                                     <div id="sticky5" class="atip" style="width:300px">
															Corresponds to layer in Photoshop template.  This screen is presented to the standard Facebook "allow app access" dialogue. We suggest giving users an expectation of how this action will be beneficial to them.                                                    </div>  
   													<div id="sticky6" class="atip" style="width:300px">
																Corresponds to layer in Photoshop template. 
                                                     </div>
                                                     <div id="sticky7" class="atip" style="width:300px">
																Corresponds to layer in Photoshop template.  This 90 x 90 pixel image is used in the Facebook newsfeed.
                                                     </div>
                                                     <div id="sticky8" class="atip" style="width:300px">
                                                     Corresponds to layer in Photoshop template.  This screen gives users the option to browse entries or enter the contest themselves.
                                                     </div>
                                                      <div id="sticky9" class="atip" style="width:300px">
                                                     Corresponds to layer in Photoshop template.  This screen gives users the option to browse entries or enter the contest themselves.
                                                     </div>
                                                      <div id="sticky10" class="atip" style="width:300px">
                                                     Corresponds to layer in Photoshop template.  This screen gives users the option to browse entries or enter the contest themselves.
                                                     </div>
                                                      <div id="sticky11" class="atip" style="width:300px">
															This screen presents the option to purchase.
                                                     </div>
                                                      <div id="sticky12" class="atip" style="width:300px">
																This screen is presented if the user has already pruchased.                                                    
                                                       </div>
                                                      <div id="sticky13" class="atip" style="width:300px">
                                                     Corresponds to layer in Photoshop template.  This screen gives users the option to browse entries or enter the contest themselves.
                                                     </div>
                                                      <div id="sticky14" class="atip" style="width:300px">
														Issued if the tipping point is reached.                                                     
                                                      </div>
                                                      <div id="sticky15" class="atip" style="width:300px">
                                                            Issued if the tipping point is not reached.                                                     
                                                       </div>
												 </div>
                                          	  <div class="stickystatus"></div>
                                        </div>                                   
                                         <script type="text/javascript" src="<?php  echo $promo_date_cal?>/jscolor.js"></script>
										</td>
								</tr>
								
							    <tr valign="top">
								      <td>Header &amp; Footer Link Color  &raquo; </td>
								      <td>
                                      <input type="text" name="link_text_color" class="color" value="<?php if(!empty($fbmo->promo_row["link_text_color"])){ echo $fbmo->promo_row["link_text_color"]; }else{?>000000<?php } ?>" />
                                      <a  data-tooltip="sticky2"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                      </td>
						      </tr>
                               <tr valign="top">
								      <td>Background Screen Color  &raquo; </td>
								      <td>
                                      <input type="text" name="system_message_bg_color" class="color" value="<?php if(!empty($fbmo->promo_row["system_message_bg_color"])){ echo $fbmo->promo_row["system_message_bg_color"]; }else{?>000000<?php } ?>" />
                                      <a  data-tooltip="sticky3"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                      </td>
						      </tr>
                            <?php
							if($_REQUEST['promo_type']==1){
							?>
                            <tr valign="top">
									<td width="25%">
										Image: "LIKE THIS PAGE"&raquo;
									</td>
									<td width="75%">
										<input name="img_profiletab_requirelike" type="file" id="img_profiletab_requirelike" size="50" />
										<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_profiletab_requirelike_hidden" value="<?php if(!empty($fbmo->promo_row["img_profiletab_requirelike"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_profiletab_requirelike"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_profiletab_requirelike"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_profiletab_requirelike" />Remove
										<?php endif; ?>
									</td>
								</tr>
							    <tr valign="top">
									<td width="25%">
										Image: "ALLOW APP ACCESS"&raquo;
									</td>
                                   
									<td width="75%">
										<input name="img_profiletab_promobanner" type="file" id="img_profiletab_promobanner`" size="50" />
										<a  data-tooltip="sticky5"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_profiletab_promobanner_hidden" value="<?php if(!empty($fbmo->promo_row["img_profiletab_promobanner"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_profiletab_promobanner"])): ?>
										<br />
										Uploaded:&nbsp;<?php echo $fbmo->promo_row["img_profiletab_promobanner"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_profiletab_promobanner" />Remove
										<?php endif; ?>
									</td>
								</tr>
								
								<tr valign="top">
									<td width="25%">
										Image: "ENTER"&raquo;
            </td>
                                     
									<td width="75%">
										<input name="img_canvas_enter_sweepstakes_520x560" type="file" id="img_canvas_enter_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_enter_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_enter_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
										Image: "ALREADY ENTERED"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_alreadyentered_sweepstakes_520x560" type="file" id="img_canvas_alreadyentered_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_alreadyentered_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_alreadyentered_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_alreadyentered_sweepstakes_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_alreadyentered_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_alreadyentered_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td> 
								</tr>
								<tr valign="top">
									<td width="25%">
									Image: "PROMOTION ENDED"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_closed_sweepstakes_520x560" type="file" id="img_canvas_closed_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_closed_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"])): ?>
										<br />Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_closed_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<!--<tr valign="top">
									<td width="25%">
									Image: "RULES and REGS"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_rulesregs_sweepstakes_520x560" type="file" id="img_canvas_rulesregs_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_rulesregs_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"])): ?>
										<br />Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_canvas_rulesregs_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>-->
								
								<tr valign="top">
									<td width="25%">
									Image: "STREAM PUBLISH"&raquo;
									</td>
									<td width="75%">
										<input name="img_streampublish_90x90" type="file" id="img_streampublish_90x90" size="50" />
										<a  data-tooltip="sticky7"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_streampublish_90x90_hidden" value="<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_streampublish_90x90"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_streampublish_90x90" />Remove
										<?php endif; ?>
									</td> 
								</tr>
								<!-- ADDED BY PREET but not needed. Remove once determine where files being used CVHJ -->
                            <?php
							}elseif($_REQUEST['promo_type']==2){
							?>
							    
								<tr valign="top">
									<td width="25%">
										Image: "LIKE THIS PAGE"&raquo;
									</td>
									<td width="75%">
										<input name="img_profiletab_requirelike" type="file" id="img_profiletab_requirelike" size="50" />
										<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_profiletab_requirelike_hidden" value="<?php if(!empty($fbmo->promo_row["img_profiletab_requirelike"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_profiletab_requirelike"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_profiletab_requirelike"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_profiletab_requirelike" />Remove
										<?php endif; ?>
									</td>
								</tr>
                                <tr valign="top">
									<td width="25%">
										Image: "ALLOW APP ACCESS"&raquo;
									</td>
									<td width="75%">
										<input name="img_profiletab_promobanner" type="file" id="img_profiletab_promobanner`" size="50" />
										<a  data-tooltip="sticky5"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_profiletab_promobanner_hidden" value="<?php if(!empty($fbmo->promo_row["img_profiletab_promobanner"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_profiletab_promobanner"])): ?>
										<br />
										Uploaded:&nbsp;<?php echo $fbmo->promo_row["img_profiletab_promobanner"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_profiletab_promobanner" />Remove
										<?php endif; ?>
									</td>
								</tr>
                                <tr valign="top">
									<td width="25%">
										Image: "HOME"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_enter_swarm_520x560" type="file" id="img_canvas_enter_swarm_520x560" size="50" />
                                        <input type="hidden" name="img_canvas_enter_swarm_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_enter_swarm_520x560"])) echo 1; else echo 0;?>" />
										<a  data-tooltip="sticky8"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
										<?php if(!empty($fbmo->promo_row["img_canvas_enter_swarm_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_enter_swarm_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_enter_swarm_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
                                <tr valign="top">
									<td width="25%">
										Image: "PROMOTE ENTRY"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_enter_sweepstakes_520x560" type="file" id="img_canvas_enter_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky10"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_enter_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_enter_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
                                <tr valign="top">
									<td width="25%">
								      Image: "BROWSE ENTRIES"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_browse_buzz_520x560" type="file" id="img_canvas_browse_buzz_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_browse_buzz_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_browse_buzz_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_browse_buzz_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_browse_buzz_520x560"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_browse_buzz_520x560" />Remove
										<?php endif; ?>
									</td> 
								</tr>
								
								
								<tr valign="top">
									<td width="25%">
										Image: "PROMOTION ENDED"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_closed_sweepstakes_520x560" type="file" id="img_canvas_closed_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_closed_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"])): ?>
										<br />Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_closed_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
										Image: "RULES and REGS"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_rulesregs_sweepstakes_520x560" type="file" id="img_canvas_rulesregs_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_rulesregs_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"])): ?>
										<br />Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_canvas_rulesregs_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
									Image: "STREAM PUBLISH"&raquo;
									</td>
									<td width="75%">
										<input name="img_streampublish_90x90" type="file" id="img_streampublish_90x90" size="50" />
										<a  data-tooltip="sticky7"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_streampublish_90x90_hidden" value="<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_streampublish_90x90"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_streampublish_90x90" />Remove
										<?php endif; ?>
									</td> 
								</tr>
                            <?php
							}
							elseif($_REQUEST['promo_type']==8){
							?>
							    
								<tr valign="top">
									<td width="25%">
										Image: "LIKE THIS PAGE"&raquo;
									</td>
									<td width="75%">
										<input name="img_profiletab_requirelike" type="file" id="img_profiletab_requirelike" size="50" />
										<a  data-tooltip="sticky4"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_profiletab_requirelike_hidden" value="<?php if(!empty($fbmo->promo_row["img_profiletab_requirelike"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_profiletab_requirelike"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_profiletab_requirelike"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_profiletab_requirelike" />Remove
										<?php endif; ?>
									</td>
								</tr>
                                <tr valign="top">
									<td width="25%">
										 Image: "ALLOW APP ACCESS"&raquo;
									</td>
									<td width="75%">
										<input name="img_profiletab_promobanner" type="file" id="img_profiletab_promobanner`" size="50" />
										<a  data-tooltip="sticky5"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_profiletab_promobanner_hidden" value="<?php if(!empty($fbmo->promo_row["img_profiletab_promobanner"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_profiletab_promobanner"])): ?>
										<br />
										Uploaded:&nbsp;<?php echo $fbmo->promo_row["img_profiletab_promobanner"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_profiletab_promobanner" />Remove
										<?php endif; ?>
									</td>
								</tr>
                                <tr valign="top">
									<td width="25%">
										Image: "HOME"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_enter_swarm_520x560" type="file" id="img_canvas_enter_swarm_520x560" size="50" />
										<a  data-tooltip="sticky11"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_enter_swarm_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_enter_swarm_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_enter_swarm_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_enter_swarm_520x560"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_browse_buzz_520x560" />Remove
										<?php endif; ?>
									</td> 
								</tr>
                                <tr valign="top">
									<td width="25%">
										Image: "ALREADY PURCHASED"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_alreadyentered_sweepstakes_520x560" type="file" id="img_canvas_alreadyentered_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky12"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_alreadyentered_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_alreadyentered_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_alreadyentered_sweepstakes_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_alreadyentered_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_alreadyentered_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td> 
								</tr>
								<tr valign="top">
									<td width="25%">
										Image: "PROMOTE ENTRY"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_enter_sweepstakes_520x560" type="file" id="img_canvas_enter_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_enter_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_enter_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_enter_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
										Image: "COUPON"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_dialogbg_sweepstakes_10x10" type="file" id="img_canvas_dialogbg_sweepstakes_10x10" size="50" />
										<a  data-tooltip="sticky14"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_dialogbg_sweepstakes_10x10_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_dialogbg_sweepstakes_10x10"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_dialogbg_sweepstakes_10x10"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_dialogbg_sweepstakes_10x10"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_dialogbg_sweepstakes_10x10" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
										Image: "PROMOTION ENDED"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_closed_sweepstakes_520x560" type="file" id="img_canvas_closed_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_closed_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"])): ?>
										<br />Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_closed_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br />
										<input name="fbm_file_remove[]" type="checkbox" value="img_canvas_closed_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
										Image: "RULES and REGS"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_rulesregs_sweepstakes_520x560" type="file" id="img_canvas_rulesregs_sweepstakes_520x560" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_canvas_rulesregs_sweepstakes_520x560_hidden" value="<?php if(!empty($fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"])): ?>
										<br />Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_rulesregs_sweepstakes_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_canvas_rulesregs_sweepstakes_520x560" />Remove
										<?php endif; ?>
									</td>
								</tr>
								<tr valign="top">
									<td width="25%">
									Image: "FEED GRAPHIC"&raquo;
									</td>
									<td width="75%">
										<input name="img_streampublish_90x90" type="file" id="img_streampublish_90x90" size="50" />
										<a  data-tooltip="sticky6"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_streampublish_90x90_hidden" value="<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_streampublish_90x90"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_streampublish_90x90" />Remove
										<?php endif; ?>
									</td> 
								</tr>
                                <tr valign="top">
									<td width="25%">
									Image: "STORE CREDIT"&raquo;
									</td>
									<td width="75%">
										<input name="img_canvas_gift_swarm_520x560" type="file" id="img_canvas_gift_swarm_520x560" size="50" />
										<a  data-tooltip="sticky15"><img src="<?php echo($promo_image_url);?>/question.jpg" width="16" height="16" align="top" /></a>
                                        <input type="hidden" name="img_streampublish_90x90_hidden" value="<?php if(!empty($fbmo->promo_row["img_streampublish_90x90"])) echo 1; else echo 0;?>" />
										<?php if(!empty($fbmo->promo_row["img_canvas_gift_swarm_520x560"])): ?>
										<br />
										Uploaded:&nbsp;
										<?php echo $fbmo->promo_row["img_canvas_gift_swarm_520x560"]; ?>&nbsp;&nbsp;&nbsp;
										<br /><input name="fbm_file_remove[]" type="checkbox" value="img_canvas_gift_swarm_520x560" />Remove
										<?php endif; ?>
									</td> 
								</tr>
                            <?php
							}
							?>
                                                            <tr>
									<td colspan="2" bgcolor="#CCCCCC" class="pagetitle">
                                        <input type="button" name="Back" value="Back" onclick="window.history.back()" /> 
                                        <input type="submit" name="Submit" value="<?php  if($_REQUEST['fb_edit_action']=="add"){?>PROCEED TO NEXT STEP<?php }else{?>SAVE AND PROCEED<?php }?> &raquo;" />
									</td>
								</tr>
								
							</table>

						</div>
					</td>
				</tr>
			</table>
		</form>
        	<?php do_html_admin_footer("admin_b_hive", "The B(gv) Hive") ?>

	</div>
</div>
<script>
function valscreenvisual(){
/*
	if(document.promoform.img_profiletab_requirelike_hidden.value==0 && document.promoform.img_profiletab_requirelike.value==""){
		alert("Please select Like Graphics.");
		return false;
	}
	if(document.promoform.img_profiletab_promobanner_hidden.value==0 && document.promoform.img_profiletab_promobanner.value==""){
		alert("Please select for Allow App Access.");
		return false;
	}
	if(document.promoform.img_canvas_enter_swarm_520x560_hidden ){
		if(document.promoform.img_canvas_enter_swarm_520x560_hidden.value==0 
		&& document.promoform.img_canvas_enter_swarm_520x560.value==""){
			alert("Please select Home Graphics.");
			return false;
		}
	}
		

	if(document.promoform.img_canvas_enter_sweepstakes_520x560_hidden.value==0 && document.promoform.img_canvas_enter_sweepstakes_520x560.value==""){
		alert("Please select Promote Graphics.");
		return false;
	}
	if(document.promoform.img_canvas_alreadyentered_sweepstakes_520x560_hidden.value==0 && document.promoform.img_canvas_alreadyentered_sweepstakes_520x560.value==""){
		alert("Please select Already entered Graphics.");
		return false;
	}
	if(document.promoform.img_canvas_dialogbg_sweepstakes_10x10_hidden && document.promoform.img_canvas_dialogbg_sweepstakes_10x10.value==""){
		if(document.promoform.img_canvas_dialogbg_sweepstakes_10x10_hidden.value==0){
			alert("Please select Coupon Graphics.");
			return false;
		}
	}
		
	if(document.promoform.img_canvas_closed_sweepstakes_520x560_hidden.value==0 && document.promoform.img_canvas_closed_sweepstakes_520x560.value=="" ){
		alert("Please select Promo Close Graphics.");
		return false;
	}
	if(document.promoform.img_canvas_rulesregs_sweepstakes_520x560_hidden.value==0 && document.promoform.img_canvas_rulesregs_sweepstakes_520x560.value=="" ){
		alert("Please select Promo Rules and Regulations Graphics.");
		return false;
	}
	if(document.promoform.img_streampublish_90x90_hidden){
			if(document.promoform.img_streampublish_90x90_hidden.value==0 && document.promoform.img_streampublish_90x90.value=="" ){
			alert("Please select Stream Publish Graphics.");
			return false;
			}
	}*/
	return true;
}
function show_branded()	{
	branded=document.getElementById("branded_visuals");
	branded.style.height="300px";
	branded.style.visibility="visible";
}
function hide_branded()	{
	branded=document.getElementById("branded_visuals");
	branded.style.height=0;
	branded.style.visibility="hidden";
}
</script>