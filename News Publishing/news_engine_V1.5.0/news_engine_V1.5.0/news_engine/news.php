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
|   > zeigt einzelne News mit Langtext an
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: news.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

if (!isset ($_GET['newsid'])) {
    header("Location: ".$sess->url("index.php"));
    exit;
} else {
    $newsid = $_GET['newsid'];
    $news = $db_sql->query_array("SELECT $news_table.*,$newscat_table.titel,$newscat_table.cat_image,$user_table.$username_table_column AS postname,$user_table.$useremail_table_column AS postmail, $user_table.$userhp_table_column AS posthp FROM $news_table 
   								LEFT JOIN $newscat_table ON ($newscat_table.catid = $news_table.catid)
								LEFT JOIN $user_table ON ($user_table.$userid_table_column = $news_table.userid)
   								WHERE newsid='$newsid' GROUP BY newsid");
    $news = stripslashes_array($news);
    $db_sql->sql_query("UPDATE $news_table SET reads=reads+1 WHERE newsid='$newsid'");
}
$newshead = trim(htmlentities($news['headline']));   

$tpl->loadFile('main', 'news.html'); 
$tpl->register('title', $newshead);

$tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => $sess->url('index.php'), $newshead => '')));

if (!isset($referer)) $referer = $_SERVER['HTTP_REFERER'];

if ($config['cat_pics'] == "1") {

    if($news['img_align']=="right" || $news['img_align']=="") {
        $image_align = "right";
        $image_top = false;
    } elseif($news['img_align']=="left") {
        $image_align = "left";	
        $image_top = false;
    } else {
        $image_align = "absmiddle";
        $image_top = true;
    }	

    if ($news['pic_n'] == 0) {
        if ($news['cat_image'] != "") {
            $catgraf = pickupImage($news['cat_image']);
            $image = "<img src=\"$config[catgrafurl]/$news[cat_image]\" $catgraf[0] $catgraf[1] border=\"0\" align=\"".$image_align."\" />";
        }
    }		   
    if ($news['pic_n'] == 1) {
        $catgraf = pickupImage($news['pic_name']);
        $image = "<img src=\"$config[catgrafurl]/$news[pic_name]\" $catgraf[0] $catgraf[1]  border=\"0\" align=\"".$image_align."\" />";
    }
    if ($news['pic_n'] == 2) $image = "";
} else {
    $image = "";
}

if($image_top) {
    $image_headline = $image;
    $image_text = "";
} else {
    $image_headline = "";
    $image_text = $image;
}

$news['postname'] = trim($news['postname']);
$postname = trim($news['postname']);

if($news['userid']) {
	$profile_link = definedBoardUrls("memberdetail",$news['userid']);
	$parse_profile_link = true;
}	
$tpl->parseIf('main', 'parse_profile_link');	
		   
if(trim($news['posthp'])) {
	$homepage_link = trim($news['posthp']);
	$parse_homepage_link = true;
}	
$tpl->parseIf('main', 'parse_homepage_link');		
	   
if(trim($news['postmail'])) {	
	$parse_email_link = true;
}	
$tpl->parseIf('main', 'parse_email_link');	

if($parse_profile_link || $parse_homepage_link || $parse_email_link) $author_information_block = true;
$tpl->parseIf('main', 'author_information_block');			   

$fulldate = $lang['php_news_at']." ".aseDate($config['shortdate'],$news['newsdate'],1)." ".$lang['php_last_visit2']." ".aseDate($config['timeformat'],$news['newsdate'],1)." ".$lang['php_news_time'];

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

/*if ($news['hometext'] != "") {
    $hometext = $bbcode->rebuildText($news['hometext']);		
    $hometext = trim($hometext);
}*/

/*$newstext = $bbcode->rebuildText($news['newstext']);		
$newstext = trim($newstext);*/

$count_reads = "&nbsp;&nbsp;".$lang['php_reads1']." ".$news['reads']."x ".$lang['php_reads2'];

    $day = date("D", $news['newsdate']);
		
    switch($day) {
        case Tue;
            $day = $lang['php_tu'];
            break;
        case Wed;
            $day = $lang['php_we'];
            break;
        case Thu;
            $day = $lang['php_th'];
            break;
        case Fri;
            $day = $lang['php_fr'];
            break;
        case Sat;
            $day = $lang['php_sa'];
            break;
        case Sun;
            $day = $lang['php_su'];
            break;
        default;
            $day = $lang['php_mo'];
            break;
    }
    
    if($config['newsdate'] == 1) {
        $fulldate = aseDate($config['shortdate'],$news['newsdate']);
    } elseif($config['newsdate'] == 2) {
        $fulldate = aseDate($config['shortdate'],$news['newsdate'])." - ".aseDate($config['timeformat'],$news['newsdate']);
    } elseif($config['newsdate'] == 3) {
        $fulldate = $day.", ".aseDate($config['shortdate'],$news['newsdate']);
    } else {
        $fulldate = $day.", ".aseDate($config['shortdate'],$news['newsdate'])." - ".aseDate($config['timeformat'],$news['newsdate']);
    }

if($config['activate_recommendation']) {
	$parse_recommend_link = true;
}
$tpl->parseIf('main', 'parse_recommend_link');	
	
if($news['comments_allowed']) {
	$parse_comment_link = true;
	$comment_count = relatedComments($news['comments_allowed'],$news['newsid']);
}	
$tpl->parseIf('main', 'parse_comment_link');	

$print_link = "<a href=\"JavaScript:Print()\"><img src=\"".$config['grafurl']."/img_print.gif\" border=\"0\" align=\"absmiddle\" />News drucken</a><br />";


if($config['categorie_before_headline']) $newshead = $config['start_category_html'].trim($news['titel']).$config['end_category_html']."&nbsp;".$newshead;

if(relatedLinks($news['newsid'])) {
	$related_links_block = true;	
	$tpl->parseLoop('main', 'links_loop');	
} else {
	$related_links_block = false;	
}

$tpl->parseIf('main', 'related_links_block');	   

$tpl->register(array('news_headline' => $newshead,
					'news_home_text' => trim($hometext),
					'news_text' => trim($newstext),
                    'image_text' => $image_text,
                    'image_headline' => $image_headline,
					'recommend_link' => $recommend_link,
					'profile_link' => $profile_link,
					'homepage_link' => $homepage_link,
					'print_news_link' => $print_link,
					'commentlink_link' => $commentlink_link,
					'related_links' => $related_links,
					'news_userid' => $news['userid'],
					'newsid' => $news['newsid'],
					'number_of_comments' => $comment_count,
					'news_profile_of' => sprintf($lang['news_profile_of'],trim($news['postname'])),
					'news_homepage_of' => sprintf($lang['news_homepage_of'],trim($news['postname'])),
					'news_email_to' => sprintf($lang['news_email_to'],trim($news['postname'])),
					'news_send_news_by_mail' => $lang['news_send_news_by_mail'],
					'news_comments' => $lang['news_comments'],
					'news_print_news' => $lang['news_print_news'],
					'news_author' => $lang['news_author'],
					'news_options' => $lang['news_options'],
					'news_date' => $fulldate,
					'news_more_links' => $lang['news_more_links']));
		
$tpl->register('query', showQueries($develope));
$tpl->register('header', $tpl->pget('header'));

$tpl->register('footer', $tpl->pget('footer'));
$tpl->pprint('main');
						
?>

