<?
include "config.php";
include "mod.php";
include "params.php";

include "cookie.php";

$items_per_page=5;

if (strlen($HTTP_GET_VARS["category"]) > 0)
      $category = r_secure($HTTP_GET_VARS["category"]);
else
	$category = "All";
if (strlen($HTTP_GET_VARS["key"]) > 0)
	$key = r_secure($HTTP_GET_VARS["key"]);
else
	die("no key specified");

?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: Search</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "cssstyle.php" ?>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>">
<?
include "top.php";
$tabnames = array("Search","View cart","Order");
$taburls = array("http://$http_location/search.php","http://$http_location/cart.php",($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/order.php".($transfer_cookie ? "?id=$id" : ""));
$tabimages = array("images/narrow.gif","images/minicart.gif","");
include "tabs.php";
?>
<tr> 
<td width="10%" bgcolor="<? echo $cl_left_tab ?>" valign="top" rowspan="2"> 
<?
include "login.php";
include "cat.php";
include "searchform.php";
include "help.php";
include "poweredby.php";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top"> 
<!-- main frame here --> 
<table border="0" width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
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
$result = mysql_query("select product, price, image, descr, productid from products where avail='Y' and (product like '%$key%' or descr like '%$key%') ".($category == "All" ? "" : "and category like '$category%'")." order by $orderby limit ".($first-1).",$items_per_page");
if (mysql_num_rows($result) == 0)
	echo "<font size=\"3\"><b><i>Nothing appropriate found</i></b></font>";
else for ($i = 0 ; ($i < $items_per_page) && (list($product,$price,$image,$descr,$productid) = mysql_fetch_row($result)); $i++) {
        display_product($product, $price, $image, $descr, 1, "main", $productid,"");
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
include "bottom.php";
?>
</body>
</html>
