<?php
// -------------------------------------------------------------
//
// $Id: reply.php,v 1.5 2005/03/28 12:30:33 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_SESSION['user_id'])
{
	$sql->query('SELECT ' . TABLE_COMMENTS . '.comment_subject, ' . TABLE_NEWS . '.news_id
			FROM ' . TABLE_COMMENTS . ', ' . TABLE_NEWS . '
			WHERE ' . TABLE_NEWS . '.news_id = \'' . $_GET['news_id'] . '\' AND ' . TABLE_COMMENTS . '.comment_id = \'' . $_GET['comment_id'] . '\' AND ' . TABLE_COMMENTS . '.news_id = ' . TABLE_NEWS . '.news_id');
	$table_comments = $sql->fetch();
	if ($table_comments['news_id'])
	{
/****************/
/***** News *****/
/****************/
		$date_format = get_date_format();
		$date_offset = get_date_offset();
		$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_comments, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
				FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
				WHERE ' . TABLE_NEWS . '.news_id = \''. $_GET['news_id'] . '\' AND ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\'');
		$table_news = $sql->fetch();
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
		$template->set_file('reply', 'comments/reply.tpl');
		$template->set_var(array(
			'CATEGORY_ID' => $table_news['category_id'],
			'CATEGORY_IMAGE' => $table_news['category_image'],
			'CATEGORY_NAME' => $table_news['category_name'],
			'NEWS_INDEX_PAGE' => $lang['NEWS_INDEX_PAGE'],
			'NEWS_INDEX_RELEASE' => sprintf($lang['NEWS_INDEX_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
			'NEWS_INDEX_SEND' => $lang['NEWS_INDEX_SEND'],
			'NEWS_SOURCE' => $news_source,
			'NEWS_SUBJECT' => $table_news['news_subject'],
			'NEWS_TEXT' => $table_news['news_text']));
/********************/
/***** Comments *****/
/********************/
		$sql->query('SELECT allow_html
				FROM ' . TABLE_SETTINGS . '');
		$table_settings = $sql->fetch();
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
			'BACK_HOME' => $lang['BACK_HOME'],
			'COMMENT_SUBJECT' => $table_comments['comment_subject'],
			'COMMENTS_REPLY_HEADER' => $lang['COMMENTS_REPLY_HEADER'],
			'FORM_COMMENT_SUBJECT' => $lang['FORM_COMMENT_SUBJECT'],
			'FORM_COMMENT_TEXT' => $lang['FORM_COMMENT_TEXT'],
			'HTML_SUPPORT' => $html_support,
			'NEWS_ID' => $_GET['news_id'],
			'SMILIES_LIST' => get_smilies_list()));
	}
	else
	{
		error_template($lang['COMMENTS_REPLY_ERROR1']);
	}
}
else
{
	error_template($lang['COMMENTS_REPLY_ERROR2']);
}

page_header($lang['COMMENTS_REPLY_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'reply');
$template->pparse('', 'success');
page_footer();

?>