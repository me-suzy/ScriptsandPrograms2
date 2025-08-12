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
$file="./_images" . $_SESSION["s_data"]["dir"] . urldecode($_GET["text"]) . "/";
$old_dir=$_SESSION["s_data"]["dir"];
$_SESSION["s_data"]["dir"].=urldecode($_GET["text"]) . "/";
GetDir($dirs,$files,$files_size);
$_SESSION["s_data"]["dir"]=$old_dir;
$sort_opt=$_SESSION["s_data"]["sort"] . "sort_" . $_SESSION["s_data"]["sort2"];
@usort($files,$sort_opt);
if (!empty($files[0]["name"])) $file.=$files[0]["name"];
if ( (file_exists($file)) && (!is_dir($file)) ){
	$txtdebug=" - found...";
	@$info=getimagesize($file);
	if ($info[2]==1){
		$img_in=@imagecreatefromgif($file);
	} else if ($info[2]==2){
		$img_in=@imagecreatefromjpeg($file);
	} else if ($info[2]==3){
		$img_in=@imagecreatefrompng($file);
	} else {
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
	unset($img_in);
}
$img_in=@imagecreatefrompng($home_url . "/skin/" . $_SESSION["s_data"]["skin"] . "/thumb_dir.png");
@imagecopy ($img,$img_in,5,15,0,0,32,32);
imagefilledrectangle($img,1,74,98,88,$thumb_dir_color);
imagestring($img,3,5,75,URLDecode($_GET["text"]),$thumb_text_color);
$thumb_file="./_images" . $_SESSION["s_data"]["dir"] . urldecode($_GET["text"]) . "_thumb.jpg";
if ($image_save_thumb){
  @ImageJpeg($img,$thumb_file,$thumb_quality);
}
ImageJpeg($img,"",$thumb_quality);
?>