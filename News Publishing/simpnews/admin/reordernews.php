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
$page_title="$l_reordernews";
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < 1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="regen")
	{
		$sql="select * from ".$tableprefix."_data where category=$newscat order by displaypos asc";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		$newdisplaypos=0;
		while($myrow=mysql_fetch_array($result))
		{
			$newdisplaypos++;
			$tmpsql="update ".$tableprefix."_data set displaypos=$newdisplaypos where newsnr=".$myrow["newsnr"];
			if(!$tmpresult = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
		}
	}
	if($mode=="move")
	{
		if($direction=="up")
		{
			$sql="select displaypos from ".$tableprefix."_data where newsnr=$input_newsnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			if(!$myrow=mysql_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]-1;
			$sql="update ".$tableprefix."_data set displaypos=displaypos+1 where displaypos=$newpos";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			$sql="update ".$tableprefix."_data set displaypos=$newpos where newsnr=$input_newsnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		}
		if($direction=="down")
		{
			$sql="select displaypos from ".$tableprefix."_data where newsnr=$input_newsnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			if(!$myrow=mysql_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]+1;
			$sql="update ".$tableprefix."_data set displaypos=displaypos-1 where displaypos=$newpos";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			$sql="update ".$tableprefix."_data set displaypos=$newpos where newsnr=$input_newsnr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		}
	}
	$sql="select * from ".$tableprefix."_data where category=$newscat and displaypos=0";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		$mycount=0;
		while($myrow=mysql_fetch_array($result))
		{
			$mycount++;
			if($myrow["displaypos"]==0)
			{
				$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=".$myrow["category"];
				if(!$tempresult = mysql_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
				if(!$temprow = mysql_fetch_array($tempresult))
					$newpos=1;
				else
					$newpos=$temprow["newdisplaypos"]+1;
				$updatesql="update ".$tableprefix."_data set displaypos=$newpos where newsnr=".$myrow["newsnr"];
				if(!$updateresult = mysql_query($updatesql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			}
		}
	}
	$mycount=0;
	$sql="select * from ".$tableprefix."_data where category=$newscat order by displaypos asc";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"4\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		echo "<tr class=\"rowheadings\"><td align=\"center\" width=\"5%\"><b>#</b></td>";
		echo "<td align=\"center\" width=\"20%\"><b>$l_date</b></td>";
		echo "<td align=\"center\" width=\"50%\"><b>$l_entry</b></td>";
		echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
		do{
			$mycount++;
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
			list($mydate,$mytime)=explode(" ",$entrydata["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($l_admdateformat,$temptime);
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
			echo "<tr class=\"displayrow\"><td align=\"right\" width=\"5%\" valign=\"top\">";
			echo $myrow["newsnr"];
			echo "</td>";
			echo "<td width=\"20%\" align=\"center\" valign=\"top\">";
			echo $displaydate;
			echo "</td>";
			echo "<td width=\"75%\" align=\"left\" valign=\"top\">";
			echo $displaytext;
			echo "</td>";
			echo "<td width=\"16\" align=\"center\" valign=\"middle\">";
			if($mycount>1)
			{
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_newsnr=$act_id&$langvar=$act_lang&direction=up&newscat=$newscat")."\">";
				echo "<img width=\"16\" height=\"16\" src=\"gfx/up.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_up\"></a>";
			}
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td width=\"16\" align=\"center\" valign=\"middle\">";
			if($mycount<mysql_num_rows($result))
			{
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_newsnr=$act_id&$langvar=$act_lang&direction=down&newscat=$newscat")."\">";
				echo "<img width=\"16\" height=\"16\" src=\"gfx/down.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_down\"></a>";
			}
			else
				echo "&nbsp;";
			echo "</td></tr>";
		}while($myrow=mysql_fetch_array($result));
	}
	if($mycount>0)
	{
		echo "<tr class=\"actionrow\"><td colspan=\"5\" align=\"center\">";
		echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=regen&$langvar=$act_lang&newscat=$newscat")."\">";
		echo "$l_resync</a></td></tr>";
	}
	echo "</table></td></tr></table>";
	echo "<div class=\"bottombox\" align=\"center\">";
	echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_reordernews</a></div>";
}
else
{
	$sql="select * from ".$tableprefix."_categories where isarchiv=1";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)<1)
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"4\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"selcat\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">";
		echo $l_selectcat.":</td>";
		echo "<td><select name=\"newscat\">";
		while($myrow=mysql_fetch_array($result))
		{
			echo "<option value=\"".$myrow["catnr"]."\">";
			echo display_encoded($myrow["catname"]);
			echo "</option>";
		}
		echo "</select></td></tr>";
		echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
		echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_ok\"></td></tr>";
		echo "</form>";
	}
	echo "</table></td></tr></table>";
}
include('./trailer.php');
?>