<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_user;
$page="users";
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$nofreemailer=$myrow["nofreemailer"];
	$watchlogins=$myrow["watchlogins"];
}
else
{
	$nofreemailer=0;
	$watchlogins=1;
}
if(!isset($sorting))
	$sorting=22;
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
		if(sn_array_key_exists($admcookievals,"users_sorting"))
			$sorting=$admcookievals["users_sorting"];
	}
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<?php
if($admin_rights<1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
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
		$sql = "select * from ".$tableprefix."_users where (usernr=$input_usernr)";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$myrow = mysql_fetch_array($result))
			die("<tr class=\"errorrow\"><td>no such entry");
		list($mydate,$mytime)=explode(" ",$myrow["lastlogin"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
			$displaydate=date($l_admdateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<tr class="displayrow"><td align="right" width="30%">User#:</td><td><?php echo $myrow["usernr"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><?php echo $myrow["username"]?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_realname?>:</td><td><?php echo $myrow["realname"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_email?>:</td><td><?php echo $myrow["email"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_language?>:</td><td><?php echo $myrow["language"]?></td></tr>
<tr class="displayrow"><td align="right"><?php echo $l_rights?>:</td><td>
<?php
		echo $l_admin_rights[$myrow["rights"]];
		echo "</td></tr>";
?>
<tr class="displayrow"><td align="right"><?php echo $l_lastlogin?>:</td><td><?php echo $displaydate?></td></tr>
<tr class="displayrow"><td align="right" valign="top"><?php echo $l_assignedcats?>:</td><td>
<?php
		$tmpsql="select * from ".$tableprefix."_cat_adm ca, ".$tableprefix."_categories cat where ca.usernr=".$myrow["usernr"]." and ca.catnr=cat.catnr";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if (!$tmprow = mysql_fetch_array($tmpresult))
			echo $l_none;
		else
		{
			echo "<ul>";
			do{
				echo "<li>".$tmprow["catname"];
			}while($tmprow=mysql_fetch_array($tmpresult));
			echo "</ul>";
		}
?>
</td></tr>
</table></td></tr></table>
<?php
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
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
<tr><td class="headingrow" align="center" colspan="2"><b><?php echo $l_newuser?></b></td></tr>
<form name="inputform" onsubmit="return checkform1();" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="sninput" type="text" name="input_username" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_realname?>:</td><td><input class="sninput" type="text" name="realname" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td><td><input class="sninput" type="text" name="email" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="sninput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="sninput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<?php
	echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_adduseroptions:</td><td>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_noicons\"";
	echo "> $l_user_noicons<br>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_nosmilies\"";
	echo "> $l_user_nosmilies<br>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_nobbcode\"";
	echo "> $l_user_nobbcode<br>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_nogfxupload\"";
	echo "> $l_user_nogfxupload<br>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_nolimitentries\"";
	echo "> $l_user_nolimitentries<br>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_foreignlinks\"";
	echo "> $l_user_foreignlinks<br>";
	echo "<input type=\"checkbox\" value=\"1\" name=\"u_adminfiles\"";
	echo "> $l_user_adminfiles<br>";
	echo "</td></tr>";
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="pwlocked" value="1"> <?php echo $l_pwlocked?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="entrylocked" value="1"> <?php echo $l_entrylocked?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo language_select($act_lang,"adm_lang")?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_rights?>:</td><td>
<select name="rights">
<?php
for($i = 0; $i< count($l_admin_rights); $i++)
	echo "<option value=\"$i\">".$l_admin_rights[$i]."</option>";
?>
</select></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="add"><input class="snbutton" type="submit" value="<?php echo $l_add?>"></td></tr>
</form>
</table></td></tr></table>
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?$langvar=$act_lang")?>"><?php echo $l_userlist?></a></div>
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
		if(!$input_username)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nousername</td></tr>";
			$errors=1;
		}
		if(!$realname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_norealname</td></tr>";
			$errors=1;
		}
		if(!$password || !$password2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nopassword</td></tr>";
			$errors=1;
		}
		if($password2!=$password)
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
		$input_username=addslashes(strtolower($input_username));
		$sql = "select * from ".$tableprefix."_users where username='$input_username'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.");
		if($row=mysql_fetch_array($result))
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_username_exists</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$addoptions=0;
			if(isset($u_noicons))
				$addoptions=setbit($addoptions,BIT_1);
			if(isset($u_nosmilies))
				$addoptions=setbit($addoptions,BIT_2);
			if(isset($u_nobbcode))
				$addoptions=setbit($addoptions,BIT_3);
			if(isset($u_nogfxupload))
				$addoptions=setbit($addoptions,BIT_4);
			if(isset($u_nolimitentries))
				$addoptions=setbit($addoptions,BIT_5);
			if(isset($u_nolimitentries))
				$addoptions=setbit($addoptions,BIT_5);
			if(isset($u_foreignlinks))
				$addoptions=setbit($addoptions,BIT_6);
			if(isset($u_adminfiles))
				$addoptions=setbit($addoptions,BIT_7);
			if($rights<2)
				$addoptions=0;
			if(isset($pwlocked))
				$lockpw=1;
			else
				$lockpw=0;
			if(isset($entrylocked))
				$lockentry=1;
			else
				$lockentry=0;
			$password=md5($password);
			if(!isset($email))
				$email="";
			$sql = "INSERT INTO ".$tableprefix."_users (username, password, email, rights, realname, lockpw, language, lockentry, addoptions) ";
			$sql .="VALUES ('$input_username', '$password', '$email', $rights, '$realname', $lockpw, '$adm_lang', $lockentry, $addoptions)";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to add user to database.");
			echo "<tr class=\"displayrow\" align=\"center\"><td>";
			echo "<i>$input_username</i> $l_useradded";
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
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nodelactual<br>";
			echo "</td></tr></table></td></tr></table>";
			echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_userlist</a></div>";
		}
		else
		{
			if(($admdelconfirm==1) && !isset($confirmed))
			{
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="confirmed" value="1">
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="input_usernr" value="<?php echo $input_usernr?>">
<input type="hidden" name="input_username" value="<?php echo $input_username?>">
<?php
				if(is_konqueror())
					echo "<tr><td></td></tr>";
				if($sessid_url)
					echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
				$tmpsql="select * from ".$tableprefix."_users where usernr=$input_usernr";
				if(!$tmpresult = mysql_query($tmpsql, $db))
				    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if (!$tmprow = mysql_fetch_array($tmpresult))
					die("<tr class=\"errorrow\"><td>no such entry");
				echo "<tr class=\"inforow\"><td align=\"center\">";
				echo "$l_confirmdel: $l_user ".$tmprow["username"];
				echo "</td></tr>";
				echo "<tr class=\"actionrow\"><td align=\"center\">";
				echo "<input class=\"snbutton\" type=\"submit\" name=\"submit\" value=\" $l_yes \">";
				echo "&nbsp;<input class=\"snbutton\" type=\"button\" value=\" $l_no \" onclick=\"self.history.back();\">";
				echo "</td></tr>";
				echo "</form></table></td></tr></table>";
				include('./trailer.php');
				exit;
			}
			$deleteSQL = "delete from ".$tableprefix."_users where (usernr=$input_usernr)";
			$success = mysql_query($deleteSQL);
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
		$sql = "select * from ".$tableprefix."_users where (usernr=$input_usernr)";
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
			$displaydate=date($l_admdateformat,mktime($hour,$min,$sec,$month,$day,$year));
		else
			$displaydate="";
?>
<form name="inputform" onsubmit="return checkform2()" method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if($sessid_url)
		echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
?>
<input type="hidden" name="input_usernr" value="<?php echo $myrow["usernr"]?>">
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="sninput" type="text" name="input_username" size="40" maxlength="80" value="<?php echo $myrow["username"]?>"></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_realname?>:</td><td><input class="sninput" type="text" name="realname" size="40" maxlength="240" value="<?php echo $myrow["realname"]?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_email?>:</td><td><input class="sninput" type="text" name="email" size="40" maxlength="80" value="<?php echo $myrow["email"]?>"></td></tr>
<?php
	if((($admin_rights > 2) || ($myrow["lockpw"]==0)) && !$enable_htaccess)
	{
?>
<tr class="inputrow"><td align="right"><?php echo $l_password?>:</td><td><input class="sninput" type="password" name="password" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_confirmpassword?>:</td><td><input class="sninput" type="password" name="password2" size="40" maxlength="80"></td></tr>
<?php
	}
	if($admin_rights > 2)
	{
		echo "<tr class=\"inputrow\"><td align=\"right\" valign=\"top\">$l_adduseroptions:</td><td>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_noicons\"";
		if(bittst($myrow["addoptions"],BIT_1))
			echo " checked";
		echo "> $l_user_noicons<br>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_nosmilies\"";
		if(bittst($myrow["addoptions"],BIT_2))
			echo " checked";
		echo "> $l_user_nosmilies<br>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_nobbcode\"";
		if(bittst($myrow["addoptions"],BIT_3))
			echo " checked";
		echo "> $l_user_nobbcode<br>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_nogfxupload\"";
		if(bittst($myrow["addoptions"],BIT_4))
			echo " checked";
		echo "> $l_user_nogfxupload<br>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_nolimitentries\"";
		if(bittst($myrow["addoptions"],BIT_5))
			echo " checked";
		echo "> $l_user_nolimitentries<br>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_foreignlinks\"";
		if(bittst($myrow["addoptions"],BIT_6))
			echo " checked";
		echo "> $l_user_foreignlinks<br>";
		echo "<input type=\"checkbox\" value=\"1\" name=\"u_adminfiles\"";
		if(bittst($myrow["addoptions"],BIT_7))
			echo " checked";
		echo "> $l_user_adminfiles<br>";
		echo "</td></tr>";
		if(!$enable_htaccess)
		{
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="pwlocked" value="1" <?php if ($myrow["lockpw"]==1) echo "checked";?>> <?php echo $l_pwlocked?></td></tr>
<?php
		}
?>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="entrylocked" value="1" <?php if ($myrow["lockentry"]==1) echo "checked";?>> <?php echo $l_entrylocked?></td></tr>
<?php
	}
?>
<tr class="inputrow"><td align="right"><?php echo $l_language?>:</td><td>
<?php echo language_select($myrow["language"],"adm_lang")?></td></tr>
<?php
if($admin_rights > 2)
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
<tr class="displayrow"><td align="right"><?php echo $l_lastlogin?>:</td><td><?php echo $displaydate?></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="update"><input class="snbutton" type="submit" value="<?php echo $l_update?>"></td></tr>
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
			$sql = "select * from ".$tableprefix."_users where usernr=$input_usernr";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if (!$myrow = mysql_fetch_array($result))
				die("<tr class=\"errorrow\"><td>no such entry");
			if($myrow["lockentry"]==1)
			{
				echo "<tr class=\"errorrow\"><td align=\"center\">";
				die("$l_functionnotallowed");
			}
		}
		$errors=0;
		if(!$input_username)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_nousername</td></tr>";
			$errors=1;
		}
		if(!$realname)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			echo "$l_norealname</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$addoptions=0;
			if(isset($u_noicons))
				$addoptions=setbit($addoptions,BIT_1);
			if(isset($u_nosmilies))
				$addoptions=setbit($addoptions,BIT_2);
			if(isset($u_nobbcode))
				$addoptions=setbit($addoptions,BIT_3);
			if(isset($u_nogfxupload))
				$addoptions=setbit($addoptions,BIT_4);
			if(isset($u_nolimitentries))
				$addoptions=setbit($addoptions,BIT_5);
			if(isset($u_foreignlinks))
				$addoptions=setbit($addoptions,BIT_6);
			if(isset($u_adminfiles))
				$addoptions=setbit($addoptions,BIT_7);
			if($rights<2)
				$addoptions=0;
			$input_username=addslashes(strtolower($input_username));
			$sql = "UPDATE ".$tableprefix."_users SET realname='$realname', username='$input_username', email='$email', language='$adm_lang'";
			if($admin_rights>2)
				$sql.=", addoptions=$addoptions";
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
				if(isset($entrylocked))
					$lockentry=1;
				else
					$lockentry=0;
				$sql .=", lockpw=$lockpw, lockentry=$lockentry, rights=$rights ";
			}
			$sql .=" WHERE (usernr = $input_usernr)";
			if(!$result = mysql_query($sql, $db))
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
if($admin_rights>2)
{
?>
<tr class="actionrow"><td align="center" colspan="5">
<a href="<?php echo do_url_session("$act_script_url?mode=newuser&$langvar=$act_lang")?>"><?php echo $l_newuser?></a>
</td></tr>
<?php
}
// Display list of actual users
$sql = "select * from ".$tableprefix."_users ";
switch($sorting)
{
	case 11:
		$sql.="order by username asc";
		break;
	case 12:
		$sql.="order by username desc";
		break;
	case 21:
		$sql.="order by rights asc, username asc";
		break;
	case 22:
		$sql.="order by rights desc, username asc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
$baseurl=$act_script_url."?".$langvar."=".$act_lang;
if($admstorefilter==1)
	$baseurl.="&dostorefilter=1";
$maxsortcol=2;
echo "<tr class=\"rowheadings\">";
echo "<td align=\"center\"><b>";
$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_username</a>";
echo getSortMarker($sorting, 1, $maxsortcol);
echo "</b></td>";
echo "<td align=\"center\"><b>";
$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
echo "<a href=\"".do_url_session($sorturl)."\" class=\"sorturl\">";
echo "$l_rights</a>";
echo getSortMarker($sorting, 2, $maxsortcol);
echo "</b></td>";
if(($watchlogins==1) && ($admin_rights>1) && !$enable_htaccess)
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
			if(($watchlogins==1) && ($admin_rights>1) && !$enable_htaccess)
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
				$dellink=do_url_session("$act_script_url?$langvar=$act_lang&input_usernr=$act_id&input_username=".$myrow["username"]."&mode=delete");
				if($admdelconfirm==2)
					echo "<a class=\"listlink\" href=\"javascript:confirmDel('$l_user ".$myrow["username"]."','$dellink')\">";
				else
					echo "<a class=\"listlink\" href=\"$dellink\" valign=\"top\">";
				echo "<img src=\"gfx/delete.gif\" border=\"0\" alt=\"$l_delete\" title=\"$l_delete\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_usernr=$act_id")."\">";
				echo "<img src=\"gfx/edit.gif\" border=\"0\" title=\"$l_edit\" alt=\"$l_edit\"></a> ";
				echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_usernr=$act_id&$langvar=$act_lang")."\">";
				echo "<img src=\"gfx/view.gif\" border=\"0\" title=\"$l_display\" alt=\"$l_display\"></a>";
				if(($watchlogins==1)&& !$enable_htaccess)
				{
					if($loglistcount>0)
					{
						echo " <a class=\"listlink\" href=\"".do_url_session("loglist.php?$langvar=$act_lang&input_usernr=$act_id&input_username=".urlencode($myrow["username"]))."\">";
						echo "<img src=\"gfx/list.gif\" border=\"0\" alt=\"$l_loginlist\" title=\"$l_loginlist\"></a>";
						echo " <a class=\"listlink\" href=\"".do_url_session("loglist.php?lang=$lang&input_usernr=$act_id&mode=clear&username=".urlencode($myrow["username"]))."\">";
						echo "<img src=\"gfx/clear.gif\" border=\"0\" alt=\"$l_clearloginlist\" title=\"$l_clearloginlist\"</a>";
					}
				}
			}
			else
			{
				if(($admin_rights > 0) && ($myrow["usernr"]==$userdata["usernr"]) && ($myrow["lockentry"]==0))
				{
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=edit&$langvar=$act_lang&input_usernr=$act_id")."\">";
					echo "<img src=\"gfx/edit.gif\" border=\"0\" alt=\"$l_edit\" title=\"$l_edit\"></a>&nbsp; ";
				}
				if($myrow["usernr"]==$userdata["usernr"])
				{
					echo "<a class=\"listlink\" href=\"".do_url_session("$act_script_url?mode=display&input_usernr=$act_id&$langvar=$act_lang")."\">";
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
<div class="bottombox" align="center"><a href="<?php echo do_url_session("$act_script_url?mode=newuser&$langvar=$act_lang")?>"><?php echo $l_newuser?></a></div>
<?php
}
}
include('./trailer.php');
?>