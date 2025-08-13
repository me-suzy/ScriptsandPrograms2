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
<?
echo "<font color=\"$cl_header\" size=\"+1\"><b>Category: $category</b></font><br>\n";
?>
<center>
<hr>
<?
include "sort.php";
include "nav.php";
?>
<hr>
<?
@mysql_free_result($result);
$orderby = "product";
switch ($sortby) {
case "price" : $orderby = "price"; break;
case "age" : $orderby = "a_date desc"; break;
case "rating" : $orderby = "rating desc"; break;
default: $orderby="product";
}
$result = mysql_query("select product, price, image, descr, productid from products where avail='Y' and category like '$category%' order by $orderby limit ".($first-1).",$items_per_page");
for ($i = 0 ; ($i < $items_per_page) && (list($product,$price,$image,$descr,$productid) = mysql_fetch_row($result)); $i++) {
        display_product($product, $price, $image, $descr, 1, "main", $productid,"");
}
mysql_free_result($result);
include "nav.php";
?>

<br>
<?
echo "<div align=\"right\"><font size=\"-1\"><b><a href=\"http://$http_location/cart.php?first=$first&sortby=$sortby&category=".urlencode($category)."\">View cart</b></font> <img src=\"images/narrow.gif\" width=\"17\" height=\"16\" align=\"top\" border=\"0\"><img src=\"images/narrow.gif\" width=\"17\" height=\"16\" align=\"top\" border=\"0\"><img src=\"images/narrow.gif\" width=\"17\" height=\"16\" align=\"top\" border=\"0\"></a>\n";
?>
</div>
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
