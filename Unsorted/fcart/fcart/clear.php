<?
include "config.php";
include "mod.php";
include "params.php";

include "cookie.php";

$wish = $HTTP_GET_VARS["mode"] == "wish" ? "Y" : "N";

mysql_query("delete from cart_data where cart='$id' and wish='$wish'");

header("Location: http://$http_location/cart.php?first=$first&sortby=$sortby&category=".urlencode($category));
?>
