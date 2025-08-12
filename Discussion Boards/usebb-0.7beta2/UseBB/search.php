<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/search.php,v 1.29 2005/09/15 15:46:45 pc_freak Exp $
	
	This file is part of UseBB.
	
	UseBB is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	UseBB is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with UseBB; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Search engine
 *
 * Shows the search form, takes a search query and shows appropriate results.
 *
 * @author	UseBB Team
 * @link	http://www.usebb.net
 * @license	GPL-2
 * @version	$Revision: 1.29 $
 * @copyright	Copyright (C) 2003-2005 UseBB Team
 * @package	UseBB
 */

define('INCLUDED', true);
define('ROOT_PATH', './');

//
// Include usebb engine
//
require(ROOT_PATH.'sources/common.php');

//
// Update and get the session information
//
$session->update('search');

//
// Include the page header
//
require(ROOT_PATH.'sources/page_head.php');

$template->set_page_title($lang['Search']);

//
// Get a list of forums the user is allowed to view
//
$result = $db->query("SELECT id, name, auth FROM ".TABLE_PREFIX."forums");

$forum_ids = $forum_names = array();
while ( $forumdata = $db->fetch_result($result) ) {
	
	//
	// Place permitted forums into the arrays
	//
	if ( $functions->auth($forumdata['auth'], 'read', $forumdata['id']) ) {
		
		$forum_ids[] = $forumdata['id'];
		$forum_names[$forumdata['id']] = $forumdata['name'];
		
	}
	
}

$_REQUEST['mode'] = ( !empty($_REQUEST['mode']) && ( $_REQUEST['mode'] === 'and' || $_REQUEST['mode'] === 'or' ) ) ? $_REQUEST['mode'] : 'and';

//
// Sanatize the keywords, removing any too short words
//
if ( !empty($_REQUEST['keywords']) ) {
	
	$keywords = preg_split('#\s+#', $_REQUEST['keywords']);
	$sanatized_keywords = array();
	foreach ( $keywords as $keyword ) {
		
		if ( strlen($keyword) >= $functions->get_config('search_nonindex_words_min_length') )
			$sanatized_keywords[] = $keyword;
		
	}
	$_REQUEST['keywords'] = join(' ', $sanatized_keywords);
	
}

//
// Sanatize the forums array
//
if ( !empty($_REQUEST['forums']) && is_array($_REQUEST['forums']) && count($_REQUEST['forums']) ) {
	
	$sanatized_forums = array();
	foreach ( $_REQUEST['forums'] as $forum ) {
		
		if ( $forum === 'all' || ( valid_int($forum) && in_array($forum, $forum_ids) ) )
			$sanatized_forums[] = $forum;
		
	}
	$_REQUEST['forums'] = $sanatized_forums;
	
} elseif ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
	
	$_REQUEST['forums'] = $forum_ids;
	
} else {
	
	$_REQUEST['forums'] = array();
	
}

if ( !count($forum_ids) ) {
	
	$template->parse('msgbox', 'global', array(
		'box_title' => $lang['Note'],
		'content' => $lang['NoViewableForums']
	));
	
} elseif ( ( !empty($_REQUEST['keywords']) || !empty($_REQUEST['author']) ) && count($_REQUEST['forums']) ) {
	
	$query_where_parts = array();
	
	if ( !empty($_REQUEST['keywords']) ) {
		
		$keywords = split(' ', $_REQUEST['keywords']);
		foreach ( $keywords as $key => $val )
			$keywords[$key] = "( p.content LIKE '%".preg_replace(array('#%#', '#_#'), array('\%', '\_'), $val)."%' OR t.topic_title LIKE '%".preg_replace(array('#%#', '#_#'), array('\%', '\_'), $val)."%' )";
		$query_where_parts[] = ' ( '.join(' '.strtoupper($_REQUEST['mode']).' ', $keywords).' ) ';
		
	}
	
	if ( !empty($_REQUEST['author']) ) {
		
		$author = preg_replace(array('#%#', '#_#', '#\s+#'), array('\%', '\_', ' '), $_REQUEST['author']);
		$query_where_parts[] = "( m.displayed_name LIKE '%".$author."%' OR p.poster_guest LIKE '%".$author."%' )";
		
	}
	
	if ( in_array('all', $_REQUEST['forums']) )
		$query_where_parts[] = "f.id IN(".join(', ', $forum_ids).")";
	else
		$query_where_parts[] = "f.id IN(".join(', ', $_REQUEST['forums']).")";
	
	$query = "SELECT DISTINCT t.id FROM ".TABLE_PREFIX."posts p LEFT JOIN ".TABLE_PREFIX."members m ON p.poster_id = m.id, ".TABLE_PREFIX."topics t, ".TABLE_PREFIX."forums f WHERE t.id = p.topic_id AND f.id = t.forum_id AND ".join(' AND ', $query_where_parts)." LIMIT ".$functions->get_config('search_limit_results');
	$result = $db->query($query);
	$topic_ids = array();
	while ( $searchdata = $db->fetch_result($result) )
		$topic_ids[] = $searchdata['id'];
	
	if ( count($topic_ids) ) {
		
		$result_data = array(
			'keywords' => ( !empty($_REQUEST['keywords']) ) ? $_REQUEST['keywords'] : '',
			'mode' => $_REQUEST['mode'],
			'author' => ( !empty($_REQUEST['author']) ) ? $_REQUEST['author'] : '',
			'results' => $topic_ids
		);
		$result_data = addslashes(serialize($result_data));
		$result = $db->query("SELECT COUNT(*) as exist FROM ".TABLE_PREFIX."searches WHERE sess_id = '".session_id()."'");
		$searchdata = $db->fetch_result($result);
		if ( $searchdata['exist'] )
			$db->query("UPDATE ".TABLE_PREFIX."searches SET time = ".time().", results = '".$result_data."' WHERE sess_id = '".session_id()."'");
		else
			$db->query("INSERT INTO ".TABLE_PREFIX."searches VALUES ('".session_id()."', ".time().", '".$result_data."')");
		
		$functions->redirect('search.php', array('act' => 'results'));
		
	} else {
		
		$template->parse('msgbox', 'global', array(
			'box_title' => $lang['Note'],
			'content' => $lang['NoSearchResults']
		));
		
	}
	
} else {
	
	if ( !empty($_GET['act']) && $_GET['act'] == 'results' ) {
		
		$result = $db->query("SELECT results FROM ".TABLE_PREFIX."searches WHERE sess_id = '".session_id()."'");
		$search_results = $db->fetch_result($result);
		
		if ( !empty($search_results['results']) ) {
			
			$search_results = unserialize(stripslashes($search_results['results']));
			
			//
			// Get page number
			//
			$numpages = ceil(intval(count($search_results['results'])) / $functions->get_config('topics_per_page'));
			$page = ( !empty($_GET['page']) && valid_int($_GET['page']) && intval($_GET['page']) <= $numpages ) ? intval($_GET['page']) : 1;
			$limit_start = ( $page - 1 ) * $functions->get_config('topics_per_page');
			$limit_end = $functions->get_config('topics_per_page');
			$page_links = $functions->make_page_links($numpages, $page, count($search_results['results']), $functions->get_config('topics_per_page'), 'search.php', NULL, true, array('act' => 'results'));
			
			$template->parse('results_header', 'search', array(
				'page_links' => $page_links,
				'keywords' => unhtml(stripslashes($search_results['keywords'])),
				'mode' => ( $search_results['mode'] == 'and' ) ? $lang['AllKeywords'] : $lang['OneOrMoreKeywords'],
				'author' => unhtml(stripslashes($search_results['author'])),
			));
			
			$result = $db->query("SELECT t.id, t.forum_id, t.topic_title, t.last_post_id, t.count_replies, t.count_views, t.status_locked, t.status_sticky, p.poster_guest, p2.poster_guest AS last_poster_guest, p2.post_time AS last_post_time, u.id AS poster_id, u.displayed_name AS poster_name, u.level AS poster_level, u2.id AS last_poster_id, u2.displayed_name AS last_poster_name, u2.level AS last_poster_level FROM ".TABLE_PREFIX."topics t, ".TABLE_PREFIX."posts p LEFT JOIN ".TABLE_PREFIX."members u ON p.poster_id = u.id, ".TABLE_PREFIX."posts p2 LEFT JOIN ".TABLE_PREFIX."members u2 ON p2.poster_id = u2.id WHERE t.id IN(".join(', ', $search_results['results']).") AND t.forum_id IN(".join(', ', $forum_ids).") AND p.id = t.first_post_id AND p2.id = t.last_post_id ORDER BY p2.post_time DESC LIMIT ".$limit_start.", ".$limit_end);
			
			while ( $topicdata = $db->fetch_result($result) ) {
				
				//
				// Loop through the topics, generating output...
				//
				$topic_name = '<a href="'.$functions->make_url('topic.php', array('id' => $topicdata['id'])).'">'.unhtml($functions->replace_badwords(stripslashes($topicdata['topic_title']))).'</a>';
				if ( $topicdata['status_sticky'] )
					$topic_name = $lang['Sticky'].': '.$topic_name;
				$last_post_author = ( $topicdata['last_poster_id'] > LEVEL_GUEST ) ? $functions->make_profile_link($topicdata['last_poster_id'], $topicdata['last_poster_name'], $topicdata['last_poster_level']) : unhtml(stripslashes($topicdata['last_poster_guest']));
				
				list($topic_icon, $topic_status) = $functions->topic_icon($topicdata['id'], $topicdata['status_locked'], $topicdata['last_post_time']);
				
				if ( $topic_status == $lang['NewPosts'] || $topic_status == $lang['LockedNewPosts'] ) {
					
					$topic_name = sprintf($template->get_config('newpost_link_format'), $functions->make_url('topic.php', array('id' => $topicdata['id'], 'act' => 'getnewpost')).'#newpost', 'templates/'.$functions->get_config('template').'/gfx/'.$template->get_config('newpost_link_icon'), $topic_status) . $topic_name;
					
				}
				
				//
				// Parse the topic template
				//
				$template->parse('results_topic', 'search', array(
					'topic_icon' => $topic_icon,
					'topic_status' => $topic_status,
					'topic_name' => $topic_name,
					'topic_page_links' => ( $topicdata['count_replies']+1 > $functions->get_config('posts_per_page') ) ? $functions->make_page_links(ceil(intval($topicdata['count_replies']+1) / $functions->get_config('posts_per_page')), '0', $topicdata['count_replies']+1, $functions->get_config('posts_per_page'), 'topic.php', $topicdata['id'], false) : '',
					'forum' => '<a href="'.$functions->make_url('forum.php', array('id' => $topicdata['forum_id'])).'">'.unhtml(stripslashes($forum_names[$topicdata['forum_id']])).'</a>',
					'author' => ( $topicdata['poster_id'] > LEVEL_GUEST ) ? $functions->make_profile_link($topicdata['poster_id'], $topicdata['poster_name'], $topicdata['poster_level']) : unhtml(stripslashes($topicdata['poster_guest'])),
					'replies' => $topicdata['count_replies'],
					'views' => $topicdata['count_views'],
					'author_date' => sprintf($lang['AuthorDate'], $last_post_author, $functions->make_date($topicdata['last_post_time'])),
					'by_author' => sprintf($lang['ByAuthor'], $last_post_author),
					'on_date' => sprintf($lang['OnDate'], $functions->make_date($topicdata['last_post_time'])),
					'last_post_url' => $functions->make_url('topic.php', array('post' => $topicdata['last_post_id'])).'#post'.$topicdata['last_post_id']
				));
				
			}
			
			$template->parse('results_footer', 'search', array(
				'page_links' => $page_links,
				'keywords' => unhtml(stripslashes($search_results['keywords'])),
				'mode' => ( $search_results['mode'] == 'and' ) ? $lang['AllKeywords'] : $lang['OneOrMoreKeywords'],
				'author' => unhtml(stripslashes($search_results['author'])),
			));
			
		} else {
			
			$functions->redirect('search.php');
			
		}
		
	} else {
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			
			$keywords = ( !empty($_REQUEST['keywords']) ) ? unhtml(stripslashes($_REQUEST['keywords'])) : '';
			$author = ( !empty($_REQUEST['author']) ) ? unhtml(stripslashes($_REQUEST['author'])) : '';
			$mode_and_checked = ( $_REQUEST['mode'] == 'and' ) ? ' checked="checked"' : '';
			$mode_or_checked = ( $_REQUEST['mode'] == 'or' ) ? ' checked="checked"' : '';
			
			$forums_all_selected = ( in_array('all', $_REQUEST['forums']) ) ? ' selected="selected"' : '';
			
			$errors = array();
			if ( empty($_REQUEST['keywords']) && empty($_REQUEST['author']) )
				$errors[] = $lang['SearchKeywords'];
			if ( !count($_REQUEST['forums']) )
				$errors[] = $lang['SearchForums'];
			
			if ( count($errors) ) {
				
				$template->parse('msgbox', 'global', array(
					'box_title' => $lang['Error'],
					'content' => sprintf($lang['MissingFields'], join(', ', $errors))
				));
				
			}
			
		} else {
			
			$keywords = '';
			$author = '';
			$mode_and_checked = ' checked="checked"';
			$mode_or_checked = '';
			$forums_all_selected = ' selected="selected"';
			
		}
		
		if ( count($forum_ids) === 1 ) {
			
			$forums_input = '<input type="hidden" name="forums[]" value="'.$forum_ids[0].'" /><em>'.unhtml(stripslashes($forum_names[$forum_ids[0]])).'</em> ('.$lang['AllForums'].')';
			
		} else {
			
			$forums_input = '<select name="forums[]" size="10" multiple="multiple"><option value="all"'.$forums_all_selected.'>'.$lang['AllForums'].'</option>';
			$seen_cats = array();
			$result = $db->query("SELECT c.id AS cat_id, c.name AS cat_name, f.id FROM ".TABLE_PREFIX."cats c, ".TABLE_PREFIX."forums f WHERE c.id = f.cat_id AND f.id IN( ".join(', ', $forum_ids)." ) ORDER BY c.sort_id ASC, c.name ASC, f.sort_id ASC, f.name ASC");
			while ( $forumdata = $db->fetch_result($result) ) {
				
				if ( !in_array($forumdata['cat_id'], $seen_cats) ) {
					
					$forums_input .= ( !count($seen_cats) ) ? '' : '</optgroup>';
					$forums_input .= '<optgroup label="'.unhtml(stripslashes($forumdata['cat_name'])).'">';
					$seen_cats[] = $forumdata['cat_id'];
					
				}
				
				$selected = ( empty($forums_all_selected) && in_array($forumdata['id'], $_REQUEST['forums']) ) ? ' selected="selected"' : '';
				$forums_input .= '<option value="'.$forumdata['id'].'"'.$selected.'>'.unhtml(stripslashes($forum_names[$forumdata['id']])).'</option>';
				
			}
			$forums_input .= '</optgroup></select>';
			
		}
		
		$template->parse('search_form', 'search', array(
			'form_begin' => '<form action="'.$functions->make_url('search.php').'" method="post">',
			'keywords_input' => '<input type="text" name="keywords" id="keywords" size="35" value="'.$keywords.'" />',
			'keywords_explain' => sprintf($lang['KeywordsExplain'], $functions->get_config('search_nonindex_words_min_length')),
			'mode_input' => '<input type="radio" name="mode" id="mode_and" value="and"'.$mode_and_checked.' /><label for="mode_and"> '.$lang['AllKeywords'].'</label> <input type="radio" name="mode" id="mode_or" value="or"'.$mode_or_checked.' /><label for="mode_or"> '.$lang['OneOrMoreKeywords'].'</label>',
			'author_input' => '<input type="text" name="author" size="35" value="'.$author.'" />',
			'forums_input' => $forums_input,
			'submit_button' => '<input type="submit" value="'.$lang['Search'].'" />',
			'reset_button' => '<input type="reset" value="'.$lang['Reset'].'" />',
			'form_end' => '</form>'
		));
		$template->set_js_onload("set_focus('keywords')");
		
	}
	
}

//
// Include the page footer
//
require(ROOT_PATH.'sources/page_foot.php');

?>
