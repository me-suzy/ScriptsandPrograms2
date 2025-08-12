<?php
// When the user submits the make new user form
if ($accounts_action=="make_account") {
	// Converting any HTML to standard text in the user data
	$new_account_username = htmlspecialchars($new_account_username);
	$new_account_account = htmlspecialchars($new_account_account);
	$new_account_name = htmlspecialchars($new_account_name);
	$new_account_email = htmlspecialchars($new_account_email);
				
	// Triming blank spaces at the start and end of the user data
	$new_account_username = trim($new_account_username);
	$new_account_account = trim($new_account_account);
	$new_account_name = trim($new_account_name);
	$new_account_email = trim($new_account_email);
				
	// Making sure the form is not blank
	if ($new_account_username==false || $new_account_password==false || $new_account_name==false || $new_account_email==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
					
	//Making sure that the two passwords match (checking Password with Confirm Password)
	if($new_account_password!=$new_account_con_password)
	{
		echo "<html><body><script language=javascript1.1>alert('Your passwords do not match'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Every thing is ok save details in the MySQL database
	$new_account_password = md5($new_account_password);
	$sqlMakeUser = "INSERT $mysql_pre$mysql_admin SET username='$new_account_username',password='$new_account_password',name='$new_account_name',email='$new_account_email'";
	$resultMakeUser = mysql_query($sqlMakeUser) or die(mysql_error()); ;
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('User account made'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

/////////////////////////////////////////////////

//  When the user submits the edit user form
if ($accounts_action=="edit_account") {
	// Converting any HTML to standard text in the user data
	$edit_account_username = htmlspecialchars($edit_account_username);
	$edit_account_account = htmlspecialchars($edit_account_account);
	$edit_account_name = htmlspecialchars($edit_account_name);
	$edit_account_email = htmlspecialchars($edit_account_email);
					
	// Triming blank spaces at the start and end of the user data
	$edit_account_username = trim($edit_account_username);
	$edit_account_account = trim($edit_account_account);
	$edit_account_name = trim($edit_account_name);
	$edit_account_email = trim($edit_account_email);
					
	// Making sure the form is not blank
	if ($edit_account_username==false || $edit_account_name==false || $edit_account_email==false || $edit_account_id==false || $edit_old_pw==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	//Making sure that the two passwords match (checking Password with Confirm Password)
	if($edit_account_password!=$edit_account_con_password)
	{
		echo "<html><body><script language=javascript1.1>alert('Your passwords do not match'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}

	// Checking if password was set
	if ($edit_account_password==false) {
		$edit_account_password = $edit_old_pw;
	}
	else
	{
		$edit_account_password = md5($edit_account_password);
		$admin_logout = "yes";
	}
				
	// All ok saving details in MySQL database
	$sqlSEditUser = "UPDATE $mysql_pre$mysql_admin SET password='$edit_account_password',name='$edit_account_name',email='$edit_account_email' WHERE id=$edit_account_id;";
	$resultSEditUser = mysql_query($sqlSEditUser) or die(mysql_error()); ;
				
	// Checking to see if they edited them self
	if ($edit_account_id=="$user_id" && $admin_logout=="yes") {
		echo "<html><body><script language=javascript1.1>alert('Your password has been changed you will now be logged out'); window.location='$PHP_SELF?module=logout';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	echo "<html><body><script language=javascript1.1>alert('User account edited'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";		
	exit;
}

/////////////////////////////////////////////////

//  When the user submits the edit new user form
if ($accounts_action=="delete") {

	// Making sure that admins don't delete them selfs!
	if ($account_id=="$user_id") {
		echo "<html><body><script language=javascript1.1>alert('Error you cannot delete your self'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
			
	// Making sure they do not delete the first account
	if ($account_id=="1") {
		echo "<html><body><script language=javascript1.1>alert('Error you cannot delete the first account'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";					
		exit;
	}
	else
	{
		$sqlDeleteUser = "DELETE FROM $mysql_pre$mysql_admin WHERE id=$account_id";
		$resultDeleteUser = mysql_query($sqlDeleteUser) or die(mysql_error()); ;
		echo "<html><body><script language=javascript1.1>alert('User account deleted'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	}
	exit;

}

// Showing a form for the user to make a new admin account
if ($accounts_action=="new")
{
?>

	<center>New Staff Account<br><br>
	<a href="javascript:history.back()"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&accounts_action=make_account">
	<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
		<tr> 
			<td class=color3>Username:</td>
			<td class=color2><input class="Input" name="new_account_username" type="text" maxlength="16" id="new_account_username"></td>
		</tr>
		<tr> 
			<td class=color3>Password:</td>
			<td class=color2><input class="Input" name="new_account_password" type="password" maxlength="16" id="new_account_password"></td>
		</tr>
		<tr> 
			<td class=color3>Confirm Password:</td>
			<td class=color2><input class="Input" name="new_account_con_password" type="password" maxlength="16" id="new_account_con_password"></td>
		</tr>
		<tr> 
			<td class=color3>Full Name:</td>
			<td class=color2><input class="Input" name="new_account_name" type="text" maxlength="30" id="new_account_name"></td>
		</tr>
		<tr> 
			<td class=color3>Email:</td>
			<td class=color2><input class="Input" name="new_account_email" type="text" id="new_account_email"></td>
		</tr>
		<tr> 
			<td class=color3>&nbsp;</td>
			<td class=color3><input class="Input" name="make_account" type="submit" id="make_account" value="Ok"></td>
		</tr>
	</table>
	</form>
<?php
}

if($accounts_action=="edit")
{
	// Making sure that another user cannot edit account number 1
	if($account_id==1 && $user_id!=1) {
		echo "<html><body><script language=javascript1.1>alert('Please login to this account to edit it'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Fetching user details
	$sqlEditUser = mysql_query("SELECT * FROM $mysql_pre$mysql_admin WHERE id=$account_id",$db);
	$resultEditUser = mysql_fetch_array($sqlEditUser);
	?>

	<center>Edit Staff Account<br>
	<a href="javascript:history.back()"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&accounts_action=edit_account">
	<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
		<tr> 
			<td class=color3>Username:</td>
			<td class=color2><input readonly class="Input" name="edit_account_username" type="text" maxlength="16" id="edit_account_username" value="<?php echo $resultEditUser["username"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Password*:</td>
			<td class=color2><input class="Input" name="edit_account_password" type="password" maxlength="16" id="edit_account_password"></td>
		</tr>
		<tr> 
			<td class=color3>Confirm Password*:</td>
			<td class=color2><input class="Input" name="edit_account_con_password" type="password" maxlength="16" id="edit_account_con_password"></td>
		</tr>
		<tr> 
			<td class=color3>Name:</td>
			<td class=color2><input class="Input" name="edit_account_name" type="text" maxlength="30" id="edit_account_name" value="<?php echo $resultEditUser["name"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Email:</td>
			<td class=color2><input class="Input" name="edit_account_email" type="text" id="edit_account_email" value="<?php echo $resultEditUser["email"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>&nbsp;</td>
			<td class=color3>
			<input name="edit_account_id" type="hidden" id="edit_account_id" value="<?php echo $resultEditUser["id"]; ?>">
			<input name="edit_old_pw" type="hidden" id="edit_old_pw" value="<?php echo $resultEditUser["password"]; ?>">
			<input class="Input" name="edit_account" type="submit" id="edit_account" value="Ok">
			</td>
		</tr>
	</table>
	</form>
	<center>*Leave this blank if you do not want to change the password!</center>

<?php 
}

if ($accounts_action==false) {
	?>
	<script language='javascript'>
	<!--
	function delete_user(theURL) {
		if (confirm('Are you sure you want to delete this user?')) {
		window.location.href=theURL;
		}
		else {
		alert ('Ok, no action has been taken');
		} 
	}
	function setup(theURL) {
		if (confirm('This will rename the config file, making the system unusable, are you sure?')) {
		window.location.href=theURL;
		}
		else {
		alert ('Ok, no action has been taken');
		} 
	}	
	//-->
	</script>
	
	<center><a href=<?php echo "$PHP_SELF?module=$module"; ?>&accounts_action=new><img src='images/new.gif' alt='Create New Account'> Create New Account</a><br><br></center>
	<?php
	$sqlAdminAccounts = mysql_query("SELECT * FROM $mysql_pre$mysql_admin ORDER BY `id` ASC",$db);
	
	if ($resultAdminAccount = mysql_fetch_array($sqlAdminAccounts)) {
		printf("<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>");
		printf("<tr><td class=color3><b><center>Username</center></b></td><td class=color3><b><center>Full Name</center></b></td><td class=color3><b><center>Email</center></b></td><td class=color3><b><center>Edit</center></b></td><td class=color3><b><center>Delete</center></b></td></tr><tr>");
		do {
		
			printf("<td class=color2>%s</td><td class=color2>%s</td><td class=color2><a href=mailto:%s>%s</a></td>", $resultAdminAccount["username"], $resultAdminAccount["name"], $resultAdminAccount["email"], $resultAdminAccount["email"]);
			printf("<td class=color2><center><A HREF=$PHP_SELF?module=$module&accounts_action=edit&account_id=%s><img src=images/edit.gif border=0 alt=Edit></a></center></td><td class=color2><center><A HREF=javascript:delete_user('$PHP_SELF?module=$module&header_footer=no&accounts_action=delete&account_id=%s')><img src=images/delete.gif border=0 alt=Delete></a></center></td>", $resultAdminAccount["id"], $resultAdminAccount["id"]);
			printf("</tr>");
		
		} while ($resultAdminAccount = mysql_fetch_array($sqlAdminAccounts));
		printf("</td></tr></table>");
	}
	if ($user_id==1) {
		echo "<center><br><br><a href=javascript:setup('$PHP_SELF?module=setup&act=edit_config&header_footer=no')>Script Setup</a></center>";
	}
}
?>