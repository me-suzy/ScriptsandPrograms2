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



//  +---------------------------------------------------------------------------+
//  | users.php                                                                 |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
require_once('include/des.inc.php');
require_once('include/httpq.inc.php'); // for $cfg('httpq_id');

$command	= GetPost('command');
$user_id	= GetPost('user_id');

if ($command == 'SetStreamProfile')	{SetStreamProfile(); $command = 'UserMenu';}
if ($command == 'SetHttpqProfile')	{SetHttpqProfile(); $command = 'UserMenu';}
if ($command == 'UserMenu')			UserMenu();
if ($command == 'online')			online();

if ($command == 'update')			{update($user_id); $command = '';}
if ($command == 'logout')			{logout($user_id); $command = '';}
if ($command == 'delete')			{del($user_id); $command = '';}
if ($command == 'edit')				edit($user_id);
if ($command == '')					home();

exit();



//  +---------------------------------------------------------------------------+
//  | Home                                                                      |
//  +---------------------------------------------------------------------------+
function home()
{
global $cfg;
authenticate('access_config');
require_once('include/header.inc.php');

//FormattedNavigator
$nav_name	= array('Configuration');
$nav_url	= array('config.php');
$nav_name[]	= 'Users';
FormattedNavigator($nav_url, $nav_name);
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td width="100">Username</td>
	<td width="60" align="center">Browse</td>
	<td width="60" align="center">Favorites</td>
	<td width="60" align="center">Playlist</td>
	<td width="60" align="center">Play</td>
	<td width="60" align="center">Add</td>
	<td width="60" align="center">Stream</td>
	<td width="60" align="center">Download</td>
	<td width="60" align="center">Cover</td>
	<td width="60" align="center">Record</td>
	<td width="60" align="center">Config</td>
	<td class="spacer"></td>
	<td></td>
	<td><a href="users.php?command=edit&amp;user_id=0" onMouseOver="return overlib('Add a new user');" onMouseOut="return nd();"><img src="<?php echo $cfg['img']; ?>/small_edit_add.gif" alt="" width="21" height="21" border="0"></a></td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="16"></td></tr>
<?php
$i=0;
$query = mysql_query('SELECT username, access_browse, access_cover, access_stream, access_playlist, access_play, access_add, access_record, access_download, access_favorites, access_config, user_id FROM configuration_users ORDER BY username');
while ($users = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="users.php?command=edit&amp;user_id=<?php echo $users['user_id']; ?>"><img src="<?php echo $cfg['img']; ?>/small_users.gif" alt="" width="21" height="21" border="0" class="space"><?php echo htmlentities($users['username']); ?></a></td>
	<td align="center"><?php if ($users['access_browse']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_favorites']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_playlist']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_play']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_add']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_stream']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_download']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_cover']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_record']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td align="center"><?php if ($users['access_config']) echo '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0">'; ?></td>
	<td></td>
	<td><a href="users.php?command=logout&amp;user_id=<?php echo $users['user_id']; ?>" target="main" onClick="return confirm('Logout all sessions from user: <?php echo htmlentities($users['username']); ?>?');" onMouseOver="return overlib('Logout');" onMouseOut="return nd();"><img src="<?php echo $cfg['img']; ?>/small_logout.gif" alt="" width="21" height="21" border="0"></a></td>
	<td><a href="users.php?command=delete&amp;user_id=<?php echo $users['user_id']; ?>" target="main" onClick="return confirm('Are you sure you want to delete user: <?php echo htmlentities($users['username']); ?>?');" onMouseOver="return overlib('Delete');" onMouseOut="return nd();"><img src="<?php echo $cfg['img']; ?>/small_delete.gif" alt="" width="21" height="21" border="0"></a></td>
	<td></td>
</tr>
<?php
	}
echo '</table>' . "\n";
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | User Menu                                                                 |
//  +---------------------------------------------------------------------------+
function UserMenu()
{
global $cfg;
authenticate('access_browse');
require_once('include/header.inc.php');

//FormattedNavigator
$nav_name	= array('User: ' . $cfg['username']);
$nav_url	= array('');
FormattedNavigator($nav_url, $nav_name);

$check = '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0" class="space">';
$uncheck = '<img src="images/dummy.gif" alt="" width="21" height="21" border="0" class="space">';
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Access</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="3"></td></tr>
<tr class="odd">
	<td></td>
	<td><?php if ($cfg['access_browse']) echo $check; else echo $uncheck; ?>Browse</td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td><?php if ($cfg['access_favorites']) echo $check; else echo $uncheck; ?>Favorites</td>
	<td></td>
</tr>
<tr class="odd">
	<td></td>
	<td><?php if ($cfg['access_playlist']) echo $check; else echo $uncheck; ?>Playlist</td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td><?php if ($cfg['access_play']) echo $check; else echo $uncheck; ?>Play</td>
	<td></td>
</tr>
<tr class="odd">
	<td></td>
	<td><?php if ($cfg['access_add']) echo $check; else echo $uncheck; ?>Add</td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td><?php if ($cfg['access_stream']) echo $check; else echo $uncheck; ?>Stream</td>
	<td></td>
</tr>
<tr class="odd">
	<td></td>
	<td><?php if ($cfg['access_download']) echo $check; else echo $uncheck; ?>Download</td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td><?php if ($cfg['access_cover']) echo $check; else echo $uncheck; ?>Cover</td>
	<td></td>
</tr>
<tr class="odd">
	<td></td>
	<td><?php if ($cfg['access_record']) echo $check; else echo $uncheck; ?>Record</td>
	<td></td>
</tr>
<tr class="even">
	<td></td>
	<td><?php if ($cfg['access_config']) echo $check; else echo $uncheck; ?>Config</td>
	<td></td>
</tr>
<?php
if ($cfg['access_stream'])
	{
	$query		= mysql_query('SELECT stream_id FROM configuration_session WHERE session_id = "' . $cfg['session_id'] . '"');
	$str_id		= @mysql_result($query, 'stream_id');
?>
<tr class="line"><td colspan="3"></td></tr>
<tr class="header">
	<td></td>
	<td>Stream profile</td>
	<td></td>
</tr>
<tr class="line"><td colspan="3"></td></tr>
<?php
	$i = 0;
	foreach($cfg['stream_name'] as $stream_id => $value)
		{
?>
<tr class="<?php if ($stream_id == $str_id) echo 'select'; else echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="users.php?command=SetStreamProfile&amp;stream_id=<?php echo $stream_id; ?>"><img src="<?php echo $cfg['img']; ?>/small_stream.gif" alt="" width="21" height="21" border="0" class="space"><?php echo $value; ?></a></td>
	<td></td>
</tr>
<?php
		}
?>
<tr class="<?php if (-1 == $str_id) echo 'select'; else echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="users.php?command=SetStreamProfile&amp;stream_id=-1"><img src="<?php echo $cfg['img']; ?>/small_stream.gif" alt="" width="21" height="21" border="0" class="space">Source</a></td>
	<td></td>
</tr>

<?php
	}
if ($cfg['access_play'])
	{
?>
<tr class="line"><td colspan="3"></td></tr>
<tr class="header">
	<td></td>
	<td>httpQ profile</td>
	<td></td>
</tr>
<tr class="line"><td colspan="3"></td></tr>
<?php
	$i = 0;
	$query = mysql_query('select httpq_id, name FROM configuration_httpq ORDER BY name');
	while ($client = mysql_fetch_array($query))
		{
?>
<tr class="<?php if ($client['httpq_id'] == $cfg['httpq_id']) echo 'select'; else echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="users.php?command=SetHttpqProfile&amp;httpq_id=<?php echo $client['httpq_id']; ?>"><img src="<?php echo $cfg['img']; ?>/small_favorites.gif" alt="" width="21" height="21" border="0" class="space"><?php echo $client['name']; ?></a></td>
	<td></td>
</tr>
<?php
		}
	}
?>
</table>
<br>
<a href="index.php?menu=browse&amp;authenticate=logout" target="_top"><img src="<?php echo $cfg['img']; ?>/button_logout.gif" alt="" width="106" height="26" border="0"></a>
<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Online                                                                    |
//  +---------------------------------------------------------------------------+
function online()
{
global $cfg;
authenticate('access_config');
require_once('include/header.inc.php');

//FormattedNavigator
$nav_name	= array('Configuration');
$nav_url	= array('config.php');
$nav_name[]	= 'Online';
FormattedNavigator($nav_url, $nav_name);

$check = '<img src="' . $cfg['img'] . '/small_check.gif" alt="" width="21" height="21" border="0" class="space">';
$uncheck = '<img src="images/dummy.gif" alt="" width="21" height="21" border="0" class="space">';
?>
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>User</td>
	<td class="textspace"></td>
	<td>Visits</td>
	<td class="textspace"></td>
	<td>Hits</td>
	<td class="textspace"></td>
	<td>Failed</td>
	<td class="textspace"></td>
	<td>Ip</td>
	<td class="textspace"></td>
	<td align="right">Idle</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="13"></td></tr>
<?php
$i = 0;
$time = time();
$query = mysql_query('SELECT logged_in, valid_counter, failed_counter, visit_counter, idle_time, ip,
					configuration_users.username,
					configuration_users.user_id
					FROM configuration_session, configuration_users 
					WHERE idle_time + 3600 * 24 > ' . $time . '
					AND configuration_session.user_id = configuration_users.user_id
					ORDER BY idle_time DESC');
while ($session = mysql_fetch_array($query))
	{
?>
<tr class="<?php echo ($i++ & 1) ? 'even' : 'odd'; ?>">
	<td></td>
	<td><a href="users.php?command=edit&user_id=<?php echo $session['user_id'];?>"><img src="<?php echo $cfg['img']; ?>/small_<?php if ($session['logged_in']) echo 'login'; else echo 'logout'; ?>.gif" alt="" width="21" height="21" border="0" class="space"><?php echo htmlentities($session['username']); ?></a></td>	
	<td></td>
	<td><?php echo $session['visit_counter']; ?></td>	
	<td></td>
	<td><?php echo $session['valid_counter']; ?></td>
	<td></td>
	<td><?php echo $session['failed_counter']; ?></td>
	<td></td>
	<td><a href="http://www.dnsstuff.com/tools/ptr.ch?ip=<?php echo $session['ip']; ?>" target="_blank"><?php echo $session['ip']; ?></a></td>
	<td></td>
	<td align="right"><?php echo FormattedTime(($time - $session['idle_time']) * 1000); ?></td>
	<td></td>
</tr>
<?php
	}
$query = mysql_query('SELECT create_time
					FROM configuration_session
					WHERE 1
					ORDER BY create_time');
$create_time = @mysql_result($query, 'create_time');
?>
<tr class="line"><td colspan="13"></td></tr>
<tr class="footer">
	<td class="spacer"></td>
	<td colspan="11">Visits, hits and failed count since: <?php echo date('r', $create_time); ?></td>
	<td class="spacer"></td>
</tr>
</table>
<br>
<a href="users.php?command=online"><img src="<?php echo $cfg['img']; ?>/button_refresh.gif" alt="" width="106" height="26" border="0"></a>
<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Set Stream Profile                                                        |
//  +---------------------------------------------------------------------------+
function SetStreamProfile()
{
global $cfg;
authenticate('access_stream', true, false);
$stream_id = get('stream_id');

mysql_query('UPDATE configuration_session
			SET stream_id		= "' . mysql_real_escape_string($stream_id) . '"
			WHERE session_id	= "' . mysql_real_escape_string($cfg['session_id']) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Set httpQ Profile                                                         |
//  +---------------------------------------------------------------------------+
function SetHttpqProfile()
{
global $cfg;
authenticate('access_play', true, false);
$cfg['httpq_id'] = get('httpq_id');

mysql_query('UPDATE configuration_session
			SET httpq_id		= "' . mysql_real_escape_string($cfg['httpq_id']) . '"
			WHERE session_id	= "' . mysql_real_escape_string($cfg['session_id']) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Edit                                                                      |
//  +---------------------------------------------------------------------------+
function edit($user_id)
{
global $cfg;
authenticate('access_config');
require_once('include/header.inc.php');

if ($user_id == '0') // Add configuraton
	{
	$username			= 'user_' . str_pad(base_convert(rand(0, 46655), 10, 36), 3, '0', STR_PAD_LEFT); // 46655 = zzz
	$txt_menu			= 'Add user';
	$txt_password		= 'Password';
	$users 				= array();
	}
else // Edit configutaion
	{
	$query = mysql_query('SELECT
						username,
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
						WHERE user_id = "' . mysql_real_escape_string($user_id) . '"');
	$users = mysql_fetch_array($query);
	$username			= $users['username'];
	$txt_menu			= 'Edit user';
	$txt_password		= 'New password';
	}

//FormattedNavigator
$nav_name	= array('Configuration');
$nav_url	= array('config.php');
$nav_name[]	= 'Users';
$nav_url[]	= 'users.php';
$nav_name[]	= $txt_menu;
FormattedNavigator($nav_url, $nav_name);

$seed = random('32base64');
mysql_query('UPDATE configuration_session SET seed = "' . $seed . '" WHERE session_id = "' . $cfg['session_id'] . '"');
?>
<script src="javascript/md5.js" type="text/javascript"></script>
<script src="javascript/des.js" type="text/javascript"></script>
<script src="javascript/base64.js" type="text/javascript"></script>
<script type="text/javascript">
	<!--
	function login(form)
	{
	if (form['new_password'].value == '') {form['error'].value = 'password_not_set';}
	if (form['new_password'].value != form['retype_password'].value) {form['error'].value = 'password_not_identical';}
	form['ciphertext'].value = base64_encode(des(base64_decode('<?php echo $seed; ?>'), md5(form['new_password'].value), 1, 0));
	form['new_password'].value = '';
	form['retype_password'].value = '';
	return true;
	}
	//-->
</script>

<form action="users.php" method="post" onSubmit="return login(this);">
	<input type="hidden" name="command" value="update">
	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
	<input type="hidden" name="ciphertext" value="">
	<input type="hidden" name="error" value="">
<table border="0" cellspacing="0" cellpadding="0" class="border">
<tr class="header">
	<td class="spacer"></td>
	<td>Access</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="4"></td></tr>
<tr class="odd">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_browse" value="1" class="space"<?php if (isset($users['access_browse']) && $users['access_browse']) echo ' checked'; ?>>Browse</td>
	<td class="spacer"></td>
</tr>
<tr class="even">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_favorites" value="1" class="space"<?php if (isset($users['access_favorites']) && $users['access_favorites']) echo ' checked'; ?>>Favorites</td>
	<td class="spacer"></td>
</tr>
<tr class="odd">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_playlist" value="1" class="space"<?php if (isset($users['access_playlist']) && $users['access_playlist']) echo ' checked'; ?>>Playlist</td>
	<td class="spacer"></td>
</tr>
<tr class="even">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_play" value="1" class="space"<?php if (isset($users['access_play']) && $users['access_play']) echo ' checked'; ?>>Play</td>
	<td class="spacer"></td>
</tr>
<tr class="odd">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_add" value="1" class="space"<?php if (isset($users['access_add']) && $users['access_add']) echo ' checked'; ?>>Add</td>
	<td class="spacer"></td>
</tr>
<tr class="even">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_stream" value="1" class="space"<?php if (isset($users['access_stream']) && $users['access_stream']) echo ' checked'; ?>>Stream</td>
	<td class="spacer"></td>
</tr>
<tr class="odd">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_download" value="1" class="space"<?php if (isset($users['access_download']) && $users['access_download']) echo ' checked'; ?>>Download</td>
	<td class="spacer"></td>
</tr>
<tr class="even">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_cover" value="1" class="space"<?php if (isset($users['access_cover']) && $users['access_cover']) echo ' checked'; ?>>Cover</td>
	<td class="spacer"></td>
</tr>
<tr class="odd">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_record" value="1" class="space"<?php if (isset($users['access_record']) && $users['access_record']) echo ' checked'; ?>>Record</td>
	<td class="spacer"></td>
</tr>
<tr class="even">
	<td class="spacer"></td>
	<td><input type="checkbox" name="access_config" value="1" class="space"<?php if (isset($users['access_config']) && $users['access_config']) echo ' checked'; ?>>Config</td>
	<td class="spacer"></td>
</tr>
<tr class="line"><td colspan="3"></td></tr>
<tr class="footer">
	<td class="spacer"></td>
	<td>Username</td>
	<td class="spacer"></td>
</tr>
<tr class="footer">
	<td class="spacer"></td>
	<td><input type="text" name="new_username" value="<?php echo htmlentities($username); ?>" maxlength="255" style="width: 100%;"></td>
	<td class="spacer"></td>
</tr>
<tr class="footer">
	<td class="spacer"></td>
	<td><?php echo $txt_password; ?></td>
	<td class="spacer"></td>
</tr>
<tr class="footer">
	<td class="spacer"></td>
	<td><input type="password" name="new_password" style="width: 100%;"></td>
	<td class="spacer"></td>
</tr>
<tr class="footer">
	<td class="spacer"></td>
	<td>Retype password</td>
	<td class="spacer"></td>
</tr>
<tr class="footer">
	<td class="spacer"></td>
	<td><input type="password" name="retype_password" style="width: 100%;"></td>
	<td class="spacer"></td>
</tr>
<tr class="footer">
	<td class="spacer"></td>
	<td><hr class="dark"><font class="xs">The password will be encrypted<br>with triple DES before transmitting.</font></td>
	<td class="spacer"></td>
</tr>
</table>
<br>
<input type="image" src="<?php echo $cfg['img']; ?>/button_save.gif">
<a href="users.php"><img src="<?php echo $cfg['img']; ?>/button_cancel.gif" alt="" width="106" height="26" border="0"></a>
</form>
<?php
require_once('include/footer.inc.php');
}



//  +---------------------------------------------------------------------------+
//  | Update                                                                    |
//  +---------------------------------------------------------------------------+
function update($user_id)
{
global $cfg;
authenticate('access_config', true, false);

$new_username		= post('new_username');
$ciphertext			= post('ciphertext');
$access_browse		= post('access_browse');
$access_favorites	= post('access_favorites');
$access_cover		= post('access_cover');
$access_stream		= post('access_stream');
$access_download	= post('access_download');
$access_playlist	= post('access_playlist');
$access_play		= post('access_play');
$access_add			= post('access_add');
$access_record		= post('access_record');
$access_config		= post('access_config');
$error				= post('error');

if ($error == 'password_not_identical') message('warning', '<strong>Passwords are not identical</strong><ul class="compact"><li><a href="users.php?command=edit&amp;user_id='. $user_id .'">Go back</a></li></ul>');
if ($error == 'password_not_set' && $user_id == '0') message('warning', '<strong>For a new user password must be set</strong><ul class="compact"><li><a href="users.php?command=edit&amp;user_id=0">Go back</a></li></ul>');
if ($new_username == '') message('warning', '<strong>Username must be set</strong><ul class="compact"><li><a href="users.php?command=edit&amp;user_id='. $user_id .'">Go back</a></li></ul>');
if (!$access_browse OR
	!$access_playlist OR
	!$access_play OR
	!$access_add OR
	!$access_record OR
	!$access_stream OR
	!$access_cover OR
	!$access_favorites OR
	!$access_download OR
	!$access_config) CheckAdminAcount($user_id);

if ($user_id == '0')
	{
	mysql_query('INSERT INTO configuration_users (user_id) VALUES ("")');
	$user_id = mysql_insert_id();
	}

if ($error == 'password_not_set')
	{
	mysql_query('UPDATE configuration_users SET
				username		= "' . mysql_real_escape_string($new_username) . '",
				access_browse	= "' . mysql_real_escape_string($access_browse) . '",
				access_playlist = "' . mysql_real_escape_string($access_playlist) . '",
				access_play		= "' . mysql_real_escape_string($access_play) . '",
				access_add		= "' . mysql_real_escape_string($access_add) . '",
				access_record	= "' . mysql_real_escape_string($access_record) . '",
				access_stream	= "' . mysql_real_escape_string($access_stream) . '",
				access_cover	= "' . mysql_real_escape_string($access_cover) . '",
				access_favorites= "' . mysql_real_escape_string($access_favorites) . '",
				access_download = "' . mysql_real_escape_string($access_download) . '",
				access_config	= "' . mysql_real_escape_string($access_config) . '"
				WHERE user_id	= "' . mysql_real_escape_string($user_id) . '"');
	}
else
	{
	$new_password = des(base64_decode($cfg['seed']), base64_decode($ciphertext), 0, 0, null);
	
	mysql_query('UPDATE configuration_users SET
				username		= "' . mysql_real_escape_string($new_username) . '",
				password		= "' . mysql_real_escape_string($new_password) . '",
				access_browse	= "' . mysql_real_escape_string($access_browse) . '",
				access_playlist	= "' . mysql_real_escape_string($access_playlist) . '",
				access_play		= "' . mysql_real_escape_string($access_play) . '",
				access_add		= "' . mysql_real_escape_string($access_add) . '",
				access_record	= "' . mysql_real_escape_string($access_record) . '",
				access_stream	= "' . mysql_real_escape_string($access_stream) . '",
				access_cover	= "' . mysql_real_escape_string($access_cover) . '",
				access_favorites= "' . mysql_real_escape_string($access_favorites) . '",
				access_download = "' . mysql_real_escape_string($access_download) . '",
				access_config	= "' . mysql_real_escape_string($access_config) . '"
				WHERE user_id	= "' . mysql_real_escape_string($user_id) . '"');
	
	logout($user_id);
	}
}


//  +---------------------------------------------------------------------------+
//  | Logout                                                                    |
//  +---------------------------------------------------------------------------+
function logout($user_id)
{
authenticate('access_config', true, false);
mysql_query('UPDATE configuration_session SET
			logged_in = "0"
			WHERE user_id = "' . mysql_real_escape_string($user_id) . '"');
}


//  +---------------------------------------------------------------------------+
//  | Delete                                                                    |
//  +---------------------------------------------------------------------------+
function del($user_id)
{
authenticate('access_config', true, false);
CheckAdminAcount($user_id);
mysql_query('DELETE FROM configuration_users	WHERE user_id = "' . mysql_real_escape_string($user_id) . '"');
mysql_query('DELETE FROM configuration_session	WHERE user_id = "' . mysql_real_escape_string($user_id) . '"');
}



//  +---------------------------------------------------------------------------+
//  | Check Admin Acount                                                        |
//  +---------------------------------------------------------------------------+
function CheckAdminAcount($user_id)
{
$query = mysql_query('SELECT user_id 
					FROM configuration_users 
					WHERE user_id != "' . mysql_real_escape_string($user_id) . '" AND
					access_browse AND
					access_cover AND
					access_stream AND
					access_playlist AND
					access_play AND
					access_add AND
					access_record AND
					access_favorites AND
					access_download AND
					access_config');
$users = mysql_fetch_array($query);
if ($users['user_id'] == '') message('warning', '<strong>There must be at least one user with all privileges</strong><ul class="compact"><li><a href="'. $_SERVER['HTTP_REFERER'] .'">Go back</a></li></ul>');
}
?>
