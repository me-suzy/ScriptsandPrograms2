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

// action: RSS, render your favorite rss feed


function WEBK_action_RSS($item)
{
	$f = fopen(PATH_ROOT.$item["path"].$item["name"],"r");
	$URL = fgets($f,4096);
	$URL = str_replace("\n","",$URL);
	$URL = str_replace("\r","",$URL);
	fclose($f);
	
	require_once(PATH_INCLUDE."thirdparty/lastRSS.php");
	$rss = new lastRSS; 
	$TPL = new phemplate(PATH_TEMPLATE);

	// setup transparent cache
	if (!is_dir(PATH_ROOT.$item["path"].".meta/.rss_cache/")) mkdir(PATH_ROOT.$item["path"].".meta/.rss_cache/");
	$rss->cache_dir = PATH_ROOT.$item["path"].".meta/.rss_cache/";
	$rss->cache_time = 3600; // one hour
	
	if ($rs = $rss->get($URL)) {
	// here we can work with RSS fields
		$TPL->set_var("title",$rs['title']);
		$content = "";
		for ($i=0;$i<count($rs['items']);$i++)
		{
			if ($i==5) break;
			
			$item = $rs['items'][$i];
			$content .= "<a style=\"font-size:9pt;font-family:sans-serif\" href=\"".$item['link']."\" target=\"_NEW\">".$item['title']."</a>
			<br><br>";
		}
		
		$TPL->set_var("content",$content);
	}
	else {
	return "<b style=\"color:red\">Error: RSS file not found...</b>";
	}

	
	$TPL->set_file("page","action/rss.html");


	
		
		
	$content = $TPL->process("","page",2);

	return $content;
}


?>