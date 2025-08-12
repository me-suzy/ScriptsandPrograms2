<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function language_select($default, $name="language", $dirname="language/", $suppresslang="")
{
	$dir = opendir($dirname);
	$lang_select = "<SELECT NAME=\"$name\">";
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			if(!$suppresslang || ($file!=$suppresslang))
			{
				$file == $default ? $selected = " SELECTED" : $selected = "";
				$lang_select .= "<OPTION value=\"$file\"$selected>$file</option>";
			}
		}
	}
	$lang_select .= "</SELECT>";
	closedir($dir);
	return $lang_select;
}

function language_select2($default, $name="language", $dirname="language/", $suppresslang="")
{
	global $l_all;

	$dir = opendir($dirname);
	$lang_select = "<SELECT NAME=\"$name\">";
	$lang_select.= "<option value=\"all\"";
	"all" == $default ? $selected = " SELECTED" : $selected = "";
	$lang_select.="$selected>$l_all</option>";
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			if(!$suppresslang || ($file!=$suppresslang))
			{
				$file == $default ? $selected = " SELECTED" : $selected = "";
				$lang_select .= "<OPTION value=\"$file\"$selected>$file</option>";
			}
		}
	}
	$lang_select .= "</SELECT>";
	closedir($dir);
	return $lang_select;
}

function language_list($dirname="language/")
{
	$langs = array();
	$dir = opendir($dirname);
	while($file = readdir($dir))
	{
		if (ereg("^lang_",$file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			array_push($langs,$file);
		}
	}
	closedir($dir);
	return $langs;
}

function escape_slashes($input)
{
	$output = str_replace('/', '\/', $input);
	return $output;
}

function gethostname($ipadr, $db, $doresolve)
{
	global $hcprefix;

	$sql="select * from ".$hcprefix."_hostcache where ipadr='$ipadr'";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	$acthostname="";
	if ((!$myrow = mysql_fetch_array($result)) && ($doresolve==true))
	{
		$acthostname=gethostbyaddr($ipadr);
		$sql = "insert into ".$hcprefix."_hostcache (ipadr, hostname) values ('$ipadr','$acthostname')";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
	}
	else
		$acthostname=$myrow["hostname"];
	return $acthostname;
}

function do_url_session($url)
{
	global $sessid_url, $url_sessid, $sesscookiename;

	$url=ereg_replace("[&?]+$", "", $url);
	if($sessid_url)
	{
		$url2="";
		if(strrpos($url,"#")>0)
		{
			$url2=substr($url,strrpos($url,"#"));
			$url=substr($url,0,strrpos($url,"#"));
		}
		$url .= ( strpos($url, "?") != false ?  "&" : "?" ).urlencode($sesscookiename)."=".$url_sessid;
		if(strlen($url2)>0)
			$url.=$url2;
    }
    return $url;
}

function getSortMarker($currentorder, $column, $maxcolumn)
{
	$sortdown="<img src=\"gfx/down2.gif\" width=\"12\" height=\"9\" border=\"0\" align=\"baseline\">";
	$sortup="<img src=\"gfx/up2.gif\" width=\"12\" height=\"9\" border=\"0\" align=\"baseline\">";
	$nosort="<img src=\"gfx/space.gif\" width=\"12\" height=\"9\" border=\"0\" align=\"baseline\">";

	if($column>$maxcolumn)
		return "";
	$currentcolumn=floor($currentorder/10);
	$currentdirection=$currentorder%10;
	if($currentcolumn!=$column)
		return $nosort;
	if($currentdirection==1)
		return $sortdown;
	else
		return $sortup;
}

function getSortURL($currentorder, $column, $maxcolumn, $baseurl, $anchor="")
{
	if($column>$maxcolumn)
		return "";
	$currentcolumn=floor($currentorder/10);
	$currentdirection=$currentorder%10;
	if($column!=$currentcolumn)
		$sorturl=$baseurl."&sorting=".$column."2";
	else if($currentdirection==1)
		$sorturl=$baseurl."&sorting=".$column."2";
	else
		$sorturl=$baseurl."&sorting=".$column."1";
	if($anchor)
		$sorturl.="#".$anchor;
	return $sorturl;
}

function tz_select($default, $name="timezone")
{
	global $timezones;

	$tzselect="<select name=\"$name\">";
	for($i=0;$i<count($timezones);$i++)
	{
		$tzselect.="<option value=\"$i\"";
		if($i==$default)
			$tzselect.=" selected";
		$tzselect.=">";
		$tzselect.=$timezones[$i][0];
		$tzselect.="</option>";
	}
	$tzselect.="</select>";
	return $tzselect;
}

function disk_usage($directory, $depth = NULL, $exclude=array())
{
	$usage=0;
	if(is_file($directory))
		return filesize($directory);
	if(isset($depth) && $depth < 0)
		return 0;
	if($directory[strlen($directory)-1] != '\\' || $directory[strlen($directory)-1] != '/')
		$directory .= '/';
	if(!in_array($directory,$exclude))
	{
		$dirhandle=@opendir($directory);
		if(!$dirhandle)
			return 0;
		while($entry = readdir($dirhandle))
		{
			if($entry != '.' && $entry != '..')
				$usage += disk_usage($directory.$entry, isset($depth) ? $depth - 1 : NULL, $exclude);
		}
		closedir($dirhandle);
	}
	return $usage;
}

function count_files($directory, $depth = NULL, $exclude=array())
{
	$numfiles=0;
	if(is_file($directory))
		return 1;
	if(isset($depth) && $depth < 0)
		return 0;
	if($directory[strlen($directory)-1] != '\\' || $directory[strlen($directory)-1] != '/')
		$directory .= '/';
	if(!in_array($directory,$exclude))
	{
		$dirhandle=@opendir($directory);
		if(!$dirhandle)
			return 0;
		while($entry = readdir($dirhandle))
		{
			if($entry != '.' && $entry != '..')
				$numfiles += count_files($directory.$entry, isset($depth) ? $depth - 1 : NULL, $exclude);
		}
		closedir($dirhandle);
	}
	return $numfiles;
}
?>