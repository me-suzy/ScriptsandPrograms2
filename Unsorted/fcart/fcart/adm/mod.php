<?
function admin_product($mode, $product, $category, $price, $image, $descr, $avail, $productid, $rating) {
global $images_url, $first, $sortby, $QUERY_STRING, $c_result;
global $cl_mod_bg, $cl_mod_price, $cl_mod_border, $cl_mod_upsel1, $cl_mod_upsel2;
global $https_adm_enabled, $http_adm_location, $https_adm_location;

$c_ = unquote($category);
$p_ = unquote($product);

echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="$cl_mod_border">
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%" align="left">
<font size="-1"><b>ID: $productid<br>Rating: $rating<br></b></font>
<img align="left" src="../$images_url/$image">
</td><td bgcolor="$cl_mod_bg" rowspan="2">
EOT;
echo "<form enctype=\"multipart/form-data\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/update.php\" method=\"POST\">";
echo <<<EOT
<input type="text" value="$product" size="50" name="product"><br>
EOT;
echo "<input type=\"checkbox\" name=\"avail\"".($avail=="Y" ? "checked":"").">";
echo "<font size=\"-1\" color=\"$cl_header\"><b>Available</b></font><br>\n";
echo <<<EOT
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td nowrap>
<font size="-1"><b>Image: $image&nbsp;&nbsp;&nbsp;&nbsp;</b></font></td>
<td>
<input type="hidden" name="MAX_FILE_SIZE" value="102400">
<input type="file" size="20" name="userfile"></td></tr></table>

<textarea rows="5" cols="50" name="descr">$descr</textarea><br>
<select name="category">
EOT;

mysql_data_seek($c_result,0);
while ($row = mysql_fetch_row($c_result)) {
        $r = unquote($row[0]);
        if ($r != $category) {
                echo "<option value=\"$r\">$r</option>\n"; } else {
                echo "<option value=\"$r\" selected>$r</option>\n";
        }
}

echo <<<EOT
</select>
<input type=text value="" size="14" name="newcategory">&nbsp;&nbsp;&nbsp;
<b>Price: <font color="$cl_mod_price">\$&nbsp;<input type="text" size="6" name="price" value="$price"></font></b>
<input type="Submit" name="update" value="Apply">
<input type=hidden name=_first value="$first">
<input type=hidden name=_sortby value="$sortby">
<input type=hidden name=_category value="$c_">
<input type=hidden name=oldimage value="$image">
<input type=hidden name=productid value="$productid">
EOT;
echo "<input type=hidden name=mode value=\"$mode\">";
echo "</form>";
# ------------ upselling ---------------------
$upsell_first = true;
$u_result = mysql_query("select link from product_links where productid='$productid'");
while ($row = @mysql_fetch_row($u_result)) {
        if ($upsell_first) {
                $upsell_first = false;
                echo "<table width= \"100%\"cellspacing=\"1\" cellpadding=\"4\" border=\"0\">\n";
                echo "<tr><td bgcolor=\"$cl_mod_upsel1\"><font size=\"-2\">Upselling links:</font></td></tr><tr><td bgcolor=\"$cl_mod_upsel2\">\n";
        }
        $id = $row[0];
        $i_result = mysql_query("select product, productid from products where productid='$id'");
        if ($row = mysql_fetch_row($i_result)) {
                $i_product = $row[0];
                $i_productid = $row[1];
                echo "<img src=\"../images/pix.gif\" width=\"8\" height=\"8\"><font size=\"-2\">&nbsp;&nbsp;<a href=\"javascript:display_product('$i_productid')\">$i_product</a> <b>ID:$i_productid</b>";
	echo "<a href=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/updatelink.php?mode=delete&productid1=$productid&productid2=$i_productid&$QUERY_STRING\"> Delete </a><br>\n";
                #echo "&nbsp;&nbsp;(<a href=".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/add.php?productid=$i_productid&amount=1&first=$first&sortby=$sortby&category=$category&wish.x=1&wish.y=1>Add to WL</a>)</font><br>\n";
        }
        mysql_free_result($i_result);
}
if ($upsell_first == false) { echo "</td></tr></table>"; }
if ($productid != "") {
echo "<form action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/updatelink.php\" method=\"GET\">";
echo <<<EOT
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$c_">
<input type=hidden name=mode value="add">
<input type=hidden name=productid1 value="$productid">
<font size="-1"><b>Link ID: </b></font><input type="text" name="productid2" size="4">
&nbsp;&nbsp;<input type="checkbox" name="twotier"><font size="-1"><b>Create two-tier link</b>&nbsp;&nbsp;</font>
<input type="Submit" value="Add link">
</form>
EOT;
}

# ------------ upselling --------------------- 

echo <<<EOT
</td></tr>
<tr><td bgcolor="$cl_mod_bg" valign="top">
EOT;
if($mode == "update") {
echo "<form action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/delete.php\" method=\"get\">";
echo "<input type=hidden name=productid value=\"$productid\">";
echo "<input type=hidden name=first value=\"$first\">";
echo "<input type=hidden name=sortby value=\"$sortby\">";
echo "<input type=hidden name=category value=\"$c_\">";
echo "<input type=\"Submit\" name=\"delete\" value=\"Delete item\">";
echo "</form>";
} else {echo "<br>&nbsp;"; }
echo <<<EOT
</td></tr></table>
</td></tr></table><br>
EOT;
}

function browse_product($product, $price, $image, $descr, $amount, $mode, $productid) {
global $images_url, $first, $sortby, $category, $wish;

$c_ = unquote($category);

echo <<<EOT
<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr><td bgcolor="$cl_mod_border">
<table border="0" width="100%" cellspacing="0" cellpadding="8"><tr><td valign="top" bgcolor="$cl_mod_bg" width="1%">
<font size="-1"><b>ID: $productid</b></font>
<img align="left" src="../$images_url/$image"></td>
<td bgcolor="$cl_mod_bg" valign="top" rowspan="2"><b>$product</b><br>
<p align="justify"><font size="-1">$descr</font></p>
<b>Price: <font color="$cl_mod_price">\$$price</font></b>
<input type=hidden name=productid value="$productid">
<input type=hidden name=first value="$first">
<input type=hidden name=sortby value="$sortby">
<input type=hidden name=category value="$c_">
</td></tr>
</table>
</td></tr></table>
EOT;
}

function safe_mode_msg ($need_head) {
	echo "<h3>Permission denied.</h3>";
	echo "You cannot do this in guest admin mode.";
	exit;
}
?>
