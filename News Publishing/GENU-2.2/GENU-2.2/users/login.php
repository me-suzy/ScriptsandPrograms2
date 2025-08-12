<?php
// -------------------------------------------------------------
//
// $Id: login.php,v 1.6 2005/04/07 17:49:47 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if (!$_SESSION['user_id'])
{
	if ($_POST['login'])
	{
		$sql->query('SELECT user_id, user_key, user_level, user_name FROM ' . TABLE_USERS . '
				WHERE user_name = \'' . $_POST['user_name'] . '\' AND user_password = \'' . md5($_POST['user_password']) . '\'');
		$table_users = $sql->fetch();
		if (!$table_users['user_name'])
		{
			error_template($lang['USERS_LOGIN_ERROR1']);
		}
		elseif ($table_users['user_key'] != 0)
		{
			error_template($lang['USERS_LOGIN_ERROR2']);
		}
		elseif ($table_users['user_level'] == 0)
		{
			error_template($lang['USERS_LOGIN_ERROR2']);
		}
		else
		{
			$sql->query('UPDATE ' . TABLE_USERS . ' SET user_ip = \'' . $_SERVER['REMOTE_ADDR'] . '\'
					WHERE user_id = \'' . $table_users['user_id'] . '\'');
			$_SESSION['user_id'] = $table_users['user_id'];
			set_user_cookies();
			success_template($lang['USERS_LOGIN_SUCCESS']);
			header('Refresh: 3; URL= ./../index.php');
		}
	}
	else
	{
		$template->set_file('login', 'users/login.tpl');
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'FORM_USER_NAME' => $lang['FORM_USER_NAME'],
			'FORM_USER_PASSWORD' => $lang['FORM_USER_PASSWORD'],
			'LOGIN' => $lang['LOGIN'],
			'USERS_LOGIN_HEADER' => $lang['USERS_LOGIN_HEADER']));
	}
}
else
{
	error_template($lang['USERS_LOGIN_ERROR3']);
	header('Refresh: 3; URL= ./../index.php');
}

page_header($lang['USERS_LOGIN_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'login');
$template->pparse('', 'success');
page_footer();

?>