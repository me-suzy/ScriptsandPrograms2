<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

include "../params.php";

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<script language="javascript">
function display_product(productid) {
    window2 = window.open("<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/productb.php?productid="+productid, "Product", "toolbar=no,scrollbars=yes,resizable=yes,width=600,height=350") ;
    }
</script>
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
<?
echo "<font color=\"$cl_header\" size=\"+1\"><b>Category: $category</b></font><br>\n";
?>
<center>
<hr>
<?
echo "<div align=\"left\"><a href=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/add.php?first=$first&sortby=$sortby&category=".urlencode($category)."\"><b>Add new product...</b></a></div>";
echo "<div align=\"left\"><a href=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/f_products.php?first=$first&sortby=$sortby&category=".urlencode($category)."\"><b>Featured products list...</b></a></div>";
?>
<hr>
<?
include "../sort.php";
include "nav.php";
?>
<hr>
<?
$orderby = "product";
switch ($sortby) {
case "price" : $orderby = "price"; break;
case "age" : $orderby = "a_date desc"; break;
case "rating" : $orderby = "rating desc"; break;
default: $orderby="product";
}
$result = mysql_query("select product, price, image, descr, avail, productid, rating from products where category='$category' order by $orderby limit ".($first-1).",$items_per_adm_page");
for ($i = 0 ; ($i < $items_per_adm_page) && ($row = mysql_fetch_row($result)); $i++) {
        $product = $row[0];
        $price = $row[1];
        $image = $row[2];
        $descr = $row[3];
		$avail = $row[4];
		$productid = $row[5];
		$rating = $row[6];
        admin_product("update", $product, $category, $price, $image, $descr, $avail, $productid, $rating);
}
mysql_free_result($result);
include "nav.php";
?>

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
