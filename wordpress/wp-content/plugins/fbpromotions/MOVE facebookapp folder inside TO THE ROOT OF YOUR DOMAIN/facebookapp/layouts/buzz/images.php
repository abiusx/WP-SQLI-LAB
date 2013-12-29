<?php
header("Content-type: image/png");
$im = @imagecreate($_GET['width'], $_GET['height'])
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 255, 255, 255);
imagestring($im, 2, 5, 5,  $_GET['txt'], $text_color);
imagepng($im);
imagedestroy($im);
?>
