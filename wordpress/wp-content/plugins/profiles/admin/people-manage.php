<?php
/*
Profiles Management Page
*/

require_once('../wp-content/plugins/profiles/library/config.php');

?>
<!--<script src="<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/js/jquery.js.php?ver=1.2.3" type="text/javascript"></script>-->
<script src="<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/js/profiles-admin.js.php" type="text/javascript"></script>
<?php
// Application Logic
$mode = "list";
$id = 1;
if (isset($_GET['people_mode'])) $mode = $_GET['people_mode'];
if (isset($_GET['people_id'])) $id = $_GET['people_id'];
if (get_option('profiles_setup_complete') != 'false') {
?>
<div class="wrap">
	<h2>Profiles</h2>
    <div class="people-load-block" style="display: none">
    <img src="<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/images/loading.gif" alt="Loading ..." title="Loading ..." class="people-ajax-image" height="28" width="28" />&nbsp;Loading ...
    </div>
    <div classs="people-edit-instructions">
    <p>Welcome! Please select a profile to edit.</p>
    </div>
<?php // Edit Mode ?>
<div class="people-editor" <?php if($mode!="single") echo "style='display: none'"; ?> >
<form class="people-single-editor">
	<input type="text" value="text" />
</form>
</div>
<?php // end: Edit Mode ?>
<?php // Table View Mode ?>
<div class="people-edit-table" <?php if($mode!="list") echo "style='display: none'"; ?> >
	<?php people_get_edit_table(); ?>
</div>
<?php // end: Table View Mode ?>
</div> <!-- Main -->
<div class="wrap" id="people-add-new">
<h2 id="people-add-person-title">Add Person</h2>
<div id="people-add-person">
<form>
<table class="editform" width="100%">
<tr><th width="33%" scope="row" valign="top"><label for="people-add-first">First Name:</label></th><td width="67%"><input type="text" name="people-add-first" id="people-add-first" /></td></tr>
<tr><th scope="row" valign="top"><label for="people-add-last">Last Name:</label></th><td><input type="text" name="people-add-last" id="people-add-last" /></td></tr>
<tr><th scope="row" valign="top"><label for"people-add-slug">Slug (text in link):</label></th><td><input type="text" name="people-add-slug" id="people-add-slug" /></td></tr>
</table>
</form>
<p class="submit"><input type="button" id="people-add-person-submit" value="Add Person >" /></p></div>
</div>
<?php
} // ? profiles_setup_complete
else {
?>
<div class="wrap">
	<h2>Profiles</h2>
    <p>It appears that you have not set up the profiles system since you installed/updated. Please visit "Settings > Profiles" and save the options in order to reset this message</p>
</div>
<?php
}
?>    