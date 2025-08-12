<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// Load up the configuration of this puppy
require('Config/Config.php');
require('Inc/RatingsFunctions.php');
require('Inc/Functions.php');

$ArticleID = isset($_GET['ArticleID']) ? $_GET['ArticleID'] : '';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE><?= GetHeadline($ArticleID) ?></TITLE>
		<LINK rel="stylesheet" href="/Styles.css" type="text/css">
	</HEAD>
	<BODY background="/Img/Background.jpg">
		<?php

		// Are we to record a vote?
		if (isset($_POST['submit']))
			RecordVote($ArticleID);
		else
			ShowVotingForm($ArticleID);
		?>
	</BODY>
</HTML> 
