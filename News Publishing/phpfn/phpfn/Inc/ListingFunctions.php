<?

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// ==============================================================================================================================

function LimitedListing()
{
	global $NewsItemsPerPage, $CatID, $Match, $SuppressLimitedNewsMoreNewsLink, $MoreNewsText1, $MoreNewsText2, $MoreNewsText3;

	if (isset($_GET['ShowAll']))
		$ShowAll = $_GET['ShowAll'];
	else
		$ShowAll = NULL;

	// Define the query
	$Query = BuildListingSQL();

	// Determine the number of records available
	$ResultSet = mysql_query($Query);
	$NumRecords = mysql_num_rows($ResultSet);

	// Apply limits?
	if ($ShowAll != 'Y')
		$Query .= " LIMIT $NewsItemsPerPage";

	// Now obtain the resultset, and show the articles or the headlines
	$ResultSet = mysql_query($Query) or die('Query failed : ' . mysql_error());
	ListNewsItems(min($NumRecords, $NewsItemsPerPage), $ResultSet);

	// More articles to display? Only if we're not suppressing it...
	if ( ($ShowAll != 'Y')  && ($NumRecords > $NewsItemsPerPage) && ($SuppressLimitedNewsMoreNewsLink != 1))
	{
		// Was a Match or category specified?
		$MatchArg = ($Match != NULL ? "&Match='$Match'" : '');
		$CatArg = ($CatID != NULL && $CatID != '0' ? "&CatID=$CatID" : '');

		$Hyperlink = '<A href="' . $_SERVER['PHP_SELF'] . '?ShowAll=Y' . $MatchArg . $CatArg . '">' . $MoreNewsText2 . "</A>";
		echo $MoreNewsText1 . $Hyperlink . $MoreNewsText3;
	}
}

// ==============================================================================================================================

function PagedListing()
{
	global $NewsItemsPerPage, $NewsPageBar, $ShowPage, $CatID, $Match;

	// Restrict by category or records in the GET variable? This will override any variables already set
	if (isset($_GET['ShowPage']))
		$ShowPage = $_GET['ShowPage'];

	// No start page? Start at page 1
	if (! isset($ShowPage))
		$ShowPage = 1;

	// Define the query
	$Query = BuildListingSQL();

	// Determine the number of records available, and work out the number of pages
	$ResultSet = mysql_query($Query);
	$NumRecords = mysql_num_rows($ResultSet);

	$RecStart = $NewsItemsPerPage * ($ShowPage-1);
	$PageNavBar = ConstructPagingBar($_SERVER['PHP_SELF'], $NumRecords, $NewsItemsPerPage, $ShowPage, $RecStart, $NewsPageBar, $CatID, $Match);

	// Now obtain the resultset, and show the articles
	$Query .= " LIMIT $RecStart, $NewsItemsPerPage";
	$ResultSet = mysql_query($Query) or die('Query failed : ' . mysql_error());

	ListNewsItems(min($NumRecords, $NewsItemsPerPage), $ResultSet);

	// Show the bar?
	if ($NumRecords > $NewsItemsPerPage)
		echo "<HR><CENTER>$PageNavBar</CENTER>";
}

// ==============================================================================================================================

function YearMonthListing()
{
	global $NewsItemsPerPage, $CatID, $Match, $AutoExpandAllYears;

	$ShowYear = (isset($_GET['Y'])) && (is_numeric($_GET['Y'])) ? $_GET['Y'] : "";
	$ShowMonth = (isset($_GET['M'])) && (is_numeric($_GET['M'])) ? $_GET['M'] : "";

	// Build an array of the news articles in each year
	$ArticlesPerYear = CountArticlesPerYear();

	while (list($Year, $PostTotal) = each($ArticlesPerYear))
	{
		?>
		&nbsp;&nbsp;<A href="<?= $_SERVER['PHP_SELF']?>?Y=<?=$Year?>"><?=$Year?> (<?=$PostTotal?> Article<?=$PostTotal > 1 ? "s" : "" ?>)</A><BR>
		<?php

		// Expand months for a given year?
		if ($Year == $ShowYear || $AutoExpandAllYears)
		{
			$ArticlesPerMonth = CountArticlesPerMonthForYear($Year);
			while (list($Month, $PostTotal) = each($ArticlesPerMonth))
			{
				$MonthName = date("F", strtotime($Year . "-" . $Month . "-01"));
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;<A href="<?= $_SERVER['PHP_SELF']?>?Y=<?=$Year?>&M=<?=$Month?>"><?=$MonthName?> (<?=$PostTotal?> Article<?=$PostTotal > 1 ? "s" : "" ?>)</A><BR>
				<?php

				// Expand articles for a given month and year?
				if (($Year == $ShowYear) && ($Month == $ShowMonth))
				{
					// Define the query
					$query = BuildListingSQL(" AND YEAR(PostDateTime) = '$ShowYear' AND MONTH(PostDateTime) = '$ShowMonth'");

					// Determine the number of records available
					$ResultSet = mysql_query($query);
					$NumRecords = mysql_num_rows($ResultSet);

					// Now obtain the resultset, and show the articles or the headlines
					$ResultSet = mysql_query($query) or die('Query failed : ' . mysql_error());
					ListNewsItems($NumRecords, $ResultSet);
					echo "<P></P>";
				}
			}
		}
	}
}

// ==============================================================================================================================

// Function: BuildListingSQL -- Build the basic listing SQL
function BuildListingSQL($AdHocClause = "")
{
	global $NewsDisplaySort;

	$Query = "SELECT DISTINCT news_posts.*, news_users.FullName FROM news_posts, news_users";

	// Apply any filters
 	$Query .= ApplyFilters();
	$Query .= " AND news_posts.AuthorID = news_users.ID";

	// Add any ad-hoc WHERE rules
	$Query .= $AdHocClause;

	// Apply the sort-criteria
	switch($NewsDisplaySort)
	{
		case 1:
			$Query .= ' ORDER BY Sticky DESC, Priority, PostDateTime DESC, ID DESC';
			break;
		case 2:
			$Query .= ' ORDER BY Sticky DESC, Priority, PostDateTime, ID DESC';
			break;
		case 3:
			$Query .= ' ORDER BY Sticky DESC, PostDateTime DESC, Priority, ID DESC';
			break;
		case 4:
			$Query .= ' ORDER BY Headline';
			break;
	}

	return $Query;
}

// ==============================================================================================================================

// Function: ListNewsItems -- Display the news article(s)
function ListNewsItems($NumRecords, $ResultSet)
{
	global $NoNews, $FullNewsDisplayMode;

	// No news?
	if ($NumRecords == 0)
	{
		echo $NoNews;
		return;
	}

	// Are we using Short or Long text?
	$TemplateMode = ($FullNewsDisplayMode == 1 ? 'L' : 'S');

	// Load the user-defined codes (only do once)
	$UserCodes = BuildUserDefinedCodesList();

	// Process the articles
	$PrevTemplateID = 0;
	$TemplateHeadlineContents = "";
	$TemplateBodyContents = "";
	while ($NewsRow = mysql_fetch_array($ResultSet, MYSQL_ASSOC))
	{
		$TemplateID = $NewsRow['TemplateID'];

		// Only read the template if it differs to the template for the previous article
		if ($TemplateID != $PrevTemplateID)
		{
			$TemplateHeadlineContents = ReadTemplate($TemplateID, "H");
			$TemplateBodyContents = ReadTemplate($TemplateID, $TemplateMode);
			$PrevTemplateID = $TemplateID;
		}

		// Output the article
		OutputArticle($NewsRow, $TemplateHeadlineContents, $TemplateBodyContents, $UserCodes);
	}
}

// ==============================================================================================================================

// Function: OutputArticle -- Send the article to the HTML page
function OutputArticle($NewsRow, $TemplateHeadlineContents, $TemplateBodyContents, $UserCodes)
{
	global $NewsDir, $FullNewsDisplayMode, $EnableRatings, $EnableComments, $InitiallyShowHeadlinesOnly, $ShowTwistie;

	$ArticleID = $NewsRow['ID'];
	$Headline = $NewsRow['Headline'];
	$ShortPost = $NewsRow['ShortPost'];
	$LongPost = $NewsRow['LongPost'];
	$DivCollapse = '';
	$LongPostExists = ($LongPost != '');
	$OriginalHeadline = $Headline;
	$Categories = CategoriesFromDB($ArticleID);

	// Are we to show a Twistie around the headline?
	if ($InitiallyShowHeadlinesOnly == 1)
	{
		if ($ShowTwistie == 1)
			$Headline = "<font ext$ArticleID='yes' face=Webdings color=blue>4</font>" . $Headline;
		if ($ShowTwistie == 2)
			$Headline = "<font ext$ArticleID='yes' color=blue>+</font>" . $Headline;
	}

	// Output the header
	$Contents = ParseTemplateCodes($ArticleID, $TemplateHeadlineContents, $NewsRow['PostDateTime'], $NewsRow['FullName'], $Headline, $NewsRow['TimesRead'], $NewsRow['AllowComments'], true, $LongPostExists, $Categories);
	echo $Contents;

	// Are we to show the news collapsed, only displaying headlines? If so, wrap a DIV around the whole body template
	if ($InitiallyShowHeadlinesOnly == 1)
	{
		$DivCollapse = " ext$ArticleID='yes' style='display:none'";
		$TemplateBodyContents = "<div $DivCollapse>" . $TemplateBodyContents . "</div>";
	}

	// Begin to build the short-post (the main body)
	$Contents = ParseTemplateCodes($ArticleID, $TemplateBodyContents, $NewsRow['PostDateTime'], $NewsRow['FullName'], $OriginalHeadline, $NewsRow['TimesRead'], $NewsRow['AllowComments'], false, $LongPostExists, $Categories);
	$Contents = BuildImage($Contents, $NewsRow['ImageID']);

	// Parse the user-defined codes for the short-post, and output the article
	$ParsedBody = ParseUserDefinedCodes($UserCodes, $ShortPost);
	$ParsedBody = ParseBBCodes($ParsedBody);
	$Contents = str_replace("{news}", $ParsedBody, $Contents);
	echo $Contents;

	// Are we to show the long post?
	if ( ($LongPostExists) && ($FullNewsDisplayMode == 1) )
	{
		$ParsedBody = ParseUserDefinedCodes($UserCodes, $LongPost);
		$ParsedBody = ParseBBCodes($ParsedBody);
		echo "<$DivCollapse><BR>" . $ParsedBody . "</div>";
	}
}

// ==============================================================================================================================

// Function: ShowNewsArticle -- Display a full news article
function OutputArticleLong($ArticleID)
{
	global $FullNewsDisplayMode;

	// Load the user-defined codes (only do once)
	$UserCodes = BuildUserDefinedCodesList();

	// Define the query
	$query = "SELECT news_posts.*, news_users.FullName".
				" FROM news_posts, news_users".
				" WHERE news_posts.AuthorID = news_users.ID AND news_posts.ID = $ArticleID";

	$newsresult = mysql_query($query) or die('Query failed : ' . mysql_error());

	// Process the data
	if ($NewsRow = mysql_fetch_array($newsresult, MYSQL_ASSOC))
	{
		// Update the "times read" statistic
		mysql_query("UPDATE news_posts SET TimesRead = TimesRead + 1 WHERE ID = $ArticleID");

		$ArticleID = $NewsRow['ID'];
		$Sticky = $NewsRow['Sticky'];
		$Headline = $NewsRow['Headline'];
		$ShortPost = $NewsRow['ShortPost'];
		$LongPost = $NewsRow['LongPost'];
		$ImageID = $NewsRow['ImageID'];
		$TemplateID = $NewsRow['TemplateID'];
		$TimesRead = $NewsRow['TimesRead'] + 1;			// Incremented by SQL *AFTER* it was read
		$LongPostExists = ($LongPost != '');
		$Categories = CategoriesFromDB($ArticleID);

		// Load the template
		$TemplateMode = ($LongPost != '' ? 'L' : 'S');
		$TemplateHeadlineContents = ReadTemplate($TemplateID, "H");
		$TemplateBodyContents = ReadTemplate($TemplateID, $TemplateMode);

		// Output the header
		$Contents = ParseTemplateCodes($ArticleID, $TemplateHeadlineContents, $NewsRow['PostDateTime'], $NewsRow['FullName'], $Headline, $NewsRow['TimesRead'], $NewsRow['AllowComments'], true, $LongPostExists, $Categories);
		echo $Contents;

		// Now build the body
		$Contents = ParseTemplateCodes($ArticleID, $TemplateBodyContents, $NewsRow['PostDateTime'], $NewsRow['FullName'], $Headline, $TimesRead, $NewsRow['AllowComments'], false, $LongPostExists, $Categories);
		$Contents = BuildImage($Contents, $ImageID);

		// Parse the article
		if ($LongPostExists)
			$ParsedBody = ParseUserDefinedCodes($UserCodes, $LongPost);
		else
			$ParsedBody = ParseUserDefinedCodes($UserCodes, $ShortPost);

		// Parse the BB codes and write the article
		$ParsedBody = ParseBBCodes($ParsedBody);
		$Contents = str_replace('{news}', $ParsedBody, $Contents);
		echo $Contents;
	}
}
?>