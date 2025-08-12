<?php
	//May 02, 2005 - 2:29p
	//Revised by Jason Farrell
	//Revision Number 1
?>
<title>Help Desk User Management Center</title>
<link href="style.css" rel="stylesheet" type="text/css">
<body link="#0000FF" vlink="#0000FF">
<p>
<?php
	$error = false;
	if (isset($_POST['submit'])) {
		include_once "./includes/userManage/addnew_validate.php";
		if ($error)
		{
			//build postdata into local variables
			foreach ($_POST as $k => $v)
				$$k = $v;	
		}
	}
	else {
		//set up default values
		$uname = $fname = $lname = $email_addr = '';
	}

	if (isset($_POST['submit']) && !$error) {
		//everythign submitted and passed validateion, show a success page
		echo "User Added to Database<br/>";
		echo '<a href="view_users.php">Click Here to Return to User Control</a>' . chr(10);
	}
	else {
		//default for page for inital entry and errored submission
?>
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="">
	<tr><th colspan="2" align="left">
		Login Information
	</td></tr>
	<tr>
		<td valign="top">Username:&nbsp;</td>
		<td valign="top">
			<input type="text" name="uname" size="20" maxlength="30" value="<?php echo $uname; ?>" />
			<?php echo isset($uname_error) ? "<br/>\n<span style='color:red'>" . $uname_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr>
		<td valign="top">Password:&nbsp;</td>
		<td valign="top">
			<input type="password" name="pass1" size="20" maxlength="30" /><br/>
			<?php echo isset($pass_error) ? "<br/>\n<span style='color:red'>" . $pass_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr><td height="3"></td></tr>
	<tr><th colspan="2" align="left">
		Personal Information
	</td></tr>
	<tr>
		<td valign="top">First Name:&nbsp;</td>
		<td valign="top">
			<input type="text" name="fname" size="20" maxlength="30" value="<?php echo $fname; ?>" />
			<?php echo isset($fname_error) ? "<br/>\n<span style='color:red'>" . $fname_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr>
		<td valign="top">Last Name:&nbsp;</td>
		<td valign="top">
			<input type="text" name="lname" size="20" maxlength="30" value="<?php echo $lname; ?>" />
			<?php echo isset($lname_error) ? "<br/>\n<span style='color:red'>" . $lname_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr>
		<td valign="top">User Type:</td>
		<td valign="top">
			<select name="userType">
				<option value="2">Administrator</option>
				<option value="1">Technican</option>
				<option value="0">Registered End User</option>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top">Email Address:&nbsp;</td>
		<td valign="top">
			<input type="text" name="email_addr" size="20" maxlength="30" value="<?php echo $email_addr; ?>" />
			<?php echo isset($email_error) ? "<br/>\n<span style='color:red'>" . $email_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr>
		<td valign="top">Phone Number:&nbsp;</td>
		<td valign="top">
			<input type="text" name="phoneNum" size="20" maxlength="30" value="<?php echo isset($_POST['phoneNum']) ? $_POST['phoneNum'] : ''; ?>" />
			<?php echo isset($phoneNum_error) ? "<br/>\n<span style='color:red'>" . $phoneNum_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr>
		<td valign="top">Phone Extension (Optional):&nbsp;</td>
		<td valign="top">
			<input type="text" name="phoneExt" size="20" maxlength="30" value="<?php echo isset($_POST['phoneExt']) ? $_POST['phoneExt'] : ''; ?>" />
			<?php echo isset($phoneExt_error) ? "<br/>\n<span style='color:red'>" . $phoneExt_error . "</span>\n" : ''; ?>
		</td>
	</tr>
	<tr><td colspan="2" align="center">
		<input type="submit" name="submit" value="Submit" class="button" />
	</td></tr>
</form>
</table>
<?php
	}
?>