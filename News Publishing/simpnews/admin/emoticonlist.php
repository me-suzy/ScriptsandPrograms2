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
if($enable_htaccess)
{
	if(isbanned(get_userip(),$db))
	{
?>
<html>
<head>
<title>SimpNews - Administration</title>
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
?>
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_emoticonlist?></title>
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
<script language='javascript'>
function chooseemoticon(code)
{
	mywin=parent.window.opener;
	addText = " "+code+" ";
	mywin.document.<?php echo $formname?>.<?php echo $inputfield?>.value+=addText;
	parent.window.focus();
	top.window.close();
	mywin.document.<?php echo $formname?>.<?php echo $inputfield?>.focus();
	return;
}
</SCRIPT>
</head>
<?php
	echo "<body";
	if(!$enable_htaccess)
		echo " onLoad=\"capture()\"";
	echo ">\n";
	$sql = "select * from ".$tableprefix."_emoticons";
	if(!$result = mysql_query($sql, $db))
	   	die("Could not connect to the database.");
?>
<table width="98%" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER">
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="#94AAD6" ALIGN="CENTER">
<TR class="headingrow" ALIGN="CENTER"><td><h3><?php echo $l_emoticonlist?></h3></td>
</td>
<td class="actionrow" align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
	if(!$myrow=mysql_fetch_array($result))
	{
?>
<tr class="displayrow" align="center">
<td align="left" valign="middle" colspan="2">
<?php echo $l_noneavailable?></td></tr>
<?php
	}
	else
	{
?>
<tr class="rowheadings" align="center">
<td align="center" valign="middle" width="2%">&nbsp;</td>
<td class="rowheadings" align="center" valign="middle" width="20%">
<b><?php echo $l_code?></b></td>
<td class="rowheadings" align="center" valign="middle">
<b><?php echo $l_emotion?></b></td>
<?php
		do{
?>
<tr class="displayrow" align="center">
<td align="center" valign="middle" width="2%">
<font size="2" color="#000000">
<a class="listlink" href="javascript:chooseemoticon('<?php echo " ".stripslashes($myrow["code"])." "?>')"><img src="<?php echo "$url_emoticons/".stripslashes($myrow["emoticon_url"])?>" border="0"></a></font></td>
<td align="center" valign="middle" width="20%">
<font size="2" color="#000000">
<?php echo do_htmlentities(stripslashes($myrow["code"]))?></font></td>
<td align="center" valign="middle">
<font size="2" color="#000000">
<?php echo do_htmlentities(stripslashes($myrow["emotion"]))?></font></td></tr>
<?php
		}while($myrow=mysql_fetch_array($result));
	}
?>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR class="actionrow" ALIGN="CENTER"><td>&nbsp;</td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
</table></td></tr></table>
</body></html>
