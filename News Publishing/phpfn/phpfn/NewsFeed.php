<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Config/Config.php');

// Instantiate and initialise the class
require_once('Inc/RSSFeed.php');
$Feed = new RSSFeed();

require_once('Inc/FeedFunctions.php');
require_once('Inc/Functions.php');
require_once('Inc/ListingFunctions.php');

GenerateNewsFeed($Feed, 1);
?>