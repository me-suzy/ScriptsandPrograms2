<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if(!$insafemode)
	@set_time_limit($longrunner);
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
require_once('./functions.php');
require_once('../functions.php');
$showtimezone=0;
$showcurrtime=0;
$usemenubar=0;
$page_title=$l_faqdownload;
$page="faq_download2";
$crlf="\n";
$url_sessid=0;
$user_loggedin=0;
$userdata=Array();
if($enable_htaccess)
{
	if(isbanned(get_user_ip(),$db))
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
<tr><td class="prognamerow"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
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
	}
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if (!$myrow = faqe_db_fetch_array($result))
	{
	    die("<tr class=\"errorrow\"><td>$l_undefuser");
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
	echo "<div align=\"center\">$l_notloggedin2</div>";
	echo "<div align=\"center\">";
	echo "<a href=\"login.php?$langvar=$act_lang\">$l_loginpage</a>";
	die ("</div>");
}
else
{
	$admin_rights=$userdata["rights"];
	$sql = "select * from ".$tableprefix."_settings where settingnr=1";
	if(!$result = faqe_db_query($sql, $db))
	    die("Could not connect to the database (layout).");
	if ($myrow = faqe_db_fetch_array($result))
	{
		$enablespcode=$myrow["enablespcode"];
		$urlautoencode=$myrow["urlautoencode"];
		$server_timezone=$myrow["timezone"];
		$showtimezone=$myrow["showtimezone"];
		$showcurrtime=$myrow["showcurrtime"];
		$faqlistshortcuts=$myrow["faqlistshortcuts"];
		$faqlimitrelated=$myrow["faqlimitrelated"];
		$bbccolorbar=$myrow["bbccolorbar"];
		$faqemail=$myrow["faqemail"];
		$disablehtmlemail=$myrow["disablehtmlemail"];
		$admstorefaqfilters=$myrow["admstorefaqfilters"];
	}
	else
	{
		$enablespcode=0;
		$urlautoencode=0;
		$server_timezone=0;
		$showtimezone=1;
		$showcurrtime=0;
		$faqlistshortcuts=0;
		$faqlimitrelated=1;
		$bbccolorbar=1;
		$faqemail="faqenine@foo.bar";
		$disablehtmlemail=0;
		$admstorefaqfilters=0;
	}
}
if($admin_rights<2)
{
	die($l_functionotallowed);
}
if(!isset($faqnrs))
{
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
<link rel=stylesheet href=./faqeadm.css type=text/css>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td class="prognamerow"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	echo "<tr class=\"errorrow\" align=\"center\"><td>";
	echo "$l_noneselected</td></tr>";
	echo "<tr class=\"actionrow\" align=\"center\"><td>";
	echo "<a href=\"javascript:history.back()\">$l_back</a>";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
$download_data="";
$numfaqs=0;
while(list($null, $faqnr) = each($faqnrs)) {
	$sql="select * from ".$tableprefix."_data where faqnr=$faqnr";
	if(!$result = faqe_db_query($sql, $db))
	    die("Could not connect to the database.");
	if(!$myrow=faqe_db_fetch_array($result))
		die("No entry for selected FAQ #$faqnr");
	$heading=$myrow["heading"];
	$heading=stripslashes($heading);
	$heading=undo_htmlentities($heading);
	$questiontext=$myrow["questiontext"];
	$questiontext=stripslashes($questiontext);
	$questiontext = str_replace("<BR>", $crlf, $questiontext);
	$questiontext = undo_htmlspecialchars($questiontext);
	$questiontext = bbdecode($questiontext);
	$questiontext = undo_make_clickable($questiontext);
	$questiontext = undo_htmlentities($questiontext);
	$answertext=stripslashes($myrow["answertext"]);
	$answertext = str_replace("<BR>", $crlf, $answertext);
	$answertext = undo_htmlspecialchars($answertext);
	$answertext = bbdecode($answertext);
	$answertext = undo_make_clickable($answertext);
	$answertext = undo_htmlentities($answertext);
	$download_data.="{faqentry}".$crlf;
	$download_data.="{faqnr}".$crlf;
	$download_data.=$myrow["faqnr"].$crlf;
	$download_data.="{/faqnr}".$crlf;
	$download_data.="{category}".$crlf;
	$download_data.=$myrow["category"].$crlf;
	$download_data.="{/category}".$crlf;
	$download_data.="{subcategory}".$crlf;
	$download_data.=$myrow["subcategory"].$crlf;
	$download_data.="{/subcategory}".$crlf;
	$download_data.="{heading}".$crlf;
	$download_data.=$heading.$crlf;
	$download_data.="{/heading}".$crlf;
	$download_data.="{question}".$crlf;
	$download_data.=$questiontext.$crlf;
	$download_data.="{/question}".$crlf;
	$download_data.="{answer}".$crlf;
	$download_data.=$answertext.$crlf;
	$download_data.="{/answer}".$crlf;
	$download_data.="{/faqentry}".$crlf;
	$numfaqs++;
}
if($numfaqs>0)
{
	$dump_buffer="{faqlist}".$crlf;
	$dump_buffer.="{".$upload_hash."}".$crlf;
	$dump_buffer.="{filedesc}".$crlf;
	$dump_buffer.="faqlist".$crlf;
	$dump_buffer.=$offlistversion.$crlf;
	$dump_buffer.=date("Y-m-d H:i:s").$crlf;
	$dump_buffer.="{/filedesc}".$crlf;
	$dump_buffer.="{numfaqs}".$crlf;
	$dump_buffer.=$numfaqs.$crlf;
	$dump_buffer.="{/numfaqs}".$crlf;
	$dump_buffer.=$download_data;
	$dump_buffer.="{/faqlist}".$crlf;
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
	header("Content-Type: application/octetstream\n");
	header("Content-Disposition: filename=\"faqs.fls\"\n");
	header("Content-Transfer-Encoding: binary\n");
	header("Content-length: ".strlen($dump_buffer)."\n");
	print($dump_buffer);
}
?>
