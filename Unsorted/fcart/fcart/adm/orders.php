<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

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
	$card_num = d_secure($HTTP_POST_VARS["card_num"]);
	$expmonth = d_secure($HTTP_POST_VARS["expmonth"]);
	$expyear = d_secure($HTTP_POST_VARS["expyear"]);
	$catt = r_secure($HTTP_POST_VARS["catt"]);
	$productid = d_secure($HTTP_POST_VARS["productid"]);
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
	$oidchange = d_secure($HTTP_POST_VARS["oidchange"]);
	$ostate = r_secure($HTTP_POST_VARS["ostate"]);
	if (!empty($oidchange))
		mysql_query("update orders set order_state='$ostate' where orderid='$oidchange'") or die ("$mysql_error_msg");
} else {
	$firsttime=true;
	$dayto = 31;
	$monthto = 12;
	$yearto = 2005;
	include "../params.php";
}

$b_statecodes = array();
$b_states = array();
$result = mysql_query("select distinct(orders.b_state),states.state from orders,states where orders.b_state=states.code order by code");
while (list($c,$s) = @mysql_fetch_row($result)) {
	array_push($b_statecodes,$c);
	array_push($b_states,$s);
}
mysql_free_result($result);
$s_statecodes = array();
$s_states = array();
$result = mysql_query("select distinct(orders.s_state),states.state from orders,states where orders.s_state=states.code order by code");
while (list($c,$s) = @mysql_fetch_row($result)) {
	array_push($s_statecodes,$c);
	array_push($s_states,$s);
}
mysql_free_result($result);
$b_countrycodes = array();
$b_countries = array();
$result = mysql_query("select distinct(orders.b_country),countries.country from orders,countries where orders.b_country=countries.code order by code");
while (list($c,$s) = @mysql_fetch_row($result)) {
	array_push($b_countrycodes,$c);
	array_push($b_countries,$s);
}
mysql_free_result($result);
$s_countrycodes = array();
$s_countries = array();
$result = mysql_query("select distinct(orders.s_country),countries.country from orders,countries where orders.s_country=countries.code order by code");
while (list($c,$s) = @mysql_fetch_row($result)) {
	array_push($s_countrycodes,$c);
	array_push($s_countries,$s);
}
mysql_free_result($result);
$cardtypes = array(1=>"Visa",2=>"Mastercard",3=>"Discover",4=>"American Express");
$months = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
$orderstates = array("Queued","Processed","Shipped","Cancelled");
?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin - orders</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "../cssstyle.php"; ?>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>">
<?
include "top.php";
$tabnames = array("Products database","Discounts","Discount coupons","Shipping rates","Orders","Configure");
if ($https_adm_enabled=="Y")
	$taburls = array("https://$https_adm_location/main.php","https://$https_adm_location/discounts.php","https://$https_adm_location/disc_coupons.php","https://$https_adm_location/shipping.php","https://$https_adm_location/orders.php","https://$https_adm_location/config_edit.php");
else
	$taburls = array("http://$http_adm_location/main.php","http://$http_adm_location/discounts.php","http://$http_adm_location/disc_coupons.php","http://$http_adm_location/shipping.php","http://$http_adm_location/orders.php","http://$http_adm_location/config_edit.php");
$tabimages = array("","","","","","");
include "../tabs.php";
?>
<tr> 
<?
#echo "<td width=\"10%\" bgcolor=\"$cl_left_tab\" valign=\"top\" rowspan=\"2\">";
#include("cat.php");
#include("searchform.php");
#echo "</td>";
$c_result = mysql_query("select category, count(*) from products group by category");
$tabcount++;
?>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="500" valign="top"> 
<!-- main frame here --> 
<table width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<center>
<hr>

<div align="right">
<?
include "help.php";
?>
</div>
<?
if (!($firsttime)) {
?>
<script language='javascript'>
function display_order(orderid) {
	window1 = window.open("<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/orderb.php?orderid="+orderid, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=320,height=550") ;
	window1.focus() ;
}
function display_product(productid) {
	window2 = window.open("<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/productb.php?productid="+productid, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=600,height=400") ;
	window2.focus() ;
}
</script>
<?
if (strlen($expmonth) == 1) $expmonth = "0".$expmonth;
if (strlen($expyear) == 1) $expyear = "0".$expyear;
$expires = $expmonth.$expyear;
$datefrom = date("YmdHis",mktime(0,0,0,$monthfrom,$dayfrom,$yearfrom));
$dateto = date("YmdHis",mktime(23,59,59,$monthto,$dayto,$yearto));
$result = mysql_query("select orders.orderid,login,order_total,order_date,order_state,order_flag,order_details.product,products.product,order_details.price,order_details.amount from orders,order_details,products where ".
	($uname == "" ? "" : "login='$uname' and ").
	($totalfrom == "" ? "" : "order_total>='$totalfrom' and ").
	($totalto == "" ? "" : "order_total<='$totalto' and ").
	"order_date>='$datefrom' and ".
	"order_date<='$dateto' and ".
	($orderstate == "All" ? "" : "order_state='$orderstate' and ").
	($fname == "" ? "" : "firstname='$fname' and ").
	($lname == "" ? "" : "lastname='$lname' and ").
	($b_address == "" ? "" : "b_address='$b_address' and ").
	($b_city == "" ? "" : "b_city='$b_city' and ").
	($b_state == "All" ? "" : "b_state='$b_state' and ").
	($b_country == "All" ? "" : "b_country='$b_country' and ").
	($b_zipcode == "" ? "" : "b_zipcode='$b_zipcode' and ").
	($s_address == "" ? "" : "s_address='$s_address' and ").
	($s_city == "" ? "" : "s_city='$s_city' and ").
	($s_state == "All" ? "" : "s_state='$s_state' and ").
	($s_country == "All" ? "" : "s_country='$s_country' and ").
	($s_zipcode == "" ? "" : "s_zipcode='$s_zipcode' and ").
	($phone == "" ? "" : "phone='$phone' and ").
	($email == "" ? "" : "email='$email' and ").
	($card_type == "All" ? "" : "card_type='$card_type' and ").
	($card_name == "" ? "" : "card_name='$card_name' and ").
	($card_num == "" ? "" : "card_number='$card_num' and ").
	($expires == "" ? "" : "card_expire='$expires' and ").
	($productid == "" ? "" : "order_details.product='$productid' and ").
	($catt == "All" ? "" : "products.category='$catt' and ").
	($orderflag == "All" ? "" : "order_flag='$orderflag' and ").
	" orders.orderid=order_details.orderid and order_details.product=products.productid order by orderid limit $items_per_orders_page");
echo <<<EOT
<table border="1" cellspacing="1" cellpadding="2" width="100%" bgcolor="$cl_tab_top">
<tr bgcolor="$cl_tab_back">
<td width="1%"><font size="-1">OrderID</font></td>
<td nowrap width="1%"><font size="-1">Username</font></td>
<td nowrap width="1%"><font size="-1">Total</font></td>
<td nowrap width="1%"><font size="-1">Date</font></td>
<td nowrap width="1%"><font size="-1">Feature</font></td>
<td nowrap width="1%"><font size="-1">Status</font></td>
<td width="1%"><font size="-1">Pr.ID</font></td>
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
while (list($_orderid,$_uname,$_total,$_date,$_ostate,$_orderflag,$_productid,$_product,$_price,$_amount) = mysql_fetch_row($result)) {
	$pricesum += $_price;
	$_product = trimm($_product,36);
	if ($_orderid != $old_orderid) {
		$totalsum += $_total;
		$cnt = 0;
		for (; $oarray[$i] == $_orderid; $i++) $cnt++;
		echo "<tr>";
		echo "<form method=\"POST\" name=\"ochange$_orderid\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/orders.php\">";
		echo <<<EOT
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
<input type=hidden name=uname value="$uname">
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
<input type=hidden name=expmonth value="$expmonth">
<input type=hidden name=expyear value="$expyear">
<input type=hidden name=catt value="$catt">
<input type=hidden name=productid value="$productid">
<input type=hidden name=totalfrom value="$totalfrom">
<input type=hidden name=totalto value="$totalto">
<input type=hidden name=dayfrom value="$dayfrom">
<input type=hidden name=monthfrom value="$monthfrom">
<input type=hidden name=yearfrom value="$yearfrom">
<input type=hidden name=dayto value="$dayto">
<input type=hidden name=monthto value="$monthto">
<input type=hidden name=yearto value="$yearto">
<input type=hidden name=orderstate value="$orderstate">
<input type=hidden name=orderflag value="$orderflag">
<td nowrap align="center" rowspan="$cnt"><a href="javascript:display_order('$_orderid')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_orderid</font></a></td>
<td nowrap align="center" rowspan="$cnt"><a href="javascript:display_order('$_orderid')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_uname</font></a></td>
<td nowrap align="right" rowspan="$cnt"><a href="javascript:display_order('$_orderid')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_total</font></a></td>
<td nowrap rowspan="$cnt"><a href="javascript:display_order('$_orderid')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_date</font></a></td>
<td align="center" rowspan="$cnt"><a href="javascript:display_order('$_orderid')" onMouseOver="window.status='Click to display order details';return true"><font size="-1">$_orderflag</font></a>&nbsp;</td>
<td height="1%" nowrap rowspan="$cnt"><font size="-2">
<input type=hidden name=oidchange value="$_orderid">
<select name="ostate" size="1" onChange="document.ochange$_orderid.submit()">
EOT;
		for ($j=0; $j<count($orderstates); $j++) {
			echo "<option value=\"$orderstates[$j]\"";
			if ($_ostate == $orderstates[$j]) echo " selected";
			echo ">$orderstates[$j]</option>";
		}
		echo "</select></font></td>";
	}
	echo <<<EOT
<td nowrap align="center"><a href="javascript:display_product('$_productid')" onMouseOver="window.status='Click to display product details';return true"><font size="-1">$_productid</font></a></td>
<td nowrap align="left"><a href="javascript:display_product('$_productid')" onMouseOver="window.status='Click to display product details';return true"><font size="-1">$_product</font></a></td>
<td nowrap align="right"><font size="-1">$_price</font></td>
<td nowrap align="right"><font size="-1">$_amount</font></td>
</form></tr>
EOT;
	$old_orderid = $_orderid;
}
echo <<<EOT
<tr bgcolor="$cl_tab_back">
<td>&nbsp;</td>
<td>&nbsp;</td>
<td nowrap><font size="-1"><b>$totalsum</b></font></td>
<td>&nbsp;</td>
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
} else { #firsttime
	$dayfrom_ = $dayto_ = date("d");
	$monthfrom_ = $monthto_ = date("m");
	$yearfrom_ = $yearto_ = date("Y");
	echo <<<EOT
<table border="0" cellspacing="0" cellpadding="10" width="100%">
<tr valign="top"><td align="right">
EOT;
echo "<a href=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/stats.php\" style=\"text-decoration: underline\" onMouseOver=\"window.status='View graphical statistics';return true\"><b>View graphical statistics</b></a></td>";
echo "<td align=\"left\"><form method=\"POST\" name=\"todaystats\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/orders.php\">";
echo <<<EOT
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$category">
<input type=hidden name=uname value="">
<input type=hidden name=fname value="">
<input type=hidden name=lname value="">
<input type=hidden name=b_address value="">
<input type=hidden name=b_city value="">
<input type=hidden name=b_state value="All">
<input type=hidden name=b_country value="All">
<input type=hidden name=b_zipcode value="">
<input type=hidden name=s_address value="">
<input type=hidden name=s_city value="">
<input type=hidden name=s_state value="All">
<input type=hidden name=s_country value="All">
<input type=hidden name=s_zipcode value="">
<input type=hidden name=phone value="">
<input type=hidden name=email value="">
<input type=hidden name=card_type value="All">
<input type=hidden name=card_name value="">
<input type=hidden name=card_num value="">
<input type=hidden name=expmonth value="">
<input type=hidden name=expyear value="">
<input type=hidden name=catt value="All">
<input type=hidden name=productid value="">
<input type=hidden name=totalfrom value="">
<input type=hidden name=totalto value="">
<input type=hidden name=dayfrom value="$dayfrom_">
<input type=hidden name=monthfrom value="$monthfrom_">
<input type=hidden name=yearfrom value="$yearfrom_">
<input type=hidden name=dayto value="$dayto_">
<input type=hidden name=monthto value="$monthto_">
<input type=hidden name=yearto value="$yearto_">
<input type=hidden name=orderstate value="All">
<input type=hidden name=orderflag value="All">
<a href="javascript:document.todaystats.submit()" style="text-decoration: underline" onMouseOver="window.status='Display today orders';return true"><b>Display today orders</b></a>
</form></td></tr>
</table>
EOT;
}
echo "<form method=\"POST\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/orders.php\">";
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
<td height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>" colspan="4">
<center><b><font color="<? echo $cl_order_bg ?>" size="-1"><i>Display orders:</i></font></b></center>
</td>
</tr>

<?
echo <<<EOT
<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;Username</font></td>
<td nowrap>
<font size="-1"><input type="text" name="uname" size="16" maxlength="32" value="$uname"></font>
</td>
<td nowrap><font size="-1">Card type</font></td>
<td nowrap><font size="-1"><select name="card_type">
EOT;
?>
<option value="All" <? if ($card_type == "All") echo "selected"; ?>>All</option>
<?
for ($i=1; $i<=count($cardtypes); $i++) {
	echo "<option value=\"$i\"";
	if ($card_type == $i) echo " selected";
	echo ">$cardtypes[$i]</option>";
}
echo <<<EOT
</select></font></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;First Name</font></td>
<td nowrap>
<font size="-1"><input type="text" name="fname" size="16" maxlength="32" value="$fname"></font>
</td>
<td nowrap><font size="-1">Card holder's name</font></td>
<td nowrap>
<font size="-1"><input type="text" name="card_name" size="16" maxlength="64" value="$card_name"></font>
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;Last Name</font></td>
<td nowrap>
<font size="-1"><input type="text" name="lname" size="16" maxlength="32" value="$lname"></font>
</td>
<td nowrap><font size="-1">Card number</font></td>
<td nowrap>
<font size="-1"><input type="text" name="card_num" size="16" maxlength="20" value="$card_num"></font>
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;Phone</font></td>
<td nowrap>
<font size="-1"><input type="text" name="phone" size="16" maxlength="32" value="$phone"></font>
</td>
<td nowrap><font size="-1">Expiration date</font></td>
<td nowrap>
<font size="-1"><input type="text" name="expmonth" size="2" maxlength="2" value="$expmonth">
<input type="text" name="expyear" size="2" maxlength="2" value="$expyear"></font>
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;E-Mail</font></td>
<td nowrap>
<font size="-1"><input type="text" name="email" size="16" maxlength="128" value="$email"></font>
</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr bgcolor="$cl_tab_top"><td colspan="4"><hr></td></tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap colspan="2" align="center"><b>Billing address</b></td>
<td nowrap colspan="2" align="center"><b>Shipping address</b></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;Street</font></td>
<td nowrap>
<font size="-1"><input type="text" name="b_address" size="16" maxlength="64" value="$b_address"></font>
</td>
<td nowrap><font size="-1">Street</font></td>
<td nowrap>
<font size="-1"><input type="text" name="s_address" size="16" maxlength="64" value="$s_address"></font>
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;City</font></td>
<td nowrap>
<font size="-1"><input type="text" name="b_city" size="16" maxlength="64" value="$b_city"></font>
</td>
<td nowrap><font size="-1">City</font></td>
<td nowrap>
<font size="-1"><input type="text" name="s_city" size="16" maxlength="64" value="$s_city"></font>
</td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;State</font></td>
<td nowrap><font size="-1"><select name="b_state" size="1">
EOT;
?>
<option value="All" <? if ($b_state == "All") echo "selected"; ?>>All</option>
<option value="  " <? if ($b_state == "  ") echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($b_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
echo <<<EOT
</select></font></td>
<td nowrap><font size="-1">State</font></td>
<td nowrap><font size="-1"><select name="s_state" size="1">
EOT;
?>
<option value="All" <? if ($s_state == "All") echo "selected"; ?>>All</option>
<option value="  " <? if ($s_state == "  ") echo "selected"; ?>>Non-US</option>
<?
for ($i=0; $i<count($states); $i++) {
	echo "<option value=\"$statecodes[$i]\"";
	if ($s_state == $statecodes[$i]) echo " selected";
	echo ">$states[$i]</option>";
}
echo <<<EOT
</select></font></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;Country</font></td>
<td nowrap><font size="-2"><select name="b_country" size="1">
EOT;
?>
<option value="All" <? if ($b_country == "All") echo "selected"; ?>>All</option>
<?
for ($i=0; $i<count($b_countries); $i++) {
	echo "<option value=\"$b_countrycodes[$i]\"";
	if ($b_country == $b_countrycodes[$i]) echo " selected";
	echo ">$b_countries[$i]</option>";
}
?>
<option value="  " <? if ($b_country == "  ") echo "selected"; ?>>Other, unknown or unspecified country</option>
<?
echo <<<EOT
</select></font></td>
<td nowrap><font size="-1">Country</font></td>
<td nowrap><font size="-2"><select name="s_country" size="1">
EOT;
?>
<option value="All" <? if ($s_country == "All") echo "selected"; ?>>All</option>
<?
for ($i=0; $i<count($s_countries); $i++) {
	echo "<option value=\"$s_countrycodes[$i]\"";
	if ($s_country == $s_countrycodes[$i]) echo " selected";
	echo ">$s_countries[$i]</option>";
}
?>
<option value="  " <? if ($s_country == "  ") echo "selected"; ?>>Other, unknown or unspecified country</option>
<?
echo <<<EOT
</select></font></td>
</tr>

<tr valign="middle" bgcolor="$cl_tab_top">
<td nowrap><font size="-1">&nbsp;&nbsp;Zip code</font></td>
<td nowrap>
<font size="-1"><input type="text" name="b_zipcode" size="16" maxlength="32" value="$b_zipcode"></font>
</td>
<td nowrap><font size="-1">Zip code</font></td>
<td nowrap>
<font size="-1"><input type="text" name="s_zipcode" size="16" maxlength="32" value="$s_zipcode"></font>
</td>
</tr>

<tr bgcolor="$cl_tab_top"><td colspan="4"><hr></td></tr>

<tr height="40" valign="middle" bgcolor="$cl_tab_top">
<td nowrap colspan="4" align="center"><font size="-1">&nbsp;&nbsp;Category&nbsp;
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
&nbsp;&nbsp;&nbsp;Product ID
<input type="text" name="productid" size="4" maxlength="10" value="$productid">
&nbsp;&nbsp;&nbsp;Total, from&nbsp;$
<input type="text" name="totalfrom" size="4" maxlength="10" value="$totalfrom">
&nbsp;to&nbsp;$
<input type="text" name="totalto" size="4" maxlength="10" value="$totalto">
&nbsp;&nbsp;&nbsp;Gift:<select name="orderflag" size="1">
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
<td height="40" nowrap colspan="4" align="center"><font size="-1">
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
</select>
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
include "../bottom.php";
?>
</body>
</html>
