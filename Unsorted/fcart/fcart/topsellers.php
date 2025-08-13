<table width="80%" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0">
  <tr> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr valign="middle" bgcolor="<? echo $cl_win_cap1 ?>"> 
          <td height="18" nowrap><font color="<? echo $cl_win_title ?>"><b><font size="-1"><i>&nbsp;Top sellers</i></font></b></font></td>
<?
$tresult = mysql_query("select productid, product from products where avail='Y' order by rating desc limit $items_toplist");
$count = 1;
while (list($productid, $product) = mysql_fetch_row($tresult)){
	echo "<tr bgcolor=\"$cl_win_tab\"><td nowrap><font size=\"-2\"><b>$count:</b> <a href=\"javascript: display_product('$productid')\">".trimm($product,28)."</a></font></td></tr>";
	$count++;
}
mysql_free_result($tresult);
?>
        </tr>
      </table>
    </td>
  </tr>
</table>
