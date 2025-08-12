<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

require('Inc/PreviewFunctions.php');

// ==============================================================================================================================

function CategoriesFromForm($Categories)
{
	$Cats = array();

	// Convert the form array (e.g. key, value) to our own format (e.g. value, true)
	if ($Categories != NULL)
		while (list($key, $val) = each($Categories))
			$Cats[$val] = TRUE;

	return $Cats;
}

// ==============================================================================================================================

function CategoriesToDB($ArticleID, $Categories)
{
	$CatWriteSuccess = TRUE;

	// Remove any existing categories
	$CatWriteSuccess = $CatWriteSuccess & mysql_query("DELETE FROM news_postcategories WHERE ArticleID = '$ArticleID'");

	// Write the new categories
	while (list($key, $val) = each($Categories))
		$CatWriteSuccess = $CatWriteSuccess & mysql_query("INSERT INTO news_postcategories (ArticleID, CatID) VALUES('$ArticleID', '$key')");

	return $CatWriteSuccess;
}

// ==============================================================================================================================

function CheckArticleSecurity($NewsData, $CheckLock)
{
	global $LoggedInEditAnyPost, $LoggedInUserId;

	// Illegal attempt to edit another user's post?
	if ((! $LoggedInEditAnyPost) and ($NewsData['AuthorID'] != $LoggedInUserId))
	{
		$errormsg = "Illegal attempt to edit another user's post!";
		DisplayError($errormsg, 0);
		exit;
	}

	// Illegal attempt to edit a locked post?
	if ($CheckLock && $NewsData['Locked'] == '1')
	{
		$errormsg = "Illegal attempt to edit a locked post!";
		DisplayError($errormsg, 0);
		exit;
	}
}

// ==============================================================================================================================

function DisplayData($NewsID, $NewsDateTime, $OriginalPostDateTime, $PostAuthor, $ImageID, $Categories, $TemplateID, $Headline, $ShortPost, $LongPost, $Sticky, $Priority, $Visible, $AllowComments, $Archived)
{
	global $LoggedInFullName, $NewsDir, $TemplateDir, $AllowTimeStampUpdate, $ErrorText, $NewsDisplay_DateFormat,
		$NewsDisplay_TimeFormat, $EnableSpellCheck, $EnableComments, $EnableArchive, $AdminScript, $AdminTextareaColumns, $UseTinyMCE;

	// Split the date into elements
	$DateTime = explode(' ', $NewsDateTime);

	$Date = explode('-', $DateTime[0]);
	$NewsYear = $Date['0'];
	$NewsMonth = $Date['1'];
	$NewsDay = $Date['2'];

	// Split the time into elements
	$Time = explode(':', $DateTime[1]);
	$NewsHour = $Time['0'];
	$NewsMinute = $Time['1'];

	DisplayGroupHeading(  ($NewsID != -1 ? 'Modify' : 'Create' ) . ' News Article');
	?>
	<TABLE class="Admin">
		<FORM name="NewsPostFrm" action="<?=$AdminScript?>?action=news&mode=post" method="post">
			<?php
			if ($ErrorText != '')
			{
				?>
				<TR>
					<TD colspan="2" class="ErrorText">
						<?= $ErrorText ?>
					</TD>
				</TR>
				<?php
			}
			?>

			<TR>
				<TD colspan="2" class="C">
					<INPUT class="but" type="reset" name="reset" value="Reset">
					<INPUT class="but" type="submit" name="PreviewShort" value="Preview(S)">
					<INPUT class="but" type="submit" name="PreviewLong" value="Preview(L)">
					<?php
					if ($EnableSpellCheck)
					{
						?>
						<INPUT class="but" type="submit" name="SpellShort" value="Spellcheck(S)">
						<INPUT class="but" type="submit" name="SpellLong" value="Spellcheck(L)">
						<?php
					}
					?>
					<INPUT class="but" type="submit" name="submit" value="Post">
				<TD>
			</TR>
			<TR>
				<TD class="FieldPrompt">
					<INPUT type="hidden" name="NewsID" value="<?= $NewsID ?>">
					<INPUT type="hidden" name="OriginalPostDateTime" value="<?=$OriginalPostDateTime?>"> 
					Original Posting:
				</TD>
				<TD align="left" valign="top">
					<?= date($NewsDisplay_DateFormat, strtotime($OriginalPostDateTime)) ?>&nbsp;<?= date($NewsDisplay_TimeFormat, strtotime($OriginalPostDateTime)) ?>
					by <?= $PostAuthor ?>
				</TD>
			</TR>

			<?php
			if ($AllowTimeStampUpdate == 1)
			{
				?>
				<TR>
					<TD class="FieldPrompt">
						Date & Time:
					</TD>
					<TD align="left" valign="top">
						<?php BuildNumericDropdown('PostDay', $NewsDay, 1, 31) ?>
						<?php BuildMonthDropdown('PostMonth', $NewsMonth) ?>
						<?php BuildNumericDropdown('PostYear', $NewsYear, 2004, 2020) ?>
						&nbsp;&nbsp;&nbsp;
						<?php BuildNumericDropdown('PostHour', $NewsHour, 0, 23, 2) ?>
						<?php BuildNumericDropdown('PostMinute', $NewsMinute, 0, 59, 2) ?>
					</TD>
				</TR>
				<?php
			}
			else
			{
				?>
				<INPUT type="hidden" name="PostDay" value="<?=$NewsDay?>"> 
				<INPUT type="hidden" name="PostMonth" value="<?=$NewsMonth?>"> 
				<INPUT type="hidden" name="PostYear" value="<?=$NewsYear?>"> 
				<INPUT type="hidden" name="PostHour" value="<?=$NewsHour?>"> 
				<INPUT type="hidden" name="PostMinute" value="<?=$NewsMinute?>"> 
				<?php
			}
			?>
			<TR>
				<TD class="FieldPrompt">
					Image:
				</TD>
				<TD align="left" valign="top">
					<?php BuildImageDropdown('ImageID', $ImageID) ?>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Categories:
				</TD>
				<TD align="left" valign="top">
					<?php
					// List all the categories, pre-selecting as appropriate
					$query = "SELECT * FROM news_categories ORDER BY CatDesc ASC";
					$result = mysql_query($query) or die('Query failed : ' . mysql_error());
					while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					{
						$CatID = $row['ID'];
						$CatDesc = $row['CatDesc'];
						$Checked = (array_key_exists($CatID, $Categories) ? " CHECKED " : "");
						?>
						<INPUT TYPE=checkbox name="CatID[]" VALUE="<?= $CatID ?>" <?= $Checked ?>><?= $CatDesc ?>
						<?php
					}
					?>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Template:
				</TD>
				<TD align="left" valign="top">
					<?php BuildTemplateDropdown('TemplateID', $TemplateID) ?>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Priority:
				</TD>
				<TD align="left" valign="top">
					<?php BuildNumericDropdown('Priority', $Priority, 1, 10) ?>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Headline:
				</TD>
				<TD align="left" valign="top">
					<TEXTAREA name="Headline" id="Headline_news" cols="<?=$AdminTextareaColumns?>" rows="2"><?=$Headline?></TEXTAREA>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					User-Defined Codes:<BR><STRONG>
				</TD>
				<TD align="left" valign="top">
					<?php
					$rows = mysql_query("SELECT ID, UserCode FROM news_usercodes ORDER BY UserCode ASC");
					while ($row = mysql_fetch_array($rows))
						echo $row['UserCode'];
					?>
				</TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					BBCodes:<BR><STRONG>
				</TD>
				<TD valign="top" nowrap> Click <A href="<?=$NewsDir?>/Inc/BBCodes.php" target="_blank">here</A> to see the supported BB Codes (new window) </TD>
			</TR>

			<TR>
				<TD class="FieldPrompt">
					Short Post:
				</TD>
				<TD align="left" valign="top">
					<TEXTAREA name="ShortPost" id="ShortPost_news" cols="<?=$AdminTextareaColumns?>" rows="15"><?=$ShortPost?></TEXTAREA>
				</TD>
			</TR>
			<TR>
				<TD class="FieldPrompt">
					Long Post:
				</TD>
				<TD align="left" valign="top">
					<TEXTAREA name="LongPost" id="LongPost_news" cols="<?=$AdminTextareaColumns?>" rows="15"><?=$LongPost?></TEXTAREA>
					<BR>
					<BR>
					<INPUT type="checkbox" name="Sticky" value="1" <?= ($Sticky == '1' ? 'checked' : '') ?>>Make this post sticky?
					<INPUT type="checkbox" name="Visible" value="1" <?= ($Visible == '1' ? 'checked' : '') ?>>Make this post visible?
					<BR>
					<?
					if ($EnableComments == 1)
					{
						?>
						<INPUT type="checkbox" name="AllowComments" value="1" <?= ($AllowComments == '1' ? 'checked' : '') ?>>Allow Comments?
						<?
					}

					if ($EnableArchive == 1)
					{
						?>
						<INPUT type="checkbox" name="Archived" value="1" <?= ($Archived == '1' ? 'checked' : '') ?>>Archive this post
						<?
					}
					?>
				</TD>
			</TR>
			<TR>
				<INPUT type="hidden" name="PostAuthor" value="<?=$LoggedInFullName?>">
				<TD colspan="2" class="C">
					<INPUT class="but" type="reset" name="reset" value="Reset">
					<INPUT class="but" type="submit" name="PreviewShort" value="Preview(S)">
					<INPUT class="but" type="submit" name="PreviewLong" value="Preview(L)">
					<?php
					if ($EnableSpellCheck)
					{
						?>
						<INPUT class="but" type="submit" name="SpellShort" value="Spellcheck(S)">
						<INPUT class="but" type="submit" name="SpellLong" value="Spellcheck(L)">
						<?php
					}
					?>
					<INPUT class="but" type="submit" name="submit" value="Post">
				</TD>
			</TR>
		</FORM>
	</TABLE>
	<?
}

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : '';
$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=NewsList">here</A> to return to news maintenance';

// =============================================================================================
// Delete an existing post - confirmed

if ($Action == 'news' AND $Mode == 'delete' AND $Confirm == 'yes')
{

	// Retrieve the post
	$newsrow =	mysql_query("SELECT news_posts. * , news_users.FullName FROM news_posts, ".
		"news_users WHERE news_posts.ID = $GetId AND news_posts.AuthorID = news_users.ID");

	$NewsData = mysql_fetch_array($newsrow);

	// Check the article security
	CheckArticleSecurity($NewsData, true);

	// Get the news headline
	$Headline = GetHeadline($GetId);

	// Send notification, if required
	if ($EmailAddressNotifyPostDeleted != "")
		SendNewsNotificationEmail($EmailAddressNotifyPostDeleted, $GetId, 'deleted');

	// Delete the article
	$ok = DeleteNewsArticle($GetId);
	if ($ok)
	{
		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_ARTICLE, 'D', $GetId, "News article deleted: " . $Headline);

		$_SESSION['Info'] = 'The news post has been deleted successfully.';
		header('location:' . $AdminScript . '?action=NewsList');
		exit;
	}
	else
	{
		$errormsg = 'There was an error deleting the news post from the database.';
		DisplayError($errormsg, 1);
	}
}

// =============================================================================================
// Delete an existing post - seek confirmation
elseif ($Action == 'news' AND $Mode == 'delete' AND $Confirm == '')
{
	// Request confirmation to delete
	DisplayGroupHeading('Remove News Post');
	?>
	<TABLE width="400" border="0" align="center">
		<TR>
			<TD width="80">
				<CENTER>
					<IMG src="Inc/Images/Question.gif">
				</CENTER>
			</TD>
			<TD>
				Are you sure you want to remove this news post from the news system?
				<BR><BR>
		  		<CENTER>
		  			<A href="<?=$AdminScript?>?action=news&mode=delete&confirm=yes&id=<?=$GetId?>">Yes</A> |
		  			<A href="<?=$AdminScript?>?action=NewsList">No</A>
		  		</CENTER>
			</TD>
		</TR>
	</TABLE>
	<?php
}

// =============================================================================================
// Editing an existing post

elseif ($Action == 'news' AND $Mode == 'edit')
{
	// Retrieve the post
	$newsrow =	mysql_query("SELECT news_posts. * , news_users.FullName FROM news_posts, ".
		"news_users WHERE news_posts.ID = $GetId AND news_posts.AuthorID = news_users.ID");

	$NewsData = mysql_fetch_array($newsrow);

	// Check the article security
	CheckArticleSecurity($NewsData, true);

	$NewsDateTime = $NewsData['PostDateTime'];
	$OriginalPostDateTime = $NewsData['OriginalPostDateTime'];

	// Automatically update the date-stamp? (but never the original stamp)
	if ($AutoUpdateTimeStampUponEdit == 1)
		$NewsDateTime = CurrentFormattedDateTime();

	// Load the categories for this article
	$Categories = CategoriesFromDB($GetId);

	DisplayData($GetId, $NewsDateTime, $OriginalPostDateTime, $NewsData['FullName'], $NewsData['ImageID'], $Categories, $NewsData['TemplateID'], $NewsData['Headline'], $NewsData['ShortPost'], $NewsData['LongPost'], $NewsData['Sticky'], $NewsData['Priority'], $NewsData['Visible'], $NewsData['AllowComments'], $NewsData['Archived']);
}

// =============================================================================================
// Copy an existing post
elseif ($Action == 'news' AND $Mode == 'copy')
{
	// Retrieve the post
	$newsrow =	mysql_query("SELECT news_posts. * , news_users.FullName FROM news_posts, ".
		"news_users WHERE news_posts.ID = $GetId AND news_posts.AuthorID = news_users.ID");

	$NewsData = mysql_fetch_array($newsrow);

	// Check the article security
	CheckArticleSecurity($NewsData, false);

	$NewsDateTime = $NewsData['PostDateTime'];
	$OriginalPostDateTime = $NewsData['OriginalPostDateTime'];

	// Should copied posts be assigned the current time
	if ($CopiedPostsRetainTime == 0)
		$NewsDateTime = CurrentFormattedDateTime();

	// Always update the "original date and time" on the copy, as it's a new post
	$OriginalPostDateTime = CurrentFormattedDateTime();

	// Load the categories for this article
	$Categories = CategoriesFromDB($GetId);

	DisplayData(-1, $NewsDateTime, $OriginalPostDateTime, $NewsData['FullName'], $NewsData['ImageID'], $Categories, $NewsData['TemplateID'], $NewsData['Headline'], $NewsData['ShortPost'], $NewsData['LongPost'], $NewsData['Sticky'], $NewsData['Priority'], $NewsData['Visible'], $NewsData['AllowComments'], $NewsData['Archived']);
}

// =============================================================================================
// Form was submitted
elseif (  (isset($_POST['submit'])) || (isset($_POST['PreviewShort'])) || (isset($_POST['PreviewLong'])) || (isset($_POST['SpellShort'])) || (isset($_POST['SpellLong']))  )
{
	// =============================================================================================

	// Get the current date & time for this news post
	$NewsID = $_POST['NewsID'];

	$PostDay = $_POST['PostDay'];
	$PostMonth = $_POST['PostMonth'];
	$PostYear = $_POST['PostYear'];
	$PostHour = $_POST['PostHour'];
	$PostMinute = $_POST['PostMinute'];

	$PostAuthor  = $_POST['PostAuthor'];
	$OriginalPostDateTime = $_POST['OriginalPostDateTime'];

	$Headline = trim($_POST['Headline']);
	$ShortPost = trim($_POST['ShortPost']);
	$LongPost = trim($_POST['LongPost']);
	$ImageID = $_POST['ImageID'];
	$Categories = CategoriesFromForm(isset($_POST['CatID']) ? $_POST['CatID'] : "");
	$TemplateID = $_POST['TemplateID'];

	$Priority = $_POST['Priority'];
	$Sticky = (isset($_POST['Sticky']) ? '1' : '0');
	$Visible = (isset($_POST['Visible']) ? '1' : '0');
	$AllowComments = (isset($_POST['AllowComments']) ? '1' : '0');
	$Archived = (isset($_POST['Archived']) ? '1' : '0');

	// Display a short or long preview?
	if (  (isset($_POST['PreviewShort'])) || (isset($_POST['PreviewLong'])) || (isset($_POST['SpellShort'])) || (isset($_POST['SpellLong']))  )
	{
		$TimesRead = 999;

		// Convert the posting-date and posting-time to database format
		$PostingDateTimeDB = $PostYear . '-' . $PostMonth . '-' . $PostDay . " " . $PostHour . ':' . $PostMinute . ':00';

		// Incomplete data?
		if ($TemplateID == "0")
		{
			// Display the form again with the data already entered
			$ErrorText = 'You must choose a template in order to preview or spell-check.';
			DisplayData($NewsID, $PostingDateTimeDB, $OriginalPostDateTime, $PostAuthor, $ImageID, $Categories, $TemplateID, $Headline, $ShortPost, $LongPost, $Sticky, $Priority, $Visible, $AllowComments, $Archived);
		}
		else
		{
			$SpellCheck = false;
			if ( (isset($_POST['SpellShort'])) || (isset($_POST['SpellLong'])) )
				$SpellCheck = true;

			if ( (isset($_POST['PreviewShort'])) || (isset($_POST['SpellShort'])) )
				PreviewArticleShort(-1, $Sticky, $Headline, $PostingDateTimeDB, $PostAuthor, $ShortPost, $LongPost, $ImageID, $TemplateID, $TimesRead, $SpellCheck, $AllowComments, $Categories);
			else
				PreviewArticleLong(-1, $Sticky, $Headline, $PostingDateTimeDB, $PostAuthor, $ShortPost, $LongPost, $ImageID, $TemplateID, $TimesRead, $SpellCheck, $AllowComments, $Categories);

			echo ('<br><br>');

			// Display the form again with the data already entered
			DisplayData($NewsID, $PostingDateTimeDB, $OriginalPostDateTime, $PostAuthor, $ImageID, $Categories, $TemplateID, $Headline, $ShortPost, $LongPost, $Sticky, $Priority, $Visible, $AllowComments, $Archived);
		}
	}	

	// =============================================================================================
	// Store to the database
	elseif (isset($_POST['submit']))
	{
		// Convert the posting-date and posting-time to database format. Disregard Seconds
		$PostingDateTimeDB = $PostYear . '-' . $PostMonth . '-' . $PostDay . " " . $PostHour . ':' . $PostMinute . ':00';

		// Incomplete data?
		if ($Headline == '' OR $ShortPost == '' OR $TemplateID == "0")
		{
			// Display the form again with the data already entered
			$ErrorText = 'You must complete both the News Headline and the Short Post, and you must choose a template.';
			DisplayData($NewsID, $PostingDateTimeDB, $OriginalPostDateTime, $PostAuthor, $ImageID, $Categories, $TemplateID, $Headline, $ShortPost, $LongPost, $Sticky, $Priority, $Visible, $AllowComments, $Archived);
		}
		else
		{
			// Update/insert
			$dbAction = '';
			$Success = TRUE;

			// Do articles require approval?
			$Approved = ($ArticlesRequireApproval) ? '0' : '1';

			$Headline = addslashes(trim($_POST['Headline']));
			$ShortPost = addslashes(trim($_POST['ShortPost']));
			$LongPost = addslashes(trim($_POST['LongPost']));

			if ($NewsID <> -1)
			{

				$sql = "UPDATE news_posts SET PostDateTime='$PostingDateTimeDB', Headline='$Headline', ShortPost='$ShortPost'," . 
					" LongPost='$LongPost', ImageID='$ImageID', TemplateID = '$TemplateID', Sticky='$Sticky', Priority='$Priority', Visible='$Visible', Approved='$Approved', AllowComments='$AllowComments', Archived='$Archived'" .
					" WHERE ID='$NewsID'";

				$Success = $Success & mysql_query($sql);
				$dbAction = 'C';

				// Update the categories
				$Success &= CategoriesToDB($NewsID, $Categories);
			}
			else
			{
				$sql = "INSERT INTO news_posts SET
					AuthorID='$LoggedInUserId', Headline='$Headline',
					PostDateTime='$PostingDateTimeDB', OriginalPostDateTime='$PostingDateTimeDB',
					TemplateID='$TemplateID', ShortPost='$ShortPost', LongPost='$LongPost',
					ImageID='$ImageID', Sticky='$Sticky', Locked='0', Priority='$Priority',
					Visible='$Visible', Approved='$Approved', AllowComments='$AllowComments', 
					Archived='$Archived'";
					$dbAction = 'A';

				$Success = $Success & mysql_query($sql);
				$NewsID = mysql_insert_id();

				// Update the categories
				$Success &= CategoriesToDB($NewsID, $Categories);
			}

			if ($Success)
			{
				// Send notification, if required
				if (($dbAction == 'A') && ($EmailAddressNotifyPostAdded != ''))
					SendNewsNotificationEmail($EmailAddressNotifyPostAdded, $NewsID, 'added');
				if (($dbAction == 'C') && ($EmailAddressNotifyPostChanged != ''))
					SendNewsNotificationEmail($EmailAddressNotifyPostChanged, $NewsID, 'changed');

				// Write audit, if required
				if ($EnableAudit == 1)
				{
					if ($dbAction == 'A')
						WriteAuditEvent(AUDIT_TYPE_ARTICLE, 'A', $NewsID, "News article created: ". $Headline);
					if ($dbAction == 'C')
						WriteAuditEvent(AUDIT_TYPE_ARTICLE, 'C', $NewsID, "News article updated: ". $Headline);
				}

				$_SESSION['Info'] = 'The news database has been updated successfully.';
				header('location:' . $AdminScript . '?action=NewsList');
				exit;
			}
			else
			{
				$errormsg = 'There was an error adding the news post to the database:';
				DisplayError($errormsg, 1);
			}
		}
	}
}
// =============================================================================================
// Display blank entry-form
else
{
	$DateTime = CurrentFormattedDateTime();
	$Categories = CategoriesFromDB(-1);
	DisplayData(-1, $DateTime, $DateTime, $LoggedInFullName, '', $Categories, '1', '', '', '', '0', $NewArticleDefaultPriority, '1', '1', '0');
}
?>