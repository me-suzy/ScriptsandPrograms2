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
$page_title=$l_leacherlist;
$page="leacher";
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="new")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering userdata
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newleacher?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_useragent?>:</td><td><input class="faqeinput" type="text" name="useragent" size="30" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_description?>:</td><td>
<textarea class="faqeinput" name="description" rows="5" cols="30"></textarea></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_leacherlist?></a></div>
<?php
	}
	if($mode=="add")
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
		if(!$useragent)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nouseragent</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "INSERT INTO ".$leacherprefix."_leachers (useragent,description) ";
			$sql .="VALUES ('$useragent','$description')";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_leacheradded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&$langvar=$act_lang")."\">$l_newleacher</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_leacherlist</a></div>";
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
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$leacherprefix."_leachers where (entrynr=$input_entrynr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_leacherlist</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$leacherprefix."_leachers where (entrynr=$input_entrynr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editleacher?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_entrynr" value="<?php echo $myrow["entrynr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_useragent?>:</td><td><input class="faqeinput" type="text" name="useragent" size="30" maxlength="80" value="<?php echo do_htmlentities($myrow["useragent"])?>"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_description?>:</td><td>
<textarea class="faqeinput" name="description" rows="5" cols="30"><?php echo do_htmlentities($myrow["description"])?></textarea></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_leacherlist?></a></div>
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
		if(!$useragent)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nouseragent</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "UPDATE ".$leacherprefix."_leachers SET useragent='$useragent', description='$description' ";
			$sql .="WHERE (entrynr = $input_entrynr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_leacherupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_leacherlist</a></div>";
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
	if($admin_rights>2)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newleacher?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$leacherprefix."_leachers order by useragent";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
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
<td align="center" width="40%"><b><?php echo $l_useragent?></b></td>
<td align="center" width="50%"><b><?php echo $l_description?></b></td>
<td width="30%">&nbsp;</td></tr>
<?php
		do {
		$act_id=$myrow["entrynr"];
		echo "<tr class=\"displayrow\">";
		echo "<td>".do_htmlentities($myrow["useragent"])."</td>";
		echo "<td>".do_htmlentities($myrow["description"])."</td>";
		echo "<td>";
		if($admin_rights > 2)
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_entrynr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_entrynr=$act_id")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			echo "&nbsp; ";
		}
   } while($myrow = faqe_db_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newleacher?></a></div>
<?php
}
}
include('./trailer.php');
?>