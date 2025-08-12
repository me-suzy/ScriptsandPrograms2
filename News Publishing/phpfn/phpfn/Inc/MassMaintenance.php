<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

// Retrieve the "new" flags
$NewArchived = (isset($_POST['NewArchived']) ? $_POST['NewArchived'] : 'N/C');
$NewVisible = (isset($_POST['NewVisible']) ? $_POST['NewVisible'] : 'N/C');
$NewSticky = (isset($_POST['NewSticky']) ? $_POST['NewSticky'] : 'N/C');
$NewApproved = (isset($_POST['NewApproved']) ? $_POST['NewApproved'] : 'N/C');
$NewCatAction = (isset($_POST['NewCatAction']) ? $_POST['NewCatAction'] : 'N/C');
$NewCatID = (isset($_POST['NewCatID']) ? $_POST['NewCatID'] : '-');

// Form Submitted?
if (isset($_POST['NumItems']))
{
	$ArticlesToUpdate = array();

	// Process all the elements
	$NumItems = $_POST['NumItems'];
	for ($i=1; $i <= $NumItems; $i++)
		if (isset($_POST['id' . $i]))
			$ArticlesToUpdate[] = $_POST['id' . $i];

	// Only one article? Append a dummy one (otherwise the SQL 'IN' statement is invalid)
	if (count($ArticlesToUpdate) == 1)
		$ArticlesToUpdate[] = -1;

	// Implode into a comma-separated lists
	$ArticleList = implode(",", $ArticlesToUpdate);

	// If changing the attributes of articles, process these in one pass
	if ($NewArchived != 'N/C' || $NewVisible != 'N/C' || $NewSticky != 'N/C' || $NewApproved != 'N/C')
	{
		$sql = "UPDATE news_posts SET ID=ID";

		if ($NewArchived != 'N/C')
			$sql .= ", Archived = '$NewArchived'";

		if ($NewVisible != 'N/C')
			$sql .= ", Visible = '$NewVisible'";

		if ($NewSticky != 'N/C')
			$sql .= ", Sticky = '$NewSticky'";

		if ($NewApproved != 'N/C')
			$sql .= ", Approved = '$NewApproved'";

		$sql .= " WHERE ID IN ($ArticleList)";

		mysql_query($sql);
	}

	// Copying (assigning) articles to a category?
	if ($NewCatAction == 1 && $NewCatID != '-')
	{
		mysql_query("DELETE FROM news_postcategories WHERE CatID = '$NewCatID' AND ArticleID IN ($ArticleList)");

		foreach ($ArticlesToUpdate as $Key=> $ArticleID)
			mysql_query("INSERT INTO news_postcategories SET ArticleID = '$ArticleID', CatID = '$NewCatID'");
	}

	// Removing articles from a category?
	if ($NewCatAction == 2 && $NewCatID != '-')
		mysql_query("DELETE FROM news_postcategories WHERE ArticleID IN ($ArticleList) AND CatID = '$NewCatID'");

	$_SESSION['Info'] = 'The articles you selected have now been updated according to your requirements.';
}

// If specified, store into the session the restriction-information
SetAdminCurrentRestrictions();

$RestrictCatId = $_SESSION['RestrictCategory'];
$Archived = $_SESSION['RestrictArchived'];
$Visible = $_SESSION['RestrictVisible'];
$Sticky = $_SESSION['RestrictSticky'];

// Determine the number of records in the file, and work out the number of pages
$Query = "SELECT DISTINCT news_posts.* FROM news_posts";

// Apply any category-restriction
$Query .= ApplyAdminCategoryRestriction($RestrictCatId);

// Always restrict by Locked criteria
$Query .= " AND Locked='0'";

// Restrict by Archived?
if ($Archived != '-')
	$Query .= ' AND Archived=' . $Archived;

// Restrict by Visible?
if ($Visible != '-')
	$Query .= ' AND Visible=' . $Visible;

// Restrict by Sticky?
if ($Sticky != '-')
	$Query .= ' AND Sticky=' . $Sticky;

// Now obtain the record count
$ResultSet = mysql_query($Query) or die("Query failed : " . mysql_error());
$NumRecords = mysql_num_rows($ResultSet);

DisplayGroupHeading("Mass Maintenance");
?>
<DIV><B>NB: Any changes made on this screen will NOT be reflected in the Audit functionality.</B></DIV>
<BR>
<TABLE class="Admin">
	<TR>
		<TD class="FieldPrompt">
			<FORM name="filter" action="<?=$AdminScript?>?action=Mass" method="post">
				Cat. <?= BuildCategoryDropdown('RestrictCatId', $RestrictCatId, false, true, true) ?>
				State <?= BuildArchivedDropdown('Archived', $Archived, true) ?>
				Vis. <?= BuildVisibleDropdown('Visible', $Visible, true) ?>
				Sticky <?= BuildStickyDropdown('Sticky', $Sticky, true) ?>
				<INPUT class="but" type="submit" name="submit" value="Filter" />
			</FORM>
		</TD>
	</TR>
</TABLE>
<?php DisplayInfoMessage(); ?>
<BR>
<TABLE class="Admin">
	<TR>
		<TD>
			<FORM name="mass" action="<?=$AdminScript?>?action=Mass" method="post" onSubmit="return ConfirmMassAction(document.mass, 'NumItems', 'id')" >
				<TABLE cellpadding="1">
					<?php

					// Apply any limits, and perform the search
					$i=0;
					while ($row = mysql_fetch_array($ResultSet))
					{
						$i++;
						$ArticleID = $row['ID'];
						$ShowDateString = date($NewsDisplay_DateFormat, strtotime($row['PostDateTime'])) . '&nbsp;' . date($NewsDisplay_TimeFormat, strtotime($row['PostDateTime']));
						?>
						<TR>
							<TD>
								<input type="checkbox" name="id<?=$i?>" value="<?=$ArticleID?>" />
							</TD>
							<TD>
								<?=$row['Headline']?><BR>
								<DIV class="NewsListDateTime"><?= $ShowDateString ?></DIV>
							</TD>
						</TR>
						<?php
					}
					?>
					<TR>
						<TD colspan="2">
							<HR />
							<B>New Attributes</B><BR><BR>
							State <?= BuildArchivedDropdown('NewArchived', $NewArchived, false, true) ?>
							Vis. <?= BuildVisibleDropdown('NewVisible', $NewVisible, false, true) ?>
							Sticky <?= BuildStickyDropdown('NewSticky', $NewSticky, false, true) ?>
							Approved <?= BuildApprovedDropdown('NewApproved', $NewApproved, false, true) ?>
							<BR />
							Category <?= BuildCopyMoveDropdown('NewCatAction', $NewCatAction, true) ?>
							<?= BuildCategoryDropdown('NewCatID', $NewCatID, true) ?>
						</TD>
					</TR>

					<TR>
						<TD colspan="2">
							<BR />
							<INPUT type="hidden" name="NumItems" value="<?=$i?>" />
							<INPUT class="but" type="button" name="SelectAll" value="Select All" onClick="SelectAllBoxes(document.mass, 'NumItems', 'id')" />
							<INPUT class="but" type="button" name="DeSelectAll" value="De-Select All" onClick="DeSelectAllBoxes(document.mass, 'NumItems', 'id')" />
							<INPUT class="but" type="submit" name="submit" value="Apply"/>
						</TD>
					</TR>
				</TABLE>
			</FORM>
		</TD>
	</TR>
</TABLE>