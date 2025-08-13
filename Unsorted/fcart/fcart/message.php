<?
include "config.php";
include "mod.php";
include "params.php";

if ($HTTPS=="on" && $transfer_cookie) {
$id = $REQUEST_METHOD == "POST" ? $HTTP_POST_VARS["id"] : $HTTP_GET_VARS["id"];
$id = r_secure($id);
} else {
include "cookie.php";
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
include "poweredby.php";
?>
</td>
<td colspan="<? echo $tabcount-1; ?>" bgcolor="<? echo $cl_tab_top ?>" height="600" valign="top">
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
include "bottom.php";
?>
</body>
</html>

