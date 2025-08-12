<?php
// -------------------------------------------------------------
//
// $Id: password.php,v 1.6 2005/05/05 13:32:31 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_POST['password'])
{
	$sql->query('SELECT user_name, user_email
			FROM ' . TABLE_USERS . '
			WHERE user_name = \'' . $_POST['user_name'] . '\' OR user_email = \'' . $_POST['user_email'] . '\'');
	$table_users = $sql->fetch();
	if (!trim($_POST['user_name']) && !trim($_POST['user_email']))
	{
		$error .= $lang['USERS_PASSWORD_ERROR'];
	}
	if ($_POST['user_name'])
	{
		if (!$table_users['user_name'])
		{
			$error .= $lang['INVALID_USER_NAME'];
		}
	}
	if ($_POST['user_email'])
	{
		if (!$table_users['user_email'])
		{
			$error .= $lang['INVALID_USER_EMAIL'];
		}
	}
	if ($error)
	{
		error_template($error);
	}
	else
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		srand((double)microtime() * 1000000);
		for ($i = 0; $i < 8; $i++)
		{
			$new_password .= $chars{rand()%strlen($chars)};
		}
		$sql->query('SELECT sitename, siteurl, sender_email, sender_name
				FROM ' . TABLE_SETTINGS . '');
		$table_settings = $sql->fetch();
		$subject = sprintf($lang['USERS_PASSWORD_SUBJECT'], $table_settings['sitename']);
		$message = sprintf($lang['USERS_PASSWORD_MESSAGE'], $new_password, $table_settings['sender_name'], $table_settings['siteurl']);
		$header .= 'From: ' . $table_settings['sender_name'] . ' <' . $table_settings['sender_email'] . '>' . "\n";
		$header .= 'Reply-To: ' . $table_settings['sender_name'] . ' <' . $table_settings['sender_email'] . '>' . "\n";
		$header .= 'X-Mailer: PHP/' . phpversion() . "\n";
		$header .= 'MIME-Version: 1.0' . "\n";
		$header .= 'Content-type: text/plain; charset=iso-8859-1' . "\n";
		mail($table_users['user_email'], $subject, $message, $header);
		$sql->query('UPDATE ' . TABLE_USERS . '
				SET user_password = \'' . md5($new_password) . '\'
				WHERE user_name = \'' . $_POST['user_name'] . '\' OR user_email = \'' . $_POST['user_email'] . '\'');
		success_template($lang['USERS_PASSWORD_SUCCESS']);
	}
}
else
{
	$template->set_file('password', 'users/password.tpl');
	$template->set_var(array(
		'BACK_HOME' => $lang['BACK_HOME'],
		'FORM_USER_EMAIL' => $lang['FORM_USER_EMAIL'],
		'FORM_USER_NAME' => $lang['FORM_USER_NAME'],
		'SEND' => $lang['SEND'],
		'USERS_PASSWORD_HEADER' => $lang['USERS_PASSWORD_HEADER']));
}

page_header($lang['USERS_PASSWORD_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'password');
$template->pparse('', 'success');
page_footer();

?>
