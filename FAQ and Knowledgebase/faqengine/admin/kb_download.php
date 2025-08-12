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
$page_title=$l_kbdownload;
$page="kb_download";
require_once('./heading.php');
if($admin_rights < 2)
	die("$l_functionnotallowed");
if(!isset($sorting))
	$sorting=11;
if(!isset($filterprog))
	$filterprog=0;
if(!isset($filterlang))
	$filterlang="none";
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
		if(faqe_array_key_exists($admcookievals,"kbdl_filterprog"))
			$filterprog=$admcookievals["kbdl_filterprog"];
		if(faqe_array_key_exists($admcookievals,"kbdl_filterlang"))
			$filterlang=$admcookievals["kbdl_filterlang"];
		if(faqe_array_key_exists($admcookievals,"kbdl_sorting"))
			$sorting=$admcookievals["kbdl_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$sql = "select dat.* from ".$tableprefix."_kb_articles dat, ".$tableprefix."_kb_cat cat, ".$tableprefix."_programm prog where prog.prognr=dat.programm and (cat.catnr=dat.category or dat.category=0)";
if(isset($filterprog) && ($filterprog>0))
	$sql.="and dat.programm=$filterprog ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="and prog.language='$filterlang' ";
$sql.="group by dat.articlenr ";
switch($sorting)
{
	case 12:
		$sql.="order by dat.articlenr desc, dat.programm asc, dat.category asc";
		break;
	case 21:
		$sql.="order by dat.heading asc, dat.programm asc, dat.category asc";
		break;
	case 22:
		$sql.="order by dat.heading desc, dat.programm asc, dat.category asc";
		break;
	case 31:
		$sql.="order by prog.programmname asc, prog.language asc, dat.category asc, dat.displaypos asc";
		break;
	case 32:
		$sql.="order by prog.programmname desc, prog.language asc, dat.category asc, dat.displaypos asc";
		break;
	case 41:
		$sql.="order by cat.catname asc";
		break;
	case 42:
		$sql.="order by cat.catname desc";
		break;
	case 51:
		$sql.="order by prog.language asc";
		break;
	case 52:
		$sql.="order by prog.language desc";
		break;
	case 61:
		$sql.="order by dat.views asc";
		break;
	case 62:
		$sql.="order by dat.views desc";
		break;
	default:
		$sql.="order by dat.articlenr asc, dat.programm asc, dat.category asc";
		break;
}
if(!$result = faqe_db_query($sql, $db))
	db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	$maxsortcol=6;
	echo "<form name=\"listform\" onsubmit=\"return checkform();\" method=\"post\" action=\"kb_download2.php\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">&nbsp;</td>";
	echo "<td align=\"center\" width=\"5%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"20%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_heading</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"15%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_programm</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"15%\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_category</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"15%\">";
	$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_language</b></a>";
	echo getSortMarker($sorting, 5, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"5%\">";
	$sorturl=getSortURL($sorting, 6, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_views</b></a>";
	echo getSortMarker($sorting, 6, $maxsortcol);
	echo "</td></tr>";
	do {
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = faqe_db_query($modsql, $db))
				db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(faqe_db_num_rows($modresult,$db)>0)
				$is_mod=true;
			else
				$is_mod=false;
		}
		if(($admin_rights == 2) && ($is_mod==false) && ($hideunassigned==1))
			continue;
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
			db_die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($tempresult))
		{
			$proglang=$l_none;
			$progname=$l_none;
		}
		else
		{
			$proglang=$temprow["language"];
			$progname=display_encoded($temprow["programmname"]);
		}
		$act_id=$myrow["articlenr"];
		if($myrow["lastedited"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		echo "<td class=\"inputrow\" align=\"center\"><input type=\"checkbox\" name=\"kbnrs[]\" value=\"".$myrow["articlenr"]."\"></td>";
		echo "<td align=\"center\">".$myrow["articlenr"]."</td>";
		echo "<td>".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		echo "<td align=\"center\">$progname</td>";
		echo "<td align=\"center\">";
		if($myrow["subcategory"]>0)
		{
			$tempsql = "select subcat.*, cat.catname as maincat from ".$tableprefix."_kb_subcat subcat, ".$tableprefix."_kb_cat cat where subcat.catnr=".$myrow["subcategory"]." and cat.catnr=subcat.category";
			if(!$tempresult = faqe_db_query($tempsql, $db))
			    die("Could not connect to the database.");
			if ($temprow = faqe_db_fetch_array($tempresult))
			{
				echo display_encoded($temprow["maincat"])." : ".display_encoded($temprow["catname"]);
			}
			else
				echo $l_none;
		}
		else if($myrow["category"]>0)
		{
			$tempsql = "select * from ".$tableprefix."_kb_cat where catnr=".$myrow["category"];
			if(!$tempresult = faqe_db_query($tempsql, $db))
			    die("Could not connect to the database.");
			if ($temprow = faqe_db_fetch_array($tempresult))
			{
				echo display_encoded($temprow["catname"]);
			}
			else
				echo $l_none;
		}
		else
			echo $l_none;
		echo "</td>";
		echo "<td align=\"center\">$proglang</td>";
		echo "<td align=\"right\">".$myrow["views"]."</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"7\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"download\">";
	echo "<input class=\"faqebutton\" type=\"submit\" value=\"$l_download_selected\">";
	echo "\n&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"faqebutton\" type=\"button\" onclick=\"checkAll(document.listform)\" value=\"$l_checkall\">\n";
	echo "\n&nbsp;&nbsp;<input class=\"faqebutton\" type=\"button\" onclick=\"uncheckAll(document.listform)\" value=\"$l_uncheckall\">\n";
	echo "</td></tr>";
	echo "</form>";
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
	include('./includes/kb_filterboxes.inc');
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("kb.php?$langvar=$act_lang")."\">$l_articlelist</a></div>";
include('./trailer.php');
?>