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
$page_title=$l_icons;
$page="icons";
require_once('./heading.php');
if(!isset($sorting))
	$sorting=11;
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
		if(sn_array_key_exists($admcookievals,"icon_sorting"))
			$sorting=$admcookievals["icon_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	// Page called with some special mode
	if($mode=="new")
	{
		// Display empty form for entering userdata
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_addicon?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_url?>:</td><td><input class="sninput" type="text" name="icon_url" size="40" maxlength="100">
<?php
if($upload_avail)
	echo "<input class=\"snbutton\" type=\"button\" value=\"$l_iconupload\" onClick=\"openWindow('".do_url_session("icon_upload.php?$langvar=$act_lang")."')\">";
else
	echo "<input class=\"snbutton\" type=\"button\" value=\"$l_choose\" onClick=\"openWindow('".do_url_session("icon_upload.php?$langvar=$act_lang")."')\">";
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="snbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_icons?></a></div>
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
		if(!$icon_url)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nourl</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$icon_url=addslashes($icon_url);
			$sql = "INSERT INTO ".$tableprefix."_icons (icon_url) ";
			$sql .="VALUES ('$icon_url')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add icon to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_iconadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_addicon</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_icons</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_icons where (iconnr=$input_iconnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_icons</a></div>";
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
		$sql = "select * from ".$tableprefix."_icons where (iconnr=$input_iconnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editicon?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_iconnr" value="<?php echo $myrow["iconnr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_url?>:</td><td><input class="sninput" type="text" name="icon_url" size="40" maxlength="100" value="<?php echo do_htmlentities(stripslashes($myrow["icon_url"]))?>">
<?php
if($upload_avail)
	echo "<input class=\"snbutton\" type=\"button\" value=\"$l_iconupload\" onClick=\"openWindow('".do_url_session("icon_upload.php?$langvar=$act_lang")."')\">";
else
	echo "<input class=\"snbutton\" type=\"button\" value=\"$l_choose\" onClick=\"openWindow('".do_url_session("icon_upload.php?$langvar=$act_lang")."')\">";
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="snbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_icons?></a></div>
<?php
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
		$errors=0;
		if(!$icon_url)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nourl</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$icon_url=addslashes($icon_url);
			$sql = "UPDATE ".$tableprefix."_icons SET icon_url='$icon_url' ";
			$sql .="WHERE (iconnr = $input_iconnr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to update the database.".mysql_error());
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_iconupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_icons</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\"><td align=\"center\">";
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
<tr class="actionrow"><td align="center" colspan="3">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_addicon?>
</td></tr>
<?php
}
$sql = "select * from ".$tableprefix."_icons ";
switch($sorting)
{
	case 11:
		$sql.="order by icon_url asc";
		break;
	case 12:
		$sql.="order by icon_url desc";
		break;
}
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr bgcolor=\"#c0c0c0\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	$maxsortcol=1;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"30%\"><b>$l_icon</b></td>";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<td align=\"center\" width=\"60%\"><b>";
	echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
	echo "$l_url</a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["iconnr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\"><img src=\"$url_icons/".$myrow["icon_url"]."\" border=\"0\"></td>";
		echo "<td align=\"center\">".do_htmlentities($myrow["icon_url"])."</td>";
		echo "<td>";
		if($admin_rights > 1)
		{
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&input_iconnr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_iconnr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
		}
		echo "</td></tr>";
	} while($myrow = mysql_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_addicon?></a></div>
<?php
}
}
include('./trailer.php');
?>