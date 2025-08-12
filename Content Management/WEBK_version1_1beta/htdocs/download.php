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

	// proxy.php : webk proxy hub

	require_once("../webk/include/init.php");
	
	
	
	// handle downloads
	if (isset($_GET["file"])) {
		$f = $_GET["file"];
		$f = explode("/",$f);

		$filename = $f[count($f)-1];
		$filepath = "";
		for ($i=0;$i<(count($f)-1);$i++) $filepath .= $f[$i]."/";
		$filepath = str_replace("..","",$filepath);
		if ($filename == "") die();
		if ($filename[0] == ".") die();
		if ($filename[0] == "!") die();
		if ($filename[0] == "@") die();
		
		
		if (file_exists(PATH_ROOT.$filepath.$filename)) {

			set_time_limit(0);
			$filetype = explode(".",$filename);
			$filetype = $filetype[1];
			
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Pragma: public");
      		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			
			$ctype = "application/octet-stream";
		
			WEBK_GetHits_FILE(true,PATH_ROOT.$filepath,$filename);
	
			if (function_exists("WEBK_download_".strtoupper($filetype)))
			{
				eval("WEBK_download_".strtoupper($filetype)."('".$filepath."','".$filename."');");
				die();
			}		
	
			header("Content-Type: $ctype");
			header("Content-Disposition: attachment; filename=".str_replace(" ","_",$filename)."");
					
			$fd = fopen(PATH_ROOT.$filepath.$filename,"rb");
			while(!feof($fd))
			{
				echo fread($fd,4096);
			}
			fclose($fd);
				
						
			die();
		}
	}
	
	

?>
