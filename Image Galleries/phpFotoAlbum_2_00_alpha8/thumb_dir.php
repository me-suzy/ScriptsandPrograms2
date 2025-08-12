<?php
session_start();
@include "./func.php";
@include "./skin/" . $_SESSION["s_data"]["skin"] . "/colors.php";
Header("Content-type: image/jpeg");
$exp=GMDate("D, d M Y H:i:s",time()+999);
Header("Expires: $exp GMT");
if (function_exists("imagecreatetruecolor")){
	if (!@$img=imagecreatetruecolor(100,100)){
		$img=imagecreate(100,100);
	}
} else {
	$img=imagecreate(100,100);
}
if (($thumb_text_color["r"]==$thumb_dir_color["r"]) && ($thumb_text_color["g"]==$thumb_dir_color["g"]) && ($thumb_text_color["b"]==$thumb_dir_color["b"])){
	$thumb_text_color=imagecolorallocate($img,0,0,0);
	$thumb_dir_color=imagecolorallocate($img,255,255,255);
} else {
	$thumb_text_color=imagecolorallocate($img,$thumb_text_color["r"],$thumb_text_color["g"],$thumb_text_color["b"]);
	$thumb_dir_color=imagecolorallocate($img,$thumb_dir_color["r"],$thumb_dir_color["g"],$thumb_dir_color["b"]);
}
imagefilledrectangle($img,0,0,99,99,$thumb_dir_color);
$img_in=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/thumb_dir.png");
@imagecopy ($img,$img_in,5,15,0,0,32,32);
imagestring($img,3,5,75,URLDecode($_GET["text"]),$thumb_text_color);
ImageJpeg($img,"",$thumb_quality);
?>