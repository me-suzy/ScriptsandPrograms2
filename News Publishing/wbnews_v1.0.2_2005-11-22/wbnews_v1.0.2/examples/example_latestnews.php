<?php

/*

	Display the Latest News

*/

define('wbnews',true);

include "../global.php";
include $config['installdir']."/news.php";

$news = new news($newsConfig);
$news->displayLastestNews();

?>
