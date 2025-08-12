<?

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 11th April 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: skin_ucp.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_ucp {

function welcome()
{
global $info;

$CMSHTML = <<<EOT
Welcome to your User Control Panel. Here you can edit all your settings.

<p><a href="{$info['base_url']}/index.php?id=ucp&amp;do=4">Click here to change your password</a> <br />
<a href="{$info['base_url']}/index.php?id=ucp&amp;do=2">Click here to change your settings</a> </p>

EOT;

return $CMSHTML;
}

function edit_form($row = '')
{
global $db, $info;

$CMSHTML  = "";
$CMSHTML .= <<<EOT
<table>
<tr>
<form name='edit_profile' id='edit_profile' action="{$info['base_url']}/index.php?id=ucp&amp;do=3" method='POST'>
<td>Your email address:</td>
<td><input type="text" name="email" value="{$row['email']}" /></td>
</tr>
<tr>
<td>Your Skin: </td>
<td><select name="skin_selector">
EOT;

$db->query('SELECT * FROM vsource_skin WHERE view="1"');
while ($s = $db->fetchrow())
{
$CMSHTML .= <<<EOT
<option value="{$s['directory']}">{$s['name']}</option>
EOT;
}

$CMSHTML .= <<<EOT
</tr>
<tr>
<td colspan="2"><input type="submit" value="Submit" /></td>
</tr>
</form>
</table>

EOT;

return $CMSHTML;
}

function changepass()
{
global $info;

$CMSHTML = <<<EOT
<table>
<tr>
<form name='change_pass' id='change_pass' action="{$info['base_url']}/index.php?id=ucp&amp;do=5" method='POST'>
<td>Old Password:</td>
<td><input type="password" name="old_password" /></td>
</tr>
<tr>
<td>New Password: </td>
<td><input type="password" name="new_password1" /></td>
</tr>
<tr>
<td>Confirm Password:</td>
<td><input type="password" name="new_password2" /></td>
<tr>
<td colspan="2"><input type="submit" value="Submit" /></td>
</tr>
</form>
</table>
EOT;

return $CMSHTML;

}

function error($errormsg)
{

$CMSHTML = <<<EOT
Im sorry, there was an error. The error was: $errormsg

<p><a href="javascript:back(-1)">Please click here to go back</a></p>

EOT;

return $CMSHTML;
}


}


?>
