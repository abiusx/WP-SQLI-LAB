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
		<h2><img src="http://bugsgoviral.com/images/icons-04.png" class="fl"/>Create your account below:</h2>																	
        <div class="entry">
        		             <form name="regfrm" action="agency_accountsetup1.php" method="post" onsubmit="return validate();">
							<div class="box roundcorner">
                            <input type="hidden" name="act" value="register" />
                            <input type="hidden" name="agency" value="1" />
                           <p><strong>Agency Name</strong>
                              <input name="bus_name" type="text" value="">
                               <strong>Select your Agency Type:</strong>
								<select name="agency_type" size="1">
                                <option name=""></option>
                                <option name="Website Design">Website Design</option>
                                <option name="Ad Agency">AdAgency</option>
                                <option name="FreeLance Consulatant">FreeLance Consulatant</option>
                                <option name="Social Media Firm">Social Media Firm</option>
                                <option name="Media Outlet">Media Outlet</option>
                                <option name="Others">Others</option>
                                </select>  
                                </p>
                               <p>                 
                                <strong>Contact First Name</strong>
                              <input name="name" type="text"  value="">
                               <strong>Contact Last Name</strong>
                              <input name="last_name" type="text" value="">
                                <strong>Address</strong>
                              <input name="add" type="text" style="width:240px;" value="">
                               <strong>City</strong>
                              <input name="city" type="text" value="">
                              <strong>Country</strong>
                                    <select id="country" name="country" onchange="refreshState(this.value)">	
                                        <option value="DZ" selected>Algeria</option>
                                        <option value="AL">Albania</option>
                                        <option value="AD">Andorra</option>
                                        <option value="AO">Angola</option>
                                        <option value="AI">Anguilla</option>
                                        <option value="AG">Antigua and Barbuda</option>
                                        <option value="AR">Argentina</option>
                                        <option value="AM">Armenia</option>
                                        <option value="AW">Aruba</option>
                                        <option value="AU">Australia</option>
                                        <option value="AT">Austria</option>
                                        <option value="AZ">Azerbaijan Republic</option>
                                        <option value="BS">Bahamas</option>
                                        <option value="BH">Bahrain</option>
                                        <option value="BB">Barbados</option>
                                        <option value="BE">Belgium</option>
                                        <option value="BZ">Belize</option>
                                        <option value="BJ">Benin</option>
                                        <option value="BM">Bermuda</option>
                                        <option value="BT">Bhutan</option>
                                        <option value="BO">Bolivia</option>
                                        <option value="BA">Bosnia and Herzegovina</option>
                                        <option value="BW">Botswana</option>
                                        <option value="BR">Brazil</option>
                                        <option value="VG">British Virgin Islands</option>
                                        <option value="BN">Brunei</option>
                                        <option value="BG">Bulgaria</option>
                                        <option value="BF">Burkina Faso</option>
                                        <option value="BI">Burundi</option>
                                        <option value="KH">Cambodia</option>
                                        <option value="CA">Canada</option>
                                        <option value="CV">Cape Verde</option>
                                        <option value="KY">Cayman Islands</option>
                                        <option value="TD">Chad</option>
                                        <option value="CL">Chile</option>
                                        <option value="C2">China Worldwide</option>
                                        <option value="CO">Colombia</option>
                                        <option value="KM">Comoros</option>
                                        <option value="CK">Cook Islands</option>
                                        <option value="CR">Costa Rica</option>
                                        <option value="HR">Croatia</option>
                                        <option value="CY">Cyprus</option>
                                        <option value="CZ">Czech Republic</option>
                                        <option value="CD">Democratic Republic of the Congo</option>
                                        <option value="DK">Denmark</option>
                                        <option value="DJ">Djibouti</option>
                                        <option value="DM">Dominica</option>
                                        <option value="DO">Dominican Republic</option>
                                        <option value="EC">Ecuador</option>
                                        <option value="SV">El Salvador</option>
                                        <option value="ER">Eritrea</option>
                                        <option value="EE">Estonia</option>
                                        <option value="ET">Ethiopia</option>
                                        <option value="FK">Falkland Islands</option>
                                        <option value="FO">Faroe Islands</option>
                                        <option value="FM">Federated States of Micronesia</option>
                                        <option value="FJ">Fiji</option>
                                        <option value="FI">Finland</option>
                                        <option value="FR">France</option>
                                        <option value="GF">French Guiana</option>
                                        <option value="PF">French Polynesia</option>
                                        <option value="GA">Gabon Republic</option>
                                        <option value="GM">Gambia</option>
                                        <option value="DE">Germany</option>
                                        <option value="GI">Gibraltar</option>
                                        <option value="GR">Greece</option>
                                        <option value="GL">Greenland</option>
                                        <option value="GD">Grenada</option>
                                        <option value="GP">Guadeloupe</option>
                                        <option value="GT">Guatemala</option>
                                        <option value="GN">Guinea</option>
                                        <option value="GW">Guinea Bissau</option>
                                        <option value="GY">Guyana</option>
                                        <option value="HN">Honduras</option>
                                        <option value="HK">Hong Kong</option>
                                        <option value="HU">Hungary</option>
                                        <option value="IS">Iceland</option>
                                        <option value="IN">India</option>
                                        <option value="ID">Indonesia</option>
                                        <option value="IE">Ireland</option>
                                        <option value="IL">Israel</option>
                                        <option value="IT">Italy</option>
                                        <option value="JM">Jamaica</option>
                                        <option value="JP">Japan</option>
                                        <option value="JO">Jordan</option>
                                        <option value="KZ">Kazakhstan</option>
                                        <option value="KE">Kenya</option>
                                        <option value="KI">Kiribati</option>
                                        <option value="KW">Kuwait</option>
                                        <option value="KG">Kyrgyzstan</option>
                                        <option value="LA">Laos</option>
                                        <option value="LV">Latvia</option>
                                        <option value="LS">Lesotho</option>
                                        <option value="LI">Liechtenstein</option>
                                        <option value="LT">Lithuania</option>
                                        <option value="LU">Luxembourg</option>
                                        <option value="MG">Madagascar</option>
                                        <option value="MW">Malawi</option>
                                        <option value="MY">Malaysia</option>
                                        <option value="MV">Maldives</option>
                                        <option value="ML">Mali</option>
                                        <option value="MT">Malta</option>
                                        <option value="MH">Marshall Islands</option>
                                        <option value="MQ">Martinique</option>
                                        <option value="MR">Mauritania</option>
                                        <option value="MU">Mauritius</option>
                                        <option value="YT">Mayotte</option>
                                        <option value="MX">Mexico</option>
                                        <option value="MN">Mongolia</option>
                                        <option value="MS">Montserrat</option>
                                        <option value="MA">Morocco</option>
                                        <option value="MZ">Mozambique</option>
                                        <option value="NA">Namibia</option>
                                        <option value="NR">Nauru</option>
                                        <option value="NP">Nepal</option>
                                        <option value="NL">Netherlands</option>
                                        <option value="AN">Netherlands Antilles</option>
                                        <option value="NC">New Caledonia</option>
                                        <option value="NZ">New Zealand</option>
                                        <option value="NI">Nicaragua</option>
                                        <option value="NE">Niger</option>
                                        <option value="NU">Niue</option>
                                        <option value="NF">Norfolk Island</option>
                                        <option value="NO">Norway</option>
                                        <option value="OM">Oman</option>
                                        <option value="PW">Palau</option>
                                        <option value="PA">Panama</option>
                                        <option value="PG">Papua New Guinea</option>
                                        <option value="PE">Peru</option>
                                        <option value="PH">Philippines</option>
                                        <option value="PN">Pitcairn Islands</option>
                                        <option value="PL">Poland</option>
                                        <option value="PT">Portugal</option>
                                        <option value="QA">Qatar</option>
                                        <option value="CG">Republic of the Congo</option>
                                        <option value="RE">Reunion</option>
                                        <option value="RO">Romania</option>
                                        <option value="RU">Russia</option>
                                        <option value="RW">Rwanda</option>
                                        <option value="VC">Saint Vincent and the Grenadines</option>
                                        <option value="WS">Samoa</option>
                                        <option value="SM">San Marino</option>
                                        <option value="ST">São Tomé and Príncipe</option>
                                        <option value="SA">Saudi Arabia</option>
                                        <option value="SN">Senegal</option>
                                        <option value="SC">Seychelles</option>
                                        <option value="SL">Sierra Leone</option>
                                        <option value="SG">Singapore</option>
                                        <option value="SK">Slovakia</option>
                                        <option value="SI">Slovenia</option>
                                        <option value="SB">Solomon Islands</option>
                                        <option value="SO">Somalia</option>
                                        <option value="ZA">South Africa</option>
                                        <option value="KR">South Korea</option>
                                        <option value="ES">Spain</option>
                                        <option value="LK">Sri Lanka</option>
                                        <option value="SH">St. Helena</option>
                                        <option value="KN">St. Kitts and Nevis</option>
                                        <option value="LC">St. Lucia</option>
                                        <option value="PM">St. Pierre and Miquelon</option>
                                        <option value="SR">Suriname</option>
                                        <option value="SJ">Svalbard and Jan Mayen Islands</option>
                                        <option value="SZ">Swaziland</option>
                                        <option value="SE">Sweden</option>
                                        <option value="CH">Switzerland</option>
                                        <option value="TW">Taiwan</option>
                                        <option value="TJ">Tajikistan</option>
                                        <option value="TZ">Tanzania</option>
                                        <option value="TH">Thailand</option>
                                        <option value="TG">Togo</option>
                                        <option value="TO">Tonga</option>
                                        <option value="TT">Trinidad and Tobago</option>
                                        <option value="TN">Tunisia</option>
                                        <option value="TR">Turkey</option>
                                        <option value="TM">Turkmenistan</option>
                                        <option value="TC">Turks and Caicos Islands</option>
                                        <option value="TV">Tuvalu</option>
                                        <option value="UG">Uganda</option>
                                        <option value="UA">Ukraine</option>
                                        <option value="AE">United Arab Emirates</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="US">United States</option>
                                        <option value="UY">Uruguay</option>
                                        <option value="VU">Vanuatu</option>
                                        <option value="VA">Vatican City State</option>
                                        <option value="VE">Venezuela</option>
                                        <option value="VN">Vietnam</option>
                                        <option value="WF">Wallis and Futuna Islands</option>
                                        <option value="YE">Yemen</option>
                                        <option value="ZM">Zambia</option></select>
                              <strong>State</strong>
                                    <span id="state" style="">
                                   <input name="state" type="text">
                               		</span>
                                    <strong>Zip</strong>
                              <input name="zip" type="text" style="width:50px;" size="6" value="">
                               
                                <strong>Phone</strong>
                              <input name="phone" type="text" style="width:100px;" size="12" value="">
                                <strong>Email</strong>
                              <input name="email" type="text" style="width:200px;" value="">
                                <strong>Password</strong>
                              <input name="user_password" type="password" value="">
                                <strong>Reytpe Password</strong>
                              <input name="password2" type="password" value="">
                                <strong>Website Address</strong>
                              <input name="website" type="text" style="width:200px;" value="">
                               </p>
                            <p>&nbsp;</p>
                            <input name="Submit" class="submitbtn" value="Submit" type="submit">
                          <div class="clearer"></div>
                          </div>
              </form>
			</div>
</div><!-- / #entry -->
                </div><!-- / #entrywrap --></div>
</body>
</html>
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