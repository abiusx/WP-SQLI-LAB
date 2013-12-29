<?php
// Profiles Main Javascript
// Uses PHP to pull neccessary 'option' values out of the wpdb
header("Content-Type: text/javascript");
require_once("../library/config.php");

?>
jQuery(document).ready(function(){
	show_hide_link();
	jQuery("a.people-listi-link").click(function(){
		change_content(this);
		return false;
	});
	jQuery(".people-load-block").fadeOut("fast");
});
function change_content(lk) {
jQuery(".people-load-block").fadeIn("fast");
jQuery(".entry-content").hide("slow",function() {
	jQuery(".entry-content").load(
		"<?php echo get_option('siteurl'); ?>/wp-content/plugins/profiles/ajax.php", 
		{id: lk.id, display: true},
		function(){
			jQuery(this).show("slow");
			jQuery(".people-load-block").fadeOut("fast");
			show_hide_link();
			process_list(lk);
	});
});
}
function show_hide_link() {
	jQuery("a.people-show-list-control").toggle(
		function(){
			jQuery(".people-show-list-content").hide("slow");
			jQuery(this).html("Show");
			return false;
		},
		function(){
			jQuery(".people-show-list-content").show("slow");
			jQuery(this).html("Hide");
			return false;
	});
	jQuery("a.people-bio-text-control").toggle(
		function(){
			jQuery(".people-bio-text-extended").show("slow");
			jQuery(this).html("Hide");
			return false;
		},
		function(){
			jQuery(".people-bio-text-extended").hide("slow");
			jQuery(this).html("More");
			return false;
	});
}
function process_list(lk) {	// Highlights the currently active name.
	jQuery(".profiles_active_link").removeClass("profiles_active_link");
	jQuery(lk).addClass("profiles_active_link");
}