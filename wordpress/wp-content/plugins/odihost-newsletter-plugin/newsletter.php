<?php

/*
Plugin Name: Odihost Newsletter Plugin
Plugin URI: http://odihost.com/newsletter-plugin
Description: Create opt-in form to save user data and send email to your mailing lists. 
Version: 1.0
Autdor: OdiHost
Autdor URI: http://www.odihost.com/

    Copyright 2011  OdiHost (email : support@odihost.com)

    tdis program is free software; you can redistribute it and/or modify
    it under tde terms of tde GNU General Public License as published by
    tde Free Software Foundation; eitder version 2 of tde License, or
    (at your option) any later version.

    tdis program is distributed in tde hope tdat it will be useful,
    but WItdOUT ANY WARRANTY; witdout even tde implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of tde GNU General Public License
    along witd tdis program; if not, write to tde Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

	//register_activation_hook(__FILE__, 'odihost_newsletter_install');
	session_start();
	
	if(is_admin()){
	   include(dirname(__FILE__).'/includes/admin.php');
	}
	else
	{	
		include(dirname(__FILE__).'/includes/front.php');
	}

	if (is_admin()) {
		add_action('admin_menu' , 'create_admin_menu');
	}

	register_activation_hook(__FILE__,'odihost_newsletter_install');

	require ("includes/common.php");
	require ("widget/newsletter-widget.php");
	if($_REQUEST["process"] == "export")
	{
	function create_csv($rows, $end_of_line ="\r\n", $pre_value='"',$post_value='",'){
			$captions = array('Id','Name','Email','IP','Join Date','Status');
			foreach($captions as $caption)
				$contents .= 	$pre_value . $caption . $post_value;

			$contents .= $end_of_line;
			foreach($rows as $row): 
				$contents .= 	$pre_value . $row->id . $post_value;
				$contents .= 	$pre_value . $row->name . $post_value;
				
				$contents .= 	$pre_value . $row->email . $post_value;
				$contents .= 	$pre_value . $row->ip . $post_value;
				$contents .= 	$pre_value . $row->joindate . $post_value;
				$contents .= 	$pre_value . $row->status . $post_value;
				
				$contents .= $end_of_line;
			endforeach;
			return $contents;
		}
		
		global  $wpdb;
		$table_users = $wpdb->prefix . "newsletter_users";
		$subscribers = $wpdb->get_results("SELECT * FROM $table_users where status=1 ORDER BY `id` DESC");
		$output = create_csv($subscribers);

		$filename = 'subscribers(' . date('j-n-Y') .').csv';
		header('Content-type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'. $filename . '"');		
		echo $output;
		exit(0);
			
	}

?>