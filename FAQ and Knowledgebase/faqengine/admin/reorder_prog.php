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
$page_title=$l_reorder_prog;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(!isset($sellang))
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<?php
		if(is_konqueror())
			echo "<tr><td></td></tr>";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr class="inputrow">
<td align="right" width="30%"><?php echo $l_language?>:</td>
<td>
<?php
		echo language_select($act_lang,"sellang");
?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2">
<input class="faqebutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
   		echo "</table></tr></td></table>";
		include('./trailer.php');
		exit;
}
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
			$sql="select displaypos from ".$tableprefix."_programm where prognr=$input_prognr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]-1;
			$sql="update ".$tableprefix."_programm set displaypos=displaypos+1 where displaypos=$newpos and language='$sellang'";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			$sql="update ".$tableprefix."_programm set displaypos=$newpos where prognr=$input_prognr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
		}
		if($direction=="down")
		{
			$sql="select displaypos from ".$tableprefix."_programm where prognr=$input_prognr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]+1;
			$sql="update ".$tableprefix."_programm set displaypos=displaypos-1 where displaypos=$newpos and language='$sellang'";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			$sql="update ".$tableprefix."_programm set displaypos=$newpos where prognr=$input_prognr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
		}
	}
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$sql = "select * from ".$tableprefix."_programm where language='$sellang' order by displaypos asc";
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
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
<td align="center"><b><?php echo $l_progname?></b></td>
<td width="2%">&nbsp;</td><td width="2%">&nbsp;</td></tr>
<?php
		$mycount=0;
		do {
		$mycount++;
		$act_id=$myrow["prognr"];
		echo "<tr class=\"displayrow\">";
		echo "<td width=\"70%\">".display_encoded($myrow["programmname"])."</td>";
		if($admin_rights > 1)
		{
			if($myrow["displaypos"]==0)
			{
				$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_programm where language='".$myrow["language"]."'";
				if(!$tempresult = faqe_db_query($tempsql, $db))
				    die("<tr bgcolor=\"#c0c0c0\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
				if(!$temprow = faqe_db_fetch_array($tempresult))
					$newpos=1;
				else
					$newpos=$temprow["newdisplaypos"]+1;
				$updatesql="update ".$tableprefix."_programm set displaypos=$newpos where prognr=".$myrow["prognr"];
				if(!$updateresult = faqe_db_query($updatesql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			}
			if($mycount>1)
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_prognr=$act_id&sellang=$sellang&$langvar=$act_lang&direction=up")."\">";
				echo "<img src=\"gfx/up.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_up\"></a>";
				echo "</td>";
			}
			else
				echo "<td class=\"inputrow\">&nbsp;</td>";
			if($mycount<faqe_db_num_rows($result))
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&$langvar=$act_lang&input_prognr=$act_id&sellang=$sellang&direction=down")."\">";
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
include('./trailer.php');
?>