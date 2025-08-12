<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// Load up the configuration
require_once('Config/Config.php');
require_once('Inc/ListingFunctions.php');
require_once('Inc/Functions.php');

$ArticleID = isset($_GET['ArticleID']) ? $_GET['ArticleID'] : '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE><?= GetHeadline($ArticleID) ?></TITLE>
		<LINK rel="stylesheet" href="/Styles.css" type="text/css">
		<?php require_once('Inc/ViewFunctions.php'); ?>
	</HEAD>
	<BODY background="/Img/Background.jpg">
		<?php OutputArticleLong($ArticleID); ?>
	</BODY>
</HTML> 