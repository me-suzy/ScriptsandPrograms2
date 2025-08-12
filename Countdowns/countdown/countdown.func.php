<?
/*
MVW Counter
===========

By Gary Kertopermono

This credit tag may not be removed.
*/

function countdown_text($tosec,$tomin,$tohour,$today,$tomonth,$toyear)
{
	$cursec = intval(date("s"));
	$curmin = intval(date("i"));
	$curhour = intval(date("G"));
	$curday = intval(date("j"));
	$curmonth = intval(date("n"));
	$curyear = intval(date("Y"));

	return date("U",mktime($tohour,$tomin,$tosec,$tomonth,$today,$toyear)) - date("U",mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear));
}

function countdown_getdays($seconds)
{
	return intval($seconds/(24*3600));
}

function countdown_gethours($seconds)
{
	return intval($seconds/(3600));
}

function countdown_getminutes($seconds)
{
	return intval($seconds/(60));
}

function countdown_flash($file,$width,$height,$isevent=false,$event="")
{
	$returnstring = "";
 $returnstring.= '<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"';
 $returnstring.= 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"';
 $returnstring.= 'WIDTH="'.$width.'" HEIGHT="'.$height.'" id="countdown" ALIGN="">';
 $returnstring.= "<PARAM NAME=movie VALUE=\"$file";
 if($isevent) $returnstring.="?event=$event";
 $returnstring.= "\">";
 $returnstring.= "<PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"$file";
 if($isevent) $returnstring.="?event=$event";
 $returnstring.= '" quality=high bgcolor=#FFFFFF  WIDTH="$width" HEIGHT="$height" NAME="countdown" ALIGN=""';
 $returnstring.= 'TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">';

 $returnstring.= '</EMBED>';
 $returnstring.= '</OBJECT>';

 return $returnstring;
}