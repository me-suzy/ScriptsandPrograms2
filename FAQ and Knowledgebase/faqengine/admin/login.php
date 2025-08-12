<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./functions.php');
require_once('../functions.php');
require_once('./auth.php');
$url_sessid=0;
$page_title=$l_loginpage;
$redirect="index.php?$langvar=$act_lang";		// Page to redirect after login
if(isset($do_login))
{
	$myusername=addslashes(strtolower($username));
	$banreason="";
	$result=do_login(addslashes($myusername),$userpw,$db);
	if($result==22)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr class="prognamerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><?php echo $l_too_many_users?></td></tr>
</table></td></tr></table></body></html>
<?php
		exit;
	}
	if($result==-99)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td class="prognamerow"><h1><img src="gfx/logo.gif" border="0" align="absmiddle"> FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
</table></td></tr></table></body></html>
<?php
		exit;
	}
	if(($result!=1) && ($result!=4711))
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - <?php echo $page_title?></title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr class="prognamerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_loginerror?></td></tr>
<?php
	}
	else
	{
		if($result==4711)
			$redirect="changepw.php?$langvar=$act_lang";
		if($sessid_url)
			$redirect=do_url_session($redirect);
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
		exit;
	}
}
else
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - <?php echo $page_title?></title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr class="prognamerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_notloggedin?></td></tr>
<?php
}
?>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="faqeinput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" name="do_login" value="<?php echo $l_login?>"></td></tr>
<?php
if($enablerecoverpw)
{
?>
<tr class="actionrow"><td align="center" colspan="2"><a href="pwlost.php?<?php echo "$langvar=$act_lang"?>"><?php echo $l_pwlost?></td></tr>
<?php
}
?>
</form></table></td></tr></table>
<?php
echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
?>
</body></html>