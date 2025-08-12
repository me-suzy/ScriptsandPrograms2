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
$page_title=$l_loginlist;
$page="loglist";
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$watchlogins=$myrow["watchlogins"];
	$enablehostresolve=$myrow["enablehostresolve"];
}
else
{
	$watchlogins=1;
	$enablehostresolve=1;
}
$dateformat="Y-m-d H:i:s";
if(!isset($sorting))
	$sorting=12;
if(!isset($dostorefilter) && ($admstorefilter==1))
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
		if(sn_array_key_exists($admcookievals,"loglist_sorting"))
			$sorting=$admcookievals["loglist_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="4"><b><?php echo "$l_user: $input_username"?></b></td></tr>
<?php
if(isset($mode))
{
	if($mode=="clear")
	{
		$deleteSQL="delete from ".$tableprefix."_iplog where (usernr=".$input_usernr.")";
		$success = mysql_query($deleteSQL);
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
		if(!$result = mysql_query($sql, $db)) {
			echo "<tr class=\"errorrow\"><td align=\"center\">";
		    die("Could not connect to the database.");
		}
		if ($myrow = mysql_fetch_array($result))
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
	}
}
$sql = "select * from ".$tableprefix."_iplog where (usernr=".$input_usernr.") ";
switch($sorting)
{
	case 11:
		$sql.="order by logtime asc";
		break;
	case 12:
		$sql.="order by logtime desc";
		break;
	case 21:
		$sql.="order by ipadr asc";
		break;
	case 22:
		$sql.="order by ipadr desc";
		break;
}
if(!$result = mysql_query($sql, $db)) {
	echo "<tr class=\"errorrow\"><td align=\"center\">";
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "$l_noentries";
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl=$act_script_url."?".$langvar."=".$act_lang."&input_usernr=$input_usernr&input_username=".urlencode($input_username);
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	$maxsortcol=2;
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"30%\"><b>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
	echo "$l_date</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\" width=\"30%\"><b>";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
	echo "$l_ipadr</a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</b></td>";
	if(($admin_rights>2) && ($enablehostresolve==1))
	{
		echo "<td align=\"center\" width=\"30%\"><b>";
		echo "$l_hostname";
		echo "</b></td>";
	}
	echo "<td align=\"center\" width=\"10%\"><b>";
	echo "$l_language";
	echo "</b></td></tr>";
	do {
		$hostname=gethostname($myrow["ipadr"],$db,false);
		list($mydate,$mytime)=explode(" ",$myrow["logtime"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($l_admdateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		if($myrow["logtime"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$displaydate."</td>";
		if(($admin_rights>2) && (strlen($hostname)<1) && ($enablehostresolve==1))
			$displayip="<a class=\"listlink\" href=\"".do_url_session("$act_script_url?input_usernr=$input_usernr&$langvar=$act_lang&mode=resolve&input_username=".urlencode($input_username)."&input_lognr=".$myrow["lognr"])."\">".$myrow["ipadr"]."</a>";
		else
			$displayip=$myrow["ipadr"];
		echo "<td align=\"center\">".$displayip."</td>";
		if(($admin_rights>2) && ($enablehostresolve==1))
			echo "<td align=\"center\">".$hostname."</td>";
		echo "<td align=\"center\">".$myrow["used_lang"]."</td>";
		echo "</tr>";
	} while($myrow = mysql_fetch_array($result));
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"4\">";
	echo "<a href=\"".do_url_session("$act_script_url?input_usernr=$input_usernr&$langvar=$act_lang&mode=clear&input_username=".urlencode($input_username))."\">";
	echo "$l_clearentries</a>";
	if(($admin_rights>2) && ($enablehostresolve==1))
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?input_usernr=$input_usernr&$langvar=$act_lang&mode=hostnames&input_username=".urlencode($input_username))."\">";
		echo "$l_determine_hostnames</a>";
	}
	echo "</td></tr>";
	echo "</table></tr></td></table>";
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("users.php?$langvar=$act_lang")."\">$l_userlist</a></div>";
include('./trailer.php');
?>