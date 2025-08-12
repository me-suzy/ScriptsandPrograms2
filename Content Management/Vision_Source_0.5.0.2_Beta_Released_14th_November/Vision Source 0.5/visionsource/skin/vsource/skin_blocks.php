<?php
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 5th August 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: skin_blocks.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_blocks {

function login_ismem($name) {
global $info;

$CMSHTML = <<<EOT
<div class="title">Welcome, {$name} </div>
      <div class="text"> Here you can change your details.<br />
        <br />
		<span class="navdot">.:</span> <a href="{$info['base_url']}/index.php?id=ucp">User Control Panel</a> <br />
        <span class="navdot">.:</span> <a href="{$info['base_url']}/index.php?id=ucp&amp;do=4">Change Password</a> <br />
        <span class="navdot">.:</span> <a href="{$info['base_url']}/index.php?id=ucp&amp;do=2">Change Settings</a> <br />
		<span class="navdot">.:</span> <a href="{$info['base_url']}/index.php?id=login&amp;do=3">Logout</a>
      </div>
	  <div class="text_bottom">&nbsp;</div>

EOT;

return $CMSHTML;
 }
 
function login_notmem() {
global $info;

$CMSHTML = <<<EOT
<div class="title">Welcome, Guest </div>
      <div class="text"> You Can login here.<br />
        <br />
<form name="login" id="login" method="post" action="{$info['base_url']}/index.php?id=login&do=4">
Username: <br />
<input type="textfield" name="username" /> <br />
Password: <br />
<input type="password" name="password" />
<p>
<input type="submit" value="Submit" />
</p> <p>
<a href="{$info['base_url']}/index.php?id=login&amp;do=2">Click here to register!</a>
</p>
</form>
      </div>
	  <div class="text_bottom">&nbsp;</div>

EOT;

/* Table
$CMSHTML = <<<EOT
<div class="title">Welcome, Guest </div>
      <div class="text"> You Can login here.<br />
        <br />
<form name="login" id="login" method="post" action="{$info['base_url']}/index.php?id=login&do=4">
<table cellspacing="0" cellpadding="0" width="10%">
<tr>
<td>Username:</td>
<td><input type="textfield" name="username" /> </td>
</tr>
<tr>
<td>Password:</td>
<td><input type="password" name="password" /> </td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Submit" /> <p>
<a href="{$info['base_url']}/index.php?id=login&amp;do=2">Click here to register!</a>
</p></td>
</tr>
</table>
</form>
      </div>
	  <div class="text_bottom">&nbsp;</div>

EOT;*/

return $CMSHTML;
 }

function search()
{
$CMSHTML = <<<EOT
 <div class="title">Search</div>
      <div class="text"> <form> 
        <div align="center"><br />
	        <input name="textfield" type="text" value="Enter keywords"> 
	        <br /> 
	        <input type="submit" name="Submit2" value="Search">
        </div>
      </form> </div>
	  <div class="text_bottom">&nbsp;</div>
EOT;

return $CMSHTML;

}
}
?>
