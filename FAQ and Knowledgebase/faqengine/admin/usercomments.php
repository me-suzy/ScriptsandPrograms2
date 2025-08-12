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
$page_title=$l_usercomments;
$page="usercomments";
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
		if(faqe_array_key_exists($admcookievals,"uc_filterprog"))
			$filterprog=$admcookievals["uc_filterprog"];
		if(faqe_array_key_exists($admcookievals,"uc_filterlang"))
			$filterlang=$admcookievals["uc_filterlang"];
		if(faqe_array_key_exists($admcookievals,"uc_sorting"))
			$sorting=$admcookievals["uc_sorting"];
	}
}
if(!isset($sorting))
	$sorting=11;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select c.*, dat.heading, prog.programmname, prog.prognr, cat.categoryname  from ".$tableprefix."_comments c, ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where ";
		$sql .="dat.category=cat.catnr and prog.prognr=cat.programm and dat.faqnr=c.faqnr and c.commentnr=$input_commentnr";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$mod_sql ="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["prognr"]." and usernr=".$userdata["usernr"];
		if(!$mod_result = faqe_db_query($mod_sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if( ($admin_rights<3) && (!$modrow=faqe_db_fetch_array($mod_result)) )
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displayusercomment?></b></td></tr>
<tr class="inforow"><td align="center" colspan="2"><b><?php echo display_encoded($myrow["programmname"]).":".display_encoded($myrow["categoryname"]).":".undo_html_ampersand(stripslashes($myrow["heading"]))?></b></td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_email?>:</td><td width="80%"><?php echo $myrow["email"]?></td></tr>
<?php
		list($mydate,$mytime)=explode(" ",$myrow["postdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		$commenttext=display_encoded($myrow["comment"]);
		$commenttext = str_replace("\n", "<BR>", $commenttext);
?>
<tr class="displayrow"><td align="right"><?php echo $l_date?>:</td><td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td><td><?php echo $myrow["ipadr"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_comment?>:</td><td><?php echo $commenttext?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_views?>:</td><td><?php echo $myrow["views"]?></td></tr>
<?php
		if($ratecomments==1)
		{
			$rating=$myrow["rating"];
			$ratingcount=$myrow["ratingcount"];
			if($ratingcount>0)
			{
				$displayrating=round($rating/$ratingcount,2);
				$displayrating.=" ($ratingcount)";
			}
			else
				$displayrating= "--";
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_rating?>:</td><td><?php echo $displayrating?></td></tr>
<?php
		}
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_usercomments</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_comments where (commentnr=$input_commentnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_usercomments</a></div>";
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights<3)
{
	$sql = "select c.*, dat.heading, prog.programmname, prog.prognr, cat.categoryname, prog.language from ".$tableprefix."_comments c, ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm_admins pa, ".$tableprefix."_programm prog ";
	$sql.= "where pa.usernr=$act_usernr and cat.programm=pa.prognr and prog.prognr=cat.programm ";
	$sql.= "and dat.category=cat.catnr and dat.faqnr=c.faqnr ";
}
else
{
	$sql = "select c.*, dat.heading, prog.programmname, prog.prognr, cat.categoryname, prog.language from ".$tableprefix."_comments c, ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where ";
	$sql.= "dat.category=cat.catnr and prog.prognr=cat.programm and dat.faqnr=c.faqnr ";
}
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="and prog.language='$filterlang' ";
if(isset($filterprog) && ($filterprog>0))
	$sql.="and prog.prognr=$filterprog ";
if(isset($filtercat) && ($filtercat>0))
	$sql.="and cat.catnr=$filtercat ";
switch($sorting)
{
	case 12:
		$sql.="order by cat.categoryname desc, dat.heading desc, c.postdate desc ";
		break;
	case 21:
		$sql.="order by prog.language asc, prog.programmname asc, c.postdate desc ";
		break;
	case 22:
		$sql.="order by prog.language asc, prog.programmname desc, c.postdate desc ";
		break;
	case 31:
		$sql.="order by c.postdate asc, prog.programmname asc ";
		break;
	case 32:
		$sql.="order by c.postdate desc, prog.programmname asc ";
		break;
	case 41:
		$sql.="order by c.views asc, c.postdate desc ";
		break;
	case 42:
		$sql.="order by c.views desc, c.postdate desc ";
		break;
	default:
		$sql.="order by cat.categoryname asc, dat.heading asc, c.postdate desc ";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database. (".faqe_db_error().")");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$maxsortcol=4;
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterprog))
		$baseurl.="&filterprog=$filterprog";
	if(isset($filtercat))
		$baseurl.="&filtercat=$filtercat";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_faq</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_progname</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_date</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" class=\"rowheadings\"><b>$l_email</b></td>";
	echo "<td align=\"center\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_views</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	if($ratecomments==1)
		echo "<td align=\"center\" width=\"5%\" class=\"rowheadings\"><b>$l_rated</b></td>";
?>
<td>&nbsp;</td></tr>
<?php
		do {
			if($myrow["postdate"]>$userdata["lastlogin"])
				echo "<tr class=\"displayrownew\">";
			else
				echo "<tr class=\"displayrow\">";
			$act_id=$myrow["commentnr"];
			$mod_sql ="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["prognr"]." and usernr=".$userdata["usernr"];
			if(!$mod_result = faqe_db_query($mod_sql, $db))
		  	  die("Could not connect to the database.");
			if($modrow=faqe_db_fetch_array($mod_result))
				$ismod=1;
			else
				$ismod=0;
			list($mydate,$mytime)=explode(" ",$myrow["postdate"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
				$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
			else
				$displaydate="";
			echo "<td width=\"15%\" align=\"center\">".display_encoded($myrow["categoryname"]).":".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
			echo "<td width=\"20%\" align=\"center\">".display_encoded($myrow["programmname"])." [".stripslashes($myrow["language"])."]</td>";
			echo "<td width=\"20%\" align=\"center\">$displaydate</td>";
			echo "<td width=\"20%\" align=\"center\">".$myrow["email"]."</td>";
			echo "<td width=\"10%\" align=\"center\">".$myrow["views"]."</td>";
			if($ratecomments==1)
			{
				echo "<td align=\"center\">";
				if($myrow["ratingcount"]>0)
					echo "*";
				else
					echo "&nbsp;";
				echo "</td>";
			}
			echo "<td>";
			if(($admin_rights > 2) || ($ismod==1))
			{
				echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&$langvar=$act_lang&input_commentnr=$act_id")."\">";
				echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
				echo "&nbsp; ";
				echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_commentnr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
			}
		echo "</td></tr>";
   } while($myrow = faqe_db_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights>1)
{
	include('./includes/faq_filterboxes.inc');
}
}
include('./trailer.php');
?>