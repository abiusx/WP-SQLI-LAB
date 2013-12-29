<?php
/*Copyright (C) 2008  David Thomas Liem

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
 */

?>
<?php 
error_reporting(E_ALL);
header('Content-Type: text/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>', "\n";
echo '<!-- ';  // Comment some headers introduced by some free web hosts
require_once('../../../wp-config.php');
require_once('../../../wp-settings.php');
echo ' -->', "\n";

$charset = get_option('blog_charset');
?>
<playlist version="0" xmlns = "http://xspf.org/ns/0/">
  <title>XSPF Player</title>
  <annotation>http://musicplayer.sourceforge.net</annotation>
 <trackList>
<?php

global $wpdb;
$table_name = $wpdb->prefix . "posts";

if (isset($_GET['post']) || isset($_GET['post_gallery'])) {

	if (isset($_GET['post_gallery']))
		$query = 'SELECT * FROM `'.$table_name.'` WHERE `post_parent` = \''.$_GET['post_gallery'].'\' AND  `post_mime_type` = \'audio/mpeg\' ORDER BY `menu_order` ASC';
	else
		$query = 'SELECT * FROM `'.$table_name.'` WHERE `ID` = \''.$_GET['post'].'\' ';
		
	//echo $query;
	$results = $wpdb->get_results($query);
	//$results = $mysql_query($query) or die (mysql_error());
	if($results != 0) {
		foreach ($results as $track) {
			//echo $track->post_title;
			echo "<track>\n";
			echo "<location>".$track->guid."</location>\n";
			/*echo "<image></image>";*/
			echo "<annotation>" . $track->post_title."</annotation>";
			echo "<image>" . $track->post_excerpt."</image>";
			echo "<info>".$track->post_description."</info>";
			echo "</track>\n";
		}
	}
	else echo "NO RESULTS";
}


?>
 </trackList>
</playlist>
