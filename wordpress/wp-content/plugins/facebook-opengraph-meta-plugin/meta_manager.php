<?php 
require(dirname(__FILE__)."../../../../wp-load.php"); 
$post_meta=get_post_meta($_REQUEST["post_id"], "_".$_GET["tag"]);
$from_seettings_pg = $_REQUEST["fromP"];
$og_types = array(
	'Activities' =>array('activity', 'sport'),
	'Businesses' =>array('bar', 'company', 'cafe', 'hotel', 'restaurant'),
	'Groups'=>array('cause', 'sports_league', 'sports_team'),
	'Organizations'=>array('band', 'government', 'non_profit', 'school', 'university'),
	'People'=>array('actor', 'athlete', 'author', 'director', 'musician', 'politician', 'public_figure'),
	'Places'=>array('city', 'country', 'landmark', 'state_province'),
	'Products and Entertainment'=>array('album', 'book', 'drink', 'food', 'game', 'movie', 'product', 'song', 'tv_show'),
	'Websites'=>array('article', 'blog', 'website')
);
$og_props=array(
	"General"=>array(
		"og:site_name"=>"Site Name",
		"og:title"=>"Title",
		"og:type"=>"Type",
		"og:url"=>"URL",
		"og:image"=>"Image",	
		"og:description"=>"Description",
                "fb:admins"=>"Admins"
	),
	"Contact"=>array(
		"og:email"=>"Email",	
		"og:phone_number"=>"Phone",	
		"og:fax_number"=>"Fax"
	),	
	"Location"=>array(
		"og:latitude"=>"Latitude",
                "og:longitude"=>"Longitude",
		"og:street-address"=>"Street address",
		"og:locality"=>"Locality ",
		"og:region"=>"Region ",
		"og:postal-code"=>"Post Code",
		"og:country-name"=>"Country"
	),
	"Video"=>array(
		"og:video"=>"Video URL(only .swf format)",
                "og:video:height"=>"Video Height",
		"og:video:width"=>"Video Width",
		"og:video:type"=>"Video Type"
	),
	"Audio"=>array(
		"og:audio"=>"Audio URL",
                "og:audio:title"=>"Audio Title",
		"og:audio:artist"=>"Audio Artist",
		"og:audio:album"=>"Audio Album Name",
                "og:audio:type"=>"Audio Type"
	)
);
if(isset($_POST["og_action"]) AND $_POST["og_action"]=="process"){
	echo "<pre>"; print_r($_POST); echo "</pre>"; 
	die(0);
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php wp_enqueue_script("jquery"); ?>
<?php wp_head(); ?>	
<link rel="stylesheet" href="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/css/og_styles.css" />
</head>
<body>
<?php if(!is_array($post_meta)) : ?>
No Meta Found 
<?php endif; ?>
<div id="stat_msg"></div>
<a href="javascript:;" onclick="og_add_row();"><img src="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/images/add.png" border="0" alt="Add" /> Add New</a>
<div id="postcustomstuff">
<form id="og_meta_form" action="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/ogmeta.php" method="post">
<!--<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"> -->
<input type="hidden" name="action" value="save_meta" />
<input type="hidden" name="post_id" value="<?php echo $_GET["post_id"]; ?>" />
<input type="hidden" name="tag" value="_<?php echo $_GET["tag"]; ?>" />
<table cellpadding="4" cellspacing="0" width="100%" id="og_metas">
<?php if(sizeof($post_meta)) : 
	$data=unserialize($post_meta[0]);		
	$meta_count=0;
?>
<?php 
	if(is_array($data)){
        foreach($data as $prop=>$meta_val) :
	$meta_count++;
?>
<tr>
<td width="150" valign="top">
<select name="props[]" onchange="switch_vals(this)">
	<option value="">Select</option>
	<?php
		$opts="";
		foreach($og_props as $key=>$val){
			$opts.='<optgroup label="'.$key.'">';
				foreach($val as $v=>$k){
					if($v==$prop){
						$opts.='<option value="'.$v.'" selected="selected">'.$k.'</option>';
					}else{
						$opts.='<option value="'.$v.'">'.$k.'</option>';
					}
				}
			$opts.='</optgroup>';
		}
		echo $opts;
	?>
</select>
</td><td valign="top">
<?php 
	switch($prop){
		case "og:type":
?>
<select name="vals[]">
<?php foreach($og_types as $k=>$v) : ?>		 
		 <optgroup label="<?php echo $k; ?>">
			<?php foreach($v as $option): ?>
				<?php if($option==$meta_val) : ?>
				<option selected="selected" value="<?php echo $option; ?>"><?php echo $option; ?></option>
				<?php else: ?>
				<option value="<?php echo $option; ?>"><?php echo $option; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		 </optgroup>		 
<?php endforeach; ?>
</select>
<?php 
			break;
		case "og:description":
			echo '<textarea rows="3" class="values" name="vals[]">'.$meta_val.'</textarea>';
			break;
		default:
			echo '<input class="values" type="text" name="vals[]" value="'.$meta_val.'">';
			break;
	}
?>	
</td>
<td<?php echo ($meta_count==1) ? ' class="hidden"' : ''; ?>><a href="javascript:;" onclick="remove_row(this);"><img src="<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/images/delete.png" alt="delete" border="0" /></a>
</tr>
<?php
    endforeach;
    } else {
        delete_post_meta($_GET["post_id"], '_' . $_GET["tag"]);
    }
?>
<?php else: ?>
<tr>
<td width="150" valign="top">
<select name="props[]" onchange="switch_vals(this)">
	<option value="">Select</option>
	<optgroup label="General">
		<option value="og:site_name">Site Name</option>
		<option value="og:title">Title</option>
		<option value="og:type">Type</option>
		<option value="og:url">URL</option>
		<option value="og:image">Image</option>	
		<option value="og:description">Description</option>
                <option value="fb:admins">Admins</option>
	</optgroup>
	<optgroup label="Contact">
		<option value="og:email">Email</option>	
		<option value="og:phone_number">Phone</option>	
		<option value="og:fax_number">Fax</option>	
	</optgroup>	
	<optgroup label="Location">
		<option value="og:latitude">Latitude</option>
                <option value="og:longitude">Longitude</option>
		<option value="og:street-address">Street address</option>
		<option value="og:locality">Locality </option>
		<option value="og:region">Region </option>
		<option value="og:postal-code">Post Code</option>
		<option value="og:country-name">Country</option>
	</optgroup>	
</select>
</td><td valign="top"><input class="values" type="text" name="vals[]"></td>
<td class="hidden"><a href="javascript:;" onclick="remove_row(this);">[-]</a>
</tr>
<?php endif; ?>
</table>
<input type="submit" class="awesome blue" value=" Save " />
</form>
</div>
<div id="og_meta_container">
</div>
<div id="og_type_select" class="hidden">
<select name="vals[]">
<?php foreach($og_types as $k=>$v) : ?>
		 <optgroup label="<?php echo $k; ?>">
			<?php foreach($v as $option): ?>
			<option value="<?php echo $option; ?>"><?php echo $option; ?></option>
			<?php endforeach; ?>
		 </optgroup>
<?php endforeach; ?>
</select>
</div>
<div id="og_type_input" class="hidden">
	<input class="values" type="text" name="vals[]">
</div>
<div id="og_type_input_title" class="hidden">
    <input class="values" type="text" name="vals[]" value="<?php echo get_post($_REQUEST["post_id"])->post_title; ?>">
</div>
<div id="og_type_input_site" class="hidden">
    <input class="values" type="text" name="vals[]" value="<?php echo get_bloginfo('name'); ?>">
</div>
<div id="og_type_video_type" class="hidden">
    <input class="values" type="text" name="vals[]" value="application/x-shockwave-flash">
</div>
<div id="og_type_audio_type" class="hidden">
    <input class="values" type="text" name="vals[]" value="application/mp3">
</div>
<div id="og_type_text" class="hidden">
	<textarea class="values" name="vals[]" rows="3" cols="40"></textarea>
</div>
<script type="text/javascript">
function update_meta_panel()
{
        if('<?php echo $from_seettings_pg;?>' != 'settings'){
            window.parent.upgdate_ogmeta_panel();
        }else{
            window.parent.update_jqg();
        }
}
jQuery(document).ready(function(){	
	jQuery("#og_meta_form").submit(function(){
		jQuery.ajax({
			url : "<?php echo bloginfo("wpurl"); ?>/wp-content/plugins/facebook-opengraph-meta-plugin/ogmeta.php",
			type : "post",
			data : jQuery("#og_meta_form").serialize(),
			dataType : "json",
			success:function(e){
				jQuery("#stat_msg").html(e.content).show().addClass("info_msg").fadeOut(3000,function(){
					jQuery(this).removeClass("info_msg");
				});
				update_meta_panel();
			}
		})
		return false;
	});
});
function switch_vals(element){
	$element=jQuery(element);	
	if($element.val()=="og:type"){
		$element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_select").html());
	}else if($element.val()=="og:description"){
		$element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_text").html());
	}else{		
                if($element.val()=="og:title"){
                    $element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_input_title").html());
                    jQuery("#og_metas").find("tr:last").find("td.hidden").removeClass("hidden");
                } else if($element.val()=="og:site_name"){
                    $element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_input_site").html());
                    jQuery("#og_metas").find("tr:last").find("td.hidden").removeClass("hidden");
                } else if($element.val()=="og:audio:type"){
                    $element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_audio_type").html());
                    jQuery("#og_metas").find("tr:last").find("td.hidden").removeClass("hidden");
                } else if($element.val()=="og:video:type"){
                    $element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_video_type").html());
                    jQuery("#og_metas").find("tr:last").find("td.hidden").removeClass("hidden");
                } else {
                    $element.parents("tr").find("td:eq(1)").html(jQuery("#og_type_input").html());
                }
	}
}
function og_add_row(){
	jQuery("#og_metas").find("tr:last").after("<tr>"+jQuery("#og_metas").find("tr:eq(0)").html()+"</tr>");
	jQuery("#og_metas").find("tr:last").find("td:eq(1)").html(jQuery("#og_type_input").html());
	jQuery("#og_metas").find("tr:last").find("td.hidden").removeClass("hidden");
}
function remove_row(element){
	jQuery(element).parents("tr").remove();
}
</script>
<style type="text/css">
.hidden{
	display:none;
}
input.values,textarea.values{
	width:98%;
}
.info_msg{
	padding:10px;
	border:1px solid #AACCDD;
	font-size:11px;
	background-color:#DDEEFF;
	margin:5px;
}
</style>
</body>
</html>