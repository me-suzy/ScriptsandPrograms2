<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //ADMIN PANEL MODCP\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Users";
$permissions = "users";
$modArea = true;

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

// get all our corresponding mod perms...
// they are specified per forum... but
// we aren't a per forum basis here, so
// if they are allowed it one forum, they have
// it everywhere. Except for announcements of course.

// can ban?
$canBan = false;
$canRestore = false;
$canEditSigs = false;
$canEditAvs = false;
$canMassPrune = false;
$canMassMove = false;
$canGenAnnounce = false;

foreach($modinfo as $forumid => $arr) {
	foreach($arr as $modid => $modarr) {
		if($modarr['userid'] != $_COOKIE['wtcBB_adminUserid']) {
			continue;
		}

		if($modarr['can_ban'] == 1) {
			$canBan = true;
		}

		if($modarr['can_restore'] == 1) {
			$canRestore = true;
		}

		if($modarr['can_edit_sigs'] == 1) {
			$canEditSigs = true;
		}

		if($modarr['can_edit_avatar'] == 1) {
			$canEditAvs = true;
		}

		if($modarr['can_massprune_threads'] == 1) {
			$canMassPrune = true;
		}

		if($modarr['can_massmove_threads'] == 1) {
			$canMassMove = true;
		}

		if($modarr['can_post_announcements'] == 1) {
			$canGenAnnounce = true;
		}
	}
}

// set $is_user to know it's the user file...
$is_user = true;

// make sure we know it's announcement...
$is_announcement = true;

// find forum settings...
$bboptions = query("SELECT * FROM wtcBBoptions LIMIT 1",1);

// ##### Right INDEX ##### \\
if($_GET['do'] == "index") {
	// do header
	admin_header("wtcBB Admin Panel - Main - Quick Stuff");

	construct_title("Quick Stuff");

	print('<p style="width: 90%; margin-left: auto; margin-right: auto;">Welcome to the WebTricksCentral Bulletin Board Moderator Control Panel! Moderators can add announcements, mass prune/mass move threads, mass prune posts, in their forums, if the Administrator has given you access. You can also edit user\'s signatures and avatars if the Administrator has given you permission.</p>');

	construct_table("options","quick","quick_submit");

	construct_header("Quick Stuff",2);

		print("\t<tr>\n");
			print("\t\t<td class=\"desc1\"><strong>User Search</strong></td>\n");
			print("\t\t<td class=\"input1\">\n");
			print("\t\t\t<form action=\"moderator.php\" method=\"get\" style=\"margin: 0;\"><input type=\"hidden\" name=\"search_user[set_form]\" value=\"1\" /><input type=\"hidden\" name=\"do\" value=\"search\" /><input type=\"text\" class=\"text\" style=\"width: 50%;\" name=\"search_user[username]\" id=\"theUsername\" /> &nbsp;&nbsp; <input type=\"submit\" value=\"Find\" style=\"margin-bottom: 4px;\" ".$submitbg."></form> \n\n<script type=\"text/javascript\">\n\tobj = document.getElementById('theUsername'); obj.focus();\n</script>\n\n</td>\n");

			print("\n\n</tr><tr>\n\n");

			print("\t\t<td class=\"desc2\"><strong>PHP Function Search</strong></td>\n");
			print("\t\t<td class=\"input2\">\n");
			print("\t\t\t<form action=\"http://www.php.net/manual-lookup.php\" method=\"get\" style=\"margin: 0;\"><input type=\"text\" class=\"text\" style=\"width: 50%;\" name=\"function\" /> &nbsp;&nbsp; <input type=\"submit\" value=\"Find\" style=\"margin-bottom: 4px;\" ".$submitbg."></form>\n\n</td>\n");

			print("\n\n</tr><tr>\n\n");

			print("\t\t<td class=\"desc1_bottom\"><strong>MySQL Language Search</strong></td>\n");
			print("\t\t<td class=\"input1_bottom\">\n");
			print("\t\t\t<form action=\"http://www.mysql.com/doc/manual.php\" method=\"get\" style=\"margin: 0;\"><input type=\"hidden\" name=\"depth\" value=\"2\" /><input type=\"text\" name=\"search_query\" class=\"text\" style=\"width: 50%;\" /> &nbsp;&nbsp; <input type=\"submit\" value=\"Find\" style=\"margin-bottom: 4px;\" ".$submitbg."></form>\n\n</td>\n");
		print("\t</tr>\n");

	print("\t<tr><td class=\"footer\" colspan=\"2\">&nbsp;</td></tr>\n");

	construct_table_END();


	print("\n\n<br /><br />\n\n");

	
	print("<div style=\"width: 90%; margin-left: auto; margin-right: auto;\">\n\n");
		print("<strong>Credits:</strong>\n");
		print("<p style=\"margin-top: 0;\">All graphics and coding were developed by <strong>Andrew Gallant (Handle: jamslam)</strong>.</p>");
		print("<p style=\"margin-top: 0;\">I would like to thank <strong>Justin Shreve (Handle: Scyth)</strong> for helping me with features, bug testing, and putting up with me. And any other future work he might do to help wtcBB.</p>");
	print("\n</div>\n");

	// do footer
	admin_footer();
}

// ##### DO CHANGE AVATAR ##### \\

else if($_GET['do'] == "change_avatar" AND $canEditAvs == true) {
	// make sure we have a valid userid...
	$checkUserid = query("SELECT * FROM user_info WHERE userid = '".$_GET['userid']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkUserid)) {
		construct_error("Sorry, there is no user matching the given USERID.");
		exit;
	}

	// get userinfo...
	$userinfo = mysql_fetch_array($checkUserid);

	// usergroupinfo
	//$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$userinfo['usergroupid']."' LIMIT 1",1);
	$usergroupinfo = $usergroupinfo[$userinfo['usergroupid']];

	// make sure this user isn't undeletable.. if so.. present error and exit!
	if(isUndeletable($userinfo['userid'])) {
		construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
		exit;
	}

	else if($usergroupinfo['is_admin'] == 1 OR $usergroupinfo['is_super_moderator'] == 1) {
		construct_error("You cannot edit Administrators or Super Moderators. <a href=\"javascript:history.back();\">Go Back.</a>");
		exit;
	}

	// update time...
	if($_POST['avatar_manager']['set_form']) {
		// custom or not?
		if($_POST['avatar_manager']['avatar'] == "custom") {
			// custom avatar.. just put the URL given into the DB...
			$update_query = "UPDATE user_info SET avatar_url = '".$_POST['avatar_manager']['custom_url']."' WHERE userid = '".$userinfo['userid']."'";

			//print($update_query);

			// run query
			query($update_query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Avatar changed successfully. You will now be redirected to ".$userinfo['username']."\'s user info page.&uri=moderator.php?do=editSTEVEid=".$userinfo['userid']);
		}

		// delete!
		else if($_POST['avatar_manager']['avatar'] == "delete") {
			// we aren't deleting anything from the DB.. just updating the avatar field to "none"
			$update_query = "UPDATE user_info SET avatar_url = 'none' WHERE userid = '".$userinfo['userid']."'";

			//print($update_query);

			// run query
			query($update_query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Avatar deleted successfully. You will now be redirected to ".$userinfo['username']."\'s user info page.&uri=moderator.php?do=editSTEVEid=".$userinfo['userid']);
		}

		// otherwise it's an avatar that is in the DB
		else {
			// get avatarinfo
			$avatarinfo2 = query("SELECT * FROM avatars WHERE avatarid = '".$_POST['avatar_manager']['avatar']."' LIMIT 1",1);

			// now form update query for user
			$update_query = "UPDATE user_info SET avatar_url = '".$avatarinfo2['filepath']."' WHERE userid = '".$userinfo['userid']."'";

			//print($update_query);

			// run query
			query($update_query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Avatar changed successfully. You will now be redirected to ".$userinfo['username']."\'s user info page.&uri=moderator.php?do=editSTEVEid=".$userinfo['userid']);
		}
	}


	// get per_page
	if(isset($_GET['per_page'])) {
		$perpage = $_GET['per_page'];
	} else {
		$perpage = 16;
	}

	// get start
	if(isset($_GET['start'])) {
		$start_count = $_GET['start'];
	} else {
		$start_count = 0;
	}

	// run query to find avatars
	$find_avs = query("SELECT * FROM avatars ORDER BY display_order LIMIT ".$start_count.", ".$perpage."");
	//print("SELECT * FROM avatars LIMIT ".$start_count.", ".$perpage." ORDER BY display_order");

	// find colspan...
	if(mysql_num_rows($find_avs) < 4) {
		$colspan = mysql_num_rows($find_avs);
	} else {
		$colspan = 4;
	}

	// time to get the pagenumbers... first find avatars total
	$avatar_total = query("SELECT * FROM avatars ORDER BY display_order");

	$numAvatars = mysql_num_rows($avatar_total);

	// get the number of pages...
	$numberPages = $numAvatars / $perpage;

	// set that to int.. get rid of decimals
	settype($numberPages,int);

	// if the mod isn't ZERO.. then we add a page
	if(($numAvatars % $perpage) != 0) {
		$numberPages++;
	}

	// get current page
	$currentPage = ($start_count + $perpage) / $perpage;

	if($numberPages > 1) {
		// now get all the page numbers
		for($x = 1; $x <= $numberPages; $x++) {
			// get start count
			$starting_count = ($x * $perpage) - $perpage;

			// disabled?
			if($currentPage == $x) {
				$disabled = " disabled=\"disabled\"";
			} else {
				$disabled = "";
			}

			$pageNumbers .= "<button type=\"button\" onclick=\"location.href='moderator.php?do=change_avatar&userid=".$_GET['userid']."&per_page=".$perpage."&start=".$starting_count."';\" style=\"padding: 1px;\" ".$submitbg.$disabled.">".$x."</button>&nbsp; ";
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Change User Avatar");

	construct_title("Change User Avatar");

	// make sure there are avatars...
	if(!mysql_num_rows($find_avs)) {
		print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
		print("No Avatars exist.");
		print("\n\n</div>\n</div>\n<br />\n\n");
	}

	else {

		?>
		<form method="post" action="" name="avatar_manager" style="margin: 0;">
		<br /><input type="hidden" name="avatar_manager[set_form]" value="1" />

		<table border="0" cellspacing="0" cellpadding="4" class="options" style="background-color: #F8F8F8; border-left: 1px solid #000000; border-right: 1px solid #000000;">

		<tr>
			<td class="header" colspan="<?php print($colspan); ?>" style="border-left: none; border-right: none;">Avatar Manager</td>
		</tr>

		<?php

		// loop through avatars
		for($x = 1; $avatarinfo = mysql_fetch_array($find_avs); $x++) {
			// get prefix
			$check = substr($avatarinfo['filepath'],0,7);

			if($check == "http://") {
				$prefix = "";
			} else {
				$prefix = "../";
			}

			if(($x % 2) == 0) {
				$backgroundColor = "#F8F8F8";
			} else {
				$backgroundColor = "#ffffff";
			}

			if($x == 1) {
				$selected = " checked=\"checked\"";
			} else {
				$selected = "";
			}

			print("\t\t<td style=\"background-color: ".$backgroundColor."; text-align: center; white-space: nowrap; padding-bottom: 10px; padding-top: 10px;\">\n");
				print("\t\t\t<label for=\"".$avatarinfo['filepath']."\"><strong>".$avatarinfo['title']."</strong><br /><br />\n");
				print("\t\t\t<img src=\"".$prefix.$avatarinfo['filepath']."\" id=\"".$avatarinfo['filepath']."\" alt=\"".$avatarinfo['title']."\" style=\"border: none;\" /><br /><br />\n");
				print("\t\t\t<input type=\"radio\" id=\"".$avatarinfo['filepath']."\" name=\"avatar_manager[avatar]\" value=\"".$avatarinfo['avatarid']."\"".$selected." /></label>\n");
			print("\t\t</td>\n");

			// mod the counter by 5.. to see if we need a new row..
			$modulus = $x % 4;

			// if mod is 0.. than new row!
			if($modulus == 0) {
				print("\n\t</tr>\n\n");
				print("\n\t<tr>\n\n");
			}
		}

		print("\t<tr><td class=\"footer\" colspan=\"".$colspan."\" style=\"border-left: none; border-right: none;\">".$pageNumbers." &nbsp;&nbsp;&nbsp; Avatars Per Page: <input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"per_page\" value=\"".$perpage."\" /> <button type=\"button\" onclick=\"location.href='moderator.php?do=change_avatar&userid=".$_GET['userid']."&per_page=' + document.avatar_manager.per_page.value;\" style=\"margin-bottom: 1px;\" ".$submitbg.">Go</button></td></tr>\n");

		construct_table_END();
	}

	if(!mysql_num_rows($find_avs)) {
		$selected2 = " checked=\"checked\"";
	} else {
		$selected2 = "";
	}

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");

	if($userinfo['avatar_url'] != "none") {
		// get prefix
		$check = substr($userinfo['avatar_url'],0,7);

		if($check == "http://") {
			$prefix = "";
		} else {
			$prefix = "../";
		}

		print("\n\n<br />Current Avatar: &nbsp;&nbsp;&nbsp; <img src=\"".$prefix.$userinfo['avatar_url']."\" alt=\"".$userinfo['avatar_url']."\" style=\"border: none;\" /><br /><br />\n\n");

		print("<label for=\"delete_avatar\"><input type=\"radio\" id=\"delete_avatar\" name=\"avatar_manager[avatar]\" value=\"delete\" /> Check this option if you wish to remove this user's avatar.</label><br />\n\n");
	}

	print("<br /><label for=\"custom_avatar\"><input type=\"radio\" id=\"custom_avatar\" name=\"avatar_manager[avatar]\" value=\"custom\"".$selected2." /> Check this option if you wish to use an avatar specified by a URL below, and not listed above.</label> <br /><br />");

	print("<strong>Custom Avatar URL:</strong> <input type=\"text\" class=\"text\" name=\"avatar_manager[custom_url]\" value=\"http://\" style=\"width: 20%; padding: 2px;\" />\n\n");

	print("<br /><div style=\"text-align: center;\"><button type=\"submit\" ".$submitbg.">Submit</button>");

	print("</div></div>\n\n<br />\n\n");

	print("\n\n</form>\n\n");

	// do footer
	admin_footer();
}

// ##### DO RESTORE USER ##### \\

else if($_GET['do'] == "restore" AND $canRestore == true) {
	// make sure we have a valid user to restore...
	$userinfo_check = query("SELECT * FROM user_info WHERE userid = '".$_GET['id']."' LIMIT 1");

	// no user :(
	if(!mysql_num_rows($userinfo_check)) {
		construct_error("Sorry, no user with that userid exists.");
		exit;
	}

	// array
	$userinfo = mysql_fetch_array($userinfo_check);

	// otherwise we are good to go.. construct_confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// put user in that default usergroup specified in wtcBBoptions!
			query("UPDATE user_info SET usergroupid = '".$bboptions['usergroup_redirect']."' WHERE userid = '".$_GET['id']."'");

			redirect("thankyou.php?message=You have successfully restored <em>".$userinfo['username']."</em>. You will now be redirected back to the view banned users page.&uri=moderator.php?do=view_banned");
		}

		// no...
		else {
			redirect("moderator.php?do=view_banned");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to restore ".$userinfo['username']."?");
}

// ##### DO VIEW BANNED USERS ##### \\
else if($_GET['do'] == "view_banned") {
	// get banned usergroups...
	$usergroupinfo_query = query("SELECT * FROM usergroups where is_banned = '1'");

	// if no usergroups exist... exit before we get an error!
	if(!mysql_num_rows($usergroupinfo_query)) {
		construct_error("Sorry no banned usergroups exist.");
		exit;
	}
	
	// intiate counter
	$x = 1;

	// loop through usergroups to find each id...
	while($usergroupinfo = mysql_fetch_array($usergroupinfo_query)) {
		// get comma
		if($x == 1) {
			$comma = "";
		} else {
			$comma = ",";
		}

		$id .= $comma.$usergroupinfo['usergroupid'];
		$x++;
	}

	// split the id
	$id_array = split(",",$id);

	// intiate counter
	$y = 1;

	// loop through array to form WHERE segment of query
	foreach($id_array as $option_key => $option_value) {
		// get OR
		if($y == 1) {
			$or = "";
		} else {
			$or = " OR ";
		}

		$segment = $or."usergroupid = '".$option_value."'";
	}

	// do userinfo query
	$userinfo_query = query("SELECT * FROM user_info WHERE ".$segment);

	// if there are no banned users... exit
	if(!mysql_num_rows($userinfo_query)) {
		construct_error("Sorry there are no banned users.");
		exit;
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Banned Users");

	construct_title("Banned Users");

	construct_table("options","ban_user","ban_submit");

	construct_header("Banned Users",6);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; background: #606B8B; text-align: center; width: 30%; font-weight: bold; color: #ffffff;\">\n");
		print("\t\t\tUser\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
		print("\t\t\tE-mail\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
		print("\t\t\tJoin Date\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
		print("\t\t\tLast Visit\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
		print("\t\t\tPosts\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

		print("\t</tr>\n\n");

		while($userinfo = mysql_fetch_array($userinfo_query)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"moderator.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"mailto:".$userinfo['email']."\">".$userinfo['email']."</a>\n");
				print("\t\t</td>\n");

				// get join date...
				$userinfo['date_joined'] = date("m-d-y",$userinfo['date_joined']);
				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$userinfo['date_joined']."\n");
				print("\t\t</td>\n");

				// get last visit
				$userinfo['lastvisit'] = date("m-d-y",$userinfo['lastvisit']);
				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$userinfo['lastvisit']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$userinfo['posts']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<form action=\"\" style=\"margin: 0px; padding: 0px;\" method=\"post\">\n");
					print("\t\t\t<button type=\"button\" onclick=\"location.href='moderator.php?do=restore&id=".$userinfo['userid']."';\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Restore</button> <button type=\"button\" onclick=\"location.href='moderator.php?do=edit&id=".$userinfo['userid']."'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button>\n");
					print("\t\t\t</form>\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"6\">&nbsp;</td></tr>\n");

	construct_table_END();

	// do footer
	admin_footer();
}

// ##### DO BAN USER ##### \\
else if($_GET['do'] == "ban" AND $canBan == true) {
	// do the form stuff
	if($_POST['ban_user']['set_form']) {
		// make sure they have entered in a valid username
		$username_query = query("SELECT * FROM user_info WHERE username = '".$_POST['ban_user']['username']."' LIMIT 1");
		$userinfo = mysql_fetch_array($username_query);

		//$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$userinfo['usergroupid']."' LIMIT 1",1);
		$usergroupinfo = $usergroupinfo[$userinfo['usergroupid']];

		if(!mysql_num_rows($username_query)) {
			construct_error("You have entered an invalid username. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		else if(isUndeletable($userinfo['userid'])) {
			construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		else if($usergroupinfo['is_admin'] OR $usergroupinfo['is_super_moderator'] OR $userinfo['userid'] == $_COOKIE['wtcBB_adminUserid']) {
			construct_error("You cannot ban Administrators, Super Moderators, or yourself. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// we are good to go! 
		else {
			redirect("moderator.php?do=ban&ban_username=".$_POST['ban_user']['username']."&ban_usergroup=".$_POST['ban_user']['usergroupid']);
		}
	}

	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// run query to ban user
			query("UPDATE user_info SET usergroupid = '".$_GET['ban_usergroup']."' WHERE username = '".$_GET['ban_username']."'");

			redirect("thankyou.php?message=You have successfully banned <em>".$_GET['ban_username']."</em>. You will now be redirected back to the user ban page.&uri=moderator.php?do=ban");
		}

		// no...
		else {
			redirect("moderator.php?do=ban");
		}
	}

	if(isset($_GET['ban_username']) AND isset($_GET['ban_usergroup'])) {	
		// do a confirm page...
		construct_confirm("Are you sure you want to ban ".$_GET['ban_username']."?");
		exit;
	}

	// make sure there are banned usergroups... use this query for getting banned usergroups in select menu as well
	// get all usergroups
	$usergroup_select = query("SELECT * FROM usergroups WHERE is_banned = '1' ORDER BY name ASC");

	if(!mysql_num_rows($usergroup_select)) {
		construct_error("There are currently no \"Banned Usergroups\". Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
		exit;
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Ban User");

	construct_title("Ban User");

	construct_table("options","ban_user","ban_submit",1);

	construct_header("Ban User",2);

	construct_text(1,"Ban User","Enter here the <em>exact</em> username of the user you want banned.","ban_user","username");

	construct_select_begin(2,"Usergroup","Select the usergroup that you wish to ban this user to. <em>(Note: Only usergroups that are considered as a \"Banned Usergroup\" are shown here.)</em>","ban_user","usergroupid",1);

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			print("<option value=\"".$usergroup['usergroupid']."\">".$usergroup['name']."</option>\n");
		}

	construct_select_end(2,1);

	construct_footer(2,"ban_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

else if($_GET['do'] == "search") {
	// translate to unix timestamp with mktime
	$search_user['birthday'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);
	$search_user['date_joined'] = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
	$search_user['lastvisit'] = mktime($_POST['hour2'],$_POST['minute2'],0,$_POST['month2'],$_POST['day2'],$_POST['year2']);
	$search_user['lastactivity'] = mktime($_POST['hour3'],$_POST['minute3'],0,$_POST['month3'],$_POST['day3'],$_POST['year3']);
	$search_user['lastpost'] = mktime($_POST['hour4'],$_POST['minute4'],0,$_POST['month4'],$_POST['day4'],$_POST['year4']);

	// start searching
	if($_REQUEST['search_user']['set_form']) {
		// intiate counter
		$x = 1;

		// let's loop through the search_user to get the fields...
		foreach($_REQUEST['search_user'] as $option_key => $option_value) {
			if($option_key != "set_form") {
				// make sure we are dealing with a non-empty variable...
				if(($option_value == '0') OR (!empty($option_value) AND $option_value != '-1')) {

					if($x != 1) {
						$beginning = " AND ";
					} else {
						$beginning = " ";
					}

					// if it's the username, email address, parent email, usertitle, AIM, YAHOO, MSN, biography, location, occupation, or interests...
					if($option_key == "username" OR $option_key == "email" OR $option_key == "parent_email" OR $option_key == "usertitle" OR $option_key == "aim" OR $option_key == "msn" OR $option_key == "yahoo" OR $option_key == "biography" OR $option_key == "locationUser" OR $option_key == "interests" OR $option_key == "occupation") {
						$search_by .= $beginning.$option_key." LIKE '%".$option_value."%'";
						$x++;
					}

					else if($option_key == "usergroupid") {
						if($option_value != "all") {
							$search_by .= $beginning.$option_key." = '".$option_value."'";
							$x++;
						}
					}

					else if($option_value == "either") {
						$search_by .= $beginning."(".$option_key." = '1' OR ".$option_key." = '0')";
						$x++;
					}

					else {
						$search_by .= $beginning.$option_key." = '".$option_value."'";
						$x++;
					}
				}
			}
		}

		$total_query = "SELECT * FROM user_info WHERE".$search_by." ORDER BY username";

		//print($total_query);
		
		// run the query
		$userinfo_query = query($total_query);

		if(empty($search_by)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// uh oh! no users found!
		else if(!mysql_num_rows($userinfo_query)) {
			construct_error("Sorry, no users were found with that criteria. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// if only one match is found.. just redirect right to that user's info...
		else if(mysql_num_rows($userinfo_query)) {
			// just get the id...
			$user_id = mysql_fetch_array($userinfo_query);
			redirect("moderator.php?do=edit&id=".$user_id['userid']);
		}

		else {
			// do header
			admin_header("wtcBB Admin Panel - Users - Select User to Edit");

			construct_title("Select a User to Edit");

			construct_table("options","userinfo_form","userinfo_submit");
			construct_header("Search Results: ".mysql_num_rows($userinfo_query)." Users Found",6);

			print("\n\n\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; background: #606B8B; text-align: center; width: 30%; font-weight: bold; color: #ffffff;\">\n");
			print("\t\t\tUser\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
			print("\t\t\tE-mail\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
			print("\t\t\tJoin Date\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
			print("\t\t\tLast Visit\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
			print("\t\t\tPosts\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; border-left: none; text-align: center; background: #606B8B; width: 30%; font-weight: bold; color: #ffffff;\">\n");
			print("\t\t\tOptions\n");
			print("\t\t</td>\n");

			print("\t</tr>\n\n");

			while($userinfo = mysql_fetch_array($userinfo_query)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<a href=\"moderator.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<a href=\"mailto:".$userinfo['email']."\">".$userinfo['email']."</a>\n");
					print("\t\t</td>\n");

					// get join date...
					$userinfo['date_joined'] = date("m-d-y",$userinfo['date_joined']);
					print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t".$userinfo['date_joined']."\n");
					print("\t\t</td>\n");

					// get last visit
					$userinfo['lastvisit'] = date("m-d-y",$userinfo['lastvisit']);
					print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t".$userinfo['lastvisit']."\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t".$userinfo['posts']."\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<form action=\"\" style=\"margin: 0px; padding: 0px;\" method=\"post\">\n");
						print("\t\t\t<button type=\"button\" onclick=\"location.href='moderator.php?do=edit&id=".$userinfo['userid']."'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button>\n");
						print("\t\t\t</form>\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}

			print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"6\">&nbsp;</td></tr>\n");
			construct_table_END();

			// do footer
			admin_footer();

			exit;

		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Search for User");

	construct_title("Search for User");

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
	print("All fields left blank will be ignored in the search. You may specify more than one field to search by.");
	print("</div></div>\n\n<br />\n\n");

	construct_table("options","search_user","user_submit",1);
	construct_header("Search for User",2);

	construct_text(1,"Username","Enter here the username of the new user you are creating.","search_user","username","");

	construct_text(2,"E-mail Address","Enter here the e-mail address of the new user you are creating.","search_user","email","");

	construct_text(1,"Parent E-mail Address","Enter here the e-mail address of the parent of this user.","search_user","parent_email","");

	construct_text(2,"User Title","Input here the user title in which will be shown in this user's profile, and below this user's username in posts.","search_user","usertitle","");

	construct_select_begin(1,"Usergroup","","search_user","usergroupid");

		// get all usergroups
		$usergroup_select = query("SELECT * FROM usergroups ORDER BY name ASC");

		print("<option value=\"all\" selected=\"selected\">All Usergroups</option>\n");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			print("<option value=\"".$usergroup['usergroupid']."\">".$usergroup['name']."</option>\n");
		}

	construct_select_end(1);

	?>
	<tr>
		<td class="desc2">
			<b>COPPA User</b>
		</td>

		<td class="input2">
			<label for="is_coppa1"><input type="radio" name="search_user[is_coppa]" id="is_coppa1" value="1" /> Yes</label>
			<label for="is_coppa2"><input type="radio" name="search_user[is_coppa]" id="is_coppa2" value="0" /> No</label>
			<label for="is_coppa3"><input type="radio" name="search_user[is_coppa]" id="is_coppa3" value="either" checked="checked" /> Either</label>
		</td>
	</tr>
	<?php

	construct_text(1,"Homepage","","search_user","homepage","");

	construct_text(2,"AOL Instant Messenger Handle","","search_user","aim","");

	construct_text(1,"MSN Handle","","search_user","msn","");

	construct_text(2,"Yahoo Messenger Handle","","search_user","yahoo","");

	construct_text(1,"ICQ Handle","","search_user","icq","");


	construct_select_begin(1,"Birthday","","search_user","birthday",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		print("<input type=\"text\" name=\"day\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		print("<input type=\"text\" name=\"year\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(1,1);


	construct_text(2,"IP Address","","search_user","user_ip_address","");

	construct_text(1,"Post Count","","search_user","posts","");

	construct_text(2,"Referrer","","search_user","referral_username");

	construct_text(1,"Referrals","","search_user","referrals","",1);

	
	
	// ##### TIME OPTIONS ##### \\

	construct_header("Time Options",2);

	construct_select_begin(2,"Join Date","","search_user","date_joined",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month1\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day1\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td></tr></table>\n");

	construct_select_end(2,1);


	construct_select_begin(1,"Last Visit","","search_user","lastvisit",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month2\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Hour<br />\n");

		// get hours
		$hours = date("h");
		print("<input type=\"text\" name=\"hour2\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Minute<br />\n");

		// get minutes
		$minutes = date("i");
		print("<input type=\"text\" name=\"minute2\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Last Activity","","search_user","lastactivity",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month3\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day3\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year3\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Hour<br />\n");

		// get hours
		$hours = date("h");
		print("<input type=\"text\" name=\"hour3\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Minute<br />\n");

		// get minutes
		$minutes = date("i");
		print("<input type=\"text\" name=\"minute3\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(2,1);


	construct_select_begin(1,"Last Post","","search_user","lastpost",1,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month4\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day4\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year4\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Hour<br />\n");

		print("<input type=\"text\" name=\"hour4\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Minute<br />\n");

		print("<input type=\"text\" name=\"minute4\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	// ##### PROFILE FIELD OPTIONS ##### \\

	construct_header("Profile Field Options",2);

	construct_text(1,"Biography","","search_user","biography");
	construct_text(2,"Location","","search_user","locationUser");
	construct_text(1,"Interests","","search_user","interests");
	construct_text(2,"Occupation","","search_user","occupation","",1);

	?>
	<tr>
		<td class="footer" colspan="2"><pre><button type="submit" id="user_submit" <?php print($submitbg); ?>>Search</button>  <button type="reset" <?php print($submitbg); ?>>Reset</button></pre></td>
	</tr>
	<?php

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO EDIT USER ##### \\
else if($_GET['do'] == "edit" AND ($canEditSigs OR $canEditAvs)) {
	// get userinfo...
	$userinfo_query = query("SELECT * FROM user_info WHERE userid = '".$_GET['id']."' LIMIT 1");

	if(!mysql_num_rows($userinfo_query)) {
		construct_error("Sorry, the user you are trying to edit does not exist.");
		exit;
	}

	$userinfo = mysql_fetch_array($userinfo_query);
	//$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$userinfo['usergroupid']."'",1);
	$usergroupinfo = $usergroupinfo[$userinfo['usergroupid']];
	
	// time to update the db
	if($_POST['edit_user']['set_form']) {
		// wait.. make sure we aren't editing an undeletable user!
		if(isUndeletable($userinfo['userid'])) {
			construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file.");
			exit;
		}

		else if($usergroupinfo['is_admin'] == 1 OR $usergroupinfo['is_super_moderator'] == 1) {
			construct_error("You cannot edit Administrators or Super Moderators. <a href=\"javascript:history.back();\">Go Back.</a>");
			exit;
		}

		// intialize beginning of query
		$query = "UPDATE user_info SET";

		// intialize counter
		$x = 1;

		foreach($_POST['edit_user'] as $option_key => $option_value) {
			if($option_key != "set_form") {
				if($x == 1) {
					$comma = "";
				} else {
					$comma = ",";
				}

				if($option_key != "signature") {
					$option_value = htmlspecialchars($option_value);
				}

				$query .= " ".$comma." ".$option_key." = '".addslashes($option_value)."'";

				$x++;
			}
		}

		$query .= " WHERE userid = '".$userinfo['userid']."'";

		//print($query);

		// run query
		query($query);

		$uri = "moderator.php?do=editSTEVEid=".$userinfo['userid'];

		redirect("thankyou.php?message=Thank you for editing <em>".$_POST['edit_user']['username']."</em>. You will now be redirected back to ".$_POST['edit_user']['username']."\'s user info page.&uri=".$uri);
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Edit User \"".$userinfo['username']."\"");

	construct_title("Edit User <em>".$userinfo['username']."</em> <span class=\"small\">(id: ".$userinfo['userid'].")</span>");

	construct_table("options","edit_user","user_submit",1);
	construct_header("General Information",2);

	// if allowed
	if($canEditSigs) {
		construct_textarea(1,"Signature","This is this user's signature. It will appear under every post they make.","edit_user","signature",htmlspecialchars($userinfo['signature']));
	}

	// if allowed
	if($canEditAvs) {
		// get prefix
		$check = substr($userinfo['avatar_url'],0,7);

		if($check == "http://") {
			$prefix = "";
		} else {
			$prefix = "../";
		}

		?>
		<tr>
			<td class="desc2_bottom">
				<b>Avatar</b> <br /> <span class="small"></span>
			</td>

			<td class="input2_bottom">
				<?php 
				if($userinfo['avatar_url'] != "none") { 
					print("<img src=\"".$prefix.$userinfo['avatar_url']."\" alt=\"".$userinfo['avatar_url']."\" style=\"border: none;\" />&nbsp;&nbsp;&nbsp; \n");
				}
				?>
				<button type="button" onclick="location.href='moderator.php?do=change_avatar&userid=<?php print($userinfo['userid']); ?>';" <?php print($submitbg); ?>>Change Avatar</button>
			</td>
		</tr>
		<?php
	}

	construct_footer(2,"user_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO MASS PRUNE THREADS ##### \\
else if($_GET['do'] == "threads" AND ($canMassPrune OR $canMassMove)) {
	if($_POST['massprune_threads']['set_form']) {
		// get timestamps for before and after...
		$before = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$after = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);

		$searchForumid = $_POST['massprune_threads']['forum'];
		$searchUsername = $_POST['massprune_threads']['threadUsername'];

		if(!empty($searchUsername)) {
			// get userid
			$useridINFO_q = query("SELECT * FROM user_info WHERE username = '".$searchUsername."' LIMIT 1");

			if(!mysql_num_rows($useridINFO_q)) {
				$searchUsername = "";
			} else {
				$useridINFO = mysql_fetch_array($useridINFO_q);

				$query = "AND thread_starter = ".$useridINFO['userid']." ";
			}
		} else {
			$query = "";
		}

		if($searchForumid != -1) {
			$query .= "AND forumid = '".$searchForumid."' ";
		}

		if($_POST['day1']) {
			$query .= "AND date_made < '".$before."' ";
		}

		if($_POST['day2']) {
			$query .= "AND date_made > '".$after."' ";
		}

		if(empty($query)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// remove first AND
		$query = preg_replace("|^AND|","",$query);

		// select
		$deletedThreads = query("SELECT * FROM threads WHERE ".$query);

		//print("SELECT * FROM threads WHERE ".$query);

		// if rows.. go through and delete threads, posts, polls, and attachments
		if(mysql_num_rows($deletedThreads)) {
			while($threadinfo = mysql_fetch_array($deletedThreads)) {
				// delete posts
				query("DELETE FROM posts WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM poll WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM poll_options WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM attachments WHERE attachmentthread = '".$threadinfo['threadid']."'");
				query("DELETE FROM thread_subscription WHERE threadid = '".$threadinfo['threadid']."'");
				query("DELETE FROM threads WHERE threadid = '".$threadinfo['threadid']."' LIMIT 1");
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully deleted <strong>".mysql_num_rows($deletedThreads)."</strong> threads. You will now be redirected back.&uri=moderator.php?do=threads");
	}

	else if($_POST['massmove_threads']['set_form']) {
		// get timestamps for before and after...
		$before = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$after = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);

		$searchForumid = $_POST['massmove_threads']['forum_begin'];
		$searchUsername = $_POST['massmove_threads']['threadUsername'];

		if(!empty($searchUsername)) {
			// get userid
			$useridINFO_q = query("SELECT * FROM user_info WHERE username = '".$searchUsername."' LIMIT 1");

			if(!mysql_num_rows($useridINFO_q)) {
				$searchUsername = "";
			} else {
				$useridINFO = mysql_fetch_array($useridINFO_q);

				$query = "AND thread_starter = ".$useridINFO['userid']." ";
			}
		} else {
			$query = "";
		}

		if($searchForumid != -1) {
			$query .= "AND forumid = '".$searchForumid."' ";
		}

		if($_POST['day1']) {
			$query .= "AND date_made < '".$before."' ";
		}

		if($_POST['day2']) {
			$query .= "AND date_made > '".$after."' ";
		}

		if(empty($query)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// remove first AND
		$query = preg_replace("|^AND|","",$query);

		// select
		$movedThreads = query("SELECT * FROM threads WHERE ".$query);

		//print("SELECT * FROM threads WHERE ".$query);

		// if rows.. go through and delete threads, posts, polls, and attachments
		if(mysql_num_rows($movedThreads)) {
			while($threadinfo = mysql_fetch_array($movedThreads)) {
				// first update thread with new forum...
				query("UPDATE threads SET forumid = '".$massmove_threads['forum_destination']."' WHERE threadid = '".$threadinfo['threadid']."'");

				// update post forum ids
				query("UPDATE posts SET forumid = '".$massmove_threads['forum_destination']."' WHERE threadid = '".$threadinfo['threadid']."'");				
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully moved <strong>".mysql_num_rows($movedThreads)."</strong> threads. You will now be redirected back.&uri=moderator.php?do=threads");
	}

	// do header
	admin_header("wtcBB Admin Panel - Mass Prune - Threads");

	construct_title("Mass Move/Prune Threads");

		construct_table("options","massprune_threads","massprune_submit",1);

		construct_header("Mass Prune Threads",2);

	
	if($canMassPrune) {
		construct_select_begin(1,"Thread Made Before","","massprune_threads","beforeDate",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month1\">\n");
			construct_select_months(0,0,0,1);
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");
			print("<input type=\"text\" name=\"day1\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");
			print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(1,1);


		construct_select_begin(2,"Thread Made After","","massprune_threads","afterDate",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month2\">\n");
			construct_select_months(0,0,0,1);
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");
			print("<input type=\"text\" name=\"day2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");
			print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(2,1);

		construct_select(1,"Forum","Select here the forum you wish for the search to be in. Select \"All Forums\" for it to be a global search in all forums.","massprune_threads","forum","",0,0,0,2);

		construct_text(2,"Delete threads started only by this user","Leave blank for this search to delete threads started by all users.","massprune_threads","threadUsername","",1);

		construct_footer(2,"massprune_submit");

		construct_table_END(1);


		print("\n\n<br /><br />\n\n");
	}


	if($canMassMove) {
		construct_table("options","massmove_threads","massmove_submit",1);

		construct_header("Mass Move Threads",2);


		construct_select_begin(1,"Thread Made Before","","massmove_threads","beforeDate",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month1\">\n");
			construct_select_months(0,0,0,1);
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");
			print("<input type=\"text\" name=\"day1\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");
			print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(1,1);


		construct_select_begin(2,"Thread Made After","","massmove_threads","afterDate",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month2\">\n");
			construct_select_months(0,0,0,1);
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");
			print("<input type=\"text\" name=\"day2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");
			print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(2,1);

		construct_select(1,"Start Forum","Select the forum you wish to move posts from.","massmove_threads","forum_begin","",0,0,0,3);

		construct_select(2,"Destination Forum","Select the forum you wish to move posts to.","massmove_threads","forum_destination","",0,0,0,3);

		construct_text(1,"Move threads started only by this user","Leave blank for this search to move threads started by all users.","massmove_threads","threadUsername","",1);

		construct_footer(2,"massmove_submit");

		construct_table_END(1);
	}

	// do footer
	admin_footer();
}

// ##### DO MASS PRUNE POSTS ##### \\
else if($_GET['do'] == "posts" AND $canMassPrune) {
	if(isset($massprune_posts['set_form'])) {
		// get timestamps for before and after...
		$before = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$after = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);

		$searchForumid = $_POST['massprune_posts']['forum'];
		$searchUsername = $_POST['massprune_posts']['threadUsername'];

		if(!empty($searchUsername)) {
			// get userid
			$useridINFO_q = query("SELECT * FROM user_info WHERE username = '".$searchUsername."' LIMIT 1");

			if(!mysql_num_rows($useridINFO_q)) {
				$searchUsername = "";
			} else {
				$useridINFO = mysql_fetch_array($useridINFO_q);

				$query = "AND userid = ".$useridINFO['userid']." ";
			}
		} else {
			$query = "";
		}

		if($searchForumid != -1) {
			$query .= "AND forumid = '".$searchForumid."' ";
		}

		if($_POST['day1']) {
			$query .= "AND date_posted > '".$before."' ";
		}

		if($_POST['day2']) {
			$query .= "AND date_posted < '".$after."' ";
		}

		if(empty($query)) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// remove first AND
		$query = preg_replace("|^AND|","",$query);

		// select
		$deletedPosts = query("SELECT * FROM posts WHERE ".$query);

		// if rows.. go through and delete threads, posts, polls, and attachments
		if(mysql_num_rows($deletedPosts)) {
			while($postinfo = mysql_fetch_array($deletedPosts)) {
				// delete posts and possibly threads!
				$deletedThread = query("SELECT * FROM threads WHERE first_post = '".$postinfo['postid']."'");

				// if the above has deleted a thread... delete all posts in that thread
				if(mysql_num_rows($deletedThread) > 0) {
					$threadinfo = mysql_fetch_array($deletedThread);
					
					query("DELETE FROM posts WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM poll WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM poll_options WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM attachments WHERE attachmentthread = '".$threadinfo['threadid']."'");
					query("DELETE FROM thread_subscriptions WHERE threadid = '".$threadinfo['threadid']."'");
					query("DELETE FROM threads WHERE threadid = '".$threadinfo['threadid']."' LIMIT 1");
				}

				else {
					query("DELETE FROM attachments WHERE attachmentpost = '".$postinfo['postid']."'");
					query("DELETE FROM posts WHERE postid = '".$postinfo['postid']."'");
				}
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully deleted <strong>".mysql_num_rows($deletedPosts)."</strong> posts. You will now be redirected back.&uri=moderator.php?do=posts");
	}

	// do header
	admin_header("wtcBB Admin Panel - Mass Prune - Posts");

	construct_title("Mass Prune Posts");

	construct_table("options","massprune_posts","massprune_submit",1);

	construct_header("Mass Prune Posts",2);


	construct_select_begin(1,"Post Made Before","","massprune_posts","beforeDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month1\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day1\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Post Made After","","massprune_posts","afterDate",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month2\">\n");
		construct_select_months(0,0,0,1);
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");
		print("<input type=\"text\" name=\"day2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");
		print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(2,1);

	construct_select(1,"Forum","Select here the forum you wish for the search to be in. Select \"All Forums\" for it to be a global search in all forums.","massprune_posts","forum","",0,0,0,2);

	construct_text(2,"Delete posts made only by this user","Leave blank for this search to delete posts made by all users.","massprune_posts","threadUsername","",1);

	construct_footer(2,"massprune_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// do add announcement
else if($_GET['do'] == "add" AND $_GET['f']) {

	$canAnnounce = false;

	// what if no perms?
	foreach($modinfo[$_GET['f']] as $modid => $arr) {
		if($arr['userid'] == $_COOKIE['wtcBB_adminUserid'] AND $arr['can_post_announcements']) {
			$canAnnounce = true;
		}
	}

	if($canAnnounce == false) {
		construct_error("You may not make announcements in this forum.");
		exit;
	}

	// translate to unix timestamp with mktime
	$_POST['announce']['start_date'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);
	$_POST['announce']['end_date'] = mktime(0,0,0,$_POST['month_end'],$_POST['day_end'],$_POST['year_end']);

	// going to update information...
	if($_POST['announce']['set_form']) {

		print("<br /><br />");

		// set counter
		$i = 0;

		$_POST['announce']['forum'] = $_GET['f'];

		// intialize the $query var
		$query = "INSERT INTO announcements (username,userid,date_addedUpdated,";

		foreach($_POST['announce'] as $option_key => $option_value) {
			if($option_key != "set_form") {
				// look for comma
				if($i > 0) {
					$comma = ",";
				} else {
					$comma = "";
					$i++;
				}

				$query .= $comma.$option_key;
			}
		}

		$query .= ") VALUES ('".$_COOKIE['wtcBB_adminUsername']."','".$_COOKIE['wtcBB_adminUserid']."','".time()."',";

		// reset counter...
		$i = 0;

		foreach($_POST['announce'] as $option_key => $option_value) {
			if($option_key != "set_form") {
				// look for comma
				if($i > 0) {
					$comma = ",";
				} else {
					$comma = "";
					$i++;
				}

				$query .= $comma."'".htmlspecialchars(addslashes($option_value))."'";
			}
		}

		$query .= ")";

		// update the DB
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding an announcement. You will now be redirected back.&uri=moderator.php?do=edit_announcements");

		/*print("<br /><br />");

		print($query);

		print("<br /><br />");*/
	}

	// do header
	admin_header("wtcBB Admin Panel - Add Announcement");

	construct_title("Add Announcement");

	construct_table("options","announce","announce_submit",1);
	construct_header("Add Announcement",2);

	construct_text(2,"Title","Input here the title of your announcement.","announce","title","");

	construct_select_begin(1,"Start date","Input here the start date in which you want the announcement to be displayed.","announce","start_date",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month\">\n");
		construct_select_months();
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(1,1);

	construct_select_begin(2,"End date","Input here the date in which the announcement will be deleted, and no longer in effect.","announce","end_date",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month_end\">\n");
		construct_select_months(1);
		print("</select>\n\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// current month.. plus one
		$current_month = date("n");
		$current_month++;
	
		// get today
		$today = date("d");

		// get current date.. 
		$current_date = date("d",mktime(0,0,0,$current_month,$today,2003));

		print("<input type=\"text\" name=\"day_end\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year_end\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n\n");

	construct_select_end(2,1);

	construct_input(1,"Parse BB Code?","","announce","parse_bbcode",0,1);

	construct_input(2,"Parse Smilies?","","announce","parse_smilies",1,1);

	?>
	
	<tr>
		<td class="desc1_bottom" colspan="2" style="border-top: 1px solid #000000;">
			<b>Text:</b> <br /><br />
				
				<div align="center">
					<textarea name="announce[message]" cols="60" rows="13"></textarea>
				</div>

		</td>
	</tr>

	<?php

	construct_footer(2,"announce_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}

else if($_GET['do'] == "edit_announcement") {
	if($_GET['id'] AND $_GET['f']) {
		$canAnnounce = false;

		// what if no perms?
		foreach($modinfo[$_GET['f']] as $modid => $arr) {
			if($arr['userid'] == $_COOKIE['wtcBB_adminUserid'] AND $arr['can_post_announcements'] == 1) {
				$canAnnounce = true;
			}
		}

		if($canAnnounce == false) {
			construct_error("You may not make announcements in this forum.");
			exit;
		}

		// run the query using the ID...
		$check_and_run = query("SELECT * FROM announcements WHERE announcementid = '".$_GET['id']."'");

		// make sure it's valid...
		if(!mysql_num_rows($check_and_run)) {
			construct_error("Invalid announcement ID");
		}

		// otherwise we are good to go...
		else {
			// let's see if we want to delete an announcement...
			if($_GET['action'] == "delete") {
				// make sure form is set..
				if($_POST['confirm']['set_form']) {
					// yes...
					if($_POST['confirm']['yes_no']) {
						query("DELETE FROM announcements WHERE announcementid = '".$_GET['id']."' LIMIT 1");

						redirect("thankyou.php?message=Thank you for deleting the announcement. You will now be redirected back.&uri=moderator.php?do=edit_announcement");
					}

					// no...
					else {
						redirect("moderator.php?do=edit_announcement");
					}
				}
				
				// do a confirm page...
				construct_confirm();
			}

			// otherwise we are just editing it...
			else {
				// fetch the results of the previous query.. avoid running two
				$announce_stuff = mysql_fetch_array($check_and_run);

				// translate to unix timestamp with mktime
				$_POST['announce']['start_date'] = mktime(0,0,0,$_POST['month2'],$_POST['day2'],$_POST['year2']);
				$_POST['announce']['end_date'] = mktime(0,0,0,$_POST['month_end2'],$_POST['day_end2'],$_POST['year_end2']);

				// only do the below if the form is set...
				if($_POST['announce']['set_form']) {

					print("<br /><br />");

					// set counter
					$i = 0;

					// intialize the $query var
					$query = "UPDATE announcements SET ";

					foreach($_POST['announce'] as $option_key => $option_value) {
						// check to make sure we don't input the "set_form"
						if($option_key != "set_form") {
							// should we use comma?
							if($i == 0) {
								$comma = "";
							} else {
								$comma = ", ";
							}

							// form the update query...
							$query .= $comma;
							$query .= $option_key." = '".htmlspecialchars(addslashes($option_value))."'";

							// increment $i
							$i++;
						}
					} 

					$query .= " , username = '".$_COOKIE['wtcBB_adminUsername']."' , date_addedUpdated = '".time()."' , userid = '".$_COOKIE['wtcBB_adminUserid']."' WHERE announcementid = '".$_GET['id']."'";

					// update the DB
					query($query);

					// redirect to thankyou page...
					redirect("thankyou.php?message=Thank you for editing <b>".$announce_stuff['title']."</b>. You will now be redirected back.&uri=moderator.php?do=edit_announcement");

					/* print("<br /><br />");

					print($query);

					print("<br /><br />"); */
				}

				// alright now use the "add" interface for the edit...

				// do header
				admin_header("wtcBB Admin Panel - Edit Announcement");

				construct_title("Edit Announcement");

				print("\n\n<br />\n\n");

				construct_table("options","announce","announce_submit",1);
				construct_header("Add Announcement",2);

				construct_text(2,"Title","Input here the title of your announcement.","announce","title",$announce_stuff['title']);

				construct_select_begin(1,"Start date","Input here the start date in which you want the announcement to be displayed.","announce","start_date",0,1);

					print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Month<br />\n");

					print("<select name=\"month2\">\n");
					construct_select_months(0,1,1);
					print("</select>\n\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Day<br />\n");

					// get start date.. 
					$current_date = date("d",$announce_stuff['start_date']);

					print("<input type=\"text\" name=\"day2\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Year<br />\n");

					// get full year...
					$full_year = date("Y",$announce_stuff['start_date']);

					print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td></tr></table>\n\n");

				construct_select_end(1,1);


				construct_select_begin(2,"End date","Input here the date in which the announcement will be deleted, and no longer in effect.","announce","end_date",0,1);

					print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Month<br />\n");

					print("<select name=\"month_end2\">\n");
					construct_select_months(1,1,2);
					print("</select>\n\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Day<br />\n");

					// current month.. plus one
					$current_month = date("n");
					$current_month++;

					// get end date.. 
					$current_date = date("d",$announce_stuff['end_date']);

					print("<input type=\"text\" name=\"day_end2\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td>\n");


					print("<td style=\"text-align: left; font-size: 8pt;\">\n");
					print("Year<br />\n");

					// get full year...
					$full_year = date("Y",$announce_stuff['end_date']);

					print("<input type=\"text\" name=\"year_end2\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");

					print("</td></tr></table>\n\n");

				construct_select_end(2,1);

				construct_input(1,"Parse BB Code?","","announce","parse_bbcode",0,0,$announce_stuff);

				construct_input(2,"Parse Smilies?","","announce","parse_smilies",1,0,$announce_stuff);

				?>
				
				<tr>
					<td class="desc1_bottom" colspan="2" style="border-top: 1px solid #000000;">
						<b>Text:</b> <br /><br />
							
							<div align="center">
								<textarea name="announce[message]" cols="60" rows="13"><?php print($announce_stuff['message']); ?></textarea>
							</div>

					</td>
				</tr>

				<?php

				construct_footer(2,"announce_submit");
				construct_table_END(1);

				// do footer
				admin_footer();
			}
		}
	}

	// otherwise we are displaying the announcements to edit...
	else {
		if(!$canGenAnnounce) {
			construct_error("You may not edit announcements.");
			exit;
		}

		// do header
		admin_header("wtcBB Admin Panel - Edit Announcements");

		construct_title("Edit Announcements");

		// run the query..
		$run_query = query("SELECT * FROM announcements ORDER BY announcementid");

		// make sure we have announcements.. if not just return a message...
		if(!mysql_num_rows($run_query)) {
			print("<blockquote style=\"width: 90%; text-align: left;\">\n");
			print("<br />No announcements found in the database.");
			print("\n</blockquote>");
		}

		// otherwise we HAVE lift off!!!
		else {
			print("\n<br />\n");

			construct_table("options","announce","announce_submit");
			construct_header("Forum Specific Announcements",3);

			loop_announcements(-1,true);

			print("\t<tr><td class=\"footer\" colspan=\"3\" style=\"border-top: none;\">&nbsp;</td></tr>\n");
			construct_table_END();
		}

		// do footer
		admin_footer();
	}
}

else {
	construct_error("You do not have permission to view this page.");
}

?>