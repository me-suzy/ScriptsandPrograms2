<?
$image = exif_thumbnail($path);  //php's implementation
Header("Content-type: image/jpeg");
echo $image;
?>
