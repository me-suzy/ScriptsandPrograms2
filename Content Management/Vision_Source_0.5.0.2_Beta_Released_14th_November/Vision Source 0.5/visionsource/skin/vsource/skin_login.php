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
//		Script: skin_test.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_login {

//////////////////////////////////
//	Main header thing for skin
/////////////////////////////////

  
function home() {

$CMSHTML = <<<EOT
 <p>
Please sign in:
 </p>
EOT;

return $CMSHTML;
}

function welcome() {
global $info;

$CMSHTML = <<<EOT
You are already loged in. To continue to MyCP Please click <a href="{$info['base_url']}/index.php?id=ucp">here</a>.
EOT;

return $CMSHTML;

}

function login() {
global $info;

$CMSHTML = <<<EOT
<form name="login" id="login" method="post" action="{$info['base_url']}/index.php?id=login&do=4">
username: <input type="textfield" name="username" /> <br />
password: <input type="password" name="password" /> <br />
<input type="submit" value="Submit" />
</form>
EOT;

return $CMSHTML;
}

function regtop() {
global $info;

$CMSHTML = <<<EOT
<form action="{$info['base_url']}/index.php?id=login&do=5" method="post">
<div class="title">Register</div>
<div class="text_main">
<table>
<tr>
<td>Username:</td>
<td><input type="text" name="username" /></td>
</tr>
<tr>
<td>Password:</td>
<td><input type="password" name="password" /></td>
</tr>
<tr>
<td>Password again:</td>
<td><input type="password" name="password_check" /></td>
</tr>
<tr>
<td>Email address:</td>
<td><input type="text" name="email" /></td>
</tr>
<tr>
<td>Email address again:</td>
<td><input type="text" name="email_check" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Submit" /></td>
</tr>
</table>
</div>
	  <div class="text_bottom">&nbsp;</div>
</form>
EOT;

return $CMSHTML;

}  

function logout() {
global $info;	

$CMSHTML = <<<EOT
<form name="login" id="login" method="post" action="{$info['base_url']}/index.php?id=login&do=3">
<input type="hidden" value="logout" name="logout" />
Are you sure you wish to logout?
<input type="submit" value="Yes" />
</form>
EOT;

return $CMSHTML;

}

function custerror($errormsg) {

$CMSHTML = <<<EOT
Im sorry, there was an error, the error was: $errormsg <br />
<a href="javascript:history.go(-1)">Go back</a>.
EOT;

return $CMSHTML;
}

function reg_complete() {
global $info;

$CMSHTML = <<<EOT
You have succesfully registered. To proceed, please login by clicking <a href="{$info['base_url']}/index.php?id=login">here</a>.

EOT;

return $CMSHTML;
}

}
?>