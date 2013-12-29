<?php
global $promo_image_url,$stats_obj,$promo_row;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>

<div class="wrap">
<div id="form1">
    <h1><em>Bugs Go Viral: Details for <?php echo($promo_row["promo_name"])?></em></h1>
    <hr width="100%" size="1" noshade>
<img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" height="125">

<table cellspacing="10"  class="tbl">
      <tr>
        <td colspan="7" class="pagetitle">Totals for <?php echo($promo_row["promo_name"])?></td>
      </tr>
<tr>
  <td>
<table cellspacing="10" class="overview">
      <tr>
        <td class="title">Participants</td>
        <td class="title">Likes</td>
        <td class="title">Shares</td>
        <td class="title">Invites</td>
        <td class="title">Referrals</td>
        <td class="title">Points</td>
        <td class="title">Highest Score</td>
      </tr>
      <tr>
        <td class="data"><?php echo($stats_obj->total_participants); ?></td>
        <td class="data"><?php echo($stats_obj->total_likes); ?></td>
        <td class="data"><?php echo($stats_obj->total_shares); ?></td>
        <td class="data"><?php echo($stats_obj->total_invites); ?></td>
        <td class="data"><?php echo($stats_obj->total_clicks); ?></td>
        <td class="data"><?php echo($stats_obj->total_points); ?></td>
        <td class="data"><?php echo($stats_obj->highest_score); ?></td>
      </tr>
</table>
</td></tr>
<tr><td>
<table class="overview">
<tr>
<?php
	$media_items=$fbmo->get_promo_media($_REQUEST["promo_id"],"vote_counter desc");
	if(count($media_items)==0)	echo("<td>There are no uploaded media items for this promo</td>");
	else echo('<td colspan=3 class="promotitle">Media Entries:</td></tr><tr>');
	$col_cnt=1;
		foreach ($media_items as $media_row)	{
			echo('<td>');
			if($promo_row["media_type"]=="photo")	{
				echo ('<img src="'.
				get_option('siteurl'). '/facebookapp/uploads/contest_images/'.
				$promo_row["promo_page"].'/'.$media_row["media_url"].
				'" border=0 width=50><br />');
			}
			echo($media_row["media_title"].'<br />');
			echo("Votes: ".$media_row["vote_counter"].'<br />');
			echo("Entrant: ".$media_row["uid_name"].'<br />');
		?>
<form id="promoform" name="promoform" method="post">
<input type="hidden" name="fb_edit_action" value="delete_media">
<input type="hidden" name="page" value="fbm_promo_detail">
<input type="hidden" name="promo_id" value="<?php echo($_REQUEST["promo_id"])?>">
<input type="hidden" name="media_id" value="<?php echo($media_row["media_id"])?>">
<input name="submit" value="Delete" type="submit" />
</form></td>
	<?php
			if($col_cnt==3)	{				echo('</tr><tr>');
				$col_cnt=1;
			} else 	$col_cnt++;
		}
		if ($col_cnt==3) echo('<td></td>');
		if ($col_cnt==2) echo('<td colspan=2></td>');
		if ($col_cnt==1) echo('<td colspan=3></td>');
?>
</tr></table>
</td>
</tr>
</table>


</div>
</div>
