<?php
// -------------------------------------------------------------
//
// $Id: edit.php,v 1.7 2005/05/15 08:40:56 raoul Exp $
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
if ($_GET['post_id'])
{
	$sql->query('SELECT ' . TABLE_POSTS . '.post_id, ' . TABLE_POSTS . '.post_subject, ' . TABLE_POSTS . '.post_text, ' . TABLE_POSTS . '.thread_id, ' . TABLE_USERS . '.user_id
			FROM ' . TABLE_POSTS . ', ' . TABLE_USERS . '
			WHERE ' . TABLE_POSTS . '.category_id = \'' . $_GET['category_id'] . '\' AND ' . TABLE_POSTS . '.post_id = \'' . $_GET['post_id'] . '\' AND ' . TABLE_POSTS . '.user_id = ' . TABLE_USERS . '.user_id');
	$table_posts = $sql->fetch();
	if (!$table_posts['post_id'])
	{
		error_template($lang['POSTS_EDIT_ERROR1']);
	}
	elseif ($table_posts['user_id'] != $_SESSION['user_id'])
	{
		error_template($lang['POSTS_EDIT_ERROR2']);
	}
	else
	{
		$template->set_file('edit', 'posts/edit.tpl');
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
			$table_posts['post_text'] = str_replace('<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $table_smilies['smiley_code'], $table_posts['post_text']);
		}
		$table_posts['post_text'] = undo_bbcode($table_posts['post_text']);
		$template->set_var(array(
			'BACK_THREADS_INDEX' => $lang['BACK_THREADS_INDEX'],
			'CATEGORY_ID' => $_GET['category_id'],
			'EDIT' => $lang['EDIT'],
			'FORM_POST_SUBJECT' => $lang['FORM_POST_SUBJECT'],
			'FORM_POST_TEXT' => $lang['FORM_POST_TEXT'],
			'HTML_SUPPORT' => $html_support,
			'POST_ID' => $_GET['post_id'],
			'POST_SUBJECT' => $table_posts['post_subject'],
			'POST_TEXT' => $table_posts['post_text'],
			'POSTS_EDIT_HEADER' => $lang['POSTS_EDIT_HEADER'],
			'SMILIES_LIST' => get_smilies_list(),
			'THREAD_ID' => $table_posts['thread_id']));
	}
}
elseif ($_POST['edit_post'])
{
	if (!trim($_POST['post_text']))
	{
		error_template($lang['NO_POST_TEXT']);
	}
	else
	{
		if ($table_settings['allow_html'] == 0)
		{
			$post_text = htmlspecialchars($_POST['post_text']);
			$post_text = do_bbcode($post_text);
		}
		else
		{
			$post_text = $_POST['post_text'];
		}
		if ($table_settings['allow_smilies'] == 1)
		{
			$sql->query('SELECT smiley_code, smiley_image
					FROM ' . TABLE_SMILIES . '');
			while ($table_smilies = $sql->fetch())
			{
				$post_text = str_replace($table_smilies['smiley_code'], '<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $post_text);
			}
		}
		$sql->query('UPDATE ' . TABLE_POSTS . '
				SET post_text = \'' . $post_text . '\', post_edition = \'' . time() . '\'
				WHERE post_id = \'' . $_POST['post_id'] . '\'');
		success_template($lang['POSTS_EDIT_SUCCESS']);
		header('Refresh: 3; URL= ./../posts/read.php?category_id=' . $_POST['category_id'] . '&thread_id=' . $_POST['thread_id'] . '');
	}
}
else
{
	error_template($lang['POSTS_EDIT_ERROR1']);
}

page_header($lang['POSTS_EDIT_TITLE']);
$template->pparse('', 'edit');
$template->pparse('', 'error');
$template->pparse('', 'success');
page_footer();

?>