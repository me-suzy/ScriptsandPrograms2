<?php
	//May 02, 2005 - 2:29p
	//Revised by Jason Farrell
	//Revision Number 1
	
	$error = false;
	if (isset($_POST['submit'])) {
		include_once "./includes/userManage/passwd_validate.php";
	}
	
	if (isset($_POST['submit']) && !$error) {
		echo "Password Changed Successfully<br/>\n";
		echo '<a href="view_users.php">Click Here to Return to User Control</a>' . chr(10);
	}
	else {
?>
<title>Help Desk User Management Center</title>
<link href="style.css" rel="stylesheet" type="text/css">
<body link="#0000FF" vlink="#0000FF">
<p>
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="">
	<tr><th colspan="2">
		User Selection:
	</td></tr>
	<tr>
		<td>Please Select a User:&nbsp;</th>
		<td>
			<select name="user" size="1">
			<?php
				$q = "select id, user from " . DB_PREFIX . "accounts order by user";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					echo '<option value="' . $r['id'] . '">' . $r['user'] . '</option>' . chr(10);
			?>
			</select>
		</td>
	</tr>
	<tr><th colspan="2">
		Password Information:
	</th></tr>
	<tr>
		<td align="left">Enter Old Password:&nbsp;</td>
		<td><input type="password" name="oldPass" size="15" maxlength="30" /></td>
	</tr>
	<tr>
		<td>Enter New Password:</td>
		<td><input type="password" name="newPass1" size="15" maxlength="30" /></td>
	</tr>
	<tr>
		<td>Confirm New Password:</td>
		<td><input type="password" name="newPass2" size="15" maxlength="30" /></td>
	</tr>
	<tr><td colspan="2">
		<input type="submit" name="submit" value="Change Password" /><br/>
		<span style="color:red"><?php echo isset($page_error) ? $page_error : ''; ?>
	</td></tr>
</form>
</table>
<?php
	}
?>