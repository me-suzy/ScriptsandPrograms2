<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################ //ADMIN PANEL USERGROUP\\ ################ \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Usergroups";
$permissions = "usergroups";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO ADMINISTRATOR PERMISIONS ##### \\
if($_GET['do'] == "admin_permissions") {
	// make sure we have a super admin...
	if(!isSuperAdmin()) {
		construct_error("Only Super Administrators defined by the <strong>\$super_administrator</strong> variable in the <strong>config.php</strong> file are allowed access to this page. <br /><br />If you feel that you should have access to this page, than simply open the <strong>config.php</strong> file inside the <em>includes</em> directory of the zip package you downloaded. Then add your userid (".$_COOKIE['wtcBB_adminUserid'].") to the variable, following the directions given inside the file.");
		
		exit;
	}

	// if id is set.. we're editing a user!
	if($_GET['userid']) {
		// make we have a valid id...
		$check_id = query("SELECT * FROM admin_permissions WHERE userid = '".$_GET['userid']."' LIMIT 1");

		// uh oh...
		if(!mysql_num_rows($check_id)) {
			construct_error("Sorry, no administrator with that userid exists.");
			exit;
		}

		// get permissions... use $check_id
		$adminPermissions = mysql_fetch_array($check_id);

		// make sure form is set
		if($_POST['edit_admin']['set_form']) {
			// intiate counter
			$x = 1;

			// start query
			$query = "UPDATE admin_permissions SET ";

			// loop through array
			foreach($_POST['edit_admin'] as $option_key => $option_value) {
				// make sure it isn't set_form
				if($option_key != "set_form") {
					// get comma
					if($x == 1) {
						$comma = "";
					} else {
						$comma = " , ";
					}

					// keep on forming query...
					$query .= $comma.$option_key." = '".$option_value."'";

					// increment counter
					$x++;
				}
			}

			// finish query
			$query .= " WHERE adminid = '".$adminPermissions['adminid']."'";

			//print($query);

			// run query
			query($query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Thank you for updating <em>".$adminPermissions['username']."</em>\'s administrator permissions. You will now be redirected back.&uri=usergroup.php?do=admin_permissions");
		}

		// do header
		admin_header("wtcBB Admin Panel - Usergroups - Administrator Permissions");

		construct_title("Edit Administrator Permissions for <em>".$adminPermissions['username']."</em>");

		construct_table("options","edit_admin","admin_submit",1);
		construct_header("Edit Administrator Permissions for <em>".$adminPermissions['username']."</em>",2);

		construct_input(1,"Can Administer wtcBB Options","","edit_admin","wtcBBoptions",0,0,$adminPermissions);

		construct_input(2,"Can Administer Announcements","","edit_admin","announcements",0,0,$adminPermissions);

		construct_input(1,"Can Administer Forums and Moderators","","edit_admin","forums_moderators",0,0,$adminPermissions);

		construct_input(2,"Can Administer Users","","edit_admin","users",0,0,$adminPermissions);

		construct_input(1,"Can Administer Usergroups","","edit_admin","usergroups",0,0,$adminPermissions);

		construct_input(2,"Can Administer Warning System","","edit_admin","warn",0,0,$adminPermissions);

		construct_input(1,"Can Administer Logs and Statistics","","edit_admin","logs_stats",0,0,$adminPermissions);

		construct_input(2,"Can Administer Avatars","","edit_admin","avatars",0,0,$adminPermissions);

		construct_input(1,"Can Administer Smilies","","edit_admin","smilies",0,0,$adminPermissions);

		construct_input(2,"Can Administer Post Icons","","edit_admin","post_icons",0,0,$adminPermissions);

		construct_input(1,"Can Administer Usertitles","","edit_admin","usertitles",0,0,$adminPermissions);

		construct_input(2,"Can Administer BBcode","","edit_admin","bbcode",0,0,$adminPermissions);

		construct_input(1,"Can Administer FAQ","","edit_admin","faq",0,0,$adminPermissions);

		construct_input(2,"Can Administer Styles","","edit_admin","styles",0,0,$adminPermissions);

		construct_input(1,"Can Administer Attachments","","edit_admin","attachments",0,0,$adminPermissions);

		construct_input(2,"Can Administer Threads &amp; Posts","","edit_admin","threads_posts",0,0,$adminPermissions);

		construct_input(1,"Can Update Information","","edit_admin","updateinfo",1,0,$adminPermissions);

		construct_footer(2,"admin_submit");
		construct_table_END(1);

		// do footer
		admin_footer();

		exit;
	}

	// get administrator usergroups...
	$admin_usergroups_query = query("SELECT * FROM usergroups WHERE is_admin = '1'");

	// do header
	admin_header("wtcBB Admin Panel - Usergroups - Administrator Permissions");

	construct_title("Select an Administrator to Edit Permissions");

	construct_table("options","admin_form","admin_submit",1);
	construct_header("Current Administrators",2);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tAdministrator\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n\n");

	print("\t</tr>\n\n");

	while($admin_usergroups = mysql_fetch_array($admin_usergroups_query)) {
		// get all users for this usergroup...
		$userinfo_query = query("SELECT * FROM user_info WHERE usergroupid = '".$admin_usergroups['usergroupid']."'");

		while($userinfo = mysql_fetch_array($userinfo_query)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: left; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<button type=\"button\" onClick=\"location.href='usergroup.php?do=admin_permissions&userid=".$userinfo['userid']."';\" ".$submitbg.">Edit Permissions</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='log.php?do=admin&admin_log%5Bset_form%5D=1&admin_log%5Bpage_num%5D=1&admin_log%5Btotal_pages%5D=&admin_log%5Borderby%5D=action_date&admin_log%5Bmadeby%5D=".$userinfo['username']."&admin_log%5Bentries_per_page%5D=15&start=0&admin_log%5Bscript%5D=all';\" ".$submitbg.">Admin Log</button>\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\">&nbsp;</td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO ADD USERGROUP ##### \\
else if($_GET['do'] == "add") {

	// only do the below if the form is set...
	if($_POST['add_usergroup']['set_form']) {
		print("<br /><br />");

		// set counter
		$i = 0;

		// intialize the $query var
		$query = "INSERT INTO usergroups (";

		foreach($_POST['add_usergroup'] as $option_key => $option_value) {
			// check to make sure we don't input the "set_form"
			if($option_key != "set_form" AND $option_key != "copy_forums_perms") {
				// should we use comma?
				if($i == 0) {
					$comma = "";
				} else {
					$comma = ",";
				}

				// form the insert query...
				$query .= $comma;
				$query .= $option_key;

				// increment $i
				$i++;
			}
		} 

		$query .= ") VALUES (";

		// reset i;
		$i = 0;

		foreach($_POST['add_usergroup'] as $option_key => $option_value) {
			// check to make sure we don't input the "set_form"
			if($option_key != "set_form" AND $option_key != "copy_forums_perms") {
				// show we use comma?
				if($i == 0) {
					$comma = "";
				} else {
					$comma = ",";
				}

				// form the insert query...
				$query .= $comma;

				// if it's usertitle OR the name stuff.. don't stip HTML...
				if($option_key == "usertitle" OR $option_key == "name_html_begin" OR $option_key == "name_html_end") {
					$query .= "'".addslashes($option_value)."'";
				}

				else {
					$query .= "'".addslashes(htmlspecialchars($option_value))."'";
				}

				// increment $i
				$i++;
			}
		}

		// finish off the query statement
		$query .= ")";

		// update the DB
		query($query);

		// get insert id...
		$usergroupInsertId = mysql_insert_id();

		// what if we want to copy forums perms?
		if($_POST['add_usergroup']['copy_forums_perms'] != -1) {
			// select all forum perms for that usergroup...
			$copyForumPermsQ = query("SELECT * FROM forums_permissions WHERE usergroupid = '".$_POST['add_usergroup']['copy_forums_perms']."'");

			// now loop through, and re-insert... ONLY changing usergroupid... if rows
			if(mysql_num_rows($copyForumPermsQ)) {	
				while($copyPerms = mysql_fetch_array($copyForumPermsQ)) {
					query("INSERT INTO forums_permissions (usergroupid,forumid,can_view_board,can_view_threads,can_view_deletion,can_search,can_attachments,can_post_threads,can_reply_own,can_reply_others,can_upload_attachments,can_edit_own,can_delete_threads_own,can_delete_own,can_close_own,can_post_polls,is_inherited,can_perm_delete,flood_immunity) VALUES ('".$usergroupInsertId."','".$copyPerms['forumid']."','".$copyPerms['can_view_board']."','".$copyPerms['can_view_threads']."','".$copyPerms['can_view_deletion']."','".$copyPerms['can_search']."','".$copyPerms['can_attachments']."','".$copyPerms['can_post_threads']."','".$copyPerms['can_reply_own']."','".$copyPerms['can_reply_others']."','".$copyPerms['can_upload_attachments']."','".$copyPerms['can_edit_own']."','".$copyPerms['can_delete_threads_own']."','".$copyPerms['can_delete_own']."','".$copyPerms['can_close_own']."','".$copyPerms['can_post_polls']."','".$copyPerms['is_inherited']."','".$copyPerms['can_perm_delete']."','".$copyPerms['flood_immunity']."')");
				}
			}
		}


		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for updating the <em>".$_POST['add_usergroup']['name']."</em> usergroup. You will now be redirected back.&uri=usergroup.php?do=manager");

		/*print("<br /><br />");

		print($query);

		print("<br /><br />");*/
	}

	// do header
	admin_header("wtcBB Admin Panel - Usergroups - Add Usergroup");

	construct_title("Add Usergroup");

	construct_table("options","add_usergroup","user_submit",1);
	construct_header("Add Usergroup",2);

	construct_text(1,"Title","Title of usergroup","add_usergroup","name");

	//construct_text(2,"Description","","add_usergroup","description");

	construct_text(2,"User Title","This will override all user <strong>ranks</strong>. However, if a user has one specifically set, this will not override it.","add_usergroup","usertitle");

	construct_text(1,"Username HTML (begin)","This HTML will display <em>before</em> this user's username everywhere on the message board. This will override any HTML for this user's usergroup. Again, this will not override a user's specific settings.","add_usergroup","name_html_begin");

	construct_text(2,"Username HTML (end)","Same as above, except this will come <em>after</em> this user's username everywhere on the message board. This will not override a user's specific settings.","add_usergroup","name_html_end","",1);


	// BASIC OPTIONS \\
	construct_header("Basic Options",2);

	construct_input(1,"Banned Usergroup","By setting this usergroup to a <em>banned</em> usergroup, it will show up as a banned usergroup when banning a member.","add_usergroup","is_banned",0,2);

	construct_input(2,"Viewable on Usergroups Page","By setting this to yes, any member will be able to see all members belonging to this usergroup on the show groups page.","add_usergroup","show_groups",0,1);

	construct_input(1,"Viewable on memberlist","By setting this to yes, any member will be able to see a user belonging to this usergroup in the memberlist.","add_usergroup","show_memberlist",0,1);

	construct_input(2,"Birthdays Viewable","By setting this to yes, members with a birthday will be displayed at the bottom of the forum home, given that birthdays are enabled.","add_usergroup","show_birthdays",1,1);


	// DISPLAY ACCESS \\
	construct_header("Display Access",2);

	construct_input(1,"Can see invisible users","By setting this to yes, user that have chosen to be invisible can be viewed by members of this usergroup in the \"Who's Online\".","add_usergroup","see_invisible",0,2);

	construct_input(2,"Can view member info","By setting this to yes, members of this usergroup will be allowed to view other user's member info.","add_usergroup","see_profile",0,1);

	construct_input(1,"Can edit own profile","By setting this to yes, members of this usergroup will be allowed to edit and change their own member profile.","add_usergroup","edit_own_profile",0,1);

	construct_input(2,"Can be invisible","By setting this to yes, members of this usergroup will be allowed to set themselves to invisible mode.","add_usergroup","can_invisible",0,1);

	construct_input(1,"Show <em>edited by</em> in posts?","By setting this to yes, members of this usergroup will have an <em>edited by</em> message at the bottom of a post that they edit.","add_usergroup","show_edited_notice",0,1);

	construct_input(2,"Can use custom title","By setting this to yes, members of this usergroup will be able to specify their own custom title, thusly overriding the one given for this usergroup.","add_usergroup","can_usertitle",0,2);

	construct_input(1,"Can use signature","By setting this to yes, members of this usergroup will be able to customize their own signature, which is displayed after every post.","add_usergroup","can_sig",1,1);


	// FORUM DISPLAY ACCESS \\
	construct_header("Forum Display Access",2);

	construct_input(1,"Can view deletion notices","","add_usergroup","can_view_deletion",0,2);

	construct_input(2,"Can use search feature","","add_usergroup","can_search",0,1);

	construct_input(1,"Can view message board","","add_usergroup","can_view_board",0,1);

	construct_input(2,"Can view others' threads","","add_usergroup","can_view_threads",1,1);


	// PERSONAL MESSAGING OPTIONS \\
	construct_header("Personal Messaging Options",2);

	construct_text(1,"Maximum amount of stored messages","Set this to <strong>0</strong> to disable personal messaging for members of this usergroup.","add_usergroup","personal_max_messages","50");

	construct_text(2,"Maximum recipients to send a PM at a time","Setting this too high could be costly for performance. Set this to <strong>0</strong> to disable.","add_usergroup","personal_max_users","5");

	construct_input(1,"Can send PM receipts?","Sending personal message receipts along with their message allows them to see if and when the user they sent them to read it or not.","add_usergroup","personal_receipts",0,2);

	construct_input(2,"Can deny PM read receipt request","","add_usergroup","personal_deny_receipt",0,2);

	construct_input(1,"Can add/edit/delete own folders?","","add_usergroup","personal_folders",0,1);

	construct_text(2,"Maximum amount of message rules","Set to <strong>0</strong> to disable rules for this user.","add_usergroup","personal_rules","5",1);


	// WARNING SYSTEM OPTIONS \\
	construct_header("Warning System Permissions",2);

	construct_input(1,"Warn Others","Setting this option to 'Yes' will allow members of this usergroup to warn other users.","add_usergroup","warn_others",0,2);

	construct_input(2,"Warn Protection","If this is on, members of this usergroup may not be warned by anyone.","add_usergroup","warn_protected",0,2);

	construct_input(1,"View Others' Warning Points","Enabling this option will allow users to view others' warning points.","add_usergroup","warn_viewOthers",0,2);

	construct_input(2,"View Own Warning Points","Disabling this will prevent users from viewing their own warning points.","add_usergroup","warn_viewOwn",1,1);


	// WHO'S ONLINE OPTIONS \\
	construct_header("Who's Online Options",2);

	construct_input(1,"Can view who's online","","add_usergroup","can_view_online",0,1);

	construct_input(2,"Can view detailed location for users","","add_usergroup","can_view_online_details",0,2);

	construct_input(1,"Can view IP Addresses","","add_usergroup","can_view_online_ip",1,2);


	// MESSAGE &AMP; ATTACHMENT OPTIONS \\
	construct_header("Message &amp; Attachment Options",2);

	construct_input(1,"Can post threads","","add_usergroup","can_post_threads",0,1);

	construct_input(2,"Can reply to own threads","","add_usergroup","can_reply_own",0,1);

	construct_input(1,"Can reply to others' threads","","add_usergroup","can_reply_others",0,1);

	construct_input(2,"Can edit own posts","","add_usergroup","can_edit_own",0,1);

	construct_input(1,"Can delete own posts","","add_usergroup","can_delete_own",0,2);

	construct_input(2,"Can open/close own threads","","add_usergroup","can_close_own",0,2);

	construct_input(1,"Can delete own threads","","add_usergroup","can_delete_threads_own",0,2);

	construct_input(2,"Can permanently delete own posts/threads","If this is set to yes, any users belonging to this usergroup will be able to permanently delete threads/posts.","add_usergroup","can_perm_delete",0,2);

	construct_input(1,"Can upload attachments","","add_usergroup","can_upload_attachments",0,1);

	construct_input(2,"Can download attachments","","add_usergroup","can_attachments",0,1);

	construct_input(1,"Can Have Default BB Code Settings","","add_usergroup","can_default_bbcode",0,1);

	construct_input(2,"Has Flood Check Immunity","","add_usergroup","flood_immunity",1,2);


	// GUESTBOOK PERMISSIONS \\
	construct_header("Guestbook Permissions",2);

	construct_input(1,"Enable Guestbook","If this is disabled, user's guestbooks in this usergroup will be completely hidden from everyone. This cannot be overridden.","add_usergroup","book_hidden",0,1);

	construct_input(2,"View Own Guestbook","","add_usergroup","book_viewOwn",0,1);

	construct_input(1,"View Others' Guestbooks","","add_usergroup","book_viewOthers",0,1);

	construct_input(2,"Add Entries to Own Guestbook","","add_usergroup","book_addOwn",0,1);

	construct_input(1,"Add Entries to Others' Guestbooks","","add_usergroup","book_addOthers",0,1);

	construct_input(2,"Edit Entries in Own Guestbook","","add_usergroup","book_editOwn",0,1);

	construct_input(1,"Edit Entries in Others' Guestbooks","","add_usergroup","book_editOthers",0,2);

	construct_input(2,"Delete Entries in Own Guestbook","","add_usergroup","book_deleteOwn",0,1);

	construct_input(1,"Delete Entries in Others' Guestbooks","","add_usergroup","book_deleteOthers",0,2);

	construct_input(2,"Permanently Delete Entries in Own Guestbook","","add_usergroup","book_permDeleteOwn",0,2);

	construct_input(1,"Permanently Delete Entries in Others' Guestbooks","","add_usergroup","book_permDeleteOthers",1,2);


	// POLL OPTIONS \\
	construct_header("Poll Options",2);

	construct_input(1,"Can make polls","","add_usergroup","can_post_polls",0,1);

	construct_input(2,"Can vote on polls","","add_usergroup","can_vote_polls",1,1);


	// AVATAR OPTIONS \\
	construct_header("Avatar Options",2);

	construct_input(1,"Can use avatar","","add_usergroup","can_use_avatar",0,1);

	construct_input(2,"Can upload avatar","","add_usergroup","can_upload_avatar",0,2);

	construct_text(1,"Maximum width in pixels","Set to <strong>0</strong> to not have a limit.","add_usergroup","avatar_width","64");

	construct_text(2,"Maximum height in pixels","Set to <strong>0</strong> to not have a limit.","add_usergroup","avatar_height","64");

	construct_text(1,"Maximum filesize in bytes","1KB = 1024 bytes <br /> Set to <strong>0</strong> to not have a limit.","add_usergroup","avatar_filesize","20000",1);


	// ADMINISTRATOR OPTIONS \\
	construct_header("Administrator Options",2);

	construct_input(1,"Super Moderator","Super Moderators can moderate all forums.","add_usergroup","is_super_moderator",0,2);

	construct_input(2,"Administrator","Administrators can access the administrator control panel. However, you can limit access to the AdminCP via the <a href=\"usergroup.php?do=admin_permissions\">Admin Permissions</a> page for each user individually that is an administrator. They can also moderate all forums.","add_usergroup","is_admin",0,2);


	construct_select_begin(1,"Base Permissions Off Of:","If you want this usergroup to have the same forum permissions as another usergroup, you can easily copy those permissions to this usergroup as a time saver.","add_usergroup","copy_forums_perms",1);

		// get all usergroups
		$usergroup_select = mysql_query("SELECT * FROM usergroups ORDER BY name ASC");

		print("<option value=\"-1\" selected=\"selected\">None</option>\n");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			print("<option value=\"".$usergroup['usergroupid']."\">".$usergroup['name']."</option>\n");
		}

	construct_select_end(1);


	construct_footer(2,"user_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}

else if($_GET['do'] == "edit") {
	// find all users belonging to this usergroup they are trying to edit
	$users_select = query("SELECT * FROM user_info WHERE usergroupid = '".$_GET['id']."'");

	// set $is_undeletable to false first...
	$is_undeletable = false;

	// loop through the users to make sure we don't have any undeletable users.. IF we have a row at least
	if(mysql_num_rows($users_select)) {
		while($user_check = mysql_fetch_array($users_select)) {
			if(isUndeletable($user_check['userid'])) {
				$is_undeletable = true;

				// that's all we need.. break!
				break;
			}
		}
	}

	if(!is_array($usergroupinfo[$_GET['id']])) {
		// spit out error
		construct_error("Invalid usergroup id. <a href=\"usergroup.php?do=manager\">Click here</a> to go back to the usergroup manager.");
		exit;
	}

	// can't delete if undeletable users belong to this usergroup!!
	else if($is_undeletable) {
		// spit out error
		construct_error("Sorry you cannot edit this usergroup because one or many of the users belonging to it are protected by the <strong>\$uneditable_user</strong> variable in the <strong>config.php</strong> file.");
		exit;
	}

	// otherwise we are free!
	else {
		// put usergroup information into array
		$usergroupinfo5 = $usergroupinfo[$_GET['id']];

		if($_POST['edit_usergroup']['set_form']) {
			// intialize beginning of query
			$query = "UPDATE usergroups SET";

			// intialize counter
			$x = 1;

			foreach($_POST['edit_usergroup'] as $option_key => $option_value) {
				if($option_key != "set_form") {
					if($x == 1) {
						$comma = "";
					} else {
						$comma = ",";
					}

					if($option_key == "usertitle" OR $option_key == "name_html_begin" OR $option_key == "name_html_end") {
						$query .= " ".$comma." ".$option_key." = '".addslashes($option_value)."'";
					}

					else {
						$query .= " ".$comma." ".$option_key." = '".htmlspecialchars(addslashes($option_value))."'";
					}

					$x++;
				}
			}

			$query .= " WHERE usergroupid = '".$usergroupinfo5['usergroupid']."'";

			//print($query);

			// run query
			query($query);

			$uri = "usergroup.php?do=editSTEVEid=".$usergroupinfo5['usergroupid'];

			redirect("thankyou.php?message=Thank you for editing <em>".$_POST['edit_usergroup']['name']."</em>. You will now be redirected back to ".$_POST['edit_usergroup']['name']."\'s permissions page.&uri=".$uri);
		}

		// do header
		admin_header("wtcBB Admin Panel - Usergroups - Edit Usergroup");

		construct_title($usergroupinfo5['name']."<span class=\"small\">(id: ".$usergroupinfo5['usergroupid'].")</span>");

		construct_table("options","edit_usergroup","user_submit",1);
		construct_header("Edit Usergroup",2);

		construct_text(1,"Title","Title of usergroup","edit_usergroup","name",$usergroupinfo5['name']);

		//construct_text(2,"Description","","edit_usergroup","description",$usergroupinfo5['description']);

		construct_text(2,"User Title","This will override all user <strong>ranks</strong>. However, if a user has one specifically set, this will not override it.","edit_usergroup","usertitle",htmlspecialchars($usergroupinfo5['usertitle']));

		construct_text(1,"Username HTML (begin)","This HTML will display <em>before</em> this user's username everywhere on the message board. This will override any HTML for this user's usergroup. Again, this will not override a user's specific settings.","edit_usergroup","name_html_begin",htmlspecialchars($usergroupinfo5['name_html_begin']));

		construct_text(2,"Username HTML (end)","Same as above, except this will come <em>after</em> this user's username everywhere on the message board. This will not override a user's specific settings.","edit_usergroup","name_html_end",htmlspecialchars($usergroupinfo5['name_html_end']),1);


		// BASIC OPTIONS \\
		construct_header("Basic Options",2);

		construct_input(1,"Banned Usergroup","By setting this usergroup to a <em>banned</em> usergroup, it will show up as a banned usergroup when banning a member.","edit_usergroup","is_banned",0,0,$usergroupinfo5);

		construct_input(2,"Viewable on Usergroups page","By setting this to yes, any member will be able to see all members belonging to this usergroup on the show groups page.","edit_usergroup","show_groups",0,0,$usergroupinfo5);

		construct_input(1,"Viewable on memberlist","By setting this to yes, any member will be able to see a user belonging to this usergroup in the memberlist.","edit_usergroup","show_memberlist",0,0,$usergroupinfo5);

		construct_input(2,"Birthdays Viewable","By setting this to yes, members with a birthday will be displayed at the bottom of the forum home, given that birthdays are enabled.","edit_usergroup","show_birthdays",1,0,$usergroupinfo5);


		// DISPLAY ACCESS \\
		construct_header("Display Access",2);

		construct_input(1,"Can see invisible users","By setting this to yes, user that have chosen to be invisible can be viewed by members of this usergroup in the \"Who's Online\".","edit_usergroup","see_invisible",0,0,$usergroupinfo5);

		construct_input(2,"Can view member info","By setting this to yes, members of this usergroup will be allowed to view other user's member info.","edit_usergroup","see_profile",0,0,$usergroupinfo5);

		construct_input(1,"Can edit own profile","By setting this to yes, members of this usergroup will be allowed to edit and change their own member profile.","edit_usergroup","edit_own_profile",0,0,$usergroupinfo5);

		construct_input(2,"Can be invisible","By setting this to yes, members of this usergroup will be allowed to set themselves to invisible mode.","edit_usergroup","can_invisible",0,0,$usergroupinfo5);

		construct_input(1,"Show <em>edited by</em> in posts?","By setting this to yes, members of this usergroup will have an <em>edited by</em> message at the bottom of a post that they edit.","edit_usergroup","show_edited_notice",0,0,$usergroupinfo5);

		construct_input(2,"Can use custom title","By setting this to yes, members of this usergroup will be able to specify their own custom title, thusly overriding the one given for this usergroup.","edit_usergroup","can_usertitle",0,0,$usergroupinfo5);

		construct_input(1,"Can use signature","By setting this to yes, members of this usergroup will be able to customize their own signature, which is displayed after every post.","edit_usergroup","can_sig",1,0,$usergroupinfo5);


		// FORUM DISPLAY ACCESS \\
		construct_header("Forum Display Access",2);

		construct_input(1,"Can view deletion notices","","edit_usergroup","can_view_deletion",0,0,$usergroupinfo5);

		construct_input(2,"Can use search feature","","edit_usergroup","can_search",0,0,$usergroupinfo5);

		construct_input(1,"Can view message board","","edit_usergroup","can_view_board",0,0,$usergroupinfo5);

		construct_input(2,"Can view others' threads","","edit_usergroup","can_view_threads",1,0,$usergroupinfo5);


		// PERSONAL MESSAGING OPTIONS \\
		construct_header("Personal Messaging Options",2);

		construct_text(1,"Maximum amount of stored messages","Set this to <strong>0</strong> to disable personal messaging for members of this usergroup.","edit_usergroup","personal_max_messages",$usergroupinfo5['personal_max_messages']);

		construct_text(2,"Maximum recipients to send a PM at a time","Setting this too high could be costly for performance. Set this to <strong>0</strong> to disable.","edit_usergroup","personal_max_users",$usergroupinfo5['personal_max_users']);

		construct_input(1,"Can send PM receipts?","Sending personal message receipts along with their message allows them to see if and when the user they sent them to read it or not.","edit_usergroup","personal_receipts",0,0,$usergroupinfo5);

		construct_input(2,"Can deny PM read receipt request","","edit_usergroup","personal_deny_receipt",0,0,$usergroupinfo5);

		construct_input(1,"Can add/edit/delete own folders?","","edit_usergroup","personal_folders",0,0,$usergroupinfo5);

		construct_text(2,"Maximum amount of message rules","Set to <strong>0</strong> to disable rules for this user.","edit_usergroup","personal_rules",$usergroupinfo5['personal_rules'],1);


		// WARNING SYSTEM OPTIONS \\
		construct_header("Warning System Permissions",2);

		construct_input(1,"Warn Others","Setting this option to 'Yes' will allow members of this usergroup to warn other users.","edit_usergroup","warn_others",0,0,$usergroupinfo5);

		construct_input(2,"Warn Protection","If this is on, members of this usergroup may not be warned by anyone.","edit_usergroup","warn_protected",0,0,$usergroupinfo5);

		construct_input(1,"View Others' Warning Points","Enabling this option will allow users to view others' warning points.","edit_usergroup","warn_viewOthers",0,0,$usergroupinfo5);

		construct_input(2,"View Own Warning Points","Disabling this will prevent users from viewing their own warning points.","edit_usergroup","warn_viewOwn",1,0,$usergroupinfo5);


		// WHO'S ONLINE OPTIONS \\
		construct_header("Who's Online Options",2);

		construct_input(1,"Can view who's online","","edit_usergroup","can_view_online",0,0,$usergroupinfo5);

		construct_input(2,"Can view detailed location for users","","edit_usergroup","can_view_online_details",0,0,$usergroupinfo5);

		construct_input(1,"Can view IP Addresses","","edit_usergroup","can_view_online_ip",1,0,$usergroupinfo5);


		// MESSAGE &AMP; ATTACHMENT OPTIONS \\
		construct_header("Message &amp; Attachment Options",2);

		construct_input(1,"Can post threads","","edit_usergroup","can_post_threads",0,0,$usergroupinfo5);

		construct_input(2,"Can reply to own threads","","edit_usergroup","can_reply_own",0,0,$usergroupinfo5);

		construct_input(1,"Can reply to others' threads","","edit_usergroup","can_reply_others",0,0,$usergroupinfo5);

		construct_input(2,"Can edit own posts","","edit_usergroup","can_edit_own",0,0,$usergroupinfo5);

		construct_input(1,"Can delete own posts","","edit_usergroup","can_delete_own",0,0,$usergroupinfo5);

		construct_input(2,"Can open/close own threads","","edit_usergroup","can_close_own",0,0,$usergroupinfo5);

		construct_input(1,"Can delete own threads","","edit_usergroup","can_delete_threads_own",0,0,$usergroupinfo5);

		construct_input(2,"Can permanently delete own posts/threads","If this is set to yes, any users belonging to this usergroup will be able to permanently delete threads/posts.","edit_usergroup","can_perm_delete",0,0,$usergroupinfo5);

		construct_input(1,"Can upload attachments","","edit_usergroup","can_upload_attachments",0,0,$usergroupinfo5);

		construct_input(2,"Can download attachments","","edit_usergroup","can_attachments",0,0,$usergroupinfo5);

		construct_input(1,"Can Have Default BB Code Settings","","edit_usergroup","can_default_bbcode",0,0,$usergroupinfo5);

		construct_input(2,"Has Flood Check Immunity","","edit_usergroup","flood_immunity",1,0,$usergroupinfo5);


		// GUESTBOOK PERMISSIONS \\
		construct_header("Guestbook Permissions",2);

		construct_input(1,"Enable Guestbook","If this is disabled, user's guestbooks in this usergroup will be completely hidden from everyone. This cannot be overridden.","edit_usergroup","book_hidden",0,0,$usergroupinfo5);

		construct_input(2,"View Own Guestbook","","edit_usergroup","book_viewOwn",0,0,$usergroupinfo5);

		construct_input(1,"View Others' Guestbooks","","edit_usergroup","book_viewOthers",0,0,$usergroupinfo5);

		construct_input(2,"Add Entries to Own Guestbook","","edit_usergroup","book_addOwn",0,0,$usergroupinfo5);

		construct_input(1,"Add Entries to Others' Guestbooks","","edit_usergroup","book_addOthers",0,0,$usergroupinfo5);

		construct_input(2,"Edit Entries in Own Guestbook","","edit_usergroup","book_editOwn",0,0,$usergroupinfo5);

		construct_input(1,"Edit Entries in Others' Guestbooks","","edit_usergroup","book_editOthers",0,0,$usergroupinfo5);

		construct_input(2,"Delete Entries in Own Guestbook","","edit_usergroup","book_deleteOwn",0,0,$usergroupinfo5);

		construct_input(1,"Delete Entries in Others' Guestbooks","","edit_usergroup","book_deleteOthers",0,0,$usergroupinfo5);

		construct_input(2,"Permanently Delete Entries in Own Guestbook","","edit_usergroup","book_permDeleteOwn",0,0,$usergroupinfo5);

		construct_input(1,"Permanently Delete Entries in Others' Guestbooks","","edit_usergroup","book_permDeleteOthers",1,0,$usergroupinfo5);


		// POLL OPTIONS \\
		construct_header("Poll Options",2);

		construct_input(1,"Can make polls","","edit_usergroup","can_post_polls",0,0,$usergroupinfo5);

		construct_input(2,"Can vote on polls","","edit_usergroup","can_vote_polls",1,0,$usergroupinfo5);


		// AVATAR OPTIONS \\
		construct_header("Avatar Options",2);

		construct_input(1,"Can use avatar","","edit_usergroup","can_use_avatar",0,0,$usergroupinfo5);

		construct_input(2,"Can upload avatar","","edit_usergroup","can_upload_avatar",0,0,$usergroupinfo5);

		construct_text(1,"Maximum width in pixels","Set to <strong>0</strong> to not have a limit.","edit_usergroup","avatar_width",$usergroupinfo5['avatar_width']);

		construct_text(2,"Maximum height in pixels","Set to <strong>0</strong> to not have a limit.","edit_usergroup","avatar_height",$usergroupinfo5['avatar_height']);

		construct_text(1,"Maximum filesize in bytes","1KB = 1024 bytes <br /> Set to <strong>0</strong> to not have a limit.","edit_usergroup","avatar_filesize",$usergroupinfo5['avatar_filesize'],1);


		// ADMINISTRATOR OPTIONS \\
		construct_header("Administrator Options",2);

		construct_input(1,"Super Moderator","Super Moderators can moderate all forums.","edit_usergroup","is_super_moderator",0,0,$usergroupinfo5);

		construct_input(2,"Administrator","Administrators can access the administrator control panel. However, you can limit access to the AdminCP via the <a href=\"usergroup.php?do=admin_permissions\">Admin Permissions</a> page for each user individually that is an administrator. They can also moderate all forums.","edit_usergroup","is_admin",1,0,$usergroupinfo5);

		construct_footer(2,"user_submit");
		construct_table_END(1);

		// do footer
		admin_footer();
	}
}

else if($_GET['do'] == "delete") {
	// find all users belonging to this usergroup they are trying to delete
	$users_select = query("SELECT * FROM user_info WHERE usergroupid = '".$_GET['id']."'");

	// set $is_undeletable to false first...
	$is_undeletable = false;

	// loop through the users to make sure we don't have any undeletable users.. IF we have a row at least
	if(mysql_num_rows($users_select)) {
		while($user_check = mysql_fetch_array($users_select)) {
			if(isUndeletable($user_check['userid'])) {
				$is_undeletable = true;

				// that's all we need.. break!
				break;
			}
		}
	}

	$usergroupss = $usergroupinfo[$_GET['id']];

	if(!is_array($usergroupinfo[$_GET['id']])) {
		// spit out error
		construct_error("Invalid usergroup id. <a href=\"usergroup.php?do=manager\">Click here</a> to go back to the usergroup manager.");
	}

	// can't delete if undeletable users belong to this usergroup!!
	else if($is_undeletable) {
		// spit out error
		construct_error("Sorry you cannot delete this usergroup because one or many of the users belonging to it are protected by the <strong>\$uneditable_user</strong> variable in the <strong>config.php</strong> file.");
	}

	// can't delete if it's a default usergroup...
	else if($usergroupss['usergroupid'] <= 8) {
		// spit out error...
		construct_error("Sorry, you cannot delete this usergroup, because it is a default usergroup. You may change the settings of this usergroup, but you may not delete it.");
	}

	else {
		// make sure form is set..
		if($_POST['confirm']['set_form']) {
			// yes...
			if($_POST['confirm']['yes_no']) {
				// move all users from the usergroup they are deleting to the usergroup_redirect
				query("UPDATE user_info SET usergroupid = '".$bboptions['usergroup_redirect']."' WHERE usergroupid = '".$_GET['id']."'");

				// remove permissions
				query("DELETE FROM forums_permissions WHERE usergroupid = '".$_GET['id']."'");

				query("DELETE FROM usergroups WHERE usergroupid = '".$_GET['id']."' LIMIT 1");

				redirect("thankyou.php?message=Thank you for deleting the usergroup. You will now be redirected back.&uri=usergroup.php?do=manager");
			}

			// no...
			else {
				redirect("usergroup.php?do=manager");
			}
		}
		
		// do a confirm page...
		construct_confirm("Are you sure you want to delete this? It <strong>cannot</strong> be undone! By deleting this usergroup, it will automatically transfer all members of it to the <em>".$usergroupinfo[$bboptions['usergroup_redirect']]['name']."</em> usergroup. If you select no you will be redirected back.\n\n<br />");
	}
}

else if($_GET['do'] == "showall") {
	// make sure we have users to select from
	$check_user_existance = query("SELECT * FROM user_info WHERE usergroupid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!is_array($usergroupinfo[$_GET['id']])) {
		// spit out error
		construct_error("Invalid usergroup id. <a href=\"usergroup.php?do=manager\">Click here</a> to go back to the usergroup manager.");
	}

	// uh oh... also do it for guest usergroup
	else if(!mysql_num_rows($check_user_existance) OR $_GET['id'] == 1) {
		construct_error("No users exist in this usergroup.");
	}

	else {
		// get arry for usergroup
		$usergroup = $usergroupinfo[$_GET['id']];

		// run query for users in this usergroup...
		$users_select = query("SELECT * FROM user_info WHERE usergroupid = '".$usergroup['usergroupid']."' ORDER BY username ASC");

		// do header
		admin_header("wtcBB Admin Panel - Usergroups - Select User to Edit");

		construct_title("Select a User to Edit");

		construct_table("options","usergroup_showall","userinfo_submit");
		construct_header(mysql_num_rows($users_select)." users in <em>".$usergroup['name']."</em> (id: ".$usergroup['usergroupid'].")",6);

		print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tUser\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tE-mail\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tJoin Date\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tLast Visit\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tPosts\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

		print("\t</tr>\n\n");

		while($userinfo = mysql_fetch_array($users_select)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
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
					print("\t\t\t<button type=\"button\" onClick=\"location.href='user.php?do=edit&id=".$userinfo['userid']."'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button> <button type=\"button\" onClick=\"location.href='user.php?do=edit&id=".$userinfo['userid']."&action=delete'\" ".$submitbg." style=\"margin: 0px;\">Delete</button>\n");
					print("\t\t\t</form>\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"6\">&nbsp;</td></tr>\n");
		construct_table_END();

		// do footer
		admin_footer();
	}
}

else if($_GET['do'] == "manager") {

	// do header
	admin_header("wtcBB Admin Panel - Usergroups - Usergroup Manager");

	construct_title("Usergroup Manager");

	construct_table("options","man_usergroup","user_submit",1);
	construct_header("Default Usergroups",3);

	// loop through DEFAULT usergroups...
	$usergroups_select = query("SELECT * FROM usergroups WHERE usergroupid <= '8' ORDER BY name");

	print("\t<tr>\n");

	print("\t\t<td class=\"cat\">\n");
	print("\t\t\tTitle\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"cat2\">\n");
	print("\t\t\tUsers\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"cat2\">\n");
	print("\t\t\tOptions\n");
	print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($usergroup = mysql_fetch_array($usergroups_select)) {
		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"padding: 7px;\">\n");
			print("\t\t\t<strong>".$usergroup['name']."</strong>\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"desc2\" style=\"padding: 7px; border-left: none; text-align: center;\">\n");
			// get users...
			if($usergroup['usergroupid'] == 1) {
				print("\t\t\t0\n");
			}

			else {
				$num_of_users = query("SELECT * FROM user_info WHERE usergroupid = '".$usergroup['usergroupid']."'");
				print("\t\t\t".mysql_num_rows($num_of_users)."\n");
			}

			print("\t\t</td>\n\n");

			print("\t\t<td class=\"desc1\" style=\"padding: 7px; white-space: nowrap; border-left: none; text-align: right;\">\n");
			print("\t\t\t<form style=\"margin: 0px; padding: 0px;\" action=\"\" method=\"post\">\n");
			print("\t\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control\" onChange=\"location.href=(form.control.options[form.control.selectedIndex].value)\">\n");
			print("\t\t\t\t\t<option value=\"usergroup.php?do=edit&id=".$usergroup['usergroupid']."\" selected=\"selected\">Edit Usergroup (ID: ".$usergroup['usergroupid'].")</option>\n");
			print("\t\t\t\t\t<option value=\"usergroup.php?do=showall&id=".$usergroup['usergroupid']."\">Show All Users</option>\n");
			print("\t\t\t\t</select>\n");
			print("\t\t\t\t <button type=\"button\" onClick=\"location.href=(form.control.options[form.control.selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
			print("\t\t</td></form>\n\n");

		print("\t</tr>\n\n");
	}

	?>
	<tr>
		<td class="footer" style="border-top: none;" colspan="3"><button type="submit" id="user_submit" style="font-family: verdana; font-size: 8pt; border: #9E9E9E 1px solid; background-image: url('./../images/button_bg.jpg'); background-repeat: repeat-x; background-color: #ECECEC;" onMouseDown="this.style.borderColor='#C98C00'; this.style.backgroundImage='url(./../images/button_bgclick.jpg)'; this.style.backgroundColor='#F6EAB9';" onMouseOver="this.style.borderColor='#245F9B'; this.style.backgroundImage='url(./../images/button_bgover.jpg)'; this.style.backgroundColor='#6FBADF';" onMouseout="this.style.borderColor='#9E9E9E'; this.style.backgroundImage='url(./../images/button_bg.jpg)'; this.style.backgroundColor='#ECECEC';" onClick="location.href='usergroup.php?do=add';">Add Usergroup</button></td>
	</tr>
	<?php

	// make sure we have custom usergroups!
	// loop through CUSTOM usergroups...
	$usergroups_select2 = query("SELECT * FROM usergroups WHERE usergroupid > '8' ORDER BY name");

	if(!mysql_num_rows($usergroups_select2)) {
		construct_table_END(1);
	}

	else {
		construct_table_END();

		print("<br /><br />\n\n");


		construct_table("options","man_usergroup","user_submit");
		construct_header("Custom Usergroups",3);

		print("\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tTitle\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tUsers\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

		print("\t</tr>\n\n");

		while($usergroup2 = mysql_fetch_array($usergroups_select2)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"padding: 7px;\">\n");
				print("\t\t\t<strong>".$usergroup2['name']."</strong>\n");
				print("\t\t</td>\n\n");

				print("\t\t<td class=\"desc2\" style=\"padding: 7px; border-left: none; text-align: center;\">\n");
				// get users...
				$num_of_users = query("SELECT * FROM user_info WHERE usergroupid = '".$usergroup2['usergroupid']."'");
				print("\t\t\t".mysql_num_rows($num_of_users)."\n");
				print("\t\t</td>\n\n");

				print("\t\t<td class=\"desc1\" style=\"padding: 7px; white-space: nowrap; border-left: none; text-align: right;\">\n");
				print("\t\t\t<form style=\"margin: 0px; padding: 0px;\" action=\"\" method=\"post\">\n");
				print("\t\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control\" onChange=\"location.href=(form.control.options[form.control.selectedIndex].value)\">\n");
				print("\t\t\t\t\t<option value=\"usergroup.php?do=edit&id=".$usergroup2['usergroupid']."\" selected=\"selected\">Edit Usergroup (ID: ".$usergroup2['usergroupid'].")</option>\n");
				print("\t\t\t\t\t<option value=\"usergroup.php?do=showall&id=".$usergroup2['usergroupid']."\">Show All Users</option>\n");
				print("\t\t\t\t\t<option value=\"usergroup.php?do=delete&id=".$usergroup2['usergroupid']."\">Delete Usergroup</option>\n");
				print("\t\t\t\t</select>\n");
				print("\t\t\t\t <button type=\"button\" onClick=\"location.href=(form.control.options[form.control.selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
				print("\t\t</td></form>\n\n");

			print("\t</tr>\n\n");
		}

		?>
		<tr>
			<td class="footer" style="border-top: none;" colspan="3"><button type="submit" id="user_submit" style="font-family: verdana; font-size: 8pt; border: #9E9E9E 1px solid; background-image: url('./../images/button_bg.jpg'); background-repeat: repeat-x; background-color: #ECECEC;" onmousedown="this.style.borderColor='#C98C00'; this.style.backgroundImage='url(./../images/button_bgclick.jpg)'; this.style.backgroundColor='#F6EAB9';" onmouseover="this.style.borderColor='#245F9B'; this.style.backgroundImage='url(./../images/button_bgover.jpg)'; this.style.backgroundColor='#6FBADF';" onmouseout="this.style.borderColor='#9E9E9E'; this.style.backgroundImage='url(./../images/button_bg.jpg)'; this.style.backgroundColor='#ECECEC';" onclick="location.href='usergroup.php?do=add';">Add Usergroup</button></td>
		</tr>
		<?php

		construct_table_END(1);
	}

	// do footer
	admin_footer();
}


// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>