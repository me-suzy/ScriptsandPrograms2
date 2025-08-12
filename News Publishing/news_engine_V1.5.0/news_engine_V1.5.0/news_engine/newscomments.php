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
|   > Kommentare zu News
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: newscomments.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
include_once($_ENGINE['eng_dir']."admin/enginelib/class.comment.php");

/*
Comments per Page
Set this to 0 to show all comments on one page (deactivate)
*/
$comments_per_page = 10;

$tpl->register(array('comment_js_too_long' => $lang['comment_js_too_long'],
                    'comment_js_maximum' => $lang['comment_js_maximum'],
                    'comment_js_long1' => $lang['comment_js_long1'],
                    'comment_js_long2' => $lang['comment_js_long2'],
                    'comment_js1' => $lang['comment_js1'],
                    'comment_js2' => $lang['comment_js2'],
                    'comment_js3' => $lang['comment_js3'],
                    'comment_js4' => $lang['comment_js4'],
                    'max_comment_length' => $config['max_comment_length']));

$tpl->loadFile('meta', 'comment_js.html'); 
$tpl->register('header_add', $tpl->pget('meta'));

$tpl->loadFile('main', 'comment.html'); 
$tpl->register('title', $lang['title_comment']);

$conditions = array("table"=>$newscomment_table,
					"id"=>"comid",
					"userid"=>"userid",
					"headline"=>"com_headline",
					"comment"=>"post_comment",
					"date"=>"com_date",
					"status"=>"com_status",
					"postid"=>"newsid",
					"ip"=>"user_ip",
					"username"=>"user_comname",
					"posticon"=>"posticon");
					
$ct = new engineComment($conditions);

if (!isset($_POST['newsid']) && !isset($_GET['newsid'])) {
	header("Location: ".$sess->url("index.php"));
	exit;
}

if (isset($_POST['comment']) && $_POST['comment']=='send') {
	if ($auth->user['canpostcomments']) {
		if ($_POST['comment_message'] == "") {
	        rideSite($sess->url('newscomments.php?newsid='.$_POST['newsid']), $lang['rec_error11']);
	        exit();      
		} else {
        
			$comment['postid'] = $_POST['newsid'];
		
			$direct_post = $ct->writeComment($comment);    
                
			if ($config['directpost'] == 1 && !$direct_post) {
		        rideSite($sess->url('newscomments.php?newsid='.$_POST['newsid']), $lang['rec_error9']);
		        exit();			
			} else {
		        rideSite($sess->url('newscomments.php?newsid='.$_POST['newsid']), $lang['rec_error10']);
		        exit();			
			}
		}
	}
}

if($_POST['newsid']) $newsid = $_POST['newsid'];
if($_GET['newsid']) $newsid = $_GET['newsid'];

$news = $db_sql->query_array("SELECT $news_table.*,$newscat_table.titel,$newscat_table.cat_image,$user_table.$username_table_column AS postname,$user_table.$useremail_table_column AS postmail, $user_table.$userhp_table_column AS posthp FROM $news_table 
							LEFT JOIN $newscat_table ON ($newscat_table.catid = $news_table.catid)
							LEFT JOIN $user_table ON ($user_table.$userid_table_column = $news_table.userid)
							WHERE newsid='$newsid' GROUP BY newsid");
$news = stripslashes_array($news);
    
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
	$fulldate = aseDate($config['shortdate'],$news['newsdate']);
} elseif($config['newsdate'] == 2) {
	$fulldate = aseDate($config['shortdate'],$news['newsdate'])." - ".aseDate($config['timeformat'],$news['newsdate']);
} elseif($config['newsdate'] == 3) {
	$fulldate = $day.", ".aseDate($config['shortdate'],$news['newsdate']);
} else {
	$fulldate = $day.", ".aseDate($config['shortdate'],$news['newsdate'])." - ".aseDate($config['timeformat'],$news['newsdate']);
}    

$tpl->register(array('fulldate' => $fulldate,
					'hometext' => trim($news['hometext']),
					'newshead' => $newshead,
					'morenews' => $morenews,
					'commentlink' => $commentlink,
					'recommend_link' => $recommend_link,
					'writerlink' => $writerlink,
					'newsid' => $news['newsid'],
					'image_text' => $image_text,
					'image_headline' => $image_headline,
					'linkshow' => $linkshow));  

if($comments_per_page >= 1) {
    $over_all = $db_sql->query_array("SELECT Count(*) as total FROM ".$ct->table." WHERE ".$ct->postid."='".intval($news['newsid'])."' AND com_status='1'");
    
    include_once($_ENGINE['eng_dir']."admin/enginelib/class.nav.php");
    if(!isset($_GET['start'])) {
        $start = 0;
    } else {
        $start = intval($_GET['start']);
    }
    $nav = new Nav_Link();
    $nav->overAll = $over_all['total'];
    $nav->perPage = $comments_per_page;
    $nav->DisplayLast = 1;
    $nav->DisplayFirst = 1;    
    $nav->MyLink = $sess->url("newscomments.php?newsid=".intval($news['newsid']))."&amp;";
    $nav->LinkClass = "page_step";
    $nav->start = $start;
    $pagecount = $nav->BuildLinks();
    if(!$pagecount) $pagecount = "<b>1</b>";
    $pages = intval($over_all['total'] / $nav->perPage);
    if($over_all['total'] % $nav->perPage) $pages++;	    
    $tpl->register('pagecount', sprintf($lang['comment_pagecount'],$pages,$pagecount,$over_all['total']));
}

if($auth->user['canpostcomments'] == 1) {
	if($auth->user['userid'] == 2) {
		if($config['guestpost'] == 1) {
			$com_post_name = $lang['php_guest'];
			$comment_details = $ct->buildComments($news['newsid']);
			$comment_form = $ct->displayCommentForm($news['newsid'],$auth->user['userid'],$com_post_name);
		} else {
			$com_post_name = "Name_Input";
			$comment_details = $ct->buildComments($news['newsid']);
			$comment_form = $ct->displayCommentForm($news['newsid'],$auth->user['userid'],$com_post_name);
		}
	} else {
		$comment_details = $ct->buildComments($news['newsid']);
		$comment_form = $ct->displayCommentForm($news['newsid'],$auth->user['userid'],$auth->user['username']);
	}
} else {
	$com_post_name = $lang['php_guest'];
    $comment_form = array('user_can_post_comments' => false, 'user_can_post_no_comments' => true);	
	$comment_details = $ct->buildComments($news['newsid']);
}

$tpl->register('comments', $comment_details);

$user_can_post_comments = $comment_form['user_can_post_comments'];
$user_can_post_no_comments = $comment_form['user_can_post_no_comments'];

if($comment_form['user_can_post_comments']) {
    $tpl->register(array('hidden' => $comment_form['hidden'],
                         'input' => $comment_form['autor'],
                         'newsid' => $newsid,
                         'user_ip' => $comment_form['user_ip']));
}                    

$tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), trim($news['headline']) => $sess->url('news.php?newsid='.$newsid) ,sprintf($lang['comment_comments'],aseDate($config['shortdate'],$entry['postdate'])) => '')));

$tpl->register(array(
                    'comment_comments' => sprintf($lang['comment_comments'],aseDate($config['shortdate'],$entry['postdate'])),
                    'comment_button_alt_answer' => $lang['comment_button_alt_answer'],
                    'comment_link_write_answer' => $lang['comment_link_write_answer'],
                    'comment_author' => $lang['comment_author'],
                    'comment_message' => $lang['comment_message'],
                    'comment_write_your_comment_here' => $lang['comment_write_your_comment_here'],
                    'comment_username' => $lang['comment_username'],
                    'comment_topic' => $lang['comment_topic'],
                    'comment_posticons' => $lang['comment_posticons'],
                    'comment_your_message' => $lang['comment_your_message'],
                    'comment_bold' => $lang['comment_bold'],
                    'comment_italic' => $lang['comment_italic'],
                    'comment_underline' => $lang['comment_underline'],
                    'comment_url' => $lang['comment_url'],
                    'comment_email' => $lang['comment_email'],
                    'comment_code' => $lang['comment_code'],
                    'comment_quote' => $lang['comment_quote'],
                    'comment_center' => $lang['comment_center'],
                    'comment_line' => $lang['comment_line'],
                    'comment_click_smilies' => $lang['comment_click_smilies'],
                    'comment_check_message_length' => $lang['comment_check_message_length'],
                    'comment_add_comment_btn' => $lang['comment_add_comment_btn'],
                    'comment_reset_btn' => $lang['comment_reset_btn'],
                    'comment_not_allowed_to_post_comment' => $lang['comment_not_allowed_to_post_comment']));

$tpl->parseIf('main', 'user_can_post_comments');
$tpl->parseIf('main', 'user_can_post_no_comments');

$tpl->register('query', showQueries($develope));
$tpl->register('header', $tpl->pget('header'));

$tpl->register('footer', $tpl->pget('footer'));
$tpl->pprint('main'); 
?>
