<?php
/* 
Bio Image Uploader
*/
?>
<?php

require_once('../library/config.php');

$id = 0;
if(isset($_POST['id'])) {
	$id=$_POST['id'];
}
else if (isset($_GET['id'])) {
	$id=$_GET['id'];
}

global $wpdb;
if($_FILES['userfile']['size'] != 0) {
	profilesCropImage(intval(get_option('profiles_image_width')), intval(get_option('profiles_image_height')), $_FILES['userfile']['tmp_name'], 'jpg', $_FILES['userfile']['tmp_name']);
	if (get_option('profiles_image_watermark_add') == 'true') {
		profilesAddWatermark($_FILES['userfile']['tmp_name']);
	}
	$query = "UPDATE ".$wpdb->prefix."bios SET photo='".addslashes(file_get_contents($_FILES['userfile']['tmp_name']))."' WHERE uid='$id'";
	mysql_query($query) or die(mysql_error());
}
$result = mysql_query("SELECT * FROM ".$wpdb->prefix."people WHERE id='$id'");
$row = mysql_fetch_array($result);
?>
<html>
<head>
<title>Peple Image Updater</title>
</head>
<body>
<h1>File Uploader</h1>
<h2>Upload a New Photo for: </h2>
<h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "$row[first_name] $row[last_name]"; ?></h3>
<p>Current Photo: <img src="../library/bio-img.php?uid=<?php echo "$id"; ?>" /></p>
<div style="clear: both">&nbsp;</div>
<?php if (!isset($_POST['change'])) { ?>
	<p>Please select a <?php echo get_option('profiles_image_width')."x".get_option('profiles_image_height'); ?> pixel jpeg (.jpg or .jpeg) image to upload. If the image does not meet the required dimensions, it will be resized. Only upload .jpg or .jpeg images.</p>
	<p>Close this window when done.</p>
	<form enctype="multipart/form-data" action="image-uploader.php" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
	<input type="hidden" name="id" id="id" value="<?php echo "$row[id]"; ?>" />
	<input type="hidden" name="change" id="change" value="true" />
	Choose a file to upload: <input name="userfile" type="file" /><br />
	<input type="submit" value="Upload File" />
	</form>
<?php } else { ?>
	<p><strong>Photo updated, please close this window</strong></p>
	<form action="image-uploader.php" method="POST">
	<input type="hidden" name="id" id="id" value="<?php echo "$row[id]"; ?>" />
	<input type="submit" value="Upload A New Photo" />
	</form>
<?php } ?>
</body>
</html>
<?php
function profilesCropImage($nw, $nh, $source, $stype, $dest) {
	$size = getimagesize($source);
	$w = $size[0];
	$h = $size[1];
	switch($stype) {
		case 'gif':
		$simg = imagecreatefromgif($source);
		break;
		case 'jpg':
		$simg = imagecreatefromjpeg($source);
		break;
		case 'png':
		$simg = imagecreatefrompng($source);
		break;
	}
	$dimg = imagecreatetruecolor($nw, $nh);
	$wm = $w/$nw;
	$hm = $h/$nh;
	$h_height = $nh/2;
	$w_height = $nw/2;
	if($w> $h) {
		$adjusted_width = $w / $hm;
		$half_width = $adjusted_width / 2;
		$int_width = $half_width - $w_height;
		imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
	} elseif(($w <$h) || ($w == $h)) {
		$adjusted_height = $h / $wm;
		$half_height = $adjusted_height / 2;
		$int_height = $half_height - $h_height;
		imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
	} else {
		imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
	}
	imagejpeg($dimg,$dest,100);
}
function profilesAddWatermark($image) {
	$img = imagecreatefromjpeg($image);
	$size = getimagesize($image);
	$w = $size[0];
	$h = $size[1];
	$text = get_option('profiles_image_watermark_text');
	$font = "../fonts/".get_option('profiles_image_watermark_font').".ttf";
	$point = intval(get_option('profiles_image_watermark_size'));
	$size = imagettfbbox($point, 0, $font, $text) or die("Cannot find font\n$font");
	$dx = $size[2] - $size[0];
	$dy = $size[1] - $size[7];
	$colors = explode("#",get_option('profiles_image_watermark_color'));
	$forecolor[0] = intval(substr($colors[0],0,2),10);
	$forecolor[1] = intval(substr($colors[0],2,2),10);
	$forecolor[2] = intval(substr($colors[0],4,2),10);
	$backcolor[0] = intval(substr($colors[1],0,2),10);
	$backcolor[1] = intval(substr($colors[1],2,2),10);
	$backcolor[2] = intval(substr($colors[1],4,2),10);
	$fore = imagecolorallocate($img, $forecolor[0], $forecolor[1], $forecolor[2]);
	$back = imagecolorallocate($img, $backcolor[0], $backcolor[1], $backcolor[2]);
	imagettftext($img, $point, 0, intval($w - ($dx + 6)), intval($h - ($dy - 2)), $back, $font, $text);
	imagettftext($img, $point, 0, intval($w - ($dx + 7)), intval($h - ($dy)), $fore, $font, $text);
	imagejpeg($img,$image,100);
}
?>