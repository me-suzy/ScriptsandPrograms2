<?

// ==============================================================================================================================

function ShowCommentsForm($ArticleID)
{
	global $NewsDir, $NewsDisplay_DateFormat, $NewsDisplay_TimeFormat, $AllowDuplicateComments, $CommentsRequireVerification;

	// Obtain the remote IP
	$ip = GetRemoteIP();

	// Load the template
	$TemplateID = GetArticleTemplateID($ArticleID);
	$TemplateContents = ReadTemplate($TemplateID, "T");

	// List all the comments for this article
	$Query = "SELECT * FROM news_comments WHERE ArticleID = '$ArticleID' AND VerificationCode = 'OK' AND Approved = '1' ORDER BY CommentDateTime";
	$ResultSet = mysql_query($Query) or die("Query failed : " . mysql_error());
	?>
	<B>News Article: </B> <?= GetHeadline($ArticleID) ?>
	<HR>
	<TABLE width="100%">
		<?
		while ($row = mysql_fetch_array($ResultSet))
		{
			$Contents = ParseCommentsTemplateCodes($TemplateContents, $row);
			echo $Contents;
		}
		?>
	</TABLE>
	<?
	// See if comments are allowed to be posted for this article
	// See if this IP Address has already commented upon this article
	$sql = "SELECT AllowComments FROM news_posts WHERE ID = '$ArticleID'";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$AllowComments = $row['AllowComments'];	

	// See if this IP Address has already commented upon this article
	$sql = "SELECT * FROM news_comments WHERE ArticleID = '$ArticleID' AND IPAddress = '$ip'";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if (($num_rows != 0) && (! $AllowDuplicateComments) && ($AllowComments))
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		// Convert the date and time to the user-specified format
		$CommentDate = date($NewsDisplay_DateFormat, strtotime($row['CommentDateTime']));
		$CommentTime = date($NewsDisplay_TimeFormat, strtotime($row['CommentDateTime']));
		$Comment = $row['Comment'];
		?>
		<table width="100%" align="center">
			<tr>
				<td>
					<HR>
					You may not record any comments as comments have already been recorded from your IP address (<?=$ip?>) for this article. Your comments were logged on <?=$CommentDate?> at <?=$CommentTime?>.<BR><HR>
					<center>
						<input class="but" type="button" name="Close" value="Close" onClick="javascript:window.close()">
					</center>
				</td>
			</tr>
		</table>
		<?php
	}
	
	if ($AllowComments)
	{
	?>
		<HR>
		<TABLE width="100%" align="center">
			<TR>
				<TD>
					<FORM name="comment" method="post" action="<?= $NewsDir . '/Comments.php?ArticleID=' . $ArticleID ?>">
						<TABLE>
							<TR>
								<TD>
									Name
								</TD>
								<TD>
									<INPUT type="text" name="Name" value="" size="50" maxlength="50">
								</TD>
							</TR>

							<TR>
								<TD>
									Email
								</TD>
								<TD>
									<INPUT type="text" name="EmailAddress" value="" size="50" maxlength="50">
								</TD>
							</TR>
							<TR>
								<TD>
									Comments
								</TD>
								<TD>
									<TEXTAREA name="Comment" cols="50" rows="6"></TEXTAREA>
								</TD>
							</TR>
							<?
							if ($CommentsRequireVerification == 1)
							{
								?>
								<TR>
									<TD>&nbsp;
										
									</TD>
									<TD>
										<B>An email will be sent to the address you have entered, and you must click on the link contained within the email before your comments will appear.</B>
									</TD>
								</TR>
								<?
								}
							?>
							<TR>
								<TD>&nbsp;
									
								</TD>
								<TD>
									<input class="but" type="submit" name="submit" value="Submit">
								</TD>
							</TR>
							<TR>
								<TD colspan="2">
									<BR><HR>Please note: Your IP address (<?= GetRemoteIP() ?>) will be logged with your comments for security purposes, but will never be displayed. Your email address will also never be displayed.
								</TD>
							</TR>
						</TABLE>
					</FORM>
				</td>
			</tr>
		</table>
	<?php
	}
	else
	{
		?>
		<BR>
		<center>
			<input class="but" type="button" name="Close" value="Close" onClick="javascript:window.close()">
		</center>
		<?php
	}	
}

// ==============================================================================================================================

function RecordComments($ArticleID)
{
	global $NewsDir, $AllowDuplicateComments, $CommentsRequireApproval, $CommentsRequireVerification, $SiteDescription, $NoReplyEmail, $WWW;

	// Obtain the remote IP
	$ip = GetRemoteIP();

	// See if this IP Address has already recorded a comment for this article
	$query = "SELECT * FROM news_comments WHERE ArticleID = '$ArticleID' AND IPAddress = '$ip'";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if (($num_rows != 0) && (! $AllowDuplicateComments))
		die ("Illegal attempt to record a new comments!");

	// Obtain the comment
	$CommentDateTime = CurrentFormattedDateTime();
	$Name = strip_tags($_POST['Name']);
	$EmailAddress = strip_tags($_POST['EmailAddress']);
	$Comment = strip_tags($_POST['Comment']);

	// Only proceed if there's anything to report
	if ($Comment != "")
	{
		if ($Name == "")
			$Name = "Anonymous";

		// Do comments require approval?
		if ($CommentsRequireApproval == 1)
			$Approved = 0;
		else
			$Approved = 1;

		// If required, send the verification email
		if ($CommentsRequireVerification == 1)
		{
			$VerificationCode = gen_rand_string(false, 30);

			$Subject = "$SiteDescription - Verification of Comments";
			$Mailheader = "From: \"$SiteDescription\" <$NoReplyEmail>\n";

			$Message = "You have posted comments at the $SiteDescription website regarding a news article.\n\n";
			$Message .= "You must verify your email address before the comments will be displayed. This is to prevent abusive, anonymous postings.\n\n";
			$Message .= "To verify your address please click <A href=\"" . $WWW . "/Comments.php?ArticleID=$ArticleID&VC=$VerificationCode\">here</A>.\n\n";
			$Message .= "Do NOT reply to this email, it will not be processed.\n";		

			// Send the email
			mail($EmailAddress, $Subject, $Message, $Mailheader);
		}
		else
			$VerificationCode = "OK";

		// Record the comments
		mysql_query("INSERT INTO news_comments (ArticleID, IPAddress, Name, EmailAddress, CommentDateTime, VerificationCode, Approved, Comment) VALUES ('$ArticleID', '$ip', '$Name', '$EmailAddress', '$CommentDateTime', '$VerificationCode', '$Approved', '$Comment')");
	}
	?>
	<table width="100%" align="center">
		<tr>
			<td>
				<B>News Article: </B> <?= GetHeadline($ArticleID) ?><HR>
				Thank you for recording your comments.
				<?= (($CommentsRequireVerification) ? "You must follow the instructions contained in your email before your comments will be shown." : "" )?>
				<BR>				
				<?= (($CommentsRequireApproval == 1) ? "Your comments will appear once approved by the moderator." : "Your comments will be shown immediately." )?>
				<BR><HR>
				<center>
					<input class="but" type="button" name="Close" value="Close" onClick="javascript:window.close()">
				</center>
			</td>
		</tr>
	</table>
	<?php
}

// ==============================================================================================================================

function VerifyComments($ArticleID, $VC)
{
	global $SiteDescription, $SiteDomain, $CommentsRequireApproval;

	// See if this verification code is correct
	$query = "SELECT * FROM news_comments WHERE VerificationCode= '$VC' AND ArticleID = '$ArticleID'";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if ($num_rows == 0)
		$msg ="Sorry, your verification code was incorrect. Please check your email.";
	else
	{
	 	mysql_query("UPDATE news_comments SET VerificationCode = 'OK' WHERE VerificationCode= '$VC' AND ArticleID = '$ArticleID'");
		$msg ="Thank you for verifying your email address.<BR>";		
		$msg .= (($CommentsRequireApproval) ? "<BR>Your comments will appear once approved." : "Your comments will be displayed immediately.");
	}
	?>
	<table width="100%" align="center">
		<tr>
			<td>
				<B><?= $SiteDescription ?> - News Article: </B> <?= GetHeadline($ArticleID) ?><HR>
				<?= $msg ?>
				<BR><HR>
				<center>
					Click <A href="<?=$SiteDomain?>">here</A> to visit the <?= $SiteDescription ?> Website.
				</center>
			</td>
		</tr>
	</table>
	<?php
}

// ==============================================================================================================================

// Function: Replace template codes with actual values
function ParseCommentsTemplateCodes($TemplateContents, $row)
{
	global $NewsDisplay_DateFormat, $NewsDisplay_TimeFormat;

	// Convert the date and time to the user-specified format
	$CommentDate = date($NewsDisplay_DateFormat, strtotime($row['CommentDateTime']));
	$CommentTime = date($NewsDisplay_TimeFormat, strtotime($row['CommentDateTime']));
	
	$Contents = $TemplateContents;

	// Now parse the special tags
	$Contents = str_replace('{commentdate}', $CommentDate, $Contents);
	$Contents = str_replace('{commenttime}', $CommentTime, $Contents);
	$Contents = str_replace('{name}', $row['Name'], $Contents);
	$Contents = str_replace('{email}', $row['EmailAddress'], $Contents);
	$Contents = str_replace('{ip}', $row['IPAddress'], $Contents);
	$Contents = str_replace('{comment}', $row['Comment'], $Contents);
	return $Contents;
}
?>