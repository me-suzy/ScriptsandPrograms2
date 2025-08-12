<?php
// -------------------------------------------------------------
//
// $Id: list.php,v 1.5 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT category_level
		FROM ' . TABLE_CATEGORIES . '
		WHERE category_id = \'' . $_GET['category_id'] . '\'');
$table_categories = $sql->fetch();
if ($table_categories['category_level'] >= 1)
{
	$sql->query('SELECT allow_html, posts_per_page, threads_per_page
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();

	if (!$_GET['page'])
	{
		$_GET['page'] = 1;
	}
	if (!$_GET['list'])
	{
		$_GET['list'] = 0;
	}
	$threads_per_page = $table_settings['threads_per_page'];
	$threads_offset = (($_GET['page'] - 1) * $threads_per_page);
	$sql->query('SELECT DISTINCT thread_id
			FROM ' . TABLE_POSTS . '
			WHERE category_id = \'' . $_GET['category_id'] . '\'');
	$num_threads = $sql->num_rows();
	$num_pages = ceil($num_threads / $threads_per_page);
	if ($_GET['page'] != 1)
	{
		if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
		{
			$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
		}
		else
		{
			$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
		}
	}
	if ($_GET['list'] != 0)
	{
		$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
	}
	for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
	{
		if ($_GET['page'] == $current_page)
		{
			$pages_list .= $_GET['page'] . ' ';
		}
		else
		{
			$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
		}
	}
	if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
	{
		$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
	}
	if (($num_pages > 1) && ($_GET['page'] != $num_pages))
	{
		if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
		{
			$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
		}
		else
		{
			$pages_list .= '<a href="./../posts/list.php?category_id=' . $_GET['category_id'] . '&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
		}
	}
	$creation_first = array();
	$creation_last = array();
	$first_id = array();
	$first_name = array();
	$folder_image = array();
	$last_id = array();
	$last_name = array();
	$sql->query('SELECT ' . TABLE_POSTS . '.post_creation, ' . TABLE_POSTS . '.thread_id, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
			FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
			WHERE ' . TABLE_POSTS . '.user_id = ' . TABLE_USERS . '.user_id
			ORDER BY post_creation');
	while ($table_posts = $sql->fetch())
	{
		if (!isset($creation_first[$table_posts['thread_id']]))
		{
			$creation_first[$table_posts['thread_id']] = $table_posts['post_creation'];
		}
		if (!isset($first_id[$table_posts['thread_id']]))
		{
			$first_id[$table_posts['thread_id']] = $table_posts['user_id'];
		}
		if (!isset($first_name[$table_posts['thread_id']]))
		{
			$first_name[$table_posts['thread_id']] = $table_posts['user_name'];
		}
	}
	$sql->query('SELECT ' . TABLE_POSTS . '.post_creation, ' . TABLE_POSTS . '.thread_id, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
			FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
			WHERE ' . TABLE_POSTS . '.user_id = ' . TABLE_USERS . '.user_id
			ORDER BY post_creation DESC');
	while ($table_posts = $sql->fetch())
	{
		if (!isset($creation_last[$table_posts['thread_id']]))
		{
			$creation_last[$table_posts['thread_id']] = $table_posts['post_creation'];
		}
		if (!isset($last_id[$table_posts['thread_id']]))
		{
			$last_id[$table_posts['thread_id']] = $table_posts['user_id'];
		}
		if (!isset($last_name[$table_posts['thread_id']]))
		{
			$last_name[$table_posts['thread_id']] = $table_posts['user_name'];
		}
	}
	$sql->query('SELECT DISTINCT thread_id
			FROM ' . TABLE_POSTS . '
			WHERE category_id = \'' . $_GET['category_id'] . '\'');
	while ($table_posts = $sql->fetch())
	{
		$folder_image[$table_posts['thread_id']] = './../images/posts/folder.png';
	}
	$sql->query('SELECT DISTINCT ' . TABLE_POSTS . '.thread_id
			FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
			WHERE ' . TABLE_POSTS . '.post_creation > ' . TABLE_USERS . '.user_lastvisit AND ' . TABLE_USERS . '.user_id = \'' . $_SESSION['user_id'] . '\' AND ' . TABLE_POSTS . '.category_id = \'' . $_GET['category_id'] . '\'');
	while ($table_posts = $sql->fetch())
	{
		if ($table_posts['thread_id'])
		{
			$folder_image[$table_posts['thread_id']] = './../images/posts/folder_new.png';
		}
	}
	$sql->query('SELECT DISTINCT thread_id
			FROM ' . TABLE_POSTS . '
			WHERE post_active = \'0\' AND category_id = \'' . $_GET['category_id'] . '\'');
	while ($table_posts = $sql->fetch())
	{
		$folder_image[$table_posts['thread_id']] = './../images/posts/closed.png';
	}
	if ($table_settings['allow_html'] == 0)
	{
		$html_support = $lang['HTML_DISABLED'];
	}
	else
	{
		$html_support = $lang['HTML_ENABLED'];
	}
	$date_format = get_date_format();
	$date_offset = get_date_offset();
	$template->set_file('list', 'posts/list.tpl');
	$template->set_block('list', 'THREADS_BLOCK', 'threads');
	$sql->query('SELECT MAX(' . TABLE_POSTS . '.post_creation) AS last_post, ' . TABLE_POSTS . '.post_subject, ' . TABLE_POSTS . '.thread_id, COUNT(' . TABLE_POSTS . '.thread_id) AS num_posts
			FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
			WHERE ' . TABLE_POSTS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_POSTS . '.category_id = \'' . $_GET['category_id'] . '\'
			GROUP BY post_subject, thread_id
			ORDER BY last_post DESC
			LIMIT ' . $threads_offset . ', ' . $threads_per_page . '');
	while ($table_posts = $sql->fetch())
	{
		$first_creation = date($date_format, ($creation_first[$table_posts['thread_id']] + $date_offset));
		$last_creation = date($date_format, ($creation_last[$table_posts['thread_id']] + $date_offset));
		$template->set_var(array(
			'FIRST_CREATION' => $first_creation,
			'FIRST_ID' => $first_id[$table_posts['thread_id']],
			'FIRST_NAME' => $first_name[$table_posts['thread_id']],
			'FOLDER_IMAGE' => $folder_image[$table_posts['thread_id']],
			'LAST_CREATION' => $last_creation,
			'LAST_ID' => $last_id[$table_posts['thread_id']],
			'LAST_LIST' => ceil((ceil($table_posts['num_posts'] / $table_settings['posts_per_page'])) / PAGES_LIMIT) - 1,
			'LAST_NAME' => $last_name[$table_posts['thread_id']],
			'LAST_PAGE' => ceil($table_posts['num_posts'] / $table_settings['posts_per_page']),
			'NUM_REPLIES' => $table_posts['num_posts'] - 1,
			'POST_SUBJECT' => $table_posts['post_subject'],
			'THREAD_ID' => $table_posts['thread_id']));
		$template->parse('threads', 'THREADS_BLOCK', true);
		$threads_exist = 'true';
	}

	$template->set_var(array(
		'ADD' => $lang['ADD'],
		'BACK_CATEGORIES_INDEX' => $lang['BACK_CATEGORIES_INDEX'],
		'BACK_HOME' => $lang['BACK_HOME'],
		'CATEGORY_ID' => $_GET['category_id'],
		'FORM_POST_SUBJECT' => $lang['FORM_POST_SUBJECT'],
		'FORM_POST_TEXT' => $lang['FORM_POST_TEXT'],
		'HTML_SUPPORT' => $html_support,
		'POSTS_LIST_FIRST' => $lang['POSTS_LIST_FIRST'],
		'POSTS_LIST_HEADER' => $lang['POSTS_LIST_HEADER'],
		'POSTS_LIST_LAST' => $lang['POSTS_LIST_LAST'],
		'POSTS_LIST_PAGE' => $lang['POSTS_LIST_PAGE'],
		'POSTS_LIST_REPLIES' => $lang['POSTS_LIST_REPLIES'],
		'POSTS_LIST_THREADS' => $lang['POSTS_LIST_THREADS'],
		'SMILIES_LIST' => get_smilies_list()));

	if ($threads_exist == 'true')
	{
		$template->set_var('POSTS_LIST_PAGES', sprintf($lang['POSTS_LIST_PAGES'], $pages_list));
	}
	else
	{
		$template->set_var('POSTS_LIST_PAGES', $lang['POSTS_LIST_ERROR1']);
	}
}
else
{
	error_template($lang['POSTS_LIST_ERROR2']);
}

page_header($lang['POSTS_LIST_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'list');
page_footer();

?>