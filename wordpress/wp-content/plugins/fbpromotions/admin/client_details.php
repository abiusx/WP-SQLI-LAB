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
		<h2><img src="http://bugsgoviral.com/images/icons-04.png" class="fl"/>Fill in your Client details below:</h2>																	
        <div class="entry">
			<div class="box roundcorner">
                       <?php
					    $key = get_option("key");
						if($_REQUEST['act']=="login"){
						?>
                        <form name="regfrm" action="confirmation.php" method="post">
                        <input type="hidden" name="act" value="login" />
                        <input type="hidden" name="parent_id" value="<?php echo $_POST["parent_id"]?>" />
                         <p><strong>Client  Name</strong>
                         <input name="client_name" type="text">
                           </p>
                            <!--AFter clicking save..if successful, take user to the confirmation screen-->
                          <input name="Submit" class="submitbtn" value="Save" type="submit">
                          <div class="clearer"></div>
                        </form>
                        <?php
						}else{
					   ?>
                    
                    	<form name="regfrm" action="confirmation.php" method="post">
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
                            <p><strong>Client  Name</strong>
                              <input name="client_name" type="text">
                            </p>
                            <!--AFter clicking save..if successful, take user to the confirmation screen-->
                          <input name="Submit" class="submitbtn" value="Save" type="submit">
                          <div class="clearer"></div>
              </form>
              <?php
						}
					?>
			</div>
</div><!-- / #entry -->
                </div><!-- / #entrywrap --></div>
</body>
</html>
