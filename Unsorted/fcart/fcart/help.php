<script language='javascript'>
function Help() {
	window.open('<? echo "http://$http_location/"; ?>h1.php','_blank','width=600,height=480,toolbar=no,scrollbars=yes,resize=yes');
}
</script>
<table width="100%" border="0" bgcolor="<? echo $cl_win_border ?>" cellpadding="1" cellspacing="0">
<tr> 
<td> 
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr valign="middle" bgcolor="<? echo $cl_win_cap1 ?>"> 
<td height="18" nowrap><font color="<? echo $cl_win_title ?>"><b><font size="-1"><i><img src="images/qu.gif" width="17" height="16" align="top">&nbsp;Help</i></font></b></font></td>
</tr>
<tr bgcolor="<? echo $cl_win_tab ?>"><td height="17"><font size="-1"><a href="javascript:Help()">How to register?</a></font></td></tr>
<tr bgcolor="<? echo $cl_win_tab ?>"><td><font size="-1"><a href="javascript:Help()">How to order?</a></font></td></tr>
<tr bgcolor="<? echo $cl_win_tab ?>"><td><font size="-1"><a href="javascript:Help()">What is gift certificate?</a></font></td></tr>
<tr bgcolor="<? echo $cl_win_tab ?>"><td height="16"><font size="-1"><a href="javascript:Help()">Forgot your password?</a></font></td></tr>
<tr bgcolor="<? echo $cl_win_tab ?>"><td height="16"><font size="-1"><a href="javascript:Help()">More help...</a></font></td></tr>
</table>
</td>
</tr>
</table>
