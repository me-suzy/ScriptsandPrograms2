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
if(!isset($annr))
	die($l_callingerror);
if($enable_htaccess)
{
	if(isbanned(get_userip(),$db))
	{
?>
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<title>SimpNews-Administration</title>
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
}
echo "<html><head><title>";
echo "$l_announcement #$annr";
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
echo "<table align=\"center\" width=\"90%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
echo "<tr><TD BGCOLOR=\"#000000\">";
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
$sql = "select * from ".$tableprefix."_announce where entrynr=$annr";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die($l_nosuchentry);
echo "<tr class=\"inforow\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<b>$l_announcement #$annr</b>";
echo "</td></tr>";
if($myrow["category"]>0)
{
	$sql = "select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
	if(!$result2 = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	if($myrow2=mysql_fetch_array($result2))
	{
		echo "<tr class=\"inforow\">";
		echo "<td align=\"center\" colspan=\"2\">";
		echo "<b>$l_category: ".display_encoded($myrow2["catname"])."</b>";
		echo "</td></tr>";
	}
}
echo "<tr class=\"newsicon\"><td width=\"2%\" height=\"100%\" align=\"center\">";
if($myrow["headingicon"])
	echo "<img src=\"$url_icons/".$myrow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
else
	echo "&nbsp;";
echo "</td>";
echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
list($mydate,$mytime)=explode(" ",$myrow["date"]);
list($year, $month, $day) = explode("-", $mydate);
list($hour, $min, $sec) = explode(":",$mytime);
if($month>0)
{
	$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
	$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
	$displaydate=date($l_admdateformat,$displaytime);
}
else
	$displaydate="";
echo "<tr class=\"newsdate\"><td align=\"left\" colspan=\"3\">";
echo $displaydate;
echo "</td></tr>";
if(strlen($myrow["heading"])>0)
{
	echo "<tr class=\"newsheading\"><td align=\"left\" colspan=\"3\">";
	echo display_encoded($myrow["heading"]);
	echo "</td></tr>";
}
echo "<tr class=\"newsentry\"><td align=\"left\" colspan=\"3\">";
$displaytext=stripslashes($myrow["text"]);
$displaytext = undo_htmlspecialchars($displaytext);
echo $displaytext."</td></tr>";
if(strlen($myrow["poster"])>0)
{
	echo "<tr class=\"newsposter\"><td align=\"left\" colspan=\"3\">";
	echo "$l_poster: ".do_htmlentities($myrow["poster"]);
	echo "</td></tr>";
}
echo "</table></td></tr></table>";
?>