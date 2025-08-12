<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./functions.php');
require_once('../functions.php');
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
if($userdata["rights"]<3)
	die("$l_functionnotallowed");
if(isset($ACTION))
{
	if ( $ACTION == "UPLOAD" ) {
		if($new_global_handling)
			$tmp_file=$_FILES['emoticonfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['emoticonfile']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
			if($new_global_handling)
				$filename=$_FILES['emoticonfile']['name'];
			else
				$filename=$HTTP_POST_FILES['emoticonfile']['name'];
			if(!move_uploaded_file($tmp_file,$path_emoticons."/".$filename))
			{
				printf($l_cantmovefile,$path_emoticons."/".$filename);
				die();
			}
		}
	} else if ( $ACTION == "DEL" ) {
		unlink($path_emoticons."/".$DELFILE);
}
}
echo "<html><head><title>$l_emoticonupload</title>\n";
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
echo "<script language='javascript'>\nfunction choosepic(filestr) {\nmywin=window.opener;\nmywin.document.forms[0].emoticon_url.value=filestr;\nwindow.close();\n}\n</SCRIPT>\n";
echo "</head><body bgcolor=\"#FFFFFF\"><center>";
if($upload_avail)
{
	echo "<FORM enctype=\"multipart/form-data\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"9999999\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"UPLOAD\">";
	echo "<input class=\"snfile\" name=\"emoticonfile\" type=\"file\" size=\"20\">";
	echo "<input class=\"snbutton\" type=\"submit\" value=\"$l_upload\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "</FORM><br>";
}
/* ********************************************************** */
$cdir = dir($path_emoticons);
echo "<table border=\"0\" width=\"95%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";

$old_cwd = getcwd();
$piccount=0;
if( !chdir($path_emoticons) )
	die($l_wrong_emoticondir);
while ($entry=$cdir->read())
{
	if ((strlen($entry)>2) && (filetype($entry)=="file"))
	{
		if(!($piccount % 2))
			$row_class = "listrow1";
		else
			$row_class = "listrow2";
        $piccount++;
        echo "<tr class=\"$row_class\">";
		echo "<td align=\"center\"><img src=\"$url_emoticons/$entry\" border=\"0\"></a></td>";
		echo "<td>$entry</a></td>";
		echo "<td align=\"right\">".filesize($entry)." bytes</a></td>";
		echo "<td align=\"right\"><a class=\"listlink\" href=\"javascript:choosepic('$entry')\">$l_choose</a></td>";
		echo "<td align=\"right\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?ACTION=DEL&DELFILE=$entry&$langvar=$act_lang")."\">$l_delete</a>";
		echo "</tr>";
	}
}
echo "</table>";
chdir($old_cwd);
if($piccount==0)
	echo "<center>$l_noemoticonsindir</center>";
echo "</center><br><br>";
?>