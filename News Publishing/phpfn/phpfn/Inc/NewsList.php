<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

$ListOffset = isset($_GET['offset']) ? $_GET['offset'] : '0';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=NewsList">here</A> to return to the news items';

// If specified, store into the session the restriction-information
SetAdminCurrentRestrictions();

$SearchString = $_SESSION['RestrictSearchString'];
$RestrictCatId = $_SESSION['RestrictCategory'];
$Archived = $_SESSION['RestrictArchived'];
$Visible = $_SESSION['RestrictVisible'];
$Sticky = $_SESSION['RestrictSticky'];
$SortMode = $_SESSION['RestrictSortMode'];
$ShowPage = isset($_REQUEST['ShowPage']) ? $_REQUEST['ShowPage'] : 1;

// Determine the number of records in the file, and work out the number of pages
$Query = "SELECT DISTINCT news_posts.*, news_users.FullName FROM news_posts, news_users";

// Apply any category-restriction
$Query .= ApplyAdminCategoryRestriction($RestrictCatId);

// Join the users table correctly
$Query .= " AND news_posts.AuthorID = news_users.ID";

// Restrict by Archived?
if ($Archived != '-')
	$Query .= ' AND Archived=' . $Archived;

// Restrict by Visible?
if ($Visible != '-')
	$Query .= ' AND Visible=' . $Visible;

// Restrict by Sticky?
if ($Sticky != '-')
	$Query .= ' AND Sticky=' . $Sticky;

// User can edit any posts?
if (!$LoggedInEditAnyPost)
	$Query .= ' AND news_posts.AuthorID=' . $LoggedInUserId;

// Apply any wildcard search, if specified
if ($SearchString != '')
	$Query .= " AND MATCH (headline, shortpost, longpost) AGAINST('$SearchString' IN BOOLEAN MODE)";

// Now obtain the record count
$ResultSet = mysql_query($Query) or die("Query failed : " . mysql_error());
$NumRecords = mysql_num_rows($ResultSet);

$RecStart = $AdminNewsPerPage * ($ShowPage-1); 
$PageNavBar = ConstructPagingBar($_SERVER['PHP_SELF'].'?action=NewsList', $NumRecords, $AdminNewsPerPage, $ShowPage, $RecStart, $AdminPageBar, '', '');

DisplayGroupHeading("News Articles - Page $ShowPage");
?>
<BR>
<TABLE class="Admin">
	<TR>
		<TD class="FieldPrompt">
			<FORM action="<?=$AdminScript?>" method="post">
				Text <INPUT type="text" name="SearchString"  value="<?= $SearchString ?>" size="10" maxlength="20">
				Cat. <?= BuildCategoryDropdown('RestrictCatId', $RestrictCatId, false, true, true) ?>
				State <?= BuildArchivedDropdown('Archived', $Archived, true) ?>
				Vis. <?= BuildVisibleDropdown('Visible', $Visible, true) ?>
				Sticky <?= BuildStickyDropdown('Sticky', $Sticky, true) ?>
				<BR>
				Sort by <?= BuildNewsListSortDropdown('SortMode', $SortMode) ?>
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
			<TABLE cellpadding="1">
				<?php
				// Apply any sort-order
				$Query .= ApplyAdminSort($SortMode);

				// Apply any limits, and perform the search
				$Query .= " LIMIT $RecStart, $AdminNewsPerPage";
				$ResultSet =	mysql_query($Query);
				while ($newsrow = mysql_fetch_array($ResultSet))
				{
					$ArticleID = $newsrow['ID'];
					$Sticky = $newsrow['Sticky'];
					$Locked = $newsrow['Locked'];
					$Visible = $newsrow['Visible'];
					$Archived = $newsrow['Archived'];

					// Specify the listing style colour
					if ($Sticky == '1')
						$liststyle = 'NewsListSticky';
					else
						$liststyle = 'NewsListNonSticky';

					// If it's invisible then override
					if ($Visible != '1')
						$liststyle = 'NewsListNonVisible';

					// If it's archived then override
					if ($Archived == '1')
						$liststyle = 'NewsListArchived';

					// Determine which date and time to show
					if ($AdminNewsListDateTime == 1)
						$ShowDateString = date($NewsDisplay_DateFormat, strtotime($newsrow['OriginalPostDateTime'])) . '&nbsp;' . date($NewsDisplay_TimeFormat, strtotime($newsrow['OriginalPostDateTime']));
					else
						$ShowDateString = date($NewsDisplay_DateFormat, strtotime($newsrow['PostDateTime'])) . '&nbsp;' . date($NewsDisplay_TimeFormat, strtotime($newsrow['PostDateTime']));
					?>
					<TR>
						<TD class="<?=$liststyle?>" nowrap="nowrap">
							<?php if (! $Locked) { ?>
								<A href="<?=$AdminScript?>?action=news&amp;mode=edit&amp;id=<?=$ArticleID?>" title="Edit News">
									<IMG src="Inc/Images/EditNews.gif" border="0" align="middle" alt="Edit Posting">
								</A>

								<A href="<?=$AdminScript?>?action=news&amp;mode=copy&amp;id=<?=$ArticleID?>" title="Copy News">
									<IMG src="Inc/Images/CopyNews.gif" border="0" align="middle" alt="Copy to New Posting">
								</A>

								<A href="<?=$AdminScript?>?action=news&amp;mode=delete&amp;id=<?=$ArticleID?>" title="Delete News">
									<IMG src="Inc/Images/RemoveNews.gif" border="0" align="middle" alt="Delete Posting">
								</A>

								<A href="<?=$AdminScript?>?action=DoSticky&amp;id=<?=$ArticleID?>" title="Toggle Sticky">
									<IMG src="Inc/Images/<?= ($Sticky == '1' ? '' : 'Non' ) ?>Sticky.gif" border="0" align="middle" alt="Toggle Sticky status">
								</A>
								<A href="<?=$AdminScript?>?action=DoVisible&amp;id=<?=$ArticleID?>" title="Toggle Visible">
									<IMG src="Inc/Images/<?= ($Visible == '1' ? 'V' : 'Inv' ) ?>isible.gif" border="0" align="middle" alt="Toggle Visible status">
								</A>
							<?php } else { ?>
								<IMG src="Inc/Images/EditNewsLocked.gif" border="0" align="middle" alt="Locked!">
								<A href="<?=$AdminScript?>?action=news&amp;mode=copy&amp;id=<?=$ArticleID?>" title="Copy News">
									<IMG src="Inc/Images/CopyNews.gif" border="0" align="middle" alt="Copy to New Posting">
								</A>
								<IMG src="Inc/Images/RemoveNewsLocked.gif" border="0" align="middle" alt="Locked!">
								<IMG src="Inc/Images/<?= ($Sticky == '1' ? '' : 'Non' ) ?>StickyLocked.gif" border="0" align="middle" alt="Locked!">
								<IMG src="Inc/Images/<?= ($Visible == '1' ? 'V' : 'Inv' ) ?>isibleLocked.gif" border="0" align="middle" alt="Locked!">
							<?php
							} 

							if ($LoggedInCanChangeLock)
								{
								?>
								<A href="<?=$AdminScript?>?action=DoLock&amp;id=<?=$ArticleID?>" title="Toggle Lock">
									<IMG src="Inc/Images/<?= ($Locked == '1' ? '' : 'Un' ) ?>Locked.gif" border="0" align="middle" alt="Toggle Lock status">
								</A>
								<?php
							}
							?>
						</TD>
						<TD class="<?=$liststyle ?>">
							<?=$newsrow['Priority']?>
						</TD>
						<TD class="<?=$liststyle ?>">
							<?=$newsrow['Headline']?><?= ($newsrow['Archived'] == 1 ? ' (Archived) ' : '') ?><BR>
							<DIV class="NewsListDateTime"><?= $ShowDateString ?> (ID=<?=$ArticleID?>)</DIV>
						</TD>
					</TR>
					<?php
				}
				?>
			</TABLE>
			<BR><BR>
			<DIV align="center">
				<?= $PageNavBar ?>
				<BR><BR>
				<IMG src="Inc/Images/EditNews.gif" border="0" align="middle" alt="Edit"> Edit |
				<IMG src="Inc/Images/RemoveNews.gif" border="0" align="middle" alt="Remove"> Delete
			</DIV>
		</TD>
	</TR>
</TABLE>
