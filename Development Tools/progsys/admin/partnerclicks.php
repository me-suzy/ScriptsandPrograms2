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
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
require('./auth.php');
$page_title=$l_partnersiteclicks;
$page="partnerclicks";
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$dateformat=$myrow["dateformat"];
	$dateformat.=" H:i:s";
	$watchlogins=$myrow["watchlogins"];
	$enablehostresolve=$myrow["enablehostresolve"];
}
else
{
	$dateformat="Y-m-d H:i:s";
	$watchlogins=1;
	$enablehostresolve=1;
}
if($admin_rights < 2)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="delete")
	{
		$sql = "delete from ".$tableprefix."_partnerclicks where day='$day'";
		if(!$result = mysql_query($sql,$db))
			die("unable to connect to database");
	}
	if($mode=="massdel")
	{
		if(isset($days))
		{
	    		while(list($null, $day) = each($_POST["days"]))
	    		{
				$sql = "delete from ".$tableprefix."_partnerclicks where day='$day'";
				if(!$result = mysql_query($sql,$db))
					die("unable to connect to database");
			}
		}
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$lastday = " ";
$sql = "select * from ".$tableprefix."_partnerclicks pc, ".$tableprefix."_partnersites ps where ps.sitenr=pc.sitenr ";
$sql.= "order by day desc, ps.sitenr asc";
if(!$result = mysql_query($sql,$db))
	die("<tr class=\"errorrow\"><td>unable to connect to database".mysql_error());
$numentries=mysql_num_rows($result);
if($numentries>0)
{
	echo "<tr class=\"displayrow\"><td>";
	echo "<form name=\"siteclicks\" method=\"post\" action=\"$act_script_url\">";
	echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
}
while($myrow = mysql_fetch_array($result))
{
	if(!strstr($lastday,$myrow["day"]))
	{
		if($lastday != " ")
			print("</table><br>\n");
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" align=\"center\" bgcolor=\"#000000\">\n";
		echo "<tr class=\"grouprow1\"><td align=\"left\" colspan=\"4\"><input type=\"checkbox\" name=\"days[]\" value=\"".$myrow["day"]."\">&nbsp;";
		echo "<b>".$myrow["day"]."</b>";
		echo "&nbsp;&nbsp;";
		$dellink=do_url_session("$act_script_url?mode=delete&day=".$myrow["day"]."&lang=$lang");
		if($admdelconfirm==1)
			echo "<a class=\"listlink\" href=\"javascript:confirmDel('".$myrow["day"]."','$dellink')\">";
		else
			echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
		echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
		echo "</td></tr>";
		echo "<tr class=\"rowheadings\"><td><b>$l_sitename</b></td><td><b>$l_siteurl</b></td><td><b>$l_clicks</b></td></tr>";

	}
	echo "<tr class=\"displayrow\">";
	echo "<td>".$myrow["name"]."</td>";
	echo "<td>".$myrow["siteurl"]."</td>";
	echo "<td>".$myrow["clicks"]."</td>";
	$lastday = $myrow["day"];
}
if($numentries>0)
{
	echo "</table></td></tr>";
	echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
	echo "<tr class=\"actionrow\"><td align=\"left\">";
	echo "<input class=\"psysbutton\" type=\"button\" onclick=\"checkAll(document.siteclicks)\" value=\"$l_checkall\">";
	echo "&nbsp; <input class=\"psysbutton\" type=\"button\" onclick=\"uncheckAll(document.siteclicks)\" value=\"$l_uncheckall\">";
	echo "&nbsp; <input class=\"psysbutton\" type=\"submit\" value=\"$l_delsel\">";
	echo "</td></tr></form>";
}
else
	echo "<tr class=\"displayrow\"><td align=\"center\">$l_noentries</td></tr>";
echo "</table></td></tr></table>";
include('trailer.php');
?>
</body>
</html>
