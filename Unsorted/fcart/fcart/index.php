<?
include "config.php";
include "mod.php";
include "params.php";

$id = $HTTP_COOKIE_VARS['ID'];
if (strlen($id) == 0) {
	$id = md5(uniqid(rand().getmypid()));
	$id = r_secure($id);
	setcookie("ID", $id, time() + $cookie_timeout);
} else {
	$id = r_secure($id);
	setcookie("ID", $id, time() + $cookie_timeout);
}
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?></title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<script language="javascript">
function display_product(productid) {
    window2 = window.open("<? echo "http://$http_location/"; ?>product.php?productid="+productid, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=600,height=380") ;
    }
</script>
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
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2"> 
<?
include("login.php");
include("cat.php");
include("searchform.php");
include("help.php");
include("poweredby.php");
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top"> 
<!-- main frame here -->
<table border="0" width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<? echo $shop_welcome ?>
<?
	echo "<font color=\"$cl_header\"><b>Featured products</b></font><br>\n";
?>
              <center>
                <hr>
<table border="0" width="100%">
<tr><td width="65%" valign="top">
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="<? echo $cl_mod_border ?>">
<?
@mysql_free_result($result);

$result = mysql_query("select products.product, products.price, products.image, products.descr, products.productid, products.category from products, featured_products where products.productid=featured_products.productid and products.avail='Y' and featured_products.avail='Y' order by featured_products.product_order");
echo "<table bgcolor=\"$cl_mod_bg\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">";

$rownum = mysql_num_rows($result);

while (list($product,$price,$image,$descr,$productid,$prodcat) = mysql_fetch_row($result)) {
		echo "<tr><td>\n";
        display_product($product, $price, $image, $descr, 1, "overview", $productid, $prodcat);
		echo "</td></tr>\n";
		if ( --$rownum) echo "<tr bgcolor=\"$cl_mod_bg\"><td><hr width=\"95%\" size=\"1\" noshade></td></tr>";
}
echo "</table>";
mysql_free_result($result);
?>
</td></tr></table>
</td><td valign="top" align="right">
<? include "topsellers.php" ?>
</td></tr>
</table>

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
