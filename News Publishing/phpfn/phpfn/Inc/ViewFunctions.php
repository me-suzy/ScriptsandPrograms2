<?

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

// Enable Ratings?
if ($EnableRatings == 1)
{
	?>
	<SCRIPT language="JavaScript" type="text/javascript">
		<!--
		function Vote(ArticleID)
		{
			window.open("<?= $NewsDir ?>/Vote.php?ArticleID=" + ArticleID, "VoteWindow", "width=500 ?>,height=200,resizable=yes,scrollbars=yes")
		}
		//-->
	</SCRIPT>		
	<?php
}

// Include the script for the Comments popup, if required
if ($EnableComments == 1)
{
	?>
	<SCRIPT language="JavaScript" type="text/javascript">
		<!--
		function Comments(ArticleID)
		{
			window.open("<?= $NewsDir ?>/Comments.php?ArticleID=" + ArticleID, "CommentWindow", "width=<?=$PopupWidth ?>,height=<?=$PopupHeight ?>,resizable=yes,scrollbars=yes")
		}
		//-->
	</SCRIPT>		
	<?php
}
?>