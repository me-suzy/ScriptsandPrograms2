<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

// Perform updates?
if (isset($_POST['Approve']))
{
	$ID = isset($_POST['commentid']) ? $_POST['commentid'] : '0';
	$Comment = isset($_POST['Comment']) ? $_POST['Comment'] : '';
	if ($ID != 0)
		mysql_query("UPDATE news_comments SET Comment = '$Comment', Approved='1' WHERE ID='$ID'");
}

if (isset($_POST['Unapprove']))
{
	$ID = isset($_POST['commentid']) ? $_POST['commentid'] : '0';
	$Comment = isset($_POST['Comment']) ? $_POST['Comment'] : '';
	if ($ID != 0)
		mysql_query("UPDATE news_comments SET Comment = '$Comment', Approved='0' WHERE ID='$ID'");
}

if (isset($_POST['Update']))
{
	$ID = isset($_POST['commentid']) ? $_POST['commentid'] : '0';
	$Comment = isset($_POST['Comment']) ? $_POST['Comment'] : '';

	if ($ID != 0)
		mysql_query("UPDATE news_comments SET Comment = '$Comment' WHERE ID='$ID'");
}

if (isset($_POST['Delete']))
{
	$ID = isset($_POST['commentid']) ? $_POST['commentid'] : '0';
	if ($ID != 0)
		mysql_query("DELETE FROM news_comments WHERE ID='$ID'");
}

$ListOffset = isset($_GET['offset']) ? $_GET['offset'] : '0';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=NewsList">here</A> to return to the news items';

// If specified, store into the session the restriction-information
SetAdminCurrentRestrictions();

$RestrictCatId = $_SESSION['RestrictCategory'];
$Approved = $_SESSION['RestrictApproved'];
$ShowPage = isset($_REQUEST['ShowPage']) ? $_REQUEST['ShowPage'] : 1;

// Determine the number of records in the file, and work out the number of pages
$Query = "SELECT news_posts.ID AS NewsID, Headline, news_comments.ID AS CommentID, IPAddress, news_comments.Approved, Comment, news_comments.CommentDateTime, news_comments.Name, news_comments.EmailAddress";
$Query .= " FROM news_posts INNER JOIN news_comments ON news_posts.ID = news_comments.ArticleID";

// Apply any category-restriction
$Query .= ApplyAdminCategoryRestriction($RestrictCatId);

// Restrict by Approved?
if ($Approved != '-')
	$Query .= ' AND news_comments.Approved=' . $Approved;

// User can edit any posts?
if (!$LoggedInEditAnyPost)
	$Query .= ' AND news_posts.AuthorID=' . $LoggedInUserId;

$Query .= " AND VerificationCode = 'OK'";

$ResultSet = mysql_query($Query); 
$NumRecords = mysql_num_rows($ResultSet);

$RecStart = $AdminCommentsPerPage * ($ShowPage-1); 
$PageNavBar = ConstructPagingBar($_SERVER['PHP_SELF'].'?action=CommentsApproval', $NumRecords, $AdminCommentsPerPage, $ShowPage, $RecStart, $AdminCommentsPageBar, '', '');

DisplayGroupHeading("Approve Comments - Page $ShowPage");
?>
<BR>
<TABLE class="Admin">
	<TR>
		<TD class="FieldPrompt">
			<FORM action="<?=$AdminScript?>?action=CommentsApproval" method="post">
				Category <?= BuildCategoryDropdown('CatID', $RestrictCatId, false, true) ?>
				Approved <?= BuildApprovedDropdown('Approved', $Approved, true) ?>
				<INPUT class="but" type="submit" name="submit" value="Filter" />
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
				// Now process the resultset
				$PrevNewsID = -1;

				while ($row = mysql_fetch_array($ResultSet))
				{
					$NewsID = $row['NewsID'];
					$CommentID = $row['CommentID'];
					$Name = $row['Name'];
					$EmailAddress = $row['EmailAddress'];
					$Approved = $row['Approved'];
					$IPAddress = $row['IPAddress'];
					$CommentDateString = date($NewsDisplay_DateFormat, strtotime($row['CommentDateTime'])) . '&nbsp;' . date($NewsDisplay_TimeFormat, strtotime($row['CommentDateTime']));

					// New news article? Display the details
					if ($PrevNewsID != $NewsID)
					{
						$PrevNewsID = $NewsID;
						?>
						<TR>
							<TD class="NewsListNonSticky" colspan="2">
								<HR size="3" width="100%">
								<?=$row['Headline']?>
							</TD>
						</TR>
						<?
					}
					?>

					<TR>
						<FORM method="post" action="<?=$AdminScript?>?action=CommentsApproval">
							<TD class="NewsListNonSticky">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<TEXTAREA name="Comment" cols="48" rows="8"><?=$row['Comment']?></TEXTAREA>
							</TD>
							<TD class="C">
								Name: <?=$Name?><BR>
								Email: <?=$EmailAddress?><BR>
								IP=<?=$IPAddress?><BR>
								<?=$CommentDateString?></BR>
								(<?= ($Approved == "1" ? "Approved" : "Unapproved") ?>)<BR>
								<INPUT class="but" type="hidden" name="commentid" value="<?=$CommentID?>">
								<INPUT class="but" type="submit" name="Approve" value="Approve">
								<INPUT class="but" type="submit" name="Unapprove" value="Unapprove"><BR><BR>
								<INPUT class="but" type="submit" name="Update" value="Update">
								<INPUT class="but" type="submit" name="Delete" value="Delete" onClick="return confirm('Delete this comment?');">
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