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
$page_title=$l_shutdownsys;
require('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($submit) || isset($preview))
{
	if(isset($shutdowntext))
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
			$displaytext=$shutdowntext;
			if($urlautoencode==1)
				$displaytext = make_clickable($displaytext);
			if($enablespcode==1)
				$displaytext = bbencode($displaytext);
			$displaytext = htmlentities($displaytext);
			$displaytext = str_replace("\n", "<BR>", $displaytext);
			$displaytext = undo_htmlspecialchars($displaytext);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="new" value="<?php echo $new?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<td align="center"><?php echo $l_shutdownpreview?>:</td></tr>
<tr class="displayrow"><td align="center" VALIGN="MIDDLE"><?php echo $displaytext?></td></tr>
<tr class="actionrow" align="center"><form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<td align="center">
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<input class="psysbutton" type="submit" name="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="shutdowntext" value="<?php echo $shutdowntext?>">
</td></form></tr></table></td></tr></table>
<?php
			include('trailer.php');
			exit;
		}
		else
		{
			if($urlautoencode==1)
				$shutdowntext = make_clickable($shutdowntext);
			if($enablespcode==1)
				$shutdowntext = bbencode($shutdowntext);
			$shutdowntext = htmlentities($shutdowntext);
			$shutdowntext = str_replace("\n", "<BR>", $shutdowntext);
			$shutdowntext=addslashes($shutdowntext);
			if($new==1)
				$sql = "INSERT INTO ".$tableprefix."_misc (shutdowntext) values ('$shutdowntext')";
			else
				$sql = "UPDATE ".$tableprefix."_misc SET shutdowntext='$shutdowntext'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
		}
	}
	if(isset($newshutdown))
	{
		if($new==1)
			$sql = "INSERT INTO ".$tableprefix."_misc (shutdown) values ($newshutdown)";
		else
			$sql = "UPDATE ".$tableprefix."_misc SET shutdown=$newshutdown";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to update the database.");
	}
}
$sql="select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	$new=1;
	$shutdown=0;
	$shutdowntext="";
}
else
{
	$new=0;
	$shutdown=$myrow["shutdown"];
	$shutdowntext=stripslashes($myrow["shutdowntext"]);
	$shutdowntext = str_replace("<BR>", "\n", $shutdowntext);
	$shutdowntext = undo_htmlspecialchars($shutdowntext);
	$shutdowntext = bbdecode($shutdowntext);
	$shutdowntext = undo_make_clickable($shutdowntext);
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="new" value="<?php echo $new?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
if($shutdown==1)
	$displaytext=$l_isshutdown;
if($shutdown==0)
	$displaytext=$l_isonline;
if($shutdown==2)
	$displaytext=$l_isnoadmin;
?>
<td align="center" colspan="2"><b><?php echo $l_systemstate?>: <?php echo $displaytext?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_shutdowntext?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td>
<td><textarea class="psysinput" name="shutdowntext" cols="50" rows="10"><?php echo $shutdowntext?></textarea></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="psysbutton" type="submit" name="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>">
</td></form></tr>
<tr class="actionrow"><td align="center" colspan="2">
<a href="<?php echo do_url_session("$act_script_url?lang=$lang&newshutdown=1&submit=1&new=$new")?>"><?php echo $l_shutdown?></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php echo do_url_session("$act_script_url?lang=$lang&newshutdown=0&submit=1&new=$new")?>"><?php echo $l_restart?></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php echo do_url_session("$act_script_url?lang=$lang&newshutdown=2&submit=1&new=$new")?>"><?php echo $l_adminshutdown?></a>
</td></tr>
</table></td></tr></table>
<?php
include('trailer.php');
?>