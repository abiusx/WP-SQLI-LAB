<?php
wpfb_loadclass('File', 'Output');

$update = isset($item) && is_object($item) && !empty($item->file_id);

$in_widget = !empty($in_widget);
$in_editor = !empty($in_editor);

$exform = $update || (!$in_editor && !empty($exform));

$multi_edit = !empty($multi_edit);
//$item_ids
	
if(empty($item))
	$file = new WPFB_File();
else
	$file = &$item;

if(!empty($post_id))
	$file->file_post_id = $post_id;

$action = ($update ? 'updatefile' : 'addfile');
$title = $update ? __('Edit File', WPFB) : __('Add File', WPFB);

$file_members_only = (!empty($file->file_required_level) && $file->file_required_level > 0);

$form_url = $in_editor ? remove_query_arg(array('file_id', 'page', 'action')) : add_query_arg('page', 'wpfilebase_files', admin_url('admin.php'));

if(!empty($_GET['redirect_to']))
	$form_url = add_query_arg(array('redirect' => 1, 'redirect_to' => $_GET['redirect_to']), $form_url);
?>
<form enctype="multipart/form-data" name="<?php echo $action ?>" id="<?php echo $action ?>" method="post" action="<?php echo $form_url ?>" class="validate">

<?php if($in_editor && !$in_widget) { ?><h3 class="media-title"><?php echo $title ?></h3>
<?php } elseif(!$in_widget) {?><h2><?php echo $title ?> <?php if(!$in_editor && !$update) { ?><a style="font-style:normal; vertical-align:super;" href="<?php echo remove_query_arg('exform') ?>&amp;exform=<?php echo ($exform ? '0' : '1') ?>" class="button"><?php _e($exform ? 'Simple Form' : 'Extended Form', WPFB) ?></a><?php } ?>
</h2><?php } ?>

<script type="text/javascript">
//<![CDATA[
function WPFB_switchFileUpload(i)
{
	jQuery('#file-upload-wrap').toggleClass('hidden');
	jQuery('#file-remote-wrap').toggleClass('hidden');
	var as = jQuery('a', jQuery('#wpfilebase-upload-menu')).toArray();
	jQuery(as[i]).addClass('current');
	jQuery(as[!i+0]).removeClass('current');
	jQuery('#file_is_remote').val(i);
	return false;
}
//]]>
</script>


<input type="hidden" name="action" value="<?php echo $action ?>" />
<?php if($update) { ?><input type="hidden" name="file_id" value="<?php echo $multi_edit ? $item_ids : $file->file_id; ?>" /><?php } ?>
<?php wp_nonce_field($action . ($update ? ($multi_edit ? $item_ids : $file->file_id) : '')); ?>
<table class="form-table">
<?php if(!$multi_edit) { ?>
	<tr id="wpfilebase-form-upload-row">
		<th scope="row" valign="top" id="wpfilebase-upload-menu">
			<a href="#" <?php echo ($file->IsRemote() ? '' : 'class="current"'); ?> onclick="return WPFB_switchFileUpload(0)"><?php _e('Upload')?></a>
			<a href="#" <?php echo ($file->IsRemote() ? 'class="current"' : ''); ?> onclick="return WPFB_switchFileUpload(1)"><?php _e('File URL')?></a>
			<input type="hidden" name="file_is_remote" id="file_is_remote" value="<?php echo ($file->IsRemote() ? 1 : 0); ?>" />
		</th>
		<td colspan="3">
		<div id="file-upload-wrap" <?php echo ($file->IsRemote() ? 'class="hidden"' : ''); ?>>
			<label for="file_upload"><?php _e('Choose File', WPFB) ?></label>
			<input type="file" name="file_upload" id="file_upload" /><br />
			<?php printf(str_replace('%d%s','%s',__('Maximum upload file size: %d%s'/*def*/)), WPFB_Output::FormatFilesize(WPFB_Admin::GetMaxUlSize())) ?>
			<?php if($update) { echo '<br /><b><a href="'.$file->GetUrl().'">' . $file->file_name . '</a></b> (' . $file->GetFormattedSize() . ')'; } ?>
		</div>
		<div id="file-remote-wrap" <?php echo ($file->IsRemote() ? '' : 'class="hidden"'); ?>>
			<label for="file_remote_uri"><?php _e('File URL') ?></label>
			<input name="file_remote_uri" id="file_remote_uri" type="text" value="<?php echo esc_attr($file->file_remote_uri); ?>" style="width:98%" /><br />
			<fieldset><legend class="hidden"></legend>
				<label><input type="radio" name="file_remote_redirect" value="1" <?php checked($file->IsRemote()); ?>/><?php _e('Redirect download to URL', WPFB) ?></label>
				<label><input type="radio" name="file_remote_redirect" value="0" <?php checked($file->IsLocal()); ?>/><?php _e('Copy file into Filebase (sideload)', WPFB) ?></label>
			</fieldset>
		</div>
		</td>
	</tr>
	<tr>		
		<?php if($exform) { ?>		
		<th scope="row" valign="top"><label for="file_upload_thumb"><?php _e('Thumbnail'/*def*/) ?></label></th>
		<td class="form-field" colspan="3"><input type="file" name="file_upload_thumb" id="file_upload_thumb" />
		<br /><?php _e('You can optionally upload a thumbnail here. If the file is a valid image, a thumbnail is generated automatically.', WPFB); ?>
		<?php if($update && !empty($file->file_thumbnail)) { ?>
			<br /><img src="<?php echo $file->GetIconUrl(); ?>" /><br />
			<b><?php echo $file->file_thumbnail; ?></b><br />
			<label for="file_delete_thumb"><?php _e('Delete') ?></label><input type="checkbox" value="1" name="file_delete_thumb" id="file_delete_thumb" style="display:inline; width:30px;" />
		<?php } ?>
		</td>
		<?php } else { ?><th scope="row"></th><td colspan="3"><?php _e('The following fields are optional.', WPFB) ?></td><?php } ?>
	</tr>
<?php } /*multi_edit*/ ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="file_display_name"><?php _e('Title') ?></label></th>
		<td width="60%"><input name="file_display_name" id="file_display_name" type="text" value="<?php echo esc_attr($file->file_display_name); ?>" size="<?php echo ($in_editor||$in_widget) ? 20 : 40 ?>" /></td>
		<th scope="row" valign="top"><label for="file_version"><?php _e('Version') ?></label></th>
		<td width="40%"><input name="file_version" id="file_version" type="text" value="<?php echo esc_attr($file->file_version); ?>" size="<?php echo ($in_editor||$in_widget) ? 10 : 20 ?>" /></td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="file_author"><?php _e('Author') ?></label></th>
		<td><input name="file_author" id="file_author" type="text" value="<?php echo esc_attr($file->file_author); ?>" size="<?php echo ($in_editor||$in_widget) ? 20 : 40 ?>" /></td>
		<?php if($exform) { ?>
		<th scope="row" valign="top"><label for="file_date"><?php _e('Date') ?></label></th>
		<td><?php
			//create a comment object for the touch_time function
			global $comment;
			$comment = new stdClass();
			$comment->comment_date = false;
			if( $file != null)					
				$comment->comment_date = $file->file_date;
			?><div class="wpfilebase-date-edit"><?php
			touch_time(($file != null),0); ?></div></td>
	</tr>
	<tr class="form-field">
		<?php } ?>
		<th scope="row" valign="top"><label for="file_category"><?php _e('Category') ?></label></th>
		<td><select name="file_category" id="file_category" class="postform"><?php echo WPFB_Output::CatSelTree(array('selected'=>$file->file_category)) ?></select></td>
		<?php if($exform) { ?>
		<th scope="row" valign="top"><label for="file_license"><?php _e('License', WPFB) ?></label></th>
		<td><select name="file_license" id="file_license" class="postform"><?php echo  WPFB_Admin::MakeFormOptsList('licenses', $file ? $file->file_license : null, true) ?></select></td>
		<?php } ?>
	</tr>

	<tr class="form-field">
		<?php if(!$in_editor) { ?>
		<th scope="row" valign="top"><label for="file_post_id"><?php _e('Post') ?> ID</label></th>
		<td><input type="text" name="file_post_id" class="small-text" size="8" style="width: 60px" id="file_post_id" value="<?php echo esc_attr($file->file_post_id); ?>" /> <span id="file_post_title" style="font-style:italic;"><?php if($file->file_post_id > 0) echo get_the_title($file->file_post_id); ?></span> <a href="javascript:;" class="button" onclick="WPFB_PostBrowser('file_post_id', 'file_post_title');"><?php _e('Select') ?>...</a></td>
		<?php } else { ?>
		<td><input type="hidden" name="file_post_id" id="file_post_id" value="<?php echo esc_attr($file->file_post_id); ?>" /></td>
		<?php } ?>
		<?php if($exform) { ?>
		<th scope="row" valign="top"><label for="file_hits"><?php _e('Download Counter', WPFB) ?></label></th>
		<td><input type="text" name="file_hits" class="small-text" id="file_hits" value="<?php echo (int)$file->file_hits; ?>" /></td>
	</tr>
	<tr class="form-field">
		<?php if(WPFB_Core::GetOpt('platforms')) { ?>
		<th scope="row" valign="top"><label for="file_platforms[]"><?php _e('Platforms', WPFB) ?></label></th>
		<td><select name="file_platforms[]" size="40" multiple="multiple" id="file_platforms[]" style="height: 80px;"><?php echo  WPFB_Admin::MakeFormOptsList('platforms', $file ? $file->file_platform : null, true) ?></select></td>
		<?php } else { ?><th></th><td></td><?php }
		if(WPFB_Core::GetOpt('requirements')) { ?>
		<th scope="row" valign="top"><label for="file_requirements[]"><?php _e('Requirements', WPFB) ?></label></th>
		<td><select name="file_requirements[]" size="40" multiple="multiple" id="file_requirements[]" style="height: 80px;"><?php echo  WPFB_Admin::MakeFormOptsList('requirements', $file ? $file->file_requirement : null, true) ?></select></td>
		<?php } else { ?><th></th><td></td><?php } ?>
	</tr>
	<tr>
	<?php if(WPFB_Core::GetOpt('languages')) { ?>
		<th scope="row" valign="top"><label for="file_languages[]"><?php _e('Languages') ?></label></th>
		<td  class="form-field"><select name="file_languages[]" size="40" multiple="multiple" id="file_languages[]" style="height: 80px;"><?php echo  WPFB_Admin::MakeFormOptsList('languages', $file ? $file->file_language : null, true) ?></select></td>
		<?php } else { ?><th></th><td></td><?php } ?>
		
		<th scope="row" valign="top"><label for="file_direct_linking"><?php _e('Direct linking', WPFB) ?></label></th>
		<td>
			<fieldset><legend class="hidden"><?php _e('Direct linking') ?></legend>
				<label title="<?php _e('Yes') ?>"><input type="radio" name="file_direct_linking" value="1" <?php checked('1', $file->file_direct_linking); ?>/> <?php _e('Allow direct linking', WPFB) ?></label><br />
				<label title="<?php _e('No') ?>"><input type="radio" name="file_direct_linking" value="0" <?php checked('0', $file->file_direct_linking); ?>/> <?php _e('Redirect to post', WPFB) ?></label>
			</fieldset>
		</td>
		<?php } ?>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="file_description"><?php _e('Description') ?></label></th>
		<td colspan="3"><textarea name="file_description" id="file_description" rows="5" cols="50" style="width: 97%;"><?php echo esc_html($file->file_description); ?></textarea></td>
	</tr>
	<?php if($exform) { ?>
	<tr>
		<th scope="row" valign="top"><label for="file_offline"><?php _e('Offline', WPFB) ?></label></th>
		<td><input type="checkbox" name="file_offline" value="1" <?php checked('1', $file->file_offline); ?>/></td>
		
		<th scope="row" valign="top"><label for="file_members_only"><?php _e('For members only', WPFB) ?></label></th>
		<td>
			<input type="checkbox" name="file_members_only" value="1" <?php checked(true, $file_members_only) ?> onclick="WPFB_CheckBoxShowHide(this, 'file_required_role')" />
			<!-- <label for="file_required_level"<?php if(!$file_members_only) { echo ' class="hidden"'; } ?>><?php printf(__('Minimum user level: (see %s)', WPFB), '<a href="http://codex.wordpress.org/Roles_and_Capabilities#Role_to_User_Level_Conversion" target="_blank">Role to User Level Conversion</a>') ?> <input type="text" name="file_required_level" class="small-text<?php if(!$file_members_only) { echo ' hidden'; } ?>" id="file_required_level" value="<?php echo max(0, intval($file->file_required_level) - 1); ?>" /></label> -->

			<label for="file_required_role"<?php if(!$file_members_only) { echo ' class="hidden"'; } ?>><?php _e('Minimum user role:', WPFB) ?>		
				<select name="file_required_role" id="file_required_role" class="<?php if(!$file_members_only) { echo ' hidden'; } ?>">
						<?php wp_dropdown_roles($file->GetRequiredRole()) ?>
				</select>
			</label>
		</td>
	</tr>
	<?php } ?>
</table>
<p class="submit"><input type="submit" class="button-primary" name="submit-btn" value="<?php echo $title ?>" <?php if(false && !$in_editor) { ?>onclick="this.form.submit(); return false;"<?php } ?>/></p>

<?php
if($update)
{
	wpfb_loadclass('GetID3');
	$info = WPFB_GetID3::GetFileInfo($file);
	if(!empty($info)) {		
		add_meta_box('wpfb_file_info_paths', __('File Info Tags (ID3 Tags)', WPFB), array('WPFB_AdminGuiFiles','FileInfoPathsBox'), 'wpfb_file_form', 'normal', 'core');
	?>
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content" class="postbox-container">
						<?php do_meta_boxes('wpfb_file_form', 'normal', $info); ?>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function($) {
				// postboxes setup					
				postboxes.add_postbox_toggles('wpfb_file_form');
				jQuery('.postbox h3, .postbox .handlediv').parent('.postbox').toggleClass('closed');
			});
			//]]>
		</script>
	<?php
	}
}
?>
</form>