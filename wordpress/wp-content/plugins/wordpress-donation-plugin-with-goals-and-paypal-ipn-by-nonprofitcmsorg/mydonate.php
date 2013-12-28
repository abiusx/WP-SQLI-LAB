<?php 
#     /* 
#     Plugin Name: WordPress Donation Plugin with Goals and Paypal IPN by NonprofitCMS.org
#     Plugin URI: http://www.nonprofitcms.org/2010/12/wordpress-donate-plugin/
#     Description: The NonprofitCMS WordPress Donate Plugin allows you to create unlimited donation campaigns with goals, capture donor information and gives a donation thermometer for each campaign!  Visit plugin website for premium version which removes credits link and provides ability to export to CSV.
#     Author: NonprofitCMS
#     Version: 1.0 
#     Author URI: http://www.nonprofitcms.org
#     */  

if( $_GET['page'] == 'ViewAllHandle' && ( $_GET['doaction'] || $_GET['delete'] ) )
{
	global $wpdb;
	$tb = $wpdb->prefix.'campaigns';
	
	if( $_GET['action'] == 'delete' || $_GET['delete']) {
	
		if( $_GET['action'] ) { $dIDs = $wpdb->escape($_GET['donor']); }
		
		$mngpg = get_option('siteurl').'/wp-admin/admin.php?page=ViewAllHandle';
		
		
		if( $_GET['delete'] ) { $dIDs[] = $wpdb->escape($_GET['delete']); }
		
		foreach( $dIDs as $dID ) {
			$del = "DELETE FROM $tb WHERE camp_id = $dID LIMIT 1";
			//echo $del; //exit;
			$wpdb->query($del);
			$msg = 2;
		} // End of foreach loop

		header("Location : $mngpg&msg=2");
	}
	
}


$donate_version = "1.0";

include_once('dpay-widget.php');

if( !class_exists('MyWpDonate') )
{
	class MyWpDonate{
	
		function MyWpDonate() { //constructor
		
			//ACTIONS
				//Add Menu in Left Side bar
				add_action( 'admin_menu', array($this, 'my_plugin_menu') );
				
//				add_action( 'admin_head', array($this, 'icon_css') );

				#Update Settings on Save
				if( $_POST['action'] == 'camp_add' )
					add_action( 'init', array($this,'SaveNewCampaign') );
					
				
				if( $_POST['action'] == 'camp_edit' )
					add_action( 'init', array($this,'SaveEditCampaign') );
				
				
				# Update General Settings
				if( $_POST['action'] == 'pay_update' )
					add_action( 'init', array($this,'saveGeneralSettings') );


				#Save Default Settings
					//add_action( 'init', array($this, 'DefaultSettings') );
				#Uninstall Donate Plus
				if( $_POST['action'] == 'dplus_delete' )
					add_action( 'init', array($this,'UninstallDP') );

			//SHORTCODES
				#Add Form Shortcode
				add_shortcode('wpdonatebuy', array($this, 'MyDonatePage') );
				
				add_shortcode('wpdonategoal', array($this, 'showdonategoal') );				
				 
				add_shortcode('wpdonatecollected', array($this, 'donationcollection') );
				
				add_shortcode('wpdonatemeter', array($this, 'donatemeter') );				

				add_shortcode('confirmdonation', array($this, 'confirmdonation') );				
				
				add_shortcode('wpdonatorlist', array($this, 'donatorlist') );				

				add_action( 'wp_head', array($this, 'email_js') );

			//INSTALL TABLE
				#Runs the database installation for the wp_donations table
				register_activation_hook( __FILE__, array($this, 'MyDonateInstall') );
				

		}
		
		function my_plugin_menu() {

			global $objDonate;
		
			add_menu_page('Donation Goals', 'Donation Goals', 'manage_options', 'MyDonate', array($objDonate, 'ViewAllCampaign'), 'div' );
			add_submenu_page( 'MyDonate', 'Donation Goals | View All Campaigns', 'View All Campaigns', 'manage_options', 'ViewAllHandle', array($objDonate, 'ViewAllCampaign'));
			add_submenu_page( 'MyDonate', 'Donation Goals | Add Campaign', 'Add Campaign', 'manage_options', 'AddCampaignHandle', array($objDonate, 'AddCampaign'));
			add_submenu_page( 'MyDonate', 'Donation Goals | General Settings', 'General Settings', 'manage_options', 'GeneralSettings', array($objDonate, 'GeneralSttings'));		
		
		//add_menu_page('Page title', 'Top-level menu title', 'manage_options', 'my-top-level-handle', 'my_magic_function');
		//add_submenu_page( 'my-top-level-handle', 'Page title', 'Sub-menu title', 'manage_options', 'my-submenu-handle', 'my_magic_function');
		
		}



		function email_js() {
		
			echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/donation-goals-paypal-ipn-nonprofitcms-free/mydonate.css" />';
		}




// View All Campaign


function ViewAllCampaign()
{
		
		    if (!current_user_can('manage_options'))  {
			   wp_die( __('You do not have sufficient permissions to access this page.') );
		    }

			
			if($_GET["dID"] != "" ) {
				
				$this->ViewDonation();
			
			} else {
			
			global $wpdb;
			
			$table_name = $wpdb->prefix.'campaigns';
			$mngpg = get_option('siteurl').'/wp-admin/admin.php?page=ViewAllHandle';
			$editpg=get_option('siteurl').'/wp-admin/admin.php?page=AddCampaignHandle';
			
			$campaigns = $wpdb->get_results("SELECT * FROM $table_name ORDER BY camp_id DESC");

			if(  $_GET['s'] ):
				$s = $wpdb->escape($_GET['s']);
				$sq = "SELECT * FROM $table_name WHERE camp_name LIKE '%$s%' ORDER BY camp_id DESC";
				$campaigns = $wpdb->get_results($sq);
			endif;
			
			$don_table_name = $wpdb->prefix.'donation';
			
			?>
            <div class="wrap">
            	<h2><?php _e('View All Campaigns');?></h2>
<?php 
	if( $_REQUEST['delete'] != "" || $_REQUEST["action"]=="delete" ) {
		echo '<div id="message" class="updated fade"><p><strong>Campaign successfully deleted.</strong></p></div><br>';
	}
 ?>

<form id="donate-filter" action="<?php echo $mngpg;?>" method="get">
                
<input type="hidden" name="page" value="ViewAllHandle" />

<p class="search-box">
	<label class="screen-reader-text" for="page-search-input"><?php _e('Search Campaign');?></label>
	<input type="text" id="donate-search-input" name="s" value="<?php echo $_GET["s"]; ?>" />
	<input type="submit" value="<?php _e('Search Campaign');?>" class="button" />

</p>

<div class="tablenav">

<div class="alignleft actions">
<select name="action">
<option value="-1" selected="selected"><?php _e('Bulk Actions');?></option>
<option value="delete"><?php _e('Delete');?></option>
</select>
<input type="submit" value="<?php _e('Apply');?>" name="doaction" id="doaction" class="button-secondary action" />
</div>

<br class="clear" />
</div>

<div class="clear"></div>

<table class="widefat page fixed" cellspacing="0">
  <thead>
  <tr>
	<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col" id="campid" class="manage-column column-donorname" style=""><?php _e('Campaign ID');?></th>
	<th scope="col" id="campname" class="manage-column column-amount" style=""><?php _e('Campaign name');?></th>
    <th scope="col" id="campdate" class="manage-column column-comment" style=""><?php _e('Date');?></th>
	<th scope="col" id="campgoal" class="manage-column column-date" style=""><?php _e('Goal Amount');?></th>
	<th scope="col" id="campamt" class="manage-column column-date" style=""><?php _e('Amount Received');?></th>
	<th scope="col" id="campact" class="manage-column column-date" style=""><?php _e('Action');?></th>        
    
  </tr>
  </thead>

  <tfoot>
  <tr>

	<th scope="col" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col" class="manage-column column-donorname" style=""><?php _e('Campaign ID');?></th>
	<th scope="col" class="manage-column column-amount" style=""><?php _e('Campaign name');?></th>
    <th scope="col" class="manage-column column-comment" style=""><?php _e('Date');?></th>
	<th scope="col" class="manage-column column-date" style=""><?php _e('Goal Amount');?></th>
	<th scope="col" class="manage-column column-date" style=""><?php _e('Amount Received');?></th>
	<th scope="col" class="manage-column column-date" style=""><?php _e('Action');?></th>        
  </tr>
  </tfoot>

  <tbody>
  <?php
  	foreach( $campaigns as $campaign ):
	if( $alt ) $alt = false; else $alt = 'alternate';
	
		$donate_rw = $wpdb->get_results("SELECT sum(don_amt) as totalreceive FROM $don_table_name WHERE don_camp_id=".$campaign->camp_id);
		//print_r($donate_rw);
		?>
        <tr class="<?php echo $alt;?> iedit">
        	<th scope="row" class="check-column"><input type="checkbox" name="donor[]" value="<?php echo $campaign->camp_id;?>" /></th>
            
            <td class="donorname">
            
            <strong><a class="row-title" href="<?php echo $mngpg.'&amp;dID='.$campaign->camp_id;?>" title="<?php _e('View Detail'); echo $campaign->camp_id;?>"><?php 
			$c_id='';
			if(strlen($campaign->camp_id) ==1){
				$c_id="C00".$campaign->camp_id;
			} else if(strlen($campaign->camp_id)==2){
				$c_id="C0".$campaign->camp_id;
			} else {
				$c_id="C".$campaign->camp_id;
			}
			echo $c_id;
			?></a></strong>
            	<div class="row-actions">
                	<span class="edit"><a href="<?php echo $mngpg.'&amp;dID='.$campaign->camp_id;?>" title="<?php _e('View Detail');?>"><?php _e('View Detail');?></a>
                </div>
           </td>
	           <td class="amount"><?php echo $campaign->camp_name;?></td>
                <td class="amount"><?php echo $campaign->camp_create_date;?></td>
                <td class="comment"><?php echo "$".$campaign->camp_goal_amt;?></td>
                <td class="date"><?php if( $donate_rw[0]->totalreceive != "") { echo "$".$donate_rw[0]->totalreceive; } else { echo "$0"; };?></td>
                
                <td class="date">
                <span class="edit"><a href="<?php echo $editpg.'&amp;edit='.$campaign->camp_id;?>" title="<?php _e('Edit this Campaign');?>"><?php _e('Edit');?></a> | </span><span class="delete"><a class="submitdelete" title="<?php _e('Delete this Campaign');?>" href="<?php echo $mngpg.'&amp;delete='.$campaign->camp_id;?>"><?php _e('Delete');?></a> </span>
                
                </td>                
        </tr>
        
        <?php
	endforeach;
  ?>
  
  </tbody>
  </table>
  </form>
                
            </div>
            
            <?php
} //end of if

} // end of viewallcampagin function

// view donation fnction for display donations of a campaign

function ViewDonation()
{
			global $wpdb;
			
			$dId= $_GET["dID"];
			
			$table_name = $wpdb->prefix.'campaigns';
			
			$DtableName = $wpdb->prefix.'donation';
			
			$mngpg = get_option('siteurl').'/wp-admin/admin.php?page=ViewAllHandle&dID='.$dId;
			
			$editpg=get_option('siteurl').'/wp-admin/admin.php?page=AddCampaignHandle';
			
			$campaigns = $wpdb->get_results("SELECT * FROM $table_name WHERE camp_id=$dId");

			$donations = $wpdb->get_results("SELECT * FROM $DtableName WHERE don_camp_id=$dId");

			if(  $_GET['s'] ):
				$s = $wpdb->escape($_GET['s']);
				$sq = "SELECT * FROM $DtableName WHERE don_first_name LIKE '%$s%' OR don_last_name LIKE '%$s%' OR don_email LIKE '%$s%' ORDER BY don_id ASC";
				$donations = $wpdb->get_results($sq);
			endif;
	?>
    
             <div class="wrap">
            	<h2><?php echo "View all donation for campaign : ".$dId." - ".$campaigns[0]->camp_name;?></h2> 




<div style="width: 400px; float: right;">

<form id="donate-filter" action="<?php echo $mngpg;?>" method="get">
                
<input type="hidden" name="page" value="ViewAllHandle&amp;dID=<?php echo $dId; ?>" />

<p class="search-box" style="float: right;">
	<label class="screen-reader-text" for="page-search-input"><?php _e('Search Donation');?></label>
	<input type="text" id="donate-search-input" name="s" value="" />
	<input type="submit" value="<?php _e('Search Donation');?>" class="button" />

</p>

</form>
<div style="float: left;">&nbsp;</div>
<div style=" clear: both;"></div>
</div>

<div class="tablenav">

<!--<div class="alignleft actions">
<select name="action">
<option value="-1" selected="selected"><?php //_e('Bulk Actions');?></option>
<option value="delete"><?php //_e('Delete');?></option>
</select>
<input type="submit" value="<?php //_e('Apply');?>" name="doaction" id="doaction" class="button-secondary action" />
</div> -->

<br class="clear" />
</div>

<div class="clear"></div>

<table class="widefat page fixed" cellspacing="0">
  <thead>
  <tr>

	<th scope="col" id="donid" class="manage-column column-donorname" style=""><?php _e('Donation ID');?></th>
	<th scope="col" id="dondate" class="manage-column column-amount" style=""><?php _e('Date');?></th>
    <th scope="col" id="amtreceived" class="manage-column column-comment" style=""><?php _e('Amount Received');?></th>
	<th scope="col" id="donfirstname" class="manage-column column-date" style=""><?php _e('First Name');?></th>
	<th scope="col" id="donlastname" class="manage-column column-date" style=""><?php _e('Last Name');?></th>
	<th scope="col" id="donlastname" class="manage-column column-date" style=""><?php _e('Transaction Id');?></th>    
	<th scope="col" id="donemail" class="manage-column column-date" style=""><?php _e('Email');?></th>        
    
  </tr>
  </thead>

  <tfoot>
  <tr>
	<th scope="col" class="manage-column column-donorname" style=""><?php _e('Donation ID');?></th>
	<th scope="col" class="manage-column column-amount" style=""><?php _e('Date');?></th>
    <th scope="col" class="manage-column column-comment" style=""><?php _e('Amount Received');?></th>
	<th scope="col" class="manage-column column-date" style=""><?php _e('First Name');?></th>
	<th scope="col" class="manage-column column-date" style=""><?php _e('Last Name');?></th>
	<th scope="col" class="manage-column column-date" style=""><?php _e('Transaction Id');?></th>    
	<th scope="col" class="manage-column column-date" style=""><?php _e('Email');?></th>        
  </tr>
  </tfoot>

  <tbody>
  <?php
  	if( $wpdb->num_rows > 0 )  {
  	foreach( $donations as $donation ):
	if( $alt ) $alt = false; else $alt = 'alternate';
		?>
        <tr class="<?php echo $alt;?> iedit">
        
            <td >
			<?php 
			$d_id='';
			if(strlen($donation->don_id) ==1){
				$d_id="D00".$donation->don_id;
			} else if(strlen($donation->don_id)==2){
				$d_id="D0".$donation->don_id;
			} else {
				$d_id="D".$campaign->don_id;
			}
			echo $d_id;
			?>
			
			</td>
	        <td ><?php echo $donation->don_date;?></td>
            <td ><?php echo "$".$donation->don_amt;?></td>
            <td ><?php echo $donation->don_first_name;?></td>
            <td ><?php echo $donation->don_last_name;?></td>
            <td ><?php echo $donation->txn_id;?></td>            
                
            <td>
                
                <?php echo $donation->don_email;?>
            </td>                
        </tr>
        
        <?php
	endforeach;
  } else {
  
  ?>
<tr height="40px;">
<td colspan="6" style="padding:10 0 0 10;" >Donations not available</td>

</tr>  
<?php } ?>  
  </tbody>
  </table>
                
            </div>   
    
    <?php

} // end of view donation function

// Add Campaign form display
		
function AddCampaign()
{
			
	  if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	  }

	  if( $_GET["edit"] != "" ){
	  	$this->EditCampaign();
	  } else {
	  ?>
<div class="campaignName">
    <h3>Add Campaign</h3>
    
<?php 
	if( $_POST['notice'] ) {
		echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '</strong></p></div><br>';
	}
 ?>

    <form name="campform" id="campform" method="post" action="" >
      
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="35%">Campaign Name</td>
          <td width="65%"><input type="text" name="camp_name" id="camp_name" /></td>
        </tr>
        <tr>
          <td>Goal Amount (USD)</td>
          <td><input type="text" name="camp_goal_amt" id="camp_goal_amt" /></td>
        </tr>

        <tr>
          <td>Description</td>
          <td><textarea name="camp_descr" rows="3" cols="40"></textarea></td>
        </tr>
        
      </table>
  
      <br />
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="35%">Include these fields</td>
          <td width="65%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_title" id="camp_field_title" /> <label for="camp_field_title">Title</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_first_name" id="camp_field_first_name" />
          <label for="camp_field_first_name">First Name</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_last_name" id="camp_field_last_name" />
          <label for="camp_field_last_name">Last Name</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_country" id="camp_field_country" />
          <label for="camp_field_country">Country</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_address" id="camp_field_address" />
          <label for="camp_field_address">Address Lines</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_city" id="camp_field_city" />
          <label for="camp_field_city">City</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_state" id="camp_field_state" />
          <label for="camp_field_state">State</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_zip" id="camp_field_zip" />
          <label for="camp_field_zip">ZIP</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_phone" id="camp_field_phone" />
          <label for="camp_field_phone">Phone</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_email" id="camp_field_email" />
          <label for="camp_field_email">E-mail</label></td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_anonymous" id="camp_field_anonymous" />
          <label for="camp_field_anonymous">Would you like to remain anonymous?</label></td>
        </tr>        
        
        <tr height="20px;">
          <td colspan="2" >&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
          <input type="submit" class="button-primary" name="button" id="button" value="Create Campaign" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
       <input name="action" value="camp_add" type="hidden" />
       
      </form>
      
  </div>
<?php
} //end of if 

} //end of add campaign function

// Edit Campaign Function

function EditCampaign()
{

	global $wpdb;
	$table_name = $wpdb->prefix.'campaigns';
	
	$cID = $_GET['edit'];
	$CampRow = $wpdb->get_row("SELECT * FROM $table_name WHERE camp_id=$cID");	
?>

<div class="wrap">
    <h3>Edit Campaign</h3>
<form name="campform" id="campform" method="post" action="" >
   	<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field( 'update-options'); ?>

<?php 
	if( $_POST['notice'] ) {
		echo '<div id="message" class="updated fade"><p><strong>' . $_POST['notice'] . '</strong></p></div>';
	}
 ?>
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="35%">Campaign Name</td>
          <td width="65%"><input type="text" name="camp_name" value="<?php echo $CampRow->camp_name; ?>" id="camp_name" /></td>
        </tr>
        <tr>
          <td>Goal Amount (USD)</td>
          <td><input type="text" name="camp_goal_amt" id="camp_goal_amt" value="<?php echo $CampRow->camp_goal_amt; ?>" /></td>
        </tr>

        <tr>
          <td>Description</td>
          <td><textarea name="camp_descr" rows="3" cols="40"><?php echo $CampRow->camp_descr; ?></textarea></td>
        </tr>
        
      </table>
      <br />
      <br />
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="35%">Include these fields</td>
          <td width="65%">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox"  name="camp_field_title" <?php if($CampRow->camp_field_title == 1 ) { echo "checked=\"checked\""; } ?>  id="camp_field_title" /> <label for="camp_field_title">Title</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_first_name" id="camp_field_first_name" <?php if($CampRow->camp_field_first_name == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_first_name">First Name</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_last_name" id="camp_field_last_name" <?php if($CampRow->camp_field_last_name == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_last_name">Last Name</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_country" id="camp_field_country" <?php if($CampRow->camp_field_country == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_country">Country</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_address" id="camp_field_address" <?php if($CampRow->camp_field_address == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_address">Address Lines</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_city" id="camp_field_city" <?php if($CampRow->camp_field_city == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_city">City</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_state" id="camp_field_state" <?php if($CampRow->camp_field_state == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_state">State</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_zip" id="camp_field_zip" <?php if($CampRow->camp_field_zip == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_zip">ZIP</label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_phone" id="camp_field_phone" <?php if($CampRow->camp_field_phone == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_phone">Phone</label></td>
        </tr>


        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_email" id="camp_field_email" <?php if($CampRow->camp_field_email == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_email">E-mail</label></td>
        </tr>        

        <tr>
          <td>&nbsp;</td>
          <td><input type="checkbox" name="camp_field_anonymous" id="camp_field_anonymous" <?php if($CampRow->camp_field_anonymous == 1 ) { echo "checked=\"checked\""; } ?> />
          <label for="camp_field_anonymous">Would you like to remain anonymous?</label></td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>

        <tr height="20px;" >
          <td>&nbsp;</td>
          <td>
          <input type="submit" name="button" class="button-primary" id="button" value="Save Changes" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    	<input name="action" value="camp_edit" type="hidden" />
	    <input type="hidden" value="<?php echo $CampRow->camp_id;?>" name="cID" />
      </form>
      
  </div>

<?php
} // end of editCampaign Function
	

function GeneralSttings()
{

$emailId = get_option( 'paypal_email_id' );

$payMsg = get_option( 'paypal_email_msg' );

$confirmUrl  = get_option( 'wpconfirm_url' );

$fromAddress  = get_option( 'from_address' );

$emailSubject  = get_option( 'email_subject' );


?>
<div class="campaignName">
    <h3>General Settings</h3>
<?php if ( !empty($_POST ) ) { ?>
	<div id="message" class="updated fade"><p><strong><?php _e('Settings saved.') ?></strong></p></div><br />
<?php } ?>
    <form name="generalform" id="generalform" method="post" action="" >
      
      <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="35%">PayPal Account Email Address</td>
          <td width="65%"><input type="text" name="paypal_email_id" value="<?php _e($emailId); ?>" id="paypal_email_id" /></td>
        </tr>

        <tr>
          <td style="vertical-align: text-bottom;">Email From Address</td>
          <td><input type="text" name="from_address" value="<?php _e($fromAddress); ?>" id="from_address" /></td>
        </tr>

        <tr>
          <td style="vertical-align: text-bottom;">Email Subject</td>
          <td><input type="text" name="email_subject" value="<?php _e($emailSubject); ?>" id="email_subject" /></td>
        </tr> 

        <tr>
          <td style="vertical-align: text-bottom;">Email Text</td>
          <td><textarea name="paypal_email_msg" rows="5" cols="40"><?php _e($payMsg); ?></textarea></td>
        </tr>
        

       
        
        <tr>
          <td style="vertical-align: text-bottom;">Confirmation Return URL</td>
          <td><input type="text" name="wpconfirm_url" style="width:320px;" value="<?php _e($confirmUrl); ?>" id="wpconfirm_url" />
          </td>
        </tr>
        

        <tr>
          <td colspan="2" >&nbsp;</td>
        </tr>

        <tr height="20px;" >
          <td>&nbsp;</td>
          <td>
          <input type="submit" name="button" id="button" class="button-primary" value="<?php echo _e("Save Changes"); ?>" /></td>
        </tr>        
        
      </table>

       <input name="action" value="pay_update" type="hidden" />
       
      </form>
      
  </div>

<?php

} // end of General Setting function


// Show Donate page In front Area

		function MyDonatePage( $arr )
		{
			//echo "Rajendra KUmar Jangid";
	//		print_r( $arr );
	//		echo $arr["id"];
			if($arr["cid"] == "" ) {
				echo "Please Enter Campaign Id";
				die();
			}
			
			global $wpdb;
			$table_name = $wpdb->prefix.'campaigns';
			$wpdb->flush();
			
			$CampRow = $wpdb->get_row("SELECT * FROM $table_name WHERE camp_id=".$arr["cid"]);	
			
			$defaultAmt=0;
			$paypal_currency="USD";
			
			$emailId = get_option( 'paypal_email_id' );
			
			$notify = str_replace(ABSPATH, trailingslashit(get_option('siteurl')), dirname(__FILE__)).'/paypal.php';
			$img_urlz = array( '1'=>'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif', '2'=>'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif', '3'=>'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif', '4'=>$dplus['custom_button']);
			//$button = $img_urlz[$dplus['button_img']];
			
			$button = "https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif";
			
			
			if( $dplus['wall_url'] == 'sidebar') $wall = get_option('siteurl');
			else $wall = get_permalink($dplus['wall_url']);
			if( strpos($wall, '?') === false )
				$tyurl = $wall.'?thankyou=true';
			else
				$tyurl = $wall.'&amp;thankyou=true';

			$tyurl=get_option( 'wpconfirm_url' );
				
			$verifyurlz = array( '1' => 'https://www.paypal.com/cgi-bin/webscr', '2'=> 'http://www.belahost.com/pp/', '3'=>'https://www.sandbox.paypal.com/cgi-bin/webscr');

		if( $wpdb->num_rows > 0 ) {
			
			$output = '<form id="mydonate" action="'.$verifyurlz[1].'" method="post"><div class="mydonatebox">';

			if($CampRow->camp_field_title == 1 ){			
				$output .='<p class="donate_amount"><strong><label>'.__($CampRow->camp_name).'</label></strong></p>';
			}
			

			//$output .='<p class="donate_amount"><label for="amount">'.__($CampRow->camp_descr).'</label> </p>';
			//<p class="donate_amount"><label for="amount">'.__('Amount $').':</label> <input type="text" name="amount" id="amount" value="'.$defaultAmt.'" /></p>';

			$output .='<input type="hidden" id="cmd" name="cmd" value="_donations">';

			if($CampRow->camp_field_first_name == 1 ){			
				$output .='<p class="donate_amount"><label for="amount">'.__('First Name').':</label><input type="text" name="first_name" id="first_name" /></p>';
			}
			
			if($CampRow->camp_field_last_name == 1 ){			
				$output .='<p class="donate_amount"><label for="amount">'.__('Last Name').':</label><input type="text" name="last_name" id="last_name" /></p>';		
			}
			
			if($CampRow->camp_field_email == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('Email').':</label><input type="text" name="email" id="email" /></p>';
			}

			if($CampRow->camp_field_country == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('Country').':</label><input type="text" name="country" id="country" /></p>';
			}


			if($CampRow->camp_field_address == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('Address').':</label><input type="text" name="address1" id="address1" /></p>';
			}


			if($CampRow->camp_field_city == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('City').':</label><input type="text" name="city" id="city" /></p>';
			}


			if($CampRow->camp_field_state == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('State').':</label><input type="text" name="state" id="state" /></p>';
			}

			if($CampRow->camp_field_zip == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('Zip').':</label><input type="text" name="zip" id="zip" /></p>';
			}


			if($CampRow->camp_field_phone == 1 ){
				$output .='<p class="donate_amount"><label for="amount">'.__('Phone').':</label><input type="text" name="night_phone_a" id="night_phone_a" /></p>';
			}

			

			$output .= '
				<input type="hidden" name="notify_url" value="'.$notify.'">
				<input type="hidden" name="item_name" value="'.$CampRow->camp_name.'">
				<input type="hidden" name="business" value="'.$emailId.'">
				<input type="hidden" name="return" value="'.$tyurl.'">
				<input type="hidden" name="custom" value="'.$CampRow->camp_id.'#'.$CampRow->camp_name.'">		
				<input type="hidden" name="currency_code" value="'.$paypal_currency.'">
				<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
				<p class="submit"><input type="image" src="'.$button.'" border="0" name="submit" alt="">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"><br/>
				</p>
				</div></form><br/>';
			} else {
				
				$output= "Campaign Not Available";
			}
			return $output;
			
		}


		function showdonategoal( $arr )
		{

			if($arr["cid"] == "" ) {
				echo "Please Enter Campaign Id";
				die();
			}
			
			global $wpdb;
			$table_name = $wpdb->prefix.'campaigns';
			$wpdb->flush();
			
			$CampRow = $wpdb->get_row("SELECT * FROM $table_name WHERE camp_id=".$arr["cid"]);
			
			if( $wpdb->num_rows > 0 ) {
				
				$output =$CampRow->camp_goal_amt .'</p>';
				
			} else {
				$output= "Campaign Not Available";
			}
			
			return $output;
		}

		
		// this calculate donation amount

		function donationcollection( $arr )
		{

			if($arr["cid"] == "" ) {
				echo "Please Enter Campaign Id";
				die();
			}
			
			global $wpdb;
			$table_name = $wpdb->prefix.'donation';
			$wpdb->flush();
			
			$amt=0;
			
			$CampRow = $wpdb->get_row("SELECT sum(don_amt) as totalamt FROM $table_name WHERE don_camp_id=".$arr["cid"]);
			
			if( $wpdb->num_rows > 0 ) {
				
				if($CampRow->totalamt != "" ) {
				
					$amt=$CampRow->totalamt;
					
				} 
				
				$output =$amt.'</p>';
				
			} else {
				$output= "Campaign Not Available";
			}
			
			return $output;
		}

		//Display donars List if arg pass the
		
		function donatorlist( $arr )
		{

			global $wpdb;
			$wpdb->flush();
			
			$sql="SELECT
					wp_donation.don_id,
					wp_donation.don_camp_id,
					wp_donation.don_amt,
					wp_donation.don_first_name,
					wp_donation.don_last_name,
					wp_donation.don_email,
					wp_donation.don_address,
					wp_donation.don_country,
					wp_donation.don_city,
					wp_donation.don_state,
					wp_donation.don_zip,
					wp_donation.don_phone,
					wp_donation.txn_id,
					wp_donation.don_date,
					wp_campaigns.camp_field_anonymous
					FROM
					wp_donation
					Inner Join wp_campaigns ON wp_donation.don_camp_id = wp_campaigns.camp_id
					WHERE
					wp_campaigns.camp_field_anonymous <>  '1'";
					
			if( $arr["cid"] != "" ) {
				$sql .=" AND wp_donation.don_camp_id =  '".$arr["cid"]."'";
			}

			$donorLists = $wpdb->get_results($sql);

			if( $wpdb->num_rows > 0 ) {
			
				$i=1;
				$output ='<div class="donorList">
					<table width="350" border="0" cellspacing="0" cellpadding="0">
					<thead>
					  <tr>
						<th align="left">Name</th>
						<th align="left">Amount</th>
						<th align="left">Date</th>
					  </tr>
					  </thead>
					  <tbody>';
			  
					  foreach($donorLists as $donorList ) {
					 
					 	if($i%2==0) {
							$cls="class='odd'";
						} else {
						    $cls='';
						}
					  
						  $output .='<tr><td '.$cls.'>'.$donorList->don_first_name.' '.$donorList->don_last_name.'</td>';						  
						  $output .='<td '.$cls.'>$'.$donorList->don_amt.'</td>';
						  $output .='<td '.$cls.'>'.$donorList->don_date.'</td>';
						  $output .= '</tr>';
					  $i++;
					  }
  
				  $output .='</tbody></table></div>';
				
				
			} else {
				$output= "No donations have been made as of yet.";
			}
			
			return $output;
		}




		// this calculate donation amount

		function donatemeter( $arr )
		{
			if($arr["cid"] == "" ) {
				echo "Please Enter Campaign Id";
				die();
			}
			
			global $wpdb;
			
			$table_name = $wpdb->prefix.'campaigns';
			$wpdb->flush();
			
			$CampRow = $wpdb->get_row("SELECT * FROM $table_name WHERE camp_id=".$arr["cid"]);
			
			if( $wpdb->num_rows > 0 ) {
				
				$goal = $CampRow->camp_goal_amt;
				
				$wpdb->flush();				
				$table_name = $wpdb->prefix.'donation';

				
				$CampRow = $wpdb->get_row("SELECT sum(don_amt) as totalamt FROM $table_name WHERE don_camp_id=".$arr["cid"]);
				
				if( $wpdb->num_rows > 0 ) {
					
					if($CampRow->totalamt != "" ) {
						$current = $CampRow->totalamt;
					} else {
						$current =0;					
					}
				} else {
					$current = 0 ;
				}
				
				

				
				if($arr["displayname"] != "" ){
					echo $arr["displayname"]."<br/>";
				}

				echo '<img src="http://chart.apis.google.com/chart?chf=bg,s,65432100&chxr=1,0,'.$goal.'&chxs=0,676767,9.5|1,FF9900,9.5,0,l,676767&chs=300x150&cht=gm&chds=0,'.$goal.'&chd=t:'.$current.'&chdl=%24'.$current.'+/+%24'.$goal.'&chdlp=b&chl=%24'.$current.'&chma=0,0,15" width="300" height="150" alt="" />';
				
//				echo '<img src="'.$pgUrl.'?current='.$current.'&goal='.$goal.'" />';
				
				//ob_start();		
				
			} else {
			
				$output= "Campaign does not exist";
				
			}
			
		} // end of donateMeter
		
		
		function confirmdonation()
		{
			$confNo='';
			
//			print_r($_REQUEST);
			
			$confNo = $_REQUEST["txn_id"];
			
			$emilsent=$_REQUEST["payer_email"];
			
			$conf='<div class="donationConfDiv">
					<h3>Your donation is now complete.</h3>
					<p>Confirmation number: '.$confNo.'
					An e-mail with your donation details has been sent to '.$emilsent.' and you can <a href="#">print your donation receipt</a>.<br />
					<br />
					</div>';
			
		return $conf;
		}
		
		
		
		
// Save Add New Campaign

		function SaveNewCampaign()
		{
			//print_r( $_POST );
			
			if( $_POST["camp_name"]=="" ) {
				$_POST['notice']= "Please enter campaign name.";
				return false;
			}
			
			if( $_POST["camp_goal_amt"] == "" ){
				$_POST['notice']= "Please enter goal amount.";
				return false;
			}
			
			if( ! is_numeric($_POST["camp_goal_amt"] ) ){
				$_POST['notice']= "Please enter valid goal amount.";
				return false;
			}
			
			
			global $wpdb;
			$table_name= $wpdb->prefix.'campaigns';
			
			$data = array();
			
			$data["camp_name"]=$wpdb->escape($_POST["camp_name"]);
			$data["camp_goal_amt"]=$wpdb->escape($_POST["camp_goal_amt"]);
			$data["camp_descr"]=$wpdb->escape($_POST["camp_descr"]);
			
			$data["camp_field_title"]= strtolower($_POST["camp_field_title"]) == "on" ? "1" : "0" ;		
			$data["camp_field_first_name"]= strtolower($_POST["camp_field_first_name"]) == "on" ? "1" : "0" ;	
			$data["camp_field_last_name"]= strtolower($_POST["camp_field_last_name"]) == "on" ? "1" : "0" ;
			$data["camp_field_country"]= strtolower($_POST["camp_field_country"]) == "on" ? "1" : "0" ;		
			$data["camp_field_address"]= strtolower($_POST["camp_field_address"]) == "on" ? "1" : "0" ;
			$data["camp_field_city"]= strtolower($_POST["camp_field_city"]) == "on" ? "1" : "0" ;			
			$data["camp_field_state"]= strtolower($_POST["camp_field_state"]) == "on" ? "1" : "0" ;		
			$data["camp_field_zip"]= strtolower($_POST["camp_field_zip"]) == "on" ? "1" : "0" ;			
			$data["camp_field_phone"]= strtolower($_POST["camp_field_phone"]) == "on" ? "1" : "0" ;		
			$data["camp_field_email"]= strtolower($_POST["camp_field_email"]) == "on" ? "1" : "0" ;		
			$data["camp_field_anonymous"]= strtolower($_POST["camp_field_anonymous"]) == "on" ? "1" : "0" ;						
			$data["camp_create_date"]= date("Y-m-d");
		
			$rows_affected = $wpdb->insert( $table_name, $data );
			
				if($rows_affected === 1 ){
					$_POST['notice']= "Compaign successfully added.";
				} else {
					$_POST['notice']= "Compaign successfully not added.";
				}
		}


		//Save Campaign Chages
		
		function SaveEditCampaign()
		{
		
			if( $_POST["camp_name"]=="" ) {
				$_POST['notice']= "Please enter campaign name.";
				return false;
			}
			
			if( $_POST["camp_goal_amt"] == "" ){
				$_POST['notice']= "Please enter goal amount.";
				return false;
			}

			if( ! is_numeric($_POST["camp_goal_amt"] ) ){
				$_POST['notice']= "Please enter valid goal amount.";
				return false;
			}
			
			global $wpdb;
			$table_name= $wpdb->prefix.'campaigns';
			
			check_admin_referer('update-options');
			
			$data = array();
			
			$data["camp_name"]=$wpdb->escape($_POST["camp_name"]);
			$data["camp_goal_amt"]=$wpdb->escape($_POST["camp_goal_amt"]);
			$data["camp_descr"]=$wpdb->escape($_POST["camp_descr"]);
						
			$data["camp_field_title"]= strtolower($_POST["camp_field_title"]) == "on" ? "1" : "0" ;		
			$data["camp_field_first_name"]= strtolower($_POST["camp_field_first_name"]) == "on" ? "1" : "0" ;	
			$data["camp_field_last_name"]= strtolower($_POST["camp_field_last_name"]) == "on" ? "1" : "0" ;
			$data["camp_field_country"]= strtolower($_POST["camp_field_country"]) == "on" ? "1" : "0" ;		
			$data["camp_field_address"]= strtolower($_POST["camp_field_address"]) == "on" ? "1" : "0" ;
			$data["camp_field_city"]= strtolower($_POST["camp_field_city"]) == "on" ? "1" : "0" ;			
			$data["camp_field_state"]= strtolower($_POST["camp_field_state"]) == "on" ? "1" : "0" ;		
			$data["camp_field_zip"]= strtolower($_POST["camp_field_zip"]) == "on" ? "1" : "0" ;			
			$data["camp_field_phone"]= strtolower($_POST["camp_field_phone"]) == "on" ? "1" : "0" ;		
			$data["camp_field_email"]= strtolower($_POST["camp_field_email"]) == "on" ? "1" : "0" ;
			$data["camp_field_anonymous"]= strtolower($_POST["camp_field_anonymous"]) == "on" ? "1" : "0" ;						

		
			$rows_affected = $wpdb->update( $table_name, $data , array( 'camp_id' => $_POST["cID"] ) );
			
				if($rows_affected === 1 ){
					$_POST['notice'] = "Changes successfully saved";
				} else {
					$_POST['notice'] = "Changes successfully not saved";
				}
		}
		
		//Save General Settinga
		
		
		function saveGeneralSettings()
		{
			if( get_option( 'paypal_email_id' ) ) {
				update_option( 'paypal_email_id', $_POST["paypal_email_id"] );
			} else {
				add_option( 'paypal_email_id', $_POST["paypal_email_id"] );			
			}
	

			if( get_option( 'from_address' ) ) {
				update_option( 'from_address', $_POST["from_address"] );
			} else {
				add_option( 'from_address', $_POST["from_address"] );			
			}

			if( get_option( 'email_subject' ) ) {
				update_option( 'email_subject', $_POST["email_subject"] );
			} else {
				add_option( 'email_subject', $_POST["email_subject"] );			
			}


			if( get_option( 'paypal_email_msg' ) ) {
				update_option( 'paypal_email_msg', $_POST["paypal_email_msg"] );
			} else {
				add_option( 'paypal_email_msg', $_POST["paypal_email_msg"] );			
			}
			

			if( get_option( 'wpconfirm_url' ) ) {
				update_option( 'wpconfirm_url', $_POST["wpconfirm_url"] );
			} else {
				add_option( 'wpconfirm_url', $_POST["wpconfirm_url"] );			
			}
			
			$_POST['notice'] = __('Settings Saved');			
		
		}


	// Install Plugin

	function MyDonateInstall()
	{

		global $wpdb, $dplus_db_version;
		
		// Create Campaign Table
		
		$table_name = $wpdb->prefix . "campaigns";
		
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE $table_name  (
					  camp_id bigint(20) NOT NULL AUTO_INCREMENT,
					  camp_name varchar(250) DEFAULT NULL,
					  camp_goal_amt double(10,2) DEFAULT '0.00',
					  camp_descr text,
					  camp_field_title tinyint(2) DEFAULT '0',
					  camp_field_first_name tinyint(2) DEFAULT '0',
					  camp_field_last_name tinyint(2) DEFAULT '0',
					  camp_field_country tinyint(2) DEFAULT '0',
					  camp_field_address tinyint(2) DEFAULT '0',
					  camp_field_city tinyint(2) DEFAULT '0',
					  camp_field_state tinyint(2) DEFAULT '0',
					  camp_field_zip tinyint(2) DEFAULT '0',
					  camp_field_phone tinyint(2) DEFAULT '0',
					  camp_field_email tinyint(2) DEFAULT '0',
					  camp_field_anonymous tinyint(2) DEFAULT '0',					  
					  camp_create_date date DEFAULT NULL,
					  PRIMARY KEY (camp_id)
				);";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			}
			
		// Create Campaign Table
					
		$table_name = $wpdb->prefix . "donation";
		
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE $table_name  (
					  don_id bigint(20) NOT NULL AUTO_INCREMENT,
					  don_camp_id bigint(20) DEFAULT NULL,
					  don_amt double(10,2) DEFAULT '0.00',
					  don_first_name varchar(250) DEFAULT NULL,
					  don_last_name varchar(250) DEFAULT NULL,
					  don_email varchar(250) DEFAULT NULL,
					  don_address text,
					  don_country varchar(50) DEFAULT NULL,
					  don_city varchar(30) DEFAULT NULL,
					  don_state varchar(30) DEFAULT NULL,
					  don_zip varchar(30) DEFAULT NULL,
					  don_phone varchar(30) DEFAULT NULL,
					  txn_id varchar(50) DEFAULT NULL,
					  don_date date DEFAULT NULL,
					  PRIMARY KEY (don_id)
				);";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
			add_option("donate_version", $donate_version);
			
	}




	}//END Class DonatePlus
} //End of Class Check If

if( class_exists('MyWpDonate') )
	$objDonate = new MyWpDonate();

function MyDonateForm( $arr ){
	global $objDonate;
	echo $objDonate->MyDonatePage( $arr );
}

?>