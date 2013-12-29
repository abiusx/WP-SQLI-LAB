<?php
/**
*	BugsGoViral Plugin for Worpress
*	Promo List View
*
*	@filesource 			promo-list.php
*	@description			HTML to display list of promotions view
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
        if(empty($fbmo->options["fb_application_secret"])){
        ?>
        <div class='update-nag'>Facebook Settings needs to be updated.</div>
        <?php
        }
        ?>
<?php
do_html_admin_masthead("promo_list","masthead","Promotions List","Promotions List Heading",1,2);
?>
<!-- TABLE for Promo List -->
<table cellspacing="20" class="data_table" id="data_table_active_promotions">
		<tr>
			<th>Title</th>
			<th>End Date</th>
			<th>Edit</th>
			<th>Activate</th>
			<th>Analytics</th>
            <th>&nbsp;</th>

		</tr>
<?php
  
	 $activePromo = 0;
	foreach ($promo_list as $promo_row)
	{
		$time= time()-(5*3600);
		$mdate = date("Y-m-d h:i A",$time);
		$d1 = new DateTime($mdate);
		if($promo_row->promo_type==2){
			$end_date = date("Y-m-d h:i A",strtotime($promo_row->vote_end));
		}else
				$d2 = date("Y-m-d h:i A",strtotime($promo_row->promo_end));

		$d2 = new DateTime($d2);
		$status='';
		if(($d2>=$d1 )&& (!empty($promo_row->activation_key))){
			$activePromo = 1;
		}
	}
	
	foreach ($promo_list as $promo_row)
	{
		$status='';
		$time= time()-(5*3600);
		$mdate = date("Y-m-d h:i A",$time);
		$d1 = new DateTime($mdate);
		if($promo_row->promo_type==2){
			$end_date = date("Y-m-d h:i A",strtotime($promo_row->vote_end));
		}else
		$end_date = date("Y-m-d h:i A",strtotime($promo_row->promo_end));
		$d2 = new DateTime($end_date);
		if ($d2>=$d1)    $status="Not Active";
		if(!empty($promo_row->activation_key))    $status="Active";
		if ($d2<$d1)    $status="Closed";
		$landing = $promo_row->landing_order;
		?>
		<tr>
       
			<td>
				<?php echo($promo_row->promo_name  .  "<br />$status") ?>
			</td>
			<td class="promo_end_date">
				Ends: <?php echo($end_date) ?>
			</td>
			<td>

			<?php do_html_edit_by_promo_id_button("block",$promo_row->promo_id) ?>

			</td>

			<?php //do_html_delete_by_promo_id_button("none",$promo_row->promo_id) ?>


            <td>
			<?php if($status=="Not Active"){ 	
			?>
				<div id="activate_by_promo_id_button" class="button">
                <form id="actFrm" method="post"  onsubmit="<?php if($activePromo == 0){?>return true;<?php }elseif($activePromo == 1){?>alert('Only one promo can be activated at a time.');return false;<?php }?>"
                action="<?php echo get_option("serverurl")?>index.php?fb_edit_action=manage_activate&product_id=<?php  echo get_option("key")?>" target="_blank">
				<input type="hidden" name="fb_edit_action" value="manage_activate">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Activate" type="submit">
				</form>
				</div><!-- DIV END "activate_by_promo_id_button" -->
			<?php
			}else{
			?>
           		 <div id="activate_by_promo_id_button" class="button">
					<input name="submit" value="Activate" type="button" disabled="disabled" readonly="readonly">
				</div>
            <?php
			}
			 ?>
             </td>
			<td>
				<div id="entries_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_edit_action" value="enter_winners">
				<input type="hidden" name="page" value="fbm_edit_promo">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Participants" type="submit">
				</form>
				</div><!-- DIV END "entries_by_promo_id_button" -->
			</td>
           
			 <td>
				<div id="details_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_edit_action" value="delete_step_2">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Delete" type="submit">
				</form>
				</div> 
			</td>
 <?php
 if($status == "Closed" && $promo_row->promo_type==1  ){
			?>
            <td>
				<div id="entries_by_promo_id_button" class="button">
				<form method="post">
                    <input type="hidden" name="fb_edit_action" value="generate_winner">
                    <input type="hidden" name="page" value="fbm_edit_promo">
                    <input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
                    <input name="submit" value="Select Winner" type="submit">
				</form>
				</div><!-- DIV END "entries_by_promo_id_button" -->
			</td>
            <?php
 }elseif($status == "Closed" && $promo_row->promo_type==2){
			?>
            <td>
				<div id="entries_by_promo_id_button" class="button">
				<form method="post">
                    <input type="hidden" name="fb_edit_action" value="view_buzz_winner">
                    <input type="hidden" name="page" value="fbm_edit_promo">
                    <input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
                    <input name="submit" value="Winner" type="submit">
				</form>
				</div><!-- DIV END "entries_by_promo_id_button" -->
			</td>
            <?php
			}elseif($promo_row->promo_type==2){
			?>
              <td>
				<div id="entries_by_promo_id_button" class="button">
				<form method="post">
                    <input type="hidden" name="fb_edit_action" value="view_media_entry">
                    <input type="hidden" name="page" value="fbm_edit_promo">
                    <input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
                    <input name="submit" value="Media Entries" type="submit">
				</form>
				</div><!-- DIV END "entries_by_promo_id_button" -->
			</td>
            <?php
			}else{
			?>
            <td>&nbsp;</td>
            <?php
			}?>
			<?php  //march2 updates

			if( $notifications_obj != null && $notifications_obj->get_enable_module_notification_value() ) 	{

				?>

			  <td>
				<!--<div id="notifications_by_promo_id_button" class="button">
				<form method="post">
				<input type="hidden" name="fb_notification" value="notify_users">
				<input type="hidden" name="page" value="fbm_promo_notification">
				<input type="hidden" name="promo_id" value="<?php echo($promo_row->promo_id)?>">
				<input name="submit" value="Notifications" type="submit">
				</form>
				</div> -->
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


