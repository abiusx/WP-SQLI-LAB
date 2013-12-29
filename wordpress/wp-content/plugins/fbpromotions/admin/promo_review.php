<?php
/**
*	BugsGoViral Plugin for Worpress
*	Promo List View
*
*	@filesource 			promo-list.php
*	@description			HTML to display list of promotions view
*	@author					BugsGoViral DevTeam (devteam@bugsgoviral.net)
* */
include("version.php");
/**
*	@todo 					style now that tables changed to divs - use style_promo_list.css
*	@todo 					Need to strip/trim input data on textarea and validate input for security
*
* */

/**
* Start display code
*/

?>

<div id="promotions_container" class="wrap">

<?php

do_html_admin_masthead("promo_list","Promotions List","Promotions List Heading",1);

do_html_cron_test_button("block");

do_html_add_new_promo_button("block");

?>

<!-- TABLE for Promo List -->
<table id="data_table_active_promotions" class="data_table">
		<tr>
			<th>Title</th>
			<th>End Date</th>
			<th>Edit</th>
			<th>Preview</th>
			<th>Activate</th>
			<th>Analytics</th>

<!--
			<th>Delete</th>
			<th>Review</th>
			<th>Entries</th>
			<th>Details</th>
			<th>Notifications</th>
-->
		</tr>
<?php

$mdate=date("Y-m-d");
	foreach ($promo_list as $promo_row)
	{
		if ($promo_row->promo_end<=$mdate)    $status="Closed";
		elseif ($promo_row->promo_start>=$mdate)    $status="Not Active";
		else    $status="Active";
		?>

		<tr>
			<td>
				<?php echo($promo_row->promo_name  .  "<br />$status") ?>
			</td>
			<td class="promo_end_date">
				Ends: <?php echo($promo_row->promo_end) ?>
			</td>
			<td>

			<?php do_html_edit_by_promo_id_button("block",$promo_row->promo_id) ?>

			</td>
			<td>

			<?php do_html_delete_by_promo_id_button("block",$promo_row->promo_id) ?>

			</td>
			<td>
			<?php
			do_html_review_promo_button(
				"block",
				$fbmo->options["fb_canvas_url"].$promo_row->promo_page)
			?>
			</td>

			<?php if(empty($promo_row->activation_key)): 	?>

			<td>
				<div id="activate_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_edit_action" value="activate">
				<input type="hidden" name="page" value="fbm_edit_promo">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Activate" type="submit">
				</form>
				</div><!-- DIV END "activate_by_promo_id_button" -->
			</td>

			<?php endif; ?>

			<td>
				<div id="entries_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_edit_action" value="enter_winners">
				<input type="hidden" name="page" value="fbm_edit_promo">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Entries" type="submit">
				</form>
				</div><!-- DIV END "entries_by_promo_id_button" -->
			</td>
			<td>
				<div id="details_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_edit_action" value="show_details">
				<input type="hidden" name="page" value="fbm_promo_detail">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Details" type="submit">
				</form>
				</div><!-- DIV END "details_by_promo_id_button" -->
			</td>

			<?php  //march2 updates

			if( $notifications_obj != null && $notifications_obj->get_enable_module_notification_value() ) 	{

				?>

			<td>
				<div id="notifications_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_notification" value="notify_users">
				<input type="hidden" name="page" value="fbm_promo_notification">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Notifications" type="submit">
				</form>
				</div><!-- DIV END "notifications_by_promo_id_button" -->
			</td>
			<?php
				}
			?>
		</tr>
	<?php
	}
	?>
</table>

</div>


<?php do_html_admin_footer("promo_list","Promotions List") ?>


