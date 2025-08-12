<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Config/Config.php');
require_once('Inc/Functions.php');

// Handle fields coming from the search-form
if (isset($_REQUEST['Match']))
	$Match = strip_tags($_REQUEST['Match']);
else
	$Match = NULL;

if (isset($_REQUEST['CatID']))
	$CatID = strip_tags($_REQUEST['CatID']);

?>
<FORM method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<?= $SearchPromptText ?><INPUT type="text" size="20" maxlength="20" name="Match" value="<?= $Match ?>">
	<INPUT type="hidden" name="NewsMode" value="<?= $NewsMode ?>">
	<INPUT type="submit" name="SearchNews" value="<?= $SearchButtonText ?>">
	<?php
	if ($SearchByCategory == 1)
	{
		echo 'Category...';
		BuildCategoryDropdown('CatID', (isset($CatID) ? $CatID : ""), false, true);
	}
	?>
</FORM>