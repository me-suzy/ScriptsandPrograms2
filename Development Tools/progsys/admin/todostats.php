<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('../config.php');
require('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_todostats;
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
	$dateformat=$myrow["dateformat"];
else
	$dateformat="Y-m-d";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < 1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$act_lang="";
$sql = "select * from ".$tableprefix."_programm order by language, prognr";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
?>
</td></tr>
</table></tr></td></table>
<?php
include('./trailer.php');
exit;
}
echo "<tr bgcolor=\"#000000\">";
for($i=0;$i<3;$i++)
{
	echo "<td><img border=\"0\" src=\"gfx/space.gif\" height=\"1\" width=\"1\"></td>";
}
echo "</tr>";
?>
<tr class="inforow"><td align="center" colspan="3">
<b><?php echo $l_top10prog?></b></td></tr>
<?php
do {
	$progname=htmlentities($myrow["programmname"]);
	$proglang=$myrow["language"];
	$prognr=$myrow["prognr"];
	$datasql="select * from ".$tableprefix."_todo where programm=$prognr and ratingcount>0 order by (rating/ratingcount) desc limit 10";
	if(!$dataresult = mysql_query($datasql, $db))
	    die("<tr class=\"errorrow\"><td colspan=\"3\" align=\"center\">Could not connect to the database.".mysql_error());
	if ($datarow = mysql_fetch_array($dataresult))
	{
		echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"6\">";
		echo "<b>".htmlentities($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>\n";
		echo "<tr class=\"rowheadings\"><td align=\"center\" width=\"5%\"><b>#</b></td><td align=\"center\" width=\"40%\"><b>$l_description</b></td>";
		echo "<td width=\"10%\" align=\"center\">";
		echo "<b>$l_rating</b>";
		echo "</td></tr>\n";
		do {
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "<a class=\"listlink\" href=\"".do_url_session("todo.php?lang=$lang&mode=display&input_todonr=".$datarow["todonr"])."\">";
			echo $datarow["todonr"];
			echo "</a></td><td align=\"center\">";
			echo $datarow["text"];
			echo "</td>";
			echo "<td align=\"center\">";
			$rating=$datarow["rating"];
			$ratingcount=$datarow["ratingcount"];
			if($ratingcount>0)
			{
				echo $l_ratings[round($rating/$ratingcount,2)]."<br>";
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
			echo "</td>";
			echo "</tr>\n";
		} while($datarow = mysql_fetch_array($dataresult));
	}
	else
	{
		echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"6\">";
		echo "<b>".htmlentities($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>";
		echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
		echo "$l_noentries";
		echo "</td></tr>";
	}
} while($myrow = mysql_fetch_array($result));
?>
</td></tr>
<tr class="inforow"><td align="center" colspan="3">
<b><?php echo $l_last10prog?></b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_programm order by language, prognr";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
while($myrow = mysql_fetch_array($result))
{
	$progname=htmlentities($myrow["programmname"]);
	$proglang=$myrow["language"];
	$prognr=$myrow["prognr"];
	$datasql="select * from ".$tableprefix."_todo where programm=$prognr and ratingcount>0 order by (rating/ratingcount) asc limit 10";
	if(!$dataresult = mysql_query($datasql, $db))
	    die("<tr class=\"errorrow\"><td colspan=\"3\" align=\"center\">Could not connect to the database.".mysql_error());
	if ($datarow = mysql_fetch_array($dataresult))
	{
		echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"6\">";
		echo "<b>".htmlentities($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>\n";
		echo "<tr class=\"rowheadings\"><td align=\"center\" width=\"5%\"><b>#</b></td><td align=\"center\" width=\"40%\"><b>$l_description</b></td>";
		echo "<td width=\"10%\" align=\"center\">";
		echo "<b>$l_rating</b>";
		echo "</td></tr>\n";
		do {
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "<a class=\"listlink\" href=\"".do_url_session("todo.php?lang=$lang&mode=display&input_todonr=".$datarow["todonr"])."\">";
			echo $datarow["todonr"];
			echo "</a></td><td align=\"center\">";
			echo $datarow["text"];
			echo "</td>";
			echo "<td align=\"center\">";
			$rating=$datarow["rating"];
			$ratingcount=$datarow["ratingcount"];
			if($ratingcount>0)
			{
				echo $l_ratings[round($rating/$ratingcount,2)]."<br>";
				echo round($rating/$ratingcount,2);
				echo " ($ratingcount)";
			}
			else
				echo "--";
			echo "</td>";
			echo "</tr>\n";
		} while($datarow = mysql_fetch_array($dataresult));
	}
	else
	{
		echo "<tr class=\"grouprow1\"><td align=\"center\" colspan=\"6\">";
		echo "<b>".htmlentities($myrow["programmname"])." [".$myrow["language"]."]</b></td></tr>";
		echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
		echo "$l_noentries";
		echo "</td></tr>";
	}
}
?>
</td></tr></table></td></tr></table>
<?php
include('trailer.php');
?>