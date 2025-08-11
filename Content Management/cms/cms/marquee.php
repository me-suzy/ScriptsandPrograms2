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
// Filename: marquee.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Edit Marquee bar
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
// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if(!($_SESSION["privilege"] & 1)){
   $db->Close();
   noPrivilege();
}

$db->Close();

if (isset($_POST['submit'])){
	foreach($activeLang as $langparam=>$langicon){
		$fp = fopen("design/marquee_$langparam.html","w");
		flock($fp,2);
		$val = htmlspecialchars($_POST[$langparam]);
		fputs($fp,"$val");
		flock($fp,3);
		fclose($fp);
	}

	header("Location: index.php");
}
?>
<?php include_once ("header.php") ?>
<form action="marquee.php" method="post">
  <table width="98%" border="0" align="center" cellpadding="5" cellspacing="2" dir="<?php echo DIRECTION; ?>">
    <tr>
      <td colspan="2"><img src="cmsimages/config.png" width="48" height="48"></td>
    </tr>
    <?php
	foreach($activeLang as $langparam=>$langicon){
		$val = file_get_contents("design/marquee_$langparam.html");
    ?>
    <tr>
      <td class="pageTitle"><?php echo $langparam; ?></td>
      <td class="bodyBlock"><input type="text" name="<?php echo $langparam; ?>" size="50" value="<?php echo htmlspecialchars ($val); ?>"></td>
    </tr>
    <?php } ?>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo LBL_EDIT; ?>"></td>
    </tr>
  </table>
</form>

<?php include_once ("footer.php") ?>
