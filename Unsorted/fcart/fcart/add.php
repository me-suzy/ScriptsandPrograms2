<?
include "config.php";
include "mod.php";
include "params.php";

include "cookie.php";

$amount= r_secure($HTTP_GET_VARS["amount"]);
$productid = d_secure($HTTP_GET_VARS["productid"]);
$wish = strstr($QUERY_STRING, '&wish.x=') ? 'Y' : 'N';

$presult = mysql_query("select amount from cart_data where cart='$id' and productid='$productid' and wish = '$wish'");
if (mysql_num_rows($presult) >= 1) {
	if (mysql_num_rows($presult) > 1)
		die("$shopcart_error_msg");
	$result = mysql_query("update cart_data set amount = amount+'$amount' where cart='$id' and productid='$productid' and wish='$wish'") or die ("$mysql_error_msg");
} else {
	$result = mysql_query("insert into cart_data (cart, productid, amount, wish) values ('$id', '$productid', '$amount', '$wish')") or die ("$mysql_error_msg");
}
	$result3 = mysql_query("delete from cart_data where amount<1");
	header("Location: http://$http_location/cart.php?first=$first&sortby=$sortby&category=".urlencode($category));
?>
