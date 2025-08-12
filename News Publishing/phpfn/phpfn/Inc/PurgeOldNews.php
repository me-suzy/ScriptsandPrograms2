<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

// Form Submitted?
if (isset($_POST['NumItems']))
{
	$ArticlesToPurge = array();

	// Process all the elements
	$NumItems = $_POST['NumItems'];
	for ($i=1; $i <= $NumItems; $i++)
		if (isset($_POST['id' . $i]))
			$ArticlesToPurge[] = $_POST['id' . $i];

	// Only one article? Append a dummy one (otherwise the SQL 'IN' statement is invalid)
	if (count($ArticlesToPurge) == 1)
		$ArticlesToPurge[] = -1;

	// Process each article in turn
	foreach ($ArticlesToPurge as $Key=> $ArticleID)
	{
		$Headline = GetHeadline($ArticleID);

		DeleteNewsArticle($ArticleID);

		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_ARTICLE, 'D', $ArticleID, "News article has been deleted: " . $Headline);
	}
}

// If specified, store into the session the restriction-information
SetAdminCurrentRestrictions();

$RestrictCatId = $_SESSION['RestrictCategory'];
$Archived = $_SESSION['RestrictArchived'];
$Visible = $_SESSION['RestrictVisible'];
$Sticky = $_SESSION['RestrictSticky'];
$PurgeDays = $_SESSION['RestrictPurgeDays'];

// Determine the number of records in the file, and work out the number of pages
$Query = "SELECT DISTINCT news_posts.* FROM news_posts";

// Apply any category-restriction
$Query .= ApplyAdminCategoryRestriction($RestrictCatId);

// Always restrict by Locked and the "days" criteria
$Query .= " AND Locked='0' AND TO_DAYS(NOW()) - TO_DAYS(PostDateTime) >= $PurgeDays";

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

DisplayGroupHeading("Purge News Articles");
?>
<BR>
<TABLE class="Admin">
	<TR>
		<TD class="FieldPrompt">
			<FORM name="filter" action="<?=$AdminScript?>?action=PurgeNews" method="post">
				Days Old <INPUT type="text" name="PurgeDays"  value="<?= $PurgeDays ?>" size="3" maxlength="3">
				Cat. <?= BuildCategoryDropdown('RestrictCatId', $RestrictCatId, false, true, true) ?>
				State <?= BuildArchivedDropdown('Archived', $Archived, true) ?>
				Vis. <?= BuildVisibleDropdown('Visible', $Visible, true) ?>
				Sticky <?= BuildStickyDropdown('Sticky', $Sticky, true) ?>
				<INPUT class="but" type="submit" name="submit" value="Filter" />
			</FORM>
		</TD>
	</TR>
</TABLE>
<BR>
<TABLE class="Admin">
	<TR>
		<TD>
			<FORM name="purge" action="<?=$AdminScript?>?action=PurgeNews" method="post" onSubmit="return ConfirmArchivePurgeAction(document.purge, 'NumItems', 'id', 'Purge')" >
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
							<BR />
							<INPUT type="hidden" name="NumItems" value="<?=$i?>" />
							<INPUT class="but" type="button" name="SelectAll" value="Select All" onClick="SelectAllBoxes(document.purge, 'NumItems', 'id')" />
							<INPUT class="but" type="button" name="DeSelectAll" value="De-Select All" onClick="DeSelectAllBoxes(document.purge, 'NumItems', 'id')" />
							<INPUT class="but" type="submit" name="submit" value="Purge"/>
						</TD>
					</TR>
				</TABLE>
			</FORM>
		</TD>
	</TR>
</TABLE>
