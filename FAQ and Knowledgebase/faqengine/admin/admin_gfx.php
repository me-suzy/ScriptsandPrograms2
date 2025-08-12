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
require_once('./auth.php');
$page_title=strip_tags($l_admingfx);
require_once('./heading.php');
if(!isset($subdir))
	$subdir="";
$gfx_dir=$path_gfx;
$pic_url=$url_gfx;
if($subdir)
{
	$gfx_dir.="/".$subdir;
	$pic_url.=$subdir;
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_admingfx?></b></td></tr>
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if($userdata["rights"]<3)
{
	$alloweddirs=array();
	$sql="select dir.* from ".$tableprefix."_dir_access dir, ".$tableprefix."_prog_dirs pd, ".$tableprefix."_programm_admins pa where dir.entrynr=pd.dirnr and pd.prognr=pa.prognr and pa.usernr=".$userdata["usernr"];
	if(!$result = faqe_db_query($sql, $db))
	    die("Unable to connect to database.");
	while($myrow=faqe_db_fetch_array($result))
	{
		$dirname=trim(stripslashes($myrow["dirname"]));
		array_push($alloweddirs,$dirname);
	}
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
				$errmsg= sprintf($l_cantmovefile,$path_gfxdir."/".$filename);
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
if($errmsg)
	echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">$errmsg</td></tr>";
if($warnings)
	echo "<tr class=\"warningrow\"><td align=\"center\" colspan=\"2\">$warnings</td></tr>";
echo "<tr class=\"actionrow\"><td align=\"center\" colspan=\"2\">";
echo "<a href=\"".do_url_session("offlinelists_gfx.php?$langvar=$act_lang")."\">";
echo $l_offlinelists;
echo "</a></td></tr>";
if($upload_avail)
{
	echo "<tr class=\"inforow\"><td align=\"left\" colspan=\"2\"><b>";
	echo $l_fileupload;
	echo "</b></td></tr>";
	echo "<FORM name=\"gfxform\" enctype=\"multipart/form-data\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"subdir\" value=\"$subdir\">";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"9999999\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"UPLOAD\">";
	echo "<tr class=\"inputrow\"><td align=\"left\" width=\"80%\">";
	echo "&nbsp;<input class=\"faqefile\" name=\"USERFILE\" type=\"File\" size=\"40\">";
	echo "</td><td align=\"center\" width=\"20%\">";
	echo "<input class=\"faqebutton\" type=\"submit\" value=\"$l_upload\">";
	echo "</td></tr>";
	echo "</FORM>";
}
if($userdata["rights"]>2)
{
	echo "<tr class=\"inforow\"><td align=\"left\" colspan=\"2\"><b>";
	echo $l_createsubdir;
	echo "</b></td></tr>";
	echo "<FORM name=\"dirform\" action=\"$act_script_url\" method=\"post\">";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"subdir\" value=\"$subdir\">";
	echo "<input type=\"hidden\" name=\"ACTION\" value=\"MKDIR\">";
	echo "<tr class=\"inputrow\"><td align=\"left\" width=\"80%\">";
	echo "&nbsp;<input class=\"faqefile\" name=\"NEWDIR\" type=\"text\" size=\"40\">";
	echo "</td><td align=\"center\" width=\"20%\">";
	echo "<input class=\"faqebutton\" type=\"submit\" value=\"$l_create\">";
	echo "</td></tr>";
	echo "</FORM>";
}
/* ********************************************************** */
$cdir = dir($gfx_dir);
echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">";
echo "<table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_basedir:</b> $path_gfx</td></tr>";
echo "<tr class=\"inforow\"><td align=\"center\"><b>$l_actsubdir:</b> ";
if($subdir)
{
	$tmpsubdir=substr($subdir,1);
	$subdirparts=explode("/",$tmpsubdir);
	$newsubdir="";
	for($i=0;$i<count($subdirparts);$i++)
	{
		$newsubdir.="/".$subdirparts[$i];
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\">";
		echo $subdirparts[$i];
		echo "</a>/";
	}
}
else
	echo $l_none2;
echo "</td></tr>";
echo "</table>";
echo "<table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"4\">";

$old_cwd = getcwd();
$piccount=0;
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
			echo "<td class=\"listlink\" align=\"right\" colspan=\"2\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a></td>";
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
		echo "<td align=\"right\" colspan=\"2\"><a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a></td>";
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
		echo "<td align=\"right\" colspan=\"2\">";
		if($userdata["rights"]<3)
		{
			$testdir=substr($newsubdir,1);
			if(is_in_array(trim($testdir),$alloweddirs))
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a>";
			else
				echo "&nbsp;";
		}
		else
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&subdir=$newsubdir")."\"><img src=\"gfx/opendir.gif\" border=\"0\" title=\"$l_changedir\" alt=\"$l_changedir\"></a>";
		echo "</td>";
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
		echo "<td align=\"center\"><img src=\"$pic_url/$entry\" border=\"0\"></td>";
		echo "<td>$entry</td>";
		echo "<td align=\"right\">".filesize($entry)." bytes</td>";
		$linkurl="$act_script_url?ACTION=DEL&DELFILE=$entry&subdir=$subdir&$langvar=$act_lang";
		echo "<td align=\"right\"><a class=\"listlink\" href=\"".do_url_session($linkurl)."\"><img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a>";
		echo "</tr>";
	}
}
echo "</table></td></tr>";
chdir($old_cwd);
if($piccount==0)
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">$l_nogfxindir</td></tr>";
echo "</table></td></tr></table>";
include('./trailer.php');
?>