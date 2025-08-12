<?php
// -------------------------------------------------------------
//
// $Id: search.php,v 1.5 2005/03/28 12:47:01 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_GET['search'])
{
	$sql->query('SELECT news_order
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$date_format = get_date_format();
	$date_offset = get_date_offset();
	$template->set_file('search', 'news/search.tpl');
	$template->set_block('search', 'NEWS_BLOCK', 'news');
	switch (SQL_TYPE)
	{
		case 'mysql' :
			if ($sql->version() < '4.0.1')
			{
				$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_comments, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
						FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
						WHERE ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\' AND MATCH (' . $_GET['match'] . ') AGAINST (\'' . $_GET['search'] . '\')
						ORDER BY ' . $table_settings['news_order'] . ' DESC');
				$boolean_operators = '';
			}
			else
			{
				$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_comments, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
						FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
						WHERE ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\' AND MATCH (' . $_GET['match'] . ') AGAINST (\'' . $_GET['search'] . '\' IN BOOLEAN MODE)
						ORDER BY ' . $table_settings['news_order'] . ' DESC');
				$boolean_operators = $lang['NEWS_SEARCH_BOOLEAN'];
			}
			break;
		case 'pgsql' :
		case 'sqlite' :
			$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_comments, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
					FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
					WHERE ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\' AND ' . TABLE_NEWS . '.' . $_GET['match'] . ' LIKE \'%' . $_GET['search'] . '%\'
					ORDER BY ' . $table_settings['news_order'] . ' DESC');
			$boolean_operators = '';
			break;
	}
	while ($table_news = $sql->fetch())
	{
		$term = split('[+-<>()~*" ]', stripslashes($_GET['search']));
		$num_terms = count($term);
		for ($i = 0; $i <= $num_terms; $i++)
		{
			if ($term[$i])
			{
				if (eregi('([^>]*<)', $table_news[$_GET['match']]))
				{
					$table_news[$_GET['match']] = preg_replace('#(' . $term[$i] . ')(?=[^>]*<)#i', '<span class="newItem">\\0</span>', $table_news[$_GET['match']]);
				}
				else
				{
					$table_news[$_GET['match']] = preg_replace('#(' . $term[$i] . ')#i', '<span class="newItem">\\0</span>', $table_news[$_GET['match']]);
				}
			}
		}
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
	$num_news = $sql->num_rows();
	if ($news_exist == 'true')
	{
		if ($num_news > SEARCH_LIMIT)
		{
			error_template(sprintf($lang['NEWS_SEARCH_ERROR1'], SEARCH_LIMIT));
		}
		else
		{
			$string = htmlspecialchars(stripslashes($_GET['search']));
			$template->set_var('NEWS_SEARCH_HEADER', sprintf($lang['NEWS_SEARCH_HEADER'], $string, $num_news));
			$template->set_var('BACK_HOME', $lang['BACK_HOME']);
		}
	}
	else
	{
		error_template(sprintf($lang['NEWS_SEARCH_ERROR2'], $boolean_operators));
	}
}
else
{
	error_template($lang['NEWS_SEARCH_ERROR3']);
}

page_header($lang['NEWS_SEARCH_TITLE']);
$template->pparse('', 'error');
if ($news_exist == 'true' && $num_news <= SEARCH_LIMIT)
{
	$template->pparse('', 'search');
}
page_footer();

?>