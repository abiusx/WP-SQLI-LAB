<?php
/*
Provides data used for loading chunks.
Designed to reduce the overhead that would be required to load
the complete page and then filter the correct divs.
*/
?>
<?php

// Setup includes
require_once('library/config.php');

//require('library/plugin-functions.php');
//require('library/people-functions.php');

if ( isset($_POST['id']) && isset($_POST['display']) ) {
	people_get_bio_by_id($_POST['id'],false);
}
else if ( isset($_POST['id']) && isset($_POST['edit']) ) {
	people_get_editor($_POST['id']);
}
else if ( isset($_POST['edit']) && isset($_POST['remote']) ) {
	people_get_edit_table();
}
else if ( isset($_POST['delete']) && isset($_POST['id']) ) {
	people_confirm_delete($_POST['id']);
}
?>