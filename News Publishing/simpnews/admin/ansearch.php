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
if(!isset($anlang))
	$anlang="all";
if(!isset($start))
	$start=0;
require_once('./auth.php');
$page_title="$l_search ($l_announcements)";
$page="search";
include_once('../includes/date_selectors.inc');
require_once('./heading.php');
if(!isset($headingtext))
	$headingtext="";
if(!isset($catnr))
	$catnr=0;
if(!isset($newscat))
	$newscat=-1;
$allowcomments=1;
$hasattach=0;
$errmsg="";
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
if(!isset($searchvalues))
	$searchvalues="";
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(($admin_rights<3) && bittst($secsettings,BIT_21) && !bittst($secsettings,BIT_4))
	$numavailcats=0;
else
	$numavailcats=1;
if(($admin_rights>2) || !bittst($secsettings,BIT_21))
	$catsql="select cat.* from ".$tableprefix."_categories cat";
else
	$catsql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
$catsql.=" order by cat.displaypos asc";
if(!$result = mysql_query($catsql, $db))
	die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
$numavailcats+=mysql_num_rows($result);
if($numavailcats<1)
{
	echo "<tr class=\"inforow\"><td align=\"center\" valign=\"top\" colspan=\"2\"><b>$l_noeditcats</b></td></tr>";
	echo "</table></td></tr></table>";
	include('./trailer.php');
	exit;
}
echo "<form name=\"searchform\" method=\"post\" action=\"$act_script_url\">";
if(is_konqueror())
	echo "<tr><td></td></tr>";
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="dosearch" value="1">
<tr class="inputrow"><td align="right" width="20%"><?php echo $l_text?>:</td>
<td width="80%"><input class="sninput" type="text" name="searchvalues" value="<?php echo $searchvalues?>" size="50" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_language?>:</td>
<td><?php echo language_select2($anlang,"anlang","../language/")?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_category?>:</td><td>
<select name="newscat">
<option value="-1" <?php if($newscat==-1) echo "selected"?>><?php echo $l_all?></option>
<?php
if($admin_rights>2)
{
	echo "<option value=\"0\"";
	if($newscat==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(!bittst($secsettings,BIT_21))
{
	echo "<option value=\"0\"";
	if($newscat==0)
		echo "selected";
	echo ">$l_general</option>";
}
else if(bittst($secsettings,BIT_4))
{
	echo "<option value=\"0\"";
	if($newscat==0)
		echo "selected";
	echo ">$l_general</option>";
}
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\"";
	if($myrow["catnr"]==$newscat)
		echo " selected";
	echo ">".display_encoded($myrow["catname"])."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right" valign="top" width="20%">
<input class="snewscheckbox" type="checkbox" value="1" name="timeframe" onClick="enable_datesels(this.checked)" <?php if(isset($timeframe)) echo "checked"?>>
<?php echo $l_betweendate?>:</td>
<td width="80%"><table width="100%" align="left" cellspacing="1" cellpadding="0">
<tr class="inputrow"><td align="left" width="50%"><i><?php echo $l_startdate?>:</i></td>
<td align="left" width="50%"><i><?php echo $l_enddate?>:</i></td></tr>
<tr><td width="50%">
<table width="100%" height="100%" cellspacing="0" cellpadding="0">
<?php
echo "<tr>";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td align=\"center\" width=\"15%\">";
	if($l_dateselformat[$i]=="day")
		echo $l_day;
	if($l_dateselformat[$i]=="month")
		echo $l_month;
	if($l_dateselformat[$i]=="year")
		echo $l_year;
	echo "</td>";
}
echo "<td width=\"55%\">&nbsp;</td>";
echo "</tr><tr><td colspan=\"3\"><hr width=\"98%\"></td><td>&nbsp;</td></tr>\n";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td align=\"center\" width=\"15%\">";
	if($l_dateselformat[$i]=="day")
		echo day_selector("startday",$startdate,isset($timeframe));
	if($l_dateselformat[$i]=="month")
		echo month_selector("startmonth",$startdate,isset($timeframe));
	if($l_dateselformat[$i]=="year")
		echo year_selector("startyear",$startdate,isset($timeframe));
	echo "</td>\n";
}
echo "<td width=\"55%\">&nbsp;</td>";
?>
</tr></table></td>
<td>
<table width="100%" height="100%"  cellspacing="0" cellpadding="0">
<?php
echo "<tr>";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td align=\"center\" width=\"15%\">";
	if($l_dateselformat[$i]=="day")
		echo $l_day;
	if($l_dateselformat[$i]=="month")
		echo $l_month;
	if($l_dateselformat[$i]=="year")
		echo $l_year;
	echo "</td>";
}
echo "<td width=\"55%\">&nbsp;</td>";
echo "</tr><tr><td colspan=\"3\"><hr width=\"98%\"></td><td>&nbsp;</td></tr>\n";
for($i=0;$i<count($l_dateselformat);$i++)
{
	echo "<td align=\"center\" width=\"15%\">";
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
<?php
if($admin_rights<3)
{
	if(!bittst($secsettings,BIT_21))
	{
		echo "<tr class=\"optionrow\"><td>&nbsp;</td><td><input type=\"checkbox\" name=\"limit2editable\" value=\"1\"";
		if(isset($limit2editable))
			echo " checked";
		echo "> $l_limit2editable</td></tr>";
	}
	else
		echo "<input type=\"hidden\" name=\"limit2editable\" value=\"1\">";
}
?>
<tr class="actionrow"><td align="center">
<a href="<?php echo "../help/".$act_lang."/search.php"?>" align="middle" target="_blank"><img src="../gfx/help.gif" border="0" title="<?php echo $l_help?>" alt="<?php echo $l_help?>"></a>
</td><td align="center"><input type="submit" name="submit" class="snbutton" value="<?php echo $l_dosearch?>"></td></tr>
</table></td></tr></table>
<?php
if(!isset($dosearch))
{
	include('./trailer.php');
	exit;
}
$searchvalues=strtolower(trim($searchvalues));
if(!$searchvalues && !isset($timeframe))
{
	include ("./trailer.php");
	exit;
}
echo "<p></p>";
$sql = "select an.* from ".$tableprefix."_announce an, ".$tableprefix."_ansearch search where an.entrynr = search.annr ";
if($anlang!="all")
{
	$sql.="and an.lang='$anlang' ";
}
if($newscat>=0)
{
	$sql.="and an.category=$newscat ";
}
else if(($admin_rights<3) && bittst($secsettings,BIT_21))
{
	$possiblecats=array();
	if(bittst($secsettings,BIT_1))
		array_push($possiblecats,0);
	$catsql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
	if(!$catresult = mysql_query($catsql, $db))
		die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
	while($catrow=mysql_fetch_array($catresult))
		array_push($possiblecats,$catrow["catnr"]);
	$firstarg=true;
	for($i=0;$i<count($possiblecats);$i++)
	{
		if($firstarg)
		{
			$firstarg=false;
			$sql.="and ( ";
		}
		else
			$sql.="or ";
		$sql.="an.category=".$possiblecats[$i]." ";
	}
	if(count($possiblecats)>0)
		$sql.=") ";
}
if(isset($timeframe))
{
	$s_startdate=$startdate." 00:00";
	$s_enddate=$enddate." 23:59";
	$sql.= "and an.date >= '$s_startdate' and an.date <= '$s_enddate' ";
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
			$sql .=" and ";
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
$sql.="order by an.date desc";
if(!$result = mysql_query($sql, $db))
    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
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
	if((mysql_num_rows($result)>0) && !isset($limit2editable))
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
	$baselink="$act_script_url?dosearch=1&$langvar=$act_lang&anlang=$anlang&newscat=$newscat";
	if(isset($limit2editable))
		$baselink.="&limit2editable=1";
	if(isset($timeframe))
	{
		$baselink.="&timeframe=1";
		$baselink.="&startday=$startday&startmonth=$startmonth&startyear=$startyear";
		$baselink.="&endday=$endday&endmonth=$endmonth&endyear=$endyear";
	}
	if(isset($searchvalues))
		$baselink.="&searchvalues=".urlencode($searchvalues);
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
if ($myrow = mysql_fetch_array($result))
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\"><b>#</b></td>";
	echo "<td>&nbsp;</td>";
	echo "<td align=\"center\"><b>$l_entry</b></td>";
	echo "<td align=\"center\"><b>$l_date</b></td>";
	echo "<td align=\"center\"><b>$l_expireson</b></td>";
	echo "<td align=\"center\"><b>$l_firstpublishon</b></td>";
	for($i=0;$i<3;$i++)
		echo "<td>&nbsp;</td>";
	echo "</tr>";
	do{
		$act_id=$myrow["entrynr"];
		$allowactions=false;
		if($admrestrict==1)
		{
			if(($admin_rights>2) || bittst($userdata["addoptions"],BIT_5))
				$allowactions=true;
			else
			{
				if($myrow["posterid"]==$userdata["usernr"])
					$allowactions=true;
			}
		}
		else
		{
			if($admin_rights > 2)
				$allowactions=true;
			else
			{
				if($myrow["category"]>0)
				{
					$tempsql="select * from ".$tableprefix."_cat_adm where catnr=".$myrow["category"]." and usernr=".$userdata["usernr"];
					if(!$tempresult = mysql_query($tempsql, $db))
						die("Could not connect to the database.".mysql_error());
					if(mysql_num_rows($tempresult)>0)
						$allowactions=true;
				}
				else if(bittst($secsettings,BIT_4))
					$allowactions=true;
			}
		}
		if(isset($limit2editable) && !$allowactions)
			continue;
		$newstext = stripslashes($myrow["text"]);
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
			if($myrow["heading"])
				$displaytext="<b>".$myrow["heading"]."</b><br>".$newstext;
			else
				$displaytext=$newstext;
		}
		else
		{
			if($myrow["heading"])
				$displaytext="<b>".$myrow["heading"]."</b>";
			else
			{
				$displaytext=strip_tags($myrow["text"]);
				if($admentrychars>0)
					$displaytext=substr($displaytext,0,$admentrychars);
				else
					$displaytext=substr($displaytext,0,20);
				$displaytext.="[...]";
			}
		}
		if($myrow["category"]>0)
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if($tmprow=mysql_fetch_array($tmpresult))
				$catname=display_encoded($tmprow["catname"]);
			else
				$catname=$l_undefined;
		}
		else
			$catname=$l_general;
		echo "<tr valign=\"top\">";
		echo "<td class=\"displayrow\" align=\"center\" width=\"8%\" valign=\"top\">";
		$showurl=do_url_session("anshow.php?$langvar=$act_lang&annr=".$myrow["entrynr"]);
		echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
		echo $myrow["entrynr"];
		echo "</a></td>";
		echo "<td class=\"newsicon\" align=\"center\" width=\"5%\">";
		if($myrow["headingicon"])
			echo "<img src=\"$url_icons/".$myrow["headingicon"]."\" border=\"0\" align=\"bottom\">";
		else
			echo "&nbsp;";
		echo "</td><td class=\"newsentry\" align=\"left\">";
		if($newscat<0)
			echo "<i>$catname</i><br>";
		echo "$displaytext</td>";
		echo "<td class=\"newsdate\" align=\"center\" width=\"20%\" valign=\"top\">";
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
		$displaydate=date($l_admdateformat,$temptime);
		echo "$displaydate</td>";
		if(($myrow["expiredate"]>0) && ($acttime>$myrow["expiredate"]))
			$rowclass="expireddate";
		else
			$rowclass="newsdate";
		echo "<td class=\"$rowclass\" align=\"center\" width=\"10%\">";
		if($myrow["expiredate"]>0)
			$expires=date($l_admdateformat,$myrow["expiredate"]);
		else
			$expires=$l_never;
		echo $expires;
		echo "</td>";
		echo "<td class=\"$rowclass\" align=\"center\" width=\"10%\">";
		if($myrow["firstdate"]>0)
			$firstdisplay=date($l_admdateformat,$myrow["firstdate"]);
		else
			$firstdisplay=$l_immediately;
		echo $firstdisplay;
		echo "</td>";
		if($allowactions)
		{
			echo "<td class=\"adminactions\" align=\"center\" width=\"2%\">";
			echo "<a href=\"".do_url_session("announce.php?$langvar=$act_lang&announcelang=".$myrow["lang"]."&mode=catmove&input_announcenr=$act_id&catnr=$catnr")."\"><img src=\"gfx/move.gif\" border=\"0\" title=\"$l_catmove\" alt=\"$l_catmove\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" width=\"2%\">";
			echo "<a href=\"".do_url_session("announce.php?$langvar=$act_lang&announcelang=".$myrow["lang"]."&mode=edit&input_announcenr=$act_id&catnr=$catnr")."\"><img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			echo "</td>";
			echo "<td class=\"adminactions\" align=\"center\" width=\"2%\">";
			$dellink=do_url_session("announce.php?$langvar=$act_lang&announcelang=".$myrow["lang"]."&mode=del&input_announcenr=$act_id&catnr=$catnr");
			if($admdelconfirm==2)
				echo "<a class=\"listlink2\" href=\"javascript:confirmDel('$l_announce #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink2\" href=\"$dellink\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a></td>";
		}
		else
		{
			for($i=0;$i<4;$i++)
			{
				echo "<td class=\"adminactions\" align=\"center\" width=\"2%\">";
				echo "&nbsp;";
				echo "</td>";
			}
		}
		echo "</tr>";
	}while($myrow=mysql_fetch_array($result));
	echo "</table></td></tr></table>";
}
else
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_noentriesfound?></td>
</table></td></tr></table>
<?php
}
if(($admepp>0) && ($numentries>$admepp))
{
	$baselink="$act_script_url?dosearch=1&$langvar=$act_lang&newslang=$newslang&newscat=$newscat";
	if(isset($limit2editable))
		$baselink.="&limit2editable=1";
	if(isset($timeframe))
	{
		$baselink.="&timeframe=1";
		$baselink.="&startday=$startday&startmonth=$startmonth&startyear=$startyear";
		$baselink.="&endday=$endday&endmonth=$endmonth&endyear=$endyear";
	}
	if(isset($searchvalues))
		$baselink.="&searchvalues=".urlencode($searchvalues);
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
include('./trailer.php');
?>
