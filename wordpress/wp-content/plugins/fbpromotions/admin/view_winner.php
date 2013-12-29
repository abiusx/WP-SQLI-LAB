<?php
global $promo_image_url,$promo_entries,$promo_row,$fbmo;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>
<div class="wrap">
<div id="form1">
<h1><em>Bugs Go Viral: Entrants</em></h1>
<hr width="100%" size="1" noshade>
<img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" >
   
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
    <td class="title">Total Ratings</td>
    <td class="title">Av.Rating</td>
    <td class="title">Likes</td>
     <td class="title">Media Title</td>
    <td class="title">Friends Count</td>
    </tr>
  <?php if(count($promo_entries)==0): ?>
  <tr>
    <td colspan="5">There are no entrants yet.</td>
    </tr>
  <?php endif; ?>
  <?php
  if($promo_row['response_type']=="LIKE"){
		$winner_txt = "like_counter";
	}else
		$winner_txt = "rate_counter";
	    $winner = $fbmo->get_winner_buzz($winner_txt,$promo_row["promo_id"]);
		//print_r($winner);
foreach ($promo_entries as $entry_row)	{
	$referals = $fbmo->get_promo_referals( $entry_row->promo_id,  $entry_row->uid);
	$l = $fbmo->getMediaEntryLikes($entry_row->entry_id,$entry_row->promo_id);
   if($entry_row->entry_id == $winner['entry_id']){
	  	$rate = $fbmo->average_rating($entry_row->promo_id,$winner['media_id'] );
 
   $class = "promotitle";
  
?>
  <tr>
    <td width="190" class="<?php echo $class?>">
      <a href = "http://www.facebook.com/#!/profile.php?id=<?php echo $entry_row->uid;?>" target="_blank"><?php echo $entry_row->name;?></a>
      <?php echo("<br />".$entry_row->entry_date); ?>
      </td>
     <td class="<?php echo $class?>"><?php echo($winner['rate_counter']);?></td>
    <td class="<?php echo $class?>"><?php echo $rate ;?></td>
    <td class="<?php echo $class?>"><?php echo($winner['like_counter']);?></td>
      <td class="<?php echo $class?>"><?php echo($winner['media_title']);?></td>
    <td class="<?php echo $class?>"><?php echo($entry_row->friends_count);?></td>
    <tr><td colspan="7" align="left">
      <?php echo($entry_row->email);?>
      </td></tr>
  <?php } }?>
</form>
</table></td></tr>
</table>

</div>
</div>
