<?php
$maxheight=100;
$url=$_GET['url'];
$ext=explode('.',$url);
switch ($ext[1]){
        case "jpg":
        $new=imagecreatefromjpeg($url);
        break;
        case "gif":
        $new=imagecreatefromgif($url);
        break;
        case "png":
        $new=imagecreatefrompng($url);
        break;
}
$width=imagesx($new);
$height=imagesy($new);
$scale=$maxheight/$height;
$newwidth=$scale*$width;
$newheight=$scale*$height;
$tmp_img = imagecreatetruecolor($newwidth, $newheight);
imagecopyresized($tmp_img, $new, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagedestroy($new);
        $new=$tmp_img;
        header("Content-type: image/jpeg");
        imagejpeg($new);
        ?>
