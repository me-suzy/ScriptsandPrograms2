<?php
// -------------------------------------------------------------
//
// $Id: register.php,v 1.14 2005/05/08 08:55:06 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT date_format, date_offset, language, language_unique, register_users, template, template_unique, sitename, siteurl, sender_email, sender_name
		FROM ' . TABLE_SETTINGS . '');
$table_settings = $sql->fetch();
if ($table_settings['register_users'] == 0)
{
	error_template($lang['USERS_REGISTER_DISABLED']);
}
elseif ($_SESSION['user_id'])
{
	error_template($lang['USERS_REGISTER_ERROR1']);
}
else
{
	if ($_GET['user_key'])
	{
		$sql->query('SELECT user_id
				FROM ' . TABLE_USERS . '
				WHERE user_key = \'' . $_GET['user_key'] . '\' AND user_key != \'0\'');
		$table_users = $sql->fetch();
		if (!$table_users['user_id'])
		{
			error_template($lang['USERS_REGISTER_ERROR2']);
		}
		else
		{
			$sql->query('UPDATE ' . TABLE_USERS . ' SET user_level = \'1\', user_key = \'0\'
					WHERE user_key = \'' . $_GET['user_key'] . '\'');
			success_template($lang['USERS_REGISTER_SUCCESS1']);
		}
	}
	elseif ($_POST['register'])
	{
		$sql->query('SELECT user_email, user_name
				FROM ' . TABLE_USERS . '
				WHERE user_name = \'' . $_POST['user_name'] . '\' OR user_email = \'' . $_POST['user_email'] . '\'');
		$table_users = $sql->fetch();
		if ($table_users['user_name'] == $_POST['user_name'])
		{
			$error .= $lang['USERS_REGISTER_ERROR3'];
		}
		if ($table_users['user_email'] == $_POST['user_email'])
		{
			$error .= $lang['USERS_REGISTER_ERROR4'];
		}
		if (!trim($_POST['user_name']))
		{
			$error .= $lang['NO_USER_NAME'];
		}
		if ($_POST['user_name'])
		{
			if (strlen(trim($_POST['user_name'])) < MIN_NAME_LENGHT)
			{
				$error .= sprintf($lang['SHORT_USER_NAME'], MIN_NAME_LENGHT);
			}
		}
		if (!trim($_POST['user_password']))
		{
			$error .= $lang['NO_USER_PASSWORD'];
		}
		if ($_POST['user_password'])
		{
			if (strlen(trim($_POST['user_password'])) < MIN_PASS_LENGHT)
			{
				$error .= sprintf($lang['SHORT_USER_PASSWORD'], MIN_PASS_LENGHT);
			}
		}
		if (trim($_POST['user_password2']) != trim($_POST['user_password']))
		{
			$error .= $lang['INVALID_USER_PASSWORD'];
		}
		if (!trim($_POST['user_email']))
		{
			$error .= $lang['NO_USER_EMAIL'];
		}
		if (check_email($_POST['user_email']))
		{
			$user_email = $_POST['user_email'];
		}
		else
		{
			$error .= $lang['INVALID_USER_EMAIL'];
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
			$user_key = md5(time() . $_POST['user_name']);
			$sql->query('INSERT INTO ' . TABLE_USERS . ' (user_level, user_name, user_password, user_email, user_viewemail, user_website, user_location, user_occupation, user_age, user_creation, user_ip, user_language, user_template, user_date_format, user_date_offset, user_key)
					VALUES (\'0\', \'' . $_POST['user_name'] . '\', \'' . md5($_POST['user_password']) . '\', \'' . $user_email . '\', \'' . $_POST['user_viewemail'] . '\', \'' . $_POST['user_website'] . '\', \'' . $_POST['user_location'] . '\', \'' . $_POST['user_occupation'] . '\', \'' . $_POST['user_age'] . '\', \'' . time() . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\', \'' . $_POST['user_language'] . '\', \'' . $_POST['user_template'] . '\', \'' . $_POST['user_date_format'] . '\', \'' . $_POST['user_date_offset'] . '\', \'' . $user_key . '\')');
			$subject = sprintf($lang['USERS_REGISTER_SUBJECT'], $table_settings['sitename']);
			$message = sprintf($lang['USERS_REGISTER_MESSAGE'], $_POST['user_name'], $table_settings['sitename'], $table_settings['siteurl'] . '/users/register.php?user_key=' . $user_key, $table_settings['sender_name']);
			$header .= 'From: ' . $table_settings['sender_name'] . ' <' . $table_settings['sender_email'] . '>' . "\n";
			$header .= 'Reply-To: ' . $table_settings['sender_name'] . ' <' . $table_settings['sender_email'] . '>' . "\n";
			$header .= 'X-Mailer: PHP/' . phpversion() . "\n";
			$header .= 'MIME-Version: 1.0' . "\n";
			$header .= 'Content-type: text/plain; charset=iso-8859-1' . "\n";
			mail($user_email, $subject, $message, $header);
			success_template($lang['USERS_REGISTER_SUCCESS2']);
		}
	}
	else
	{
		if ($table_settings['language_unique'] == 1)
		{
			$language_options = '<option value="' . $table_settings['language'] . '">' . $lang['' . strtoupper($table_settings['language']) . ''] . '</option>';
		}
		else
		{
			$language_options = '<option value="dutch">' . $lang['DUTCH'] . '</option><option value="english">' . $lang['ENGLISH'] . '</option><option value="french">' . $lang['FRENCH'] . '</option><option value="german">' . $lang['GERMAN'] . '</option><option value="italian">' . $lang['ITALIAN'] . '</option><option value="polish">' . $lang['POLISH'] . '</option><option value="spanish">' . $lang['SPANISH'] . '</option>';
		}
		if ($table_settings['template_unique'] == 1)
		{
			$template_options = '<option value="' . $table_settings['template'] . '">' . $table_settings['template'] . '</option>';
		}
		else
		{
			$template_options = '<option value="default">default</option><option value="original">original</option>';
		}
		$template->set_file('register', 'users/register.tpl');
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'DATE_FORMAT' => $table_settings['date_format'],
			'DATE_OFFSET' => $table_settings['date_offset'],
			'FORM_USER_AGE' => $lang['FORM_USER_AGE'],
			'FORM_USER_DATE_FORMAT' => $lang['FORM_USER_DATE_FORMAT'],
			'FORM_USER_DATE_OFFSET' => $lang['FORM_USER_DATE_OFFSET'],
			'FORM_USER_EMAIL' => $lang['FORM_USER_EMAIL'],
			'FORM_USER_LANGUAGE' => $lang['FORM_USER_LANGUAGE'],
			'FORM_USER_LOCATION' => $lang['FORM_USER_LOCATION'],
			'FORM_USER_NAME' => $lang['FORM_USER_NAME'],
			'FORM_USER_OCCUPATION' => $lang['FORM_USER_OCCUPATION'],
			'FORM_USER_PASSWORD' => $lang['FORM_USER_PASSWORD'],
			'FORM_USER_PASSWORD2' => $lang['FORM_USER_PASSWORD2'],
			'FORM_USER_TEMPLATE' => $lang['FORM_USER_TEMPLATE'],
			'FORM_USER_VIEWEMAIL' => $lang['FORM_USER_VIEWEMAIL'],
			'FORM_USER_WEBSITE' => $lang['FORM_USER_WEBSITE'],
			'LANGUAGE_OPTIONS' => $language_options,
			'NO' => $lang['NO'],
			'REGISTER' => $lang['REGISTER'],
			'TEMPLATE_OPTIONS' => $template_options,
			'USERS_REGISTER_HEADER' => $lang['USERS_REGISTER_HEADER'],
			'YES' => $lang['YES']));
	}
}

page_header($lang['USERS_REGISTER_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'register');
$template->pparse('', 'success');
page_footer();

?>