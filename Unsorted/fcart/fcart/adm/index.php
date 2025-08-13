<?
include "../config.php";
include "../mod.php";
include "mod.php";
if ($https_adm_enabled=="Y" && $HTTPS!="on") {
	header("Location: https://$https_adm_location/index.php");
	exit;
}
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
<?
include "../cssstyle.php";
?>
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
<hr>
<!-- -->
<center>
<table align="center" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0">
<tr><td>
  <table width="100%" border="0" cellpadding="5" cellspacing="0" height="100%" bgcolor="#FFFFFF">
  <tr>
  <td align="center">
  <a href="http://www.fcart.com"><img src="../images/fcart.gif" width="100" height="66" border="0"></a>
  </td>
  </tr>
  </table>
</td></tr>
</table>
<h3>
Welcome to F-Cart admin mode!
</h3>
</center>
The admin back-office is the way to manage your shop via WWW interface.<br>
Here you can:
<ul type="disc">
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/add.php"><b>Add new product to your products database</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/main.php"><b>Browse and modify the products database</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/f_products.php"><b>Edit your featured products list</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/discounts.php"><b>Customize discount rates</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/shipping.php"><b>Customize shipping costs</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/disc_coupons.php"><b>Send discount coupons to specified or all customers</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/orders.php"><b>Query orders database to see any or specific orders</b></a></li>
<li><a href="<? echo ($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location") ?>/config_edit.php"><b>Configure your shop and change its look and feel</b></a></li>
</ul>
<p>
<b><i>Enjoy!</i></b><br>
Send comments and feedback to <a href="mailto:rrf@rrf.ru">developer</a>.<br>
<!-- -->
<hr>
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
