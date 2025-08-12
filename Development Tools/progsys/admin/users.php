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
$page_title=$l_useradmin_title;
require('./heading.php');
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$nofreemailer=$myrow["nofreemailer"];
	$dateformat=$myrow["dateformat"];
	$dateformat.=" H:i:s";
	$watchlogins=$myrow["watchlogins"];
}
else
{
	$nofreemailer=0;
	$dateformat="Y-m-d H:i:s";
	$watchlogins=1;
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
		if(($admin_rights < 3) && ($input_usernr!=$userdata["usernr"]))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_admins where (usernr=$input_usernr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		list($mydate,$mytime)=explode(" ",$myrow["lastlogin"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><?php echo $myrow["username"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_realname?>:</td><td><?php echo $myrow["realname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_language?>:</td><td><?php echo $myrow["language"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_rights?>:</td><td>
<?php
	echo $l_admin_rights[$myrow["rights"]];
?>
</td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_lastlogin?>:</td><td><?php echo $displaydate?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_userlist</a></div>";
	}
	// Page called with some special mode
	if($mode=="newuser")
	{
		if($admin_rights < 3)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering userdata
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_newuser?></b></td></tr>
<form method="post" action="users.php"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="psysinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_realname?>:</td><td><input class="psysinput" type="text" name="realname" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td><td><input class="psysinput" type="text" name="email" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="psysinput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="psysinput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo language_select($lang,"adminlang","../language")?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="pwlocked" value="1"> <?php echo $l_pwlocked?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="entrylocked" value="1"> <?php echo $l_entrylocked?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_rights?>:</td><td>
<select name="rights">
<?php
for($i = 0; $i< count($l_admin_rights); $i++)
	echo "<option value=\"$i\">".$l_admin_rights[$i]."</option>";
?>
</select></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="psysbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_userlist?></a></div>
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
		// Add new user to database
		$errors=0;
		if(isset($pwlocked))
			$lockpw=1;
		else
			$lockpw=0;
		if(isset($entrylocked))
			$lockentry=1;
		else
			$lockentry=0;
		if(!$username)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nousername</td></tr>";
			$errors=1;
		}
		if(!$realname)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_norealname</td></tr>";
			$errors=1;
		}
		if(!$password || !$password2)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nopassword</td></tr>";
			$errors=1;
		}
		if($password2!=$password)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_passwordmismatch</td></tr>";
			$errors=1;
		}
		if($email)
		{
			if(!validate_email($email))
			{
				echo "<tr class=\"errorrow\" align=\"center\"><td>";
				echo "$l_invalidemail</td></tr>";
				$errors=1;
			}
			else if($nofreemailer==1)
			{
				if(forbidden_freemailer($email, $db))
				{
					echo "<tr class=\"errorrow\" align=\"center\"><td>";
					echo "$l_forbidden_freemailer</td></tr>";
					$errors=1;
				}
			}

		}
		$username=addslashes(strtolower($username));
		$sql = "select * from ".$tableprefix."_admins where username='$username'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if($row=mysql_fetch_array($result))
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_username_exists</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$password=md5($password);
			if(!isset($email))
				$email="";
			$sql = "INSERT INTO ".$tableprefix."_admins (username, password, email, rights, language, realname, lockpw, lockentry) ";
			$sql .="VALUES ('$username', '$password', '$email', $rights, '$adminlang', '$realname', $lockpw, $lockentry)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add user to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$username</i> $l_useradded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_userlist</a></div>";
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
		if($input_usernr==$userdata["usernr"])
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nodelactual<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_userlist</a></div>";
		}
		else
		{
			$deleteSQL = "delete from ".$tableprefix."_admins where (usernr=$input_usernr)";
			$success = mysql_query($deleteSQL);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$input_username</i> $l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_userlist</a></div>";
		}
	}
	if($mode=="edit")
	{
		if(($admin_rights < 3)  && ($input_usernr!=$userdata["usernr"]))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$sql = "select * from ".$tableprefix."_admins where (usernr=$input_usernr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		if(($admin_rights<3) && ($myrow["lockentry"]==1))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		list($mydate,$mytime)=explode(" ",$myrow["lastlogin"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editadmins?></b></td></tr>
<form method="post" action="users.php"><input type="hidden" name="lang" value="<?php echo $lang?>">
<?php
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_usernr" value="<?php echo $myrow["usernr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="psysinput" type="text" name="username" size="40" maxlength="80" value="<?php echo $myrow["username"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_realname?>:</td><td><input class="psysinput" type="text" name="realname" size="40" maxlength="240" value="<?php echo $myrow["realname"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td><td><input class="psysinput" type="text" name="email" size="40" maxlength="80" value="<?php echo $myrow["email"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo language_select($myrow["language"],"adminlang","../language")?></td></tr>
<?php
	if(($admin_rights > 2) || ($myrow["lockpw"]==0))
	{
?>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="psysinput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="psysinput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<?php
	}
	if($admin_rights > 2)
	{
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="pwlocked" value="1" <?php if ($myrow["lockpw"]==1) echo "checked";?>> <?php echo $l_pwlocked?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="entrylocked" value="1" <?php if ($myrow["lockentry"]==1) echo "checked";?>> <?php echo $l_entrylocked?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_rights?>:</td><td>
<select name="rights">
<?php
for($i = 0; $i< count($l_admin_rights); $i++)
{
	echo "<option value=\"$i\"";
	if($i==$myrow["rights"])
		echo " selected";
	echo ">".$l_admin_rights[$i]."</option>";
}
?>
</select>
</td></tr>
<?php
	}
?>
<tr class="displayrow"><td align="right"><?php echo $l_lastlogin?>:</td><td><?php echo $displaydate?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="psysbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?lang=$lang")?>"><?php echo $l_userlist?></a></div>
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
		if($admin_rights < 3)
		{
			$sql = "select * from ".$tableprefix."_admins where usernr=$input_usernr";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if (!$myrow = faqe_db_fetch_array($result))
				die("<tr class=\"errorrow\"><td>no such entry");
			if($myrow["lockentry"]==1)
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
		$errors=0;
		if(!$username)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_nousername</td></tr>";
			$errors=1;
		}
		if(!$realname)
		{
			echo "<tr class=\"errorrow\" align=\"center\"><td>";
			echo "$l_norealname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$username=addslashes(strtolower($username));
			$sql = "UPDATE ".$tableprefix."_admins SET realname='$realname', language='$adminlang', username='$username', email='$email', rights=$rights";
			if($password)
			{
				if(!$password2 || ($password2!=$password))
				{
					echo "<tr class=\"errorrow\" align=\"center\"><td>";
					echo "$l_passwordmismatch</td></tr>";
					$errors=1;
				}
				$password=md5($password);
				$sql .=", password='$password'";
			}
			if($admin_rights>2)
			{
				if(isset($pwlocked))
					$lockpw=1;
				else
					$lockpw=0;
				if(isset($entrylocked))
					$lockentry=1;
				else
					$lockentry=0;
				$sql .=", lockpw=$lockpw, lockentry=$lockentry";
			}
			$sql .=" WHERE (usernr = $input_usernr)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_userupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?lang=$lang")."\">$l_userlist</a></div>";
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
$sql = "select * from ".$tableprefix."_admins order by rights desc, username";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
?>
<tr class="rowheadings">
<td align="center"><b><?php echo $l_username?></b></td>
<td align="center"><b><?php echo $l_rights?></b></td>
<?php
if(($watchlogins==1) && ($admin_rights>1))
{
?>
<td align="center"><b><?php echo $l_logins?></b></td>
<?php
}
?>
<td>&nbsp;</td></tr>
<?php
if (!$myrow = mysql_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"4\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	do {
		$act_id=$myrow["usernr"];
		if(($admin_rights > 2) || ($myrow["username"]==$userdata["username"]))
		{
			echo "<tr class=\"displayrow\">";
			echo "<td width=\"40%\">".$myrow["username"]."</td>";
			echo "<td width=\"30%\" align=\"center\">";
			echo $l_admin_rights[$myrow["rights"]];
			echo "</td>";
			if(($watchlogins==1) && ($admin_rights>1))
			{
				$iplog_sql="select count(lognr) from ".$tableprefix."_iplog where (usernr=$act_id)";
				if(!$iplog_result = mysql_query($iplog_sql, $db))
   					die("Could not connect to the database.");
				if ($iplog_row = mysql_fetch_array($iplog_result))
					$loglistcount=$iplog_row["count(lognr)"];
				else
					$loglistcount=0;
				echo "<td align=\"center\">";
				echo $loglistcount;
				echo "</td>";
			}
			echo "<td>";
			if($admin_rights > 2)
			{
				$dellink=do_url_session("$act_script_url?mode=delete&input_usernr=$act_id&lang=$lang&input_username=".urlencode($myrow["username"]));
				if($admdelconfirm==1)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('".$l_user.": ".$myrow["username"]."','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_usernr=$act_id")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_usernr=$act_id&lang=$lang")."\">";
				echo "<img src=\"gfx/view.gif\" border=\"0\" alt=\"$l_display\" title=\"$l_display\"></a>";
				if($watchlogins==1)
				{
					if($loglistcount>0)
					{
						echo " <a class=\"listlink\" href=\"".do_url_session("loglist.php?lang=$lang&input_usernr=$act_id&username=".urlencode($myrow["username"]))."\">";
						echo "<img src=\"gfx/list.gif\" border=\"0\" alt=\"$l_loginlist\" title=\"$l_loginlist\"</a>";
						echo " <a class=\"listlink\" href=\"".do_url_session("loglist.php?lang=$lang&input_usernr=$act_id&mode=clear&username=".urlencode($myrow["username"]))."\">";
						echo "<img src=\"gfx/clear.gif\" border=\"0\" alt=\"$l_clearloginlist\" title=\"$l_clearloginlist\"</a>";
					}
				}
			}
			else
			{
				if(($admin_rights > 0) && ($myrow["usernr"]==$userdata["usernr"]) && ($myrow["lockentry"]==0))
				{
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&lang=$lang&input_usernr=$act_id")."\">";
					echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>";
				}
				else if($myrow["usernr"]==$userdata["usernr"])
				{
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_usernr=$act_id&lang=$lang")."\">";
					echo "<img src=\"gfx/view.gif\" border=\"0\" alt=\"$l_display\" title=\"$l_display\"></a>";
				}
			}
		}
		echo "</td></tr>";
   } while($myrow = mysql_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 2)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=newuser&lang=$lang")?>"><?php echo $l_newuser?></a></div>
<?php
}
}
include('trailer.php');
?>