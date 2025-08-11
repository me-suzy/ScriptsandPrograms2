<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: login.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Login!
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("security.inc.php") ?>
<?php
if (isset($_POST["submit"]) && (!$admin_ip || $_SERVER['REMOTE_ADDR']==$admin_ip)) {
	$validpwd = False;

	// setup variables
	$userid = htmlspecialchars(@$_POST["userid"]);
	$passwd = htmlspecialchars(@$_POST["passwd"]);
	if (!$validpwd) {
		// create an object instance
		// configure library for a MySQL connection
		$db = NewADOConnection("mysql");
		
		// open connection to database
		$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");
		
		// get resultset as associative array
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

		$strsql = "SELECT user_pw, privilege, rootpage FROM `users` WHERE `user_id` = '" . $userid . "'";
		$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		if (!$rs->EOF) {
			if ($rs->fields["user_pw"] == crypt(strtolower($passwd),$rs->fields["user_pw"])) {
				$_SESSION["user_id"] = $userid;
				$_SESSION["ip"] = getip();
				$_SESSION["privilege"] = $rs->fields["privilege"];
				$_SESSION["rootpage"] = $rs->fields["rootpage"];
				$validpwd = True;
			}
		}	
		$db->Close();
	}
	if ($validpwd) {

		// write cookies
		if (@$_POST["rememberme"] <> "") {
			setCookie("user_id", $userid, time()+365*24*60*60); // change cookie expiry time here
		}		
		$_SESSION["status"] = "login";

		header("location: index.php");
	}
} else {
	$validpwd = True;
}
?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<script language="JavaScript" type="text/javascript">
<!-- start JavaScript
function  checkForm(frm) {
      if(frm.userid.value == ''){
	      alert('<?php echo MISS_USERID; ?>');
	      frm.userid.focus();
	      return false;
      }

      if(frm.passwd.value == ''){
	      alert('<?php echo MISS_PW; ?>');
	      frm.passwd.focus();
	      return false;
      }

      return true;
}
// end JavaScript -->
</script>
<form action=<? if($useSSL){ echo 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; }else{ echo "login.php"; } ?> method="post" onSubmit="return checkForm(this);" name="login">
	    <table width="98%" border="0" align="center" cellpadding="5" cellspacing="2" dir="<?php echo DIRECTION; ?>">
<?php if (!$validpwd) {?>
	      <tr class="linksBlock">
		      <td colspan="2" align="center"><font color="#FF0000"><?php echo LBL_INCORRECT; ?></font></td>
	      </tr>
<?php } ?>
	      <tr>
	      <td><img src="cmsimages/login.gif" width="34" height="40" alt=""></td>
	      <td class="linksBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>" valign="bottom">
	      <?php
		   foreach($activeLang as $langparam=>$langicon){
		     if($langparam != $lang){
	      ?>

		    <a href="login.php?lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
	      <?php
		     }
		   }
	      ?>
	      </td>
	      </tr>
	      <tr>
		<td class="pageTitle"><?php echo LBL_USERID; ?></td>
		<td class="bodyBlock"><input type="text" name="userid" size="20" value="<?php echo htmlspecialchars(@$_COOKIE["user_id"]); ?>"></td>
	      </tr>
	      <tr>
		<td class="pageTitle"><?php echo LBL_PW; ?></td>
		<td class="bodyBlock"><input type="password" name="passwd" size="20"></td>
	      </tr>
	      <tr>
		<td>&nbsp;</td>
		<td class="bodyBlock"><input type="checkbox" name="rememberme" value="true">
		  &nbsp;<?php echo LBL_REM; ?></td>
	      </tr>
	      <tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo LBL_LOGIN; ?>"></td>
	      </tr>
	    </table>
</form>
<script language="JavaScript" type="text/javascript">
document.login.userid.focus();
</script>
<?php include_once ("footer.php") ?>
