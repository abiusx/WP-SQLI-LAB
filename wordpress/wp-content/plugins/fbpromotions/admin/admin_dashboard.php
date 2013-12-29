<?php
/**
*	BugsGoViral Plugin for Worpress
*	Admin Dashboard View
*
*	@filesource 			admin_dashboard.php
*	@description			Default dashboard view for Bugs Go Viral Plugin
*	@todo 					Style now that tables changed to divs - use style_promo_list.css
*	@todo 					Need to strip/trim input data on textarea and validate input for security
*
* */
include("version.php");

/**
* Start display code
*/

?>
<form name="regfrm" action="<?php echo get_option("serverurl")?>/index.php?fb_edit_action=manage_account&product_id=<?php  echo get_option("key")?>" target="_blank" method="post" onsubmit="return validate();">
<input type="hidden" name="act" value="<?php echo $_POST["act"]?>" />
 <input type="hidden"  name="user_name" value="<?php echo $_POST["user_name"]?>">
<input type="hidden"  name="user_password" value="<?php echo $_POST["user_password"]?>">
  <input name="bus_name" type="hidden" value="<?php echo $_POST["bus_name"]?>">
  <input name="name" type="hidden" value="<?php echo $_POST["name"]?>">
  <input name="last_name" type="hidden" value="<?php echo $_POST["last_name"]?>">
  <input name="add" type="hidden" style="width:240px;" value="<?php echo $_POST["add"]?>">
  <input name="country" type="hidden" value="<?php echo $_POST["country"]?>">
  <input name="state" type="hidden" value="<?php echo $_POST["state"]?>">
  <input name="city" type="hidden" value="<?php echo $_POST["city"]?>">
  <input name="zip" type="hidden" value="<?php echo $_POST["zip"]?>">
  <input name="phone" type="hidden" value="<?php echo $_POST["phone"]?>">
  <input name="email" type="hidden" value="<?php echo $_POST["email"]?>">
  <input name="website" type="hidden" value="<?php echo $_POST["website"]?>">
  <input name="agency" type="hidden"   value="<?php echo $_POST["agency"]?>">
  <input name="agency_type"  type="hidden"  value="<?php echo $_POST["agency_type"]?>">
</form>
<?php
	$serverurl =  get_option("serverurl");
	$key = trim(get_option("key"));
    $data_likes= file_get_contents($serverurl."index.php?product_id=".$key."&fb_edit_action=returnLikes");
	$current_version= file_get_contents($serverurl."index.php?product_id=".$key."&fb_edit_action=get_current_version");
	$stored_version = get_option("fbpromo_version");
?>
<div id="admin_dashboard_container" class="wrap">
		<?php
	if($stored_version<$current_version){
	?>
    <div class='update-nag'>Updates version <?php echo $current_version;?> available for plugin </div>
    <?php
	}
      if(!get_option("key")){
	   ?>
		<div class='update-nag'>Account setup is mandatory before configuring any promo.</div>
      <?php
	  }elseif(empty($fbmo->options["fb_application_secret"])){
        ?>
        <div class='update-nag'>Facebook Settings needs to be updated.</div>
        <?php
        }
		if($_POST['act']){
			 if($_POST['agency']==1){
				 $page = 4;
			 }else{
				 $page = 5;
			 }
		}else{
			$page = 6;
		}
        ?>
	<?php do_html_admin_masthead("admin_dastboard","masthead","Admin Dashboard","Admin Heading",0,$page);
	 ?>
    <?php
	$arr_likes = explode("##",$data_likes);
    if($arr_likes[0]>0)
    $likes = $arr_likes[0];
    else
    $likes = $res->likes;
	$installs = $arr_likes[1];
    echo "<table width='50%'>
	<tr>
	<th align='left'>Total Likes</th>
	<th align='left'>Total Participants</th>
	<th align='left'>Top Influencers</th>
	<th align='left'>Total Installs</th>
	</tr>
	<tr>
		<td valign='top'>".$likes."</td>
		<td valign='top'>".$res->participants."</td>
		<td>"?>
        <?php
		   foreach ($res_top_inf as $top_inf_row)	{
			   echo $top_inf_row->name."<br>";
		   }
	   ?>
      <?php
		echo "</td>
		<td valign='top'>".$installs."</td>
	</tr>
	</table>";
	?>
	<?php do_html_admin_dashboard_main("admin_dashboard","content","","",$val=1) ?>

</div><!-- DIV END "admin_dashboard_container" -->

<?php do_html_admin_footer("admin_dashboard","admin_footer") ?>


