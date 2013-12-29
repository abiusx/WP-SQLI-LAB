<?php
global $promo_image_url;
$promo_image_url=get_option('siteurl')."/facebookapp/layouts/landing/images/";
?>

<style type="text/css">
<!--
h1 {
	font:1.6em Georgia, "Times New Roman", Times, serif;
	margin:0;
	padding:5px 10px;
}
p{
	font:1.0em Georgia, "Times New Roman", Times, serif;
	padding:5px 10px;
}
-->
</style>
<table width="760" border="0" cellpadding="0" cellspacing="0" background="<?php echo($promo_image_url);?>/bugsbg_03.jpg">
  <tr>
    <td width="250" height="700" valign="top"><table width="250" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="250" height="250">

</td>
      </tr>
      <tr>
        <td width="250" height="250">
		</td>
      </tr>
    </table></td>
    <td valign="top"><table width="510" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="85">&nbsp;</td>
      </tr>
      <tr>
        <td height="372" valign="top"><h1>Landing Page</h1>
        	<p>You have reached the landing page because no promo ID was specified.
</p>
        </td>
      </tr>
      <tr>
        <td><table width="510" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="110">&nbsp;</td>
            <td></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="760" border="1" cellpadding="20" cellspacing="0" bordercolor="#666666">
<tr>
        <td align="center" valign="middle"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
</font></td>
      </tr>
      <tr>
        <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>RULES &amp; REGULATIONS</strong></font></p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
			<?php echo($promo_row["promo_rules"])?>
            </font></p>
          </td>
      </tr>
    </table>
