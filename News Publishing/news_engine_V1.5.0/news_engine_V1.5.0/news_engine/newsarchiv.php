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
|   > Newsarchiv
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: newsarchiv.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

if (isset($_GET['month'])&& isset($_GET['year'])) {
    $tpl->loadFile('main', 'newsarchiv.html'); 
    $parse_archive_headlines = false;
    $parse_archive_news = true;    

    if($_GET['month'] == 'yearly') {
        $begin = mktime( 0,0,0,1,1,$_GET['year'] );;
        $end = mktime ( 24,59,59,12,31,$_GET['year'] );  
        $mlong = $_GET['year'];
    } else {
        $begin = mktime( 0,0,0,$_GET['month'],1,$_GET['year'] );
        $lastday = lastDay($_GET['mlong']);
        $end = mktime ( 24,59,59,$_GET['month'],$lastday,$_GET['year'] );  
        $mlong = GetGerMonth($_GET['month'])." ".$_GET['year'];
    }
    $tpl->register('title', 'Archiv '.$mlong);
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => $sess->url("index.php"), $lang['title_news_archive'] => $sess->url('newsarchiv.php'), 'Archiv '.$mlong => '')));        
       
    $result = $db_sql->sql_query("SELECT $news_table.*,$newscat_table.titel,$newscat_table.cat_image,$user_table.$username_table_column AS postname,$user_table.$useremail_table_column AS postmail,COUNT($newslinks_table.linkid) AS linkC FROM $news_table
    							  LEFT JOIN $newslinks_table ON ($newslinks_table.newsid = $news_table.newsid)
    							  LEFT JOIN $newscat_table ON ($newscat_table.catid = $news_table.catid)
    							  LEFT JOIN $user_table ON ($user_table.$userid_table_column = $news_table.userid)
    							  WHERE published='1' AND  newsdate BETWEEN '$begin' AND '$end' GROUP BY newsid ORDER BY newsdate DESC");
    while($news = $db_sql->fetch_array($result)) {
        $news = stripslashes_array($news);
        unset($image);
        unset($linkshow);
        unset($count_reads);
        unset($catgraf);
    	unset($image_align_right);
    	unset($image_align_left);
    	unset($image_top);
    	unset($writerlink);
    	
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
    		
    	$writerlink = $lang['index_news_poster']." <a href=\"".$sess->url("misc.php?action=formmailer&memberid=".$news['userid'])."\">".trim($news['postname'])."</a>&nbsp;&nbsp;|";
    		
        if ($news['comments_allowed'] == 1) {
            $comments = getCommentNo($news['newsid']);
            $commentlink = "&nbsp;&nbsp;<a href=\"".$sess->url("newscomments.php?newsid=".$news['newsid'])."\">".$lang['php_comments']."</a> (".$comments.")";
        } else {
            $commentlink = "";
        }
    		
        if ($news['news_links'] == 0) {
            $linkshow = "";
        } else {
            if ($news['linkC'] != "0") {
    			$linkshow .= "<br /><br />".$lang['index_more_links'];
                $result4 = $db_sql->sql_query("SELECT * FROM $newslinks_table WHERE newsid='$news[newsid]'");
                while ($links = $db_sql->fetch_array($result4)) {
                    $links = stripslashes_array($links);
                    if($links['link_target'] == 1) {
                        $target = "target=\"_blank\"";
                    } else {
                        $target = "target=\"_self\"";
                    }					
                    $linkshow .= "<br />&nbsp;&nbsp;&nbsp;&nbsp;&raquo; <a href=\"".stripslashes($links['link_url'])."\" $target>".stripslashes($links['link_name'])."</a>";
                }
            } else {
                $linkshow = "";
            }
        }
    
        if ($news['newstext'] != "") {
            $newshead = "<a class=\"cat_headline\" href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".stripslashes($news['headline'])."</a>";
            $morenews = "&nbsp;&nbsp;[<a href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".$lang['index_read_more']."</a>]";
            $image = "<a href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".$image."</a>";
            $count_reads = "&nbsp;&nbsp;".$lang['php_reads1']." ".$news['reads']."x ".$lang['php_reads2'];
        } else {
            $newshead = "<span style=\"font-size: 12px;\"><b>".trim($news['headline'])."</b></span>";
            $morenews = "&nbsp;";
        }
    	
    	if($config['categorie_before_headline']) $newshead = $config['start_category_html'].trim($news['titel']).$config['end_category_html']."&nbsp;".$newshead;
    		
        include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
        $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);	
        $news['hometext'] = $bbcode->rebuildText($news['hometext']);		
        
        if($config['activate_recommendation']) {
    		$recommend_link = "&nbsp;&nbsp;<a href=\"".$sess->url("recommend.php?newsid=".$news['newsid'])."\">".$lang['index_send_news']."</a>&nbsp;&nbsp;|";
    	} else {
    		$recommend_link = "";
    	}
    
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
            // Datum
            $fulldate = aseDate($config['shortdate'],$news['newsdate']);
        } elseif($config['newsdate'] == 2) {
            // Uhrzeit - Datum
            $fulldate = aseDate($config['shortdate'],$news['newsdate'])." - ".aseDate($config['timeformat'],$news['newsdate']);
        } elseif($config['newsdate'] == 3) {
            // Tag, Datum
            $fulldate = $day.", ".aseDate($config['shortdate'],$news['newsdate']);
        } else {
            // Tag, Datum - Uhrzeit
            $fulldate = $day.", ".aseDate($config['shortdate'],$news['newsdate'])." - ".aseDate($config['timeformat'],$news['newsdate']);
        }
        
    	$newsno++;
    	
    	$news_loop[] = array(
    						'fulldate' => $fulldate,
    						'hometext' => trim($news['hometext']),
    						'newshead' => $newshead,
    						'morenews' => $morenews,
    						'commentlink' => $commentlink,
    						'recommend_link' => $recommend_link,
    						'writerlink' => $writerlink,
    						'newsid' => $news['newsid'],
    						'image_text' => $image_text,
    						'image_headline' => $image_headline,
    						'linkshow' => $linkshow);
    }
    $tpl->parseLoop('main', 'news_loop');
}

if (!isset($_GET['month'])) {
    $parse_archive_headlines = true;
    $parse_archive_news = false;
    $tpl->loadFile('main', 'newsarchiv.html'); 
    $tpl->register('title', $lang['title_news_archive']);    
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => $sess->url("index.php"), $lang['title_news_archive'] => '')));        
    $tpl->register('archive_links', GetArchivLinks());	
}
$tpl->parseIf('main', 'parse_archive_headlines');				   
$tpl->parseIf('main', 'parse_archive_news');    


$tpl->register('query', showQueries($develope));
$tpl->register('header', $tpl->pget('header'));

$tpl->register('footer', $tpl->pget('footer'));
$tpl->pprint('main');
?>