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
// Filename: usersedit.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Edit user account
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("security.inc.php") ?>
<?php if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php") ?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if (empty($key)) {
	if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}
if (empty($key)) {
	$key=1;
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if (!isset($_POST['submit'])){
    $tkey = "" . $key . "";
    $strsql = "SELECT * FROM `users` WHERE `id`=" . $tkey;
    $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
    if ($rs->RecordCount() == 0) {
	    header("Location: login.php");
    }

    // get the field contents
    $x_user_id = @$rs->fields["user_id"];
    $x_user_pw = @$rs->fields["user_pw"];
    $x_fullname = @$rs->fields["fullname"];
	$db->Close();
}else{
    $tkey = "" . $key . "";

    // get the form values
    $x_user_id = htmlspecialchars(@$_POST["x_user_id"]);
    $x_user_pw = htmlspecialchars(@$_POST["x_user_pw"]);
    $x_fullname = htmlspecialchars(@$_POST["x_fullname"]);

    // add the values into an array

    // user_id
    $theValue = $x_user_id;
    $theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
    $fieldList["`user_id`"] = $theValue;

    // user_pw
	if($x_user_pw != ''){
		$x_user_pw = crypt(strtolower($x_user_pw));
		$theValue = $x_user_pw;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`user_pw`"] = $theValue;
	}

    // fullname
    $theValue = $x_fullname;
    $theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
    $fieldList["`fullname`"] = $theValue;

    // update
    $updateSQL = "UPDATE `users` SET ";
    foreach ($fieldList as $key=>$temp) {
	    $updateSQL .= "$key = $temp, ";		    
    }
    if (substr($updateSQL, -2) == ", ") {
	    $updateSQL = substr($updateSQL, 0, strlen($updateSQL)-2);
    }
    $updateSQL .= " WHERE `id`=".$tkey;
    $db->Execute($updateSQL) or die("Error in query: $strsql. " . $db->ErrorMsg());
	$db->Close();
    header("Location: logout.php");
}
?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<script language="JavaScript" type="text/javascript">
<!-- start Javascript
function  checkForm(frm) {
      if(frm.x_user_id.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_user_id.focus();
	      return false;
      }

      if(frm.x_fullname.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_fullname.focus();
	      return false;
      }

      return true;
}

// end JavaScript -->
</script>
<form onSubmit="return checkForm(this);"  action="usersedit.php" method="post">
<input type="hidden" name="key" value="<?php echo $key; ?>">
		  <table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
		  <tr> 
			<td><img src="cmsimages/user.png" width="48" height="48" alt=""></td>
	      <td class="linksBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>" valign="bottom">
	      <?php
		   foreach($activeLang as $langparam=>$langicon){
		     if($langparam != $lang){
	      ?>

		    <a href="usersedit.php?lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
	      <?php
		     }
		   }
	      ?>
	      </td>
	      </tr>
	      <tr> 
		<td class="pageTitle"><?php echo LBL_USERID; ?>&nbsp;</td>
		<td class="bodyBlock"> 
		  <input type="text" name="x_user_id" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_user_id); ?>">
		  &nbsp;</td>
	      </tr>
	      <tr> 
		<td class="pageTitle"><?php echo LBL_PW; ?>&nbsp;</td>
		<td class="bodyBlock"> 
		  <input type="password" name="x_user_pw" size="30" maxlength="255" value="">
		  &nbsp;</td>
	      </tr>
	      <tr> 
		<td class="pageTitle"><?php echo LBL_FULLNAME; ?>&nbsp;</td>
		<td class="bodyBlock"> 
		  <input type="text" name="x_fullname" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_fullname); ?>">
		  &nbsp;</td>
	      </tr>
		  <tr> 
			<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo LBL_EDIT; ?>"></td>
	      </tr>
	    </table>

</form>
<?php 
	include_once ("footer.php")
?>
