<?
echo "<form method=\"GET\" action=\"".($https_adm_enabled=="Y" ? "https://$https_adm_location" : "http://$http_adm_location")."/search.php\">";
echo "<input type=hidden name=sortby value=\"$sortby\">";
?>
<table width="100%" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0">
<tr>
<td> 
<table width="100%" border="0" cellspacing="0" cellpadding="2" height="100%">
<tr bgcolor="<? echo $cl_win_cap1 ?>">
<td valign="middle"><font color="<? echo $cl_win_title ?>"><b><font size="-1"><i>Search</i></font></b></font></td>
</tr>
<tr bgcolor="<? echo $cl_win_tab ?>">
<td  nowrap align="center"><font size="-2">Search for id/substring:</font><br>
<input type="text" name="productid" maxlength="5" size="3">
<input type="text" name="key" maxlength="64" size="11">
</td>
</tr>
<tr bgcolor="<? echo $cl_win_tab ?>">
<td  nowrap align="center"><font size="-2">In category: </font><br>
<select name="category">
<option value="All" selected>All</option>
<?
	mysql_data_seek($c_result,0);
	while ($row = mysql_fetch_row($c_result)) {
		$r = unquote($row[0]);
		if (!strstr($r,"/")) echo "<option value=\"$r\">$r</option>\n";
	}
?>
</select>
</td>
</tr>
<tr>
<td bgcolor="<? echo $cl_win_tab ?>" align="center">
<input type="submit" value="Search">
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>
