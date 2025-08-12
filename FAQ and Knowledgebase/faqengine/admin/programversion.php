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
$page="programversion";
$page_title=$l_versions;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$sql = "select * from ".$tableprefix."_programm where prognr=$input_prognr";
if(!$result = faqe_db_query($sql, $db))
    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("Calling error");
}
else
{
	$progname=$myrow["programmname"];
	$proglang=$myrow["language"];
}
if(isset($mode))
{
	// Page called with some special mode
	if($mode=="new")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<td align="center" colspan="2"><b><?php echo $l_programm?>: <?php echo "$progname [$proglang]"?></b></td></tr>
<tr class="headingrow">
<td align="center" colspan="2"><b><?php echo $l_newversion?></b></td></tr>
<form name="inputform" onsubmit="return checkform()" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_prognr" value="<?php echo $input_prognr?>">
<input type="hidden" name="mode" value="add">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_versionnr?>:</td>
<td><input class="faqeinput" type="text" name="versionnr" size="20" maxlength="20"></td></tr>
<tr class="actionrow">
<td align="center" colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</table></td></tr></table>
<?php
	}
	if($mode=="add")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<td align="center" colspan="2"><b><?php echo $l_programm?>: <?php echo "$progname [$proglang]"?></b></td></tr>
<tr class="headingrow">
<td align="center" colspan="2"><b><?php echo $l_newversion?></b></td></tr>
<?php
		$errors=0;
		if(!$versionnr)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_noversion</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "insert into ".$tableprefix."_programm_version (programm, version) values ($input_prognr, '$versionnr')";
			if(!$result = faqe_db_query($sql, $db))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
			    die("Unable to add version to database.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_versionadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang&input_prognr=$input_prognr")."\">$l_newversion</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&input_prognr=$input_prognr")."\">$l_versions</a></div>";
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
<tr class="inforow">
<td align="center" colspan="2"><b><?php echo $l_programm?>: <?php echo "$progname [$proglang]"?></b></td></tr>
<?php
		$sql = "delete from ".$tableprefix."_faq_prog_version where versionnr=$entry";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("Unable to delete version from database.");
		}
		$sql = "delete from ".$tableprefix."_kb_prog_version where versionnr=$entry";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("Unable to delete version from database.");
		}
		$sql = "delete from ".$tableprefix."_programm_version where entrynr=$entry";
		if(!$result = faqe_db_query($sql, $db))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("Unable to delete version from database.");
		}
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_versiondeleted";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&input_prognr=$input_prognr")."\">$l_versions</a></div>";
	}
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang&input_prognr=$input_prognr")?>"><?php echo $l_newversion?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow">
<td align="center" colspan="2"><b><?php echo $l_programm?>: <?php echo "$progname [$proglang]"?></b></td></tr>
<?php
	$sql = "select * from ".$tableprefix."_programm_version where programm=$input_prognr order by version desc";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
	if (!$myrow = faqe_db_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"2\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
?>
<tr class="rowheadings">
<td align="center" width="60%"><b><?php echo $l_versionnr?></b></td>
<td>&nbsp;</td></tr>
<?php
		do {
			$act_id=$myrow["entrynr"];
			echo "<tr class=\"displayrow\">";
			echo "<td align=\"right\">".$myrow["version"]."</td>";
			echo "<td align=\"center\"><a class=\"listlink2\" href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=delete&input_prognr=$input_prognr&entry=$act_id")."\">$l_delete</a>";
			echo "</td></tr>";
		} while($myrow = faqe_db_fetch_array($result));
		echo "</table></tr></td></table>";
	}
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang&mode=new&input_prognr=$input_prognr")."\">$l_newversion</a></div>";
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("program.php?$langvar=$act_lang")."\">$l_proglist</a></div>";
include('./trailer.php');
?>