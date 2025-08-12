<?php

//---------------------------------------------------------------------------//
// author: Yanick Bourbeau <ybourbeau@mrgtech.ca> 
// date:   2005.09.30
// web:    http://mrgibson.zapto.org
// info:   WEBK
//---------------------------------------------------------------------------//
// copyleft license
//
// this software is provided 'as-is', without any express or implied
// warranty. in no event will the authors be held liable for any damages
// arising from the use of this software.
//
// permission is granted to anyone to use this software for any purpose,
// including commercial applications, and to alter it and redistribute it
// freely, subject to the following restrictions: 
//
// 1. the origin of this software must not be misrepresented;
//	  you must not claim that you wrote the original software. 
//	  if you use this software in a product, an acknowledgment 
//	  in the product documentation would be appreciated but is not required.
//
// 2. altered source versions must be plainly marked as such, 
//	  and must not be misrepresented as being the original software.
//
// 3. mail about the fact of using this class in production 
//	  would be very appreciated.
//
// 4. this notice may not be removed or altered from any source distribution.
//
//---------------------------------------------------------------------------//


function WEBK_preview_TXT($path,$file)
{
	$filename = $path."/".$file;
	$fd = fopen($filename,"rb");
	$filedata = fread($fd,filesize($filename));
	fclose($fd);
	$filedata = str_replace("\r\n","\n",$filedata);
	$filedata = str_replace("\r","\n",$filedata);
	$filedata = explode("\n",$filedata);


	$imgfile = $path.".meta/.image_".md5($file)."_".filesize($path.$file);
	$img = imagecreatetruecolor(96,72);
	
	$color_white = imagecolorallocate($img,255,255,255);
	$color_black = imagecolorallocate($img,0,0,0);

	imagefill($img,0,0,$color_black);
	imagefilledrectangle($img,1,1,94,70,$color_white);
	for ($i=0;$i<count($filedata);$i++)
		imagestring($img,2,4,$i*12,$filedata[$i],$color_black);

	imagejpeg($img,$imgfile,90);
	imagedestroy($img);

}


// some aliases
function WEBK_preview_ASC($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_NFO($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_DIZ($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_C($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_CPP($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_H($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_HPP($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_CXX($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_PHP($path,$file) { WEBK_preview_TXT($path,$file); } 
function WEBK_preview_FL($path,$file) { WEBK_preview_TXT($path,$file); } 


?>
