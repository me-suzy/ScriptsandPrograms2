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

function DisplayData($ID, $UserCode, $ReplacementText)
{
	global $ErrorText, $AdminScript, $AdminTextareaColumns;

	// De-sanitise the input
    $ReplacementText = stripslashes($ReplacementText);

	DisplayGroupHeading( ($ID != -1 ? 'Modify' : 'Create' ) . ' User-Defined Code');
	?>
	<TABLE class="Admin">
		<FORM name="UDCMaint" action="<?=$AdminScript?>?action=UserCodes" method="post">
	   		<INPUT type="hidden" name="id" value="<?= $ID ?>">

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
				<TD class="FieldPrompt">
					Code:
				</TD>
				<TD align="left">
					<INPUT type="text" name="UserCode" value="<?= $UserCode ?>" size="30" maxlength="30" />
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Replacement<BR>Text:
				</TD>
				<TD align="left">
					<TEXTAREA name="ReplacementText" cols="<?=$AdminTextareaColumns?>" rows="15"><?=$ReplacementText?></TEXTAREA>
				</TD>
			</TR>
					
  			<TR>
  				<TD colspan="3">
  					<HR width="100%" size="2">
				</TD>
			</TR>
			<TR>
				<TD colspan="3" class="C">
					<INPUT class="but" type="reset" name="submit" value="Reset" />
					<INPUT class="but" type="submit" name="submit" value="Save Changes" />
				</TD>
			</TR>
		</FORM>
	</TABLE>
	<SCRIPT language="javascript" type="text/javascript">
		UDCMaint.UserCode.focus();
	</SCRIPT>

	<?php
}

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : '';
$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=UserCodes">here</A> to return to user-code maintenance';

if ($Action == 'UserCodes' AND $Mode == 'delete' AND $Confirm == 'yes')
{
	// Get the code
	$sql = "SELECT UserCode FROM news_usercodes WHERE ID = $GetId";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$UserCode = $row['UserCode'];

	// Delete the code
	$ok1 = mysql_query("DELETE FROM news_usercodes WHERE ID=$GetId");
	if ($ok1)
	{
		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_USERDEFCODE, 'D', $GetId, "User-def code deleted: " . $UserCode);

		$_SESSION['Info'] = 'The user-defined code has been removed from the database.';
		header('location:' . $AdminScript . '?action=UserCodes');
		exit;
	}
	else
	{
		$errormsg = 'There was an error removing the user-defined from the database.' . $ReturnText;
		DisplayError($errormsg, 1);
	}
	echo "<br><br>";
}

elseif ($Action == 'UserCodes' AND $Mode == 'delete' AND $Confirm == '')
{
	// Request confirmation
	$rows = mysql_query("SELECT UserCode, ReplacementText FROM news_usercodes WHERE ID=$GetId");
	if (!$rows)
	{
		$errormsg = 'Error fetching user-defined code information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}
	$row = mysql_fetch_array($rows);

	DisplayGroupHeading('Remove User-Defined Code');
	?>
	<TABLE class="Admin">
		<TR>
			<TD width="80">
				<CENTER><IMG src="Inc/Images/Question.gif" alt="Question"></CENTER>
			</TD>
			<TD>
				<DIV class="plaintext">
					Are you sure you want to remove user-defined code<I> <?= $row['UserCode'] ?></I> from the news system?
				</DIV>
				<BR>
				<BR>
		  		<CENTER>
		  			<A href="<?=$AdminScript?>?action=UserCodes&amp;mode=delete&amp;confirm=yes&amp;id=<?=$GetId?>">Yes</A> |
		  			<A href="<?=$AdminScript?>?action=UserCodes">No</A>
		  		</CENTER>
			</TD>
		</TR>
	</TABLE>

	<?php
}

elseif ($Action == 'UserCodes' AND $Mode == 'edit')
{
	// Get user-defined code information from the database that matches the ID variable
	$rows=mysql_query("SELECT * FROM news_usercodes WHERE ID=$GetId");
	if (!$rows)
	{
		$errormsg = 'Error fetching user-defined code information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$row = mysql_fetch_array($rows);
	$UserCode = $row['UserCode'];
	$ReplacementText = $row['ReplacementText'];

	// Display the information in the form for editing
	DisplayData($GetId, $UserCode, $ReplacementText);
}

elseif ($Action == 'UserCodes' AND $Mode == 'copy')
{
	// Get user-defined code information from the database that matches the ID variable
	$rows=mysql_query("SELECT * FROM news_usercodes WHERE ID=$GetId");
	if (!$rows)
	{
		$errormsg = 'Error fetching user-defined code information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$row = mysql_fetch_array($rows);
	$UserCode = $row['UserCode'];
	$ReplacementText = $row['ReplacementText'];

	// Display the information in the form for editing
	DisplayData(-1, $UserCode, $ReplacementText);
}
elseif ($Action == 'UserCodes' AND $Mode == 'create')
{
	DisplayData(-1, '', '');
}

elseif (isset($_POST['submit']))
{
	$ID = $_POST['id'];
	$UserCode = $_POST['UserCode'];
	$ReplacementText = $_POST['ReplacementText'];

	// Verify that all fields have been completed
	if (($UserCode == '') OR ($ReplacementText == ''))
	{
		$ErrorText = 'You must enter a code and replacement text.';
		DisplayData($ID, $UserCode, $ReplacementText);
	}
	else
	{
		// Update/insert
		if ($ID <> -1)
			$sql = "UPDATE news_usercodes SET UserCode='$UserCode', ReplacementText='$ReplacementText' WHERE ID='$ID'";
		else
			$sql = "INSERT INTO news_usercodes SET UserCode='$UserCode', ReplacementText='$ReplacementText'";

		if (mysql_query($sql))
		{
			// Write audit, if required
			if ($EnableAudit == 1)
			{
				if ($ID <> -1)
					WriteAuditEvent(AUDIT_TYPE_USERDEFCODE, 'C', $ID, "User-def code updated: ". $UserCode);
				else
					WriteAuditEvent(AUDIT_TYPE_USERDEFCODE, 'A', mysql_insert_id(), "User-def code created: ". $UserCode);
			}

			$_SESSION['Info'] = 'The user-defined code details have been updated successfully.';
			header('location:' . $AdminScript . '?action=UserCodes');
			exit;
		}	
		else
		{	
			$errormsg = 'There was a problem updating the user-defined code details.' . $ReturnText;
			DisplayError($errormsg, 1);
		}
	}
}

elseif ($Action == 'UserCodes')
{
	// Display the category admin section
	DisplayGroupHeading('User-Defined Codes');
	?>
	<TABLE class="Admin">
 		<TR>
 			<TD width="100">
				<DIV align="center">
					<A href="<?=$AdminScript?>?action=UserCodes&amp;mode=create">
						<IMG src="Inc/Images/CreateUserDefinedCode.gif" align="middle" border="0" alt="Create">
						<BR>Create Code
					</A>
				</DIV>
			</TD>
 			<TD width="450">
 				<DIV class="plaintext">User-Defined Codes can be used to construct substitution tags within your articles. For example, if all your articles contain the words "Please visit our website" then you could setup a substitution of {visit}. HTML code is supported.</DIV>
 			</TD>
 		</TR>
 	</TABLE>
	<BR>

	<?php
	DisplayGroupHeading('User-Defined Codes Maintenance');
	DisplayInfoMessage();
	?>
	<TABLE class="Admin">
		<TR>
			<TD>
				<BR>
				<TABLE border="0">

				<?php
				$rows = mysql_query("SELECT ID, UserCode FROM news_usercodes ORDER BY UserCode ASC");
				if (!$rows)
				{
					$errormsg = 'Error retrieving user-defined codes from database.';
					DisplayError($errormsg, 1);
				}

				// Display current codes in the system
				while ($row = mysql_fetch_array($rows))
				{
					$id = $row['ID'];
					$UserCode = $row['UserCode'];
					?>
					<TR>
						<TD class="plaintext">
							<a href="<?=$AdminScript?>?action=UserCodes&amp;mode=edit&amp;id=<?=$id?>"><IMG src="Inc/Images/EditUserDefinedCode.gif" border="0" align="middle" alt="Edit"></a>
							<a href="<?=$AdminScript?>?action=UserCodes&amp;mode=copy&amp;id=<?=$id?>"><IMG src="Inc/Images/CopyUserDefinedCode.gif" border="0" align="middle" alt="Copy"></a>
							<a href="<?=$AdminScript?>?action=UserCodes&amp;mode=delete&amp;id=<?=$id?>"><IMG src="Inc/Images/RemoveUserDefinedCode.gif" border="0" align="middle" alt="Delete"></a>
						</TD>
						<TD class="plaintext">
							<?=$UserCode ?>
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