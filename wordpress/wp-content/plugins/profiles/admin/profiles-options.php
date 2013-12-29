<?php
/*
Profiles Options Page
*/

require_once('../wp-content/plugins/profiles/library/config.php');

?>
<?php

// Update options

if (isset($_POST['profiles_setup_complete'])) update_option('profiles_setup_complete',$_POST['profiles_setup_complete']);


if (isset($_POST['profiles_default_text'])) update_option('profiles_default_text',$_POST['profiles_default_text']);
if (isset($_POST['profiles_user_level'])) update_option('profiles_user_level',$_POST['profiles_user_level']);
if (isset($_POST['profiles_image_width'])) update_option('profiles_image_width',$_POST['profiles_image_width']);
if (isset($_POST['profiles_image_height'])) update_option('profiles_image_height',$_POST['profiles_image_height']);
if (isset($_POST['profiles_image_watermark_add'])) update_option('profiles_image_watermark_add',$_POST['profiles_image_watermark_add']);
if (isset($_POST['profiles_image_watermark_text'])) update_option('profiles_image_watermark_text',$_POST['profiles_image_watermark_text']);
if (isset($_POST['profiles_image_watermark_color'])) update_option('profiles_image_watermark_color',$_POST['profiles_image_watermark_color']);
if (isset($_POST['profiles_image_watermark_font'])) update_option('profiles_image_watermark_font',$_POST['profiles_image_watermark_font']);
if (isset($_POST['profiles_image_watermark_size'])) update_option('profiles_image_watermark_size',$_POST['profiles_image_watermark_size']);
if (isset($_POST['profiles_location'])) update_option('profiles_location',$_POST['profiles_location']);
// Default format if (isset($_POST['profiles_!'])) update_option('profiles_!',$_POST['profiles_!']);

if (isset($_POST['profiles_load_jquery'])) {
	update_option('profiles_load_jquery','true');
} else {
	if (isset($_POST['profiles_setup_complete'])) {
		update_option('profiles_load_jquery','false');
	}
}	

if (isset($_POST['profiles_use_permalinks'])) {
	update_option('profiles_use_permalinks','true');
} else {
	if (isset($_POST['profiles_setup_complete'])) {
		update_option('profiles_use_permalinks','false');
	}
}	

if (isset($_POST['profiles_image_watermark_add'])) {
	update_option('profiles_image_watermark_add','true');
} else {
	if (isset($_POST['profiles_setup_complete'])) {
		update_option('profiles_image_watermark_add','false');
	}
}	

?>
<?php if (isset($_POST['profiles_setup_complete'])) {?><div class="updated"><p><strong>Options Updated</strong> The Profiles plugin has been activated.</p></div><?php } ?>
<div class="wrap">
	<h2>Profiles Advanced Options</h2>
    <p>Not much here at the moment, just a display of option values</p>
    <table class="form-table">
    	<tbody>
        	<tr valign="top">
            	<th scope="row">Database Revision</th>
                <td>
                	<input id="pdr" name="pdr" class="profiles_unchangeable_option" value="<?php echo get_option('profiles_database_revision'); ?>" disabled="true" size="24" type="text" />
                    <label for="pdr">Internal Database Revision</label>
                    <br />
                    <span class="explanatory-text">WARNING: <strong>Do not</strong> edit unless you have made changes to the table structure.</span>
                </td>
           </tr>
        </tbody>
    </table>
</div>
<form id="profiles_advanced_options" name="profiles_advanced_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<div class="wrap" id="general-options">
    <h2>General Options</h2>
    <table class="form-table">
    	<tbody>
       		<tr valign="top">
            	<th scope="row">Default Profile Text</th>
                <td>
                	<input id="profiles_default_text" name="profiles_default_text" class="profiles_general_option" value="<?php echo get_option('profiles_default_text'); ?>" type="text" size="50" />
                    <br />
                    <span class="explanatory-text">Defaults to: <code>{first_name}</code> <code>{last_name}</code> <code>{default_text}</code></span>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">jQuery</th>
                <td>
                	<input id="profiles_load_jquery" name="profiles_load_jquery" class="profiles_general_option" type="checkbox" <?php if (get_option('profiles_load_jquery') == 'true') echo "checked"; ?> />
                    <label for="profiles_load_jquery">Load jQuery on display page</label>
                    <br />
                    <span class="explanatory-text">Note: k2 loads jQuery by default as do some other plugins.</span>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">User Access Level</th>
                <td>
                    <input id="profiles_user_level" name="profiles_user_level" class="profiles_general_option" type="text" value="<?php echo get_option('profiles_user_level'); ?>" size="4"/>
                    <label for="profiles_user_level">Userlevel to edit profiles</label>
                    <br />
                    <span class="explanatory-text">Note: This is for the "Manage > Profiles" tab only. This tab <em>always</em> requires an administrator</span>
                </td>
            </tr>
       </tbody>
    </table>
</div>
<div class="wrap" id="watermarking-options">
    <h2>Images & Watermarking</h2>
	<h3>Images</h3>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row">Image Size</th>
                <td>
                    <label for="profiles_image_width">Width</label>
                    <input name="profiles_image_width" id="profiles_image_width" value="<?php echo get_option('profiles_image_width'); ?>" size="6" type="text" />
                    <label for="profiles_image_height">Height</label>
                    <input name="profiles_image_height" id="profiles_image_height" value="<?php echo get_option('profiles_image_height'); ?>" size="6" type="text" />
                    <br />
                </td>
            </tr>
        </tbody>    
    </table>
    <h3>Watermarking</h3>
    <p>Watermarking is experimental at this point. It saves a short bit of text over the lower-right corner of profile images on upload. Some versions of PHP and gd are compiled without required functions. Enable watermarking at your own risk.</p>
    <table class="form-table">
    	<tbody>
            <tr valign="top">
            	<th scope="row">Watermark</th>
                <td>
                	<input name="profiles_image_watermark_add" id="profiles_image_watermark_add" type="checkbox" <?php if (get_option('profiles_image_watermark_add') == 'true') echo "checked"; ?> />
                    <label for="profiles_image_watermark_add">Enable Image Watermarking</label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Watermark Text</th>
                <td>
                	<input name="profiles_image_watermark_text" id="profiles_image_watermark_text" type="text" size="30" value="<?php echo get_option('profiles_image_watermark_text'); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Watermark Color</th>
                <td>
                	<input name="profiles_image_watermark_color" id="profiles_image_watermark_color" type="text" size="30" value="<?php echo get_option('profiles_image_watermark_color'); ?>" />
                    <br />
                    <span class="explanatory-text">Must be <em>exactly</em> of the form: NNNNNN#MMMMMM where NNNNNN and MMMMMM are hex colors</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Watermark Font</th>
                <td>
                	<input name="profiles_image_watermark_font" id="profiles_image_watermark_font" type="text" size="30" value="<?php echo get_option('profiles_image_watermark_font'); ?>" />
                    <br />
                  	<span class="explanatory-text">The name of the .ttf file in the fonts directory. Don't include .ttf.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Watermark Size</th>
                <td>
                	<input name="profiles_image_watermark_size" id="profiles_image_watermark_size" type="text" size="8" value="<?php echo get_option('profiles_image_watermark_size'); ?>" />
					<label for="profiles_image_watermark_size">Watermark size in points</label>
                </td>
            </tr>            
        </tbody>
    </table>
</div>
<div class="wrap" id="permalink-options">
    <h2>Permalinks</h2>
    <p>In order for profiles to function properly, it needs to know which page you are using as the base for your profiles. Create a new page, with the desired title and a blank body using the "profiles" template. Included in the template folder are templates for both the default and kubrick themes. It should be fairly easy to adapt these to other themes. Once you have created the page, not it's "slug" location. If you created a page at <?php echo get_option('blogurl'); ?>/people then the slug is 'people'.</p>
    <p>If using permalinks, then individual pages will use 'pretty permalinks' of the form <?php echo get_option('blogurl'); ?>/<?php echo get_option('profiles_location'); ?>/{person_slug}, otherwise, they are of the form <?php echo get_option('blogurl'); ?>/<?php echo get_option('profiles_location'); ?>/?people_slug=<code>{slug}</code>.</p>
    <p><strong>IMPORTANT:</strong> After enabling permalinks, go to the 'Settings > Permalinks' page and click "Save" to write the permalinks to file.</p>
    <table class="form-table">
        <tbody>
        	<tr valign="top">
            	<th scope="row">Permalinks</th>
                <td>
                    <input name="profiles_use_permalinks" id="profiles_use_permalinks" type="checkbox" <?php if (get_option('profiles_use_permalinks') == 'true') echo "checked"; ?> />
                    <label for="profiles_use_permalinks">Use Permalinks</label>
                    <br />
                    <span class="explanatory-text">If disabled, links are of the form <?php echo get_option('profiles_location'); ?>/?people_slug=<code>{slug}</code></span>
                </td>
            </tr>
            <tr valign="top">
            	<th scope="row">Profiles Location</th>
                <td>
                	<input name="profiles_location" id="profiles_location" type="text" value="<?php echo get_option('profiles_location'); ?>" />
                    <br />
                    <span class="explanatory-text">Omit leading slash. I.E. for <code>http://url.com/people</code> enter <code>people</code>.</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<input name="profiles_setup_complete" id="profiles_setup_complete" type="hidden" value="true" />
<div class="profiles_advanced_options_submit">
	<p><input type="submit" name="Submit" value="Save >" /></p>
</div>
</form>