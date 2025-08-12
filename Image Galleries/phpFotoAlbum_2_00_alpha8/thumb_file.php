<?php
session_start();
include "./func.php";
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
$thumb_dir_color=imagecolorallocate($img,$thumb_dir_color["r"],$thumb_dir_color["g"],$thumb_dir_color["b"]);
$barva=ImageColorAllocate($img,0,0,0);
$barva2=ImageColorAllocate($img,255,255,255);
imagefilledrectangle($img,0,0,100,100,$thumb_dir_color);
$file="./_images" . $_SESSION["s_data"]["dir"] . $_GET["file"];
@$info=getimagesize($file);
if ($info[2]==1){
	$img_in=@imagecreatefromgif($file);
	$img_in2=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/list_gif.png");
} else if ($info[2]==2){
	$img_in=@imagecreatefromjpeg($file);
	$img_in2=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/list_jpg.png");
} else if ($info[2]==3){
	$img_in=@imagecreatefrompng($file);
	$img_in2=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/list_png.png");
} else {
	$img_in2=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/list_file.png");
}
$new_width=100;
$new_height=100;
$newx=1;
$newy=1;
if ($info[0]<$info[1]){
	@$new_width = floor($info[0]/($info[1]/100)); // kvuli zarovnani velikostnich pomeru 
	@$newx = (100-$new_width)/2;// kvuli vycentrovani 
} else {
	@$new_height = floor($info[1]/($info[0]/100)); // kvuli zarovnani velikostnich pomeru 
	@$newy = (100-$new_height)/2;// kvuli vycentrovani 
}
if ($thumb_resample){
	if (function_exists("imagecopyresampled")){
		if (!@imagecopyresampled($img,$img_in,$newx,$newy,1,1,$new_width,$new_height,$info[0],$info[1])){
			@imagecopyresized($img,$img_in,$newx,$newy,1,1,$new_width,$new_height,$info[0],$info[1]);
		}
	} else {
		@imagecopyresized($img,$img_in,$newx,$newy,1,1,$new_width,$new_height,$info[0],$info[1]);
	}
} else {
	@imagecopyresized($img,$img_in,$newx,$newy,1,1,$new_width,$new_height,$info[0],$info[1]);
}
@imagecopy ($img,$img_in2,2,2,0,0,16,16);
unset($img_in);
unset($img_in2);
if ($thumb_show_info==2){
	imagefilledrectangle($img,1,77,98,86,$barva2);
}
if (($thumb_show_info==2) || ($thumb_show_info==1)){
	imagefilledrectangle($img,1,89,98,97,$barva2);
}
if (!empty($info[0])){
	$text=cz2en($_GET["file"]);
	$text2=cz2en($info[0] . "x" . $info[1] . ", " . ShowSize(filesize($file)));
	if ($thumb_show_info==2){
		imagestring($img,1,5,78,$text,$barva);
		imagestring($img,1,5,90,$text2,$barva);
	}
	if ($thumb_show_info==1){
		imagestring($img,1,5,90,$text,$barva);
	}
} else {
	$img_in=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/thumb_file.png");
	@imagecopy ($img,$img_in,34,34,0,0,32,32);
	if ($thumb_show_info==2){
		imagestring($img,1,5,78,cz2en(URLDecode($_GET["file"])),$thumb_text_color);
		imagestring($img,1,5,90,ShowSize(filesize($file)),$thumb_text_color);
	}
	if ($thumb_show_info==1){
		imagestring($img,1,5,90,cz2en(URLDecode($_GET["file"])),$thumb_text_color);
	}
}
$thumb_file=$file . "_thumb.jpg";
if ($image_save_thumb){
  @ImageJpeg($img,$thumb_file,$thumb_quality);
}
ImageJpeg($img,"",$thumb_quality);
?>