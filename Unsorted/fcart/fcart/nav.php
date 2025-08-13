<table width="98%" border="0" cellpadding="0" cellspacing="1">
    <tr bgcolor="<? echo $cl_sort_bg ?>"> 
<?
$nresult = mysql_query("select count(*) from products where category like '$category%'");
$row = mysql_fetch_row($nresult);
$num = $row[0];
mysql_free_result($nresult);
$nav_first = $first - (($first-1) % $items_per_nav);
if ($nav_first > 1) {
		$nav_first_ = $nav_first - $items_per_nav >= 1 ? $nav_first - $items_per_nav : 1;
        echo "<td width=\"50\" align=\"center\" height=\"14\" bgcolor=\"$cl_nav_deactive\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '$cl_nav_deactive'\"><font size=\"-1\"><b><a href=\"http://$http_location/main.php?sortby=$sortby&first=$nav_first_&category=".urlencode($category)."\">&nbsp;&lt;&lt;&lt;&nbsp;"."</a></b></font></td>\n";
}
for ($i = $nav_first; ($i <= $num) && ($i <= $nav_first + $items_per_nav - 1); $i+= $items_per_page) {
        echo "<td width=\"50\" nowrap align=\"center\" height=\"14\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".(($first >= $i) && ($first < $i+$items_per_page) ? $cl_sort_active : $cl_nav_deactive)."'\" bgcolor=".(($first >= $i) && ($first < $i+$items_per_page) ? $cl_sort_active : $cl_nav_deactive)."><font size=\"-1\"><b><a href=\"http://$http_location/main.php?sortby=$sortby&first=$i&category=".urlencode($category)."\">&nbsp;&nbsp;$i...".($i+$items_per_page-1 > $num ? $num : $i+$items_per_page-1)."&nbsp;&nbsp;"."</a></b></font></td>\n";
}
$nav_first_ = $nav_first + $items_per_nav;
if ($nav_first_ <= $num) {
        echo "<td width=\"50\" align=\"center\" height=\"14\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '$cl_nav_deactive'\" bgcolor=\"$cl_nav_deactive\"><font size=\"-1\"><b><a href=\"http://$http_location/main.php?sortby=$sortby&first=$nav_first_&category=".urlencode($category)."\">&nbsp;&gt;&gt;&gt;&nbsp;"."</a></b></font></td>\n";
}
echo "<td bgcolor=\"$cl_sort_bg\" width=\"90%\" align=\"right\" nowrap height=\"14\"><font size=\"-1\"><b>$num items&nbsp;&nbsp;</b></font></td>\n";
?>
  </tr>
</table>
