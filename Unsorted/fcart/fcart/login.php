<?
$logged_in="";
$gift_log=false;
$lresult=mysql_query("select login,points from customers where userid='$id'");
if (mysql_num_rows($lresult) == 1) {
	list($logged_in,$dvdpoints)=@mysql_fetch_row($lresult);
	if ($dvdpoints>=$minimal_dvd_points)
		$dvdpoints_usd = $dvdpoints*$dvd_to_usd;
	}
# gift cert code
else {
$gresult = mysql_query("select giftcerts.amount from giftcerts where giftcerts.cart='$id' and status='S'");
if (mysql_num_rows($gresult) == 1) {
	list($bucks)=@mysql_fetch_row($gresult);
    $logged_in="$gift_logname: \$$bucks";
	$gift_log=true;
    }
# /gift cert code
mysql_free_result($gresult);
}
mysql_free_result($lresult);
echo "<form method=\"".(empty($logged_in) ? "POST" : "GET")."\" action=\"".(empty($logged_in) ? "".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/log_in.php" : "http://$http_location/log_out.php")."\">\n";
if ($transfer_cookie && empty($logged_in))
	echo "<input type=hidden name=id value=\"$id\">";
echo "<input type=hidden name=first value=\"$first\">\n";
echo "<input type=hidden name=sortby value=\"$sortby\">\n";
echo "<input type=hidden name=category value=\"$category\">\n";
?>
<table width="100%" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0"><tr><td> 
<table width="100%" border="0" cellspacing="0" cellpadding="2" height="100%">

<tr bgcolor="<? echo $cl_win_cap1 ?>">
<td valign="middle" colspan="2"><b><font color="<? echo $cl_win_title ?>" size="-1"><i>Log in</i></font></b></td>
</tr>

<?
if (empty($logged_in)) {
echo <<<EOT
<tr bgcolor="$cl_win_tab">
<td nowrap><font size="-1">&nbsp;&nbsp;Username:</font></td>
<td nowrap align="right"><input type="text" name="uname" maxlength="32" size="9"></td>
</tr>
<tr bgcolor="$cl_win_tab">
<td nowrap><font size="-1">&nbsp;&nbsp;Password: </font></td>
<td nowrap align="right"><input type="password" name="upass" maxlength="32" size="9"></td>
</tr>
<tr bgcolor="$cl_win_tab">
<td align="center"><font size="-1">
EOT;
echo "<a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/register.php?".($transfer_cookie ? "id=$id&" : "")."first=$first&sortby=$sortby&category=".urlencode($category)."\" style=\"text-decoration: underline; color: red\">Register<br>for free</a></font></td>";
echo <<<EOT
<td align="center"><input type="submit" value="Log in"></td>
</tr>
EOT;
} else {
	echo <<<EOT
<tr bgcolor="$cl_win_tab">
<td align="center" colspan="2"><font size="-1">Logged in as <b>$logged_in</b></font>
<hr size="1" noshade>
</td>
</tr>
EOT;
	if (!empty($dvdpoints)) {
		echo "<tr bgcolor=\"$cl_win_tab\">";
		echo "<td align=\"center\" colspan=\"2\" nowrap><font size=\"-2\">$bonus_points earned: <b>$dvdpoints</b>";
		echo "</font></td></tr>";
	}
	if (!empty($dvdpoints_usd)) {
		echo "<tr bgcolor=\"$cl_win_tab\" nowrap>";
		echo "<td align=\"center\" colspan=\"2\"><font size=\"-2\">USD value: <b>\$$dvdpoints_usd</b>";
		echo "</font></td></tr>";
	}
	echo <<<EOT
<tr bgcolor="$cl_win_tab">
<td align="center" nowrap><font size="-2">
EOT;
# gift cert code
if (!$gift_log) {
	echo "<a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/register.php?".($transfer_cookie ? "id=$id&" : "")."first=$first&sortby=$sortby&category=".urlencode($category)."&mode=edit\">Edit info</a><br>";
	echo "<a href=\"".($https_enabled=="Y" ? "https://$https_location" : "http://$http_location")."/orders.php?".($transfer_cookie ? "id=$id&" : "")."first=$first&sortby=$sortby&category=".urlencode($category)."\">Orders history</a>";
} else 
	echo "&nbsp;\n";
# /gift cert code
echo <<<EOT
</font></td>
<td align="center"><input type="submit" value="Log out"></td>
</tr>
EOT;
}
?>
</table>
</td></tr></table>
</form>
