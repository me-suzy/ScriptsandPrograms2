<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_users
{

function manage($row) {
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
<tr>
<td>{$row['id']} - {$row['username']}</td>
<td> [ <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_users&amp;item=4&amp;userid={$row['id']}">Edit User</a> - <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_users&amp;item=6&amp;userid={$row['id']}">Delete User</a> ] </td>
</tr>
EOT;

return $CMSHTML;
}

function manage_top() {

$CMSHTML .= <<<EOT
<table style="width: 80%; padding: 3px; margin: auto 0 auto 0;">
EOT;

return $CMSHTML;
}

function manage_bottom() {
global $info;
$sesid = $_GET['ses'];

$CMSHTML .= <<<EOT
</table>

<p><a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_users&amp;item=2">Add User</a></p>

EOT;

return $CMSHTML;
}

function add_user() {
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT

<table style="width: 80%; padding: 3px; margin: auto 0 auto 0;">
<form name="adduser" id="adduser" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_users&amp;item=3" method="POST">
<tr>
<td>username:</td>
<td><input type="textfield" name="username" /> </td>
</tr>
<tr>
<td>Password:</td>
<td><input type="password" name="password" /> </td>
</tr>
<tr>
<td>Confirm Password:</td>
<td><input type="password" name="password_check" /> </td>
</tr>
<tr>
<td>Email Address:</td>
<td><input type="text" name="email" /> </td>
</tr>
<tr>
<td>admin?:</td>
<td><input type="checkbox" name="admin" /> </td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Submit" /></td>
</form>
EOT;

return $CMSHTML;

}

function edit_user($row = '', $admin) {
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT

<table style="width: 80%; padding: 3px; margin: auto 0 auto 0;">
<form name="edituser" id="edituser" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_users&amp;item=5" method="POST">
<input type="hidden" name="userid" value="{$row['id']}"
<tr>
<td>username:</td>
<td><input type="textfield" name="username" value="{$row['username']}" /> </td>
</tr>
<tr>
<td>Password:</td>
<td><input type="password" name="password" /> (Leave blank to stay the same) </td>
</tr>
<tr>
<td>Email Address:</td>
<td><input type="text" name="email" value="{$row['email']}" /> </td>
</tr>
<tr>
<td>admin?:</td>
<td>
EOT;

if ($admin == 1)
{
$CMSHTML .= <<<EOT
<input type="checkbox" name="admin" checked /> </td>
EOT;
}

else
{
$CMSHTML .= <<<EOT
<input type="checkbox" name="admin" /> </td>
EOT;
}

$CMSHTML .= <<<EOT
</tr>
<tr>
<td colspan="2"><input type="submit" value="Submit" /></td>
</form>
EOT;

return $CMSHTML;

}

function reg_complete() {

$CMSHTML = <<<EOT
User added
EOT;

return $CMSHTML;
}

function edit_complete() {

$CMSHTML = <<<EOT
User edited.
EOT;

return $CMSHTML;
}

function delete_complete() {

$CMSHTML = <<<EOT
The user was successfully deleted.
EOT;

return $CMSHTML;
}


}
?>