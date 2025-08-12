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
require('../functions.php');
require('./auth.php');
$url_sessid=0;
$page_title=$l_loginpage;
$redirect="index.php?lang=$lang";		// Page to redirect after login
if(isset($do_login))
{
	$myusername=addslashes(strtolower($username));
	$result=do_login(addslashes($myusername),$userpw,$db, &$banreason);
	if($result==22)
	{
?>
<html>
<head>
<title>ProgSys - Administration</title>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" bgcolor="#94AAD6"><font size="+2"><b>ProgSys v<?php echo $version?></b></font></td></tr>
<tr><td align="CENTER" bgcolor="#c0c0c0"><font size="+2"><?php echo $page_title?></font></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr bgcolor="#c0c0c0"><td colspan="2" align="center"><?php echo $l_too_many_users?></td></tr>
</table></td></tr></table></body></html>
<?php
		exit;
	}
	if($result==-99)
	{
?>
<html>
<head>
<title>ProgSys - Administration</title>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" bgcolor="#94AAD6"><font size="+2"><b>ProgSys v<?php echo $version?></b></font></td></tr>
<tr><td align="CENTER" bgcolor="#c0c0c0"><font size="+2"><?php echo $page_title?></font></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr bgcolor="#c0c0c0"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<tr bgcolor="#c0c0c0"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
</table></td></tr></table></body></html>
<?php
		exit;
	}
	if($result!=1)
	{
?>
<html>
<head>
<title>ProgSys - <?php echo $page_title?></title>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" bgcolor="#94AAD6"><font size="+2"><b>ProgSys v<?php echo $version?></b></font></td></tr>
<tr><td align="CENTER" bgcolor="#c0c0c0"><font size="+2"><?php echo $page_title?></font></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr bgcolor="#94AAD6"><td align="center" colspan="2">
<?php echo $l_loginerror?></td></tr>
<?php
	}
	else
	{
		if($sessid_url)
			$redirect=do_url_session($redirect);
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
		exit;
	}
}
else
{
?>
<html>
<head>
<title>ProgSys - <?php echo $page_title?></title>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" bgcolor="#94AAD6"><font size="+2"><b>ProgSys v<?php echo $version?></b></font></td></tr>
<tr><td align="CENTER" bgcolor="#c0c0c0"><font size="+2"><?php echo $page_title?></font></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr bgcolor="#94AAD6"><td align="center" colspan="2">
<?php echo $l_notloggedin?></td></tr>
<?php
}
?>
<tr bgcolor="#c0c0c0"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input type="text" name="username" size="40" maxlength="80"></td></tr>
<tr bgcolor="#c0c0c0"><td align="right"><?php echo $l_password?>:</td><td><input type="password" name="userpw" size="40" maxlength="40"></td></tr>
<tr bgcolor="#94AAD6"><td align="center" colspan="2"><input type="submit" name="do_login" value="<?php echo $l_login?>"></td></tr>
</form></table></td></tr></table>
<?php
echo "<hr><div align=\"center\"><font size=\"2\">$copyright_url</font><br>$copyright_note</div>";
?>
