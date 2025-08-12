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
$page_title=$l_forbiddenreferers;
require('./heading.php');
if(!isset($address))
	$address="";
$errmsg_new="";
$errmsg_edit="";
$entryadded=false;
$entryupdated=false;
$entrydeleted=false;
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
		$address=trim($address);
		if(!$address)
		{
			$errmsg_new.="<li>$l_noaddress</li>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "INSERT INTO ".$tableprefix."_forbidden_referers (address) ";
			$sql .="VALUES ('$address')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Unable to add address to database.");
			$entryadded=true;
			$address="";
		}
	}
	if(isset($delete))
	{
		$deleteSQL = "delete from ".$tableprefix."_forbidden_referers where (entrynr=$input_entrynr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("$l_cantdelete.");
		$entrydeleted=true;
		$address="";
	}
	if(isset($update))
	{
		$errors=0;
		$address=trim($address);
		if(!$address)
		{
			$errmsg_new.="<li>$l_noaddress</li>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_forbidden_referers SET address='$address' ";
			$sql .="WHERE (entrynr = $input_entrynr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			$entryupdated=true;
		}
		$address="";
	}
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="3">
<b><?php echo $l_addreferer?></b></td></tr>
<?php
if($errmsg_new)
{
	echo "<tr class=\"errorrow\"><td colspan=\"3\">";
	echo "$l_errorsoccured:<ul>$errmsg_new</ul></td></tr>";
}
if($entryadded)
{
	echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
	echo "<i>$l_siteadded</i></td></tr>";
}
?>
<tr class="rowheadings">
<td align="center" width="60%"><b><?php echo $l_siteaddress?></b></td>
<td width="40%">&nbsp;</td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="mode" value="add">
<tr class="inputrow"><td align="center">
<input class="psysinput" type="text" name="address" size="40" maxlength="250" value="<?php echo $address?>"></td>
<td><input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr></form>
<tr class="inforow"><td align="center" colspan="3">
<b><?php echo $l_actualreferers?></b></td></tr>
<?php
if($errmsg_edit)
{
	echo "<tr class=\"errorrow\"><td colspan=\"3\">";
	echo "$l_errorsoccured:<ul>$errmsg_edit</ul></td></tr>";
}
if($entrydeleted)
{
	echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
	echo "<i>$l_sitedeleted</i></td></tr>";
}
if($entryupdated)
{
	echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
	echo "<i>$l_siteupdated</i></td></tr>";
}
$sql = "select * from ".$tableprefix."_forbidden_referers order by address asc";
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
<td align="center" width="60%"><b><?php echo $l_siteaddress?></b></td>
<td width="40%">&nbsp;</td></tr>
<?php
		do {
			$act_id=$myrow["entrynr"];
			echo "<form method=\"post\" action=\"$act_script_url\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">";
			echo "<input type=\"hidden\" name=\"input_entrynr\" value=\"$act_id\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"1\">";
			echo "<tr class=\"inputrow\">";
			echo "<td align=\"center\">";
			echo "<input class=\"psysinput\" type=\"text\" name=\"address\" value=\"".htmlentities($myrow["address"])."\" size=\"40\" maxlength=\"250\"></td>";
			echo "<td>";
			echo "<input class=\"psysbutton\" type=\"submit\" name=\"delete\" value=\"$l_delete\">";
			echo "&nbsp;&nbsp;";
			echo "<input class=\"psysbutton\" type=\"submit\" name=\"update\" value=\"$l_update\">";
			echo "</td></tr></form>";
	   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
include('./trailer.php');
?>