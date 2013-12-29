<?php
/**
*	BugsGoViral Plugin for Worpress
*	Delete Promotion View
*
*	@filesource 			promo-delete.php
*	@description			HTML view for deleting promotion
* */
include("version.php");

/**
*	@todo 					Style now that tables removed
*	@todo 					Add confirmation screen routine for delete and cancels
*
* */

/**
* Start display code
*/
?>
<div id="promo_delete_container" class="wrap">
	<?php do_html_admin_masthead ("promo_delete","Delete Promotion","DELETE >> " . $promo_row["promo_name"],1) ?>

	<div id="promo_delete_form_container" class="form_container">
		<form id="promo_delete_form" name="promo_delete_form" method="post">
			<div id="promo_delete_form_fields_hidden"  class="form_fields_hidden" >
				<input type="hidden" name="fb_edit_action" value="delete_step_2">
				<input type="hidden" name="page" value="fbm_edit_promo">
				<input type="hidden" name="promo_id" value="<?php echo($_REQUEST["promo_id"]); ?>">
			</div><!-- DIV END "promo_delete_form_fields_hidden" -->
			<div id="promo_delete_form_fields_shown"  class="form_fields_shown" >
				<ul>
					<li>
						<ul>
							<li>Promotion Page &raquo;</li>
							<li><?php echo($promo_row["promo_page"])?></li>
						</ul>
					</li>
					<li>
						<ul>
							 <li>Promotion Name &raquo;</li>
							 <li><?php echo($promo_row["promo_name"])?></li>
						</ul>
					</li>
					<li>
						<ul>
							<li>Short Description &raquo;</li>
							<li><?php echo($promo_row["promo_description"])?></li>
						</ul>
					</li>
				</ul>
				<div id="promo_delete_form_user_action_bar" class="user_action_bar_bottom" >
					<input type="button" name="Cancel" value="Cancel" onclick="window.history.back()" />
					<input type="submit" name="Submit" value="DELETE THIS PROMO &gt;&gt;&gt;">
				</div><!-- DIV END "promo_delete_form_user_action_bar" -->
			</div><!-- DIV END "promo_delete_form_fields_shown" -->
		</form>
	</div><!-- DIV END "promo_delete_form_container" -->
	<?php do_html_admin_footer("promo_delete","Delete Promotion") ?>
</div><!-- DIV END "promo_delete_container" -->

