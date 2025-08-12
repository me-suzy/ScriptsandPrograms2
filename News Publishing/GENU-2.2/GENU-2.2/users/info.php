<?php
// -------------------------------------------------------------
//
// $Id: info.php,v 1.4 2005/03/13 13:37:07 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_GET['user_id'])
{
	$sql->query('SELECT user_age, user_comments, user_creation, user_email, user_id, user_language, user_level, user_location, user_name, user_occupation, user_posts, user_viewemail, user_website
			FROM ' . TABLE_USERS . '
			WHERE user_id = \'' . $_GET['user_id'] . '\'');
	$table_users = $sql->fetch();
	if (!$table_users['user_id'])
	{
		error_template($lang['USERS_INFO_ERROR1']);
	}
	else
	{
		if ($table_users['user_viewemail'] == 0)
		{
			$user_email = '';
		}
		else
		{
			$user_email = $table_users['user_email'];
		}
		if ((substr($table_users['user_website'], 0, 7) != 'http://') && ($table_users['user_website'] != ''))
		{
			$user_website = 'http://' . $table_users['user_website'];
		}
		else
		{
			$user_website = $table_users['user_website'];
		}
		$date_format = get_date_format();
		$date_offset = get_date_offset();
		$user_creation = date($date_format, ($table_users['user_creation'] + $date_offset));
		$level_list = array(
			'0' => $lang['LEVEL_BANNED'],
			'1' => $lang['LEVEL_PUBLIC'],
			'2' => $lang['LEVEL_MODERATOR1'],
			'3' => $lang['LEVEL_MODERATOR2'],
			'4' => $lang['LEVEL_ADMIN']);
		$user_level = $level_list[$table_users['user_level']];
		$template->set_file('info', 'users/info.tpl');
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'FORM_USER_AGE' => $lang['FORM_USER_AGE'],
			'FORM_USER_COMMENTS' => $lang['FORM_USER_COMMENTS'],
			'FORM_USER_CREATION' => $lang['FORM_USER_CREATION'],
			'FORM_USER_EMAIL' => $lang['FORM_USER_EMAIL'],
			'FORM_USER_LANGUAGE' => $lang['FORM_USER_LANGUAGE'],
			'FORM_USER_LEVEL' => $lang['FORM_USER_LEVEL'],
			'FORM_USER_LOCATION' => $lang['FORM_USER_LOCATION'],
			'FORM_USER_OCCUPATION' => $lang['FORM_USER_OCCUPATION'],
			'FORM_USER_POSTS' => $lang['FORM_USER_POSTS'],
			'FORM_USER_WEBSITE' => $lang['FORM_USER_WEBSITE'],
			'USER_AGE' => $table_users['user_age'],
			'USER_COMMENTS' => $table_users['user_comments'],
			'USER_CREATION' => $user_creation,
			'USER_EMAIL' => $user_email,
			'USER_LANGUAGE' => $table_users['user_language'],
			'USER_LEVEL' => $user_level,
			'USER_LOCATION' => $table_users['user_location'],
			'USER_OCCUPATION' => $table_users['user_occupation'],
			'USER_POSTS' => $table_users['user_posts'],
			'USER_WEBSITE' => $user_website,
			'USERS_INFO_HEADER' => sprintf($lang['USERS_INFO_HEADER'], $table_users['user_name'])));
	}
}
else
{
	error_template($lang['USERS_INFO_ERROR2']);
}

page_header($lang['USERS_INFO_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'info');
page_footer();

?>