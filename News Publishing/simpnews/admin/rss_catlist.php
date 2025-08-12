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
$page_title=$l_rss_catlist;
$page="rss_catlist";
require_once('./heading.php');
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
echo "<tr><TD BGCOLOR=\"#000000\">";
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
if(!isset($layoutid))
{
	echo "<form name=\"inputform\" method=\"post\" action=\"$act_script_url\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<tr class=\"inputrow\"><td align=\"right\" width=\"30%\">$l_layout:</td>";
	echo "<td><select name=\"layoutid\">";
	$sql="select * from ".$tableprefix."_layout group by id";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
		echo "<option value=\"".$myrow["id"]."\">".$myrow["id"]."</option>";
	echo "</select></td></tr>";
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
	echo "<input type=\"hidden\" name=\"destmode\" value=\"add\">";
	echo "<input class=\"snbutton\" type=\"button\" name=\"doadd\" onclick=\"do_add()\" value=\"$l_manage_cats1\">";
	echo "&nbsp;&nbsp;<input class=\"snbutton\" type=\"button\" name=\"doedit\" onclick=\"do_edit()\" value=\"$l_manage_cats2\">";
	echo "</td></tr>";
	echo "</form></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if(isset($mode))
{
	if($mode=="add")
	{
		$destmode="add";
		if($dodebug)
			echo "<tr class=\"displayrow\"><td colspan=\"3\">destmode: $destmode</td></tr>";
		if(isset($rem_cats))
		{
			while(list($null, $actcat) = each($_POST["rem_cats"]))
			{
				$tmpsql = "DELETE FROM ".$tableprefix."_rss_catlist where catnr=$actcat and layoutid='$layoutid'";
				if(!mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			}
			$tmpsql="select * from ".$tableprefix."_rss_catlist where layoutid='$layoutid' order by displaypos asc";
			if(!$tmpresult=mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			$curdisplaypos=0;
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				$curdisplaypos++;
				$tmpsql2="update ".$tableprefix."_rss_catlist set displaypos=$curdisplaypos where catnr=".$tmprow["catnr"]." and layoutid='$layoutid'";
				if(!mysql_query($tmpsql2, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			}
		}
		if(isset($new_cats))
		{
			while(list($null, $actcat) = each($_POST["new_cats"]))
			{
				$tmpsql = "INSERT INTO ".$tableprefix."_rss_catlist (catnr, layoutid) VALUES ('$actcat','$layoutid')";
				if(!mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			}
			$tmpsql="select * from ".$tableprefix."_rss_catlist where layoutid='$layoutid' order by displaypos asc";
			if(!$tmpresult=mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			$curdisplaypos=0;
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				if($tmprow["displaypos"]!=0)
				{
					if($curdisplaypos==0)
						$curdisplaypos=$tmprow["displaypos"];
					else
					{
						if(($curdisplaypos+1)!=$tmprow["displaypos"])
						{
							$curdisplaypos++;
							$tmpsql2="update ".$tableprefix."_rss_catlist set displaypos=$curdisplaypos where catnr=".$tmprow["catnr"]." and layoutid='$layoutid'";
							if(!mysql_query($tmpsql2, $db))
								die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
						}
						else
							$curdisplaypos=$tmprow["displaypos"];
					}
				}
				else
				{
					$curdisplaypos++;
					$tmpsql2="update ".$tableprefix."_rss_catlist set displaypos=$curdisplaypos where catnr=".$tmprow["catnr"]." and layoutid='$layoutid'";
					if(!mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
				}
			}
		}
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\"><b>";
		echo $l_settingsupdated;
		echo "</b></tr></td>";
	}
	if($mode=="move")
	{
		$destmode="edit";
		if($dodebug)
			echo "<tr class=\"displayrow\"><td colspan=\"3\">destmode: $destmode</td></tr>";
		if($direction=="up")
		{
			$sql="select displaypos from ".$tableprefix."_rss_catlist where catnr=$input_catnr and layoutid='$layoutid'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error. No result for catnr=$input_catnr and layout=$layoutid");
			$newpos=$myrow["displaypos"]-1;
			@mysql_free_result($result);
			$sql="select catnr from ".$tableprefix."_rss_catlist where displaypos=$newpos and layoutid='$layoutid'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if($myrow=mysql_fetch_array($result))
			{
				$movecat=$myrow["catnr"];
				@mysql_free_result($result);
				$sql="update ".$tableprefix."_rss_catlist set displaypos=displaypos+1 where catnr=$movecat and layoutid='$layoutid'";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
				@mysql_free_result($result);
			}
			$sql="update ".$tableprefix."_rss_catlist set displaypos=$newpos where catnr=$input_catnr and layoutid='$layoutid'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			@mysql_free_result($result);
		}
		if($direction=="down")
		{
			$sql="select displaypos from ".$tableprefix."_rss_catlist where catnr=$input_catnr and layoutid='$layoutid'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]+1;
			@mysql_free_result($result);
			$sql="select catnr from ".$tableprefix."_rss_catlist where displaypos=$newpos and layoutid='$layoutid'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			if($myrow=mysql_fetch_array($result))
			{
				$movecat=$myrow["catnr"];
				@mysql_free_result($result);
				$sql="update ".$tableprefix."_rss_catlist set displaypos=displaypos-1 where catnr=$movecat and layoutid='$layoutid'";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
				@mysql_free_result($result);
			}
			$sql="update ".$tableprefix."_rss_catlist set displaypos=$newpos where catnr=$input_catnr and layoutid='$layoutid'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			@mysql_free_result($result);
		}
	}
}
if(!isset($destmode))
{
	echo("<tr class=\"errorrow\"><td>No destmode defined");
	if(isset($mode))
		echo " - mode was $mode";
	die();
}
if($destmode=="add")
{
	echo "<form method=\"post\" action=\"$act_script_url\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"layoutid\" value=\"$layoutid\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"add\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\"><b>$l_manage_cats1</b></td></tr>";
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">".$l_layout.": ".$layoutid."</td></tr>";
	echo "<tr class=\"inputrow\"><td width=\"30%\" align=\"right\" valign=\"top\">".$l_categories.":</td>";
	echo "<td valign=\"top\">";
	$tmpsql = "select * from ".$tableprefix."_rss_catlist where catnr=0 and layoutid='$layoutid'";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		echo $l_general." (<input type=\"checkbox\" name=\"rem_cats[]\" value=\"0\"> $l_remove)<BR>";
		$generalselected=true;
	}
	$tmpsql = "select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_rss_catlist rc where cat.catnr=rc.catnr and rc.layoutid='$layoutid'";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("Could not connect to the database.");
	if($temprow=mysql_fetch_array($tmpresult))
	{
		 do {
			echo $temprow["catname"]." (<input type=\"checkbox\" name=\"rem_cats[]\" value=\"".$temprow["catnr"]."\"> $l_remove)<BR>";
			$current_cats[] = $temprow["catnr"];
		 } while($temprow = mysql_fetch_array($tmpresult));
		 echo "<br>";
	}
	$tmpsql = "SELECT * FROM ".$tableprefix."_categories ";
	$firstentry=true;
	if(isset($current_cats))
	{
		while(list($null, $currCat) = each($current_cats)) {
			if($firstentry)
			{
				$tmpsql.="WHERE ";
				$firstentry=false;
			}
			else
				$tmpsql.="AND ";
			$tmpsql .= "catnr != $currCat ";
		}
	}
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("Could not connect to the database.");
	if(($temprow = mysql_fetch_array($tmpresult)) || !$generalselected) {
		echo"<b>$l_add:</b><br>";
		echo"<SELECT NAME=\"new_cats[]\" size=\"5\" multiple>";
		if(!$generalselected)
			echo "<OPTION VALUE=\"0\">$l_general</OPTION>\n";
		do {
			echo "<OPTION VALUE=\"".$temprow["catnr"]."\" >".$temprow["catname"]."</OPTION>\n";
		} while($temprow = mysql_fetch_array($tmpresult));
		echo"</select>";
	}
	echo "</td></tr>";
	echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
	echo "<input type=\"submit\" name=\"submit\" class=\"snbutton\" value=\"$l_update\">";
	echo "</td></tr></form>";
	echo "</table></td></tr></table>";
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_changelayout</a>";
	echo "&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&layoutid=$layoutid&destmode=edit")."\">$l_manage_cats2</a>";
	echo "</div>";
}
else if($destmode=="edit")
{
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"3\"><b>$l_manage_cats2</b></td></tr>";
	echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"3\">".$l_layout.": ".$layoutid."</td></tr>";
	$sql = "select * from ".$tableprefix."_rss_catlist where layoutid='$layoutid' order by displaypos asc";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
	if(mysql_num_rows($result)<1)
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">$l_noentries</td></tr>";
	else
	{
		echo "<tr class=\"rowheadings\">";
		echo "<td align=\"center\"><b>$l_category</b></td>";
		echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
		$mycount=0;
		$olddisplaypos=0;
		while($myrow=mysql_fetch_array($result))
		{
			$mycount++;
			$act_id=$myrow["catnr"];
			echo "<tr class=\"inputrow\"><td align=\"center\" width=\"90%\">";
			if($myrow["catnr"]==0)
				echo $l_general;
			else
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["catnr"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
				if($tmprow=mysql_fetch_array($tmpresult))
					echo $tmprow["catname"];
				else
					echo $l_unknown;
			}
			echo "</td>";
			if($myrow["displaypos"]==0)
			{
				$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_rss_catlist where layoutid='$layoutid'";
				if(!$tempresult = mysql_query($tempsql, $db))
					die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
				if(!$temprow = mysql_fetch_array($tempresult))
					$newpos=1;
				else
					$newpos=$temprow["newdisplaypos"]+1;
				$updatesql="update ".$tableprefix."_rss_catlist set displaypos=$newpos where layoutid='$layoutid' and catnr=".$myrow["catnr"];
				if(!$updateresult = mysql_query($updatesql, $db))
					die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
			}
			else
			{
				if(($olddisplaypos==0) || ($myrow["displaypos"]==($olddisplaypos+1)))
					$olddisplaypos=$myrow["displaypos"];
				else
				{
					$newpos=$olddisplaypos+1;
					$updatesql="update ".$tableprefix."_rss_catlist set displaypos=$newpos where layoutid='$layoutid' and catnr=".$myrow["catnr"];
					if(!$updateresult = mysql_query($updatesql, $db))
						die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
				}
			}
			if($mycount>1)
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_catnr=$act_id&$langvar=$act_lang&direction=up&layoutid=$layoutid")."\">";
				echo "<img width=\"16\" height=\"16\" src=\"gfx/up.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_up\"></a>";
				echo "</td>";
			}
			else
				echo "<td class=\"inputrow\" width=\"16\">&nbsp;</td>";
			if($mycount<mysql_num_rows($result))
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&$langvar=$act_lang&input_catnr=$act_id&direction=down&layoutid=$layoutid")."\">";
				echo "<img width=\"16\" height=\"16\" src=\"gfx/down.gif\" border=\"0\" title=\"$l_move_down\" alt=\"$l_move_down\"></a>";
				echo "</td>";
			}
			else
				echo "<td class=\"inputrow\" width=\"16\">&nbsp;</td>";
		}
	}
	echo "</table></td></tr></table>";
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_changelayout</a>";
	echo "&nbsp;&nbsp;<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&layoutid=$layoutid&destmode=add")."\">$l_manage_cats1</a>";
	echo "</div>";
}
else
	die("<tr class=\"errorrow\"><td>Unknown destmode ".$destmode);
include('./trailer.php');
?>
