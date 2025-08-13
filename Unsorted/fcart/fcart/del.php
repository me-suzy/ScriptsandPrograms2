<?
include "config.php";
include "mod.php";
include "params.php";

include "cookie.php";

$amount= r_secure($HTTP_GET_VARS["amount"]);
$productid = d_secure($HTTP_GET_VARS["productid"]);
$wish = $HTTP_GET_VARS["mode"] == "wish" ? "Y" : "N";

mysql_query("delete from cart_data where cart='$id' and productid='$productid' and wish = '$wish'");

header("Location: $HTTP_REFERER");
?>
