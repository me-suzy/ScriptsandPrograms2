<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

// Load up the configuration
require('Config/Config.php');
require('Inc/CommentsFunctions.php');
require('Inc/Functions.php');

$ArticleID = isset($_GET['ArticleID']) ? $_GET['ArticleID'] : '';
$VC = isset($_GET['VC']) ? $_GET['VC'] : '';
?>

<HTML>
	<HEAD>
		<TITLE><?= GetHeadline($ArticleID) ?></TITLE>
		<LINK rel="stylesheet" href="/Styles.css" type="text/css">
	</HEAD>
	<BODY background="/Img/Background.jpg">
		<?php

		// Are we to record comments?
		if (isset($_POST['submit']))
			RecordComments($ArticleID);
		elseif ($VC != '')
			VerifyComments($ArticleID, $VC);
		else
			ShowCommentsForm($ArticleID);
		?>
	</BODY>
</HTML> 