<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

$items_per_adm_page=100;

if (strlen($HTTP_GET_VARS["category"]) > 0)
      $category = r_secure($HTTP_GET_VARS["category"]);
else
	$category = "All";
$sortby = $default_sortby;
if (strlen($HTTP_GET_VARS["sortby"]) > 0)
        $sortby = r_secure($HTTP_GET_VARS["sortby"]);
if (strlen($HTTP_GET_VARS["first"]) > 0)
        $first = r_secure($HTTP_GET_VARS["first"]);
else
        $first = 1;

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin - search</title>
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
$tabnames = array("Search","Discounts","Discount coupons","Shipping rates","Orders","Configure");
if ($https_adm_enabled=="Y")
	$taburls = array("https://$https_adm_location/search.php","https://$https_adm_location/discounts.php","https://$https_adm_location/disc_coupons.php","https://$https_adm_location/shipping.php","https://$https_adm_location/orders.php","https://$https_adm_location/config_edit.php");
else
	$taburls = array("http://$http_adm_location/main.php","http://$http_adm_location/discounts.php","http://$http_adm_location/disc_coupons.php","http://$http_adm_location/shipping.php","http://$http_adm_location/orders.php","http://$http_adm_location/config_edit.php");
$tabimages = array("","","","","","");
include "../tabs.php";
?>
<tr> 
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2"> 
<?
include "cat.php";
include "searchform.php";
echo "<center>";
include("help.php");
echo "</center>";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>"> 
<!-- main frame here --> 
<table width="100%" height="100%" cellpadding="10">
<tr valign="top"> 
<td height="500"> 
<center>
<hr>
<?
$orderby = "product";
switch ($sortby) {
case "price" : $orderby = "price"; break;
case "age" : $orderby = "a_date desc"; break;
case "rating" : $orderby = "rating desc"; break;
default: $orderby="product";
}
$result = mysql_query("select product, price, image, descr, avail, productid, category, rating from products where ".($productid == "" ? "" : "productid='$productid' and ")."(product like '%$key%' or descr like '%$key%') ".($category == "All" ? "" : "and category='$category'")." order by $orderby limit ".($first-1).",$items_per_adm_page");
if (mysql_num_rows($result) == 0)
	echo "<font size=\"5\">Nothing appropriate found</font>";
else for ($i = 0 ; ($i < $items_per_adm_page) && (list($product,$price,$image,$descr,$avail,$productid,$_category, $rating) = mysql_fetch_row($result)); $i++) {
        admin_product("update", $product, $_category, $price, $image, $descr, $avail, $productid, $rating);
}
mysql_free_result($result);
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
