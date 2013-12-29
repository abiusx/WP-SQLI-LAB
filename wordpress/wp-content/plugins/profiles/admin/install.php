<?php
/*

Install Script for the profile viewer

ver. 2.0 RC 1

*/

function profiles_install_database() {

	require_once(dirname(__FILE__). '/../../../../wp-admin/upgrade-functions.php');

	global $wpdb;
	$profiles_database_revision = "DB: 2.0, Release: 2.0 RC 1, Dataformat: 2.6.7.4, Options: -facebook -shows -udbs";

	$people_table = $wpdb->prefix."people";
	$bios_table = $wpdb->prefix."bios";
	
	if (get_option('profiles_databse_revision') != $profiles_database_revision) {
		$sql = "CREATE TABLE `".$people_table."` (
			`id` int(11) NOT NULL auto_increment,
			`first_name` varchar(30) default NULL,
			`last_name` varchar(30) default NULL,
			`slug` varchar(60) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM ;";

		dbDelta($sql);

		$sql2 = "CREATE TABLE `".$bios_table."` (
			  `id` int(11) NOT NULL auto_increment,
			  `uid` int(11) NOT NULL default '0',
			  `text` longtext NOT NULL,
			  `photo` blob,
			 PRIMARY KEY  (`id`)
			) ENGINE=MyISAM ;";
      
		dbDelta($sql2);
	
		update_option('profiles_database_revision',$profiles_database_revision);

	}

}
?>