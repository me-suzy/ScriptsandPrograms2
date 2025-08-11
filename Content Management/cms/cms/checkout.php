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
// Filename: checkout.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Script to call CashU script to collect money
// ----------------------------------------------------------------------

include_once ("wfcart.php");
session_start();

$cart =& $_SESSION['cart'];
if(!is_object($cart)) $cart = new wfCart();
?>
<?php include_once ("config.php") ?>
<?php include_once ("db.php") ?>
<?php
// Collect user shipping address

$path = 'http://'.$_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
$thxpath = str_replace("checkout.php", "checkdone.php", $path);
$cancelpath = str_replace("checkout.php", "pagescart.php", $path);
?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
<tr>
<p><a href="pagescart.php"><img src="cmsimages/cart.gif" width="25" height="19" border="0" alt="<?php echo LBL_CART; ?>"> <?php echo LBL_BACK . " " . LBL_CART; ?></a></p>
<? if($cashu == 1){ ?>
<td class="bodyBlock" align="center">
<?php
     echo CART_TOTAL . ": <b>" . sprintf("%01.2f", $cart->total) . "</b> USD";

      // $token will have the MD5 digest value of the string.
      $total = $cart->total;
      $token = md5("$merchant_id:$total:usd:keyword1");
?>
		<form action="https://www.cashu.com/cgi-bin/pcashu.cgi" method="post">
			<input type="hidden" name="merchant_id" value="<?php echo $merchant_id; ?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="display_text" value="<?php echo SITE_TITLE; ?>">
			<input type="hidden" name="currency" value="USD">
			<input type="hidden" name="amount" value="<?php echo $cart->total; ?>">
			<input type="hidden" name="language" value="<?php echo $lang; ?>">
			<input type="hidden" name="email" value="<?php echo $merchant_id; ?>@cashucard.com">
			<input type="hidden" name="session_id" value="<?php echo session_id(); ?> ">
			<input type="hidden" name="thanx_url" value="<?php echo $thxpath; ?>">
			<input type="hidden" name="txt1" value="<?php echo SITE_TITLE; ?>">
			<?php if($cashu_testmode == 1){ ?>
				<input type="hidden" name="test_mode" value="1">
			<?php } ?>
			<input type="image" src="cmsimages/cashu_pay.gif" width="100" height="50" border="0">
		</form>
</td>
<? } ?>
<? if($paypal == 1){ ?>
<td class="bodyBlock" align="center">
<?php
     echo CART_TOTAL . ": <b>" . sprintf("%01.2f", $cart->total) . "</b> USD";
?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="<?php echo $business_id; ?>">
			<input type="hidden" name="return" value="<?php echo $thxpath; ?>">
			<input type="hidden" name="item_name" value="<?php echo SITE_TITLE; ?>">
			<input type="hidden" name="item_number" value="<?php echo session_id(); ?> ">
			<input type="hidden" name="amount" value="<?php echo $cart->total; ?>">
			<input type="hidden" name="invoice" value="<?php echo session_id(); ?> ">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="cancel_return" value="<?php echo $cancelpath; ?>">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="currency_code" value="USD">
			<input type="image" src="cmsimages/paypal_pay.gif" width="60" height="38" border="0">
		</form>
</td>
<? } ?>
</tr><tr>
<? if($cashu == 1){ ?>
   <td class="bodyBlock" align="center"><a href="http://www.CashUcard.com" target="_blank"><img src="cmsimages/cashu_cards.gif" alt="" width="282" height="79" border="0"></a></td>
<? } ?>
<? if($paypal == 1){ ?>
   <td class="bodyBlock" align="center"><a href="http://www.PayPal.com" target="_blank"><img src="cmsimages/paypal_cards.gif" alt="" width="253" height="80" border="0"></a></td>
<? } ?>
</tr>
</table>
<?php
	include_once ("footer.php");
?>
