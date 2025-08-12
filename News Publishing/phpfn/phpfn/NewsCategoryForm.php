<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Config/Config.php');
require_once('Inc/Functions.php');

if (isset($_GET['CatID']))
	$CatID = strip_tags($_GET['CatID']);
else
	$CatID = 'A';
?>
<FORM method="get" action="<?= $_SERVER['PHP_SELF'] ?>">
	<INPUT type="hidden" name="NewsMode" value="<?= $NewsMode ?>">
	<?php
	echo 'Category...';
	BuildCategoryDropdown('CatID', $CatID, false, true);
	?>
	<INPUT type="submit" value="<?= $FilterButtonText ?>">	
</FORM>