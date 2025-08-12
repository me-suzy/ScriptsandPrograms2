<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_ipbanlist;
$bbcbuttons=true;
$page="banlist";
require_once('./heading.php');
include_once("./includes/bbcode_buttons.inc");
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$banprefix."_banlist where (bannr=$input_bannr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$displaybanreason=stripslashes($myrow["reason"]);
		$displaybanreason = undo_htmlspecialchars($displaybanreason);
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editbanlist?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><?php echo $myrow["ipadr"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_subnetmask?>:</td><td><?php echo $myrow["subnetmask"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_reason?>:</td><td><?php echo $displaybanreason?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_ipbanlist</a></div>";
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
		// Display empty form for entering userdata
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newadr?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><input class="sninput" type="text" name="ipadr" value="<?php echo $ipadr?>" size="16" maxlength="16"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subnetmask?>:</td><td><input class="sninput" type="text" name="subnetmask" value="<?php echo $subnetmask?>" size="16" maxlength="16"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_reason?>:</td>
<td><textarea class="sninput" cols="40" rows="5" name="input_banreason"></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"input_banreason",false,false) ?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" checked> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" checked> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="snbutton" type="submit" value="<?php echo $l_add?>">
&nbsp;&nbsp;<input class="snbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_ipbanlist?></a></div>
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
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noipadr</td></tr>";
			$errors=1;
		}
		if(!$subnetmask)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
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
					$displaybanreason = do_htmlentities($displaybanreason);
					$displaybanreason = str_replace("\n", "<BR>", $displaybanreason);
					$displaybanreason = undo_htmlspecialchars($displaybanreason);
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newadr?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><?php echo $ipadr?><input type="hidden" name="ipadr" value="<?php echo $ipadr?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subnetmask?>:</td><td><?php echo $subnetmask?><input type="hidden" name="subnetmask" value="<?php echo $subnetmask?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_reason?>:</td><td><?php echo $displaybanreason?><input type="hidden" name="input_banreason" value="<?php echo do_htmlentities($input_banreason)?>"></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="snbutton" type="submit" value="<?php echo $l_enter?>">&nbsp;&nbsp;
<input class="snbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
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
					$input_banreason = do_htmlentities($input_banreason);
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
				echo "<div class=\"bottombox\" align=\"center\">";
				echo "<a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_ipbanlist</a>";
				echo "&nbsp;&nbsp;";
				echo "<a href=\"".do_url_session("loginfailures.php?$langvar=$act_lang")."\">$l_failed_logins</a>";
				echo "</div>";
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
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_ipbanlist</a></div>";
	}
	if($mode=="massdel")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(isset($bannrs))
		{
    		while(list($null, $input_bannr) = each($_POST["bannrs"]))
    		{
				$deleteSQL = "delete from ".$banprefix."_banlist where (bannr=$input_bannr)";
				$success = mysql_query($deleteSQL);
				if (!$success)
					die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_ipbanlist</a></div>";
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
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$reasontext=stripslashes($myrow["reason"]);
		$reasontext = str_replace("<BR>", "\n", $reasontext);
		$reasontext = undo_htmlspecialchars($reasontext);
		$reasontext = bbdecode($reasontext);
		$reasontext = undo_make_clickable($reasontext);
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editbanlist?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_bannr" value="<?php echo $myrow["bannr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><input class="sninput" type="text" name="ipadr" size="16" maxlength="16" value="<?php echo $myrow["ipadr"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_subnetmask?>:</td><td><input class="sninput" type="text" name="subnetmask" size="16" maxlength="16" value="<?php echo $myrow["subnetmask"]?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_reason?>:</td>
<td><textarea class="sninput" cols="40" rows="5" name="input_banreason"><?php echo $reasontext?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"input_banreason",false,false) ?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" checked> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" checked> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="snbutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="snbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_ipbanlist?></a></div>
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
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noipadr</td></tr>";
			$errors=1;
		}
		if(!$subnetmask)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
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
					$displaybanreason = do_htmlentities($displaybanreason);
					$displaybanreason = str_replace("\n", "<BR>", $displaybanreason);
					$displaybanreason = undo_htmlspecialchars($displaybanreason);
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newadr?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_ipadr?>:</td><td><?php echo $ipadr?><input type="hidden" name="ipadr" value="<?php echo $ipadr?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_subnetmask?>:</td><td><?php echo $subnetmask?><input type="hidden" name="subnetmask" value="<?php echo $subnetmask?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_reason?>:</td><td><?php echo $displaybanreason?><input type="hidden" name="input_banreason" value="<?php echo do_htmlentities($input_banreason)?>"></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="snbutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="snbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="input_bannr" value="<?php echo $input_bannr?>">
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
					$input_banreason = do_htmlentities($input_banreason);
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
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_ipbanlist</a></div>";
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
if($admin_rights>2)
{
?>
<tr class="actionrow"><td align="center" colspan="4">
<a href="<?php echo do_url_session("$act_script_url?mode=newadr&$langvar=$act_lang")?>"><?php echo $l_newadr?>
</td></tr>
<?php
}
$sql = "select * from ".$banprefix."_banlist order by ipadr asc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr bgcolor=\"#c0c0c0\">";
	if($admin_rights>2)
		echo "<td align=\"center\" colspan=\"4\">";
	else
		echo "<td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
if($admin_rights>2)
{
	echo "<form name=\"banlist\" method=\"post\" action=\"$act_script_url\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"massdel\">";
}
echo "<tr class=\"rowheadings\">";
if($admin_rights>2)
	echo "<td width=\"5%\">&nbsp;</td>";
?>
<td align="center" width="40%"><b><?php echo $l_ipadr?></b></td>
<td align="center" width="40%"><b><?php echo $l_subnetmask?></b></td>
<td>&nbsp;</td></tr>
<?php
		do {
		$act_id=$myrow["bannr"];
		echo "<tr class=\"displayrow\">";
		if($admin_rights>2)
			echo "<td width=\"5%\"><input type=\"checkbox\" name=\"bannrs[]\" value=\"$act_id\"></td>";
		echo "<td align=\"right\">".$myrow["ipadr"]."</td>";
		echo "<td align=\"right\">".$myrow["subnetmask"]."</td>";
		echo "<td>";
		if($admin_rights > 2)
		{
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&input_bannr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_bannr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_bannr=$act_id&$langvar=$act_lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   if($admin_rights>2)
   {
		echo "<tr class=\"actionrow\"><td colspan=\"4\" align=\"left\"><input class=\"snbutton\" type=\"submit\" value=\"$l_delselected\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"checkAll(document.banlist)\" value=\"$l_checkall\">";
		echo "&nbsp; <input class=\"snbutton\" type=\"button\" onclick=\"uncheckAll(document.banlist)\" value=\"$l_uncheckall\">";
		echo "</td></tr></form>";
	}
	echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=newadr&$langvar=$act_lang")?>"><?php echo $l_newadr?></a></div>
<?php
}
}
include('./trailer.php');
?>