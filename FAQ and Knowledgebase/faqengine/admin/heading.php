<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($page))
	$page="";
if(!isset($uses_bbcode))
	$uses_bbcode=false;
if(!$noseccheck)
{
	$stop_now=0;
	$stopmsg="";
	if(file_exists("./fill_freemailer.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","fill_freemailer.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	if(file_exists("./install.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","install.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	if(file_exists("./mkconfig.php"))
	{
		$stop_now=1;
		$msg=str_replace("{file}","mkconfig.php",$l_remove_file);
		$stopmsg.="<li>$msg";
	}
	$dir = opendir("./");
	while ($file = readdir($dir))
	{
		if (ereg("^upgrade_", $file))
		{
			$stop_now=1;
			$msg=str_replace("{file}",$file,$l_remove_file);
			$stopmsg.="<li>$msg";
		}
	}
	if(@fopen("../config.php", "a"))
	{
		$stop_now=1;
		$stopmsg.="<li>$l_config_writeable";
	}
	if($stop_now==1)
		die("<ul>".$stopmsg."</ul>");
}
require_once('../functions.php');
require_once('./functions.php');
if(is_leacher($HTTP_USER_AGENT))
{
	header("HTTP/1.0 403 Forbidden");
	exit;
}
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
if(!isset($redirect))
{
	// Page to redirect after login
	$redirect=$url_faqengine."/admin/index.php?$langvar=$act_lang";
	if($iis_workaround && $HTTP_SERVER_VARS['QUERY_STRING'])
		$redirect = $act_script_url . "?" .$HTTP_SERVER_VARS['QUERY_STRING'];
	else if($new_global_handling)
		$redirect=$_SERVER["REQUEST_URI"];
	else
		$redirect=$REQUEST_URI;
}
$user_loggedin=0;
$url_sessid=0;
$userdata = Array();
if(isset($do_login))
{
	$myusername=addslashes(strtolower($username));
	$banreason="";
	$result=do_login($myusername,$userpw,$db);
	if($result==22)
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
<table width="80%" align="center" valign="top" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr></table>
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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr></table>
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
<title>FAQEngine - <?php echo $l_loginpage?></title>
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
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr></table>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_loginerror?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="redirect" value="<?php echo $redirect?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="faqeinput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<input type="hidden" name="fid" value="022a9b32a909bf2b875da24f0c8f1225">
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" name="do_login" value="<?php echo $l_login?>"></td></tr>
<?php
if($enablerecoverpw && !$enable_htaccess)
{
?>
<tr class="actionrow"><td align="center" colspan="2"><a href="pwlost.php?<?php echo "$langvar=$act_lang"?>"><?php echo $l_pwlost?></td></tr>
<?php
}
?>
</form></table></td></tr></table>
<?php
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	exit;
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
if($enable_htaccess)
{
	if(isbanned(get_user_ip(),$db))
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
<table width="80%" align="CENTER" valign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr></table>
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
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if (!$myrow = faqe_db_fetch_array($result))
	    die("<tr class=\"errorrow\"><td>$l_undefuser");
	$userid=$myrow["usernr"];
	$user_loggedin=1;
    $userdata = get_userdata_by_id($userid, $db);
}
else if($sessid_url)
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
	$page_title=$l_loginpage;
else if(isset($storefaqfilter))
{
	include('./includes/store_filter.inc');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
if($user_loggedin!=0)
{
	$sql = "select * from ".$tableprefix."_settings where settingnr=1";
	if(!$result = faqe_db_query($sql, $db))
	    die("Could not connect to the database (layout).");
	if ($myrow = faqe_db_fetch_array($result))
	{
		$enablespcode=$myrow["enablespcode"];
		$urlautoencode=$myrow["urlautoencode"];
		$server_timezone=$myrow["timezone"];
		$usemenubar=$myrow["usemenubar"];
		$showtimezone=$myrow["showtimezone"];
		$showcurrtime=$myrow["showcurrtime"];
		$faqlistshortcuts=$myrow["faqlistshortcuts"];
		$faqlimitrelated=$myrow["faqlimitrelated"];
		$bbccolorbar=$myrow["bbccolorbar"];
		$faqemail=$myrow["faqemail"];
		$disablehtmlemail=$myrow["disablehtmlemail"];
		$admstorefaqfilters=$myrow["admstorefaqfilters"];
		$admhideunassigned=$myrow["admhideunassigned"];
		$admdelconfirm=$myrow["admdelconfirm"];
		$zlibavail=$myrow["zlibavail"];
		$defmailsig=$myrow["defmailsig"];
		$msendlimit=$myrow["msendlimit"];
		$admedoptions=$myrow["admedoptions"];
		$textareasrows=$myrow["admtextareasrows"];
		$textareascols=$myrow["admtextareascols"];
		$userquestionanswermail=$myrow["userquestionanswermail"];
		$enablekbrating=$myrow["enablekbrating"];
		$enablehostresolve=$myrow["enablehostresolve"];
		$watchlogins=$myrow["watchlogins"];
		$enablefailednotify=$myrow["enablefailednotify"];
		$loginlimit=$myrow["loginlimit"];
		$displayrating=$myrow["displayrating"];
		$ratecomments=$myrow["ratecomments"];
		$allowusercomments=$myrow["allowusercomments"];
		$maxconfirmtime=$myrow["maxconfirmtime"];
		$userquestionanswermail=$myrow["userquestionanswermail"];
		$userquestionanswermode=$myrow["userquestionanswermode"];
		$userquestionautopublish=$myrow["userquestionautopublish"];
		$nofreemailer=$myrow["nofreemailer"];
		$dateformat=$myrow["admdateformat"];
		$subscriptionavail=$myrow["subscriptionavail"];
		$uqscmail=$myrow["uqscmail"];
		if($myrow["dosearchlog"]==1)
			$searchlogaccess=2;
		else
			$searchlogaccess=5;
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
		$faqlimitrelated=1;
		$bbccolorbar=1;
		$faqemail="faqenine@foo.bar";
		$disablehtmlemail=0;
		$admstorefaqfilters=0;
		$admhideunassigned=0;
		$admdelconfirm=0;
		$zlibavail=0;
		$defmailsig="";
		$msendlimit=30;
		$admedoptions=0;
		$textareasrows=10;
		$textareascols=50;
		$userquestionanswermail=0;
		$enablekbrating=1;
		$enablehostresolve=1;
		$watchlogins=1;
		$enablefailednotify=1;
		$loginlimit=0;
		$displayrating=0;
		$ratecomments=0;
		$allowusercomments=0;
		$maxconfirmtime=0;
		$userquestionanswermail=0;
		$userquestionanswermode=0;
		$userquestionautopublish=0;
		$nofreemailer=0;
		$dateformat="Y-m-d H:i:s";
		$searchlogaccess=5;
		$subscriptionavail=0;
		$uqscmail=0;
	}
	if($subscriptionavail==1)
		$nllevel=2;
	else
		$nllevel=6;
	if($alt_admmenu || (!is_opera() && !is_ns4() && !is_gecko() && !is_msie()))
		require_once("./menus.php");
	else
		require_once("./menus2.php");
	if($uses_bbcode)
		include_once("./includes/js/bbcode.inc");
	include_once("./includes/js/global.inc");
if($alt_admmenu || (!is_opera() && !is_ns4() && !is_gecko() && !is_msie()))
{
	include_once("./includes/js/menu1.inc");
}
else
{
	include_once("./includes/js/menu2.inc");
}
if($page)
{
	if(file_exists('./includes/js/'.$page.'.inc'))
		include_once('./includes/js/'.$page.'.inc');
}
}
?>
</head>
<body>
<table width="80%" align="center" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td align="CENTER" class="sitename"><h4><?php echo "$faqsitedesc ($faqsitename)"?></h4></td></tr>
<tr class="pagetitlerow"><td class="pagetitlerow" align="center"><h2><?php echo $page_title?></h2></td></tr></table>
<?php
if($user_loggedin==0)
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_notloggedin?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="redirect" value="<?php echo $redirect?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="faqeinput" type="password" name="userpw" size="40" maxlength="40"></td></tr>
<input type="hidden" name="fid" value="022a9b32a909bf2b875da24f0c8f1225">
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" name="do_login" value="<?php echo $l_login?>"></td></tr>
<?php
if($enablerecoverpw && !$enable_htaccess)
{
?>
<tr class="actionrow"><td align="center" colspan="2"><a href="pwlost.php?<?php echo "$langvar=$act_lang"?>"><?php echo $l_pwlost?></td></tr>
<?php
}
?>
</form></table></td></tr></table>
<?php
	echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
	exit;
}
else
{
	$shutdown=0;
	$act_usernr=$userdata["usernr"];
	$admin_rights=$userdata["rights"];
	if($admin_rights==0)
	{
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"displayrow\"><td align=\"center\">";
		echo $l_loginsuspended;
		echo "</td></tr></table></td></tr></table>";
		echo "<div align=\"center\"><a href=\"".do_url_session("logout.php?$langvar=$act_lang")."\">";
		echo "$l_logout</a></div>";
		echo "<hr><div class=\"copyright\" align=\"center\">";
		echo "$copyright_url $copyright_note</div>\n";
		die();
	}
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
			$shutdown=1;
			include('./trailer.php');
			exit;
		}
	}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	$nummenucols=0;
	if($usemenubar==1)
	{
		if($alt_admmenu || (!is_opera() && !is_ns4() && !is_gecko() && !is_msie()))
		{
			echo "<tr class=\"menurow\">";
			for($i=0;$i<count($l_menus);$i++)
			{
				if($l_menus[$i][0]["level"]<=$userdata["rights"])
				{
					$menuurl=do_url_session($l_menus[$i][0]["url"]);
					echo "<td align=\"center\" valign=\"middle\" width=\"80\" height=\"20\">";
					echo "<a href=\"".$menuurl."\" ";
					echo "onMouseOver = \"enterTopItem ($i);\" onMouseOut = \"exitTopItem ($i);\" class=\"topMenu\">".$l_menus[$i][0]["entry"]."</a></td>";
					$nummenucols++;
				}
			}
		}
		else
		{
			echo "<tr class=\"menurow\">";
			for($i=0;$i<count($l_menus);$i++)
			{
				if($l_menus[$i][0]["level"]<=$userdata["rights"])
				{
  					echo "<td width=\"8%\">";
  					echo "<ilayer id=\"layerMenu$i\"><div id=\"divMenu$i\">";
    				echo "<img src=\"gfx/space.gif\" width=\"6\" height=\"35\" alt=\"\" border=\"0\">";
  					echo "</div></ilayer></td>";
  					$nummenucols++;
  				}
			}
			for($j=$nummenucols;$j<count($l_menus);$j++)
				echo "<td width=\"8%\">&nbsp;</td>";
		}
	}
	else
		echo "<tr bgcolor=\"#C0C0C0\">";
	echo "</tr>";
	if(($userdata["rights"]>2)||($shutdown<1))
	{
		echo "<tr class=\"actionrow\"><td align=\"center\"";
		if($nummenucols>0)
			echo " colspan=\"".count($l_menus)."\"";
		echo ">";
		echo "<a href=\"".do_url_session("index.php?$langvar=$act_lang")."\">$l_mainmenu</a>";
		echo "</td></tr>";
	}
	echo "</table></td></tr></table>";
}
?>