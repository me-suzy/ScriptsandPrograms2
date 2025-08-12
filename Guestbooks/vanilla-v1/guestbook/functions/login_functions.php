<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: login_functions.php
*	Description: Contains all functions pertaining to logging in/out
****************************************************************************
*	Build Date: August 20, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net/
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

//Register the user a session so that they can use the VSNS
function login()
{
	$pass = $_POST["password"];
	$pass = md5($pass);

	$result = mysql_query("SELECT * FROM vanilla_config WHERE config_name='password' AND config_value='$pass'");

	if ($result && mysql_num_rows($result) > 0)
	{
		$_SESSION['password'] = "The answer is 42";
		$act = "idx";
		index_display();
		mysql_free_result($result);
	}

	else
	{
?>
<p class=\"response\">Login failed.  Please re-enter your information and doublecheck that it is correct.</p>
<?php
	login_form();
	}
}

//Display the login form
function login_form()
{
?>
<form id="loginform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

	<div class="bk_form">
		<input type="hidden" name="act" value="login_check" />
		<span class="bk_label">
			<label for="password">Password</label>:
		</span>
		<span class="bk_field">
			<input type="password" name="password" id="password" tabindex="2" />
		</span>
	</div>
	<div class="bk_form bk_buttons">
		<input type="submit" value="Login" class="button" />
	</div>
</form>
<?php
}

//Logout, end the session, and then present the login form again
function logout()
{
	$_SESSION = array();
	session_destroy();

	echo "<p class=\"response\">Your session has been ended.  Use the form below to login again.</p>";
	login_form();
}
?>