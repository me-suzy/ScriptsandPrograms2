<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('../config.php');
require('./auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_references;
$page="reference";
require('./heading.php');
if(!isset($filterstat))
	$filterstat=-1;
if(!isset($prognr))
	$prognr=-1;
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
		if(psys_array_key_exists($admcookievals,"ref_prognr"))
			$prognr=$admcookievals["ref_prognr"];
		if(psys_array_key_exists($admcookievals,"ref_state"))
			$filterstat=$admcookievals["ref_state"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(isset($mode))
{
	if($mode=="display")
	{
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_references where (id=$input_id)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displayreference?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo $temprow["programmname"]?> [<?php echo $temprow["language"]?>]</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_sitename?>:</td>
<td><?php echo $myrow["sitename"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_siteurl?>:</td>
<td><a href="<?php echo $myrow["prot"]?>://<?php echo $myrow["url"]?>"><?php echo $myrow["prot"]?>://<?php echo $myrow["url"]?></a></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_contactname?>:</td>
<td><?php echo $myrow["contactname"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_email?>:</td>
<td><?php echo $myrow["contactmail"]?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_references</a></div>";
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_references where (id=$input_id)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_references</a></div>";
	}
	if($mode=="edit")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_references where (id=$input_id)";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		if($admin_rights <3)
		{
			$sql2="select * from ".$tableprefix."_programm_admins (prognr=".$myrow["programm"].") and (usernr=".$userdata["usernr"].")";
			if(!$result2 = mysql_query($sql2, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$tmprow = mysql_fetch_array($result2))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editreference?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_id" value="<?php echo $myrow["id"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td>
<?php
		if($admin_rights>2)
		{
			$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
			if(!$result1 = mysql_query($sql1, $db)) {
				die("Could not connect to the database (3).".mysql_error());
			}
			if (!$temprow = mysql_fetch_array($result1))
			{
				echo "<a href=\"".do_url_session("program.php?mode=new&lang=$lang")."\" target=\"_blank\">$l_new</a>";
			}
			else
			{
?>
<select name="programm">
<option value="-1">???</option>
<?php
				do {
					$progname=htmlentities($temprow["programmname"]);
					$proglang=$temprow["language"];
					echo "<option value=\"".$temprow["prognr"]."\"";
					if($temprow["prognr"]==$myrow["programm"])
						echo " selected";
					echo ">";
					echo "$progname [$proglang]";
					echo "</option>";
				} while($temprow = mysql_fetch_array($result1));
?>
</select>
<?php
			}
		}
		else
		{
			echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]";
			echo "<input type=\"hidden\" name=\"programm\" value=\"".$myrow["programm"]."\"";
		}
?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sitename?>:</td>
<td><input class="psysinput" type="text" name="sitename" size="30" maxlength="250" value="<?php echo $myrow["sitename"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_siteurl?>:</td>
<td><select name="prot">
<?php
	echo "<option value=\"http\"";
	if($myrow["prot"]=="http")
		echo "selected";
	echo ">http</option>";
	echo "<option value=\"https\"";
	if($myrow["prot"]=="https")
		echo "selected";
	echo ">https</option>";
echo "</select><b>://</b>";
?>
<input class="psysinput" type="text" name="input_url" size="30" maxlength="250" value="<?php echo $myrow["url"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_contactname?>:</td>
<td><input class="psysinput" type="text" name="contactname" size="30" maxlength="250" value="<?php echo $myrow["contactname"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td>
<td><input class="psysinput" type="text" name="contactmail" size="30" maxlength="250" value="<?php echo $myrow["contactmail"]?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_language?>:</td>
<td><?php echo $myrow["enter_lang"]?></td></tr>
<?php
		if($admin_rights > 2)
		{
?>
<tr class="inputrow"><td align="right"><?php echo $l_pin?>:</td>
<td><input class="psysinput" type="text" name="pin" size="10" maxlength="10" value="<?php echo $myrow["pin"]?>"></td></tr>
<?php
		}
		else
			echo "<input type=\"hidden\" name=\"pin\" value=\"".$myrow["pin"]."\"";
?>
<tr class="inputrow"><td align="right">&nbsp;</td>
<td><input type="checkbox" name="do_publish" value="1" <?php if($myrow["publish"]==1) echo "checked"?>><?php echo $l_publish?></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td>
<td><input type="checkbox" name="do_approve" value="1" <?php if($myrow["approved"]==1) echo "checked"?>><?php echo $l_approved?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_references</a></div>";
	}
	if($mode=="update")
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
		if(!isset($do_publish))
			$publish=0;
		else
			$publish=1;
		if(isset($do_approve))
			$approved=1;
		else
			$approved=0;
		if(!$contactmail || !validate_email($contactmail))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noemail</td></tr>";
			$errors=1;
		}
		if(!$input_url)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nourl</td></tr>";
			$errors=1;
		}
		if(!$sitename)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nositename</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(!$pin || ($pin<1))
			{
				do{
					$maximum=9999999999;
					if($maximum>mt_getrandmax())
						$maximum=mt_getrandmax();
					mt_srand((double)microtime()*1000000);
					$pin=mt_rand(10000,$maximum);
					$sql = "select * from ".$tableprefix."_references where pin=$pin";
					if(!$result = mysql_query($sql, $db))
					    die("Could not connect to the database.");
				}while($myrow=mysql_fetch_array($result));
			}
			$input_url=addslashes($input_url);
			$contactname=addslashes($contactname);
			$contactmail=addslashes($contactmail);
			$sql = "UPDATE ".$tableprefix."_references SET pin='$pin', publish=$publish, approved=$approved, url='$input_url', ";
			$sql .="sitename='$sitename', contactmail='$contactmail', contactname='$contactname', programm=$programm, prot='$prot' ";
			$sql .="WHERE (id = $input_id)";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_referenceupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_references</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="approve")
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
		$sql = "UPDATE ".$tableprefix."_references SET approved=1 ";
		$sql .="WHERE (id = $input_id)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_entryapproved";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_references</a></div>";
	}
}
else
{
if(($admin_rights > 1) && ($topfilter==1))
{
	echo "</td></tr></table>";
?>
<table class="filterbox" align="center" width="50%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1" <?php if($prognr==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<?php
	do {
		$progname=htmlentities($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(isset($prognr))
		{
			if($prognr==$temprow["prognr"])
				echo " selected";
		}
		echo ">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td>&nbsp;</td></tr>
<?php
	}
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_statusfilter?>:</td>
<td align="left" width="30%"><select name="filterstat">
<option value="-1" <?php if($filterstat==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<option value="0" <?php if($filterstat==0) echo "selected"?>><?php echo $l_onlyapproved?></option>
<option value="1" <?php if($filterstat==1) echo "selected"?>><?php echo $l_onlyunapproved?></option>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$firstarg=true;
// Display list of actual references
if(isset($prognr) && ($prognr>=0))
{
	$tempsql="select * from ".$tableprefix."_programm where prognr=$prognr";
	if(!$tempresult = mysql_query($tempsql, $db)) {
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	}
	if($temprow=mysql_fetch_array($tempresult))
		echo "<tr class=\"inforow\"><td colspan=\"6\" align=\"center\">$l_onlyprog: <i>".$temprow["programmname"]."</i></td></tr>";
	$sql = "select * from ".$tableprefix."_references where programm=$prognr";
	$firstarg=false;
}
else
	$sql = "select * from ".$tableprefix."_references ";
if($filterstat>=0)
{
		if($firstarg)
		{
			$sql.=" where";
			$firstarg=false;
		}
		else
			$sql.=" and";
		echo "<tr class=\"inforow\"><td colspan=\"6\" align=\"center\">$l_onlystate: <i>";
		if($filterstat==0)
		{
			$sql.=" approved=1";
			echo $l_onlyapproved;
		}
		else
		{
			$sql.=" approved=0";
			echo $l_onlyunapproved;
		}
		echo "</i></td></tr>";

}
$sql.="	order by programm desc";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_programm</b></td>";
	echo "<td align=\"center\" width=\"25%\"><b>$l_sitename</b></td>";
	echo "<td align=\"center\" width=\"30%\"><b>$l_siteurl</b></td>";
	echo "<td align=\"center\" width=\"5%\"><b>$l_public</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = mysql_query($tempsql, $db)) {
			die("Could not connect to the database.");
		}
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		$act_id=$myrow["id"];
		if($myrow["approved"]==0)
			$rowclass="refunapproved";
		else
			$rowclass="displayrow";
		echo "<tr class=\"$rowclass\">";
		echo "<td align=\"right\">".$myrow["id"]."</td>";
		echo "<td align=\"center\">".$temprow["programmname"]." [".$temprow["language"]."]</td>";
		echo "<td align=\"center\">".$myrow["sitename"]."</td>";
		echo "<td align=\"center\"><a class=\"listlink\" href=\"".$myrow["prot"]."://".$myrow["url"]."\" target=\"_blank\">".$myrow["prot"]."://".$myrow["url"]."</a></td>";
		echo "<td align=\"center\">";
		if($myrow["publish"]==1)
			echo "<img src=\"gfx/checked.gif\" border=\"0\" align=\"absmiddle\">";
		else
			echo "<img src=\"gfx/unchecked.gif\" border=\"0\" align=\"absmiddle\">";
		echo "</td><td>";
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$temprow["prognr"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = mysql_query($modsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(($modrow = mysql_fetch_array($modresult)) || ($admin_rights > 2))
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_id=$act_id&lang=$lang");
				if($admdelconfirm==1)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_reference #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_id=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a> ";
				if($myrow["approved"]==0)
				{
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=approve&input_id=$act_id&lang=$lang")."\">";
					echo "<img src=\"gfx/go.gif\" border=\"0\" title=\"$l_approve\" alt=\"$l_approve\"></a> ";
				}
			}
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_id=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 1)
{
?>
<table class="filterbox" align="center" width="50%" border="0" cellspacing="0" cellpadding="1" valign="top">
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
	if($admstorefilter==1)
		echo "<input type=\"hidden\" name=\"dostorefilter\" value=\"1\">";
	if($admin_rights<3)
		$sql1 = "select prog.* from ".$tableprefix."_programm prog, ".$tableprefix."_programm_admins pa where prog.prognr = pa.prognr and pa.usernr=$act_usernr order by prog.prognr";
	else
		$sql1 = "select prog.* from ".$tableprefix."_programm prog order by prog.prognr";
	if(!$result1 = mysql_query($sql1, $db))
		die("Could not connect to the database (3).".mysql_error());
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1" <?php if($prognr==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<?php
	do {
		$progname=htmlentities($temprow["programmname"]);
		$proglang=$temprow["language"];
		echo "<option value=\"".$temprow["prognr"]."\"";
		if(isset($prognr))
		{
			if($prognr==$temprow["prognr"])
				echo " selected";
		}
		echo ">";
		echo "$progname [$proglang]";
		echo "</option>";
	} while($temprow = mysql_fetch_array($result1));
?>
</select></td><td>&nbsp;</td></tr>
<?php
	}
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_statusfilter?>:</td>
<td align="left" width="30%"><select name="filterstat">
<option value="-1" <?php if($filterstat==-1) echo "selected"?>><?php echo $l_nofilter?></option>
<option value="0" <?php if($filterstat==0) echo "selected"?>><?php echo $l_onlyapproved?></option>
<option value="1" <?php if($filterstat==1) echo "selected"?>><?php echo $l_onlyunapproved?></option>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
}
}
include('trailer.php');
?>