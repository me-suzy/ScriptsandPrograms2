<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

CheckAuthority();

function DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword)
{
	global $ErrorText, $AdminScript;

	// If this is the original admin user then ensure that the access-level cannot be changed
	$ShowOnlyAdmin = false;
	if ($_SESSION['EditUserID'] == '1')
		$ShowOnlyAdmin = true;	

	DisplayGroupHeading( ($_SESSION['EditUserID'] != -1) ? 'Modify User Account' : 'Create User Account');

	?>
	<TABLE class="Admin">
		<FORM action="<?=$AdminScript?>?action=Users" method="post">
			<?php
			if ($ErrorText != '')
			{
				?>
				<TR>
					<TD colspan="3" class="ErrorText">
						<?= $ErrorText ?>
					</TD>
				</TR>
				<?php
			}
			?>

			<TR>
				<TD rowspan="10" align="center" width="10%">
					<IMG src="Inc/Images/Users.gif" alt="Users">
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt" width="30%">
					Login:
				</TD>
				<TD align="left">
					<INPUT type="text" name="Username" value="<?=$Username?>" size="20" maxlength="30">
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Full Name:
				</TD>
				<TD align="left">
					<INPUT type="text" name="FullName" value="<?=$FullName?>" size="20" maxlength="255" />
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Password:
				</TD>
				<TD align="left">
					<INPUT type="password" name="Password" value="" size="20" maxlength="30" />
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Retype Password:
				</TD>
				<TD align="left">
					<INPUT type="password" name="Password2" value="" size="20" maxlength="30" />
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Access Level:
				</TD>
				<TD align="left">
					<?= BuildAccessLevelDropdown('AccessLevel', $AccessLevel, false, $ShowOnlyAdmin) ?>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Edit any user's post?
				</TD>
				<TD align="left">
					<INPUT type="checkbox" name="EditAnyPost" value="1" <?= ($EditAnyPost == '1' ? 'checked' : '') ?>>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Can approve posts?
				</TD>
				<TD align="left">
					<INPUT type="checkbox" name="CanApprovePosts" value="1" <?= ($CanApprovePosts == '1' ? 'checked' : '') ?>>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Can Lock/Unlock posts?
				</TD>
				<TD align="left">
					<INPUT type="checkbox" name="CanChangeLock" value="1" <?= ($CanChangeLock == '1' ? 'checked' : '') ?>>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Must change password?
				</TD>
				<TD align="left">
					<INPUT type="checkbox" name="MustChangePassword" value="1" <?= ($MustChangePassword == '1' ? 'checked' : '') ?>>
				</TD>
			</TR>

			<TR>
				<TD colspan="3">
					<HR width="100%" size="2">
				</TD>
			</TR>

			<TR>
				<TD colspan="3" class="C">
					<INPUT class="but" type="reset" name="submit" value="Reset">
					<INPUT class="but" type="submit" name="submit" value="Save Changes">
				</TD>
			</TR>
		</FORM>
	</TABLE>
	<?php
}

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : '';
$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=Users">here</A> to return to user maintenance';

if ($Action == 'Users' AND $Mode == 'delete' AND $Confirm == 'yes')
{
	// Get the ID from the session, not the request, for security
	$UserID = $_SESSION['DeleteUserID'];

	if ($UserID == 1)
	{
		$errormsg = 'Illegal attempt to delete the default admin user!';
		DisplayError($errormsg, 0);
		exit;
	}

	// Get the user name
	$sql = "SELECT Username FROM news_users WHERE ID = $UserID";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$Username = $row['Username'];
	
	// Delete the user
	$result = mysql_query("DELETE FROM news_users WHERE ID=$UserID");
	if ($result)
	{
		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_USER, 'D', $UserID, "User deleted: " . $Username);

		// Change all posts to the default user
		$result = mysql_query("UPDATE news_posts SET AuthorID = 1 WHERE AuthorID = $UserID");
		if ($result)
		{
			$_SESSION['Info'] = 'The user has been deleted successfully. Associated articles now below to the default Administrator';
			header('location:' . $AdminScript . '?action=Users');
			exit;
		}
		else
		{
			$_SESSION['Info'] = 'The user has been deleted successfully, but there was an error when detaching articles.';
			header('location:' . $AdminScript . '?action=Users');
			exit;
		}
	}
	else
	{
		$errormsg = 'There was an error removing the user from the database.' . $ReturnText;
		DisplayError($errormsg, 1);
	}
}

// Request to delete a user?
elseif ($Action == 'Users' AND $Mode == 'delete' AND $Confirm == '')
{
	if ($GetId == 1)
	{
		$errormsg = 'Illegal attempt to delete the default admin user!' . $ReturnText;
		DisplayError($errormsg, 0);
		exit;
	}

	// Store the user ID in the session (rather than a hidden form field, for security)
	$_SESSION['DeleteUserID'] = $GetId;

	// Request confirmation
	$users = mysql_query("SELECT FullName FROM news_users WHERE ID=$GetId");
	if (!$users)
	{
		$errormsg = 'Error fetching user information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}
	$user = mysql_fetch_array($users);

	DisplayGroupHeading('Remove User Account');
	?>

	<TABLE class="Admin">
		<TR>
			<TD width="80">
				<CENTER><IMG src="Inc/Images/Question.gif" align="Question"></CENTER>
			</TD>
			<TD>
				<DIV class="plaintext">Are you sure you want to remove user <I> <?= $user['FullName'] ?></I> from the news system?</DIV>
				<BR>
				<BR>
		  		<CENTER>
		  			<A href="<?=$AdminScript?>?action=Users&amp;mode=delete&amp;confirm=yes">Yes</A> |
		  			<A href="<?=$AdminScript?>?action=Users">No</A>
		  		</CENTER>
			</TD>
		</TR>
	</TABLE>

	<?php
}

// Request to edit a user
elseif ($Action == 'Users' AND $Mode == 'edit')
{
	// Get user information from the database that matches the ID variable
	$users=mysql_query("SELECT * FROM news_users WHERE ID=$GetId");
	if (!$users)
	{
		$errormsg = 'Error fetching users information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$user = mysql_fetch_array($users);
	$Username = $user['Username'];
	$FullName = $user['FullName'];
	$AccessLevel = $user['AccessLevel'];
	$EditAnyPost = $user['EditAnyPost'];
	$CanApprovePosts = $user['CanApprovePosts'];
	$CanChangeLock = $user['CanChangeLock'];
	$MustChangePassword = $user['MustChangePassword'];
		
	// Store the user ID in the session (rather than a hidden form field, for security)
	$_SESSION['EditUserID'] = $GetId;

	DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword, false);
}

elseif ($Action == 'Users' AND $Mode == 'create')
{
	$_SESSION['EditUserID'] = -1;
	DisplayData('', '', '', 0, 0, 0, 1, false);
}

elseif (isset($_POST['submit']))
{
	// Get the ID from the session, not the request, for security
	$UserID = $_SESSION['EditUserID'];
	$Username = $_POST['Username'];
	$AccessLevel = $_POST['AccessLevel'];
	$FullName = $_POST['FullName'] ;
	$Password = $_POST['Password'] ;
	$Password2 = $_POST['Password2'] ;
	$EditAnyPost =  (isset($_POST['EditAnyPost']) ? '1' : '0');
	$CanApprovePosts = (isset($_POST['CanApprovePosts']) ? '1' : '0');
	$CanChangeLock = (isset($_POST['CanChangeLock']) ? '1' : '0');
	$MustChangePassword = (isset($_POST['MustChangePassword']) ? '1' : '0');

	// Verify that all fields have been completed
	if ($Username == '')
	{
		$ErrorText = 'Username must be specified.';
		DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword);
	}
	elseif ($FullName == '')
	{
		$ErrorText = "User's Full Name must be specified.";
		DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword);
	}
	elseif ($AccessLevel == "")
	{
		$ErrorText = "User's Access Level must be selected.";
		DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword);
	}
	elseif ($Password != $Password2)
	{
		$ErrorText = 'The password fields do not match.  Please re-type or leave blank.';
		DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword);
	}
	elseif (! UsernameIsUnique($Username, $UserID))
	{
		$ErrorText = 'Sorry, but Username "' . $Username . '" is already in use.';
		DisplayData($Username, $FullName, $AccessLevel, $EditAnyPost, $CanApprovePosts, $CanChangeLock, $MustChangePassword);
	}
    else
    {
		// If this is the default admin user, ensure that it remains an Admin!
		if ($UserID == 1)
			$AccessLevel = 2;

		// Update/insert
		if ($UserID <> -1)
			$sql = "UPDATE news_users SET Username='$Username',
			AccessLevel='$AccessLevel', FullName='$FullName', EditAnyPost=$EditAnyPost, CanApprovePosts=$CanApprovePosts, CanChangeLock=$CanChangeLock, MustChangePassword=$MustChangePassword
			WHERE ID='$UserID'";
		else
			$sql = "INSERT INTO news_users SET Username='$Username',
			AccessLevel='$AccessLevel', FullName='$FullName', EditAnyPost=$EditAnyPost, CanApprovePosts=$CanApprovePosts, CanChangeLock=$CanChangeLock, MustChangePassword=$MustChangePassword";

		if (mysql_query($sql))
		{

			if ($UserID == -1)
				$UserID = mysql_insert_id();

			// Write audit, if required
			if ($EnableAudit == 1)
			{
				if ($UserID <> -1)
					WriteAuditEvent(AUDIT_TYPE_USER, 'C', $UserID, "User updated: ". $Username);
				else
					WriteAuditEvent(AUDIT_TYPE_USER, 'A', $UserID, "User created: ". $Username);
			}

			$successmsg = "The user's details have been updated successfully." . $ReturnText;
			DisplaySuccess($successmsg, 0);

			// Also update the password?
			if ($Password != '')
			{
				mysql_query("UPDATE news_users SET Password=MD5('$Password') WHERE ID='$UserID'");

				if ($EnableAudit == 1)
					WriteAuditEvent(AUDIT_TYPE_PASSWORD, 'C', $UserID, "User's password changed:" . $Username);
			}

			// If we have just updated the logged-in user's details the also update the session variables to prevent 
			// authentication errors from the AccessControl.php script
			if ($UserID == $LoggedInUserId)
			{
				$_SESSION['LoginUsername'] = $_POST['Username'];

				if ($_POST['Password'] != "")
					$_SESSION['LoginPassword'] = $_POST['Password'];
			}				

			$_SESSION['Info'] = 'The user account details have been updated successfully.';
			header('location:' . $AdminScript . '?action=Users');
			exit;
		}	
		else
		{	
			$errormsg = "There was a problem updating the user's details." . $ReturnText;
			DisplayError($errormsg, 1);
		}
	}
}

elseif ($Action == 'Users')
{
	// Display the user accounts selection page
	DisplayGroupHeading('User Accounts');
	?>
	<TABLE class="Admin">
 		<TR>
 			<TD width="100">
				<DIV align="center">
					<A href="<?=$AdminScript?>?action=Users&amp;mode=create"><IMG src="Inc/Images/Users.gif" align="middle" border="0" alt="Create">
					<BR>Create User Account</A>
				</DIV>
			</TD>
 			<TD class="plaintext">
 				You can define many users, but you should restrict the number of Administrators.<BR>
				To assist with security, Administrators are listed first.
 			</TD>
 		</TR>
 	</TABLE>
	<BR>

	<?php
	// Display the heading
	DisplayGroupHeading('User Maintenance');
	DisplayInfoMessage();
	?>
	<TABLE class="Admin">
		<TR>
			<TD class="plaintext">
				You cannot remove your own account, nor the initial Admin account.
 				<BR>
				<BR>
				<TABLE border="0">
	
				<?php
				$users = mysql_query("SELECT * FROM news_users ORDER BY AccessLevel DESC, Username ASC");
				if (!$users)
				{
					$errormsg = 'Error retrieving user list from database.';
					DisplayError($errormsg, 1);
				}

				// Display current users in the system
				while ($user = mysql_fetch_array($users))
				{
					$id = $user['ID'];
					$Username = $user['Username'];
					$AccessLevel = $user['AccessLevel'];
					$FullName = $user['FullName'];
					if ($AccessLevel == '2')
						$userimage = 'AdminUser.gif';
					elseif ($AccessLevel == '1')
						$userimage = 'NormalUser.gif';
					else
						$userimage = 'DisabledUser.gif';

					?>
					<TR>
						<TD class="plaintext">
	                    	<A href="<?=$AdminScript?>?action=Users&amp;mode=edit&amp;id=<?=$id?>"><IMG src="Inc/Images/EditUser.gif" border="0" align="middle" alt="Edit"></A>
						</TD>
						<TD class="plaintext">
							<?php
							if (($id != $LoggedInUserId) && ($id != 1 ))			// Cannot delete default Admin user or self
							{
								?>
								<A href="<?=$AdminScript?>?action=Users&amp;mode=delete&amp;id=<?=$id?>"><IMG src="Inc/Images/RemoveUser.gif" border="0" align="middle" alt="Delete"></A>
								<?php
							}
							else
							{
								?>
								<IMG src="Inc/Images/RemoveUserDisabled.gif" border="0" align="middle" alt="Cannot Delete">
								<?php
							}
							?>
						</TD>
						<TD class="plaintext">
							<?=$Username?>
						</TD>
						<TD class="plaintext">
							<?=$FullName?>
						</TD>
						<TD>
							<IMG src="Inc/Images/<?=$userimage?>" align="middle" alt="Access Level">&nbsp;&nbsp;
						</TD>
					</TR>
					<?php					
				}
				?>
				</TABLE>
			</TD>
		</TR>
	</TABLE>
	<?php
}
?>