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
$page_title=$l_reorder_faq;
require_once('./heading.php');
if($admin_rights < 2)
	die($l_functionnotallowed);
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	// Page called with some special mode
	if($mode=="move")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		if($direction=="up")
		{
			$sql="select displaypos from ".$tableprefix."_data where faqnr=$input_faqnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]-1;
			$sql="update ".$tableprefix."_data set displaypos=displaypos+1 where displaypos=$newpos and category=$input_catnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			$sql="update ".$tableprefix."_data set displaypos=$newpos where faqnr=$input_faqnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
		}
		if($direction=="down")
		{
			$sql="select displaypos from ".$tableprefix."_data where faqnr=$input_faqnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]+1;
			$sql="update ".$tableprefix."_data set displaypos=displaypos-1 where displaypos=$newpos and category=$input_catnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			$sql="update ".$tableprefix."_data set displaypos=$newpos where faqnr=$input_faqnr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
		}
	}
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(!isset($input_catnr))
{
	if($admin_rights>2)
		$sql = "select prog.programmname, prog.language, cat.categoryname, cat.catnr from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where prog.prognr=cat.programm and cat.numfaqs > 0 group by cat.catnr order by prog.language, prog.prognr";
	else
		$sql = "select prog.programmname, prog.language, cat.categoryname, cat.catnr from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_category_admins ca where prog.prognr=cat.programm and cat.catnr=ca.catnr and ca.usernr=".$userdata["usernr"]." and cat.numfaqs > 0 group by cat.catnr order by prog.language, prog.prognr";
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
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_selectcat?>:</td>
<td><select name="input_catnr">
<?php
	do{
		echo "<option value=\"".$myrow["catnr"]."\">";
		echo display_encoded($myrow["categoryname"])." [".display_encoded($myrow["programmname"]).", ".$myrow["language"]."]</option>";
	}while($myrow=faqe_db_fetch_array($result));
?>
</select></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
</table></td></tr></table>
<?php
	}
}
else
{
$sql = "select prog.*, cat.categoryname from ".$tableprefix."_programm prog, ".$tableprefix."_category cat where cat.catnr=$input_catnr and prog.prognr=cat.programm";
if(!$result = faqe_db_query($sql, $db))
    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"3\">Could not connect to the database.".faqe_db_error());
if(!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"errorrow\"><td align=\"center\" colspan=\"3\">";
	die("Calling error");
}
else
{
	$catname=display_encoded($myrow["categoryname"]);
	$progname=display_encoded($myrow["programmname"]);
	$proglang=$myrow["language"];
}
?>
<tr class="inforow">
<td align="center" colspan="3"><b><?php echo "$progname [$proglang] : $catname"?></b></td></tr>
<?php
$sql = "select * from ".$tableprefix."_data where category=$input_catnr order by displaypos asc";
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr bgcolor=\"#c0c0c0\"><td align=\"center\" colspan=\"3\">Could not connect to the database.".faqe_db_error());
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
?>
<tr class="rowheadings">
<td align="center"><b><?php echo $l_heading?></b></td>
<td width="2%">&nbsp;</td><td width="2%">&nbsp;</td></tr>
<?php
		$mycount=0;
		do {
		$mycount++;
		$act_id=$myrow["faqnr"];
		echo "<tr class=\"displayrow\">";
		echo "<td width=\"70%\">".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		if($admin_rights > 1)
		{
			if($myrow["displaypos"]==0)
			{
				$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_data where category=".$myrow["category"];
				if(!$tempresult = faqe_db_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
				if(!$temprow = faqe_db_fetch_array($tempresult))
					$newpos=1;
				else
					$newpos=$temprow["newdisplaypos"]+1;
				$updatesql="update ".$tableprefix."_data set displaypos=$newpos where faqnr=".$myrow["faqnr"];
				if(!$updateresult = faqe_db_query($updatesql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			}
			if($mycount>1)
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_catnr=$input_catnr&input_faqnr=$act_id&$langvar=$act_lang&direction=up")."\">";
				echo "<img src=\"gfx/up.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_up\"></a>";
				echo "</td>";
			}
			else
				echo "<td class=\"inputrow\">&nbsp;</td>";
			if($mycount<faqe_db_num_rows($result))
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&$langvar=$act_lang&input_catnr=$input_catnr&input_faqnr=$act_id&direction=down")."\">";
				echo "<img src=\"gfx/down.gif\" border=\"0\" title=\"$l_move_down\" alt=\"$l_move_down\"></a>";
				echo "</td>";
			}
			else
				echo "<td class=\"inputrow\">&nbsp;</td>";
		}
		else
			echo "<td>&nbsp;</td>";
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</table></tr></td></table>";
}
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_change_cat</a></div>";
}
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("categories.php?$langvar=$act_lang")."\">$l_catlist</a></div>";
include('./trailer.php');
?>