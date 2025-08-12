<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title="$l_viewcounts ($l_events)";
$page="evviews";
require_once('./heading.php');
if(!isset($filterlang))
	$filterlang="all";
if(!isset($filtercat))
	$filtercat=-1;
if(!isset($start))
	$start=0;
if(!isset($sorting))
	$sorting=52;
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
		if(sn_array_key_exists($admcookievals,"evviews_filterlang"))
			$filterlang=$admcookievals["evviews_filterlang"];
		if(sn_array_key_exists($admcookievals,"evviews_filtercat"))
			$filtercat=$admcookievals["evviews_filtercat"];
		if(sn_array_key_exists($admcookievals,"evviews_sorting"))
			$sorting=$admcookievals["evviews_sorting"];
	}
}
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="resetviews")
	{
		$sql="update ".$tableprefix."_events set views=0 where eventnr=$input_eventnr";
		if(!$result=mysql_query($sql,$db))
		{
			echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
			echo "<tr><TD BGCOLOR=\"#000000\">";
			echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
	}
	if($mode=="resetall")
	{
		$sql="update ".$tableprefix."_events set views=0";
		if(!$result=mysql_query($sql,$db))
		{
			echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
			echo "<tr><TD BGCOLOR=\"#000000\">";
			echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
	}
}
$sql="select * from ".$tableprefix."_events where views>$minviews ";
if(isset($filterlang) && ($filterlang!="all"))
	$sql.="and lang='$filterlang' ";
if(isset($filtercat) && ($filtercat!=-1))
	$sql.="and category=$filtercat ";
switch($sorting)
{
	case 11:
		$sql.="order by eventnr asc";
		break;
	case 12:
		$sql.="order by eventnr desc";
		break;
	case 21:
		$sql.="order by heading asc";
		break;
	case 22:
		$sql.="order by heading desc";
		break;
	case 31:
		$sql.="order by date asc";
		break;
	case 32:
		$sql.="order by date desc";
		break;
	case 41:
		$sql.="order by lang asc";
		break;
	case 42:
		$sql.="order by lang desc";
		break;
	case 51:
		$sql.="order by views asc";
		break;
	default:
		$sql.="order by views desc";
		break;
}
if(!$result=mysql_query($sql,$db))
{
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
$numentries=mysql_num_rows($result);
if($numentries<1)
{
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"displayrow\"><td align=\"center\">$l_noentries</td></tr>";
	echo "</table></td></tr></table>";
}
else
{
	if($admepp>0)
	{
		if(($start>0) && ($numentries>$admepp))
		{
			$sql .=" limit $start,$admepp";
		}
		else
		{
			$sql .=" limit $admepp";
		}
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
			echo "<tr><TD BGCOLOR=\"#000000\">";
			echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
			if(($admepp+$start)>$numentries)
				$displayresults=$numentries;
			else
				$displayresults=($admepp+$start);
			$displaystart=$start+1;
			$displayend=$displayresults;
			echo "<tr class=\"pagenav\"><td align=\"center\">";
			echo "<a id=\"list\"><b>$l_page ".ceil(($start/$admepp)+1)."/".ceil(($numentries/$admepp))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b></a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if(($admepp>0) && ($numentries>$admepp))
	{
		$baselink="$act_script_url?$langvar=$act_lang";
		if(isset($filterlang))
			$baselink.="&filterlang=$filterlang";
		if(isset($filtercat))
			$baselink.="&filtercat=$filtercat";
		if(isset($sorting))
			$baselink.="&sorting=$sorting";
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"pagenav\"><td align=\"center\">";
		echo "<b>$l_page</b> ";
		if(floor(($start+$admepp)/$admepp)>1)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
			echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
			echo "</a> ";
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
			echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
			echo "</a> ";
		}
		for($i=1;$i<($numentries/$admepp)+1;$i++)
		{
			if(floor(($start+$admepp)/$admepp)!=$i)
			{
				echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
				echo "#list\"><b>[$i]</b></a> ";
			}
			else
				echo "<b>($i)</b> ";
		}
		if($start < (($i-2)*$admepp))
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
			echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
			echo "</a> ";
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
			echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
			echo "</a> ";
		}
		echo "</font></td></tr></table></td></tr></table>";
	}
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	$maxsortcol=5;
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><a id=\"resultlist\"></a><b>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "#</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_entry</a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_date</a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	echo $l_category;
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_language</a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_views</a>";
	echo getSortMarker($sorting, 5, $maxsortcol);
	echo "</b></td>";
	echo "<td>&nbsp;</td>";
	echo "</tr>";
	while($myrow=mysql_fetch_array($result))
	{
		$act_id=$myrow["eventnr"];
		if($myrow["linkeventnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$entrydata=$tmprow;
		}
		$newstext = stripslashes($entrydata["text"]);
		$newstext = undo_htmlspecialchars($newstext);
		if($admentrychars>0)
		{
			$newstext=undo_htmlentities($newstext);
			$newstext=strip_tags($newstext);
			$newstext=substr($newstext,0,$admentrychars);
			$newstext.="[...]";
		}
		if($admonlyentryheadings==0)
		{
			if($entrydata["heading"])
				$displaytext="<b>".$entrydata["heading"]."</b><br>".$newstext;
			else
				$displaytext=$newstext;
		}
		else
		{
			if($entrydata["heading"])
				$displaytext="<b>".$entrydata["heading"]."</b>";
			else
			{
				$displaytext=strip_tags($entrydata["text"]);
				if($admentrychars>0)
					$displaytext=substr($displaytext,0,$admentrychars);
				else
					$displaytext=substr($displaytext,0,20);
				$displaytext.="[...]";
			}
		}
		$allowactions=false;
		if($admrestrict==1)
		{
			if(($admin_rights>2) || bittst($userdata["addoptions"],BIT_5))
				$allowactions=true;
			else
			{
				if($entrydata["posterid"]==$userdata["usernr"])
					$allowactions=true;
			}
		}
		else
			if($admin_rights > 1)
				$allowactions=$allowadding;
		echo "<tr>";
		echo "<td class=\"displayrow\" align=\"center\" width=\"5%\" valign=\"top\">";
		if($myrow["linkeventnr"]!=0)
			echo "<img src=\"gfx/link_small.gif\" border=\"0\" align=\"top\" title=\"$l_islink: ".$myrow["linknewsnr"]."\" alt=\"$l_islink: ".$myrow["linknewsnr"]."\"> ";
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where linkeventnr=".$myrow["eventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(mysql_num_rows($tmpresult)>0)
				echo "<img src=\"gfx/link_target.gif\" border=\"0\" align=\"top\" title=\"$l_islinktarget\" alt=\"$l_islinktarget\"> ";
		}
		if($myrow["linkeventnr"]==0)
			$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow["eventnr"]);
		else
			$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow["linkeventnr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["eventnr"];
		echo "</a></td>";
		echo "<td class=\"newsentry\" align=\"left\" width=\"50%\">";
		echo "$displaytext</td>";
		echo "<td class=\"eventdate\" align=\"center\" width=\"20%\" valign=\"top\">";
		list($curdate,$curtime)=explode(" ",$entrydata["date"]);
		list($curyear,$curmonth,$curday)=explode("-",$curdate);
		list($curhour,$curmin,$cursec)=explode(":",$curtime);
		$tmpdate=mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear);
		if(($curhour>0) || ($curmin>0) || ($cursec>0))
			$displaydate=date($l_admdateformat,$tmpdate);
		else
			$displaydate=date($l_admdateformat2,$tmpdate);
		echo $displaydate."</td>";
		echo "<td class=\"displayrow\" align=\"center\" width=\"10%\" valign=\"top\">";
		if($myrow["category"]>0)
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				echo $l_unknown;
			else
				echo display_encoded($tmprow["catname"]);
		}
		else
			echo $l_general;
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"right\" width=\"10%\" valign=\"top\">";
		echo $myrow["lang"];
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"right\" width=\"5%\" valign=\"top\">";
		echo $myrow["views"];
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"left\" valign=\"top\">";
		$baseurl="$act_script_url?$langvar=$act_lang";
		if(isset($start))
			$baseurl.="&start=$start";
		if(isset($filtercat))
			$baseurl.="&filtercat=$filtercat";
		if(isset($filterlang))
			$baseurl.="&filterlang=$filterlang";
		if(isset($sorting))
			$baseurl.="&sorting=$sorting";
		if($allowactions)
		{
			if($myrow["linkeventnr"]==0)
				echo "<a class=\"listlink\" href=\"".do_url_session($baseurl."&mode=resetviews&input_eventnr=$act_id")."\"><img src=\"gfx/clear.gif\" border=\"0\" title=\"$l_resetviews\" alt=\"$l_resetviews\"></a>";
			else
				echo "&nbsp;";
		}
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table></td></tr></table>";
	if(($admepp>0) && ($numentries>$admepp))
	{
		$baselink="$act_script_url?$langvar=$act_lang";
		if(isset($filterlang))
			$baselink.="&filterlang=$filterlang";
		if(isset($filtercat))
			$baselink.="&filtercat=$filtercat";
		if(isset($sorting))
			$baselink.="&sorting=$sorting";
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"pagenav\"><td align=\"center\">";
		echo "<b>$l_page</b> ";
		if(floor(($start+$admepp)/$admepp)>1)
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=0")."#list\">";
			echo "<img src=\"../gfx/first.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
			echo "</a> ";
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start-$admepp))."#list\">";
			echo "<img src=\"../gfx/prev.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
			echo "</a> ";
		}
		for($i=1;$i<($numentries/$admepp)+1;$i++)
		{
			if(floor(($start+$admepp)/$admepp)!=$i)
			{
				echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-1)*$admepp));
				echo "#list\"><b>[$i]</b></a> ";
			}
			else
				echo "<b>($i)</b> ";
		}
		if($start < (($i-2)*$admepp))
		{
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".($start+$admepp))."#list\">";
			echo "<img src=\"../gfx/next.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
			echo "</a> ";
			echo "<a class=\"pagenav\" href=\"".do_url_session("$baselink&start=".(($i-2)*$admepp))."#list\">";
			echo "<img src=\"../gfx/last.gif\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
			echo "</a> ";
		}
		echo "</font></td></tr></table></td></tr></table>";
	}
	if($admin_rights>2)
	{
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"actionrow\">";
		echo "<td align=\"center\">";
		$baseurl="$act_script_url?$langvar=$act_lang";
		if(isset($start))
			$baseurl.="&start=$start";
		if(isset($filtercat))
			$baseurl.="&filtercat=$filtercat";
		if(isset($filterlang))
			$baseurl.="&filterlang=$filterlang";
		if(isset($sorting))
			$baseurl.="&sorting=$sorting";
		echo "<a href=\"".do_url_session($baseurl."&mode=resetall")."\">$l_resetallcounts</a>";
		echo "</td></tr>";
		echo "</table></td></tr></table>";
	}
}
include('./includes/nviews_filter.inc');
include('./trailer.php');
?>
