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
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
require('./auth.php');
$page_title=$l_emptyhostcache;
require('./heading.php');
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
	if($mode=="doclear")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		$sql = "DELETE FROM ".$hcprefix."_hostcache";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center"><?php echo $l_hostcachecleared?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_hostcache</a></div>";
		include('trailer.php');
		exit;
	}
	if($mode=="clear")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="mode" value="doclear">
<td align="center"><?php echo $l_hostcachewarning?></td></tr>
<tr class="actionrow">
<td align="center"><input class="psysbutton" type="submit" name="submit" value="<?php echo $l_yes?>"></td></tr>
</table></td></tr></table>
<?php
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	$sql = "select * from ".$hcprefix."_hostcache order by ipadr";
	if(!$result = mysql_query($sql, $db)) {
	    die("Could not connect to the database.");
	}
?>
<tr class="rowheadings">
<td align="center"><b><?php echo $l_ipadr?></b></td>
<td align="center"><b><?php echo $l_hostname?></b></td>
</tr>
<?php
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		do {
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo $myrow["ipadr"];
			echo "</td><td align=\"center\">";
			echo $myrow["hostname"];
			echo "</td></tr>";
		} while($myrow = mysql_fetch_array($result));
		echo "</table></tr></td></table>";
	}
	if($admin_rights > 2)
	{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=clear&lang=$lang")?>"><?php echo $l_emptyhostcache?></a></div>
<?php
	}
}
include('trailer.php');
?>