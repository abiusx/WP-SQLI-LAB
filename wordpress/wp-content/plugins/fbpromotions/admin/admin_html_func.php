<?php
/**
*	BugsGoViral Plugin for Worpress
*	Admin HTML Functions
*
*	@filesource 			admin_html_func.php
*	@description			Functions for writing HTML to admin views
* */
include("version.php");

/**
*	@name			admin_images_url ()
*	@description	Defines the root url to use to link to Admin UI images
*/
function admin_images_url () {
	$admin_images_url  =  get_option('siteurl')  .  "/wp-content/plugins/fbpromotions/admin/images";
	return $admin_images_url;
}

/**
*	@name			do_html_admin_masthead($title,$slug)
* 	@description	Writes the html code to display the Masthead in Admin UI
* 	@param 			$slug = link friendly page identification (i.e. "page_name") 
* 	@param 			$title = Human readable name for page to display  (i.e. "Page Name")
* 	@param 			$header = Human readable name to display in header bar  (i.e. Header Text")
* 	@param 			$val = print header bar value, 1 = print header, 0 = do not print header
*/	
function do_html_admin_masthead($slug,$title,$header,$val)  {
	echo '
	<div id="'  .  $slug  .  '_masthead" class="masthead"><h1>Bugs Go Viral &raquo; '  .   $title  .  '</h1>
	<hr width="100%" size="1" noshade>
	<img src="'  .  admin_images_url()  .  '/headergraphic.png" width="700" height="125">';

	if ($val == 1) echo '
	<div id="'  .  $slug  .  '_heading" class="heading">'  .  $header  .  '</div><!-- DIV END "'  .  $slug  .  '_heading" -->';
	
	if (isset($fbmo->admin_messages['message'])) {
	echo '<div  class="error">'  .  $fbmo->admin_messages['message']  .  '</div>'; 
	} else {
	echo '</div>';
		
	}
}

/**
*	@name			do_html_admin_footer($title,$slug)
* 	@description	Writes the html code to display the Footer in Admin UI
* 	@param 			$slug = link friendly page identification (i.e. "page_name") 
* 	@param 			$title = Human readable name for page to display  (i.e. "Page Name")
*/	
function do_html_admin_footer($slug,$title)  {
	echo '
	<div id="'  .  $slug  .  '_footer" class="footer">
			<img src="'  .  admin_images_url()  .  '/copyright.jpg" width="657" height="56">
	</div>';
}

?>