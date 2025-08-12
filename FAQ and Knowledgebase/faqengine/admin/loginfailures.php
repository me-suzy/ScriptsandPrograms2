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
$page_title=$l_failed_logins;
$page="loginfailures";
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
		if(faqe_array_key_exists($admcookievals,"lf_sorting"))
			$sorting=$admcookievals["lf_sorting"];
	}
}
if(!isset($sorting))
	$sorting=32;
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
		if(!$result = faqe_db_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = faqe_db_fetch_array($result))
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
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
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
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
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
		$success = faqe_db_query($deleteSQL,$db);
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
		$success = faqe_db_query($deleteSQL,$db);
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
$sql = "select * from ".$tableprefix."_failed_logins ";
switch($sorting)
{
	case 12:
		$sql.="order by username desc";
		break;
	case 21:
		$sql.="order by ipadr asc";
		break;
	case 22:
		$sql.="order by ipadr desc";
		break;
	case 31:
		$sql.="order by logindate asc";
		break;
	case 32:
		$sql.="order by logindate desc";
		break;
	default:
		$sql.="order by username asc";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$maxsortcol=3;
	$baseurl="$act_script_url?$langvar=$act_lang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"20%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_username</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"20%\"><b>$l_password</b></td>";
	echo "<td align=\"center\" width=\"20%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_ipadr</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	if($enablehostresolve==1)
		echo "<td class=\"rowheadings\" align=\"center\"><b>$l_hostname</b></td>";
	echo "<td align=\"center\" width=\"15%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_date</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td>&nbsp;</td></tr>";
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
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		echo "<td width=\"15%\" align=\"center\">".$myrow["username"]."</td>";
		echo "<td width=\"15%\" align=\"center\">".$myrow["usedpw"]."</td>";
		echo "<td width=\"15%\" align=\"center\">";
		$hostname=gethostname($myrow["ipadr"],$db,false);
		if((strlen($hostname)<1) && ($enablehostresolve==1))
			echo " <a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=resolve&input_loginnr=$act_id&$langvar=$act_lang")."\">";
		echo $myrow["ipadr"];
		if((strlen($hostname)<1) && ($enablehostresolve==1))
			echo "</a>";
		echo "</td>";
		if($enablehostresolve==1)
			echo "<td width=\"20%\" align=\"center\">".$hostname."</td>";
		echo "<td width=\"15%\" align=\"center\">$displaydate</td>";
		echo "<td>";
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&$langvar=$act_lang&input_loginnr=$act_id")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "&nbsp; ";
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_loginnr=$act_id&$langvar=$act_lang")."\">";
		echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
		if(!isbanned($myrow["ipadr"],$db))
		{
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("banlist?mode=newadr&subnetmask=255.255.255.255&ipadr=".$myrow["ipadr"]."&lang=$lang")."\">";
			echo "<img src=\"gfx/noentry.gif\" border=\"0\" title=\"$l_banip\" alt=\"$l_banip\"></a>";
		}
		echo "</td></tr>";
		} while($myrow = faqe_db_fetch_array($result));
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