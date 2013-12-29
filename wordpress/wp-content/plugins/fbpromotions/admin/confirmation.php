<?php
if ( !isset($wp_did_header) ) 
	{
		$wp_did_header = true;
		require_once('../../../../wp-load.php' );
	}
    global $promo_list;
	global $fbmo;
	global $wpdb;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="js/popupstyle.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="popupcontainer">
	<div class="entrywrap roundcorner">
		<h2><img src="http://bugsgoviral.com/images/icons-04.png" class="fl"/>Accounts creation  process</h2>																	
        <div class="entry">
			<div class="box roundcorner">
            <?php
			$serverurl =  get_option("serverurl");
			$key = trim(get_option("key"));	
			
		    $_POST['agency_type'] = urlencode($_POST['agency_type']);
			$_POST['client_name'] =   urlencode($_POST['client_name']);
			$_POST['name'] =   urlencode($_POST['name']);
			$_POST['last_name'] =   urlencode($_POST['last_name']);
			$_POST['bus_name'] =   urlencode($_POST['bus_name']);
			$_POST['add'] =   urlencode($_POST['add']);
			$_POST['city'] =   urlencode($_POST['city']);
			$_POST['state'] =   urlencode($_POST['state']);
			$_POST['country'] =   urlencode($_POST['country']);
			$_POST['phone'] =   urlencode($_POST['phone']);
			$_POST['website'] =   urlencode($_POST['website']);
			
			 $url = ($serverurl."index.php?agency_type=".$_POST['agency_type']."&client_name=".$_POST['client_name']."&agency=".$_POST['agency']."&bus_name=".$_POST['bus_name']."&name=".$_POST['name']."&last_name=".$_POST['last_name']."&add=".$_POST['add']."&city=".$_POST['city']."&state=".$_POST['state']."&zip=".$_POST['zip']."&country=".$_POST['country']."&phone=".$_POST['phone']."&email=".$_POST['email']."&user_password=".$_POST['user_password']."&website=".$_POST['website']."&product_id=".$key."&fb_edit_action=manage_reg&act=".$_POST['act']);
			
			
		   $msg = file_get_contents($url);	
			
	         if($_REQUEST['act']=="login"){

				if(!empty($_POST['parent_id'])){
				
					   if($_POST['client_name'])
					   $_POST['client']=$_POST['client_name'];
					   $date = time();
					   //echo $serverurl."index.php?client_name=".$_POST['client']."&product_id=".$key."&fb_edit_action=update_client";
						$msg = file_get_contents($serverurl."index.php?fb_edit_action=update_client&date=".$date."&client_name=".$_POST['client']."&product_id=".$key);	
						
				}else{
					    $msg = "You have been sucessfully logged in.";
				}
			?>
            <p><?php echo $msg;?></p>
            <?php
			}else{
				if(isset($msg))
				echo "<p>5$msg</p>";
				else{
			?>
			  <p>Congratulations, your account/s have been created successfully!</p>
             <?php
				}
			}
			?>
			</div>
</div><!-- / #entry -->
                </div><!-- / #entrywrap --></div>
</body>
</html>
