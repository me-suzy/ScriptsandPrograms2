<?php
// -------------------------------------------------------------
//
// $Id: common.php,v 1.8 2005/04/03 16:20:26 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

$invalid = eregi('(http|ftp|www)', $_SERVER['QUERY_STRING']);
if ($invalid != false)
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>GENU - Error</title>
</head>
<body><p>Invalid query string. Exiting...</p></body>
</html>';
	exit();
}

$mtime = explode(' ', microtime());
$start_time = $mtime[1] + $mtime[0];

error_reporting(E_ALL ^ E_NOTICE);
// error_reporting(E_ALL);

$table_prefix = 'genu_';
define('ACCOUNT_EXPIRY', 2592000);
define('COOKIE_EXPIRY', 2592000);
define('GENU_VERSION', '2.2');
define('MAX_NEWS_LENGHT', 1024);
define('MIN_NAME_LENGHT', 3);
define('MIN_PASS_LENGHT', 3);
define('PAGES_LIMIT', 10);
define('POLLS_LIMIT', 10);
define('POST_INTERVAL', 30);
define('SEARCH_LIMIT', 30);
define('TABLE_ANSWERS', $table_prefix . 'answers');
define('TABLE_CATEGORIES', $table_prefix . 'categories');
define('TABLE_COMMENTS', $table_prefix . 'comments');
define('TABLE_NEWS', $table_prefix . 'news');
define('TABLE_POSTS', $table_prefix . 'posts');
define('TABLE_QUESTIONS', $table_prefix . 'questions');
define('TABLE_SESSIONS', $table_prefix . 'sessions');
define('TABLE_SETTINGS', $table_prefix . 'settings');
define('TABLE_SMILIES', $table_prefix . 'smilies');
define('TABLE_USERS', $table_prefix . 'users');
define('TABLE_VOTES', $table_prefix . 'votes');
define('USERS_LIMIT', 30);
define('VOTE_INTERVAL', 86400);
define('WORD_WRAP', 95);

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
include('./../includes/session.php');
include('./../includes/template.php');
$template = new template('./../templates/' . get_template() . '');
include('./../languages/' . get_language() . '.php');

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

?>