<table width="100%" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr valign="middle" bgcolor="<? echo $cl_win_cap1 ?>">
          <td height="18"><font color="<? echo $cl_win_title ?>"><b><font size="-1"><i>Categories</i></font></b></font></td>
        </tr>
<?
$c_result = mysql_query("select category, count(*) from products group by category");
while (list($cat,$cat_count) = mysql_fetch_row($c_result)) {
	$catname = ereg_replace("[^\/.]*\/","&nbsp;-&nbsp;",$cat);
echo "<tr><td nowrap bgcolor=".($category == $cat ? $cl_cat_active : $cl_win_tab)." onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($category == $cat ? $cl_cat_active : $cl_win_tab)."'\" onClick=\"window.location='".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/main.php?sortby=$sortby&category=".urlencode($cat)."'\"><a href=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/main.php?sortby=$sortby&category=".urlencode($cat)."\">".($catname == $cat ? "<font size=\"-1\"><b>$catname</b></a> ($cat_count)</font>" : "<font size=\"-2\">$catname</a> ($cat_count)</font>")."</td></tr>\n";
}
?>
      </table>
    </td>
  </tr>
</table>
