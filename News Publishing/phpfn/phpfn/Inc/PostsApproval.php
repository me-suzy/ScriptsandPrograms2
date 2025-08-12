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

if (! $LoggedInCanApprovePosts)
	die("Illegal attempt to approve posts!");

$ArticleID = isset($_POST['ArticleID']) ? $_POST['ArticleID'] : '0';

// Perform updates?

// Get the news headline
if ($ArticleID != 0)
	$Headline = GetHeadline($ArticleID);

if (isset($_POST['Approve']))
{
	if ($ArticleID != 0)
		mysql_query("UPDATE news_posts SET Approved='1' WHERE ID='$ArticleID'");

	// Write audit, if required
	if ($EnableAudit == 1)
		WriteAuditEvent(AUDIT_TYPE_ARTICLEAPPROVAL, 'C', $ArticleID, 'News article has been approved: ' . $Headline);
}

if (isset($_POST['Unapprove']))
{
	if ($ArticleID != 0)
		mysql_query("UPDATE news_posts SET Approved='0' WHERE ID='$ArticleID'");

	// Write audit, if required
	if ($EnableAudit == 1)
		WriteAuditEvent(AUDIT_TYPE_ARTICLEAPPROVAL, 'C', $ArticleID, 'News article has been unapproved: ' . $Headline);
}

if (isset($_POST['Delete']))
{
	if ($ArticleID != 0)
	{
		mysql_query("DELETE FROM news_comments WHERE ArticleID='$ArticleID'");
		mysql_query("DELETE FROM news_posts WHERE ID='$ArticleID'");
		mysql_query("DELETE FROM news_postcategories WHERE ArticleID='$ArticleID'");

		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_ARTICLE, 'D', $ArticleID, "News article deleted: " . $Headline);
	}
}

// If specified, store into the session the restriction-information
SetAdminCurrentRestrictions();

$RestrictCatId = $_SESSION['RestrictCategory'];
$Archived = $_SESSION['RestrictArchived'];
$Approved = $_SESSION['RestrictApproved'];
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

// Restrict by Approved?
if ($Approved != '-')
	$Query .= ' AND Approved=' . $Approved;

// User can edit any posts?
if (!$LoggedInEditAnyPost)
	$Query .= ' AND AuthorID=' . $LoggedInUserId;

// Now obtain the record count
$ResultSet = mysql_query($Query) or die("Query failed : " . mysql_error());
$NumRecords = mysql_num_rows($ResultSet);

$RecStart = $AdminArticleApprovalPerPage * ($ShowPage-1); 
$PageNavBar = ConstructPagingBar($_SERVER['PHP_SELF'] . "?action=postsapproval", $NumRecords, $AdminArticleApprovalPerPage, $ShowPage, $RecStart, $AdminArticleApprovalPageBar, '', '');

DisplayGroupHeading("Approve Posts - Page $ShowPage");
?>
<BR>
<TABLE class="Admin">
	<TR>
		<TD class="FieldPrompt">
			<FORM action="<?=$AdminScript?>?action=postsapproval" method="post">
				Cat. <?= BuildCategoryDropdown('RestrictCatId', $RestrictCatId, false, true, true) ?>
				State <?= BuildArchivedDropdown('Archived', $Archived, true) ?>
				Approved <?= BuildApprovedDropdown('Approved', $Approved, true) ?>
				<BR>
				Sort by <?= BuildNewsListSortDropdown('SortMode', $SortMode) ?>
				<INPUT type="submit" class="but" name="submit" value="Filter" />
			</FORM>
		</TD>
	</TR>
</TABLE>
<BR>
<TABLE class="Admin">
	<TR>
		<TD>
			<TABLE class="Admin">
				<?php
				// Apply any sort-order
				$Query .= ApplyAdminSort($SortMode);

				// Apply any limits, and perform the search
				$Query .= " LIMIT $RecStart, $AdminArticleApprovalPerPage";
				$ResultSet =	mysql_query($Query);
				while ($Row = mysql_fetch_array($ResultSet))
				{
					$ArticleID = $Row['ID'];		
					$Headline = $Row['Headline'];		
					$PostAuthor = $Row['FullName'];
					$PostDateTime = $Row['PostDateTime'];
					$ShortPost = $Row['ShortPost'];
					$LongPost = $Row['LongPost'];
					$ImageID = $Row['ImageID'];
					$AllowComments = $Row['AllowComments'];
					$TemplateID = $Row['TemplateID'];
					$TimesRead = $Row['TimesRead'];
					$Approved = $Row['Approved'];
					$Sticky = $Row['Sticky'];
					$SpellCheck = false;
					?>
					<TR>
						<FORM method="post" action="<?=$AdminScript?>?action=postsapproval">
							<TD class="NewsListNonSticky">
								<?
								PreviewArticleShort($ArticleID, $Sticky, $Headline, $PostDateTime, $PostAuthor, $ShortPost, $LongPost, $ImageID, $TemplateID, $TimesRead, $SpellCheck, $AllowComments);
								?>
							</TD>
							<TD class="C">
								<INPUT type="hidden" name="ArticleID" value="<?=$ArticleID?>">
								(<?= ($Approved == "1" ? "A" : "Una") ?>pproved)<BR /><BR />
								<INPUT class="but" type="submit" name="Approve" value="Approve">
								<INPUT class="but" type="submit" name="Unapprove" value="Unapprove"><BR />
								<INPUT class="but" type="submit" name="Delete" value="Delete" onClick="return confirm('Delete this article?');">
							</TD>
						</FORM>
					</TR>
					<?php
				}
				?>
			</TABLE>
			<BR><BR>
			<DIV align="center">
				<?= $PageNavBar ?>
				<BR>
			</DIV>
		</TD>
	</TR>
</TABLE>
<?php
?>