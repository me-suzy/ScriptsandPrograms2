<?php

/**
* Plugbox
* Copyright 2004 - 2005 Donnie Adams. All rights reserved.
* Author: Donnie Adams (pluggingit.com)
*
* Terms of Uses:
* You are allowed to distribute (unmodified versions), alter for personal use, 
* and make a profit off of Plugbox. You are NOT allowed to remove or alter 
* any of the copyright and TOS notices in any way, shape, or form. You may 
* distribute UNMODIFIED versions of this script that are directly downloaded
* from http://pluggingit.com. You may NOT use or sell any code snippets
* from this script for any other purposes without prior written consent. This 
* script is provided "AS IS" and by using it you agree to indemnify us from liability 
* of any kind that may arise from its use. We reserves the right to change the 
* above copyright statement at anytime, for any reason, without notice.
*/

error_reporting(E_ALL ^ E_NOTICE);// Production
//error_reporting(E_ALL);// Development
set_magic_quotes_runtime(0);

// Disable browser caching

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// File and directory paths

$pb_paths['root']		= str_replace('\\', '/', dirname(__FILE__)) . '/';
$pb_paths['plug_db']	= $pb_paths['root'] . 'plugs.txt';
$pb_paths['banned_db']	= $pb_paths['root'] . 'banned.txt';
$pb_paths['config_db']	= $pb_paths['root'] . 'config.php';

// Un-quote characters in _GET, _POST, _COOKIE, _REQUEST super globals

if (get_magic_quotes_gpc())
{
	if ($_GET)		unquote($_GET);
	if ($_POST)		unquote($_POST);
	if ($_COOKIE)	unquote($_COOKIE);
	if ($_REQUEST)	unquote($_REQUEST);
}

require_once($pb_paths['config_db']);

//
//-------------------------------------------------------------------
// BEGIN PLUGBOARD ADMIN PANELS
//-------------------------------------------------------------------
//

do_auth();

$cpanel = empty($_REQUEST['cp']) ? '' : $_REQUEST['cp'];
$action = empty($_REQUEST['act']) ? '' : $_REQUEST['act'];

switch ($cpanel)
{

	// ------------------------------------
	// PLUGS: view, ban, and delete
	// ------------------------------------

	case 'plugs':

		$message = '';

		switch ($action)
		{
			case 'delete':
				if (!empty($_POST['plug_IDs']))
				{
					if (is_numeric($total_deleted = act_delete_plugs($_POST['plug_IDs'])))
					{
						if ($total_deleted == 1)
						{
							$message = "One plug has been successfully deleted!";
						}
						else
						{
							$message = "A total of <strong>{$total_deleted}" .
								"</strong> plugs have been successfully deleted!";
						}
					}
				}
				break;

			case 'ban':
				if (isset($_GET['ip']) && act_ban_ip($_GET['ip']))
				{
					$message = "The IP address <em>{$_GET['ip']}</em> is now banned!";
				}
				break;

			default:
		}

		print_cp_plugs($message);

		exit();
		break;

	// ------------------------------------
	// OPTIONS: update
	// ------------------------------------

	case 'options':

		$message = '';

		if (($action == 'update') && isset($_POST['submit']))
		{
			if (act_update_options())
			{
				$message = 'Options have been successfully updated!<br />' . 
					'Please note, you will need to login again if your username or password has changed.';

			}
			else
			{
				$message = 'Could <strong>not</strong> update options!<br />' . 
					'Please confirm that <em>' . basename($pb_paths['root']) . 
					'</em> exist and is writable.';
			}
		}

		print_cp_options($message);
		exit();
		break;

	// ------------------------------------
	// BANS: ban, add, and remove
	// ------------------------------------

	case 'bans':

		$message = '';

		switch ($action)
		{
			case 'add':
				if (isset($_POST['ip']) && act_ban_ip($_POST['ip']))
				{
					$message = "The IP address <em>{$_POST['ip']}</em> is now banned!";
				}
				break;

			case 'remove':
				if (isset($_GET['ip']) && act_remove_ip_ban($_GET['ip']))
				{
					$message = "The IP address <em>{$_GET['ip']}</em> is no longer banned!";
				}
				break;
		}

		print_cp_ipban($message);
		exit();
		break;

	// ------------------------------------
	// Edit Plug: edit plugger's infomation
	// ------------------------------------

	case 'edit_plug':

		if (empty($_GET['id']) || !is_numeric($_GET['id']))
		{
			print_cp_plugs('Can not edit. Invalid plug ID.');
			exit();
		}

		$id      = intval($_GET['id']);
		$message = '';

		if (('edit' == $action))
		{
			if (empty($_POST['button_url']) || 
				(trim($_POST['button_url']) == '') ||
				(trim($_POST['button_url']) == 'http://'))
			{
				$message = 'You must enter a button URL.';
			}
			elseif (empty($_POST['website_url']) || 
				(trim($_POST['website_url']) == '') ||
				(trim($_POST['website_url']) == 'http://'))
			{
				$message = 'You must enter a website URL.';
			}
			else
			{
				$_POST['click_count'] = empty($_POST['click_count']) ? 0 : $_POST['click_count'];

				act_edit_plug($id, $_POST['button_url'], $_POST['website_url'], $_POST['click_count']);
				$message = 'The plug has been successfully updated!';
			}
		}

		print_cp_edit_plug($id, $message);
		exit();
		break;

	// ------------------------------------
	// Default page
	// ------------------------------------

	default:
		print_cp_header('Main');

		$hour24 = date("G");

		if ($hour24 >= 18)
		{
			$greeting = 'Good Evening';
		}
		elseif ($hour24 >= 12)
		{
			$greeting = 'Good Afternoon';
		}
		else
		{
			$greeting = 'Good Morning';
		}

		$plug_total = @intval(count(file($pb_paths['plug_db'])));
		$ban_total  = @intval(count(file($pb_paths['banned_db'])));

		?>

<h2><?php echo $greeting; ?>, <?php echo $pb_cfg['admin_uname']; ?></h2>

<p>Welcome to the Plugbox admin panel.</p>

<p>Please use the above menu to navigate through the admin panel.</p>

<div class="important">

<p>You currently have a total of <strong><?php echo $plug_total; 
?></strong> plugs.<br />
You also have a total of <strong><?php echo $ban_total; 
?></strong> banned IP adresses.</p>

</div>

<p>Thank you for using Plugbox <?php echo $pb_cfg['version']; ?>.</p>

		<?php

		print_cp_footer();
}

//
//-------------------------------------------------------------------
// MISC FUNCTION DEFINITIONS
//-------------------------------------------------------------------
//

//-------------------------------------
// Un-quote string quoted with addslashes
//-------------------------------------

function unquote(&$input)
{
	if (is_string($input))
	{
		$input = stripslashes($input);
	}
	elseif (is_array($input))
	{
		foreach ($input as $key => $val)
		{
			$input[$key] = unquote($val);
		}
	}

	return $input;
}

//-------------------------------------
// Replace newline characters
//-------------------------------------

function replace_nl($subject, $replacement = ' ')
{
	return preg_replace("/(\015\012)|(\015)|(\012)/", $replacement, $subject);
}

// ------------------------------------
// Validate an IP address
// ------------------------------------

function is_valid_ip($ip_address)
{
	if (preg_match( "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $ip_address))
	{
		return true;
	}
	else
	{
		return false;
	}
}

// ------------------------------------
// Get a human readable file size
// ------------------------------------

function human_filesize($filename)
{
	$suffixes = array(
		' Bytes', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'
		);

	$filesize = filesize($filename);

	$i = min((int)(log($filesize)/log(1024)), count($suffixes)-1);
	return $filesize . $suffixes[$i];
}

//
//-------------------------------------------------------------------
// ACTION FUNCTION DEFINITIONS
//-------------------------------------------------------------------
//

//-------------------------------------
// Authorize admin useres
//-------------------------------------

function do_auth()
{
	global $pb_cfg;

	if ((isset($_COOKIE['pb_uname'])) && (isset($_COOKIE['pb_upass'])))
	{
		if (($pb_cfg['admin_uname'] !== $_COOKIE['pb_uname']) ||
			($pb_cfg['admin_upass'] !== $_COOKIE['pb_upass']))
		{
			setcookie('pb_uname', '', time() - 3600);
			setcookie('pb_upass', '', time() - 3600);
			print_cp_login('Your username or password has changed. Please login again.', $_COOKIE['pb_uname'], true);
			exit();
		}
		elseif (isset($_GET['act']) && ('logout' == $_GET['act']))
		{
			setcookie('pb_uname', '', time()-3600);
			setcookie('pb_upass', '', time()-3600);
			print_cp_login('You have been successfully logged out.');
			exit();
		}
	}
	elseif (isset($_POST['act']) && ('login' == $_POST['act']))
	{
		$username    = isset($_POST['username']) ? $_POST['username'] : null;
		$password    = isset($_POST['password']) ? $_POST['password'] : null;
		$remember_me = isset($_POST['remember_me']) ? true : false;

		if (($pb_cfg['admin_uname'] === $username) &&
			($pb_cfg['admin_upass'] === md5($password)))
		{
			$cookie_expiration = ($remember_me) ? (time()+31104000) : null;

			setcookie('pb_uname', $pb_cfg['admin_uname'], $cookie_expiration);
			setcookie('pb_upass', $pb_cfg['admin_upass'], $cookie_expiration);
		}
		else
		{
			print_cp_login('The username and/or password you entered is invalid.', $username, $remember_me);
			exit();
		}
	}
	else
	{
		print_cp_login();
		exit();
	}
}

// ------------------------------------
// Remove plugs from the database
// ------------------------------------

function act_delete_plugs($plug_IDs = array())
{
	global $pb_paths;

	$delete_total = 0;

	if (!empty($plug_IDs) && is_array($plug_IDs))
	{
		if ($plugs = @file($pb_paths['plug_db']))
		{
			foreach ($plugs as $key => $plug)
			{
				if ($plug_id = substr(strrchr(rtrim($plug), '|'), 1))
				{
					if (in_array($plug_id, $plug_IDs, true))
					{
						unset($plugs[$key]);
						$delete_total++;
					}
				}
			}
		}
	}

	$result = false;

	if ($delete_total > 0)
	{
		ignore_user_abort(true);
		$plugs = implode('', $plugs);

		if ($fp = @fopen($pb_paths['plug_db'], 'wb'))
		{
			@flock($fp, LOCK_EX);
			$result = fwrite($fp, $plugs, strlen($plugs));
			@flock($fp, LOCK_UN);
			fclose($fp);
		}

		ignore_user_abort(false);
	}

	return (($result !== false) && $result != -1) ? $delete_total : false;
}

// ------------------------------------
// Ban an IP address
// ------------------------------------

function act_ban_ip($ip_address)
{
	global $pb_paths;

	$ip_address = trim($ip_address);

	if ( ! is_valid_ip($ip_address))
	{
		return false;
	}

	if ($fp = @fopen($pb_paths['banned_db'], 'r+b'))
	{

		$banned_db = '';

		while (!feof($fp))
		{
			$line = fgets($fp, 1024);

			if ($ip_address == rtrim($line))
			{
				return true;
			}

			$banned_db .= $line;
		}

		$banned_db = $ip_address . "\n" . $banned_db;
		$result     = false;

		ignore_user_abort(true);
		@flock($fp, LOCK_EX);
		rewind($fp);
		$result = fwrite($fp, $banned_db, strlen($banned_db));
		fflush($fp);
		ftruncate($fp, ftell($fp));
		@flock($fp, LOCK_UN);
		fclose($fp);
		ignore_user_abort(false);

		return (($result !== false) && $result != -1) ? true : false;
	}

	return false;
}

// ------------------------------------
// Remove a banned IP address
// ------------------------------------

function act_remove_ip_ban($ip_address)
{
	global $pb_paths;

	if ( ! is_valid_ip($ip_address))
	{
		return false;
	}

	if ($banned_IPs = @file($pb_paths['banned_db']))
	{
		if ($removal_keys = array_keys($banned_IPs, ($ip_address . "\n")))
		{
			foreach ($removal_keys as $val)
			{
				unset($banned_IPs[$val]);
			}

			$banned_IPs = implode('', $banned_IPs);

			ignore_user_abort(true);

			$result = false;

			if ($fp = @fopen($pb_paths['banned_db'], 'wb'))
			{
				@flock($fp, LOCK_EX);
				$result = fwrite($fp, $banned_IPs, strlen($banned_IPs));
				@flock($fp, LOCK_UN);
				fclose($fp);
			}

			ignore_user_abort(false);

			return ($result !== false) ? true : false;
		}
	}

	return false;
}

// ------------------------------------
// Update config options
// ------------------------------------

function act_update_options()
{
	global $pb_cfg, $pb_paths;

	//-------------------------------------
	// Username
	//-------------------------------------

	$opts['admin_uname'] = trim($_POST['admin_uname']);
	if (empty($opts['admin_uname']))
	{
		print_cp_options('You must enter your username.');
		exit();
	}
	elseif (!preg_match('/^[a-z0-9\_\-]+$/i', $opts['admin_uname']))
	{
		print_cp_options('Your username must contain alphanumeric characters only.');
		exit();
	}

	//-------------------------------------
	// Password
	//-------------------------------------

	$password     = trim($_POST['admin_upass']);
	$confirmed_pw = $_POST['confirmed_pw'];
	if (!empty($password))
	{
		if (empty($confirmed_pw))
		{
			print_cp_options('You must confirm your new password by entering it twice.');
			exit();
		}
		elseif ($password !== $confirmed_pw)
		{
			print_cp_options('The passwords you entered do not match.');
			exit();
		}
		else
		{
			$opts['admin_upass'] = md5($password);
		}
	}

	//-------------------------------------
	// Updated Options
	//-------------------------------------

	$opts['base_url']			= $_POST['base_url'];
	$opts['site_url']			= $_POST['site_url'];
	$opts['max_plugs']			= intval(abs($_POST['max_plugs']));
	$opts['timeout']			= intval(abs($_POST['timeout']));
	$opts['allow_milti_post']	= ($_POST['allow_milti_post']) ? 1 : 0;
	$opts['date_format']		= $_POST['date_format'];
	$opts['button_width']		= intval(abs($_POST['button_width']));
	$opts['button_height']		= intval(abs($_POST['button_height']));
	$opts['error_tpl']			= $_POST['error_tpl'];

	if ((trim($opts['base_url']) != '') && (strrchr($opts['base_url'], '/') !== '/'))
	{
		$opts['base_url'] .= '/';
	}

	switch (strtoupper($_POST['display_order']))
	{
		case 'ASC':
			$opts['display_order']	= 'ASC';
			break;

		case 'RANDOM':
			$opts['display_order']	= 'RANDOM';
			break;

		default:// DESC
			$opts['display_order']	= 'DESC';
	}

	//-------------------------------------
	// Update Configurations File
	//-------------------------------------

	$str  = "<?php\n\n";
	$str .= "// Caution: Auto-Generated File. Do Not Edit!\n";
	$str .= "// Edit these variable through the admin panel.\n\n";

	$chars2quote = array(
		'search'  => array('\\', '$', '"'),
		'replace' => array('\\\\', '\$', '\"')
		);

	foreach ($pb_cfg as $key => $val)
	{
		if (isset($opts[$key]))
		{
			$val = $opts[$key];
		}

		if ($key != 'error_tpl')
		{
			$val = trim($val);
		}

		$val  = str_replace($chars2quote['search'], $chars2quote['replace'], $val);
		$val = replace_nl($val, '\\n');
		$str .= "\$pb_cfg['{$key}'] = \"" . ($val) . "\";\n";
	}

	$str .= "\n?>";

	$result = false;

	ignore_user_abort(true);

	if ($fp = @fopen($pb_paths['config_db'], 'wb'))
	{
		@flock($fp, LOCK_EX);
		$result = @fwrite($fp, $str, strlen($str));
		@flock($fp, LOCK_UN);
		@fclose($fp);
	}

	ignore_user_abort(false);

	$pb_cfg = array_merge($pb_cfg, $opts);

	return (($result !== false) && $result != -1) ? true : false;
}

// ------------------------------------
// Edit an existing plug
// ------------------------------------

function act_edit_plug($id, $button_url, $website_url, $click_count)
{
	global $pb_cfg, $pb_paths;

	if (is_numeric($id) && ($plugs = @file($pb_paths['plug_db'])))
	{
		$token = '|' . intval($id);

		foreach ($plugs as $key => $val)
		{
			if (strpos($val, $token) !== false)
			{
				// ------------------------------------
				// Format and encode
				// ------------------------------------

				$button_url = str_replace(array('http://', 'www.'), '', $button_url);
				$button_url = htmlspecialchars(utf8_decode($button_url), ENT_QUOTES);
				$button_url = rtrim($button_url, '/');

				$website_url = str_replace(array('http://', 'www.'), '', $website_url);
				$website_url = htmlspecialchars(utf8_decode($website_url), ENT_QUOTES);
				$website_url = rtrim($website_url, '/');

				// Array $val
				// index 0: button URL
				// index 1: web site URL
				// index 2: click total
				// index 3: IP address
				// index 4: UNIX timestamp

				$val         = explode('|', $val);
				$val[0]      = $button_url;
				$val[1]      = $website_url;
				$val[2]      = abs(intval($click_count));
				$plugs[$key] = implode('|', $val);

				//-------------------------------------
				// Write updated plug to file.
				//-------------------------------------

				$result = false;

				$plugs = implode('', $plugs);

				ignore_user_abort(true);

				if ($fp = @fopen($pb_paths['plug_db'], 'wb'))
				{
					@flock($fp, LOCK_EX);
					$result = fwrite($fp, $plugs, strlen($plugs));
					@flock($fp, LOCK_UN);
					fclose($fp);
				}

				ignore_user_abort(false);

				return (($result !== false) && $result != -1) ? true : false;
			}
			// END IF
		}
		//END FOREACH
	}
	// END IF

	return false;
}

//
//-------------------------------------------------------------------
// CONTROL PANEL TEMPLATE FUNCTION DEFINITIONS
//-------------------------------------------------------------------
//

//-------------------------------------
// HTML header template
//-------------------------------------

function print_cp_header($page_title = '', $logged_in = true)
{
	global $pb_cfg;

	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Plugbox <?php echo $pb_cfg['version']; ?> - <?php echo $page_title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="title" content="Plugbox" />
	<meta name="author" content="Donnie Adams" />
	<meta name="copyright" content="&copy; 2005 Donnie Adams. All Rights Reserved.">
	<meta name="keywords" content="Plugbox, Plugboard, Plug" />
	<meta name="description" content="PHP powered, plugboard management system.">
	<style type="text/css">
		body {
			background-color: #DD3344;
			color: #000;
			text-align: center;
			margin: 10;
			padding: 0;
		}

		body, td, th {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;
		}

		a:link, a:visited {
			color: #ACC742;
		}

		a:active, a:hover {
			color: #CEE574;
		}

		img {
			border: none;
		}

		table {
			text-align: left;
			margin: auto;
			width: auto;
		}

		form table {
			width: 100%;
		}

		form {
			text-align: center;
			width: auto;
			margin: auto;
		}

		input, textarea {
			background-color: #FFF;
			color: #000;
			border: 1px solid #5B93EF;
		}

		.button {
			background-color: #DD3344;
			color: #FFF;
			font-weight: bold;
			font-size: 12px;
			border: 1px solid #FFF;
		}

		h1 {
			font-size: 18px;
			font-family: Georgia, "Times New Roman", Times, serif;
			letter-spacing: .5em;
		}

		h2 {
			font-size: 14px;
			font-family: Georgia, "Times New Roman", Times, serif;
		}

		h3 {
			font-size: 12px;
			font-family: Georgia, "Times New Roman", Times, serif;
		}

		strong, b {
			color: #DD3344;
		}

		em, i {
			color: #ACC742;
		}

		#wrapper {
			background-color: #FFF;
			border: 15px solid #EA848E;
			width: 715px;
			margin: 0 auto;
			padding: 5px;
		}

		#content {
			background-color: #F7CCD1;
			color: #000;
			border: 1px solid #EA848E;
			padding: 5px 0;
		}

		.heading th {
			border-bottom: 1px solid #EA848E;
		}

		.pagination {
			color: #333;
			font-weight: bold;
		}

		.pagination a:link, 
		.pagination a:visited {
			color: #DD3344;
			text-decoration: none;
			font-weight: bold;
		}

		.pagination a:active, 
		.pagination a:hover {
			color: #EA848E;
			text-decoration: none;
			font-weight: bold;
		}

		.alt-one {
			background-color: #FAE2E5;
			
		}

		.alt-two {
			background-color: #FEF8F8;
			
		}

		#menu li {
			display: inline;
			list-style-type: none;
			padding-right: 10px;
		}

		.important {
			background-color: #FFF;
			color: #2B7ADF;
			border-top: 1px solid #EA848E;
			border-bottom: 1px solid #EA848E;
		}
	</style>
</head>
<body>

<div id="wrapper">
	<div id="header">
		<h1>Plugbox <?php echo $pb_cfg['version']; ?></h1>
		<h2>Plugboard management system.</h2>
	</div>

	<?php if ($logged_in): ?>
	<ul id="menu">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Main</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?cp=plugs">Manage Plugs</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?cp=options">Edit Options</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?cp=bans">IP Banning</a></li>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?act=logout">Logout</a></li>
	</ul>
	<?php endif; ?>

	<div id="content">

	<?php
}

//-------------------------------------
// HTML footer template
//-------------------------------------

function print_cp_footer()
{
	global $pb_cfg;

	?>

	<!-- Close #content -->
	</div>

	<p id="footer">Powered by <a href="http://pluggingit.com/" target="_blank">Plugbox <?php echo $pb_cfg['version']; ?></a></p>

<!-- Close #wrapper -->
</div>

</body>
</html>

	<?php
}

//-------------------------------------
// Login template
//-------------------------------------

function print_cp_login($message = '', $username = '', $remembered = false)
{
	?>

<?php print_cp_header('Login', false); ?>

<h2>Admin Login</h2>

<?php if($message): ?>
<p class="important"><?php echo $message; ?></p>
<?php endif; ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
?>" name="login" id="login">
	<input type="hidden" name="act" value="login" />
	<p>
		Username:
		<input type="text" id="username" name="username" value="<?php 
			echo htmlspecialchars($username, ENT_QUOTES);
			?>" size="25" maxlength="25" />
	</p>
	<p>
		Password:
		<input type="password" id="password" name="password" size="25" maxlength="25" />
	</p>
	<p>
		<input type="submit" class="button" name="submit" value="Login" />
	</p>
	<p>
		<label>Remember me?
		<input type="checkbox" name="remember_me" <?php 
			if($remembered): echo('checked="checked" '); endif; ?>/></label>
	</p>
</form>

<?php print_cp_footer(); ?>

	<?php
}

//-------------------------------------
// 
//-------------------------------------

function print_cp_plugs($message = '')
{
	global $pb_cfg, $pb_paths;

	if (isset($_POST['per_page']))
	{
		$per_page = abs(intval($_POST['per_page']));
		setcookie('pb_per_page', $per_page);
	}
	elseif (isset($_COOKIE['pb_per_page']))
	{
		$per_page = abs(intval($_COOKIE['pb_per_page']));
	}
	else
	{
		$per_page = 20;
	}

	if ($per_page == 0)
	{
		$per_page = 20;
	}

	?>

<?php print_cp_header('Manage Plugs'); ?>

<h2>Manage Plugs</h2>

<?php if ($message): ?>
<p class="important"><?php echo $message; ?></p>
<?php endif; ?>

<script language="javascript" type="text/javascript">
<!--
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('plug_IDs[]');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
	if ( ref == 1 )
	{
		if ( chkAll.checked == true )
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
		}
		else
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
		}
	}
	else
	{
		for ( i=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true )
			{
			allChecked = true;
			continue;
			}
			else
			{
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		chkAll.checked = true;
		else
		chkAll.checked = false;
	}
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
}
//-->
</script>

<?php if ($plugs = @file($pb_paths['plug_db'])): ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
?>?cp=plugs&amp;act=delete" onsubmit="return confirm('Are you sure you want to delete the selected items?');"
name="plugs" id="plugs">
	<table cellspacing="0" cellpadding="2">
		<tr class="heading">
			<th>Button</th>
			<th>Clicks</th>
			<th>IP Address</th>
			<th>Actions</th>
			<th title="Delete All"><label><input type="checkbox" onclick="checkAllFields(1);" id="checkAll" /> Delete</label></th>
		</tr>
		<?php

		$pagination = '';

			//-------------------------------------
			// Paging
			//-------------------------------------

			$total = count($plugs);
			$pages = ceil($total/$per_page);
			$page  = (empty($_GET['page']) || ($_GET['page'] > $pages)) ? 1 : intval($_GET['page']);
			$start = ceil(($page * $per_page) - $per_page);

			if ($start > $total)
			{
				$start = 0;
			}

			$plugs = array_slice($plugs, $start, $per_page);

			if ($page <= 1)
			{
				$pagination .= '&lt;&lt; <span>Previous</span>';
			}
			else
			{
				$pagination .= "<a href='{$_SERVER['PHP_SELF']}?cp=plugs&amp;page=" . ($page - 1) . "'>&lt;&lt; Previous</a>";
			}

			$pagination .= ' &nbsp;&nbsp; ';

			if ($page < $pages)
			{
				$pagination .= "<a href='{$_SERVER['PHP_SELF']}?cp=plugs&amp;page=" . ($page + 1) . "'>Next &gt;&gt;</a>";
			}
			else
			{
				$pagination .= '<span>Next</span> &gt;&gt;';
			}

			//---------------------------------------------

			// Output to browser

			$button_width  = $pb_cfg['button_width'];
			$button_height = $pb_cfg['button_height'];
			$i = 1;

			foreach ($plugs as $plug):
				@list($button_url, $website_url, $click_count, $ip_address, $timestamp) = explode('|', rtrim($plug));

				$redirect_url = $pb_cfg['base_url'] . 'plug.php?act=go&amp;id=' . $timestamp;
				$date         = date("Y-m-d h:i A", $timestamp);
				$alt_row      = ((($i++ % 2) == 1) ? 'alt-one' : 'alt-two');

				echo <<<HTML

		<tr class="{$alt_row}" title="URL: {$website_url}; Posted: {$date}">
			<td title="{$website_url}"><a href="{$redirect_url}" target="_blank"><img src="http://{$button_url}" border="0" width="{$button_width}" height="{$button_height}" alt="" /></a></td>
			<td><strong>{$click_count}</strong></td>
			<td>{$ip_address}</td>
			<td><a href="{$_SERVER['PHP_SELF']}?cp=edit_plug&amp;id={$timestamp}">Edit</a> -
			<a href="{$_SERVER['PHP_SELF']}?cp=plugs&amp;act=ban&amp;ip={$ip_address}">Ban</a></td>
			<td><input type="checkbox" name="plug_IDs[]" value="{$timestamp}" /></td>
		</tr>
HTML;
			endforeach;?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" class="button" name="submit" value="Delete" /></td>
		</tr>
	</table>
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
?>?cp=plugs" name="pager" id="pager">
	Per Page:
	<input type="text" name="per_page" value="<?php echo $per_page; ?>" size="3" maxlength="3" />
</form>

<?php if ($pagination): ?>
<p class="pagination"><?php echo $pagination; ?></p>
<?php endif; ?>

<?php else: ?>

<p class="important">No plugs exist.</p>

<?php endif ?>

<?php print_cp_footer(); ?>

	<?php
}

// ------------------------------------
// IP banning template
// ------------------------------------

function print_cp_IPban($message = '')
{
	global $pb_paths;

		$i = 1;
	?>

<?php print_cp_header('IP Banning'); ?>

<h2>Ban an IP Address</h2>

<?php if ($message): ?>
<p class="important"><?php echo $message; ?></p>
<?php endif; ?>

<p>Enter the IP of the person you want to ban.</p>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
?>?cp=bans&amp;act=add" name="banning" id="banning">
	<p>
		<label>IP Address: 
		<input type="text" name="ip" /></label>
		<input type="submit" class="button" name="submit" name="ban_ip" value="Ban" />
	</p>
</form>

<?php if ($banned_IPs = @file($pb_paths['banned_db'])): ?>

<h2>Banned IP Addresses</h2>

<table cellspacing="0" cellpadding="2">
	<tr class="heading">
		<th>IP Address</th>
		<th>Remove Ban</th>
	</tr>
	
	<?php foreach ($banned_IPs as $banned_IP): ?>
	<?php $banned_IP = rtrim($banned_IP); ?>
	<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
		<td><?php echo $banned_IP; ?></td>
		<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?cp=bans&amp;act=remove&amp;ip=<?php 
		echo urlencode($banned_IP); ?>">Remove</a></td>
	</tr>
	<?php endforeach; ?>
	
</table>

<?php endif; ?>

<?php print_cp_footer(); ?>

	<?php
}

// ------------------------------------
// Edit options template
// ------------------------------------

function print_cp_options($message = '')
{
		global $pb_cfg;

		$i = 1;
	?>

<?php print_cp_header('Edit Options'); ?>

<h2>Edit Options</h2>

<?php if ($message): ?>
<p class="important"><?php echo $message; ?></p>
<?php endif; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; 
?>?cp=options&amp;act=update" method="post" name="options" id="options">
	<table cellspacing="0" cellpadding="2">
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Username:</th>
			<td><input type="text" name="admin_uname" value="<?php 
			echo htmlspecialchars($pb_cfg['admin_uname']); ?>" maxlength="14" /></td>
			<td>Your Admin Panel login name.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">New Password:</th>
			<td><input type="password" name="admin_upass" maxlength="14" /></td>
			<td>Your new Admin Panel login password. (Only if Changing)</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Confirm Password:</th>
			<td><input type="password" name="confirmed_pw" maxlength="14" /></td>
			<td>Repeat your new password.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Base URL:</th>
			<td><input type="text" name="base_url" value="<?php 
				echo htmlspecialchars($pb_cfg['base_url'], ENT_QUOTES) ?>" maxlength="100" /></td>
			<td>The full URL to the directory that Plugbox is installed in.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Site URL:</th>
			<td><input type="text" name="site_url" value="<?php 
				echo htmlspecialchars($pb_cfg['site_url'], ENT_QUOTES); ?>" maxlength="100" /></td>
			<td>The full URL to the web page that your plugboard resides on.<br />
				<span class="important">Note:</span> This is the URL that visitors will be 
				redirected back to after they've successfully plugged on your plugboard.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Max Plugs:</th>
			<td><input type="text" name="max_plugs" value="<?php 
				echo $pb_cfg['max_plugs']; ?>" maxlength="3" /></td>
			<td>The maximum number of plugs you want displayed.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th>Flood Limit:</th>
			<td><input type="text" name="timeout" value="<?php 
			echo $pb_cfg['timeout']; ?>" maxlength="8" /></td>
			<td>The amount of time in seconds before a visitor can plug again.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top"><a name="milti_post"></a>Allow Multiple IP Posting:</th>
			<td><label><input type="radio" name="allow_milti_post" value="1"<?php 
				if($pb_cfg['allow_milti_post']): echo ' checked="checked"'; endif; ?> /> Yes</label>
				<label><input type="radio" name="allow_milti_post" value="0"<?php 
				if(!$pb_cfg['allow_milti_post']): echo ' checked="checked"'; endif; ?> /> No</label></td>
			<td>Whether to allow multiple posting from the same IP address.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Date Format:</th>
			<td><input type="text" name="date_format" value="<?php 
				echo htmlspecialchars($pb_cfg['date_format'], ENT_QUOTES); ?>" maxlength="50" /></td>
			<td>Use PHP <a href="http://php.net/date">date()</a> formatting characters.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Button Order:</th>
			<td><label><input type="radio" name="display_order" value="ASC"<?php 
				if($pb_cfg['display_order'] == 'ASC'): echo ' checked="checked"'; endif; ?> /> Oldest first</label><br />
			<label><input type="radio" name="display_order" value="DESC"<?php 
				if(($pb_cfg['display_order'] == 'DESC') || empty($pb_cfg['display_order'])): 
					echo ' checked="checked"'; endif; ?> /> Newest first</label><br />
			<label><input type="radio" name="display_order" value="RANDOM"<?php 
				if($pb_cfg['display_order'] == 'RANDOM'): echo ' checked="checked"'; endif; ?> /> Randomly</label></td>
			<td>The order in which buttons will be displayed on your plugboard.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Button Width:</th>
			<td><input type="text" name="button_width" value="<?php 
				echo $pb_cfg['button_width']; ?>" size="2" maxlength="4" /> Pixels</td>
			<td>The width of buttons in pixels.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Button Height:</th>
			<td><input type="text" name="button_height" value="<?php 
				echo $pb_cfg['button_height']; ?>" size="2" maxlength="4" /> Pixels</td>
			<td>The height of buttons in pixels.</td>
		</tr>
		<tr class="<?php echo (($i % 2) ? 'alt-one' : 'alt-two'); $i++; ?>">
			<th valign="top">Error Template:</th>
			<td colspan="2"><span class="important">Tags:</span> <code>{IP_ADDRESS}, {ERROR_MESSAGE}, {BASE_URL}, {WEBSITE_URL}</code><br />
			<textarea name="error_tpl" rows="4" cols="60"><?php 
			echo $pb_cfg['error_tpl']; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><input type="submit" class="button" name="submit" value="Update" /></td>
		</tr>
	</table>
</form>

<?php print_cp_footer(); ?>

	<?php
}

// ------------------------------------
// 
// ------------------------------------

function print_cp_edit_plug($id, $message = '')
{
	global $pb_cfg, $pb_paths;

	$in_db = false;

	if (is_numeric($id))
	{
		if ($plugs = @file($pb_paths['plug_db']))
		{
			$token = '|' . intval($id);

			foreach ($plugs as $key => $val)
			{
				if (strpos($val, $token) !== false)
				{
					@list($button_url, $website_url, $click_count) = explode('|', rtrim($val));
					$in_db = true;
					break;// stop foreach loop
				}
			}
		}

		unset($plugs);
	}

	if (!$in_db)
	{
		print_cp_plugs('Plug ID not found in database.');
		return false;
	}

	?>

<?php print_cp_header('Edit Plug'); ?>

<h2>Edit Plug</h2>

<?php if ($message): ?>
<p class="important"><?php echo $message; ?></p>
<?php endif; ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; 
?>?cp=edit_plug&amp;act=edit&amp;id=<?php echo $id; 
?>" onsubmit="return confirm('Are you sure you want to edit this plug?');"
name="edit_plug" id="edit_plug">
	<table cellspacing="0" cellpadding="2">
		<tr>
			<td colspan="2" style="text-align: center"><img class="important" src="http://<?php 
			echo $button_url; ?>" border="0" width="<?php 
			echo $pb_cfg['button_width']; ?>" height="<?php echo $pb_cfg['button_height']; 
			?>" alt="<?php echo $website_url; ?>" /></td>
		</tr>
		<tr class="alt-one">
			<th><span class="important">*</span> Button URL:</th>
			<td><input type="text" name="button_url" value="http://<?php 
			echo $button_url; ?>" size="25" maxlength="100" /></td>
		</tr>
		<tr class="alt-two">
			<th><span class="important">*</span> Website URL:</th>
			<td><input type="text" name="website_url" value="http://<?php 
			echo $website_url; ?>" size="25" maxlength="100" /></td>
		</tr>
		<tr class="alt-one">
			<th>Click Count:</th>
			<td><input type="text" name="click_count" value="<?php 
			echo $click_count; ?>" size="3" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" class="button" name="submit" value="Edit" /></td>
		</tr>
	</table>
</form>

<p><span class="important">*</span> indicates required field.</p>

<?php print_cp_footer(); ?>

	<?php
}

?>