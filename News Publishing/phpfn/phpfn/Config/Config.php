<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// ==============================================================================================================================
// MANDATORY CONFIGRATION SECTION - You MUST configure these values
// ==============================================================================================================================

// State whether Install.php is allowed to run. Set this to false once you have installed the application.
$AllowInstall = true;

// MySQL Database Information
$dbhost = 'localhost';									// Database server hostname (usually 'localhost')
$dbuser = 'XXXXX';									// Database user
$dbpass = 'XXXXX';									// Password for database user
$db = 'XXXXX';								// The database you've created for PHPFreeNews

// URL of your web site
$SiteDescription = "Your Website Description";			// Put your site name here
$SiteDomain = "http://www.phpfreenews.co.uk";			// Put your domain here. This should be ONLY the domain, no subfolders.
$AdminEmail = "webmaster@yourdomain.co.uk";				// Put your email address here
$NoReplyEmail = "no-reply@yourdomain.co.uk";			// Used as the from-address for emails which do not expect a reply

// How about we only show the important errors...
error_reporting  (E_ERROR | E_WARNING | E_PARSE);

// Enable these two lines for enhanced debugging (NOT for production use!!!)
// error_reporting  (E_ALL);
// ini_set("display_errors", "1");

// ==============================================================================================================================
// OPTIONAL CONFIGRATION SECTION - You MAY configure these values if you need to tweak functionality
// ==============================================================================================================================

// Has the TinyMCE Editor been integrated?
//		0=No (Default)
//		1=Yes
$UseTinyMCE = 0;

// Specify your main application directory, relative to your web root. Leading slash:YES, trailing slash:NO
$NewsDir = '/phpfn';										

// Specify your News Images directory, relative to your News directory. Leading slash:YES, trailing slash:NO
// CHMOD this folder to 777 or you will not be able to upload images to it.
$ImageDir = '/NewsImages';

// Maximum allowable file-size for uploaded images (in bytes)
$MaxImageFileSize = 40000;

// News display mode
//	1=Paged - show articles in pages, with a paging bar (Default)
//	2=Limited - Show a limited number of articles
//	3=By Date - Group articles by year and month
$NewsMode = 1;

// Auto-expand all years? Only applicable if $NewsMode=3
//		0=No (Default)
//		1=Yes
$AutoExpandAllYears = 0;

// Allow Online Version Check
//		0=No
//		1=Yes (Default)
$OnlineVersionCheck = 1;

// Include live postings in the News display?
//		0=No (Default)
//		1=Yes
$ExcludeLivePosts = 0;

// Include archived postings in the News display?
//		0=No
//		1=Yes (Default)
$ExcludeArchivedPosts = 1;

// Number of news posts to be displayed per page on the Headline scripts, and the number of page-links.
// Only effective if $NewsMode = 1 or 2
$NewsItemsPerPage = 10;
$NewsPageBar = 10;

// Text to show in Limited mode ($NewsMode = 2) when there are more news articles to display.
// $MoreNewsText1 is shown before the hyperlink; $MoreNewsText2 is the hyperlink; $MoreNewsText3 is shown after it.
// Only effective if there are more articles, and if $SuppressLimitedNewsMoreNewsLink <> 1
$MoreNewsText1 = '<HR><I>Some news articles have been archived. To see every article, click ';
$MoreNewsText2 = 'here';
$MoreNewsText3 = "</I>";

// Suppress the "items have been archived, click here" message in Limited mode ($NewsMode = 2)
//		0=No (Default)
//		1=Yes
$SuppressLimitedNewsMoreNewsLink = 0;

// Control how to show "long posts". See also $ReadMoreString below.
//		1=Display in the listing (instead of Short Posts)
//		2=Display via a "Read More..." URL, launching in the same window
//		3=Display via a "Read More..." URL, launching in the popup window (Default)
$FullNewsDisplayMode = 3;

// Force the news articles to be initially "collapsed".
//		0=Articles are not collapsed (Default)
//		1=Articles are collapsed (and can be expanded)
$InitiallyShowHeadlinesOnly = 0;

// Should we show a "twistie" beside the headline? (Ignored when notj showing collapsible articles)
//		0=No
//		1=Yes, show arrows
//		2=Yes, show a + and a - (Default)
$ShowTwistie = 2;

// Control how news articles are sorted on the main page
// In every case Sticky articles are shown first, with the same sort-criteria being applied.
//		1=Priority then date (descending) (Default)
//		2=Priority then date
//		3=Date (descending), then priority
//		4=Alphabetically by Headline.
$NewsDisplaySort = 1;

// Suppress forward-dated articles from display
//		0=All articles are displayed, even those whose date is in the future
//		1=Only show articles whose "news date and time" is in the past. (Default) (Default)
$SuppressForwardDatedArticles = 1;

// Show articles assigned to NO categories if no category-selection is specified at run-time
//		0=No (Default)
//		1=Yes
$ShowIfNoCat = 0;

// Default priority for new news articles
$NewArticleDefaultPriority = 5;

// ==============================================================================================================================

// Control the popup window. Only required if $FullNewsDisplayMode = 3
$PopupWidth = 700;
$PopupHeight = 400;

// String literal for the {timesread} code. %s will be replaced with the number of times an article has been read.
$TimesReadString = '(Read %s times)';

// Number of news items to offer in your RSS feed (detail-feed and headline-feed). 0 = unlimited.
$RSSNewsItems = 20;

// Literal for "no news..."
$NoNews = '<CENTER>Sorry, there are no news articles available to display</CENTER>';

// ==========================================================================================================

// Do articles require approval before they appear?
// If they do, articles automatically become un-approved again when they are edited, and must be re-approved.
//		0=No (Default)
//		1=Yes
$ArticlesRequireApproval = 0;

// Number of news comments to be displayed per page on the Admin Articles Approval, and the number of page-links.
$AdminArticleApprovalPerPage = 10;
$AdminArticleApprovalPageBar = 10;

// ==============================================================================================================================

// Number of news posts to be displayed per page on the Admin scripts, and the number of page-links.
$AdminNewsPerPage = 10;
$AdminPageBar = 10;

// Date to show on the admin "news list" page - original creation date, or article's current date
//		1=Show orginal date and time
//		2=Show the post's current date and time (Default)
$AdminNewsListDateTime = 2;

// Number of text-columns to show in the admin Template and News maintenance screens
$AdminTextareaColumns = 60;

// ==============================================================================================================================

// Enable News Purge?
//		0=No
//		1=Yes (Default)
$EnableNewsPurge = 1;

// Default number of days for the "purge" function (only used of $EnablePurge = true)
$DefaultNewsPurgeDays = 180;

// ==============================================================================================================================

// Enable Archive function?
//		0=No
//		1=Yes (Default)
$EnableArchive = 1;

// Default number of days for the "archive" function (only used of $EnableArchive = true)
$DefaultArchiveDays = 60;

// ==============================================================================================================================

// Enable Audit functionality?
//		0=No
//		1=Yes (Default)
$EnableAudit = 1;

// Default number of days for the "audit purge" function (only used of $EnableAudit = true)
$DefaultAuditPurgeDays = 60;

// Number of audit-events to be displayed per page on the Admin scripts, and the number of page-links.
$AdminAuditEventsPerPage = 50;
$AdminAuditEventsPageBar = 10;

// Number of images to be displayed per page on the Admin Image-maintenance script, and the number of page-links.
$AdminImagesPerPage = 3;
$AdminImagesPageBar = 10;

// ==============================================================================================================================

// PHP format string for setting how date & time are displayed in the public-facing scripts
// Refer to http://us4.php.net/manual/en/function.date.php for usage
$NewsDisplay_DateFormat = 'l, jS F Y';
$NewsDisplay_TimeFormat = 'g:iA';

// Offset (in hours) of your server's time from your local time
$ServerTimeOffset = 0;

// Control how the "search..." button is displayed
$SearchPromptText = 'Search for News...';
$SearchButtonText = 'Search';

// Control how the "filter..." button is displayed
$FilterButtonText = 'Filter';

// String literal for the "Read More..." text, if $FullNewsDisplayMode = 2 or 3, and there's Long Text
$ReadMoreString = "...Read more";

// Control whether or not the user is allowed to search by category
//		0=No
//		1=Yes (Default)
$SearchByCategory = 1;

// Control the search mode
//		1=Search on the short-text field only
//		2=Search on the long-text field only
//		3=Search on the short-text field and the long-text field
//		4=Search on the headline, the short-text field and the long-text field (Default)
$SearchFieldControl = 4;

// Control whether or not the user is allowed to modify the time when updating news postings
//		0=No
//		1=Yes (Default)
$AllowTimeStampUpdate = 1;

// Control whether or not the datestamp of a news item is updated to the current time when updating news postings
//		0=No (Default)
//		1=Yes
$AutoUpdateTimeStampUponEdit = 0;

// Control whether or not news items which are copied retain their original posting date and time
//		0=No - copied posts are set to the current system time (Default)
//		1=Yes - copied posts retain their original time
$CopiedPostsRetainTime = 0;

// Fields to control the appearance of the Admin site
$WelcomeMessage = 'Welcome';
$AdminSiteLogo = 'Logo.gif';

// Control whether or not to encode mailto URLs
//		0=No - leave them as plaintext (Default)
//		1=Yes - encode them as hex codes
$ObfuscateMailtoURLs = 0;

//	Control whether or not to auto-encode URLs (without requiring [email] or [www] BBCodes)
//		0=No - leave them as plaintext
//		1=Yes - encode them as hex codes (Default)
$AutoEncodeURLs = 1;

// Email addresses for Posts notification. Leave as empty strings to disable notification.
// NB Please note that these addresses will not be validated.
$EmailAddressNotifyPostAdded = '';								// e.g. 'webmaster@xx.com', or 'user1@xx.com,user2@xx.com'
$EmailAddressNotifyPostChanged = '';
$EmailAddressNotifyPostDeleted = '';
$EmailAddressNotifyVisibleChanged = '';
$EmailAddressNotifyStickyChanged = '';
$EmailAddressNotifyLockedChanged = '';

// ==========================================================================================================

// Control whether or not to allow spell-checking facilities
//		0=No
//		1=Yes (Default)
$EnableSpellCheck = 1;

// If spell-checking is enabled, state which language is to be used (ignored if $EnableSpellCheck = 0)
// See ISO 639
$SpellCheckLanguage = 'en';

// If spell-checking is enabled, state the minimum word-length to be checked (ignored if $EnableSpellCheck = 0)
$SpellCheckMinWordLength = 3;

// ==========================================================================================================

// Enable or Disable the Ratings system
//		0=No
//		1=Yes (Default)
$EnableRatings = 1;

// String literals for the {rating} code. Has no effect unless $EnableRatings = 1
// The first %s will be replaced with the average rating.
// The second %s will be replaced with the number of votes.
// The third %s will be replaced with the Voting string, and will use $RatingVoteString.
$RatingString = "(Average rating %s from %s votes. %s)";
$RatingVoteString = "Vote";

// Maximum rating (e.g. 10 for 1-10). Has no effect unless $EnableRatings = 1
$MaxRating = 10;

// Allow or disallow multiple votes from the same IP address for the same article
//		0=Disallow (Default)
//		1=Allow
$AllowDuplicateRating = 0;

// ==========================================================================================================

// Enable or Disable the Comments system
//		0=No
//		1=Yes (Default)
$EnableComments = 1;

// String literals for the {comments} code. Has no effect unless $EnableComments = 1
// The %s will be replaced with the number of comments for the article.
$CommentsString = "(%s Comments)";

// Allow or disallow multiple comments from the same IP address for the same article
//		0=Disallow
//		1=Allow (Default)
$AllowDuplicateComments = 1;

// Do comments require verification by the contributor before they appear?
//		0=No
//		1=Yes (Default)
$CommentsRequireVerification = 1;

// Do comments require approval by the news-site staff before they appear?
//		0=No
//		1=Yes (Default)
$CommentsRequireApproval = 1;

// Number of news comments to be displayed per page on the Admin Comments Approval, and the number of page-links.
$AdminCommentsPerPage = 10;
$AdminCommentsPageBar = 10;

// ==============================================================================================================================
// "DO NOT TOUCH" CONFIGURATION SECTION - You SHOULD NOT change anything beneath this line, else it'll break!
// ==============================================================================================================================

$ScriptVersion = '1.57';
$MonthNames = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$WWW = $SiteDomain . $NewsDir;

define('IN_PHPFN', 'Y');

define ("AUDIT_TYPE_ARTICLE", 1);
define ("AUDIT_TYPE_STICKY", 2);
define ("AUDIT_TYPE_VISIBLE", 3);
define ("AUDIT_TYPE_CATEGORY", 4);
define ("AUDIT_TYPE_TEMPLATE", 5);
define ("AUDIT_TYPE_IMAGE", 6);
define ("AUDIT_TYPE_USER", 7);
define ("AUDIT_TYPE_PASSWORD", 8);
define ("AUDIT_TYPE_LOGIN", 9);
define ("AUDIT_TYPE_USERDEFCODE", 10);
define ("AUDIT_TYPE_ARTICLEAPPROVAL", 11);
define ("AUDIT_TYPE_LOCKED", 12);

// Establish the database connection
mysql_connect($dbhost, $dbuser, $dbpass) or die("Could not connect : " . mysql_error());
mysql_select_db($db) or die('Could not select database ' . $db . ', please check your configuration.');
?>