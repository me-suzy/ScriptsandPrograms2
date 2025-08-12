<?php
// -------------------------------------------------------------
//
// $Id: send.php,v 1.8 2005/05/08 08:55:06 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

include('./../includes/common.php');

$sql->query('SELECT sitename, siteurl, send_news, sender_email, sender_name
		FROM ' . TABLE_SETTINGS . '');
$table_settings = $sql->fetch();
$sql->query('SELECT news_id
		FROM ' . TABLE_NEWS . '
		WHERE news_id = \'' . $_REQUEST['news_id'] . '\' AND news_active = \'1\'');
$table_news = $sql->fetch();
if (!$_REQUEST['news_id'] || !$table_news['news_id'])
{
	error_template($lang['NEWS_SEND_ERROR']);
}
else
{
	if ($table_settings['send_news'] == 0)
	{
		error_template($lang['NEWS_SEND_DISABLED']);
	}
	elseif ($_POST['send'])
	{
		$sql->query('SELECT news_subject, news_text
				FROM ' . TABLE_NEWS . '
				WHERE news_id = \'' . $_POST['news_id'] . '\'');
		$table_news = $sql->fetch();
		$subject = sprintf($lang['NEWS_SEND_SUBJECT'], $table_settings['sitename']);
		if (!trim($_POST['user_name']))
		{
			$error .= $lang['NO_USER_NAME'];
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
		if (!trim($_POST['friend_email']))
		{
			$error .= $lang['NO_FRIEND_EMAIL'];
		}
		if (check_email($_POST['friend_email']))
		{
			$friend_email = $_POST['friend_email'];
		}
		else
		{
			$error .= $lang['INVALID_USER_EMAIL'];
		}
		if ($error)
		{
			error_template($error);
		}
		else
		{
			$header .= 'From: ' . $table_settings['sender_name'] . ' <' . $table_settings['sender_email'] . '>' . "\n";
			$header .= 'Reply-To: ' . $_POST['user_name'] . ' <' . $user_email . '>' . "\n";
			$header .= 'X-Mailer: PHP/' . phpversion() . "\n";
			$header .= 'MIME-Version: 1.0' . "\n";
			if ($_POST['html_email'] == 0)
			{
				$header .= 'Content-Type: text/plain; charset=iso-8859-1' . "\n";
				$table_news['news_text'] = str_replace('./..', $table_settings['siteurl'], $table_news['news_text']);
				$message = sprintf($lang['NEWS_SEND_PLAIN'], $_POST['user_name'], $table_news['news_subject'], $table_news['news_text'], $table_settings['siteurl']);
			}
			else
			{
				$header .= 'Content-Type: text/html; charset=iso-8859-1' . "\n";
				$table_news['news_text'] = str_replace("\n", '<br />', $table_news['news_text']);
				$table_news['news_text'] = str_replace("\r", '', $table_news['news_text']);
				$table_news['news_text'] = str_replace('./..', $table_settings['siteurl'], $table_news['news_text']);
				$message = sprintf($lang['NEWS_SEND_HTML'], $_POST['user_name'], $table_news['news_subject'], $table_news['news_text'], $table_settings['siteurl'], $table_settings['sitename']);
			}
			mail($friend_email, $subject, $message, $header);
			success_template($lang['NEWS_SEND_SUCCESS']);
		}
	}
	else
	{
		$sql->query('SELECT user_name, user_email
				FROM ' . TABLE_USERS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
		$table_users = $sql->fetch();
		$template->set_file('send', 'news/send.tpl');
		$template->set_var(array(
			'BACK_HOME' => $lang['BACK_HOME'],
			'FORM_FRIEND_EMAIL' => $lang['FORM_FRIEND_EMAIL'],
			'FORM_HTML_EMAIL' => $lang['FORM_HTML_EMAIL'],
			'FORM_USER_EMAIL' => $lang['FORM_USER_EMAIL'],
			'FORM_USER_NAME' => $lang['FORM_USER_NAME'],
			'NEWS_ID' => $_GET['news_id'],
			'NEWS_SEND_HEADER' => $lang['NEWS_SEND_HEADER'],
			'SEND' => $lang['SEND'],
			'USER_EMAIL' => $table_users['user_email'],
			'USER_NAME' => $table_users['user_name']));
	}
}

page_header($lang['NEWS_SEND_TITLE']);
$template->pparse('', 'error');
$template->pparse('', 'send');
$template->pparse('', 'success');
page_footer();

?>
