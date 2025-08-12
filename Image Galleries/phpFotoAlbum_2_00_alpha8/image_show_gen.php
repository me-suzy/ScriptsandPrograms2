<?php
session_start();
include "./func.php";
@include "./skin/" . $_SESSION["s_data"]["skin"] . "/colors.php";
Header("Content-type: image/jpeg");
$exp=GMDate("D, d M Y H:i:s",time()+999);
Header("Expires: $exp GMT");
$file="./_images" . $_SESSION["s_data"]["dir"] . $_SESSION["s_data"]["file"];
@$info=getimagesize($file);
if ($_SESSION["s_data"]["res"]=="orig"){
	$resx=$info[0];
	$resy=$info[1];
} else {
	List($resx,$resy)=Explode("x",$_SESSION["s_data"]["res"]);
}
if (function_exists("imagecreatetruecolor")){
	if (!@$img=imagecreatetruecolor($resx,$resy)){
		$img=imagecreate($resx,$resy);
	}
} else {
	$img=imagecreate($resx,$resy);
}
if (($thumb_text_color["r"]==$thumb_dir_color["r"]) && ($thumb_text_color["g"]==$thumb_dir_color["g"]) && ($thumb_text_color["b"]==$thumb_dir_color["b"])){
	$thumb_text_color=imagecolorallocate($img,0,0,0);
	$thumb_dir_color=imagecolorallocate($img,255,255,255);
} else {
	$thumb_text_color=imagecolorallocate($img,$thumb_text_color["r"],$thumb_text_color["g"],$thumb_text_color["b"]);
	$thumb_dir_color=imagecolorallocate($img,$thumb_dir_color["r"],$thumb_dir_color["g"],$thumb_dir_color["b"]);
}
imagefilledrectangle($img,0,0,$resx,$resy,$thumb_dir_color);
if ($info[2]==1){
	$img_in=@imagecreatefromgif($file);
} else if ($info[2]==2){
	$img_in=@imagecreatefromjpeg($file);
} else if ($info[2]==3){
	$img_in=@imagecreatefrompng($file);
}
$new_width=$resx;
$new_height=$resy;
$newx=1;
$newy=1;
if ($info[0]<$info[1]){
	@$new_width = floor($info[0]/($info[1]/$resy)); // kvuli zarovnani velikostnich pomeru 
	@$newx = ($resx-$new_width)/2;// kvuli vycentrovani 
} else {
	@$new_height = floor($info[1]/($info[0]/$resx)); // kvuli zarovnani velikostnich pomeru 
	@$newy = ($resy-$new_height)/2;// kvuli vycentrovani 
}
if ($_SESSION["s_data"]["res"]=="orig"){
	@imagecopy ($img,$img_in,0,0,0,0,$resx,$resy);
} else {
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
}
$img_in=@imagecreatefrompng($home_url . "/skin/-shared-/logo.png");
@imagecopy ($img,$img_in,$resx/2-60,$resy-20,0,0,120,20);
ImageJpeg($img,"",$_SESSION["s_data"]["quality"]);