<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><!-- EDITED 1/21 BY CVHJ TODO: Too much to write-->

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
	function comboDivOnBgPos(target,newpos,showdiv){ 
		document.getElementById(target).style.backgroundPosition = (newpos);	
		document.getElementById(showdiv).style.display = "block";
	}
	function comboDivOffBgPos(target,newpos,hidediv){
		document.getElementById(target).style.backgroundPosition = (newpos);	
		document.getElementById(hidediv).style.display = "none";
	}
	function toggleOff(hidediv) {
		document.getElementById(hidediv).style.display = "none";
	}
	function toggleOn(showdiv) {
		document.getElementById(showdiv).style.display = "block";
	}
</script>
<?php
	$rulesReg =0;
	if($_REQUEST['action']=="show_rules")
    $rulesReg = 1;


?>
<!-- TODO: Change wrapper image selection to a switch statement -->
<style type="text/css">
	
	#container { background:url(<?php echo($promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"])?>) top left no-repeat;}
</style>
<?php
if( $rulesReg == 1 )
			{
					if(!empty($promo_row["img_canvas_rulesregs_sweepstakes_520x560"]))
					{
						?>
						<style>
		                #container{ 
						background:url(<?php echo($promo_image_uploads.$promo_row["img_canvas_rulesregs_sweepstakes_520x560"]);?>) top left no-repeat;}
			</style>
            <?php
			}
			}
	?>
<?php if ($fbmo_is_entered && $rulesReg != 1){ ?> <!-- FIX JUST entered here for future -->
	<style>
		#container	{ background:url(<?php echo($promo_image_uploads.$promo_row["img_canvas_alreadyentered_sweepstakes_520x560"]);?>) top left no-repeat;}
	</style>

<?php $body = "<body onload=\"publishShare();\">" ?> 

<?php if ($body) echo $body; ?>

<?php  }?>

<?php if ($promo_row["status"] == "closed"): ?>
	<style>
		#container { background:url(<?php echo($promo_image_uploads.$promo_row["img_canvas_closed_sweepstakes_520x560"]);?>) top left no-repeat;}
	</style>
<!--<meta property="og:title" content="<?php echo($promo_row["promo_name"])?> | <?php echo $fbmo->fboptions["application_name"]?>"/> 
<meta property="og:type" content="Company"/> 
<meta property="og:image" content="<?php show_stream_image()?>"/> 
<meta property="og:url" content="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"])?>.php"/>
<meta property="og:site_name" content="<?php echo($promo_row["promo_name"])?>"/>
<meta property="fb:app_id" content="<?=$fbmo->options['fb_application_id'] ?>"/> 	
-->
<?php $body = "<body>" ?>

<?php if($body) echo $body; ?>


<?php  endif; ?> 
<style>
body	{
	color:#<?php echo($promo_row["text_color"])?>;
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
<?php if (! $body) { echo "<body>" ;} ?>

<?php include_once( ABSPATH . '/facebookapp/includes/js_init.php')?>   

<div id="container">
    <div id="header">
				<!--  Rules and Regs:  TODO: edit rules and regs to be separate page and not mouseover -->
				<ul>
					<li> 
						<a style="color:#<?php echo $promo_row["link_text_color"];?>" target="_self" href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=show_rules&facebook_page=Main" title="See Rules & Regs">Rules:</a>
					</li>
				</ul>
			</div><!-- end of "promo_header" div --> 
          <div id="content">
                 <?php
				 if($rulesReg==1){
				 ?>
				<div id="rulesregs">
                <h3>Rules & Regulations</h3>
                <p><?php echo $promo_row["promo_rules"];?></p>
            </div><!-- end of "rulesregs" div -->
            <?php
				 }
				 ?>
				<div class="universallinks">
					<ul>
                    	<li><!-- universal link two-->
							<a href="<?php echo($promo_row["link_universal_two"])?>" target="_blank" title="<?php echo($promo_row["link_anchortext_universal_two"])?>">
								<?php echo($promo_row["link_anchortext_universal_two"])?>
							</a>
						</li>
						<li> <!-- universal link one -->
							<a href="<?php echo($promo_row["link_universal_one"])?>" target="_blank" title="<?php echo($promo_row["link_anchortext_universal_one"])?>">
								<?php echo($promo_row["link_anchortext_universal_one"])?>
							</a>
						</li>
					
					</ul>
				</div><!-- end of "universallinks" div -->
				<p class="sponsorlink">
					<a href="<?php echo $promo_row["link_sponsor_one"]?>" target="_blank">
						<img src="/facebookapp/ui_images/transparent.gif" />
					</a>
				</p><!-- end of "sponsorlink" p class -->
					<!-- 	Start App Display Logic
						TODO: make db for editing dialog message text 
						TODO: add some way of sharing winner announcement. Maybe "php echo $like_winner_button" 
						TO DO: email input form:  
						<p>If you want us to let you know about deals or contests in the future send us your email address.</p> 
						<form><input type="text" length="30" name="add_email_to_list" value="valid email address"><br /><input type="button" name="submit" onclick="submit"></form>
					-->
				
				
					
				<!-- Check whether promotion has begun -->
				<?php if ($promo_row["status"]=="not_active"): ?>
					<!-- Start promo not yet started display -->
					<div id="entrypopup"> 							
						<h1>
							This promotion has not begun.
						</h1>
						<p>
							Check back:<?php echo($promo_row["promo_start"]);?>
						</p>
					</div><!-- end of "entrypopup" div -->
					<!-- End promo not yet started display -->
					
				<!-- Check whether user has entered promo -->
				<?php elseif ($fbmo_is_entered && $rulesReg!=1): ?>
					<!-- Start successful entry display -->
					<div id="entrypopup" style="visibility:hidden;"> 							
						<h1>
							<a href="#" onclick="publishShare();">
								<img src="/facebookapp/ui_images/btn_fbshare.jpg" border="0" />
							</a>
						</h1> 
						<p>
							You have entered the <?php echo($promo_row["promo_name"])?> promotion using the <?php echo $fbmo->fboptions["application_name"]?> promotions app.
						</p>
						<p>
							<strong>
								Good luck on winning the Prize -<?php echo($promo_row["promo_prize"])?>!
							</strong>
						</p>
					</div><!-- end of "entrypopup" div -->
					<!-- End successful entry display -->
					
				<!-- Check whether user likes designated fan page --> 
				<?php elseif (! $is_fan): ?>
					<!-- Start display when user NOT A FAN -->
					<!--<div id="entrypopup" >
						<h1>
							Please "like" our Fan Page!
						</h1>
						<p>
							<strong>
								Click "Like" below then 
								<a href="<?php echo $_SERVER['PHP_SELF'];?>"> 
									click here to retry.
								</a>
							</strong>
						</p>
						<script src="http://connect.facebook.net/en_US/all.js#xfbml=1" onClick="changeDisplay()"></script>
						<fb:fan profile_id="<?php echo($promo_row["fbid_profile_to_like"]);?>" stream="0" connections="5" logobar="0" width="410px"></fb:fan>
					</div>--><!-- end of "entrypopup" div -->
					<!-- End display when user NOT A FAN -->
				
				<?php elseif ($promo_row["status"]=="active"): ?>
					<?php if(!empty($promo_row["promo_description"])):?>
						<p>
							<?php // echo($promo_row["promo_description"])?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
				<?php
				//$fbmo->options["fb_canvas_url"] = "http://scenictrace.com/facebookapp/";
				?>
				<!-- Start Entry Process -->
				<?php if ($promo_row["status"]=="active"  and !$fbmo_is_entered): ?>
					<p class="enterlink">
						<a href="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=save_entry" target="_self">
							<img src="<?php echo($promo_image_uploads."transparent.gif");?>" />
						</a>
					</p>
				<?php endif; ?>
				<!-- End Entry Processing -->
			</div><!-- end of "promo_content" div -->
	    <div id="footer">
        	<div class="footersharebtn">
            	<a href="#" onclick="publishShare();"><img src="<?php echo get_option('siteurl')?>/facebookapp/ui_images/sharebtn.png" border="0" /></a>	
            </div>
          
            <!--<div class="footersendbtn">
            	<fb:send href="<?php //echo $fbmo->getInviteLink();?>" font="verdana"></fb:send>
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