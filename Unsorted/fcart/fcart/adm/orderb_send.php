<?
include "../config.php";
include "../mod.php";
include "auth.php";
include "../processt.php";

$orderid = r_secure($HTTP_POST_VARS["orderid"]);
$uname = r_secure($HTTP_POST_VARS["uname"]);
$total = r_secure($HTTP_POST_VARS["total"]);
$discount = r_secure($HTTP_POST_VARS["discount"]);
$disc_type = r_secure($HTTP_POST_VARS["disc_type"]);
$disc_discount = r_secure($HTTP_POST_VARS["disc_discount"]);
$shipping = r_secure($HTTP_POST_VARS["shipping"]);
$date = r_secure($HTTP_POST_VARS["date"]);
$ostate = r_secure($HTTP_POST_VARS["ostate"]);
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
$card_type = r_secure($HTTP_POST_VARS["card_type"]);
$card_name = r_secure($HTTP_POST_VARS["card_name"]);
$card_num = r_secure($HTTP_POST_VARS["card_num"]);
$card_exp = r_secure($HTTP_POST_VARS["card_exp"]);
$orderflag = r_secure($HTTP_POST_VARS["orderflag"]);
$mailfrom = r_secure($HTTP_POST_VARS["mailfrom"]);
$mailto = r_secure($HTTP_POST_VARS["mailto"]);
$mailsubj = r_secure($HTTP_POST_VARS["mailsubj"]);
$mailbody = r_secure($HTTP_POST_VARS["mailbody"]);

$recipient = r_secure($HTTP_POST_VARS["recipient"]);
$disc_discount = r_secure($HTTP_POST_VARS["disc_discount"]);
$disc_type = r_secure($HTTP_POST_VARS["disc_type"]);
$disc_count = r_secure($HTTP_POST_VARS["disc_count"]);
$day = r_secure($HTTP_POST_VARS["day"]);
$month = r_secure($HTTP_POST_VARS["month"]);
$year = r_secure($HTTP_POST_VARS["year"]);

switch ($mode) {
case "coupon":
	$fillerror = empty($recipient) || empty($disc_discount) || empty($disc_type) || empty($disc_count) || empty($day) || empty($month) || empty($year);
	if ($fillerror) {
		$recipient = "$fname $lname <$email>";
		$disc_count = 1;
		$date = mktime(0,0,0,date("m")+1,date("d"),date("Y"));
		$day = date("d",$date);
		$month = date("m",$date);
		$year = date("Y",$date);
	}
	break;
default:
	$fillerror = empty($mailfrom) || empty($mailto) || empty($mailsubj) || empty($mailbody);
}

?>
<html>
<head>
<title>
<? if ($mode == "shipping") echo "Send shipping information";
elseif ($mode == "declined") echo "Send decline notification";
elseif ($mode == "coupon") echo "Send discount coupon";
elseif ($mode == "print") echo "Print order";
?>
</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "../cssstyle.php"; ?>
<SCRIPT LANGUAGE="JavaScript">
<!--
var da = (document.all) ? 1 : 0;
var pr = (window.print) ? 1 : 0;
var mac = (navigator.userAgent.indexOf("Mac") != -1);
function printPage() {
  if (pr) // NS4, IE5
    window.print()
  else if (da && !mac) // IE4 (Windows)
    vbPrintPage()
  else // other browsers
    alert("Sorry, your browser doesn't support this feature.");
	return false;
}
  if (da && !pr && !mac) with (document) {
  writeln('<OBJECT ID="WB" WIDTH="0" HEIGHT="0" CLASSID="clsid:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>');
  writeln('<' + 'SCRIPT LANGUAGE="VBScript">');
  writeln('Sub window_onunload');
  writeln('  On Error Resume Next');
  writeln('  Set WB = nothing');
  writeln('End Sub');
  writeln('Sub vbPrintPage');
  writeln('  OLECMDID_PRINT = 6');
  writeln('  OLECMDEXECOPT_DONTPROMPTUSER = 2');
  writeln('  OLECMDEXECOPT_PROMPTUSER = 1');
  writeln('  On Error Resume Next');
  writeln('  WB.ExecWB OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER');
  writeln('End Sub');
  writeln('<' + '/SCRIPT>');
}
// -->
</SCRIPT>
</head>
<body bgcolor="<? echo $cl_tab_top ?>" <? if ($mode=="coupon") echo "onLoad=\"self.resizeTo(550, 240)\""; elseif ($mode=="print") echo "onLoad=\"self.resizeTo(600,400); return printPage()\""; else echo "onLoad=\"self.resizeTo(600, 550)\""; ?>>
<center>
<?

if ($mode == "print") {
	echo "</center>";
	echo <<<EOT
<font size="3" face="Courier">
<table border="0" cellspacing="0" cellpadding="3">
<tr><td nowrap><b>OrderID:</b></td><td nowrap>$orderid</td></tr>
<tr><td nowrap><b>Discount:</b></td><td nowrap>\$$discount</td></tr>
<tr><td nowrap><b>Discount coupon:</b></td><td nowrap>
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
<tr><td nowrap><b>Shipping:</b></td><td nowrap>\$$shipping</td></tr>
<tr><td nowrap><b>Total:</b></td><td nowrap>\$$total</td></tr>
<tr><td nowrap><b>Date:</b></td><td nowrap>$date</td></tr>
<tr><td nowrap><b>Status:</b></td><td nowrap>$ostate</td></tr>
<tr><td nowrap colspan="2">&nbsp;</td></tr>
<tr><td nowrap colspan="2"><b><u>Customer information:</u></b></td></tr>
<tr><td nowrap><b>Username:</b></td><td nowrap>$uname</td></tr>
<tr><td nowrap><b>First Name:</b></td><td nowrap>$fname</td></tr>
<tr><td nowrap><b>Last Name:</b></td><td nowrap>$lname</td></tr>
<tr><td nowrap colspan="2"><b>Billing address:</b></td></tr>
<tr><td nowrap>&nbsp;&nbsp;Street:</td><td nowrap>$b_address<br>
<tr><td nowrap>&nbsp;&nbsp;City:</td><td nowrap>$b_city</td></tr>
<tr><td nowrap>&nbsp;&nbsp;State:</td><td nowrap>$b_state</td></tr>
<tr><td nowrap>&nbsp;&nbsp;Country:</td><td nowrap>$b_country</td></tr>
<tr><td nowrap>&nbsp;&nbsp;Zip Code:</td><td nowrap>$b_zipcode</td></tr>
<tr><td nowrap colspan="2"><b>Shipping address:</b></td></tr>
<tr><td nowrap>&nbsp;&nbsp;Street:</td><td nowrap>$s_address</td></tr>
<tr><td nowrap>&nbsp;&nbsp;City:</td><td nowrap>$s_city</td></tr>
<tr><td nowrap>&nbsp;&nbsp;State:</td><td nowrap>$s_state</td></tr>
<tr><td nowrap>&nbsp;&nbsp;Country:</td><td nowrap>$s_country</td></tr>
<tr><td nowrap>&nbsp;&nbsp;Zip Code:</td><td nowrap>$s_zipcode</td></tr>
<tr><td nowrap><b>Phone:</b></td><td nowrap>$phone</td></tr>
<tr><td nowrap><b>E-Mail:</b></td><td nowrap>$email</td></tr>
EOT;
if ($orderflag != "Reward") {
echo <<<EOT
<tr><td nowrap><b>Card type:</b></td><td nowrap>$card_type</td></tr>
<tr><td nowrap><b>Name on card:</b></td><td nowrap>$card_name</td></tr>
<tr><td nowrap><b>Card number:</b></td><td nowrap>$card_num</td></tr>
<tr><td nowrap><b>Exp. date:</b></td><td nowrap>$card_exp</td></tr>
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
<p>
<b><u>Products ordered:</u></b><p>
<table border="1" cellspacing="1" cellpadding="2" width="100%">
<tr bgcolor="$cl_tab_back">
<td nowrap width="1%"><font size="-1">ProductID</font></td>
<td nowrap><font size="-1">Product</font></td>
<td nowrap width="1%"><font size="-1">Price</font></td>
<td nowrap width="1%"><font size="-1">Amount</font></td>
</tr>
EOT;
	$result = mysql_query("select order_details.product,order_details.price,order_details.amount,products.product from order_details,products where orderid='$orderid' and order_details.product=products.productid");
	while (list($productid,$price,$amount,$product) = mysql_fetch_row($result)) {
	echo <<<EOT
<tr>
<td nowrap width="1%"><font size="-1">$productid</font></td>
<td nowrap><font size="-1">$product</font></td>
<td nowrap width="1%"><font size="-1">$price</font></td>
<td nowrap width="1%"><font size="-1">$amount</font></td>
</tr>
EOT;
	}
	mysql_free_result($result);
	echo "</table></font><center>";
} else {
if ($mode == "coupon") {
	echo "<form method=\"POST\" name=\"send\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/orderb_send.php\">";
	echo "<input type=hidden name=\"mode\" value=\"coupon\">";
	if (!$fillerror) {
		$coupon = md5(uniqid(rand().getmypid()));
		$coupon = r_secure($coupon);

		mysql_query("insert into discount_coupons (coupon,discount,type,count,expire) values ('$coupon','$disc_discount','$disc_type','$disc_count','$year-$month-$day')") or die ("$mysql_error_msg");
		process_template_coupon($mail_disc_coupon,$coupon,$recipient,$disc_discount,$disc_type,$disc_count,$day,$month,$year);
		process_template_coupon($mail_disc_coupon_subj,$coupon,$recipient,$disc_discount,$disc_type,$disc_count,$day,$month,$year);
		$result = mail($recipient,$mail_disc_coupon_subj,$mail_disc_coupon,"From: $orders_email\nReply-To: $orders_email\nX-Mailer: PHP/".phpversion());
		if ($result)
			echo "<b>Mail sent!</b>";
		else
			echo "<font color=\"red\"><b>Error while sending mail! Mail not sent!</b></font>";
		echo "<br>";
	} else {	
echo "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">";
include "send_disc.php";
echo "</table><hr><font size=\"-1\"><b>";
echo "<input type=\"button\" value=\"Send\" onClick=\"javascript:document.send.submit()\">&nbsp;&nbsp;&nbsp;&nbsp;";
echo "</b></font>";
	}
} else { # mode!=coupon
if (!$fillerror) {
	$result = mail($mailto,$mailsubj,$mailbody,"From: $mailfrom\nReply-To: $mailfrom\nX-Mailer: PHP/".phpversion());
	if ($result)
		echo "<b>Mail sent!</b>";
	else
		echo "<font color=\"red\"><b>Error while sending mail! Mail not sent!</b></font>";
	echo "<form>";
} else {
	$mailfrom = $orders_email;
	$mailto = "$fname $lname <$email>";
	if ($mode == "shipping") {
		$mailsubj = $mail_shipping_subj;
		$mailbody = $mail_shipping;
		$mail_pl = $mail_shipping_pl;
	} elseif ($mode == "declined") {
		$mailsubj = $mail_decline_subj;
		$mailbody = $mail_decline;
		$mail_pl = $mail_decline_pl;
	}
	$products = "";
	$result = mysql_query("select order_details.product,order_details.price,order_details.amount,products.product from order_details,products where orderid='$orderid' and order_details.product=products.productid");
	while (list($productid,$price,$amount,$product) = mysql_fetch_row($result)) {
		make_products_line($products,$mail_pl,$productid,$amount,$price,$product);
	}
	mysql_free_result($result);
	process_template($mailsubj,$orderid,$date,$ostate,$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$s_address,$s_city,$s_state,$s_country,$s_zipcode,$phone,$email,$card_type,$card_name,$card_num,$card_exp,$discount,$disc_type,$disc_discount,$shipping,$total,"",$products);
	process_template($mailbody,$orderid,$date,$ostate,$orderflag,$uname,$fname,$lname,$b_address,$b_city,$b_state,$b_country,$b_zipcode,$s_address,$s_city,$s_state,$s_country,$s_zipcode,$phone,$email,$card_type,$card_name,$card_num,$card_exp,$discount,$disc_type,$disc_discount,$shipping,$total,"",$products);
echo "<form method=\"POST\" name=\"send\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/orderb_send.php\">";
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1">
<tr valign="middle"><td width="1%"><b>From:</b></td><td><input type="text" name="mailfrom" size="40" maxlength="40" value="$mailfrom"></td></tr>
<tr valign="middle"><td width="1%"><b>To:</b></td><td><input type="text" name="mailto" size="40" maxlength="40" value="$mailto"></td></tr>
<tr valign="middle"><td width="1%"><b>Subj:</b></td><td><input type="text" name="mailsubj" size="40" maxlength="40" value="$mailsubj"></td></tr>
<tr valign="top"><td width="1%"><b>Text:</b></td><td><textarea name="mailbody" rows="20" cols="60">$mailbody</textarea></td></tr>
</table>
</form>
<font size="-1"><b>
<form>
<input type="button" value="Send mail" onClick="javascript:document.send.submit()">
</b></font>&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
}
}
echo <<<EOT
<font size="-1"><b>
<input type="button" value="Close" onClick="javascript:self.close()">
</b></font></form>
</center>
EOT;
}
?>
</body></html>
