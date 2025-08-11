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
// Filename: pagescart.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Manage user shopping cart
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
if(is_numeric($_GET["key"])){ $key = @$_GET["key"]; }
$actList = array("add", "delete", "change", "empty");
if (in_array(@$_GET["action"], $actList)) { $action = @$_GET["action"]; }
if(is_numeric($_GET["q"])){ $q = @$_GET["q"]; }

if (!empty($action)) {
	switch ($action) {
	case "add":
		if (!empty($key)) {
			// create an object instance
			// configure library for a MySQL connection
			$db = NewADOConnection(DBTYPE);
			
			// open connection to database
			$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");
			
			// get resultset as associative array
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			
			$tkey = "" . $key . "";
			$strsql = "SELECT * FROM `pages` WHERE `id`=" . $tkey . " AND `lang`='" . $lang . "'";
			$rs = $db->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
			if ($rs->RecordCount()	!= 0 ) {
				// get the field contents
				$x_id = @$rs->fields["id"];
				$x_title = @$rs->fields["title"];
				$x_price = @$rs->fields["price"];
				$cart->add_item($key, 1, $x_price, $x_title);
			}
			$db->Close();
		}
		break;
	case "delete":
		if (!empty($key)) {
			$cart->del_item($key);
		}
		break;
	case "change":
		if (!empty($key) && !empty($q)) {
			$cart->edit_item($key, $q);
		}
		break;
	case "empty":
		$cart->empty_cart();
		break;
	}
}
	


?>
<?php include_once ("header.php") ?>
<table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
<tr>
	<td class="linksBlock" colspan="3">
	<img src="cmsimages/cart.gif" width="25" height="19" border="0" alt="<?php echo LBL_CART; ?>">
	<?php 
		$count = $cart->itemcount;
		echo IN_CART1 . " <b>" . $count . "</b> " . IN_CART2; 
	?>
	 [<a href=pagescart.php?action=empty onClick="return confirm('<?php echo EMPTY_CART_CONFIRM; ?>')"><?php echo EMPTY_CART; ?></a>]
	</td>

	      <td class="linksBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
	      <?php
		   foreach($activeLang as $langparam=>$langicon){
		     if($langparam != $lang){
	      ?>

		    <a href="pagescart.php?lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
	      <?php
		     }
		   }
	      ?>
	      </td>
	      <td class="linksBlock">&nbsp;</td>
</tr>
<tr>
	<td class="pageTitle" width="50%" align="center"><?php echo CART_ITEM_TITLE; ?>&nbsp;</td>
	<td class="pageTitle" width="15%" align="center"><?php echo CART_ITEM_QUANTITY; ?>&nbsp;</td>
	<td class="pageTitle" width="15%" align="center"><?php echo CART_ITEM_PRICE; ?>&nbsp;</td>
	<td class="pageTitle" width="15%" align="center"><?php echo CART_ITEM_SUBTOTAL; ?>&nbsp;</td>
	<td class="linksBlock" width="5%">&nbsp;</td>
</tr>
<?php
$items = $cart->get_contents();
foreach($items as $item){
?>
<tr>
	<td class="bodyBlock"><a href=pagesview.php?key=<?php echo $item['id']; ?>><?php echo $item['info']; ?></a>&nbsp;</td>
	<td class="bodyBlock" align="center">
		<?php echo $item['qty']; ?>&nbsp;&nbsp;
		<a href="pagescart.php?action=change&key=<?php echo $item['id']; ?>&q=<?php echo $item['qty']+1; ?>"><img src=cmsimages/ord_up.gif width=15 height=15 border=0 alt=""></a>
		<a href="pagescart.php?action=change&key=<?php echo $item['id']; ?>&q=<?php echo $item['qty']-1; ?>"><img src=cmsimages/ord_down.gif width=15 height=15 border=0 alt=""></a>
	</td>
	<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $item['price']); ?>&nbsp;</td>
	<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $item['subtotal']); ?>&nbsp;USD</td>
	<td class="linksBlock" align="center"><a href=pagescart.php?key=<?php echo $item['id']; ?>&action=delete onClick="return confirm('<?php echo DEL_CART_CONFIRM; ?>')"><img src=cmsimages/publish_x.png width=12 height=12 border=0 alt="<?php echo LBL_CART_DEL; ?>"></a>&nbsp;</td>
</tr>
<?php } ?>
<tr>
	<td class="bodyBlock" colspan=3><b><?php echo CART_TOTAL; ?></b>&nbsp;</td>
	<td class="bodyBlock" align="right"><?php echo sprintf("%01.2f", $cart->total); ?>&nbsp;USD</td>
	<td class="linksBlock">&nbsp;</td>
</tr>
<tr>
	<td class="linksBlock" colspan="5" align="center"><br>
	<input type="button" value="<?php echo CART_CLOSE; ?>" name="close" onClick="window.location='pagesview.php?key=1'">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="<?php echo CART_CHECKOUT; ?>" name="checkout" onClick="window.location='shipaddr.php'">
	<br><br></td>
</tr>
</table>
<?php 
	include_once ("footer.php");
?>
