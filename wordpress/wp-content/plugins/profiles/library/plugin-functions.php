<?php
/*
Functions required for the proper internal operation of the plugin
*/
?>
<?php

function people_rewrite_rules($wp_rewrite) {
	if (get_option('profiles_use_permalinks') == 'true') {
		$wp_rewrite->non_wp_rules = array( get_option('profiles_location').'/([^/\.]+)/?$' => 'index.php?people_slug=$1&pagename='.get_option('profiles_location') );
	}
}

function people_generate_permalink($slug) {
	if (get_option('profiles_use_permalinks') == 'true') {
		return get_option('blogurl')."/".get_option('profiles_location')."/$slug";
	}
	else {
		return get_option('blogurl')."/".get_option('profiles_location')."/?people_slug=$slug";
	}
}

function people_add_head() {
	if (get_option('profiles_load_jquery') == 'true') {
		echo "<script type='text/javascript' src='".get_option('siteurl')."/wp-content/plugins/profiles/js/jquery.js.php?ver=1.2.3'></script>\n";
	}
	echo "<script type='text/javascript' src='".get_option('siteurl')."/wp-content/plugins/profiles/js/profiles.js.php'></script>\n";
	echo "<link href='".get_option('siteurl')."/wp-content/plugins/profiles/css/profiles.css' media='screen' type='text/css' rel='stylesheet'>\n";
}

function people_manage_add_head() {
	echo "<script src='".get_option('siteurl')."/wp-content/plugins/profiles/js/jquery.js.php?ver=1.2.1' type='text/javascript'/>";
}

function people_add_admin() {
	add_management_page('Profiles','Profiles',get_option('profiles_user_level'),'profiles','people_manage_page');
	add_options_page('Profiles','Profiles',9,'profiles-options','profiles_option_page');
}

function people_manage_page() {
	include_once('../wp-content/plugins/profiles/admin/people-manage.php');
}

function profiles_option_page() {
	include_once('../wp-content/plugins/profiles/admin/profiles-options.php');
}

function people_get_editor($id) {
	global $wpdb;
	$result = mysql_query("SELECT ".$wpdb->prefix."people.first_name AS first_name, ".$wpdb->prefix."people.last_name AS last_name, ".$wpdb->prefix."people.id AS id, ".$wpdb->prefix."people.slug AS slug, ".$wpdb->prefix."bios.id AS bid, ".$wpdb->prefix."bios.text AS text FROM ".$wpdb->prefix."people, ".$wpdb->prefix."bios WHERE ".$wpdb->prefix."people.id='$id' AND ".$wpdb->prefix."people.id=".$wpdb->prefix."bios.uid");
	$row = mysql_fetch_array($result);
	echo "<form>\n";
	echo "<input type='hidden' class='people-editor-bid' value='$row[bid]' />";
	echo "<input type='hidden' class='people-editor-id' value='$id' />";
	echo "<p>Biography for <strong>$row[first_name] $row[last_name]</strong></p><img src='".get_option('blogurl')."/wp-content/plugins/profiles/library/bio-img.php?id=$id' style='float: right'>\n";
	echo "<textarea name='people-bio-edit-text' id='people-bio-edit-text' rows='20' cols='50' wrap='virtual'>".stripslashes($row['text'])."</textarea>\n";
	echo "</form>\n";
	echo "<div style='clear: both'>&nbsp;</div>\n";
	echo "<p class='submit'><input type='button' class='people-editor-cancel' value='Cancel' /><input type='button' class='people-editor-submit' value='Submit' /></p>\n";
}

function people_get_edit_table() {
echo "<table class='widefat'>\n";
echo "\t<thead>\n";
echo "\t<tr>\n";
echo "\t\t<th scope='col' style='text-align: center'>ID</th>\n";
echo "\t\t<th scope='col'>First</th>\n";
echo "\t\t<th scope='col'>Last</th>\n";
echo "\t\t<th scope='col' colspan='4' style='text-align: center'>Action</th>\n";
echo "\t</tr>\n";
echo "</thead>\n";
echo "\t<tbody id='the-list'>\n";
global $wpdb;
$result = mysql_query("SELECT * FROM ".$wpdb->prefix."people ORDER BY last_name");
$alt = " ";
while($row = mysql_fetch_array($result)) {
	if ($alt == " ") $alt = "alternate";
	else $alt = " ";
	echo "\t<tr id='person-$row[id]' class='$alt'>\n";
	echo "\t\t<th scope='row' style='text-align: center'>$row[id]</th>\n";
	echo "\t\t<td>$row[first_name]</td>\n";
	echo "\t\t<td>$row[last_name]</td>\n";
	echo "\t\t<td><a href='".people_generate_permalink($row['slug'])."'>View</a></td>\n";
	echo "\t\t<td><a href='#' class='people-edit-single' id=$row[id]>Edit</a></td>\n";
	echo "\t\t<td><a href='#' class='people-new-photo' id=$row[id]>Change Photo</a></td>\n";
	echo "\t\t<td><a href='#' class='people-delete-single' id=$row[id]>Delete</a></td>\n";
	echo "\t</tr>\n";
	}
echo "\t</tbody>\n";
echo "</table>\n";
}
?>