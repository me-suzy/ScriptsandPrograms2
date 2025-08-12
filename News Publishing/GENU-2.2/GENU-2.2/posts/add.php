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
	$sql->query('SELECT post_id
			FROM ' . TABLE_POSTS . '
			WHERE post_active = \'0\' AND thread_id = \'' . $_POST['thread_id'] . '\'');
	$table_posts = $sql->fetch();
	if (!$table_posts['post_id'])
	{
		$sql->query('SELECT category_id
				FROM ' . TABLE_CATEGORIES . '
				WHERE category_id = \'' . $_POST['category_id'] . '\'');
		$table_categories = $sql->fetch();
		if ($table_categories['category_id'])
		{
			if ($_POST['add_post'])
			{
				if (!trim($_POST['post_subject']))
				{
					$error .= $lang['NO_POST_SUBJECT'];
				}
				if (!trim($_POST['post_text']))
				{
					$error .= $lang['NO_POST_TEXT'];
				}
				$sql->query('SELECT post_creation
						FROM ' . TABLE_POSTS . '
						WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
				while ($table_posts = $sql->fetch())
				{
					if ($table_posts['post_creation'] >= (time() - POST_INTERVAL))
					{
						$error .= sprintf($lang['POSTS_ADD_ERROR1'], POST_INTERVAL);
					}
				}
				if ($error)
				{
					error_template($error);
				}
				else
				{
					$post_subject = htmlspecialchars($_POST['post_subject']);
					$sql->query('SELECT allow_html, allow_smilies
							FROM ' . TABLE_SETTINGS . '');
					$table_settings = $sql->fetch();
					if ($table_settings['allow_html'] == 0)
					{
						$post_text = htmlspecialchars($_POST['post_text']);
						$post_text = do_bbcode($post_text);
					}
					else
					{
						$post_text = $_POST['post_text'];
					}
					$post_text = make_clickable($post_text);
					if ($table_settings['allow_smilies'] == 1)
					{
						$sql->query('SELECT smiley_code, smiley_image
								FROM ' . TABLE_SMILIES . '');
						while ($table_smilies = $sql->fetch())
						{
							$post_text = str_replace($table_smilies['smiley_code'], '<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $post_text);
						}
					}
					$sql->query('INSERT INTO ' . TABLE_POSTS . ' (category_id, user_id, post_subject, post_text, post_creation, post_active)
							VALUES (\'' . $_POST['category_id'] . '\', \'' . $_SESSION['user_id'] . '\', \'' . $post_subject . '\', \'' . $post_text . '\', \'' . time() . '\', \'1\')');
					$id = $sql->insert_id();
					$sql->query('SELECT thread_id
							FROM ' . TABLE_POSTS . '
							WHERE post_id = \'' . $_POST['post_id'] . '\' AND category_id = \'' . $_POST['category_id'] . '\'');
					$table_posts = $sql->fetch();
					if (!$table_posts['thread_id'])
					{
						$sql->query('UPDATE ' . TABLE_POSTS . '
								SET thread_id = ' . $id . '
								WHERE post_id = ' . $id . '');
					}
					else
					{
						$sql->query('UPDATE ' . TABLE_POSTS . '
								SET thread_id = \'' . $table_posts['thread_id'] . '\'
								WHERE post_id = ' . $id . '');
					}
					$sql->query('UPDATE ' . TABLE_CATEGORIES . '
							SET category_posts = category_posts + 1
							WHERE category_id = \'' . $_POST['category_id'] . '\'');
					$sql->query('UPDATE ' . TABLE_USERS . '
							SET user_posts = user_posts + 1, user_ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'
							WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
					success_template($lang['POSTS_ADD_SUCCESS']);
					header('Refresh: 3; URL= ./../posts/list.php?category_id=' . $_POST['category_id'] . '');
				}
			}
			else
			{
				error_template($lang['POSTS_ADD_ERROR2']);
			}
		}
		else
		{
			error_template($lang['POSTS_ADD_ERROR2']);
		}
	}
	else
	{
		error_template($lang['POSTS_ADD_ERROR3']);
	}
}
else
{
	error_template($lang['POSTS_ADD_ERROR4']);
}

page_header($lang['POSTS_ADD_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'success');
page_footer();

?>