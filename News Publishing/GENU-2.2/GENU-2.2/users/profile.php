<?php
// -------------------------------------------------------------
//
// $Id: profile.php,v 1.11 2005/05/08 08:55:06 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

if ($_SESSION['user_id'])
{
	if ($_POST['profile'])
	{
		// nothing is done if only user_password2 field is provided
		if ($_POST['user_password'])
		{
			if (strlen(trim($_POST['user_password'])) < MIN_PASS_LENGHT)
			{
				$error .= sprintf($lang['SHORT_USER_PASSWORD'], MIN_PASS_LENGHT);
			}
			if (trim($_POST['user_password2']) != trim($_POST['user_password']))
			{
				$error .= $lang['INVALID_USER_PASSWORD'];
			}
			if (trim($_POST['user_password2']) == trim($_POST['user_password']))
			{
				$new_password = 'true';
			}
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
			if ($new_password == 'true')
			{
				$sql->query('UPDATE ' . TABLE_USERS . '
				SET user_password = \'' . md5($_POST['user_password']) . '\'
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
			}
			if (!trim($_POST['user_date_offset']))
			{
				$_POST['user_date_offset'] = 0;
			}
			$sql->query('UPDATE ' . TABLE_USERS . '
					SET user_email = \'' . $user_email . '\', user_viewemail = \'' . $_POST['user_viewemail'] . '\', user_website = \'' . $_POST['user_website'] . '\', user_location = \'' . $_POST['user_location'] . '\', user_occupation = \'' . $_POST['user_occupation'] . '\', user_age = \'' . $_POST['user_age'] . '\', user_ip = \'' . $_SERVER['REMOTE_ADDR'] . '\', user_language = \'' . $_POST['user_language'] . '\', user_template = \'' . $_POST['user_template'] . '\', user_date_format = \'' . $_POST['user_date_format'] . '\', user_date_offset = \'' . $_POST['user_date_offset'] . '\'
					WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
			set_user_cookies();
			success_template($lang['USERS_PROFILE_SUCCESS']);
		}
	}
	else
	{
		$sql->query('SELECT language, language_unique, template, template_unique
				FROM ' . TABLE_SETTINGS . '');
		$table_settings = $sql->fetch();
		$sql->query('SELECT user_name, user_email, user_viewemail, user_website, user_location, user_occupation, user_age, user_language, user_template, user_date_format, user_date_offset
				FROM ' . TABLE_USERS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
		$table_users = $sql->fetch();
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
		$template->set_file('profile', 'users/profile.tpl');
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'EDIT' => $lang['EDIT'],
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
			'NO_SELECTED' => $no_selected,
			'TEMPLATE_OPTIONS' => $template_options,
			'USER_AGE' => $table_users['user_age'],
			'USER_DATE_FORMAT' => $table_users['user_date_format'],
			'USER_DATE_OFFSET' => $table_users['user_date_offset'],
			'USER_EMAIL' => $table_users['user_email'],
			'USER_LOCATION' => $table_users['user_location'],
			'USER_NAME' => $table_users['user_name'],
			'USER_OCCUPATION' => $table_users['user_occupation'],
			'USER_VIEWEMAIL' => $table_users['user_viwemail'],
			'USER_WEBSITE' => $table_users['user_website'],
			'USERS_PROFILE_HEADER' => $lang['USERS_PROFILE_HEADER'],
			'YES' => $lang['YES'],
			'YES_SELECTED' => $yes_selected));
	}
}
else
{
	error_template($lang['USERS_PROFILE_ERROR']);
}

page_header($lang['USERS_PROFILE_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'profile');
$template->pparse('', 'success');
page_footer();

?>