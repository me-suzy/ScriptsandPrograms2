<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
require_once('./functions.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$user_loggedin=0;
$userdata=Array();
if(!isset($mode))
	die($l_callingerror);
if($enable_htaccess)
{
	if(isbanned(get_userip(),$db))
	{
?>
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<title>SimpNews- Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=\"./css/snadm_ns4.css\" type=\"text/css\">\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=\"./css/snadm_ns6.css\" type=\"text/css\">\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=\"./css/snadm_opera.css\" type=\"text/css\">\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=\"./css/snadm_konqueror.css\" type=\"text/css\">\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=\"./css/snadm_gecko.css\" type=\"text/css\">\n";
	else
		echo "<link rel=stylesheet href=\"./css/snadm.css\" type=\"text/css\">\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
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
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_users where username='$myusername'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if (!$myrow = mysql_fetch_array($result))
	{
	    die("<tr class=\"errorrow\"><td>User not defined for SimpNews");
	}
	$userid=$myrow["usernr"];
	$user_loggedin=1;
    $userdata = get_userdata_by_id($userid, $db);
}
else if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_userip(), $db);
		if ($userid) {
		   $user_loggedin = 1;
		   update_session($url_sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($url_sessid, $sesscookietime, get_userip(), $db);
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
			$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		}
	}
	else
	{
		if(isset($_COOKIE[$sesscookiename]))
		{
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		}
	}
	if ($userid) {
	   $user_loggedin = 1;
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_userip(), $db);
	}
}
if($user_loggedin==0)
	die($l_loginfirst);
if($userdata["rights"]<2)
	die("$l_functionnotallowed");
$act_usernr=$userdata["usernr"];
$admin_rights=$userdata["rights"];
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if($myrow = mysql_fetch_array($result))
{
	$usemenubar=$myrow["usemenubar"];
	$servertimezone=$myrow["servertimezone"];
	$displaytimezone=$myrow["displaytimezone"];
	$admrestrict=$myrow["admrestrict"];
	$newsletternoicons=$myrow["newsletternoicons"];
	$admonlyentryheadings=$myrow["admonlyentryheadings"];
	$admentrychars=$myrow["admentrychars"];
	$admdelconfirm=$myrow["admdelconfirm"];
	$mailattach=$myrow["mailattach"];
	$evnewsletterinclude=$myrow["evnewsletterinclude"];
	$msendlimit=$myrow["msendlimit"];
	$admepp=$myrow["admepp"];
	$secsettings=$myrow["secsettings"];
	$bbcimgdefalign=$myrow["bbcimgdefalign"];
	$enablerating=$myrow["enablerating"];
}
else
{
	$usemenubar=0;
	$servertimezone=0;
	$displaytimezone=0;
	$admrestrict=0;
	$newsletternoicons=1;
	$admonlyentryheadings=0;
	$admentrychars=20;
	$admdelconfirm=0;
	$mailattach=0;
	$evnewsletterinclude=0;
	$msendlimit=30;
	$admepp=0;
	$secsettings=0;
	$bbcimgdefalign="center";
	$enablerating=0;
}
echo "<html><head><title>";
echo "$l_events - $l_preview";
echo "</title>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$contentcharset\">";
if(is_ns4())
	echo "<link rel=stylesheet href=\"./css/snadm_ns4.css\" type=\"text/css\">\n";
else if(is_ns6())
	echo "<link rel=stylesheet href=\"./css/snadm_ns6.css\" type=\"text/css\">\n";
else if(is_opera())
	echo "<link rel=stylesheet href=\"./css/snadm_opera.css\" type=\"text/css\">\n";
else if(is_konqueror())
	echo "<link rel=stylesheet href=\"./css/snadm_konqueror.css\" type=\"text/css\">\n";
else if(is_gecko())
	echo "<link rel=stylesheet href=\"./css/snadm_gecko.css\" type=\"text/css\">\n";
else
	echo "<link rel=stylesheet href=\"./css/snadm.css\" type=\"text/css\">\n";
echo "</head><body>";
?>
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$simpnewssitedesc ($simpnewssitename)"?></h4></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $l_events." - ".$l_preview?></h2></td></tr>
</table>
<?php
echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
echo "<tr><TD BGCOLOR=\"#000000\">";
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
$newsdate=date("Y-m-d H:i:s");
$errmsg="";
$infotext="";
$preview=1;
if(!isset($usetime))
	$usetime=0;
if($mode=="add")
{
	if(!isset($headingicon))
		$headingicon="";
	if(!isset($eventtext) || !$eventtext)
		echo "<tr class=\"errorrow\"><td>$l_noeventtext</td></tr>";
	else
	{
		$heading=stripslashes($heading);
		$eventtext=stripslashes($eventtext);
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_previewprelude</td></tr>";
		if($usetime!=0)
			$displaydate=date($l_admdateformat,mktime($sel_hour,$sel_min,0,$sel_month,$sel_day,$sel_year));
		else
			$displaydate=date($l_admdateformat2,mktime(0,0,0,$sel_month,$sel_day,$sel_year));
		if(isset($urlautoencode))
			$eventtext = make_clickable($eventtext);
		if(isset($enablespcode))
			$eventtext = bbencode($eventtext);
		if(!isset($disableemoticons))
			$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
		$eventtext = do_htmlentities($eventtext);
		$eventtext = str_replace("\n", "<BR>", $eventtext);
		$eventtext = undo_htmlspecialchars($eventtext);
		echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"eventicon\">";
		if($headingicon)
			echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
		else
			echo "&nbsp;";
		echo "</td>";
		echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
		echo "<tr><td align=\"left\" class=\"eventdate\">";
		echo $displaydate."</td></tr>";
		if(strlen($heading)>0)
		{
			echo "<tr class=\"eventheading\"><td align=\"left\">";
			echo do_htmlentities($heading);
			echo "</td></tr>";
		}
		echo "<tr class=\"evententry\"><td align=\"left\">";
		echo $eventtext;
		echo "</td></tr>";
		if(isset($wap_short))
		{
			echo "<tr class=\"displayrow\"><td align=\"left\">";
			echo "$l_wap_short:<br>$wap_short";
			echo "</td></tr>";
		}
		echo "</table></td></tr>";
	}
}
if($mode=="update")
{
	if(!isset($headingicon))
		$headingicon="";
	if(!isset($eventtext) || !$eventtext)
		echo "<tr class=\"errorrow\"><td>$l_noeventtext</td></tr>";
	else
	{
		$heading=stripslashes($heading);
		$eventtext=stripslashes($eventtext);
		echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_previewprelude</td></tr>";
		if($usetime!=0)
			$displaydate=date($l_admdateformat,mktime($sel_hour,$sel_min,0,$sel_month,$sel_day,$sel_year));
		else
			$displaydate=date($l_admdateformat2,mktime(0,0,0,$sel_month,$sel_day,$sel_year));
		if(isset($urlautoencode))
			$eventtext = make_clickable($eventtext);
		if(isset($enablespcode))
			$eventtext = bbencode($eventtext);
		if(!isset($disableemoticons))
			$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
		$eventtext = do_htmlentities($eventtext);
		$eventtext = str_replace("\n", "<BR>", $eventtext);
		$eventtext = undo_htmlspecialchars($eventtext);
		echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"eventicon\">";
		if($headingicon)
			echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
		else
			echo "&nbsp;";
		echo "</td>";
		echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
		echo "<tr><td align=\"left\" class=\"eventdate\">";
		echo $displaydate."</td></tr>";
		if(strlen($heading)>0)
		{
			echo "<tr class=\"eventheading\"><td align=\"left\">";
			echo do_htmlentities($heading);
			echo "</td></tr>";
		}
		echo "<tr class=\"evententry\"><td align=\"left\">";
		echo $eventtext;
		echo "</td></tr>";
		if(isset($wap_short))
		{
			echo "<tr class=\"displayrow\"><td align=\"left\">";
			echo "$l_wap_short:<br>$wap_short";
			echo "</td></tr>";
		}
		echo "</table></td></tr>";
	}
}
echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"button\" value=\"$l_closewindow\" onclick=\"window.close()\"></td></tr>";
echo "</table></td></tr></table></body></html>";
?>
