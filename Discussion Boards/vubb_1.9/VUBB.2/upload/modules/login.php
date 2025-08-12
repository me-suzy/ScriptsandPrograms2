<?php
/*
Copyright 2005 VUBB
*/
if (!isset($_GET['action']) && !isset($_SESSION['user']) && !isset($_SESSION['pass']))
{
	get_template('login');
}

// Login action
else if (isset($_GET['action']) && ($_GET['action'] = 'login'))
{
	if (!isset($_SESSION['user']) && !isset($_SESSION['pass'])) 
	{
		login($_POST['user'], $_POST['pass']);
	}
	
	else
	{
		message($lang['title']['logged'],$lang['text']['logged']);
	}
}
?>