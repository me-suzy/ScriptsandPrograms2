<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

include "../params.php";

$mode = r_secure($HTTP_GET_VARS["mode"]);

if ($REQUEST_METHOD=="POST") {
	if ($safe_admin) safe_mode_msg(true);
	while(list($key,$val)=each($HTTP_POST_VARS))
{
	$val=r_secure($val);
	if (strstr($key,"-")) {
		list($field,$productid)=split("-",$key);
		if ($field=="avail") $val="Y";
		mysql_query("update featured_products set avail='N', $field='$val' where productid='$productid'") or die ("$mysql_error_msg");
	}
}
	$newproductid=r_secure($HTTP_POST_VARS["newproductid"]);
	$qstring="?first=".r_secure($HTTP_POST_VARS["first"])."&sortby=".r_secure($HTTP_POST_VARS["sortby"])."&category=".r_secure($HTTP_POST_VARS["category"]);
	if ($newproductid!="") {
		$neworder=r_secure($HTTP_POST_VARS["neworder"]);
		$newavail=(r_secure($HTTP_POST_VARS["newavail"])=="on" ? "Y" : "N");
		if ($neworder=="") {
			$m_result = mysql_query("select max(product_order) from featured_products");
			if (!(list($maxorder) = mysql_fetch_row($m_result))) $maxorder=0;
			mysql_free_result($m_result);
			$neworder=$maxorder+1;
		}
	mysql_query("insert into featured_products (productid, product_order, avail) values ('$newproductid','$neworder','$newavail')") or die ("$mysql_error_msg");
	}
	header("Location: ".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/f_products.php".$qstring);
}

if ($mode=="delete") {
	if ($safe_admin) safe_mode_msg(true);
	$productid=r_secure($HTTP_GET_VARS["productid"]);
	mysql_query("delete from featured_products where productid='$productid'") or die ("$mysql_error_msg");
} 

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "../cssstyle.php"; ?>
<script language="javascript">
function display_product(productid) {
    window2 = window.open("<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/productb.php?productid="+productid, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=600,height=380") ;
    }
</script>
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
<font color="<? echo $cl_header ?>" size="+1"><b>Featured products</b></font>
<center>
<hr>
<!--                                                -->
<?
$fresult = mysql_query("select featured_products.productid, products.product, featured_products.product_order, featured_products.avail from featured_products, products where featured_products.productid=products.productid order by featured_products.product_order");
echo "<form action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/f_products.php\" method=\"POST\">";
echo "<table width=\"100%\" border=\"0\">";
echo "<tr bgcolor=\"$cl_tab_back\"><td>Order</td><td>Product ID</td><td>Product name</td><td>Avail</td><td bgcolor=\"$cl_tab_top\">&nbsp;</td></tr>\n";
while (list($productid,$product,$order,$avail) = mysql_fetch_row($fresult)){
	echo "<tr>";
	echo "<td><input type=\"text\" name=product_order-".$productid." size=2 value=\"$order\"></td>";
	echo "<td><input type=\"text\" name=productid-".$productid." size=5 value=\"$productid\"></td>";
	echo "<td><a href=\"javascript:display_product('$productid')\">".trimm($product,36)."</a></td>";
	echo "<td><input type=\"checkbox\" name=avail-".$productid." ".($avail=="Y" ? "checked":"unchecked")."></td>";
	echo "<td><input type=\"button\" value=\"Delete\" onClick=\"document.location='".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/f_products.php?mode=delete&productid='+".$productid."+'&first=$first"."&sortby=$sortby"."&category=".urlencode($category)."'\"></td>";
	echo "</tr>\n";
}
echo "<tr>";
echo "<td><input type=\"text\" name=neworder size=2></td>";
echo "<td><input type=\"text\" name=newproductid size=5></td>";
echo "<td>- Empty -</td>";
echo "<td><input type=\"checkbox\" name=newavail checked></td>";
echo "<td><input type=\"hidden\" name=\"category\" value=\"".urlencode($category)."\">
<input type=\"hidden\" name=\"sortby\" value=\"$sortby\">
<input type=\"hidden\" name=\"first\" value=\"$first\">
<input type=\"Submit\" value=\"Update\"></td>";
echo "</tr>\n";
echo "</table>";
echo "</form>";

mysql_free_result($fresult);
?>
<!--                                                -->
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
