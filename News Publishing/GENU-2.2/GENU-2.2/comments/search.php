<?php
// -------------------------------------------------------------
//
// $Id: search.php,v 1.4 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$i = 1;
$new_comments = array();
$sql->query('SELECT ' . TABLE_COMMENTS . '.comment_id, ' . TABLE_COMMENTS . '.news_id
		FROM ' . TABLE_COMMENTS . ', ' . TABLE_USERS . '
		WHERE ' . TABLE_COMMENTS . '.comment_creation > ' . TABLE_USERS . '.user_lastvisit AND ' . TABLE_USERS . '.user_id = \'' . $_SESSION['user_id'] . '\'');
while ($table_comments = $sql->fetch())
{
	if ($table_comments['news_id'])
	{
		$new_comments[$table_comments['news_id']]++;
		$num_comments = $i++;
	}
}
$j = 0;
$num_news = count($new_comments);
while (list($key) = each($new_comments))
{
	$j++;
	if ($j < 2)
	{
		$clause = '' . TABLE_NEWS . '.news_id = \'' . $key . '\'';
	}
	else
	{
		$clause .= ' OR ' . TABLE_NEWS . '.news_id = \'' . $key . '\'';
	}
}
if ($clause)
{
	$sql->query('SELECT news_order
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$news_order = $table_settings['news_order'];
	$date_format = get_date_format();
	$date_offset = get_date_offset();
	$template->set_file('search', 'comments/search.tpl');
	$template->set_block('search', 'NEWS_BLOCK', 'news');
	$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_comments, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
			FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
			WHERE (' . $clause . ') AND ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\'
			ORDER BY ' . $news_order . ' DESC');
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
			'BACK_HOME' => $lang['BACK_HOME'],
			'CATEGORY_ID' => $table_news['category_id'],
			'CATEGORY_IMAGE' => $table_news['category_image'],
			'CATEGORY_NAME' => $table_news['category_name'],
			'COMMENTS_SEARCH_HEADER' => sprintf($lang['COMMENTS_SEARCH_HEADER'], $num_comments, $num_news),
			'NEWS_COMMENTS' => $table_news['news_comments'],
			'NEWS_ID' => $table_news['news_id'],
			'NEWS_INDEX_COMMENT' => $lang['NEWS_INDEX_COMMENT'],
			'NEWS_INDEX_RELEASE' => sprintf($lang['NEWS_INDEX_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
			'NEWS_INDEX_SEND' => $lang['NEWS_INDEX_SEND'],
			'NEWS_SOURCE' => $news_source,
			'NEWS_SUBJECT' => $table_news['news_subject'],
			'NEWS_TEXT' => $table_news['news_text']));
		$template->parse('news', 'NEWS_BLOCK', true);
	}
}
else
{
	if ($_SESSION['user_id'])
	{
		error_template($lang['COMMENTS_SEARCH_ERROR1']);
	}
	else
	{
		error_template($lang['COMMENTS_SEARCH_ERROR2']);
	}
}

page_header($lang['COMMENTS_SEARCH_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'search');
page_footer();

?>