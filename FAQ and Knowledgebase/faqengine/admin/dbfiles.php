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
if($userdata["rights"]<2)
	die($l_functionnotallowed);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $l_select_file_from_db?></title>
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
?>
<script language='javascript'>
function selectFile(selfilename,selfilenr)
{
	var mywin=window.opener;
	mywin.addFile(selfilename,selfilenr);
}
</script>
</head>
<body>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr class="headingrow"><td align="CENTER"><b><?php echo $l_select_file_from_db?></b></td>
<td align="center" valign="middle" width="2%"><a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="../gfx/close.gif" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a></font></td></tr>
</table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(isset($ACTION))
{
	if($ACTION=="UPLOAD")
	{
		if($new_global_handling)
			$tmp_file=$_FILES['uploadfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['uploadfile']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
			$errors=0;
			$errmsg="";
			if($new_global_handling)
			{
				$filename=$_FILES['uploadfile']['name'];
				$filesize=$_FILES['uploadfile']['size'];
			}
			else
			{
				$filename=$HTTP_POST_FILES['uploadfile']['name'];
				$filesize=$HTTP_POST_FILES['uploadfile']['size'];
			}
			$filetype=getUploadFileType($filename);
			if($attach_in_fs)
			{
				$filedata="";
				if ( preg_match("/[\\/:*?\"<>|]/i", $filename) )
				{
					$errors = 1;
					$errmsg.="<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">";
					$errmsg.= $l_invalidfilename;
					$errmsg.="</td></tr>";
				}
				else
				{
					if(file_exists($path_attach."/".$filename))
					{
						$tmpnum=1;
						$tmpext=getRealFileExtension($filename);
						$tmpfilename=getRealFilename($filename);
						while(file_exists($path_attach."/".$tmpfilename."_".$tmpnum.".".$tmpext))
							$tmpnum++;
						$physfile=$tmpfilename."_".$tmpnum.".".$tmpext;
					}
					else
						$physfile=$filename;
					if(!move_uploaded_file($tmp_file,$path_attach."/".$physfile))
					{
						$errors=1;
						$errmsg.="<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">";
						$errmsg.= sprintf($l_cantmovefile,$path_attach."/".$physfile);
						$errmsg.="</td></tr>";
					}
					else if($attach_do_chmod)
						chmod($patch_attach."/".$physfile, $attach_fmode);
				}
			}
			else
				$filedata=addslashes(get_file($tmp_file));
			if($errors==0)
			{
				$sql ="INSERT INTO ".$tableprefix."_files (filename, filesize, mimetype, bindata";
				if($attach_in_fs)
					$sql.=", fs_filename";
				$sql.=") VALUES (";
				$sql.="'$filename', '$filesize', '$filetype', '$filedata'";
				if($attach_in_fs)
					$sql.=", '$physfile'";
				$sql.=")";
				if(!$result = faqe_db_query($sql, $db))
				    echo "<tr class=\"errorrow\"><td colspan=\"4\" align=\"center\">Unable to add file to database. ".faqe_db_error()."</td></tr>";
				else
					echo "<tr class=\"inforow\"><td colspan=\"4\" align=\"center\">$l_file_added</td></tr>";
			}
			else
				echo $errmsg;
		}
		else
		{
			echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"4\">";
			echo "$l_nofile";
			if($has_file_errors)
			{
				$uploaderror=$_FILES['uploadfile']['error'];
				echo " (".$l_fileerrors[$uploaderror].")";
				if($uploaderror==1)
					echo "<br>upload_max_filesize=".ini_get('upload_max_filesize');
				if($uploaderror==2)
					echo "<br>maxfilesize=".$maxfilesize;
			}
			echo "</td></tr>";
		}
	}
}
if($upload_avail)
{
	echo "<tr class=\"inforow\"><td colspan=\"5\"><b>$l_addfile<b></td></tr>";
	echo "<tr class=\"inputrow\"><td colspan=\"5\" align=\"center\">";
	echo "<FORM name=\"gfxform\" enctype=\"multipart/form-data\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(isset($mode))
		echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
	if(isset($faqnr))
		echo "<input type=\"hidden\" name=\"faqnr\" value=\"$faqnr\">";
	if(isset($articlenr))
		echo "<input type=\"hidden\" name=\"faqnr\" value=\"$articlenr\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"UPLOAD\">";
	echo "<input class=\"faqefile\" name=\"uploadfile\" type=\"File\" size=\"20\">&nbsp;";
	echo "<input class=\"faqebutton\" type=\"submit\" name=\"upload\" value=\"$l_upload\">";
	echo "</FORM></td></tr>";
}
?>
<tr class="inforow"><td align="left" colspan="5">
<b><?php echo $l_files_in_db?></b></td></tr>
<?php
	$sql="select * from ".$tableprefix."_files ";
	if(($mode==1) && isset($faqnr))
	{
		$tmpsql="select * from ".$tableprefix."_files f, ".$tableprefix."_faq_attachs fa where f.entrynr=fa.attachnr and fa.faqnr=$faqnr";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(faqe_db_num_rows($tmpresult)>0)
		{
			$firstarg=true;
			while($tmprow=faqe_db_fetch_array($tmpresult))
			{
				if($firstarg)
				{
					$sql.="where ";
					$firstarg=false;
				}
				else
					$sql.="and ";
				$sql.="entrynr!=".$tmprow["attachnr"]." ";
			}
		}
	}
	if(($mode==2) && isset($articlenr))
	{
		$tmpsql="select * from ".$tableprefix."_files f, ".$tableprefix."_kb_attachs kba where f.entrynr=kba.attachnr and kba.articlenr=$articlenr";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(faqe_db_num_rows($tmpresult)>0)
		{
			$firstarg=true;
			while($tmprow=faqe_db_fetch_array($tmpresult))
			{
				if($firstarg)
				{
					$sql.="where ";
					$firstarg=false;
				}
				else
					$sql.="and ";
				$sql.="entrynr!=".$tmprow["attachnr"]." ";
			}
		}
	}
	$sql.="order by filename asc";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if(!$myrow=faqe_db_fetch_array($result))
	{
?>
<tr class="displayrow" align="center">
<td align="center" valign="middle" colspan="5">
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
<td align="center" width="30%">
<b><?php echo $l_description?></b></td>
<td align="left">&nbsp;</td></tr>
<?php
		do{
?>
<tr class="displayrow" align="center">
<td align="center"><?php echo $myrow["filename"]?></td>
<td align="center"><?php echo $myrow["filesize"]?> Bytes</td>
<td align="center"><?php echo $myrow["mimetype"]?></td>
<td align="center"><?php echo $myrow["description"]?></td>
<td align="center">
<a class="listlink" href="javascript:selectFile('<?php echo $myrow["filename"]?>','<?php echo $myrow["entrynr"]?>')">
<?php echo $l_select?></td></tr>
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
