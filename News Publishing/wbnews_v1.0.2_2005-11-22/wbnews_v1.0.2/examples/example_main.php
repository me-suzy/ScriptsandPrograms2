<?php

define('wbnews',true);

include "../global.php";
include $config['installdir']."/news.php";

if (!isset($_GET['action'])) 
{
    $news = new news($newsConfig);
    if (!isset($_GET['newsid'])) 
    {
        $news->displayNews('all'); //want to display all news try $news->displayNews('all'); or $news->displayNews('', 100); displays first 100characters
        $news->pagination();
    }
    else
        $news->displaySingleNews($_GET['newsid']);
} 
else 
{
    if ($_GET['action'] != 'sendto') 
    {
        include $config['installdir']."/comments.php";
        $comments = new comments($newsConfig, @$_GET['newsid']);
        $comments->showNews();
        $comments->viewComments();
        $comments->displayForm();
    } 
    else 
    {
        include $config['installdir']."/sendtofriend.php";
        $sendto = new sendToFriend($newsConfig);
        $sendto->sendFriend();
    }
}

?>
