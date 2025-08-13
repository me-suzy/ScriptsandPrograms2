<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

include "../params.php";

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin - graphical statistics</title>
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
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2"> 
<?
include("cat.php");
include("searchform.php");
echo "<center>";
include("help.php");
echo "</center>";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="500" valign="top"> 
<!-- main frame here --> 
<table width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<center>
<hr>
<!--                                                -->

<?
$res = mysql_query("select hour(orders.order_date) as dom, sum(order_details.price* order_details.amount) from order_details, orders where order_details.orderid = orders.orderid group by dom order by dom");
$i = 1;
$x = "";
$y = "";
while ($row = mysql_fetch_row($res)) {
	while ($i < $row[0]) {
		if ($i != 1) {
			$x .= "_";
			$y .= "_";
		}
		$x .= $i;
		$i++;
		$y .= 0;
	}
	if ($i != 1) {
		$x .= "_";
		$y .= "_";
	}
	$x .= $i;
	$i++;
	$y .= $row[1];
}
while ($i < 24) {
	if ($i != 1) {
		$x .= "_";
		$y .= "_";
	}
	$x .= $i++;
	$y .= '0';
}
@mysql_free_result($res);
echo "<table border=\"0\" bgcolor=\"#000000\" cellspacing=\"0\" cellpadding=\"1\">
<tr><td><img src=\"chart.cgi?mode=sales_by_hour&x=$x&y=$y\"></td></tr>
</table><br>";
$res = mysql_query("select dayofmonth(orders.order_date) as dom, sum(order_details.price* order_details.amount) from order_details, orders where order_details.orderid = orders.orderid group by dom order by dom");
$i = 1;
$x = "";
$y = "";
while ($row = mysql_fetch_row($res)) {
	while ($i < $row[0]) {
		if ($i != 1) {
			$x .= "_";
			$y .= "_";
		}
		$x .= $i;
		$i++;
		$y .= 0;
	}
	if ($i != 1) {
		$x .= "_";
		$y .= "_";
	}
	$x .= $i;
	$i++;
	$y .= $row[1];
}
@mysql_free_result($res);
echo "
<table border=\"0\" bgcolor=\"#000000\" cellspacing=\"0\" cellpadding=\"1\">
<tr><td><img src=\"chart.cgi?mode=sales_this_month&x=$x&y=$y\"></td></tr>
</table><br>";
$res = mysql_query("select monthname(orders.order_date) as dom, sum(order_details.price* order_details.amount) from order_details, orders where order_details.orderid = orders.orderid group by dom order by dom");
$x = "";
$y = "";
$i = 1;
while ($row = mysql_fetch_row($res)) {
	if ($i != 1) {
		$x .= "_";
		$y .= "_";
	}
	$i++;
	$x.=$row[0];
	$y.=$row[1];
}
@mysql_free_result($res);
echo "
<table border=\"0\" cellpadding=\"0\"  cellspacing=\"0\" width=\"520\"><tr><td align=\"left\">
<table border=\"0\" bgcolor=\"#000000\" cellspacing=\"0\" cellpadding=\"1\">
<tr><td><img src=\"chart.cgi?mode=sales_this_year&x=$x&y=$y\"></td></tr>
</table></td>";
$res = mysql_query("select count(*) from customers");
$row = mysql_fetch_row($res);
$other_customers = $row[0];
$edge = $other_customers * .03;
@mysql_free_result($res);
$res = mysql_query("select country as c, count(*) from customers,countries where countries.code = customers.b_country group by c");
$x = "";
$y = "";
$i = 1;
while ($row = mysql_fetch_row($res)) {
	if ($row[1] >= $edge) {
		if ($i != 1) {
			$x .= "_";
			$y .= "_";
		}
		$i++;
		$x .= urlencode($row[0]);
		$y .= $row[1];
		$other_customers -= $row[1];
	}
}
@mysql_free_result($res);
if ($i != 1) {
	$x .= "_";
	$y .= "_";
}
$x .= "Other";
$y .= $other_customers;
echo "<td align=\"right\">
<table border=\"0\" bgcolor=\"#000000\" cellspacing=\"0\" cellpadding=\"1\">
<tr><td><img src=\"chart.cgi?mode=countries&x=$x&y=$y\"></td></tr>
</table>";
echo "</td></tr></table>\n";
echo "<hr>";
?>

<!--                                                -->
</center>
</td>
</tr>
</table>
<!-- /main frame -->
</td>
<?
include "../bottom.php";
?>
</body>
</html>
