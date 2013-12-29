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
	if($_POST['submit']!="Generate Winner" && empty($promo_row["winner_text"])){
	?>
<table class="tbl">
          <tr>
            <td align="left" class="">To randomly select a winner...click the button below:</td>
          </tr>
        <tr><td>
            <table class="overview">
                 <form id="promoform" name="promoform" method="post">
                    <input type="hidden" name="fb_edit_action" value="generate_winner">
                    <input type="hidden" name="page" value="fbm_edit_promo">
                    <input type="hidden" name="promo_id" value="<?php echo($promo_row["promo_id"])?>">
                    <p class="submit_button">
                    <input name="submit" value="Generate Winner" type="submit" />
                    </p>
                </form>
            </table>
        </td></tr>
</table>
<?php
}elseif(($_POST['submit']=="Generate Winner" )|| (! empty($promo_row["winner_text"]))) {
?>
<table class="tbl">
      <tr>
        <td align="left" class="pagetitle">Winner for <?php echo($promo_row["promo_name"])?></td>
      </tr>
<tr><td>
<table class="overview">
      <form id="promoform" name="promoform" method="post">
        <input type="hidden" name="fb_edit_action" value="save_entries">
        <input type="hidden" name="page" value="fbm_edit_promo">
        <input type="hidden" name="promo_id" value="<?php echo($promo_row["promo_id"])?>">
        <tr>
          <td width="190" class="title">Name</td>
          <td class="title">Location</td>
          <td class="title">Points</td>
          <td class="title">Friends Count</td>
          </tr>
  <?php if(count($promo_entries)==0): ?>
        <tr>
          <td colspan="13">There are no entrants yet.</td>
          </tr>
  <?php endif; ?>
  <?php
foreach ($promo_entries as $entry_row)	{
	$total_points = $fbmo->calculate_entry_points($entry_row->uid,$entry_row->media_id,$promo_row["promo_id"],$promo_row["promo_type"]);
	if( ($promo_row["promo_type"]==2 && (!empty($entry_row->media_id))) || ($promo_row["promo_type"]==1) || ($promo_row["promo_type"]==8)){
		if(!empty($entry_row->name) && $entry_row->name==$promo_row['winner_text'] && $name_dis!=$entry_row->name ){
		if(empty($entry_row->location)){
		$location = $fbmo->get_user_details($entry_row->uid);
		}else
		$location = $entry_row->location;
		?>
        <tr>
          <td width="190" class="promotitle">
            <a href = "http://www.facebook.com/#!/profile.php?id=<?php echo $entry_row->uid;?>" target="_blank"><?php echo $entry_row->name;?></a>
            <?php echo("<br />".$entry_row->entry_date); ?>
            </td>
          <td class="data"><?php echo($location);?></td>
          
          <td class="data"><?php echo ($total_points);?></td>
         
          <td class="data"><?php echo($entry_row->friends_count);?></td>
          
            </tr>
  <?php
  $name_dis = $entry_row->name;
   }} 
  }?>
        <tr>
                </form>

      </table></td></tr>
        </table>
<?php
}
?>
</div>
</div>
