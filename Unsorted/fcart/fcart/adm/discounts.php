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
	$mode = r_secure($HTTP_POST_VARS["mode"]);
	$price = r_secure($HTTP_POST_VARS["price"]);
	$discount = r_secure($HTTP_POST_VARS["discount"]);
	if (($mode == "add") && ($price != "") && ($discount != "")){
		if ($safe_admin) safe_mode_msg(true);
		mysql_query("insert into discounts (price,discount) values ('$price','$discount')") or die ("$mysql_error_msg"); 
} elseif ($mode == "delete") {
		if ($safe_admin) safe_mode_msg(true);
		mysql_query("delete from discounts where price='$price'")or die ("$mysql_error_msg"); }
} else {
	$firsttime=true;
	include "../params.php";
}

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin - discount rates</title>
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

<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
<td bgcolor="<? echo $cl_order_border ?>">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
<td height="22" valign="middle" bgcolor="<? echo $cl_win_cap2 ?>" colspan="6">
<center><b><font color="<? echo $cl_win_title ?>" size="-1"><i>Discounts:</i></font></b></center>
</td>
</tr>

<?
$result = mysql_query("select price,discount from discounts order by price");
$pprice = -1;
while (list($price,$discount) = mysql_fetch_row($result)) {
	echo "<tr bgcolor=\"$cl_tab_top\">";
	echo "<form method=\"POST\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/discounts.php\">";
	if (($pprice == -1) && ($price != 0))
		echo "<td nowrap align=\"right\">For orders between</td><td width=\"1%\" align=\"right\">\$0</td><td width=\"1%\">and</td><td width=\"1%\" align=\"right\">\$$price</td><td>&nbsp;-&nbsp;No discount</td><td>&nbsp;</td>";
	elseif ($price != 0)
		echo "<input type=hidden name=mode value=\"delete\"><input type=hidden name=price value=\"$pprice\"><td nowrap align=\"right\">For orders between</td><td align=\"right\">\$$pprice</td><td>and</td><td align=\"right\">\$$price</td><td>&nbsp;-&nbsp;Discount \$$pdiscount</td><td><font size=\"-1\"><b><input type=\"submit\" name=\"submit\" value=\"Delete\"></b></font></td>";
	$pdiscount = $discount;
	$pprice = $price;
	echo "</form></tr>";
}
if ($pprice != -1)
	echo "<tr bgcolor=\"$cl_tab_top\"><form method=\"POST\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/discounts.php\"><input type=hidden name=mode value=\"delete\"><input type=hidden name=price value=\"$pprice\"><td nowrap align=\"right\">For orders from</td><td width=\"1%\" align=\"right\">\$$pprice</td><td width=\"1%\">and</td><td width=\"1%\" align=\"left\">more</td><td>&nbsp;-&nbsp;Discount \$$pdiscount</td><td><font size=\"-1\"><b><input type=\"submit\" name=\"submit\" value=\"Delete\"></b></font></td></form></tr>";
mysql_free_result($result);
echo <<<EOT
<tr bgcolor="$cl_tab_top"><td colspan="6"><hr></td></tr>
<tr bgcolor="$cl_tab_top">
EOT;
echo "<form method=\"POST\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/discounts.php\">";
echo <<<EOT
<input type=hidden name=mode value="add"><td height="40" nowrap align="right" colspan="5">For orders from&nbsp;&nbsp;$<input type=text name="price" size="7" maxlength="14">&nbsp;&nbsp;add discount&nbsp;&nbsp;$<input type=text name="discount" size="7" maxlength="14">&nbsp;</td><td><font size="-1"><b><input type="submit" name="submit" value="Add"></b></font></td></form></tr>
EOT;
?>

</table>
</td>
</tr>
</table>

<hr>
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
