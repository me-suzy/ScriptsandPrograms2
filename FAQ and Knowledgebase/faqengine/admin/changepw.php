<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if($admoldhdr)
{
	header('Pragma: no-cache');
	header('Expires: 0');
}
else
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
}
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
require_once('./functions.php');
require_once('../functions.php');
$page="changepw";
$user_loggedin=0;
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	$faqemail=$myrow["faqemail"];
	if(!$faqemail)
		$faqemail="faq@foo.bar";
}
else
{
	$faqemail="faq@foo.bar";
}
if($enable_htaccess)
	die($l_notavail_htaccess);
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
<tr class="prognamerow"><td align="center" class="prognamerow"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<?php
if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
		if ($userid) {
		   $user_loggedin = 1;
		   update_session($url_sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
}
else
{
	$userid="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$sesscookiename]))
		{
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
	else
	{
		if(isset($_COOKIE[$sesscookiename])) {
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
	if ($userid)
	{
	   $user_loggedin = 1;
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_user_ip(), $db);
	}
}
if($user_loggedin==0)
{
	echo "<tr><TD BGCOLOR=\"#000000\">";
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "$l_notloggedin2</td></tr>";
	echo "<tr class=\"actionrow\"><td align=\"center\">";
	echo "<a class=\"actionlink\" href=\"login.php?$langvar=$act_lang\">$l_loginpage</a>";
	echo "</td></tr></table></td></tr></table>";
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	exit;
}
else
	$admin_rights=$userdata["rights"];
if($userdata["lockpw"]==1)
	die($l_functionnotallowed);
if($userdata["lockentry"]==1)
	die($l_functionnotallowed);
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = faqe_db_query($sql, $db))
    die("Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	$enablespcode=$myrow["enablespcode"];
	$urlautoencode=$myrow["urlautoencode"];
	$server_timezone=$myrow["timezone"];
	$usemenubar=$myrow["usemenubar"];
	$showtimezone=$myrow["showtimezone"];
	$showcurrtime=$myrow["showcurrtime"];
	$faqlistshortcuts=$myrow["faqlistshortcuts"];
}
else
{
	$enablespcode=0;
	$urlautoencode=0;
	$server_timezone=0;
	$usemenubar=0;
	$showtimezone=1;
	$showcurrtime=0;
	$faqlistshortcuts=0;
}
if($admin_rights==0)
	die("$l_functionnotallowed");
$shutdown=0;
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database (faq_misc).");
if ($temprow = faqe_db_fetch_array($result))
{
	if(($temprow["shutdown"]>0) && ($admin_rights<4))
	{
		echo "<div align=\"center\">";
		$shutdowntext=stripslashes($temprow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		if($shutdowntext)
			echo $shutdowntext;
		else
			echo $l_sysisshutdown;
		echo "</div>";
		exit;
	}
}
?>
<tr class="pagetitlerow"><td align="center" class="pagetitlerow"><h2><?php echo $l_changepw?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if($mode=="change")
	{
		$errors=0;
		if(!$password || !$password2)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nopassword</td></tr>";
			$errors=1;
		}
		if($password2!=$password)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_passwordmismatch</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$password=md5($password);
			$sql = "update ".$tableprefix."_admins set autopin=0, password='$password' where usernr=".$userdata["usernr"];
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to change password in database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_pwchanged";
			echo "</td></tr></table></td></tr></table>";
			include('./trailer.php');
			exit;
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
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_enternewpw?></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="faqeinput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="faqeinput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="change"><input class="faqebutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
<?php
}
?>
</table></td></tr></table>
<?php
echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
?>
</body></html>
