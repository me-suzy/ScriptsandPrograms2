<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.5 2005/04/06 19:57:21 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_POST['user_language'])
{
	setcookie('language', $_POST['user_language'], (time() + COOKIE_EXPIRY), '/', '');
	header('Location: ' . $_SERVER['PHP_SELF'] . '');
}
if ($_POST['user_template'])
{
	setcookie('template', $_POST['user_template'], (time() + COOKIE_EXPIRY), '/', '');
	header('Location: ' . $_SERVER['PHP_SELF'] . '');
}
if (!$_GET['page'])
{
	$_GET['page'] = 1;
}
if (!$_GET['list'])
{
	$_GET['list'] = 0;
}
if ($_GET['news_id'])
{
	$clause .= 'WHERE news_id = \'' . $_GET['news_id'] . '\'';
}
if ($_GET['category_id'])
{
	if ($_GET['news_id'])
	{
		$clause .= ' AND category_id = \'' . $_GET['category_id'] . '\'';
	}
	else
	{
		$clause .= 'WHERE category_id = \'' . $_GET['category_id'] . '\'';
	}
}
if ($_GET['news_month'])
{
	if ($_GET['news_id'])
	{
		$clause .= ' AND news_month = \'' . $_GET['news_month'] . '\'';
	}
	elseif ($_GET['category_id'])
	{
		$clause .= ' AND news_month = \'' . $_GET['news_month'] . '\'';
	}
	else
	{
		$clause .= 'WHERE news_month = \'' . $_GET['news_month'] . '\'';
	}
}
if ($_GET['news_year'])
{
	if ($_GET['news_id'])
	{
		$clause .= ' AND news_year = \'' . $_GET['news_year'] . '\'';
	}
	elseif ($_GET['category_id'])
	{
		$clause .= ' AND news_year = \'' . $_GET['news_year'] . '\'';
	}
	elseif ($_GET['news_month'])
	{
		$clause .= ' AND news_year = \'' . $_GET['news_year'] . '\'';
	}
	else
	{
		$clause .= 'WHERE news_year = \'' . $_GET['news_year'] . '\'';
	}
}
if (!$_GET['news_id'] && !$_GET['category_id'] && !$_GET['news_month'] && !$_GET['news_year'])
{
	$clause .= 'WHERE';
}
else
{
	$clause .= ' AND';
}
$sql->query('SELECT headlines_per_backend, news_order, news_per_page, sitename
		FROM ' . TABLE_SETTINGS . '');
$table_settings = $sql->fetch();
$news_per_page = $table_settings['news_per_page'];
$news_offset = (($_GET['page'] - 1) * $news_per_page);
$sql->query('SELECT news_id
		FROM ' . TABLE_NEWS . '
		' . $clause . ' news_active = \'1\'');
$num_news = $sql->num_rows();
$num_pages = ceil($num_news / $news_per_page);
if ($_GET['page'] != 1)
{
	if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
	{
		$pages_list .= '<a href="./../news/index.php?page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../news/index.php?page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
	}
}
if ($_GET['list'] != 0)
{
	$pages_list .= '<a href="./../news/index.php?page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
}
for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
{
	if ($_GET['page'] == $current_page)
	{
		$pages_list .= $_GET['page'] . ' ';
	}
	else
	{
		$pages_list .= '<a href="./../news/index.php?page=' . $current_page . '&amp;list=' . $_GET['list'] . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
	}
}
if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
{
	$pages_list .= '<a href="./../news/index.php?page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
}
if (($num_pages > 1) && ($_GET['page'] != $num_pages))
{
	if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
	{
		$pages_list .= '<a href="./../news/index.php?page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
	else
	{
		$pages_list .= '<a href="./../news/index.php?page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '&amp;category_id=' . $_GET['category_id'] . '&amp;news_month=' . $_GET['news_month'] . '&amp;news_year=' . $_GET['news_year'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
	}
}
$clause = '';
if ($_GET['news_id'])
{
	$clause .= 'WHERE ' . TABLE_NEWS . '.news_id = \''. $_GET['news_id'] . '\'';
}
if ($_GET['category_id'])
{
	if ($_GET['news_id'])
	{
		$clause .= ' AND ' . TABLE_NEWS . '.category_id = \'' . $_GET['category_id'] . '\'';
	}
	else
	{
		$clause .= 'WHERE ' . TABLE_NEWS . '.category_id = \'' . $_GET['category_id'] . '\'';
	}
}
if ($_GET['news_month'])
{
	if ($_GET['news_id'])
	{
		$clause .= ' AND ' . TABLE_NEWS . '.news_month = \'' . $_GET['news_month'] . '\'';
	}
	elseif ($_GET['category_id'])
	{
		$clause .= ' AND ' . TABLE_NEWS . '.news_month = \'' . $_GET['news_month'] . '\'';
	}
	else
	{
		$clause .= 'WHERE ' . TABLE_NEWS . '.news_month = \'' . $_GET['news_month'] . '\'';
	}
}
if ($_GET['news_year'])
{
	if ($_GET['news_id'])
	{
		$clause .= ' AND ' . TABLE_NEWS . '.news_year = \'' . $_GET['news_year'] . '\'';
	}
	elseif ($_GET['category_id'])
	{
		$clause .= ' AND ' . TABLE_NEWS . '.news_year = \'' . $_GET['news_year'] . '\'';
	}
	elseif ($_GET['news_month'])
	{
		$clause .= ' AND ' . TABLE_NEWS . '.news_year = \'' . $_GET['news_year'] . '\'';
	}
	else
	{
		$clause .= 'WHERE ' . TABLE_NEWS . '.news_year = \'' . $_GET['news_year'] . '\'';
	}
}
if (!$_GET['news_id'] && !$_GET['category_id'] && !$_GET['news_month'] && !$_GET['news_year'])
{
	$clause .= 'WHERE';
}
else
{
	$clause .= ' AND';
}
$news_order = $table_settings['news_order'];
$date_format = get_date_format();
$date_offset = get_date_offset();
$template->set_file('index', 'news/index.tpl');
$template->set_block('index', 'NEWS_BLOCK', 'news');
$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_comments, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
		FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
		' . $clause . ' ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\'
		ORDER BY ' . $news_order . ' DESC
		LIMIT ' . $news_offset . ', ' . $news_per_page . '');
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
	if ((strlen($table_news['news_text']) > MAX_NEWS_LENGHT) && !$_GET['news_id'])
	{
		$table_news['news_text'] = substr($table_news['news_text'], 0, MAX_NEWS_LENGHT);
		$table_news['news_text'] = substr($table_news['news_text'], 0, strlen($table_news['news_text']) - strpos(strrev($table_news['news_text']), ' .') - 1);
		$table_news['news_text'] = close_tags($table_news['news_text']) . ' (<a href="./../comments/index.php?news_id=' . $table_news['news_id'] . '" title="' . $lang['NEWS_INDEX_READ'] . '">' . $lang['NEWS_INDEX_READ'] . '</a>)';
	}
	$template->set_var(array(
		'CATEGORY_ID' => $table_news['category_id'],
		'CATEGORY_IMAGE' => $table_news['category_image'],
		'CATEGORY_NAME' => $table_news['category_name'],
		'NEWS_COMMENTS' => $table_news['news_comments'],
		'NEWS_ID' => $table_news['news_id'],
		'NEWS_INDEX_COMMENT' => $lang['NEWS_INDEX_COMMENT'],
		'NEWS_INDEX_RELEASE' => sprintf($lang['NEWS_INDEX_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
		'NEWS_INDEX_SEND' => $lang['NEWS_INDEX_SEND'],
		'NEWS_SOURCE' => $news_source,
		'NEWS_SUBJECT' => $table_news['news_subject'],
		'NEWS_TEXT' => $table_news['news_text']));
	$template->parse('news', 'NEWS_BLOCK', true);
	$news_exist = 'true';
}
$sql->query('SELECT ' . TABLE_COMMENTS . '.comment_id
		FROM ' . TABLE_COMMENTS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_COMMENTS . '.comment_creation > ' . TABLE_USERS . '.user_lastvisit AND ' . TABLE_USERS . '.user_id = \'' . $_SESSION['user_id'] . '\'');
$new_comments = $sql->num_rows();
$sql->query('SELECT ' . TABLE_NEWS . '.news_id
		FROM ' . TABLE_NEWS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_NEWS . '.news_date > ' . TABLE_USERS . '.user_lastvisit AND ' . TABLE_NEWS . '.news_active = \'1\' AND ' . TABLE_USERS . '.user_id = \'' . $_SESSION['user_id'] . '\'');
$new_news = $sql->num_rows();
if ($news_exist == 'true')
{
	if ($_SESSION['user_id'])
	{
		$template->set_var('NEWS_INDEX_WHATSNEW', sprintf($lang['NEWS_INDEX_WHATSNEW'], $new_news, $new_comments));
	}
	$current_date = date($date_format, (time() + $date_offset));
	$template->set_var(array(
		'BACKEND_RSS' => parse_rss($table_settings['headlines_per_backend']),
		'BACKEND_TXT' => parse_txt($table_settings['headlines_per_backend']),
		'FORM_LANGUAGE' => $lang['FORM_LANGUAGE'],
		'FORM_TEMPLATE' => $lang['FORM_TEMPLATE'],
		'NEWS_INDEX_HEADER' => sprintf($lang['NEWS_INDEX_HEADER'], $table_settings['sitename'], $current_date),
		'NEWS_INDEX_PAGES' => sprintf($lang['NEWS_INDEX_PAGES'], $pages_list),
		'SEND' => $lang['SEND']));
}
else
{
	error_template($lang['NEWS_INDEX_ERROR']);
}

page_header($lang['NEWS_INDEX_TITLE']);
$template->pparse('', 'error');
if ($news_exist == 'true')
{
	$template->pparse('', 'index');
}
page_footer();

?>