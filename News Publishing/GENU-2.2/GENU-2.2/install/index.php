<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.9 2005/05/06 11:15:35 raoul Exp $
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

if ($_GET['step'] == 2)
{
	echo '
		<div class="rightMain">
			<div class="rightTop">Please choose your database type.</div>
			<form method="post" action="./index.php">
				<div class="formLeft">Database type:</div>
				<div class="formRight"><select name="sql_type"><option value="mysql">MySQL</option><option value="pgsql">PostgreSQL</option><option value="sqlite">SQLite</option></select></div>
				<div class="formLeft">&nbsp;</div>
				<div class="formRight"><input type="submit" value="Send" name="add_database" /></div>
			</form>
		</div>';
}
elseif ($_POST['add_database'])
{
	$data .= "<?php\n";
	$data .= "// Database config file\n";
	$data .= "define('SQL_TYPE', '" . $_POST['sql_type'] . "');\n";
	if ($_POST['sql_type'] == 'sqlite')
	{
		$data .= "define('SQL_DATABASE', './../db/genu.db');\n";
	}
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
		if ($_POST['sql_type'] == 'sqlite')
		{
			echo '
		<div class="rightMain">
			<div class="rightContent">Settings updated successfully.<br /><br /><a href="./index.php?step=3" title="Next step">Next step &gt;</a></div>
		</div>';
		}
		else
		{
			echo '
		<div class="rightMain">
			<div class="rightTop">Please fill in the form with your database settings.</div>
			<form method="post" action="./index.php">
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
	}
}
elseif ($_POST['add_settings'])
{
	$data .= "<?php\n";
	$data .= "// Database config file\n";
	$data .= "define('SQL_TYPE', '" . SQL_TYPE . "');\n";
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
			<div class="rightContent">Settings updated successfully.<br /><br /><a href="./index.php?step=3" title="Next step">Next step &gt;</a></div>
		</div>';
	}
}
elseif ($_GET['step'] == 3)
{
	$sql_file = './../sql/' . SQL_TYPE . '.sql';
	$fp = fopen($sql_file, 'r');
	if (!$fp)
	{
		$error = 'Unable to open "' . $sql_file . '" file.';
	}
	else
	{
		$queries = fread($fp, filesize($sql_file));
		$queries = split_sql_file($queries);
		for ($i = 0; $i < count($queries); $i++)
		{
			$sql->query($queries[$i]);
		}
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
			<div class="rightContent">Tables created successfully.<br /><br /><a href="./index.php?step=4" title="Next step">Next step &gt;</a></div>
		</div>';
	}
}
elseif ($_GET['step'] == 4)
{
	echo '
		<div class="rightMain">
			<div class="rightTop">Please fill in the form with a username, a password and a <span style="font-weight: bold">valid</span> e-mail address.</div>
			<form method="post" action="./index.php">
				<div class="formLeft">Username:</div>
				<div class="formRight"><input type="text" name="user_name" size="25" maxlength="16" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">Password:</div>
				<div class="formRight"><input type="password" name="user_password" size="25" maxlength="32" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">Password (confirmation):</div>
				<div class="formRight"><input type="password" name="user_password2" size="25" maxlength="32" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">E-mail:</div>
				<div class="formRight"><input type="text" name="user_email" size="25" maxlength="64" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">&nbsp;</div>
				<div class="formRight"><input type="submit" value="Send" name="add_user" /></div>
			</form>
		</div>';
}
elseif ($_POST['add_user'])
{
	if (!trim($_POST['user_name']))
	{
		$error .= 'You must supply a username.<br />';
	}
	if ($_POST['user_name'])
	{
		if (strlen(trim($_POST['user_name'])) < 3)
		{
			$error .= sprintf('Username must be at least %s characters long.<br />', 3);
		}
	}
	if (!trim($_POST['user_password']))
	{
		$error .= 'You must supply a password.<br />';
	}
	if ($_POST['user_password'])
	{
		if (strlen(trim($_POST['user_password'])) < 6)
		{
			$error .= sprintf('Password must be at least %s characters long.<br />', 6);
		}
	}
	if (trim($_POST['user_password2']) != trim($_POST['user_password']))
	{
		$error .= 'Passwords don\'t match.<br />';
	}
	if (!trim($_POST['user_email']))
	{
		$error .= 'You must supply an e-mail address.<br />';
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
		$sql->query('INSERT INTO genu_users (user_level, user_name, user_password, user_email, user_creation, user_ip, user_key)
				VALUES (\'4\', \'' . $_POST['user_name'] . '\', \'' . md5($_POST['user_password']) . '\', \'' . $_POST['user_email'] . '\', \'' . time() . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\', \'0\')');
		$sql->query('INSERT INTO genu_settings (sender_email, sender_name)
				VALUES (\'' . $_POST['user_email'] . '\', \'' . $_POST['user_name'] . '\')');
		echo '
		<div class="rightMain">
			<div class="rightContent">User created successfully.<br /><br /><a href="./index.php?step=5" title="Next step">Next step &gt;</a></div>
		</div>';
	}
}
elseif ($_GET['step'] == 5)
{
	echo '
		<div class="rightMain">
			<div class="rightTop">Please fill in the form with your site name and URL (without trailing slash /).</div>
			<form method="post" action="./index.php">
				<div class="formLeft">Site name:</div>
				<div class="formRight"><input type="text" name="sitename" size="20" maxlength="64" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">Site URL:</div>
				<div class="formRight"><input type="text" name="siteurl" size="20" maxlength="255" value="http://" />&nbsp;<span style="color: red">*</span></div>
				<div class="formLeft">Install smilies pack ?</div>
				<div class="formRight"><select name="add_smilies"><option value="0">No</option><option value="1">Yes</option></select></div>
				<div class="formLeft">&nbsp;</div>
				<div class="formRight"><input type="submit" value="Send" name="add_info" /></div>
			</form>
		</div>';
}
elseif ($_POST['add_info'])
{
	if (!trim($_POST['sitename']))
	{
		$error .= 'You must supply a site name.<br />';
	}
	if (!trim($_POST['siteurl']))
	{
		$error .= 'You must supply a site URL.<br />';
	}
	if (ereg('/$', trim($_POST['siteurl'])))
	{
		$error .= 'Trailing slash is not allowed in site URL.<br />';
	}
	if ($_POST['add_smilies'] == 1)
	{
		$sql_file = './../sql/smilies.sql';
		$fp = fopen($sql_file, 'r');
		if (!$fp)
		{
			$error = 'Unable to open "' . $sql_file . '" file.';
		}
		else
		{
			$queries = fread($fp, filesize($sql_file));
			$queries = split_sql_file($queries);
			for ($i = 0; $i < count($queries); $i++)
			{
				$sql->query($queries[$i]);
			}
			fclose($fp);
		}
	}
	else
	{
		foreach (glob('./../images/smilies/*.gif') as $filename)
		{
			if (!$filename)
			{
				$error = 'Unable to delete "' . $filename . '" file.';
			}
			else
			{
				unlink($filename);
			}
		}
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
		$sql->query('UPDATE genu_settings
				SET sitename = \'' . $_POST['sitename'] . '\', siteurl = \'' . $_POST['siteurl'] . '\'');
		echo '
		<div class="rightMain">
			<div class="rightContent">Informations updated successfully.<br /><br />Installation is finished, please remove <span style="font-weight: bold">install</span> folder from your server.<br /><br />Go to the <a href="' . $_POST['siteurl'] . '/index.php" title="Home">index page</a> of your website.</div>
		</div>';
	}
}
else
{
	echo '
		<div class="rightMain">
			<div class="rightContent">Welcome to GENU installation.<br /><br />In order to have GENU working completely, the following is required:<br />&middot; A webserver or web hosting account<br />&middot; PHP 4.2.0 or higher (with mail function enabled, preferably)<br />&middot; A MySQL database (3.23.23 or higher) or a PostgreSQL database (6.5.x or higher) or SQLite database support in PHP 5.x<br /><br /><a href="./index.php?step=2" title="Next step">Next step &gt;</a></div>
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