<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
require_once('./auth.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$user_loggedin=0;
$userdata=Array();
if(!isset($subdir))
	$subdir="";
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
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td class="prognamerow"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
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
	die($l_loginfirst);
if($userdata["rights"]<3)
	die($l_functionnotallowed);
$gfx_dir=$path_gfx;
$pic_url=$url_gfx;
if($subdir)
{
	$gfx_dir.="/".$subdir;
	$pic_url.=$subdir;
}
$errmsg="";
$warnings="";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $l_choosedir?></title>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=\"./css/faqeadm_ns4.css\" type=\"text/css\">\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=\"./css/faqeadm_ns6.css\" type=\"text/css\">\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=\"./css/faqeadm_opera.css\" type=\"text/css\">\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=\"./css/faqeadm_konqueror.css\" type=\"text/css\">\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=\"./css/faqeadm_gecko.css\" type=\"text/css\">\n";
	else
		echo "<link rel=stylesheet href=\"./css/faqeadm.css\" type=\"text/css\">\n";
if($new_global_handling)
	$myserver=$_SERVER['HTTP_HOST'];
else
	$myserver=$HTTP_SERVER_VARS['HTTP_HOST'];
?>
<script language='javascript'>
function choosedir(dirstr)
{
	var mywin=window.opener;
	mywin.inputform.dirname.value=dirstr;
	window.close()
}
</SCRIPT>
</head>
<body><center>
<table width="100%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="headingrow"><td align="CENTER"><b><?php echo $l_choosedir?></b></td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></font></td></tr>
</table>
<?php
if($errmsg)
	echo "<div class=\"errorbox\">$errmsg</div>";
if($warnings)
	echo "<div class=\"warningbox\">$warnings</div>";
/* ********************************************************** */
$cdir = dir($gfx_dir);
echo "<table border=\"0\" width=\"95%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_actsubdir:</b> ";
if($subdir)
{
	$tmpsubdir=substr($subdir,1);
	$subdirparts=explode("/",$tmpsubdir);
	$newsubdir="";
	for($i=0;$i<count($subdirparts);$i++)
	{
		$newsubdir.="/".$subdirparts[$i];
		echo "<a class=\"listlink3\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\">";
		echo $subdirparts[$i];
		echo "</a>/";
	}
}
else
	echo $l_none2;
echo "</td></tr>";
echo "</table>";
echo "<table border=\"0\" width=\"95%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";

$old_cwd = getcwd();
if(!chdir($gfx_dir) )
	die($l_wronggfxdir);
if($subdir)
{
		if(substr_count($subdir,"/")>1)
		{
	        echo "<tr class=\"listrow3\">";
			echo "<td align=\"center\">&lt;/&gt;</a></td>";
			echo "<td>$l_rootdir</a></td>";
			echo "<td align=\"right\"></td>";
			$newsubdir="";
			echo "<td class=\"listlink\" align=\"right\" colspan=\"2\"><a class=\"listlink3\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\">$l_changedir</a></td>";
			echo "</tr>";
		}
        echo "<tr class=\"listrow3\">";
		echo "<td align=\"center\">&lt;..&gt;</a></td>";
		echo "<td>$l_parentdir</a></td>";
		echo "<td align=\"right\"></td>";
		if($parentend=strrpos($subdir,"/"))
		{
			if($parentend<1)
				$newsubdir="";
			else
				$newsubdir=substr($subdir,0,$parentend);
		}
		else
			$newsubdir="";
		echo "<td align=\"right\" colspan=\"2\"><a class=\"listlink3\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\">$l_changedir</a></td>";
		echo "</tr>";
}
while ($entry=$cdir->read())
{
	if ((strlen($entry)>2) && (filetype($entry)=="dir"))
	{
        echo "<tr class=\"listrow3\">";
		echo "<td align=\"center\">&lt;$entry&gt;</a></td>";
		echo "<td>$entry</a></td>";
		echo "<td align=\"right\"></td>";
		$newsubdir=$subdir."/".$entry;
		$selsubdir=substr($newsubdir,1);
		echo "<td align=\"right\" colspan=\"2\">";
		echo "<a class=\"listlink3\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\">$l_changedir</a>&nbsp;";
		echo "<a class=\"listlink3\" href=\"javascript:choosedir('".$selsubdir."');\">$l_choose</a>";
		echo "</td>";
		echo "</tr>";
	}
}
echo "</table>";
chdir($old_cwd);
echo "</center><br><br></body></html>";
?>