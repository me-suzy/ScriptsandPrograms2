<?php
//Admin Languages Functions

//change language
function changelanguage($languagen)
{
	global $conn, $language, $ses, $sid, $la_failed_session;
	$conn->Execute("update inl_config set value='$languagen' where name='language'");
	$ses["lang"] = $languagen;
	if(!save_session($sid))
		echo $la_failed_session."<br>".$conn->ErrorMsg();
}

//change datefmt
function changedatefmt($dateformat)
{
	global $conn, $datefmt;
	$conn->Execute("update inl_config set value='$dateformat' where name='datefmt'");
	$datefmt = $dateformat;
}


//load language files
function loadlangfile($langfile)
{
	global $language, $filedir, $language_txt;
	$file = $filedir . "languages/$language/$langfile";
	$fd = fopen($file, "r");
	$language_txt = fread($fd, filesize($file));
	fclose($fd);
}


//edit language file
function editlangfile($language_txt, $langfile)
{	global $language, $filedir;
	
	$file = $filedir . "languages/$language/$langfile";
	$fd = fopen($file, "w");
	fputs($fd, $language_txt);
	fclose($fd);
}

//language list
function languagelist() 
{
	global $filedir, $languages, $language, $ses;
	$languages = "<select name=\"languagen\" class=\"text\">";
	$dir = opendir($filedir . "languages/");
	while ($file = readdir($dir))
	{
	    if (($file!="..") && ($file!=".") && is_dir($filedir."languages/".$file))
		{ 
			if ($file == $ses["lang"])
				$languages .= "<option selected>$file</option>";
			else
				$languages .= "<option>$file</option>";

		}
	}
	$languages .= "</select>";
}


//language files list
function languagefileslist()
{
	global $filedir, $languagefiles, $language, $langfile;
	$languagefiles = "<select name=\"langfile\" class=\"text\">";
	$dir = opendir($filedir . "languages/" . $language . "/");
	while ($file = readdir($dir))
	{
		if (($file!="..") && ($file!=".") && (!is_dir($filedir . "languages/" . $language . "/".$file)))
		{
			if ($file == $langfile)
				$languagefiles .= "<option value=\"$file\" selected>$file</option>";
			else			
				$languagefiles .= "<option value=\"$file\">$file</option>";
		}
	}
	$languages .= "</select>";
}
?>