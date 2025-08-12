<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function display_inline_comments($newsnr)
{
	global $tableprefix, $db, $servertimezone, $displaytimezone, $dateformat;
	global $l_comment, $l_on, $l_by;
	global $icdisplayemail;
	global $ic_heading_bgcolor, $ic_heading_font, $ic_heading_fontsize, $ic_heading_fontcolor, $ic_heading_style;
	global $ic_body_bgcolor, $ic_body_font, $ic_body_fontsize, $ic_body_fontcolor, $ic_body_style;

	$tempsql="select * from ".$tableprefix."_comments where entryref=$newsnr";
	if(!$tempresult = mysql_query($tempsql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($temprow=mysql_fetch_array($tempresult))
	{
		echo "<tr bgcolor=\"$ic_heading_bgcolor\"><td align=\"left\" colspan=\"3\">";
		echo "<font face=\"$ic_heading_font\" size=\"$ic_heading_fontsize\" color=\"$ic_heading_fontcolor\">";
		echo get_start_tag($ic_heading_style);
		echo "$l_comment $l_by ".display_encoded($temprow["poster"]);
		if(($temprow["email"]) && ($icdisplayemail==1))
			echo " (".emailencode(stripslashes($temprow["email"])).")";
		list($mydate,$mytime)=explode(" ",$temprow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($dateformat,$temptime);
		}
		else
			$displaydate="";
		echo " $l_on $displaydate";
		echo get_end_tag($ic_heading_style);
		echo "</font></td></tr>";
		echo "<tr bgcolor=\"$ic_body_bgcolor\"><td align=\"left\" colspan=\"3\">";
		echo "<font face=\"$ic_body_font\" size=\"$ic_body_fontsize\" color=\"$ic_body_fontcolor\">";
		echo get_start_tag($ic_body_style);
		echo display_encoded($temprow["comment"]);
		echo get_end_tag($ic_body_style);
		echo "</font></td></tr>";
	}
}
?>