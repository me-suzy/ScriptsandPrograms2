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
if(!isset($newslang))
	$newslang=$act_lang;
require_once('./auth.php');
$page_title=$l_listofcomments;
$page="clist";
require_once('./heading.php');
if($admin_rights < 2)
{
	die($l_functionotallowed);
}
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$subscriptionsendmode=$myrow["subscriptionsendmode"];
	$enablesubscriptions=$myrow["enablesubscriptions"];
	$subject=$myrow["subject"];
	$simpnewsmail=$myrow["simpnewsmail"];
}
else
{
	$subscriptionsendmode=0;
	$enablesubscriptions=0;
	$subject="News";
	$simpnewsmail="simpnews@foo.bar";
}
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
		if(sn_array_key_exists($admcookievals,"cl_sorting"))
			$sorting=$admcookievals["cl_sorting"];
	}
}
if(!isset($sorting))
	$sorting=22;
if(!isset($start))
	$start=0;
if(isset($mode))
{
	if($mode=="del")
	{
		$sql = "delete from ".$tableprefix."_comments where commentnr=$input_commentnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		else
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center"><?php echo $l_entrydeleted?></td></tr>
<?php
			echo "</table></td></tr></table>";
		}
	}
	if($mode=="edit")
	{
		$sql="select * from ".$tableprefix."_comments where commentnr=$input_commentnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
			die("No such entry");
		$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["entryref"]);
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_commentnr" value="<?php echo $input_commentnr?>">
<input type="hidden" name="mode" value="update">
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_editcomment?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_newsentry?>:</td><td>
<a class="shdetailslink" href="javascript:openWindow3('<?php echo $showurl?>','nShow',20,20,400,200);"><?php echo $myrow["entryref"]?></a></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_poster?>:</td><td><?php echo $myrow["poster"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td><td>
<a href="mailto:<?php echo $myrow["email"]?>"><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_date?>:</td><td>
<?php echo $displaydate?></td></tr>
<?php
		echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_text:</td><td>";
		echo "<textarea class=\"sninput\" name=\"commenttext\" rows=\"10\" cols=\"50\">";
		echo undo_htmlentities($myrow["comment"]);
		echo "</textarea>";
		echo "<tr class=\"actionrow\"><td colspan=\"2\" align=\"center\">";
		echo "<input type=\"submit\" class=\"snbutton\" value=\"$l_update\" name=\"submit\">";
		echo "</td></tr></form>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("comments.php?$langvar=$act_lang&entryref=".$myrow["entryref"])."\">$l_listofcomments</a></div>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("news.php?$langvar=$act_lang")."\">$l_news</a></div>";
		include('./trailer.php');
		exit;
	}
	if($mode=="update")
	{
		$sql="update ".$tableprefix."_comments set comment='$commenttext' where commentnr=$input_commentnr";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		else
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center"><?php echo $l_entryupdated?></td></tr>
<?php
			echo "</table></td></tr></table>";
		}
	}
}
$sql = "select * from ".$tableprefix."_comments ";
switch($sorting)
{
	case 11:
		$sql.="order by commentnr asc";
		break;
	case 12:
		$sql.="order by commentnr desc";
		break;
	case 21:
		$sql.="order by enterdate asc";
		break;
	case 22:
		$sql.="order by enterdate desc";
		break;
	case 31:
		$sql.="order by poster asc";
		break;
	case 32:
		$sql.="order by poster desc";
		break;
	case 41:
		$sql.="order by email asc";
		break;
	case 42:
		$sql.="order by email desc";
		break;
	case 51:
		$sql.="order by entryref asc";
		break;
	case 52:
		$sql.="order by entryref desc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
$numentries=mysql_num_rows($result);
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
	$baselink="$act_script_url?$langvar=$act_lang&eventlang=$eventlang&catnr=$catnr";
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
if($myrow = mysql_fetch_array($result))
{
	$maxsortcol=5;
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><a id=\"resultlist\"></a><b>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "#</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_date</a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\" width=\"10%\"><b>";
	$sorturl=getSortURL($sorting, 5, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_newsentry</a>";
	echo getSortMarker($sorting, 5, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_poster</a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl, "resultlist");
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "$l_email</a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_text</b></td>";
	echo "<td width=\"2%\">&nbsp;</td>";
	echo "<td width=\"2%\">&nbsp;</td></tr>";
	do{
		$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$myrow["entryref"]);
		list($mydate,$mytime)=explode(" ",$myrow["enterdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		$act_id=$myrow["commentnr"];
		$commenttext=display_encoded($myrow["comment"]);
		if($myrow["enterdate"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">";
		echo $myrow["commentnr"]."</td>";
		echo "<td align=\"center\">";
		echo $displaydate."</td>";
		echo "<td align=\"center\">";
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["entryref"];
		echo "</a></td>";
		echo "<td align=\"left\">";
		echo $myrow["poster"];
		echo "</td>";
		echo "<td align=\"left\">";
		echo $myrow["email"];
		echo "</td>";
		echo "<td align=\"left\">";
		echo "$commenttext</td>";
		if($admin_rights > 1)
		{
			echo "<td align=\"center\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=edit&input_commentnr=$act_id")."\"><img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a></td>";
			echo "<td align=\"center\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=del&input_commentnr=$act_id")."\"><img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a></td>";
		}
		echo "</tr>";
	}while($myrow=mysql_fetch_array($result));
	echo "</table></td></tr></table>";
	if(($admepp>0) && ($numentries>$admepp))
	{
		$baselink="$act_script_url?$langvar=$act_lang&eventlang=$eventlang&catnr=$catnr";
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
}
else
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<tr class=\"displayrow\"><td align=\"center\">$l_noentries</td></tr>";
	echo "</table></td></tr></table>";
}
include('./trailer.php')
?>
