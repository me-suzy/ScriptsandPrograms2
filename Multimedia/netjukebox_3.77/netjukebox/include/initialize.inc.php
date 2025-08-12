<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



$cfg = Array();
//  +---------------------------------------------------------------------------+
//  | Start time , Version & Username                                           |
//  +---------------------------------------------------------------------------+
list($usec, $sec) 			= explode(' ', microtime());
$cfg['start_time']			= (float)$usec + (float)$sec;
$cfg['netjukebox_version']	= '3.77';
$cfg['username']			= '';



//  +---------------------------------------------------------------------------+
//  | Check for Windows OS                                                      |
//  +---------------------------------------------------------------------------+
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
	{
	$cfg['windows']	= true;
	$cfg['slash']	= '\\';
	}
else
	{
	$cfg['windows']	= false;
	$cfg['slash']	= '/';
	}



//  +---------------------------------------------------------------------------+
//  | Check for PHP version                                                     |
//  +---------------------------------------------------------------------------+
if (version_compare('4.3.0', phpversion(), '>='))	message('error', '<strong>netjukebox requires PHP 4.3.0 or higher</strong><br>you are running PHP ' . phpversion());



//  +---------------------------------------------------------------------------+
//  | Check for Required Extensions                                             |
//  +---------------------------------------------------------------------------+
if (!function_exists('imagecreatetruecolor'))
	if ($cfg['windows'])	message('error', '<strong>GD2 extension not loaded</strong><ul class="compact"><li>Enable php_gd2.dll in the php.ini</li><li>Restart webserver</li></ul>');
	else					message('error', '<strong>GD2 not supported</strong><br>Compile PHP with GD2 support<br>or use a loadable module in the php.ini<br>(extension="libgd.so")<br>For more information: http://www.boutell.com/gd');
if (!function_exists('mysql_pconnect'))
	if ($cfg['windows'])	message('error', '<strong>MYSQL extension not loaded</strong>');
	else					message('error', '<strong>MYSQL not supported</strong><br>Compile PHP with MYSQL support<br>or use a loadable module in the php.ini<br>(extension="mysql.so")<br>For more information: http://www.mysql.com');



//  +---------------------------------------------------------------------------+
//  | Get Home Directory                                                        |
//  +---------------------------------------------------------------------------+
$directory				= dirname(__FILE__);
$directory				= realpath($directory . '/..');
$cfg['home_dir']	= str_replace('\\', '/', $directory);



//  +---------------------------------------------------------------------------+
//  | Require Once                                                              |
//  +---------------------------------------------------------------------------+
require_once($cfg['home_dir'] . '/include/config.inc.php');
require_once($cfg['home_dir'] . '/include/mysql.inc.php');
require_once($cfg['home_dir'] . '/include/globalize.inc.php');
require_once($cfg['home_dir'] . '/include/format.inc.php');



//  +---------------------------------------------------------------------------+
//  | Temp                                                                      |
//  +---------------------------------------------------------------------------+
function temp($command)
{
global $cfg;
if (!isset($cfg['secret']) || !isset($cfg['session_id'])) exit('Temp error');

$home	= $cfg['home_dir'] . '/temp';
$temp	= $cfg['home_dir'] . '/temp/' . $cfg['session_id'] . '_' . sha1($cfg['secret']);
$hidden	= $cfg['home_dir'] . '/temp/' . $cfg['session_id'] . '_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

if ($command == 'create')
	{
	if (! file_exists($home)) @mkdir($home, 0777) or message('error', '<strong>Can\'t create directory:</strong><br>' . $home);
	if (! file_exists($temp)) @mkdir($temp, 0777) or message('error', '<strong>Can\'t create directory:</strong><br>' . $hidden);
	temp('erase');
	}
elseif ($command == 'delete')
	{
	temp('erase');
	@rmdir($temp) or message('error', '<strong>Can\'t delete directory:</strong><br>' . $hidden);
	$temp = $home;
	}
elseif ($command == 'erase')
	{
	$handle = @opendir($temp) or message('error', '<strong>Can\'t open directory:</strong><br>' . $hidden);
	while($entry = readdir($handle)) 
		{
		$file = $temp . '/' . $entry;
		if ($entry != '..' && $entry != '.' && is_file($file))
			@unlink($file) or message('error', '<strong>Can\'t delete files in directory:</strong><br>' . $hidden . '/');
		}
	closedir($handle);
	}

if ($cfg['windows'])
	$temp = str_replace('/', '\\', $temp);
$cfg['temp'] = $temp;
}



//  +---------------------------------------------------------------------------+
//  | Authenticate                                                              |
//  +---------------------------------------------------------------------------+
function authenticate($access, $disable_cache = true, $refresh_sid = true)
{
global $cfg;
if ($disable_cache)
	{
	header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
	header('Pragma: no-cache');// HTTP/1.0
	}
else
	{
	header('Cache-Control: private, max-age=60, s-maxage=0'); // HTTP/1.1 private cache only
	}

if (get('authenticate') == 'logout')
	{
	login();
	}
elseif (post('username'))
	{
	$sid		= cookie('netjukebox_sid');
	$username	= post('username');
	$hash		= post('password');
	
	$query		= mysql_query('SELECT idle_time, ip, user_agent, seed, secret, session_id FROM configuration_session WHERE sid = "' . mysql_real_escape_string($sid) . '"');
	$session	= mysql_fetch_array($query);
	
	$query		= mysql_query('SELECT user_id, password FROM configuration_users WHERE username = "' . mysql_real_escape_string($username) . '"');
	$users		= mysql_fetch_array($query);
	$user_id	= $users['user_id'];
	
	if (	$hash					== hmacsha1($users['password'], $session['seed']) &&
			$session['ip']			== $_SERVER['REMOTE_ADDR'] &&
			$session['user_agent']	== $_SERVER['HTTP_USER_AGENT'] &&
			$users['password']		!= '' &&
			$users['password']		!= 'd41d8cd98f00b204e9800998ecf8427e')
		{
		if (time() > $session['idle_time'] + 3600)	$add_visit = 1;
		else										$add_visit = 0;
		
		$sid = random('40hex');
		setcookie('netjukebox_sid', $sid, $cfg['cookie_lifetime'], '');
		flush();
		
		mysql_query('UPDATE configuration_session SET
					logged_in		= "1",
					user_id			= "' . $user_id . '",
					login_time		= "' . time() . '",
					idle_time		= "' . time() . '",
					sid				= "' . $sid . '",
					visit_counter	= visit_counter + ' . $add_visit . ',
					valid_counter	= valid_counter + 1
					WHERE sid		= "' . mysql_real_escape_string(cookie('netjukebox_sid')) . '"');
		}
	else
		{
		login(1);
		}
	}
else
	{
	$sid = cookie('netjukebox_sid');
	
	$query			= mysql_query('SELECT logged_in, user_id, idle_time, ip, user_agent, seed, secret, session_id FROM configuration_session WHERE sid = "' . mysql_real_escape_string($sid) . '"');
	$session		= mysql_fetch_array($query);
	$user_id		= $session['user_id'];
	
	if ($session['logged_in'] &&
		$session['ip']			== $_SERVER['REMOTE_ADDR'] &&
		$session['user_agent']	== $_SERVER['HTTP_USER_AGENT'] &&
		$session['idle_time'] + $cfg['authenticate_expire'] > time())
		{
		if (time() > $session['idle_time'] + 3600)	$add_visit = 1;
		else										$add_visit = 0;
		
		if ($refresh_sid && time() > $session['idle_time'] + 60)
			{
			$sid = random('40hex');
			setcookie('netjukebox_sid', $sid, $cfg['cookie_lifetime'], '');
			flush();
			}
		
		mysql_query('UPDATE configuration_session SET
					idle_time		= "' . time() . '",
					sid				= "' . mysql_real_escape_string($sid) . '",
					visit_counter	= visit_counter + ' . $add_visit . ',
					valid_counter	= valid_counter + 1
					WHERE sid = "' . mysql_real_escape_string(cookie('netjukebox_sid')) . '"');
		}
	else
		{
		login();
		}
	}

// Username & user privalages
unset($cfg['username']);
$query = mysql_query('SELECT username,
					access_browse,
					access_favorites,
					access_cover,
					access_stream,
					access_download,
					access_playlist,
					access_play,
					access_add,
					access_record,
					access_config
					FROM configuration_users
					WHERE user_id = "' . $user_id . '"');
$cfg += mysql_fetch_array($query);

if (!$cfg[$access])
	{
	message('warning', '<strong>You have no privilege to access this page</strong>
	<ul class="compact"><li><a href="index.php?menu=browse&amp;authenticate=logout" target="_top">Login as another user</a></li></ul>');
	}

$cfg['secret']			= $session['secret'];
$cfg['seed']			= $session['seed'];
$cfg['session_id']		= $session['session_id'];
}



//  +---------------------------------------------------------------------------+
//  | Login                                                                     |
//  +---------------------------------------------------------------------------+
function login($add_failed = 0)
{
global $cfg;

$query = mysql_query('SELECT sid FROM configuration_session WHERE sid = "' . mysql_real_escape_string(cookie('netjukebox_sid')) . '"');
if (mysql_fetch_array($query) != '')
	{
	// Update current session
	mysql_query('UPDATE configuration_session SET
				logged_in		= "0",
				failed_counter	= failed_counter + ' . $add_failed . ',
				ip				= "' . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . '",
				user_agent		= "' . mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']) . '",
				seed			= "' . random('32base64') . '",
				secret			= "' . random('32base64') . '"
				WHERE sid		= "' . mysql_real_escape_string(cookie('netjukebox_sid')) . '"');
	}
else
	{
	// Create new session
	$sid = random('40hex');
	setcookie('netjukebox_sid', $sid, $cfg['cookie_lifetime'], '');
	flush();
	
	mysql_query('INSERT INTO configuration_session (logged_in, create_time, ip, user_agent, sid, seed, secret) VALUES (
				"0",
				"' . time() . '",
				"' . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . '",
				"' . mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']) . '",
				"' . $sid . '",
				"' . random('32base64') . '",
				"' . random('32base64') . '")');
	}

// Delete sessions never used for 1 hour
mysql_query('DELETE FROM configuration_session WHERE valid_counter = 0 AND failed_counter = 0 AND create_time + 3600 < ' . time() );

// Delete sessions idle for more than 98 days (delete 1 week)
$query = mysql_query('SELECT sid FROM configuration_session WHERE idle_time > 0 AND idle_time + 3600 * 24 * 98 < ' . time() );
if (mysql_fetch_array($query) != '')
	{
	mysql_query('DELETE FROM configuration_session WHERE idle_time > 0 AND idle_time + 3600 * 24 * 91 < ' . time() );
	mysql_query('OPTIMIZE TABLE configuration_session');
	}
?>
<form action="login.php" method="post" name="AutoPost" id="AutoPost" target="main">
	<input type="hidden" name="netjukebox_version" value="<?php echo $cfg['netjukebox_version']; ?>">
	<noscript>
	Javascript is required for netjukebox.<hr>
	<input type="submit" value="Continue">
	</noscript>
</form>

<script type="text/javascript">
	<!--
	window.onload=function(){document.AutoPost.submit()};
	document.AutoPost.submit();
	//-->
</script>
<?php
exit();
}



//  +---------------------------------------------------------------------------+
//  | Message: Ok / Warning / Error                                             |
//  +---------------------------------------------------------------------------+
function message($type, $message)
{
global $cfg;
?>
<form action="message.php" method="post" name="AutoPost" id="AutoPost" target="main">
	<input type="hidden" name="type" value="<?php echo $type; ?>">
	<input type="hidden" name="message" value="<?php echo htmlentities($message); ?>">
	<input type="hidden" name="username" value="<?php echo htmlentities($cfg['username']); ?>">
	<input type="hidden" name="netjukebox_version" value="<?php echo $cfg['netjukebox_version']; ?>">
	<noscript>
	Javascript is required for netjukebox.<hr>
	<input type="submit" value="Continue">
	</noscript>
</form>

<script type="text/javascript">
	<!--
	window.onload=function(){document.AutoPost.submit()};
	document.AutoPost.submit();
	//-->
</script>
<?php
exit();
}
?>
