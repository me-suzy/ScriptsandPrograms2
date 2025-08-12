<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
require_once('./functions.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$user_loggedin=0;
$userdata=Array();
if(!isset($subdir))
	$subdir="";
if(!isset($mode))
	$mode=0;
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
$path_gfx=$path_faqe."/gfx";
$gfx_dir=$path_gfx;
$pic_url=$url_faqengine."/gfx";
if($subdir)
{
	$gfx_dir.=$subdir;
	$pic_url.=$subdir;
}
$errmsg="";
$warnings="";
if(isset($ACTION))
{
	if ( $ACTION == "UPLOAD" ) {
		if($new_global_handling)
			$tmp_file=$_FILES['USERFILE']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['USERFILE']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
			if($new_global_handling)
				$filename=$_FILES['USERFILE']['name'];
			else
				$filename=$HTTP_POST_FILES['USERFILE']['name'];
			if(!move_uploaded_file($tmp_file,$gfx_dir."/".$filename))
				$errmsg= sprintf($l_cantmovefile,$gfx_dir."/".$filename);
		}
		else
			$errmsg=$l_nofileuploaded;
	} else if ( $ACTION == "DEL" ) {
		if(!@unlink($gfx_dir."/".$DELFILE))
			$errmsg=str_replace("{filename}",$gfx_dir."/".$DELFILE,$l_cantdeletefile);
	} else if ( $ACTION == "MKDIR" ) {
		if($NEWDIR)
		{
			$fullnewdir=$gfx_dir."/".$NEWDIR;
			if(!@mkdir($fullnewdir,0755))
				$errmsg=str_replace("{dirname}",$fullnewdir,$l_unabletocreatedir);
			else
				@chmod ($fullnewdir,0777);
		}
		else
			$warnings=$l_nodirprovided;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
<?php
if($upload_avail)
	echo $l_gfxupload;
else
	echo $l_choose;
?>
</title>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
echo "<script language='javascript'>\n";
echo "function choosepic(filestr)\n";
echo "{\n";
echo "	mywin=window.opener;\n";
echo "	mywin.document.$inputform.$inputfield.value=filestr;\n";
echo "	window.close();\n";
echo "}\n";
echo "</SCRIPT>\n";
echo "</head><body><center>";
?>
<table width="100%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="headingrow"><td align="CENTER"><b><?php echo $l_uploadgfx?></b></td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></font></td></tr>
</table>
<?php
if($errmsg)
	echo "<div class=\"errorbox\">$errmsg</div>";
if($warnings)
	echo "<div class=\"warningbox\">$warnings</div>";
if($upload_avail)
{
	echo "<FORM enctype=\"multipart/form-data\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"9999999\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"UPLOAD\">";
	echo "<input class=\"faqefile\" name=\"USERFILE\" type=\"File\" size=\"20\">";
	echo "<input class=\"faqebutton\" type=\"submit\" value=\"$l_upload\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"subdir\" value=\"$subdir\">";
	echo "<input type=\"hidden\" name=\"inputfield\" value=\"$inputfield\">";
	echo "<input type=\"hidden\" name=\"inputform\" value=\"$inputform\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
	echo "</FORM><br>";
}
echo "<FORM name=\"dirform\" action=\"$act_script_url\" method=\"post\">";
if($sessid_url)
	echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
echo "<input type=\"hidden\" name=\"subdir\" value=\"$subdir\">";
echo "<input type=\"hidden\" name=\"ACTION\" value=\"MKDIR\">";
echo "<input class=\"faqeinput\" name=\"NEWDIR\" type=\"text\" size=\"20\">&nbsp;";
echo "<input class=\"faqebutton\" type=\"submit\" value=\"$l_createsubdir\">";
echo "<input type=\"hidden\" name=\"inputfield\" value=\"$inputfield\">";
echo "<input type=\"hidden\" name=\"inputform\" value=\"$inputform\">";
echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
echo "</FORM><br>";
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
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir&inputfield=$inputfield&inputform=$inputform&mode=$mode")."\">";
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
$piccount=0;
if( !chdir($gfx_dir) )
	die($l_wrong_emoticondir);
if($subdir)
{
		if(substr_count($subdir,"/")>1)
		{
	        echo "<tr class=\"listrow3\">";
			echo "<td align=\"center\">&lt;/&gt;</a></td>";
			echo "<td>$l_rootdir</td>";
			echo "<td align=\"right\"></td>";
			$newsubdir="";
			echo "<td class=\"listlink\" align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir&inputfield=$inputfield&inputform=$inputform&mode=$mode")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a></td>";
			echo "</tr>";
		}
        	echo "<tr class=\"listrow3\">";
		echo "<td align=\"center\">&lt;..&gt;</td>";
		echo "<td>$l_parentdir</td>";
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
		echo "<td align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir&inputfield=$inputfield&inputform=$inputform&mode=$mode")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a></td>";
		echo "</tr>";
}
while ($entry=$cdir->read())
{
	if ((strlen($entry)>2) && (filetype($entry)=="dir"))
	{
        	echo "<tr class=\"listrow3\">";
		echo "<td align=\"center\">&lt;$entry&gt;</a></td>";
		echo "<td>$entry</td>";
		echo "<td align=\"right\"></td>";
		$newsubdir=$subdir."/".$entry;
		echo "<td align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir&inputfield=$inputfield&inputform=$inputform&mode=$mode")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a></td>";
		echo "</tr>";
	}
}
$cdir->rewind();
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
		echo "<td align=\"center\">";
		echo "<img src=\"$pic_url/$entry\" border=\"0\">";
		echo "</td>";
		echo "<td>$entry</td>";
		echo "<td align=\"right\">".filesize($entry)." bytes</td>";
		$reldir=str_replace($path_gfx,"",$gfx_dir);
		if(substr($reldir,0,1)=="/")
			$reldir=substr($reldir,1);
		if(strlen($reldir)>0)
			$reldir.="/";
		if($mode==1)
		{
			$tmppath=str_replace($path_faqe,"",$path_gfx);
			if(substr($tmppath,0,1)=="/")
				$tmppath=substr($tmppath,1);
			$reldir=$tmppath."/".$reldir;
		}
		echo "<td align=\"right\"><a class=\"listlink\" href=\"javascript:choosepic('".$reldir.$entry."')\"><img src=\"gfx/transfer.gif\" border=\"0\" title=\"$l_choose\" alt=\"$l_choose\"></a></td>";
		echo "<td align=\"right\"><a class=\"listlink\" href=\"$act_script_url?ACTION=DEL&DELFILE=$entry&$langvar=$act_lang&subdir=$subdir&inputfield=$inputfield&inputform=$inputform&mode=$mode\"><img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
		echo "</tr>";
	}
}
echo "</table>";
chdir($old_cwd);
if($piccount==0)
	echo "<center>$l_nogfxindir</center>";
echo "</center><br><br></body></html>";
?>