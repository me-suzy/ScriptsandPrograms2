<?php

/*

	Archived News Display

*/

define('wbnews',true);

include "../global.php";
include $config['installdir']."/news.php";
include $config['installdir']."/search.php";

$search = new newsSearch($newsConfig);
if (!isset($_POST['search_submit']))
{
	$search->displayEasy(); //or $search->displayAdvanced();
}
else
{
	$search->displayResults();
}

?>
