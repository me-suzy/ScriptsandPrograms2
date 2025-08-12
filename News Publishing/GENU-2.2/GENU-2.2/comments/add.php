<?php
// -------------------------------------------------------------
//
// $Id: add.php,v 1.7 2005/03/28 12:30:33 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_SESSION['user_id'])
{
	$sql->query('SELECT news_id
			FROM ' . TABLE_NEWS . '
			WHERE news_id = \'' . $_POST['news_id'] . '\'');
	$table_news = $sql->fetch();
	if ($table_news['news_id'])
	{
		if ($_POST['add_comment'])
		{
			if (!trim($_POST['comment_subject']))
			{
				$error .= $lang['NO_COMMENT_SUBJECT'];
			}
			if (!trim($_POST['comment_text']))
			{
				$error .= $lang['NO_COMMENT_TEXT'];
			}
			$sql->query('SELECT comment_creation
					FROM ' . TABLE_COMMENTS . '
					WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
			while ($table_comments = $sql->fetch())
			{
				if ($table_comments['comment_creation'] >= (time() - POST_INTERVAL))
				{
					$error .= sprintf($lang['COMMENTS_ADD_ERROR1'], POST_INTERVAL);
				}
			}
			if ($error)
			{
				error_template($error);
			}
			else
			{
				$comment_subject = htmlspecialchars($_POST['comment_subject']);
				$sql->query('SELECT allow_html, allow_smilies
						FROM ' . TABLE_SETTINGS . '');
				$table_settings = $sql->fetch();
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
				$sql->query('SELECT comment_subject, reply_id
						FROM ' . TABLE_COMMENTS . '
						WHERE comment_subject = \'' . $comment_subject . '\' AND news_id = \'' . $_POST['news_id'] . '\'');
				$table_comments = $sql->fetch();
				$sql->query('INSERT INTO ' . TABLE_COMMENTS . ' (news_id, user_id, comment_subject, comment_text, comment_creation)
						VALUES (\'' . $_POST['news_id'] . '\', \'' . $_SESSION['user_id'] . '\', \'' . $comment_subject . '\', \'' . $comment_text . '\', \'' . time() . '\')');
				$id = $sql->insert_id();
				if (!$table_comments['comment_subject'])
				{
					$sql->query('UPDATE ' . TABLE_COMMENTS . '
							SET reply_id = ' . $id . '
							WHERE comment_id = ' . $id . '');
				}
				else
				{
					$sql->query('UPDATE ' . TABLE_COMMENTS . '
							SET reply_id = \'' . $table_comments['reply_id'] . '\'
							WHERE comment_id = ' . $id . '');
				}
				$sql->query('UPDATE ' . TABLE_USERS . '
						SET user_comments = user_comments + 1, user_ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'
						WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
				$sql->query('UPDATE ' . TABLE_NEWS . '
						SET news_comments = news_comments + 1
						WHERE news_id = \'' . $_POST['news_id'] . '\'');
				success_template($lang['COMMENTS_ADD_SUCCESS']);
				header('Refresh: 3; URL= ./../comments/index.php?news_id=' . $_POST['news_id'] . '');
			}
		}
		else
		{
			error_template($lang['COMMENTS_ADD_ERROR2']);
		}
	}
	else
	{
		error_template($lang['COMMENTS_ADD_ERROR2']);
	}
}
else
{
	error_template($lang['COMMENTS_ADD_ERROR3']);
}

page_header($lang['COMMENTS_ADD_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'success');
page_footer();

?>
