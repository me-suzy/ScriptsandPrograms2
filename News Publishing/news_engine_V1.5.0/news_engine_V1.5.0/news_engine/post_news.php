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
|   > Newsposting Formular 
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: post_news.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
if(!$config['wysiwyg_user']) $tpl->register("header_add","<script language=\"Javascript\" src=\"admin/includes/bbcode/bbcode.js\"></script>\n<script language=\"Javascript\" src=\"admin/includes/bbcode/bbcode_language".$lang['php_bb_code_add_on'].".js\"></script>");

if ($auth->user['canpostnews'] != 1) {
	rideSite($sess->url('index.php'), $lang['misc_2']);
    exit;
}
   
$news_poststatus = 0;	   
if (isset($_POST['news']) && $_POST['news']=="send") {
    if(empty($_POST['headline']) || empty($_POST['hometext'])) {
        rideSite($sess->url('post_news.php'), $lang['rec_error39']);
        exit();    
    } else {
        $message = "";    
        if($config['direct_news'] == "1") {
            $news_poststatus = 1;
            $message = $lang['rec_error41'];
        } else {
            if($auth->user['groupid'] == 1) {
                $news_poststatus = 1;
                $message = $lang['rec_error41'];
            } else {
                $news_poststatus = 0;
                $message = $lang['rec_error40'];
            }		   
        } 
        
        /*if($config['wysiwyg_user']) {   
    		include_once($_ENGINE['eng_dir']."admin/enginelib/function.wysiwyg.php");
            $_POST['hometext'] = turnWysiwygIntoBbcode($_POST['hometext']);		
    		$_POST['newstext'] = turnWysiwygIntoBbcode($_POST['newstext']);		
        }*/
        $newsid = WriteNewNews($_POST['poster'],$_POST['headline'],$_POST['catid'],$_POST['hometext'],$_POST['newstext'],$_POST['comments_allowed'],$news_poststatus,$_POST['news_links'],$_POST['is_html']);    
        
        rideSite($sess->url('post_news.php?news=addlinks&newsid='.$newsid), $message);
        exit();             
    }		    	    
}

if (isset($_POST['news']) && $_POST['news']=="links") {
    WriteLinks($_POST['newsid'],$_POST['link_name'],$_POST['link_url'],$_POST['link_target']);
    rideSite($sess->url('post_news.php?news=addlinks&newsid='.$_POST['newsid']), $lang['rec_error42']);
    exit();    
}

if (!isset($_POST['news']) && !isset($_GET['news'])) {
    $tpl->loadFile('main', 'post_news.html'); 
    $tpl->register('title', $lang['title_new_news']);

	$result = $db_sql->sql_query("SELECT catid, titel FROM $newscat_table");
	$cat_loop = array();
	while ($cat = $db_sql->fetch_array($result)) {
		$post_categories .= "<option value=\"$cat[catid]\">".$cat['titel']."</option>\n\t\t\t\t";
		$cat_loop[] = array('cat_id' => $cat['catid'],
							'cat_titel' => $cat['titel']);
	}    
	$tpl->parseLoop('main', 'cat_loop');
	
	$post_user_id = $auth->user['userid'];
	$post_user = $auth->user['username'];
	
	$tpl->register(array('news_post_log_out_url' => $sess->url("misc.php?action=logout"),
						'post_user' => $auth->user['username'],
						'post_user_id' => $auth->user['userid']));
    
    if($auth->user['userid'] != 2) $logout_link = "[<a class=\"list_desc\" href=\"".$sess->url("misc.php?action=logout")."\">".$lang['php_log_out']."</a>]";
    
    if($config['wysiwyg_user']) {
    	/*include_once('admin/includes/spaw/spaw_control.class.php');
    	$sw2 = new SPAW_Wysiwyg('hometext',$art[$properties],$lang['php_mailer_lang'], 'mini2', '','100%', '100px', 'templates/'.$config['template_folder'].'/style.css');	
    	$small_editor = $sw2->show();*/    
        
        include_once($_ENGINE['eng_dir']."admin/enginelib/class.fckeditor.php") ;   
        $oFCKeditor = new FCKeditor('hometext') ;
        $oFCKeditor->BasePath = $_ENGINE['main_url']."/admin/includes/FCKeditor/";
        $oFCKeditor->Value = '';
        $oFCKeditor->ToolbarSet = 'Basic';
        $oFCKeditor->Height = "100";
        $small_editor .= $oFCKeditor->CreateHtml();        
        
    	/*$sw = new SPAW_Wysiwyg('newstext',$art[$properties],$lang['php_mailer_lang'], 'engine', '','100%', '200px', 'templates/'.$config['template_folder'].'/style.css');	
    	$editor = $sw->show();*/
        
        $oFCKeditor2 = new FCKeditor('newstext') ;
        $oFCKeditor2->BasePath = $_ENGINE['main_url']."/admin/includes/FCKeditor/";
        $oFCKeditor2->Value = $news['newstext'];
        $oFCKeditor2->Height = "350";
        $editor .= $oFCKeditor2->CreateHtml();          
		
		$tpl->register(array('small_editor' => $small_editor,
							'editor' => $editor,
                            'hidden_html' => "<input type=\"hidden\" name=\"is_html\" value=\"1\" />",
							'wysiwyg_on_submit' => 'onsubmit="return checkForm(this)"',
							'form_post' => 'post'));
		$wysiwyg_1 = true;
		$wysiwyg_2 = true;
		$no_wysiwyg_1 = false;
		$no_wysiwyg_2 = false;
    	
    } else {
        if ($config['click_smilies'] != "0") $parse_click_smilies = true;
		$tpl->register(array('form_post' => 'alp',
							'post_news_easy' => $lang['post_news_easy'],
							'post_news_widened' => $lang['post_news_widened'],
							'post_news_small' => $lang['post_news_small'],
							'post_news_middle' => $lang['post_news_middle'],
							'post_news_big' => $lang['post_news_big'],
							'post_news_hugely' => $lang['post_news_hugely'],
							'post_news_sup' => $lang['post_news_sup'],
							'post_news_sup_desc' => $lang['post_news_sup_desc'],
							'post_news_sub' => $lang['post_news_sub'],
							'post_news_sub_desc' => $lang['post_news_sub_desc'],
							'post_news_url' => $lang['post_news_url'],
							'post_news_url_desc' => $lang['post_news_url_desc'],
							'post_news_email' => $lang['post_news_email'],
							'post_news_email_desc' => $lang['post_news_email_desc'],
							'post_news_code' => $lang['post_news_code'],
							'post_news_code_desc' => $lang['post_news_code_desc'],
							'post_news_hr' => $lang['post_news_hr'],
							'post_news_hr_desc' => $lang['post_news_hr_desc'],
							'post_news_list' => $lang['post_news_list'],
							'post_news_list_desc' => $lang['post_news_list_desc'],
							'post_news_quote' => $lang['post_news_quote'],
							'post_news_quote_desc' => $lang['post_news_quote_desc'],
							'post_news_img' => $lang['post_news_img'],
							'post_news_img_desc' => $lang['post_news_img_desc'],
							'post_news_closetag' => $lang['post_news_closetag'],
							'post_news_closealltags' => $lang['post_news_closealltags'],
                            'post_news_click_smilies' => $lang['post_news_click_smilies'],
							'post_news_js1' => $lang['post_news_js1'],
							'post_news_js2' => $lang['post_news_js2'],
							'post_news_js3' => $lang['post_news_js3'],
							'post_news_js4' => $lang['post_news_js4']));		
		$wysiwyg_1 = false;
		$wysiwyg_2 = false;
		$no_wysiwyg_1 = true;
		$no_wysiwyg_2 = true;		
    }
	$tpl->parseIf('main', 'wysiwyg_1');				   
	$tpl->parseIf('main', 'wysiwyg_2');				   
	$tpl->parseIf('main', 'no_wysiwyg_1');				   
	$tpl->parseIf('main', 'no_wysiwyg_2');		
    $tpl->parseIf('main', 'parse_click_smilies');		   
	
	$tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), $lang['post_news_new_newspost'] => '')));
	$tpl->register(array('post_news_new_newspost' => $lang['post_news_new_newspost'],
						'post_news_details_to_your_newspost' => $lang['post_news_details_to_your_newspost'],
						'post_news_name' => $lang['post_news_name'],
						'post_news_log_out' => $lang['post_news_log_out'],
						'post_news_topic' => $lang['post_news_topic'],
						'post_news_category' => $lang['post_news_category'],
						'post_news_teaser_text' => $lang['post_news_teaser_text'],
						'post_news_displayed_on_mainpage' => $lang['post_news_displayed_on_mainpage'],
						'post_news_main_text' => $lang['post_news_main_text'],
						'post_news_will_be_displayed_read_more' => $lang['post_news_will_be_displayed_read_more'],
						'post_news_options' => $lang['post_news_options'],
						'post_news_comments_allowed' => $lang['post_news_comments_allowed'],
						'post_news_display_links_on_mainpage' => $lang['post_news_display_links_on_mainpage'],
						'post_news_save_news' => $lang['post_news_save_news'],
						'post_news_reset_form' => $lang['post_news_reset_form']));
}

if ($_GET['news']=='addlinks' && $_GET['newsid']) {
    $newsid = $_GET['newsid'];
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

    if ($news['is_html'] == 0) {		
        include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
        $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);
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
						'linkshow' => $linkshow,
                        'post_news_links_post_links' => $lang['post_news_links_post_links'],
                        'post_news_links_news_preview' => $lang['post_news_links_news_preview'],
                        'post_news_links_links_related_to_the_newspost' => $lang['post_news_links_links_related_to_the_newspost'],
                        'post_news_links_link_title' => $lang['post_news_links_link_title'],
                        'post_news_links_link_url' => $lang['post_news_links_link_url'],
                        'post_news_links_link_target' => $lang['post_news_links_link_target'],
                        'post_news_links_open_in_same_window' => $lang['post_news_links_open_in_same_window'],
                        'post_news_links_open_in_new_window' => $lang['post_news_links_open_in_new_window'],
                        'post_news_links_new_newspost' => $lang['post_news_links_new_newspost'],
                        'post_news_links_add_link_btn' => $lang['post_news_links_add_link_btn'],
                        'post_news_links_reset_btn' => $lang['post_news_links_reset_btn']));    
    
    $tpl->loadFile('main', 'post_news_links.html'); 
    $tpl->register('title', $lang['title_new_news']);
	$tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), $lang['post_news_links_new_newspost'] => '')));    

    $poster_id = $auth->user['userid'];
	$newsid = $_GET['newsid'];
}

$tpl->register('query', showQueries($develope));
$tpl->register('header', $tpl->pget('header'));

$tpl->register('footer', $tpl->pget('footer'));
$tpl->pprint('main');	 
?>