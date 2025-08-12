<table cellpadding="0" cellspacing="0" align="center" style="border: 1px solid red; padding:5px">
<form method="post" action="">
	<tr><td colspan="2" align="center" style="font-family:Arial">
		<strong>Registed User Log In</strong>
	</td></tr>
	<tr><td height="5"></td></tr>
	
	<tr>
		<td style="font-family:Arial; font-size:12px; color:black; font-weight:bold">Username:&nbsp;</td>
		<td><input type="text" name="uname" size="20" maxlength="30" /></td>
	</tr>
	<tr><td height="5"></td></tr>
	
	<tr>
		<td style="font-family:Arial; font-size:12px; color:black; font-weight:bold">Password:&nbsp;</td>
		<td><input type="password" name="upass" size="20" maxlength="30" /></td>
	</tr>
	<tr><td height="5"></td></tr>
	
	<tr><td colspan="2" style="text-align:center; font-family:Arial; font-weight:bold; color:red; font-size:10px">
		<input type="submit" name="command" value="Login" /><br/>
		<?php echo isset($error_msg) ? $error_msg : ''; ?>
	</td></tr>
</form>
</table>