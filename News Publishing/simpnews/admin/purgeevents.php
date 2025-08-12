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
$page_title=$l_purgeevents;
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("SimpNews not set up.");
$maxage=$myrow["maxage"];
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < $pevlevel)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode) && ($mode=="massdel"))
{
	$errors=0;
	if(!isset($eventnr))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_noentriesselected</td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		while(list($null, $input_eventnr) = each($_POST["eventnr"]))
		{
			if(isset($transferevents))
			{
				$sql = "update ".$tableprefix."_events set category=$destcat where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
					die("Unable to connect to database.".mysql_error());
			}
			else
			{
				$sql = "delete from ".$tableprefix."_events where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
					die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_events where linkeventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
					die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_evsearch where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
					die("Unable to connect to database.".mysql_error());
				$sql = "delete from ".$tableprefix."_events_attachs where eventnr=$input_eventnr";
				if(!$result = mysql_query($sql, $db))
					die("Unable to connect to database.".mysql_error());
			}
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_eventspurged?></td></tr>
</table></td></tr></table>
<?php
	}
	else
	{
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
	}
	include('trailer.php');
	exit;
}
if(isset($preview))
{
	$errors=0;
	if(!$purgedays)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_nopurgedays</td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		if(isset($transferevents))
		{
			if($destcat>0)
			{
				$sql="select * from ".$tableprefix."_categories where catnr=$destcat";
				if(!$result = mysql_query($sql, $db))
					die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.");
				if(!$myrow=mysql_fetch_array($result))
					die("<tr bgcolor=\"#cccccc\"><td>No such category.");
				$catname=display_encoded($myrow["catname"]);
			}
			else
				$catname=$l_general;
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo "$l_move2cat: $catname"?></b></td></tr>
<?php

		}
		$actdate = date("Y-m-d H:i:s");
		if($admin_rights<3)
		{
			$sql="select ev.* from ".$tableprefix."_events ev, ".$tableprefix."_cat_adm ca where ev.date<date_sub('$actdate', INTERVAL $purgedays DAY) and (ev.category=ca.catnr and ca.usernr=".$userdata["usernr"];
			if($purgecat>=0)
				$sql.=" and ev.category=$purgecat";
			else if(!bittst($secsettings,BIT_2))
				$sql.=" and ev.category != 0";
			else
				$sql.=" or ev.category=0";
			$sql.=")";
			if(isset($transferevents))
				$sql.=" and ev.category != $destcat";
			$sql.=" group by ev.eventnr";
		}
		else
		{
			$sql = "SELECT ev.* FROM ".$tableprefix."_events ev where ev.date<date_sub('$actdate', INTERVAL $purgedays DAY)";
			if(isset($transferevents))
				$sql.=" and ev.category != $destcat";
			if($purgecat>=0)
				$sql.=" and ev.category=$purgecat";
		}
		$sql.= " order by ev.date desc";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.");
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		if(mysql_num_rows($result)>0)
		{
			echo "<form name=\"inputform\" action=\"$act_script_url\" method=\"post\">";
			if(isset($transferevents))
			{
				echo "<input type=\"hidden\" name=\"transferevents\" value=\"1\">";
				echo "<input type=\"hidden\" name=\"destcat\" value=\"$destcat\">";
			}
			while($myrow=mysql_fetch_array($result))
			{
				$act_id=$myrow["eventnr"];
				$eventtext=stripslashes($myrow["text"]);
				$eventtext = undo_htmlspecialchars($eventtext);
				if($admentrychars>0)
				{
					$eventtext=strip_tags($eventtext);
					$eventtext=substr($eventtext,0,$admentrychars);
					$eventtext.="[...]";
				}
				if($admonlyentryheadings==0)
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b><br>".$eventtext;
					else
						$displaytext=$eventtext;
				}
				else
				{
					if($myrow["heading"])
						$displaytext="<b>".$myrow["heading"]."</b>";
					else
					{
						$text=strip_tags(undo_htmlspecialchars(stripslashes($myrow["text"])));
						if($admentrychars>0)
							$displaytext=substr($text,0,$admentrychars);
						else
							$displaytext=substr($text,0,20);
						$displaytext.="[...]";
					}
				}
				echo "<tr>";
				echo "<td class=\"inputrow\" align=\"center\" width=\"1%\">";
				echo "<input type=\"checkbox\" name=\"eventnr[]\" value=\"$act_id\">";
				echo "</td>";
				echo "<td class=\"displayrow\" align=\"center\" width=\"8%\">";
				if($myrow["linkeventnr"]==0)
					$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow["eventnr"]);
				else
					$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$myrow["linkeventnr"]);
				echo "<a class=\"shdetailslink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200);\">";
				echo $myrow["eventnr"]."</a></td>";
				echo "<td class=\"evententry\" align=\"left\">";
				echo "$displaytext</td>";
				echo "<td class=\"eventcat\" align=\"center\" valign=\"top\" width=\"20%\">";
				if($myrow["category"]>0)
				{
					$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
						die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.");
					if($tmprow=mysql_fetch_array($tmpresult))
						echo display_encoded($tmprow["catname"]);
				}
				else
					echo $l_general;
				echo "</td>";
				echo "<td class=\"eventdate\" align=\"center\" width=\"20%\">";
				list($curdate,$curtime)=explode(" ",$myrow["date"]);
				list($curyear,$curmonth,$curday)=explode("-",$curdate);
				list($curhour,$curmin,$cursec)=explode(":",$curtime);
				$tmpdate=mktime($curhour,$curmin,$cursec,$curmonth,$curday,$curyear);
				if(($curhour>0) || ($curmin>0) || ($cursec>0))
					$displaydate=date($l_admdateformat,$tmpdate);
				else
					$displaydate=date($l_admdateformat2,$tmpdate);
				echo $displaydate;
				echo "</td></tr>";
			}
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<tr class=\"actionrow\"><td colspan=\"5\" align=\"left\">";
			if(isset($transferevents))
				echo "<input class=\"snbutton\" type=\"submit\" name=\"del\" value=\"$l_moveselected\">";
			else
				echo "<input class=\"snbutton\" type=\"submit\" name=\"del\" value=\"$l_delselected\">";
			echo "&nbsp;&nbsp;";
			echo "<input class=\"snbutton\" type=\"button\" value=\"$l_checkall\" onclick=\"checkAll(inputform)\">";
			echo "&nbsp;&nbsp;";
			echo "<input class=\"snbutton\" type=\"button\" value=\"$l_uncheckall\" onclick=\"uncheckAll(inputform)\">";
			echo "</td></tr>";
			echo "</form>";
		}
		else
		{
			echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"5\">";
			echo $l_nomatchingentries;
			echo "</td></tr>";
		}
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_purgeevents</a></div>";
	}
	else
	{
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
	}
	include('trailer.php');
	exit;
}
if(isset($submit))
{
	$errors=0;
	if(!$purgedays)
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		echo "$l_nopurgedays</td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		$actdate = date("Y-m-d H:i:s");
		if($admin_rights<3)
		{
			$sql="select ev.* from ".$tableprefix."_events ev, ".$tableprefix."_cat_adm ca where ev.date<date_sub('$actdate', INTERVAL $purgedays DAY) and (ev.category=ca.catnr and ca.usernr=".$userdata["usernr"];
			if($purgecat>=0)
				$sql.=" and ev.category=$purgecat";
			else if(!bittst($secsettings,BIT_1))
				$sql.=" and ev.category != 0";
			else
				$sql.=" or ev.category=0";
			$sql.=")";
			if(isset($transferevents))
				$sql.=" and ev.category != $destcat";
			$sql.=" group by ev.eventnr";
		}
		else
		{
			$sql = "SELECT ev.* FROM ".$tableprefix."_events ev where ev.date<date_sub('$actdate', INTERVAL $purgedays DAY)";
			if(isset($transferevents))
				$sql.=" and ev.category != $destcat";
			if($purgecat>=0)
				$sql.=" and ev.category=$purgecat";
		}
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.");
		else
		{
			while($myrow=mysql_fetch_array($result))
			{
				if(isset($transferevents))
				{
					$sql2 = "update ".$tableprefix."_events set category=$destcat where eventnr=".$myrow["eventnr"];
					if(!$result2 = mysql_query($sql2, $db))
						die("Unable to connect to database.".mysql_error());
				}
				else
				{
					$sql2 = "delete from ".$tableprefix."_events_attachs where eventnr=".$myrow["eventnr"];
					if(!$result2 = mysql_query($sql2, $db))
						die("Unable to connect to database.".mysql_error());
					$sql2 = "DELETE FROM ".$tableprefix."_events where eventnr=".$myrow["eventnr"];
					if(!$result2 = mysql_query($sql2, $db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
					$sql2 = "DELETE FROM ".$tableprefix."_events where linkeventnr=".$myrow["eventnr"];
					if(!$result2 = mysql_query($sql2, $db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
					$sql2 = "DELETE FROM ".$tableprefix."_evsearch where eventnr=".$myrow["eventnr"];
					if(!$result2 = mysql_query($sql2, $db))
						die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_eventspurged?></td></tr>
</table></td></tr></table>
<?php
		}
	}
	else
	{
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
	}
	include('trailer.php');
	exit;
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inputrow">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<td align="center"><?php echo $l_purgeevents2a?> <input class="sninput" type="text" name="purgedays" size="3" maxlength="5"> <?php echo "$l_days $l_purgeevents2b"?></td></tr>
<tr class="inputrow"><td align="center"><?php echo $l_workoncat?>:
<select name="purgecat">
<option value="-1"><?php echo $l_all?></option>
<?php
if(bittst($secsettings,BIT_2) || ($admin_rights>2))
	echo "<option value=\"0\">$l_general</option>";
if($admin_rights==2)
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." group by cat.catnr";
else
	$sql="select cat.* from ".$tableprefix."_categories cat";
$sql.=" order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
	die("<tr bgcolor=\"#cccccc\"><td>Unable to conntect to database.");
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo display_encoded($myrow["catname"])."</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="center"><input type="checkbox" name="transferevents" value="1"> <?php echo $l_nodelbuttrans?>:
<select name="destcat">
<?php
if(bittst($secsettings,BIT_2) || ($admin_rights>2))
	echo "<option value=\"0\">$l_general</option>";
if($admin_rights==2)
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_cat_adm ca where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." group by cat.catnr";
else
	$sql="select cat.* from ".$tableprefix."_categories cat";
$sql.=" order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
	die("<tr bgcolor=\"#cccccc\"><td>Unable to conntect to database.");
while($myrow=mysql_fetch_array($result))
{
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo display_encoded($myrow["catname"])."</option>";
}
?>
</select>
</td></tr>
<tr class="optionrow"><td align="center"><input type="checkbox" name="preview" value="1"> <?php echo $l_previewlistevents?></td></tr>
<tr class="actionrow">
<td align="center"><input class="snbutton" type="submit" name="submit" value="<?php echo $l_submit?>"></td></tr>
</table></td></tr></table>
<?php
include('./trailer.php');
?>