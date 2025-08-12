<?php
// ==============================================================================================================================

// Set defaults for the admin-site restrictions
function SetAdminDefaultRestrictions()
{
	global $DefaultArchiveDays, $DefaultNewsPurgeDays;

	if (! isset($_SESSION['Info']))
		$_SESSION['Info'] = '';

	if (! isset($_SESSION['RestrictCategory']))
		$_SESSION['RestrictCategory'] = 'A';		// All

	if (! isset($_SESSION['RestrictArchived']))
		$_SESSION['RestrictArchived'] = '-';		// All

	if (! isset($_SESSION['RestrictApproved']))
		$_SESSION['RestrictApproved'] = '0';		// Not approved

	if (! isset($_SESSION['RestrictVisible']))
		$_SESSION['RestrictVisible'] = '-';			// All

	if (! isset($_SESSION['RestrictSticky']))
		$_SESSION['RestrictSticky'] = '-';			// All

	if (! isset($_SESSION['RestrictSortMode']))
		$_SESSION['RestrictSortMode'] = '1';

	if (! isset($_SESSION['RestrictSearchString']))
		$_SESSION['RestrictSearchString'] = '';

	if (! isset($_SESSION['RestrictArchiveDays']))
		$_SESSION['RestrictArchiveDays'] = $DefaultArchiveDays;

	if (! isset($_SESSION['RestrictPurgeDays']))
		$_SESSION['RestrictPurgeDays'] = $DefaultNewsPurgeDays;
}

// ==============================================================================================================================

// Update the admin-site restrictions
function SetAdminCurrentRestrictions()
{ 
	if (isset($_POST['SearchString']))
		$_SESSION['RestrictSearchString'] = $_POST['SearchString'];

	if (isset($_POST['RestrictCatId']))
		$_SESSION['RestrictCategory'] = $_POST['RestrictCatId'];

	if (isset($_POST['Archived']))
		$_SESSION['RestrictArchived'] = $_POST['Archived'];

	if (isset($_POST['Approved']))
		$_SESSION['RestrictApproved'] = $_POST['Approved'];

	if (isset($_POST['Visible']))
		$_SESSION['RestrictVisible'] = $_POST['Visible'];

	if (isset($_POST['Sticky']))
		$_SESSION['RestrictSticky'] = $_POST['Sticky'];

	if (isset($_POST['SortMode']))
		$_SESSION['RestrictSortMode'] = $_POST['SortMode'];

	if (isset($_POST['ArchiveDays']))
		$_SESSION['RestrictArchiveDays'] = $_POST['ArchiveDays'];

	if (isset($_POST['PurgeDays']))
		$_SESSION['RestrictPurgeDays'] = $_POST['PurgeDays'];
}

// ==============================================================================================================================

// Apply any admin-site category restrictions
function ApplyAdminCategoryRestriction($RestrictCatId)
{
	$ReturnText = "";
	switch ($RestrictCatId)
	{
		case '0':				// No restriction
			$ReturnText = " WHERE news_posts.ID != 0";
			break;
		case 'N':				// No categories (always select from left table)
			$ReturnText = " LEFT JOIN news_postcategories ON news_posts.ID = news_postcategories.ArticleID WHERE CatID IS NULL";
			break;
		case 'A':				// All categories
			$ReturnText = " WHERE news_posts.ID != 0";
			break;
		default:				// Specific category (must match)
			$ReturnText = " INNER JOIN news_postcategories ON news_posts.ID = news_postcategories.ArticleID WHERE CatID='" . $RestrictCatId ."'";
			break;
	}

	return $ReturnText;
}


// ==============================================================================================================================

// Function: CheckUserUnique -- See if a user-id is unique within the database
function UsernameIsUnique($UserName, $UserID = -1)
{
	// Construct the SQL
	$sql = "SELECT FullName FROM news_users WHERE Username = '$UserName'";

	// If we have been passed a user-id then we are in edit-mode, and therefore the user can exist if the ID is the same
	if ($UserID != -1)
		$sql .= " AND ID != $UserID";

	$Result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$NumRows = mysql_num_rows($Result);

	return ($NumRows == 0);
}

// ==============================================================================================================================

// Function: DisplayError -- Let the user know an error has occured
function DisplayError($ErrorMsg, $mysql_code)
{
	DisplayGroupHeading('Error!!!');
	?>
	<BR>
	<TABLE class="Admin">
		<TR>
			<TD width="80">
				<CENTER><IMG src="Inc/Images/Error.gif"></CENTER>
			</TD>
			<TD>
				<CENTER>
					<?=$ErrorMsg?>
					<?= ($mysql_code == 1 ? mysql_error() : '') ?>
				</CENTER>
			</TD>
		</TR>
	</TABLE>

	<?php
	return;
}

// ==============================================================================================================================

// Function: DisplaySuccess - Let the user know the command went OK
function DisplaySuccess($SuccessMsg)
{
	DisplayGroupHeading('Success!');
	?>
	<BR>
	<TABLE class="Admin">
		<TR>
			<TD width="80">
				<CENTER><IMG src="Inc/Images/Info.gif"></CENTER>
			</TD>
			<TD>
				<CENTER><?=$SuccessMsg?></CENTER>
			</TD>
		</TR>
	</TABLE>
	 <?php
}

// ==============================================================================================================================

// Function: DisplayGroupHeading - Display consistent group-heading text
function DisplayGroupHeading($HeadingText)
{
	?>
	<TABLE class="Admin">
		<TR>
			<TD colspan="3" class="GroupHeading">
				&nbsp;&nbsp;<?= $HeadingText ?>
			</TD>
		</TR>
	</TABLE>
	<?php
}

// ==============================================================================================================================

// Function: DisplayInfoMessage - Display any current Info message
function DisplayInfoMessage()
{ 
	if (isset($_SESSION['Info']) && $_SESSION['Info'] != '')
	{
		?>
		<TABLE class="Admin">
			<TR>
				<TD colspan="3" class="Info">
					<?= $_SESSION['Info'] ?>
				</TD>
			</TR>
		</TABLE>
		<?php
	}
	$_SESSION['Info'] = '';
}

// ==============================================================================================================================

// Check that the user is an Administrator
function CheckAuthority()
{
	global $LoggedInAccessLevel;
	if ($LoggedInAccessLevel != '2')
	{
		$errormsg = 'Your account type does not grant you access to this section.';
		DisplayError($errormsg, 0);
		exit();
	}
}


// ==============================================================================================================================

// Build a drop-down for images
function BuildImageDropdown($FieldName, $SelectedID, $ShowSelectOne = true)
{
	echo '<SELECT name="' . $FieldName . '">';
	if ($ShowSelectOne)
		echo '<option value="0">Select One</option>';

	// Execute the query
	$query = "SELECT * FROM news_images ORDER BY ImageName ASC";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$ImageID = $row['ID'];
		$ImageName = $row['ImageName'];
		echo '<OPTION value="' . $ImageID . '"' . ($ImageID == $SelectedID ? ' SELECTED' : '') . ">$ImageName</OPTION>\n";
	}

	echo "</SELECT>\n";
}

// ==============================================================================================================================

// Build a drop-down for users
function BuildUserDropdown($FieldName, $SelectedID, $ShowSelectOne = true, $ShowSelectAll = false)
{
	echo '<SELECT name="' . $FieldName . '">';
	if ($ShowSelectOne)
		echo '<option value="0">Select One</option>';

	if ($ShowSelectAll)
		echo '<option value="0">All Users</option>';

	// Execute the query
	$query = "SELECT * FROM news_users ORDER BY Username ASC";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$UserID = $row['ID'];
		$Username = $row['Username'];
		echo '<OPTION value="' . $UserID . '"' . ($UserID == $SelectedID ? ' SELECTED' : '') . ">$Username</OPTION>\n";
	}

	echo "</SELECT>\n";
}
// ==============================================================================================================================

// Build a drop-down for access level
function BuildAccessLevelDropdown($FieldName, $CurrentLevel, $ShowSelectOne = true, $OnlyShowAdmin = false)
{
	echo '<SELECT name="' . $FieldName . '">';
	if ($ShowSelectOne)
		echo '<OPTION value="">Select One</OPTION>';

	if ($OnlyShowAdmin != true)
	{
		echo '<OPTION value="0"' . ($CurrentLevel == '0' ? ' SELECTED' : '') . ">Disabled</OPTION>\n";
		echo '<OPTION value="1"' . ($CurrentLevel == '1' ? ' SELECTED' : '') . ">Normal User</OPTION>\n";
	}
	echo '<OPTION value="2"' . ($CurrentLevel == '2' ? ' SELECTED' : '') . ">Administrator</OPTION>\n";

	echo "</SELECT>\n";
}

// ==============================================================================================================================

// Build a drop-down for "archived"
function BuildArchivedDropdown($FieldName, $CurrentMode, $ShowAll = false, $ShowNoChange = false)
{
	echo '<SELECT name="' . $FieldName . '">';

	if ($ShowAll)
		echo '<OPTION value="-"' . ($CurrentMode == '-' ? ' SELECTED' : '') . ">All Posts</OPTION>\n";

	if ($ShowNoChange)
		echo '<OPTION value="N/C"' . ($CurrentMode == 'N/C' ? ' SELECTED' : '') . ">No Change</OPTION>\n";

	echo '<OPTION value="0"' . ($CurrentMode == '0' ? ' SELECTED' : '') . ">Live</OPTION>\n";
	echo '<OPTION value="1"' . ($CurrentMode == '1' ? ' SELECTED' : '') . ">Archived</OPTION>\n";

	echo "</SELECT>\n";
}
// ==============================================================================================================================

// Build a drop-down for "newslist sorting"
function BuildNewsListSortDropdown($FieldName, $CurrentMode)
{
	echo '<SELECT name="' . $FieldName . '">';
	echo '<OPTION value="1"' . ($CurrentMode == '1' ? ' SELECTED' : '') . ">Archived, Priority, Date(D)</OPTION>\n";
	echo '<OPTION value="2"' . ($CurrentMode == '2' ? ' SELECTED' : '') . ">Archived(D), Sticky, Priority, Date(D)</OPTION>\n";
	echo '<OPTION value="3"' . ($CurrentMode == '3' ? ' SELECTED' : '') . ">Archived, Sticky, Priority, Date(D)</OPTION>\n";
	echo '<OPTION value="4"' . ($CurrentMode == '4' ? ' SELECTED' : '') . ">Headline, Date</OPTION>\n";
	echo '<OPTION value="5"' . ($CurrentMode == '5' ? ' SELECTED' : '') . ">Date, Headline</OPTION>\n";
	echo '<OPTION value="6"' . ($CurrentMode == '6' ? ' SELECTED' : '') . ">Date(D), Headline</OPTION>\n";
	echo "</SELECT>\n";
}

// ==============================================================================================================================

// Build a drop-down for "event category"
function BuildEventCategoryDropdown($FieldName, $SelectedID, $ShowAll = false)
{
	echo '<SELECT name="' . $FieldName . '">';

	if ($ShowAll)
		echo '<OPTION value="-"' . ($SelectedID == '-' ? ' SELECTED' : '') . ">All Events</OPTION>\n";

	// Execute the query
	$query = "SELECT * FROM news_audit_categories ORDER BY CatDesc ASC";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$CatID = $row['ID'];
		$CatDesc = $row['CatDesc'];
		echo "<OPTION value=\"$CatID\"" . ($CatID == $SelectedID ? ' SELECTED' : '') . ">$CatDesc</OPTION>\n";
	}

	echo "</SELECT>\n";
}

// ==============================================================================================================================

function BuildVisibleDropdown($FieldName, $CurrentMode, $ShowAll = false, $ShowNoChange = false)
{
	echo "\n<SELECT name=\"$FieldName\">\n";

	if ($ShowAll)
		echo '<OPTION value="-"' . ($CurrentMode == '-' ? ' SELECTED' : '') . ">All Posts</OPTION>\n";

	if ($ShowNoChange)
		echo '<OPTION value="N/C"' . ($CurrentMode == 'N/C' ? ' SELECTED' : '') . ">No Change</OPTION>\n";

	echo '<OPTION value="0"' . ($CurrentMode == '0' ? ' SELECTED' : '') . ">Not Visible</OPTION>\n";
	echo '<OPTION value="1"' . ($CurrentMode == '1' ? ' SELECTED' : '') . ">Visible</OPTION>\n";

	echo "</SELECT>\n";
}
// ==============================================================================================================================

function BuildStickyDropdown($FieldName, $CurrentMode, $ShowAll = false, $ShowNoChange = false)
{
	echo "\n<SELECT name=\"$FieldName\">\n";

	if ($ShowAll)
		echo '<OPTION value="-"' . ($CurrentMode == '-' ? ' SELECTED' : '') . ">All Posts</OPTION>\n";

	if ($ShowNoChange)
		echo '<OPTION value="N/C"' . ($CurrentMode == 'N/C' ? ' SELECTED' : '') . ">No Change</OPTION>\n";

	echo '<OPTION value="0"' . ($CurrentMode == '0' ? ' SELECTED' : '') . ">Not Sticky</OPTION>\n";
	echo '<OPTION value="1"' . ($CurrentMode == '1' ? ' SELECTED' : '') . ">Sticky</OPTION>\n";

	echo "</SELECT>\n";
}

// ==============================================================================================================================

function BuildApprovedDropdown($FieldName, $CurrentMode, $ShowAll = false, $ShowNoChange)
{
	echo "\n<SELECT name=\"$FieldName\">\n";

	if ($ShowAll)
		echo '<OPTION value="-"' . ($CurrentMode == '-' ? ' SELECTED' : '') . ">All Items</OPTION>\n";

	if ($ShowNoChange)
		echo '<OPTION value="N/C"' . ($CurrentMode == 'N/C' ? ' SELECTED' : '') . ">No Change</OPTION>\n";

	echo '<OPTION value="0"' . ($CurrentMode == '0' ? ' SELECTED' : '') . ">Not Approved</OPTION>\n";
	echo '<OPTION value="1"' . ($CurrentMode == '1' ? ' SELECTED' : '') . ">Approved</OPTION>\n";

	echo "</SELECT>\n";
}

// ==============================================================================================================================

function BuildCopyMoveDropdown($FieldName, $CurrentMode, $ShowNoChange)
{
	echo "\n<SELECT name=\"$FieldName\">\n";

	if ($ShowNoChange)
		echo '<OPTION value="-"' . ($CurrentMode == 'N/C' ? ' SELECTED' : '') . ">No Change</OPTION>\n";

	echo '<OPTION value="1"' . ($CurrentMode == '1' ? ' SELECTED' : '') . ">Assign to Category</OPTION>\n";
	echo '<OPTION value="2"' . ($CurrentMode == '2' ? ' SELECTED' : '') . ">Remove from Category</OPTION>\n";

	echo "</SELECT>\n";
}

// ==============================================================================================================================

// Build a drop-down for templates
function BuildTemplateDropdown($FieldName, $SelectedID, $ShowSelectOne = true, $ShowAllTemplates=false)
{
	echo "\n<SELECT name=\"$FieldName\">\n";

	if ($ShowSelectOne)
		echo '<OPTION value="0">Select One</OPTION>';

	if ($ShowAllTemplates)
		echo '<OPTION value="0">All Templates</OPTION>';

	// Execute the query
	$query = "SELECT * FROM news_templates ORDER BY TemplateName ASC";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$TemplateID = $row['ID'];
		$TemplateName = $row['TemplateName'];
		echo "<OPTION value=\"$TemplateID\"" . ($TemplateID == $SelectedID ? ' SELECTED' : '') . ">$TemplateName</OPTION>\n";
	}
	echo "</SELECT>\n";
}

// ==============================================================================================================================

// Build a drop-down for months
function BuildMonthDropdown($FieldName, $SelectedMonth)
{
	global $MonthNames;

	echo '<SELECT name="' . $FieldName . '">';

	for ($i = 1; $i <= 12; $i++)
	{
		$j = $i - 1;
		echo "<OPTION value=\"$i\"" . ($i == $SelectedMonth ? ' SELECTED' : '') . ">$MonthNames[$j]</OPTION>\n";
	}
	echo "</SELECT>\n";
}

// ==============================================================================================================================

function SendNewsNotificationEmail($EmailAddress, $NewsID, $Mode)
{
	global $AdminEmail, $SiteDescription;

	// Execute the query
	$sql = "SELECT Headline, ShortPost, Sticky, Visible, FullName FROM news_posts, news_users WHERE news_posts.AuthorID = news_users.ID AND news_posts.ID = $NewsID";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);

	// Extract the data
	$Headline = $row['Headline'];
	$ShortPost = $row['ShortPost'];
	$FullName = $row['FullName'];
	$Sticky = $row['Sticky'];
	$Visible = $row['Visible'];

	// Format the short-post
	$UserCodes = BuildUserDefinedCodesList();
	$ShortPost = ParseUserDefinedCodes($UserCodes, $ShortPost);
	$ShortPost = StripBBCodes($ShortPost);
	$ShortPost = htmlspecialchars(strip_tags($ShortPost));

	$Subject = "$SiteDescription Content-Change Notification (article $Mode)";
	$Mailheader = "From: $AdminEmail\n";
	$Mailheader .= "Reply-To: $AdminEmail\n";

	$Message = "A News Article has been $Mode by $FullName .\n\n";
	$Message .= "Headline  : $Headline\n";
	$Message .= "Sticky    : " . ($Sticky == 1 ? "Yes" : "No") . "\n";
	$Message .= "Visible   : " . ($Visible == 1 ? "Yes" : "No") . "\n";
	$Message .= "Short Post:\n $ShortPost";		

	// Send the email
	mail($EmailAddress, $Subject, $Message, $Mailheader) or die ('Failure sending notification email!');
}

// ==============================================================================================================================

function WriteAuditEvent($EventCatID, $EventType, $LinkedID, $Description)
{
	global $LoggedInUserId;
	$sql = "INSERT INTO news_audit SET EventDateTime = now(), EventCatID = '$EventCatID', EventType = '$EventType', EventUserID='$LoggedInUserId', LinkedID = '$LinkedID', Description = '$Description'";
	mysql_query($sql);
}

// ==============================================================================================================================

function GetLatestVersionNumber()
{
	$errno = 0;
	$errstr = $VersionInfo = '';

	if ($fsock = @fsockopen('www.phpfreenews.co.uk', 80, $errno, $errstr))
	{
		@fputs($fsock, "GET /Version.txt HTTP/1.1\r\n");
		@fputs($fsock, "HOST: www.phpfreenews.co.uk\r\n");
		@fputs($fsock, "Connection: close\r\n\r\n");

		$get_info = false;
		while (!@feof($fsock))
		{
			if ($get_info)
				$VersionInfo .= @fread($fsock, 1024);
			else
				if (@fgets($fsock, 1024) == "\r\n")
					$get_info = true;
		}
		@fclose($fsock);
		$VersionInfo = explode("\n", $VersionInfo);
//		$LatestLiveVersion = $VersionInfo[0];
//		$LatestBetaVersion = $VersionInfo[1];
		return $VersionInfo;
	}
	else
	{
		if ($errstr)
			return -1;					// Unable to connect
		else
			return -2;					// Unable to use Sucket functions
	}
}

// ==============================================================================================================================

// Apply a sort-order in the admin script
function ApplyAdminSort($SortMode)
{
	$Sort = " ORDER BY";

	switch ($SortMode)
	{
		case 1:
			$Sort .= " Archived, Priority, PostDateTime DESC, ID DESC";
			break;
		case 2:
			$Sort .= " Archived DESC, Sticky DESC, Priority, PostDateTime DESC, ID DESC";
			break;
		case 3:
			$Sort .= " Sticky DESC, Archived DESC, Priority, PostDateTime DESC, ID DESC";
			break;
		case 4:
			$Sort .= " Headline, PostDateTime DESC, ID DESC";
			break;
		case 5:
			$Sort .= " PostDateTime, Headline";
			break;
		case 6:
			$Sort .= " PostDateTime DESC, Headline";
			break;
		default:
			$Sort .= " Archived, Priority, PostDateTime DESC, ID DESC";
			break;
	}
	return $Sort;
}

// ==============================================================================================================================

// Delete a single news article
function DeleteNewsArticle($ArticleID)
{
	$Success = true;

	// Delete the news-categories information
	@mysql_query("DELETE FROM news_postcategories WHERE ArticleID='$ArticleID'");
		
	// Now delete any comments
	@mysql_query("DELETE FROM news_comments WHERE ArticleID='$ArticleID'");

	// Now delete any ratings
	@mysql_query("DELETE FROM news_ratings WHERE ArticleID='$ArticleID'");

	// Finally, delete the article
	$Success = mysql_query("DELETE FROM news_posts WHERE ID='$ArticleID'");

	return $Success;
}

?>