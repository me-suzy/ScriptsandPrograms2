<?php
// -------------------------------------------------------------
//
// $Id: submit.php,v 1.5 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT submit_news, allow_html
		FROM ' . TABLE_SETTINGS . '');
$table_settings = $sql->fetch();
if (!$_SESSION['user_id'])
{
	error_template($lang['NEWS_SUBMIT_ERROR1']);
}
else
{
	if ($table_settings['submit_news'] == 0)
	{
		error_template($lang['NEWS_SUBMIT_DISABLED']);
	}
	elseif ($_POST['submit'])
	{
		$sql->query('SELECT user_id
				FROM ' . TABLE_NEWS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\' AND news_active = \'0\'');
		$table_news = $sql->fetch();
		if ($table_news['user_id'])
		{
			$error .= $lang['NEWS_SUBMIT_ERROR2'];
		}
		else
		{
			if (!$_POST['category_id'])
			{
				$error .= $lang['NO_CATEGORY_NAME'];
			}
			if (!trim($_POST['news_subject']))
			{
				$error .= $lang['NO_NEWS_SUBJECT'];
			}
			if (!trim($_POST['news_text']))
			{
				$error .= $lang['NO_NEWS_TEXT'];
			}
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$news_subject = htmlspecialchars($_POST['news_subject']);
			if ($table_settings['allow_html'] == 0)
			{
				$news_text = htmlspecialchars($_POST['news_text']);
				$news_source = htmlspecialchars($_POST['news_source']);
				$news_text = do_bbcode($news_text);
				$news_source = do_bbcode($news_source);
			}
			else
			{
				$news_text = $_POST['news_text'];
				$news_source = $_POST['news_source'];
			}
			$news_text = make_clickable($news_text);
			$news_source = make_clickable($news_source);
			$sql->query('INSERT INTO ' . TABLE_NEWS . ' (category_id, user_id, news_active, news_subject, news_text, news_source, news_date, news_month, news_year)
					VALUES (\'' . $_POST['category_id'] . '\', \'' . $_SESSION['user_id'] . '\', \'0\', \'' . $news_subject . '\', \'' . $news_text . '\', \'' . $news_source . '\', \'' . time() . '\', \'' . date('m', time()) . '\', \'' . date('Y', time()) . '\')');
			$sql->query('SELECT news_id
					FROM ' . TABLE_NEWS . ' WHERE news_active = \'0\'');
			$num_submitted_news = $sql->num_rows();
			success_template(sprintf($lang['NEWS_SUBMIT_SUCCESS'], $num_submitted_news));
		}
	}
	else
	{
		$sql->query('SELECT category_id, category_name
				FROM ' . TABLE_CATEGORIES . '
				WHERE category_level != \'1\'
				ORDER BY category_name');
		while ($table_categories = $sql->fetch())
		{
			$category_name_options .= '<option value="' . $table_categories['category_id'] . '">' . $table_categories['category_name'] . '</option>';
		}
		if ($table_settings['allow_html'] == 0)
		{
			$html_support = $lang['HTML_DISABLED'];
		}
		else
		{
			$html_support = $lang['HTML_ENABLED'];
		}
		$template->set_file('submit', 'news/submit.tpl');
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'CATEGORY_NAME_OPTIONS' => $category_name_options,
			'FORM_NEWS_CATEGORY' => $lang['FORM_NEWS_CATEGORY'],
			'FORM_NEWS_SOURCE' => $lang['FORM_NEWS_SOURCE'],
			'FORM_NEWS_SUBJECT' => $lang['FORM_NEWS_SUBJECT'],
			'FORM_NEWS_TEXT' => $lang['FORM_NEWS_TEXT'],
			'HTML_SUPPORT' => $html_support,
			'NEWS_SUBMIT_HEADER' => $lang['NEWS_SUBMIT_HEADER'],
			'SMILIES_LIST' => get_smilies_list(),
			'SUBMIT' => $lang['SUBMIT']));
	}
}

page_header($lang['NEWS_SUBMIT_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'submit');
$template->pparse('', 'success');
page_footer();

?>
