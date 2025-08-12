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

$INUNPDIR = true;

// +------------------------------------------------------------------+
// | Process Profile Submission                                       |
// +------------------------------------------------------------------+
if ($action == '')
{
	if (isset($_POST['submitchanges']))
	{
		$loggedinuser = $USER['username'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$email = $_POST['email'];
		// Fix up text
		$username = addslashes(trim($username));
		$email = addslashes(trim($email));
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
		$getUser = $DB->query("SELECT * FROM `unp_user` WHERE username='$loggedinuser'");
		$user = $DB->fetch_array($getUser);
		// PASSWORD CHANGES
		if (!unp_isempty($password) && !unp_isempty($password2))
		{
			if (strlen($password) < 3)
			{
				unp_msgBox('You have entered an invalid password. Passwords must be at least 3 characters.');
				exit;
			}
				$password = md5($password);
				// finish password upate
				$update2 = $DB->query("UPDATE `unp_user` SET password='$password' WHERE username='$loggedinuser'");
				$_SESSION['unp_pass'] = $password;
		}
		// USERNAME CHANGES
		if ($username != $user['username'])
		{
			$newusername = $username;
			$oldusername = $user['username'];
			$checkAlreadyExists = $DB->query("SELECT * FROM `unp_user` WHERE username='$newusername'");
			if ($DB->num_rows($checkAlreadyExists) != 0)
			{
				unp_msgBox('A user with this username already exists!');
				exit;
			}
			$updateNews = $DB->query("UPDATE `unp_news` SET poster='$newusername' WHERE posterid='$USER[userid]'");
			$_SESSION['unp_user'] = $newusername;
			setcookie('unp_user', $newusername, time()+60*60*24*999999); // update cookie used to fill in username field automatically
		}
		// UPDATE
		$update1 = $DB->query("UPDATE `unp_user` SET username='$username',email='$email' WHERE username='$loggedinuser'");
		unp_autoBuildCache();
		unp_redirect('profile.php?action=edit','Your profile was successfully updated!<br />You will now be taken back to your profile page.');
	}
	else
	{
		unp_msgBox($gp_invalidrequest);
		exit;
	}
}

// +------------------------------------------------------------------+
// | Process Profile Page Content                                     |
// +------------------------------------------------------------------+
if ($action == 'edit')
{
	$loggedinuser = $USER['username'];
	$getuser = $DB->query("SELECT * FROM `unp_user` WHERE username='$loggedinuser'");
	$user = $DB->fetch_array($getuser);
		$groupid = $user['groupid'];
		$username = $user['username'];
		$email = $user['email'];
	if ($groupid == 1)
	{
		$usergroup = 'Administrator';
	}
	elseif ($groupid == 2)
	{
		$usergroup = 'Enhanced Level';
	}
	elseif ($groupid == 3)
	{
		$usergroup = 'Standard Level';
	}
	/* avatars */
	$avatarcheck = unp_checkAvatar($USER['userid']);
	if ($avatarallowance == '1' && $avatarcheck)
	{
		$avatary = '<img src="'.$avatarcheck.'" alt="Avatar" />'.'&nbsp;[ <a href="profile.php?action=editavatar">Update Avatar</a> ]&nbsp;[ <a href="profile.php?action=removeavatar">Remove Avatar</a> ]';
	}
	elseif ($avatarallowance == '1' && !$avatarcheck)
	{
		$avatary = '<img src="images/avatars/noavatar.gif" alt="Avatar" />'.'&nbsp;[ <a href="profile.php?action=editavatar">Update Avatar</a> ]';
	}
	else
	{
		$avatary = '&nbsp;[ Avatars Disabled ]';
	}
	/* avatars */
	include('header.php');
	unp_openbox();
	echo '<strong>Edit Profile</strong>&nbsp;';
	unp_faqLink(10);
	echo '
	<form action="profile.php" method="post">
	<table border="0" width="100%" cellpadding="1" cellspacing="0">
		<tr>
			<td width="50%">Username</td>
			<td width="50%"><input type="text" value="'.$username.'" name="username" size="35" /></td>
		</tr>
		<tr>
			<td width="50%">Permissions Level</td>
			<td width="50%">'.$usergroup.'</td>
		</tr>
		<tr>
			<td width="50%">Password<br />
			<span class="smallfont">Leave this blank unless you want to change your password.</span></td>
			<td width="50%"><input type="password" value="" name="password" size="35" /></td>
		</tr>
		<tr>
			<td width="50%">Verify Password<br />
			<span class="smallfont">Leave this blank unless you want to change your password.</span></td>
			<td width="50%"><input type="password" value="" name="password2" size="35" /></td>
		</tr>
		<tr>
			<td width="50%">E-Mail Address</td>
			<td width="50%"><input type="text" value="'.$email.'" name="email" size="35" /></td>
		</tr>
		<tr>
			<td width="50%">Avatar</td>
			<td width="50%">'.$avatary.'</td>
		</tr>
		<tr>
			<td width="100%" colspan="2"><center><input type="submit" value="Submit Profile Changes" name="submitchanges" accesskey="s" /></center></td>
		</tr>
	</table>
	</form>';
	unp_closebox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Avatar Edit                                              |
// +------------------------------------------------------------------+
if ($action == 'editavatar')
{
	if ($avatarallowance != '1')
	{
		unp_msgBox('The administrator has disabled avatars.');
		exit;
	}
	$avatarcheck = unp_checkAvatar($USER['userid']);
	if (!$avatarcheck)
	{
		$avatar = '<img src="images/avatars/noavatar.gif" alt="Avatar" />';
	}
	else
	{
		$avatar = '<img src="'.$avatarcheck.'" alt="Avatar" />&nbsp;[ <a href="profile.php?action=removeavatar">Remove Avatar</a> ]';
	}
	include('header.php');
	unp_openBox();
	echo '
		<table width="90%" border="0" align="center">
		<tr><td>
			<table border="0" width="100%" valign="top" style="border: 1px solid #000000; border-bottom: 0;" cellpadding="5" cellspacing="0">
				<tr>
					<td bgcolor="#6384B0" style="border-bottom: #000000 1px solid" colspan="2">
					<span class="tblheadtxt"><strong>Edit Avatar</strong></span>
					</td>
				</tr>
				<tr>
					<td width="60%" bgcolor="#FFFFFF" style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;">
					<strong>Current Avatar:</strong><br /><span class="smallfont">Note: The maximum dimensions of your avatar are '.$avatardimensions.'x'.$avatardimensions.'</span></td>

					<td width="40%" bgcolor="#FFFFFF" style="border-bottom: 1px solid #000000;">
					<span class="smallfont">You currently have the following avatar on file:</span><br />
					'.$avatar.'</td>
				</tr>
				<tr>
					<td width="60%" bgcolor="#FFFFFF" style="border-bottom: 1px solid #000000; border-right: 1px solid #000000;">
					<strong>Upload Avatar:</strong></td>

					<td width="40%" bgcolor="#FFFFFF" style="border-bottom: 1px solid #000000;">
						<form action="profile.php?action=uploadavatar" method="post" enctype="multipart/form-data">
						<input type="file" name="avatar" /><br />
						<input type="submit" value="Update Avatar" name="submit" style="margin-top: 3px;" />
						</form>
					</td>
				</tr>
				</table>
		</td></tr>
		</table>';
	unp_closeBox();
	include('footer.php');
}

// +------------------------------------------------------------------+
// | Process Avatar Upload                                            |
// +------------------------------------------------------------------+
if ($action == 'uploadavatar')
{
	if ($avatarallowance != '1')
	{
		unp_msgBox('The administrator has disabled avatars.');
		exit;
	}
	// ######## EXTENSIONS ########
	switch ($_FILES['avatar']['type'])
	{
		case 'image/jpeg':
			$extension = '.jpg';
			break;
		case 'image/pjpeg':
			$extension = '.jpg';
			break;
		case 'image/gif':
			$extension = '.gif';
			break;
		case 'image/x-png':
			$extension = '.png';
			break;
		case 'image/png':
			$extension = '.png';
			break;
		default:
			$extension = false;
			break;
	}
	if (!$extension)
	{
		// image is incorrect file type
		unp_msgBox('Incorrect avatar file type. Correct file types are jpg, gif, and png.');
		exit;
	}
	// ######## CHECKING ########
	$filename = 'images/avatars/avatar-'.$USER['userid'].$extension;
	if (!is_uploaded_file($_FILES['avatar']['tmp_name']))
	{
		// Attempted exploit
		unp_msgBox('Avatar could not be uploaded. Please try again.');
		exit;
	}
	$imageinfo = @getimagesize($_FILES['avatar']['tmp_name']);
	if ($imageinfo[0] > $avatardimensions || $imageinfo[1] > $avatardimensions)
	{
		// too large
		unp_msgBox('Avatar dimensions are too large. Maximum dimensions are '.$avatardimensions.'x'.$avatardimensions.'.');
		exit;
	}
	if ($imageinfo[2] != 1 && $imageinfo[2] != 2 && $imageinfo[2] != 3)
	{
		// image is incorrect file type
		unp_msgBox('Incorrect avatar file type. Correct file types are jpg, gif, and png.');
		exit;
	}
	// clear any existing avatars
	@unlink('images/avatars/avatar-'.$USER['userid'].'.jpg');
	@unlink('images/avatars/avatar-'.$USER['userid'].'.gif');
	@unlink('images/avatars/avatar-'.$USER['userid'].'.png');
	$ok = @copy($_FILES['avatar']['tmp_name'], $filename);
	if (!$ok)
	{
		unp_msgBox('Avatar could not be uploaded. Please try again.');
		exit;
	}
	// ######## DONE ########
	unp_autoBuildCache();
	unp_redirect('profile.php?action=edit','Your avatar was successfully added!<br />You will now be taken back to your profile.');
}

// +------------------------------------------------------------------+
// | Process Avatar Remove                                            |
// +------------------------------------------------------------------+
if ($action == 'removeavatar')
{
	isset($_GET['verify']) ? $verify = $_GET['verify'] : $verify = '';
	$avatarcheck = unp_checkAvatar($USER['userid']);
	if (!$avatarcheck)
	{
		unp_msgBox('You do not have an avatar to remove.');
		exit;
	}
	if ($verify == '1')
	{
		@unlink('images/avatars/avatar-'.$USER['userid'].'.jpg');
		@unlink('images/avatars/avatar-'.$USER['userid'].'.gif');
		@unlink('images/avatars/avatar-'.$USER['userid'].'.png');
		unp_autoBuildCache();
		unp_redirect('profile.php?action=edit','Your avatar was successfully removed!<br />You will now be taken back to your profile.');
	}
	else
	{
		include('header.php');
		unp_openBox();
		echo 'Are you sure you want to remove your avatar?<br />
		<a href="profile.php?action=removeavatar&verify=1">Yes</a><br />
		<a href="profile.php?action=editavatar">No</a>';
		unp_closeBox();
		include('footer.php');
	}
}
?>