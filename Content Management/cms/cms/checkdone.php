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
// Filename: checkdone.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Script called by CashU script after paid money by user
// ----------------------------------------------------------------------

include_once ("wfcart.php");
session_start();

$cart =& $_SESSION['cart'];
if(!is_object($cart)) $cart = new wfCart();
$cart->empty_cart();

// Check that referrer come from CashU or PayPal website
// --- code ----

$key = htmlspecialchars(@$_GET["session_id"]);
if (empty($key)) {
	 $key = htmlspecialchars(@$_POST["session_id"]);
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$tkey = "" . $key . "";

// update
$updateSQL = "UPDATE `invoice` SET `paid`='1' WHERE `session`=".$tkey;
$db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());

?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
<tr>
    <td class="bodyBlock" align="center"><?php echo CHECKDONE; ?><br>
    <?php
         $path = 'http://'.$_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
         $invpath = str_replace("checkdone.php", "invoice.php?id=$key", $path);
         echo "<a href=$invpath target=_blank>$invpath</a>";
    ?>
    </td>
</tr>
</table>
<?php 
	$db->Close();
	include_once ("footer.php");
?>
