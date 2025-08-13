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
	$catt = r_secure($HTTP_POST_VARS["catt"]);
	$product = r_secure($HTTP_POST_VARS["product"]);
	$totalfrom = r_secure($HTTP_POST_VARS["totalfrom"]);
	$totalto = r_secure($HTTP_POST_VARS["totalto"]);
	$dayfrom = d_secure($HTTP_POST_VARS["dayfrom"]);
	$monthfrom = d_secure($HTTP_POST_VARS["monthfrom"]);
	$yearfrom = d_secure($HTTP_POST_VARS["yearfrom"]);
	$dayto = d_secure($HTTP_POST_VARS["dayto"]);
	$monthto = d_secure($HTTP_POST_VARS["monthto"]);
	$yearto = d_secure($HTTP_POST_VARS["yearto"]);
	$orderstate = r_secure($HTTP_POST_VARS["orderstate"]);
	$orderflag = r_secure($HTTP_POST_VARS["orderflag"]);
} else {
	$firsttime=true;
	$dayto = 31;
	$monthto = 12;
	$yearto = 2005;
	include "params.php";
}

$months = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
$orderstates = array("Queued","Processed","Shipped","Cancelled");
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: User orders</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "cssstyle.php" ?>
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
<?
#echo "<td width=\"10%\" bgcolor=\"$cl_left_tab\" valign=\"top\" rowspan=\"2\">";
#include("login.php");
#include("cat.php");
#include("searchform.php");
#include("help.php");
#include "poweredby.php";
#echo "</td>";
$c_result = mysql_query("select category, count(*) from products group by category");
$tabcount++;
?>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top">
<!-- main frame here -->
<table width="100%" height="100%" cellpadding="10">
<tr>
<td valign="top">
<center>
<hr>

<?
if (!($firsttime)) {
?>
<script language='javascript'>
function display_order(orderid, total, discount, disc_discount, shipping, date, ostate, orderflag, fname, lname, s_address, s_city, s_state, s_country, s_zipcode, email) {
	window1 = window.open("", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=320,height=490") ;
	window1.document.open("text/html","replace") ;
	window1.document.write("<html><head><title>Order information</title>");
	
	window1.document.write("<script language=javascript>");	
	window1.document.write("function expandwindow() { if (self.document.height) { doc_height = self.document.height; doc_width = self.document.width; self.resizeTo(doc_width, doc_height); } }");
	window1.document.write("<\/script>");	
	window1.document.write("</head><body bgcolor=\"<? echo $cl_tab_top ?>\" onLoad=\"expandwindow()\"><center>") ;
	window1.document.write("<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Order ID:</b></td><td nowrap>"+orderid+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Order date:</b></td><td nowrap>"+date+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Discount:</b></td><td nowrap>$"+discount+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Shipping cost:</b></td><td nowrap>$"+shipping+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Discount coupon:</b></td><td nowrap>"+disc_discount+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Total:</b></td><td nowrap>$"+total+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Order status:</b></td><td nowrap>"+ostate+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>First name:</b></td><td nowrap>"+fname+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>Last name:</b></td><td nowrap>"+lname+"</td></tr>") ;
	window1.document.write("<tr><td nowrap colspan=\"2\" align=\"left\">&nbsp;<b>Shipping address:</b></td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;&nbsp;&nbsp;Street:</td><td nowrap>"+s_address+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;&nbsp;&nbsp;City:</td><td nowrap>"+s_city+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;&nbsp;&nbsp;State:</td><td nowrap>"+s_state+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;&nbsp;&nbsp;Country:</td><td nowrap>"+s_country+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;&nbsp;&nbsp;Zip code:</td><td nowrap>"+s_zipcode+"</td></tr>") ;
	window1.document.write("<tr><td nowrap>&nbsp;<b>E-Mail:</b></td><td nowrap>"+email+"</td></tr>") ;
	window1.document.write("<tr><td colspan=\"2\"><hr></td></tr>") ;
	if (orderflag=='Gift') {
		window1.document.write("<tr><td nowrap colspan=\"2\" align=\"center\"><b>Order was made with gift certificate.</b></td></tr>")
	}
	if (orderflag=='Reward') {
		window1.document.write("<tr><td nowrap colspan=\"2\" align=\"center\"><b>Order was made with <? echo $bonus_points ?>.</b></td></tr>")
	}
	window1.document.write("</table><form><font size=\"-1\"><b><input type=\"button\" value=\"Close\" onClick=\"javascript:self.close()\"></b></font></form></center></body></html>") ;
	window1.document.close() ;
}
function display_product(productid) {
	window2 = window.open("<? echo "http://$http_location/"; ?>productb.php?productid="+productid, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=550,height=380") ;
	window2.focus() ;
}
</script>
<?
if (strlen($expmonth) == 1) $expmonth = "0".$expmonth;
if (strlen($expyear) == 1) $expyear = "0".$expyear;
$expires = $expmonth.$expyear;
$datefrom = date("YmdHis",mktime(0,0,0,$monthfrom,$dayfrom,$yearfrom));
$dateto = date("YmdHis",mktime(23,59,59,$monthto,$dayto,$yearto));
$result = mysql_query("select orders.orderid,order_total,order_discount,order_disc_coupon,order_shipping,order_date,order_state,orders.order_flag,orders.firstname,orders.lastname,orders.s_address,orders.s_city,orders.s_state,orders.s_country,orders.s_zipcode,orders.email,order_details.product,products.product,order_details.price,order_details.amount from customers,orders,order_details,products where ".
	($totalfrom == "" ? "" : "order_total>='$totalfrom' and ").
	($totalto == "" ? "" : "order_total<='$totalto' and ").
	"order_date>='$datefrom' and ".
	"order_date<='$dateto' and ".
	($orderstate == "All" ? "" : "order_state='$orderstate' and ").
	($product == "" ? "" : "(products.product like '%$product%' or products.descr like '%$product%') and ").
	($catt == "All" ? "" : "products.category='$catt' and ").
	($orderflag == "All" ? "" : "order_flag='$orderflag' and ").
	" customers.userid='$id' and orders.login=customers.login and orders.orderid=order_details.orderid and order_details.product=products.productid order by orderid limit $items_per_orders_page");
echo <<<EOT
<table border="1" cellspacing="1" cellpadding="2" width="100%" bgcolor="$cl_doc_bg">
<tr bgcolor="$cl_tab_back">
<td width="1%"><font size="-1">OrderID</font></td>
<td nowrap width="1%"><font size="-1">Total</font></td>
<td nowrap width="1%"><font size="-1">Date</font></td>
<td nowrap width="1%"><font size="-1">Feature</font></td>
<td nowrap width="1%"><font size="-1">Status</font></td>
<td nowrap><font size="-1">Product</font></td>
<td nowrap width="1%"><font size="-1">Price</font></td>
<td nowrap width="1%"><font size="-1">Amount</font></td>
</tr>
EOT;
$oarray = array();
while (list($_orderid) = mysql_fetch_row($result)) {
	array_push($oarray,$_orderid);
	}
if (mysql_num_rows($result) > 0) mysql_data_seek($result,0);
$i = 0;
$old_orderid = 0;
$totalsum = 0;
$pricesum = 0;
while (list($_orderid,$_total,$_discount,$_coupon,$_shipping,$_date,$_ostate,$_orderflag,$_fname,$_lname,$_s_address,$_s_city,$_s_state,$_s_country,$_s_zipcode,$_email,$_productid,$_product,$_price,$_amount) = mysql_fetch_row($result)) {
	$_product = trimm($_product,36);
	$result1 = mysql_query("select state from states where code='$_s_state'");
	list($_s_state) = mysql_fetch_row($result1);
	if (empty($_s_state)) $_s_state = "Non-US";
	mysql_free_result($result1);
	$result1 = mysql_query("select country from countries where code='$_s_country'");
	list($_s_country) = mysql_fetch_row($result1);
	if (empty($_s_country)) $_s_country = "Unknown";
	mysql_free_result($result1);
	$_disc_discount = $_disc_type = "";
	if (!empty($_coupon)) {
		$result1 = mysql_query("select discount,type from discount_coupons where coupon='$_coupon'");
		if (mysql_num_rows($result1) == 1)
			list($_disc_discount,$_disc_type) = @mysql_fetch_row($result1);
	}
	switch ($_disc_type) {
	case "Fixed":
		$disc_discount = "\$$_disc_discount";
		break;
	case "Percent":
		$_disc_discount = (float)$_disc_discount;
		$disc_discount = "$_disc_discount%";
		break;
	default:
		$disc_discount = "None";
	}
	$pricesum += $_price;
	if ($_orderid != $old_orderid) {
		$totalsum += $_total;
		$cnt = 0;
		for (; $oarray[$i] == $_orderid; $i++) $cnt++;
		echo <<<EOT
<tr><td nowrap align="center" rowspan="$cnt"><a href="javascript:display_order('$_orderid','$_total','$_discount','$disc_discount','$_shipping','$_date','$_ostate','$_orderflag','$_fname','$_lname','$_s_address','$_s_city','$_s_state','$_s_country','$_s_zipcode','$_email')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_orderid</font></a></td>
<td nowrap align="right" rowspan="$cnt"><a href="javascript:display_order('$_orderid','$_total','$_discount','$disc_discount','$_shipping','$_date','$_ostate','$_orderflag','$_fname','$_lname','$_s_address','$_s_city','$_s_state','$_s_country','$_s_zipcode','$_email')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_total</font></a></td>
<td nowrap rowspan="$cnt"><a href="javascript:display_order('$_orderid','$_total','$_discount','$disc_discount','$_shipping','$_date','$_ostate','$_orderflag','$_fname','$_lname','$_s_address','$_s_city','$_s_state','$_s_country','$_s_zipcode','$_email')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_date</font></a></td>
<td align="center" rowspan="$cnt"><a href="javascript:display_order('$_orderid','$_total','$_discount','$disc_discount','$_shipping','$_date','$_ostate','$_orderflag','$_fname','$_lname','$_s_address','$_s_city','$_s_state','$_s_country','$_s_zipcode','$_email')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_orderflag</font></a>&nbsp;</td>
<td height="1%" nowrap align="left" rowspan="$cnt"><a href="javascript:display_order('$_orderid','$_total','$_discount','$disc_discount','$_shipping','$_date','$_ostate','$_orderflag','$_fname','$_lname','$_s_address','$_s_city','$_s_state','$_s_country','$_s_zipcode','$_email')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_ostate</font></a></td>
EOT;
	}
	echo <<<EOT
<td nowrap align="left"><a href="javascript:display_product('$_productid')" onMouseOver="window.status='Click to display product details';return true"><font size="-1">$_product</font></a></td>
<td nowrap align="right"><font size="-1">$_price</font></td>
<td nowrap align="right"><font size="-1">$_amount</font></td>
</tr>
EOT;
	$old_orderid = $_orderid;
}
echo <<<EOT
<tr bgcolor="$cl_tab_back">
<td>&nbsp;</td>
<td nowrap><font size="-1"><b>$totalsum</b></font></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td nowrap><font size="-1"><b>$pricesum</b></font></td>
<td>&nbsp;</td>
</tr>
</table><br>
EOT;
$rows = mysql_num_rows($result);
echo "<font size=\"3\"><b><i>$rows records found</i></b></font>";
mysql_free_result($result);
}
echo "<form method=\"POST\" action=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/orders.php\">";
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
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>Display orders:</i></font></b></center>
</td>
</tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td height="40" nowrap align="center"><font size="-1">&nbsp;&nbsp;Category&nbsp;
<font size="-2">
<select name="catt">
EOT;
?>
<option value="All" <? if ($catt == "All") echo "selected"; ?>>All</option>
<?
	mysql_data_seek($c_result,0);
	while ($row = mysql_fetch_row($c_result)) {
		$r = unquote($row[0]);
		echo "<option value=\"$r\"";
		if ($catt == $r) echo " selected";
		echo ">$r</option>\n";
	}
echo <<<EOT
</select>
</font>
&nbsp;&nbsp;&nbsp;Product
<input type="text" name="product" size="10" maxlength="32" value="$product">
&nbsp;&nbsp;&nbsp;Total, from&nbsp;$
<input type="text" name="totalfrom" size="4" maxlength="10" value="$totalfrom">
&nbsp;to&nbsp;$
<input type="text" name="totalto" size="4" maxlength="10" value="$totalto">
&nbsp;&nbsp;&nbsp;Feature:<select name="orderflag" size="1">
EOT;
?>
<option value="All" <? if ($orderflag == "All") echo "selected"; ?> >All</option>
<option value="Gift" <? if ($orderflag == "Gift") echo "selected"; ?> >Gift</option>
<option value="Reward" <? if ($orderflag == "Reward") echo "selected"; ?> >Reward</option>
<?
echo <<<EOT
</select></font>
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td height="40" nowrap align="center"><font size="-1">
&nbsp;&nbsp;Date, from&nbsp;
<select name="dayfrom" size="1">
EOT;
for ($i=1; $i<=31; $i++) {
	echo "<option value=\"$i\"";
	if ($dayfrom == $i) echo " selected";
	echo ">$i</option>";
}
echo "</select><select name=\"monthfrom\" size=\"1\">";
for ($i=1; $i<=count($months); $i++) {
	echo "<option value=\"$i\"";
	if ($monthfrom == $i) echo " selected";
	echo ">$months[$i]</option>";
}
echo "</select><select name=\"yearfrom\" size=\"1\">";
for ($i=2000; $i<=2005; $i++) {
	echo "<option value=\"$i\"";
	if ($yearfrom == $i) echo " selected";
	echo ">$i</option>";
}
echo "</select>&nbsp;&nbsp;to&nbsp;<select name=\"dayto\" size=\"1\">";
for ($i=1; $i<=31; $i++) {
	echo "<option value=\"$i\"";
	if ($dayto == $i) echo " selected";
	echo ">$i</option>";
}
echo "</select><select name=\"monthto\" size=\"1\">";
for ($i=1; $i<=count($months); $i++) {
	echo "<option value=\"$i\"";
	if ($monthto == $i) echo " selected";
	echo ">$months[$i]</option>";
}
echo "</select><select name=\"yearto\" size=\"1\">";
for ($i=2000; $i<=2005; $i++) {
	echo "<option value=\"$i\"";
	if ($yearto == $i) echo " selected";
	echo ">$i</option>";
}
?>
</select></font>
&nbsp;&nbsp;&nbsp;Order status: <select name="orderstate" size="1">
<option value="All" <? if ($orderstate == "All") echo "selected"; ?>>All</option>
<?
for ($i=0; $i<count($orderstates); $i++) {
	echo "<option value=\"$orderstates[$i]\"";
	if ($orderstate == $orderstates[$i]) echo " selected";
	echo ">$orderstates[$i]</option>";
}
?>
</select></font></td>
</tr>

</table>
</td>
</tr>
</table>

<br>

<div align="center"><font size="-1"><b>
<input type="submit" value="Submit"></b></font></div>
</form>

<hr>
</center>
</td>
</tr>
</table>

<!-- /main frame -->
</td>
<?
$dont_display_lc = 1;
include "bottom.php";
?>
</body>
</html>
