<?php
global $promo_image_url;
$promo_image_url=get_option('siteurl')."/wp-content/plugins/fbpromotions/admin/images";
?>
<link href="js/popupstyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo get_option('siteurl')?>/wp-content/plugins/fbpromotions/admin/js/jquery/*.js"></script> 
<script type="text/javascript" src="<?php echo get_option('siteurl')?>/wp-content/plugins/fbpromotions/admin/js/top_up-min.js"></script>
<script type="text/javascript">
  TopUp.host = "<?php echo get_option('siteurl')?>/";
  TopUp.images_path = "wp-content/plugins/fbpromotions/admin/images/top_up/";
</script>
<div class="wrap">
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
      <div id="form1">
    <h1><em>Bugs Go Viral: Account Settings</em></h1>
    <hr width="100%" size="1" noshade>
     <img src="<?php echo($promo_image_url);?>/headergraphic.png" width="700" >
     <div class="entrywrap roundcorner">
        			<h2>Your Account</h2>
					<div class="entry">
                   	  <div class="box roundcorner">
                      <form name="loginFrm" method="post" >
                            <p>Already Have an Account? Login below:</p>
                                <strong>Login</strong>
                             <input name="user_name" type="text">
                            <strong>Password</strong>
                            <input name="user_password" type="password">
                            <!-- If user is agency, go to agencyloggedin1.html -->
                            <!-- If user is individual, after successfully logged in...should be taken to dashboard -->
                           <script>
							function setHref() {
								   var string2 = document.loginFrm.user_name.value;
								   var string3 = "&p="+document.loginFrm.user_password.value;
								   var string1 = "<?php echo get_option('siteurl')?>/wp-content/plugins/fbpromotions/admin/agency_login1.php?u=";
								   hrefLnk = string1 + string2 + string3 ; // Concatenating strings
									document.getElementById('link1').href = hrefLnk; 
							}
						   </script>
                           <p align="right">
                           <a  toptions="type = iframe, effect = fade, x=180,y=120, width = 800, height = 600, overlayClose = 1" class="button roundcorner" id="link1" onclick="setHref();" >
                          	 Login
                           </a></p>
                          <div class="clearer"></div>
                          </form>
                      </div><!--end of login box-->
                      <div class="box roundcorner">
                      	<p>Don't have an account, click below to create one:</p>
                      	<p><a href="<?php echo get_option('siteurl')?>/wp-content/plugins/fbpromotions/admin/new_account2.php" toptions="type = iframe, effect = fade, x=180,y=120, width = 800, height = 600, overlayClose = 1" class="button roundcorner">Create Account &raquo;</a></p>
                      </div><!--end of click here to create account box-->
					<div class="clearer"></div>
                    </div><!-- / #entry -->
                </div>
</div>
</div>
			<?php do_html_admin_footer("plugin_settings","footer") ?>

    <script type="text/javascript">
	function timeMsg()
	{
	var t=setTimeout("alertMsg()",3000);
	}
	function alertMsg()
	{
	document.frm1.submit();
	}
</script>
<script>
function validate(){
	if(document.regfrm.bus_name.value==""||document.regfrm.name.value==""||document.regfrm.last_name.value==""||document.regfrm.add.value==""||document.regfrm.city.value==""||document.regfrm.state.value==""||document.regfrm.zip.value==""
	||document.regfrm.country.value==""||document.regfrm.phone.value==""||document.regfrm.email.value==""||document.regfrm.website.value==""){
			alert("Please enter data in all fileds.");
			return false;
	}
	 else if(document.regfrm.user_password.value!=document.regfrm.password2.value){
		alert("Both passwords donot match.");
		return false;
	}
	//timeMsg();
	return true;
}
function validate1(){
	if(document.logifrm.user_name.value==""||document.logifrm.user_password.value==""){
			alert("Please enter data in both fileds.");
			return false;
	}
	return true;
}
function refreshState(country){
	if(country == "US"){
		var states = '<select id="state" name="state" ><option value=""></option> <option value="AA">AA</option> <option value="AE">AE</option> <option value="AK">AK</option> <option value="AL">AL</option> <option value="AP">AP</option> <option value="AR">AR</option> <option value="AS">AS</option> <option value="AZ">AZ</option> <option value="CA">CA</option> <option value="CO">CO</option> <option value="CT">CT</option> <option value="DC">DC</option> <option value="DE">DE</option> <option value="FL">FL</option> <option value="FM">FM</option> <option value="GA">GA</option> <option value="GU">GU</option> <option value="HI">HI</option> <option value="IA">IA</option> <option value="ID">ID</option> <option value="IL">IL</option> <option value="IN">IN</option> <option value="KS">KS</option> <option value="KY">KY</option> <option value="LA">LA</option> <option value="MA">MA</option> <option value="MD">MD</option> <option value="ME">ME</option> <option value="MH">MH</option> <option value="MI">MI</option> <option value="MN">MN</option> <option value="MO">MO</option> <option value="MP">MP</option> <option value="MS">MS</option> <option value="MT">MT</option> <option value="NC">NC</option> <option value="ND">ND</option> <option value="NE">NE</option> <option value="NH">NH</option> <option value="NJ">NJ</option> <option value="NM">NM</option> <option value="NV">NV</option> <option value="NY">NY</option> <option value="OH">OH</option> <option value="OK">OK</option> <option value="OR">OR</option> <option value="PA">PA</option> <option value="PR">PR</option> <option value="PW">PW</option> <option value="RI">RI</option> <option value="SC">SC</option> <option value="SD">SD</option> <option value="TN">TN</option> <option value="TX">TX</option> <option value="UT">UT</option> <option value="VA">VA</option> <option value="VI">VI</option> <option value="VT">VT</option> <option value="WA">WA</option> <option value="WI">WI</option> <option value="WV">WV</option> <option value="WY">WY</option></select>';
  }else if(country == "AU"){
	  var states ='<select id="state" name="state"> <option value=""></option> <option value="Australian Capital Territory">Australian Capital Territory</option> <option value="New South Wales">New South Wales</option> <option value="Northern Territory">Northern Territory</option> <option value="Queensland">Queensland</option> <option value="South Australia">South Australia</option> <option value="Tasmania">Tasmania</option> <option value="Victoria">Victoria</option> <option value="Western Australia">Western Australia</option></select>';
  }else if(country=="GB"){
	   var states ='<select id="state" name="state"> <option value=""></option> <optgroup label="England"><option value="Avon">Avon</option><option value="Bedfordshire">Bedfordshire</option><option value="Berkshire">Berkshire</option><option value="Bristol">Bristol</option><option value="Buckinghamshire">Buckinghamshire</option><option value="Cambridgeshire">Cambridgeshire</option><option value="Cheshire">Cheshire</option><option value="Cleveland">Cleveland</option><option value="Cornwall">Cornwall</option><option value="Cumbria">Cumbria</option><option value="Derbyshire">Derbyshire</option><option value="Devon">Devon</option><option value="Dorset">Dorset</option><option value="Durham">Durham</option><option value="East Riding of Yorkshire">East Riding of Yorkshire</option><option value="East Sussex">East Sussex</option><option value="Essex">Essex</option><option value="Gloucestershire">Gloucestershire</option><option value="Greater Manchester">Greater Manchester</option><option value="Hampshire">Hampshire</option><option value="Herefordshire">Herefordshire</option><option value="Hertfordshire">Hertfordshire</option><option value="Humberside">Humberside</option><option value="Isle of Wight">Isle of Wight</option><option value="Isles of Scilly">Isles of Scilly</option><option value="Kent">Kent</option><option value="Lancashire">Lancashire</option><option value="Leicestershire">Leicestershire</option><option value="Lincolnshire">Lincolnshire</option><option value="London">London</option><option value="Merseyside">Merseyside</option><option value="Middlesex">Middlesex</option><option value="Norfolk">Norfolk</option><option value="North Yorkshire">North Yorkshire</option><option value="North East Lincolnshire">North East Lincolnshire</option><option value="Northamptonshire">Northamptonshire</option><option value="Northumberland">Northumberland</option><option value="Nottinghamshire">Nottinghamshire</option><option value="Oxfordshire">Oxfordshire</option><option value="Rutland">Rutland</option><option value="Shropshire">Shropshire</option><option value="Somerset">Somerset</option><option value="South Yorkshire">South Yorkshire</option><option value="Staffordshire">Staffordshire</option><option value="Suffolk">Suffolk</option><option value="Surrey">Surrey</option><option value="Tyne and Wear">Tyne and Wear</option><option value="Warwickshire">Warwickshire</option><option value="West Midlands">West Midlands</option><option value="West Sussex">West Sussex</option><option value="West Yorkshire">West Yorkshire</option><option value="Wiltshire">Wiltshire</option><option value="Worcestershire">Worcestershire</option> </optgroup> <optgroup label="Northern Ireland"><option value="Antrim">Antrim</option><option value="Armagh">Armagh</option><option value="Down">Down</option><option value="Fermanagh">Fermanagh</option><option value="Londonderry">Londonderry</option><option value="Tyrone">Tyrone</option> </optgroup> <optgroup label="Scotland"><option value="Aberdeen City">Aberdeen City</option><option value="Aberdeenshire">Aberdeenshire</option><option value="Angus">Angus</option><option value="Argyll and Bute">Argyll and Bute</option><option value="Banffshire">Banffshire</option><option value="Borders">Borders</option><option value="Clackmannan">Clackmannan</option><option value="Dumfries and Galloway">Dumfries and Galloway</option><option value="East Ayrshire">East Ayrshire</option><option value="East Dunbartonshire">East Dunbartonshire</option><option value="East Lothian">East Lothian</option><option value="East Renfrewshire">East Renfrewshire</option><option value="Edinburgh City">Edinburgh City</option><option value="Falkirk">Falkirk</option><option value="Fife">Fife</option><option value="Glasgow">Glasgow (City of)</option><option value="Highland">Highland</option><option value="Inverclyde">Inverclyde</option><option value="Midlothian">Midlothian</option><option value="Moray">Moray</option><option value="North Ayrshire">North Ayrshire</option><option value="North Lanarkshire">North Lanarkshire</option><option value="Orkney">Orkney</option><option value="Perthshire and Kinross">Perthshire and Kinross</option><option value="Renfrewshire">Renfrewshire</option><option value="Roxburghshire">Roxburghshire</option><option value="Shetland">Shetland</option><option value="South Ayrshire">South Ayrshire</option><option value="South Lanarkshire">South Lanarkshire</option><option value="Stirling">Stirling</option><option value="West Dunbartonshire">West Dunbartonshire</option><option value="West Lothian">West Lothian</option><option value="Western Isles">Western Isles</option> </optgroup> <optgroup label="Unitary Authorities of Wales"><option value="Blaenau Gwent">Blaenau Gwent</option><option value="Bridgend">Bridgend</option><option value="Caerphilly">Caerphilly</option><option value="Cardiff">Cardiff</option><option value="Carmarthenshire">Carmarthenshire</option><option value="Ceredigion">Ceredigion</option><option value="Conwy">Conwy</option><option value="Denbighshire">Denbighshire</option><option value="Flintshire">Flintshire</option><option value="Gwynedd">Gwynedd</option><option value="Isle of Anglesey">Isle of Anglesey</option><option value="Merthyr Tydfil">Merthyr Tydfil</option><option value="Monmouthshire">Monmouthshire</option><option value="Neath Port Talbot">Neath Port Talbot</option><option value="Newport">Newport</option><option value="Pembrokeshire">Pembrokeshire</option><option value="Powys">Powys</option><option value="Rhondda Cynon Taff">Rhondda Cynon Taff</option><option value="Swansea">Swansea</option><option value="Torfaen">Torfaen</option><option value="The Vale of Glamorgan">The Vale of Glamorgan</option><option value="Wrexham">Wrexham</option> </optgroup> <optgroup label="UK Offshore Dependencies"><option value="Channel Islands">Channel Islands</option><option value="Isle of Man">Isle of Man</option> </optgroup></select>';
  }else if(country == "IN"){
	  var states = '<select id="state" name="state"> <option value="" selected>Select State</option> <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option> <option value="Andhra Pradesh">Andhra Pradesh</option> <option value="Arunachal Pradesh">Arunachal Pradesh</option> <option value="Assam">Assam</option> <option value="Bihar">Bihar</option> <option value="Chandigarh">Chandigarh</option> <option value="Chhattisgarh">Chhattisgarh</option> <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option> <option value="Daman and Diu">Daman and Diu</option> <option value="NCT-Delhi">NCT-Delhi</option> <option value="Goa">Goa</option> <option value="Gujarat">Gujarat</option> <option value="Haryana">Haryana</option> <option value="Himachal Pradesh">Himachal Pradesh</option> <option value="Jammu and Kashmir">Jammu and Kashmir</option> <option value="Jharkhand">Jharkhand</option> <option value="Karnataka">Karnataka</option> <option value="Kerala">Kerala</option> <option value="Lakshadweep">Lakshadweep</option> <option value="Madhya Pradesh">Madhya Pradesh</option> <option value="Maharashtra">Maharashtra</option> <option value="Manipur">Manipur</option> <option value="Meghalaya">Meghalaya</option> <option value="Mizoram">Mizoram</option> <option value="Nagaland">Nagaland</option> <option value="Orissa">Orissa</option> <option value="Puducherry">Puducherry</option> <option value="Punjab">Punjab</option> <option value="Rajasthan">Rajasthan</option> <option value="Sikkim">Sikkim</option> <option value="Tamil Nadu">Tamil Nadu</option> <option value="Tripura">Tripura</option> <option value="Uttar Pradesh">Uttar Pradesh</option> <option value="Uttarakhand">Uttarakhand</option> <option value="West Bengal">West Bengal</option></select>';
  }else{
	  		 var states = '<input name="state" type="text">';
  }
  		document.getElementById('state').innerHTML= states;

}
</script>