<?php
global $promo_image_url,$promo_entries,$promo_row,$fbmo;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>
<div class="wrap">
<div id="form1">
<h1><em>Bugs Go Viral: Media Entries</em></h1>
<hr width="100%" size="1" noshade>
<img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" >
   <?php
   if(!empty($_REQUEST['media_id'])){
		$media_item_selected=$fbmo->get_promo_media_selected($promo_row['promo_id'],$_REQUEST['media_id']);
		if($promo_row["media_type"]=="PHOTO UPLOAD"){
			$media =   $media_item_selected["media_url"];
			$media =   (' <img src="'.
						get_option('siteurl'). '/facebookapp/uploads/contest_images/'.$media.'" border=0 class="userimage">');
		}
		 elseif($promo_row["media_type"]=="YOUTUBE VIDEO LINK"){
			$media = $media_item_selected["media_url"];
			$media =$fbmo->get_youtube_iframe($media);
		 }
		 elseif($promo_row["media_type"]=="TEXT ENTRY")
			$media = $media_item_selected["media_text"];

	 ?>
		<table class="tbl">
      <tr>
        <td align="left" class="pagetitle">View Media Entry Info  for <?php echo($media_item_selected["media_title"])?></td>
      </tr>
        <tr><td>
        <table class="overview">
            <tr><td>Media Entry:</td>
            <td>
					<?php echo  $media;?>
            </td></tr>
				
			
			<tr><td>
					<p>Total Likes: </p></td>
					<td align="left"><?php echo  $media_item_selected["like_counter"];?></td>
             </td>
             </tr>
            
                <tr>
                     <td>
                            <p class="boldtitle">Entry Title:</p>
                       </td>
                       <td align="left"><?php echo($media_item_selected["media_title"]);
                             ?>
                       </td>
               </tr>
               <tr>
                     <td>
                            <p class="boldtitle">Entry Author:</p>
                       </td>
                       <td align="left"><?php echo($media_item_selected["uid_name"]);
                             ?>
                       </td>
               </tr>
					  
					</table></td></tr></table>
            <?php
   }else{
   ?>
<table class="tbl">
      <tr>
        <td align="left" class="pagetitle">View Media Entries for <?php echo($promo_row["promo_name"])?></td>
      </tr>
<tr><td>
<table class="overview">
      

  <tr>
    <td width="190" class="title">Media Title</td>
    <td class="title">User Name</td>
    <td class="title">User Email</td>
    <td class="title">Entry Date</td>
    <td class="title">Action</td>
    </tr>
  <?php if(count($media_items)==0): ?>
  <tr>
    <td colspan="5">There are no media entries yet.</td>
    </tr>
  <?php endif; ?>
  <?php
foreach ($media_items as $media_row)	{
   $class = "promotitle";
?>
  <tr>
      <td width="190" class="<?php echo $class?>">
	  <a href="admin.php?fb_edit_action=view_media_entry&page=fbm_edit_promo&promo_id=<?php echo $media_row['promo_id']?>&media_id=<?php echo($media_row['media_id'])?>" target="_blank"><?php echo($media_row['media_title'])?></a>
      </td>
     <td class="<?php echo $class?>"><?php echo($media_row['uid_name'])?></td>
     <td class="<?php echo $class?>"><?php echo($media_row['user_email'])?></td>
    <td class="<?php echo $class?>"><?php echo date("Y-m-d",strtotime($media_row['date_entered']));?></td>
    <td>
                 <form method="post" onsubmit="return frmVal();">
                    <input type="hidden" name="fb_edit_action" value="delete_media_entry">
                    <input type="hidden" name="page" value="fbm_edit_promo">
                    <input type="hidden" name="promo_id" value="<?php echo($media_row['promo_id'])?>">
                    <input type="hidden" name="media_id" value="<?php echo($media_row['media_id'])?>">
                    <input name="submit" value="Delete" type="submit">
				</form>
        </td>
    </tr>
  <?php  }?>
</table></td></tr>
<script>
function valEmailFrm(){
	if(document.send_email.from_email.value==""){
		alert("Please enter From Email.");
		return false;
   }
	if(document.send_email.subject.value==""){
		alert("Please enter Subject.");
		return false;
    }
	if(document.send_email.message.value==""){
		alert("Please enter some message.");
		return false;
    }
	alert('Email will be sent to all entrants!');return true;
}
</script>
<tr>
<td>
<form method="post" onsubmit="return valEmailFrm();" name="send_email">
<input type="hidden" name="fb_edit_action" value="send_email_media_entry">
  <input type="hidden" name="page" value="fbm_edit_promo">
 <input type="hidden" name="promo_id" value="<?php echo($media_row['promo_id'])?>">
<table class="overview">      
      <tr>
        <td class="title">From:</td>
        <td align="left"><input type="text" name="from_email" value="" /></td>
        </tr>  
		<tr>
        <td class="title">Subject:</td>
        <td align="left"><input type="text" name="subject" value="" /></td>
        </tr>
        <tr>
        <td class="title">Message:</td>
        <td align="left"><textarea name="message"/></textarea></td>
        </tr>
        <tr>
        <td colspan="2"><input name="submit" value="Send Email" type="submit"></td>
        </tr>
        </table>
        
</form>
</td>
</tr>
</table>
<?php
   }
   ?>
</div>
</div>
<script>
function frmVal(){
	if(confirm('Are you sure to delete entry?'))
	return true; 
	else 
	return false;
}
</script>