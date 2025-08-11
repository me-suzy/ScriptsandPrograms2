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
// Filename: shipaddr.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  User want to check out items in his/her shopping cart
//	     We will get his shpping address
// ----------------------------------------------------------------------

include_once ("wfcart.php");
session_start();

$cart =& $_SESSION['cart'];
if(!is_object($cart)) $cart = new wfCart();
?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php include_once ("lang.php") ?>
<?php
// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if (isset($_POST['submit'])){
		// get the form values
		if(is_numeric($_POST["key"])){ $x_id = @$_POST["key"]; }else{ header("Location: pagescart.php"); }
		$x_phone = htmlspecialchars(@$_POST["x_phone"]);
		$x_name = htmlspecialchars(@$_POST["x_name"]);
		$x_address = htmlspecialchars(@$_POST["x_address"]);
		$x_city = htmlspecialchars(@$_POST["x_city"]);
		$x_state = htmlspecialchars(@$_POST["x_state"]);
		$x_zip = htmlspecialchars(@$_POST["x_zip"]);
		$x_country = htmlspecialchars(@$_POST["x_country"]);

		// add the values into an array

		// Get record ID
		$fieldList["`id`"] = $db->GenID('invoice');

		// phone number
		$theValue = $x_phone;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`phone`"] = $theValue;

		// shipping name
		$theValue = $x_name;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`name`"] = $theValue;

		// shipping address
		$theValue = $x_address;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`address`"] = $theValue;

		// shipping city
		$theValue = $x_city;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`city`"] = $theValue;

		// shipping state
		$theValue = $x_state;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`state`"] = $theValue;

		// shipping zip/post
		$theValue = $x_zip;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`zip`"] = $theValue;

		// shipping state
		$theValue = $x_country;
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`country`"] = $theValue;

		// session id
		$theValue = session_id();
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`session`"] = $theValue;

		// date
		$fieldList["`date`"] = time();

		// invoice total
		$fieldList["`total`"] = $cart->total;

		// paid flag
		$fieldList["`paid`"] = 0;

                $db->BeginTrans();
                $ok = 1;

		if(!empty($x_id)){
			// update all pages (same id)
			$updateSQL = "UPDATE `invoice` SET ";
			foreach ($fieldList as $key=>$temp) {
				$updateSQL .= "$key = $temp, "; 		
			}
			if (substr($updateSQL, -2) == ", ") {
				$updateSQL = substr($updateSQL, 0, strlen($updateSQL)-2);
			}
			$updateSQL .= " WHERE `id`=" . $x_id;
			if ($ok) $ok = $db->Execute($updateSQL) or die("Error in query: $updateSQL. " . $db->ErrorMsg());
		}else{
			// insert into database
			$strsql = "INSERT INTO `invoice` (";
			$strsql .= implode(",", array_keys($fieldList));
			$strsql .= ") VALUES (";
			$strsql .= implode(",", array_values($fieldList));
			$strsql .= ")";
			if ($ok) $ok = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		}


		$items = $cart->get_contents();

		if(!empty($x_id)){
			$strsql = "DELETE FROM `invoice_details` WHERE `invoice_id`=" . $x_id;
			if ($ok) $ok = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
			$id = $x_id;
		}else{
			$id = $db->Insert_ID();
		}

		foreach($items as $item){
			$strsql = "INSERT INTO `invoice_details` (`invoice_id`,`item_id`,`quantity`,`price`) VALUES (";
			$strsql .= $id . ", " . $item['id'] . ", " . $item['qty'] . ", " . $item['price'] . ")";
			if ($ok) $ok = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		}

                if ($ok) $db->CommitTrans();
                else $db->RollbackTrans();
                header("Location: checkout.php");
}else{
		$strsql = "SELECT * FROM `invoice` WHERE `session`='" . session_id() . "' AND `paid`=0";
		$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
		if ($rs->RecordCount() != 0) {
			// get the field contents
			$key = @$rs->fields["id"];
			$x_phone = @$rs->fields["phone"];
			$x_name = @$rs->fields["name"];
			$x_address = @$rs->fields["address"];
			$x_city = @$rs->fields["city"];
			$x_state = @$rs->fields["state"];
			$x_zip = @$rs->fields["zip"];
			$x_country = @$rs->fields["country"];
		}
}
$db->Close();
?>
<?php include_once ("header.php") ?>
<script language="JavaScript">

<!-- start Javascript
function  checkForm(frm) {
      if(frm.x_name.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_name.focus();
	      return false;
      }

      if(frm.x_address.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_address.focus();
	      return false;
      }

      if(frm.x_city.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_city.focus();
	      return false;
      }

      if(frm.x_state.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_state.focus();
	      return false;
      }

      if(frm.x_phone.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_phone.focus();
	      return false;
      }

      if(frm.x_country.value == ''){
	      alert('<?php echo INVALID_MSG; ?>');
	      frm.x_country.focus();
	      return false;
      }

      return true;
}
// end JavaScript -->
</script>

<form  action="shipaddr.php" method="post" name="shipper" onSubmit="return checkForm(this);">
<input type="hidden" name="key" value="<?php echo $key; ?>">
<table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
	<tr>
		<td class="bodyBlock">
		<p><a href="pagescart.php"><img src="cmsimages/cart.gif" width="25" height="19" border="0" alt="<?php echo LBL_CART; ?>"> <?php echo LBL_BACK . " " . LBL_CART; ?></a></p>

<table width="680" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center"><tr>
<td class="pageTitle" width=55% align="center"><?php echo CART_ITEM_TITLE; ?>&nbsp;</td>
<td class="pageTitle" width=15% align="center"><?php echo CART_ITEM_QUANTITY; ?>&nbsp;</td>
<td class="pageTitle" width=15% align="center"><?php echo CART_ITEM_PRICE; ?>&nbsp;</td>
<td class="pageTitle" width=15% align="center"><?php echo CART_ITEM_SUBTOTAL; ?>&nbsp;</td></tr>
<?php
$items = $cart->get_contents();
foreach($items as $item){
?>
	<tr><td class="bodyBlock"><?php echo $item['info']; ?>&nbsp;</td>
	<td class="bodyBlock" align="center"><?php echo $item['qty']; ?></td>
	<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $item['price']); ?>&nbsp;</td>
	<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $item['subtotal']); ?>&nbsp;USD</td></tr>
<?php
}
?>
<tr><td class="bodyBlock" colspan=3><b><?php echo CART_TOTAL; ?></b>&nbsp;</td>
<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $cart->total); ?>&nbsp;USD</td></tr></table>
<br><br>

      <table border="0" align="center" cellpadding="5" cellspacing="2">
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_NAME; ?></td>
	  <td class="bodyBlock"><input name="x_name" type="text" id="x_name" value="<?php echo htmlspecialchars(@$x_name); ?>"></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_ADDRESS; ?></td>
	  <td class="bodyBlock">
	    <textarea name="x_address" cols="60" rows="4" id="x_address"><?php echo htmlspecialchars(@$x_address); ?></textarea></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_CITY; ?></td>
	  <td class="bodyBlock"><input name="x_city" type="text" id="x_city" value="<?php echo htmlspecialchars(@$x_city); ?>"></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_STATE; ?></td>
	  <td class="bodyBlock"><input name="x_state" type="text" id="x_state" value="<?php echo htmlspecialchars(@$x_state); ?>"></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_PHONE; ?></td>
	  <td class="bodyBlock"><input name="x_phone" type="text" id="x_phone" value="<?php echo htmlspecialchars(@$x_phone); ?>"> </td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_ZIP; ?></td>
	  <td class="bodyBlock"><input name="x_zip" type="text" id="x_zip" value="<?php echo htmlspecialchars(@$x_zip); ?>"></td>
	</tr>
	<tr>
	  <td width="150" valign="top" class="pageTitle"><?php echo LBL_COUNTRY; ?></td>
	  <td class="bodyBlock"><select name="x_country" id="x_country">
	      <option><?php if($x_country != ''){ echo htmlspecialchars(@$x_country); }else{ echo LBL_SELECT; } ?></option>
                  <?php
                  $shipping_country = str_replace(' ','',$shipping_country);
                  $country_list     = split(',',$shipping_country);
                  foreach($country_list as $country){
                        echo "<option value=\"$country\">$country</option>";
                  }
                  ?>
	    </select></td>
	</tr>
      </table><br>&nbsp;
	</td>
	</tr>
	<tr>
		<td class="linksBlock" align="center"><br>
		<input type="submit" value="<?php echo LBL_BUY; ?>" name="submit">
		<br><br></td>
	</tr>
</table>
</form>
<?php
	include_once ("footer.php");
?>
