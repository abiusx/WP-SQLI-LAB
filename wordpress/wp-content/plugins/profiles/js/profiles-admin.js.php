<?php
// Profiles Admin Javascript
// Uses PHP to pull neccessary 'option' values out of the wpdb
header("Content-Type: text/javascript");
require_once("../library/config.php");

?>

// Prepare the page when it loads
jQuery(document).ready(function() {
	edit_links();
	jQuery("#people-add-person-submit").click(function() {
		add_person();
	});
    jQuery("#people-add-first").keyup(function() {
        update_slug();
    });
	jQuery("#people-add-last").keyup(function() {
        update_slug();
    });
});

// Auto update the post slug
function update_slug() {
    var frst = jQuery("#people-add-first").val().replace(/'/g,"").replace(/ /g,"-");
    var last = jQuery("#people-add-last").val().replace(/'/g,"").replace(/ /g,"-");
	jQuery("#people-add-slug").val(frst+"-"+last);
}

// Make the table links work
function edit_links() {
	
	// The edit link
	jQuery("a.people-edit-single").click(function(){
		get_edit(this);
		return false;
	});
	
    // The delete link
    jQuery("a.people-delete-single").click(function(){
        get_delete(this);
        return false;
    });
    
	// Cancel Edit
	jQuery(".people-editor-cancel").click(function() {
		jQuery(".people-load-block").fadeIn("fast");
		jQuery(".people-editor").hide("slow",function() {
			jQuery(".people-edit-instructions").html("<p>Welcome! Please select a person to edit.</p>");
			jQuery(".people-edit-table").show("slow");
			jQuery("#people-add-new").fadeIn("slow");
			jQuery(".people-load-block").fadeOut("fast");
		});
	});
	
	// Submit new info
	jQuery(".people-editor-submit").click(function() {
		jQuery.get(
		"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/admin/people-edit.php",
		{id: jQuery(".people-editor-bid").val(), text: jQuery("#people-bio-edit-text").val()},
		function() {
			window.location = "<?php echo get_option('blogurl')."/".get_option('profiles_location'); ?>/?people_id=" + jQuery(".people-editor-id").val();
		});
	});
	
	// Change a photo
	jQuery(".people-new-photo").click(function() {  // formerly a.people-new-photo
		window.open(
			"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/admin/image-uploader.php?id="+jQuery(this).attr("id"),
			"Uploader",
			"status=0,toolbar=0,location=0,height=700,width=500"
		);
	});
	
	// Bye
	return true;
}

// Add a new person
function add_person() {
	jQuery("#people-add-person-title").fadeOut("fast",function() {
		jQuery(this).html("Adding . . .");
		jQuery(this).fadeIn("fast");
	});
	jQuery("#people-add-person").fadeOut("slow",function() {
		jQuery.post(
			"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/admin/people-edit.php",
			{new: true, first: jQuery("#people-add-first").val(), last: jQuery("#people-add-last").val(), slug: jQuery("#people-add-slug").val()},
			function() {
				update_list();
		});
		jQuery("#people-add-first").val("");
		jQuery("#people-add-last").val("");
		jQuery(".people-load-block").fadeIn("fast");
		jQuery(".people-edit-table").hide("slow");
	});
}

// Refresh the list
function update_list() {
	jQuery(".people-edit-table").load(
		"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/ajax.php",
		{edit: true, remote: true},
		function() {
			jQuery(".people-edit-instructions").html("<p>Welcome! Please select a person to edit.</p>");
			jQuery(".people-edit-table").show("slow");
			jQuery("#people-add-person").fadeIn("slow");
			jQuery("#people-add-person-title").fadeOut("fast",function() {
				jQuery(this).html("Add Person");
				jQuery(this).fadeIn("fast");
			});
			jQuery(".people-load-block").fadeOut("fast");
			edit_links();
	});
}

// Get the individual editor
function get_edit(lk) {
	jQuery(".people-load-block").fadeIn("fast");
	jQuery("#people-add-new").fadeOut("slow");
	jQuery(".people-edit-table").hide("slow",function() {
		jQuery(".people-editor").load(
			"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/ajax.php", 
			{id: lk.id, edit: true},
			function(){
				jQuery(".people-editor").show("slow");
				jQuery(".people-load-block").fadeOut("fast");
                jQuery(".people-edit-instructions").html("<p>To create a 'more' section type {more} outside of any tags. Until a rich text editor is included, you will need to use html tags around your content</p>");
				edit_links();
		});
	});
}

// Delete an individual user
function get_delete(lk)  {
	jQuery(".people-load-block").fadeIn("fast");
	jQuery("#people-add-new").fadeOut("slow");
	jQuery(".people-edit-table").hide("slow",function() {
		jQuery(".people-editor").load(
			"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/ajax.php", 
			{id: lk.id, delete: true},
			function(){
				jQuery(".people-editor").show("slow");
				jQuery(".people-load-block").fadeOut("fast");
                jQuery(".people-edit-instructions").html("<p>Please verify deletion.</p>");
                jQuery(".people-delete-cancel").click(function(){
                    jQuery(".people-editor").hide("fast");
                    update_list();
                    jQuery("#people-add-new").fadeIn("slow");
                });
                jQuery(".people-delete-submit").click(function(){
                    do_delete();
                });
		});
	});
}

// Perform the actual delete
function do_delete() {
		jQuery.post(
			"<?php echo get_option('wpurl'); ?>/wp-content/plugins/profiles/admin/people-edit.php",
			{delete: true, id: jQuery(".people-delete-id").val(), bid: jQuery(".people-delete-bid").val()},
			function() {
				    jQuery(".people-editor").hide("fast");
                    update_list();
                    jQuery("#people-add-new").fadeIn("slow");
		});
}