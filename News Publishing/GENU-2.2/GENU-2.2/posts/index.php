<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.4 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$creation_last = array();
$id_last = array();
$folder_image = array();
$name_last = array();
$num_threads = array();
$sql->query('SELECT DISTINCT thread_id, category_id
		FROM ' . TABLE_POSTS . '');
while ($table_posts = $sql->fetch())
{
	if ($table_posts['category_id'])
	{
		$num_threads[$table_posts['category_id']]++;
	}
}
$sql->query('SELECT ' . TABLE_POSTS . '.category_id, ' . TABLE_POSTS . '.post_creation, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
		FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_POSTS . '.user_id = ' . TABLE_USERS . '.user_id
		ORDER BY post_creation DESC');
while ($table_posts = $sql->fetch())
{
	if (!isset($creation_last[$table_posts['category_id']]))
	{
		$creation_last[$table_posts['category_id']] = $table_posts['post_creation'];
	}
	if (!isset($id_last[$table_posts['category_id']]))
	{
		$id_last[$table_posts['category_id']] = $table_posts['user_id'];
	}
	if (!isset($name_last[$table_posts['category_id']]))
	{
		$name_last[$table_posts['category_id']] = $table_posts['user_name'];
	}
}
$sql->query('SELECT category_id
		FROM ' . TABLE_CATEGORIES . '
		WHERE category_level != \'0\'');
while ($table_categories = $sql->fetch())
{
	$folder_image[$table_categories['category_id']] = './../images/posts/folder.png';
}
$sql->query('SELECT ' . TABLE_POSTS . '.category_id
		FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_POSTS . '.post_creation > ' . TABLE_USERS . '.user_lastvisit AND ' . TABLE_USERS . '.user_id = \'' . $_SESSION['user_id'] . '\'');
while ($table_posts = $sql->fetch())
{
	if ($table_posts['category_id'])
	{
		$folder_image[$table_posts['category_id']] = './../images/posts/folder_new.png';
	}
}
$date_format = get_date_format();
$date_offset = get_date_offset();
$template->set_file('index', 'posts/index.tpl');
$template->set_block('index', 'CATEGORIES_BLOCK', 'categories');
$sql->query('SELECT category_id, category_name, category_posts
		FROM ' . TABLE_CATEGORIES . '
		WHERE category_level != \'0\'
		ORDER BY category_name');
while ($table_categories = $sql->fetch())
{
	if ($id_last[$table_categories['category_id']])
	{
		$last_creation = date($date_format, ($creation_last[$table_categories['category_id']] + $date_offset));
		$last_id = $id_last[$table_categories['category_id']];
		$last_name = $name_last[$table_categories['category_id']];
	}
	else
	{
		$last_creation = $last_id = $last_name = '';
	}
	$template->set_var(array(
		'CATEGORY_ID' => $table_categories['category_id'],
		'CATEGORY_NAME' => $table_categories['category_name'],
		'CATEGORY_POSTS' => $table_categories['category_posts'],
		'FOLDER_IMAGE' => $folder_image[$table_categories['category_id']],
		'LAST_CREATION' => $last_creation,
		'LAST_ID' => $last_id,
		'LAST_NAME' => $last_name,
		'NUM_THREADS' => $num_threads[$table_categories['category_id']],
		'POSTS_INDEX_CATEGORIES' => $lang['POSTS_INDEX_CATEGORIES'],
		'POSTS_INDEX_LAST' => $lang['POSTS_LIST_LAST'],
		'POSTS_INDEX_POSTS' => $lang['POSTS_INDEX_POSTS'],
		'POSTS_INDEX_THREADS' => $lang['POSTS_INDEX_THREADS']));
	$template->parse('categories', 'CATEGORIES_BLOCK', true);
	$categories_exist = 'true';
}

if ($categories_exist == 'true')
{
	$template->set_var('BACK_HOME', $lang['BACK_HOME']);
}
else
{
	error_template($lang['POSTS_INDEX_ERROR']);
}

page_header($lang['POSTS_INDEX_TITLE']);
$template->pparse('', 'error');
if ($categories_exist == 'true')
{
	$template->pparse('', 'index');
}
page_footer();

?>