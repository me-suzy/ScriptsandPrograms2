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
$page_title=$l_reorder_kb;
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
			$sql="select displaypos from ".$tableprefix."_kb_articles where articlenr=$input_articlenr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]-1;
			$sql="update ".$tableprefix."_kb_articles set displaypos=displaypos+1 where displaypos=$newpos";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			$sql="update ".$tableprefix."_kb_articles set displaypos=$newpos where articlenr=$input_articlenr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
		}
		if($direction=="down")
		{
			$sql="select displaypos from ".$tableprefix."_kb_articles where articlenr=$input_articlenr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			if(!$myrow=faqe_db_fetch_array($result))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Calling error.");
			$newpos=$myrow["displaypos"]+1;
			$sql="update ".$tableprefix."_kb_articles set displaypos=displaypos-1 where displaypos=$newpos";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			$sql="update ".$tableprefix."_kb_articles set displaypos=$newpos where articlenr=$input_articlenr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
		}
	}
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$sql = "select * from ".$tableprefix."_kb_articles order by displaypos asc";
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
<td align="center" width="10%">&nbsp;</td>
<td align="center" width="60%"><b><?php echo $l_heading?></b></td>
<td width="2%">&nbsp;</td><td width="2%">&nbsp;</td></tr>
<?php
		$mycount=0;
		do {
		$mycount++;
		$act_id=$myrow["articlenr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["articlenr"]."</td>";
		echo "<td align=\"left\">".undo_html_ampersand(stripslashes($myrow["heading"]))."</td>";
		if($admin_rights > 1)
		{
			if($myrow["displaypos"]==0)
			{
				$tempsql="select max(displaypos) as newdisplaypos from ".$tableprefix."_kb_articles";
				if(!$tempresult = faqe_db_query($tempsql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
				if(!$temprow = faqe_db_fetch_array($tempresult))
					$newpos=1;
				else
					$newpos=$temprow["newdisplaypos"]+1;
				$updatesql="update ".$tableprefix."_kb_articles set displaypos=$newpos where articlenr=".$myrow["articlenr"];
				if(!$updateresult = faqe_db_query($updatesql, $db))
				    die("<tr class=\"errorrow\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".faqe_db_error());
			}
			if($mycount>1)
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&input_articlenr=$act_id&$langvar=$act_lang&direction=up")."\">";
				echo "<img src=\"gfx/up.gif\" border=\"0\" title=\"$l_move_up\" alt=\"$l_move_up\"></a>";
				echo "</td>";
			}
			else
				echo "<td class=\"inputrow\">&nbsp;</td>";
			if($mycount<faqe_db_num_rows($result))
			{
				echo "<td class=\"inputrow\" align=\"center\" width=\"16\">";
				echo "<a href=\"".do_url_session("$act_script_url?mode=move&$langvar=$act_lang&input_articlenr=$act_id&direction=down")."\">";
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