<?php
/**
*	BugsGoViral Plugin for Worpress
*	Promo-list.php HTML Functions
*
*	@filesource 			promo-list_html_func.php
*	@description			Functions for writing HTML to promo-list view
* */
include("version.php");
/**

*	@name		get_current_script_name()
*/
function get_current_script_name()  {
	$script_name_with_dir = $_SERVER["SCRIPT_NAME"];
	$break = Explode('/', $script_name_with_dir);
	$script_name = $break[count($break) - 1];
	return $script_name;
}

/**
*	@name		do_html_cron_test_button ("$str")
*/
function do_html_cron_test_button($str)  {

	echo '
	<div id="cron_test_button" class="button" style="display:'  .  $str  .  '">
	<form id="crontest" name="crontest" method="post">
			<input type="hidden" name="fb_edit_action" value="add">
			<input type="hidden" name="page" value="fbm_cron">
			<input name="submit" value="Cron Test" type="submit">
		</form>
	</div><!-- DIV END "cron_test_button" -->';
}

/**
*	@name		do_html_add_new_promo_button($str)
*/
function do_html_add_new_promo_button($str)  {

	echo '
	<div id="add_new_promo_button" class="button" style="display:'  .  $str  .  '">
	<form id="promoform" name="promoform" method="post">
	<input type="hidden" name="fb_edit_action" value="add">
	<input type="hidden" name="page" value="fbm_edit_promo">
	<input name="submit" value="Add New Promotion" type="submit">
	</form>
	</div><!-- DIV END "add_new_promo_button" -->';
}

/**
*	@name		do_html_edit_by_promo_id_button($str,$promo_id)
*/
function do_html_edit_by_promo_id_button($str,$promo_id)  {

	echo '
	<div id="edit_by_promo_id_button" class="button" style="display:'  .  $str  .  '">
	<form method="post">
	<input type="hidden" name="fb_edit_action" value="edit">
	<input type="hidden" name="page" value="fbm_edit_promo">
	<input type="hidden" name="promo_id" value="'  .  $promo_id  .  '">
	<input name="submit" value="Edit" type="submit">
	</form>
	</div><!-- DIV END "edit_by_promo_id_button" -->';
}


/**
*	@name		do_html_delete_by_promo_id_button($str,$promo_id)
*/
function do_html_delete_by_promo_id_button($str,$promo_id)  {

	echo '
	<div id="delete_by_promo_id_button" class="button" style="display:'  .  $str  .  '">
	<form method="post">
	<input type="hidden" name="fb_edit_action" value="delete">
	<input type="hidden" name="page" value="fbm_edit_promo">
	<input type="hidden" name="promo_id" value="'  .  $promo_id  .  '">
	<input name="submit" value="Delete" type="submit">
	</form>
	</div><!-- DIV END "delete_by_promo_id_button" -->';
}

/**
*	@name		do_html_review_promo_button($str,$promo_id)
*/
function do_html_review_promo_button($str,$promo_page)  {

	echo '
	<div id="review_promo_button" class="button"  style="display:'  .  $str  .  '">
	<form method="link" action="'  .  $promo_page  .  '.php" target="_blank">
	<input type="submit" value="Review">
	</form>
	</div><!-- DIV END "review_promo_button" -->';
}


