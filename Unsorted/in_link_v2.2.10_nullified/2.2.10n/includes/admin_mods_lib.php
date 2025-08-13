<?php
//Admin Mods Functions

//list mods
function modlist() {
global $filedir, $mods, $modselected;
$mods = "<select name=\"modselected\" class=\"text\">";
$dir = opendir($filedir . "mods/");
while ($file = readdir($dir)) {
	
    if (($file!="..") && ($file!=".") && (!is_dir($filedir . "mods/" . $language . "/".$file)))
	{	if (($file == $modselected) || ($modselected == "")                                       )
		{	$mods .= "<option value=\"$file\" selected>$file</option>";
			$modselected=$file;
		}
		else
			$mods .= "<option value=\"$file\">$file</option>";
	}
}
$mods .= "</select>";
}



?>