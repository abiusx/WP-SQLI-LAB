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
                            <input type="hidden" name="act" value="login" />
                            <input type="hidden" name="parent_id" value="<?php echo $_POST['parent_id'];?>" />
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
