<?php
// The file
$filename = $_REQUEST['file'];

// Set a maximum height and width
$width = 405;
$height = 232;

// Content type
header('Content-Type: image/jpeg');

// Get new dimensions
list($width_orig, $height_orig) = getimagesize($filename);


$ratio_orig = $width_orig/$height_orig;

if ($width/$height > $ratio_orig) {
   $width = 405;
} else {
   $height = $width/$ratio_orig;
}
// Resample
$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
imagejpeg($image_p, null, 100);
?>