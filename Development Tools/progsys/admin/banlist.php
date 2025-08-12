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
require('auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_ipbanlist;
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
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
		$sql = "select * from ".$banprefix."_banlist where (bannr=$input_bannr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$displaybanreason=stripslashes($myrow["reason"]);
		$displaybanreason = undo_htmlspecialchars($displaybanreason);
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><?php echo $myrow["ipadr"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subnetmask?>:</td><td><?php echo $myrow["subnetmask"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_reason?>:</td><td><?php echo $displaybanreason?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_ipbanlist</a></div>";
	}
	// Page called with some special mode
	if($mode=="newadr")
	{
		if(!isset($ipadr))
			$ipadr="";
		if(!isset($subnetmask))
			$subnetmask="";
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newadr?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><input class="psysinput" value="<?php echo $ipadr?>" type="text" name="ipadr" size="16" maxlength="16"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subnetmask?>:</td><td><input class="psysinput" value="<?php echo $subnetmask?>" type="text" name="subnetmask" size="16" maxlength="16"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_reason?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" cols="40" rows="5" name="input_banreason"></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="psysbutton" type="submit" value="<?php echo $l_add?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_ipbanlist?></a></div>
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
		if(!$ipadr)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noipadr</td></tr>";
			$errors=1;
		}
		if(!$subnetmask)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nosubnetmask</td></tr>";
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
				$displaybanreason="";
				if($input_banreason)
				{
					$displaybanreason=$input_banreason;
					if($urlautoencode==1)
						$displaybanreason = make_clickable($displaybanreason);
					if($enablespcode==1)
						$displaybanreason = bbencode($displaybanreason);
					$displaybanreason = htmlentities($displaybanreason);
					$displaybanreason = str_replace("\n", "<BR>", $displaybanreason);
					$displaybanreason = undo_htmlspecialchars($displaybanreason);
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newadr?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><?php echo $ipadr?><input type="hidden" name="ipadr" value="<?php echo $ipadr?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subnetmask?>:</td><td><?php echo $subnetmask?><input type="hidden" name="subnetmask" value="<?php echo $subnetmask?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_reason?>:</td><td><?php echo $displaybanreason?><input type="hidden" name="input_banreason" value="<?php echo htmlentities($input_banreason)?>"></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class= "psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="add">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if($input_banreason)
				{
					if($urlautoencode==1)
						$input_banreason = make_clickable($input_banreason);
					if($enablespcode==1)
						$input_banreason = bbencode($input_banreason);
					$input_banreason = htmlentities($input_banreason);
					$input_banreason = str_replace("\n", "<BR>", $input_banreason);
					$input_banreason=addslashes($input_banreason);
				}
				$sql = "INSERT INTO ".$banprefix."_banlist (ipadr, subnetmask, reason) ";
				$sql .="VALUES ('$ipadr', '$subnetmask', '$input_banreason')";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to add address to database.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_adradded";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_ipbanlist</a></div>";
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
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$banprefix."_banlist where (bannr=$input_bannr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_ipbanlist</a></div>";
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
		$sql = "select * from ".$banprefix."_banlist where (bannr=$input_bannr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$reasontext=stripslashes($myrow["reason"]);
		$reasontext = str_replace("<BR>", "\n", $reasontext);
		$reasontext = undo_htmlspecialchars($reasontext);
		$reasontext = bbdecode($reasontext);
		$reasontext = undo_make_clickable($reasontext);
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editbanlist?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_bannr" value="<?php echo $myrow["bannr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><input class="psysinput" type="text" name="ipadr" size="16" maxlength="16" value="<?php echo $myrow["ipadr"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subnetmask?>:</td><td><input class="psysinput" type="text" name="subnetmask" size="16" maxlength="16" value="<?php echo $myrow["subnetmask"]?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_reason?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" cols="40" rows="5" name="input_banreason"><?php echo $reasontext?></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="psysbutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_ipbanlist?></a></div>
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
		if(!$ipadr)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noipadr</td></tr>";
			$errors=1;
		}
		if(!$subnetmask)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nosubnetmask</td></tr>";
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
				$displaybanreason="";
				if($input_banreason)
				{
					$displaybanreason=$input_banreason;
					if($urlautoencode==1)
						$displaybanreason = make_clickable($displaybanreason);
					if($enablespcode==1)
						$displaybanreason = bbencode($displaybanreason);
					$displaybanreason = htmlentities($displaybanreason);
					$displaybanreason = str_replace("\n", "<BR>", $displaybanreason);
					$displaybanreason = undo_htmlspecialchars($displaybanreason);
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newadr?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><?php echo $ipadr?><input type="hidden" name="ipadr" value="<?php echo $ipadr?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subnetmask?>:</td><td><?php echo $subnetmask?><input type="hidden" name="subnetmask" value="<?php echo $subnetmask?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_reason?>:</td><td><?php echo $displaybanreason?><input type="hidden" name="input_banreason" value="<?php echo htmlentities($input_banreason)?>"></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="input_bannr" value="<?php echo $input_bannr?>">;
<input type="hidden" name="mode" value="update">
</td></tr></form></table></td></tr></table>
<?php
			}
			else
			{
				if($input_banreason)
				{
					if($urlautoencode==1)
						$input_banreason = make_clickable($input_banreason);
					if($enablespcode==1)
						$input_banreason = bbencode($input_banreason);
					$input_banreason = htmlentities($input_banreason);
					$input_banreason = str_replace("\n", "<BR>", $input_banreason);
					$input_banreason=addslashes($input_banreason);
				}
				$sql = "UPDATE ".$banprefix."_banlist SET ipadr='$ipadr', subnetmask='$subnetmask', reason='$input_banreason' ";
				$sql .="WHERE (bannr = $input_bannr)";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_adrupdated";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_ipbanlist</a></div>";
			}
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
$sql = "select * from ".$banprefix."_banlist order by bannr";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<tr class="rowheadings">
<td align="center" width="45%"><b><?php echo $l_ipadr?></b></td>
<td align="center" width="45%"><b><?php echo $l_subnetmask?></b></td>
<td>&nbsp;</td></tr>
<?php
		do {
		$act_id=$myrow["bannr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["ipadr"]."</td>";
		echo "<td align=\"right\">".$myrow["subnetmask"]."</td>";
		echo "<td>";
		if($admin_rights > 2)
		{
			$dellink=do_url_session("$act_script_url?mode=delete&input_bannr=$act_id&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('#$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_bannr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_bannr=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=newadr&lang=$lang")?>"><?php echo $l_newadr?></a>
<?php
echo "&nbsp;&nbsp; <a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_failed_logins</a></div>";
}
}
include('trailer.php');
?>