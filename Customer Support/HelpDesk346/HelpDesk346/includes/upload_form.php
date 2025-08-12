<table cellpadding="0" cellspacing="0" border="0">
	<tr><td style="color:blue; font-weight:bold; padding-top:10px" colspan="2">
		Upload a File Associated with this Problem
	</td></tr>
<?php
	if (isset($_SESSION['enduser']))
		$u = unserialize($_SESSION['enduser']);
	if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true)		//no login case
	{
?>
	<tr>
		<td style="font-weight:bold">Username:&nbsp;</td>
		<td><input type="text" name="uname" size="10" maxlength="30" /></td>
	</tr>
	<tr><td height="5"></td></tr>
	
	<tr>
		<td style="font-weight:bold">Password:&nbsp;</td>
		<td><input type="password" name="upass" size="10" maxlength="30" /></td>
	</tr>
	<tr><td height="5"></td></tr>
	
	<tr><td colspan="2" style="font-family:Arial; font-size:12px; color:red">
		<input type="submit" name="command" value="Login" /><br/>
		<?php echo isset($error_msg) ? $error_msg : ''; ?>
	</td></tr>
<?php
	}
	else if ( (isset($_SESSION['enduser']) && ($t->get('regUser') == $u->get('id'))) || ($u->get('securityLevel') > ENDUSER_SECURITY_LEVEL) || ($t->get('regUser', 'intval') == 0) ) {	//login to viwe your ticket
?>
	<tr>
		<td style="font-weight:bold">Select a File:&nbsp;</td>
		<td><input type="file" name="file" size="50" maxlength="255" /></td>
	</tr>
	<tr><td height="5"></td></tr>
<?php
	}
	else {	//nothing to do
?>
	<tr><td colspan="2" style="color:red; font-weight:bold; font-family: Arial; font-size:10pt">
		You are Logged In, But Not Registered with this Ticket - Upload Not Permitted
	</td></tr>
<?php
	}
?>
</table>