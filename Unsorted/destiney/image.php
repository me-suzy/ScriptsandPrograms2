<?php
include("./admin/config.php");
include("$include_path/common.php");

if(!isset($_GET['id'])){
	header("Location: $base_url/");
	exit();
}

$sql = "
	select
		average_rating,
		image_ext,
		concat(id,'.',image_ext) as image
	from
		$tb_users
	where
		id = '$_GET[id]'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	$ext="";

	switch($array["image_ext"]){
		case "jpg":
		case "jpeg":
		case "JPG":
		case "JPEG":
			$ext = "jpeg";
			break;
		case "png":
		case "PNG":
			$ext = "png";
			break;
		case "gif":
		case "GIF":
			$ext = "gif";
			break;
	}

	$image = $image_path . "/" . $array["image"];
	$avg = $array["average_rating"] == "10.0000" ? "10.000" : $array["average_rating"];

	if($watermark_images){

		switch($ext){
			case "jpg":
			case "jpeg":
			case "JPG":
			case "JPEG":
				$im = imagecreatefromjpeg($image);
				break;
			case "png":
			case "PNG":
				$im = imagecreatefrompng($image);
				break;
			case "gif":
			case "GIF":
				$im = imagecreatefromgif($image);
				break;
		}

		$height = imagesy($im);
		$width = imagesx($im);
		$fg = imagecolorresolve($im, $watermark_fg_color_r, $watermark_fg_color_g, $watermark_fg_color_b);
		$bg = imagecolorresolve($im, $watermark_bg_color_r, $watermark_bg_color_g, $watermark_bg_color_b);
		$shadow = imagecolorresolve($im, $watermark_shadow_color_r, $watermark_shadow_color_g, $watermark_shadow_color_b);

		if($width >= 170){
			imagefilledrectangle($im, $width - 52, $height - 24, $width - 6, $height - 6, $shadow);
			imagefilledrectangle($im, $width - 53, $height - 25, $width - 7, $height - 7, $bg);
			imagerectangle($im, $width - 53, $height - 7, $width - 7, $height - 25, $fg);
			imagettftext($im, 9, 0, $width - 48, $height - 10, $shadow, "$font_path/arial.ttf", $avg);
			imagettftext($im, 9, 0, $width - 49, $height - 11, $fg, "$font_path/arial.ttf", $avg);
		}
		
		imagefilledrectangle($im, 7, $height - 24, 85, $height - 6, $shadow);
		imagefilledrectangle($im, 6, $height - 25, 84, $height - 7, $bg);
		imagerectangle($im, 6, $height - 7, 84, $height - 25, $fg);
		imagettftext($im, 9, 0, 10, $height - 11, $shadow, "$font_path/arial.ttf", $site_title);
		imagettftext($im, 9, 0, 9, $height - 12, $fg, "$font_path/arial.ttf", $site_title);
		header("Content-type: image/" . $ext);

		switch($ext){
			case "jpg":
			case "jpeg":
			case "JPG":
			case "JPEG":
				imagejpeg($im, '', 97);
				break;
			case "png":
			case "PNG":
				imagepng($im, '', 97);
				break;
			case "gif":
			case "GIF":
				imagegif($im, '', 97);
				break;
		}
		
		imagedestroy($im);

} else {

	readfile($image);

}
?>