<?
$filename = $_GET["filename"];
if($filename == '')return;
$max_size = (isset($_GET["size"]))?$_GET["size"]:150;
$size = getimagesize($filename);
$w = $size[0];
$h = $size[1];
$tmp=0;
if($w >= $h)
	$tmp = $w/$max_size;
else
	$tmp = $h/$max_size;

$image = imagecreate($w/$tmp,$h/$tmp);

if(stristr($filename,".png")){
	$img_old = imagecreatefrompng($filename);
	imagecopyresized($image,$img_old,0,0,0,0,$w/$tmp,$h/$tmp,$w,$h);
	header("Content-type: image/png");
	imagepng($image);
}elseif(stristr($filename,".jpg")){
 $im=@imagecreatefromjpeg($filename);                // path to your gallery
   $small = imagecreatetruecolor($w/$tmp,$h/$tmp);    // new image
   ImageCopyResampled($small, $im, 0, 0, 0, 0, $w/$tmp, $h/$tmp, $w,$h);
	header("Content-type: image/jpeg");
	imagejpeg($small);
   ImageDestroy($im);  	
}elseif(stristr($filename,".gif")){
	$img_old = imagecreatefromgif($filename);
	imagecopyresized($image,$img_old,0,0,0,0,$w/$tmp,$h/$tmp,$w,$h);
	header("Content-type: image/gif");
	imagegif($image);
}
?>
