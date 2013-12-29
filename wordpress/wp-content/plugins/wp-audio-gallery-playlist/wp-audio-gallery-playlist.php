<?php
/*
Plugin Name: wp audio gallery playlist
Plugin URI: http://dliem.wordpress.com
Description: This is a plugin to allow audio associated with a post to be played in an XSPF player, multiple players.  To insert a single track, simply insert the audio into your post via "Add Audio".  To insert ALL music associated with your post insert the tag [WP AUDIO PLAYLIST] into your post. (WP 2.6 or greater required)
Author: David Liem
Version: 0.12
Author URI: http://dliem.wordpress.com


	Copyright (C) 2008  David Thomas Liem

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

Change Log: 
	2008.11.04 - added http_user_agent checker to disable flash player when user is iphone or ipod
	2008.06.01 - added size options and auto embed for single player option
	2009.06.12 - fix for undefined playlist issue.  turned out i had hardcoded the "wp_" prefix causing the issue for some folks.
	2009.06.22 - i managed to miss the wp_ prefix issue in playlist.php.  fixed it.
 */

?>
<?php

$wp_events_db = wp_eventscalendar_main;

$player = "slim";

if(isset($_GET['activate']) && ($_GET['activate'] == 'true')) {		
	$gallery_player_width = get_option('wp-audio-gallery_player_width');	
	$gallery_player_height = get_option('wp-audio-gallery_player_height');
	$single_player_width = get_option('wp-audio-single_player_width');
	$single_player_enable = get_option('wp-audio-single_player_enable');
	if ($gallery_player_width == FALSE || gallery_player_height == FALSE || $single_player_width == FALSE || $single_player_enable == FALSE) {
		//echo "NO OPTION: wp-audio-gallery_player_width<br>";
		add_option("wp-audio-gallery_player_width", '400', '', '');
		add_option("wp-audio-gallery_player_height", '153', '', '');
		add_option("wp-audio-single_player_width", '400', '', '');
		add_option("wp-audio-single_player_enable", 'yes', '', '');
	}
	/*else {
		echo "FOUND IT: <br>";
	}*/
}

add_filter('the_content', 'wp_audio_gallery_playlist');
add_action('wp_head', 'wp_audio_gallery_playlist_wp_head');
add_action('admin_menu', 'wp_audio_gallery_playlist_option');

function wp_audio_gallery_playlist_wp_head() {
    $url = get_settings('siteurl');
	echo "<link rel='stylesheet' href='$url/wp-content/plugins/wp-audio-gallery-playlist/wp-audio-gallery-playlist_style.css' type='text/css' media='all' />";
}

function wp_audio_gallery_playlist( $content )
{
	global $wpdb;
	global $post;
	global $player;

	$table_name = $wpdb->prefix . "posts";

	$container = $_SERVER['HTTP_USER_AGENT'];
	//$content .= "This is a $container user agent<br>";
	
	/*$useragents = array ('iPhone','iPod');
	$this->mobile = false;
	if ($useragents != NULL) {
		foreach ( $useragents as $useragent ) {
			echo "HELLO loop ";
			if (eregi($useragent,$container)){
				$this->mobile = true;
			}
		}
	}
	else {
		echo "NULL.. ";
	}*/
	
	/*if ($this->iphone) {
		$content .= "This is an iPhone<br>";
	}
	else {
		$content .= "Not an iPhone <br>";
	}*/
	
	if ($player == "button")
		$xspf_player = get_option('home') . "/wp-content/plugins/wp-audio-gallery-playlist/xspf/musicplayer.swf";
	else {
		$xspf_player_slim = get_option('home') . "/wp-content/plugins/wp-audio-gallery-playlist/xspf/xspf_player_slim.swf";
		$xspf_player = get_option('home') . "/wp-content/plugins/wp-audio-gallery-playlist/xspf/xspf_player.swf";
	}
		
	$xspf_xml = get_option('home') . "/wp-content/plugins/wp-audio-gallery-playlist/playlist.php?post=";
	$xspf_xml_gallery = get_option('home') . "/wp-content/plugins/wp-audio-gallery-playlist/playlist.php?post_gallery=";
	
	$single_player_enable = get_option('wp-audio-single_player_enable');
	
	// 
	$search = "/\[WP AUDIO PLAYLIST\]/";
	preg_match_all($search, $content, $lc_matches);
	
	if ($single_player_enable == "yes") {
		$search_single = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.mp3))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";
		preg_match_all($search_single, $content, $lc_matches_single);
	}
	

	if (is_array($lc_matches[0])) //&& !$this->mobile)
	{
		foreach ($lc_matches[0] as $filename)
		{
			$search = "[WP AUDIO PLAYLIST]";
			
			//$current_date = date("Y-m-d",time());
			

				$replace = "<table>";
									
		    //if (results == 0)  $content .= "NO RESULTS<br>";
		    if ($player == "button") {
		    
				$replace .= "<tr><td colspan=2 class='audio_gallery'><strong>Track Title</strong></td>";
				$replace .= "<td></td></tr>";
				$query = "SELECT * FROM `".$table_name."` WHERE `post_parent` = '$post->ID' AND  `post_mime_type` = 'audio/mpeg' ORDER BY `menu_order` ASC";
			
					
				//echo $query;
				$results = $wpdb->get_results($query);

				foreach ($results as $tracks) {
					$replace .= "<tr><td class='gallery_left'>$tracks->post_title</td>";
					$replace .= "<td class='gallery_right'><span id=\"xspf_player0\">\n
								<object	type=\"application/x-shockwave-flash\" data=\"$xspf_player?autoload=true&amp;playlist_url=$xspf_xml$tracks->ID\"
		   						width=\"17\" height=\"17\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\">
		   						<param name=\"movie\" value=\"$xspf_player?autoload=true&amp;playlist_url=$xspf_xml$tracks->ID\" />
								<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		 						<param name=\"quality\" value=\"high\" />
								<param name=\"wmode\" value=\"transparent\" />
								</object>
	   						  </span></td></tr>";

				}
			}
			else {
					$replace .= "<tr><td><span id=\"xspf_player0\">\n
								<object	type=\"application/x-shockwave-flash\" data=\"$xspf_player?autoload=true&amp;playlist_url=$xspf_xml_gallery$post->ID\"
		   						width=\"".get_option('wp-audio-gallery_player_width')."\" height=\"".get_option('wp-audio-gallery_player_height')."\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\">
		   						<param name=\"movie\" value=\"$xspf_player?autoload=true&amp;playlist_url=$xspf_xml_gallery$post->ID\" />
								<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		 						<param name=\"quality\" value=\"high\" />
								<param name=\"wmode\" value=\"transparent\" />
								</object>
	   						  </span></td></tr>";
			}
			
			$replace .= "</table>";
			
			$content = str_replace ($search, $replace, $content);
		}
	}
	
	
	if ($single_player_enable == "yes") {
	if (is_array($lc_matches_single[0]) ) //&& !$this->mobile)
	{
		foreach ($lc_matches_single[0] as $filename)
		{
			//
			$search_single = "$filename";
			$search_file = "/(([^\"']+\.mp3))/";
			preg_match($search_file, $filename, $match_file);
			
			$query = "SELECT * FROM `".$table_name."` WHERE `post_parent` = '$post->ID' AND  `post_mime_type` = 'audio/mpeg' AND `guid` = '$match_file[0]' LIMIT 1";
			$track = $wpdb->get_row($query);
			
				//$replace = "$match_file[0] ($result)";
			
				$replace = "<table class='single_track'>";
				
				
		    	if ($player == "button") {
					$replace .= "<tr><td class='single_track_left'>$track->post_title</td>";
					$replace .= "<td width=15 class='single_track_right'><span id=\"xspf_player0\">\n
								<object	type=\"application/x-shockwave-flash\" data=\"$xspf_player?autoload=true&amp;playlist_url=$xspf_xml$track->ID\"
		   						width=\"17\" height=\"17\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\">
		   						<param name=\"movie\" value=\"$xspf_player?autoload=true&amp;playlist_url=$xspf_xml$track->ID\" />
								<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		 						<param name=\"quality\" value=\"high\" />
								<param name=\"wmode\" value=\"transparent\" />
								</object>
	   						  </span></td></tr>";
				
				}
				else {
					$replace .= "<tr><td><span id=\"xspf_player0\">\n
								<object	type=\"application/x-shockwave-flash\" data=\"$xspf_player_slim?autoload=true&amp;playlist_url=$xspf_xml$track->ID\"
		   						width=\"".get_option('wp-audio-single_player_width')."\" height=\"17\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\">
		   						<param name=\"movie\" value=\"$xspf_player_slim?autoload=true&amp;playlist_url=$xspf_xml$track->ID\" />
								<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		 						<param name=\"quality\" value=\"high\" />
								<param name=\"wmode\" value=\"transparent\" />
								</object>
	   						  </span></td></tr>";
				}
				
				$replace .= "</table>";
			
			$content = str_replace ($filename, $replace, $content);
			
		}
	}
	}

	return $content;
}

function wp_audio_gallery_playlist_option() {
	add_options_page('WP Audio Gallery Options', 'WP Audio Gallery', 10, 'wp-audio-gallery-playlist/options.php');
}

?>
