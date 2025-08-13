<?
include "config.php";
?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?></title>
<? include "cssstyle.php" ?>
<style>
body { background-repeat: repeat-y }
</style>
</head>
<body bgcolor="<? echo $cl_doc_bg ?>" background="images/fon.jpg">
<?
include "top.php";
$tabnames = array("F-Cart shopping system","How to setup your own web store?");
$taburls = array("http://$http_location/welcome.php","http://$http_location/wsetup.php");
$tabimages = array("","");
include "tabs.php";
?>
<tr> 
<? $tabcount++ ?>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top"> 
<!-- main frame here -->
<table border="0" width="100%" height="100%" cellpadding="10">
<tr> 
<td valign="top"> 
<hr>
<table border="0" width="100%">
<tr><td>
<h3>Welcome to F-Cart.com!</h3>The ultimate e-commerce solution for web stores is based on PHP4 + MySQL database with
highly configurable implementation.
<p><b>Brief list of features:</b><br>
<ul>
<li>Cookie identification.</li>
<li>Multilevel category tree.</li>
<li>Advanced shopping cart.</li>
<li>Wish list.</li>
<li>Gift certificates.</li>
<li>Shipping rates.</li>
<li>Discounts.</li>
<li>Discount coupons.</li>
<li>Bonus points.</li>
<li>Featured products list.</li>
<li>Upselling list.</li>
<li>Top sellers list.</li>
<li>Search by content/category.</li>
<li>Totally secure on-line registering/ordering.</li>
<li>Web-based secure administrator back office.</li>
</ul>
<hr><font size="-1" color="red">Please, note - this is DEMO! Do not enter any private info.</font>
<hr>
<b><a href="index.php"><img src="images/narrow.gif" border="0" width="17" height="16">&nbsp;Proceed to F-Cart web store DEMO</a></b><hr>
<b><a href="adm/index.php"><img src="images/narrow.gif" border="0" width="17" height="16">&nbsp;Proceed to F-Cart admin mode DEMO</a></b><br><br>
<font size="-1">Enter '<b>guest</b>' as login and '<b>guest</b>' as password.</font><br>
<hr>
</td></tr>
</table>
</td><td width="300" valign="top">
<img src="images/cart.gif" width="260" height="300">
</td>
</tr>
</table>
<!-- /main frame -->
</td>
<?
$dont_display_lc = 1;
include "bottom.php";
?>
</body>
</html>
