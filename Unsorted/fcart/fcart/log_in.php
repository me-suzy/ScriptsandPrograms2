<?
include "config.php";
include "mod.php";

if ($transfer_cookie) {
$id = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["id"] : $HTTP_GET_VARS["id"];
$id = r_secure($id);
} else {
include "cookie.php";
}

if (strlen($HTTP_POST_VARS["category"]) > 0)
	$category = r_secure($HTTP_POST_VARS["category"]);
if ($category == "All")
	$category=$default_category;
$sortby = $default_sortby;
if (strlen($HTTP_POST_VARS["sortby"]) > 0)
	$sortby = r_secure($HTTP_POST_VARS["sortby"]);
if (strlen($HTTP_POST_VARS["first"]) > 0)
	$first = r_secure($HTTP_POST_VARS["first"]);
else
	$first = 1;

$uname = r_secure($HTTP_POST_VARS["uname"]);
$upass = r_secure($HTTP_POST_VARS["upass"]);

$error = empty($uname) || empty($upass);
if (!($error)) {
	$error = true;
	$result = mysql_query("select password,userid from customers where login='$uname'");
	if (mysql_num_rows($result) == 1) {
		list($passwd,$userid) = @mysql_fetch_row($result);
		$error = ($passwd != $upass);
	}
	mysql_free_result($result);
}
if ($error) {
	$result = mysql_query("select password,userid from customers where email='$uname'");
	if (mysql_num_rows($result) == 1) {
		list($passwd,$userid) = @mysql_fetch_row($result);
		$error = ($passwd != $upass);
	}
	mysql_free_result($result);
}

# gift cert code
if ($error) {
	$result = mysql_query("select cart from giftcerts where cert='$uname' and status='S'");
	$error = (mysql_num_rows($result) != 1);
	list($userid) = @mysql_fetch_row($result);
	mysql_free_result($result);
}
# /gift cert code

if ($error)
	header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/message.php?first=$first&sortby=$sortby&category=".urlencode($category)."&text=".urlencode("Invalid login. Try again."));
else {
	$array = array("N","Y");
	while ($wish = each($array)) {
	$result1 = mysql_query("select productid,amount from cart_data where cart='$id' and wish = '$wish[1]'");
	while (list($productid,$amount) = @mysql_fetch_row($result1)) {
		$productid = d_secure($productid);
		$amount = r_secure($amount);
		$result2 = mysql_query("select amount from cart_data where cart='$userid' and productid='$productid' and wish = '$wish[1]'");
		if (mysql_num_rows($result2) >= 1) {
			if (mysql_num_rows($result2) > 1)
				die("$shopcart_error_msg");
			mysql_query("update cart_data set amount = amount+'$amount' where cart='$userid' and productid='$productid' and wish='$wish[1]'") or die ("$mysql_error_msg");
		} else {
			mysql_query("insert into cart_data (cart, productid, amount, wish) values ('$userid', '$productid', '$amount', '$wish[1]')") or die ("$mysql_error_msg");
		}
		mysql_free_result($result2);
	}
	mysql_free_result($result1);
	mysql_query("delete from cart_data where cart='$id' and wish = '$wish[1]'");
	}
	mysql_query("delete from cart_data where amount<1");

	if (!$transfer_cookie) {
		setcookie("ID", "$userid", time() + $cookie_timeout);
		if (strstr($HTTP_REFERER,"main.php") || strstr($HTTP_REFERER,"cart.php") || strstr($HTTP_REFERER,"order.php") || strstr($HTTP_REFERER,"gift.php"))
			$location=$HTTP_REFERER;
		else
			$location="http://$http_location/index.php?first=$first&sortby=$sortby&category=".urlencode($category);
	} else {
		if (strstr($HTTP_REFERER,"main.php") || strstr($HTTP_REFERER,"cart.php") || strstr($HTTP_REFERER,"order.php") || strstr($HTTP_REFERER,"gift.php"))
			$referer=$HTTP_REFERER;
		else
			$referer="http://$http_location/index.php?first=$first&sortby=$sortby&category=".urlencode($category);
		$referer=ereg_replace("\\?.*","",$referer);
		$location="http://$http_location/setcookie.php?id=$userid&referer=".urlencode($referer)."&first=$first&sortby=$sortby&category=".urlencode($category);
	}
	echo "<script type=\"text/javascript\">\nlocation.href='$location'\n</script>";
}
?>
