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
$page_title=$l_managewparts;
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
	if($mode=="maint")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if(!isset($mm))
			$mm=0;
		$sql="update ".$tableprefix."_wparts set maint=$mm where id=$input_id";
		$success = mysql_query($sql);
		if (!$success)
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		if($mm==0)
			echo "$l_maintdisabled<br>";
		else
			echo "$l_maintenabled<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_wpartlist</a></div>";
	}
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
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newwpart?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_wpartdesc?>:</td><td><input class="psysinput" type="text" name="wpartdesc" size="40" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_wpmainttxt?>:</td><td><textarea class="psysinput" name="wpmainttxt" cols="40" rows="10"></textarea></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="wpmaint" value="1"> <?php echo $l_inmaintmode?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_wpartlist?></a></div>
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
		if(!$wpartdesc)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nowpartdesc</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($wpmaint))
				$maintmode=1;
			else
				$maintmode=0;
			$wpmainttxt=addslashes($wpmainttxt);
			$sql = "INSERT INTO ".$tableprefix."_wparts (wpdesc, mainttxt, maint) ";
			$sql .="VALUES ('$wpartdesc', '$wpmainttxt', $maintmode)";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to add website part to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_wpartadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=newadr&lang=$lang")."\">$l_newwpart</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_wpartlist</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_wparts where (id=$input_id)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_wpartlist</a></div>";
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
		$sql = "select * from ".$tableprefix."_wparts where (id=$input_id)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editwpart?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_id" value="<?php echo $myrow["id"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_wpartdesc?>:</td><td><input class="psysinput" type="text" name="wpartdesc" size="40" maxlength="255" value="<?php echo $myrow["wpdesc"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_wpmainttxt?>:</td><td><textarea class="psysinput" name="wpmainttxt" cols="40" rows="10"><?php echo htmlentities($myrow["mainttxt"])?></textarea></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="wpmaint" value="1" <?php if($myrow["maint"]==1) echo "checked"?>> <?php echo $l_inmaintmode?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_wpartlist?></a></div>
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
		if(!$wpartdesc)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nowpartdesc</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($wpmaint))
				$maintmode=1;
			else
				$maintmode=0;
			$wpmainttxt=addslashes($wpmainttxt);
			$sql = "UPDATE ".$tableprefix."_wparts SET wpdesc='$wpartdesc',  mainttxt='$wpmainttxt', maint=$maintmode ";
			$sql .="WHERE (id = $input_id)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_wpartupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_wpartlist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newwpart?></a>
</td></tr>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$tableprefix."_wparts order by id asc";
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
<td align="center" width="80%"><b><?php echo $l_wpartdesc?></b></td>
<td align="center" width="5%">&nbsp;</td>
<td width="30%">&nbsp;</td></tr>
<?php
	do {
		$act_id=$myrow["id"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"right\">".$myrow["id"]."</td>";
		echo "<td>".htmlentities($myrow["wpdesc"])."</td>";
		echo "<td align=\"center\">";
		if($myrow["maint"]==1)
			echo "<img src=\"gfx/noentry.gif\" border=\"0\">";
		else
			echo "<img src=\"gfx/go.gif\" border=\"0\">";
		echo "</td>";
		echo "<td>";
		if($admin_rights > 2)
		{
			$dellink=do_url_session("$act_script_url?mode=delete&input_id=$act_id&lang=$lang");
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:confirmDel('#$act_id','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_id=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
			if($myrow["maint"]==0)
			{
				echo "<a class=\"listlink\" href=\"";
				echo do_url_session("$act_script_url?mode=maint&lang=$lang&input_id=$act_id&mm=1");
				echo "\" valign=\"top\">";
				echo "<img src=\"gfx/noentry.gif\" border=\"0\" alt=\"$l_gotomaint\" title=\"$l_gotomaint\">";
			}
			else
			{
				echo "<a class=\"listlink\" href=\"";
				echo do_url_session("$act_script_url?mode=maint&lang=$lang&input_id=$act_id&mm=0");
				echo "\" valign=\"top\">";
				echo "<img src=\"gfx/go.gif\" border=\"0\" alt=\"$l_endmaint\" title=\"$l_endmaint\">";
			}
			echo "</a>";
		}
	} while($myrow = mysql_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newwpart?></a></div>
<?php
}
}
include('trailer.php');
?>