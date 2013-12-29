<?php
/**
*	BugsGoViral Plugin for Worpress
*	Admin HTML Functions
*
*	@filesource 			admin_html_func.php
*	@description			Functions for writing HTML to admin views
*
* */
include("version.php");

/**
*	@name			admin_images_url ()
*	@description	Defines the root url to use to link to Admin UI images
*/
function admin_images_url () {
	$admin_images_url  =  get_option('siteurl')  .  "/wp-content/plugins/fbpromotions/admin/images";
	return $admin_images_url;
}

/**
*	@name			do_html_admin_masthead($title,$slug)
* 	@description	Writes the html code to display the Masthead in Admin UI
* 	@param 			$id = link friendly page name slug (i.e. "page_name")
* 	@param 			$class = link friendly div class (i.e. "admin_masthead)
*  	@param 			$title = Human readable name for page to display  (i.e. "Page Name")
*  	@param 			$sub_title = Human readable name to display in header bar  (i.e. Header Text")
* 	@param 			$val = print heading bar value, 1 = print heading, 0 = do not print heading
*/
function do_html_admin_masthead($id="id",$class="class",$title="title",$sub_title="sub title",$val,$page='')  {
	echo '
	<div id="'  .  $id  .  '_'  .  $class  .  '" class="'  .  $class  .  '">
	<img src="'  .  admin_images_url()  .  '/headergraphic.png"> <br>
	<h1 id="'  .  $id  .  '_title" class="title">Bugs Go Viral &raquo; '  .   $title  .  '</h1>';
	$serverurl =  get_option("serverurl");
	$key = trim(get_option("key"));
    $data = file_get_contents($serverurl."index.php?product_id=".$key."&fb_edit_action=credits_info");
	$arr = explode("?",$data);
	if($page==1){
        echo '<p>Configure a promotion by beginning step one below. Hold your mouse over the blue question marks for a brief explanation of each field.
       </p>';
	}else if($page ==2){
		   echo '<p>Below are the promos that you have configured. Select an option to proceed. The Activate button appears for promos that have been configured, but not activated. When you click activate, you will be taken to your Bugs Go Viral account area in a new window.
       </p>';
	}else if($page ==3){
		   echo '<p> It\'s fun to keep tabs on how everyone else is doing with Bugs Go Viral promotions. The numbers below are aggregate numbers for all active and completed promos around the world. This is the Bugs Go Viral "hive." Hear the buzz?
       </p>';
	}else if($page == 4){
	?>
    <p>Welcome <?php echo $arr[0];?>. This is your BugsGoViral dashboard .You have <?php echo $arr[2];?> credits available.<a href="#" onclick="document.regfrm.submit()">Click here</a> to view your account,purchase credits,allocate credits and activate promos.
      </p>
    <?php	
	}else if($page == 5){
	?>
    <p>Welcome <?php echo $arr[0];?>. This is your BugsGoViral dashboard .You have <?php echo $arr[2];?> credits available.<a href="#" onclick="document.regfrm.submit()">Click here</a> to view your account,purchase credits and activate promos.
      </p>
    <?php	
	}else if($page == 6){
		
	?>
    <p>Welcome, <?php echo $arr[0];?> <?php echo $arr[1];?>. This is your Bugs Go Viral dashboard with a snapshot of your activity. Your lifetime performance stats are shown below. Select an option to continue.
    </p>
    <p>
    Total Credits:<?php echo $arr[2]+$arr[3];?>
    </p>
    <p>
    Total Unallocated Credits: <?php echo $arr[2];?>
     </p>
    <?php	
	}

	if ($val == 1) echo '
	<p id="'  .  $id  .  '_sub_title" class="sub_title">'  .   $sub_title  .  '</p>';

	if (isset($fbmo->admin_messages['message'])) {
	echo '<div  class="error">'  .  $fbmo->admin_messages['message']  .  '</div>';
	} else {
	echo '</div><!-- DIV END "'  .  $id  .  '" -->';

	}
}

/**
*	@name			do_html_admin_footer($title,$slug)
* 	@description	Writes the html code to display the Footer in Admin UI
* 	@param 			$id = link friendly page name slug (i.e. "page_name")
* 	@param 			$class = link friendly div class (i.e. "admin_footer)
*/
?>
<?php
function do_html_admin_footer($id="id",$class="class")  {
	$serverurl =  get_option("serverurl");
	$key = trim(get_option("key"));
	$stored_version = get_option("fbpromo_version");
		$current_version_last_update= file_get_contents($serverurl."index.php?product_id=".$key."&fb_edit_action=get_last_updated_date");


	$style = "background-image:url(".admin_images_url() ."/footergraphic.png);width:700px; height:150px;";
	echo '
	<hr width="100%" size="1" noshade>
	<div id="'  .  $id  .  '_'  .  $class  .  '" class="'  .  $class  .  '" style="'.$style.'">
     <p>Version is '.$stored_version.' & requires Wordpress version {3.0} & higher. Last updated '.$current_version_last_update.'</p>
	</div>';
}


/**
*	@name			do_html_admin_iframe_dashboard_banner()
* 	@description	Writes the html code to display Bugs Go Viral iframe in Admin Dashboard
* 	@param 			$width = iframe width
* 	@param 			$height = iframe height
*
*/
function do_html_admin_iframe_dashboard_banner( $width="700" , $height="200") {
	# $iframe_src = "http://charleshoman.com";
	# $noiframe_client_href = "http://charleshoman.com";
	# $noiframe_client_img_src = "http://charleshoman.com";
	$iframe_src = "http://bugsgoviral.net/broadcast/wp_plugin/index.html?iframe=1&context=dashboard_banner";
	$noiframe_client_href = "http://bugsgoviral.net/broadcast/wp_plugin/index.html?iframe=0&context=dashboard_banner";
	$noiframe_client_img_src = "http://bugsgoviral.net/broadcast/wp_plugin/images/noiframe_dashboard_bannner.jpg";
	 $data_likes= file_get_contents($serverurl."index.php?product_id=".$key."&fb_edit_action=returnLikes");
	$arr_likes = explode("##",$data_likes);
    if($arr_likes[0]>0)
    $likes = $arr_likes[0];
    else
    $likes = $res->likes;
	$installs = $arr_likes[1];
	echo "<table width='100%'>
	<tr>
	<th>Total Likes</th>
	<th>Total Participants</th>
	<th>Top Influencers</th>
	<th>Total Installs</th>
	</tr>
	<tr>
		<td>".$likes."</td>
		
		<td>".$res->participants."</td>
		
		<td>".$res->likes."</td>
		<td>".$installs."</td>
		
	</tr>
	</table>";
}
/**
*	@name			do_html_admin_iframe_b_hive()
* 	@description	Writes the html code to display B(gv) Hive iframe in Admin Dashboard
* 	@param 			$width = iframe width
* 	@param 			$height = iframe height
*
*/
function do_html_admin_iframe_b_hive( $width="700" , $height="500") {
	# $iframe_src = "http://charleshoman.com";
	# $noiframe_client_href = "http://charleshoman.com";
	# $noiframe_client_img_src = "http://charleshoman.com";
	$iframe_src = "http://bugsgoviral.net/broadcast/wp_plugin/index.html?iframe=1&context=b_hive";
	$noiframe_client_href = "http://bugsgoviral.net/broadcast/wp_plugin/index.html?iframe=0&context=b_hive";
	$noiframe_client_img_src = "http://bugsgoviral.net/broadcast/wp_plugin/images/noiframe_b_hive.jpg";
	echo '
	<iframe src="'  .  $iframe_src  .  '" width="'  .  $width  .  '" height="'  .  $height  .  '" scrolling="no">
		<a href="'  .  $noiframe_client_href  .  '">
		<img src="'  .  $noiframe_client_img_src  .  '" width="'  . $width .  '" height="'  . $height .  '">'.  $noiframe_client_img_src  . '</a>
	</iframe>';
}

/**
*	@name			do_html_admin_dashboard_main($id,$class)
* 	@description	Writes the html code to display content for Main div Admin Dashboard
* 	@param 			$id = link friendly page name slug (i.e. "dasboard_main")
* 	@param 			$class = link friendly div class (i.e. "content)
*  	@param 			$headline = Human readable title for content to display  (i.e. "Headline for Text")
*  	@param 			$sub_head = Human readable name to display in header bar  (i.e. Subheader for text")
* 	@param 			$val = print subhead, 1 = print subhead, 0 = do not print subhead
*/
function do_html_admin_dashboard_main($id="id",$class="class", $headline="headline", $subhead="subhead",$val="0")  {
	echo '
	<div id="'  .  $id  .  '_'  .  $class  .  '" class="'  .  $class  .  '">
	<h1 id="'  .  $id  .  '_headline" class="headline">'  .  $headline  .  '</h1><br>';

	if ($val == '1') {
		echo '
			<p id="'  .  $id  .  '_subhead" class="subhead">'  .  $subhead  .  '</p>';
	}
	$key = get_option("key");
	echo '
<table>
	<tr>
		<td><a href="admin.php?page=fbm_options"><img src="'  .  admin_images_url()  .  '/icons_dashboard_fbappsettings.jpg"></a></td>
		<td><a href="admin.php?page=fbm_create_promo"><img src="'  .  admin_images_url()	.  '/icons_dashboard_createpromo.jpg"></a></td>
		<td><a href="'.get_option("serverurl").'index.php?fb_edit_action=manage_account&product_id='.$key.'" target="_blank"><img src="'  .  admin_images_url()	.  '/icons_myaccount.jpg"></a></td>

	</tr>
</table>
<table>
	<tr>
		<td><a href="admin.php?page=fbm_edit_promo"><img src="'  .  admin_images_url()	.  '/icons_dashboard_editactpromo.jpg"></a></td>
	
		<td><a href="admin.php?page=fbm_admin_b_hive"><img src="'  .	admin_images_url()	.  '/icons_dashboard_bgvhive.jpg"></a></td>
	</tr>
</table>
</div>
<!-- DIV END admin_dashboard_main -->';
}


