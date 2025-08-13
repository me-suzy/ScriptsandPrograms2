<? if (strstr($SCRIPT_NAME,"index.php")) $category="null" ?>
<table width="100%" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr valign="middle" bgcolor="<? echo $cl_win_cap1 ?>">
          <td height="18"><font color="<? echo $cl_win_title ?>"><b><font size="-1"><i>Categories</i></font></b></font></td>
        </tr>
<?
$c_result = mysql_query("select category, count(*) from products where avail='Y' group by category");

while ($row = mysql_fetch_row($c_result))
	$rows[$row[0]] = $row[1];
$keys = array_keys($rows);
reset($rows);
for ($i = 0; $i < count($keys); $i++) {
	$c = 0;
	for ($j = $i; $j < count($keys) && ereg("^".$keys[$i], $keys[$j]); $j++)
		$c += $rows[$keys[$j]];
	$rows[$keys[$i]] = $c;
}
reset($rows);
if ($cat_pulldown=='Y') {
	while ($k = each($rows)) {
		$cat = $k[0];
		$cat_count = $k[1];
		if (ereg("^".$cat, $category) || !strchr($cat, "/") || ereg("^".$category."/[^/]*$", $cat)) {
			echo "<tr><td nowrap bgcolor=".(($category == $cat) && !strstr($SCRIPT_NAME,"gift.php") ? $cl_cat_active : $cl_win_tab)." onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".(($category == $cat) && !strstr($SCRIPT_NAME,"gift.php") ? $cl_cat_active : $cl_win_tab)."'\" onClick=\"window.location='http://$http_location/main.php?sortby=$sortby&category=".urlencode($cat)."'\">";
			for ($cat_ = $cat; ereg("/", $cat_); $cat_ = substr($cat_, strpos($cat_, "/")+1))
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			$cat_ = ereg_replace(".*/", "", $cat);
			echo "<font size=\"-1\">";
			if ($cat_ == $cat) echo "<b>";
			echo "<a href=\"http://$http_location/main.php?sortby=$sortby&category=".urlencode($cat)."\">$cat_</a>";
			if ($cat_ == $cat) echo "</b>";
			echo " ($cat_count)</font></td></tr>\n";
		}
	}
} else {
	while ($k = each($rows)) {
		$cat = $k[0];
		$cat_count = $k[1];
    	$catname = ereg_replace("[^\/.]*\/","&nbsp;-&nbsp;",$cat);
	echo "<tr><td nowrap bgcolor=".($category == $cat ? $cl_cat_active : $cl_win_tab)." onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($category == $cat ? $cl_cat_active : $cl_win_tab)."'\" onClick=\"window.location='http://$http_location/main.php?sortby=$sortby&category=".urlencode($cat)."'\"><a href=\"http://$http_location/main.php?sortby=$sortby&category=".urlencode($cat)."\">".($catname == $cat ? "<font size=\"-1\"><b>$catname</b></a> ($cat_count)</font>" : "<font size=\"-2\">$catname</a> ($cat_count)</font>")."</td></tr>\n";
	}
	#while (list($cat,$cat_count) = mysql_fetch_row($c_result)) {
    	#$catname = ereg_replace("[^\/.]*\/","&nbsp;-&nbsp;",$cat);
	#echo "<tr><td nowrap bgcolor=".($category == $cat ? $cl_cat_active : $cl_win_tab)." onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($category == $cat ? $cl_cat_active : $cl_win_tab)."'\" onClick=\"window.location='http://$http_location/main.php?sortby=$sortby&category=".urlencode($cat)."'\"><a href=\"http://$http_location/main.php?sortby=$sortby&category=".urlencode($cat)."\">".($catname == $cat ? "<font size=\"-1\"><b>$catname</b></a> ($cat_count)</font>" : "<font size=\"-2\">$catname</a> ($cat_count)</font>")."</td></tr>\n";
	#}
}

if(!$gift_log)
	echo "<tr><td bgcolor=".(strstr($SCRIPT_NAME,"gift.php") ? $cl_cat_active : $cl_win_tab)." onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".(strstr($SCRIPT_NAME,"gift.php") ? $cl_cat_active : $cl_win_tab)."'\" onClick=\"window.location='".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/gift.php".($transfer_cookie ? "?id=$id" : "")."'\"><a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/gift.php".($transfer_cookie ? "?id=$id" : "")."\"><font size=\"-1\"><b>Gift certificates</b></font></a></td></tr>\n";
?>
      </table>
    </td>
  </tr>
</table>
