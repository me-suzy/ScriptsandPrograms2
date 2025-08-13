<?
include "config.php";
include "mod.php";

if ($transfer_cookie) {
$id = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["id"] : $HTTP_GET_VARS["id"];
$id = r_secure($id);
} else {
include "cookie.php";
}

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
	$uname = r_secure($HTTP_POST_VARS["uname"]);
	$passwd = r_secure($HTTP_POST_VARS["passwd"]);
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
} else {
	$firsttime = true;
	include "params.php";
	if ($mode == "edit") {
		$result = mysql_query("select login,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email from customers where userid='$id'");
		if (mysql_num_rows($result) != 1) {
			header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/message.php?first=$first&sortby=$sortby&category=".urlencode($category)."&text=".urlencode("Login timeout. Please log in."));
			exit;
		}
		list($uname,$fname,$lname,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$s_address,$s_city,$s_state,$s_country,$s_zipcode,$phone,$email) = mysql_fetch_row($result);
		if (empty($b_state)) $b_state = "  ";
		if (empty($b_country)) $b_country = "  ";
		if (empty($s_state)) $s_state = "  ";
		if (empty($s_country)) $s_country = "  ";
	} else {
		$b_state = "  ";
		$b_country = "US";
		$s_state = "  ";
		$s_country = "US";
	}
}

$result = mysql_query("select password from customers where login='$uname'");
$uerror = !($firsttime) && ($mode != "edit") && !(empty($uname)) && (mysql_num_rows($result) >= 1);
list($passwd_) = @mysql_fetch_row($result);
$passwderror = ($passwd != $passwd_);
mysql_free_result($result);
$result = mysql_query("select login from customers where email='$email' and login!='$uname'");
$eerror = !($firsttime) && !(empty($email)) && (mysql_num_rows($result) >= 1);
mysql_free_result($result);
$fillerror = !($firsttime) && (empty($uname) || empty($passwd1) || empty($passwd2) || ($passwd1 != $passwd2) || empty($fname) || empty($lname) || empty($b_address) || empty($b_city) || empty($b_state) || empty($b_country) || empty($b_zipcode) || empty($phone) || empty($email));
if (($mode == "edit") && !($firsttime)) $fillerror = $fillerror || $passwderror;
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
if ($firsttime || $uerror || $eerror || $fillerror) {
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: <? if ($mode == "edit") echo "Edit information"; else echo "Register"; ?></title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<?
include "cssstyle.php";
?>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>">
<?
include "top.php";
$tabnames = array("Shop","View cart","Order");
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
?></td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top">
<!-- main frame here -->
<table width="100%" height="100%" cellpadding="10">
<tr>
<td valign="top">
<font color="<? echo $cl_header ?>" size="+1"><b>Register/Edit</b></font><br>
<center>
<hr>
<?
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/register.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
<input type=hidden name=mode value="$mode">
EOT;
if ($mode == "edit")
	echo "<input type=hidden name=uname value=\"$uname\">";
?>
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="<? echo $cl_order_border ?>">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>">
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>
<?
if ($mode == "edit")
	echo "Customer information";
elseif ($firsttime)
	echo "Please fill out the registration form";
else
	echo "Registration form";
?>
</i></font></b></center>
</td>
</tr>

<?
if ($uerror || $eerror || $fillerror) {
	echo "<tr><td bgcolor=\"$cl_win_title\">";
	echo "<font color=\"$cl_order_red\"><b>";
	if ($uerror)
		echo "Username already exist, please select another one<br>";
	if ($eerror)
		echo "E-mail already exist<br>";
	if ($fillerror) {
		if (empty($uname)) echo "You forgot to enter Username<br>";
		if ($mode == "edit") {
			if ($passwderror) echo "Old password incorrect<br>";
			if (empty($passwd1)) echo "You forgot to enter New Password<br>";
			elseif (empty($passwd2)) echo "You forgot to confirm New Password<br>";
			elseif ($passwd1!=$passwd2) echo "Passwords don't match<br>";
		} else {
			if (empty($passwd1)) echo "You forgot to enter Password<br>";
			elseif (empty($passwd2)) echo "You forgot to confirm Password<br>";
			elseif ($passwd1!=$passwd2) echo "Passwords don't match<br>";
		}
		if (empty($fname)) echo "You forgot to enter First Name<br>";
		if (empty($lname)) echo "You forgot to enter Last Name<br>";
		if (empty($b_address)) echo "You forgot to enter Address<br>";
		if (empty($b_city)) echo "You forgot to enter City<br>";
		if (empty($b_state)) echo "You forgot to enter State<br>";
		if (empty($b_country)) echo "You forgot to enter Country<br>";
		if (empty($b_zipcode)) echo "You forgot to enter Zip Code<br>";
		if (empty($phone)) echo "You forgot to enter Phone<br>";
		if (empty($email)) echo "You forgot to enter E-Mail<br>";
	}
	echo "</b></font></td></tr>";
}
?>
<tr>
<td valign="top" bgcolor="<? echo $cl_win_title ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td colspan="3"><font size="-1">Note:&nbsp;&nbsp;<font color="<? echo $cl_order_red ?>">*</font>&nbsp;means that
field is required</font></td>
</tr>

<?
if ($mode == "edit") {
echo <<<EOT
<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Username &amp; password</font></b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Username:</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap>
<font size="3"><b>$uname</b></font>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Old Password:</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap><input type="password" name="passwd" size=
"32" maxlength="32"></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;New Password:</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap><input type="password" name="passwd1" size=
"32" maxlength="32"></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Confirm New Password:</td>
<td><font color="$cl_order_red">*</font></td>
<td nowrap><input type="password" name="passwd2" size=
"32" maxlength="32"></td>
</tr>
EOT;
} else {
echo <<<EOT
<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Select username &amp; password</font></b></td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Username:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap>
<input type="text" name="uname" size="32" maxlength="32" <? if (!($uerror)) echo "value=\"$uname\""; ?>
></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Password:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><input type="password" name="passwd1" size=
"32" maxlength="32"></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Confirm Password:</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><input type="password" name="passwd2" size=
"32" maxlength="32"></td>
</tr>
<?
}

echo <<<EOT
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

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;State</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><select name="b_state" size="1">
<option value="  " <? if ($b_state == "  ") echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($b_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
?>
</select></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Country</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap><select name="b_country" size="1">
<?
for ($i=0; $i<count($countries); $i++) {
	echo "<option value=\"$countrycodes[$i]\"";
	if ($b_country == $countrycodes[$i]) echo " selected";
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
EOT;
?>

<tr valign="middle" bgcolor="<? echo $cl_header ?>">
<td height="20" colspan="3"><b><font color="<? echo $cl_win_title ?>" size="-1">Shipping Address <? if ($mode != "edit") echo "(leave empty if same as billing address)"; ?></font></b></td>
</tr>

<?
echo <<<EOT
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

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;State</td>
<td>&nbsp;</td>
<td nowrap><select name="s_state" size="1">
<option value="  " <? if ($s_state == "  ") echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($s_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
?>
</select></td>
</tr>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Country</td>
<td>&nbsp;</td>
<td nowrap><select name="s_country" size="1">
<?
for ($i=0; $i<count($countries); $i++) {
	echo "<option value=\"$countrycodes[$i]\"";
	if ($s_country == $countrycodes[$i]) echo " selected";
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
EOT;
?>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;E-Mail</td>
<td><font color="<? echo $cl_order_red ?>">*</font></td>
<td nowrap>
<input type="text" name="email" size="32" maxlength="128" <? if (!($eerror)) echo "value=\"$email\""; ?>
</td>
</tr>

<?
if ($mode == "edit") {
echo <<<EOT
<tr valign="middle" bgcolor="$cl_header">
<td height="20" colspan="3"><b><font color="$cl_win_title" size="-1">Credit card information</font></b></td>
</tr>
EOT;
?>

<tr valign="middle" bgcolor="<? echo $cl_tab_top ?>">
<td>&nbsp;&nbsp;Card type</td>
<td>&nbsp;</td>
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
echo <<<EOT
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Card holder's name</td>
<td>&nbsp;</td>
<td nowrap>
EOT;
if (!$cc_disabled)
	echo "<input type=\"text\" name=\"card_name\" size=\"32\" maxlength=\"64\" value=\"$card_name\">";
else {
	echo "<input type=hidden name=\"card_name\" value=\"$generic_card_name\">";
	echo "$generic_card_name";
}
echo <<<EOT
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Card number</td>
<td>&nbsp;</td>
<td nowrap>
EOT;
if (!$cc_disabled)
	echo "<input type=\"text\" name=\"card_num\" size=\"20\" maxlength=\"20\" value=\"$card_num\">";
else {
	echo "<input type=hidden name=\"card_num\" value=\"$generic_card_num\">";
	echo "$generic_card_num";
}
echo <<<EOT
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td>&nbsp;&nbsp;Expiration date</td>
<td>&nbsp;</td>
<td nowrap>
EOT;
if (!$cc_disabled) {
	echo "<input type=\"text\" name=\"expmonth\" size=\"2\" maxlength=\"2\" value=\"$expmonth\">";
	echo "<input type=\"text\" name=\"expyear\" size=\"2\" maxlength=\"2\" value=\"$expyear\">";
} else {
	echo "<input type=hidden name=\"expmonth\" value=\"$generic_expmonth\">";
	echo "<input type=hidden name=\"expyear\" value=\"$generic_expyear\">";
	echo "$generic_expmonth/$generic_expyear";
}
echo <<<EOT
</td>
</tr>
EOT;
}
echo <<<EOT
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

<br>
EOT;
?>

<div align="center"><font><b>
<?
if ($mode == "edit") echo "<input type=\"submit\" value=\"Update\"></b></font></div>";
else echo "<input type=\"submit\" value=\"Submit\"></b></font></div>";
?>
</form>

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
<?
} else {
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
	if ($mode == "edit") {
		mysql_query("update customers set password='$crypted',firstname='$fname',lastname='$lname',b_address='$b_address',b_city='$b_city',b_state='$b_state',b_country='$b_country',b_zipcode='$b_zipcode',s_address='$s_address',s_city='$s_city',s_state='$s_state',s_country='$s_country',s_zipcode='$s_zipcode',phone='$phone',email='$email',card_type='$card_type',card_name='$card_name',card_number='$card_num',card_expire='$expires' where userid='$id'") or die ("$mysql_error_msg");
		header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/message.php?first=$first&sortby=$sortby&category=".urlencode($category)."&black=1&text=".urlencode("Your data has been saved! Please continue shopping."));
	} else {
		mysql_query("insert into customers (login,password,userid,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,card_type,card_name,card_number,card_expire) values ('$uname','$crypted','$id','$fname','$lname','$b_address','$b_city','$b_state','$b_country','$b_zipcode','$s_address','$s_city','$s_state','$s_country','$s_zipcode','$phone','$email','$card_type','$card_name','$card_num','$expires')") or die ("$mysql_error_msg");
		header("Location: ".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/message.php?first=$first&sortby=$sortby&category=".urlencode($category)."&black=1&text=".urlencode("Thank you for registering! Please continue shopping."));
		}
}
?>
