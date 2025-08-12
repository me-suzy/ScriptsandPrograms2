<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/
if (strstr($_GET["dir"], "..")) die();
if (strstr($_GET["img"], "..")) die();
chdir("..");
include_once "./pa_config.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();
$img_path = pa_image_dir . $_GET["dir"] . "/" . $_GET["img"];
$thumb_file = pa_image_dir . $_GET["dir"] . "/" . pa_thumb_prefix . $_GET["img"];
$use_stored_thumb = false;
if (file_exists($thumb_file)) {
	$filemtime_thumb=filemtime($thumb_file);
	$filemtime_file=filemtime($img_path);
	if (($filemtime_file < $filemtime_thumb) && (pa_delete_old_thumbs < $filemtime_thumb)) {
		$use_stored_thumb = true;
	}
}
// OUTPUT
Header("Content-type: image/jpeg");
$exp=GMDate("D, d M Y H:i:s",time()+999);
Header("Expires: $exp GMT");
if ($use_stored_thumb) {
	// READ THUMB FROM FILE
	$fp = fopen($thumb_file, "rb");
	while(!feof($fp)) {
		$buf = fread($fp, 4096);
		echo $buf;
		$bytesSent+=strlen($buf);    /* We know how many bytes were sent to the user */
	}
} else {
	// GENERATE THUMB...
	$bgc["r"]=hexdec(substr(pa_thumb_background,0,2));
	$bgc["g"]=hexdec(substr(pa_thumb_background,2,2));
	$bgc["b"]=hexdec(substr(pa_thumb_background,4,2));
	if (function_exists("imagecreatetruecolor")){
		if (!@$img=imagecreatetruecolor(pa_image_show_thumb_size, pa_image_show_thumb_size)){
			$img=imagecreate(pa_image_show_thumb_size, pa_image_show_thumb_size);
		}
	} else {
		$img=imagecreate(pa_image_show_thumb_size, pa_image_show_thumb_size);
	}
	$thumb_dir_color=imagecolorallocate($img,$bgc["r"],$bgc["g"],$bgc["b"]);
	imagefilledrectangle($img,0,0,pa_image_show_thumb_size,pa_image_show_thumb_size,$thumb_dir_color);
	@$info=getimagesize($img_path);
	if ($info[2]==1){
		$img_in=@imagecreatefromgif($img_path);
	} else if ($info[2]==2){
		$img_in=@imagecreatefromjpeg($img_path);
	} else if ($info[2]==3){
		$img_in=@imagecreatefrompng($img_path);
	}
	$new_width = pa_image_show_thumb_size;
	$new_height = pa_image_show_thumb_size;
	$newx=1;
	$newy=1;
	if ($info[0]<$info[1]){
		@$new_width = floor($info[0]/($info[1]/pa_image_show_thumb_size)); // kvuli zarovnani velikostnich pomeru 
		@$newx = (pa_image_show_thumb_size-$new_width)/2;// kvuli vycentrovani 
	} else {
		@$new_height = floor($info[1]/($info[0]/pa_image_show_thumb_size)); // kvuli zarovnani velikostnich pomeru 
		@$newy = (pa_image_show_thumb_size-$new_height)/2;// kvuli vycentrovani 
	}
	if (pa_thumb_resample){
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
	if (pa_image_save_thumb){
		@ImageJpeg($img,$thumb_file,pa_thumb_quality);
	}
	ImageJpeg($img,"",pa_thumb_quality);
}

?>