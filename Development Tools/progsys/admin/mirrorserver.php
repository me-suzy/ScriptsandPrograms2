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
$page_title=$l_mirrorserver;
$page="mirrorserver";
require('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="new")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newmirror?></b></td></tr>
<form name="myform" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_name?>:</td><td><input class="psysinput" type="text" name="mirrorname" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_description?>:</td><td><input class="psysinput" type="text" name="mirrordesc" size="40" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_downurl?>:</td><td><input class="psysinput" type="text" name="downurl" size="40" maxlength="255"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add">
<input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_mirrorlist?></a></div>
<?php
	}
	if($mode=="add")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		if(!$mirrorname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(strlen($downurl)>0)
			{
				$downurl=stripslashes($downurl);
				$downurl=str_replace("\\","/",$downurl);
			}
			$sql="INSERT INTO ".$tableprefix."_mirrorserver (servername, description, downurl) ";
			$sql.="VALUES ('".$mirrorname."', '".$mirrordesc."', '".$downurl."')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add mirror to database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_mirroradded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newmirror</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_mirrorlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="display")
	{
		$sql = "select * from ".$tableprefix."_mirrorserver where (servernr=$input_servernr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"headingrow\"><td align=\"center\" colspan=\"2\"><b>$l_displaymirror</b></td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\">$l_name:</td><td>";
		echo htmlentities($myrow["servername"]);
		echo "</td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_description:</td><td>";
		echo htmlentities($myrow["description"]);
		echo "</td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\" width=\"30%\" valign=\"top\">$l_downurl:</td><td>";
		echo $myrow["downurl"];
		echo "</td></tr>";
		echo "</table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_mirrorlist</a></div>";
	}
	if($mode=="delete")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$deleteSQL = "delete from ".$tableprefix."_mirrorserver where (servernr=$input_servernr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_mirrorserver $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_mirrorlist</a></div>";
	}
	if($mode=="edit")
	{
		$sql = "select * from ".$tableprefix."_mirrorserver where (servernr=$input_servernr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editmirror?></b></td></tr>
<form name="myform" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_servernr" value="<?php echo $input_servernr?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_name?>:</td><td><input class="psysinput" type="text" name="mirrorname" size="40" maxlength="80" value="<?php echo htmlentities($myrow["servername"])?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_description?>:</td><td><input class="psysinput" type="text" name="mirrordesc" size="40" maxlength="255" value="<?php echo htmlentities($myrow["description"])?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_downurl?>:</td><td><input class="psysinput" type="text" name="downurl" size="40" maxlength="255" value="<?php echo $myrow["downurl"]?>"></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_mirrorlist?></a></div>
<?php
	}
	if($mode=="update")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		if(!$mirrorname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(strlen($downurl)>0)
			{
				$downurl=stripslashes($downurl);
				$downurl=str_replace("\\","/",$downurl);
			}
			$sql="UPDATE ".$tableprefix."_mirrorserver set servername='$mirrorname', description='$mirrordesc', downurl='$downurl' ";
			$sql.="where servernr=$input_servernr";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to update mirror in database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_mirrorupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_mirrorlist</a></div>";
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
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newmirror?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
// Display list of actual mirrors
$sql = "select * from ".$tableprefix."_mirrorserver order by servernr";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
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
	echo "<td align=\"center\" width=\"30%\"><b>$l_name</b></td>";
	echo "<td align=\"center\" width=\"55%\"><b>$l_description</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["servernr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["servernr"]."</td>";
		echo "<td>".htmlentities($myrow["servername"])."</td>";
		echo "<td align=\"center\">".htmlentities($myrow["description"])."</td><td>";
		$dellink=do_url_session("$act_script_url?mode=delete&input_servernr=$act_id&lang=$lang");
		if($admdelconfirm==1)
			echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_mirror #$act_id','$dellink')\">";
		else
			echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
		echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a> ";
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_servernr=$act_id")."\">";
		echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_servernr=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
	} while($myrow = mysql_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newmirror?></a></div>
<?php
}
}
include('trailer.php');
?>