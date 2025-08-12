<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
include_once('./includes/date_selectors.inc');
if(!isset($category))
	$category=-1;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
require_once('./includes/search_func.inc');
if(!isset($startday))
	$startday=date("d");
if(!isset($startmonth))
	$startmonth=date("m");
if(!isset($startyear))
	$startyear=date("Y");
$startdate=$startyear."-".$startmonth."-".$startday;
if(!isset($endday))
	$endday=date("d");
if(!isset($endmonth))
	$endmonth=date("m");
if(!isset($endyear))
	$endyear=date("Y");
$enddate=$endyear."-".$endmonth."-".$endday;
if($eventheading)
	$pageheading=$eventheading;
else
	$pageheading=$l_search;
if(!isset($srchcat))
	$srchcat=-1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<meta name="fid" content="<?php echo $fid?>">
<?php
if(file_exists("metadata.php"))
	include ("metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $pageheading?></title>
<?php
}
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
include_once('./includes/js/search.inc');
?>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>" <?php echo $addbodytags?>>
<?php
if($enableevsearch==0)
	die($l_functiondisabled);
if($usecustomheader==1)
{
	if(($headerfile) && ($headerfilepos==0))
	{
		if(is_phpfile($headerfile))
			include_once($headerfile);
		else
			file_output($headerfile);
		if($customheader)
			echo "<br>\n";
	}
	if($customheader)
		echo "$customheader\n";
	if(($headerfile) && ($headerfilepos==1))
	{
		if($customheader)
			echo "<br>\n";
		if(is_phpfile($headerfile))
			include_once($headerfile);
		else
			file_output($headerfile);
	}
}
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($eventheading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $eventheading?></b></font></td></tr>
<?php
}
if(!isset($backurl))
{
	$backurl="eventcal.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category";
	if(isset($start))
		$backurl.="&amp;start=$start";
}
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" width="99%"><a name="top"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $l_search?></b></font></a></td>
<td align="center" valign="middle" width="1%"><a href="<?php echo $backurl?>"><img src="<?php echo "$url_gfx/$backpic"?>" border="0" align="absmiddle" title="<?php echo $l_back?>" alt="<?php echo $l_back?>"></a>&nbsp;<a href="help/<?php echo $act_lang?>/search.php?<?php echo "$langvar=$act_lang"?>&amp;layout=<?php echo $layout?>" target="_blank"><img src="<?php echo "$url_gfx/$helppic"?>" border="0" align="absmiddle" title="<?php echo $l_help?>" alt="<?php echo $l_help?>"></a></td></tr>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $contentbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
?>
</td></tr></table>
<?php
		include ("./includes/footer.inc");
		exit;
	}
}
if(!isset($searchvalues))
	$searchvalues="";
if(!isset($searchpart))
	$searchpart=0;
?>
</table>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<form name="searchform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="mode" value="search">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="category" value="<?php echo $category?>">
<?php
if(isset($start))
	echo "<input type=\"hidden\" name=\"start\" value=\"$start\">";
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<tr bgcolor="<?php echo $contentbgcolor?>"><td align="right" width="20%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_searchtext?>:</font></td>
<td width="80%"><input class="snewsinput" type="text" name="searchvalues" value="<?php echo $searchvalues?>" size="50" maxlength="255"></td></tr>
<?php
if(bittst($srchaddoptions,BIT_2))
{
?>
<tr bgcolor="<?php echo $contentbgcolor?>"><td align="right" width="20%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_searchby?>:</font></td><td width="80%">
<input type="radio" name="searchpart" value="0" <?php if($searchpart==0) echo "checked"?>><?php echo $l_content?>&nbsp;&nbsp;
<input type="radio" name="searchpart" value="1" <?php if($searchpart==1) echo "checked"?>><?php echo $l_poster?>
</td></tr>
<?php
}
if((($category<0) || ($srchnolimit==0)) && bittst($srchaddoptions,BIT_1))
{
?>
<tr bgcolor="<?php echo $contentbgcolor?>"><td align="right" width="20%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_category?>:</font></td><td width="80%">
<select name="srchcat">
<option value="-1" <?php if($srchcat==-1) echo "selected"?>><?php echo $l_all_cats?></option>
<option value="0" <?php if($srchcat==0) echo "selected"?>><?php echo $l_general?></option>
<?php
$tmpsql="select * from ".$tableprefix."_categories where ignoreonsearch=0";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
while($tmprow=mysql_fetch_array($tmpresult))
{
	echo "<option value=\"";
	echo $tmprow["catnr"];
	echo "\"";
	if($tmprow["catnr"]==$srchcat)
		echo " selected";
	echo ">";
	$cattext=display_encoded(stripslashes($tmprow["catname"]));
	$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
	if(!$tmpresult2=mysql_query($tmpsql2,$db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($tmprow2=mysql_fetch_array($tmpresult2))
	{
		if(strlen($tmprow2["catname"])>0)
			$cattext=display_encoded(stripslashes($tmprow2["catname"]));
	}
	echo $cattext;
	echo "</option>";
}
?>
</select>
<?php
}
?>
<tr bgcolor="<?php echo $contentbgcolor?>"><td align="right" valign="top" width="20%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewscheckbox" type="checkbox" value="1" name="timeframe" onClick="enable_datesels(this.checked)" <?php if(isset($timeframe)) echo "checked"?>>
<?php echo $l_betweendate?>:</font></td>
<td width="80%"><table width="100%" align="left" bgcolor="<?php echo $bordercolor?>" cellspacing="1" cellpadding="0">
<tr bgcolor="<?php echo $contentbgcolor?>"><td align="left" width="50%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<i><?php echo $l_startdate?>:</i>
</font></td>
<td align="left" width="50%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<i><?php echo $l_enddate?>:</i>
</font></td></tr>
<tr><td width="50%">
<table width="100%" height="100%" bgcolor="<?php echo $contentbgcolor?>" cellspacing="0" cellpadding="0">
<?php
echo "<tr>";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td bgcolor=\"$contentbgcolor\" align=\"center\" width=\"15%\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	if($l_dateselformat[$i]=="day")
		echo $l_day;
	if($l_dateselformat[$i]=="month")
		echo $l_month;
	if($l_dateselformat[$i]=="year")
		echo $l_year;
	echo "</font></td>";
}
echo "<td width=\"55%\">&nbsp;</td>";
echo "</tr><tr><td colspan=\"3\"><hr width=\"98%\"></td><td>&nbsp;</td></tr>\n";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td bgcolor=\"$contentbgcolor\" align=\"center\" width=\"15%\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	if($l_dateselformat[$i]=="day")
		echo day_selector("startday",$startdate,isset($timeframe));
	if($l_dateselformat[$i]=="month")
		echo month_selector("startmonth",$startdate,isset($timeframe));
	if($l_dateselformat[$i]=="year")
		echo year_selector("startyear",$startdate,isset($timeframe));
	echo "</font></td>\n";
}
echo "<td width=\"55%\">&nbsp;</td>";
?>
</tr></table></td>
<td>
<table width="100%" height="100%" bgcolor="<?php echo $contentbgcolor?>" cellspacing="0" cellpadding="0">
<?php
echo "<tr>";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td bgcolor=\"$contentbgcolor\" align=\"center\" width=\"15%\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	if($l_dateselformat[$i]=="day")
		echo $l_day;
	if($l_dateselformat[$i]=="month")
		echo $l_month;
	if($l_dateselformat[$i]=="year")
		echo $l_year;
	echo "</font></td>";
}
echo "<td width=\"55%\">&nbsp;</td>";
echo "</tr><tr><td colspan=\"3\"><hr width=\"98%\"></td><td>&nbsp;</td></tr>\n";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td bgcolor=\"$contentbgcolor\" align=\"center\" width=\"15%\">";
	if($l_dateselformat[$i]=="day")
		echo day_selector("endday",$enddate,isset($timeframe));
	if($l_dateselformat[$i]=="month")
		echo month_selector("endmonth",$enddate,isset($timeframe));
	if($l_dateselformat[$i]=="year")
		echo year_selector("endyear",$enddate,isset($timeframe));
	echo "</td>";
}
echo "<td width=\"55%\">&nbsp;</td>";
?>
</table></td></tr></table></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>"><td colspan="2" align="center">
<input class="snewsbutton" type="submit" value="<?php echo $l_dosearch?>"></td></tr></form>
</table></td></tr>
<?php
if(isset($mode))
{
$searchvalues=strtolower(trim($searchvalues));
if(!$searchvalues && !isset($timeframe))
{
	echo "</table></div>";
	include ("./includes/footer.inc");
	exit;
}
?>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $l_result?></b></font></td></tr>
<?php
$sql = "select dat.* from ".$tableprefix."_events dat, ".$tableprefix."_evsearch search where dat.eventnr = search.eventnr ";
if($separatebylang==1)
	$sql.="and dat.lang='$act_lang' ";
if(($category>=0) && ($srchnolimit==0))
	$sql.= "and dat.category='$category' ";
if($srchcat>=0)
	$sql.= "and dat.category='$srchcat' ";
if($maxage>0)
{
	$actdate = date("Y-m-d H:i:s");
	$sql.= "and dat.date >= date_sub('$actdate', INTERVAL $maxage DAY) ";
}
if(isset($timeframe))
{
	$s_startdate=$startdate." 00:00";
	$s_enddate=$enddate." 23:59";
	$sql.= "and dat.date >= '$s_startdate' and dat.date <= '$s_enddate' ";
}
$baseurl="$act_script_url?$langvar=$act_lang&layout=$layout";
if(isset($start))
	$baseurl.="&start=$start";
if($srchcat>=0)
	$baseurl.="&srchcat=$srchcat";
$baseurl.="&category=$category&mode=search&searchpart=$searchpart&searchvalues=".urlencode($searchvalues);
if(isset($timeframe))
{
	$baseurl.="&timeframe=1&startday=$startday&startmont=$startmonth&startyear=$startyear&endday=$endday&endmonth=$endmonth&endyear=$endyear";
}
$musts=array();
$cans=array();
$nots=array();
$searchcriterias=0;
$searchterms = explode(" ",$searchvalues);
foreach($searchterms as $searchstring)
{
	$qualifier=substr($searchstring,0,1);
	if($qualifier=='-')
	{
		$actnot=trim(substr($searchstring,1,strlen($searchstring)-1));
		if(strlen($actnot)>0)
			array_push($nots,$actnot);
	}elseif ($qualifier=='+')
	{
		$actmust=trim(substr($searchstring,1,strlen($searchstring)-1));
		if(strlen($actmust)>0)
			array_push($musts,$actmust);
	}
	else
	{
		$actcan=trim($searchstring);
		if(strlen($actcan)>0)
			array_push($cans,$actcan);
	}
}
if($searchpart==0)
{
	$first=1;
	if(count($musts)>0)
	{
		$sql .="and ((";
		$searchcriterias++;
		for($i=0;$i<count($musts);$i++)
		{
			if($first==1)
				$first=0;
			else
				$sql.=" and ";
			$sql.="search.text like '%".$musts[$i]."%'";
		}
		$sql .=")";
	}
	$first=1;
	if(count($nots)>0)
	{
		if($searchcriterias>0)
			$sql.=" and ";
		else
			$sql.="and (";
		$sql .="(";
		$searchcriterias++;
		for($i=0;$i<count($nots);$i++)
		{
			if($first==1)
				$first=0;
			else
				$sql.=" and ";
			$sql.="search.text not like '%".$nots[$i]."%'";
		}
		$sql .=")";
	}
	$first=1;
	if((count($cans)>0) && (count($musts)<1))
	{
		if($searchcriterias>0)
			$sql.=" and ";
		else
			$sql.="and (";
		$sql.="(";
		$searchcriterias++;
		for($i=0;$i<count($cans);$i++)
		{
			if($first==1)
				$first=0;
			else
				$sql .=" or ";
			$sql.="search.text like '%".$cans[$i]."%'";
		}
		$sql .=")";
	}
	if($searchcriterias>0)
		$sql.=")";
}
else if($searchpart==1)
{
	$tmpsql="select * from ".$tableprefix."_users where ";
	$first=1;
	if(count($musts)>0)
	{
		$searchcriterias++;
		$tmpsql.="(";
		for($i=0;$i<count($musts);$i++)
		{
			if($first==1)
				$first=0;
			else
				$tmpsql .=" and ";
			$tmpsql.="username like '%".$musts[$i]."%'";
		}
		$tmpsql.=")";
	}
	$first=1;
	if(count($nots)>0)
	{
		if($searchcriterias>0)
			$tmpsql.=" and ";
		$tmpsql .="(";
		$searchcriterias++;
		for($i=0;$i<count($nots);$i++)
		{
			if($first==1)
				$first=0;
			else
				$tmpsql.=" and ";
			$tmpsql.="username not like '%".$nots[$i]."%'";
		}
		$tmpsql .=")";
	}
	$first=1;
	if((count($cans)>0) && (count($musts)<1))
	{
		if($searchcriterias>0)
			$tmpsql.=" and ";
		$tmpsql.="(";
		$searchcriterias++;
		for($i=0;$i<count($cans);$i++)
		{
			if($first==1)
				$first=0;
			else
				$tmpsql .=" or ";
			$tmpsql.="username like '%".$cans[$i]."%'";
		}
		$tmpsql .=")";
	}
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Unable to get data");
	if(!$tmprow=mysql_fetch_array($tmpresult))
		$sql.=" and dat.posterid=-1";
	else
	{
		do
		{
			$sql.=" and dat.posterid=".$tmprow["usernr"];
		}while($tmprow=mysql_fetch_array($tmpresult));
	}
}
$tmpsql="select * from ".$tableprefix."_categories where ignoreonsearch=1";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
while($tmprow=mysql_fetch_array($tmpresult))
{
	$sql.=" and dat.category!=".$tmprow["catnr"];
}
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
$sql.= " order by dat.date desc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to get data");
$numentries=mysql_numrows($result);
if($dosearchlog==1)
{
	$logfile=fopen($path_logfiles."/evsearch.log","a");
	if($logfile)
	{
		$logtxt="[".date("Y-m-d H:i:s")."]";
		$logtxt.=" $searchvalues [$numentries]".$crlf;
		fwrite($logfile,$logtxt);
		fclose($logfile);
	}
}
if(($entriesperpage>0) && ($numentries>0))
{
	if(!isset($start))
		$start=0;
	echo "<TR BGCOLOR=\"$headingbgcolor\" ALIGN=\"CENTER\">";
	echo "<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
	if(isset($searchstart) && ($searchstart>0) && ($numentries>$entriesperpage))
	{
		$sql .=" limit $searchstart,$entriesperpage";
	}
	else
	{
		$sql .=" limit $entriesperpage";
		$searchstart=0;
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		if(($entriesperpage+$searchstart)>$numentries)
			$displayresults=$numentries;
		else
			$displayresults=($entriesperpage+$start);
		$displaystart=$searchstart+1;
		$displayend=$displayresults;
		echo "<b>$l_page ".ceil(($searchstart/$entriesperpage)+1)."/".ceil(($numentries/$entriesperpage))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b>";
	}
	else
		echo "&nbsp;";
	echo "</font></td></tr>";
}
if(($entriesperpage>0) && ($numentries>$entriesperpage))
{
	echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\"><b>$l_page</b> ";
	if(floor(($searchstart+$entriesperpage)/$entriesperpage)>1)
	{
		echo "<a href=\"$baserul&searchstart=0\">";
		if($pagepic_first)
			echo "<img src=\"$url_gfx/$pagepic_first\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		else
			echo "<b>[&lt;&lt;]</b>";
		echo "</a> ";
		echo "<a href=\"$baseurl&searchstart=".($searchstart-$entriesperpage)."\">";
		if($pagepic_back)
			echo "<img src=\"$url_gfx/$pagepic_back\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		else
			echo "<b>[&lt;]</b>";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
	{
		if(floor(($searchstart+$entriesperpage)/$entriesperpage)!=$i)
		{
			echo "<a href=\"$baseurl&searchstart=".(($i-1)*$entriesperpage);
			echo "\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($searchstart < (($i-2)*$entriesperpage))
	{
		echo "<a href=\"$baseurl&searchstart=".($searchstart+$entriesperpage)."\">";
		if($pagepic_next)
			echo "<img src=\"$url_gfx/$pagepic_next\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		else
			echo "<b>[&gt;]</b>";
		echo "</a> ";
		echo "<a href=\"$baseurl&".(($i-2)*$entriesperpage)."\">";
		if($pagepic_last)
			echo "<img src=\"$url_gfx/$pagepic_last\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		else
			echo "<b>[&gt;&gt;]</b>";
		echo "</a> ";
	}
	echo "</font></td></tr>";
}
if($searchonlyheadings==1)
	include_once('./includes/evsearch_short.inc');
else
	include_once('./includes/evsearch_full.inc');
if(($entriesperpage>0) && ($numentries>$entriesperpage))
{
	echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\"><b>$l_page</b> ";
	if(floor(($searchstart+$entriesperpage)/$entriesperpage)>1)
	{
		echo "<a href=\"$baseurl&searchstart=0\">";
		if($pagepic_first)
			echo "<img src=\"$url_gfx/$pagepic_first\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
		else
			echo "<b>[&lt;&lt;]</b>";
		echo "</a> ";
		echo "<a href=\"$baseurl&searchstart=".($searchstart-$entriesperpage)."\">";
		if($pagepic_back)
			echo "<img src=\"$url_gfx/$pagepic_back\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
		else
			echo "<b>[&lt;]</b>";
		echo "</a> ";
	}
	for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
	{
		if(floor(($searchstart+$entriesperpage)/$entriesperpage)!=$i)
		{
			echo "<a href=\"$baseurl&searchstart=".(($i-1)*$entriesperpage);
			echo "\"><b>[$i]</b></a> ";
		}
		else
			echo "<b>($i)</b> ";
	}
	if($searchstart < (($i-2)*$entriesperpage))
	{
		echo "<a href=\"$baseurl&searchstart=".($searchstart+$entriesperpage)."\">";
		if($pagepic_next)
			echo "<img src=\"$url_gfx/$pagepic_next\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
		else
			echo "<b>[&gt;]</b>";
		echo "</a> ";
		echo "<a href=\"$baseurl&searchstart=".(($i-2)*$entriesperpage)."\">";
		if($pagepic_last)
			echo "<img src=\"$url_gfx/$pagepic_last\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
		else
			echo "<b>[&gt;&gt;]</b>";
		echo "</a> ";
	}
	echo "</font></td></tr>";
}
if($numentries==0)
{
	echo "<TR BGCOLOR=\"$contentbgcolor\" ALIGN=\"CENTER\">";
	echo "<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_noentriesfound;
	echo "</td></tr>";
}
echo "</table></td></tr>";
}
echo "</table></div>";
include('./includes/footer.inc');
?>
