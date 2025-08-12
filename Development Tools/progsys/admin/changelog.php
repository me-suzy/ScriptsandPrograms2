<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('../config.php');
require('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_changelog;
$page="changelog";
require('./heading.php');
if(!isset($beta))
	$beta=-1;
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
		if(psys_array_key_exists($admcookievals,"chlog_prognr"))
			$prognr=$admcookievals["chlog_prognr"];
		if(psys_array_key_exists($admcookievals,"chlog_beta"))
			$beta=$admcookievals["chlog_beta"];
	}
}
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
	$dateformat=$myrow["dateformat"];
	$dateformatlong=$myrow["dateformatlong"];
}
else
{
	$dateformat="Y-m-d";
	$dateformatlong="Y-m-d H:i:s";
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_changelog where (entrynr=$input_entrynr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr bgcolor=\"#cccccc\"><td>Database inconsitency error");
		list($year, $month, $day) = explode("-", $myrow["versiondate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		$changelogtext=stripslashes($myrow["changes"]);
		$changelogtext = undo_htmlspecialchars($changelogtext);
		$proghead=stripslashes($temprow["programmname"])." [".$temprow["language"]."]";
		if($myrow["isbeta"]==1)
			$proghead.=" (Beta)";
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editchangelog?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo $proghead?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_version?>:</td>
<td><?php echo $myrow["version"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_versiondate?>:</td>
<td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_changes?>:</td>
<td><?php echo $changelogtext?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newchangelog?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right"><?php echo $l_programm?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if (!$temprow = mysql_fetch_array($result1))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&lang=$lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		$progname=htmlentities($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = mysql_fetch_array($result1));
?>
</select>
<?php
	}
	list($year, $month, $day) = explode("-", date("Y-m-d"));
?>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_version?>:</td><td><input class="psysinput" type="text" name="input_version" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="isbeta" value="1">
<?php echo $l_isbeta?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_versiondate?>:</td>
<td>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="day">
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$day)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="month">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$month)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="year">
<?php
for($i=$year-10;$i<$year+2;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$year)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
</tr>
</table>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_changes?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" name="changes" cols="50" rows="10"></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input type="hidden" name="mode" value="add">
<input class="psysbutton" type="submit" value="<?php echo $l_add?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
	}
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		// Add new changelog to database
		$errors=0;
		if(!$input_version)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noversion</td></tr>";
			$errors=1;
		}
		if($programm<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!checkdate($month,$day,$year))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_invaliddate</td></tr>";
			$errors=1;
		}
		else
			$versiondate=$year."-".$month."-".$day;
		if(!$changes)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nochanges</td></tr>";
			$errors=1;
		}
		if(!isset($isbeta))
			$isbeta=0;
		if($errors==0)
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			if(isset($preview))
			{
				$displaychanges=$changes;
				if($urlautoencode==1)
					$displaychanges = make_clickable($displaychanges);
				if($enablespcode==1)
					$displaychanges = bbencode($displaychanges);
				$displaychanges = htmlentities($displaychanges);
				$displaychanges = str_replace("\n", "<BR>", $displaychanges);
				$displaychanges = undo_htmlspecialchars($displaychanges);
				$displayversion=htmlentities($input_version);
				$tempsql = "select * from ".$tableprefix."_programm where prognr=$programm";
				if(!$tempresult = mysql_query($tempsql, $db))
				    die("Unable to connect to database.");
				if(!$temprow=mysql_fetch_array($tempresult))
				{
					$progname=$l_undefined;
					$proglang=$l_undefined;
				}
				else
				{
					$progname=$temprow["programmname"];
					$proglang=$temprow["language"];
				}
				$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newchangelog?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td width="70%"><?php echo $progname." [".$proglang."]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_version?>:</td>
<td width="70%"><?php echo $displayversion?><input type="hidden" name="input_version" value="<?php echo $input_version?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_versiondate?>:</td>
<td width="70%"><?php echo $displaydate?>
<input type="hidden" name="year" value="<?php echo $year?>">
<input type="hidden" name="month" value="<?php echo $month?>">
<input type="hidden" name="day" value="<?php echo $day?>">
<input type="hidden" name="isbeta" value="<?php echo $isbeta?>">
</td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_changes?>:</td>
<td width="70%"><?php echo $displaychanges?><input type="hidden" name="changes" value="<?php echo $changes?>"></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="add">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				$input_version=htmlentities($input_version);
				$input_version=addslashes($input_version);
				if($urlautoencode==1)
					$changes = make_clickable($changes);
				if($enablespcode==1)
					$changes = bbencode($changes);
				$changes = htmlentities($changes);
				$changes = str_replace("\n", "<BR>", $changes);
				$changes=addslashes($changes);
				$sql = "INSERT INTO ".$tableprefix."_changelog (version, versiondate, programm, changes, isbeta) ";
				$sql .="VALUES ('$input_version', '$versiondate', $programm, '$changes', $isbeta)";
				if(!$result = mysql_query($sql, $db))
				    die("Unable to add changelog to database.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_changelogadded";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newchangelog</a></div>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
			}
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_changelog where (entrynr=$input_entrynr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_changelog where (entrynr=$input_entrynr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");

		if($admin_rights <3)
		{
			$sql2="select * from ".$tableprefix."_programm_admins (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
			if(!$result2 = mysql_query($sql2, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$tmprow = mysql_fetch_array($result2))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
		list($year, $month, $day) = explode("-", $myrow["versiondate"]);
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editchangelog?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_entrynr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if (!$temprow = mysql_fetch_array($result1))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&lang=$lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		$progname=htmlentities($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\"";
		if($myrow["programm"]==$temprow["prognr"])
			echo " selected";
		echo ">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = mysql_fetch_array($result1));
?>
</select>
<?php
	}
?>
</td></tr>
<?php
	$changelogtext=stripslashes($myrow["changes"]);
	$changelogtext = str_replace("<BR>", "\n", $changelogtext);
	$changelogtext = undo_htmlspecialchars($changelogtext);
	$changelogtext = bbdecode($changelogtext);
	$changelogtext = undo_make_clickable($changelogtext);
?>
<tr class="inputrow"><td align="right"><?php echo $l_version?>:</td>
<td><input class="psysinput" type="text" name="input_version" size="20" maxlength="20" value="<?php echo $myrow["version"]?>">
</td></tr>
<tr class="inputrow"><td></td><td><input type="checkbox" name="isbeta" value="1" <?php if($myrow["isbeta"]==1) echo "checked"?>>
<?php echo $l_isbeta?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_versiondate?>:</td>
<td>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="day">
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$day)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="month">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$month)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="year">
<?php
if($year<1900)
{
	list($year, $NULL, $NULL) = explode("-", date("Y-m-d"));
}
for($i=$year-5;$i<$year+6;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$year)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
</tr>
</table>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_changes?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" name="changes" cols="50" rows="10"><?php echo $changelogtext?></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
	}
	if($mode=="update")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<div align=\"center\">";
		$errors=0;
		if(!isset($isbeta))
			$isbeta=0;
		if(!$input_version)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noversion</td></tr>";
			$errors=1;
		}
		if($programm<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!checkdate($month,$day,$year))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_invaliddate</td></tr>";
			$errors=1;
		}
		else
			$versiondate=$year."-".$month."-".$day;
		if(!$changes)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nochanges</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($local_urlautoencode))
				$urlautoencode=0;
			else
				$urlautoencode=1;
			if(!isset($local_enablespcode))
				$enablespcode=0;
			else
				$enablespcode=1;
			if(isset($preview))
			{
				$displaychanges=$changes;
				if($urlautoencode==1)
					$displaychanges = make_clickable($displaychanges);
				if($enablespcode==1)
					$displaychanges = bbencode($displaychanges);
				$displaychanges = htmlentities($displaychanges);
				$displaychanges = str_replace("\n", "<BR>", $displaychanges);
				$displaychanges = undo_htmlspecialchars($displaychanges);
				$displayversion=strip_tags($input_version);
				$displayversion=htmlentities($displayversion);
				$tempsql = "select * from ".$tableprefix."_programm where prognr=$programm";
				if(!$tempresult = mysql_query($tempsql, $db))
				    die("Unable to connect to database.");
				if(!$temprow=mysql_fetch_array($tempresult))
				{
					$progname=$l_undefined;
					$proglang=$l_undefined;
				}
				else
				{
					$progname=$temprow["programmname"];
					$proglang=$temprow["language"];
				}
				$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editchangelog?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td width="70%"><?php echo $progname." [".$proglang."]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_version?>:</td>
<td width="70%"><?php echo $displayversion?><input type="hidden" name="input_version" value="<?php echo $input_version?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_versiondate?>:</td>
<td width="70%"><?php echo $displaydate?>
<input type="hidden" name="year" value="<?php echo $year?>">
<input type="hidden" name="month" value="<?php echo $month?>">
<input type="hidden" name="day" value="<?php echo $day?>">
<input type="hidden" name="isbeta" value="<?php echo $isbeta?>">
</td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_changes?>:</td>
<td width="70%"><?php echo $displaychanges?><input type="hidden" name="changes" value="<?php echo $changes?>"></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				$input_version=strip_tags($input_version);
				$input_version=htmlentities($input_version);
				$input_version=addslashes($input_version);
				if($urlautoencode==1)
					$changes = make_clickable($changes);
				if($enablespcode==1)
					$changes = bbencode($changes);
				$changes = htmlentities($changes);
				$changes = str_replace("\n", "<BR>", $changes);
				$changes=addslashes($changes);
				$sql = "UPDATE ".$tableprefix."_changelog SET isbeta=$isbeta, version='$input_version', versiondate='$versiondate',";
				$sql .="programm=$programm, changes='$changes' ";
				$sql .="WHERE (entrynr = $input_entrynr)";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update the database.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_changelogupdated";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
			}
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="copy")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_changelog where (entrynr=$input_entrynr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		if($admin_rights <3)
		{
			$sql2="select * from ".$tableprefix."_programm_admins (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
			if(!$result2 = mysql_query($sql2, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$tmprow = mysql_fetch_array($result2))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		list($year, $month, $day) = explode("-", $myrow["versiondate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		$changelogtext=stripslashes($myrow["changes"]);
		$changelogtext = undo_htmlspecialchars($changelogtext);
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_copychangelog?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]"?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_version?>:</td>
<td><?php echo $myrow["version"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_versiondate?>:</td>
<td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_changes?>:</td>
<td><?php echo $changelogtext?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
<td align="right"><?php echo $l_destprog?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).");
	}
	if (!$temprow = mysql_fetch_array($result1))
	{
		echo "<a href=\"".do_url_session("program.php?mode=new&lang=$lang")."\" target=\"_blank\">$l_new</a>";
	}
	else
	{
?>
<select name="programm">
<option value="-1">???</option>
<?php
	do {
		if($myrow["programm"]!=$temprow["prognr"])
		{
			$progname=htmlentities($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\">";
			echo "$progname [$proglang]";
			echo "</option>";
		}
	} while($temprow = mysql_fetch_array($result1));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="docopy">
<input class="psysbutton" type="submit" value="<?php echo $l_copy?>"></td></tr></form>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
	}
	if($mode=="massdel")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($entrynr))
		{
    		while(list($null, $entry) = each($_POST["entrynr"]))
    		{
				$del_query = "delete from ".$tableprefix."_changelog where entrynr=$entry";
    		   	if(!mysql_query($del_query, $db))
				    die("<tr class=\"errorrow\"><td>Unable to delete from database.");
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_entriesdeleted";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
	}
	if($mode=="docopy")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<div align=\"center\">";
		$errors=0;
		if($programm<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$tempsql = "select * from ".$tableprefix."_changelog where entrynr=$input_entrynr";
			if(!$tempresult = mysql_query($tempsql, $db)) {
				die("<tr class=\"errorrow\"><td>");
				die("Could not connect to the database (3).");
			}
			if(!$temprow=mysql_fetch_array($tempresult))
				die("<tr class=\"errorrow\"><td>no such entry");
			$sql = "INSERT INTO ".$tableprefix."_changelog (version, versiondate, programm, changes) ";
			$sql .="values ('".$temprow["version"]."', '".$temprow["versiondate"]."', $programm, '".$temprow["changes"]."')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to copy entry.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_changelogcopied";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_changeloglist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights>1)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newchangelog?></a>
</td></tr>
</table></td></tr></table>
<?php
		if($topfilter==1)
		{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<?php
			if($admstorefilter==1)
				echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="top"><?php echo $l_versiontype?>:</td>
<td align="left" width="30%"><select name="beta">
<option value="-1"><?php echo $l_nofilter?></option>
<option value="0" <?php if($beta==0) echo "selected"?>>release</option>
<option value="1" <?php if($beta==1) echo "selected"?>>beta</option>
</select>
</td><td>&nbsp;</td></tr>
<?php
			if($admin_rights<3)
				$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
			else
				$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
			if(!$result1 = mysql_query($sql1, $db)) {
				die("Could not connect to the database (3).".mysql_error());
			}
			if ($temprow = mysql_fetch_array($result1))
			{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
				do {
					$progname=htmlentities($temprow["programmname"]);
					$proglang=$temprow["language"];
					echo "<option value=\"".$temprow["prognr"]."\"";
					if(isset($prognr))
					{
						if($prognr==$temprow["prognr"])
							echo " selected";
					}
					echo ">";
					echo "$progname [$proglang]";
					echo "</option>";
				} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
			}
		}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$firstarg=true;
$sql = "select * from ".$tableprefix."_changelog ";
// Display list of actual changelogs
if(isset($prognr) && ($prognr>=0))
{
	$tempsql="select * from ".$tableprefix."_programm where prognr='$prognr'";
	if(!$tempresult = mysql_query($tempsql, $db)) {
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	}
	if($temprow=mysql_fetch_array($tempresult))
		echo "<tr bgcolor=\"#c0c0c0\"><td colspan=\"6\" align=\"center\">$l_onlyprog: <i>".$temprow["programmname"]."</i></td></tr>";
	$sql.="where programm='$prognr' ";
	$firstarg=false;
}
if(isset($beta) && ($beta>=0))
{
	if($firstarg)
	{
		$sql.="where ";
		$firstarg=false;
	}
	else
		$sql.="and ";
	$sql.="isbeta='$beta' ";
}
$sql.= "order by programm, versiondate desc, version desc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	if($admin_rights > 1)
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<tr class=\"rowheadings\"><td align=\"center\" width=\"5%\">&nbsp;</td>";
	}
	else
		echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"30%\"><b>$l_programm</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_version</b></td>";
	echo "<td align=\"center\" width=\"15%\"><b>$l_versiondate</b></td>";
	echo "<td align=\"center\" width=\"15%\"><b>$l_newsletter</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = mysql_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		$act_id=$myrow["entrynr"];
		if($myrow["versiondate"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		if($admin_rights > 1)
			echo "<td align=\"center\"><input type=\"checkbox\" name=\"entrynr[]\" value=\"$act_id\"></td>";
		echo "<td align=\"center\">".$temprow["programmname"]." [".$temprow["language"]."]</td>";
		echo "<td align=\"center\">".$myrow["version"]."</td>";
		list($year, $month, $day) = explode("-", $myrow["versiondate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo "<td align=\"center\">".$displaydate."</td>";
		list($date,$time) = explode(" ",$myrow["nlsenddate"]);
		list($year, $month, $day) = explode("-", $date);
		list($hour, $min, $sec) = explode(":", $time);
		if($month>0)
			$displaydate=date($dateformatlong,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
		echo "<td align=\"center\">";
		echo $displaydate;
		echo "</td>";
		echo "<td>";
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$temprow["prognr"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = mysql_query($modsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(($modrow = mysql_fetch_array($modresult)) || ($admin_rights > 2))
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_entrynr=$act_id&lang=$lang");
				if($admdelconfirm==1)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_changelog #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
				echo " <a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_entrynr=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a> ";
				if($myrow["isbeta"]==0)
				{
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=copy&input_entrynr=$act_id&lang=$lang")."\">";
					echo "<img src=\"gfx/copy.gif\" border=\"0\" title=\"$l_copy\" alt=\"$l_copy\"></a> ";
				}
				if($temprow["enablenewsletter"]==1)
				{
					$tempsql2 = "select * from ".$tableprefix."_newsletter where programm=".$temprow["prognr"]." and confirmed=1 and listtype=".$myrow["isbeta"];
					if(!$tempresult2 = mysql_query($tempsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if(mysql_num_rows($tempresult2)>0)
					{
						echo "<a class=\"listlink\" href=\"".do_url_session("newsletter.php?mode=changelog&changelognr=$act_id&lang=$lang&prognr=".$temprow["prognr"])."&listtype=".$myrow["isbeta"]."\">";
						echo "<img src=\"gfx/sendmail.gif\" border=\"0\" alt=\"$l_newsletter\" title=\"$l_newsletter\"></a> ";
					}
				}
			}
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_entrynr=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
	} while($myrow = mysql_fetch_array($result));
	if($admin_rights > 1)
	{
		echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"left\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
		echo "<input class=\"psysbutton\" type=\"submit\" value=\"$l_delselected\">";
		echo "</tr></form>";
	}
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
?>
<table class="filterbox" align="center" width="80%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<?php
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td align="right" width="50%" valign="top"><?php echo $l_versiontype?>:</td>
<td align="left" width="30%"><select name="beta">
<option value="-1"><?php echo $l_nofilter?></option>
<option value="0" <?php if($beta==0) echo "selected"?>>release</option>
<option value="1" <?php if($beta==1) echo "selected"?>>beta</option>
</select>
</td><td>&nbsp;</td></tr>
<?php
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db))
		die("Could not connect to the database (3).".mysql_error());
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
		do {
			$progname=htmlentities($temprow["programmname"]);
			$proglang=$temprow["language"];
			echo "<option value=\"".$temprow["prognr"]."\"";
			if(isset($prognr))
			{
				if($prognr==$temprow["prognr"])
					echo " selected";
			}
			echo ">";
			echo "$progname [$proglang]";
			echo "</option>";
		} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
	}
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newchangelog?></a></div>
<?php
}
}
include('trailer.php');
?>