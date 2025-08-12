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

function WEBK_download_CPP($path,$file)
{
	$fullpath = PATH_ROOT.$path."/".$file;
	$fd = fopen($fullpath,"rb");
	$filedata = fread($fd,filesize($fullpath));
	
	$tpl = new phemplate(PATH_TEMPLATE);
	$tpl->set_file("page","download/cpp.html");

	$tpl->set_var("title",$file);

	$filedata = str_replace("\r\n","\r",$filedata);
	$filedata = str_replace("\r","\n",$filedata);


	$filedata = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",htmlentities($filedata));

	$filedata = str_replace(" ","&nbsp;",$filedata);


	$filedata = explode("\n",$filedata);
	
	$lines = array();
	
	for ($i=0;$i<count($filedata);$i++)
	{
		$bgcolor1 = ($i%2==0?"#707070":"#808080");
		$bgcolor2 = ($i%2==0?"#eaeaea":"#f0f0f0");

		$lines[] = array("number"=>$i,"data"=>$filedata[$i],"bgcolor1"=>$bgcolor1,"bgcolor2"=>$bgcolor2);
	}


	$tpl->set_loop("lines",$lines);
	print $tpl->process("","page",2);

}


// aliases go here
function WEBK_download_CXX($path,$file) { WEBK_download_CPP($path,$file); }
function WEBK_download_HPP($path,$file) { WEBK_download_CPP($path,$file); }
function WEBK_download_C($path,$file) { WEBK_download_CPP($path,$file); }
function WEBK_download_PHP($path,$file) { WEBK_download_CPP($path,$file); }
function WEBK_download_H($path,$file) { WEBK_download_CPP($path,$file); }


?>
