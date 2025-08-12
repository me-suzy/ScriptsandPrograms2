<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > Funktionen für die druckbare Anzeige eines Artikels
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: print.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

if (!isset ($_GET['newsid'])) {
   header("Location: ".$sess->url("index.php"));
   exit;
}  else {
   $news = News($_GET['newsid']);
}

define('IS_POPUP',true);

$tpl->loadFile('main', 'print.html'); 
$tpl->register('title', stripslashes(trim($news['headline'])));

$heute = getdate();
$printed_std = date("d.m.Y");
$printed_date = aseDate($config['shortdate'],time())." $lang[php_last_visit2] ".aseDate($config['timeformat'],time());

include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
$bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);	

if ($news['hometext'] != "" && $news['is_html'] == 0) {
    $hometext = $bbcode->rebuildText($news['hometext']);		
    $hometext = trim($hometext);
} else {
    $hometext = $news['hometext'];
}

if ($news['is_html'] == 0) {
    $newstext = $bbcode->rebuildText($news['newstext']);		
    $newstext = trim($newstext);  
} else {
    $newstext = $news['newstext'];
}

$tpl->register(array('headline' => stripslashes(trim($news['headline'])),
                    'newstext' => trim($newstext),
                    'print_date' => aseDate($config['shortdate'],time())." - ".aseDate($config['timeformat'],time()),
                    'hometext' => trim($hometext),
                    'print_at' => $lang['print_at'],
                    'print_topic' => $lang['print_topic'],
                    'print_author' => $lang['print_author'],
                    'print_url' => $lang['print_url'],
                    'newsid' => $news['newsid'],
                    'scriptname' => $config['scriptname']));

$tpl->register('query', showQueries($develope));

$tpl->pprint('main');
?> 