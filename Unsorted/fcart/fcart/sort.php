<table width="98%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="<? echo $cl_sort_bg ?>" align="center" nowrap><font size="-1"><b><font color="<? echo $cl_sort_font ?>">Sort:</font></b></font></td>
  <td bgcolor="<? echo $cl_sort_border ?>">
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
      <tr>
<?
echo "<td align=\"center\" nowrap onClick=\"self.location='main.php?sortby=alpha&first=$first&category=".urlencode($category)."'\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($sortby == "alpha" ? $cl_sort_active : $cl_sort_deactive)."'\" bgcolor=".($sortby == "alpha" ? $cl_sort_active : $cl_sort_deactive)."><font size=\"-1\"><a href=\"main.php?sortby=alpha&first=$first&category=".urlencode($category)."\"><b>alphabetically</b></a></font></td>\n";
echo "<td align=\"center\" nowrap onClick=\"self.location='main.php?sortby=age&first=$first&category=".urlencode($category)."'\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($sortby == "age" ? $cl_sort_active : $cl_sort_deactive)."'\" bgcolor=".($sortby == "age" ? $cl_sort_active : $cl_sort_deactive)."><font size=\"-1\"><a href=\"main.php?sortby=age&first=$first&category=".urlencode($category)."\"><b>by date</b></a></font></td>\n";
echo "<td align=\"center\" nowrap onClick=\"self.location='main.php?sortby=rating&first=$first&category=".urlencode($category)."'\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($sortby == "rating" ? $cl_sort_active : $cl_sort_deactive)."'\" bgcolor=".($sortby == "rating" ? $cl_sort_active : $cl_sort_deactive)."><font size=\"-1\"><a href=\"main.php?sortby=rating&first=$first&category=".urlencode($category)."\"><b>by rating</b></a></font></td>\n";
echo "<td align=\"center\" nowrap onClick=\"self.location='main.php?sortby=price&first=$first&category=".urlencode($category)."'\" onMouseOver=\"bgColor = '$cl_tab_top'\" onMouseOut=\"bgColor = '".($sortby == "price" ? $cl_sort_active : $cl_sort_deactive)."'\" bgcolor=".($sortby == "price" ? $cl_sort_active : $cl_sort_deactive)."><font size=\"-1\"><a href=\"main.php?sortby=price&first=$first&category=".urlencode($category)."\"><b>by price</b></a></font></td>\n";
?>
      </tr>
    </table>
  </td>
</tr>
</table>
