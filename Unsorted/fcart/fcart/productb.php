<?
include "config.php";
include "mod.php";

$productid = d_secure($HTTP_GET_VARS["productid"]);

?>
<html>
<head><? include "meta.php" ?>
<title>Product information</title>
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
$result = mysql_query("select product,category,price,image,descr,avail from products where productid='$productid'");
list($product,$category,$price,$image,$descr,$avail) = mysql_fetch_row($result);
mysql_free_result($result);
echo "<font color=\"$cl_header\" size=\"+1\"><b>Category: $category</b></font><hr><center>\n";

echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="$cl_mod_border">
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%"><img align="left" src="$images_url/$image"></td>
<td bgcolor="$cl_mod_bg" valign="top">
<b>$product</b><br>
<p align="justify"><font size="-1">$descr</font></p>
<b>Price: <font color="$cl_mod_price">\$$price</font></b><br>
EOT;
echo "<b>Available:</b> ".($avail=="Y" ? "Yes" : "<font color=\"$cl_mod_price\"><b>No</b></font>");
echo <<<EOT
</td></tr>
</table>
</td></tr></table>
<form><font size="-1"><b><input type="button" value="Close" onClick="javascript:self.close()"></b></font></form></center></body></html>
EOT;
?>
