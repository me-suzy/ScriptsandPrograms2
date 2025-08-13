<?
include "config.php";
include "mod.php";
include "params.php";

if ($transfer_cookie) {
$id = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["id"] : $HTTP_GET_VARS["id"];
$id = r_secure($id);
} else {
include "cookie.php";
}

$first = r_secure($HTTP_POST_VARS["first"]);
$sortby = r_secure($HTTP_POST_VARS["sortby"]);
$category = r_secure($HTTP_POST_VARS["category"]);
$coupon = r_secure($HTTP_POST_VARS["coupon"]);

$error = empty($coupon);
$result = mysql_query("select discount,type from discount_coupons where coupon='$coupon' and now()<expire and count>0");
$error |= (mysql_num_rows($result) != 1);
list($disc_discount,$disc_type) = @mysql_fetch_row($result);
mysql_free_result($result);

if ($error)
	header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/message.php?first=$first&sortby=$sortby&category=".urlencode($category)."&text=".urlencode("Invalid discount coupon. Try again."));
else
	header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php?".($transfer_cookie ? "id=$id&" : "")."dc=$coupon&first=$first&sortby=$sortby&category=".urlencode($category));
?>
