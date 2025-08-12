<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.5 2005/03/28 12:30:33 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT allow_html, comments_per_page
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
$sql->query('SELECT DISTINCT comment_subject
		FROM ' . TABLE_COMMENTS . '
		WHERE news_id = \'' . $_GET['news_id'] . '\'');
$num_subjects = $sql->num_rows();
if (!$num_subjects)
{
	$num_subjects = 0;
}
$sql->query('SELECT COUNT(comment_subject) AS comments_per_subject
		FROM ' . TABLE_COMMENTS . '
		WHERE news_id = \'' . $_GET['news_id'] . '\'
		GROUP BY comment_subject
		LIMIT 0, ' . ($_GET['page'] - 1) * $table_settings['comments_per_page'] . '');
while ($table_comments = $sql->fetch())
{
	$num_comments += $table_comments['comments_per_subject'];
}
if (!$num_comments)
{
	$num_comments = 0;
}
$sql->query('SELECT COUNT(comment_subject) AS comments_per_subject
		FROM ' . TABLE_COMMENTS . '
		WHERE news_id = \'' . $_GET['news_id'] . '\'
		GROUP BY comment_subject
		LIMIT ' . ($_GET['page'] - 1) * $table_settings['comments_per_page'] . ', ' . $table_settings['comments_per_page'] . '');
while ($table_comments = $sql->fetch())
{
	$comments_per_page += $table_comments['comments_per_subject'];
}
if (!$comments_per_page)
{
	$comments_per_page = 0;
}
$comments_offset = $num_comments;
$num_pages = ceil($num_subjects / $table_settings['comments_per_page']);
if ($_GET['page'] != 1)
{
	if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
	{
		$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
}
if ($_GET['list'] != 0)
{
	$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
}
for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
{
	if ($_GET['page'] == $current_page)
	{
		$pages_list .= $_GET['page'] . ' ';
	}
	else
	{
		$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
	}
}
if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
{
	$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
}
if (($num_pages > 1) && ($_GET['page'] != $num_pages))
{
	if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
	{
		$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../comments/index.php?news_id=' . $_GET['news_id'] . '&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
}
/********************/
/***** Comments *****/
/********************/
$date_format = get_date_format();
$date_offset = get_date_offset();
$sql->query('SELECT user_lastvisit, user_level
		FROM ' . TABLE_USERS . '
		WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
$table_users = $sql->fetch();
$template->set_file('index', 'comments/index.tpl');
$template->set_block('index', 'COMMENTS_BLOCK', 'comments');
$sql->query('SELECT ' . TABLE_COMMENTS . '.comment_id, ' . TABLE_COMMENTS . '.comment_subject, ' . TABLE_COMMENTS . '.comment_text, ' . TABLE_COMMENTS . '.comment_creation, ' . TABLE_COMMENTS . '.comment_edition, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
		FROM ' . TABLE_COMMENTS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_COMMENTS . '.news_id = \'' . $_GET['news_id'] . '\' AND ' . TABLE_COMMENTS . '.user_id = ' . TABLE_USERS . '.user_id
		ORDER BY reply_id, comment_creation
		LIMIT ' . $comments_offset . ', ' . $comments_per_page . '');
while($table_comments = $sql->fetch())
{
	$comment_creation = date($date_format, ($table_comments['comment_creation'] + $date_offset));
	$comment_edition = date($date_format, ($table_comments['comment_edition'] + $date_offset));
	if ($table_comments['comment_edition'])
	{
		$comment_edition = sprintf($lang['COMMENTS_INDEX_EDITION'], $comment_edition);
	}
	else
	{
		$comment_edition = '';
	}
	if ($table_comments['comment_creation'] > $table_users['user_lastvisit'])
	{
		$subject_class = 'newItem';
	}
	else
	{
		$subject_class = 'oldItem';
	}
	if (($table_comments['user_id'] == $_SESSION['user_id']) && ($table_users['user_level'] != 4))
	{
		$comments_index_edit = '<a href="./../comments/edit.php?news_id=' . $_GET['news_id'] . '&amp;comment_id=' . $table_comments['comment_id'] . '"><img src="./../images/comments/edit.png" alt="." title="' . $lang['COMMENTS_INDEX_EDIT'] . '" /></a>';
	}
	elseif ($table_users['user_level'] == 4)
	{
		$comments_index_edit = '<a href="./../admin/index.php?action=edit_comment&amp;comment_id=' . $table_comments['comment_id'] . '"><img src="./../images/comments/edit.png" alt="." title="' . $lang['COMMENTS_INDEX_EDIT'] . '" /></a>';
	}
	else
	{
		$comments_index_edit = '';
	}
	if ($previous_subject != $table_comments['comment_subject'])
	{
		$comment_subject = $table_comments['comment_subject'];
		$comments_index_reply = '<a href="./../comments/reply.php?news_id=' . $_GET['news_id'] . '&amp;comment_id=' . $table_comments['comment_id'] . '"><img src="./../images/comments/reply.png" alt="." title="' . $lang['COMMENTS_INDEX_REPLY'] . '" /></a>';
		$text_class = 'middleColumn';
	}
	else
	{
		$comment_subject = $lang['COMMENTS_INDEX_PREVIOUS'] . $table_comments['comment_subject'];
		$comments_index_reply = '';
		$text_class = 'middleColumn2';
	}
	$table_comments['comment_text'] = str_replace("\n", '<br />', $table_comments['comment_text']);
	$table_comments['comment_text'] = str_replace("\r", '', $table_comments['comment_text']);
	$table_comments['comment_text'] = preg_replace('#(\s*)([^>]{' . WORD_WRAP . ',})(<|$)#e', "'\\1' . wordwrap('\\2', WORD_WRAP, ' ', 1) . '\\3'", $table_comments['comment_text']);
	$template->set_var(array(
		'COMMENT_EDITION' => $comment_edition,
		'COMMENT_ID' => $table_comments['comment_id'],
		'COMMENT_SUBJECT' => $comment_subject,
		'COMMENT_TEXT' => $table_comments['comment_text'],
		'COMMENTS_INDEX_EDIT' => $comments_index_edit,
		'COMMENTS_INDEX_RELEASE' => sprintf($lang['COMMENTS_INDEX_RELEASE'], $table_comments['user_id'], $table_comments['user_name'], $comment_creation),
		'COMMENTS_INDEX_REPLY' => $comments_index_reply,
		'SUBJECT_CLASS' => $subject_class,
		'TEXT_CLASS' => $text_class));
	$template->parse('comments', 'COMMENTS_BLOCK', true);
	$previous_subject = $table_comments['comment_subject'];
	$comments_exist = 'true';
}
/****************/
/***** News *****/
/****************/
$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_text, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
		FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_NEWS . '.news_id = \''. $_GET['news_id'] . '\' AND ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\'');
while ($table_news = $sql->fetch())
{
	$news_date = date($date_format, ($table_news['news_date'] + $date_offset));
	$table_news['news_text'] = str_replace("\n", '<br />', $table_news['news_text']);
	$table_news['news_text'] = str_replace("\r", '', $table_news['news_text']);
	$table_news['news_source'] = str_replace("\n", '<br />', $table_news['news_source']);
	$table_news['news_source'] = str_replace("\r", '', $table_news['news_source']);
	if ($table_news['news_source'])
	{
		$news_source = sprintf($lang['NEWS_INDEX_SOURCE'], $table_news['news_source']);
	}
	else
	{
		$news_source = '';
	}
	if ($table_settings['allow_html'] == 0)
	{
		$html_support = $lang['HTML_DISABLED'];
	}
	else
	{
		$html_support = $lang['HTML_ENABLED'];
	}
	$template->set_var(array(
		'ADD' => $lang['ADD'],
		'CATEGORY_ID' => $table_news['category_id'],
		'CATEGORY_IMAGE' => $table_news['category_image'],
		'CATEGORY_NAME' => $table_news['category_name'],
		'COMMENTS_INDEX_HEADER' => $lang['COMMENTS_INDEX_HEADER'],
		'FORM_COMMENT_SUBJECT' => $lang['FORM_COMMENT_SUBJECT'],
		'FORM_COMMENT_TEXT' => $lang['FORM_COMMENT_TEXT'],
		'HTML_SUPPORT' => $html_support,
		'NEWS_ID' => $_GET['news_id'],
		'NEWS_INDEX_RELEASE' => sprintf($lang['NEWS_INDEX_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
		'NEWS_INDEX_SEND' => $lang['NEWS_INDEX_SEND'],
		'NEWS_SOURCE' => $news_source,
		'NEWS_SUBJECT' => $table_news['news_subject'],
		'NEWS_TEXT' => $table_news['news_text']));
	$news_exist = 'true';
}
if ($news_exist == 'true')
{
	if ($comments_exist == 'true')
	{
		$template->set_var('COMMENTS_INDEX_PAGES', sprintf($lang['COMMENTS_INDEX_PAGES'], $pages_list));
	}
	else
	{
		$template->set_var('COMMENTS_INDEX_PAGES', sprintf($lang['COMMENTS_INDEX_PAGES'], 1));
	}
	$template->set_var(array(
		'BACK_HOME' => $lang['BACK_HOME'],
		'SMILIES_LIST' => get_smilies_list()));
}
else
{
	error_template($lang['COMMENTS_INDEX_ERROR']);
}

page_header($lang['COMMENTS_INDEX_TITLE']);
$template->pparse('', 'error');
if ($news_exist == 'true')
{
	$template->pparse('', 'index');
}
page_footer();

?>