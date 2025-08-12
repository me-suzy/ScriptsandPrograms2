<?php
/*
Copyright 2005 VUBB
*/
// Check if new registrations are allowed
if ($site_config['new_registrations'] == '0')
{
	message($lang['title']['no_reg'], $lang['text']['no_reg']);
}

else if (isset($_SESSION['user']) && isset($_SESSION['pass']))
{
	message($lang['title']['no_reg'], $lang['text']['already_reg']);
}

else
{	
	if (!isset($_GET['action']))
	{	
		echo eval(get_template('register'));
	}
	
	if (isset($_GET['action']) &&  $_GET['action'] == 'register')
	{
		// Start register function
		register($_POST['user'], $_POST['email'], $_POST['vemail'], $_POST['pass'], $_POST['vpass']);
	}
}
?>