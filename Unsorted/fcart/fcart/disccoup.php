<?
list($discount,$shipping,$total) = calc_sum($sum);
if ($coupon != "") {
	$result1 = mysql_query("select discount,type from discount_coupons where coupon='$coupon' and now()<expire and count>0");
	if (mysql_num_rows($result1) != 1)
		$coupon = "";
	else
		list($disc_discount,$disc_type) = @mysql_fetch_row($result1);
	mysql_free_result($result1);
}
if ($coupon != "")
switch ($disc_type) {
case "Fixed":
	if ($total<=$disc_discount)
		$coupon="";
	else
		$total -= $disc_discount;
	break;
case "Percent":
	$disc_discount = (float)$disc_discount;
	$total = ceil($total*(100-$disc_discount))/100;
	break;
}
echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";
echo "<tr><td nowrap align=\"left\" valign=\"top\"><b>Calculated sum: \$$sum<br>";
if ($discount > 0) echo "Discount: \$$discount<br>";
echo "Shipping cost: \$$shipping<br>";
if (!empty($coupon)) {
	echo "<font color=\"$cl_discount\">Discount coupon: ";
	switch ($disc_type) {
	case "Fixed":
		echo "\$$disc_discount";
		break;
	case "Percent":
		echo "$disc_discount%";
		break;
	}
	echo "</font><br>\n";
}
echo "<font color=\"$cl_order_total\">Total: \$$total";
if ($gift_log && ($total > $bucks))
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;!!!  You have exceeded your Gift Certificate amount  !!!<br>";
echo "</b></td>";
echo "<td nowrap width=\"1%\" valign=\"top\">";
if (empty($coupon) && empty($dont_display_disc_coupon)) {
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/disc_enter.php\">";
if ($transfer_cookie)
	echo "<input type=hidden name=id value=\"$id\">";
echo <<<EOT
<input type=hidden name="first" value="$first">
<input type=hidden name="sortby" value="$sortby">
<input type=hidden name="category" value="$category">
<table width="100%" border="0" bgcolor="$cl_order_border" cellpadding="1" cellspacing="0"><tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="2" height="100%">
<tr bgcolor="$cl_win_cap1">
<td nowrap colspan="2"><font color="$cl_win_title" size="-1"><b>Enter discount coupon</b></font></td></tr>
<tr bgcolor="$cl_win_tab">
<td nowrap><font size="-1"><input type="text" name="coupon" maxlength="32" size="16"></font></td>
<td nowrap align="right"><font size="-1"><b><input type="submit" value="Submit"></b></font></td>
</tr>
</table>
</td>
</tr>
</table>
</form>
EOT;
} else {
	echo "&nbsp;";
}
?>
</td></tr></table><br>
