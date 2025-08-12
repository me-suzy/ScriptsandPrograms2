<?php
/*
////////////////////////////////////////////////
//             Utopia Software                //
//      http://www.utopiasoftware.net         //
//             Utopia News Pro                //
////////////////////////////////////////////////
*/

require('functions.inc.php');
session_start();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';

// +------------------------------------------------------------------+
// | Process Log In Info                                              |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_REQUEST['username']) && isset($_REQUEST['password']))
	{
		$username = addslashes($_REQUEST['username']);
		$password = $_REQUEST['password'];
		if (unp_isempty($username) || unp_isempty($password))
		{
			unp_msgBox($gp_invalidpassword);
			exit;
		}
		$password = md5($password);
		$login = $DB->query("SELECT * FROM `unp_user` WHERE username='$username' LIMIT 1");
		$loginarray = $DB->fetch_array($login);
		// process info
		$loginuserid = $loginarray['userid'];
		$loginusername = $loginarray['username'];
		$loginpassword = $loginarray['password'];
		if ($password == $loginpassword)
		{
			// successfully authenticated - create sessions
			$_SESSION['unp_user'] = $loginusername;
			$_SESSION['unp_pass'] = $loginpassword;
			setcookie('unp_user', $loginusername, time()+60*60*24*999999); // cookie used to fill in username field automatically
			unp_redirect('index.php','Successfully Logged In');
		}
		else
		{
			unp_msgBox($gp_invalidpassword);
			exit;
		}
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
	}
}

// +------------------------------------------------------------------+
// | Process Log Out Info                                             |
// +------------------------------------------------------------------+
if ($action == 'logout')
{
	$USER = unp_getUser();
	unset($_SESSION['unp_user']);
	unset($_SESSION['unp_pass']);
	$_SESSION = array(); // destroy any session variables that there are
	session_destroy(); // destroy session
	// remember - don't delete that cookie! :D
	unp_redirect('index.php','Successfully Logged Out');
}
?>