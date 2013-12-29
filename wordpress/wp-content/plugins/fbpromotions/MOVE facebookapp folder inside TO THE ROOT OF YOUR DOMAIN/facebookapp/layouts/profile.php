<?php
ob_start();
?>
<STYLE type="text/css">
#wrapper{
	width:520px;
	position:relative;
	font:.9em Verdana, Geneva, sans-serif;	
	overflow:hidden;
}#non-fans {
 width:520px;
 position:relative; top:0; left:0;
 z-index:0;
 overflow:hidden;
}
#fans {
 width:520px;
 position:relative; top:0; left:0;
 z-index:1;
 overflow:hidden;
}
</STYLE>
<div id="wrapper">
<?php
if( $signed_request ){
?>
<div id="fans">
<?php
$fbmo->options["fb_canvas_url"] = get_option('siteurl'). '/facebookapp/';
$url=urlencode($fbmo->options['fb_canvas_url']);
function fbm_display_promo($promo_row)	{
	global $fbmo,$promo_image_uploads;
	//echo $promo_row["img_profiletab_promobanner"];
	if(is_array($promo_row))	{
		$save_entry ="";
		if($promo_row['promo_type']!=1){
			        $save_entry = "?action=save_entry&fb_redirect=1&promo_id=".$promo_row['promo_id'];
		}else
					$save_entry = "?page=Main&promo_id=".$promo_row['promo_id'];
		echo('<a href="'.$fbmo->options["fb_canvas_url"].$promo_row['promo_page'].'.php'.$save_entry.'" target="_self">');
		if (!empty($promo_row["img_profiletab_promobanner"]))	{
			echo('<img src="'.$promo_image_uploads.$promo_row["img_profiletab_promobanner"].
			'" border=0 />');
		} else	{
			echo $promo_row["promo_name"];
		}
		echo "</a>";
	} else	{
		$save_entry ="";
		if($promo_row->promo_type!=1){
			$save_entry = "?action=save_entry&fb_redirect=1&promo_id=".$promo_row->promo_id;
		}else
			$save_entry = "?fb_redirect=1&promo_id=".$promo_row->promo_id;
		echo('<a href="'.$fbmo->options["fb_canvas_url"].$promo_row->promo_page.'.php'.$save_entry.'" target="_self">');
		if (!empty($promo_row->img_profiletab_promobanner))	{
			echo('<img src="'.$promo_image_uploads.$promo_row->img_profiletab_promobanner.'" border=0 />');
		} else	{
			echo $promo_row->promo_name;
		}
		echo "</a>";
	}
}

if(! empty($promo_row))	{
	fbm_display_promo($promo_row);
}
else	{
	# get a list and display because they haven't set a specific promo id
	global $promo_list;
	$fbmo->get_landing_list();
	if($promo_list)	{
		foreach($promo_list as $promo_row)	{
			$fbmo_is_entered=$fbmo->check_promo_entry($promo_row->promo_id);
			fbm_display_promo($promo_row);
			echo("<br />");	
		}
	}
}
?>
</div>
<?php 
}
else
{
	if(empty($promo_row))	{
		# get a list and display because they haven't set a specific promo id
		global $promo_list;
		$fbmo->get_landing_list();
	
		if($promo_list)	{
			    foreach($promo_list as $promo_row)	{
				break;
			}
		}
	}
?>
<div id="non-fans">
<img src="<?php echo ($promo_image_uploads.$promo_row->img_profiletab_requirelike);?>" border="0" />
</div>
<?php 
}
?>
</div>
