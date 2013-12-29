<?php
/*
Contains template tags for people listing and attendant functions
*/
?>
<?php

function people_list_basic() {
	
	global $wpdb;

	$result = mysql_query("SELECT * FROM ".$wpdb->prefix."people");
	echo "<ul class='people-list'>\n";
	while ($row = mysql_fetch_array($result)) {
		echo "\t<li class='people-listi' id='$row[id]'>$row[first_name] $row[last_name]</li>\n";
	}
	echo "</ul>\n";
}

function people_list_ajax() {
	global $wpdb;
	$result = mysql_query("SELECT * FROM ".$wpdb->prefix."people ORDER BY last_name");
	echo "<ul class='people-list'>\n";
	while ($row = mysql_fetch_array($result)) {
		echo "\t<li class='people-listi' id='$row[id]'><a href='".people_generate_permalink($row['slug'])."' class='people-listi-link' id='$row[id]'>$row[first_name] $row[last_name]</a></li>\n";
	}
	echo "</ul>\n";
}

function people_get_random_bio() {
	global $wpdb;
	$row = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS count FROM ".$wpdb->prefix."bios"));
	$count = $row[count];
	$rid = rand(1,$count);
	people_get_bio_by_id($rid);
}

function people_get_bio_by_id($id) {
	global $wpdb;
	$result = mysql_fetch_array(mysql_query("SELECT id, uid, text FROM ".$wpdb->prefix."bios WHERE uid='$id'"));
	$presult = mysql_fetch_array(mysql_query("SELECT * FROM ".$wpdb->prefix."people WHERE id='$id'"));
	echo "<h3 class='people-bio-heading'><a title='Permanant Link to $presult[first_name] $presult[last_name]' rel='bookmark' href='".people_generate_permalink($presult['slug'])."'>$presult[first_name] $presult[last_name]</a></h3>\n";
	$sections = explode("{more}",$result[text]);
	echo "<div class='people-bio'>\n\t<img class='people-bio-img' src='".get_option('siteurl')."/wp-content/plugins/profiles/library/bio-img.php?id=$result[id]' />\n\t<div class='people-bio-text'>$sections[0]";
	if (count($sections) > 1) {
		echo "<a href='#' class='people-bio-text-control'>More</a>";
		echo "\n\t<div class='people-bio-text-extended' style='display: none'>$sections[1]</div>";
	}
	echo "\n\t</div>\n</div>\n";
}

function people_get_bio_by_slug($slug) {
	global $wpdb;
	$result = mysql_fetch_array(mysql_query("SELECT id FROM ".$wpdb->prefix."people WHERE slug='$slug'"));
	people_get_bio_by_id($result['id']);
}

function people_confirm_delete($id) {
	global $wpdb;
	$result = mysql_query("SELECT ".$wpdb->prefix."people.first_name AS first_name, ".$wpdb->prefix."people.last_name AS last_name, ".$wpdb->prefix."people.id AS id, ".$wpdb->prefix."people.slug AS slug, ".$wpdb->prefix."bios.id AS bid, ".$wpdb->prefix."bios.text AS text FROM ".$wpdb->prefix."people, ".$wpdb->prefix."bios WHERE ".$wpdb->prefix."people.id='$id' AND ".$wpdb->prefix."people.id=".$wpdb->prefix."bios.uid");
	$row = mysql_fetch_array($result);
	echo "<form>\n";
	echo "<input type='hidden' class='people-delete-bid' value='$row[bid]' />";
	echo "<input type='hidden' class='people-delete-id' value='$id' />";
	echo "<h3>Are you sure you want to delete $row[first_name] $row[last_name]?</h3>\n";
	echo "<p>Once you have deleted a profile it cannot be recovered</p>\n";
	echo "</form>\n";
	echo "<div style='clear: both'>&nbsp;</div>\n";
	echo "<p class='submit'><input type='button' class='people-delete-cancel' value='Cancel' /><input type='button' class='people-delete-submit' value='Submit' /></p>\n";
}
	
?>