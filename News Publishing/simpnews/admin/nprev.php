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
echo "$l_news - $l_preview";
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
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $l_news." - ".$l_preview?></h2></td></tr>
</table>
<?php
echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
echo "<tr><TD BGCOLOR=\"#000000\">";
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
$newsdate=date("Y-m-d H:i:s");
$errmsg="";
$infotext="";
$preview=1;
if($mode=="add")
{
	if(!isset($headingicon))
		$headingicon="";
	$newstext=trim($newstext);
	if(!isset($newstext) || !$newstext)
		echo "<tr class=\"errorrow\"><td>$l_nonewstext</td></tr>";
	else
	{
		if(isset($rss_short))
		{
			$rss_short=stripslashes($rss_short);
			$rss_short=strip_tags($rss_short);
			$rss_short=addslashes($rss_short);
		}
		if(isset($preview))
		{
			$newstext=stripslashes($newstext);
			$heading=stripslashes($heading);
			echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_previewprelude</td></tr>";
			if(isset($urlautoencode))
				$newstext = make_clickable($newstext);
			if(isset($enablespcode))
				$newstext = bbencode($newstext);
			if(!isset($disableemoticons))
				$newstext = encode_emoticons($newstext, $url_emoticons, $db);
			$newstext = do_htmlentities($newstext);
			$newstext = str_replace("\n", "<BR>", $newstext);
			$newstext = str_replace("\r","",$newstext);
			$newstext = undo_htmlspecialchars($newstext);
			if(isset($specialdate))
			{
				$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
				$actdate=date("Y-m-d H:i:s",$temptime);
			}
			else
				$actdate = date("Y-m-d H:i:s");
			echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"newsicon\">";
			if($headingicon)
				echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr><td align=\"left\" class=\"newsdate\">";
			echo $actdate."</td></tr>";
			if(strlen($heading)>0)
			{
				echo "<tr class=\"newsheading\"><td align=\"left\">";
				echo display_encoded(stripslashes($heading));
				echo "</td></tr>";
			}
			echo "<tr class=\"newsentry\"><td align=\"left\">";
			echo $newstext;
			echo "</td></tr>";
			if(isset($rss_short))
			{
				echo "<tr class=\"displayrow\"><td align=\"left\">";
				echo "$l_rss_short:<br>$rss_short";
				echo "</td></tr>";
			}
			if(isset($tickerurl) && ($tickerurl))
			{
				echo "<tr class=\"displayrow\"><td align=\"left\">";
				echo "$l_tickerurl: $tickerurl";
				echo "</td></tr>";
			}
			if(isset($nopurge))
			{
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
				echo $l_dontpurge;
				echo "<input type=\"hidden\" name=\"dontpurge\" value=\"1\">";
				echo "</td></tr>";
			}
			echo "</table></td></tr>";
		}
	}
}
if($mode=="update")
{
	if(!isset($headingicon))
		$headingicon="";
	$newstext=trim($newstext);
	if(!isset($newstext) || !$newstext)
		echo "<tr class=\"errorrow\"><td>$l_nonewstext</td></tr>";
	else
	{
		if(isset($enablecomments))
			$allowcomments=1;
		else
			$allowcomments=0;
		if(isset($rss_short))
		{
			$rss_short=stripslashes($rss_short);
			$rss_short=strip_tags($rss_short);
			$rss_short=addslashes($rss_short);
		}
		if(isset($preview))
		{
			$newstext=stripslashes($newstext);
			$heading=stripslashes($heading);
			echo "<tr class=\"inforow\"><td align=\"center\" colspan=\"2\">$l_previewprelude</td></tr>";
			if(isset($urlautoencode))
				$newstext = make_clickable($newstext);
			if(isset($enablespcode))
				$newstext = bbencode($newstext);
			if(!isset($disableemoticons))
				$newstext = encode_emoticons($newstext, $url_emoticons, $db);
			$newstext = do_htmlentities($newstext);
			$newstext = str_replace("\n", "<BR>", $newstext);
			$newstext = str_replace("\r", "", $newstext);
			$newstext = undo_htmlspecialchars($newstext);
			$today=date("Y-m-d H:i:s");
			if(isset($resetdate))
			{
				$acttime=transposetime(time(),$servertimezone,$displaytimezone);
				$actdate = date("Y-m-d H:i:s",$acttime);
			}
			else if(isset($specialdate))
			{
				$temptime=mktime($input_hour,$input_min,0,$input_month,$input_day,$input_year);
				$actdate=date("Y-m-d H:i:s",$temptime);
			}
			else
			{
				$tempsql="select * from ".$tableprefix."_data where newsnr=$input_newsnr";
				if(!$tempresult = mysql_query($tempsql, $db))
					die("Unable to connect to database.".mysql_error());
				if($temprow=mysql_fetch_array($tempresult))
				{
					list($mydate,$mytime)=explode(" ",$temprow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					$temptime=mktime($hour,$min,$sec,$month,$day,$year);
					$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
					$actdate=date("Y-m-d H:i:s",$temptime);
				}
				else
					$actdate="";
			}
			echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" class=\"newsicon\">";
			if($headingicon)
				echo "<img src=\"$url_icons/".$headingicon."\" border=\"0\" align=\"middle\"> ";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"#c0c0c0\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr><td align=\"left\" class=\"newsdate\">";
			echo $actdate."</td></tr>";
			if(strlen($heading)>0)
			{
				echo "<tr class=\"newsheading\"><td align=\"left\">";
				echo do_htmlentities($heading);
				echo "</td></tr>";
			}
			echo "<tr class=\"newsentry\"><td align=\"left\">";
			echo $newstext;
			echo "</td></tr>";
			if(isset($rss_short))
			{
				echo "<tr class=\"displayrow\"><td align=\"left\">";
				echo "$l_rss_short:<br>$rss_short";
				echo "</td></tr>";
			}
			if(isset($tickerurl) && ($tickerurl))
			{
				echo "<tr class=\"displayrow\"><td align=\"left\">";
				echo "$l_tickerurl: $tickerurl";
				echo "</td></tr>";
			}
			if(isset($nopurge))
			{
				echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
				echo $l_dontpurge;
				echo "<input type=\"hidden\" name=\"dontpurge\" value=\"1\">";
				echo "</td></tr>";
			}
			echo "</table></td></tr>";
		}
	}
}
echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\"><input class=\"snbutton\" type=\"button\" value=\"$l_closewindow\" onclick=\"window.close()\"></td></tr>";
echo "</table></td></tr></table></body></html>";
?>
