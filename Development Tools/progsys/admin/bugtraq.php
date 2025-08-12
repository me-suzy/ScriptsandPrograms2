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
require('auth.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
$page_title=$l_bugtracking;
$page="bugtraq";
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
		if(psys_array_key_exists($admcookievals,"bugs_prognr"))
			$prognr=$admcookievals["bugs_prognr"];
		if(psys_array_key_exists($admcookievals,"bugs_state"))
			$filterstate=$admcookievals["bugs_state"];
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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newbugreport?></b></td></tr>
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
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_version?>:</td>
<td><input class="psysinput" type="text" name="usedversion" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_bugreport?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" name="bugtext" cols="50" rows="10"></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_state?>:</td>
<td><select name="state">
<?php
for($i=0;$i<count($l_states);$i++)
{
	echo "<option value=\"$i\">".$l_states[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fixversion?>:</td>
<td><input class="psysinput" type="text" name="fixversion" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_fix?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" name="fixtext" cols="50" rows="10"></textarea></td></tr>
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
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
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
		if(!$usedversion)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noversion</td></tr>";
			$errors=1;
		}
		if(!$bugtext)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nobugreport</td></tr>";
			$errors=1;
		}
		if(($state==3) && (!$fixversion))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nofixversion</td></tr>";
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
				$displaybugtext=$bugtext;
				if($urlautoencode==1)
					$displaybugtext = make_clickable($displaybugtext);
				if($enablespcode==1)
					$displaybugtext = bbencode($displaybugtext);
				$displaybugtext = htmlentities($displaybugtext);
				$displaybugtext = str_replace("\n", "<BR>", $displaybugtext);
				$displaybugtext = undo_htmlspecialchars($displaybugtext);
				$displayfixtext=$fixtext;
				if($urlautoencode==1)
					$displayfixtext = make_clickable($displayfixtext);
				if($enablespcode==1)
					$displayfixtext = bbencode($displayfixtext);
				$displayfixtext = htmlentities($displayfixtext);
				$displayfixtext = str_replace("\n", "<BR>", $displayfixtext);
				$displayfixtext = undo_htmlspecialchars($displayfixtext);
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
						$stateclass= "statenew";
						break;
					case 1:
						$stateclass="stateopen";
						break;
					case 2:
						$stateclass="statewip";
						break;
					case 3:
						$stateclass="stateclosed";
						break;
					case 4:
						$stateclass="statedeffered";
						break;
					default:
						$stateclass="stateunknown";
						break;
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newbugreport?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td width="70%"><?php echo $progname." [".$proglang."]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_usedversion?>:</td>
<td width="70%"><?php echo $usedversion?><input type="hidden" name="usedversion" value="<?php echo $usedversion?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_enterdate?>:</td>
<td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_entername?>:</td>
<td><?php echo $userdata["realname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td>
<td><?php echo $userdata["email"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td>
<td><?php echo get_userip()?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_bugreport?>:</td>
<td><?php echo $displaybugtext?><input type="hidden" name="bugtext" value="<?php echo $bugtext?>"></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_state?>:</td>
<td class="<?php echo $stateclass?>"><?php echo $l_states[$state]?></td></tr>
<input type="hidden" name="state" value="<?php echo $state?>">
<tr class="displayrow"><td align="right"><?php echo $l_processor?>:</td>
<td><?php echo $userdata["realname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_lastedited?>:</td>
<td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_fixversion?>:</td>
<td><?php echo $fixversion?></td></tr>
<input type="hidden" name="fixversion" value="<?php echo $fixversion?>">
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_fix?>:</td>
<td width="70%"><?php echo $displayfixtext?><input type="hidden" name="fixtext" value="<?php echo $fixtext?>"></td></tr>
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
<input type="hidden" name="input_entrynr" value="<?php echo $input_entrynr?>">
</td></tr></form></table></td></tr></table>
<?php
				}
				else
				{
					$actdate = date("Y-m-d");
					if($urlautoencode==1)
						$bugtext = make_clickable($bugtext);
					if($enablespcode==1)
						$bugtext = bbencode($bugtext);
					$bugtext = htmlentities($bugtext);
					$bugtext = str_replace("\n", "<BR>", $bugtext);
					$bugtext=addslashes($bugtext);
					if($urlautoencode==1)
						$fixtext = make_clickable($fixtext);
					if($enablespcode==1)
						$fixtext = bbencode($fixtext);
					$fixtext = htmlentities($fixtext);
					$fixtext = str_replace("\n", "<BR>", $fixtext);
					$fixtext=addslashes($fixtext);
					$sql = "INSERT INTO ".$tableprefix."_bugtraq (programm, custname, custmail, enterdate, processor, state, fixversion, lastedited, bugtext, fixtext, usedversion, enterip) ";
					$sql .="values ($programm, '".$userdata["realname"]."', '".$userdata["email"]."', '$actdate', ".$userdata["usernr"].", $state, '$fixversion', '$actdate', '$bugtext', '$fixtext', '$usedversion', '".get_userip()."')";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to connect to database.");
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "$l_bugreportadded";
					echo "</td></tr></table></td></tr></table>";
					echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=new&lang=$lang")."\">$l_newbugreport</a></div>";
					echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
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
		$sql = "select * from ".$tableprefix."_bugtraq where (bugnr=$input_bugnr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr bgcolor=\"#cccccc\"><td>no such entry");
		$tempsql="select * from ".$tableprefix."_programm where prognr=".$myrow["programm"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr class=\"errorrow\"><td>Database inconsitency error");
		list($year, $month, $day) = explode("-", $myrow["enterdate"]);
		if($month>0)
			$displaydate1=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate1="";
		list($year, $month, $day) = explode("-", $myrow["lastedited"]);
		if($month>0)
			$displaydate2=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate2="";
		$bugtext=stripslashes($myrow["bugtext"]);
		$bugtext = undo_htmlspecialchars($bugtext);
		switch($myrow["state"])
		{
			case 0:
				$stateclass= "statenew";
				break;
			case 1:
				$stateclass="stateopen";
				break;
			case 2:
				$stateclass="statewip";
				break;
			case 3:
				$stateclass="stateclosed";
				break;
			case 4:
				$stateclass="statedeffered";
				break;
			default:
				$stateclass="stateunknown";
				break;
		}
?>
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_displaybugreport?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td><?php echo stripslashes($temprow["programmname"])." [".$temprow["language"]."]"?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_version?>:</td>
<td><?php echo $myrow["usedversion"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_enterdate?>:</td>
<td><?php echo $displaydate1?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_entername?>:</td>
<td><?php echo $myrow["custname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td>
<td><?php echo $myrow["custmail"]?></td></tr>
<?php
if($admin_rights > 1)
{
?>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td>
<td><?php echo $myrow["enterip"]?></td></tr>
<?php
}
?>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_bugreport?>:</td>
<td><?php echo $bugtext?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_state?>:</td>
<td class="<?php echo $stateclass?>"><?php echo $l_states[$myrow["state"]]?></td></tr>
<?php
		if($myrow["processor"]!=0)
		{
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_processor:</td>";
			$usersql="select * from ".$tableprefix."_admins where usernr=".$myrow["processor"];
			if(!$userresult = mysql_query($usersql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$userrow = mysql_fetch_array($userresult))
				$processor=$l_undefined;
			else
				$processor=$userrow["realname"];
			echo "<td>$processor</td></tr>";
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_lastedited:</td>";
			echo "<td>$displaydate2</td></tr>";
		}
		if($myrow["fixversion"])
		{
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_fixversion:</td>";
			echo "<td>".$myrow["fixversion"]."</td></tr>";
		}
		if($myrow["fixtext"])
		{
			$fixtext=stripslashes($myrow["fixtext"]);
			$fixtext = undo_htmlspecialchars($fixtext);
			echo "<tr class=\"displayrow\"><td align=\"right\">$l_fix:</td>";
			echo "<td>".$fixtext."</td></tr>";
		}
?>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
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
		$deleteSQL = "delete from ".$tableprefix."_bugtraq where (bugnr=$input_bugnr)";
		$success = mysql_query($deleteSQL);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
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
		$sql = "select * from ".$tableprefix."_bugtraq where (bugnr=$input_bugnr)";
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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editbugreport?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="input_bugnr" value="<?php echo $myrow["bugnr"]?>">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<?php
	$sql1 = "select * from ".$tableprefix."_programm where prognr = ".$myrow["programm"];
	if(!$result1 = mysql_query($sql1, $db)) {
		die("Could not connect to the database (3).".mysql_error());
	}
	if (!$temprow = mysql_fetch_array($result1))
		$progname=$l_undefined;
	else
		$progname=$temprow["programmname"];
?>
<td><?php echo $progname?></td></tr>
<?php
	list($year, $month, $day) = explode("-", $myrow["enterdate"]);
	if($month>0)
		$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
	else
		$displaydate="";
	$bugtext=stripslashes($myrow["bugtext"]);
	$bugtext = undo_htmlspecialchars($bugtext);
	$fixtext=stripslashes($myrow["fixtext"]);
	$fixtext = str_replace("<BR>", "\n", $fixtext);
	$fixtext = undo_htmlspecialchars($fixtext);
	$fixtext = bbdecode($fixtext);
	$fixtext = undo_make_clickable($fixtext);
?>
<tr class="displayrow"><td align="right"><?php echo $l_version?>:</td>
<td><?php echo $myrow["usedversion"]?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_enterdate?>:</td>
<td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_entername?>:</td>
<td><?php echo $myrow["custname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td>
<td><?php echo $myrow["custmail"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td>
<td><?php echo $myrow["enterip"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_bugreport?>:</td>
<td><?php echo $bugtext?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_state?>:</td>
<td><select name="state">
<?php
for($i=0;$i<count($l_states);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$myrow["state"])
		echo " selected";
	echo ">".$l_states[$i]."</option>";
}
?>
</select></td>
<input type="hidden" name="programm" value="<?php echo $myrow["programm"]?>">
<tr class="inputrow"><td align="right"><?php echo $l_fixversion?>:</td>
<td><input class="psysinput" type="text" name="fixversion" value="<?php echo $myrow["fixversion"]?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_fix?>:<br>
<?php echo "<a class=\"listlink\" href=\"help/".$lang."/bbcode.html\" target=\"_blank\">$l_bbcodehelp</a>"?>
</td><td><textarea class="psysinput" name="fixtext" cols="50" rows="10"><?php echo $fixtext?></textarea></td></tr>
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
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
	}
	if($mode=="transfer")
	{
		if($admin_rights < 2)
		{
			echo "<tr bgcolor=\"#cccccc\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$errors=0;
		$oldprog=0;
		if(!isset($bugnr))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noentriesselected</td></tr>";
			$errors=1;
		}
		else
		{
			$bugnrs=array();
			while((list($null, $bug) = each($_POST["bugnr"])) && ($errors==0))
			{
				array_push($bugnrs, $bug);
				$sql = "select * from ".$tableprefix."_bugtraq where bugnr=$bug";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if(!$myrow=mysql_fetch_array($result))
				    die("<tr class=\"errorrow\"><td>$l_callingerror");
				if($oldprog>0)
				{
					if($myrow["programm"]!=$oldprog)
					{
						echo "<tr class=\"errorrow\" align=\"center\"><td>";
						echo "$l_progmixed</td></tr>";
						$errors=1;
					}
				}
				$oldprog=$myrow["programm"];
			}
		}
		if($errors==0)
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_transfer2changelog?></b></td></tr>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_transferprelude?></td></tr>
<?php
			$sql = "select * from ".$tableprefix."_changelog where programm=$oldprog order by versiondate desc";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($myrow=mysql_fetch_array($result))
			{
?>
<tr><td class="grouprow1" align="left" colspan="2"><b><?php echo $l_addtochangelog?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				for($i=0;$i<count($bugnrs);$i++)
				{
					echo "<input type=\"hidden\" name=\"bugnr[]\" value=\"".$bugnrs[$i]."\">\n";
				}
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="submode" value="append">
<tr><td class="inputrow" align="center" colspan="2">
<select name="changelog"><option value="-1">???</option>
<?php
				do{
					list($year, $month, $day) = explode("-", $myrow["versiondate"]);
					if($month>0)
						$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
					else
						$displaydate="";
					echo "<option value=\"".$myrow["entrynr"]."\">";
					$progversion="v".$myrow["version"];
					echo "$progversion ($displaydate)</option>";
				}while($myrow=mysql_fetch_array($result));
?>
</select></td></tr>
<input type="hidden" name="mode" value="dotransfer">
<tr><td class="actionrow" align="center" colspan="2"><input class="psysbutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
<?php
			}
?>
<tr><td class="grouprow1" align="left" colspan="2"><b><?php echo $l_newchangelog?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="submode" value="new">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_fixversion?>:</td>
<td><input class="psysinput" type="text" name="fixversion" size="20" maxlength="20"></td>
</tr>
<input type="hidden" name="mode" value="dotransfer">
<tr><td class="actionrow" align="center" colspan="2"><input class="psysbutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
<?php
			for($i=0;$i<count($bugnrs);$i++)
			{
				echo "<input type=\"hidden\" name=\"bugnr[]\" value=\"".$bugnrs[$i]."\">\n";
			}
?>
</form>
</table></tr></td></table>
<?php
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
	}
	if($mode=="dotransfer")
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
		$oldchangelogtext="";
		if(!isset($bugnr))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_noentriesselected</td></tr>";
			$errors=1;
		}
		if($submode=="append")
		{
			if($changelog<0)
			{
				echo "<tr class=\"errorrow\" align=\"center\"><td>";
				echo "$l_nochangelogselected</td></tr>";
				$errors=1;
			}
			else
			{
				$sql = "select * from ".$tableprefix."_changelog where entrynr=$changelog";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if(!$myrow=mysql_fetch_array($result))
				    die("<tr class=\"errorrow\"><td>$l_callingerror");
				$oldchangelogtext=$myrow["changes"];
				$fixversion=$myrow["version"];
			}
		}
		else
		{
			if(!$fixversion)
			{
				echo "<tr class=\"errorrow\" align=\"center\"><td>";
				echo "$l_nofixversion</td></tr>";
				$errors=1;
			}
		}
		if($errors==0)
		{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_transfer2changelog?></b></td></tr>
<?php
			$changetext="<!-- SPCode ulist Start --><UL><!-- SPCode --><LI>$l_bugsfixed ($l_fixedbugs: ";
			$first=1;
			$bugnrs=$_POST["bugnr"];
			asort($bugnrs);
			while(list($null, $bug) = each($bugnrs))
			{
				if($first==1)
				{
					$sql = "select * from ".$tableprefix."_bugtraq where bugnr=$bug";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
					if(!$myrow=mysql_fetch_array($result))
					    die("<tr class=\"errorrow\"><td>$l_callingerror");
					$prognr=$myrow["programm"];
					$first=0;
				}
				else
					$changetext.=", ";
				$changetext.="#".$bug;
				$sql = "update ".$tableprefix."_bugtraq set state=3, fixversion='$fixversion' where bugnr=$bug";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			}
			$changetext.=")</UL><!-- SPCode ulist End -->";
			$changetext = htmlentities($changetext);
			$changetext=addslashes($changetext);
			if($submode=="append")
			{
				$actdate = date("Y-m-d");
				$newchangelogtext=$oldchangelogtext.$changetext;
				$sql = "update ".$tableprefix."_changelog set versiondate='$actdate', changes='$newchangelogtext' where entrynr=$changelog";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			}
			else
			{
				$actdate = date("Y-m-d");
				$sql = "insert into ".$tableprefix."_changelog (version, versiondate, programm, changes) ";
				$sql .="values ('$fixversion', '$actdate', $prognr, '$changetext')";
				if(!$result = mysql_query($sql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			}
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_changelogadded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("changelog.php?lang=$lang")."\">$l_changeloglist</a></div>";
		}
		else
		{
			echo "<tr class=\"actionrow\" align=\"center\"><td>";
			echo "<a href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
		}
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
		if(($state==3) && (!$fixversion))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nofixversion</td></tr>";
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
				$displayfixtext=$fixtext;
				if($urlautoencode==1)
					$displayfixtext = make_clickable($displayfixtext);
				if($enablespcode==1)
					$displayfixtext = bbencode($displayfixtext);
				$displayfixtext = htmlentities($displayfixtext);
				$displayfixtext = str_replace("\n", "<BR>", $displayfixtext);
				$displayfixtext = undo_htmlspecialchars($displayfixtext);
				$sql = "select * from ".$tableprefix."_bugtraq where (bugnr=$input_bugnr)";
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
				list($year, $month, $day) = explode("-", $myrow["enterdate"]);
				if($month>0)
					$displaydate1=date($dateformat,mktime(0,0,0,$month,$day,$year));
				else
					$displaydate1="";
				$displaydate2=date($dateformat);
				$bugtext=stripslashes($myrow["bugtext"]);
				$bugtext = undo_htmlspecialchars($bugtext);
				switch($state)
				{
					case 0:
						$stateclass= "statenew";
						break;
					case 1:
						$stateclass="stateopen";
						break;
					case 2:
						$stateclass="statewip";
						break;
					case 3:
						$stateclass="stateclosed";
						break;
					case 4:
						$stateclass="statedeffered";
						break;
					default:
						$stateclass="stateunknown";
						break;
				}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_editbugreport?></b></td></tr>
<form method="post" action="<?php echo $act_script_url?>"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr><td class="inforow" align="center" colspan="2"><?php echo $l_previewprelude?>:</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_programm?>:</td>
<td width="70%"><?php echo $progname." [".$proglang."]"?><input type="hidden" name="programm" value="<?php echo $programm?>"></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_usedversion?>:</td>
<td width="70%"><?php echo $myrow["usedversion"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_enterdate?>:</td>
<td><?php echo $displaydate1?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_entername?>:</td>
<td><?php echo $myrow["custname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td>
<td><?php echo $myrow["custmail"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_ipadr?>:</td>
<td><?php echo $myrow["enterip"]?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_bugreport?>:</td>
<td><?php echo $bugtext?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_state?>:</td>
<td class="<?php echo $stateclass?>"><?php echo $l_states[$state]?></td></tr>
<input type="hidden" name="state" value="<?php echo $state?>">
<tr class="displayrow"><td align="right"><?php echo $l_processor?>:</td>
<td><?php echo $userdata["realname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_lastedited?>:</td>
<td><?php echo $displaydate2?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_fixversion?>:</td>
<td><?php echo $fixversion?></td></tr>
<input type="hidden" name="fixversion" value="<?php echo $fixversion?>">
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_fix?>:</td>
<td width="70%"><?php echo $displayfixtext?><input type="hidden" name="fixtext" value="<?php echo $fixtext?>"></td></tr>
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
<input type="hidden" name="input_bugnr" value="<?php echo $input_bugnr?>">
</td></tr></form></table></td></tr></table>
<?php
				}
				else
				{
					$actdate = date("Y-m-d");
					if($urlautoencode==1)
						$fixtext = make_clickable($fixtext);
					if($enablespcode==1)
						$fixtext = bbencode($fixtext);
					$fixtext = htmlentities($fixtext);
					$fixtext = str_replace("\n", "<BR>", $fixtext);
					$fixtext=addslashes($fixtext);
					$sql = "UPDATE ".$tableprefix."_bugtraq SET lastedited='$actdate', processor=".$userdata["usernr"].",";
					$sql .="state=$state, fixversion='$fixversion', fixtext='$fixtext' ";
					$sql .="WHERE (bugnr = $input_bugnr)";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update the database.");
					echo "<tr class=\"displayrow\" align=\"center\"><td>";
					echo "$l_bugreportupdated";
					echo "</td></tr></table></td></tr></table>";
					echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_buglist</a></div>";
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
<a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newbugreport?></a>
</td></tr>
</table></td></tr></table>
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
			if(!$result1 = mysql_query($sql1, $db))
				die("Could not connect to the database (3).".mysql_error());
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
		for($i=0;$i<count($l_states);$i++)
		{
			echo "<option value=\"$i\"";
			if(isset($filterstate))
			{
				if($i==$filterstate)
					echo " selected";
			}
			echo ">".$l_states[$i]."</option>";
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
$firstarg=true;
$sql = "select * from ".$tableprefix."_bugtraq ";
// Display list of actual bugentries
if(isset($prognr) && ($prognr>=0))
{
	$tempsql="select * from ".$tableprefix."_programm where prognr=$prognr";
	if(!$tempresult = mysql_query($tempsql, $db)) {
	    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	}
	if($temprow=mysql_fetch_array($tempresult))
		echo "<tr class=\"inforow\"><td colspan=\"7\" align=\"center\">$l_onlyprog: <i>".$temprow["programmname"]."</i></td></tr>";
	if($firstarg)
	{
		$firstarg=false;
		$sql.= "where programm=$prognr ";
	}
	else
		$sql.= "and programm=$prognr ";
}
if(isset($filterstate) && ($filterstate >=0))
{
	if($firstarg)
	{
		$sql.= "where state=$filterstate ";
		$firstarg=false;
	}
	else
		$sql.= "and state=$filterstate ";
}
$sql.="order by programm asc, usedversion desc, state asc, enterdate desc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database. ".mysql_error());
}
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	if($admin_rights>1)
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		echo "<input type=\"hidden\" name=\"lang\" value=\"$lang\">";
		echo "<input type=\"hidden\" name=\"mode\" value=\"transfer\">";
		echo "<tr class=\"rowheadings\">";
		echo "<td align=\"center\" width=\"2%\">&nbsp;</td>";
	}
	else
		echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"5%\"><b>#</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_programm</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_version</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_enterdate</b></td>";
	echo "<td align=\"center\" width=\"20%\"><b>$l_state</b></td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$tempsql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
		if(!$tempresult = mysql_query($tempsql, $db)) {
		    die("Could not connect to the database.");
		}
		if (!$temprow = mysql_fetch_array($tempresult))
			die("<tr bgcolor=\"#cccccc\"><td>Database inconsitency error");
		$act_id=$myrow["bugnr"];
		if($myrow["enterdate"]>$userdata["lastlogin"])
			echo "<tr class=\"displayrownew\">";
		else
			echo "<tr class=\"displayrow\">";
		if($admin_rights>1)
		{
			echo "<td align=\"center\"><input type=\"checkbox\" name=\"bugnr[]\" value=\"".$myrow["bugnr"]."\"></td>";
		}
		echo "<td align=\"center\">".$myrow["bugnr"]."</td>";
		list($year, $month, $day) = explode("-", $myrow["enterdate"]);
		if($month>0)
			$displaydate1=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate1="";
		list($year, $month, $day) = explode("-", $myrow["lastedited"]);
		if($month>0)
			$displaydate2=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate2="";
		echo "<td align=\"center\">".$temprow["programmname"]." [".$temprow["language"]."]</td>";
		echo "<td align=\"center\">".$myrow["usedversion"]."</td>";
		echo "<td align=\"center\">".$displaydate1."</td>";
		switch($myrow["state"])
		{
			case 0:
				$stateclass= "statenew";
				break;
			case 1:
				$stateclass="stateopen";
				break;
			case 2:
				$stateclass="statewip";
				break;
			case 3:
				$stateclass="stateclosed";
				break;
			case 4:
				$stateclass="statedeffered";
				break;
			default:
				$stateclass="stateunknown";
				break;
		}
		echo "<td align=\"center\" class=\"$stateclass\">".$l_states[$myrow["state"]]."</td>";
		echo "<td>";
		if($admin_rights > 1)
		{
			$modsql="select * from ".$tableprefix."_programm_admins where (prognr=".$temprow["prognr"].") and (usernr=".$userdata["usernr"].")";
			if(!$modresult = mysql_query($modsql, $db))
			    die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.");
			if(($modrow = mysql_fetch_array($modresult)) || ($admin_rights > 2))
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_bugnr=$act_id&lang=$lang");
				if($admdelconfirm==1)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_bugreport #$act_id','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&input_bugnr=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a> ";
			}
		}
		echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_bugnr=$act_id&lang=$lang")."\">";
		echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   if($admin_rights>1)
   {
   		echo "<tr class=\"actionrow\"><td colspan=\"7\" align=\"center\">";
   		echo $l_transfer2changelog;
   		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"psysbutton\" type=\"submit\" value=\"$l_ok\"></td></tr></form>";
   }
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
for($i=0;$i<count($l_states);$i++)
{
	echo "<option value=\"$i\"";
	if(isset($filterstate))
	{
		if($i==$filterstate)
			echo " selected";
	}
	echo ">".$l_states[$i]."</option>";
}
?>
</select></td><td align="left"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></td></tr>
</form></table>
<?php
}
if($admin_rights > 1)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=new&lang=$lang")?>"><?php echo $l_newbugreport?></a></div>
<?php
}
}
include('trailer.php');
?>