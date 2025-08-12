<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Config/Config.php');
require_once('Inc/ListingFunctions.php');
require_once("Inc/ScriptFunctions.php");
require_once("Inc/ViewFunctions.php");
require_once('Inc/Functions.php');

// =============================================================================

// We are in headline mode
$Saved = $InitiallyShowHeadlinesOnly;
$InitiallyShowHeadlinesOnly = 1;

// Display according to the specified mode (or the default)

switch ($NewsMode)
{
	case 1:
		PagedListing();
		break;
	case 2:
		LimitedListing();
		break;
	case 3:
		YearMonthListing();
		break;
	default:
		PagedListing();
		break;
}

$InitiallyShowHeadlinesOnly = $Saved;
?>