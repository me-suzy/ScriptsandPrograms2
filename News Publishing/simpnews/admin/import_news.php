<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_importnews;
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
	$maxconfirmtime=$myrow["maxconfirmtime"];
else
	$maxconfirmtime=0;
$dateformat="Y-m-d H:i:s";
if(!isset($start))
	$start=0;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(($admin_rights < $importlevel) || (!$upload_avail))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="impfile")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		if($new_global_handling)
			$tmp_file=$_FILES['listfile']['tmp_name'];
		else
			$tmp_file=$HTTP_POST_FILES['listfile']['tmp_name'];
		if(is_uploaded_file($tmp_file))
		{
			if($new_global_handling)
			{
				$filename=$_FILES['listfile']['name'];
				$filesize=$_FILES['listfile']['size'];
			}
			else
			{
				$filename=$HTTP_POST_FILES['listfile']['name'];
				$filesize=$HTTP_POST_FILES['listfile']['size'];
			}
			$filedata="";
			if($filesize>0)
			{
				if(isset($path_tempdir) && $path_tempdir)
				{
					if(!move_uploaded_file ($tmp_file, $path_tempdir."/".$filename))
					{
						echo "<tr class=\"errorrow\"><td align=\"center\">";
						printf($l_cantmovefile,$path_attach."/".$physfile);
						echo "</td></tr>";
						die();
					}
					$orgfile=$path_tempdir."/".$filename;
				}
				else
					$orgfile=$tmp_file;
			}
		}
		if(!isset($filename) || ($filesize<1))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nolistfile</td></tr>";
			$errors=1;
		}
		if(isset($stripstags))
			$strip_tags=true;
		else
			$strip_tags=false;
		if($errors==0)
		{
			$imported=import_newsfile($orgfile, $newslang, $newscat, $strip_tags);
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo str_replace("{imported}",$imported,$l_listimported);
			echo "</td></tr></table></td></tr></table>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
}
else
{
if(($admin_rights < 2) || (!$upload_avail))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form <?php if($upload_avail) echo "enctype=\"multipart/form-data\""?> method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="impfile">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_newslistfile?>:</td>
<td><input class="sninput" type="file" name="listfile"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_language?>:</td>
<td><?php echo language_select("","newslang","../language")?></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_category?>:</td>
<td><select name="newscat">
<option value="0"><?php echo $l_general?></option>
<?php
if(($admrestrict==1) && ($userdata["rights"]==2) && !bittst($userdata["addoptions"],BIT_5))
	$tmpsql="select cat.* from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"];
else
	$tmpsql="select * from ".$tableprefix."_categories";
if(!$tmpresult = mysql_query($tmpsql, $db))
    die("Could not connect to the database.");
while($tmprow = mysql_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["catnr"]."\">".$tmprow["catname"]."</option>";
}
?>
</select></td></tr>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="striptags" value="1">
<?php echo $l_striphtmltags?></td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="snbutton" type="submit" value="<?php echo $l_import?>"></td></tr>
<?php
echo "</td></tr></table></td></tr></table>";
}
include_once('./trailer.php');
function import_newsfile($inputfile, $newslang, $newscat, $strip_tags)
{
	global $tableprefix, $db, $userdata, $comments_allowed;

	$emailfile=fopen($inputfile,"r");
	$maxsize=filesize($inputfile);
	$imported=0;
	$entrytxt="";
	if(strlen($userdata["realname"]>0))
		$poster=$userdata["realname"];
	else
		$poster=$userdata["username"];
	$actdate = date("Y-m-d H:i:s");
	while($fileLine=fgets($emailfile,$maxsize))
	{
		$fileLine=str_replace("\n","",$fileLine);
		$fileLine=str_replace("\r","",$fileLine);
		$fileLine=trim($fileLine);
		if($strip_tags)
			$fileLine=strip_tags($fileLine);
		if(strlen($fileLine)<1)
			continue;
		if(eregi("^\{newentry\}",$fileLine))
		{
			$entrytxt=addslashes($entrytxt);
			$sql="insert into ".$tableprefix."_data (lang, date, text, poster, category, dontemail, allowcomments, posterid) values ('$newslang','$actdate','$entrytxt', '$poster', $newscat, 1, $comments_allowed, ".$userdata["usernr"].")";
			if(!$result = mysql_query($sql, $db))
				die("Could not connect to the database.".mysql_error());
			$imported++;
			$entrytxt="";
		}
		else
			$entrytxt.="<br>".$fileLine;
	}
	return $imported;
}
?>