<?php
// -------------------------------------------------------------
//
// $Id: upgrade.php,v 1.5 2005/05/05 13:25:57 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

error_reporting(E_ALL ^ E_NOTICE);
// error_reporting(E_ALL);

include('./../includes/config.php');
switch (SQL_TYPE)
{
	case 'mysql' :
		include('./../includes/mysql.php');
		$sql = new mysql;
		break;
	case 'pgsql' :
		include('./../includes/pgsql.php');
		$sql = new pgsql;
		break;
	case 'sqlite' :
		include('./../includes/sqlite.php');
		$sql = new sqlite;
		break;
}
include('./../includes/function.php');

set_magic_quotes_runtime(0);
if (get_magic_quotes_gpc())
{
	$_GET = strip_input_data($_GET);
	$_POST = strip_input_data($_POST);
	$_COOKIE = strip_input_data($_COOKIE);
}
$_GET = slash_input_data($_GET);
$_POST = slash_input_data($_POST);
$_COOKIE = slash_input_data($_COOKIE);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>GENU - Installation</title>
<link href="./../templates/original/style.css" type="text/css" rel="stylesheet" />
<meta http-equiv="content-language" content="de, en, es, fr, it, nl" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<div class="pageMain">
	<div class="pageLeft">
		<div class="leftLogo"><img src="./../images/logo.png" alt="." title="GENU" /></div>
		<div class="leftTitle">Links</div>
		<div class="leftContent">&middot; Home<br />&middot; Browse news<br />&middot; Submit news<br />&middot; Forum<br />&middot; Polls<br />&middot; Administration</div>
		<div class="leftTitle">Users</div>
		<div class="leftContent">&middot; Log in<br />&middot; Register<br />&middot; Forgot password?</div>
		<div class="leftTitle">Search</div>
		<div class="leftContent" style="text-align: center"><form method="get" action=""><div><input type="text" name="search" size="16" maxlength="64" /><br /><select name="match"><option value="news_subject">in subject</option><option value="news_text">in text</option></select><br /><input type="submit" value="Search" /></div></form></div>
		<div class="leftTitle">Online user(s)</div>
		<div class="leftContent" style="text-align: center">Currently, there is/are 1 user(s) online.</div>
	</div>
	<div class="pageRight">';

if ($_POST['version'] == 1)
{
	/* genu_categories */
	$sql->query('ALTER TABLE genu_categories ADD category_news INT(10) UNSIGNED NOT NULL DEFAULT \'0\' AFTER category_image');
	$sql->query('ALTER TABLE genu_categories ADD category_posts INT(10) UNSIGNED NOT NULL DEFAULT \'0\' AFTER category_news');
	$sql->query('ALTER TABLE genu_categories ADD category_level ENUM(\'0\',\'1\',\'2\') NOT NULL DEFAULT \'0\' AFTER category_posts');
	echo '
		<div class="rightMain">
			<div class="rightContent">Table <span style="font-weight: bold">genu_categories</span> updated successfully.<br />';
	/* genu_comments */
	$sql->query('ALTER TABLE genu_comments ADD reply_id INT(10) UNSIGNED NOT NULL DEFAULT \'0\' AFTER comment_id');
	$reply_id = array();
	$sql->query('SELECT comment_id, comment_subject
			FROM genu_comments
			ORDER BY comment_subject, comment_creation');
	while($table_comments = $sql->fetch())
	{
		if ($table_comments['comment_subject'] == $previous_subject)
		{
			$reply_id[$table_comments['comment_id']] = $previous_id;
		}
		else
		{
			$reply_id[$table_comments['comment_id']] = $table_comments['comment_id'];
		}
		$previous_id = $table_comments['comment_id'];
		$previous_subject = $table_comments['comment_subject'];
	}
	for ($i = 0; $i <= count($reply_id); $i++)
	{
		$sql->query('UPDATE genu_comments SET reply_id = \'' . $reply_id[$i] . '\' WHERE comment_id = \'' . $i . '\'');
	}
	echo 'Table <span style="font-weight: bold">genu_comments</span> updated successfully.<br />';
	/* genu_settings */
	$sql->query('ALTER TABLE genu_settings CHANGE language language ENUM(\'dutch\',\'english\',\'french\',\'german\',\'italian\',\'polish\',\'spanish\') DEFAULT \'english\' NOT NULL');
	$sql->query('ALTER TABLE genu_settings ADD template ENUM(\'default\',\'original\') NOT NULL DEFAULT \'default\' AFTER language');
	$sql->query('ALTER TABLE genu_settings ADD language_unique ENUM(\'0\',\'1\') DEFAULT \'0\' NOT NULL AFTER language');
	$sql->query('ALTER TABLE genu_settings ADD template_unique ENUM(\'0\',\'1\') DEFAULT \'0\' NOT NULL AFTER template');
	$sql->query('ALTER TABLE genu_settings ADD threads_per_page TINYINT(2) UNSIGNED NOT NULL DEFAULT \'20\' AFTER headlines_per_backend');
	$sql->query('ALTER TABLE genu_settings ADD posts_per_page TINYINT(2) UNSIGNED NOT NULL DEFAULT \'20\' AFTER threads_per_page');
	$sql->query('ALTER TABLE genu_settings ADD sender_email VARCHAR(64) NOT NULL DEFAULT \'\' AFTER register_users');
	$sql->query('ALTER TABLE genu_settings ADD sender_name VARCHAR(16) NOT NULL DEFAULT \'\' AFTER sender_email');
	$sql->query('SELECT user_email, user_name
			FROM genu_users
			WHERE user_id = \'1\'');
	$table_users = $sql->fetch();
	$sql->query('UPDATE genu_settings SET sender_email = \'' . $table_users['user_email'] . '\', sender_name = \'' . $table_users['user_name'] . '\'');
	echo 'Table <span style="font-weight: bold">genu_settings</span> updated successfully.<br />';
	/* genu_users */
	$sql->query('ALTER TABLE genu_users ADD user_posts SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\' AFTER user_comments');
	$sql->query('ALTER TABLE genu_users CHANGE user_language user_language ENUM(\'dutch\',\'english\',\'french\',\'german\',\'italian\',\'polish\',\'spanish\') DEFAULT \'english\' NOT NULL');
	$sql->query('ALTER TABLE genu_users ADD user_template ENUM(\'default\',\'original\') NOT NULL DEFAULT \'default\' AFTER user_language');
	echo 'Table <span style="font-weight: bold">genu_users</span> updated successfully.<br />';
	/* genu_answers */
	$sql->query('CREATE TABLE genu_answers (answer_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT, question_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', answer_text VARCHAR(64) NOT NULL DEFAULT \'\', answer_votes SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', PRIMARY KEY (answer_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_answers</span> created successfully.<br />';
	/* genu_posts */
	$sql->query('CREATE TABLE genu_posts (post_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, thread_id INT(10) UNSIGNED NOT NULL DEFAULT \'0\', category_id TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\', user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', post_subject VARCHAR(64) NOT NULL DEFAULT \'\', post_text TEXT NOT NULL, post_creation INT(11) NOT NULL DEFAULT \'0\', post_edition INT(11) NOT NULL DEFAULT \'0\', post_active ENUM(\'0\',\'1\') NOT NULL DEFAULT \'0\', PRIMARY KEY (post_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_posts</span> created successfully.<br />';
	/* genu_questions */
	$sql->query('CREATE TABLE genu_questions (question_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT, question_text VARCHAR(255) NOT NULL DEFAULT \'\', question_votes SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', question_date INT(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (question_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_questions</span> created successfully.<br />';
	/* genu_votes */
	$sql->query('CREATE TABLE genu_votes (vote_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, question_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', user_ip VARCHAR(15) NOT NULL DEFAULT \'\', vote_date INT(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (vote_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_votes</span> created successfully.<br /><br />Please remove <span style="font-weight: bold">install</span> folder from your server.</div>
		</div>';
}
elseif ($_POST['version'] == 2)
{
	/* genu_settings */
	$sql->query('ALTER TABLE genu_settings CHANGE language language ENUM(\'dutch\',\'english\',\'french\',\'german\',\'italian\',\'polish\',\'spanish\') DEFAULT \'english\' NOT NULL');
	$sql->query('ALTER TABLE genu_settings ADD language_unique ENUM(\'0\',\'1\') DEFAULT \'0\' NOT NULL AFTER language');
	$sql->query('ALTER TABLE genu_settings ADD template_unique ENUM(\'0\',\'1\') DEFAULT \'0\' NOT NULL AFTER template');
	$sql->query('ALTER TABLE genu_settings ADD sender_email VARCHAR(64) NOT NULL DEFAULT \'\' AFTER register_users');
	$sql->query('ALTER TABLE genu_settings ADD sender_name VARCHAR(16) NOT NULL DEFAULT \'\' AFTER sender_email');
	$sql->query('SELECT user_email, user_name
			FROM genu_users
			WHERE user_id = \'1\'');
	$table_users = $sql->fetch();
	$sql->query('UPDATE genu_settings SET sender_email = \'' . $table_users['user_email'] . '\', sender_name = \'' . $table_users['user_name'] . '\'');
	echo '
		<div class="rightMain">
			<div class="rightContent">Table <span style="font-weight: bold">genu_settings</span> updated successfully.<br />';
	/* genu_users */
	$sql->query('ALTER TABLE genu_users CHANGE user_language user_language ENUM(\'dutch\',\'english\',\'french\',\'german\',\'italian\',\'polish\',\'spanish\') DEFAULT \'english\' NOT NULL');
	echo 'Table <span style="font-weight: bold">genu_users</span> updated successfully.<br />';
	/* genu_answers */
	$sql->query('CREATE TABLE genu_answers (answer_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT, question_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', answer_text VARCHAR(64) NOT NULL DEFAULT \'\', answer_votes SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', PRIMARY KEY (answer_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_answers</span> created successfully.<br />';
	/* genu_questions */
	$sql->query('CREATE TABLE genu_questions (question_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT, question_text VARCHAR(255) NOT NULL DEFAULT \'\', question_votes SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', question_date INT(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (question_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_questions</span> created successfully.<br />';
	/* genu_votes */
	$sql->query('CREATE TABLE genu_votes (vote_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, question_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\', user_ip VARCHAR(15) NOT NULL DEFAULT \'\', vote_date INT(11) NOT NULL DEFAULT \'0\', PRIMARY KEY (vote_id)) TYPE=MyISAM');
	echo 'Table <span style="font-weight: bold">genu_votes</span> created successfully.<br /><br />Please remove <span style="font-weight: bold">install</span> folder from your server.</div>
		</div>';
}
elseif ($_POST['version'] == 3)
{
	/* genu_settings */
	$sql->query('ALTER TABLE genu_settings CHANGE language language ENUM(\'dutch\',\'english\',\'french\',\'german\',\'italian\',\'polish\',\'spanish\') DEFAULT \'english\' NOT NULL');
	$sql->query('ALTER TABLE genu_settings ADD language_unique ENUM(\'0\',\'1\') DEFAULT \'0\' NOT NULL AFTER language');
	$sql->query('ALTER TABLE genu_settings ADD template_unique ENUM(\'0\',\'1\') DEFAULT \'0\' NOT NULL AFTER template');
	$sql->query('ALTER TABLE genu_settings ADD sender_email VARCHAR(64) NOT NULL DEFAULT \'\' AFTER register_users');
	$sql->query('ALTER TABLE genu_settings ADD sender_name VARCHAR(16) NOT NULL DEFAULT \'\' AFTER sender_email');
	$sql->query('SELECT user_email, user_name
			FROM genu_users
			WHERE user_id = \'1\'');
	$table_users = $sql->fetch();
	$sql->query('UPDATE genu_settings SET sender_email = \'' . $table_users['user_email'] . '\', sender_name = \'' . $table_users['user_name'] . '\'');
	echo '
		<div class="rightMain">
			<div class="rightContent">Table <span style="font-weight: bold">genu_settings</span> updated successfully.<br />';
	/* genu_users */
	$sql->query('ALTER TABLE genu_users CHANGE user_language user_language ENUM(\'dutch\',\'english\',\'french\',\'german\',\'italian\',\'polish\',\'spanish\') DEFAULT \'english\' NOT NULL');
	echo 'Table <span style="font-weight: bold">genu_users</span> updated successfully.<br /><br />Please remove <span style="font-weight: bold">install</span> folder from your server.</div>
		</div>';
}
elseif ($_POST['add_settings'])
{
	$data .= "<?php\n";
	$data .= "// Database config file\n";
	$data .= "define('SQL_TYPE', 'mysql');\n";
	$data .= "define('SQL_HOST', '" . $_POST['db_host'] . "');\n";
	$data .= "define('SQL_PORT', '" . $_POST['db_port'] . "');\n";
	$data .= "define('SQL_DATABASE', '" . encode($_POST['db_name']) . "');\n";
	$data .= "define('SQL_USER', '" . encode($_POST['db_user']) . "');\n";
	$data .= "define('SQL_PASSWORD', '" . encode($_POST['db_password']) . "');\n";
	$data .= "?>";

	$config_file = './../includes/config.php';
	$fp = fopen($config_file, 'w');
	if (!$fp)
	{
		$error = 'Unable to open "' . $config_file . '" file.';
	}
	else
	{
		flock($fp, LOCK_EX);
		fwrite($fp, $data, strlen($data));
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	if ($error)
	{
		echo '
		<div class="rightMain">
			<div class="rightContent">' . $error . '</div>
		</div>';
	}
	else
	{
		echo '
		<div class="rightMain">
			<div class="rightTop">Settings updated successfully. Please choose which version of GENU you want to upgrade.</div>
			<form method="post" action="./upgrade.php">
				<div class="formLeft">GENU:</div>
				<div class="formRight"><select name="version"><option value="1">1.0</option><option value="2">2.0</option><option value="3">2.1</option></select></div>
				<div class="formLeft">&nbsp;</div>
				<div class="formRight"><input type="submit" value="Submit" /></div>
			</form>
		</div>';
	}
}
else
{
	echo '
		<div class="rightMain">
			<div class="rightTop">Please fill in the form with your MySQL database settings.</div>
			<form method="post" action="./upgrade.php">
				<div class="formLeft">Host:</div>
				<div class="formRight"><input type="text" name="db_host" size="25" maxlength="64" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">Port:</div>
				<div class="formRight"><input type="text" name="db_port" size="25" maxlength="64" /></div>
				<div class="formLeft">Database name:</div>
				<div class="formRight"><input type="text" name="db_name" size="25" maxlength="64" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">User:</div>
				<div class="formRight"><input type="text" name="db_user" size="25" maxlength="64" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">Password:</div>
				<div class="formRight"><input type="password" name="db_password" size="25" maxlength="64" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">&nbsp;</div>
				<div class="formRight"><input type="submit" value="Send" name="add_settings" /></div>
			</form>
		</div>';
}

echo '
		<div class="rightBottom">Back to home</div>
	</div>
</div>
<div class="pageBottom">&copy; Copyright 2003-2005 GENU - All rights reserved<br />Powered by <a href="http://genu.org/" title="GENU">GENU</a> 2.2</div>
</body>
</html>';

?>