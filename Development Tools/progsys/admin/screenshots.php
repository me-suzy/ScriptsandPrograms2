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
require_once('../config.php');
require_once('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include_once('./language/lang_'.$lang.'.php');
$page_title=$l_screenshots;
$page="screenshots";
require_once('./heading.php');
$checked_pic="gfx/checked.gif";
$unchecked_pic="gfx/unchecked.gif";
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
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "select * from ".$tableprefix."_screenshotdirs dirs, ".$tableprefix."_programm prog where prog.prognr=dirs.program and dirs.entrynr=$input_entrynr";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaydirs?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo htmlentities($myrow["programmname"])?> [<?php echo $myrow["language"]?>]</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_picdir?>:</td>
<td><?php echo $myrow["picdir"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_thumbdir?>:</td><td>
<?php echo $myrow["thumbdir"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_addheader?>:</td><td>
<?php echo htmlentities($myrow["addheader"])?></td></tr>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_dirlist</a></div>";
	}
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newscreenshot?></b></td></tr>
<form name="myform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_directory?>:</td><td>
<select name="dir">
<option value="0">&nbsp;</option>
<?php
		$sql="select * from ".$tableprefix."_screenshotdirs dirs, ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where dirs.program=prog.prognr and pa.prognr=prog.prognr and pa.usernr=$act_usernr order by prog.prognr asc";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		while($myrow = mysql_fetch_array($result))
		{
			echo "<option value=\"".$myrow["prognr"]."\">";
			echo htmlentities($myrow["programmname"])." [".$myrow["language"]."] (".$myrow["picdir"].")";
			echo "</option>";
		}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_filename?>:</td><td><input class="psysinput" type="text" name="filename" size="40" maxlength="255">
<input class="psysbutton" type="button" value="<?php echo $l_choose?>" onClick="openWindow('<?php echo do_url_session("filechooser.php?$lang=$lang&dirnr=".$myrow["dir"])?>')">
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_shortcomment?>:</td><td><input class="psysinput" type="text" name="shortcomment" size="40" maxlength="255"></td></tr>
<tr class="inputrow" valign="top"><td align="right"><?php echo $l_longcomment?>:</td><td><textarea class="psysinput" name="longcomment" rows="8" cols="40"></textarea></td></tr>
<?php
if($gdavail)
{
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="genthumb" value="1" checked> <?php echo $l_genthumbnail?></td></tr>
<?php
}
?>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add">
<input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_dirlist?></a></div>
<?php
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
		$errors=0;
		if(!$program)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!$picdir)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nopicdir</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($addheader))
				$addheader="";
			if(strlen($picdir)>0)
			{
				$picdir=stripslashes($picdir);
				$picdir=str_replace("\\","/",$picdir);
			}
			if(strlen($thumbdir)>0)
			{
				$thumbdir=stripslashes($thumbdir);
				$thumbdir=str_replace("\\","/",$thumbdir);
			}
			$sql = "INSERT INTO ".$tableprefix."_screenshotdirs (program, picdir, thumbdir, addheader) ";
			$sql.= "VALUES ($program, '$picdir', '$thumbdir', '$addheader')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add directory to database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_diradded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newdirectory</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_dirlist</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_screenshotdirs where (entrynr=$input_entrynr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "<i>$l_directory</i> $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_dirlist</a></div>";
	}
	if($mode=="edit")
	{
		$modsql="select * from ".$tableprefix."_programm_admins pa, ".$tableprefix."_screenshotdirs dirs where pa.prognr=dirs.program and pa.usernr=$act_usernr and dirs.entrynr=$input_entrynr";
		if(!$modresult = mysql_query($modsql, $db)) {
		    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
		}
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_dirlist</a></div>";
			include('trailer.php');
			exit;
		}
		$sql = "select * from ".$tableprefix."_screenshotdirs where entrynr=$input_entrynr";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editdirs?></b></td></tr>
<form name="myform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_entrynr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td><td>
<select name="program">
<?php
		$tmpsql="select * from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where pa.prognr=prog.prognr and pa.usernr=$act_usernr order by prog.prognr asc";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		while($tmprow = mysql_fetch_array($tmpresult))
		{
			echo "<option value=\"".$tmprow["prognr"]."\"";
			if($tmprow["prognr"]==$myrow["program"])
				echo " selected";
			echo ">";
			echo htmlentities($tmprow["programmname"])." [".$tmprow["language"]."]";
			echo "</option>";
		}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_picdir?>:</td><td><input class="psysinput" type="text" name="picdir" size="40" maxlength="255" value="<?php echo $myrow["picdir"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_thumbdir?>:</td><td><input class="psysinput" type="text" name="thumbdir" size="40" maxlength="255" value="<?php echo $myrow["thumbdir"]?>"></td></tr>
<tr class="inputrow" valign="top"><td align="right"><?php echo $l_addheader?>:</td><td><textarea class="psysinput" name="addheader" rows="8" cols="40"><?php echo htmlentities($myrow["addheader"])?></textarea></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></tr></td></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_dirlist?></a></div>
<?php
	}
	if($mode=="update")
	{
		$modsql="select * from ".$tableprefix."_programm_admins pa, ".$tableprefix."_screenshotdirs dirs where pa.prognr=dirs.program and pa.usernr=$act_usernr and dirs.entrynr=$input_entrynr";
		if(!$modresult = mysql_query($modsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights < 2) || (($admin_rights < 3) && ($ismod==0)))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_functionnotallowed</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_dirlist</a></div>";
			include('trailer.php');
			exit;
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$program)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!$picdir)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nopicdir</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!isset($addheader))
				$addheader="";
			if(strlen($picdir)>0)
			{
				$picdir=stripslashes($picdir);
				$picdir=str_replace("\\","/",$picdir);
			}
			if(strlen($thumbdir)>0)
			{
				$thumbdir=stripslashes($thumbdir);
				$thumbdir=str_replace("\\","/",$thumbdir);
			}
			$sql = "UPDATE ".$tableprefix."_screenshotdirs SET program=$program, picdir='$picdir', thumbdir='$thumbdir', addheader='$addheader' where entrynr=$input_entrynr";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error()."<br>$sql");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_dirupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_dirlist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newscreenshot?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$tableprefix."_screenshotdirs dirs, ".$tableprefix."_programm prog, ".$tableprefix."_screenshots shots where shots.dir=dirs.entrynr and prog.prognr=dirs.program order by prog.prognr asc, dirs.entrynr asc, shots.filename asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
	echo "<td align=\"center\" width=\"50%\"><b>$l_progname</b></td>";
	echo "<td align=\"center\" width=\"15%\"><b>$l_language</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["entrynr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["entrynr"]."</td>";
		echo "<td>".htmlentities($myrow["programmname"])."</td>";
		echo "<td align=\"center\">".$myrow["language"]."</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=".$myrow["prognr"]." and usernr=$act_usernr";
		if(!$modresult = mysql_query($modsql, $db)) {
			die("Could not connect to the database.");
		}
		if($modrow=mysql_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			$dellink=do_url_session("$act_script_url?mode=delete&input_entrynr=$act_id&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_directory #$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" alt=\"$l_delete\" title=\"$l_delete\" border=\"0\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_entrynr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" title=\"$l_edit\" alt=\"$l_edit\" border=\"0\"></a> ";
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_entrynr=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" alt=\"$l_display\" title=\"$l_display\" border=\"0\"></a>";
		echo "</td></tr>";
	} while($myrow = mysql_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newscreenshot?></a></div>
<?php
}
}
include('trailer.php');
?>