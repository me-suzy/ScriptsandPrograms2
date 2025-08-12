<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############## //ADMIN PANEL GLOBAL ADMIN\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// uh oh!
if($opener = @opendir("./../install")) {
	// do header
	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");
	print("<html>\n");
	print("<head>\n");
	print("<title> Error! </title>\n");
	print("<link rel=\"stylesheet\" href=\"content_style.css\" type=\"text/css\" />\n");
	print("<script language=\"javascript\" type=\"text/javascript\" src=\"./../scripts/global.js\"></script>\n");
	print("</head>\n");
	print("<body>\n\n\n");

	// construct error
	print("<h1 style=\"width: 60%;\">ERROR!</h1>\n\n");
	print("<div align=\"center\"><blockquote style=\"width: 60%; text-align: left;\">\n\n");
	print("There has been a security risk detected! You <strong>must</strong> remove the <strong>install</strong> directory. If you do not remove the directory, you will not be able to access the Control Panel.");
	print("</blockquote></div>\n\n");

	// do footer
	print("\n\n\n<span class=\"footer\"><a href=\"http://www.webtrickscentral.com\" target=\"_top\">Copyright ©, WebTricksCentral.com</a></span>");
	print("\n\n\n</body>");
	print("\n</html>\n\n");

	exit;
}

// ##### SOME FUNCTIONS TO DECLARE ##### \\
// ##### they have to be made here in order to use them.. oh well... ##### \\

// devise function to insert default administrator permissions
function insertAdminPermissions($username,$userid) {
	// run query
	query("INSERT INTO admin_permissions (userid,username,wtcBBoptions,announcements,forums_moderators,users,usergroups,logs_stats,avatars,smilies,post_icons,usertitles,bbcode,styles,attachments,threads_posts,updateinfo,warn) VALUES ('".$userid."','".$username."','0','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0')");
}

// devise function to DELETE from administrator permissions
function deleteAdminPermissions($userid) {
	// run query
	query("DELETE FROM admin_permissions WHERE userid = '".$userid."' LIMIT 1");
}

// combine above functions to determine if we should delete or insert...
function determineAdminPermissions() {
	// find users belonging to admin usergroups
	$admin_usergroup_query = query("SELECT * FROM usergroups WHERE is_admin = '1'");

	// loop through admin usergroups
	while($admin_usergroup = mysql_fetch_array($admin_usergroup_query)) {
		// find users who belong to this usergroup
		$userinfo_select = query("SELECT * FROM user_info WHERE usergroupid = '".$admin_usergroup['usergroupid']."'");

		// now loop through users
		while($userinfo = mysql_fetch_array($userinfo_select)) {
			// check to see if this user has admin permissions already...
			$check_adminPermissions = query("SELECT * FROM admin_permissions WHERE userid = '".$userinfo['userid']."' LIMIT 1");

			// if there are no rows.. than that means we need to insert one.. after all, this user HAS to be an admin
			if(mysql_num_rows($check_adminPermissions) == 0) {
				// insertAdminPermissions
				insertAdminPermissions($userinfo['username'],$userinfo['userid']);
			}
		}
	}

	// ok the above was to check and see if we needed to INSERT a row.. now we need to see if there are any old administrators... loop through admin_permissions
	$adminPermissions_query = query("SELECT * FROM admin_permissions");

	while($adminPermissions = mysql_fetch_array($adminPermissions_query)) {
		// find userinfo
		$userinfo = mysql_fetch_array(query("SELECT * FROM user_info WHERE userid = '".$adminPermissions['userid']."' LIMIT 1"));

		// alright now we need to find the usergroup belonging to each user...
		$usergroupinfo = mysql_fetch_array(query("SELECT * FROM usergroups WHERE usergroupid = '".$userinfo['usergroupid']."' LIMIT 1"));

		// if that's is NOT an admin group.. then deletion time!
		if($usergroupinfo['is_admin'] == 0) {
			deleteAdminPermissions($userinfo['userid']);
		}
	}

	// all done! this will be run in this file only!
}

// ##### END FUNCTION DECLARATION ##### \\

// do routine administrator crap... make sure the user is logged in...
if($_COOKIE['wtcBB_adminUsername'] AND $_COOKIE['wtcBB_adminUserid'] AND !$_COOKIE['wtcBB_adminIsMod']) {
	// admin log... don't need to do this if it's the navigation..
	if($fileAction != "Navigation") {
		// no update queries.. just insert
		$adminLog_insert = "INSERT INTO log_admin (userid,username,filepath,file_action,action_date,ip_address) VALUES ('".addslashes($_COOKIE['wtcBB_adminUserid'])."','".addslashes($_COOKIE['wtcBB_adminUsername'])."','".$_SERVER['PHP_SELF']."','".$fileAction."','".time()."','".$_SERVER['REMOTE_ADDR']."')";

		// run query
		query($adminLog_insert);
	}

	// do the admin permissions.. instead of running this whenever a user is edited in any way, shape, or form.. just do it on every page
	// brilliant function.... defined at the top of this file
	determineAdminPermissions();

	// get admin permissions for corresponding user
	$adminPermissions = mysql_fetch_array(query("SELECT * FROM admin_permissions WHERE userid = '".addslashes($_COOKIE['wtcBB_adminUserid'])."' LIMIT 1"));

	// check to make sure whoever is viewing this page has proper access to it
	if($permissions AND $_GET['do'] != "admin_permissions") {
		if($adminPermissions[$permissions] == 0) {
			// do header
			print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");
			print("<html>\n");
			print("<head>\n");
			print("<title> ".$title." </title>\n");
			print("<link rel=\"stylesheet\" href=\"content_style.css\" type=\"text/css\" />\n");
			print("<script language=\"javascript\" type=\"text/javascript\" src=\"./../scripts/global.js\"></script>\n");
			print($meta);
			print("</head>\n");
			print("<body".$onLoad.">\n\n\n");

			// construct error
			print("<h1 style=\"width: 60%;\">ERROR!</h1>\n\n");
			print("<div align=\"center\"><blockquote style=\"width: 60%; text-align: left;\">\n\n");
			print("Sorry, the Super Administrator does not want you accessing this page. If you are the Super Administrator you can change these permissions in the <strong>\"Administrator Permissions\"</strong> page, located in the usergroups section of the Control Panel.");
			print("</blockquote></div>\n\n");

			// do footer
			print("\n\n\n<span class=\"footer\"><a href=\"http://www.webtrickscentral.com\" target=\"_top\">Copyright ©, WebTricksCentral.com</a></span>");
			print("\n\n\n</body>");
			print("\n</html>\n\n");

			exit;
		}
	}
}

// if mod, and NOT mod area... disallow access
if($_COOKIE['wtcBB_adminIsMod'] AND !$modArea) {
	// do header
	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");
	print("<html>\n");
	print("<head>\n");
	print("<title> ".$title." </title>\n");
	print("<link rel=\"stylesheet\" href=\"content_style.css\" type=\"text/css\" />\n");
	print("<script language=\"javascript\" type=\"text/javascript\" src=\"./../scripts/global.js\"></script>\n");
	print($meta);
	print("</head>\n");
	print("<body".$onLoad.">\n\n\n");

	// construct error
	print("<h1 style=\"width: 60%;\">ERROR!</h1>\n\n");
	print("<div align=\"center\"><blockquote style=\"width: 60%; text-align: left;\">\n\n");
	print("Moderators cannot access this area of the control panel.");
	print("</blockquote></div>\n\n");

	// do footer
	print("\n\n\n<span class=\"footer\"><a href=\"http://www.webtrickscentral.com\" target=\"_top\">Copyright ©, WebTricksCentral.com</a></span>");
	print("\n\n\n</body>");
	print("\n</html>\n\n");

	exit;
}

$noGood = false;

if($_COOKIE['wtcBB_adminUsername'] AND $_COOKIE['wtcBB_adminUserid']) {
	$user = query("SELECT * FROM user_info WHERE userid = '".addslashes($_COOKIE['wtcBB_adminUserid'])."' LIMIT 1",1);

	if(!$_COOKIE['wtcBB_adminPassword'] OR $user['password'] != $_COOKIE['wtcBB_adminPassword']) {
		$noGood = true;
	}
}

// if cookie is already set... don't do this...
if((!$_COOKIE['wtcBB_adminUsername'] AND !$_COOKIE['wtcBB_adminUserid']) OR $noGood) {
	// make sure you send an encrypted md5 password as an argument...
	function confirmLogin2($username, $password) {
		// we're getting an encrypted password
		$checkValiditity = query("SELECT * FROM user_info WHERE username = '".addslashes(htmlspecialchars($username))."' LIMIT 1");

		// true or false?
		if(mysql_num_rows($checkValiditity)) {
			$checkinfo = mysql_fetch_array($checkValiditity);

			// if we imported vB.. we need to check their "salt"
			if($checkinfo['vBsalt'] != null) {
				if($checkinfo['password'] == md5($password.$checkinfo['vBsalt'])) {
					return true;
				}

				else {
					return false;
				}
			}

			else {
				if($checkinfo['password'] == $password) {
					return true;
				}

				else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

	$message = "";

	if($_POST['login']['set_form']) {
		// check to make sure we have proper access...
		$userinfo_query = query("SELECT * FROM user_info WHERE username = '".addslashes($_POST['wtcBB_username'])."' LIMIT 1");

		// array
		if(mysql_num_rows($userinfo_query)) {
			$userinfo = mysql_fetch_array($userinfo_query);
		}

		// find respecting usergroup
		$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$userinfo['usergroupid']."' LIMIT 1",1);

		// is mod?
		if($bboptions['general_modcp']) {
			$checkMod = query("SELECT * FROM moderators WHERE userid = '".$userinfo['userid']."'");

			if(mysql_num_rows($checkMod)) {
				$isMod = true;
			}

			else {
				$isMod = false;
			}
		}

		else {
			$isMod = false;
		}

		// confirm login
		$checkLogin = confirmLogin2($_POST['wtcBB_username'],md5(addslashes($_POST['wtcBB_password'])));

		if(!$checkLogin) {
			$message = "<div style=\"text-align: center; width: 400px; margin-left: auto; margin-right: auto; margin-top: 20px; margin-bottom: 0px; font-weight: bold; color: #BB0000;\">Sorry, you have entered in an invalid username or password.</div>\n";
		}

		else if(!$usergroupinfo['is_admin'] AND !$isMod) {
			$message = "<div style=\"text-align: center; width: 400px; margin-left: auto; margin-right: auto; margin-top: 20px; margin-bottom: 0px; font-weight: bold; color: #BB0000;\">Sorry, that user does not belong to a usergroup that has access to the Control Panel</div> <br />";
		}

		// access granted!
		else {
			setcookie("wtcBB_adminUsername",$userinfo['username'],0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
			setcookie("wtcBB_adminUserid",$userinfo['userid'],0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
			setcookie("wtcBB_adminPassword",$userinfo['password'],0,$bboptions['cookie_path'],$bboptions['cookie_domain']);

			// if mod and not admin.. set a "special" cookie
			if($usergroupinfo['is_admin'] == 0 AND $isMod == true) {
				setcookie("wtcBB_adminIsMod",true,0,$bboptions['cookie_path'],$bboptions['cookie_domain']);
			}

			//redirect
			redirect($_SERVER['REQUEST_URI']);
		}
	}

	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<title> wtcBB Admin Panel - Login </title>
	<link rel="stylesheet" href="./../admin/content_style.css" type="text/css" />
	<script language="javascript" type="text/javascript" src="./../scripts/global.js"></script>
	</head>
	<body>


	<h1 style="width: 400px;">WebTricksCentral Bulletin Board Login</h1>

	<div style="text-align: center; width: 400px; margin: 20px auto 0 auto;">Welcome to <strong>WebTricksCentral Bulletin Board</strong> Administrator control panel!</div>

	<?php print($message); ?>

	<form method="post" action="" name="login">
	<br /><input type="hidden" name="login[set_form]" value="1" />

	<table border="0" cellspacing="0" cellpadding="4" class="options" style="width: 400px;">
		<tr>
			<td class="header" colspan="2">Login</td>
		</tr>

		<tr>
			<td class="desc1">
				<strong>Username:</strong>
			</td>

			<td class="input1">
				<input type="text" class="text" name="wtcBB_username" value="" />
				<script type="text/javascript">
					document.login.wtcBB_username.focus();
				</script>
			</td>
	</tr>

		<tr>
			<td class="desc2_bottom">
				<strong>Password:</strong>
			</td>

			<td class="input2_bottom">
				<input type="password" class="text" name="wtcBB_password" value="" />
			</td>
	</tr>

		<tr>
			<td class="footer" colspan="2"><pre><button type="submit" id="ban_submit" style="font-family: verdana; font-size: 8pt; border: #9E9E9E 1px solid; background-image: url('./../images/button_bg.jpg'); background-repeat: repeat-x; background-color: #ECECEC;" onMouseDown="this.style.borderColor='#C98C00'; this.style.backgroundImage='url(./../images/button_bgclick.jpg)'; this.style.backgroundColor='#F6EAB9';" onMouseOver="this.style.borderColor='#245F9B'; this.style.backgroundImage='url(./../images/button_bgover.jpg)'; this.style.backgroundColor='#6FBADF';" onMouseout="this.style.borderColor='#9E9E9E'; this.style.backgroundImage='url(./../images/button_bg.jpg)'; this.style.backgroundColor='#ECECEC';">Submit</button>  <button type="reset" style="font-family: verdana; font-size: 8pt; border: #9E9E9E 1px solid; background-image: url('./../images/button_bg.jpg'); background-repeat: repeat-x; background-color: #ECECEC;" onMouseDown="this.style.borderColor='#C98C00'; this.style.backgroundImage='url(./../images/button_bgclick.jpg)'; this.style.backgroundColor='#F6EAB9';" onMouseOver="this.style.borderColor='#245F9B'; this.style.backgroundImage='url(./../images/button_bgover.jpg)'; this.style.backgroundColor='#6FBADF';" onMouseout="this.style.borderColor='#9E9E9E'; this.style.backgroundImage='url(./../images/button_bg.jpg)'; this.style.backgroundColor='#ECECEC';">Reset</button></pre></td>
		</tr>
	</table>
	</form>

	<span class="footer"><a href="http://www.webtrickscentral.com" target="_top">Copyright ©, WebTricksCentral.com</a></span>


	</body>
	</html>

<?php
	exit;
}
?>