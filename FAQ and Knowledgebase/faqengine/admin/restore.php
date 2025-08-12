<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if(!$insafemode)
	@set_time_limit($longrunner);
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_dbrestore;
$page="restore";
require_once('./heading.php');
require_once('./includes/constants.inc');
if($admin_rights < 3)
	die($l_functionnotallowed);
if(isset($dorestore))
{
	$errors=0;
	$errmsg="";
	$restoresource=$l_uploadedfile;
	if(strlen($backuplocal)>0)
	{
		$tmp_file=stripslashes($backuplocal);
		$restoresource=$l_file_from_server." (".$backuplocal.")";
	}
	else
	{
		if($new_global_handling)
			$tmp_file=$_FILES['backupfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['backupfile']['tmp_name'];
		if(!is_uploaded_file($tmp_file))
		{
			$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
			$errmsg.=$l_nofile;
			if($has_file_errors)
			{
				$uploaderror=$_FILES['backupfile']['error'];
				$errmsg.="<br>(".$l_fileerrors[$uploaderror].")";
				if($uploaderror==1)
					$errmsg.="<br>upload_max_filesize=".ini_get('upload_max_filesize');
				if($uploaderror==2)
					$errmsg.="<br>maxfilesize=".$maxfilesize;
			}
			$errmsg.="</td></tr>";
			$errors=1;
		}
	}
	if(!isset($tmp_file))
	{
		$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
		$errmsg.= $l_nofile;
		$errmsg.="</td></tr>";
		$errors=1;
	}
	if(filesize($tmp_file)<1)
	{
		$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
		$errmsg.= $l_nofile." (".$l_filesize.": ".filesize($tmp_file).")";
		$errmsg.="</td></tr>";
		$errors=1;
	}
	if($errors==0)
	{
		if(!$file=@fopen($tmp_file,"r"))
		{
			$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
			$errmsg.="$l_cantopenfile ($tmp_file)</td></tr>";
			$errors=1;
		}
		else
		{
			if(!$fmarker=fgets($file,255))
			{
				$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
				$errmsg.="$l_cantreadfile ($tmpfile)</td></tr>";
				$errors=1;
			}
			else
			{
				$fmarker=str_replace("\r","",$fmarker);
				$fmarker=str_replace("\n","",$fmarker);
				if(strcmp($fmarker,"# FAQEDATABACKUP")!=0)
				{
					$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
					$errmsg.="$l_nodatabackup</td></tr>";
					$errors=1;
				}
				else if(!$fmarker2=fgets($file,255))
				{
					$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
					$errmsg.="$l_cantreadfile</td></tr>";
					$errors=1;
				}
				else
				{
					$fmarker2=str_replace("\r","",$fmarker2);
					$fmarker2=str_replace("\n","",$fmarker2);
					sscanf($fmarker2,"# FAQEBACKUPVERSION %s",$file_backup_version);
					if($file_backup_version!=$restore_version)
					{
						$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
						$errmsg.=sprintf($l_incompatbackup,$file_backup_version,$restore_version);
						$errmsg.="</td></tr>";
						$errors=1;
					}
				}
			}
			@fclose($file);
		}
	}
	if(isset($checkonly) && ($errors==0))
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo $l_restoresource.": ".$restoresource."<br>";
			echo $l_noerrorsfound. " (".$file_backup_version.")";
			echo "</td></tr></table></td></tr></table>";
			include('./trailer.php');
			exit;
	}
	if($errors==0)
	{
		$localcrlf=$crlftypes[$selectedcrlftype];
		if(!$file=fopen($tmp_file,"r"))
		{
			$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
			$errmsg.="$l_cantopenfile ($tmp_file)</td></tr>";
			$errors=1;
		}
		if(!$insafemode)
			@set_time_limit($longrunner);
		$restoredlines=0;
		echo "<div id =\"progressbox\" class=\"progress\">";
		if($dodebug)
			echo "Filesize: ".filesize($tmp_file)."<br>";
		else
			echo "$l_restoringdata: ";
		while($tmpdata=fgets($file))
		{
			if(strncmp("# ",$tmpdata,2)!=0)
			{
				if($dodebug)
				{
					echo "Working on: (Linesize=".strlen($tmpdata).") ".substr($tmpdata,0,20)."<br>";
					flush();
				}
				$query=explode(";#%%".$localcrlf,$tmpdata);
				for ($i=0;$i < count($query)-1;$i++)
				{
					if(!faqe_db_query($query[$i],$db))
					{
						echo "<br>";
						echo $l_errors_occured."<br>";
						echo "<a href=\"javascript:hideprogressbox()\"";
						echo " class=\"actionlink\">$l_hideprogressbox</a>";
						echo "</div>\n";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
						echo "<tr class=\"errorrow\"><td align=\"center\">";
						echo "$l_restorefailed - ";
						echo faqe_db_error()."</td></tr>";
						if($do_debug)
							echo "<tr class=\"errorrow\"><td align=\"left\">Debugoutput:<br>".str_replace($localcrlf,"<br>",substr($query[$i],0,1024))."</td></tr>";
						die();
					}
					$restoredlines++;
					if(!$dodebug)
					{
						if(($restoredlines%100)==0)
							echo "<br>";
						echo "&bull;";
						flush();
					}
				}
			}
		}
		fclose($file);
		echo "<br>";
		echo "<a href=\"javascript:hideprogressbox()\"";
		echo " class=\"actionlink\">$l_hideprogressbox</a>";
		echo "</div>\n";
		flush();
		if($errors==0)
		{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo $l_restoresource.": ".$restoresource."<br>";
			echo $l_dbrestoredone;
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($errors==1)
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo $l_restoresource.": ".$restoresource."</td></tr>";
		echo $errmsg;
		echo "<tr class=\"actionrow\" align=\"center\"><td>";
		echo "<a href=\"javascript:history.back()\">$l_back</a>";
		echo "</td></tr></table></td></tr></table>";
	}
}
else
{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form <?php if($upload_avail) echo "enctype=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="dorestore" value="1">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_dbrestoreprelude?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_crlftype?>:</td>
<td>
<?php
for($i=0;$i<count($crlftypes);$i++)
{
	echo "<input type=\"radio\" name=\"selectedcrlftype\" value=\"$i\"";
	if($crlf==$crlftypes[$i])
		echo " checked";
	echo ">".$crlftype_text[$i]."<br>";
}
?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="checkonly" value="1"> <?php echo $l_onlyfilecheck?></td></tr>
<?php
if($upload_avail)
{
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\">";
?>
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_upload_file?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_backupfile?>:</td>
<td><input type="file" class="faqefile" name="backupfile"></td></tr>
<?php
}
?>
<tr class="inforow"><td align="center" colspan="2"><?php echo $l_file_from_server?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_backupfile?>:<br><span class="remark">(<?php echo $l_with_full_path?>)</span></td>
<td><input type="text" class="faqeinput" size="40" maxlength="1024" name="backuplocal"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="submit" name="submit" class="faqebutton" value="<?php echo $l_restore?>">
</td></tr></form>
</table></td></tr></table>
<?php
}
include('./trailer.php');
?>