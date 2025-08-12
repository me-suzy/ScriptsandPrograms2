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
$page_title="$l_topratings ($l_news)";
$page="ntop";
require_once('./heading.php');
if(!isset($filterlang))
	$filterlang="all";
if(!isset($filtercat))
	$filtercat=-1;
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
		if(sn_array_key_exists($admcookievals,"ntop_filterlang"))
			$filterlang=$admcookievals["ntop_filterlang"];
		if(sn_array_key_exists($admcookievals,"ntop_filtercat"))
			$filtercat=$admcookievals["ntop_filtercat"];
		if(sn_array_key_exists($admcookievals,"ntop_limit"))
			$limitentries=$admcookievals["ntop_limit"];
	}
}
if(!isset($limitentries))
	$limitentries=10;
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="resetratings")
	{
		$sql="update ".$tableprefix."_data set ratings=0, ratingcount=0 where newsnr=$input_newsnr";
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
		$sql="update ".$tableprefix."_data set ratings=0, ratingcount=0";
		if(!$result=mysql_query($sql,$db))
		{
			echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
			echo "<tr><TD BGCOLOR=\"#000000\">";
			echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}
	}
}
$sql="select * from ".$tableprefix."_data where ratingcount>0 ";
if(isset($filterlang) && ($filterlang!="all"))
	$sql.="and lang='$filterlang' ";
if(isset($filtercat) && ($filtercat!=-1))
	$sql.="and category=$filtercat ";
$sql.="order by (ratings/ratingcount) desc ";
if(isset($limitentries) && ($limitentries!=-1))
	$sql.="limit $limitentries ";
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
	echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><a id=\"resultlist\"></a><b>#";
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	echo "$l_entry";
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	echo "$l_date";
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	echo $l_category;
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	echo "$l_language";
	echo "</b></td>";
	echo "<td align=\"center\"><b>";
	echo "$l_ratings";
	echo "</b></td>";
	echo "<td>&nbsp;</td>";
	echo "</tr>";
	while($myrow=mysql_fetch_array($result))
	{
		$act_id=$myrow["newsnr"];
		if($myrow["linknewsnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
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
		if($myrow["linknewsnr"]!=0)
			echo "<img src=\"gfx/link_small.gif\" border=\"0\" align=\"top\" title=\"$l_islink: ".$myrow["linknewsnr"]."\" alt=\"$l_islink: ".$myrow["linknewsnr"]."\"> ";
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where linknewsnr=".$myrow["newsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(mysql_num_rows($tmpresult)>0)
				echo "<img src=\"gfx/link_target.gif\" border=\"0\" align=\"top\" title=\"$l_islinktarget\" alt=\"$l_islinktarget\"> ";
		}
		if($myrow["linknewsnr"]==0)
			$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["newsnr"]);
		else
			$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["linknewsnr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["newsnr"];
		echo "</a></td>";
		echo "<td class=\"newsentry\" align=\"left\" width=\"50%\">";
		echo "$displaytext</td>";
		echo "<td class=\"newsdate\" align=\"center\" width=\"15%\" valign=\"top\">";
		list($mydate,$mytime)=explode(" ",$entrydata["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		echo "$displaydate</td>";
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
		echo round($myrow["ratings"]/$myrow["ratingcount"],2)." (";
		echo $myrow["ratingcount"].")";
		echo "</td>";
		echo "<td class=\"displayrow\" align=\"left\" valign=\"top\">";
		$baseurl="$act_script_url?$langvar=$act_lang";
		if(isset($start))
			$baseurl.="&start=$start";
		if(isset($filtercat))
			$baseurl.="&filtercat=$filtercat";
		if(isset($filterlang))
			$baseurl.="&filterlang=$filterlang";
		if(isset($limitentries))
			$baseurl.="&limitentries=$limitentries";
		if($allowactions)
		{
			if($myrow["linknewsnr"]==0)
				echo "<a class=\"listlink\" href=\"".do_url_session($baseurl."&mode=resetratings&input_newsnr=$act_id")."\">$l_resetratings</a>";
			else
				echo "&nbsp;";
		}
		else
			echo "&nbsp;";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table></td></tr></table>";
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
		if(isset($limitentries))
			$baseurl.="&limitentries=$limitentries";
		echo "<a href=\"".do_url_session($baseurl."&mode=resetall")."\">$l_resetallratings</a>";
		echo "</td></tr>";
		echo "</table></td></tr></table>";
	}
}
include('./includes/ntop_filter.inc');
include('./trailer.php');
?>
