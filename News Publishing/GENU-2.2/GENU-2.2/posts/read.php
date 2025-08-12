<?php
// -------------------------------------------------------------
//
// $Id: read.php,v 1.5 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT allow_html, posts_per_page
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
$posts_per_page = $table_settings['posts_per_page'];
$posts_offset = (($_GET['page'] - 1) * $posts_per_page);
$sql->query('SELECT post_subject
		FROM ' . TABLE_POSTS . '
		WHERE thread_id = \'' . $_GET['thread_id'] . '\'');
$num_posts = $sql->num_rows();
$num_pages = ceil($num_posts / $posts_per_page);
if ($_GET['page'] != 1)
{
	if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
	{
		$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
}
if ($_GET['list'] != 0)
{
	$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
}
for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
{
	if ($_GET['page'] == $current_page)
	{
		$pages_list .= $_GET['page'] . ' ';
	}
	else
	{
		$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
	}
}
if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
{
	$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
}
if (($num_pages > 1) && ($_GET['page'] != $num_pages))
{
	if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
	{
		$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../posts/read.php?category_id=' . $_GET['category_id'] . '&amp;thread_id=' . $_GET['thread_id'] . '&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
}
if ($table_settings['allow_html'] == 0)
{
	$html_support = $lang['HTML_DISABLED'];
}
else
{
	$html_support = $lang['HTML_ENABLED'];
}
//
$date_format = get_date_format();
$date_offset = get_date_offset();
$sql->query('SELECT user_lastvisit, user_level
		FROM ' . TABLE_USERS . '
		WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
$table_users = $sql->fetch();
$template->set_file('read', 'posts/read.tpl');
$template->set_block('read', 'POSTS_BLOCK', 'posts');
$sql->query('SELECT ' . TABLE_POSTS . '.post_id, ' . TABLE_POSTS . '.post_subject, ' . TABLE_POSTS . '.post_text, ' . TABLE_POSTS . '.post_creation, ' . TABLE_POSTS . '.post_edition, ' . TABLE_POSTS . '.thread_id, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
		FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_POSTS . '.category_id = \'' . $_GET['category_id'] . '\' AND ' . TABLE_POSTS . '.thread_id = \'' . $_GET['thread_id'] . '\' AND ' . TABLE_POSTS . '.user_id = ' . TABLE_USERS . '.user_id
		ORDER BY post_creation
		LIMIT ' . $posts_offset . ', ' . $posts_per_page . '');
while($table_posts = $sql->fetch())
{
	$post_creation = date($date_format, ($table_posts['post_creation'] + $date_offset));
	$post_edition = date($date_format, ($table_posts['post_edition'] + $date_offset));
	if ($table_posts['post_edition'])
	{
		$post_edition = sprintf($lang['POSTS_READ_EDITION'], $post_edition);
	}
	else
	{
		$post_edition = '';
	}
	if ($table_posts['post_creation'] > $table_users['user_lastvisit'])
	{
		$subject_class = 'newItem';
	}
	else
	{
		$subject_class = 'oldItem';
	}
	if (($table_posts['user_id'] == $_SESSION['user_id']) && ($table_users['user_level'] != 4))
	{
		$posts_read_edit = '<a href="./../posts/edit.php?category_id=' . $_GET['category_id'] . '&amp;post_id=' . $table_posts['post_id'] . '"><img src="./../images/posts/edit.png" alt="." title="' . $lang['POSTS_READ_EDIT'] . '" /></a>';
	}
	elseif ($table_users['user_level'] == 4)
	{
		$posts_read_edit = '<a href="./../admin/index.php?action=edit_post&amp;post_id=' . $table_posts['post_id'] . '"><img src="./../images/posts/edit.png" alt="." title="' . $lang['POSTS_READ_EDIT'] . '" /></a>';
	}
	else
	{
		$posts_read_edit = '';
	}
	if ($previous_subject != $table_posts['post_subject'] && $_GET['page'] < 2)
	{
		$post_subject = $table_posts['post_subject'];
	}
	else
	{
		$post_subject = $lang['POSTS_READ_PREVIOUS'] . $table_posts['post_subject'];
	}
	$table_posts['post_text'] = str_replace("\n", '<br />', $table_posts['post_text']);
	$table_posts['post_text'] = str_replace("\r", '', $table_posts['post_text']);
	$table_posts['post_text'] = preg_replace('#(\s*)([^>]{' . WORD_WRAP . ',})(<|$)#e', "'\\1' . wordwrap('\\2', WORD_WRAP, ' ', 1) . '\\3'", $table_posts['post_text']);
	$template->set_var(array(
		'ADD' => $lang['ADD'],
		'BACK_CATEGORIES_INDEX' => $lang['BACK_CATEGORIES_INDEX'],
		'BACK_HOME' => $lang['BACK_HOME'],
		'BACK_THREADS_INDEX' => $lang['BACK_THREADS_INDEX'],
		'CATEGORY_ID' => $_GET['category_id'],
		'FORM_POST_TEXT' => $lang['FORM_POST_TEXT'],
		'HIDDEN_SUBJECT' => $table_posts['post_subject'],
		'HTML_SUPPORT' => $html_support,
		'POST_EDITION' => $post_edition,
		'POST_ID' => $table_posts['post_id'],
		'POST_SUBJECT' => $post_subject,
		'POST_TEXT' => $table_posts['post_text'],
		'POSTS_READ_EDIT' => $posts_read_edit,
		'POSTS_READ_HEADER' => $lang['POSTS_READ_HEADER'],
		'POSTS_READ_RELEASE' => sprintf($lang['POSTS_READ_RELEASE'], $table_posts['user_id'], $table_posts['user_name'], $post_creation),
		'SUBJECT_CLASS' => $subject_class,
		'THREAD_ID' => $table_posts['thread_id']));
	$template->parse('posts', 'POSTS_BLOCK', true);
	$previous_subject = $table_posts['post_subject'];
	$posts_exist = 'true';
}

if ($posts_exist == 'true')
{
	$template->set_var(array(
		'POSTS_READ_PAGES' => sprintf($lang['POSTS_READ_PAGES'], $pages_list),
		'SMILIES_LIST' => get_smilies_list()));
}
else
{
	error_template($lang['POSTS_READ_ERROR']);
}

page_header($lang['POSTS_READ_TITLE']);
$template->pparse('', 'error');
if ($posts_exist == 'true')
{
	$template->pparse('', 'read');
}
page_footer();

?>