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
$page_title=$l_todo;
$page="todo";
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
	$dateformat=$myrow["dateformat"];
else
	$dateformat="Y-m-d";
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
		if(psys_array_key_exists($admcookievals,"todo_prognr"))
			$prognr=$admcookievals["todo_prognr"];
		if(psys_array_key_exists($admcookievals,"todo_state"))
			$filterstate=$admcookievals["todo_state"];
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
	if($mode=="new")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newtodo?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td>
<?php
	if($admin_rights<3)
		$sql = "select pr.* from ".$tableprefix."_programm pr, ".$tableprefix."_programm_admins pa where pr.prognr = pa.prognr and pa.usernr=$act_usernr order by pr.prognr";
	else
		$sql = "select pr.* from ".$tableprefix."_programm pr order by pr.prognr";
	if(!$result = mysql_query($sql, $db)) {
		die("Could not connect to the database (3).");
	}
	if (!$temprow = mysql_fetch_array($result))
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
		echo "<option value=\"".$temprow["prognr"]."\">";
		echo htmlentities(stripslashes($temprow["programmname"]));
		echo " [";
		echo stripslashes($temprow["language"]);
		echo "]</option>";
	} while($temprow = mysql_fetch_array($result));
?>
</select>
<?php
	}
?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_text?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" name="inputtext" cols="50" rows="10"></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_state?>:</td>
<td><select name="state">
<?php
for($i=0;$i<count($l_todo_states);$i++)
{
	echo "<option value=\"$i\">".$l_todo_states[$i]."</option>";
}
?>
</select></td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_todolist</a></div>";
	}
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
		if($programm<0)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noprogramm</td></tr>";
			$errors=1;
		}
		if(!$inputtext)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_notext</td></tr>";
			$errors=1;
		}
		if(!isset($local_urlautoencode))
			$urlautoencode=0;
		else
			$urlautoencode=1;
		if(!isset($local_enablespcode))
			$enablespcode=0;
		else
			$enablespcode=1;
		if($errors==0)
		{
			if(isset($preview))
			{
				$displaytext=$inputtext;
				if($urlautoencode==1)
					$displaytext = make_clickable($displaytext);
				if($enablespcode==1)
					$displaytext = bbencode($displaytext);
				$displaytext = htmlentities($displaytext);
				$displaytext = str_replace("\n", "<BR>", $displaytext);
				$displaytext = undo_htmlspecialchars($displaytext);
				$tempsql = "select * from ".$tableprefix."_programm where prognr=$programm";
				if(!$tempresult = mysql_query($tempsql, $db))
					die("tr class=\"errorrow\" align=\"center\"><td>Unable to connect to database.");
				if(!$temprow=mysql_fetch_array($tempresult))
				{
					$progname=$l_undefined;
					$proglang=$l_undefined;
				}
				else
				{
					$progname=$temprow["programmname"];
					$proglang=$temprow["language"];
				}
				$displaydate=date($dateformat);
				switch($state)
				{
					case 0:
						$stateclass = "todoplanned";
						break;
					case 1:
						$stateclass = "todowip";
						break;
					case 2:
						$stateclass = "tododeffered";
						break;
					default:
						$stateclass = "todounknown";
						break;
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newtodo?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td width="70%"><?php echo $progname." [".$proglang."]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><?php echo $displaytext?><input type="hidden" name="inputtext" value="<?php echo $inputtext?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_state?>:</td>
<td class="<?php echo $stateclass?>"><?php echo $l_todo_states[$state]?></td></tr>
<input type="hidden" name="state" value="<?php echo $state?>">
<tr class="displayrow"><td align="right"><?php echo $l_processor?>:</td>
<td><?php echo $userdata["username"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_enterdate?>:</td>
<td><?php echo $displaydate?></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="add">
</td></tr></form></table></td></tr></table>
<?php
				}
				else
				{
					$actdate = date("Y-m-d");
					if($urlautoencode==1)
						$inputtext = make_clickable($inputtext);
					if($enablespcode==1)
						$inputtext = bbencode($inputtext);
					$inputtext = htmlentities($inputtext);
					$inputtext = str_replace("\n", "<BR>", $inputtext);
					$inputtext=addslashes($inputtext);
					$sql = "INSERT INTO ".$tableprefix."_todo (programm, editor, lastedited, state, text) ";
					$sql .="values ($programm, '".$userdata["usernr"]."', '$actdate', $state, '$inputtext')";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to connect to database.");
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "$l_todoadded";
					echo "</td></tr></table></td></tr></table>";
					echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newtodo</a></div>";
					echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_todolist</a></div>";
				}
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
		if($admin_rights < 1)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_todo where (todonr=$input_todonr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		list($year, $month, $day) = explode("-", $myrow["lastedited"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		$displaytext=stripslashes($myrow["text"]);
		$displaytext = undo_htmlspecialchars($displaytext);
		switch($myrow["state"])
		{
			case 0:
				$stateclass = "todoplanned";
				break;
			case 1:
				$stateclass = "todowip";
				break;
			case 2:
				$stateclass = "tododeffered";
				break;
			default:
				$stateclass = "todounknown";
				break;
		}
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaytodo?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]"?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><?php echo $displaytext?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_state?>:</td>
<td class="<?php echo $stateclass?>"><?php echo $l_todo_states[$myrow["state"]]?></td></tr>
<?php
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_processor:</td>";
		$usersql="select * from ".$tableprefix."_admins where usernr=".$myrow["editor"];
		if(!$userresult = mysql_query($usersql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if(!$userrow = mysql_fetch_array($userresult))
			$editor=$l_undefined;
		else
			$editor=$userrow["username"];
		echo "<td>$editor</td></tr>";
		echo "<tr class=\"displayrow\"><td align=\"right\">$l_lastedited:</td>";
		echo "<td>$displaydate</td></tr>";
?>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_todolist</a>";
		if($admin_rights>1)
		{
			echo "<br>";
			$dellink=do_url_session("$act_script_url?lang=$lang&mode=delete&input_todonr=".$myrow["todonr"]);
			if($admdelconfirm==1)
				echo "<a class=\"listlink\" href=\"javascript:doconfirm('$l_confirmdel','$dellink')\">";
			else
				echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
			echo "$l_deleteentry</a>";
		}
		echo "</div>";
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
		$deleteSQL = "delete from ".$tableprefix."_todo where (todonr=$input_todonr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_todolist</a>";
		echo "</div>";
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
		$sql = "select * from ".$tableprefix."_todo where (todonr=$input_todonr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");

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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_edittodo?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_todonr" value="<?php echo $myrow["todonr"]?>">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<?php
	$sql1 = "select * from ".$tableprefix."_programm where prognr = ".$myrow["programm"];
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if (!$temprow = mysql_fetch_array($result1))
	{
		$progname=$l_undefined;
		$proglang=$l_undefined;
	}
	else
	{
		$progname=$temprow["programmname"];
		$proglang=$temprow["language"];
	}
?>
<td><?php echo "$progname [$proglang]"?></td></tr>
<?php
	$displaytext=stripslashes($myrow["text"]);
	$displaytext = str_replace("<BR>", "\n", $displaytext);
	$displaytext = undo_htmlspecialchars($displaytext);
	$displaytext = bbdecode($displaytext);
	$displaytext = undo_make_clickable($displaytext);
?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><textarea class="psysinput" name="inputtext" cols="50" rows="10"><?php echo $displaytext?></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_state?>:</td>
<td><select name="state">
<?php
for($i=0;$i<count($l_todo_states);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$myrow["state"])
		echo " selected";
	echo ">".$l_todo_states[$i]."</option>";
}
?>
</select></td>
<input type="hidden" name="programm" value="<?php echo $myrow["programm"]?>">
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="local_urlautoencode" value="1" <?php if($urlautoencode==1) echo "checked"?>> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="local_enablespcode" value="1" <?php if($enablespcode==1) echo "checked"?>> <?php echo $l_enablespcode?>
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">
&nbsp;&nbsp;<input class="psysbutton" type="submit" name="preview" value="<?php echo $l_preview?>"></td></tr>
</form>
</table></tr></td></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_todolist</a></div>";
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
		if(!$inputtext)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_notext</td></tr>";
			$errors=1;
		}
		if(!isset($local_urlautoencode))
			$urlautoencode=0;
		else
			$urlautoencode=1;
		if(!isset($local_enablespcode))
			$enablespcode=0;
		else
			$enablespcode=1;
		if($errors==0)
		{
			if(isset($preview))
			{
				$displaytext=$inputtext;
				if($urlautoencode==1)
					$displaytext = make_clickable($displaytext);
				if($enablespcode==1)
					$displaytext = bbencode($displaytext);
				$displaytext = htmlentities($displaytext);
				$displaytext = str_replace("\n", "<BR>", $displaytext);
				$displaytext = undo_htmlspecialchars($displaytext);
				$sql = "select * from ".$tableprefix."_todo where (todonr=$input_todonr)";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if (!$myrow = mysql_fetch_array($result))
					die("<tr class=\"errorrow\"><td>no such entry");
				$tempsql = "select * from ".$tableprefix."_programm where prognr=$programm";
				if(!$tempresult = mysql_query($tempsql, $db))
					die("Unable to connect to database.");
				if(!$temprow=mysql_fetch_array($tempresult))
				{
					$progname=$l_undefined;
					$proglang=$l_undefined;
				}
				else
				{
					$progname=$temprow["programmname"];
					$proglang=$temprow["language"];
				}
				$displaydate=date($dateformat);
				switch($state)
				{
					case 0:
						$stateclass = "todoplanned";
						break;
					case 1:
						$stateclass = "todowip";
						break;
					case 2:
						$stateclass = "tododeffered";
						break;
					default:
						$stateclass = "todounknown";
						break;
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_edittodo?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td width="70%"><?php echo $progname." [".$proglang."]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_text?>:</td>
<td><?php echo $displaytext?><input type="hidden" name="inputtext" value="<?php echo $inputtext?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_state?>:</td>
<td class="<?php echo $stateclass?>"><?php echo $l_todo_states[$state]?></td></tr>
<input type="hidden" name="state" value="<?php echo $state?>">
<tr class="displayrow"><td align="right"><?php echo $l_processor?>:</td>
<td><?php echo $userdata["username"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_lastedited?>:</td>
<td><?php echo $displaydate?></td></tr>
<?php
if(isset($local_urlautoencode))
	echo "<input type=\"hidden\" name=\"local_urlautoencode\" value=\"1\">";
if(isset($local_enablespcode))
	echo "<input type=\"hidden\" name=\"local_enablespcode\" value=\"1\">";
?>
<tr class="actionrow"><td colspan="2" align="center">
<input class="psysbutton" type="submit" value="<?php echo $l_update?>">&nbsp;&nbsp;
<input class="psysbutton" type="button" value="<?php echo $l_back ?>" onclick="self.history.back();">
<input type="hidden" name="mode" value="update">
<input type="hidden" name="input_todonr" value="<?php echo $input_todonr?>">
</td></tr></form></table></td></tr></table>
<?php
				}
				else
				{
					$actdate = date("Y-m-d");
					if($urlautoencode==1)
						$inputtext = make_clickable($inputtext);
					if($enablespcode==1)
						$inputtext = bbencode($inputtext);
					$inputtext = htmlentities($inputtext);
					$inputtext = str_replace("\n", "<BR>", $inputtext);
					$inputtext=addslashes($inputtext);
					$sql = "UPDATE ".$tableprefix."_todo SET lastedited='$actdate', editor=".$userdata["usernr"].",";
					$sql .="state=$state, text='$inputtext'";
					if($state!=0)
						$sql.=", rating=0, ratingcount=0";
					$sql .=" WHERE (todonr = $input_todonr)";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update the database.");
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "$l_todoupdated";
					echo "</td></tr></table></td></tr></table>";
					echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_todolist</a></div>";
				}
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
	if($admin_rights>1)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newtodo?></a>
</td></tr></table></td></tr></table>
<?php
		if($topfilter==1)
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
			if(!$result1 = mysql_query($sql1, $db)) {
				die("Could not connect to the database (3).".mysql_error());
			}
			if ($temprow = mysql_fetch_array($result1))
			{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1"><?php echo $l_nofilter?></option>
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
<tr><td align="right" width="50%" valign="top"><?php echo $l_statefilter?>:</td>
<td align="left" width="30%"><select name="filterstate">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
			for($i=0;$i<count($l_todo_states);$i++)
			{
				echo "<option value=\"$i\"";
				if(isset($filterstate))
				{
					if($i==$filterstate)
						echo " selected";
				}
				echo ">".$l_todo_states[$i]."</option>";
			}
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
		}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
$sql = "select * from ".$tableprefix."_todo ";
$firstarg=true;
// Display list of actual bugentries
if(isset($prognr) && ($prognr>=0))
{
	$tempsql="select * from ".$tableprefix."_programm where prognr=$prognr";
	if(!$tempresult = mysql_query($tempsql, $db)) {
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	}
	if($temprow=mysql_fetch_array($tempresult))
		echo "<tr class=\"inforow\"><td colspan=\"5\" align=\"center\">$l_onlyprog: <i>".$temprow["programmname"]."</i></td></tr>";
	$sql.="where programm=$prognr ";
	$firstarg=false;
}
if(isset($filterstate) && ($filterstate >=0))
{
	if($firstarg)
	{
		$sql.="where ";
		$firstarg=false;
	}
	else
		$sql.="and ";
	$sql.= "state=$filterstate ";
}
$sql.="order by programm asc, state asc, lastedited desc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
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
	echo "<td align=\"center\" width=\"20%\"><b>$l_lastedited</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_state</b></td>";
	echo "<td align=\"center\" width=\"25%\"><b>$l_rating</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = mysql_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		$act_id=$myrow["todonr"];
		if($myrow["lastedited"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["todonr"]."</td>";
		list($year, $month, $day) = explode("-", $myrow["lastedited"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo "<td align=\"center\">".$temprow["programmname"]." [".$temprow["language"]."]</td>";
		echo "<td align=\"center\">".$displaydate."</td>";
		switch($myrow["state"])
		{
			case 0:
				$stateclass = "todoplanned";
				break;
			case 1:
				$stateclass = "todowip";
				break;
			case 2:
				$stateclass = "tododeffered";
				break;
			default:
				$stateclass = "todounknown";
				break;
		}
		echo "<td align=\"center\" class=\"$stateclass\">".$l_todo_states[$myrow["state"]]."</td>";
		echo "<td align=\"center\">";
		$rating=$myrow["rating"];
		$ratingcount=$myrow["ratingcount"];
		if($ratingcount>0)
		{
			echo $l_ratings[round($rating/$ratingcount,2)]."<br>";
			echo round($rating/$ratingcount,2);
			echo " ($ratingcount)";
		}
		else
			echo "--";
		echo "</td><td>";
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$temprow["prognr"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = mysql_query($modsql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(($modrow = mysql_fetch_array($modresult)) || ($admin_rights > 2))
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_todonr=$act_id&lang=$lang");
				if($admdelconfirm==1)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_todo #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" title=\"$l_delete\" alt=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_todonr=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
			}
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_todonr=$act_id&lang=$lang")."\">";
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
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if ($temprow = mysql_fetch_array($result1))
	{
?>
<tr><td align="right" width="50%" valign="top"><?php echo $l_progfilter?>:</td>
<td align="left" width="30%"><select name="prognr">
<option value="-1"><?php echo $l_nofilter?></option>
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
<tr><td align="right" width="50%" valign="top"><?php echo $l_statefilter?>:</td>
<td align="left" width="30%"><select name="filterstate">
<option value="-1"><?php echo $l_nofilter?></option>
<?php
for($i=0;$i<count($l_todo_states);$i++)
{
	echo "<option value=\"$i\"";
	if(isset($filterstate))
	{
		if($i==$filterstate)
			echo " selected";
	}
	echo ">".$l_todo_states[$i]."</option>";
}
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
}
if($admin_rights > 1)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newtodo?></a></div>
<?php
}
}
include('trailer.php');
?>