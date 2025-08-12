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
$page_title=strip_tags($l_diraccess);
$page="diraccess";
require_once('./heading.php');
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
	if($mode=="edit")
	{
		$sql="select * from ".$tableprefix."_dir_access where entrynr=$input_entrynr";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
		if(!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td align=\"center\">No such entry.</td></tr>");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editsubdir?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_dirname?>:</td><td><input class="faqeinput" type="text" name="dirname" size="40" maxlength="80" value="<?php echo do_htmlentities(stripslashes($myrow["dirname"]))?>">
<input type="button" class="faqebutton" value="<?php echo $l_choose?>" onclick="choosedir()"></td></tr>
<?php
		echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_progs:</td>";
		echo "<td>";
		$tempsql = "select prog.* from ".$tableprefix."_prog_dirs pd, ".$tableprefix."_programm prog where prog.prognr=pd.prognr and pd.dirnr=$input_entrynr";
		if(!$tempresult = faqe_db_query($tempsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
		if ($temprow = faqe_db_fetch_array($tempresult))
		{
			do {
				echo display_encoded($temprow["programmname"])." [".$temprow["language"]."] (<input type=\"checkbox\" name=\"rem_progs[]\" value=\"".$temprow["prognr"]."\"> $l_remove)<BR>";
				$current_progs[] = $temprow["prognr"];
			} while($temprow = faqe_db_fetch_array($tempresult));
			echo "<br>";
		}
		else
			echo "$l_noprogs<br><br>";
		$tempsql = "select * from ".$tableprefix."_programm";
		$firstarg=true;
		if(isset($current_progs))
		{
			while(list($null, $curprog) = each($current_progs))
			{
				if($firstarg)
				{
					$tempsql.=" where prognr != $curprog";
					$firstarg=false;
				}
				else
					$tempsql.=" and prognr != $curprog";
			}
		}
		$tempsql.=" order by programmname";
		if(!$tempresult = faqe_db_query($tempsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
		if ($temprow = faqe_db_fetch_array($tempresult))
		{
			echo "<span class=\"inlineheading1\">$l_add:</span><br>";
			echo "<SELECT NAME=\"progs[]\" size=\"5\" multiple>";
			do {
				echo "<OPTION VALUE=\"".$temprow["prognr"]."\" >".display_encoded($temprow["programmname"])." [".$temprow["language"]."]</OPTION>\n";
			} while($temprow = faqe_db_fetch_array($tempresult));
			echo"</select>";
		}
		echo "</td></tr>";
?>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subdirlist?></a></div>
<?php
	}
	if($mode=="update")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		$dirname=trim($dirname);
		if(!$dirname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nodirname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "update ".$tableprefix."_dir_access set dirname='$dirname' where entrynr=$input_entrynr";
			if(!faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Unable to update the database.");
			if(isset($rem_progs))
			{
	    		while(list($null, $prognr) = each($_POST["rem_progs"])) {
		    		$progquery = "delete from ".$tableprefix."_prog_dirs where prognr=$prognr and entrynr=$input_entrynr";
		    		if(!faqe_db_query($progquery, $db))
						die("<tr class=\"errorrow\"><td align=\"center\">Unable to update the database.");
    			}
			}
			if(isset($progs))
			{
	    		while(list($null, $prognr) = each($_POST["progs"])) {
		    		$progquery = "INSERT INTO ".$tableprefix."_prog_dirs (prognr, dirnr) VALUES ($prognr,$input_entrynr)";
		    		if(!faqe_db_query($progquery, $db))
						die("<tr class=\"errorrow\"><td align=\"center\">Unable to update the database.");
    			}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_dirupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subdirlist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="display")
	{
		$sql="select * from ".$tableprefix."_dir_access where entrynr=$input_entrynr";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
		if(!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td align=\"center\">No such entry.</td></tr>");
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaysubdir?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_dirname?>:</td>
<td><?php echo do_htmlentities(stripslashes($myrow["dirname"]))?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_progs?>:</td>
<td>
<?php
		$tempsql="select prog.* from ".$tableprefix."_prog_dirs pd, ".$tableprefix."_programm prog where prog.prognr=pd.prognr and pd.dirnr=$input_entrynr";
		if(!$tempresult = faqe_db_query($tempsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
		if ($temprow = faqe_db_fetch_array($tempresult))
		{
			echo "<ul>";
			do{
				echo "<li>".display_encoded($temprow["programmname"])." [".$temprow["language"]."]";
			}while($temprow=faqe_db_fetch_array($tempresult));
			echo "</ul>";
		}
		else
			echo $l_noprogs;
?>
</td></tr>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subdirlist?></a></div>
<?php
	}
	if($mode=="new")
	{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newsubdir?></b></td></tr>
<form name="inputform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_dirname?>:</td><td><input class="faqeinput" type="text" name="dirname" size="40" maxlength="80">
<input type="button" class="faqebutton" value="<?php echo $l_choose?>" onclick="choosedir()"></td></tr>
<?php
		$progsql="select * from ".$tableprefix."_programm order by programmname";
		if(!$progresult = faqe_db_query($progsql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
    	if($progrow = faqe_db_fetch_array($progresult))
    	{
			echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_progs:</td>";
			echo "<td><select name=\"progs[]\" size=\"5\" multiple>";
			do{
				echo "<option value=\"".$progrow["prognr"]."\">";
				echo display_encoded($progrow["programmname"])." [".stripslashes($progrow["language"])."]";
				echo "</option>";
			}while($progrow=faqe_db_fetch_array($progresult));
			echo "</select></td></tr>";
		}
?>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_subdirlist?></a></div>
<?php
	}
	if($mode=="add")
	{
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$errors=0;
		$dirname=trim($dirname);
		if(!$dirname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nodirname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "insert into ".$tableprefix."_dir_access (dirname) values ('$dirname')";
			if(!$result = faqe_db_query($sql, $db))
				die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.</td></tr>");
			$entrynr=faqe_db_insert_id($db);
			if(isset($progs))
			{
	    		while(list($null, $prognr) = each($_POST["progs"])) {
		    		$progquery = "INSERT INTO ".$tableprefix."_prog_dirs (prognr, dirnr) VALUES ($prognr,$entrynr)";
		    		if(!faqe_db_query($progquery, $db))
						die("<tr class=\"errorrow\"><td align=\"center\">Unable to update the database.");
    			}
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_diradded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subdirlist</a></div>";
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
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
		$sql="delete from ".$tableprefix."_prog_dirs where dirnr=$input_entrynr";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Unable to update database.</td></tr>");
		$sql="delete from ".$tableprefix."_dir_access where entrynr=$input_entrynr";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td align=\"center\">Unable to update database.</td></tr>");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_dirdeleted";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_subdirlist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubdir?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
	$sql = "select * from ".$tableprefix."_dir_access order by dirname";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.");
?>
<tr class="rowheadings">
<td width="5%" align="center"><b>#</b></td>
<td width="90%" align="center"><b><?php echo $l_dirname?></b></td>
<td>&nbsp;</td></tr>
<?php
	if (!$myrow = faqe_db_fetch_array($result))
	{
		echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"3\">";
		echo $l_noentries;
		echo "</td></tr></table></td></tr></table>";
	}
	else
	{
		do{
			$act_id=$myrow["entrynr"];
			echo "<tr class=\"displayrow\">";
			echo "<td align=\"center\">$act_id</td>";
			echo "<td align=\"center\">".do_htmlentities($myrow["dirname"])."</td>";
			echo "<td align=\"left\">";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_entrynr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_entrynr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a>";
			echo "&nbsp; ";
			echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=delete&input_entrynr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a>";
			echo "</td></tr>";
		}while($myrow=faqe_db_fetch_array($result));
		echo "</table></tr></td></table>";
	}
	if($admin_rights > 2)
	{
?>
	<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&$langvar=$act_lang")?>"><?php echo $l_newsubdir?></a></div>
<?php
	}
}
include('./trailer.php');
?>