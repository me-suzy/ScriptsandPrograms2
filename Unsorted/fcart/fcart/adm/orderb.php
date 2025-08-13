<?
include "../config.php";
include "../mod.php";
include "auth.php";

$orderid = d_secure($HTTP_GET_VARS["orderid"]);

?>
<html>
<head>
<title>Order information</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<script language="javascript">
function expandwindow() {
    if (self.document.height) {
        doc_height = self.document.height;
        doc_width = self.document.width;
        self.resizeTo(doc_width, doc_height);
    }
}
</script>
<? include "../cssstyle.php"; ?>
</head>
<body bgcolor="<? echo $cl_tab_top ?>" onLoad="expandwindow()">
<?
$cardtypes = array(1=>"Visa",2=>"Mastercard",3=>"Discover",4=>"American Express");
$result = mysql_query("select login,order_total,order_discount,order_disc_coupon,order_shipping,order_date,order_state,order_flag,firstname,lastname,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,card_type,card_name,card_number,card_expire from orders where orderid='$orderid'");
list($uname,$total,$discount,$coupon,$shipping,$date,$ostate,$orderflag,$fname,$lname,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$s_address,$s_city,$s_state,$s_country,$s_zipcode,$phone,$email,$card_type,$card_name,$card_num,$card_exp) = @mysql_fetch_row($result);
mysql_free_result($result);
$result = mysql_query("select state from states where code='$b_state'");
list($b_state) = mysql_fetch_row($result);
if (empty($b_state)) $b_state = "Non-US";
mysql_free_result($result);
$result = mysql_query("select country from countries where code='$b_country'");
list($b_country) = mysql_fetch_row($result);
if (empty($b_country)) $b_country = "Unknown";
mysql_free_result($result);
$result = mysql_query("select state from states where code='$s_state'");
list($s_state) = mysql_fetch_row($result);
if (empty($s_state)) $s_state = "Non-US";
mysql_free_result($result);
$result = mysql_query("select country from countries where code='$s_country'");
list($s_country) = mysql_fetch_row($result);
if (empty($s_country)) $s_country = "Unknown";
mysql_free_result($result);
$card_type = $cardtypes[$card_type];
if (!empty($coupon)) {
	$result = mysql_query("select discount,type from discount_coupons where coupon='$coupon'");
	if (mysql_num_rows($result) == 1)
		list($disc_discount,$disc_type) = @mysql_fetch_row($result);
	mysql_free_result($result);
}

echo "<center>";
echo "<form method=\"POST\" name=\"send\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/orderb_send.php\">";
echo <<<EOT
<table width="100%" border="0" cellspacing="0" cellpadding="1">

<tr><td nowrap>&nbsp;<b>Order ID:</b></td><td nowrap>$orderid</td></tr>
<tr><td nowrap>&nbsp;<b>Username:</b></td><td nowrap>$uname</td></tr>
<tr><td nowrap>&nbsp;<b>Discount:</b></td><td nowrap>\$$discount</td></tr>
<tr><td nowrap>&nbsp;<b>Discount coupon:</b></td><td nowrap>
EOT;
switch ($disc_type) {
case "Fixed":
	echo "\$$disc_discount";
	break;
case "Percent":
	$disc_discount = (float)$disc_discount;
	echo "$disc_discount%";
	break;
default:
	echo "None";
}
echo <<<EOT
</td></tr>
<tr><td nowrap>&nbsp;<b>Shipping cost:</b></td><td nowrap>\$$shipping</td></tr>
<tr><td nowrap>&nbsp;<b>Total:</b></td><td nowrap>\$$total</td></tr>
<tr><td nowrap>&nbsp;<b>Date:</b></td><td nowrap>$date</td></tr>
<tr><td nowrap>&nbsp;<b>Status:</b></td><td nowrap>$ostate</td></tr>
<tr><td nowrap>&nbsp;<b>First name:</b></td><td nowrap>$fname</td></tr>
<tr><td nowrap>&nbsp;<b>Last name:</b></td><td nowrap>$lname</td></tr>
<tr><td nowrap colspan="2">&nbsp;<b>Billing address:</b></td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;Street:</td><td nowrap>$b_address</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;City:</td><td nowrap>$b_city</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;State:</td><td nowrap>$b_state</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;Country:</td><td nowrap>$b_country</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;Zipcode:</td><td nowrap>$b_zipcode</td></tr>
<tr><td nowrap colspan="2">&nbsp;<b>Shipping address:</b></td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;Street:</td><td nowrap>$s_address</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;City:</td><td nowrap>$s_city</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;State:</td><td nowrap>$s_state</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;Country:</td><td nowrap>$s_country</td></tr>
<tr><td nowrap>&nbsp;&nbsp;&nbsp;Zipcode:</td><td nowrap>$s_zipcode</td></tr>
<tr><td nowrap>&nbsp;<b>Phone:</b></td><td nowrap>$phone</td></tr>
<tr><td nowrap>&nbsp;<b>E-Mail:</b></td><td nowrap>$email</td></tr>
EOT;
if ($orderflag != "Reward") {
echo <<<EOT
<tr><td nowrap>&nbsp;<b>Card type:</b></td><td nowrap>$card_type</td></tr>
<tr><td nowrap>&nbsp;<b>Name on card:</b></td><td nowrap>$card_name</td></tr>
<tr><td nowrap>&nbsp;<b>Card number:</b></td><td nowrap>$card_num</td></tr>
<tr><td nowrap>&nbsp;<b>Card expires:</b></td><td nowrap>$card_exp</td></tr>
EOT;
}
echo <<<EOT
<tr><td colspan="2"><hr></td></tr>
EOT;
if ($orderflag == "Gift")
	echo "<tr><td nowrap colspan=\"2\" align=\"center\"><b>Order was made with gift certificate.</b></td></tr><tr><td colspan=\"2\"><hr></td></tr>";
elseif ($orderflag == "Reward")
	echo "<tr><td nowrap colspan=\"2\" align=\"center\"><b>Order was made with $bonus_points.</b></td></tr><tr><td colspan=\"2\"><hr></td></tr>";

echo <<<EOT
</table>
<input type=hidden name=orderid value="$orderid">
<input type=hidden name=uname value="$uname">
<input type=hidden name=total value="$total">
<input type=hidden name=discount value="$discount">
<input type=hidden name=disc_type value="$disc_type">
<input type=hidden name=disc_discount value="$disc_discount">
<input type=hidden name=shipping value="$shipping">
<input type=hidden name=date value="$date">
<input type=hidden name=ostate value="$ostate">
<input type=hidden name=fname value="$fname">
<input type=hidden name=lname value="$lname">
<input type=hidden name=b_address value="$b_address">
<input type=hidden name=b_city value="$b_city">
<input type=hidden name=b_state value="$b_state">
<input type=hidden name=b_country value="$b_country">
<input type=hidden name=b_zipcode value="$b_zipcode">
<input type=hidden name=s_address value="$s_address">
<input type=hidden name=s_city value="$s_city">
<input type=hidden name=s_state value="$s_state">
<input type=hidden name=s_country value="$s_country">
<input type=hidden name=s_zipcode value="$s_zipcode">
<input type=hidden name=phone value="$phone">
<input type=hidden name=email value="$email">
<input type=hidden name=card_type value="$card_type">
<input type=hidden name=card_name value="$card_name">
<input type=hidden name=card_num value="$card_num">
<input type=hidden name=card_exp value="$card_exp">
<input type=hidden name=orderflag value="$orderflag">
<input type=hidden name=mode value="none">
<font size="-1"><b>
<input type="button" value="Print order" onClick="document.send.mode.value='print'; document.send.submit()"><br><br>
<input type="button" value="Send shipping information" onClick="document.send.mode.value='shipping'; document.send.submit()"><br><br>
<input type="button" value="Send decline notification" onClick="document.send.mode.value='declined'; document.send.submit()"><br><br>
<input type="button" value="Send discount coupon" onClick="document.send.mode.value='coupon'; document.send.submit()"><br><hr>
<input type="button" value="Close" onClick="javascript:self.close()">
</b></font></form>
</center>
EOT;
?>
</body></html>
