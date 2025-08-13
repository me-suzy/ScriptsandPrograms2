<?
function display_product($product, $price, $image, $descr, $amount, $mode, $productid, $prodcat) {
global $images_url, $first, $sortby, $category, $wish;
global $cl_mod_border, $cl_mod_bg, $cl_mod_price, $cl_mod_upsel1, $cl_mod_upsel2;
global $http_location;

$c_ = unquote($category);
if ($mode == "overview" ) {
echo <<<EOT
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%"><a href="javascript:display_product('$productid')"><img align="left" src="$images_url/$image" width="40" border="0"></a></td>
<td bgcolor="$cl_mod_bg">
<font size="-2">Category:&nbsp;$prodcat<br></font>
<b><a href="javascript:display_product('$productid')"><font size="-1">$product</font></a></b><br>
<!-- <p align="justify" style="font-size: 7pt">$descr</p> -->
<br>
<font size="-1">Price: <font color="$cl_mod_price">\$$price</font></font>
</td></tr>
</table>
EOT;
}
elseif ($mode == "main" || $mode == "search") {
echo "<form method=\"get\" action=\"http://$http_location/add.php\">";
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="$cl_mod_border">
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%"><img align="left" src="$images_url/$image"></td>
<td bgcolor="$cl_mod_bg" valign="top" rowspan="2"><b>$product</b><br>
<p align="justify"><font size="-1">$descr</font></p>
<b>Price: <font color="$cl_mod_price">\$$price</font></b>
<input type=hidden name=productid value="$productid">
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$c_">
<table border="0"><tr><td>
<i>Quantity:</i> <input type=text name=amount value=1 size=2 maxlength=2></td>
<td><input type=image src="images/load.gif" width="29" height="32" border="0" alt="Add to shopping cart" name="add" value="add">
</td></tr>
</table>
</td></tr>
<tr><td bgcolor="$cl_mod_bg" align="center">
<input type=image src="images/add2wl.gif" width="72" height="9" border="0" alt="Add to wish list" name="wish" value="wish">
</td></tr>
</table>
</td></tr></table>
</form>
EOT;
} elseif ($mode=="brief")
{
echo "<tr>";
echo "<td>$product</td><td>$amount</td><td>\$".$price * $amount."</td>";
echo "</tr>";
} else {
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="$cl_mod_border">
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%"><img align="left" src="$images_url/$image"></td><td bgcolor="$cl_mod_bg" rowspan="2" valign="top"><b>
EOT;
echo "<a href=\"javascript:display_product('$productid','first=$first&sortby=$sortby&category=".urlencode($category)."')\">$product</a></b><br><br>";
#echo "<a href=\"http://$http_location/product.php?category=".urlencode($category)."&productid=$productid\">$product</a></b><br><br>";
	if ($amount > 1) {
		echo "<b>Price:<font color=\"$cl_mod_price\"> \$$price * $amount = \$".($price*$amount)."</font></b>\n";
	} else {
		echo "<b>Price:<font color=\"$cl_mod_price\"> \$$price</font></b>\n";
	}
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="4" width="100%"><tr><td valign="top">
<table border="0" cellspacing="0" cellpadding="0">
<tr><td valign="top">
<i>Quantity:</i>&nbsp;&nbsp;$amount&nbsp;&nbsp;<br><br>
EOT;
if ($wish == 'Y') {
	echo "<a href=\"http://$http_location/del.php?productid=$productid&first=$first&sortby=$sortby&category=".urlencode($c_)."&mode=wish\"><img src=\"images/delitem.gif\" height=\"9\" width=\"63\"border=\"0\" alt=\"Delete from wish list\"></a>&nbsp;&nbsp;";
	echo "<a href=\"http://$http_location/unwish.php?productid=$productid&amount=$amount&first=$first&sortby=$sortby&category=".urlencode($c_)."\"><img src=\"images/2cart.gif\" height=\"9\" width=\"63\"border=\"0\" alt=\"Delete from wish list\"></a>";
	echo "</td></tr>";
} else { 
	echo "<a href=\"http://$http_location/del.php?productid=$productid&first=$first&sortby=$sortby&category=".urlencode($c_)."&mode=cart\"><img src=\"images/delitem.gif\" height=\"9\" width=\"63\"border=\"0\" alt=\"Delete from shopping cart\"></a>";
	echo "</td></tr>";
	}
echo "</table>";

echo "</td><td width=\"250\" align=\"right\">&nbsp;";

$upsell_first = true;
$u_result = mysql_query("select link from product_links where productid='$productid'");
while ($row = @mysql_fetch_row($u_result)) {
	if ($upsell_first) {
		$upsell_first = false;
		echo "<table cellspacing=\"1\" cellpadding=\"4\" border=\"0\">\n";
		echo "<tr><td bgcolor=\"$cl_mod_upsel1\"><font size=\"-2\">We also recommend you:</font></td></tr><tr><td bgcolor=\"$cl_mod_upsel2\" nowrap>\n";
	}
	$id = $row[0];
	$i_result = mysql_query("select product, productid from products where productid='$id'");
	if ($row = mysql_fetch_row($i_result)) {
		$i_product = $row[0];
		$i_productid = $row[1];
		echo "<img src=\"images/pix.gif\" width=\"8\" height=\"8\"><font size=\"-2\">&nbsp;&nbsp;<a href=\"javascript:display_product('$i_productid','first=$first&sortby=$sortby&category=".urlencode($category)."')\">".trimm($i_product,48)."</a><br>";
		#echo "&nbsp;&nbsp;(<a href=http://$http_location/add.php?productid=$i_productid&amount=1&first=$first&sortby=$sortby&category=$category&wish.x=1&wish.y=1>Add to WL</a>)</font><br>\n";
	}
	mysql_free_result($i_result);
}
if ($upsell_first == false) { echo "</td></tr></table>"; }

@mysql_free_result($u_result);

echo "</td></tr></table>\n";
echo "</td></tr></table>\n";
echo "</td></tr></table><br>\n";
}
}

function calc_sum($sum) {
	$result = mysql_query("select discount from discounts where price<='$sum' order by price desc limit 1");
	if (mysql_num_rows($result) > 0)
		list($discount) = mysql_fetch_row($result);
	else
		$discount = 0;
	mysql_free_result($result);
	$result = mysql_query("select shipping from shipping where price<='$sum' order by price desc limit 1");
	if (mysql_num_rows($result) > 0)
		list($shipping) = mysql_fetch_row($result);
	else
		$shipping = 0;
	mysql_free_result($result);
	$arr = array($discount,$shipping,$sum-$discount+$shipping);
	return $arr;
}

function r_secure($var) {
	$v = str_replace("\\", "", $var);
	return str_replace("'", "\\'", $v);
}
function unquote($var) {
	return str_replace("\"", "", $var);
}
function d_secure($var) {
	return preg_replace("/[^0-9]/", "", $var);
}

function trimm($string, $length) {
	$trimmed = substr ($string,0,$length);
	if ($trimmed!=$string) $trimmed.="...";
	return $trimmed;
}
?>
