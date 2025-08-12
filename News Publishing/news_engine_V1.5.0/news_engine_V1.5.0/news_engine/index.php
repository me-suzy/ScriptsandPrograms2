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
|   > Startseite News-System
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: index.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
$title = $lang['title_news'];

//------------- News Pagination --------//
//------ Set false to deactivate -------//
$activate_news_pagination = true;
//--------------------------------------//

$tpl->loadFile('main', 'index.html'); 
$tpl->register('title', $lang['title_news']);

if($_GET['action'] == "newsletter" && $config['enable_newsletter']) {
    $letter = $db_sql->query_array("SELECT * FROM $newsletter_table WHERE abomail='".htmlspecialchars(addslashes(trim($_GET['abomail'])))."'");
    
    if(trim($_GET['abouser']) == "") {
        rideSite($sess->url('index.php'), $lang['rec_error53']);
        exit();       
    }
    
    if(trim($_GET['abomail']) == "") {
        rideSite($sess->url('index.php'), $lang['rec_error54']);
        exit();     
    }    
    
    if(!isEmail($_GET['abomail'])) {
        rideSite($sess->url('index.php'), $lang['rec_error55']);
        exit();     
    } 
    
    if($_GET['option'] == 1) {
        if($letter['abomail']) {
            rideSite($sess->url('index.php'), $lang['rec_error56']);
            exit();    
        }    
    
        $db_sql->sql_query("INSERT INTO $newsletter_table (abouser,abomail,abostart) VALUES ('".htmlspecialchars(addslashes(trim($_GET['abouser'])))."','".htmlspecialchars(addslashes(trim($_GET['abomail'])))."','".time()."')");
        rideSite($sess->url('index.php'), $lang['rec_error57']);
        exit();           
    } else {
        $db_sql->sql_query("DELETE FROM $newsletter_table WHERE abouser='".htmlspecialchars(addslashes(trim($_GET['abouser'])))."' AND abomail='".htmlspecialchars(addslashes(trim($_GET['abomail'])))."'");    
        rideSite($sess->url('index.php'), $lang['rec_error58']);
        exit();           
    }     
     
}

if($config['show_headline'] == "1") {
    if ($config['headlineno'] > $config['newsno']) $config['headlineno'] = $config['newsno'];
    $result = $db_sql->sql_query("SELECT * FROM $news_table WHERE published='1' ORDER BY newsdate DESC LIMIT $config[headlineno]");
	$headline_loop = array();
    while($headl = $db_sql->fetch_array($result)) {
        $headl = stripslashes_array($headl);	
        $headdate = aseDate($config['shortdate'],$headl['newsdate'])." - ".aseDate($config['shortdate'],$headl['newsdate'],1);		
        $headlink = "<a class=\"inbox\" href=\"".$sess->url("index.php")."#$headl[newsid]\">".stripslashes($headl['headline'])."</a>";	
		$headline_loop[] = array('headline_link' => $sess->url("index.php")."#$headl[newsid]",
								'headline_title' => stripslashes($headl['headline']),
								'headline_date' => aseDate($config['shortdate'],$headl['newsdate'],1)." - ".aseDate($config['timeformat'],$headl['newsdate']));
    }
	$tpl->parseLoop('main', 'headline_loop');
	$parse_news_headlines = true;
}		
$tpl->parseIf('main', 'parse_news_headlines');				   

if($config['show_catlink'] == "1") {
    $result = $db_sql->sql_query("SELECT * FROM $newscat_table");
    while ($cat = $db_sql->fetch_array($result)) {
        $cat = stripslashes_array($cat);
        $choose_categorie .= "&#8250;&nbsp;<a href=\"".$sess->url("index.php?showcat=jump&f=".$cat['catid']."&t=".urlencode(trim($cat['titel'])))."\" class=\"left_box_content\"><b>".trim($cat['titel'])."</b></a><br />";
    }
    $tpl->register('categorie_links',$choose_categorie);
    $show_categorie_overview = true;
}			   	

$tpl->parseIf('main', 'show_categorie_overview');				   

if(isset($_GET['showcat']) && $_GET['showcat'] == "jump") $categorie = "AND $news_table.catid='".$_GET['f']."'";

if($_GET['f'] && $_GET['t']) {
	$site_headline = urldecode($_GET['t']);
	$tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => $sess->url('index.php'), $site_headline => '')));
} else {
	$site_headline = $lang['title_news'];
	$tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => '')));
}
$tpl->register(array('site_headline' => $site_headline,
                     'index_newspage' => $lang['index_newspage'],
                     'index_categories' => $lang['index_categories'],
                     'index_news_feed' => $lang['index_news_feed'],
                     'index_we_allow_to_use_our_headlines' => $lang['index_we_allow_to_use_our_headlines'],
                     'index_show_rss' => $lang['index_show_rss']));
	
//------------------------------------------
if($_GET['start']) {
    $start = $_GET['start'];
} elseif($_POST['start']) {
    $start = $_POST['start'];
}

if($activate_news_pagination === true) {
    $over_all = $db_sql->query_array("SELECT Count(newsid) as total FROM $news_table 
                                  WHERE $news_table.newsdate <= '".time()."' 
    							  AND ($news_table.news_enddate >= '".time()."' 
    							  OR ISNULL($news_table.news_enddate)) 
    							  AND $news_table.published = '1'
                                  $categorie");
                                  
    if(!class_exists(Nav_Link)) include_once($_ENGINE['eng_dir']."admin/enginelib/class.nav.php");
    if(!isset($start)) $start = 0;
    $nav = new Nav_Link();
    $nav->overAll = $over_all['total'];
    $nav->perPage = $config['newsno'];
    $nav->DisplayLast = 1;
    $nav->DisplayFirst = 1;            
    $url_neu = (isset($_GET['showcat']) && $_GET['showcat'] == "jump") ? $sess->url("index.php?showcat=jump&f=".intval($_GET['f'])."&t=".$_GET['t'])."&amp;" : $sess->url("index.php")."&amp;";
    $nav->MyLink = "$url_neu";
    $nav->LinkClass = "page_step";
    $nav->start = $start;
    $pagecount = $nav->BuildLinks();
    if($over_all['total'] != 0) {
    $pages = intval($over_all['total'] / $config['newsno']);
    if($over_all['total'] % $config['newsno']) $pages++;	            
        if(!$pagecount) $pagecount = "<b>1</b>";
        $tpl->register('pagecount', $lang['php_page']." (".$pages."): ".$pagecount);
    }               
} else {
    $start = 0;
}         
//------------------------------------------

$newsno = 0;
$news_loop = array();
$result = $db_sql->sql_query("SELECT $news_table.*,$newscat_table.titel,$newscat_table.cat_image,$user_table.$username_table_column  AS postname,$user_table.$useremail_table_column AS postmail,COUNT($newslinks_table.linkid) AS linkC FROM $news_table
							  LEFT JOIN $newslinks_table ON ($newslinks_table.newsid = $news_table.newsid)
							  LEFT JOIN $newscat_table ON ($newscat_table.catid = $news_table.catid)
							  LEFT JOIN $user_table ON ($user_table.$userid_table_column = $news_table.userid)
							  WHERE $news_table.newsdate <= '".time()."' 
							  AND ($news_table.news_enddate >= '".time()."' 
							  OR ISNULL($news_table.news_enddate)) 
							  AND $news_table.published = '1' 
							  $categorie 
							  GROUP BY $news_table.newsid ORDER BY $news_table.newsdate DESC LIMIT ".$start.",$config[newsno]");
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
        $newshead = "<a class=\"cat_headline\" href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".stripslashes(trim($news['headline']))."</a>";
        $morenews = "&nbsp;&nbsp;[<a href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".$lang['index_read_more']."</a>]";
        $image = "<a href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".$image."</a>";
        $count_reads = "&nbsp;&nbsp;".$lang['php_reads1']." ".$news['reads']."x ".$lang['php_reads2'];
    } else {
        $newshead = "<span style=\"font-size: 12px;\"><b>".stripslashes(trim($news['headline']))."</b></span>";
        $morenews = "&nbsp;";
    }
	
	if($config['categorie_before_headline']) $newshead = $config['start_category_html'].trim($news['titel']).$config['end_category_html']."&nbsp;".$newshead;
		   
    include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
    $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);    
    /*$news['hometext'] = $bbcode->rebuildText($news['hometext']);*/		
    if ($news['is_html'] == 0) {
        $hometext = $bbcode->rebuildText($hometext);		
        $news['hometext'] = $bbcode->rebuildText($news['hometext']);
    }    
    
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

if($config['activate_rss']) $show_rss_hint = true; //$rss_hint = "<div class=\"newsbottom\">".$lang['rss_available']."</div>";
if($config['enable_newsletter']) {
    $show_newsletter_field = true;
    $tpl->register(array('index_newsletter' => $lang['index_newsletter'],
                        'index_sign_on_for_newsletter' => $lang['index_sign_on_for_newsletter'],
                        'index_your_name' => $lang['index_your_name'],
                        'index_your_email' => $lang['index_your_email'],
                        'index_sign_on' => $lang['index_sign_on'],
                        'index_sign_off' => $lang['index_sign_off'],
                        'index_send' => $lang['index_send'],
                        'index_reset' => $lang['index_reset']));    
}    

$tpl->parseIf('main', 'show_newsletter_field');			
$tpl->parseIf('main', 'show_rss_hint');

if($show_newsletter_field || $show_categorie_overview || $show_rss_hint) {
    $show_additionally_fields = true;
    $tpl->register(array('width1' => '20%', 'width2' => '80%'));
} else {
    $show_additionally_fields = false;
    $tpl->register('width2', '100%');
}

$tpl->parseIf('main', 'show_additionally_fields');			   
		
$tpl->register('query', showQueries($develope));
$tpl->register('header', $tpl->pget('header'));

$tpl->register('footer', $tpl->pget('footer'));
$tpl->pprint('main');
?>
