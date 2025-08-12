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
// OUTPUT
Header("Content-type: image/jpeg");
$exp=GMDate("D, d M Y H:i:s",time()+999);
Header("Expires: $exp GMT");
if (!pa_image_add_logo) {
	// READ IMAGE FROM FILE
	$fp = fopen($img_path, "rb");
	while(!feof($fp)) {
		$buf = fread($fp, 4096);
		echo $buf;
		$bytesSent+=strlen($buf);    /* We know how many bytes were sent to the user */
	}
} else {
	// GENERATE IMAGE...
	// TO-DO !!!
	$bgc["r"]=hexdec(substr(pa_thumb_background,0,2));
	$bgc["g"]=hexdec(substr(pa_thumb_background,2,2));
	$bgc["b"]=hexdec(substr(pa_thumb_background,4,2));
	
}
?>