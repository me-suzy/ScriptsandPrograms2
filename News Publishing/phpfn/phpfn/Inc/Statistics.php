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

$query					= mysql_query("SELECT count(*) FROM news_posts");
$newscount				= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_posts WHERE Visible='1'");
$visiblenewscount		= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_posts WHERE Approved !='1'");
$unapprovednewscount	= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_posts WHERE Archived='1'");
$archivednewscount		= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_posts WHERE AuthorID=$LoggedInUserId");
$mynewscount			= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_comments");
$commentcount			= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_comments WHERE Approved !='1'");
$unapprovedcommentcount	= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_images");
$imagecount				= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_users");
$usercount				= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_categories");
$catcount				= mysql_result($query, 0);

$query					= mysql_query("SELECT count(*) FROM news_templates");
$templatecount			= mysql_result($query, 0);

$query					= mysql_query("SELECT TimesRead, Headline FROM news_posts ORDER BY TimesRead DESC LIMIT 1");
$row 					= mysql_fetch_array($query, MYSQL_ASSOC);
$mostreadheadline 		= $row['Headline'];
$mostreadcount 			= $row['TimesRead'];

// Display the heading
DisplayGroupHeading('System Statistics');
?>
	<BR><BR>
	<TABLE align="center" border="1" width="90%" cellspacing="0" cellpadding="3">
		<TR>
			<TH width="15%" nowrap>
				<B>Total Posts:</B>
			</TH>
			<TD>
				<?= $newscount ?> (<?=$visiblenewscount?> visible, <?=$unapprovednewscount?> unapproved)
			</TD>
		</TR>

		<TR>
			<TH width="15%" nowrap>
				<B>Archived Posts:</B>
			</TH>
			<TD>
				<?= $archivednewscount ?>
			</TD>
		</TR>

		<TR>
			<TH width="15%" nowrap>
				<B>Your Posts:</B>
			</TH>
			<TD>
				<?= $mynewscount ?>
			</TD>
		</TR>

		<?
		if ($EnableComments)
		{
			?>
			<TR>
				<TH width="15%" nowrap>
					<B>Comments:</B>
				</TH>
				<TD>
					<?= $commentcount ?> (<?=$unapprovedcommentcount?> unapproved)
				</TD>
			</TR>
			<?
		}
		?>

		<TR>
			<TH width="15%" nowrap>
				<B>Images:</B>
			</TH>
			<TD>
				<?= $imagecount ?>
			</TD>
		</TR>

		<TR>
			<TH width="15%" nowrap>
				<B>Users:</B>
			</TH>
			<TD>
				<?= $usercount ?>
			</TD>
		</TR>

		<TR>
			<TH width="15%" nowrap>
				<B>Categories:</B>
			</TH>
			<TD>
				<?= $catcount ?>
			</TD>
		</TR>

		<TR>
			<TH width="15%" nowrap>
				<B>Templates:</B>
			</TH>
			<TD>
				<?= $templatecount ?>
			</TD>
		</TR>

		<TR>
			<TH width="15%" nowrap>
				<B>Most-Read Article:</B>
			</TH>
			<TD>
				<?= $mostreadheadline ?> (Viewed <?= $mostreadcount ?> times)
			</TD>
		</TR>
	</TABLE>