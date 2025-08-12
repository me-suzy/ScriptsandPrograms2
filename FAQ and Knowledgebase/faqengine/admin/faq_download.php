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
$page_title=$l_faqdownload;
$page="faq_download";
require_once('./heading.php');
if($admin_rights < 2)
	die("$l_functionnotallowed");
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
		if(faqe_array_key_exists($admcookievals,"faqdl_filterprog"))
			$filterprog=$admcookievals["faqdl_filterprog"];
		if(faqe_array_key_exists($admcookievals,"faqdl_filtercat"))
			$filtercat=$admcookievals["faqdl_filtercat"];
		if(faqe_array_key_exists($admcookievals,"faqdl_filterlang"))
			$filterlang=$admcookievals["faqdl_filterlang"];
		if(faqe_array_key_exists($admcookievals,"faqdl_sorting"))
			$sorting=$admcookievals["faqdl_sorting"];
	}
}
if(!isset($filterprog))
	$filterprog=0;
if(!isset($filterlang))
	$filterlang="none";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(!isset($sorting))
{
	if($faqlistshortcuts==1)
		$sorting=21;
	else
		$sorting=11;
}
if(($faqlistshortcuts==1) && ($sorting==1))
{
	echo "<tr class=\"shortcutbar\"><td colspan=\"7\" align=\"center\">";
	for($i=65;$i<91;$i++)
		echo "<a class=\"shortcutbar\" href=\"#".chr($i)."\">".chr($i)."</a> ";
	echo "</td></tr>";
	$startchar="";
}
// Display list of actual FAQ
$sql = "select dat.* from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where dat.category=cat.catnr and cat.programm=prog.prognr and dat.linkedfaq=0 ";
if(isset($filterprog) && ($filterprog>0))
	$sql.="and prog.prognr=$filterprog ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="and prog.language='$filterlang' ";
if(isset($filtercat) && ($filtercat>0))
	$sql.="and cat.catnr=$filtercat ";
$sql.="group by dat.faqnr ";
switch($sorting)
{
	case 12:
		$sql.="order by dat.faqnr desc";
		break;
	case 21:
		$sql.="order by dat.heading asc";
		break;
	case 22:
		$sql.="order by dat.heading desc";
		break;
	case 31:
		$sql.="order by cat.categoryname asc";
		break;
	case 32:
		$sql.="order by cat.categoryname desc";
		break;
	case 41:
		$sql.="order by prog.programmname asc";
		break;
	case 42:
		$sql.="order by prog.programmname desc";
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
		$sql.="order by dat.faqnr asc";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.".faqe_db_error());
}
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
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	$maxsortcol=6;
	echo "<form name=\"listform\" onsubmit=\"return checkform();\" method=\"post\" action=\"faq_download2.php\">";
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
	echo "<td align=\"center\" width=\"30%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_heading</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_category</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_programm</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_language</b></a>";
	echo getSortMarker($sorting, 5, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"10%\"><b>";
	$sorturl=getSortURL($sorting, 6, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_views</b></a>";
	echo getSortMarker($sorting, 6, $maxsortcol);
	echo "</td></tr>";
	do {
		$tempsql = "select * from ".$tableprefix."_category where (catnr=".$myrow["category"].")";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = faqe_db_fetch_array($tempresult))
		{
			$prognr=0;
			$catname=$l_undefined;
		}
		else
		{
			$prognr=$temprow["programm"];
			$catname=display_encoded($temprow["categoryname"]);
		}
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=$prognr)";
		if(!$tempresult = faqe_db_query($tempsql, $db)) {
		    die("Could not connect to the database.");
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
		$act_id=$myrow["faqnr"];
		if($myrow["editdate"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		if(($faqlistshortcuts==1) && ($sorting==1))
		{
			$asc_heading=undo_htmlentities($myrow["heading"]);
			$firstchar=substr($asc_heading,0,1);
			$firstchar=strtoupper($firstchar);
			if(($firstchar!=$startchar) && (ord($firstchar)>64) && (ord($firstchar)<91))
			{
				$startchar=$firstchar;
				echo "<a name=\"#$startchar\"></a>";
			}
		}
		if($myrow["subcategory"]>0)
		{
			$subcatsql="select * from ".$tableprefix."_subcategory where catnr=".$myrow["subcategory"];
			if(!$subcatresult = faqe_db_query($subcatsql, $db))
				db_die("Unable to connect to database.");
			if($subcatrow=faqe_db_fetch_array($subcatresult))
				$subcatname=display_encoded($subcatrow["categoryname"]);
		}
		else
			$subcatname="";
		echo "<td class=\"inputrow\" align=\"center\"><input type=\"checkbox\" name=\"faqnrs[]\" value=\"".$myrow["faqnr"]."\"></td>";
		echo "<td align=\"center\">".$myrow["faqnr"]."</td>";
		echo "<td>".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		if($subcatname)
			echo "<td align=\"center\">$catname : $subcatname</td>";
		else
			echo "<td align=\"center\">$catname</td>";
		echo "<td align=\"center\">$progname</td>";
		echo "<td align=\"center\">$proglang</td>";
		echo "<td align=\"right\">".$myrow["views"]."</td>";
		echo "</tr>";
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
	include('./includes/faq_filterboxes.inc');
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("faq.php?$langvar=$act_lang")."\">$l_faqlist</a></div>";
include('./trailer.php');
?>