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
// Filename: pagesdelete.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Delete one page
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("security.inc.php") ?>
<?php if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php") ?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php
// single delete record
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
if (empty($key)) {
	if(is_numeric($_POST["key"])){ $key = @$_POST["key"]; }
}
if (empty($key)) {
	header("Location: sitemap.php");
}
if(is_numeric($_POST["parent_id"])){ $parent = @$_POST["parent_id"]; }else{ $parent = 1; }
$sqlKey = "`id`=" . "" . $key . "";

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if(!($_SESSION["privilege"] & 8) || !inTree($db, $_SESSION["rootpage"], $key)){
   $db->Close();
   noPrivilege();
}

if (!isset($_POST['submit'])){
		$strsql = "SELECT * FROM `pages` WHERE " . $sqlKey . " AND `lang`='" . $lang . "'";
		$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		if ($rs->RecordCount() == 0) {
			header("Location: index.php");
		}

		$x_id = @$rs->fields["id"];
		$x_title = @$rs->fields["title"];
		$x_content = @$rs->fields["content"];
		$x_parent_id = @$rs->fields["parent_id"];
		$x_status = @$rs->fields["status"];
		$x_lang = @$rs->fields["lang"];
		$x_privilege = @$rs->fields["privilege"];
		$x_modified = @$rs->fields["modified"];
		$x_create = @$rs->fields["create"];
		$x_user_id = @$rs->fields["user_id"];

		$x_parent_title = '';
		if ($x_parent_id && $x_parent_id != $key){
		  $strsql = "SELECT * FROM `pages` WHERE `id`=" . $x_parent_id . " AND `lang`='" . $lang . "'";
		  $rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		  if ($rs->RecordCount()) {
			  $x_parent_title = @$rs->fields["title"];
		  }
		}

		$db->Close();
}else{
		$strsql = "DELETE FROM `pages` WHERE " . $sqlKey;
		$db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		$db->Close();
		header("Location: pagesedit.php?key=$parent");
}
?>
<?php include_once ("header.php") ?>
<form action="pagesdelete.php" method="post">
<input type="hidden" name="key" value="<?php echo $key; ?>">
<input type="hidden" name="parent_id" value="<?php echo $x_parent_id; ?>">
		  <table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
		    <tr>
				<td class="linksBlock">
					<?php if ($x_parent_title){ ?>
					<img src="cmsimages/up.gif" width="15" height="15" border="0" alt="">
					<a href="pagesedit.php?key=<?php echo $x_parent_id; ?>"><?php echo LBL_BACK . " " . $x_parent_title; ?></a>
					<?php } ?>
				</td>
	      <td class="linksBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
	      <?php
		   foreach($activeLang as $langparam=>$langicon){
		     if($langparam != $lang){
	      ?>

		    <a href="pagesdelete.php?key=<?php echo $key; ?>&lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
	      <?php
		     }
		   }
	      ?>
	      <img src="cmsimages/trash.png" width="48" height="48" border="0" alt="<?php echo LBL_DELETE; ?>">
	      </td>
		    </tr>
			<tr><td class="pageTitle" colspan="2"><?php echo CONFIRM_DEL_MSG; ?></td></tr>
			<tr><td class="bodyBlock" colspan="2"><?php echo $x_title; ?></td></tr>
			<tr><td align="center" colspan="2"><input type="submit" name="submit" value="<?php echo CONFIRM_DEL_LBL; ?>"></td></tr>
		</table>
</form>
<?php include_once ("footer.php") ?>
