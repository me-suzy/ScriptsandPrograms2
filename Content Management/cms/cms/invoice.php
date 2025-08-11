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
// Filename: invoice.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  View invoice details
// ----------------------------------------------------------------------

?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php
$id = htmlspecialchars(@$_GET["id"]);
if (empty($id)) {
	header("Location: index.php");
}

// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$strsql = "SELECT invoice.phone, invoice.name, invoice.address, invoice.city, invoice.state, invoice.zip, invoice.country, pages.title, invoice_details.quantity, invoice_details.price FROM `invoice_details`, `invoice`, `pages` WHERE `invoice_details`.`invoice_id` = `invoice`.`id` AND `invoice_details`.`item_id` = `pages`.`id` AND `invoice`.`session`='$id' AND `lang`='$lang' AND `invoice`.`paid`='1'";
$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
if ($rs->RecordCount()	!= 0 ) {
	// get the field contents
	$x_phone = @$rs->fields["phone"];
	$x_name = @$rs->fields["name"];
	$x_address = @$rs->fields["address"];
	$x_address = str_replace(chr(10), "<br>" ,@$x_address . "");
	$x_city = @$rs->fields["city"];
	$x_state = @$rs->fields["state"];
	$x_zip = @$rs->fields["zip"];
	$x_country = @$rs->fields["country"];
}
?>
<?php include_once ("header.php") ?>

<table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
	<tr><td class="bodyBlock">

      <table width="680" border="0" align="center" cellpadding="5" cellspacing="2">
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_NAME; ?></td>
	  <td class="bodyBlock"><?php echo htmlspecialchars(@$x_name); ?></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_ADDRESS; ?></td>
	  <td class="bodyBlock">
	    <?php echo htmlspecialchars(@$x_address); ?></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_CITY; ?></td>
	  <td class="bodyBlock"><?php echo htmlspecialchars(@$x_city); ?></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_STATE; ?></td>
	  <td class="bodyBlock"><?php echo htmlspecialchars(@$x_state); ?></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_PHONE; ?></td>
	  <td class="bodyBlock"><?php echo htmlspecialchars(@$x_phone); ?> </td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_ZIP; ?></td>
	  <td class="bodyBlock"><?php echo htmlspecialchars(@$x_zip); ?></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_COUNTRY; ?></td>
	  <td class="bodyBlock"><? echo htmlspecialchars(@$x_country); ?></td>
	</tr>
      </table><br><br>
      <table width="680" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center"><tr>
      <td class="pageTitle" width=55% align="center"><?php echo CART_ITEM_TITLE; ?>&nbsp;</td>
      <td class="pageTitle" width=15% align="center"><?php echo CART_ITEM_QUANTITY; ?>&nbsp;</td>
      <td class="pageTitle" width=15% align="center"><?php echo CART_ITEM_PRICE; ?>&nbsp;</td>
      <td class="pageTitle" width=15% align="center"><?php echo CART_ITEM_SUBTOTAL; ?>&nbsp;</td></tr>
      <?php
      while (!$rs->EOF) {
      ?>
      	<tr><td class="bodyBlock"><?php echo @$rs->fields['title']; ?>&nbsp;</td>
      	<td class="bodyBlock" align="center"><?php echo @$rs->fields['quantity']; ?></td>
      	<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", @$rs->fields['price']); ?>&nbsp;</td>
      	<td class="bodyBlock" align="right"><?php $subtotal = @$rs->fields['price']*@$rs->fields['quantity']; $total += $subtotal; echo sprintf("%01.2f", $subtotal); ?>&nbsp;USD</td></tr>
      <?php
	  $rs->MoveNext();
      }
      ?>
      <tr><td class="bodyBlock" colspan=3><b><?php echo CART_TOTAL; ?></b>&nbsp;</td>
      <td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $total); ?>&nbsp;USD</td></tr></table>
      <br>&nbsp;
	</td>
	</tr>
</table>

<?php
	$db->Close();
	include_once ("footer.php");
?>
