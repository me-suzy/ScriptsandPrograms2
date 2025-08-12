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
$page_title=$l_managepartnersites;
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
	// Page called with some special mode
	if($mode=="new")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newpartnersite?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_sitename?>:</td><td><input class="psysinput" type="text" name="name" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_siteurl?>:</td><td><input class="psysinput" type="text" name="siteurl" size="40" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_logourl?>:</td><td><input class="psysinput" type="text" name="logourl" size="40" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_linktarget?>:</td><td><input class="psysinput" type="text" name="linktarget" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td><input class="psysinput" type="text" name="email" size="40" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_emaillang?>:</td><td>
<?php echo language_select($lang,"emaillang","../language")?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="dontlink" value="1"> <?php echo $l_linkingdisabled?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_partnersitelist?></a></div>
<?php
	}
	if($mode=="add")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$name)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nositename</td></tr>";
			$errors=1;
		}
		if(!$siteurl)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nositeurl</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($dontlink))
				$nolinking=1;
			else
				$nolinking=0;
			$sql = "INSERT INTO ".$tableprefix."_partnersites (name, siteurl, email, emaillang, logourl, linktarget, disabled) ";
			$sql .="VALUES ('$name','$siteurl', '$email', '$emaillang', '$logourl', '$linktarget', $nolinking)";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to add address to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_partnersiteadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=newadr&lang=$lang")."\">$l_newpartnersite</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_partnersitelist</a></div>";
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
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_partnersites where (sitenr=$input_sitenr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_partnersitelist</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_partnersites where (sitenr=$input_sitenr)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editpartnersite?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_sitenr" value="<?php echo $myrow["sitenr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_sitename?>:</td><td><input class="psysinput" type="text" name="name" size="40" maxlength="80" value="<?php echo $myrow["name"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_siteurl?>:</td><td><input class="psysinput" type="text" name="siteurl" size="40" maxlength="255" value="<?php echo $myrow["siteurl"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_logourl?>:</td><td><input class="psysinput" type="text" name="logourl" size="40" maxlength="255" value="<?php echo $myrow["logourl"]?>"</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_linktarget?>:</td><td><input class="psysinput" type="text" name="linktarget" size="40" maxlength="80" value="<?php echo $myrow["linktarget"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_email?>:</td><td><input class="psysinput" type="text" name="email" size="40" maxlength="255" value="<?php echo $myrow["email"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_emaillang?>:</td><td>
<?php echo language_select($myrow["emaillang"],"emaillang","../language")?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="dontlink" value="1" <?php if($myrow["disabled"]==1) echo "checked"?>> <?php echo $l_linkingdisabled?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_partnersitelist?></a></div>
<?php
	}
	if($mode=="update")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		$errors=0;
		if(!$name)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nositename</td></tr>";
			$errors=1;
		}
		if(!$siteurl)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nositeurl</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($dontlink))
				$nolinking=1;
			else
				$nolinking=0;
			$sql = "UPDATE ".$tableprefix."_partnersites SET name='$name', siteurl='$siteurl', email='$email', emaillang='$emaillang', logourl='$logourl', linktarget='$linktarget', disabled=$nolinking ";
			$sql .="WHERE (sitenr = $input_sitenr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_partnersiteupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_partnersitelist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newpartnersite?></a>
</td></tr>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$tableprefix."_partnersites order by name";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<tr class="rowheadings">
<td align="center" width="5%"><b>#</b></td>
<td align="center" width="45%"><b><?php echo $l_sitename?></b></td>
<td align="center" width="40%"><b><?php echo $l_siteurl?></b></td>
<td align="center" width="5%">&nbsp;</td>
<td width="30%">&nbsp;</td></tr>
<?php
	do {
		$act_id=$myrow["sitenr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["sitenr"]."</td>";
		echo "<td>".htmlentities($myrow["name"])."</td>";
		echo "<td>".htmlentities($myrow["siteurl"])."</td>";
		echo "<td align=\"center\">";
		if($myrow["disabled"]==1)
			echo "<img src=\"gfx/noentry.gif\" border=\"0\">";
		else
			echo "<img src=\"gfx/go.gif\" border=\"0\">";
		echo "</td>";
		echo "<td>";
		if($admin_rights > 2)
		{
			$dellink=do_url_session("$act_script_url?mode=delete&input_sitenr=$act_id&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('#$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_sitenr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
		}
	} while($myrow = mysql_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newpartnersite?></a></div>
<?php
}
}
include('trailer.php');
?>