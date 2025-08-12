<?php

/*

	Can be used best when using Send to friend to link to this page. 

*/

define('wbnews',true);
include "../global.php";
include $config['installdir']."/news.php";

if (isset($_GET['newsid'])) 
{
    $news = new news($newsConfig);
    $news->displaySingleNews($_GET['newsid']);
}

?>
