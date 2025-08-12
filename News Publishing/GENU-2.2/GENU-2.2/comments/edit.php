<?php
// -------------------------------------------------------------
//
// $Id: edit.php,v 1.5 2005/03/28 12:30:33 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT allow_html, allow_smilies
		FROM ' . TABLE_SETTINGS . '');
$table_settings = $sql->fetch();
if ($_GET['comment_id'])
{
	$sql->query('SELECT ' . TABLE_COMMENTS . '.comment_id, ' . TABLE_COMMENTS . '.comment_subject, ' . TABLE_COMMENTS . '.comment_text, ' . TABLE_USERS . '.user_id
			FROM ' . TABLE_COMMENTS . ', ' . TABLE_USERS . '
			WHERE ' . TABLE_COMMENTS . '.news_id = \'' . $_GET['news_id'] . '\' AND ' . TABLE_COMMENTS . '.comment_id = \'' . $_GET['comment_id'] . '\' AND '. TABLE_COMMENTS .'.user_id = ' . TABLE_USERS . '.user_id');
	$table_comments = $sql->fetch();
	if (!$table_comments['comment_id'])
	{
		error_template($lang['COMMENTS_EDIT_ERROR1']);
	}
	elseif ($table_comments['user_id'] != $_SESSION['user_id'])
	{
		error_template($lang['COMMENTS_EDIT_ERROR2']);
	}
	else
	{
		$template->set_file('edit', 'comments/edit.tpl');
/****************/
/***** News *****/
/****************/
		$date_format = get_date_format();
		$date_offset = get_date_offset();
		$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_id, ' . TABLE_CATEGORIES . '.category_image, ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.news_source, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
				FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . ', ' . TABLE_USERS . '
				WHERE ' . TABLE_NEWS . '.news_id = \'' . $_GET['news_id'] . '\' AND ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id AND ' . TABLE_CATEGORIES . '.category_level != \'1\' AND ' . TABLE_NEWS . '.news_active = \'1\'');
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
/********************/
/***** Comments *****/
/********************/
		if ($table_settings['allow_html'] == 0)
		{
			$html_support = $lang['HTML_DISABLED'];
		}
		else
		{
			$html_support = $lang['HTML_ENABLED'];
		}
		$sql->query('SELECT smiley_code, smiley_image
				FROM ' . TABLE_SMILIES . '');
		while ($table_smilies = $sql->fetch())
		{
			$table_comments['comment_text'] = str_replace('<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $table_smilies['smiley_code'], $table_comments['comment_text']);
		}
		$table_comments['comment_text'] = undo_bbcode($table_comments['comment_text']);
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'CATEGORY_ID' => $table_news['category_id'],
			'CATEGORY_IMAGE' => $table_news['category_image'],
			'CATEGORY_NAME' => $table_news['category_name'],
			'COMMENT_ID' => $_GET['comment_id'],
			'COMMENT_SUBJECT' => $table_comments['comment_subject'],
			'COMMENT_TEXT' => $table_comments['comment_text'],
			'COMMENTS_EDIT_HEADER' => $lang['COMMENTS_EDIT_HEADER'],
			'EDIT' => $lang['EDIT'],
			'FORM_COMMENT_SUBJECT' => $lang['FORM_COMMENT_SUBJECT'],
			'FORM_COMMENT_TEXT' => $lang['FORM_COMMENT_TEXT'],
			'HTML_SUPPORT' => $html_support,
			'NEWS_ID' => $_GET['news_id'],
			'NEWS_INDEX_RELEASE' => sprintf($lang['NEWS_INDEX_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
			'NEWS_INDEX_SEND' => $lang['NEWS_INDEX_SEND'],
			'NEWS_SOURCE' => $news_source,
			'NEWS_SUBJECT' => $table_news['news_subject'],
			'NEWS_TEXT' => $table_news['news_text'],
			'SMILIES_LIST' => get_smilies_list()));
	}
}
elseif ($_POST['edit_comment'])
{
	if (!trim($_POST['comment_text']))
	{
		error_template($lang['NO_COMMENT_TEXT']);
	}
	else
	{
		if ($table_settings['allow_html'] == 0)
		{
			$comment_text = htmlspecialchars($_POST['comment_text']);
			$comment_text = do_bbcode($comment_text);
		}
		else
		{
			$comment_text = $_POST['comment_text'];
		}
		$comment_text = make_clickable($comment_text);
		if ($table_settings['allow_smilies'] == 1)
		{
			$sql->query('SELECT smiley_code, smiley_image
					FROM ' . TABLE_SMILIES . '');
			while ($table_smilies = $sql->fetch())
			{
				$comment_text = str_replace($table_smilies['smiley_code'], '<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $comment_text);
			}
		}
		$sql->query('UPDATE ' . TABLE_COMMENTS . '
				SET comment_text = \'' . $comment_text . '\', comment_edition = \'' . time() . '\'
				WHERE comment_id = \'' . $_POST['comment_id'] . '\'');
		success_template($lang['COMMENTS_EDIT_SUCCESS']);
		header('Refresh: 3; URL= ./../comments/index.php?news_id=' . $_POST['news_id'] . '');
	}
}
else
{
	error_template($lang['COMMENTS_EDIT_ERROR1']);
}

page_header($lang['COMMENTS_EDIT_TITLE']);
$template->pparse('', 'edit');
$template->pparse('', 'error');
$template->pparse('', 'success');
page_footer();

?>