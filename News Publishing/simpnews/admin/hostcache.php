<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_emptyhostcache;
$page="hostcache";
require_once('./heading.php');
if(!isset($sorting))
	$sorting=11;
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
		if(sn_array_key_exists($admcookievals,"hc_sorting"))
			$sorting=$admcookievals["hc_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="doclear")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "DELETE FROM ".$hcprefix."_hostcache";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_hostcachecleared?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_hostcache</a></div>";
		include('./trailer.php');
		exit;
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
<tr class="displayrow">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="doclear">
<td align="center"><?php echo $l_hostcachewarning?></td></tr>
<tr class="actionrow">
<td align="center"><input class="snbutton" type="submit" name="submit" value="<?php echo $l_yes?>"></td></tr>
</table></td></tr></table>
<?php
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	$sql = "select * from ".$hcprefix."_hostcache ";
	switch($sorting)
	{
		case 11:
			$sql.="order by ipadr asc";
			break;
		case 12:
			$sql.="order by ipadr desc";
			break;
		case 21:
			$sql.="order by hostname asc";
			break;
		case 22:
			$sql.="order by hostname desc";
			break;
	}
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	$maxsortcol=2;
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
	echo "$l_ipadr</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
	echo "$l_hostname</a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</b></td></tr>";
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		do {
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo $myrow["ipadr"];
			echo "</td><td align=\"center\">";
			echo $myrow["hostname"];
			echo "</td></tr>";
		} while($myrow = mysql_fetch_array($result));
		echo "</table></tr></td></table>";
	}
	if($admin_rights > 2)
	{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=clear&$langvar=$act_lang")?>"><?php echo $l_emptyhostcache?></a></div>
<?php
	}
}
include('./trailer.php');
?>