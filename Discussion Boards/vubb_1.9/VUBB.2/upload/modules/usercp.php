<?php
/*
Copyright 2005 VUBB
*/
if (!isset($_GET['action']))
{
	get_template('usercp');
	
	$user_info['sig'] = edit_post_parser($user_info['sig']);
	
	// bring in the start of the usercp using the template
	usercp_start();
	
	// if view isnt set then view the welcome page
	if (!isset($_GET['view']))
	{
		$page = usercp_welcome();
	}
		
	// else view the page that has been asked for
	else
	{
		$cp_page = $_GET['view'];
		$page = 'usercp_' . $cp_page;
		$page();
	}
}

// Edit Profile
if(isset($_GET['action']) && $_GET['action'] == 'editprofile')
{
	if (!isset($_POST['avatar']))
	{
		$avatar_link = $site_config['site_url'] . 'images/noav.jpg';
	}
	
	else
	{
		$avatar_link = $_POST['avatar'];
	}
	
	mysql_query("UPDATE `members` SET `sig` = '" . addslashes($_POST['body1']) . "', `avatar_link` = '" . addslashes($avatar_link) . "', `location` = '" . addslashes($_POST['location']) . "', `website` = '" . addslashes($_POST['website']) . "', `aim` = '" . addslashes($_POST['aim']) . "', `msn` = '" . addslashes($_POST['msn']) . "', `yahoo` = '" . addslashes($_POST['yahoo']) . "', `icq` = '" . addslashes($_POST['icq']) . "' WHERE `user` = '" . $user_info['user'] . "'");
	
	message($lang['title']['edited'], $lang['text']['edited']);
}

// Edit Password
if(isset($_GET['action']) && $_GET['action'] == 'editpassword')
{
	if (!isset($_POST['cpass']) || !isset($_POST['cpassa']) || !isset($_POST['npass']) || !isset($_POST['npassa']))
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		if ($_POST['cpass'] != $_POST['cpassa'])
		{
			message($lang['title']['error'], $lang['text']['cpass_no_match']);
		}
		
		else if ($_POST['npass'] != $_POST['npassa'])
		{
			message($lang['title']['error'], $lang['text']['npass_no_match']);
		}
		
		else
		{
			$encrypted_current_pass = md5($_POST['cpass']);
			
			if ($encrypted_current_pass != $_SESSION['pass'])
			{
				message($lang['title']['error'], $lang['text']['cpass_no_match_db']);
			}
			
			else
			{
				$encrypted_pass = md5($_POST['npass']);
				
				mysql_query("UPDATE `members` SET `pass` = '" . $encrypted_pass . "'");

				unset($_SESSION["pass"]);
				
				$_SESSION['pass'] = $encrypted_pass;
				
				message($lang['title']['edited'], $lang['text']['pass_edited']);
			}
		}
	}
}

?>