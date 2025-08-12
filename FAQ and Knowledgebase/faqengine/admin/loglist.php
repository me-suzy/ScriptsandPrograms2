<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_loginlist;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($enable_htaccess)
{
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"errorrow\" align=\"center\"><td>";
	echo "$l_notavail_htaccess";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="4"><b><?php echo "$l_user: $username"?></b></td></tr>
<?php
if(isset($mode))
{
	if($mode=="clear")
	{
		$deleteSQL="delete from ".$tableprefix."_iplog where (usernr=".$input_usernr.")";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_entries $l_deleted</td></tr>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("users.php?$langvar=$act_lang")."\">$l_userlist</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="resolve")
	{
		if($enablehostresolve!=1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_iplog where lognr=$input_lognr";
		if(!$result = faqe_db_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = faqe_db_fetch_array($result))
		{
			if(strlen($myrow["hostname"])<1)
			{
				$acthostname=gethostname($myrow["ipadr"],$db,true);
			}
		}
	}
	if($mode=="hostnames")
	{
		if($enablehostresolve!=1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_iplog where (usernr=".$input_usernr.")";
		if(!$result = faqe_db_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = faqe_db_fetch_array($result))
		{
			do{
				$acthostname=gethostname($myrow["ipadr"],$db,true);
			}while($myrow=faqe_db_fetch_array($result));
		}
	}
}
$sql = "select * from ".$tableprefix."_iplog where (usernr=".$input_usernr.") order by logtime desc";
if(!$result = faqe_db_query($sql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "$l_noentries";
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"30%\"><b>$l_date</b></td><td align=\"center\" width=\"30%\"><b>$l_ipadr</b></td>";
	if(($admin_rights>2) && ($enablehostresolve==1))
		echo "<td align=\"center\" width=\"30%\"><b>$l_hostname</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>$l_language</b></td>";
	echo "</tr>";
	do {
		$hostname=gethostname($myrow["ipadr"],$db,false);
		list($mydate,$mytime)=explode(" ",$myrow["logtime"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if($myrow["logtime"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$displaydate."</td>";
		if(($admin_rights>2) && (strlen($hostname)<1) && ($enablehostresolve==1))
			$displayip="<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?input_usernr=$input_usernr&$langvar=$act_lang&mode=resolve&username=$username&input_lognr=".$myrow["lognr"])."\">".$myrow["ipadr"]."</a>";
		else
			$displayip=$myrow["ipadr"];
		echo "<td align=\"center\">".$displayip."</td>";
		if(($admin_rights>2) && ($enablehostresolve==1))
			echo "<td align=\"center\">".$hostname."</td>";
		echo "<td align=\"center\">".$myrow["used_lang"]."</td>";
		echo "</tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"4\">";
	echo "<a href=\"".do_url_session("$act_script_url?input_usernr=$input_usernr&$langvar=$act_lang&mode=clear&username=$username")."\">";
	echo "$l_clearentries</a>";
	if(($admin_rights>2) && ($enablehostresolve==1))
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?input_usernr=$input_usernr&$langvar=$act_lang&mode=hostnames&username=$username")."\">";
		echo "$l_determine_hostnames</a>";
	}
	echo "</td></tr>";
	echo "</table></tr></td></table>";
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("users.php?$langvar=$act_lang")."\">$l_userlist</a></div>";
include('./trailer.php');
?>