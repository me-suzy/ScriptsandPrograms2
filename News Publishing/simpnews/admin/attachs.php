<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page="files";
$page_title=$l_adminfiles;
require_once('./heading.php');
if(!isset($sorting))
	$sorting=11;
if(!isset($dostorefilter) && ($admstorefilter==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
		if(sn_array_key_exists($admcookievals,"files_sorting"))
			$sorting=$admcookievals["files_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(($admin_rights < 3) && !bittst($userdata["addoptions"],BIT_7))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="tofs")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$processedfiles=0;
		$sql="select * from ".$tableprefix."_files";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		while($myrow=mysql_fetch_array($result))
		{
			if($myrow["bindata"])
			{
				$processedfiles++;
				$filename=stripslashes($myrow["filename"]);
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
				$newfile=fopen($path_attach."/".$physfile,"wb");
				if(!$newfile)
					die("<tr class=\"errorrow\"><td>Error creating file $physfile in $path_attach (".$myrow["entrynr"].")");
				if(!fwrite($newfile,$myrow["bindata"]))
					die("<tr class=\"errorrow\"><td>Error writing to file $physfile in $path_attach (".$myrow["entrynr"].")");
				fclose($newfile);
				if($attach_do_chmod)
					chmod($patch_attach."/".$physfile, $attach_fmode);
				$tmpsql="UPDATE ".$tableprefix."_files set fs_filename='$physfile', bindata='' where entrynr=".$myrow["entrynr"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
				@mysql_free_result($tmpresult);
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_filesfromdb2fsdone<br>";
		printf($l_files_processed,$processedfiles);
		echo "<br>$l_switch_attach_fs";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
	}
	if($mode=="todb")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$sql="select * from ".$tableprefix."_files";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
		while($myrow=mysql_fetch_array($result))
		{
			if(!$myrow["bindata"])
			{
				if(file_exists($path_attach."/".$myrow["fs_filename"]))
				{
					$fsfile=@fopen($path_attach."/".$myrow["fs_filename"],"rb");
					if(!$fsfile)
						die("<tr class=\"errorrow\"><td>Error opening file ".$path_attach."/".$myrow["fs_filename"]." (".$myrow["entrynr"].")");
					$filedata=fread($fsfile,filesize($path_attach."/".$myrow["fs_filename"])+1);
					$filedata=addslashes($filedata);
					fclose($fsfile);
					$tmpsql="UPDATE ".$tableprefix."_files set fs_filename='', bindata='$filedata' where entrynr=".$myrow["entrynr"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
					@mysql_free_result($tmpresult);
					@unlink($path_attach."/".$myrow["fs_filename"]);
				}
				else
				{
					$tmpsql="DELETE FROM ".$tableprefix."_files where entrynr=".$myrow["entrynr"];
					if(!$tmpresult = mysql_query($tmpsql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				}
			}
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_filesfromfs2dbdone";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
	}
	if($mode=="dlclear")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql="update ".$tableprefix."_files set downloads=0";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_dlresetedall<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
	}
	if($mode=="dlreset")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql="update ".$tableprefix."_files set downloads=0 where entrynr=$input_entrynr";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_dlreseted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
	}
	if($mode=="display")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_files where (entrynr=$input_entrynr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displayfiles?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_filename?>:</td><td><?php echo $myrow["filename"]?></td></tr>
<?php
		if($myrow["fs_filename"])
		{
			echo "<tr class=\"displayrow\"><td align=\"right\">".$l_filename_on_disk.":</td>";
			echo "<td>".$myrow["fs_filename"]."</td></tr>";
		}
?>
<tr class="displayrow"><td align="right"><?php echo $l_file_mimetype?>:</td><td><?php echo $myrow["mimetype"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_filesize?>:</td><td><?php echo $myrow["filesize"]?> Byte</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_associated_news?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_news_attachs na, ".$tableprefix."_data dat ";
		$tmpsql.="where dat.linknewsnr=0 and na.attachnr=".$myrow["entrynr"]." and dat.newsnr=na.newsnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = mysql_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				if($tmprow["category"]>0)
				{
					$tmpsql2="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
						$catname=$tmprow2["catname"];
					else
						$catname=$l_undefined;
				}
				else
					$catname=$l_general;
				echo "<li> $catname :".undo_html_ampersand(stripslashes($tmprow["heading"]));
				if($tmprow["linknewsnr"]==0)
					$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$tmprow["newsnr"]);
				else
					$showurl=do_url_session("nshow.php?$langvar=$act_lang&newsnr=".$tmprow["linknewsnr"]);
				echo "&nbsp;&nbsp;&nbsp;<a class=\"listlink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200)\">$l_display</a>";
			}while($tmprow = mysql_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_associated_events?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_events_attachs ea, ".$tableprefix."_events ev ";
		$tmpsql.="where ev.linkeventnr=0 and ea.attachnr=".$myrow["entrynr"]." and ev.eventnr=ea.eventnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = mysql_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				if($tmprow["category"]>0)
				{
					$tmpsql2="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
						$catname=$tmprow2["catname"];
					else
						$catname=$l_undefined;
				}
				else
					$catname=$l_general;
				echo "<li> $catname :".undo_html_ampersand(stripslashes($tmprow["heading"]));
				if($tmprow["linkeventnr"]==0)
					$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$tmprow["eventnr"]);
				else
					$showurl=do_url_session("evshow.php?$langvar=$act_lang&eventnr=".$tmprow["linkeventnr"]);
				echo "&nbsp;<a class=\"listlink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200)\">$l_display</a>";
			}while($tmprow = mysql_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_associated_announce?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_announce_attachs ana, ".$tableprefix."_announce an ";
		$tmpsql.="where ana.attachnr=".$myrow["entrynr"]." and an.entrynr=ana.announcenr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = mysql_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				if($tmprow["category"]>0)
				{
					$tmpsql2="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
						$catname=$tmprow2["catname"];
					else
						$catname=$l_undefined;
				}
				else
					$catname=$l_general;
				echo "<li> $catname :".undo_html_ampersand(stripslashes($tmprow["heading"]));
				$showurl=do_url_session("anshow.php?$langvar=$act_lang&annr=".$tmprow["entrynr"]);
				echo "&nbsp;&nbsp;&nbsp;<a class=\"listlink\" href=\"javascript:openWindow3('$showurl','nShow',20,20,400,200)\">$l_display</a>";
			}while($tmprow = mysql_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_associated_newsproposal?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_tmpnews_attachs tna, ".$tableprefix."_tmpdata td ";
		$tmpsql.="where tna.attachnr=".$myrow["entrynr"]." and td.entrynr=tna.newsnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = mysql_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				if($tmprow["category"]>0)
				{
					$tmpsql2="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
						$catname=$tmprow2["catname"];
					else
						$catname=$l_undefined;
				}
				else
					$catname=$l_general;
				echo "<li> $catname :".undo_html_ampersand(stripslashes($tmprow["heading"]));
				$showurl=do_url_session("proposes.php?input_entrynr=".$tmprow["entrynr"]."&langvar=$act_lang&mode=display");
				echo "&nbsp;<a class=\"listlink\" href=\"$showurl\">$l_display</a>";
			}while($tmprow = mysql_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_associated_eventproposal?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_tmpevents_attachs tea, ".$tableprefix."_tmpevents te ";
		$tmpsql.="where tea.attachnr=".$myrow["entrynr"]." and te.entrynr=tea.eventnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = mysql_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				if($tmprow["category"]>0)
				{
					$tmpsql2="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
						$catname=$tmprow2["catname"];
					else
						$catname=$l_undefined;
				}
				else
					$catname=$l_general;
				echo "<li> $catname :".undo_html_ampersand(stripslashes($tmprow["heading"]));
				$showurl=do_url_session("evproposes.php?input_entrynr=".$tmprow["entrynr"]."&langvar=$act_lang&mode=display");
				echo "&nbsp;<a class=\"listlink\" href=\"$showurl\">$l_display</a>";
			}while($tmprow = mysql_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_addfile?></b></td></tr>
<form onsubmit="return checkform()" enctype="multipart/form-data" name="inputform" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_file?>:</td><td align="left">
<input class="sninput" name="uploadfile" type="File" size="40">
</td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_description?>:</td><td align="left">
<input class="sninput" type="text" name="description" size="40" maxlength="255"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="snbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_filelist?></a></div>
<?php
	}
	if($mode=="add")
	{
		$errors=0;
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if($new_global_handling)
			$tmp_file=$_FILES['uploadfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['uploadfile']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
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
			$filedata = addslashes(get_file($tmp_file));
			$filetype=getUploadFileType($filename);
		}
		else
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nofile";
			if($has_file_errors)
			{
				$uploaderror=$_FILES['uploadfile']['error'];
				echo "<br>(".$l_fileerrors[$uploaderror].")";
				if($uploaderror==1)
					echo "<br>upload_max_filesize=".ini_get('upload_max_filesize');
				if($uploaderror==2)
					echo "<br>maxfilesize=".$maxfilesize;
			}
			echo "</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
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
						$errmsg.="<tr class=\"errorrow\"><td colspan=\"5\" align=\"center\">";
						$errmsg.= sprintf($l_cantmovefile,$path_attach."/".$physfile);
						$errmsg.="</td></tr>";
					}
					else if($attach_do_chmod)
						chmod($patch_attach."/".$physfile, $attach_fmode);
				}
			}
		}
		if($errors==0)
		{
			$sql ="INSERT INTO ".$tableprefix."_files (filename, filesize, mimetype, bindata, description";
			if($attach_in_fs)
				$sql.=", fs_filename";
			$sql.=") VALUES (";
			$sql.="'$filename', '$filesize', '$filetype', '$filedata', '$description'";
			if($attach_in_fs)
				$sql.=", '$physfile'";
			$sql.=")";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add file to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_file_added";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_addfile</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="delete")
	{
		if(($admdelconfirm==1) && !isset($confirmed))
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
<?php
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			$tmpsql="select * from ".$tableprefix."_files where entrynr=$input_entrynr";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database.");
			if(!$tmprow = mysql_fetch_array($tmpresult))
				$filename=$l_unknown;
			else
				$filename=$tmprow["filename"];
			echo "<tr class=\"inforow\"><td align=\"center\">";
			echo "$l_confirmdel: $l_file #$input_entrynr [$filename]";
			echo "</td></tr>";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
			echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
			echo "</td></tr>";
			echo "</form></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$numattached=0;
		$tmpsql="select * from ".$tableprefix."_news_attachs where attachnr=$input_entrynr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$numattached+=mysql_num_rows($tmpresult);
		$tmpsql="select * from ".$tableprefix."_events_attachs where attachnr=$input_entrynr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$numattached+=mysql_num_rows($tmpresult);
		$tmpsql="select * from ".$tableprefix."_tmpnews_attachs where attachnr=$input_entrynr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$numattached+=mysql_num_rows($tmpresult);
		$tmpsql="select * from ".$tableprefix."_tmpevents_attachs where attachnr=$input_entrynr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$numattached+=mysql_num_rows($tmpresult);
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if($attach_in_fs)
		{
			$tmpsql="select * from ".$tableprefix."_files where entrynr=$input_entrynr";
			if(!$tmpresult = mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>$l_cantdelete. ".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			if(!unlink($path_attach."/".$tmprow["fs_filename"]))
				die("<tr class=\"errorrow\"><td>$l_cantdelete ".$path_attach."/".$tmprow["fs_filename"]);
		}
		$deleteSQL = "delete from ".$tableprefix."_files where (entrynr=$input_entrynr)";
		$success = mysql_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_news_attachs where (attachnr=$input_entrynr)";
		$success = mysql_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_events_attachs where (attachnr=$input_entrynr)";
		$success = mysql_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_tmpnews_attachs where (attachnr=$input_entrynr)";
		$success = mysql_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_tmpevents_attachs where (attachnr=$input_entrynr)";
		$success = mysql_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
	}
	if($mode=="edit")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_files where (entrynr=$input_entrynr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editfiles?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_filename?>:</td><td><?php echo $myrow["filename"]?></td></tr>
<?php
		if($myrow["fs_filename"])
		{
			echo "<tr class=\"displayrow\"><td align=\"right\">".$l_filename_on_disk.":</td>";
			echo "<td>".$myrow["fs_filename"]."</td></tr>";
		}
?>
<tr class="displayrow"><td align="right"><?php echo $l_file_mimetype?>:</td><td><?php echo $myrow["mimetype"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_filesize?>:</td><td><?php echo $myrow["filesize"]?> Byte</td></tr>
<form name="inputform" enctype="multipart/form-data" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_entrynr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_description?>:</td><td>
<input class="sninput" type="text" name="description" size="40" maxlength="255" value="<?php echo do_htmlentities($myrow["description"])?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newfile?>:</td><td>
<input class="sninput" name="uploadfile" type="file" size="40">
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="snbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_filelist?></a></div>
<?php
	}
	if($mode=="update")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		$fileavail=false;
		if($new_global_handling)
			$tmp_file=$_FILES['uploadfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['uploadfile']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
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
			$filedata = addslashes(get_file($tmp_file));
			$filetype=getUploadFileType($filename);
			$fileavail=true;
		}
		else if(!$description)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nofile</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if($fileavail)
			{
				if($attach_in_fs)
				{
					$tmpsql="select * from ".$tableprefix."_files where entrynr=$input_entrynr";
					if(!$tmpresult = mysql_query($tmpsql,$db))
						die("<tr class=\"errorrow\"><td>$l_cantdelete.");
					if(!$tmprow=mysql_fetch_array($tmpresult))
						die("<tr class=\"errorrow\"><td>$l_cantdelete.");
					$physfile=$tmprow["fs_filename"];
					if(!unlink($path_attach."/".$physfile))
						die("<tr class=\"errorrow\"><td>$l_cantdelete ".$path_attach."/".$physfile);
					$filedata="";
					if(!move_uploaded_file($tmp_file,$path_attach."/".$physfile))
					{
						$errors=1;
						$errmsg.="<tr class=\"errorrow\"><td colspan=\"5\" align=\"center\">";
						$errmsg.= sprintf($l_cantmovefile,$path_attach."/".$physfile);
						$errmsg.="</td></tr>";
					}
					else if($attach_do_chmod)
						chmod($patch_attach."/".$physfile, $attach_fmode);
				}
				$sql = "UPDATE ".$tableprefix."_files SET filename='$filename', filesize='$filesize', mimetype='$filetype', bindata='$filedata'";
				if($attach_in_fs)
					$sql.=", fs_filename='$physfile'";
				$sql.="where entrynr=$input_entrynr";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			}
			$sql = "UPDATE ".$tableprefix."_files SET description='$description' where entrynr=$input_entrynr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_fileupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="purge")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">\n";
		echo "<form name=\"inputform\" method=\"post\" action=\"$act_script_url\">\n";
		if(is_konqueror())
			echo "<tr><td></td></tr>\n";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">\n";
		echo "<input type=\"hidden\" name=\"mode\" value=\"dopurge\">\n";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">\n";
		echo "<tr class=\"inforow\"><td align=\"center\"><b>";
		echo $l_purgefiles;
		echo "</b></td></tr>\n";
		echo "<tr class=\"optionrow\"><td align=\"center\">\n";
		echo "<input type=\"checkbox\" name=\"doprev\" value=\"1\"> $l_previewlistfiles</td></tr>\n";
		echo "<tr class=\"actionrow\">\n";
		echo "<td align=\"center\"><input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\"$l_submit\"></td></tr>\n";
		echo "</form></table></td></tr></table>\n";
	}
	if($mode=="dopurge")
	{
		if(isset($doprev))
		{
			$attached_files=array();
			$sql="select * from ".$tableprefix."_announce_attachs";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			while($myrow=mysql_fetch_array($result))
			{
				array_push($attached_files,$myrow["attachnr"]);
			}
			$sql="select * from ".$tableprefix."_news_attachs";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			while($myrow=mysql_fetch_array($result))
			{
				array_push($attached_files,$myrow["attachnr"]);
			}
			$sql="select * from ".$tableprefix."_events_attachs";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			while($myrow=mysql_fetch_array($result))
			{
				array_push($attached_files,$myrow["attachnr"]);
			}
			$sql="select * from ".$tableprefix."_tmpnews_attachs";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			while($myrow=mysql_fetch_array($result))
			{
				array_push($attached_files,$myrow["attachnr"]);
			}
			$sql="select * from ".$tableprefix."_tmpevents_attachs";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			while($myrow=mysql_fetch_array($result))
			{
				array_push($attached_files,$myrow["attachnr"]);
			}
			$attached_files=array_unique($attached_files);
			$sql = "select * from ".$tableprefix."_files where entrynr not in (";
			$firstarg=true;
			while (list($key) = each($attached_files))
			{
				if($firstarg)
					$firstarg=false;
				else
					$sql.=", ";
				$sql.=$attached_files[$key];
			}
			$sql.=")";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.");
			if(mysql_num_rows($result)<1)
			{
				echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
				echo "<tr class=\"displayrow\" align=\"center\"><td>";
				echo "$l_nofilestopurge";
				echo "</td></tr></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
			}
			else
			{
				echo "<form name=\"inputform\" method=\"post\" action=\"$act_script_url\">";
				echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
				if(is_konqueror())
					echo "<tr><td></td></tr>\n";
				echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">\n";
				echo "<input type=\"hidden\" name=\"mode\" value=\"dopurge\">\n";
				echo "<input type=\"hidden\" name=\"frompreview\" value=\"1\">\n";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">\n";
				echo "<tr class=\"rowheadings\">";
				echo "<td width=\"2%\">&nbsp;</td><td><b>$l_filename</b></td>";
				echo "</tr>";
				while($myrow=mysql_fetch_array($result))
				{
					echo "<tr class=\"inputrow\">";
					echo "<td align=\"center\"><input type=\"checkbox\" name=\"filenr[]\" value=\"".$myrow["entrynr"]."\"></td>";
					echo "<td>".$myrow["filename"]."</td></tr>";
				}
				echo "<tr class=\"actionrow\"><td colspan=\"5\" align=\"left\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"del\" value=\"$l_delselected\">";
				echo "&nbsp;&nbsp;";
				echo "<input class=\"snbutton\" type=\"button\" value=\"$l_checkall\" onclick=\"checkAll(inputform)\">";
				echo "&nbsp;&nbsp;";
				echo "<input class=\"snbutton\" type=\"button\" value=\"$l_uncheckall\" onclick=\"uncheckAll(inputform)\">";
				echo "</td></tr>";
				echo "</form></table></td></tr></table>";
				echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
			}
		}
		else
		{
			if(!isset($frompreview))
			{
				$attached_files=array();
				$sql="select * from ".$tableprefix."_announce_attachs";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				while($myrow=mysql_fetch_array($result))
				{
					array_push($attached_files,$myrow["attachnr"]);
				}
				$sql="select * from ".$tableprefix."_news_attachs";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				while($myrow=mysql_fetch_array($result))
				{
					array_push($attached_files,$myrow["attachnr"]);
				}
				$sql="select * from ".$tableprefix."_events_attachs";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				while($myrow=mysql_fetch_array($result))
				{
					array_push($attached_files,$myrow["attachnr"]);
				}
				$sql="select * from ".$tableprefix."_tmpnews_attachs";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				while($myrow=mysql_fetch_array($result))
				{
					array_push($attached_files,$myrow["attachnr"]);
				}
				$sql="select * from ".$tableprefix."_tmpevents_attachs";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				while($myrow=mysql_fetch_array($result))
				{
					array_push($attached_files,$myrow["attachnr"]);
				}
				$attached_files=array_unique($attached_files);
				$sql = "delete from ".$tableprefix."_files where entrynr not in (";
				$firstarg=true;
				while (list($key) = each($attached_files))
				{
					if($firstarg)
						$firstarg=false;
					else
						$sql.=", ";
					$sql.=$attached_files[$key];
				}
				$sql.=")";
				if(!$result = mysql_query($sql, $db))
					die("Could not connect to the database.");
				if($attach_in_fs)
				{
					$dir = opendir($path_attach);
					while ($file = readdir($dir))
					{
						if(is_file($path_attach."/".$file))
						{
							$tmpsql="select * from ".$tableprefix."_files where fs_filename='".$file."'";
							if(!$tmpresult = mysql_query($tmpsql, $db))
								die("Could not connect to the database.");
							if(mysql_num_rows($tmpresult)==0)
								if(!unlink($path_attach."/".$file))
									die("<tr class=\"errorrow\"><td>$l_cantdelete ".$path_attach."/".$file);
						}
					}
				}
			}
			else
			{
				while(list($null, $act_filenr) = each($_POST["filenr"]))
				{
					if($attach_in_fs)
					{
						$tmpsql="select * from ".$tableprefix."_files where entrynr=$act_filenr";
						if(!$tmpresult = mysql_query($tmpsql, $db))
							die("Could not connect to the database.");
						if(!$tmprow=mysql_fetch_array($tmpresult))
							die("Could not connect to the database.");
						if(!unlink($path_attach."/".$tmprow["fs_filename"]))
							die("<tr class=\"errorrow\"><td>$l_cantdelete ".$path_attach."/".$tmprow["fs_filename"]);
					}
					$sql="delete from ".$tableprefix."_files where entrynr=$act_filenr";
					if(!$result = mysql_query($sql, $db))
						die("Could not connect to the database.");
				}
			}
			echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_filespurged";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filelist</a></div>";
		}
	}
}
else
{
	echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	if($admin_rights > 1)
	{
		echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"center\">";
		if($upload_avail)
			echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_addfile</a>&nbsp;&nbsp; ";
		echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=purge&$langvar=$act_lang")."\">$l_purgefiles</a>&nbsp;&nbsp; ";
		echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=dlclear&$langvar=$act_lang")."\">$l_dlresetall</a>";
		if(!$attach_in_fs)
			echo "&nbsp; <a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=tofs&$langvar=$act_lang")."\">$l_transfer2fs</a>";
		else
			echo "&nbsp; <a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=todb&$langvar=$act_lang")."\">$l_transfer2db</a>";
		echo "</table></td></tr></table>";
		echo "<table align=\"center\" width=\"80%\" CELLPADDING=\"1\" CELLSPACING=\"0\" border=\"0\" valign=\"top\">";
		echo "<tr><TD BGCOLOR=\"#000000\">";
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
	}
	$sql = "select * from ".$tableprefix."_files ";
	switch($sorting)
	{
		case 12:
			$sql.="order by filename desc";
			break;
		case 21:
			$sql.="order by mimetype asc";
			break;
		case 22:
			$sql.="order by mimetype desc";
			break;
		case 31:
			$sql.="order by downloads asc";
			break;
		case 32:
			$sql.="order by downloads desc";
			break;
		default:
			$sql.="order by filename asc";
			break;
	}
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.");
	$baseurl=$act_script_url."?".$langvar."=".$act_lang;
	$maxsortcol=3;
	if($admstorefilter==1)
		$baseurl.="&dostorefilter=1";
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\">#</td>";
	echo "<td align=\"center\" width=\"30%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_filename</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"15%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_file_mimetype</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"20%\"><b>$l_description</b></td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"10%\"><b>$l_has_association</b></td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"5%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"sorturl\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_downloads</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td width=\"5%\">&nbsp;</td><td>&nbsp;</td></tr>";
	if (!$myrow = mysql_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"8\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		do {
			$act_id=$myrow["entrynr"];
			echo "<tr class=\"displayrow\">";
			echo "<td align=\"center\">$act_id</td>";
			echo "<td align=\"center\">".$myrow["filename"]."</td>";
			echo "<td align=\"center\">";
			echo $myrow["mimetype"];
			echo "</td>";
			echo "<td align=\"center\">";
			echo $myrow["description"];
			echo "</td>";
			$numattached=0;
			$tmpsql="select * from ".$tableprefix."_news_attachs where attachnr=$act_id";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database.".mysql_error());
			$numattached+=mysql_num_rows($tmpresult);
			$tmpsql="select * from ".$tableprefix."_events_attachs where attachnr=$act_id";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database.");
			$numattached+=mysql_num_rows($tmpresult);
			$tmpsql="select * from ".$tableprefix."_tmpnews_attachs where attachnr=$act_id";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database.");
			$numattached+=mysql_num_rows($tmpresult);
			$tmpsql="select * from ".$tableprefix."_tmpevents_attachs where attachnr=$act_id";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database.");
			$numattached+=mysql_num_rows($tmpresult);
			$tmpsql="select * from ".$tableprefix."_announce_attachs where attachnr=$act_id";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Could not connect to the database.".mysql_error());
			$numattached+=mysql_num_rows($tmpresult);
			echo "<td align=\"center\">";
			if($numattached>0)
				echo "<img src=\"gfx/checked.gif\" align=\"absmiddle\" border=\"0\">";
			else
				echo "&nbsp;";
			echo "</td>";
			echo "<td align=\"right\">";
			echo $myrow["downloads"];
			echo "</td><td align=\"center\">";
			if(!$myrow["bindata"])
				echo "<img src=\"gfx/hd.gif\" alt=\"$l_fileinfs\" title=\"$l_fileinfs\" border=\"0\">";
			else
				echo "&nbsp;";
			echo "</td><td>";
			if(($admin_rights > 2) || ($numattached==0))
			{
				$dellink=do_url_session("$act_script_url?$langvar=$act_lang&mode=delete&input_entrynr=$act_id");
				$filename=undo_htmlentities($myrow["filename"]);
				if($admdelconfirm==2)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_file #$act_id [$filename]','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
			}
			if($upload_avail)
			{
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_entrynr=$act_id")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
			}
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_entrynr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a> ";
			echo "<a class=\"listlink\" target=\"download\" href=\"".do_url_session("../sndownload.php?entrynr=$act_id&$langvar=$act_lang&nodlcount=1")."\">";
			echo "<img src=\"gfx/download.gif\" border=\"0\" title=\"$l_download\" alt=\"$l_download\"></a>";
			if($myrow["downloads"]>0)
			{
				echo " <a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=dlreset&input_entrynr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/clear.gif\" border=\"0\" title=\"$l_resetdownloads\" alt=\"$l_resetdownloads\"></a>";
			}
			echo "</td></tr>";
	   } while($myrow = mysql_fetch_array($result));
	   echo "</table></tr></td></table>";
	}
	if($admin_rights > 1)
	{
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		echo "<tr class=\"actionrow\"><td colspan=\"6\" align=\"center\">";
		if($upload_avail)
			echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_addfile</a>&nbsp;&nbsp; ";
		echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=purge&$langvar=$act_lang")."\">$l_purgefiles</a>&nbsp;&nbsp; ";
		echo "<a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=dlclear&$langvar=$act_lang")."\">$l_dlresetall</a>";
		if(!$attach_in_fs)
			echo "&nbsp; <a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=tofs&$langvar=$act_lang")."\">$l_transfer2fs</a>";
		else
			echo "&nbsp; <a class=\"actionlink\" href=\"".do_url_session("$act_script_url?mode=todb&$langvar=$act_lang")."\">$l_transfer2db</a>";
		echo "</tr></td></table></td></tr></table>";
	}
}
include('./trailer.php');
?>