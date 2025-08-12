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
	$clock_start = microtime(true);
	

	// proxy.php : webk proxy hub

	require_once("../webk/include/init.php");
	
	
	if (isset($_GET["down"])) {
			$down = explode("/",$_GET["down"]);
			$PATH = "";
			for ($i=0;$i<(count($down)-2);$i++)
			{
				$PATH .= $down[$i]."/";
			}

			
			$_GET["folder"] = $PATH;
	}
	
	
	if (!isset($_GET["folder"])) 
	{
		$PATH = "";
	} else {
		$PATH = $_GET["folder"];
		if ($PATH != "")
		if ($PATH[strlen($PATH)-1] != "/") $PATH .= "/";
		$PATH = str_replace("..","_",$PATH);
		$PATH = str_replace("|","_",$PATH);
		
		if (!file_exists(PATH_ROOT.$PATH)) {
			header("Location: proxy.php");
			die("<script>window.location='proxy.php';</script>");
		}

		if (!is_dir(PATH_ROOT.$PATH)) {
			header("Location: proxy.php");
			die("<script>window.location='proxy.php';</script>");
		}
		
	}

	if (isset($_POST["password"]))
	{
		$_SESSION[$PATH] = $_POST["password"];		
	}

	
	//////////////////////////////////////////
	WEBK_GetHits_FOLDER(true,PATH_ROOT.$PATH);
	
	$files_ROOT = WEBK_FetchFiles(PATH_ROOT);
	$files_PATH = WEBK_FetchFiles(PATH_ROOT.$PATH);
	
	// checking for security 
	$locked = false;
	
	$rpath = explode("/",$PATH);
	$P = "";
	
	for ($i=0;$i<(count($rpath)-1);$i++)
	{
		
		$P = $P.$rpath[$i]."/";
		
	if (file_exists(PATH_ROOT.$P.".meta/.locked"))
	{
		$locked = true;
		
		
		if (isset($_SESSION[$P])) {
			
		$key = file(PATH_ROOT.$P.".meta/.locked");
		$key = $key[0];
		$key = explode("=",$key);
		$key[1] = str_replace("\n","",$key[1]);
		$key[1] = str_replace("\r","",$key[1]);
		
		switch($key[0])
		{
			case "MD5":
			if (md5($_SESSION[$P]) == $key[1]) $locked = false;
			break;
			
			default: // plain
			
			if ($_SESSION[$P] == $key[1]) {
				$locked = false;				
			}
			break;
			
		}
	} else {
		if ($P != $PATH)
		{
			WEBK_Redirect("?folder=$P");
		}
	
	}
	}
		if ($locked) break;
	}
	
	if ($locked)
	{	
	$menu = WEBK_FilterMenu($files_ROOT);
	WEBK_renderPage(str_replace(PATH_ROOT,"",$PATH),$menu,WEBK_renderLocked(),"","",$clock_start);
		
	} else {
	
	$menu = WEBK_FilterMenu($files_ROOT);
	$folders = WEBK_FilterFolders($files_PATH);
	$files = WEBK_FilterFiles($files_PATH);
	$actions_TOP = WEBK_FilterActions_TOP($files_PATH);
	$actions_BOTTOM = WEBK_FilterActions_BOTTOM($files_PATH);
	
	WEBK_renderPage(str_replace(PATH_ROOT,"",$PATH),$menu,
	WEBK_renderActions($actions_TOP).
	WEBK_renderFolders($folders).
	WEBK_renderFiles($files).
	WEBK_renderActions($actions_BOTTOM),WEBK_renderSide("_LEFT"),WEBK_renderSide("_RIGHT"),$clock_start
	);

}

?>
