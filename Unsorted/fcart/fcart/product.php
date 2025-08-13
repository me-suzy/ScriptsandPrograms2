<?
include "config.php";
include "mod.php";
include "params.php";

include "cookie.php";

$productid = d_secure($HTTP_GET_VARS["productid"]);

?>
<html>
<head><? include "meta.php" ?>
<title><? echo "$main_title"; ?>: View product</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<script language="javascript">
function expandwindow() {
    if (self.document.height) {
        doc_height = self.document.height;
        doc_width = self.document.width;
    	self.resizeTo(doc_width, doc_height);
	}
}
</script>
<? include "cssstyle.php" ?>
</head>
<body bgcolor="<? echo $cl_tab_top ?>" onLoad="expandwindow()">
<?
$result = mysql_query("select product, price, image, descr, category, productid from products where productid='$productid' and avail='Y'");
list($product,$price,$image,$descr,$category,$productid) = mysql_fetch_row($result);

echo "<font color=\"$cl_header\" size=\"+1\"><b>Category: $category</b></font><br>\n";
mysql_free_result($result);
?>
<center>
<?
$c_ = urlencode(unquote($category));
echo "<form onSubmit=\"javascript:window.opener.location='http://$http_location/add.php?productid=$productid&first=$first&sortby=$sortby&category=$c_&amount='+window.document.forms[0].amount.value; window.close()\">";
echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="$cl_mod_border">
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%"><img align="left" src="$images_url/$image"></td>
<td bgcolor="$cl_mod_bg" valign="top" rowspan="2"><b>$product</b><br>
<p align="justify"><font size="-1">$descr</font></p>
<b>Price: <font color="$cl_mod_price">\$$price</font></b>
<table border="0"><tr><td>
<i>Quantity:</i> 
<input type=text name=amount value=1 size=2 maxlength=2></td>
<td>
EOT;
echo "<a href=\"javascript:window.opener.location='http://$http_location/add.php?productid=$productid&first=$first&sortby=$sortby&category=$c_&amount='+window.document.forms[0].amount.value; window.close()\">";
echo <<<EOT
<img src="images/load.gif" width="29" height="32" border="0" alt="Add to shopping cart">
</a>
</td></tr>
</table>
</td></tr>
<tr><td bgcolor="$cl_mod_bg" align="center">
EOT;
echo "<a href=\"javascript:window.opener.location='http://$http_location/add.php?productid=$productid&first=$first&sortby=$sortby&category=$c_&wish.x=0&amount='+window.document.forms[0].amount.value; window.close()\">";
echo <<<EOT
<img src="images/add2wl.gif" width="72" height="9" border="0" alt="Add to wish list">
</a>
</td></tr>
</table>
</td></tr></table>
</form>
EOT;
?>
<form><font size="-1"><b><input type="button" value="Close" onClick="javascript:window.close()"></b></font></form>
</center>
</body>
</html>
