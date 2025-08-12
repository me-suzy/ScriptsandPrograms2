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

// Perform the action?
$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$NumDays = isset($_GET['NumDays']) ? $_GET['NumDays'] : 0;
if ($Action == 'PurgeAudit' AND $Mode == 'delete')
{
	// Delete the audit records
	$ok1 = mysql_query("DELETE FROM news_audit WHERE TO_DAYS(NOW()) - TO_DAYS(EventDateTime) >= $NumDays");
	if ($ok1)
	{
		$successmsg = "All audit records older than $NumDays days have been successfully purged.";
		DisplaySuccess($successmsg, 0);
	}
	else
	{
		$errormsg = 'There was an error purging your audit records.';
		DisplayError($errormsg, 1);
		exit();
	}
}
elseif (isset($_POST['submit']) OR (isset($_POST['Threshold'])))
{
	// Age in days of the news posts you want to delete (or delete).  At least x days old
	$NumDays = intval($_POST['Threshold']);

	// Was the input box left blank?
	if ($NumDays == '0')
	{
		$errormsg = 'This field must contain numeric data.  Go back and try again.';
		DisplayError($errormsg, 0);
	}
	else
	{
		// Do the query
		$result = mysql_query("SELECT count(*) AS NumRecs FROM news_audit WHERE TO_DAYS(NOW()) - TO_DAYS(EventDateTime) >= $NumDays");
		$resultset = mysql_fetch_array($result); 
		$NumRecords = $resultset['NumRecs'];

		// No records were found within the range given
		if ($NumRecords == 0)
		{
			$errormsg = "Sorry, but there are no audit records within that range.";
			DisplayError($errormsg, 0);
		}
		else
		{
			// Show the form to the user
			DisplayGroupHeading('Confirm Audit Record Purge');
			?>
			<TABLE class="Admin">
				<TR>
					<TD width="80">
						<DIV align="center"><IMG src="Inc/Images/Question.gif" /></DIV>
					</TD>
					<TD class="WarningText">
						You are about to permanently remove <?= $NumRecords ?> audit records.  Are you SURE you wish to do this?
						<BR>
						<BR>
						<DIV align="center"><A href="<?=$AdminScript?>?action=PurgeAudit&mode=delete&NumDays=<?php echo $NumDays; ?>">Yes</A> | 
						<A href="<?=$AdminScript?>?action=PurgeAudit">No</A></DIV>
					</TD>
				</TR>
			</TABLE>
			<?php
		}
	}
}
// Display the form for entry
else
{
	// Display the heading
	DisplayGroupHeading('Purge Old Audit Records');
	?>
	<TABLE class="Admin">
		<TR>
			<TD align="center">
				<FORM action="<?=$AdminScript?>?action=PurgeAudit" method="post">Purge audit records that are <INPUT type="text" name="Threshold" value="<?=$DefaultAuditPurgeDays?>" size="3" /> days old and older.
					<INPUT class="but" type="submit" name="submit" value="Purge">
				</FORM>
			</TD>
		</TR>
		<TR>
			<TD>
				Entering in a '0' or non-numerical data will cause ALL audit records to be removed.<BR>
				You will have the chance to confirm your request.
			</TD>
		</TR>
	</TABLE>
	<?php
}
?>
