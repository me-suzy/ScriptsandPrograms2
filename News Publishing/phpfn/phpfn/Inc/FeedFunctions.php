<?

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// ==============================================================================================================================

function GenerateNewsFeed($Feed, $Mode = 1)			// 1=Standard; 2=Headlines
{
	global $WWW, $SiteDomain, $SiteDescription, $RSSNewsItems;

	// Define the query
	$Query = BuildListingSQL();

	// Now obtain the resultset
	$ResultSet = mysql_query($Query) or die("Query failed : " . mysql_error());

	// Build the list of user-defined codes (once only)
	$UserCodes = BuildUserDefinedCodesList();

	// Write the feed header
	$Feed->Header($SiteDescription, $SiteDomain, $WWW . "/Inc/Images/RSS.gif", $SiteDescription . " Latest News");

	// Limit the number of articles?
	if ($RSSNewsItems!= 0)
		$Query .= " LIMIT $RSSNewsItems";

	// Now obtain the resultset, and show the articles
	$ResultSet = mysql_query($Query) or die('Query failed : ' . mysql_error());
	while ($NewsRow = mysql_fetch_array($ResultSet, MYSQL_ASSOC))
	{
		$Headline = $NewsRow["Headline"];

		// Construct the link URL
		if ($NewsRow['LongPost'] != "")
			$MainItemURL = $WWW . "/View.php?ArticleID=" . $NewsRow['ID'];
		else
			$MainItemURL = NULL;

		//Output the feed
		switch ($Mode)
		{
			case 1:
				$ShortPost = $NewsRow["ShortPost"];
				$ShortPost = ParseUserDefinedCodes($UserCodes, $ShortPost);
				$ShortPost = StripBBCodes($ShortPost);
				$ShortPost = htmlspecialchars(strip_tags($ShortPost));
				$Feed->Item($Headline, $ShortPost, $MainItemURL);
				break;
			case 2:
				$Feed->Item($Headline, NULL, $MainItemURL);
				break;
		}
	}

	// Write the feed footer
	$Feed->Footer();
}
?>