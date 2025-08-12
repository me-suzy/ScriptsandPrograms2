<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
require_once('./auth.php');
require_once('./functions.php');
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
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
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
<tr><td class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
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
	    die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
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
		if(isset($_COOKIE[$sesscookiename])) {
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		}
	}
	if ($userid)
	{
	   $user_loggedin = 1;
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_userip(), $db);
	}
}
if($user_loggedin==0)
	die($l_loginfirst);
if($userdata["rights"]<2)
	die($l_functionnotallowed);
?>
<head>
<title><?php echo $l_db2inline?></title>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
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
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr class="headingrow"><td align="CENTER"><b><?php echo $l_db2inline?></b></td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></font></td></tr>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(isset($action))
{
	$tmpsql="select * from ".$tableprefix."_files where entrynr=$entrynr";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
	if(!$tmprow = mysql_fetch_array($tmpresult))
	    die("<tr class=\"errorrow\"><td>Could not get data from database.");
	if(!$tmprow["bindata"])
	{
		$srcfile=$path_attach."/".stripslashes($tmprow["fs_filename"]);
		$destpath=$path_inline_gfx;
		if($subdir)
			$destpath.="/".$subdir;
		if(file_exists($destpath."/".$filename))
		{
			$tmpnum=1;
			$tmpext=getRealFileExtension($filename);
			$tmpfilename=getRealFilename($filename);
			while(file_exists($destpath."/".$tmpfilename."_".$tmpnum.".".$tmpext))
				$tmpnum++;
			$physfile=$tmpfilename."_".$tmpnum.".".$tmpext;
		}
		else
			$physfile=$filename;
		$destfile=$destpath."/".$phyfile;
		if(!copy($srcfile,$destfile))
		    die("<tr class=\"errorrow\"><td>Unable to copy $srcfile to $destfile.");
	}
	else
	{
		$filename=stripslashes($tmprow["filename"]);
		$destpath=$path_inline_gfx;
		if($subdir)
			$destpath.="/".$subdir;
		if(file_exists($destpath."/".$filename))
		{
			$tmpnum=1;
			$tmpext=getRealFileExtension($filename);
			$tmpfilename=getRealFilename($filename);
			while(file_exists($destpath."/".$tmpfilename."_".$tmpnum.".".$tmpext))
				$tmpnum++;
			$physfile=$tmpfilename."_".$tmpnum.".".$tmpext;
		}
		else
			$physfile=$filename;
		$newfile=@fopen($destpath."/".$physfile,"wb");
		if(!$newfile)
			die("<tr class=\"errorrow\"><td>Error creating file $physfile ($destpath)");
		if(!fwrite($newfile,$tmprow["bindata"]))
			die("<tr class=\"errorrow\"><td>Error writing to file $physfile ($destpath)");
		fclose($newfile);
		if($attach_do_chmod)
			chmod($destpath."/".$physfile, $attach_fmode);
		$destfile=$destpath."/".$physfile;
	}
	echo "<tr class=\"inforow\"><td align=\"left\" colspan=\"4\">";
	printf($l_filecopied2,$destfile);
	echo "</td></tr>";
}
echo "<tr class=\"inforow\"><td align=\"left\" colspan=\"4\">";
echo "<i>$l_destdir: ";
echo $path_inline_gfx;
if($subdir)
	echo "/".$subdir;
echo "</i></td></tr>";
echo "<tr class=\"inforow\"><td align=\"left\" colspan=\"4\">";
echo "<b>$l_files_in_db</b></td></tr>";
$sql="select * from ".$tableprefix."_files ";
$sql.="order by filename asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
?>
<tr class="displayrow" align="center">
<td align="center" valign="middle" colspan="4">
<?php echo $l_none?></td></tr>
<?php
}
else
{
?>
<tr class="rowheadings">
<td align="center" valign="middle" width="30%">
<b><?php echo $l_filename?></b></td>
<td align="center" width="20%">
<b><?php echo $l_filesize?></b></td>
<td align="center" width="30%">
<b><?php echo $l_file_mimetype?></b></td>
<td align="left">&nbsp;</td></tr>
<?php
	do{
		$linkurl="$act_script_url?$langvar=$act_lang&action=copy&mode=$mode&inputfield=$inputfield&subdir=$subdir";
		if(isset($inputform))
			$linkurl.="&inputform=$inputform";
		$linkurl.="&entrynr=".$myrow["entrynr"];
?>
<tr class="displayrow" align="center">
<td align="center"><a class="listlink" href="<?php echo do_url_session($linkurl)?>">
<?php echo $myrow["filename"]?></a></td>
<td align="center"><?php echo $myrow["filesize"]?> Bytes</td>
<td align="center"><?php echo $myrow["mimetype"]?></td>
<td align="center">
<?php
		echo "<a class=\"listlink\" href=\"".do_url_session($linkurl)."\">";
		echo $l_copy."</a>";
		echo "</td></tr>";
	}while($myrow=mysql_fetch_array($result));
}
?>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR class="actionrow" ALIGN="CENTER"><td>
<?php
$linkurl="gfx_upload?$langvar=$act_lang&mode=$mode&inputfield=$inputfield&subdir=$subdir";
if(isset($inputform))
	$linkurl.="&inputform=$inputform";
echo "<a class=\"actionlink\" href=\"".do_url_session($linkurl)."\">";
echo $l_choosegfx."</a>";
?>
</td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></td></tr>
</table></td></tr></table>
</body></html>
