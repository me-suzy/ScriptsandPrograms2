<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// ==============================================================================================================================

function MySql4()
{
	return (intval(mysql_get_server_info()) >= 4);
}

// ==============================================================================================================================

function GetHeadline($ArticleID)
{
	if ($ArticleID == null)
		die('Error - no news article specified!');

	if ($ArticleID == -1)
		return "";

	// Get the headline
	$query = "SELECT Headline FROM news_posts WHERE ID = '$ArticleID'";

	$newsresult = mysql_query($query) or die('Query failed : ' . mysql_error());
	$newsrow = mysql_fetch_array($newsresult, MYSQL_ASSOC);

	if (!$newsrow)
	        die("News article $ArticleID was not found!");

	// Process the data
	return $newsrow['Headline'];
}

// ==============================================================================================================================

function GetArticleTemplateID($ArticleID)
{
	if ($ArticleID == null)
		die('Error - no news article specified!');

	// Get the headline
	$query = "SELECT TemplateID FROM news_posts WHERE ID = '$ArticleID'";

	$newsresult = mysql_query($query) or die('Query failed : ' . mysql_error());
	$newsrow = mysql_fetch_array($newsresult, MYSQL_ASSOC);

	if (!$newsrow)
	        die("News article $ArticleID was not found!");

	// Process the data
	return $newsrow['TemplateID'];
}
// ==============================================================================================================================

function GetRemoteIP()
{
	if (isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']) && eregi("^[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}$", $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']))
		return $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
	else
		return getenv('REMOTE_ADDR');
}

// ==============================================================================================================================

function ReadTemplate($TemplateID, $TemplateMode)
{
	$Contents = "";

	$result = mysql_query("SELECT * FROM news_templates WHERE ID = '$TemplateID'");
	$row = mysql_fetch_array($result);
	if (!$row)
		die("Could not retrieve template!");

	// Return the data
	if ($TemplateMode == 'S')
		$Contents = $row['ShortPost'];
	elseif ($TemplateMode == 'H')
		$Contents = $row['Headline'];
	elseif ($TemplateMode == 'T')
		$Contents = $row['Comments'];
	else
		$Contents = $row['LongPost'];

	return $Contents;
}

// ==============================================================================================================================

// Function: Replace template codes with actual values
function ParseTemplateCodes($ArticleID, $TemplateContents, $PostDateTime, $PostAuthor, $Headline, $TimesRead, $AllowComments, $ApplyOnClickCode, $LongPostExists, $Categories)
{
	global $NewsDir, $TimesReadString, $ReadMoreString, $NewsDisplay_DateFormat, $NewsDisplay_TimeFormat, $EnableRatings, $RatingString, $RatingVoteString, $EnableComments, $CommentsString, $FullNewsDisplayMode, $InitiallyShowHeadlinesOnly;

	// Convert the date and time to the user-specified format
	$PostDate = date($NewsDisplay_DateFormat, strtotime($PostDateTime));
	$PostTime = date($NewsDisplay_TimeFormat, strtotime($PostDateTime));
	
	$TimesReadStringComplete = sprintf($TimesReadString, number_format($TimesRead));
	$Contents = $TemplateContents;

	// Now parse the special tags
	if ( $ApplyOnClickCode && $InitiallyShowHeadlinesOnly == 1 )
		$Contents = str_replace('{headline}', "<DIV id=exp$ArticleID onClick=\"exp(event, 'div', 'ext$ArticleID');\">" . nl2br($Headline) . "</DIV>", $TemplateContents);
	else
		$Contents = str_replace('{headline}', nl2br($Headline), $TemplateContents);

	$Contents = str_replace('{author}', $PostAuthor, $Contents);
	$Contents = str_replace('{newsdate}', $PostDate, $Contents);
	$Contents = str_replace('{newstime}', $PostTime, $Contents);
	$Contents = str_replace('{timesread}', $TimesReadStringComplete, $Contents);
	$Contents = str_replace('{id}', $ArticleID, $Contents);
	$Contents = str_replace('{categories}', GetCategoryNames($Categories), $Contents);

	// Does a long-post exist? If so, parse the {readmore} tag
	if ($LongPostExists)
	{
		switch ($FullNewsDisplayMode)
		{
			case 1:
				$Contents = str_replace('{readmore}', '', $Contents);
				break;
			case 2:
				$Repl = "<a href=\"$NewsDir/View.php?ArticleID=$ArticleID\">$ReadMoreString</a>";
				$Contents = str_replace('{readmore}', $Repl, $Contents);
				break;
			case 3:	
				$Repl = "<a href=\"javascript:ViewArticle('$ArticleID')\">$ReadMoreString</a>";
				$Contents = str_replace('{readmore}', $Repl, $Contents);
				break;
		}
	}
	else
		$Contents = str_replace('{readmore}', '', $Contents);

	// Build a rating string, if ratings are enabled
	if ($EnableRatings == 1)
	{
		if ($ArticleID != -1)
		{
			// Find the number of votes, and the average vote.
			$sql = "SELECT COUNT(*) AS NumVotes, AVG(Rating) AS AverageRating FROM news_ratings WHERE ArticleID='$ArticleID' GROUP BY ArticleID";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$NumVotes = $row['NumVotes'];
			$AverageRating = $row['AverageRating'];
			$RatingVoteStringComplete = "<A href=\"javascript:Vote($ArticleID)\">" . $RatingVoteString . "</A>";
			$RatingStringComplete = sprintf($RatingString, number_format($AverageRating), number_format($NumVotes), $RatingVoteStringComplete);
		}
		else
		{
			$RatingStringComplete = sprintf($RatingString, number_format('5'), number_format('99999'), $RatingVoteString);
		}
		$Contents = str_replace('{rating}', $RatingStringComplete, $Contents);
	}
	else
		$Contents = str_replace('{rating}', "", $Contents);

	// Build a Comments string, if comments are enabled
	if ($EnableComments)
	{
		// Find the number of comments
		$sql = "SELECT COUNT(*) AS NumComments FROM news_comments WHERE ArticleID='$ArticleID' AND VerificationCode = 'OK' AND Approved = '1'";
		$result = mysql_query($sql);
		$NumComments = mysql_result($result, 0, 'NumComments');

		// If comments are allowed, OR not allowed but comments already exists, then show the link
	 	if (($AllowComments) || ($NumComments != 0))
		{
			if ($ArticleID != -1)
				$CommentStringComplete = "<A href=\"javascript:Comments($ArticleID)\">" . sprintf($CommentsString, number_format($NumComments)) . "</A>";
			else
				$CommentStringComplete =  sprintf($CommentsString, number_format($NumComments));
			$Contents = str_replace('{comments}', $CommentStringComplete, $Contents);
		}
		else
			$Contents = str_replace('{comments}', "", $Contents);
	}
	else
		$Contents = str_replace('{comments}', "", $Contents);

	return $Contents;
}

// ==============================================================================================================================

// Function: Build Image
function BuildImage($Contents, $ImageID)
{
	global $ImageDir, $NewsDir;

	// No image?
	if ($ImageID == "0" OR $ImageID == "") 
	{
		$Contents = str_replace('{image}', '', $Contents);
		$Contents = str_replace('{imagel}', '', $Contents);
		$Contents = str_replace('{imagec}', '', $Contents);
		$Contents = str_replace('{imager}', '', $Contents);
	}
	else
	{
		$imagedata = mysql_query("SELECT * FROM news_images WHERE ID=$ImageID");
		$imagedata = mysql_fetch_array($imagedata);
		$ImageName = $imagedata['ImageName'];
		$ImageFilename = $imagedata['ImageFilename'];

		$imagecode = "<img src=\"$NewsDir$ImageDir" . "/$ImageFilename\" border=\"0\" alt=\"$ImageName\"";

		// Perform the substitutions
		$Replace = $imagecode . '>';
		$Contents = str_replace('{image}', $Replace, $Contents);

		$Replace = $imagecode . ' align="left">';
		$Contents = str_replace('{imagel}', $Replace, $Contents);

		$Replace = $imagecode . ' align="center">';
		$Contents = str_replace('{imagec}', $Replace, $Contents);

		$Replace = $imagecode . ' align="right">';
		$Contents = str_replace('{imager}', $Replace, $Contents);
	}

	return $Contents;
}

// ==============================================================================================================================

// Function: Replace Custom Codes
function ParseUserDefinedCodes ($UserCodes, $Contents)
{
	while (list($key, $val) = each($UserCodes))
		$Contents = str_replace($key, $val, $Contents);
	return $Contents;
}

// ==============================================================================================================================

// Function: ParseBBCodes (IMPORTANT - keep this method and StripBBCodes in sync, if adding new BB codes)
function ParseBBCodes ($Contents) 
{
	global $ObfuscateMailtoURLs, $AutoEncodeURLs;

	$Contents = preg_replace("/\[b\](.*?)\[\/b\]/si", "<b>\\1</b>", $Contents);
	$Contents = preg_replace("/\[i\](.*?)\[\/i\]/si", "<i>\\1</i>", $Contents);
	$Contents = preg_replace("/\[u\](.*?)\[\/u\]/si", "<u>\\1</u>", $Contents);
	$Contents = preg_replace("/\[p\](.*?)\[\/p\]/si", "<p>\\1</p>", $Contents);
	$Contents = preg_replace("/\[code\](.*?)\[\/code\]/si", "<blockquote><pre>\\1</pre></blockquote>", $Contents);
	$Contents = preg_replace("/\[quote\](.*?)\[\/quote\]/si", "<blockquote>\\1</blockquote>", $Contents);
	$Contents = preg_replace("/(^|\s)(http:\/\/)(.*?)(\s|\n|$)/si", "\\1<a href=\"\\2\\3\">\\2\\3</a>", $Contents);
	$Contents = preg_replace("/(^|\s)(www\.)(.*?)(\s|\n|$)/si", "\\1<a href=\"http://\\2\\3\">\\2\\3</a>", $Contents);
	$Contents = preg_replace("/\[url\](http|https|ftp)(:\/\/\S+?)\[\/url\]/si", "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>", $Contents);
	$Contents = preg_replace("/\[url\](\S+?)\[\/url\]/si", "<a href=\"http://\\1\" target=\"_blank\">\\1</a>", $Contents);
	$Contents = preg_replace("/\[url=(http|https|ftp)(:\/\/\S+?)\](.*?)\[\/url\]/si", "<a href=\"\\1\\2\" target=\"_blank\">\\3</a>", $Contents);
	$Contents = preg_replace("/\[url=(\S+?)\](\S+?)\[\/url\]/si", "<a href=\"http://\\1\" target=\"_blank\">\\2</a>", $Contents); 
	$Contents = preg_replace("/\[url2\](http|https|ftp)(:\/\/\S+?)\[\/url2\]/si", "<a href=\"\\1\\2\">\\1\\2</a>", $Contents);
	$Contents = preg_replace("/\[url2\](\S+?)\[\/url2\]/si", "<a href=\"http://\\1\">\\1</a>", $Contents);
	$Contents = preg_replace("/\[url2=(http|https|ftp)(:\/\/\S+?)\](.*?)\[\/url2\]/si", "<a href=\"\\1\\2\">\\3</a>", $Contents);
	$Contents = preg_replace("/\[url2=(\S+?)\](\S+?)\[\/url2\]/si", "<a href=\"http://\\1\">\\2</a>", $Contents);

	// Replace any manually-keyed mailto text (e.g. [email]address[/email] and [email=address[/email]
	$Contents = preg_replace("/\[email\](\S+?@\S+?\\.\S+?)\[\/email\]/si", "<a href=\"mailto:\\1\">\\1</a>", $Contents);
	$Contents = preg_replace("/\[email=(\S+?@\S+?\\.\S+?)\](.*?)\[\/email\]/si", "<a href=\"mailto:\\1\">\\2</a>", $Contents);

	// Auto-encode any "lazy" emails?
	if ($AutoEncodeURLs)
		$Contents = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $Contents);

	// Auto-scramble the URLS?
	if ($ObfuscateMailtoURLs)
		$Contents = preg_replace_callback("|\"mailto\:(.*?)\"|", "EncodeMailtoHex", $Contents); 

	$Contents = preg_replace("/\[img\](\S+?)\[\/img\]/si", "<img src=\"\\1\" border=0 alt=\"\\1\">", $Contents);
 	$Contents = nl2br($Contents);
	return $Contents;
}

// ==============================================================================================================================

// Function: StripBBCodes (IMPORTANT - keep this method and ParseBBCodes in sync, if adding new BB codes!)
function StripBBCodes ($Contents) 
{
	$Contents = preg_replace("/\[b\](.*?)\[\/b\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[i\](.*?)\[\/i\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[u\](.*?)\[\/u\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[p\](.*?)\[\/p\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[code\](.*?)\[\/code\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[quote\](.*?)\[\/quote\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[url\](http|https|ftp)(:\/\/\S+?)\[\/url\]/si", "\\1\\2", $Contents);
	$Contents = preg_replace("/\[url\](\S+?)\[\/url\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[url=(http|https|ftp)(:\/\/\S+?)\](.*?)\[\/url\]/si", "\\1\\2", $Contents);
	$Contents = preg_replace("/\[url=(\S+?)\](\S+?)\[\/url\]/si", "\\1", $Contents); 
	$Contents = preg_replace("/\[url2\](http|https|ftp)(:\/\/\S+?)\[\/url2\]/si", "\\1\\2", $Contents);
	$Contents = preg_replace("/\[url2\](\S+?)\[\/url2\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[url2=(http|https|ftp)(:\/\/\S+?)\](.*?)\[\/url2\]/si", "\\1\\2", $Contents);
	$Contents = preg_replace("/\[url2=(\S+?)\](\S+?)\[\/url2\]/si", "\\1", $Contents);
	$Contents = preg_replace("/\[email\](\S+?@\S+?\\.\S+?)\[\/email\]/si", "\\1", $Contents); 
	$Contents = preg_replace("/\[email=(\S+?@\S+?\\.\S+?)\](.*?)\[\/email\]/si", "\\1", $Contents); 
	$Contents = preg_replace("/\[img\](\S+?)\[\/img\]/si", "", $Contents);
	return $Contents;
}

// ==============================================================================================================================

// Function EncodeMailtoHex: Construct an encoded email URL 
function EncodeMailtoHex($Data)
{
	$EncodedURL = '';

	// Convert the email link
	for ($i =0; $i < strlen($Data[1]); $i++)
		$EncodedURL .= '%' . dechex(ord(substr($Data[1], $i, 1)));

	return "mailto:" . $EncodedURL;
}

// ==============================================================================================================================

// Construct a paging bar
function ConstructPagingBar($ScriptName, $NumRecords, $ItemsPerPage, $ShowPage, $RecStart, $MaxPagesOnBar, $CatID, $Match)
{
	// If the script name already contains a ? then we need to use a &
	if (strstr($ScriptName, "?"))
		$Sep = "&amp;";
	else
		$Sep = "?";

	// Calculate the total number of pages
	$NumPages = ceil($NumRecords / $ItemsPerPage); 

	// Less pages available than we're to display? Easy calculation!
	if ($NumPages < $MaxPagesOnBar)
	{
		$ShowPage_start = 0;
		$ShowPage_end = $NumPages;
	}
	else
	{
		// Construct the FROM and TO pages to show in the navigation bar. We want to keep the "display" page in the middle of the range if we can
		$MidPoint = floor($MaxPagesOnBar / 2);
	
		if ( ($MaxPagesOnBar / 2) != floor($MaxPagesOnBar / 2) )		// Odd number
		{
			$MaxPagesToLeft = $MidPoint + 1;
			$MaxPagesToRight = $MidPoint;
		}
		else
		{
			$MaxPagesToLeft = $MidPoint;
			$MaxPagesToRight = $MidPoint-1;
		}

		$ShowPage_start = max (0, $ShowPage-$MaxPagesToLeft);
		$ShowPage_end = $ShowPage_start + $MaxPagesOnBar;

		// Nearing the end of the pages?
		if ($ShowPage_end > $NumPages)
		{
			$ExtraPages = $ShowPage_end - $NumPages;
			if ($ShowPage_start > $ExtraPages)
				$ShowPage_start -= $ExtraPages;
		}
	}

	// Was a Match or category specified?
	$MatchArg = ($Match != NULL ? "&Match='$Match'" : '');
	$CatArg = ($CatID != NULL && $CatID != '0' ? "&CatID=$CatID" : '');

	// Construct the navigation bar
	$PageNavBar = "";
	for ($P = $ShowPage_start + 1; ($P <= $ShowPage_end) && ($P <= $NumPages); $P++)
	{ 
		// Current page? No hyperlink
		if ($ShowPage == $P)
			$PageNavBar .= "<b>$P</b> "; 
		else
			$PageNavBar .= "<a href=\"$ScriptName" . $Sep . "ShowPage=$P$MatchArg$CatArg\">$P</a> "; 
	} 

	// Append a "Next" button?
	if ($NumRecords > $RecStart + $ItemsPerPage)
	{ 
		$Next_P = $ShowPage + 1;
		$NextMsg = "<a href=\"$ScriptName" . $Sep . "ShowPage=$Next_P$MatchArg$CatArg\">Next</a> "; 
	} 
	else
	{
		$NextMsg = 'Next'; 
	}

	// Append a "Prev" button?
	if ($ShowPage > 1)
	{ 
		$Prev_P=$ShowPage-1; 
		$PrevMsg="<a href=\"$ScriptName" . $Sep . "ShowPage=$Prev_P$MatchArg$CatArg\">Prev</a> "; 
	} 
	else
	{
		$PrevMsg = 'Prev '; 
	}

	// Finally, construct the "go to start" and "go to end" links
	if ($ShowPage != 1)
		$GoStart = "<a href=\"$ScriptName" . $Sep . "ShowPage=1$MatchArg$CatArg\"><<</a> ";
	else
		$GoStart = "<< ";

	if ($ShowPage != $NumPages)
		$GoEnd = " <a href=\"$ScriptName" . $Sep . "ShowPage=$NumPages$MatchArg$CatArg\">>></a>";
	else
		$GoEnd = " >>";

	// Construct a navigation bar
	$FullNavBar = 'Pages: ' . $GoStart . $PrevMsg . $PageNavBar . $NextMsg . $GoEnd;
	
	return $FullNavBar;
}

// ==============================================================================================================================

// Build a drop-down for numeric values
function BuildNumericDropdown($FieldName, $SelectedID, $Min, $Max, $PadLength = 1)
{
	echo '<SELECT name="' . $FieldName . '">';
	for ($i = $Min; $i <= $Max; $i++)
		echo "<OPTION value=\"$i\"" . ($i == $SelectedID ? ' SELECTED' : '') . '>' . str_pad($i, $PadLength, '0', STR_PAD_LEFT) . "</OPTION>\n";

	echo "</SELECT>\n";
}

// ==============================================================================================================================

// Build a drop-down for categories
function BuildCategoryDropdown($FieldName, $SelectedID, $ShowSelectOne = true, $ShowAllCategories=false, $ShowNoCategories=false)
{
	echo "\n<SELECT name=\"$FieldName\">\n";

	if ($ShowSelectOne)
		echo '<OPTION value="0"' . ($SelectedID == '0' ? ' SELECTED' : '') . ">Select One</OPTION>\n";

	if ($ShowAllCategories)
		echo '<OPTION value="A"' . ($SelectedID == 'A' ? ' SELECTED' : '') . ">All Categories</OPTION>\n";

	if ($ShowNoCategories)
		echo '<OPTION value="N"' . ($SelectedID == 'N' ? ' SELECTED' : '') . ">In No Categories</OPTION>\n";

	// Execute the query
	$query = "SELECT * FROM news_categories ORDER BY CatDesc ASC";
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

// Return the description for a category
function GetCategoryDescription($CatID)
{
	// No category?
	if ($CatID == 0)
		return '';

	// Execute the query
	$query = "SELECT CatDesc FROM news_categories WHERE ID = $CatID";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	if ($row)
		return $row['CatDesc'];
	else
		return 'Unknown!';
}


// ==============================================================================================================================

function CurrentFormattedDateTime()
{
	global $ServerTimeOffset;
	return (date('Y-m-d H:i:s', time() + ($ServerTimeOffset * 3600)));
}

// ==============================================================================================================================

function BuildUserDefinedCodesList()
{
	// Execute the query
	$query = "SELECT * FROM news_usercodes ORDER BY UserCode ASC";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());

	$Codes = array();

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$UserCode = $row['UserCode'];
		$ReplacementText = $row['ReplacementText'];
		$Codes[$UserCode] = $ReplacementText;
	}

	return $Codes;
}

// ==============================================================================================================================

function gen_rand_string($hash = true, $MaxLength = 30)
{
	$chars = array( 'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L',
		'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1',
		'2', '3', '4', '5', '6', '7', '8', '9', '0');

	$max_chars = count($chars) - 1;
	srand( (double) microtime()*1000000);

	$rand_str = '';
	for($i = 0; $i < $MaxLength; $i++)
		$rand_str = ( $i == 0 ) ? $chars[rand(0, $max_chars)] : $rand_str . $chars[rand(0, $max_chars)];

	return ($hash) ? md5($rand_str) : $rand_str;
}


// ==============================================================================================================================

function CountArticlesPerYear()
{
	$ArticleCount = array();

	// Generate the query
	$Query = "SELECT DISTINCT YEAR(PostDateTime) AS PostYear, news_posts.ID FROM news_posts";
	$Query .= ApplyFilters();	
	$Query .= "	ORDER BY YEAR(PostDateTime)";

	// Execute the query
	$result = mysql_query($Query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$PostYear = $row['PostYear'];
		if (isset ($ArticleCount[$PostYear]))
			$ArticleCount[$PostYear] = $ArticleCount[$PostYear] + 1;
		else
			$ArticleCount[$PostYear] = 1;
	}

	return $ArticleCount;
}

// ==============================================================================================================================

function CountArticlesPerMonthForYear($PostYear)
{
	$ArticleCount = array();

	// Generate the query
	$Query = "SELECT DISTINCT MONTH(PostDateTime) AS PostMonth, DATE_FORMAT(PostDateTime, '%M') AS PostMonthName, news_posts.ID FROM news_posts";
	$Query .= ApplyFilters();	
	$Query .= " AND YEAR(PostDateTime) = $PostYear ORDER BY MONTH(PostDateTime)";

	$result = mysql_query($Query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$PostMonth = $row['PostMonth'];
		if (isset ($ArticleCount[$PostMonth]))
			$ArticleCount[$PostMonth] = $ArticleCount[$PostMonth] + 1;
		else
			$ArticleCount[$PostMonth] = 1;
	}

	return $ArticleCount;
}

// ==============================================================================================================================

// Function: ApplyFilters -- Apply any filtering
function ApplyFilters()
{
	global $CatID, $SuppressForwardDatedArticles, $ExcludeArchivedPosts, $ExcludeLivePosts, $Match, $SearchFieldControl, $ShowIfNoCat, $ArticleList;

	// Allow no-category display?
	// If so then apply no filter, otherwise apply an inner-join to ensure we don't list articles in NO categories (INNER JOIN only finds true matches)
	// (Only if required)
	if ($ShowIfNoCat)
		$Filter = " LEFT JOIN news_postcategories ON news_posts.ID = news_postcategories.ArticleID";
	else
		$Filter = " INNER JOIN news_postcategories ON news_posts.ID = news_postcategories.ArticleID";
	

	// If a Category has been specified then include this in the WHERE clause
	if ( (isset($CatID)) && ($CatID != 'A') )
	{
		// Extract the list of categories (and sanitise them)
		$CatArray = explode(',', $CatID);
		$CatList = "'-1'";
		foreach ($CatArray as $CatElement)
		{
			$CatElement = str_replace("'", '', $CatElement);
			$CatList .= ",'" . addslashes($CatElement) . "'";
		}
		$Filter .= " WHERE CatID IN ($CatList)";
	}
	else
		$Filter .= ' WHERE news_posts.ID != 0';			// Dummy, to start the WHERE claus

	// Display a fixed list of articles?
	if (isset($ArticleList))
	{
		// Extract the list of categories (and sanitise them)
		$ArticleArray = explode(',', $ArticleList);
		$ArticleList = "'-1'";
		foreach ($ArticleArray as $ArticleElement)
		{
			$ArticleElement = str_replace("'", '', $ArticleElement);
			$ArticleList .= ",'" . addslashes($ArticleElement) . "'";
		}
		$Filter .= " AND news_posts.ID IN ($ArticleList)";
	}

	// Article search?
	if ($Match != '')
	{
		// Determine the search-field
		$SearchFields = 'shortpost';
		switch($SearchFieldControl)
		{
			case 1:
				$SearchFields = 'shortpost';
				break;
			case 2:
				$SearchFields = 'longpost';
				break;
			case 3:
				$SearchFields = 'shortpost, longpost';
				break;
			case 4:
				$SearchFields = 'headline, shortpost, longpost';
				break;
			default:
				$SearchFields = 'shortpost, longpost';
				break;
		}
		$Filter .= " AND MATCH ($SearchFields) AGAINST('$Match' IN BOOLEAN MODE)";
	}

	// Exclude archived posts?
	if ($ExcludeArchivedPosts == 1)
		$Filter .= " AND Archived != '1'";

	// Exclude live posts?
	if ($ExcludeLivePosts == 1)
		$Filter .= " AND Archived != '0'";

	// Suppress forward-dated articles?
	if ($SuppressForwardDatedArticles == 1)
		$Filter .= " AND PostDateTime <= '" . CurrentFormattedDateTime() . "'";   // " AND PostDateTime <= now()";

	// Always apply the Approved and Visible criteria
	$Filter .= "  AND Visible = '1' AND Approved = '1'";

	return $Filter;
}

// ==============================================================================================================================

function GetCategoryNames($Categories)
{
	$CatIds = array();
	$CatDescs = array();

	// Explode the array of categories, extracting the keys
	foreach ($Categories as $key => $item)
		$CatIds[] = $key;

	// No categories? Leave...
	if (count($CatIds) == 0)
		return '';

	// Implode into a comma-separated lists
	$CatCodes = implode(",", $CatIds);

	// Only one category? Append a dummy (otherwise the SQL 'IN' statement is invalid)
	if (count($CatIds) == 1)
		$CatCodes .= ',-1';

	// Obtain the descriptions
	$Query = "SELECT CatDesc FROM news_categories WHERE ID IN ($CatCodes) ORDER BY CatDesc";

	$result = mysql_query($Query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		$CatDescs[] = $row['CatDesc'];

	// Now expand the array into a comma-separated list
	$Contents = implode(", ", $CatDescs);
	return $Contents;
}

// ==============================================================================================================================

function CategoriesFromDB($ArticleID)
{
	$Cats = array();

	// Execute the query
	$query = "SELECT CatID FROM news_postcategories WHERE ArticleID = '$ArticleID' ORDER BY CatID ASC";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$CatID = $row['CatID'];
		$Cats[$CatID] = TRUE;
	}

	return $Cats;
}

// ==============================================================================================================================
// ==============================================================================================================================

function StripMagicQuotes($arr) 
{ 
	foreach($arr as $k => $v) 
	{ 
		if (is_array($v)) 
			$arr[$k] = StripMagicQuotes($v);
		else 
			$arr[$k] = stripslashes($v);
    } 

    return $arr; 
} 

// Strip magic quotes if required
if (get_magic_quotes_gpc())
{
    if (!empty($_GET))
		$_GET = StripMagicQuotes($_GET);

    if (!empty($_POST))
		$_POST = StripMagicQuotes($_POST);

    if (!empty($_COOKIE))
		$_COOKIE = StripMagicQuotes($_COOKIE);
}

?>
