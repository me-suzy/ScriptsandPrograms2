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
$page_title=$l_useradmin_title;
$page="users";
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
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
		if(faqe_array_key_exists($admcookievals,"adm_sorting"))
			$sorting=$admcookievals["adm_sorting"];
	}
}
if(!isset($sorting))
	$sorting=11;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
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
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		list($mydate,$mytime)=explode(" ",$myrow["lastlogin"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($dateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_displayadmins?></b></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><?php echo $myrow["username"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_rights?>:</td><td>
<?php
	echo $l_admin_rights[$myrow["rights"]];
	$signaturetext=$myrow["signature"];
	$signaturetext=display_encoded($signaturetext);
	$signaturetext=str_replace("\n","<BR>",$signaturetext);
?>
</td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_signature?>:</td><td><?php echo $signaturetext?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_lastlogin?>:</td><td><?php echo $displaydate?></td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
	}
	// Page called with some special mode
	if($mode=="newuser")
	{
		if($admin_rights < 4)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
		// Display empty form for entering userdata
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newuser?></b></td></tr>
<form name="inputform" onsubmit="return checkform1()" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td><td><input class="faqeinput" type="text" name="email" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="emailhide" value="1"> <?php echo $l_hideemail?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="faqeinput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="faqeinput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<?php
		if(!$enable_htaccess)
		{
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="pwlocked" value="1"> <?php echo $l_pwlocked?></td></tr>
<?php
		}
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="entrylocked" value="1"> <?php echo $l_entrylocked?></td></tr>
<tr bgcolor="#cccccc"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo language_select($act_lang,"inputlang","./language/")?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_rights?>:</td><td>
<select name="rights">
<?php
for($i = 0; $i< count($l_admin_rights); $i++)
	echo "<option value=\"$i\">".$l_admin_rights[$i]."</option>";
?>
</select></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_signature?>:</td><td>
<textarea class="faqeinput" name="signature" rows="5" cols="40"></textarea></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="faqebutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userlist?></a></div>
<?php
	}
	if($mode=="add")
	{
		if($admin_rights < 4)
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
		if(isset($emailhide))
			$hideemail=1;
		else
			$hideemail=0;
		if(!$username)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nousername</td></tr>";
			$errors=1;
		}
		if(!$password || !$password2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nopassword</td></tr>";
			$errors=1;
		} else if($password2!=$password)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_passwordmismatch</td></tr>";
			$errors=1;
		}
		if($email)
		{
			if(!validate_email($email))
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				echo "$l_invalidemail</td></tr>";
				$errors=1;
			}
			else if($nofreemailer==1)
			{
				if(forbidden_freemailer($email, $db))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
					echo "$l_forbidden_freemailer</td></tr>";
					$errors=1;
				}
			}

		}
		$username=addslashes(strtolower($username));
		$sql = "select * from ".$tableprefix."_admins where username='$username'";
		if(!$result = faqe_db_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if($row=faqe_db_fetch_array($result))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_username_exists</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$password=md5($password);
			if(!isset($email))
				$email="";
			$sql = "INSERT INTO ".$tableprefix."_admins (username, password, email, rights, signature, language, lockpw, hideemail, lockentry) ";
			$sql .="VALUES ('$username', '$password', '$email', $rights, '$signature', '$inputlang', $lockpw, $hideemail, $lockentry)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add user to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$username</i> $l_useradded";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?mode=newuser&$langvar=$act_lang")."\">$l_newuser</a></div>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
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
		if($admin_rights < 4)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if($input_usernr==$userdata["usernr"])
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nodelactual<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
		}
		else
		{
			$deleteSQL = "delete from ".$tableprefix."_programm_admins where (usernr=$input_usernr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_category_admins where (usernr=$input_usernr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			$deleteSQL = "delete from ".$tableprefix."_admins where (usernr=$input_usernr)";
			$success = faqe_db_query($deleteSQL,$db);
			if (!$success)
				die("<tr class=\"errorrow\"><td>$l_cantdelete.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$input_username</i> $l_deleted<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
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
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = faqe_db_fetch_array($result))
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
		$signaturetext=$myrow["signature"];
		$signaturetext=display_encoded($signaturetext);
?>
<tr class="headingrow"><td align="center" colspan="2"><b><?php echo $l_editadmins?></b></td></tr>
<form name="inputform" onsubmit="return checkform2()" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<input type="hidden" name="input_usernr" value="<?php echo $myrow["usernr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80" value="<?php echo $myrow["username"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td><td><input class="faqeinput" type="text" name="email" size="40" maxlength="80" value="<?php echo $myrow["email"]?>"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="emailhide" value="1" <?php if ($myrow["hideemail"]==1) echo "checked";?>> <?php echo $l_hideemail?></td></tr>
<?php
	if((($admin_rights > 2) || ($myrow["lockpw"]==0)) && !$enable_htaccess)
	{
?>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="faqeinput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="faqeinput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<?php
	}
	if($admin_rights > 2)
	{
		if(!$enable_htaccess)
		{
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="pwlocked" value="1" <?php if ($myrow["lockpw"]==1) echo "checked";?>> <?php echo $l_pwlocked?></td></tr>
<?php
		}
	}
	if($admin_rights > 3)
	{
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="entrylocked" value="1" <?php if ($myrow["lockentry"]==1) echo "checked";?>> <?php echo $l_entrylocked?></td></tr>
<?php
	}
	else
	{
		echo "<tr class=\"displayrow\"><td>&nbsp;</td><td>";
		if($myrow["lockentry"]==1)
			$stateimg="checked.gif";
		else
			$stateimg="unchecked.gif";
		echo "<img class=\"checkbox\" src=\"gfx/".$stateimg."\" border=\"0\" align=\"middle\">";
		echo " $l_entrylocked</td></tr>";
	}
?>
<tr class="inputrow"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo language_select($myrow["language"],"inputlang","./language/")?>
</td></tr>
<?php
	if($admin_rights > 3)
	{
?>
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
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_signature?>:</td><td><textarea class="faqeinput" name="signature" cols="40" rows="5"><?php echo $signaturetext?></textarea></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_lastlogin?>:</td><td><?php echo $displaydate?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="faqebutton" type="submit" value="<?php echo $l_update?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userlist?></a></div>
<?php
	}
	if($mode=="update")
	{
		if(($admin_rights < 3)  && ($input_usernr!=$userdata["usernr"]))
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
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nousername</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			if(isset($emailhide))
				$hideemail=1;
			else
				$hideemail=0;
			$username=addslashes(strtolower($username));
			$sql = "UPDATE ".$tableprefix."_admins SET autopin=0, signature='$signature', username='$username', email='$email', language='$inputlang'";
			if(isset($rights))
				$sql.=", rights=$rights";
			if($password)
			{
				if(!$password2 || ($password2!=$password))
				{
					echo "<tr class=\"errorrow\"><td align=\"center\">";
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
				$sql .=", lockpw=$lockpw";
			}
			if($admin_rights>3)
			{
				if(isset($entrylocked))
					$lockentry=1;
				else
					$lockentry=0;
				$sql.=", lockentry=$lockentry";
			}
			$sql .=", hideemail=$hideemail";
			$sql .=" WHERE (usernr = $input_usernr)";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to update the database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "$l_userupdated";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
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
	if($admin_rights>3)
	{
?>
<tr class="actionrow"><td colspan="6" align="center">
<a href="<?php echo do_url_session("$act_script_url?mode=newuser&$langvar=$act_lang")?>"><?php echo $l_newuser?></a>
</table></td></tr></table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
	}
// Display list of actual users
$sql = "select * from ".$tableprefix."_admins ";
switch($sorting)
{
	case 12:
		$sql.="order by username desc";
		break;
	case 21:
		$sql.="order by rights asc, username asc";
		break;
	case 22:
		$sql.="order by rights desc, username asc";
		break;
	default:
		$sql.="order by username asc";
		break;
}
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
$maxsortcol=2;
$baseurl="$act_script_url?$langvar=$act_lang";
if($admstorefaqfilters==1)
	$baseurl.="&storefaqfilter=1";
echo "<tr class=\"rowheadings\">";
echo "<td align=\"center\">";
$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_username</b></a>";
echo getSortMarker($sorting, 1, $maxsortcol);
echo "</td>";
echo "<td align=\"center\">";
$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
echo "<b>$l_rights</b></a>";
echo getSortMarker($sorting, 2, $maxsortcol);
echo "</td>";
if(($watchlogins==1) && ($admin_rights>1) && (!$enable_htaccess))
{
?>
<td class="rowheadings" align="center"><b><?php echo $l_logins?></b></td>
<?php
}
?>
<td>&nbsp;</td></tr>
<?php
if (!$myrow = faqe_db_fetch_array($result))
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
			if(($watchlogins==1) && ($admin_rights>1) && (!$enable_htaccess))
			{
				$iplog_sql="select count(lognr) from ".$tableprefix."_iplog where (usernr=$act_id)";
				if(!$iplog_result = faqe_db_query($iplog_sql, $db))
   					die("Could not connect to the database.");
				if ($iplog_row = faqe_db_fetch_array($iplog_result))
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
				if($admin_rights > 3)
				{
					echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=delete&input_usernr=$act_id&$langvar=$act_lang&input_username=".urlencode($myrow["username"]))."\">";
					echo "<img src=\"gfx/delete.gif\" alt=\"$l_delete\" title=\"$l_delete\" border=\"0\"></a> ";
				}
				echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_usernr=$act_id")."\">";
				echo "<img src=\"gfx/edit.gif\" alt=\"$l_edit\" title=\"$l_edit\" border=\"0\"></a> ";
				echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_usernr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/view.gif\" alt=\"$l_display\" title=\"$l_display\" border=\"0\"></a> ";
				if(($watchlogins==1) && ($admin_rights>1) && (!$enable_htaccess))
				{
					if($loglistcount>0)
					{
						echo "<a class=\"listlink2\" href=\"".do_url_session("loglist.php?$langvar=$act_lang&input_usernr=$act_id&username=".urlencode($myrow["username"]))."\">";
						echo "<img src=\"gfx/list.gif\" title=\"$l_loginlist\" alt=\"$l_loginlist\" border=\"0\"></a> ";
						echo "<a class=\"listlink2\" href=\"".do_url_session("loglist.php?$langvar=$act_lang&input_usernr=$act_id&mode=clear&username=".urlencode($myrow["username"]))."\">";
						echo "<img src=\"gfx/clear.gif\" border=\"0\" alt=\"$l_clearloginlist\" title=\"$l_clearloginlist\"</a>";
					}
				}
			}
			else
			{
				if(($admin_rights > 0) && ($myrow["usernr"]==$userdata["usernr"]) && ($myrow["lockentry"]==0))
				{
					echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_usernr=$act_id")."\">";
					echo "$l_edit</a>";
				}
				else if($myrow["usernr"]==$userdata["usernr"])
				{
					echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=display&input_usernr=$act_id&$langvar=$act_lang")."\">";
					echo "$l_display</a>";
				}
			}
		}
		echo "</td></tr>";
   } while($myrow = faqe_db_fetch_array($result));
   echo "</table></tr></td></table>";
}
if($admin_rights > 3)
{
?>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=newuser&$langvar=$act_lang")?>"><?php echo $l_newuser?></a></div>
<?php
}
}
include('./trailer.php');
?>