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
$page_title=$l_badwordlist;
require_once('./heading.php');
if(!isset($badword))
	$badword="";
if(!isset($replacement))
	$replacement="";
$errmsg_new="";
$errmsg_edit="";
$entryadded=false;
$entryupdated=false;
$entrydeleted=false;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="add")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		$badword=trim($badword);
		$replacement=trim($replacement);
		if(!$badword)
		{
			$errmsg_new.="<li>$l_nobadword</li>";
			$errors=1;
		}
		if(!$replacement)
		{
			$errmsg_new.="<li>$l_noreplacement</li>";
			$errors=1;
		}
		if($errors==0)
		{
			$badword=addslashes($badword);
			$replacement=addslashes(do_htmlentities($replacement));
			$sql = "INSERT INTO ".$badwordprefix."_bad_words (word, replacement) ";
			$sql .="VALUES ('$badword','$replacement')";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add censorstring to database.");
			$entryadded=true;
			$badword="";
			$replacement="";
		}
	}
	if(isset($delete))
	{
		$deleteSQL = "delete from ".$badwordprefix."_bad_words where (indexnr=$input_indexnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("tr class=\"errorrow\"><td>$l_cantdelete.");
		$entrydeleted=true;
		$badword="";
		$replacement="";
	}
	if(isset($update))
	{
		$errors=0;
		$badword=trim($badword);
		$replacement=trim($replacement);
		if(!$badword)
		{
			$errmsg_edit.="<li>$l_nobadword</li>";
			$errors=1;
		}
		if(!$replacement)
		{
			$errmsg_edit.="<li>$l_noreplacement</li>";
			$errors=1;
		}
		if($errors==0)
		{
			$badword=addslashes($badword);
			$replacement=addslashes(do_htmlentities($replacement));
			$sql = "UPDATE ".$badwordprefix."_bad_words SET word='$badword', replacement='$replacement' ";
			$sql .="WHERE (indexnr = $input_indexnr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.".mysql_error());
			$entryupdated=true;
		}
		$badword="";
		$replacement="";
	}
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="3">
<b><?php echo $l_addbadword?></b></td></tr>
<?php
if($errmsg_new)
{
	echo "<tr bgcolor=\"#c0c0c0\"><td colspan=\"3\">";
	echo "$l_errorsoccured:<ul>$errmsg_new</ul></td></tr>";
}
if($entryadded)
{
	echo "<tr bgcolor=\"#c0c0c0\"><td colspan=\"3\" align=\"center\">";
	echo "<i>$l_badwordadded</i></td></tr>";
}
?>
<tr class="rowheadings">
<td class="rowheadings" align="center" width="30%"><b><?php echo $l_badword?></b></td>
<td class="rowheadings" align="center" width="30%"><b><?php echo $l_replacement?></b></td>
<td width="30%">&nbsp;</td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="add">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="center">
<input class="faqeinput" type="text" name="badword" size="30" maxlength="100" value="<?php echo $badword?>"></td>
<td align="center">
<input class="faqeinput" type="text" name="replacement" size="30" maxlength="100" value="<?php echo $replacement?>"></td>
<td><input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr></form>
<tr class="headingrow"><td align="center" colspan="3">
<b><?php echo $l_actualbadwords?></b></td></tr>
<?php
if($errmsg_edit)
{
	echo "<tr class=\"errorrow\"><td colspan=\"3\">";
	echo "$l_errorsoccured:<ul>$errmsg_edit</ul></td></tr>";
}
if($entrydeleted)
{
	echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
	echo "<i>$l_badworddeleted</i></td></tr>";
}
if($entryupdated)
{
	echo "<tr class=\"displayrow\"><td colspan=\"3\" align=\"center\">";
	echo "<i>$l_badwordupdated</i></td></tr>";
}
$sql = "select * from ".$badwordprefix."_bad_words order by word asc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<tr class="rowheadings">
<td class="rowheadings" align="center" width="30%"><b><?php echo $l_badword?></b></td>
<td class="rowheadings" align="center" width="30%"><b><?php echo $l_replacement?></b></td>
<td width="30%">&nbsp;</td></tr>
<?php
		do {
			$act_id=$myrow["indexnr"];
			echo "<form method=\"post\" action=\"$act_script_url\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"input_indexnr\" value=\"$act_id\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"1\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<tr class=\"inputrow\">";
			echo "<td align=\"center\">";
			echo "<input class=\"faqeinput\" type=\"text\" name=\"badword\" value=\"".do_htmlentities($myrow["word"])."\" size=\"30\" maxlength=\"100\"></td>";
			echo "<td align=\"center\">";
			echo "<input class=\"faqeinput\" type=\"text\" name=\"replacement\" value=\"".$myrow["replacement"]."\" size=\"30\" maxlength=\"100\"></td>";
			echo "<td>";
			echo "<input class=\"faqebutton\" type=\"submit\" name=\"delete\" value=\"$l_delete\">";
			echo "&nbsp;&nbsp;";
			echo "<input class=\"faqebutton\" type=\"submit\" name=\"update\" value=\"$l_update\">";
			echo "</td></tr></form>";
	   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
include('./trailer.php');
?>