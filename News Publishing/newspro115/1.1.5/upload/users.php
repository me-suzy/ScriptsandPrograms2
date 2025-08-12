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
$USER = unp_getUser();
unp_getsettings();

isset($_GET['action']) ? $action = $_GET['action'] : $action = '';
// +------------------------------------------------------------------+
// | Process Submission                                               |
// +------------------------------------------------------------------+
if ($action == '')
{
	if ($USER['groupid'] != 1)
	{
		// permission denied
		unp_msgBox($gp_permserror);
		exit;
	}
	if (isset($_POST['submitchanges']))
	{
		$userid = $_POST['userid'];
		$username = $_POST['username'];
		$groupid = $_POST['groupid'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$email = $_POST['email'];
		$username = addslashes(trim($username));
		$email = addslashes(trim($email));
		// Check all are valid
		if (unp_isempty($username))
		{
			unp_msgBox('You have entered an invalid username.');
			exit;
		}
		if (ereg('[\'"]', $username))
		{
			unp_msgBox('Username must not contain quotation marks.');
			exit;
		}
		if (!preg_match('/^[\d]+$/', $userid))
		{
			unp_msgBox('You have entered an invalid user ID.');
			exit;
		}
		if (!preg_match('/^[\d]+$/', $groupid))
		{
			unp_msgBox('You have entered an invalid group ID.');
			exit;
		}
		if (!unp_isvalidemail($email))
		{
			unp_msgBox($gp_invalidemail);
			exit;
		}
		// check to see if username has changed - if so, update news with new username
		$getuser = $DB->query("SELECT * FROM `unp_user` WHERE userid='$userid'");
		$user = $DB->fetch_array($getuser);
		$dbusername = $user['username']; // the username already in the database for this user
		if ($dbusername != $username)
		{
			$DB->query("UPDATE `unp_news` SET poster='$username' WHERE poster='$dbusername'");
		}
		// end check all are valid
		if (!unp_isempty($password))
		{
			if ($password != $password2)
			{
				unp_msgBox('You have entered two different passwords.');
				exit;
			}
			else
			{
				$password = md5($password);
				$editsql = $DB->query("UPDATE `unp_user` SET username='$username',password='$password',groupid='$groupid',email='$email' WHERE userid='$userid'");
			}
		}
		else
		{
			$editsql = $DB->query("UPDATE `unp_user` SET username='$username',groupid='$groupid',email='$email' WHERE userid='$userid'");
		}
		if ($dbusername == $USER['username'])
		{
			// user has changed his/her own name through user panel instead of profile page - update sessions anyway
			$_SESSION['unp_user'] = $username;
			setcookie('unp_user', $username, time()+60*60*24*999999); // update cookie used to fill in username field automatically
		}
		unp_redirect('users.php?action=main','User was successfully updated!<br />You will now be taken back to the user management page.');
	}
	elseif (isset($_POST['submitnew']))
	{
		$username = $_POST['username'];
		$groupid = $_POST['groupid'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$email = $_POST['email'];
		// fix up text
		$username = addslashes(trim($username));
		$email = addslashes(trim($email));
		$password = md5($password);
		$password2 = md5($password2);
		// check validity
		if (unp_isempty($username))
		{
			unp_msgBox('You have entered an invalid username.');
			exit;
		}
		if (ereg('[\'"]', $username))
		{
			unp_msgBox('Username must not contain quotation marks.');
			exit;
		}
		if (strlen($password) < 3)
		{
			unp_msgBox('You have entered an invalid password. Passwords must be at least 3 characters.');
			exit;
		}
		if (!unp_isvalidemail($email))
		{
			unp_msgBox($gp_invalidemail);
			exit;
		}
		if ($password != $password2)
		{
			unp_msgBox('You have entered two different passwords.');
			exit;
		}
		// MAKE SURE THIS USERNAME DOESN'T ALREADY EXIST
		$checkuser = $DB->query("SELECT * FROM `unp_user` WHERE username='$username'");
		$checkusernum = $DB->num_rows($checkuser);
		if ($checkusernum != 0)
		{
			unp_msgBox('A user with that username already exists.');
			exit;
		}
		$addquery = $DB->query("INSERT INTO `unp_user` (`groupid`, `username`, `password`, `email`) VALUES ('$groupid','$username','$password','$email')");
		unp_redirect('users.php?action=main','User was successfully added!<br />You will now be taken back to the user management page.');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}

// +------------------------------------------------------------------+
// | Process Main Users Page Content                                  |
// +------------------------------------------------------------------+
if ($action == 'main')
{
	$allusers = $DB->query("SELECT * FROM `unp_user` ORDER BY `username` ASC");
	include('header.php');
	unp_openbox();
	echo '
	<strong>Users Management Control Panel</strong>&nbsp;';
	unp_faqLink(6);
	echo '<br />
	<center>[ <a href="users.php?action=add">Add User</a> | <a href="users.php?action=main">Edit Users</a> ]</center>
	<strong>Current Users:</strong>
	<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td width="25%"><em>Username</em></td>
		<td width="25%"><em>Permission Level</em></td>
		<td width="25%"><em>E-Mail Address</em></td>
		<td width="25%"><em>Action</em></td>
	</tr>';
	while ($mainusers = $DB->fetch_array($allusers))
	{
		$userid = $mainusers['userid'];
		$groupid = $mainusers['groupid'];
		$username = $mainusers['username'];
		$email = $mainusers['email'];
		if ($groupid == '1')
		{
			$permslevel = 'Administrator';
		}
		elseif ($groupid == '2')
		{
			$permslevel = 'Enhanced Level';
		}
		elseif ($groupid == '3')
		{
			$permslevel = 'Standard Level';
		}
		echo '
		<tr>
			<td>'.$username.'</td>
			<td>'.$permslevel.'</td>
			<td>'.$email.'</td>
			<td>[<a href="users.php?action=edit&amp;userid='.$userid.'">Edit</a>][<a href="users.php?action=delete&amp;userid='.$userid.'">Remove</a>]</td>
		</tr>';
	}
	echo '</table>';
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Add Users Page Content                                   |
// +------------------------------------------------------------------+
if ($action == 'add')
{
	if ($USER['groupid'] != 1)
	{
		// permission denied
		unp_msgBox($gp_permserror);
		exit;
	}
	include('header.php');
	unp_openbox();
	echo '
	<center>[ <a href="users.php?action=add">Add User</a> | <a href="users.php?action=main">Edit Users</a> ]</center>
	<form action="users.php" method="post">
	<table border="0" width="100%" cellpadding="1" cellspacing="0">
	<tr>
		<td>Username</td>
		<td><input type="text" value="" name="username" size="35" /></td>
	</tr>
	<tr>
		<td>Permission Level</td>
		<td><select name="groupid">
			<option value="1">Administrator</option>
			<option value="2">Enhanced Level</option>
			<option value="3" selected="selected">Standard Level</option>
			</select></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" value="" name="password" size="35" /></td>
	</tr>
	<tr>
		<td>Verify Password</td>
		<td><input type="password" value="" name="password2" size="35" /></td>
	</tr>
	<tr>
		<td>E-Mail Address</td>
		<td><input type="text" value="" name="email" size="35" /></td>
	</tr>
	<tr>
		<td colspan="2"><center><input type="submit" value="Add User" name="submitnew" accesskey="s" /></center></td>
	</tr>
	</table>
	</form>';
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Delete Users Page Content                                |
// +------------------------------------------------------------------+
if ($action == 'delete')
{
	if ($USER['groupid'] != 1)
	{
		// permission denied
		unp_msgBox($gp_permserror);
		exit;
	}
	isset($_GET['userid']) ? $userid = $_GET['userid'] : $userid = '';
	if ($userid == '')
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	if (!preg_match('/^[\d]+$/', $userid))
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
	$getuser = $DB->query("SELECT * FROM `unp_user` WHERE userid='$userid'");
	$getusernum = $DB->num_rows($getuser);
	if ($getusernum < 1)
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
	$getuser2 = $DB->fetch_array($getuser);
	$username = $getuser2['username'];
	// CHECK TO MAKE SURE USER IS NOT DELETING SELF
	if ($username == $USER['username'])
	{
		unp_msgBox('You cannot remove yourself.');
		exit;
	}
	include('header.php');
	unp_openbox();
	echo 'Are you sure you want to remove user <span>'.$username.'</span>?<br />
	<a href="users.php?action=remove&amp;userid='.$userid.'&amp;verify=1">Yes</a><br />
	<a href="users.php?action=main">No</a>';
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Delete Users                                             |
// +------------------------------------------------------------------+
if ($action == 'remove')
{
	if ($USER['groupid'] != 1)
	{
		// permission denied
		unp_msgBox($gp_permserror);
		exit;
	}
	isset($_GET['userid']) ? $userid = $_GET['userid'] : $userid = '';
	isset($_GET['verify']) ? $verify = $_GET['verify'] : $verify = '';
	if ($verify != 1)
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	if (!isset($userid))
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	if ($userid == '')
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	if (!eregi('[0-9]+', $userid))
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
	$getuser = $DB->query("SELECT * FROM `unp_user` WHERE userid='$userid'");
	if (!$DB->is_single_row($getuser))
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
	$getuser2 = $DB->fetch_array($getuser);
	$username = $getuser2['username'];
	// CHECK TO MAKE SURE USER IS NOT DELETING SELF
	if ($username == $USER['username'])
	{
		unp_msgBox('You cannot remove yourself.');
		exit;
	}	
	$removeuser = $DB->query("DELETE FROM `unp_user` WHERE userid='$userid'");
	if (mysql_affected_rows() != 1)
	{
		unp_msgBox('There was a problem removing the user. This user may not exist.');
		exit;
	}
	unp_redirect('users.php?action=main','User was successfully removed!<br />You will now be taken back to the user management page.');
}

// +------------------------------------------------------------------+
// | Process Edit Users Page Content                                  |
// +------------------------------------------------------------------+
if ($action == 'edit')
{
	if ($USER['groupid'] != 1)
	{
		// permission denied
		unp_msgBox($gp_permserror);
		exit;
	}
	isset ($_GET['userid']) ? $userid = $_GET['userid'] : $userid = '';
	if (!eregi('[0-9]+', $userid))
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
		$getuser = $DB->query("SELECT * FROM `unp_user` WHERE userid='$userid'");
		if (!$DB->is_single_row($getuser))
		{
			unp_msgBox('You have entered an invalid user ID.');
			exit;
		}
		while ($user = $DB->fetch_array($getuser))
		{
			$groupid = $user['groupid'];
			$username = $user['username'];
			$email = $user['email'];
				// create group id drop down
				if ($groupid == '1')
				{
					$group1 = '<option value="1" selected="selected">Administrator</option>';
				}
				else
				{
					$group1 = '<option value="1">Administrator</option>';
				}
				if ($groupid == '2')
				{
					$group2 = '<option value="2" selected="selected">Enhanced Level</option>';
				}
				else
				{
					$group2 = '<option value="2">Enhanced Level</option>';
				}
				if ($groupid == '3')
				{
					$group3 = '<option value="3" selected="selected">Standard Level</option>';
				}
				else
				{
					$group3 = '<option value="3">Standard Level</option>';
				}
			include('header.php');
			unp_openbox();
			echo '
			<center>[ <a href="users.php?action=add">Add User</a> | <a href="users.php?action=main">Edit Users</a> ]</center>
			<center>[ <a href="users.php?action=removeusernews&amp;userid='.$userid.'">Remove All News By '.$username.'</a> | <a href="users.php?action=delete&amp;userid='.$userid.'">Delete This User</a> ]</center>
			<form action="users.php" method="post">
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
			<tr>
				<td>Username</td>
				<td><input type="text" value="'.$username.'" name="username" size="35" /></td>
			</tr>
			<tr>
				<td>Permission Level</td>
				<td><select name="groupid">';
					echo ($group1);
					echo ($group2);
					echo ($group3);
			echo '</select></td>
			</tr>
			<tr>
				<td>Password<br /><span class="smallfont">Leave this blank unless you want to change your password.</span></td>
				<td><input type="password" value="" name="password" size="35" /></td>
			</tr>
			<tr>
				<td>Verify Password<br /><span class="smallfont">Leave this blank unless you want to change your password.</span></td>
				<td><input type="password" value="" name="password2" size="35" /></td>
			</tr>
			<tr>
				<td>E-Mail Address</td>
				<td><input type="text" value="'.$email.'" name="email" size="35" /></td>
			</tr>
			<tr>
				<td colspan="2"><center><input type="submit" value="Submit Changes" name="submitchanges" accesskey="s" /></center></td>
			</tr>
			</table>
			<input type="hidden" value="'.$userid.'" name="userid" />
			</form>';
			unp_closebox();
			include('footer.php');
		}
}

// +------------------------------------------------------------------+
// | Process Edit Users Page Content                                  |
// +------------------------------------------------------------------+
if ($action == 'removeusernews')
{
	isset ($_GET['userid']) ? $userid = $_GET['userid'] : $userid = '';
	isset ($_GET['verify']) ? $verify = $_GET['verify'] : $verify = '';
	if ($USER['groupid'] != 1)
	{
		// permission denied
		unp_msgBox($gp_permserror);
			exit;
	}
	if ($userid == '')
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
	if (!eregi('[0-9]+', $userid))
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
	$getuser = $DB->query("SELECT * FROM `unp_user` WHERE userid='$userid'");
	if (!$DB->is_single_row($getuser))
	{
		unp_msgBox('You have entered an invalid user ID.');
		exit;
	}
	$getuser2 = $DB->fetch_array($getuser);
	$username = $getuser2['username'];
	if ($verify != 1)
	{
		include('header.php');
		unp_openbox();
		echo 'Are you sure you want to remove all of <strong>'.$username.'</strong>\'s news?<br />
		<a href="users.php?action=removeusernews&amp;userid='.$userid.'&amp;verify=1">Yes</a><br />
		<a href="users.php?action=main">No</a>';
		unp_closebox();
		include('footer.php');
	}
	elseif ($verify == 1)
	{
		$getuser = $DB->query("SELECT * FROM `unp_user` WHERE userid='$userid'");
		if (!$DB->is_single_row($getuser))
		{
			unp_msgBox('You have entered an invalid user ID.');
			exit;
		}
		$getuser2 = $DB->fetch_array($getuser);
		$username = $getuser2['username'];
		$removenews = $DB->query("DELETE FROM `unp_news` WHERE poster='$username'");
		unp_redirect('users.php?action=main','Successfully removed all of user\'s news!<br />You will now be taken back to the user management page.');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
	}
}
?>