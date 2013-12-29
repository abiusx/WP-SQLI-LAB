<?php
if ( !isset($wp_did_header) ) 
	{
		$wp_did_header = true;
		require_once('../../../../wp-load.php' );
	}
    global $promo_list;
	global $fbmo;
	global $wpdb;
	//print_r($_REQUEST);
	if(isset($_REQUEST['u'])){
		 $serverurl =  get_option("serverurl");
		 $key = trim(get_option("key"));
		 $data = file_get_contents($serverurl."index.php?product_id=".$key."&email=".$_REQUEST['u']."&pass=".$_REQUEST['p']."&fb_edit_action=check_agency_login");
	     $arr = explode("?",$data);
	}
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
		<h2><img src="http://bugsgoviral.com/images/icons-04.png" class="fl"/>Choose type of Install:</h2>																	
        <div class="entry">
			<div class="box roundcorner">
            <?php
			if(trim($arr[0])== "Invalid details"){
		    ?>
            <p>Oops, Invalid Login details.Please correct your user name and password.</p>
            <?php
			}else{
			?>
        		<form name="regfrm"   method="post" onsubmit="return checkAction();">
                            <input type="hidden" name="act" value="login" />
                            <input type="hidden" name="parent_id" value="<?php echo $arr[0];?>" />
                            <p><strong>Is install for new or existing client?:</strong>
                              <select name="options" id="options">
                                <option value="">*Select an Option Below:</option>
                                <option value="new">New Client</option>
                                <option value="existing">Existing Client</option>
                              </select>
                            </p>
                            <p><strong>
                              <input type="checkbox" name="neither" id="checkbox" />
                              <label for="checkbox"></label>
                            or neither, promotion is for agency.</strong></p>
                            <!--if user selects neither, promo is for agency, they will be taken to confirmation screen-->
                            <!--if user selects new or exisiting..
                            if user selects existing client...they should be taken to agencyloggedin2.html
                            If user selects new client...they should be taken to clientdetails.html -->
                          <input name="Submit" class="submitbtn" value="Continue" type="submit">
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
<script>
function checkAction(){
	if(document.regfrm.neither.checked){
		document.regfrm.action="confirmation.php";
		return true;
	}
	else if(document.regfrm.options.selectedIndex == "1"){
		document.regfrm.action="client_details.php";
		return true;
	}
	else if(document.regfrm.options.selectedIndex == "2"){
		document.regfrm.action="agency_login2.php";
		return true;
	}
}
</script>
