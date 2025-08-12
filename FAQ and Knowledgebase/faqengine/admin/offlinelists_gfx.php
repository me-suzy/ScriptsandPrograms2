<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('../config.php');
include('../functions.php');
if(!$insafemode)
	@set_time_limit($longrunner);
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include('./language/lang_'.$act_lang.'.php');
require('./auth.php');
$crlf="\n";
$url_sessid=0;
$user_loggedin=0;
$userdata=Array();
if($enable_htaccess)
{
	if(isbanned(get_user_ip(),$db))
	{
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" bgcolor="#94AAD6"><font size="+2"><img src="gfx/logo.gif" border="0" align="absmiddle"> <b>FAQEngine v<?php echo $faqeversion?></b></font></td></tr>
<tr><td align="CENTER" bgcolor="#c0c0c0"><font size="+2"><?php echo $page_title?></font></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<tr class="display"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
</table></td></tr></table></body></html>
<?php
	}
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = faqe_db_query($sql, $db))
	    db_die("<tr class=\"errorrow\"><td>Unable to connect to database");
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
}
if($admin_rights<2)
{
	die($l_functionotallowed);
}
$dump_buffer="{".$upload_hash."}".$crlf;
$dump_buffer.="{filedesc}".$crlf;
$dump_buffer.="inlinegfxlist".$crlf;
$dump_buffer.=$inlinegfxlistversion.$crlf;
$dump_buffer.=date("Y-m-d H:i:s").$crlf;
$dump_buffer.="{/filedesc}".$crlf;
$dump_buffer.="{inlinegfxlist}".$crlf;
$dump_buffer.="{baseurl}".$crlf;
$dump_buffer.=$faqe_prot."://".$faqsitename.$url_gfx.$crlf;
$dump_buffer.="{/baseurl}".$crlf;
$dump_buffer.="{basedir}".$crlf;
$dump_buffer.=$path_gfx.$crlf;
$dump_buffer.="{/basedir}".$crlf;
$dump_buffer.=dump_dir($path_gfx);
$dump_buffer.="{/inlinegfxlist}".$crlf;
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
header("Content-Disposition: filename=\"inlinegfx.fqe\"\n");
header("Content-Transfer-Encoding: binary\n");
header("Content-length: ".strlen($dump_buffer)."\n");
print($dump_buffer);
exit;

function dump_dir($dirpath)
{
	global $crlf;
	
	$dir_dump="";
	$cdir=dir($dirpath);
	while ($entry=$cdir->read())
	{
		if ((strlen($entry)>2) && (filetype($dirpath."/".$entry)=="dir"))
			$dir_dump.=dump_dir($dirpath."/".$entry);
	}
	$cdir->rewind();
	while ($entry=$cdir->read())
	{
		if (filetype($dirpath."/".$entry)=="file")
		{
			$dir_dump.="{inlinegfx}".$crlf;
			$dir_dump.=$dirpath."/".$entry.$crlf;
			$dir_dump.=filesize($dirpath."/".$entry).$crlf;
			$dir_dump.="{/inlinegfx}".$crlf;
		}
	}
	return $dir_dump;
}	
?>
