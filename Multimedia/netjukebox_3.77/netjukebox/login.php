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
//  | login.php                                                                 |
//  +---------------------------------------------------------------------------+
list($usec, $sec) 			= explode(' ', microtime());
$cfg['start_time']			= (float)$usec + (float)$sec;
$cfg['header']		 		= 'align';

header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');// HTTP/1.0

require_once('include/config.inc.php');
require_once('include/mysql.inc.php');
require_once('include/format.inc.php');
require_once('include/globalize.inc.php');

$cfg['netjukebox_version']	= post('netjukebox_version');	//required for footer



//  +---------------------------------------------------------------------------+
//  | Delete deprecated cookies                                                 |
//  +---------------------------------------------------------------------------+
if	(cookie('netjukebox_username') || cookie('netjukebox_hash'))
	{
	// delete netjukebox 3.75 and older cookies
	setcookie('netjukebox_username', '', $cfg['cookie_expire'], '');
	setcookie('netjukebox_hash', '', $cfg['cookie_expire'], '');
	setcookie('netjukebox_expire', '', $cfg['cookie_expire'], '');
	setcookie('netjukebox_httpq_id', '', $cfg['cookie_expire'], '');
	// delete netjukebox 3.68 and older cookies
	setcookie('httpq_host', '', $cfg['cookie_expire'], '');
	setcookie('httpq_port', '', $cfg['cookie_expire'], '');
	setcookie('httpq_pass', '', $cfg['cookie_expire'], '');
	setcookie('media_share', '', $cfg['cookie_expire'], '');
	}



//  +---------------------------------------------------------------------------+
//  | Login                                                                     |
//  +---------------------------------------------------------------------------+
$query				= $query = mysql_query('SELECT seed FROM configuration_session WHERE sid = "' . mysql_real_escape_string(cookie('netjukebox_sid')) . '"');
$seed				= @mysql_result($query, 'seed');

$query				= mysql_query('SELECT password FROM configuration_users WHERE username = "' . $cfg['authenticate_anonymous_user'] . '"');
$anonymous_password	= @mysql_result($query, 'password');

require_once('include/header.inc.php');
?>
<script src="javascript/md5.js" type="text/javascript"></script>
<script src="javascript/sha1.js" type="text/javascript"></script>
<script type="text/javascript">
	<!--
	function login(form)
	{
	if (form['username'].value == '<?php echo $cfg['authenticate_anonymous_user']; ?>' && form['password'].value == '')
		form['password'].value = '<?php echo hmacsha1($anonymous_password, $seed) ?>';
	else
		form['password'].value = hmacsha1(md5(form['password'].value), '<?php echo $seed; ?>');
	return true;
	}
	//-->
</script>

<form action="browse.php" method="post" name="loginform" target="main" onSubmit="return login(this);">
<table cellspacing="0" cellpadding="0" class="warning">
<tr><td height="10" colspan="4"></td></tr>
<tr>
	<td width="10"></td>
	<td width="75">Username</td>
	<td align="right"><input type="text" name="username" <?php if (isset($anonymous_password)) echo 'value="' . $cfg['authenticate_anonymous_user'] . '" '; ?>maxlength="255" style="width: 150px;"></td>
	<td width="10"></td>
</tr>
<tr>
	<td></td>
	<td>Password</td>
	<td align="right"><input type="password" name="password" style="width: 150px;"></td>
	<td></td>
</tr>
<tr><td height="10" colspan="4"></td></tr>
<tr>
	<td></td>
	<td colspan="2" align="right"><input type="submit" value="login" class="submit_login"></td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td colspan="2"><hr class="dark"><font class="xs">Cookies and Javascript are required to login.<br>
	The password will be hashed with a key before transmitting.</font></td>
	<td></td>
</tr>
<tr><td height="10" colspan="4"></td></tr>
</table>
</form>

<script type="text/javascript">
	<!--
	document.loginform.username.focus();
	document.loginform.username.select();
	//-->
</script>
<?php
require_once('include/footer.inc.php');
?>
