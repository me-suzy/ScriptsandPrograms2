<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

function DisplayData($pw1, $pw2)
{
	global $ErrorText, $AdminScript;

	DisplayGroupHeading('Change Password');
	?>
	<FORM action="<?= $AdminScript?>?action=Password" method="post">
		<TABLE class="Admin">
			<?php
			if ($ErrorText != "")
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
				<TD rowspan="2" class="C" width="20%">
					<IMG src="Inc/Images/Users.gif">
				</TD>
				<TD class="FieldPrompt">
					Password:
				</TD>
				<TD align="left">
					<INPUT type="password" name="Password" value="<?=$pw1?>" size="20" maxlength="30" />
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Retype Password:
				</TD>
				<TD align="left">
					<INPUT type="password" name="Password2" value="<?=$pw2?>" size="20" maxlength="30" />
				</TD>
			</TR>

			<TR>
				<TD colspan="3" class="C">
					<BR>
					<INPUT class="but" type="reset" name="submit" value="Reset">
					<INPUT class="but" type="submit" name="submit" value="Save Changes">
				</TD>
			</TR>
		</TABLE>
	</FORM>
	<?php
}

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : "";
$UserID = isset($_GET['id']) ? $_GET['id'] : "";
$ReturnText = ' Click <A href="' . $AdminScript . '">here</A> to return.';

// Updated details have been submitted?
if (isset($_POST['submit']))
{
	// Verify that all fields have been completed
	if (($_POST['Password'] == "") or ($_POST['Password2'] == ""))
	{
		// Display the form again with the data already entered
		$ErrorText = 'Both password fields must be completed.  Please try again.';
		DisplayData('', '');
	}
	elseif ($_POST['Password'] != $_POST['Password2'])
	{
		$ErrorText = 'The password fields do not match.  Please try again.';
		DisplayData('', '');
	}
    else
   	{
		$sql = "UPDATE news_users SET Password=MD5('$_POST[Password]'), MustChangePassword='0' WHERE ID='$LoggedInUserId'";

		if (mysql_query($sql))
		{
			if ($EnableAudit == 1)
				WriteAuditEvent(AUDIT_TYPE_PASSWORD, 'C', $LoggedInUserId, "User's password changed");

			$successmsg = 'Your password has been changed successfully.' . $ReturnText;
			DisplaySuccess($successmsg, 0);

			// Also update the session variables to prevent authentication errors from the AccessControl.php script
			$_SESSION['LoginPassword'] = $_POST['Password'];
		}	
		else
		{	
			$errormsg = 'There was a problem updating your password.' . $ReturnText;
			DisplayError($errormsg, 1);
		}
	}
}
else
{
	// Get the user information from the database 
	$user=mysql_query("SELECT * FROM news_users WHERE ID=$LoggedInUserId");
	if (!$user)
	{
		$errormsg = 'There was an error fetching your details from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$user = mysql_fetch_array($user);
	$Password = $user['Password'];

	// Display the user account information in the form for editing
	DisplayData('', '');
}
?>
