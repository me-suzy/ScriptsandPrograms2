<?
include "../config.php";
include "../mod.php";
include "mod.php";
include "auth.php";

if ($safe_admin)
	safe_mode_msg(true);

include "../params.php";

?>
<html>
<head>
<title><? echo "$main_title"; ?>: Admin - add new product</title>
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
<?
echo "<font color=\"$cl_header\" size=\"+1\"><b>Category: $category</b></font><br>\n";
?>
<hr>
<div align="left"><font size="-2">Recommended image file dimensions 50x50-150x150. Maximum size = 100Kb </font></div>
<center>
<hr>
<?
$product = "";
$price = "";
$image = $default_image;
$descr = "";
$avail = "Y";
admin_product("add", $product, $category, $price, $image, $descr, $avail, "", 0);
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
