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
$page="kb_download2";
$crlf="\n";
$url_sessid=0;
$user_loggedin=0;
$userdata=Array();
$shutdown=0;
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
		db_die("Could not fetch settings from DB.");
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
if(!isset($kbnrs))
{
?>
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
$numkbs=0;
while(list($null, $articlenr) = each($kbnrs)) {
	$sql="select * from ".$tableprefix."_kb_articles where articlenr=$articlenr";
	if(!$result = faqe_db_query($sql, $db))
		db_die("Could not get article data from DB.");
	if(!$myrow=faqe_db_fetch_array($result))
		die("No entry for selected article #$articlenr");
	$heading=$myrow["heading"];
	$heading=stripslashes($heading);
	$heading=undo_htmlentities($heading);
	$text=$myrow["article"];
	$text=stripslashes($text);
	$text = str_replace("<BR>", $crlf, $text);
	$text = undo_htmlspecialchars($text);
	$text = bbdecode($text);
	$text = undo_make_clickable($text);
	$text = undo_htmlentities($text);
	$download_data.="{kbentry}".$crlf;
	$download_data.="{kbnr}".$crlf;
	$download_data.=$myrow["articlenr"].$crlf;
	$download_data.="{/kbnr}".$crlf;
	$download_data.="{category}".$crlf;
	$download_data.=$myrow["category"].$crlf;
	$download_data.="{/category}".$crlf;
	$download_data.="{subcategory}".$crlf;
	$download_data.=$myrow["subcategory"].$crlf;
	$download_data.="{/subcategory}".$crlf;
	$download_data.="{program}".$crlf;
	$download_data.=$myrow["programm"].$crlf;
	$download_data.="{/program}".$crlf;
	$tmpsql="select * from ".$tableprefix."_kb_keywords where articlenr=$articlenr group by keywordnr order by keywordnr asc";
	if(!$tmpresult = faqe_db_query($tmpsql, $db))
		db_die("Could not get keywords from DB.");
	if($tmprow=faqe_db_fetch_array($tmpresult))
	{
		$download_data.="{keywords}".$crlf;
		do{
			$download_data.=$tmprow["keywordnr"].$crlf;
		}while($tmprow=faqe_db_fetch_array($tmpresult));
		$download_data.="{/keywords}".$crlf;
	}
	$tmpsql="select * from ".$tableprefix."_kb_prog_version where articlenr=$articlenr group by progversion order by progversion asc";
	if(!$tmpresult = faqe_db_query($tmpsql, $db))
		db_die("Could not get assigned program versions from DB.");
	if($tmprow=faqe_db_fetch_array($tmpresult))
	{
		$download_data.="{progvers}".$crlf;
		do{
			$download_data.=$tmprow["progversion"].$crlf;
		}while($tmprow=faqe_db_fetch_array($tmpresult));
		$download_data.="{/progvers}".$crlf;
	}
	$tmpsql="select * from ".$tableprefix."_kb_os where articlenr=$articlenr group by osnr order by osnr asc";
	if(!$tmpresult = faqe_db_query($tmpsql, $db))
		db_die("Could not get assigned OS from DB.");
	if($tmprow=faqe_db_fetch_array($tmpresult))
	{
		$download_data.="{os}".$crlf;
		do{
			$download_data.=$tmprow["osnr"].$crlf;
		}while($tmprow=faqe_db_fetch_array($tmpresult));
		$download_data.="{/os}".$crlf;
	}
	$download_data.="{heading}".$crlf;
	$download_data.=$heading.$crlf;
	$download_data.="{/heading}".$crlf;
	$download_data.="{text}".$crlf;
	$download_data.=$text.$crlf;
	$download_data.="{/text}".$crlf;
	$download_data.="{/kbentry}".$crlf;
	$numkbs++;
}
if($numkbs>0)
{
	$dump_buffer="{kblist}".$crlf;
	$dump_buffer.="{".$upload_hash."}".$crlf;
	$dump_buffer.="{filedesc}".$crlf;
	$dump_buffer.="kblist".$crlf;
	$dump_buffer.=$kbofflistversion.$crlf;
	$dump_buffer.=date("Y-m-d H:i:s").$crlf;
	$dump_buffer.="{/filedesc}".$crlf;
	$dump_buffer.="{numkbs}".$crlf;
	$dump_buffer.=$numkbs.$crlf;
	$dump_buffer.="{/numkbs}".$crlf;
	$dump_buffer.=$download_data;
	$dump_buffer.="{/kblist}".$crlf;
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
	header("Content-Disposition: filename=\"kbs.kal\"\n");
	header("Content-Transfer-Encoding: binary\n");
	header("Content-length: ".strlen($dump_buffer)."\n");
	print($dump_buffer);
}
?>
