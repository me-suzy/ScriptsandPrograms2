<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.27 2005/05/08 13:32:04 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT user_level
		FROM ' . TABLE_USERS . '
		WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
$table_users = $sql->fetch();

if (!$table_users['user_level'])
{
	error_template($lang['ADMIN_AREA1_ERROR']);
}
else
{
	if ($_GET['action'] == 'add_news')
	{
		if ($table_users['user_level'] < 2)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT allow_html
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
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
			$template->set_file('admin', 'admin/news/add.tpl');
			$template->set_var(array(
				'ADD' => $lang['ADD'],
				'ADMIN_NEWS_HEADER1' => $lang['ADMIN_NEWS_HEADER1'],
				'BACK_ADMIN_AREA1' => $lang['BACK_ADMIN_AREA1'],
				'CATEGORY_NAME_OPTIONS' => $category_name_options,
				'FORM_NEWS_CATEGORY' => $lang['FORM_NEWS_CATEGORY'],
				'FORM_NEWS_SOURCE' => $lang['FORM_NEWS_SOURCE'],
				'FORM_NEWS_SUBJECT' => $lang['FORM_NEWS_SUBJECT'],
				'FORM_NEWS_TEXT' => $lang['FORM_NEWS_TEXT'],
				'HTML_SUPPORT' => $html_support,
				'SMILIES_LIST' => get_smilies_list()));
		}
	}
//
	elseif ($_POST['add_news'])
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
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$news_subject = htmlspecialchars($_POST['news_subject']);
			$sql->query('SELECT allow_html, allow_smilies
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
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
			if ($table_settings['allow_smilies'] == 1)
			{
				$sql->query('SELECT smiley_code, smiley_image
						FROM ' . TABLE_SMILIES . '');
				while ($table_smilies = $sql->fetch())
				{
					$news_text = str_replace($table_smilies['smiley_code'], '<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $news_text);
				}
			}
			$sql->query('INSERT INTO ' . TABLE_NEWS . ' (category_id, user_id, news_active, news_subject, news_text, news_source, news_date, news_month, news_year)
					VALUES (\'' . $_POST['category_id'] . '\', \'' . $_SESSION['user_id'] . '\', \'1\', \'' . $news_subject . '\', \'' . $news_text . '\', \'' . $news_source . '\', \'' . time() . '\', \'' . date('m', time()) . '\', \'' . date('Y', time()) . '\')');
			$sql->query('UPDATE ' . TABLE_CATEGORIES . '
					SET category_news = category_news + 1
					WHERE category_id = \'' . $_POST['category_id'] . '\'');
			make_backend_rss();
			make_backend_txt();
			success_template($lang['ADMIN_NEWS_ADDED']);
			header('Refresh: 3; URL= ./../admin/index.php');
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'view_submitted_news')
	{
		if ($table_users['user_level'] < 2)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['page'])
			{
				$_GET['page'] = 1;
			}
			if (!$_GET['list'])
			{
				$_GET['list'] = 0;
			}
			$sql->query('SELECT news_order, news_per_page
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
			$news_order = $table_settings['news_order'];
			$news_per_page = $table_settings['news_per_page'];
			$news_offset = (($_GET['page'] - 1) * $news_per_page);
			$sql->query('SELECT news_id
					FROM ' . TABLE_NEWS . '
					WHERE news_active = \'0\'');
			$num_news = $sql->num_rows();
			$num_pages = ceil($num_news / $news_per_page);
			if ($_GET['page'] != 1)
			{
				if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
			}
			if ($_GET['list'] != 0)
			{
				$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
			}
			for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
			{
				if ($_GET['page'] == $current_page)
				{
					$pages_list .= $_GET['page'] . ' ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
				}
			}
			if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
			{
				$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
			}
			if (($num_pages > 1) && ($_GET['page'] != $num_pages))
			{
				if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_submitted_news&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
			}
			$date_format = get_date_format();
			$date_offset = get_date_offset();
			$template->set_file('admin', 'admin/news/view_submitted.tpl');
			$template->set_block('admin', 'NEWS_BLOCK', 'news');
			$sql->query('SELECT ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_subject, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
					FROM ' . TABLE_NEWS . ', ' . TABLE_USERS . '
					WHERE ' . TABLE_NEWS . '.news_active = \'0\' AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id
					ORDER BY ' . $news_order . ' DESC
					LIMIT ' . $news_offset . ', ' . $news_per_page . '');
			while ($table_news = $sql->fetch())
			{
				$news_date = date($date_format, ($table_news['news_date'] + $date_offset));
				$template->set_var(array(
					'ADD' => $lang['ADD'],
					'DELETE' => $lang['DELETE'],
					'NEWS_ID' => $table_news['news_id'],
					'NEWS_RELEASE' => sprintf($lang['ADMIN_NEWS_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
					'NEWS_SUBJECT' => $table_news['news_subject']));
				$template->parse('news', 'NEWS_BLOCK', true);
			}
			$template->set_var(array(
				'ADMIN_NEWS_HEADER2' => $lang['ADMIN_NEWS_HEADER2'],
				'ADMIN_NEWS_PAGES' => sprintf($lang['ADMIN_NEWS_PAGES'], $pages_list),
				'BACK_ADMIN_AREA1' => $lang['BACK_ADMIN_AREA1']));
		}
	}
//
	elseif ($_GET['action'] == 'add_submitted_news')
	{
		if ($table_users['user_level'] < 2)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text, ' . TABLE_NEWS . '.user_id
					FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . '
					WHERE ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND ' . TABLE_NEWS . '.news_id = \'' . $_GET['news_id'] . '\'');
			$table_news = $sql->fetch();
			$sql->query('SELECT category_id, category_name
					FROM ' . TABLE_CATEGORIES . '
					WHERE category_level != \'1\'
					ORDER BY category_name');
			while ($table_categories = $sql->fetch())
			{
				if ($table_categories['category_name'] == $table_news['category_name'])
				{
					$category_name_options .= '<option value="' . $table_categories['category_id'] . '" selected="selected">' . $table_categories['category_name'] . '</option>';
				}
				else
				{
					$category_name_options .= '<option value="' . $table_categories['category_id'] . '">' . $table_categories['category_name'] . '</option>';
				}
			}
			$sql->query('SELECT smiley_code, smiley_image
					FROM ' . TABLE_SMILIES . '');
			while ($table_smilies = $sql->fetch())
			{
				$table_news['news_text'] = str_replace('<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $table_smilies['smiley_code'], $table_news['news_text']);
			}
			$table_news['news_text'] = undo_bbcode($table_news['news_text']);
			$table_news['news_source'] = undo_bbcode($table_news['news_source']);
			$template->set_file('admin', 'admin/news/add_submitted.tpl');
			$template->set_var(array(
				'ADD' => $lang['ADD'],
				'ADMIN_NEWS_HEADER3' => $lang['ADMIN_NEWS_HEADER3'],
				'BACK_ADMIN_AREA1' => $lang['BACK_ADMIN_AREA1'],
				'CATEGORY_NAME_OPTIONS' => $category_name_options,
				'FORM_NEWS_CATEGORY' => $lang['FORM_NEWS_CATEGORY'],
				'FORM_NEWS_SOURCE' => $lang['FORM_NEWS_SOURCE'],
				'FORM_NEWS_SUBJECT' => $lang['FORM_NEWS_SUBJECT'],
				'FORM_NEWS_TEXT' => $lang['FORM_NEWS_TEXT'],
				'NEWS_ID' => $_GET['news_id'],
				'NEWS_SUBJECT' => $table_news['news_subject'],
				'NEWS_SOURCE' => $table_news['news_source'],
				'NEWS_TEXT' => $table_news['news_text'],
				'SMILIES_LIST' => get_smilies_list(),
				'USER_ID' => $table_news['user_id']));
		}
	}
//
	elseif ($_POST['add_submitted_news'])
	{
		if (!trim($_POST['news_subject']))
		{
			$error .= $lang['NO_NEWS_SUBJECT'];
		}
		if (!trim($_POST['news_text']))
		{
			$error .= $lang['NO_NEWS_TEXT'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$news_subject = htmlspecialchars($_POST['news_subject']);
			$sql->query('SELECT allow_html, allow_smilies
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
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
			if ($table_settings['allow_smilies'] == 1)
			{
				$sql->query('SELECT smiley_code, smiley_image
						FROM ' . TABLE_SMILIES . '');
				while ($table_smilies = $sql->fetch())
				{
					$news_text = str_replace($table_smilies['smiley_code'], '<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $news_text);
				}
			}
			$sql->query('UPDATE ' . TABLE_NEWS . '
					SET category_id = \'' . $_POST['category_id'] . '\', user_id = \'' . $_POST['user_id'] . '\', news_active = \'1\', news_subject = \'' . $news_subject . '\', news_text = \'' . $news_text . '\', news_source = \'' . $news_source . '\', news_date = \'' . time() . '\', news_month = \'' . date('m', time()) . '\', news_year = \'' . date('Y', time()) . '\'
					WHERE news_id = \'' . $_POST['news_id'] . '\'');
			$sql->query('UPDATE ' . TABLE_CATEGORIES . '
					SET category_news = category_news + 1
					WHERE category_id = \'' . $_POST['category_id'] . '\'');
			make_backend_rss();
			make_backend_txt();
			success_template($lang['ADMIN_NEWS_ADDED']);
			header('Refresh: 3; URL= ./../admin/index.php');
		}
	}
//
	elseif ($_GET['action'] == 'delete_submitted_news')
	{
		if ($table_users['user_level'] < 2)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT category_id
					FROM ' . TABLE_NEWS . '
					WHERE news_id = \'' . $_GET['news_id'] . '\'');
			$table_news = $sql->fetch();
			$sql->query('UPDATE ' . TABLE_CATEGORIES . '
					SET category_news = category_news - 1
					WHERE category_id = \'' . $table_news['category_id'] . '\'');
			$sql->query('DELETE FROM ' . TABLE_NEWS . '
					WHERE news_id = \'' . $_GET['news_id'] . '\'');
			success_template($lang['ADMIN_NEWS_DELETED']);
			header('Refresh: 3; URL= ./../admin/index.php');
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'view_news')
	{
		if ($table_users['user_level'] < 3)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['page'])
			{
				$_GET['page'] = 1;
			}
			if (!$_GET['list'])
			{
				$_GET['list'] = 0;
			}
			$sql->query('SELECT news_order, news_per_page
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
			$news_order = $table_settings['news_order'];
			$news_per_page = $table_settings['news_per_page'];
			$news_offset = (($_GET['page'] - 1) * $news_per_page);
			$sql->query('SELECT news_id
					FROM ' . TABLE_NEWS . '
					WHERE news_active = \'1\'');
			$num_news = $sql->num_rows();
			$num_pages = ceil($num_news / $news_per_page);
			if ($_GET['page'] != 1)
			{
				if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
			}
			if ($_GET['list'] != 0)
			{
				$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
			}
			for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
			{
				if ($_GET['page'] == $current_page)
				{
					$pages_list .= $_GET['page'] . ' ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
				}
			}
			if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
			{
				$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
			}
			if (($num_pages > 1) && ($_GET['page'] != $num_pages))
			{
				if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_news&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
			}
			$date_format = get_date_format();
			$date_offset = get_date_offset();
			$template->set_file('admin', 'admin/news/view.tpl');
			$template->set_block('admin', 'NEWS_BLOCK', 'news');
			$sql->query('SELECT ' . TABLE_NEWS . '.news_date, ' . TABLE_NEWS . '.news_id, ' . TABLE_NEWS . '.news_subject, ' . TABLE_USERS . '.user_id, ' . TABLE_USERS . '.user_name
					FROM ' . TABLE_NEWS . ', ' . TABLE_USERS . '
					WHERE ' . TABLE_NEWS . '.news_active = \'1\' AND ' . TABLE_NEWS . '.user_id = ' . TABLE_USERS . '.user_id
					ORDER BY ' . $news_order . ' DESC
					LIMIT ' . $news_offset . ', ' . $news_per_page . '');
			while ($table_news = $sql->fetch())
			{
				$news_date = date($date_format, ($table_news['news_date'] + $date_offset));
				$template->set_var(array(
					'DELETE' => $lang['DELETE'],
					'EDIT' => $lang['EDIT'],
					'NEWS_ID' => $table_news['news_id'],
					'NEWS_RELEASE' => sprintf($lang['ADMIN_NEWS_RELEASE'], $table_news['user_id'], $table_news['user_name'], $news_date),
					'NEWS_SUBJECT' => $table_news['news_subject']));
				$template->parse('news', 'NEWS_BLOCK', true);
			}
			$template->set_var(array(
				'ADMIN_NEWS_HEADER4' => $lang['ADMIN_NEWS_HEADER4'],
				'ADMIN_NEWS_PAGES' => sprintf($lang['ADMIN_NEWS_PAGES'], $pages_list),
				'BACK_ADMIN_AREA1' => $lang['BACK_ADMIN_AREA1']));
		}
	}
//
	elseif ($_GET['action'] == 'edit_news')
	{
		if ($table_users['user_level'] < 3)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT ' . TABLE_CATEGORIES . '.category_name, ' . TABLE_NEWS . '.news_source, ' . TABLE_NEWS . '.news_subject, ' . TABLE_NEWS . '.news_text
					FROM ' . TABLE_CATEGORIES . ', ' . TABLE_NEWS . '
					WHERE ' . TABLE_CATEGORIES . '.category_id = ' . TABLE_NEWS . '.category_id AND news_id = \'' . $_GET['news_id'] . '\'');
			$table_news = $sql->fetch();
			$sql->query('SELECT category_id, category_name
					FROM ' . TABLE_CATEGORIES . '
					WHERE category_level != \'1\'
					ORDER BY category_name');
			while ($table_categories = $sql->fetch())
			{
				if ($table_categories['category_name'] == $table_news['category_name'])
				{
					$category_id_old = $table_categories['category_id'];
					$category_name_options .= '<option value="' . $table_categories['category_id'] . '" selected="selected">' . $table_categories['category_name'] . '</option>';
				}
				else
				{
					$category_name_options .= '<option value="' . $table_categories['category_id'] . '">' . $table_categories['category_name'] . '</option>';
				}
			}
			$sql->query('SELECT smiley_code, smiley_image
					FROM ' . TABLE_SMILIES . '');
			while ($table_smilies = $sql->fetch())
			{
				$table_news['news_text'] = str_replace('<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $table_smilies['smiley_code'], $table_news['news_text']);
			}
			$table_news['news_text'] = undo_bbcode($table_news['news_text']);
			$table_news['news_source'] = undo_bbcode($table_news['news_source']);
			$template->set_file('admin', 'admin/news/edit.tpl');
			$template->set_var(array(
				'ADMIN_NEWS_HEADER5' => $lang['ADMIN_NEWS_HEADER5'],
				'BACK_ADMIN_AREA1' => $lang['BACK_ADMIN_AREA1'],
				'CATEGORY_ID_OLD' => $category_id_old,
				'CATEGORY_NAME_OPTIONS' => $category_name_options,
				'EDIT' => $lang['EDIT'],
				'FORM_NEWS_CATEGORY' => $lang['FORM_NEWS_CATEGORY'],
				'FORM_NEWS_SOURCE' => $lang['FORM_NEWS_SOURCE'],
				'FORM_NEWS_SUBJECT' => $lang['FORM_NEWS_SUBJECT'],
				'FORM_NEWS_TEXT' => $lang['FORM_NEWS_TEXT'],
				'NEWS_ID' => $_GET['news_id'],
				'NEWS_SOURCE' => $table_news['news_source'],
				'NEWS_SUBJECT' => $table_news['news_subject'],
				'NEWS_TEXT' => $table_news['news_text'],
				'SMILIES_LIST' => get_smilies_list()));
		}
	}
//
	elseif ($_POST['edit_news'])
	{
		if (!trim($_POST['news_subject']))
		{
			$error .= $lang['NO_NEWS_SUBJECT'];
		}
		if (!trim($_POST['news_text']))
		{
			$error .= $lang['NO_NEWS_TEXT'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$news_subject = htmlspecialchars($_POST['news_subject']);
			$sql->query('SELECT allow_html, allow_smilies
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
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
			if ($table_settings['allow_smilies'] == 1)
			{
				$sql->query('SELECT smiley_code, smiley_image
						FROM ' . TABLE_SMILIES . '');
				while ($table_smilies = $sql->fetch())
				{
					$news_text = str_replace($table_smilies['smiley_code'], '<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $news_text);
				}
			}
			$sql->query('UPDATE ' . TABLE_NEWS . '
					SET news_subject = \'' . $news_subject . '\', category_id = \'' . $_POST['category_id'] . '\', news_text = \'' . $news_text . '\', news_source = \'' . $news_source . '\'
					WHERE news_id = \'' . $_POST['news_id'] . '\'');
			if ($_POST['category_id'] != $_POST['category_id_old'])
			{
				$sql->query('UPDATE ' . TABLE_CATEGORIES . '
						SET category_news = category_news + 1
						WHERE category_id = \'' . $_POST['category_id'] . '\'');
				$sql->query('UPDATE ' . TABLE_CATEGORIES . '
						SET category_news = category_news - 1
						WHERE category_id = \'' . $_POST['category_id_old'] . '\'');
			}
			make_backend_rss();
			make_backend_txt();
			success_template($lang['ADMIN_NEWS_EDITED']);
			header('Refresh: 3; URL= ./../admin/index.php');
		}
	}
//
	elseif ($_GET['action'] == 'delete_news')
	{
		if ($table_users['user_level'] < 3)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT news_id
					FROM ' . TABLE_COMMENTS . '
					WHERE news_id = \'' . $_GET['news_id'] . '\'');
			$table_comments = $sql->fetch();
			if ($table_comments['news_id'])
			{
				error_template($lang['ADMIN_NEWS_ERROR2']);
			}
			else
			{
				$sql->query('SELECT category_id
						FROM ' . TABLE_NEWS . '
						WHERE news_id = \'' . $_GET['news_id'] . '\'');
				$table_news = $sql->fetch();
				$sql->query('UPDATE ' . TABLE_CATEGORIES . '
						SET category_news = category_news - 1
						WHERE category_id = \'' . $table_news['category_id'] . '\'');
				$sql->query('DELETE FROM ' . TABLE_NEWS . '
						WHERE news_id = \'' . $_GET['news_id'] . '\'');
				make_backend_rss();
				make_backend_txt();
				success_template($lang['ADMIN_NEWS_DELETED']);
				header('Refresh: 3; URL= ./../admin/index.php');
			}
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'view_advanced_settings')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$template->set_file('admin', 'admin/area2.tpl');
			$template->set_var(array(
				'ADMIN_AREA2_HEADER' => $lang['ADMIN_AREA2_HEADER'],
				'ADMIN_AREA2_LINK1' => $lang['ADMIN_AREA2_LINK1'],
				'ADMIN_AREA2_LINK2' => $lang['ADMIN_AREA2_LINK2'],
				'ADMIN_AREA2_LINK3' => $lang['ADMIN_AREA2_LINK3'],
				'ADMIN_AREA2_LINK4' => $lang['ADMIN_AREA2_LINK4'],
				'ADMIN_AREA2_LINK5' => $lang['ADMIN_AREA2_LINK5'],
				'ADMIN_AREA2_LINK6' => $lang['ADMIN_AREA2_LINK6'],
				'ADMIN_AREA2_LINK7' => $lang['ADMIN_AREA2_LINK7'],
				'ADMIN_AREA2_LINK8' => $lang['ADMIN_AREA2_LINK8'],
				'ADMIN_AREA2_LINK9' => $lang['ADMIN_AREA2_LINK9'],
				'ADMIN_AREA2_LINK10' => $lang['ADMIN_AREA2_LINK10'],
				'ADMIN_IMG_CATEGORIES' => $lang['ADMIN_IMG_CATEGORIES'],
				'ADMIN_IMG_POLLS' => $lang['ADMIN_IMG_POLLS'],
				'ADMIN_IMG_SETTINGS' => $lang['ADMIN_IMG_SETTINGS'],
				'ADMIN_IMG_SMILIES' => $lang['ADMIN_IMG_SMILIES'],
				'ADMIN_IMG_TEMPLATES' => $lang['ADMIN_IMG_TEMPLATES'],
				'ADMIN_IMG_USERS' => $lang['ADMIN_IMG_USERS'],
				'BACK_ADMIN_AREA1' => $lang['BACK_ADMIN_AREA1']));
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'edit_advanced_settings')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT *
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
			if ($table_settings['language'] == 'dutch')
			{
				$dutch_selected = ' selected="selected"';
				$english_selected = '';
				$french_selected = '';
				$german_selected = '';
				$italian_selected = '';
				$polish_selected = '';
				$spanish_selected = '';
			}
			elseif ($table_settings['language'] == 'english')
			{
				$dutch_selected = '';
				$english_selected = ' selected="selected"';
				$french_selected = '';
				$german_selected = '';
				$italian_selected = '';
				$polish_selected = '';
				$spanish_selected = '';
			}
			elseif ($table_settings['language'] == 'french')
			{
				$dutch_selected = '';
				$english_selected = '';
				$french_selected = ' selected="selected"';
				$german_selected = '';
				$italian_selected = '';
				$polish_selected = '';
				$spanish_selected = '';
			}
			elseif ($table_settings['language'] == 'german')
			{
				$dutch_selected = '';
				$english_selected = '';
				$french_selected = '';
				$german_selected = ' selected="selected"';
				$italian_selected = '';
				$polish_selected = '';
				$spanish_selected = '';
			}
			elseif ($table_settings['language'] == 'italian')
			{
				$dutch_selected = '';
				$english_selected = '';
				$french_selected = '';
				$german_selected = '';
				$italian_selected = ' selected="selected"';
				$polish_selected = '';
				$spanish_selected = '';
			}
			elseif ($table_settings['language'] == 'polish')
			{
				$dutch_selected = '';
				$english_selected = '';
				$french_selected = '';
				$german_selected = '';
				$italian_selected = '';
				$polish_selected = ' selected="selected"';
				$spanish_selected = '';
			}
			else
			{
				$dutch_selected = '';
				$english_selected = '';
				$french_selected = '';
				$german_selected = '';
				$italian_selected = '';
				$polish_selected = '';
				$spanish_selected = ' selected="selected"';
			}
			if ($table_settings['language_unique'] == 1)
			{
				$more_language = '';
				$one_language = ' selected="selected"';
			}
			else
			{
				$more_language = ' selected="selected"';
				$one_language = '';
			}
			if ($table_settings['template'] == 'default')
			{
				$default_selected = ' selected="selected"';
				$original_selected = '';
			}
			else
			{
				$default_selected = '';
				$original_selected = ' selected="selected"';
			}
			if ($table_settings['template_unique'] == 1)
			{
				$more_template = '';
				$one_template = ' selected="selected"';
			}
			else
			{
				$more_template = ' selected="selected"';
				$one_template = '';
			}
			if ($table_settings['news_order'] == 'news_date')
			{
				$date_selected = ' selected="selected"';
				$month_selected = '';
				$year_selected = '';
			}
			elseif ($table_settings['news_order'] == 'news_month')
			{
				$date_selected = '';
				$month_selected = ' selected="selected"';
				$year_selected = '';
			}
			else
			{
				$date_selected = '';
				$month_selected = '';
				$year_selected = ' selected="selected"';
			}
			if ($table_settings['allow_html'] == 1)
			{
				$html_yes = ' selected="selected"';
				$html_no = '';
			}
			else
			{
				$html_yes = '';
				$html_no = ' selected="selected"';
			}
			if ($table_settings['allow_smilies'] == 1)
			{
				$smilies_yes = ' selected="selected"';
				$smilies_no = '';
			}
			else
			{
				$smilies_yes = '';
				$smilies_no = ' selected="selected"';
			}
			if ($table_settings['submit_news'] == 1)
			{
				$submit_yes = ' selected="selected"';
				$submit_no = '';
			}
			else
			{
				$submit_yes = '';
				$submit_no = ' selected="selected"';
			}
			if ($table_settings['send_news'] == 1)
			{
				$send_yes = ' selected="selected"';
				$send_no = '';
			}
			else
			{
				$send_yes = '';
				$send_no = ' selected="selected"';
			}
			if ($table_settings['register_users'] == 1)
			{
				$register_yes = ' selected="selected"';
				$register_no = '';
			}
			else
			{
				$register_yes = '';
				$register_no = ' selected="selected"';
			}
			$template->set_file('admin', 'admin/settings/edit.tpl');
			$template->set_var(array(
				'ADMIN_SETTINGS_HEADER' => $lang['ADMIN_SETTINGS_HEADER'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'COMMENTS_PER_PAGE' => $table_settings['comments_per_page'],
				'DATE' => $lang['DATE'],
				'DATE_FORMAT' => $table_settings['date_format'],
				'DATE_OFFSET' => $table_settings['date_offset'],
				'DATE_SELECTED' => $date_selected,
				'DEFAULT_SELECTED' => $default_selected,
				'DUTCH' => $lang['DUTCH'],
				'DUTCH_SELECTED' => $dutch_selected,
				'EDIT' => $lang['EDIT'],
				'ENGLISH' => $lang['ENGLISH'],
				'ENGLISH_SELECTED' => $english_selected,
				'FORM_ALLOW_HTML' => $lang['FORM_ALLOW_HTML'],
				'FORM_ALLOW_SMILIES' => $lang['FORM_ALLOW_SMILIES'],
				'FORM_COMMENTS_PER_PAGE' => $lang['FORM_COMMENTS_PER_PAGE'],
				'FORM_DATE_FORMAT' => $lang['FORM_DATE_FORMAT'],
				'FORM_DATE_OFFSET' => $lang['FORM_DATE_OFFSET'],
				'FORM_HEADLINES_PER_BACKEND' => $lang['FORM_HEADLINES_PER_BACKEND'],
				'FORM_LANGUAGE' => $lang['FORM_LANGUAGE'],
				'FORM_NEWS_ORDER' => $lang['FORM_NEWS_ORDER'],
				'FORM_NEWS_PER_PAGE' => $lang['FORM_NEWS_PER_PAGE'],
				'FORM_POSTS_PER_PAGE' => $lang['FORM_POSTS_PER_PAGE'],
				'FORM_REGISTER_USERS' => $lang['FORM_REGISTER_USERS'],
				'FORM_SEND_NEWS' => $lang['FORM_SEND_NEWS'],
				'FORM_SENDER_EMAIL' => $lang['FORM_SENDER_EMAIL'],
				'FORM_SENDER_NAME' => $lang['FORM_SENDER_NAME'],
				'FORM_SETTINGS_ONLY' => $lang['FORM_SETTINGS_ONLY'],
				'FORM_SITENAME' => $lang['FORM_SITENAME'],
				'FORM_SITEURL' => $lang['FORM_SITEURL'],
				'FORM_SUBMIT_NEWS' => $lang['FORM_SUBMIT_NEWS'],
				'FORM_TEMPLATE' => $lang['FORM_TEMPLATE'],
				'FORM_THREADS_PER_PAGE' => $lang['FORM_THREADS_PER_PAGE'],
				'FRENCH' => $lang['FRENCH'],
				'FRENCH_SELECTED' => $french_selected,
				'GERMAN' => $lang['GERMAN'],
				'GERMAN_SELECTED' => $german_selected,
				'HEADLINES_PER_BACKEND' => $table_settings['headlines_per_backend'],
				'HTML_NO' => $html_no,
				'HTML_YES' => $html_yes,
				'ITALIAN' => $lang['ITALIAN'],
				'ITALIAN_SELECTED' => $italian_selected,
				'MONTH' => $lang['MONTH'],
				'MONTH_SELECTED' => $month_selected,
				'MORE_LANGUAGE' => $more_language,
				'MORE_TEMPLATE' => $more_template,
				'NEWS_PER_PAGE' => $table_settings['news_per_page'],
				'NO' => $lang['NO'],
				'ONE_LANGUAGE' => $one_language,
				'ONE_TEMPLATE' => $one_template,
				'ORIGINAL_SELECTED' => $original_selected,
				'POLISH' => $lang['POLISH'],
				'POLISH_SELECTED' => $polish_selected,
				'POSTS_PER_PAGE' => $table_settings['posts_per_page'],
				'REGISTER_NO' => $register_no,
				'REGISTER_YES' => $register_yes,
				'SEND_NO' => $send_no,
				'SEND_YES' => $send_yes,
				'SENDER_EMAIL' => $table_settings['sender_email'],
				'SENDER_NAME' => $table_settings['sender_name'],
				'SITENAME' => $table_settings['sitename'],
				'SITEURL' => $table_settings['siteurl'],
				'SMILIES_NO' => $smilies_no,
				'SMILIES_YES' => $smilies_yes,
				'SPANISH' => $lang['SPANISH'],
				'SPANISH_SELECTED' => $spanish_selected,
				'SUBMIT_NO' => $submit_no,
				'SUBMIT_YES' => $submit_yes,
				'THREADS_PER_PAGE' => $table_settings['threads_per_page'],
				'YEAR' => $lang['YEAR'],
				'YEAR_SELECTED' => $year_selected,
				'YES' => $lang['YES']));
		}
	}
//
	elseif ($_POST['edit_advanced_settings'])
	{
		if (!trim($_POST['sitename']))
		{
			$error .= $lang['NO_SITENAME'];
		}
		if (!trim($_POST['siteurl']))
		{
			$error .= $lang['NO_SITEURL'];
		}
		if (ereg('/$', trim($_POST['siteurl'])))
		{
			$error .= $lang['ADMIN_SETTINGS_ERROR'];
		}
		if (!trim($_POST['sender_email']))
		{
			$error .= $lang['NO_USER_EMAIL'];
		}
		if (check_email($_POST['sender_email']))
		{
			$sender_email = $_POST['sender_email'];
		}
		else
		{
			$error .= $lang['INVALID_USER_EMAIL'];
		}
		if (!trim($_POST['sender_name']))
		{
			$error .= $lang['NO_USER_NAME'];
		}
		if (!trim($_POST['date_format']))
		{
			$error .= $lang['NO_DATE_FORMAT'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			if (!trim($_POST['news_per_page']))
			{
				$_POST['news_per_page'] = 1;
			}
			if (!trim($_POST['comments_per_page']))
			{
				$_POST['comments_per_page'] = 1;
			}
			if (!trim($_POST['headlines_per_backend']))
			{
				$_POST['headlines_per_backend'] = 1;
			}
			if (!trim($_POST['threads_per_page']))
			{
				$_POST['threads_per_page'] = 1;
			}
			if (!trim($_POST['posts_per_page']))
			{
				$_POST['posts_per_page'] = 1;
			}
			if (!trim($_POST['date_offset']))
			{
				$_POST['date_offset'] = 0;
			}
			$sql->query('UPDATE ' . TABLE_SETTINGS . '
					SET sitename = \'' . $_POST['sitename'] . '\', siteurl = \'' . $_POST['siteurl'] . '\', language = \'' . $_POST['language'] . '\', language_unique = \'' . $_POST['language_unique'] . '\', template = \'' . $_POST['template'] . '\', template_unique = \'' . $_POST['template_unique'] . '\', news_per_page = \'' . $_POST['news_per_page'] . '\', comments_per_page = \'' . $_POST['comments_per_page'] . '\', headlines_per_backend = \'' . $_POST['headlines_per_backend'] . '\', threads_per_page = \'' . $_POST['threads_per_page'] . '\', posts_per_page = \'' . $_POST['posts_per_page'] . '\', news_order = \'' . $_POST['news_order'] . '\', allow_html = \'' . $_POST['allow_html'] . '\', allow_smilies = \'' . $_POST['allow_smilies'] . '\', submit_news = \'' . $_POST['submit_news'] . '\', send_news = \'' . $_POST['send_news'] . '\', register_users = \'' . $_POST['register_users'] . '\', sender_email = \'' . $sender_email . '\', sender_name = \'' . $_POST['sender_name'] . '\', date_format = \'' . $_POST['date_format'] . '\', date_offset = \'' . $_POST['date_offset'] . '\'');
			make_backend_rss();
			make_backend_txt();
			success_template($lang['ADMIN_SETTINGS_SUCCESS']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'add_categories')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$template->set_file('admin', 'admin/categories/add.tpl');
			$template->set_var(array(
				'ADD' => $lang['ADD'],
				'ADMIN_CATEGORIES_HEADER1' => $lang['ADMIN_CATEGORIES_HEADER1'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'FORM_CATEGORY_IMAGE' => $lang['FORM_CATEGORY_IMAGE'],
				'FORM_CATEGORY_LEVEL' => $lang['FORM_CATEGORY_LEVEL'],
				'FORM_CATEGORY_NAME' => $lang['FORM_CATEGORY_NAME']));
		}
	}
//
	elseif ($_POST['add_category'])
	{
		$upload_dir = './../images/categories/';
		$upload_name = $_FILES['category_image']['name'];
		$upload_file = $upload_dir . $upload_name;
		$upload_ext = substr($upload_name, strrpos($upload_name, '.'));
		$valid_ext = array('.gif', '.GIF', '.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
		$sql->query('SELECT category_name
				FROM ' . TABLE_CATEGORIES . '
				WHERE category_name = \'' . $_POST['category_name'] . '\'');
		$table_categories = $sql->fetch();
		if ($table_categories['category_name'])
		{
			$error .= $lang['ADMIN_CATEGORIES_ERROR1'];
		}
		if (!trim($_POST['category_name']))
		{
			$error .= $lang['NO_CATEGORY_NAME'];
		}
		if (!trim($upload_name))
		{
			$error .= $lang['NO_CATEGORY_IMAGE'];
		}
		if (file_exists($upload_file))
		{
			$error .= $lang['ADMIN_CATEGORIES_ERROR2'];
		}
		if (!in_array($upload_ext, $valid_ext))
		{
			$error .= $lang['INVALID_IMAGE_FILE'];
		}
		if (!is_writable($upload_dir))
		{
			$error .= sprintf($lang['ADMIN_CATEGORIES_ERROR3'], $upload_dir);
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$sql->query('INSERT INTO ' . TABLE_CATEGORIES . ' (category_name, category_image, category_level)
					VALUES (\'' . $_POST['category_name'] . '\', \'' . $upload_file . '\', \'' . $_POST['category_level'] . '\')');
			move_uploaded_file($_FILES['category_image']['tmp_name'], $upload_file);
			success_template($lang['ADMIN_CATEGORIES_ADDED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
//
	elseif ($_GET['action'] == 'edit_categories')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT category_id, category_image, category_name
					FROM ' . TABLE_CATEGORIES . '
					ORDER BY category_name');
			$template->set_file('admin', 'admin/categories/view.tpl');
			$template->set_block('admin', 'CATEGORIES_BLOCK', 'categories');
			while ($table_categories = $sql->fetch())
			{
				$template->set_var(array(
					'CATEGORY_ID' => $table_categories['category_id'],
					'CATEGORY_IMAGE' => $table_categories['category_image'],
					'CATEGORY_NAME' => $table_categories['category_name'],
					'DELETE' => $lang['DELETE'],
					'EDIT' => $lang['EDIT']));
				$template->parse('categories', 'CATEGORIES_BLOCK', true);
			}
			$template->set_var(array(
				'ADMIN_CATEGORIES_HEADER2' => $lang['ADMIN_CATEGORIES_HEADER2'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2']));
		}
	}
//
	elseif ($_GET['action'] == 'edit_category')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT category_id, category_image, category_level, category_name
					FROM ' . TABLE_CATEGORIES . '
					WHERE category_id = \'' . $_GET['category_id'] . '\'');
			$table_categories = $sql->fetch();
			if ($table_categories['category_level'] == 0)
			{
				$level0_selected = ' selected="selected"';
				$level1_selected = '';
				$level2_selected = '';
			}
			elseif ($table_categories['category_level'] == 1)
			{
				$level0_selected = '';
				$level1_selected = ' selected="selected"';
				$level2_selected = '';
			}
			else
			{
				$level0_selected = '';
				$level1_selected = '';
				$level2_selected = ' selected="selected"';
			}
			$template->set_file('admin', 'admin/categories/edit.tpl');
			$template->set_var(array(
				'ADMIN_CATEGORIES_HEADER3' => $lang['ADMIN_CATEGORIES_HEADER3'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'CATEGORY_ID' => $table_categories['category_id'],
				'CATEGORY_IMAGE' => $table_categories['category_image'],
				'EDIT' => $lang['EDIT'],
				'CATEGORY_NAME' => $table_categories['category_name'],
				'FORM_CATEGORY_IMAGE' => $lang['FORM_CATEGORY_IMAGE'],
				'FORM_CATEGORY_LEVEL' => $lang['FORM_CATEGORY_LEVEL'],
				'FORM_CATEGORY_NAME' => $lang['FORM_CATEGORY_NAME'],
				'FORM_CURRENT_IMAGE' => $lang['FORM_CURRENT_IMAGE'],
				'LEVEL0_SELECTED' => $level0_selected,
				'LEVEL1_SELECTED' => $level1_selected,
				'LEVEL2_SELECTED' => $level2_selected));
		}
	}
//
	elseif ($_POST['edit_category'])
	{
		$upload_dir = './../images/categories/';
		$upload_name = $_FILES['category_image2']['name'];
		$upload_file = $upload_dir . $upload_name;
		$upload_ext = substr($upload_name, strrpos($upload_name, '.'));
		$valid_ext = array('.gif', '.GIF', '.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
		$sql->query('SELECT category_name
				FROM ' . TABLE_CATEGORIES . '
				WHERE category_name = \'' . $_POST['category_name'] . '\' AND category_id != \'' . $_POST['category_id'] . '\'');
		$table_categories = $sql->fetch();
		if ($table_categories['category_name'])
		{
			$error .= $lang['ADMIN_CATEGORIES_ERROR1'];
		}
		if (!trim($_POST['category_name']))
		{
			$error .= $lang['NO_CATEGORY_NAME'];
		}
		if ($upload_name)
		{
			if (file_exists($upload_file))
			{
				$error .= $lang['ADMIN_CATEGORIES_ERROR2'];
			}
			if (!in_array($upload_ext, $valid_ext))
			{
				$error .= $lang['INVALID_IMAGE_FILE'];
			}
			if (!is_writable($upload_dir))
			{
				$error .= sprintf($lang['ADMIN_CATEGORIES_ERROR3'], $upload_dir);
			}
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			if (!$upload_name)
			{
				$category_image = $_POST['category_image'];
			}
			else
			{
				$category_image = $upload_file;
				unlink($_POST['category_image']);
			}
			$sql->query('UPDATE ' . TABLE_CATEGORIES . '
					SET category_name = \'' . $_POST['category_name'] . '\', category_image = \'' . $category_image . '\', category_level = \'' . $_POST['category_level'] . '\'
					WHERE category_id = \'' . $_POST['category_id'] . '\'');
			move_uploaded_file($_FILES['category_image2']['tmp_name'], $upload_file);
			success_template($lang['ADMIN_CATEGORIES_EDITED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
//
	elseif ($_GET['action'] == 'delete_category')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT category_id
					FROM ' . TABLE_NEWS . '
					WHERE category_id = \'' . $_GET['category_id'] . '\'');
			$table_news = $sql->fetch();
			$sql->query('SELECT category_id
					FROM ' . TABLE_POSTS . '
					WHERE category_id = \'' . $_GET['category_id'] . '\'');
			$table_posts = $sql->fetch();
			if ($table_news['category_id'] || $table_posts['category_id'])
			{
				error_template($lang['ADMIN_CATEGORIES_ERROR4']);
			}
			else
			{
				$sql->query('SELECT category_image
						FROM ' . TABLE_CATEGORIES . '
						WHERE category_id = \'' . $_GET['category_id'] . '\'');
				$table_categories = $sql->fetch();
				unlink($table_categories['category_image']);
				$sql->query('DELETE FROM ' . TABLE_CATEGORIES . '
						WHERE category_id = \'' . $_GET['category_id'] . '\'');
				success_template($lang['ADMIN_CATEGORIES_DELETED']);
				header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
			}
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'add_smilies')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$template->set_file('admin', 'admin/smilies/add.tpl');
			$template->set_var(array(
				'ADD' => $lang['ADD'],
				'ADMIN_SMILIES_HEADER1' => $lang['ADMIN_SMILIES_HEADER1'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'FORM_SMILEY_CODE' => $lang['FORM_SMILEY_CODE'],
				'FORM_SMILEY_IMAGE' => $lang['FORM_SMILEY_IMAGE']));
		}
	}
//
	elseif ($_POST['add_smiley'])
	{
		$upload_dir = './../images/smilies/';
		$upload_name = $_FILES['smiley_image']['name'];
		$upload_file = $upload_dir . $upload_name;
		$upload_ext = substr($upload_name, strrpos($upload_name, '.'));
		$valid_ext = array('.gif', '.GIF', '.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
		$sql->query('SELECT smiley_code
				FROM ' . TABLE_SMILIES . '
				WHERE smiley_code = \'' . $_POST['smiley_code'] . '\'');
		$table_smilies = $sql->fetch();
		if ($table_smilies['smiley_code'])
		{
			$error .= $lang['ADMIN_SMILIES_ERROR1'];
		}
		if (!trim($_POST['smiley_code']))
		{
			$error .= $lang['NO_SMILEY_CODE'];
		}
		if (!trim($upload_name))
		{
			$error .= $lang['NO_SMILEY_IMAGE'];
		}
		if (file_exists($upload_file))
		{
			$error .= $lang['ADMIN_SMILIES_ERROR2'];
		}
		if (!in_array($upload_ext, $valid_ext))
		{
			$error .= $lang['INVALID_IMAGE_FILE'];
		}
		if (!is_writable($upload_dir))
		{
			$error .= sprintf($lang['ADMIN_SMILIES_ERROR3'], $upload_dir);
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$sql->query('INSERT INTO ' . TABLE_SMILIES . ' (smiley_code, smiley_image)
					VALUES (\'' . $_POST['smiley_code'] . '\', \'' . $upload_file . '\')');
			move_uploaded_file($_FILES['smiley_image']['tmp_name'], $upload_file);
			success_template($lang['ADMIN_SMILIES_ADDED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
//
	elseif ($_GET['action'] == 'edit_smilies')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$template->set_file('admin', 'admin/smilies/view.tpl');
			$template->set_block('admin', 'SMILIES_BLOCK', 'smilies');
			$sql->query('SELECT *
					FROM ' . TABLE_SMILIES . '');
			while ($table_smilies = $sql->fetch())
			{
				$template->set_var(array(
					'DELETE' => $lang['DELETE'],
					'EDIT' => $lang['EDIT'],
					'SMILEY_CODE' => $table_smilies['smiley_code'],
					'SMILEY_ID' => $table_smilies['smiley_id'],
					'SMILEY_IMAGE' => $table_smilies['smiley_image']));
				$template->parse('smilies', 'SMILIES_BLOCK', true);
			}
			$template->set_var(array(
				'ADMIN_SMILIES_HEADER2' => $lang['ADMIN_SMILIES_HEADER2'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2']));
		}
	}
//
	elseif ($_GET['action'] == 'edit_smiley')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT *
					FROM ' . TABLE_SMILIES . '
					WHERE smiley_id = \'' . $_GET['smiley_id'] . '\'');
			$table_smilies = $sql->fetch();
			$template->set_file('admin', 'admin/smilies/edit.tpl');
			$template->set_var(array(
				'ADMIN_SMILIES_HEADER3' => $lang['ADMIN_SMILIES_HEADER3'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'EDIT' => $lang['EDIT'],
				'FORM_CURRENT_IMAGE' => $lang['FORM_CURRENT_IMAGE'],
				'FORM_SMILEY_CODE' => $lang['FORM_SMILEY_CODE'],
				'FORM_SMILEY_IMAGE' => $lang['FORM_SMILEY_IMAGE'],
				'SMILEY_CODE' => $table_smilies['smiley_code'],
				'SMILEY_ID' => $table_smilies['smiley_id'],
				'SMILEY_IMAGE' => $table_smilies['smiley_image']));
		}
	}
//
	elseif ($_POST['edit_smiley'])
	{
		$upload_dir = './../images/smilies/';
		$upload_name = $_FILES['smiley_image2']['name'];
		$upload_file = $upload_dir . $upload_name;
		$upload_ext = substr($upload_name, strrpos($upload_name, '.'));
		$valid_ext = array('.gif', '.GIF', '.jpg', '.JPG', '.jpeg', '.JPEG', '.png', '.PNG');
		$sql->query('SELECT smiley_code
				FROM ' . TABLE_SMILIES . '
				WHERE smiley_code = \'' . $_POST['smiley_code'] . '\' AND smiley_id != \'' . $_POST['smiley_id'] . '\'');
		$table_smilies = $sql->fetch();
		if ($table_smilies['smiley_code'])
		{
			$error .= $lang['ADMIN_SMILIES_ERROR1'];
		}
		if (!trim($_POST['smiley_code']))
		{
			$error .= $lang['NO_SMILEY_CODE'];
		}
		if ($upload_name)
		{
			if (file_exists($upload_file))
			{
				$error .= $lang['ADMIN_SMILIES_ERROR2'];
			}
			if (!in_array($upload_ext, $valid_ext))
			{
				$error .= $lang['INVALID_IMAGE_FILE'];
			}
			if (!is_writable($upload_dir))
			{
				$error .= sprintf($lang['ADMIN_SMILIES_ERROR3'], $upload_dir);
			}
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			if (!$upload_name)
			{
				$smiley_image = $_POST['smiley_image'];
			}
			else
			{
				$smiley_image = $upload_file;
				unlink($_POST['smiley_image']);
			}
			$sql->query('UPDATE ' . TABLE_SMILIES . '
					SET smiley_code = \'' . $_POST['smiley_code'] . '\', smiley_image = \'' . $smiley_image . '\'
					WHERE smiley_id = \'' . $_POST['smiley_id'] . '\'');
			move_uploaded_file($_FILES['smiley_image2']['tmp_name'], $upload_file);
			success_template($lang['ADMIN_SMILIES_EDITED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
//
	elseif ($_GET['action'] == 'delete_smiley')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT smiley_image
					FROM ' . TABLE_SMILIES . '
					WHERE smiley_id = \'' . $_GET['smiley_id'] . '\'');
			$table_smilies = $sql->fetch();
			unlink($table_smilies['smiley_image']);
			$sql->query('DELETE FROM ' . TABLE_SMILIES . '
					WHERE smiley_id = \'' . $_GET['smiley_id'] . '\'');
			success_template($lang['ADMIN_SMILIES_DELETED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'edit_users')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['page'])
			{
				$_GET['page'] = 1;
			}
			if (!$_GET['list'])
			{
				$_GET['list'] = 0;
			}
			$users_per_page = USERS_LIMIT;
			$users_offset = (($_GET['page'] - 1) * $users_per_page);
			$sql->query('SELECT user_id
					FROM ' . TABLE_USERS . '
					WHERE user_id != \'1\'');
			$num_users = $sql->num_rows();
			$num_pages = ceil($num_users / $users_per_page);
			if ($_GET['page'] != 1)
			{
				if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
				{
					$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
			}
			if ($_GET['list'] != 0)
			{
				$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
			}
			for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
			{
				if ($_GET['page'] == $current_page)
				{
					$pages_list .= $_GET['page'] . ' ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
				}
			}
			if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
			{
				$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
			}
			if (($num_pages > 1) && ($_GET['page'] != $num_pages))
			{
				if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
				{
					$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=edit_users&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
			}
			$sql->query('SELECT user_id, user_name
					FROM ' . TABLE_USERS . '
					ORDER BY user_name
					LIMIT ' . $users_offset . ', ' . $users_per_page . '');
			while ($table_users = $sql->fetch())
			{
				$user_name_list .= '(#' . $table_users['user_id'] . ')&nbsp;<a href="./../admin/index.php?action=edit_user&amp;user_id=' . $table_users['user_id'] . '" title="' . $table_users['user_name'] . '">' . $table_users['user_name'] . '</a><br />';
			}
			$template->set_file('admin', 'admin/users/view.tpl');
			$template->set_var(array(
				'ADMIN_USERS_HEADER1' => $lang['ADMIN_USERS_HEADER1'],
				'ADMIN_USERS_PAGES' => sprintf($lang['ADMIN_USERS_PAGES'], $pages_list),
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'FORM_USER_NAME' => $lang['FORM_USER_NAME'],
				'USER_NAME_LIST' => $user_name_list));
		}
	}
//
	elseif ($_GET['action'] == 'edit_user')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT language, language_unique, template, template_unique
					FROM ' . TABLE_SETTINGS . '');
			$table_settings = $sql->fetch();
			$sql->query('SELECT user_age, user_date_format, user_date_offset, user_email, user_ip, user_language, user_level, user_location, user_name, user_occupation, user_template, user_viewemail, user_website
					FROM ' . TABLE_USERS . '
					WHERE user_id = \'' . $_GET['user_id'] . '\'');
			$table_users = $sql->fetch();
			if ($table_users['user_level'] == 0)
			{
				$level0_selected = ' selected="selected"';
				$level1_selected = '';
				$level2_selected = '';
				$level3_selected = '';
				$level4_selected = '';
			}
			elseif ($table_users['user_level'] == 1)
			{
				$level0_selected = '';
				$level1_selected = ' selected="selected"';
				$level2_selected = '';
				$level3_selected = '';
				$level4_selected = '';
			}
			elseif ($table_users['user_level'] == 2)
			{
				$level0_selected = '';
				$level1_selected = '';
				$level2_selected = ' selected="selected"';
				$level3_selected = '';
				$level4_selected = '';
			}
			elseif ($table_users['user_level'] == 3)
			{
				$level0_selected = '';
				$level1_selected = '';
				$level2_selected = '';
				$level3_selected = ' selected="selected"';
				$level4_selected = '';
			}
			else
			{
				$level0_selected = '';
				$level1_selected = '';
				$level2_selected = '';
				$level3_selected = '';
				$level4_selected = ' selected="selected"';
			}
			if ($table_settings['language_unique'] == 1)
			{
				$language_options = '<option value="' . $table_settings['language'] . '">' . $lang['' . strtoupper($table_settings['language']) . ''] . '</option>';
			}
			elseif ($table_users['user_language'] == 'dutch')
			{
				$language_options = '<option value="dutch" selected="selected">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
			}
			elseif ($table_users['user_language'] == 'english')
			{
				$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english" selected="selected">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
			}
			elseif ($table_users['user_language'] == 'french')
			{
				$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french" selected="selected">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
			}
			elseif ($table_users['user_language'] == 'german')
			{
				$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german" selected="selected">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
			}
			elseif ($table_users['user_language'] == 'italian')
			{
				$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian" selected="selected">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
			}
			elseif ($table_users['user_language'] == 'polish')
			{
				$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish" selected="selected">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
			}
			else
			{
				$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish" selected="selected">' . $lang['SPANISH'] . '</option>';
			}
			if ($table_settings['template_unique'] == 1)
			{
				$template_options = '<option value="' . $table_settings['template'] . '">' . $table_settings['template'] . '</option>';
			}
			elseif ($table_users['user_template'] == 'default')
			{
				$template_options = '<option value="default" selected="selected">default</option><option value="original">original</option>';
			}
			else
			{
				$template_options = '<option value="default">default</option><option value="original" selected="selected">original</option>';
			}
			if ($table_users['user_viewemail'] == 0)
			{
				$no_selected = ' selected="selected"';
				$yes_selected = '';
			}
			else
			{
				$no_selected = '';
				$yes_selected = ' selected="selected"';
			}
			$template->set_file('admin', 'admin/users/edit.tpl');
			$template->set_var(array(
				'ADMIN_USERS_HEADER2' => $lang['ADMIN_USERS_HEADER2'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'EDIT' => $lang['EDIT'],
				'FORM_USER_AGE' => $lang['FORM_USER_AGE'],
				'FORM_USER_DATE_FORMAT' => $lang['FORM_USER_DATE_FORMAT'],
				'FORM_USER_DATE_OFFSET' => $lang['FORM_USER_DATE_OFFSET'],
				'FORM_USER_EMAIL' => $lang['FORM_USER_EMAIL'],
				'FORM_USER_IP' => $lang['FORM_USER_IP'],
				'FORM_USER_LANGUAGE' => $lang['FORM_USER_LANGUAGE'],
				'FORM_USER_LEVEL' => $lang['FORM_USER_LEVEL'],
				'FORM_USER_LOCATION' => $lang['FORM_USER_LOCATION'],
				'FORM_USER_NAME' => $lang['FORM_USER_NAME'],
				'FORM_USER_OCCUPATION' => $lang['FORM_USER_OCCUPATION'],
				'FORM_USER_TEMPLATE' => $lang['FORM_USER_TEMPLATE'],
				'FORM_USER_VIEWEMAIL' => $lang['FORM_USER_VIEWEMAIL'],
				'FORM_USER_WEBSITE' => $lang['FORM_USER_WEBSITE'],
				'LANGUAGE_OPTIONS' => $language_options,
				'LEVEL0_SELECTED' => $level0_selected,
				'LEVEL1_SELECTED' => $level1_selected,
				'LEVEL2_SELECTED' => $level2_selected,
				'LEVEL3_SELECTED' => $level3_selected,
				'LEVEL4_SELECTED' => $level4_selected,
				'NO' => $lang['NO'],
				'NO_SELECTED' => $no_selected,
				'LANGUAGE_OPTIONS' => $language_options,
				'TEMPLATE_OPTIONS' => $template_options,
				'USER_AGE' => $table_users['user_age'],
				'USER_DATE_FORMAT' => $table_users['user_date_format'],
				'USER_DATE_OFFSET' => $table_users['user_date_offset'],
				'USER_EMAIL' => $table_users['user_email'],
				'USER_ID' => $_GET['user_id'],
				'USER_IP' => $table_users['user_ip'],
				'USER_LOCATION' => $table_users['user_location'],
				'USER_NAME' => $table_users['user_name'],
				'USER_OCCUPATION' => $table_users['user_occupation'],
				'USER_TEMPLATE' => $table_users['user_template'],
				'USER_VIEWEMAIL' => $table_users['user_viewemail'],
				'USER_WEBSITE' => $table_users['user_website'],
				'YES' => $lang['YES'],
				'YES_SELECTED' => $yes_selected));
		}
	}
//
	elseif ($_POST['edit_user'])
	{
		if (!trim($_POST['user_name']))
		{
			$error .= $lang['NO_USER_NAME'];
		}
		if (!trim($_POST['user_email']))
		{
			$error .= $lang['NO_USER_EMAIL'];
		}
		if (!trim($_POST['user_date_format']))
		{
			$error .= $lang['NO_USER_DATE_FORMAT'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			if (!trim($_POST['user_date_offset']))
			{
				$_POST['user_date_offset'] = 0;
			}
			$sql->query('UPDATE ' . TABLE_USERS . '
					SET user_age = \'' . $_POST['user_age'] . '\', user_date_format = \'' . $_POST['user_date_format'] . '\', user_date_offset = \'' . $_POST['user_date_offset'] . '\', user_email = \'' . $_POST['user_email'] . '\', user_language = \'' . $_POST['user_language'] . '\', user_level = \'' . $_POST['user_level'] . '\', user_location = \'' . $_POST['user_location'] . '\', user_name = \'' . $_POST['user_name'] . '\', user_occupation = \'' . $_POST['user_occupation'] . '\', user_template = \'' . $_POST['user_template'] . '\', user_viewemail = \'' . $_POST['user_viewemail'] . '\', user_website = \'' . $_POST['user_website'] . '\'
					WHERE user_id = \'' . $_POST['user_id'] . '\'');
			success_template($lang['ADMIN_USERS_EDITED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
//
	elseif ($_GET['action'] == 'purge_users')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$account_expiry = time() - ACCOUNT_EXPIRY;
			$sql->query('DELETE FROM ' . TABLE_USERS . '
					WHERE user_creation < \'' . $account_expiry . '\' AND user_level = \'0\' AND user_key != \'0\'');
			success_template($lang['ADMIN_USERS_PURGED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'add_polls')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$template->set_file('admin', 'admin/polls/add.tpl');
			$template->set_var(array(
				'ADD' => $lang['ADD'],
				'ADMIN_POLLS_HEADER1' => $lang['ADMIN_POLLS_HEADER1'],
				'ADMIN_POLLS_EXAMPLE' => $lang['ADMIN_POLLS_EXAMPLE'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'FORM_ANSWER_TEXT' => $lang['FORM_ANSWER_TEXT'],
				'FORM_QUESTION_TEXT' => $lang['FORM_QUESTION_TEXT']));
		}
	}
	elseif ($_POST['add_poll'])
	{
		if (!trim($_POST['question_text']))
		{
			$error .= $lang['NO_QUESTION_TEXT'];
		}
		if (!trim($_POST['answer_text']))
		{
			$error .= $lang['NO_ANSWER_TEXT'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$sql->query('INSERT INTO ' . TABLE_QUESTIONS . ' (question_text, question_date)
					VALUES (\'' . $_POST['question_text'] . '\', \'' . time() . '\')');
			$question_id = $sql->insert_id();
			$answer_text = explode("\n", $_POST['answer_text']);
			for ($i = 0; $i < count($answer_text); $i++)
			{
				$sql->query('INSERT INTO ' . TABLE_ANSWERS . ' (question_id, answer_text)
						VALUES (\'' . $question_id . '\', \'' . $answer_text[$i] . '\')');
			}
			success_template($lang['ADMIN_POLLS_ADDED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
	elseif ($_GET['action'] == 'edit_polls')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['page'])
			{
				$_GET['page'] = 1;
			}
			if (!$_GET['list'])
			{
				$_GET['list'] = 0;
			}
			$polls_per_page = POLLS_LIMIT;
			$polls_offset = (($_GET['page'] - 1) * $polls_per_page);
			$sql->query('SELECT question_id
					FROM ' . TABLE_QUESTIONS . '');
			$num_polls = $sql->num_rows();
			$num_pages = ceil($num_polls / $polls_per_page);
			if ($_GET['page'] != 1)
			{
				if ($_GET['page'] > (PAGES_LIMIT * $_GET['list']) + 1)
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . ($_GET['page'] - 1) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . $lang['PREVIOUS_PAGE'] . '">&lt;</a> ';
				}
			}
			if ($_GET['list'] != 0)
			{
				$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . (PAGES_LIMIT * $_GET['list']) . '&amp;list=' . ($_GET['list'] - 1) . '" title="' . (PAGES_LIMIT * $_GET['list']) . '">-</a> ';
			}
			for ($current_page = (PAGES_LIMIT * $_GET['list']) + 1; $current_page <= PAGES_LIMIT * ($_GET['list'] + 1) && $current_page <= $num_pages; $current_page++)
			{
				if ($_GET['page'] == $current_page)
				{
					$pages_list .= $_GET['page'] . ' ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . $current_page . '&amp;list=' . $_GET['list'] . '" title="' . $current_page . '">' . $current_page . '</a> ';
				}
			}
			if (($_GET['list'] + 1) < ($num_pages / PAGES_LIMIT))
			{
				$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . (PAGES_LIMIT * ($_GET['list'] + 1) + 1) . '">+</a> ';
			}
			if (($num_pages > 1) && ($_GET['page'] != $num_pages))
			{
				if ($_GET['page'] < PAGES_LIMIT * ($_GET['list'] + 1))
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . $_GET['list'] . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
				else
				{
					$pages_list .= '<a href="./../admin/index.php?action=view_polls&amp;page=' . ($_GET['page'] + 1) . '&amp;list=' . ($_GET['list'] + 1) . '" title="' . $lang['NEXT_PAGE'] . '">&gt;</a> ';
				}
			}
			$template->set_file('admin', 'admin/polls/view.tpl');
			$template->set_block('admin', 'QUESTIONS_BLOCK', 'questions');
			$sql->query('SELECT question_id, question_text
					FROM ' . TABLE_QUESTIONS . '
					ORDER BY question_date DESC
					LIMIT ' . $polls_offset . ', ' . $polls_per_page . '');
			while ($table_questions = $sql->fetch())
			{
				$template->set_var(array(
					'DELETE' => $lang['DELETE'],
					'EDIT' => $lang['EDIT'],
					'QUESTION_ID' => $table_questions['question_id'],
					'QUESTION_TEXT' => $table_questions['question_text']));
				$template->parse('questions', 'QUESTIONS_BLOCK', true);
			}
			$template->set_var(array(
				'ADMIN_POLLS_PAGES' => sprintf($lang['ADMIN_POLLS_PAGES'], $pages_list),
				'ADMIN_POLLS_HEADER2' => $lang['ADMIN_POLLS_HEADER2'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2']));
		}
	}
	elseif ($_GET['action'] == 'edit_poll')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT question_id, question_text
					FROM ' . TABLE_QUESTIONS . '
					WHERE question_id = \'' . $_GET['question_id'] . '\'');
			$table_questions = $sql->fetch();
			$template->set_file('admin', 'admin/polls/edit.tpl');
			$template->set_block('admin', 'ANSWERS_BLOCK', 'answers');
			$sql->query('SELECT answer_id, answer_text
					FROM ' . TABLE_ANSWERS . '
					WHERE question_id = \'' . $_GET['question_id'] . '\'');
			while ($table_answers = $sql->fetch())
			{
				$template->set_var(array(
					'ANSWER_ID' => $table_answers['answer_id'],
					'ANSWER_TEXT' => $table_answers['answer_text']));
				$template->parse('answers', 'ANSWERS_BLOCK', true);
			}
			$template->set_var(array(
				'ADMIN_POLLS_HEADER3' => $lang['ADMIN_POLLS_HEADER3'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'DELETE' => $lang['DELETE'],
				'EDIT' => $lang['EDIT'],
				'FORM_ANSWER_TEXT' => $lang['FORM_ANSWER_TEXT'],
				'FORM_QUESTION_TEXT' => $lang['FORM_QUESTION_TEXT'],
				'QUESTION_ID' => $table_questions['question_id'],
				'QUESTION_TEXT' => $table_questions['question_text']));
		}
	}
	elseif ($_POST['edit_poll'])
	{
		$answer_id = array_keys($_POST['answer_text']);
		$answer_text = array_values($_POST['answer_text']);
		if ($_POST['delete_id'])
		{
			$delete_id = array_keys($_POST['delete_id']);
		}
		else
		{
			$delete_id = 0;
		}
		if (!trim($_POST['question_text']))
		{
			$error .= $lang['NO_QUESTION_TEXT'];
		}
		for ($i = 0; $i < count($_POST['answer_text']); $i++)
		{
			if (!trim($answer_text[$i]))
			{
				$error .= $lang['NO_ANSWER_TEXT'];
			}
		}
		if (count($delete_id) == count($_POST['answer_text']))
		{
			$error .= $lang['NO_ANSWER_TEXT'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			for ($i = 0; $i < count($_POST['answer_text']); $i++)
			{
				if ($delete_id[$i] == 0)
				{
					$sql->query('UPDATE ' . TABLE_ANSWERS . '
							SET answer_text = \'' . $answer_text[$i] . '\'
							WHERE answer_id = \'' . $answer_id[$i] . '\'');
				}
				else
				{
					$sql->query('DELETE FROM ' . TABLE_ANSWERS . '
							WHERE answer_id = \'' . $delete_id[$i] . '\'');
				}
			}
			$sql->query('UPDATE ' . TABLE_QUESTIONS . '
					SET question_text = \'' . $_POST['question_text'] . '\'
					WHERE question_id = \'' . $_POST['question_id'] . '\'');
			success_template($lang['ADMIN_POLLS_EDITED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
	elseif ($_GET['action'] == 'delete_poll')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('DELETE FROM ' . TABLE_ANSWERS . '
					WHERE question_id = \'' . $_GET['question_id'] . '\'');
			$sql->query('DELETE FROM ' . TABLE_QUESTIONS . '
					WHERE question_id = \'' . $_GET['question_id'] . '\'');
			$sql->query('DELETE FROM ' . TABLE_VOTES . '
					WHERE question_id = \'' . $_GET['question_id'] . '\'');
			success_template($lang['ADMIN_POLLS_DELETED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'edit_comment')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['comment_id'])
			{
				error_template($lang['ADMIN_COMMENTS_ERROR']);
			}
			else
			{
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
				$sql->query('SELECT comment_subject, comment_text, news_id
						FROM ' . TABLE_COMMENTS . '
						WHERE comment_id = \'' . $_GET['comment_id'] . '\'');
				$table_comments = $sql->fetch();
				$sql->query('SELECT smiley_code, smiley_image
						FROM ' . TABLE_SMILIES . '');
				while ($table_smilies = $sql->fetch())
				{
					$table_comments['comment_text'] = str_replace('<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $table_smilies['smiley_code'], $table_comments['comment_text']);
				}
				$table_comments['comment_text'] = undo_bbcode($table_comments['comment_text']);
				$template->set_file('admin', 'admin/comments/edit.tpl');
				$template->set_var(array(
					'ADMIN_COMMENTS_HEADER' => $lang['ADMIN_COMMENTS_HEADER'],
					'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
					'COMMENT_ID' => $_GET['comment_id'],
					'COMMENT_SUBJECT' => $table_comments['comment_subject'],
					'COMMENT_TEXT' => $table_comments['comment_text'],
					'DELETE' => $lang['DELETE'],
					'EDIT' => $lang['EDIT'],
					'FORM_COMMENT_SUBJECT' => $lang['FORM_COMMENT_SUBJECT'],
					'FORM_COMMENT_TEXT' => $lang['FORM_COMMENT_TEXT'],
					'HTML_SUPPORT' => $html_support,
					'NEWS_ID' => $table_comments['news_id'],
					'SMILIES_LIST' => get_smilies_list()));
			}
		}
	}
//
	elseif ($_POST['edit_comment'])
	{
		if (!trim($_POST['comment_subject']))
		{
			$error .= $lang['NO_COMMENT_SUBJECT'];
		}
		if (!trim($_POST['comment_text']))
		{
			$error .= $lang['NO_COMMENT_TEXT'];
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
			$sql->query('SELECT comment_id
					FROM ' . TABLE_COMMENTS . '
					WHERE comment_subject = \'' . $_POST['comment_subject_old'] . '\' AND news_id = \'' . $_POST['news_id'] . '\'');
			$num_subject = $sql->num_rows();
			if ($num_subject != 1)
			{
				$sql->query('UPDATE ' . TABLE_COMMENTS . '
						SET comment_subject = \'' . $comment_subject . '\'
						WHERE comment_subject = \'' . $_POST['comment_subject_old'] . '\'');
				$sql->query('UPDATE ' . TABLE_COMMENTS . '
						SET comment_text = \'' . $comment_text . '\', comment_edition = \'' . time() . '\'
						WHERE comment_id = \'' . $_POST['comment_id'] . '\'');
			}
			else
			{
				$sql->query('UPDATE ' . TABLE_COMMENTS . '
						SET comment_subject = \'' . $comment_subject . '\',comment_text = \'' . $comment_text . '\', comment_edition = \'' . time() . '\'
						WHERE comment_id = \'' . $_POST['comment_id'] . '\'');
			}
			success_template($lang['ADMIN_COMMENTS_EDITED']);
			header('Refresh: 3; URL= ./../comments/index.php?news_id=' . $_POST['news_id'] . '');
		}
	}
//
	elseif ($_POST['delete_comment'])
	{
		$sql->query('SELECT news_id, user_id
				FROM ' . TABLE_COMMENTS . '
				WHERE comment_id = \'' . $_POST['comment_id'] . '\'');
		$table_comments = $sql->fetch();
		$sql->query('UPDATE ' . TABLE_NEWS . '
				SET news_comments = news_comments - 1
				WHERE news_id = \'' . $table_comments['news_id'] . '\'');
		$sql->query('UPDATE ' . TABLE_USERS . '
				SET user_comments = user_comments - 1
				WHERE user_id = \'' . $table_comments['user_id'] . '\'');
		$sql->query('DELETE FROM ' . TABLE_COMMENTS . '
				WHERE comment_id = \'' . $_POST['comment_id'] . '\'');
		success_template($lang['ADMIN_COMMENTS_DELETED']);
		header('Refresh: 3; URL= ./../comments/index.php?news_id=' . $_POST['news_id'] . '');
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'edit_post')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['post_id'])
			{
				error_template($lang['ADMIN_POSTS_ERROR']);
			}
			else
			{
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
				$sql->query('SELECT category_id, post_active, post_subject, post_text, thread_id
						FROM ' . TABLE_POSTS . '
						WHERE post_id = \'' . $_GET['post_id'] . '\'');
				$table_posts = $sql->fetch();
				if ($table_posts['post_active'] == 1)
				{
					$close_no = ' selected="selected"';
					$close_yes = '';
				}
				else
				{
					$close_no = '';
					$close_yes = ' selected="selected"';
				}
				$sql->query('SELECT category_id, category_name
						FROM ' . TABLE_CATEGORIES . '
						WHERE category_level != \'0\'
						ORDER BY category_name');
				while ($table_categories = $sql->fetch())
				{
					if ($table_categories['category_id'] == $table_posts['category_id'])
					{
						$category_id_old = $table_categories['category_id'];
						$category_name_options .= '<option value="' . $table_categories['category_id'] . '" selected="selected">' . $table_categories['category_name'] . '</option>';
					}
					else
					{
						$category_name_options .= '<option value="' . $table_categories['category_id'] . '">' . $table_categories['category_name'] . '</option>';
					}
				}
				$sql->query('SELECT smiley_code, smiley_image
						FROM ' . TABLE_SMILIES . '');
				while ($table_smilies = $sql->fetch())
				{
					$table_posts['post_text'] = str_replace('<img src="' . $table_smilies['smiley_image'] . '" alt="." title="' . $table_smilies['smiley_code'] . '" />', $table_smilies['smiley_code'], $table_posts['post_text']);
				}
				$table_posts['post_text'] = undo_bbcode($table_posts['post_text']);
				$template->set_file('admin', 'admin/posts/edit.tpl');
				$template->set_var(array(
					'ADMIN_POSTS_HEADER' => $lang['ADMIN_POSTS_HEADER'],
					'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
					'CATEGORY_ID' => $table_posts['category_id'],
					'CATEGORY_NAME_OPTIONS' => $category_name_options,
					'CLOSE_NO' => $close_no,
					'CLOSE_YES' => $close_yes,
					'DELETE' => $lang['DELETE'],
					'EDIT' => $lang['EDIT'],
					'FORM_POST_ACTIVE' => $lang['FORM_POST_ACTIVE'],
					'FORM_POST_CATEGORY' => $lang['FORM_POST_CATEGORY'],
					'FORM_POST_SUBJECT' => $lang['FORM_POST_SUBJECT'],
					'FORM_POST_TEXT' => $lang['FORM_POST_TEXT'],
					'HTML_SUPPORT' => $html_support,
					'NO' => $lang['NO'],
					'POST_ID' => $_GET['post_id'],
					'POST_SUBJECT' => $table_posts['post_subject'],
					'POST_TEXT' => $table_posts['post_text'],
					'SMILIES_LIST' => get_smilies_list(),
					'YES' => $lang['YES'],
					'THREAD_ID' => $table_posts['thread_id']));
			}
		}
	}
//
	elseif ($_POST['edit_post'])
	{
		if (!trim($_POST['post_subject']))
		{
			$error .= $lang['NO_POST_SUBJECT'];
		}
		if (!trim($_POST['post_text']))
		{
			$error .= $lang['NO_POST_TEXT'];
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
			$sql->query('SELECT post_id
					FROM ' . TABLE_POSTS . '
					WHERE thread_id = \'' . $_POST['thread_id'] . '\' AND category_id = \'' . $_POST['category_id_old'] . '\'');
			$num_subject = $sql->num_rows();
			if ($_POST['category_id'] != $_POST['category_id_old'])
			{
				$sql->query('UPDATE ' . TABLE_CATEGORIES . '
						SET category_posts = category_posts + ' . $num_subject . '
						WHERE category_id = \'' . $_POST['category_id'] . '\'');
				$sql->query('UPDATE ' . TABLE_CATEGORIES . '
						SET category_posts = category_posts - ' . $num_subject . '
						WHERE category_id = \'' . $_POST['category_id_old'] . '\'');
			}
			if ($num_subject != 1)
			{
				$sql->query('UPDATE ' . TABLE_POSTS . '
						SET category_id = \'' . $_POST['category_id'] . '\', post_active = \'' . $_POST['post_active'] . '\', post_subject = \'' . $post_subject . '\'
						WHERE thread_id = \'' . $_POST['thread_id'] . '\'');
				$sql->query('UPDATE ' . TABLE_POSTS . '
						SET post_text = \'' . $post_text . '\', post_edition = \'' . time() . '\'
						WHERE post_id = \'' . $_POST['post_id'] . '\'');
			}
			else
			{
				$sql->query('UPDATE ' . TABLE_POSTS . '
						SET category_id = \'' . $_POST['category_id'] . '\', post_active = \'' . $_POST['post_active'] . '\', post_subject = \'' . $post_subject . '\',post_text = \'' . $post_text . '\', post_edition = \'' . time() . '\'
						WHERE post_id = \'' . $_POST['post_id'] . '\'');
			}
			success_template($lang['ADMIN_POSTS_EDITED']);
			header('Refresh: 3; URL= ./../posts/read.php?category_id=' . $_POST['category_id'] . '&thread_id=' . $_POST['thread_id'] . '');
		}
	}
//
	elseif ($_POST['delete_post'])
	{
		$sql->query('SELECT category_id, user_id
				FROM ' . TABLE_POSTS . '
				WHERE post_id = \'' . $_POST['post_id'] . '\'');
		$table_posts = $sql->fetch();
		$sql->query('UPDATE ' . TABLE_CATEGORIES . '
				SET category_posts = category_posts - 1
				WHERE category_id = \'' . $table_posts['category_id'] . '\'');
		$sql->query('UPDATE ' . TABLE_USERS . '
				SET user_posts = user_posts - 1
				WHERE user_id = \'' . $table_posts['user_id'] . '\'');
		$sql->query('DELETE FROM ' . TABLE_POSTS . '
				WHERE post_id = \'' . $_POST['post_id'] . '\'');
		success_template($lang['ADMIN_POSTS_DELETED']);
		header('Refresh: 3; URL= ./../posts/index.php?category_id=' . $_POST['category_id'] . '');
	}
// ------------------------------------------------------------------------------------------------
	elseif ($_GET['action'] == 'edit_templates')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$template->set_file('admin', 'admin/templates/view.tpl');
			$template->set_var(array(
				'ADMIN_TEMPLATES_HEADER1' => $lang['ADMIN_TEMPLATES_HEADER1'],
				'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
				'TEMPLATES_LIST' => get_templates_list()));
		}
	}
//
	elseif ($_GET['action'] == 'edit_template')
	{
		if ($table_users['user_level'] < 4)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			if (!$_GET['file'])
			{
				error_template($lang['ADMIN_TEMPLATES_ERROR1']);
			}
			else
			{
				if (file_exists($_GET['file']))
				{
					$fp = fopen($_GET['file'], 'r');
					while (!feof($fp))
					{
						$template_content .= fgets($fp, 4096);
					}
					fclose($fp);
					$template_content = preg_replace('#{([0-9A-Z_]*?)}#', '%\\1%', $template_content);
					$template->set_file('admin', 'admin/templates/edit.tpl');
					$template->set_var(array(
						'ADMIN_TEMPLATES_HEADER2' => $lang['ADMIN_TEMPLATES_HEADER2'],
						'BACK_ADMIN_AREA2' => $lang['BACK_ADMIN_AREA2'],
						'EDIT' => $lang['EDIT'],
						'FILE' => $_GET['file'],
						'FORM_TEMPLATE_CONTENT' => $lang['FORM_TEMPLATE_CONTENT'],
						'TEMPLATE_CONTENT' => htmlspecialchars($template_content)));
				}
				else
				{
					error_template($lang['ADMIN_TEMPLATES_ERROR2']);
				}
			}
		}
	}
//
	elseif ($_POST['edit_template'])
	{
		if (!$_POST['file'])
		{
			error_template($lang['ADMIN_TEMPLATES_ERROR1']);
		}
		elseif (is_writable($_POST['file']))
		{
			$_POST['template_content'] = preg_replace('#%([0-9A-Z_]*?)%#', '{\\1}', $_POST['template_content']);
			$fp = fopen($_POST['file'], 'w');
			flock($fp, LOCK_EX);
			fwrite($fp, stripslashes($_POST['template_content']), strlen($_POST['template_content']));
			flock($fp, LOCK_UN);
			fclose($fp);
			success_template($lang['ADMIN_TEMPLATES_EDITED']);
			header('Refresh: 3; URL= ./../admin/index.php?action=view_advanced_settings');
		}
		else
		{
			error_template(sprintf($lang['ADMIN_TEMPLATES_ERROR3'], $_POST['file']));
		}
	}
// ------------------------------------------------------------------------------------------------
	else
	{
		if ($table_users['user_level'] < 2)
		{
			error_template($lang['ADMIN_LEVEL_ERROR']);
		}
		else
		{
			$sql->query('SELECT news_id
					FROM ' . TABLE_NEWS . '
					WHERE news_active = \'0\'');
			$num_news = $sql->num_rows();
			if ($table_users['user_level'] > 1)
			{
				$news_management .= $lang['ADMIN_AREA1_LINK1'];
				if ($num_news != 0)
				{
					$news_management .= sprintf($lang['ADMIN_AREA1_LINK2'], $num_news);
				}
			}
			if ($table_users['user_level'] > 2)
			{
				$news_management .= $lang['ADMIN_AREA1_LINK3'];
			}
			if ($table_users['user_level'] > 3)
			{
				$advanced_settings .= $lang['ADMIN_AREA1_LINK4'];
			}
			else
			{
				$advanced_settings .= $lang['ADMIN_AREA2_ERROR'];
			}
			$template->set_file('admin', 'admin/area1.tpl');
			$template->set_var(array(
				'ADMIN_AREA1_HEADER' => $lang['ADMIN_AREA1_HEADER'],
				'ADMIN_IMG_AREA2' => $lang['ADMIN_IMG_AREA2'],
				'ADMIN_IMG_NEWS' => $lang['ADMIN_IMG_NEWS'],
				'ADVANCED_SETTINGS' => $advanced_settings,
				'BACK_HOME' => $lang['BACK_HOME'],
				'NEWS_MANAGEMENT' => $news_management));
		}
	}
}

page_header($lang['ADMIN_INDEX_TITLE']);
$template->pparse('', 'admin');
$template->pparse('', 'error');
$template->pparse('', 'success');
page_footer();

?>