<?php
if ( !isset($wp_did_header) ) 
	{
		$wp_did_header = true;
		require_once('../../../../wp-load.php' );
	}
    global $promo_list;
	global $fbmo;
	global $wpdb;
	$serverurl =  get_option("serverurl");
	$key = trim(get_option("key"));
	$clients_data = file_get_contents($serverurl."index.php?product_id=".$key."&parent_id=".$_POST['parent_id']."&fb_edit_action=get_clients");
	$arr_clients = explode("?",$clients_data);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="js/popupstyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
</head>

<body>
<div class="popupcontainer">
	<div class="entrywrap roundcorner">
		<h2><img src="http://bugsgoviral.com/images/icons-04.png" class="fl"/>Choose Client:</h2>																	
        <div class="entry">
			<div class="box roundcorner">
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
                            <p><strong>Choose client below and click continue:</strong>
                              <select name="client">
                                <option value="">*Select an Option Below:</option>
                                 <?php
								 for($i=0;$i<count($arr_clients);$i++){
									 $atr = explode("=",$arr_clients[$i]);
									 if(!empty($atr[2])){
								  ?>
                               		   <option value="<?php echo $atr[2];?>"><?php echo $atr[2];?></option>
                                  <?php
									 }
								  }
								  ?>
                              </select>
                            </p>
                            <p>&nbsp;</p>
                            <!--if successful, take user to confirmation screen-->
                               <input name="Submit" class="submitbtn" value="Continue" type="submit">
                          <div class="clearer"></div>
              </form>
			</div>
</div><!-- / #entry -->
                </div><!-- / #entrywrap --></div>
</body>
</html>
