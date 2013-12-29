<?php
/**
*	BugsGoViral Plugin for Worpress
*	Admin Dashboard View
*
*	@filesource 			admin_b_hive.php
*	@description			View for B(gv) Hive - iframe to BugsGoViral.net page
* */
include("version.php");

/**
* Start display code
*/
global $promo_image_url,$promo_entries,$promo_row;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>
<div id="admin_b_hive_container" class="wrap">
<?php
      if(!get_option("key")){
	   ?>
		<div class='update-nag'>Account setup is mandatory before configuring any promo.</div>
      <?php
	  }elseif(empty($fbmo->options["fb_application_secret"])){
        ?>
        <div class='update-nag'>Facebook Settings needs to be updated.</div>
        <?php
        }
        ?>
	<?php do_html_admin_masthead("admin_b_hive","masthead", "The BGV Hive","Welcome",0,3) ?>

    <table cellspacing="10" class="data_table" id="data_table_active_promotions">
		<tr>
			<th>Total Participants</th>
			<th>Total Likes</th>
            <th>Total Prize Value</th>

		</tr>
<?php if(count($data)==0): ?>
      <tr>
        <td colspan="6">There are no entrants yet.</td>
      </tr>
<?php endif; ?>
          <tr>
            
            <td class="data"><?php echo($data[0]);?></td>
            <td class="data"><?php echo($data[1]);?></td>
            <td class="data"><?php echo($data[2]);?></td>
          </tr>
   </table>

	<?php do_html_admin_footer("admin_b_hive", "The B(gv) Hive") ?>


</div><!-- DIV END "admin_dashboard_container" -->
