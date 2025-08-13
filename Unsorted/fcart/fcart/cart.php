<?
include "config.php";
include "mod.php";
include "params.php";

function cart_footer() {
	global $sum, $first, $sortby, $category, $gift_log, $bucks;
	global $HTTP_COOKIE_VARS, $http_location;

global $cl_discount, $cl_order_total, $cl_order_border, $cl_win_cap1, $cl_win_title, $cl_win_tab;

$dont_display_disc_coupon = 1;
include "disccoup.php";
	echo "<form action=\"http://$http_location/clear.php\" method=\"get\">\n";
	echo "<input type=hidden name=first value=\"$first\">\n";
	echo "<input type=hidden name=sortby value=\"$sortby\">\n";
	echo "<input type=hidden name=category value=\"$category\">\n";
	echo "<br><div align=\"left\">";
	echo "<input type=image align=\"center\" src=\"images/empty.gif\" alt=\"Clear your shopping cart\" border=\"0\"><font size=\"-2\">Clear your shopping cart&nbsp;&nbsp;&nbsp;</font>\n";
	echo "</div>";
	echo "</form>";
}

include "cookie.php";

$presult = mysql_query("select productid, amount, wish from cart_data where cart='$id' order by wish asc");
$presultww = mysql_query("select productid from cart_data where cart='$id' and wish='N'");
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: View cart</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<script language="javascript">
function display_product(productid, params) {
    window2 = window.open("<? echo "http://$http_location/" ?>product.php?productid="+productid+"&"+params, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=600,height=380") ;
	}
</script>
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
include "help.php";
include "poweredby.php";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top"> 
<!-- main frame here -->
<table width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<font color="<? echo $cl_header ?>" size="+1"><b>Cart contents:</b></font>
<center>
<hr>
<?
	if (!(mysql_num_rows($presultww) >= 1))
		echo "<font size=\"3\"><b><i>Your shopping cart is empty</i></b></font>";
	$sum = 0;
	while (list($productid,$amount,$wish) = mysql_fetch_row($presult)) {
        	$productid = d_secure($productid);
        	$amount = r_secure($amount);
        	$result = mysql_query("select price, image, descr, product from products where productid='$productid'");
        	list($price,$image,$descr,$product) = mysql_fetch_row($result);
			if ($wish == 'N') {
        		$sum += $price*$amount;
				$N_wishes_found = true;
			} else
				if (! ($oldwish == 'Y')) {
					$oldwish = 'Y';
					if ($N_wishes_found) {
						cart_footer();
						$N_wishes_found = false;
					}
					echo "<hr><div align=\"left\"><font color=\"$cl_header\" size=\"+1\"><b>Wish list contents:</b></font></div>\n";
					echo "<hr>\n";
				}
        	display_product($product, $price, $image, $descr, $amount, $amount, $productid,"");
        	mysql_free_result($result);
	}
		if ($N_wishes_found)
			cart_footer();
		elseif ($oldwish == 'Y') {
			echo "<form action=\"http://$http_location/clear.php\" method=\"get\">\n";
			echo "<input type=hidden name=first value=\"$first\">\n";
			echo "<input type=hidden name=sortby value=\"$sortby\">\n";
			echo "<input type=hidden name=category value=\"$category\">\n";
			echo "<input type=hidden name=mode value=\"wish\">\n";
			echo "<br><div align=\"left\">";
			echo "<input type=image align=\"center\" src=\"images/wempty.gif\" alt=\"Clear wish list\" border=\"0\"><font size=\"-2\">&nbsp;&nbsp;Clear wish list&nbsp;&nbsp;&nbsp;</font>\n";
			echo "</div>";
			echo "</form>";
		}
        echo "<div align=\"right\"><font size=\"-1\"><b><a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php?".($transfer_cookie ? "id=$id&" : "")."first=$first&sortby=$sortby&category=".urlencode($category)."\">Order</b></font> <img src=\"images/narrow.gif\" width=\"17\" height=\"16\" align=\"top\" border=\"0\"><img src=\"images/narrow.gif\" width=\"17\" height=\"16\" align=\"top\" border=\"0\"><img src=\"images/narrow.gif\" width=\"17\" height=\"16\" align=\"top\" border=\"0\"></a></div>\n";

mysql_free_result($presult);
mysql_free_result($presultww);
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
