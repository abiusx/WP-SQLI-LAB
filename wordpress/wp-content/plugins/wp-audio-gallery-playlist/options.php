<? 
$options_page = get_option('siteurl') . '/wp-admin/options-general.php?page=wp-audio-gallery-playlist/options.php';
$location = $options_page; // Form Action URI ?>

<div id="wpbody-content">
	<div class="wrap">
	<div id="icon-options-general" class="icon32">
		<br/>
	</div>
	<h2>WP Audio Gallery Playlist Settings </h2>
	
	<?php
		
		if (isset($_POST['gallery_player_width']))
			update_option('wp-audio-gallery_player_width', $_POST['gallery_player_width']);
		if (isset($_POST['gallery_player_height']))
			update_option('wp-audio-gallery_player_height', $_POST['gallery_player_height']);
		if (isset($_POST['single_player_width']))
			update_option('wp-audio-single_player_width', $_POST['single_player_width']);
		if (isset($_POST['single_player_enable'])) {
			update_option('wp-audio-single_player_enable', $_POST['single_player_enable']);
		}
			
			
		$gallery_player_width = get_option('wp-audio-gallery_player_width');	
		$gallery_player_height = get_option('wp-audio-gallery_player_height');
		$single_player_width = get_option('wp-audio-single_player_width');
		$single_player_enabled = get_option('wp-audio-single_player_enable');
	
	?>
	
	<form action="<?php echo $location ?>&amp;updated=true" method="post">

	
	<h3>Size of Audio Gallery Player</h3>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th><label for="default_post_edit_rows">    Width</label></th>
			<td><input id="default_post_edit_rows" class="small-text" type="text" value="<? echo $gallery_player_width; ?>" name="gallery_player_width"/> pixels</td>
			</tr>
			<tr valign="top">
				<th><label for="default_post_edit_rows">    Height</label></th>
			<td><input id="default_post_edit_rows" class="small-text" type="text" value="<? echo $gallery_player_height; ?>" name="gallery_player_height"/> pixels</td>
			</tr>
		</tbody>
	</table>
	
	<h3>Single Audio Player</h3>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th><label for="default_post_edit_rows">    Width</label></th>
			<td><input id="default_post_edit_rows" class="small-text" type="text" value="<? echo $single_player_width; ?>" name="single_player_width"/> pixels</td>
			</tr>			
			<tr valign="top">
				<th><label for="default_post_edit_rows">    Auto Embed player for single track?</label></th>
			<td>
				<select name="single_player_enable">
  				<option value="yes"<? if ($single_player_enabled == "yes") echo " selected"; ?>>enabled</option>
  				<option value="no"<? if ($single_player_enabled == "no") echo " selected"; ?>>disabled</option>
				</select>
			</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input class="button-primary" type="submit" value="Save Changes" name="Submit"/>
	</p>
	</form>
	</div> <!-- end wrap -->
</div> <!-- wpbody-content -->