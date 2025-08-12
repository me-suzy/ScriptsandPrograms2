<?
/*
MVW Counter
===========

By Gary Kertopermono

This credit tag may not be removed.
*/

$isevent=false;
if(file_exists($event))
{
	$tempvar = implode("\n",file($event));

	preg_match("#&event_name=(.*?)&#si",$tempvar,$match);

	$isevent=true;
}
?>
<HTML>
<HEAD>
<meta http-equiv=Content-Type content="text/html;  charset=">
<TITLE>Countdown<? if($isevent) echo ": ".$match[1];?></TITLE>
</HEAD>
<BODY bgcolor="#FFFFFF">
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 WIDTH="200" HEIGHT="100" id="countdown" ALIGN="">
 <?
 echo "<PARAM NAME=movie VALUE=\"countdown.swf";
 if($isevent) echo"?event=$event";
 echo "\">";
 ?>
 <?
 echo "<PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src=\"countdown.swf";
 if($isevent) echo"?event=$event";
 echo '" quality=high bgcolor=#FFFFFF  WIDTH="200" HEIGHT="100" NAME="countdown" ALIGN="" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">';
 ?>
 </EMBED>
</OBJECT>
<br><font size=1>MVW Counter, by Gary Kertopermono</font>
</BODY>
</HTML>
