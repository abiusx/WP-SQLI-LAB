<?php
/*
Plugin Name: Profile Viewer
Plugin URI: http://compu.terlicio.us/code/plugins/profiles
Description: Profile Viewer / Biography Engine
Version: 2.0 RC 1
Author: Christopher O'Connell
Author URI: http://compu.terlicio.us/
*/
?>
<?php

$profiles_revision = "2.0 RC 1";

// Setup includes
require_once('library/config.php');

require_once('library/plugin-functions.php');
require_once('library/people-functions.php');

// Add activation script
register_activation_hook(__FILE__,'profiles_install');

// Hook to rewrite rules
add_filter('generate_rewrite_rules', 'people_rewrite_rules');

// Hook to the head
add_action('wp_head','people_add_head');
//add_action('admin_head','people_manage_add_head');

// Hooks for the admin pages
add_action('admin_menu','people_add_admin');

// Check if the current version of the plugin is installed
if (get_option('profiles_revision') != $profiles_revision) {
	profiles_install();
}

// Hook into DB setup
function profiles_install() {
	require_once('admin/install.php');
	profiles_install_database();
	global $profiles_revision;
	if (get_option('template') != "k2") {
		update_option('profiles_load_jquery','true');
	} else {
		update_option('profiles_load_jquery','false');
	}
	update_option('profiles_use_permalinks','true');
	update_option('profiles_default_text','is a ignominiously listed on '.get_option('blogname'));
	update_option('profiles_location','people');
	update_option('profiles_revision',$profiles_revision);
	update_option('profiles_setup_complete','false');
	update_option('profiles_user_level','1');
	update_option('profiles_image_width','200');
	update_option('profiles_image_height','300');
	update_option('profiles_image_watermark_add','false');
	update_option('profiles_image_watermark_text','Profiles');
	update_option('profiles_image_watermark_color','990100#123456');
	update_option('profiles_image_watermark_font','trumania');
	update_option('profiles_image_watermark_size','18');
}
?>
