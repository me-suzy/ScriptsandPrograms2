<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page="mimetypes";
$page_title=$l_filetypes;
include_once('./includes/gfx_selector.inc');
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
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
		if(faqe_array_key_exists($admcookievals,"mimetypes_sorting"))
			$sorting=$admcookievals["mimetypes_sorting"];
	}
}
if(!isset($sorting))
	$sorting=11;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="display")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_mimetypes where entrynr=$input_entrynr";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displayfiletypes?></b></td></tr>
<tr class="displayrow"><td width="30%" align="right"><?php echo $l_file_mimetype?>:</td><td width="70%"><?php echo $myrow["mimetype"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_icongfx?>:</td><td>
<?php
if($myrow["icon"])
	echo "<img src=\"$url_faqengine/gfx/".$myrow["icon"]."\" border=\"0\" align=\"absmiddle\">";
else
	echo "$l_none";
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_descriptions?>:</td><td valign="top">
<?php
		$tmpsql="select * from ".$tableprefix."_filetypedescription where mimetype=$input_entrynr";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = faqe_db_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				echo "<li> ".$tmprow["language"].": ".$tmprow["description"];
			}while($tmprow = faqe_db_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_fileextensions?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_fileextensions where mimetype=$input_entrynr";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if (!$tmprow = faqe_db_fetch_array($tmpresult))
			echo "$l_none";
		else
		{
			echo "<ul>";
			do{
				echo "<li> ".$tmprow["extension"];
			}while($tmprow = faqe_db_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filetypelist</a></div>";
	}
	// Page called with some special mode
	if($mode=="new")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_addfiletype?></b></td></tr>
<form onsubmit="return checkform()" name="inputform" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_file_mimetype?>:</td><td>
<input class="faqeinput" name="mimetype" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_icongfx?>:</td>
<?php echo gfx_selector("icon","")?>
</tr>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_entrynr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_fileextensions?>:</td><td>
<?php
		echo "<select name=\"new_exts[]\" id=\"new_exts\" size=\"5\" multiple></select><hr>";
		echo "<input type=\"text\" class=\"faqeinput\" size=\"10\" maxlength=\"20\" id=\"new_extension\" name=\"new_extension\">&nbsp;";
		echo "<input type=\"button\" class=\"faqebutton\" onclick=\"addExtension();\" value=\"$l_add\">";
?>
</td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_descriptions?>:</td><td>
<?php
		$availlangs=language_list("../language/");
		for($i=0;$i<count($availlangs);$i++)
		{
			if($i>0)
				echo "<br>";
			echo $availlangs[$i].": ";
			echo "<input type=\"text\" class=\"faqeinput\" size=\"40\" maxlength=\"80\" name=\"desc[".$availlangs[$i]."]\">";
		}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_filetypelist?></a></div>
<?php
	}
	if($mode=="add")
	{
		$errors=0;
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(!$mimetype)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nomimetype</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "INSERT INTO ".$tableprefix."_mimetypes (mimetype, icon) ";
			$sql .="VALUES ('$mimetype', '$icon')";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add filetype to database.");
			$mimetypenr=faqe_db_insert_id($db);
			if(isset($new_exts))
			{
				while(list($null, $extension) = each($_POST["new_exts"]))
				{
					$sql="insert into ".$tableprefix."_fileextensions (mimetype,extension) values ('$mimetypenr','$extension')";
					if(!$result = faqe_db_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to add fileextension to database.");
				}
			}
			if(isset($desc))
			{
				while(list($desclang, $description) = each($_POST["desc"]))
				{
					$sql="insert into ".$tableprefix."_filetypedescription (mimetype,language,description) values ('$mimetypenr','$desclang','$description')";
					if(!$result = faqe_db_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_filetype_added";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_addfiletype</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filetypelist</a></div>";
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
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_filetypedescription where (mimetype=$input_entrynr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_fileextensions where (mimetype=$input_entrynr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_mimetypes where (entrynr=$input_entrynr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filetypelist</a></div>";
	}
	if($mode=="edit")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_mimetypes where (entrynr=$input_entrynr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<form name="inputform" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editfiletypes?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_file_mimetype?>:</td><td>
<input class="faqeinput" name="mimetype" value="<?php echo $myrow["mimetype"]?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_icongfx?>:</td>
<?php echo gfx_selector("icon",$myrow["icon"])?>
</tr>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_entrynr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_fileextensions?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_fileextensions where mimetype=$input_entrynr";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if ($tmprow = faqe_db_fetch_array($tmpresult))
		{
			 do {
				echo $tmprow["extension"]." (<input type=\"checkbox\" name=\"rem_exts[]\" value=\"".$tmprow["entrynr"]."\"> $l_remove)<BR>";
			 } while($tmprow = faqe_db_fetch_array($tmpresult));
			 echo "<br>";
		}
		else
			echo "$l_none<br><br>";
		echo "<span class=\"inlineheading1\">$l_add:</span><br>";
		echo "<select name=\"new_exts[]\" id=\"new_exts\" size=\"5\" multiple></select><hr>";
		echo "<input type=\"text\" class=\"faqeinput\" size=\"10\" maxlength=\"20\" id=\"new_extension\" name=\"new_extension\">&nbsp;";
		echo "<input type=\"button\" class=\"faqebutton\" onclick=\"addExtension();\" value=\"$l_add\">";
?>
</td></tr>
<tr class="inputrow"><td align="right" width="30%" valign="top"><?php echo $l_descriptions?>:</td><td>
<?php
		$availlangs=language_list("../language/");
		for($i=0;$i<count($availlangs);$i++)
		{
			if($i>0)
				echo "<br>";
			echo $availlangs[$i].": ";
			echo "<input type=\"text\" class=\"faqeinput\" size=\"40\" maxlength=\"80\" name=\"desc[".$availlangs[$i]."]\"";
			$tmpsql="select * from ".$tableprefix."_filetypedescription where mimetype=$input_entrynr and language='".$availlangs[$i]."'";
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if ($tmprow = faqe_db_fetch_array($tmpresult))
				echo " value=\"".$tmprow["description"]."\"";
			echo ">";
		}
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_filetypelist?></a></div>
<?php
	}
	if($mode=="update")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		if(!$mimetype)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nomimetype</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$tableprefix."_mimetypes SET mimetype='$mimetype', icon='$icon' where entrynr=$input_entrynr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			if(isset($rem_exts))
			{
				while(list($null, $extension) = each($_POST["rem_exts"]))
				{
					$sql="delete from ".$tableprefix."_fileextensions where entrynr=".$extension;
					@faqe_db_query($sql, $db);
				}
			}
			if(isset($new_exts))
			{
				while(list($null, $extension) = each($_POST["new_exts"]))
				{
					$sql="insert into ".$tableprefix."_fileextensions (mimetype,extension) values ('$input_entrynr','$extension')";
					if(!$result = faqe_db_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			if(isset($desc))
			{
				$sql="delete from ".$tableprefix."_filetypedescription where mimetype=$input_entrynr";
				if(!$result = faqe_db_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Unable to update the database.");
				while(list($desclang, $description) = each($_POST["desc"]))
				{
					$sql="insert into ".$tableprefix."_filetypedescription (mimetype,language,description) values ('$input_entrynr','$desclang','$description')";
					if(!$result = faqe_db_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to update the database.");
				}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_filetypeupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_filetypelist</a></div>";
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
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	if($admin_rights > 2)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_addfiletype?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
// Display list of actual users
$sql = "select * from ".$tableprefix."_mimetypes ";
switch($sorting)
{
	case 12:
		$sql.="order by mimetype desc";
		break;
	default:
		$sql.="order by mimetype asc";
		break;
}
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
$maxsortcol=1;
$baseurl="$act_script_url?$langvar=$act_lang";
if($admstorefaqfilters==1)
	$baseurl.="&storefaqfilter=1";
echo "<tr class=\"rowheadings\">";
echo "<td align=\"center\" width=\"60%\">";
$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_file_mimetype</b></a>";
echo getSortMarker($sorting, 1, $maxsortcol);
echo "</td>";
echo "<td class=\"rowheadings\" align=\"center\" width=\"30%\"><b>$l_fileextensions</b></td>";
echo "<td>&nbsp;</td></tr>";
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"4\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	do {
		$act_id=$myrow["entrynr"];
		echo "<tr class=\"displayrow\">";
		echo "<td width=\"40%\">".$myrow["mimetype"]."</td>";
		echo "<td align=\"left\" width=\"30%\">";
		$tmpsql="select * from ".$tableprefix."_fileextensions where mimetype=$act_id";
		if(!$tmpresult = faqe_db_query($tmpsql, $db))
			die("Could not connect to the database.");
		$firstentry=true;
		while($tmprow=faqe_db_fetch_array($tmpresult))
		{
			if($firstentry)
				$firstentry=false;
			else
				echo ", ";
			echo $tmprow["extension"];
		}
		echo "</td>";
		echo "<td>";
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_entrynr=$act_id&$langvar=$act_lang")."\">";
		echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
		echo "&nbsp; ";
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_entrynr=$act_id")."\">";
		echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
		echo "&nbsp; ";
		echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_entrynr=$act_id&$langvar=$act_lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
   } while($myrow = faqe_db_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_addfiletype?></a></div>
<?php
}
}
include('./trailer.php');
?>