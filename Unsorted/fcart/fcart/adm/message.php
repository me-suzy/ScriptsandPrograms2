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
<center>
<hr>
<? echo "<b><i>$text</b></i>"; ?>
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
