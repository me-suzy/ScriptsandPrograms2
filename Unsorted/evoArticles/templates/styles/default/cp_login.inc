$error
<br />
<form method="POST" action="$_SERVER[PHP_SELF]?login">
<table cellpadding="0" cellspacing="0" align="center" width="80%">
<tr><td class="tblborder"><table border="0" cellpadding="4" cellspacing="0" width="100%">
<tr><td class="tblhead" colspan="2">$evoLANG[logintext]:</td></tr>
<tr>
<td class="secondalt" width="30%"><b>$evoLANG[username]</b></td> <td class="secondalt"><input type="text" name="username" style="width:90%" /></td>
</tr>
<tr>
<td class="secondalt" width="30%"><b>$evoLANG[password]</b></td> <td class="secondalt"><input type="password" name="password" style="width:90%" />
<input type="hidden" name="redirect" value="$redirect" />
</td>
</tr>

<tr class="tblhead"><td align="center" colspan="2">
<input type="submit" name="auth" value="$evoLANG[logintext]" />
</td></tr>
</table></td></tr></table>
</form>



