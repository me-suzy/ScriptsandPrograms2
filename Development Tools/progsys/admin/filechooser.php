<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require_once('../config.php');
require_once('../functions.php');
require_once('./functions.php');
require_once('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$user_loggedin=0;
$userdata=Array();
if(!isset($subdir))
	$subdir="";
if($enable_htaccess)
{
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
	    die("<tr class=\"errorrow\"><td>User not defined for Progsys");
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
	if(isset($_COOKIE[$sesscookiename]))
	{
		$sessid = $_COOKIE[$sesscookiename];
		$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		if ($userid)
		{
			$user_loggedin = 1;
			update_session($sessid, $db);
			$userdata = get_userdata_by_id($userid, $db);
		}
	}
}
if($user_loggedin==0)
{
	echo "<div align=\"center\">$l_notloggedin</div>";
	echo "<div align=\"center\">";
	echo "<a href=\"login.php?lang=$lang\">$l_loginpage</a>";
	die ("</div>");
}
else
{
	$admin_rights=$userdata["rights"];
}

if($userdata["rights"]<2)
	die("$l_functionnotallowed");
if(!isset($dirnr))
	die($l_callingerror);
$gfx_dir=$path_gfx;
$pic_url=$url_gfx;
$gfx_basedir=$gfx_dir;
$gfx_baseurl=$pic_url;
if($subdir)
{
	$gfx_dir.=$subdir;
	$pic_url.=$subdir;
}
echo "<html><head><title>";
echo $l_choose;
echo "</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$contentcharset\">\n";
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
echo "<script language='javascript'>\n";
if(($mode==1) || ($mode==2))
{
	echo "function choosepic(filestr)\n";
	echo "{\n";
	echo "	mywin=window.opener;\n";
	echo "	mywin.document.$inputform.$inputfield.value=filestr;\n";
	echo "	window.close();\n";
	echo "	return;\n";
	echo "}\n";
}
else
{
	echo "function choosepic(filestr)\n";
	echo "{\n";
	echo "	var strSelection=\"\";\n";
	echo "	mywin=window.opener;\n";
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = mywin.document.selection.createRange().text;\n";
	echo "	if (strSelection == \"\")\n";
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		mywin.document.$inputform.$inputfield.focus();\n";
	echo "	var addText = \"[img align=$bbcimgdefalign]http://".$simpnewssitename."/\" + filestr + \"[/img]\";\n";
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	mywin.document.selection.createRange().text = addText;\n";
	else
		echo "	mywin.document.$inputform.$inputfield.value += addText;\n";
	echo "	window.close();\n";
	echo "	return;\n";
	echo "}\n";
	echo "function choosethumb(thumbstr, filestr)\n";
	echo "{\n";
	echo "	var strSelection=\"\";\n";
	echo "	mywin=window.opener;\n";
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = mywin.document.selection.createRange().text;\n";
	echo "	if (strSelection == \"\")\n";
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		mywin.document.$inputform.$inputfield.focus();\n";
	echo "	var addText = \"[url=http://".$simpnewssitename."/\"+filestr+\" target=snpics][img align=$bbcimgdefalign]http://".$simpnewssitename."/\" + thumbstr + \"[/img][/url]\";\n";
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	mywin.document.selection.createRange().text = addText;\n";
	else
		echo "	mywin.document.$inputform.$inputfield.value += addText;\n";
	echo "	window.close();\n";
	echo "	return;\n";
	echo "}\n";
}
echo "</SCRIPT>\n";
echo "</head><body><center>";
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
			if($mode==3)
			{
				$accept=true;
				$autoscaled=false;
				$imagesize = GetImageSize ($tmp_file);
				if(($inline_maxwidth>0) && ($imagesize[0]>$inline_maxwidth))
					$accept=false;
				if(($inline_maxheight>0) && ($imagesize[1]>$inline_maxheight))
					$accept=false;
				if($accept)
				{
					if(!move_uploaded_file($tmp_file,$gfx_dir."/".$filename))
					{
						printf($l_cantmovefile,$gfx_dir."/".$filename);
						die();
					}
					if($inline_genthumbs)
					{
						mk_thumb($gfx_dir,$thumbdir,$filename);
					}
				}
				else
				{
					$errmsg=str_replace("{maxwidth}",$inline_maxwidth,$l_gfx2large);
					$errmsg=str_replace("{maxheight}",$inline_maxheight,$errmsg);
					$errmsg=str_replace("{actwidth}",$imagesize[0],$errmsg);
					$errmsg=str_replace("{actheight}",$imagesize[1],$errmsg);
				}
			}
			else
			{
				if(!move_uploaded_file($tmp_file,$gfx_dir."/".$filename))
				{
					printf($l_cantmovefile,$gfx_dir."/".$filename);
					die();
				}
			}
		}
		else
			$errmsg=$l_nofileuploaded;
	} else if ( $ACTION == "DEL" ) {
		if(!@unlink($gfx_dir."/".$DELFILE))
			$errmsg=str_replace("{filename}",$gfx_dir."/".$DELFILE,$l_cantdeletefile);
		else if($mode==3)
			@unlink($gfx_dir."/".$thumbdir."/".$DELFILE);
	} else if ( $ACTION == "MKDIR" ) {
		if($NEWDIR)
		{
			$fullnewdir=$gfx_dir."/".$NEWDIR;
			if(!@mkdir($fullnewdir,0755))
				$errmsg=str_replace("{dirname}",$fullnewdir,$l_unabletocreatedir);
			else
			{
				@chmod($fullnewdir,0777);
				if(($mode==3) && ($inline_genthumbs==1))
				{
					if(!@mkdir($fullnewdir."/".$thumbdir,0755))
					{
						$errmsg=$l_unabletocreatethumbdir;
						$errmsg=str_replace("{dirname}",$fullnewdir,$errmsg);
					}
					else
						@chmod ($fullnewdir."/".$thumbdir,0777);
				}
			}
		}
		else
			$warnings=$l_nodirprovided;
	}
}
if($errmsg)
	echo "<div class=\"errors\">$errmsg</div>";
if($warnings)
	echo "<div class=\"warnings\">$warnings</div>";
if($upload_avail)
{
	echo "<div align=\"center\">";
	echo "<FORM enctype=\"multipart/form-data\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(isset($inputform))
		echo "<input type=\"hidden\" name=\"inputform\" value=\"$inputform\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"9999999\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"UPLOAD\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
	echo "<input type=\"hidden\" name=\"inputfield\" value=\"$inputfield\">";
	echo "<input class=\"snfile\" name=\"USERFILE\" type=\"File\" size=\"20\">";
	echo "<input class=\"snbutton\" type=\"submit\" value=\"$l_upload\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"subdir\" value=\"$subdir\">";
	echo "</FORM><br>";
	echo "<FORM name=\"dirform\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(isset($inputform))
		echo "<input type=\"hidden\" name=\"inputform\" value=\"$inputform\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"subdir\" value=\"$subdir\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"MKDIR\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"$mode\">";
	echo "<input type=\"hidden\" name=\"inputfield\" value=\"$inputfield\">";
	echo "<input class=\"snfile\" name=\"NEWDIR\" type=\"text\" size=\"20\">&nbsp;";
	echo "<input class=\"snbutton\" type=\"submit\" value=\"$l_createsubdir\">";
	echo "</FORM></div><br>";
}
if($mode==3)
{
	echo "<table border=\"0\" width=\"95%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";
	echo "<tr class=\"actionrow\"><td align=\"center\">";
	$linkurl="db2inline.php?$langvar=$act_lang&mode=$mode&inputfield=$inputfield&subdir=$subdir";
	if(isset($inputform))
		$linkurl.="&inputform=$inputform";
	echo "<a class=\"actionlink\" href=\"".do_url_session($linkurl)."\">";
	echo $l_db2inline."</a>";
	echo "</td></tr></table>";
}
/* ********************************************************** */
$cdir = dir($gfx_dir);
echo "<table border=\"0\" width=\"95%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_actbasedir:</b> $gfx_basedir</td></tr>";
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_actsubdir:</b> ";
if($subdir)
{
	$tmpsubdir=substr($subdir,1);
	$subdirparts=explode("/",$tmpsubdir);
	$newsubdir="";
	for($i=0;$i<count($subdirparts);$i++)
	{
		$newsubdir.="/".$subdirparts[$i];
		$link_url="$act_script_url?$langvar=$act_lang&inputfield=$inputfield&subdir=$newsubdir&mode=$mode";
		if(isset($inputform))
			$link_url.="&inputform=$inputform";
		echo "<a class=\"listlink\" href=\"".do_url_session($link_url)."\">";
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
if(!chdir($gfx_dir) )
	die($l_wrong_emoticondir);
if($subdir)
{
		if(substr_count($subdir,"/")>1)
		{
			$newsubdir="";
			$link_url="$act_script_url?$langvar=$act_lang&inputfield=$inputfield&subdir=$newsubdir&mode=$mode";
			if(isset($inputform))
				$link_url.="&inputform=$inputform";
	        echo "<tr class=\"listrow3\">";
			echo "<td align=\"center\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">&lt;/&gt;</a></td>";
			echo "<td><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$l_rootdir</a></td>";
			echo "<td align=\"right\">&nbsp;</td>";
			echo "<td class=\"listlink\" align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$l_changedir</a></td>";
			echo "</tr>";
		}
		if($parentend=strrpos($subdir,"/"))
		{
			if($parentend<1)
				$newsubdir="";
			else
				$newsubdir=substr($subdir,0,$parentend);
		}
		else
			$newsubdir="";
		$link_url="$act_script_url?$langvar=$act_lang&inputfield=$inputfield&subdir=$newsubdir&mode=$mode";
		if(isset($inputform))
			$link_url.="&inputform=$inputform";
        echo "<tr class=\"listrow3\">";
		echo "<td align=\"center\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">&lt;..&gt;</a></td>";
		echo "<td><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$l_parentdir</a></td>";
		echo "<td align=\"right\">&nbsp;</td>";
		echo "<td align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$l_changedir</a></td>";
		echo "</tr>";
}
while ($entry=$cdir->read())
{
	if (($entry!="..") && ($entry!=".") && (filetype($entry)=="dir"))
	{
		if(($mode!=3) || ($entry!=$thumbdir))
		{
			$newsubdir=$subdir."/".$entry;
			$link_url="$act_script_url?$langvar=$act_lang&inputfield=$inputfield&subdir=$newsubdir&mode=$mode";
			if(isset($inputform))
				$link_url.="&inputform=$inputform";
			echo "<tr class=\"listrow3\">";
			echo "<td align=\"center\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">&lt;$entry&gt;</a></td>";
			echo "<td><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$entry</a></td>";
			echo "<td align=\"right\">&nbsp;</td>";
			echo "<td align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$l_changedir</a></td>";
			echo "</tr>";
		}
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
		$reldir=str_replace($gfx_basedir,"",$gfx_dir);
		if(substr($reldir,0,1)=="/")
			$reldir=substr($reldir,1);
		if(strlen($reldir)>0)
			$reldir.="/";
		if(($mode==2) || ($mode==3))
		{
			$reldir=$gfx_baseurl."/".$reldir;
			if(substr($reldir,0,1)=="/")
				$reldir=substr($reldir,1);
		}
        echo "<tr class=\"$row_class\">";
		echo "<td align=\"center\">";
		echo "<a class=\"listlink\" href=\"javascript:choosepic('".$reldir.$entry."')\">";
		if(file_exists($thumbdir."/".$entry))
			echo "<img src=\"$pic_url/$thumbdir/$entry\" border=\"0\">";
		else
			echo "<img src=\"$pic_url/$entry\" border=\"0\">";
		echo "</a>";
		echo "</td>";
		echo "<td><a class=\"listlink\" href=\"javascript:choosepic('".$reldir.$entry."')\">$entry</a></td>";
		echo "<td align=\"right\">".filesize($entry)." bytes</td>";
		echo "<td align=\"right\"><a class=\"listlink\" href=\"javascript:choosepic('".$reldir.$entry."')\">$l_choose</a></td>";
		if($mode==3)
			if(file_exists($thumbdir."/".$entry))
				echo "<td align=\"center\"><a class=\"listlink\" href=\"javascript:choosethumb('".$reldir.$thumbdir."/".$entry."','".$reldir.$entry."')\">$l_choosethumb</a></td>";
			else
				echo "<td>&nbsp;</td>";
		$link_url="$act_script_url?ACTION=DEL&DELFILE=$entry&SUBDIR=$reldir&$langvar=$act_lang&inputfield=$inputfield&subdir=$subdir&mode=$mode";
		if(isset($inputform))
			$link_url.="&inputform=$inputform";
		echo "<td align=\"right\"><a class=\"listlink\" href=\"".do_url_session($link_url)."\">$l_delete</a>";
		echo "</tr>";
	}
}
echo "</table>";
chdir($old_cwd);
if($piccount==0)
	echo "<center>$l_nogfxindir</center>";
echo "</center><br><br>";
function mk_thumb($basedir,$thumbdir,$filename)
{
	global $inline_thumbheight, $inline_thumbwidth;

	$JPG_QUALITY = 90;
	$use_imagecreatetruecolor=true;
	$use_imagecopyresampled=true;
	$orig_file=$basedir."/".$filename;
	$arr_img = image_from_upload($orig_file);
	if($arr_img["img"]==null)
		return;
	$wh	= get_sizes($arr_img["w"], $arr_img["h"], $inline_thumbwidth, $inline_thumbheight);
	$img_res = img_get_resized(
		$arr_img["img"],
		$arr_img["w"], $arr_img["h"],
		$wh["w"], $wh["h"],
		$use_imagecreatetruecolor,
		$use_imagecopyresampled);
	$thumbfile=$basedir."/".$thumbdir."/".$filename;
	ImageJPEG($img_res,$thumbfile, $JPG_QUALITY);
}
function image_from_upload($uploaded_file)
{
	global $inline_thumbwidth, $inline_thumbheight;

	$img=null;
	$img_type="unsupported";
	$img_sz =  getimagesize( $uploaded_file );
	if(($img_sz[0]>$inline_thumbwidth) || ($img_sz[1]>$inline_thumbheight))
	{
		switch( $img_sz[2] ){
			case 2:
				$img = ImageCreateFromJpeg($uploaded_file);
				$img_type = "JPG";
			break;
			case 3:
				$img = ImageCreateFromPng($uploaded_file);
				$img_type = "PNG";
			break;
			case 4:
				$img = ImageCreateFromSwf($uploaded_file);
				$img_type = "SWF";
			break;
		}
	}
	return array("img"=>$img, "w"=>$img_sz[0], "h"=>$img_sz[1], "type"=>$img_sz[2], "html"=>$img_sz[3]);
}
function get_sizes($src_w, $src_h, $dst_w,$dst_h ){
	$mlt_w = $dst_w / $src_w;
	$mlt_h = $dst_h / $src_h;

	$mlt = $mlt_w < $mlt_h ? $mlt_w:$mlt_h;
	if($dst_w == "*") $mlt = $mlt_h;
	if($dst_h == "*") $mlt = $mlt_w;
	if($dst_w == "*" && $dst_h == "*") $mlt=1;

	$img_new_w =  round($src_w * $mlt);
	$img_new_h =  round($src_h * $mlt);
	return array("w" => $img_new_w, "h" => $img_new_h, "mlt_w"=>$mlt_w, "mlt_h"=>$mlt_h,  "mlt"=>$mlt);
}
function img_get_resized($img_original,$img_w,$img_h,$img_new_w,$img_new_h,$use_imagecreatetruecolor=false, $use_imagecopyresampled=false){

	if( $use_imagecreatetruecolor && function_exists("imagecreatetruecolor")){
		$img_resized = imagecreatetruecolor($img_new_w,$img_new_h) or die("<br><font color=\"red\"><b>Failed to create destination image.</b></font><br>");
	} else {
		$img_resized = imagecreate($img_new_w,$img_new_h) or die("<br><font color=\"red\"><b>Failed to create destination image.</b></font><br>");

	}
	if($use_imagecopyresampled && function_exists("imagecopyresampled")){
		imagecopyresampled($img_resized, $img_original, 0, 0, 0, 0,$img_new_w, $img_new_h, $img_w,$img_h) or die("<br><font color=\"red\"><b>Failed to resize @ ImageCopyResampled()</b></font><br>");

	}else{
		imagecopyresized($img_resized, $img_original, 0, 0, 0, 0,$img_new_w, $img_new_h, $img_w,$img_h) or die("<br><font color=\"red\"><b>Failed to resize @ ImageCopyResized()</b></font><br>");
	}
	return $img_resized;
}
?>