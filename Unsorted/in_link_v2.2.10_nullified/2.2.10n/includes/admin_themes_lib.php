<?php
//Admin Themes Functions

//change theme
function changetheme($themeselect) {
global $conn, $theme;
$conn->Execute("update inl_config set value='$themeselect' where name='theme'");
$theme = $themeselect;
}

//list themes
function themelist() {
global $filedir, $theme, $themes;
$themes = "<select name=\"themeselect\" class=\"text\">";
$dir = opendir($filedir . "themes/");
while ($file = readdir($dir)) {
	
    if (($file!="..") && ($file!=".") && is_dir($filedir."themes/".$file)){ 
        if ($file == $theme) {
    $themes .= "<option selected>$file</option>";
} else {
    $themes .= "<option>$file</option>";
}
}
}
$themes .= "</select>";
}

//load template file
function loadtempl($templlist) 
{
	global $theme, $filedir, $templ_txt;

	$file = $filedir . "themes/$theme/$templlist";
	$fd = fopen($file, "r");
	$templ_txt = fread($fd, filesize($file));
	fclose($fd);
}

//edit template file
function edittemplfile($templ_txt, $templlist) 
{	global $theme, $filedir;

	$file = $filedir . "themes/$theme/$templlist";

	$fd = fopen($file, "w");
	#$language_txt = stripslashes($language_txt);
	fputs($fd, $templ_txt);
	fclose($fd);
}




//template files list
function templateslist() 
{
	global $filedir, $theme, $templlist, $templates;
	$templates = "<select name=\"templlist\" class=\"text\">";
	$dir = opendir($filedir . "themes/" . $theme . "/");
	while ($file[] = readdir($dir)) {;}
	sort($file);
	for($i=0;$i<count($file);$i++)
	{
		if (($file[$i]!="..") && ($file[$i]!=".") && (!is_dir($filedir . "themes/" . $theme . "/".$file[$i])))
		{
	        if ($file[$i] == $templlist)
				$templates .= "<option value=\"$file[$i]\" selected>$file[$i]</option>";
			else
				$templates .= "<option value=\"$file[$i]\">$file[$i]</option>";
		}
	}
	$templates .= "</select>";
}
?>