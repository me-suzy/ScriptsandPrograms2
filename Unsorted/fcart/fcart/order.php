<?
include "config.php";
include "mod.php";
include "processt.php";

if ($transfer_cookie) {
$id = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["id"] : $HTTP_GET_VARS["id"];
$id = r_secure($id);
} else {
include "cookie.php";
}

$coupon = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["dc"] : $HTTP_GET_VARS["dc"];
$coupon = r_secure($coupon);

if ($REQUEST_METHOD == "POST") {
	$firsttime=false;
	$category=$default_category;
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
	if ($mode == "register") {
		$uname = r_secure($HTTP_POST_VARS["uname"]);
		$passwd1 = r_secure($HTTP_POST_VARS["passwd1"]);
		$passwd2 = r_secure($HTTP_POST_VARS["passwd2"]);
		$fname = r_secure($HTTP_POST_VARS["fname"]);
		$lname = r_secure($HTTP_POST_VARS["lname"]);
		$b_address = r_secure($HTTP_POST_VARS["b_address"]);
		$b_city = r_secure($HTTP_POST_VARS["b_city"]);
		$b_state = r_secure($HTTP_POST_VARS["b_state"]);
		$b_country = r_secure($HTTP_POST_VARS["b_country"]);
		$b_zipcode = r_secure($HTTP_POST_VARS["b_zipcode"]);
		$s_address = r_secure($HTTP_POST_VARS["s_address"]);
		$s_city = r_secure($HTTP_POST_VARS["s_city"]);
		$s_state = r_secure($HTTP_POST_VARS["s_state"]);
		$s_country = r_secure($HTTP_POST_VARS["s_country"]);
		$s_zipcode = r_secure($HTTP_POST_VARS["s_zipcode"]);
		$phone = r_secure($HTTP_POST_VARS["phone"]);
		$email = r_secure($HTTP_POST_VARS["email"]);
		$card_type = d_secure($HTTP_POST_VARS["card_type"]);
		$card_name = r_secure($HTTP_POST_VARS["card_name"]);
		$card_num = d_secure($HTTP_POST_VARS["card_num"]);
		$expmonth = d_secure($HTTP_POST_VARS["expmonth"]);
		$expyear = d_secure($HTTP_POST_VARS["expyear"]);
		$paypoints = $HTTP_POST_VARS["paypoints"];
		$result = mysql_query("select login from customers where login='$uname'");
		$uerror = !(empty($uname)) && (mysql_num_rows($result) >= 1);
		mysql_free_result($result);
		$fillerror = empty($uname) || empty($passwd1) || empty($passwd2) || ($passwd1 != $passwd2) || empty($fname) || empty($lname) || empty($b_address) || empty($b_city) || empty($b_state) || empty($b_country) || empty($b_zipcode) || empty($phone) || empty($email) || ($cc_required ? empty($card_type) || empty($card_name) || empty($card_num) || empty($expmonth) || empty($expyear) : false);
		if (!($fillerror) && !($uerror)) {
			$crypted = $passwd1;
			if (strlen($expmonth) == 1) $expmonth = "0".$expmonth;
			if (strlen($expyear) == 1) $expyear = "0".$expyear;
			$expires = $expmonth.$expyear;
			if (empty($s_address) && empty($s_city) && empty($s_zipcode)) {
				$s_state = $b_state;
				$s_country = $b_country;
			}
			if (empty($s_address)) $s_address = $b_address;
			if (empty($s_city)) $s_city = $b_city;
			if (empty($s_zipcode)) $s_zipcode = $b_zipcode;
			mysql_query("insert into customers (login,password,userid,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,card_type,card_name,card_number,card_expire) values ('$uname','$crypted','$id','$fname','$lname','$b_address','$b_city','$b_state','$b_country','$b_zipcode','$s_address','$s_city','$s_state','$s_country','$s_zipcode','$phone','$email','$card_type','$card_name','$card_num','$expires')") or die ("$mysql_error_msg");
			header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php?".($transfer_cookie ? "id=$id&" : "")."dc=$coupon&first=$first&sortby=$sortby&category=".urlencode($category));
		}
	} elseif ($mode == "gift") {
		$fname = r_secure($HTTP_POST_VARS["fname"]);
		$lname = r_secure($HTTP_POST_VARS["lname"]);
		$email = r_secure($HTTP_POST_VARS["email"]);
		$s_address = r_secure($HTTP_POST_VARS["s_address"]);
		$s_city = r_secure($HTTP_POST_VARS["s_city"]);
		$s_state = r_secure($HTTP_POST_VARS["s_state"]);
		$s_country = r_secure($HTTP_POST_VARS["s_country"]);
		$s_zipcode = r_secure($HTTP_POST_VARS["s_zipcode"]);
		$cert = r_secure($HTTP_POST_VARS["cert"]);
		$result = mysql_query("select cert from giftcerts where cart='$id'");
		list($cert_) = mysql_fetch_row($result);
		mysql_free_result($result);
		$fillerror = empty($s_address) || empty($s_city) || empty($s_state) || empty($s_country) || empty($s_zipcode) || empty($cert) || ($cert != $cert_);
	}
} else {
	$firsttime = true;
	$fillerror = false;
	include "params.php";
}

$presult = mysql_query("select productid, amount from cart_data where cart='$id' and wish='N'");
$statecodes = array();
$states = array();
$result = mysql_query("select state,code from states order by code");
while (list($s,$c) = @mysql_fetch_row($result)) {
	array_push($statecodes,$c);
	array_push($states,$s);
}
mysql_free_result($result);
$countrycodes = array();
$countries = array();
$result = mysql_query("select country,code from countries order by code");
while (list($s,$c) = @mysql_fetch_row($result)) {
	array_push($countrycodes,$c);
	array_push($countries,$s);
}
mysql_free_result($result);
$cardtypes = array(0=>" ",1=>"Visa",2=>"Mastercard",3=>"Discover",4=>"American Express");
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: Order</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "cssstyle.php" ?>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>">
<?
include "top.php";
$tabnames = array("Continue shopping","View cart","Order");
$taburls = array("http://$http_location/main.php","http://$http_location/cart.php",($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php".($transfer_cookie ? "?id=$id" : ""));
$tabimages = array("images/narrow.gif","images/minicart.gif","");
include "tabs.php";
?>
<tr>
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2">
<?
include("login.php");
include("cat.php");
include("searchform.php");
include("help.php");
include "poweredby.php";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top">
<!-- main frame here -->
<table width="100%" height="100%" cellpadding="10">
<tr>
<td valign="top">
<font color="<? echo $cl_header ?>" size="+1"><b>Checkout</b></font><br>
<center>
<hr>
<?
if (mysql_num_rows($presult) == 0) {
	echo "<font size=\"3\"><b><i>Your shopping cart is empty</i></b></font>";
} else {
echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"4\"
width=\"100%\">";
echo "<tr
bgcolor=\"$cl_tab_back\"><td><b>Product</b></td><td><b>Quantity</b></td><td><b>Cost</b></td></tr>";
        while (list($productid,$amount) = mysql_fetch_row($presult)) {
                $productid = r_secure($productid);
				$amount = r_secure($amount);
                $result = mysql_query("select price, image, descr,
product from products where productid='$productid'");
                list($price,$image,$descr,$product) = mysql_fetch_row($result);
        		$sum += $price*$amount;
                display_product($product, $price, $image, $descr, $amount, "brief", $productid,"");
                mysql_free_result($result);
        }

echo "</table><br>\n";

if (empty($logged_in)) {
# -
$dont_display_disc_coupon = 1;
include "disccoup.php";
$dvdpointsearned = floor($total/$usd_to_dvd);
# -
	if (empty($mode)) {

echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/log_in.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
EOT;
?>
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="<? echo $cl_order_border ?>">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>">
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>Existing users: please log in</i></font></b></center>
</td>
</tr>

<tr>
<td valign="top" bgcolor=<? echo $cl_order_bg ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td colspan="3"><font size="-1">Note:&nbsp;&nbsp;<font color="<? echo $cl_order_red ?>">*</font>&nbsp;means that
field is required</font></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_header ?>">
<td height="20" colspan="3"><b><font color="<? echo $cl_win_title ?>" size="-1">Enter username &amp; password</font></b></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Username:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><input type="text" name="uname" size=
"32" maxlength="32"></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Password:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><input type="password" name="upass" size=
"32" maxlength="32"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<div align="center"><font><b>
<input type="submit" value="Log in"></b></font></div>
</form>

<?
	}
	if (empty($mode) || ($mode == "register")) {

echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name=dc value="$coupon">
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
<input type=hidden name=mode value="register">
EOT;
?>
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="<? echo $cl_order_border ?>">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>">
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>New users: Please fill out the registration form</i></font></b></center>
</td>
</tr>

<?
if ($uerror || $fillerror) {
	echo "<tr><td bgcolor=$cl_order_bg>";
	echo "<font color=\"$cl_order_red\"><b>";
	if ($uerror)
		echo "Username already exist, please select another one<br>";
	if ($fillerror) {
		if (empty($uname)) echo "You forgot to enter Username<br>";
		if (empty($passwd1)) echo "You forgot to enter Password<br>";
		elseif (empty($passwd2)) echo "You forgot to confirm Password<br>";
		elseif ($passwd1!=$passwd2) echo "Passwords don't match<br>";
		if (empty($fname)) echo "You forgot to enter First Name<br>";
		if (empty($lname)) echo "You forgot to enter Last Name<br>";
		if (empty($b_address)) echo "You forgot to enter Address<br>";
		if (empty($b_city)) echo "You forgot to enter City<br>";
		if (empty($b_state)) echo "You forgot to enter State<br>";
		if (empty($b_country)) echo "You forgot to enter Country<br>";
		if (empty($b_zipcode)) echo "You forgot to enter Zip Code<br>";
		if (empty($phone)) echo "You forgot to enter Phone<br>";
		if (empty($email)) echo "You forgot to enter E-Mail<br>";
		if ($cc_required) {
			if (empty($card_type)) echo "You forgot to enter Card Type<br>";
			if (empty($card_name)) echo "You forgot to enter Card Name<br>";
			if (empty($card_num)) echo "You forgot to enter Card Number<br>";
			if (empty($expmonth)) echo "You forgot to enter Card Exp. Month<br>";
			if (empty($expyear)) echo "You forgot to enter Card Exp. Year<br>";
		}
	}
	echo "</b></font></td></tr>";
}
?>
<tr>
<td valign="top" bgcolor=<? echo $cl_order_bg ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td colspan="3"><font size="-1">Note:&nbsp;&nbsp;<font color="<? echo $cl_order_red ?>">*</font>&nbsp;means that
field is required</font></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_header ?>">
<td height="20" colspan="3"><b><font color="<? echo $cl_win_title ?>" size="-1">Select username &amp; password</font></b></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Username:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap>
<input type="text" name="uname" size="32" maxlength="32" <? if (!($uerror)) echo "value=\"$uname\""; ?>
></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Password:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><input type="password" name="passwd1" size=
"32" maxlength="32"></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Confirm password:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><input type="password" name="passwd2" size=
"32" maxlength="32"></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_header ?>">
<td height="20" colspan="3"><b><font color="<? echo $cl_win_title ?>" size="-1">Customer information</font></b></td>
</tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;First Name</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="fname" size="32" maxlength="32" value="$fname">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Last Name</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="lname" size="32" maxlength="32" value="$lname">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Billing Address</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Street</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="b_address" size="32" maxlength="64" value="$b_address">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;City</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="b_city" size="32" maxlength="64" value="$b_city">
</td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;State</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><select name="b_state" size="1">
<option value="  " <? if (($firsttime) || ($b_state == "  ")) echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($b_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
?>
</select></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Country</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><select name="b_country" size="1">
<?
for ($i=0; $i<count($countries); $i++) {
	echo "<option value=\"$countrycodes[$i]\"";
	if ((($firsttime) && ($countrycodes[$i] == "US")) || ($b_country == $countrycodes[$i])) echo " selected";
	echo ">$countries[$i]</option>";
}
?>
<option value="  " <? if ($b_country == "  ") echo "selected"; ?>>Other, unknown or unspecified country</option>
</select></td>
</tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Zip code</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="b_zipcode" size="32" maxlength="32" value="$b_zipcode">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Shipping Address (leave empty if same as billing address)</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Street</td>
<td>&nbsp;</td>
<td nowrap>
<input type="text" name="s_address" size="32" maxlength="64" value="$s_address">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;City</td>
<td>&nbsp;</td>
<td nowrap>
<input type="text" name="s_city" size="32" maxlength="64" value="$s_city">
</td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;State</td>
<td>&nbsp;</td>
<td nowrap><select name="s_state" size="1">
<option value="  " <? if (($firsttime) || ($s_state == "  ")) echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($s_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
?>
</select></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Country</td>
<td>&nbsp;</td>
<td nowrap><select name="s_country" size="1">
<?
for ($i=0; $i<count($countries); $i++) {
	echo "<option value=\"$countrycodes[$i]\"";
	if ((($firsttime) && ($countrycodes[$i] == "US")) || ($s_country == $countrycodes[$i])) echo " selected";
	echo ">$countries[$i]</option>";
}
?>
<option value="  " <? if ($s_country == "  ") echo "selected"; ?>>Other, unknown or unspecified country</option>
</select></td>
</tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Zip code</td>
<td>&nbsp;</td>
<td nowrap>
<input type="text" name="s_zipcode" size="32" maxlength="32" value="$s_zipcode">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Phone &amp; E-Mail</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Phone</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="phone" size="32" maxlength="32" value="$phone">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;E-Mail</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="email" size="32" maxlength="128" value="$email">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Credit card information</font></b></td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Card type</td>
<td><? echo ($cc_required ? "<font color=\"$cl_order_red\">*</font>" : "&nbsp;") ?></td>
<td nowrap>
<?
if (!$cc_disabled) {
	echo "<select name=\"card_type\">";
	for ($i=0; $i<count($cardtypes); $i++) {
		echo "<option value=\"$i\"";
		if ((($firsttime) && ($i == "1")) || ($card_type == $i)) echo " selected";
		echo ">$cardtypes[$i]</option>";
	}
	echo "</select>";
} else {
	echo "<input type=hidden name=\"card_type\" value=\"$generic_card_type\">";
	echo "$cardtypes[$generic_card_type]";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Card holder's name</td>
<td><? echo ($cc_required ? "<font color=\"$cl_order_red\">*</font>" : "&nbsp;") ?></td>
<td nowrap>
<?
if (!$cc_disabled)
	echo "<input type=\"text\" name=\"card_name\" size=\"32\" maxlength=\"64\" value=\"$card_name\">";
else {
	echo "<input type=hidden name=\"card_name\" value=\"$generic_card_name\">";
	echo "$generic_card_name";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Card number</td>
<td><? echo ($cc_required ? "<font color=\"$cl_order_red\">*</font>" : "&nbsp;") ?></td>
<td nowrap>
<?
if (!$cc_disabled)
	echo "<input type=\"text\" name=\"card_num\" size=\"20\" maxlength=\"20\" value=\"$card_num\">";
else {
	echo "<input type=hidden name=\"card_num\" value=\"$generic_card_num\">";
	echo "$generic_card_num";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Expiration date</td>
<td><? echo ($cc_required ? "<font color=\"$cl_order_red\">*</font>" : "&nbsp;") ?></td>
<td nowrap>
<?
if (!$cc_disabled) {
	echo "<input type=\"text\" name=\"expmonth\" size=\"2\" maxlength=\"2\" value=\"$expmonth\">";
	echo "<input type=\"text\" name=\"expyear\" size=\"2\" maxlength=\"2\" value=\"$expyear\">";
} else {
	echo "<input type=hidden name=\"expmonth\" value=\"$generic_expmonth\">";
	echo "<input type=hidden name=\"expyear\" value=\"$generic_expyear\">";
	echo "$generic_expmonth/$generic_expyear";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

<br>

<div align="center"><font><b>
<input type="submit" value="Register"></b></font></div>
</form>

<?
	}
} else { # logged in
if ($gift_log && $total<=$bucks) {
if ($firsttime || $fillerror) {
	# -
	include "disccoup.php";
	$dvdpointsearned = floor($total/$usd_to_dvd);
	# -
	if ($firsttime) {
		$result = mysql_query("select recipient,remail from giftcerts where cart='$id'");
		list($fname,$email) = @mysql_fetch_row($result);
		mysql_free_result($result);
	}
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name=dc value="$coupon">
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
<input type=hidden name=mode value="gift">
EOT;
?>
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="<? echo $cl_order_border ?>">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>">
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>Please fill out the form below</i></font></b></center>
</td>
</tr>

<?
if ($fillerror) {
	echo "<tr><td bgcolor=$cl_order_bg>";
	echo "<font color=\"$cl_order_red\"><b>";
	if (empty($fname)) echo "You forgot to enter First Name<br>";
	if (empty($lname)) echo "You forgot to enter Last Name<br>";
	if (empty($email)) echo "You forgot to enter E-Mail<br>";
	if (empty($s_address)) echo "You forgot to enter Address<br>";
	if (empty($s_city)) echo "You forgot to enter City<br>";
	if (empty($s_state)) echo "You forgot to enter State<br>";
	if (empty($s_country)) echo "You forgot to enter Country<br>";
	if (empty($s_zipcode)) echo "You forgot to enter Zip Code<br>";
	if (empty($cert)) echo "You forgot to enter Gift Certificate<br>";
	elseif ($cert != $cert_) echo "Invalid Gift Certificate<br>";
	echo "</b></font></td></tr>";
}
echo <<<EOT
<tr>
<td valign="top" bgcolor="$cl_order_bg">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle" bgcolor="$cl_tab_top">
<td colspan="3"><font size="-1">Note:&nbsp;&nbsp;<font color="$cl_order_red">*</font>&nbsp;means that
field is required</font></td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Customer information</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;First Name</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="fname" size="32" maxlength="32" value="$fname">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Last Name</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="lname" size="32" maxlength="32" value="$lname">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;E-Mail</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="email" size="32" maxlength="128" value="$email">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Shipping Address</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Street</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="s_address" size="32" maxlength="64" value="$s_address">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;City</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="s_city" size="32" maxlength="64" value="$s_city">
</td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;State</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><select name="s_state" size="1">
<option value="  " <? if (($firsttime) || ($s_state == "  ")) echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($s_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
?>
</select></td>
</tr>

<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td>&nbsp;&nbsp;Country</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><select name="s_country" size="1">
<?
for ($i=0; $i<count($countries); $i++) {
	echo "<option value=\"$countrycodes[$i]\"";
	if ((($firsttime) && ($countrycodes[$i] == "US")) || ($s_country == $countrycodes[$i])) echo " selected";
	echo ">$countries[$i]</option>";
}
?>
<option value="  " <? if ($s_country == "  ") echo "selected"; ?>>Other, unknown or unspecified country</option>
</select></td>
</tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Zip code</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="s_zipcode" size="32" maxlength="32" value="$s_zipcode">
</td>
</tr>

<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Gift certificate confirmation</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Gift certificate</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<input type="text" name="cert" size="32" maxlength="32" value="$cert">
</td>
</tr>

</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

<br>
 
<div align="center"><font><b>
<input type="submit" value="Submit"></b></font></div>
</form>
EOT;

} else { #gift order ok
	# -
	$dont_display_disc_coupon = 1;
	include "disccoup.php";
	$dvdpointsearned = floor($total/$usd_to_dvd);
	# -
	$result = mysql_query("select login,firstname,b_address,b_city,b_state,b_country,b_zipcode,email,phone,card_type,card_name,card_number,card_expire from customers,giftcerts where customers.userid=giftcerts.userid and giftcerts.cart='$id'");
	list($uname,$fname_,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$email_,$phone_,$card_type,$card_name,$card_num,$expires) = @mysql_fetch_row($result);
	mysql_free_result($result);

	$card_type_ = $cardtypes[$card_type];
	$result = mysql_query("select country from countries where code='$b_country'");
	list($b_country_) = @mysql_fetch_row($result);
	mysql_free_result($result);
	if (empty($b_country_)) $b_country_="Unknown";
	$result = mysql_query("select state from states where code='$b_state'");
	list($b_state_) = @mysql_fetch_row($result);
	mysql_free_result($result);
	if (empty($b_state_)) $b_state_="Non-US";
	$result = mysql_query("select country from countries where code='$s_country'");
	list($s_country_) = @mysql_fetch_row($result);
	mysql_free_result($result);
	if (empty($s_country_)) $s_country_="Unknown";
	$result = mysql_query("select state from states where code='$s_state'");
	list($s_state_) = @mysql_fetch_row($result);
	mysql_free_result($result);
	if (empty($s_state_)) $s_state_="Non-US";
	mysql_query("insert into orders (login,order_total,order_discount,order_disc_coupon,order_shipping,order_date,order_flag,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,card_type,card_name,card_number,card_expire) values ('$uname','$total','$discount','$coupon','$shipping',now(),'Gift','$fname','$lname','$b_address','$b_city','$b_state','$b_country','$b_zipcode','$s_address','$s_city','$s_state','$s_country','$s_zipcode','$phone_','$email','$card_type','$card_name','$card_num','$expires')") or die ("$mysql_error_msg");
	$insert_id = mysql_insert_id();
	mysql_query("update customers set points=points+$dvdpointsearned where login='$uname'") or die ("$mysql_error_msg");
	if (!empty($coupon))
		mysql_query("update discount_coupons set count=count-1 where coupon='$coupon'") or die ("$mysql_error_msg");
	mysql_query("update giftcerts set status='U' where cart='$id'") or die ("$mysql_error_msg");
	mysql_data_seek($presult, 0);
	$products1 = "";
	$products2 = "";
	$products3 = "";
	while (list($productid,$amount) = mysql_fetch_row($presult)) {
		$productid = r_secure($productid);
		$amount = r_secure($amount);
		$result1 = mysql_query("select price from products where productid='$productid'");
		list($price) = mysql_fetch_row($result1);
		mysql_free_result($result1);
		mysql_query("insert into order_details (orderid,product,amount,price) values ('$insert_id','$productid','$amount','$price')") or die ("$mysql_error_msg");
		mysql_query("update products set rating=rating+1 where productid='$productid'") or die ("$mysql_error_msg");
		$result = mysql_query("select price,product from products where productid='$productid'");
		list($price,$product) = mysql_fetch_row($result);
		mysql_free_result($result);
		make_products_line($products1,$mail_notification_pl,$productid,$amount,$price,$product);
		make_products_line($products2,$mail_receipt_pl,$productid,$amount,$price,$product);
		make_products_line($products3,$mail_giftredeem_pl,$productid,$amount,$price,$product);
	}
	process_template($mail_notification,$insert_id,"","","Gift",$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,$phone_,$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$fname_,$products1);
	process_template($mail_notification_subj,$insert_id,"","","Gift",$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,$phone_,$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$fname_,$products1);
	process_template($mail_receipt,$insert_id,"","","Gift",$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,$phone_,$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$fname_,$products2);
	process_template($mail_receipt_subj,$insert_id,"","","Gift",$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,$phone_,$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$fname_,$products2);
	process_template($mail_giftredeem,$insert_id,"","","Gift",$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,$phone_,$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$fname_,$products3);
	process_template($mail_giftredeem_subj,$insert_id,"","","Gift",$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,$phone_,$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,$fname_,$products3);
	mysql_query("delete from cart_data where cart='$id' and wish='N'");
	echo "<center><br><hr><font size=\"3\"><b><i>Your order has been processed. Thank you for your order.</i></b></font></center>";
	mail($orders_notification_email,$mail_notification_subj,$mail_notification,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
	mail($email,$mail_receipt_subj,$mail_receipt,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
	mail($email_,$mail_giftredeem_subj,$mail_giftredeem,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
}
} elseif (!$gift_log) { #!gift_log
$passwd = r_secure($HTTP_POST_VARS["passwd"]);
$_card_type = $card_type;
$_card_name = $card_name;
$_card_num = $card_num;
$_expmonth = $expmonth;
$_expyear = $expyear;
$result = mysql_query("select login,password,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,card_type,card_name,card_number,card_expire from customers where userid='$id'");
list($uname,$passwd_,$fname,$lname,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$s_address,$s_city,$s_state,$s_country,$s_zipcode,$phone,$email,$card_type,$card_name,$card_num,$expires) = @mysql_fetch_row($result);
mysql_free_result($result);
$fillerror = !$firsttime && (($passwd != $passwd_) || ($cc_required ? (empty($card_type) || empty($card_name) || empty($card_num) || empty($expires)) && (empty($_card_type) || empty($_card_name) || empty($_card_num) || empty($_expmonth) || empty($_expyear)) : false));
$card_type_ = $cardtypes[$card_type];
$card_num_ = substr($card_num, -4);
$result = mysql_query("select country from countries where code='$b_country'");
list($b_country_) = @mysql_fetch_row($result);
mysql_free_result($result);
if (empty($b_country_)) $b_country_="Unknown";
$result = mysql_query("select state from states where code='$b_state'");
list($b_state_) = @mysql_fetch_row($result);
mysql_free_result($result);
if (empty($b_state_)) $b_state_="Non-US";
$result = mysql_query("select country from countries where code='$s_country'");
list($s_country_) = @mysql_fetch_row($result);
mysql_free_result($result);
if (empty($s_country_)) $s_country_="Unknown";
$result = mysql_query("select state from states where code='$s_state'");
list($s_state_) = @mysql_fetch_row($result);
mysql_free_result($result);
if (empty($s_state_)) $s_state_="Non-US";
if ($firsttime || $fillerror) {
	# -
	include "disccoup.php";
	$dvdpointsearned = floor($total/$usd_to_dvd);
	# -
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name=dc value="$coupon">
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
EOT;
if ($total<$dvdpoints_usd) {
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr><td><input type="checkbox" name="paypoints"></td>
<td><font color="blue"><b>You can pay your order with $bonus_points you have earned.<br>
Check this to pay with <? echo $bonus_points ?> (your credit card will not be charged)</b></font></td></tr>
</table><p>
EOT;
}
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="$cl_order_border">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td height="22" valign="middle" bgcolor="$cl_win_cap2">
<center><b><font color="$cl_win_title" size="-1"><i>Order confirmation</i></font></b></center>
</td>
</tr>
EOT;
if ($fillerror) {
	echo "<tr><td bgcolor=$cl_order_bg>";
	echo "<font color=\"$cl_order_red\"><b>";
	if ($passwd != $passwd_) echo "Password error<br>";
	if ($cc_required && empty($card_type) && empty($_card_type)) echo "You forgot to enter Card Type<br>";
	if ($cc_required && empty($card_type) && empty($_card_name)) echo "You forgot to enter Card Name<br>";
	if ($cc_required && empty($card_type) && empty($_card_num)) echo "You forgot to enter Card Number<br>";
	if ($cc_required && empty($card_type) && empty($_expmonth)) echo "You forgot to enter Card Exp. Month<br>";
	if ($cc_required && empty($card_type) && empty($_expyear)) echo "You forgot to enter Card Exp. Year<br>";
	echo "</b></font></td></tr>";
}
?>
<tr>
<td valign="top" bgcolor=<? echo $cl_order_bg ?>>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle" bgcolor=<? echo $cl_tab_top ?>>
<td colspan="2"><font size="-1">&nbsp;&nbsp;Please check the information below and either submit it or 
<? echo "<b><a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/register.php?".($transfer_cookie ? "id=$id&" : "")."first=$first&sortby=$sortby&category=".urlencode($category)."&mode=edit\">edit your data</a></b>"; ?>
<br>&nbsp;&nbsp;Enter your password to confirm your order.
</font></td>
</tr>

<tr bgcolor=<? echo $cl_tab_top ?>><td colspan="2"><hr></td></tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>Username:</b></td>
<td nowrap>&nbsp;$uname</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>First Name:</b></td>
<td nowrap>&nbsp;$fname</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>Last Name:</b></td>
<td nowrap>&nbsp;$lname</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>Billing Address:</b></td>
<td>&nbsp;</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;Street:</td>
<td nowrap>&nbsp;$b_address</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;City:</td>
<td nowrap>&nbsp;$b_city</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;State:</td>
<td nowrap>&nbsp;$b_state_</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;Country:</td>
<td nowrap>&nbsp;$b_country_</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;Zip code:</td>
<td nowrap>&nbsp;$b_zipcode</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>Shipping Address:</b></td>
<td>&nbsp;</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;Street:</td>
<td nowrap>&nbsp;$s_address</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;City:</td>
<td nowrap>&nbsp;$s_city</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;State:</td>
<td nowrap>&nbsp;$s_state_</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;Country:</td>
<td nowrap>&nbsp;$s_country_</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;&nbsp;&nbsp;Zip code:</td>
<td nowrap>&nbsp;$s_zipcode</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>Phone:</b></td>
<td nowrap>&nbsp;$phone</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;<b>E-Mail:</b></td>
<td nowrap>&nbsp;$email</td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;<b>Card type:</b></td>
<td nowrap>
<?
if (!empty($card_type))
	echo "&nbsp;$card_type_";
elseif ($cc_disabled) {
	echo "<input type=hidden name=\"card_type\" value=\"$generic_card_type\">";
	echo "&nbsp;$cardtypes[$generic_card_type]";
} else {
	echo "<select name=\"card_type\">";
	for ($i=0; $i<count($cardtypes); $i++) {
		echo "<option value=\"$i\"";
		if ($_card_type == $i) echo " selected";
		echo ">$cardtypes[$i]</option>";
	}
	echo "</select>";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;<b>Card holder's name:</b></td>
<td nowrap>
<?
if (!empty($card_name))
	echo "&nbsp;$card_name";
elseif ($cc_disabled) {
	echo "<input type=hidden name=\"card_name\" value=\"$generic_card_name\">";
	echo "&nbsp;$generic_card_name";
} else {
	echo "<input type=\"text\" name=\"card_name\" size=\"32\" maxlength=\"64\" value=\"$_card_name\">";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td nowrap>&nbsp;&nbsp;<b>Card number<? if (!empty($card_type)) echo " (last 4 digits)"; ?>:</b></td>
<td nowrap>
<?
if (!empty($card_num))
	echo "&nbsp;$card_num_";
elseif ($cc_disabled) {
	echo "<input type=hidden name=\"card_num\" value=\"$generic_card_num\">";
	echo "&nbsp;$generic_card_num";
} else {
	echo "<input type=\"text\" name=\"card_num\" size=\"20\" maxlength=\"20\" value=\"$_card_num\">";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;<b>Expiration date:</b></td>
<td nowrap>
<?
if (!empty($expires))
	echo "&nbsp;$expires";
elseif ($cc_disabled) {
	echo "<input type=hidden name=\"expmonth\" value=\"$generic_expmonth\">";
	echo "<input type=hidden name=\"expyear\" value=\"$generic_expyear\">";
	echo "&nbsp;$generic_expmonth/$generic_expyear";
} else {
	echo "<input type=\"text\" name=\"expmonth\" size=\"2\" maxlength=\"2\" value=\"$_expmonth\">&nbsp;";
	echo "<input type=\"text\" name=\"expyear\" size=\"2\" maxlength=\"2\" value=\"$_expyear\">";
}
?>
</td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;<b>Password:</b></td>
<td nowrap><input type="password" name="passwd" size="32" maxlength="32"></td>
</tr>

</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

<br>
 
<div align="center"><font><b>
<input type="submit" value="Confirm order"></b></font></div>
</form>
<?
} else { #order ok
	# -
	$dont_display_disc_coupon = 1;
	include "disccoup.php";
	$dvdpointsearned = floor($total/$usd_to_dvd);
	# -
	$pointsspent = 0;
	if ($paypoints=="on" && $total<$dvdpoints_usd) {
		$pointsspent = ceil($total/$dvd_to_usd);
	}
	$orderflag = (empty($pointsspent) ? "" : "Reward");
	if (empty($card_type)) {
		$card_type = $_card_type;
		$card_name = $_card_name;
		$card_num = $_card_num;
		if (strlen($_expmonth) == 1) $_expmonth = "0".$_expmonth;
		if (strlen($_expyear) == 1) $_expyear = "0".$_expyear;
		$expires = $_expmonth.$_expyear;
		mysql_query("update customers set card_type='$card_type',card_name='$card_name',card_number='$card_num',card_expire='$expires' where login='$uname'");
	}
	mysql_query("insert into orders (login,order_total,order_discount,order_disc_coupon,order_shipping,order_date,order_flag,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,card_type,card_name,card_number,card_expire) values ('$uname','$total','$discount','$coupon','$shipping',now(),'$orderflag','$fname','$lname','$b_address','$b_city','$b_state','$b_country','$b_zipcode','$s_address','$s_city','$s_state','$s_country','$s_zipcode','$phone','$email','$card_type','$card_name','$card_num','$expires')") or die ("$mysql_error_msg");
	$insert_id = mysql_insert_id();
	if (empty($pointsspent))
		mysql_query("update customers set points=points+$dvdpointsearned where login='$uname'") or die ("$mysql_error_msg");
	else
		mysql_query("update customers set points=points-$pointsspent where login='$uname'") or die ("$mysql_error_msg");
	if (!empty($coupon))
		mysql_query("update discount_coupons set count=count-1 where coupon='$coupon'") or die ("$mysql_error_msg");
	mysql_data_seek($presult, 0);
	$products1 = "";
	$products2 = "";
	while (list($productid,$amount) = mysql_fetch_row($presult)) {
		$productid = r_secure($productid);
		$amount = r_secure($amount);
		$result1 = mysql_query("select price from products where productid='$productid'");
		list($price) = mysql_fetch_row($result1);
		mysql_free_result($result1);
		mysql_query("insert into order_details (orderid,product,amount,price) values ('$insert_id','$productid','$amount','$price')") or die ("$mysql_error_msg");
		mysql_query("update products set rating=rating+1 where productid='$productid'") or die ("$mysql_error_msg");
		$result = mysql_query("select price,product from products where productid='$productid'");
		list($price,$product) = mysql_fetch_row($result);
		mysql_free_result($result);
		make_products_line($products1,$mail_notification_pl,$productid,$amount,$price,$product);
		make_products_line($products2,$mail_receipt_pl,$productid,$amount,$price,$product);
	}
	process_template($mail_notification,$insert_id,"","",$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,"$phone",$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,"",$products1);
	process_template($mail_notification_subj,$insert_id,"","",$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,"$phone",$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,"",$products1);
	process_template($mail_receipt,$insert_id,"","",$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,"$phone",$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,"",$products2);
	process_template($mail_receipt_subj,$insert_id,"","",$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state_,$b_country_,$b_zipcode,$s_address,$s_city,$s_state_,$s_country_,$s_zipcode,"$phone",$email,$card_type_,$card_name,$card_num,$expires,$discount,$disc_type,$disc_discount,$shipping,$total,"",$products2);
	mysql_query("delete from cart_data where cart='$id' and wish='N'");
	if ($affiliates_support == 'Y')
		echo "<img src=\"$affiliates_url"."?cashflow=$total>\n";
	echo "<center><br><hr><font size=\"3\"><b><i>Your order has been processed. Thank you for your order.<br>Please&nbsp;&nbsp;";
echo "<a href=\"http://$http_location/main.php?first=$first&sortby=$sortby&category=".urlencode($category)."\">Continue shopping</a>";
echo "&nbsp;&nbsp;or&nbsp;&nbsp;";
echo "<a href=\"http://$http_location/log_out.php?first=$first&sortby=$sortby&category=".urlencode($category)."\">log out</a></i></b></font></center>";
	mail($orders_notification_email,$mail_notification_subj,$mail_notification,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
	mail($email,$mail_receipt_subj,$mail_receipt,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
}
}
}
}
?>
<hr>
</center>
</td>
</tr>
</table>

<!-- /main frame -->
</td>
<?
include "bottom.php";
?>
</body>
</html>
