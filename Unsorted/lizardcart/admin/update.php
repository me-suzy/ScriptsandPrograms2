<?
include ("atho.inc.php");
include ("config.inc.php");
include "ccfunctions.inc.php";
define("DEBUG",0); 
$strKey="0987654321";
$EncryptCCNumber=encrypt($CCNumber, $strKey);
$query = "UPDATE orders SET ChargeID ='$ChargeID' , TOTAL ='$TOTAL' , SUBTOTAL ='$SUBTOTAL' , SHIPPING ='$SHIPPING' , TAX ='$TAX' , CCNumber ='$EncryptCCNumber' , CCExpireDate ='$CCExpireDate' , first_name ='$first_name' , last_name ='$last_name' , address1 ='$address1' , address2 ='$address2' , city ='$city' , state ='$state' , zip ='$zip' , country ='$country' , email ='$email' , item_name ='$item_name' , comment ='$comment'";
$req = mysql_query($query);

if (!$req)
{ echo "<B>Error ".mysql_errno()." :</B> ".mysql_error()."";
exit; }

$aff_rows = mysql_affected_rows();

echo "$aff_rows value(s) has been updated";


?>
<? include ("header.php");?>
<div> 
<table width="100%" border="2">
	  <tr align="center" valign="middle">
		<td colspan="2" align="center"></td>
	  </tr>
<tr>
	<td>Order Number</td>
	<td><? echo "$ChargeID" ?></td>
</tr>
<tr>
	<td>Order First Name</td>
	<td><? echo "$first_name" ?></td>
</tr>
<tr>
	<td>Order Last Name</td>
	<td><? echo "$last_name" ?></td>
</tr>
<tr>
	<td>IOrder Address 1</td>
	<td><? echo "$address1" ?></td>
</tr>
<tr>
	<td>Order Address 2</td>
	<td><? echo "$address2" ?></td>
</tr>
<tr>
	<td>Order City</td>
	<td><? echo "$city" ?></td>
</tr>
<tr>
	<td>Order State</td>
	<td><? echo "$state" ?></td>
</tr>
<tr>
	<td>Order Zip code</td>
	<td><? echo "$zip" ?></td>
</tr>
<tr>
	<td>Order Contry</td>
	<td><? echo "$country" ?></td>
</tr>
<tr>
	<td>Order Email</td>
	<td><? echo "$email" ?></td>
</tr>
<tr>
	<td>Items Purchased</td>
	<td><? echo "$item_name" ?></td>
</tr>
<tr>
	<td>Order Comment</td>
	<td><? echo "$comment" ?></td>
</tr>
<tr>
	<td>Order Total:</td>
	<td><? echo "$TOTAL" ?></td>
</tr>
<tr>
	<td>Order Subtotal:</td>
	<td><? echo "$SUBTOTAL" ?></td>
</tr>
<tr>
	<td>Order Shipping</td>
	<td><? echo "$SHIPPING" ?></td>
</tr>
<tr>
	<td>Order Tax</td>
	<td><? echo "$TAX" ?></td>
</tr>
<tr>
	<td>Order Credit Card Number Encrypted</td>
	<td><? echo "$CCNumber" ?></td>
</tr>
<tr>
	<td>Order CC Expire Date  </td>
	<td><? echo "$CCExpireDate" ?></td>
</tr>
	  <tr align="center" valign="middle">
		<td colspan="2" align="center"></td>
	  </tr>
</table>

        </td>
      </tr>
    </table>
    <br>
  </div>
  <? include ("footer.php");?>