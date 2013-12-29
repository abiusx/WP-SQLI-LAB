<?php
global $promo_image_url,$promo_entries,$promo_row,$fbmo;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>
<div class="wrap">
<div id="form1">
<h1><em>Bugs Go Viral: Entrants</em></h1>
<hr width="100%" size="1" noshade>
<img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" >
    <?php
    echo "<table width='50%'>
	<tr>
	<th align='left'>Total Likes</th>
	<th align='left'>Total Participants</th>
	<th align='left'>Top Influencers</th>
	</tr>
	<tr>
		<td valign='top'>".$res->likes."</td>
		<td valign='top'>".$res->participants."</td>
		<td>"?>
        <?php
		   foreach ($res_top_inf as $top_inf_row)	{
			   echo $top_inf_row->name."<br>";
		   }
	   ?>
      <?php
		echo "</td>
	</tr>
	</table>";
	?>
<table class="tbl">
      <tr>
        <td align="left" class="pagetitle">Entrants for <?php echo($promo_row["promo_name"])?></td>
      </tr>
<tr><td>
<table class="overview">
      <tr> 
            <td colspan="12" align="right">
            	<form method="post">
				<input type="hidden" name="fb_edit_action" value="enter_winners">
				<input type="hidden" name="page" value="fbm_edit_promo">
				<input type="hidden" name="promo_id" value="<?php echo($_REQUEST["promo_id"])?>">
                  Sort By:
                  <select name="sort_order" id="menu" onChange="form.submit();">
                   <option value="points DESC" <?php if($_REQUEST["sort_order"]=="points DESC") echo("SELECTED");?>>Influence</option>
			        <option value="name" <?php if($_REQUEST["sort_order"]=="name") echo("SELECTED");?>>Name</option>
                    <option value="entry_date" <?php if($_REQUEST["sort_order"]=="entry_date") echo("SELECTED");?>>Entry Date</option>
                    <option value="location" <?php if($_REQUEST["sort_order"]=="location") echo("SELECTED");?>>Location</option>
                  </select>
                  <input type="submit" name="go" value="Go" />
              </form>

        </td>
              </tr>

        <form id="promoform" name="promoform" method="post">
        <input type="hidden" name="fb_edit_action" value="save_entries">
        <input type="hidden" name="page" value="fbm_edit_promo">
        <input type="hidden" name="promo_id" value="<?php echo($promo_row["promo_id"])?>">
          <tr>
            <td width="190" class="title">Name</td>
            <td class="title">Location</td>
            <!--<td class="title">Referrals</td>
            <td class="title">Shares</td>
            <td class="title">Invites</td>-->
            <td class="title">Points</td>
             <?php
			if($promo_row["promo_type"]==2){
			?>
            <td class="title">Total Votes</td>
            <?php
			}
			?>
			<td class="title">Friends Count</td>
            <?php
			if($promo_row["promo_type"]==8){
			?>
            <td class="title">Friends Name fo Gift Card</td>
            <td class="title">Friends Email fo Gift Card</td>
            <td class="title">Units Purcahsed</td>
            <td class="title">Redeemed</td>
            <?php
			}
			?>
          </tr>
<?php if(count($promo_entries)==0): ?>
      <tr>
        <td colspan="13">There are no entrants yet.</td>
      </tr>
<?php endif; ?>
<?php
foreach ($promo_entries as $entry_row)	{
	$total_points = $fbmo->calculate_entry_points($entry_row->uid,$entry_row->media_id,$promo_row["promo_id"],$promo_row["promo_type"]);
	if( ($promo_row["promo_type"]==2 ) || ($promo_row["promo_type"]==1) || ($promo_row["promo_type"]==8)){
		if(!empty($entry_row->name) ){
		if($promo_row['response_type']=="LIKE"){
			$total_votes = $entry_row->like_counter;
		}else{
			$total_votes = $entry_row->rate_counter;
		}
		
		if(empty($entry_row->location)){
		$location = $fbmo->get_user_details($entry_row->uid);
		}else
		$location = $entry_row->location;
		?>
          <tr>
            <td width="190" class="promotitle">
               <a href = "http://www.facebook.com/#!/profile.php?id=<?php echo $entry_row->uid;?>" target="_blank"><?php echo $entry_row->name;?></a>
            	<?php echo("<br />".$entry_row->entry_date); ?>
                <br />
                <?php if(!empty($entry_row->email)){ echo "(".($entry_row->email).")";}?>
            </td>
            <td class="data"><?php echo($location);?></td>
       
            <td class="data"><?php echo ($total_points);?></td>
              <?php
			if($promo_row["promo_type"]==2){
			?>
            <td class="data"><?php echo ($total_votes);?></td>
            <?php
			}
			?>
             <td class="data"><?php echo($entry_row->friends_count);?></td>
             <?php
			if($promo_row["promo_type"]==8){
			?>
             <td class="data"><?php echo($entry_row->gift_card_friends_name);?></td>
             <td class="data"><?php echo($entry_row->gift_card_friends_email);?></td>
             <td class="data"><?php echo($entry_row->	promo_units_purchased 	);?></td>
             <td class="data"><input type="hidden" name="ids[]" value="<?php echo($entry_row->entry_id);?>" />
             <?php
			 if($entry_row->promo_units_purchased 	>0){ 
			 ?>
             <input type="text" name="reedemed_units[]" value="<?php echo($entry_row->reedeemed_units)?>" style="width:40px;"/>
             <?php
			 }else{
				 echo "-";
			 }
				?>
                 
             </td>
       
          <?php
			}
			?>
          </tr>
<?php }} }?>
      <tr>
      <td colspan="13">
      <p class="submit_button">
        <input name="submit" value="Update" type="submit" />
        </p>
      </td>
      </tr>
      </form>
     <!--<tr>
        <td colspan="13"  class="pagetitle">Enter the winner/s of your promotion below &raquo;</td>
       </tr>-->
        <!--<tr><td colspan="13">
        <form id="promoform" name="promoform" method="post">
        <input type="hidden" name="fb_edit_action" value="save_winners">
        <input type="hidden" name="page" value="fbm_edit_promo">
        <input type="hidden" name="promo_id" value="<?php // echo($promo_row["promo_id"])?>">
        <textarea name="winner_text" cols="50" rows="5" wrap="VIRTUAL"><?php //echo($fbmo->promo_row["winner_text"]) ?></textarea>
        <p class="submit_button">
        <input name="submit" value="Submit" type="submit" />
        </p>
        </form>
        </td></tr>-->
        </table></td></tr>
        </table>

</div>
</div>
