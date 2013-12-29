<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo($promo_row["promo_name"])?> | <?php echo $fbmo->fboptions["application_name"]?></title>
<link href="<?php echo $promo_stylesheet ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo get_option('siteurl')."/facebookapp/layouts/buzz/"?>jquery.js"></script>
<script type="text/javascript" src="<?php echo get_option('siteurl')."/facebookapp/layouts/buzz/"?>script.js"></script>
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
	function valPopForm(){
		if(document.entry_form.user_email.value==""){
			alert("Please enter your email.");
			return false;
		}
		if(document.entry_form.media_title.value==""){
			alert("Please enter media title.");
			return false;
		}
		return true;
	}
</script>
</head>
<?php  
	$rulesReg =0;
	$browseEntries = 0;
	$my_entry =0;
	if($_REQUEST['action']=="show_rules")
    	$rulesReg = 1;
	if($_SESSION['my_entry']==1 && isset($_SESSION['browse_id']))
   		 $my_entry = 1;
	if(($_REQUEST['action']=="browse_entries" )|| (isset($_SESSION['browse_id'])))
   		     $browseEntries = 1;
	if(($_REQUEST['action']=="browse_entries")  && (!isset($_GET['browse_id']))){
	       unset( $_REQUEST['browse_id'] );
		   unset( $_SESSION['browse_id'] );
	}
	if((isset($_SESSION['browse_id']) ) && (!isset($_REQUEST['browse_id'])))
	        $_REQUEST['browse_id'] = $_SESSION['browse_id'];
	
	 $fbmo_is_entered=0;
	 $is_media_entered_array = $fbmo->get_promo_media_selected($get_promo_id);
	 $is_media_entered = $is_media_entered_array['media_id'];
	 
     $fbmo_is_entered_array=$fbmo->check_promo_entry($get_promo_id);
	 $is_fan=$fbmo->check_entrant_fan_status();
	if(!empty($fbmo_is_entered_array["entry_id"])){
		$fbmo_is_entered=1;
	}
	$time= time()-(5*3600);
	$mdate = date("Y-m-d h:i A",$time);
	$d1 = new DateTime($mdate);
	$end_date = date("Y-m-d h:i A",strtotime($promo_row["vote_end"]));
	$d2 = new DateTime($end_date);
	if ($d2<$d1)   $promoStatus="closed";
	$current_date  = date("Y-m-d");
	$vote_end_date = date("Y-m-d",strtotime($promo_row["vote_end"]));
	$vote_start_date = date("Y-m-d",strtotime($promo_row["vote_start"]));
	$submission_end_date = date("Y-m-d",strtotime($promo_row["promo_end"]));
	if ( $vote_end_date>=$current_date && $vote_start_date<=$current_date)   {
		$votingStatus="started";
		$browseEntries = 1;
	}
	if($votingStatus!="started")
    $days_to_enter = dateDiff($current_date,$submission_end_date);
	else
	 $days_to_enter = dateDiff($current_date,$vote_end_date);

     if($days_to_enter<0)
	$days_to_enter = 0;
	if ($submission_end_date<$current_date)   {
		$submissionStatus="closed";
		if($_REQUEST['action']!="browse_entries" && !empty($is_media_entered))
		$_REQUEST['browse_id'] = $is_media_entered;
	}
	if ($vote_end_date<$current_date)   {
		$votingStatus="closed";
		$browseEntries = 0;
	}
	
	if($days_to_enter<=0){
		$daysNot = " Day";
	}else
	   $daysNot = " Days";
	if($submission_end_date == $current_date)  {
		$days_left = " Last";
	}
	else{
		$days_left = $days_to_enter;
	}
	function dateDiff($start, $end) {
	  $start_ts = strtotime($start);
	  $end_ts = strtotime($end);
	  $diff = $end_ts - $start_ts;
	  return round($diff / 86400);
	}
	
	//print_r($_REQUEST);
?>
<!-- set dynamic css -->
<style>
body	{
	color:#<?php echo($promo_row["text_color"])?>;
}
#container	{
<?php
 if( ! $fbmo_is_entered )
	{
				if(!empty($promo_row["img_profiletab_promobanner"]))
				{
					echo "background-image:url(" . $promo_image_uploads.$promo_row["img_profiletab_promobanner"] . ");";		
				}		
    }else{
	if( $fbmo_is_entered && $browseEntries!=1 && $my_entry!=1 && $votingStatus!="started" && $submissionStatus!="closed")
	{
				
				 if(!empty($promo_row["img_canvas_enter_swarm_520x560"]))
				{
					echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_swarm_520x560"] . ");";	
				}
			}
	if( $browseEntries == 1  && !isset($_REQUEST['browse_id']))
	{
			if(!empty($promo_row["img_canvas_browse_buzz_520x560"]))
			{
				echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_browse_buzz_520x560"] . ");";		
			}
	}
	if( isset($_REQUEST['browse_id']) && $days_to_enter>0){
		if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
		{
			echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";	
		}
	}
	if( $my_entry == 1 && $browseEntries != 1 )
	{
					if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";		
					}
	}
	}
	if(empty($_REQUEST['browse_id']) && ($is_media_entered)){
	           $_REQUEST['browse_id']=$is_media_entered;
			   	if(!empty($promo_row["img_canvas_enter_sweepstakes_520x560"]))
					{
						echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_enter_sweepstakes_520x560"] . ");";		
					}
			   
	}
	if ($promoStatus == "closed")
	{
		if(!empty($promo_row["img_canvas_closed_sweepstakes_520x560"]))
		{
			echo "background-image:url(" . $promo_image_uploads.$promo_row["img_canvas_closed_sweepstakes_520x560"] . ");";		
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
<body>
<?php include_once( ABSPATH . '/facebookapp/includes/js_init.php');
$fbmo->options["fb_canvas_url"] = get_option('siteurl')."/facebookapp/";
?>
<div id="wrapper">
	<div id="header">
    	<div class="headernav">
    	<ul>
        	<li><a href="<?php echo ($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?facebook_page=Main">Home</a></li>
            <?php
			if($votingStatus=="started" || $promoStatus=="closed"){
			?>
        	<li><a  href="<?php echo ($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=browse_entries&facebook_page=Main">Browse Entries</a></li>
        	<?php }
			if(empty($is_media_entered)){ 
				 if( $promoStatus!="closed" && $submissionStatus!="closed" ){
				?>
				 <li><a href="#" onclick="document.getElementById('entrypopup').style.visibility='visible';">Enter</a></li>
				<?php
				}
			}
			?>
        	<li><a  href="#" onclick=" document.getElementById('rulesregspopup').style.visibility='visible';return false;">Rules/Regs</a></li>
            <div class="clearer"></div>
        </ul><!--end of headernav ul-->
        </div><!--end of headernav-->
        <div class="headerlinks" >
        	<a href="<?php echo($promo_row["link_universal_two"])?>" target="_blank" class="headerlink1"></a><!--end of headerlink1-->
        	<a href="<?php echo($promo_row["link_universal_one"])?>" target="_blank" class="headerlink2"></a><!--end of headerlink2-->
            <div class="clearer"></div>
        </div><!--end of headerlinks-->
        <div class="clearer"></div>
        
    </div><!--end of header div-->
   <?php
	if( ! $fbmo_is_entered )
	{
    ?>
     <a href="<?php echo ($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=save_entry">
     <div id="container" class="allowapp">
		<!--<p class="entrelink1">
        <a href="<?php//echo ($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?action=save_entry">Allow App Link<img src="<?php echo get_option('siteurl')."/facebookapp/ui_images/transparent.gif"?>"/></a>
         </p>   --> 
     </div>
     </a>
     
    <?php
	}
	else if($fbmo_is_entered && $promoStatus!="closed")
	{
		if($submissionStatus!="closed" && !isset($_SESSION['browse_id']) && (!$is_media_entered) ){
			//still entry submission period
		?>
		  <!-- 3.HowtoEnter -->
		<!-- The HowtoEnter screen is ONLY shown after the user allows the app and during the VOTING PROCESS. After the Voting Period has ended..users will be sent straight to the BROWSE SCREEN -->
		<div id="container" class="howtoenter">
			<div class="ticker">
				<p><em><?php echo $days_left;?><?php echo $daysNot;?></em>  to Enter</p>
			</div>
			<div class="entrycontainer"  
				<?php if($promo_row["media_type"]=="TEXT ENTRY"){?>
					style="padding:10px;overflow:auto;height:210px; "<?php }?> id="entrycontainer">
					<?php echo  $fbmo->get_youtube_iframe_how_to_enter($promo_row["how_to_video_url"]);?>
			</div><!--end of entrycontainer-->
			<a href="#" class="enterbtn" onclick="document.getElementById('entrypopup').style.visibility='visible';"></a>
		</div><!--end of HowtoEnter div-->
     
		<?php
		}
		//voting started
		else {
			//browse specific entry
			
			if(!empty($_REQUEST['browse_id'])){
						   if($_REQUEST['browse_id'])
							$sel_id = $_REQUEST['browse_id'];
							$media_item_selected=$fbmo->get_promo_media_selected($get_promo_id,$sel_id );
							if($promo_row["media_type"]=="PHOTO UPLOAD"){
								$media =   $media_item_selected["media_url"];
								$media =   (' <img src="'.
											get_option('siteurl'). '/facebookapp/uploads/contest_images/'.$media.'" border=0 class="userimage">');
							}
							 elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){
								$media = $media_item_selected["media_url"];
								$media =$fbmo->get_youtube_iframe($media);
							 }
							 elseif($promo_row["media_type"]=="TEXT ENTRY")
								$media = $media_item_selected["media_text"];
								
								 $path_media_share = $fbmo->options["fb_canvas_url"].$promo_row["promo_page"].".php?action=browse_entries&browse_id=".$media_item_selected["media_id"];
						         $fbmo->updateLikes($path_media_share,$media_item_selected["media_id"],$promo_row["promo_id"]);	   
		?>
		<!-- 4.Promote-->
		<!-- This screen is used to show a single video that was clicked on from the BROWSE screen and is also used for the PROMOTE screen that a user is sent to after the enter their video. -->
		<div id="container" class="promote">
			<div class="ticker">
					<p><em><?php echo $days_left;?><?php echo $daysNot;?></em>  to <?php if($votingStatus=="started") echo "Vote";else{?>Enter<?php }?></p>
				</div>
			<div class="entrycontainer"  
				<?php if($promo_row["media_type"]=="TEXT ENTRY"){?>
					style="padding:10px;overflow:auto;height:210px; "<?php }?>>
					<?php echo  $media;?>
			</div><!--end of entrycontainer-->
			<div class="entryinfowrapper">
				<div class="entryinfoleftcol">
					<p class="boldtitle">Vote for this entry by clicking the "Like" button below:</p>
					<p><?php echo  fbm_show_like_button_entry_buzz($path_media_share);?></p>
					<p>Total Likes: <?php echo  $media_item_selected["like_counter"];?></p>
					<p class="boldtitle">Entry Info:</p>
					  <p> Entry Title:
					<?php echo($media_item_selected["media_title"]);
					 ?>
					</p>
					 <p>Entry Author: 
					<?php echo($media_item_selected["uid_name"]);
					 ?>
					</p>
				</div><!--end of entryinfoleft-->
				<div class="entryinfopromote">
                <?php
				if($votingStatus=="started"){
				?>
					<p class="boldtitle">Promote this entry:</p>
					<div class="footersharebtn">
						<a href="#" onclick="publishEntry();"><img src="<?php echo get_option('siteurl')?>/facebookapp/ui_images/sharebtn.png" border="0" /></a>
					</div><!--end of footersharebtn-->
					<!--<div class="footersendbtn">
						<a href="#" onclick="publishEntry();"><img src="images/fb_sharebtn.png" border="0" /></a>	
					</div>end of footersendbtn-->
				</div><!--end of entryinfopromote-->
                <?php
				
			}else{?>
            <p>&nbsp;</p>
            <div class="footersharebtn">
            <p><br />You can start promoting your video to your friends on <?php echo date("F d,Y",strtotime($promo_row["vote_start"])); ?></p>
            </div>
            </div>
            <?php
			}
			?> 
		  <div class="clearer"></div>
			</div><!--end of entryinfowrapper-->
			<div class="conditionalbtnarea">
			   <?php
			   if(isset($_REQUEST['browse_id']) && ($_REQUEST['action']=="browse_entries" ) && $votingStatus=="started"){
			   ?>
				<!--BACK BUTTON ONLY DISPLAYS IF USER IS VIEWING ANY VIDEO BUT HIS OWN-->
				<div class="backbtn"><a href="<?php echo $fbmo->options["fb_canvas_url"].$promo_row["promo_page"].".php?action=browse_entries";?>"><input name="Back" type="button" id="Back" value="&laquo; Back" /></a></div>
				<?php
			   }elseif($is_media_entered == $_REQUEST['browse_id'] && $votingStatus=="started"  && $votingStatus=="started"){
				?>
				<!--BROWSE ENTRIES BUTTON ONLY DISPLAYS IF USER IS VIEWING THEIR OWN VIDEO (For example after I Enter...I will be taken to this Promote screen...and will only see the browse button..not the back button)-->
				<div class="browsebtn"><a href="<?php echo $fbmo->options["fb_canvas_url"].$promo_row["promo_page"].".php?action=browse_entries";?>"><input name="Back" type="button" id="Back" value="&raquo; Browse Entries" /></a></div>
				<?php
				unset($_SESSION['my_entry']);unset($_SESSION['browse_id']);
				}
				?>
				<div class="clearer"></div>
			</div><!--end of conditionalbtnarea-->
		</div><!--end of PROMOTE div-->
		<?php
		}
		    //browse all entries
			else if($browseEntries==1  && (empty($_REQUEST['browse_id'])) && $votingStatus=="started"){
			   $media_items=$fbmo->get_promo_media($get_promo_id);
		   ?>
			 <!-- 5.Browse-->
			<!--This Screen is NOT available until after the Entry period has Ended and Voting Period has Begun -->
			<div id="container" class="browse">
			 <div class="ticker">
					<p><em><?php echo $days_left;?><?php echo $daysNot;?></em>  to <?php if($votingStatus=="started") echo "Vote";else{?>Enter<?php }?></p>
				</div>
				<div class="searchbox">
					<p class="boldtitle">Search for an entry</p>
					<form name="form" id="form" method="get" action="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?facebook_page=Main&action=browse_entries">
						<input type="hidden" name="action" value="browse_entries" />
						<select name="search_txt" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
							<option value="" selected="selected">*Search by:</option>
							<option value="media_title">Entry Title</option>
							<option value="uid_name">Contest Participant Name</option>
						</select>
						<input name="Search" type="text" />
						<input name="button" type="submit" value="Search" />
					</form>
				</div><!--end of searchbox-->
				<div class="browsewrapper">
					<div class="browsetopnav">
						<div class="pagination">
						  <a href="#" onClick="previous()">&laquo; Previous</a> | <a href="#" onClick="next()"> Next &raquo;</a> 
						</div><!--end of pagination-->
						<div class="sortby">
							<form name="form1" id="form" method="get" action="<?php echo($fbmo->options["fb_canvas_url"].$promo_row["promo_page"]); ?>.php?facebook_page=Main&action=browse_entries">
								<input type="hidden" name="action" value="browse_entries" />
								<select name="sort_txt" id="jumpMenu" onchange="document.form1.submit();">
									<option value="" selected="selected">*Sort by:</option>
									<option value="like_counter" <?php if($_REQUEST['sort_txt']=="like_counter") echo "selected='selected'";?>>Most Votes</option>
									<option value="media_title" <?php if($_REQUEST['sort_txt']=="media_title") echo "selected='selected'";?>>Entry Title</option>
									<option value="uid_name" <?php if($_REQUEST['sort_txt']=="uid_name") echo "selected='selected'";?>>Entrant Name</option>
								</select>
							</form>
						</div><!--end of Sortyby-->
						<div class="clearer"></div>
					</div><!--end of browsetopnav-->
					<div class="browsethumbswrapper">
					<script type="text/javascript">
					var myvacation=new Array();
					<?php
					$array_img = array();
					$array_img_links = array();
					$arr_i =0;
					foreach ($media_items as $media_row)	
					{
						$path_media_id = $fbmo->options["fb_canvas_url"].$promo_row["promo_page"].".php?action=browse_entries&facebook_page=Main&browse_id=".$media_row["media_id"];
						//$fbmo->updateLikes($path_media_id,$media_row["media_id"],$promo_row["promo_id"]);
						if($promo_row["media_type"]=="PHOTO UPLOAD")	{	
									$img_path= get_option('siteurl'). '/facebookapp/uploads/contest_images/'.$media_row["media_url"];
						}
						elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){
								 $img_path = "http://img.youtube.com/vi/".$media_row["media_url"]."/0.jpg";
						}
						else	{	
								 $media_text = ($media_row["media_title"]);
								 $img_path = get_option('siteurl'). '/facebookapp/layouts/buzz/images.php?txt='.$media_text.'&width=95&height=63';
								}
								?>
								myvacation[<?php echo $arr_i?>]=["<?php echo $img_path?>", "<?php echo $media_row["media_title"];?>/<?php echo $media_row["uid_name"];?> Total Votes:<?php echo $media_row["like_counter"];?>", "<?php echo $path_media_id?>"];
								<?php
								$arr_i++;
						 }
				?>
					  var thepics=new photogallery(myvacation, 4, 5, '110px', '78px',['Page', ''])
			   </script>
					</div>
				</div><!--end of browsewrapper-->
			</div><!--end of HowtoEnter div-->
			<?php
			  }
			}
			?>
			<div id="entrypopup" <?php if($_REQUEST['sub_act']!="enter"){?>style="visibility:hidden"<?php } ?>>
		<form name="entry_form" action="<?php echo(get_option('siteurl')."/facebookapp/".$promo_row["promo_page"]); ?>.php" method="post" enctype="multipart/form-data" target="_self" onsubmit="return valPopForm();">
			<input name="action" type="hidden" value="save_usermedia">
			<input name="fb_redirect" type="hidden" value="1" />
			<input name="entry_id" type="hidden" value="<?php echo($entry_id); ?>">
			<div class="enterform">
            	<div class="closebar">
                	<p><a href="javascript:void();" onclick="document.getElementById('entrypopup').style.visibility='hidden';document.getElementById('browseEntDiv').style.visibility='visible';return false;">Close X</a></p>
                </div>
				<p>To Enter, fill out the form below and click submit:</p>
                    <table width="100%" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td valign="top"><p>Full Name:</p></td>
                            <td><input name="uid_name" type="hidden"  value="<?php echo $fbmo->fbme['name']?>" readonly="readonly"/>
                            <?php echo $fbmo->fbme['name']?>
                            </td>
                        </tr>
                        <tr>
                        	<td valign="top"><p>Date of Birth:</p></td>
                            <td><input name="user_birth_date" type="text" value="mm-dd-yyyy" /></td>
                        </tr>
                        <tr>
                        	<td valign="top"><p>*Email:</p></td>
                            <td><input name="user_email" type="text" value="" /></td>
                        </tr>
                         <tr>
                        	<td valign="top"><p>*Entry Title:</p></td>
                            <td><input name="media_title" type="text" /></td>
                        </tr>
                        <tr>
                        	<td valign="top"><p>
							   <?php if($promo_row["media_type"]=="PHOTO UPLOAD"){ ?>
                               Upload image:<br />(.jpeg or .gif only)
                                <?php } elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){ ?>
                                Paste the full Youtube URL : 
                                <?php }elseif($promo_row["media_type"]=="TEXT ENTRY"){?>
                               Type/Paste your entry here:
                                <?php } ?>
								</p>
                            </td>
                            <td>
                            <?php if($promo_row["media_type"]=="PHOTO UPLOAD"){ ?>
                                <input type="file" name="upload_photo" length="50">
                                <?php } elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){ ?>
                                <input type="text" name="media_url" length="50">
                                <?php }elseif($promo_row["media_type"]=="TEXT ENTRY"){?>
                                <textarea name="media_text" cols="30" rows="14" >Enter or Paste your text here</textarea>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                        <td colspan="2" align="center"><br /><input  name="Enter" value="Submit Entry" type="submit"/></td>
                        </tr>
                         <tr>
                        	<td colspan="2" valign="top"><p class="entryrequirement">*Required in case we need to contact you regarding your entry.</p></td>
                        </tr>
                    </table>
			</div><!--End of enterform div  -->
		</form>
	</div>
    <?php
	}//promo not closed

	elseif($promoStatus == "closed"){
		   if($promo_row['response_type']=="LIKE"){
						$winner_txt = "like_counter";
						}else
						$winner_txt = "rate_counter";
						$media_items_winner=$fbmo->get_winner_buzz($winner_txt,$get_promo_id);
						$media_item_winner=$fbmo->get_promo_media_selected($get_promo_id,$media_items_winner["media_id"]);
					if($promo_row["media_type"]=="PHOTO UPLOAD"){
							$media =   $media_item_winner["media_url"];
							$media =   (' <img src="'.
										get_option('siteurl'). '/facebookapp/uploads/contest_images/'.
										$media.
										'" border=0  width="150">');
						}
						 elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){
							$media = $media_item_winner["media_url"];
							$media =$fbmo->get_youtube_iframe($media);
						 }
						 elseif($promo_row["media_type"]=="TEXT ENTRY")
							$media = $media_item_winner["media_text"];
						   $path_media_share = $fbmo->options["fb_canvas_url"].$promo_row["promo_page"].".php?action=browse_entries&browse_id=".$media_item_winner["media_id"];
						   $fbmo->updateLikes($path_media_share,$media_item_winner["media_id"],$promo_row["promo_id"]);
						   $_REQUEST['browse_id'] = $media_item_winner["media_id"];
	?>
    <!-- 6.Promotion Ended-->
    <div id="container" class="promote">
		<div class="entrycontainer" <?php if($promo_row["media_type"]=="TEXT ENTRY"){?>style="padding:10px;overflow:auto;height:210px; "<?php }?>>
				<?php echo  $media;?>
		</div><!--end of entrycontainer-->
		<div class="entryinfowrapper">
        	<div class="entryinfoleftcol">
            	<p class="boldtitle">Winning Entry Info:</p>
                <p>Entry Title:
				          <?php echo($media_item_winner["media_title"]);
								$rate = $fbmo->average_rating($media_item_winner["promo_id"],$media_item_winner["media_id"] );
							 ?>
                 </p>
                <p>Entry Author:
                	<?php echo($media_item_winner["uid_name"]);
								$rate = $fbmo->average_rating($media_item_winner["promo_id"],$media_item_winner["media_id"] );
				   ?>
                </p>
                <p>Total Likes: <?php echo  $media_item_winner["like_counter"];?></p>
                <p><?php echo fbm_show_like_button_entry_buzz($path_media_share);?></p>
            </div><!--end of entryinfoleft-->
            <div class="entryinfopromote">
            	<p class="boldtitle">Promote this entry:</p>
                <div class="footersharebtn">
                    <a href="#" onclick="publishEntry();"><img src="<?php echo get_option('siteurl')?>/facebookapp/ui_images/sharebtn.png" border="0" /></a>	
                </div><!--end of footersharebtn-->
                <!--<div class="footersendbtn">
                    <a href="#" onclick="publishEntry();"><img src="images/fb_sharebtn.png" border="0" /></a>	
                </div>end of footersendbtn-->
            </div><!--end of entryinfopromote-->
      		<div class="clearer"></div>
        </div><!--end of entryinfowrapper-->
    </div><!--end of Promo Ended div-->
    <?php
	}
	?>
    <?php
	 if(isset($media_error)){
	?>
    <div id="errorpopup" style="visibility:visible;">
        <div class="closebar">
            <p><a href="javascript:void();" onclick="document.getElementById('errorpopup').style.visibility='hidden';return false;">Close X</a></p>
        </div>
        <p><?php echo $media_error;?></p>
    </div>
    <?php
	 }
	 ?>
    <!-- Rules/Regs POPUP  -->
    <div id="rulesregspopup" style="visibility:hidden;">
        <div class="closebar">
            <p><a href="javascript:void();" onclick="document.getElementById('rulesregspopup').style.visibility='hidden';return false;">Close X</a></p>
        </div>
        <p><?php echo nl2br($promo_row["promo_rules"]);?></p>
    </div><!--END OF Rules / Regs popup-->
    <!-- System MSG POPUP  -->
<!--END OF System Msg-->
    <div id="footer">
    	<div class="sponsorbanner">
			<a href="<?php echo $promo_row["link_sponsor_one"];?>" class="sponsorbtn" target="_blank"></a>
        </div><!--end of sponsorbanner-->
        <div class="footersharebox">
        	<div class="footersharebtn">
            	<a href="#" onclick="publishShare();"><img src="<?php echo get_option('siteurl')?>/facebookapp/ui_images/sharebtn.png" border="0" /></a>	
            </div>
           <!-- <div class="footersendbtn">
            	<fb:send ></fb:send>
            </div>-->
            <div class="footerlikebtn">
            	<?php fbm_show_like_button();?>
            </div>
            <div class="clearer"></div>
        </div><!--end of footersharebox-->
	</div><!--end of footer div-->
    <div class="copyright">
        <p>NO PURCHASE NECESSARY. Void where prohibited. This promotion is in no way sponsored, endorsed or administered by, or associated with, Facebook. You understand that you are not providing information to Facebook. The information you provide will only be used to administer this promotion. </p>
		<p>&copy; Copyright <?php echo $page_name = $fbmo->get_page_name();?></p>
	</div><!--end of copyright-->
</div><!--end wrapper div-->

    <?php include_once( ABSPATH . '/facebookapp/includes/js_stream.php')?>
</body>
</html>