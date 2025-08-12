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
chdir("..");
include_once "./pa_config.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();
$dir = pa_image_dir . $_GET["dir"] . "/";
$files = $pa_core->GetFiles($dir);
if (is_array($files)){
	foreach ($files as $file){
		$img_info = @getimagesize($dir . $file["name"]);
		echo $file["name"];
		echo ";";
		echo $img_info[0];
		echo ";";
		echo $img_info[1];
		echo ";";
		echo $file["time"];
		echo "\n";
	}
}
?>