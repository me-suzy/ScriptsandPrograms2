<?php

/*

	Archived News Display

*/

define('wbnews',true);

include "../global.php";
include $config['installdir']."/news.php";
include $config['installdir']."/archive.php";

$archive = new newsArchive($newsConfig);
$archive->archiveList();

if (isset($_GET['year']))
	$archive->displayNewsArticles();
?>
