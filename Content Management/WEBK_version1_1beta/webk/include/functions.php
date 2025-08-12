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

	// functions.php



	/***************************************************/
	// WEBK_GetFiles :: retrieve a list of files
	/***************************************************/	
	function WEBK_FetchFiles($path,$root=PATH_ROOT)
	{
		$files = array();
		
		$dir = opendir($path);
		
		while($file = readdir($dir))
		{
			if ($file[0] == ".") continue;
	
			$item = array();
			$item["path"] = substr($path,strlen(PATH_ROOT));
			$item["name"] = $file;
			
			if ($item["name"] == "Thumbs.db") continue; // windows annoying file
			if ($item["name"] == "_LEFT") continue; // _LEFT reserved word
			if ($item["name"] == "_RIGHT") continue; // _RIGHT reserved word
	
			if ($item["name"][0] == "_") {
				$item["type"] = "menu";
				$item["name"] = substr($item["name"],1);		
				$files[] = $item;
				continue;
			}	
						
			if ($item["name"][0] == "!") {
				$item["type"] = "action_top";
				$item["name"] = substr($item["name"],1);		
				$files[] = $item;
				continue;
			}	

			if ($item["name"][0] == "@") {
				$item["type"] = "action_bottom";
				$item["name"] = substr($item["name"],1);		
				$files[] = $item;
				continue;
			}	

						 
			if (is_dir($path.$file)) {
					$item["type"] = "folder";
					$item["name"] .= "/";
					}
					else
					$item["type"] = "file";
			
			$files[] = $item;
		}
		
		closedir($dir);
	
		sort($files);	
			
		return $files;
	}


	/***************************************************/
	// WEBK_GetMenu :: retrieve the menu from a filelist
	/***************************************************/	
	function WEBK_FilterMenu($files)
	{
		$menus = array();
		
		for ($i=0;$i<count($files);$i++) {
		
			if ($files[$i]["type"] == "menu") {
				
				$menu = array();
				$menu["link"] = "proxy.php?folder=_".$files[$i]["name"]."/";
				$menu["name"] =  $files[$i]["name"];
				$menu["id"] = "menu_$i";				
				$menus[] = $menu;
				
			}	
			
		}
		
		return $menus;
	}


	/***************************************************/
	// WEBK_GetFolders :: retrieve the folders
	/***************************************************/	
	function WEBK_FilterFolders($files)
	{
		$folders = array();
		
		for ($i=0;$i<count($files);$i++) {
		
			if ($files[$i]["type"] == "folder") {
		
				$folder = array();
				$folder["link"] = "proxy.php?folder=".$files[$i]["path"].$files[$i]["name"];
				$folder["name"] = $files[$i]["name"];				
				$folder["path"] = $files[$i]["path"];
				
				$folders[] = $folder;
				
				
			}	
			
		}
		
		return $folders;
	}


	/***************************************************/
	// WEBK_FilterFiles :: retrieve the files
	/***************************************************/	
	function WEBK_FilterFiles($items)
	{
		$files = array();
		for ($i=0;$i<count($items);$i++) {
		
			if ($items[$i]["type"] == "file") {
				
				$file = array();
				$file["link"] = "download.php?file=".urlencode($items[$i]["path"].$items[$i]["name"]);
				$file["name"] = $items[$i]["name"];				
				$file["path"] = $items[$i]["path"];
				$files[] = $file;
				
			}	
			
		}
		
		return $files;
	}

	/***************************************************/
	// WEBK_FilterFiles :: retrieve the files(Actions)
	/***************************************************/	
	function WEBK_FilterActions_TOP($items)
	{
		$files = array();
		
		for ($i=0;$i<count($items);$i++) {
		
			if ($items[$i]["type"] == "action_top") {
					$file = array();
					$file["link"] = "download.php?file=".$items[$i]["path"].$items[$i]["name"];
					$file["name"] = "!".$items[$i]["name"];				
					$file["path"] = $items[$i]["path"];
					$files[] = $file;
				
			}	
			
		}
		
		return $files;
	}


	/***************************************************/
	// WEBK_FilterFiles :: retrieve the files(ACtions)
	/***************************************************/	
	function WEBK_FilterActions_BOTTOM($items)
	{
		$files = array();
		
		for ($i=0;$i<count($items);$i++) {
		
			if ($items[$i]["type"] == "action_bottom") {
	
					$file = array();
					$file["link"] = "download.php?file=".$items[$i]["path"].$items[$i]["name"];
					$file["name"] = "@".$items[$i]["name"];				
					$file["path"] = $items[$i]["path"];
					$files[] = $file;
				
			}	
			
		}
		
		return $files;
	}




	/***************************************************/
	// WEBK_RenderPage :: Used to render main body
	/***************************************************/	
	function WEBK_RenderPage($title,$menu,$content,$left,$right,$clock)
	{
		$TPL = new phemplate(PATH_TEMPLATE);
		$TPL->set_file("page","body.html");

		$TPL->set_var("title",$title);
		$TPL->set_loop("menu",$menu);
		$TPL->set_var("content",$content);
		$TPL->set_var("left",$left);
		$TPL->set_var("right",$right);
		$TPL->set_var("webk_left_width",WEBK_LEFT_WIDTH);
		$TPL->set_var("webk_right_width",WEBK_RIGHT_WIDTH);
		
		
		$TPL->set_var("clock",substr((microtime(true)-$clock),0,8)." seconds");
		print $TPL->process("","page",2);
	}	




	/***************************************************/
	// WEBK_RenderFolders 
	/***************************************************/	
	function WEBK_RenderFolders($folders)
	{
		if (count($folders) > 0)
		$content = "<table>\n";
		else 
		$content = "";

		
		$TPL = new phemplate(PATH_TEMPLATE);
		$TPL->set_file("page","folders.html");
    		$count = 0;

    		for ($i=0;$i<count($folders);$i++) {
	
			if ($count == 0) $content .= "<tr>\n";
  			
  			$hits = WEBK_GetHits_FOLDER(false,PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"]);
  			
  			$items = 0;

 			$f_FOLDER = WEBK_FetchFiles(PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"]);
 			$folders_FOLDER = WEBK_FilterFolders($f_FOLDER);
 			$files_FOLDER = WEBK_FilterFiles($f_FOLDER);
 			$items = count($f_FOLDER);
				
			
			$TPL->set_var("title",substr($folders[$i]["name"],0,strlen($folders[$i]["name"])-1));
 			$TPL->set_var("hits",$hits);
 			$TPL->set_var("items",$items);
 			$TPL->set_var("link",$folders[$i]["link"]);
 			
 			
 			$image = "";
 			
 			if (file_exists(PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"].".meta/folder.jpg"))
	 			$image = "download.php?file=".$folders[$i]["path"].$folders[$i]["name"].".meta/folder.jpg";
	 		
	 		if (file_exists(PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"].".meta/folder.gif"))
	 			$image = "download.php?file=".$folders[$i]["path"].$folders[$i]["name"].".meta/folder.gif";
	 		
	 		if (file_exists(PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"].".meta/folder.png"))
	 			$image = "download.php?file=".$folders[$i]["path"].$folders[$i]["name"].".meta/folder.png";
	 		
	 		if ($image == "") {
	 			
	 			$imgdir = PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"].".meta/";
	 			$filelist = opendir($imgdir);
	 			$foundfile = "";
	 			while ($file = readdir($filelist))
	 			{
	 				if (substr($file,0,7)==".image_")
	 				{
	 					$foundfile = $file;
	 					copy($imgdir.$foundfile,PATH_ROOT.$folders[$i]["path"].$folders[$i]["name"].".meta/folder.jpg");
	 					
	 					$image = "download.php?file=".$folders[$i]["path"].$folders[$i]["name"].".meta/folder.jpg";	
	 				}
	 			}
	 			closedir($filelist);
	 			
	 			if ($foundfile == "") 
	 			{
	 				$image = "images/".WEBK_THEME."/interface/folder_thumbnail.gif";
	 			}
	 			
	 		}
	 			
 			$TPL->set_var("image",$image);
 		
	    		$content .= "\t<td>".$TPL->process("","page",2)."</td>\n";
				
			if ($count == 3) {
				$content .= "</tr>\n";
				$count = 0; 
				} else $count++;

    	}
    
		if ($count != 0)    	
    	if ($count != 3) $content .= "</tr>\n";

	if (count($folders) > 0)
    	$content .= "</table>\n";
  
  	
	   return $content;
	}

	/***************************************************/
	// WEBK_RenderFiles
	/***************************************************/	
	function WEBK_RenderFiles($files)
	{

		if (count($files) > 0)
		$content = "<table>\n";
		else $content = "";

		$TPL = new phemplate(PATH_TEMPLATE);
		$TPL->set_file("page","files.html");
    		$count = 0;

	    	for ($i=0;$i<count($files);$i++) {
  			
  			if ($count==0) $content .= "<tr>\n";
  			
				
    			$TPL->set_var("title",substr($files[$i]["name"],0,strlen($files[$i]["name"])));
 			$TPL->set_var("link",$files[$i]["link"]);

  			$hits = WEBK_GetHits_FILE(false,PATH_ROOT.$files[$i]["path"],$files[$i]["name"]);

 			$TPL->set_var("hits",$hits);
 			
 			$pf = explode(".",$files[$i]["name"]);
 			if (count($pf) == 1) $filetype = "???";
 			else
 			$filetype = $pf[count($pf)-1];
 			$TPL->set_var("type",substr($filetype,0,4));
 			
 			$size = @filesize(PATH_ROOT.$files[$i]["path"].$files[$i]["name"]);
 			
 			$size /= 1024;
			$size = explode(".",$size);

			if ($size[0] >= 1000)
			{
				 $size = $size[0];
				 $size /= 1024;
				 $size = explode(".",$size);
				@$size[1] = substr(@$size[1],0,2);
				if (@$size[1] == "") $size[1] = "0";
				$size = $size[0].".".$size[1]." mb";
				 
			}
			else
			{
				@$size[1] = substr(@$size[1],0,2);
				if (@$size[1] == "") $size[1] = "0";
				$size = $size[0].".".$size[1]." kb";
			}			
			 			
 			$TPL->set_var("size",$size);
 			$TPL->set_var("image",WEBK_GetFileThumbnail($files[$i]["path"],$files[$i]["name"]));
 			
    		$content .= "\t<td valign=\"top\">".$TPL->process("","page",2)."</td>\n";
			
			if ($count == 3) {
				$content .= "</tr>\n";
				$count = 0; 
				} else $count++;


    	}

		if ($count != 0)    	
    	if ($count != 3) $content .= "</tr>\n";
    	
	if (count($files) > 0)
    	$content .= "</table>\n";
  


	   return $content;
	}


	/***************************************************/
	// 
	/***************************************************/	
	function WEBK_GetHits_FOLDER($doCount,$path)
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		if (!is_dir($path.".meta/")) mkdir($path.".meta/",0777);
					
		if (!file_exists($path.".meta/.count_DIRECTORY")) {
			$fd = fopen($path.".meta/.count_DIRECTORY","w");
			$count = 0;
			fwrite($fd,"hits|$ip|$count|\n");			
			fclose($fd);
		}


		$fd = fopen($path.".meta/.count_DIRECTORY","r");
		$line = fread($fd,4999);	
		$line = explode("|",$line);
		
		$last_ip = $line[1];
		$count = $line[2];		
		fclose($fd);
	
		
		if ($doCount) 
		if ($ip != $last_ip) {
			$count++;
			$fd = fopen($path.".meta/.count_DIRECTORY","w");
			fwrite($fd,"hits|$ip|$count|\n");			
			fclose($fd);
		}		
		
		return $count;
	}
	
	/***************************************************/
	// WEBK_RenderActions 
	/***************************************************/	
	function WEBK_RenderActions($files)
	{
		$content = "";
		for ($i=0;$i<count($files);$i++)
		{
			$action = $files[$i]["name"];
			$action = explode("_",$action);
			
			$action = $action[1];

			$item = $files[$i];
			if (function_exists("WEBK_action_".$action))
			eval("\$content .= WEBK_action_".$action."(\$item);");
		}
		
		return $content;
	}
	

	/***************************************************/
	// 
	/***************************************************/	
	function WEBK_GetHits_FILE($doCount,$path,$file)
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		if (!is_dir($path.".meta/")) mkdir($path.".meta/",0777);
					
		if (!file_exists($path.".meta/.count_FILE_$file")) {
			$fd = fopen($path.".meta/.count_FILE_$file","w");
			$count = 0;
			fwrite($fd,"hits|$ip|$count|\n");			
			fclose($fd);
		}


		$fd = fopen($path.".meta/.count_FILE_$file","r");
		$line = fread($fd,4999);	
		$line = explode("|",$line);
		
		$last_ip = $line[1];
		$count = $line[2];		
		fclose($fd);
	
		
		if ($doCount) 
		if ($ip != $last_ip) {
			$count++;
			$fd = fopen($path.".meta/.count_FILE_$file","w");
			fwrite($fd,"hits|$ip|$count|\n");			
			fclose($fd);
		}		
		
		// we verify if the file is currently a valid image
		$imgfile = $path.".meta/.image_".md5($file)."_".filesize($path.$file);
		$filetype = @explode(".",strtolower($file));
		$filetype = @$filetype[1];
		
		if (!file_exists($imgfile))
		{
			if (function_exists("WEBK_preview_".$filetype) )
				eval("WEBK_preview_".$filetype."(\"".$path."\",\"".$file."\");");
				
				
		}
		
		return $count;
	}
	

	/***************************************************/
	// 
	/***************************************************/	
	function WEBK_GetFileThumbnail($path,$file)
	{
		$filetype = @explode(".",$file);
		$filetype = @$filetype[1];

		$filename = PATH_ROOT.$path.".meta/.image_".md5($file)."_".filesize(PATH_ROOT.$path.$file);
							
		if (file_exists($filename))
			return "image.php?path=".urlencode($path)."&file=".urlencode($file);
			
		if (file_exists(PATH_WEB."images/filetype/$filetype.gif"))
			return "images/".WEBK_THEME."/filetype/$filetype.gif";
			else
			return "images/".WEBK_THEME."/interface/file_thumbnail.gif";
	
	
	}



	/***************************************************/
	// 
	/***************************************************/	
	function WEBK_renderLocked()
	{
		$TPL = new phemplate(PATH_TEMPLATE);
		$TPL->set_file("page","locked.html");		
		return $TPL->process("","page",2);
	}	
	
	
	
	/***************************************************/
	// WEBK_Redirect
	/***************************************************/
	function WEBK_Redirect($URL)
	{
		header("Location: $URL");		
		die("<SCRIPT>window.location='$URL';</SCRIPT>");
	}
	
	
	
	/***************************************************/
	// WEBK_renderSide
	/***************************************************/	
	function WEBK_renderSide($side)
	{
		if (!is_dir(PATH_ROOT.$side."/")) return "";
		
		$PATH = $side."/";
		
		WEBK_GetHits_FOLDER(true,PATH_ROOT.$PATH);
	
		$files_PATH = WEBK_FetchFiles(PATH_ROOT.$PATH);
	
		
		$folders = WEBK_FilterFolders($files_PATH);
		$files = WEBK_FilterFiles($files_PATH);
		$actions_TOP = WEBK_FilterActions_TOP($files_PATH);
		$actions_BOTTOM = WEBK_FilterActions_BOTTOM($files_PATH);
	
	return WEBK_renderActions($actions_TOP).WEBK_renderFolders($folders).WEBK_renderFiles($files).WEBK_renderActions($actions_BOTTOM);
		
	}
	
?>
