<?

$GLOBALS['instructions'] = "Please enter your database connection information here.&nbsp; If you are unsure about what to use or experience difficulty, contact your network administrator/site hosts.";
$GLOBALS['stage'] = "select_db";

	if ($_COOKIE['ck_csv']['serverName'] != "") $serverName = $_COOKIE['ck_csv']['serverName'];
	if ($_COOKIE['ck_csv']['username'] != "") $username = $_COOKIE['ck_csv']['username'];
	if ($_COOKIE['ck_csv']['password'] != "") $password = $_COOKIE['ck_csv']['password'];

$GLOBALS['display_block'] = '
	<tr>
		<td width="20%">Server name:</td>
		<td width="80%"><input type="text" name="serverName" value="' . $serverName . '"></td>
	</tr>
	<tr>
		<td>Username:</td>
		<td><input type="username" name="username" value="' . $username . '"></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="password" value="' . $password . '"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr class="sectionHeader">
		<td colspan="2"><input type="checkbox" name="saveConnection" ' . SaveConnection($_COOKIE['ck_csv']['serverName'], $_COOKIE['ck_csv']['username'], $_COOKIE['ck_csv']['password']) . '>&nbsp;Always use this connection.</td>
	</tr>' . Repost();

?>