<?
include "config.php";
include "mod.php";
include "params.php";

include "cookie.php";

$amount= r_secure($HTTP_GET_VARS["amount"]);
$productid = d_secure($HTTP_GET_VARS["productid"]);

$presult = mysql_query("delete from cart_data where cart='$id' and productid='$productid' and wish = 'Y'");
header("Location: http://$http_location/add.php?productid=$productid&amount=$amount&first=$first&sortby=$sortby&category=".urlencode($category));
?>
