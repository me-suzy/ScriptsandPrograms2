<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_failed_logins;
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
	$enablehostresolve=$myrow["enablehostresolve"];
else
	$enablehostresolve=1;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="hostnames")
	{
		if($enablehostresolve!=1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_failed_logins";
		if(!$result = mysql_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = mysql_fetch_array($result))
		{
			do{
				$acthostname=gethostname($myrow["ipadr"],$db,true);
			}while($myrow=mysql_fetch_array($result));
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_hostnames_resolved";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_failed_logins</a></div>";
	}
	if($mode=="resolve")
	{
		if($enablehostresolve!=1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_failed_logins where loginnr=$input_loginnr";
		if(!$result = mysql_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = mysql_fetch_array($result))
		{
			$acthostname=gethostname($myrow["ipadr"],$db,true);
		}
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_hostnames_resolved";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_failed_logins</a></div>";
	}
	if($mode=="display")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_failed_logins where (loginnr=$input_loginnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displayfailedlogin?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><?php echo $myrow["username"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td><td><?php echo $myrow["ipadr"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_hostname?>:</td><td><?php echo gethostname($myrow["ipadr"],$db,false)?></td></tr>
<?php
		list($mydate,$mytime)=explode(" ",$myrow["logindate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($l_admdateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="displayrow"><td align="right"><?php echo $l_date?>:</td><td><?php echo $displaydate?></td></tr>
<?php
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_failed_logins</a></div>";
	}
	if($mode=="delete")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_failed_logins where (loginnr=$input_loginnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_failed_logins</a></div>";
	}
	if($mode=="clear")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_failed_logins";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_entries $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_failed_logins</a></div>";
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights<3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$sql = "select * from ".$tableprefix."_failed_logins order by logindate desc, username";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<tr class="rowheadings">
<td align="center"><b><?php echo $l_username?></b></td>
<td align="center"><b><?php echo $l_password?></b></td>
<td align="center"><b><?php echo $l_ipadr?></b></td>
<?php
if($enablehostresolve==1)
	echo "<td align=\"center\"><b>$l_hostname</b></td>";
?>
<td align="center"><b><?php echo $l_date?></b></td>
<td>&nbsp;</td></tr>
<?php
		do {
			$act_id=$myrow["loginnr"];
			if($myrow["logindate"]>$userdata["lastlogin"])
				echo "<tr class=\"displayrownew\">";
			else
				echo "<tr class=\"displayrow\">";
			list($mydate,$mytime)=explode(" ",$myrow["logindate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
				$displaydate=date($l_admdateformat,mktime($hour,$min,$sec,$month,$day,$year));
			else
				$displaydate="";
			echo "<td width=\"15%\" align=\"center\">".$myrow["username"]."</td>";
			echo "<td width=\"15%\" align=\"center\">".$myrow["usedpw"]."</td>";
			echo "<td width=\"15%\" align=\"center\">";
			$hostname=gethostname($myrow["ipadr"],$db,false);
			if((strlen($hostname)<1) && ($enablehostresolve==1))
				echo " <a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=resolve&input_loginnr=$act_id&$langvar=$act_lang")."\">";
			echo $myrow["ipadr"];
			if((strlen($hostname)<1) && ($enablehostresolve==1))
				echo "</a>";
			echo "</td>";
			if($enablehostresolve==1)
				echo "<td width=\"20%\" align=\"center\">".$hostname."</td>";
			echo "<td width=\"15%\" align=\"center\">$displaydate</td>";
			echo "<td>";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&$langvar=$act_lang&input_loginnr=$act_id")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" alt=\"$l_display\" title=\"$l_display\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&input_loginnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
			if(!isbanned($myrow["ipadr"],$db))
			{
				echo " <a class=\"listlink\" href=\"".do_url_session("banlist.php?mode=newadr&subnetmask=255.255.255.255&ipadr=".$myrow["ipadr"]."&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/noentry.gif\" border=\"0\" alt=\"$l_banip\" title=\"$l_banip\"></a>";
			}
			echo "</td></tr>";
		} while($myrow = mysql_fetch_array($result));
		if($enablehostresolve==1)
		{
			echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"6\">";
			echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=hostnames")."\">";
			echo "$l_determine_hostnames</a></td></tr>";
		}
		echo "</table></tr></td></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=clear")."\">$l_clearentries</a></div>";
	}
}
include('./trailer.php');
?>