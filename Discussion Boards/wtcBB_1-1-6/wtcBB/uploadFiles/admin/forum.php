<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //ADMIN PANEL FORUMS\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Forums";
$permissions = "forums_moderators";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// remove mod from all moderated forums
if($_GET['do'] == "removeMod") {
	// make sure mod exists...
	$checkMod = query("SELECT * FROM moderators WHERE userid = '".$_GET['userid']."'");

	// uh oh...
	if(!mysql_num_rows($checkMod)) {
		construct_error("Sorry, no moderator exists in the database with the given ID.");
		exit;
	}

	// construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// run queries
			query("DELETE FROM moderators WHERE userid = '".$_GET['userid']."'");

			redirect("thankyou.php?message=Moderator has successfully been removed from all forums. You will now be redirected back to the \'Show All Moderators\' page.&uri=forum.php?do=show_mods");
		}

		// no...
		else {
			redirect("forum.php?do=show_mods");
		}
	}
	
	// do a confirm page...
	construct_confirm();

	exit;
}

// ##### SHOW ALL MODERATORS ##### \\
if($_GET['do'] == "show_mods") {
	// query moderators from DB
	$allMods = query("SELECT * FROM moderators LEFT JOIN user_info ON moderators.userid = user_info.userid GROUP BY moderators.userid ORDER BY user_info.username ASC");

	// if now mods.. error
	if(!mysql_num_rows($allMods)) {
		construct_error("No moderators exist. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// do header
	admin_header("wtcBB Admin Panel - Moderators");

	construct_title("Moderators");

	construct_table("options","meh","meh");

	// loop
	while($moderators = mysql_fetch_array($allMods)) {
		print("\t<strong>".$moderators['username']."</strong> <span class=\"small\">(Last Activity: ".date("m-d-y \a\\t h:i",$moderators['lastactivity']).")</span>\n");

		// get forums
		$moddedForums = query("SELECT * FROM moderators LEFT JOIN forums ON moderators.forumid = forums.forumid WHERE moderators.userid = '".$moderators['userid']."'");

		// if rows, loop
		if(mysql_num_rows($moddedForums) > 0) {
			print("<p class=\"small\" style=\"margin: 0;\"><a href=\"forum.php?do=removeMod&amp;userid=".$moderators['userid']."\">Remove Moderator From All Forums</a></p>\n");
			print("<ul style=\"margin-top: 0; padding: 0;\">\n");

			while($modforuminfo = mysql_fetch_array($moddedForums)) {
				print("\t<li><strong>".$modforuminfo['forum_name']."</strong> - <a href=\"forum.php?do=edit_moderator&amp;forumid=".$modforuminfo['forumid']."&amp;userid=".$modforuminfo['moderatorid']."\">Edit Permissions</a></li>\n");
			}

			print("</ul>\n");
		}
	}

	// do footer
	admin_footer();
}


// ##### DO FORUM PERMISSIONS ##### \\
else if($_GET['do'] == "permission") {
	// check for permissionsid
	if($_GET['permissionsid']) {
		// make sure we have a valid permissionsid
		$checkAndRun_permissions = query("SELECT * FROM forums_permissions WHERE permissionsid = '".$_GET['permissionsid']."' LIMIT 1");

		// uh oh...
		if(!mysql_num_rows($checkAndRun_permissions)) {
			construct_error("Sorry, no permissions exist with the given ID.");
			exit;
		}

		// arrays
		$perms = mysql_fetch_array($checkAndRun_permissions);

		// check to see if we want default
		if($_GET['action'] == "default") {
			// construct confirm!
			// make sure form is set..
			if($_POST['confirm']['set_form']) {
				// yes...
				if($_POST['confirm']['yes_no']) {
					// run function...
					backToDefault($foruminfo[$perms['forumid']]['forumid'],$perms['usergroupid']);

					redirect("thankyou.php?message=Thank you for setting these permissions back to their defaults. You will now be redirected back to the \"Edit Permissions\" page.&uri=forum.php?do=permission");
				}

				// no...
				else {
					redirect("forum.php?do=permission");
				}
			}
			
			// do a confirm page...
			construct_confirm();

			exit;
		}

		if($_POST['update_perms']['set_form']) {
			// intialize beginning of query
			$query = "UPDATE forums_permissions SET";

			// intialize counter
			$x = 1;

			foreach($_POST['update_perms'] as $option_key => $option_value) {
				if($option_key != "set_form") {
					if($x == 1) {
						$comma = "";
					} else {
						$comma = ",";
					}

					$query .= " ".$comma." ".$option_key." = '".$option_value."'";
					$x++;
				}
			}

			$query_end .= " , is_inherited = '0' WHERE permissionsid = '".$_GET['permissionsid']."'";


			// ##### WE NEED TO DO THIS IN CASE WE NEED TO ADD NEW ROWS! ##### \\
			// set counter
			$i = 0;

			// intialize the $query var
			$start_query = "INSERT INTO forums_permissions (";

			foreach($_POST['update_perms'] as $option_key => $option_value) {
				// check to make sure we don't input the "set_form"
				if($option_key != "set_form") {
					// should we use comma?
					if($i == 0) {
						$comma = "";
					} else {
						$comma = ",";
					}

					// form the insert query...
					$key_query .= $comma;
					$key_query .= $option_key;

					// increment $i
					$i++;
				}
			} 

			$middle_query .= ",forumid,usergroupid,is_inherited) VALUES (";

			// reset i;
			$i = 0;

			foreach($_POST['update_perms'] as $option_key => $option_value) {
				// check to make sure we don't input the "set_form"
				if($option_key != "set_form") {
					// show we use comma?
					if($i == 0) {
						$comma = "";
					} else {
						$comma = ",";
					}

					// form the insert query...
					$value_query .= $comma;
					$value_query .= "'".$option_value."'";

					// increment $i
					$i++;
				}
			}

			// finish off the query statement
			$end_query .= ",'".$foruminfo[$perms['forumid']]['forumid']."','".$perms['usergroupid']."','0')";

			// add
			//print($start_query.$key_query.$middle_query.$value_query.$end_query);

			// update
			//print($query.$query_end);

			// run query
			query($query_start.$query.$query_end);

			updateInheritedPermissions($foruminfo[$perms['forumid']]['forumid'],$perms['usergroupid']);

			redirect("thankyou.php?message=Thank you for updating the permissions. You will now be redirected back to the \"Edit Permissions\" page.&uri=forum.php?do=permission");
		}

		// do header
		admin_header("wtcBB Admin Panel - Forums - Edit Permissions");

		construct_title($usergroupinfo[$perms['usergroupid']]['name']." <span class=\"small\">(id: ".$usergroupinfo[$perms['usergroupid']]['usergroupid'].")</span>");

		print("\n\n<div style=\"text-align: center; margin-top: 10px; margin-bottom: 0px;\"><div style=\"text-align: left; width: 90%; margin-bottom: 0px;\">\n");
		print("\t<a href=\"forum.php?do=permission&permissionsid=".$perms['permissionsid']."&action=default\">Set these permissions back to default</a>\n");
		print("</div></div>\n\n");

		construct_table("options","update_perms","perm_submit",1);


		// FORUM VIEWING PERMISSIONS \\
		construct_header("Forum Viewing Permissions",2);

		construct_input(1,"Can view message board","","update_perms","can_view_board",0,0,$perms);

		construct_input(2,"Can view others' threads","","update_perms","can_view_threads",0,0,$perms);

		construct_input(1,"Can see deletion notices","","update_perms","can_view_deletion",0,0,$perms);

		construct_input(2,"Can search forums","","update_perms","can_search",0,0,$perms);

		construct_input(1,"Can download attachments","","update_perms","can_attachments",1,0,$perms);


		// POST / THREAD / ATTACHMENTS Permissions \\
		construct_header("Post/Thread/Attachments Permissions",2);

		construct_input(1,"Can post threads","","update_perms","can_post_threads",0,0,$perms);

		construct_input(2,"Can reply to own threads","","update_perms","can_reply_own",0,0,$perms);

		construct_input(1,"Can reply to others' threads","","update_perms","can_reply_others",0,0,$perms);

		construct_input(2,"Can edit own posts","","update_perms","can_edit_own",0,0,$perms);

		construct_input(1,"Can delete own posts","","update_perms","can_delete_own",0,0,$perms);

		construct_input(2,"Can open/close own threads","","update_perms","can_close_own",0,0,$perms);

		construct_input(1,"Can delete own threads","","update_perms","can_delete_threads_own",0,0,$perms);

		construct_input(2,"Can permanently delete own posts/threads","This setting will automatically override the above setting. If this is set to yes, any users belonging to this usergroup will be able to view deletion notices, and permanently delete threads/posts no matter what the previous setting is set to.","update_perms","can_perm_delete",0,0,$perms);

		construct_input(1,"Can upload attachments","","update_perms","can_upload_attachments",0,0,$perms);

		construct_input(2,"Has Flood Check Immunity","","update_perms","flood_immunity",1,0,$perms);


		// POLL PERMISSIONS \\
		construct_header("Poll Permissions",2);

		construct_input(1,"Can make polls","","update_perms","can_post_polls",0,0,$perms);

		construct_input(2,"Can vote on polls","","update_perms","can_vote_polls",1,0,$perms);


		construct_footer(2,"perm_submit");
		construct_table_END(1);

		// do footer
		admin_footer();

		exit;
	}

	// check for usergroupid...
	if($_GET['usergroupid'] AND $_GET['forumid']) {
		// uh oh...
		if(!is_array($usergroupinfo[$_GET['usergroupid']])) {
			construct_error("Sorry, a usergroup with the ID given does not exist.");
			exit;
		}

		// uh oh...
		if(!is_array($foruminfo[$_GET['forumid']])) {
			construct_error("Sorry, a forum with the ID given does not exist.");
			exit;
		}

		// only do the below if the form is set...
		if($_POST['edit_perms']['set_form']) {
			print("<br /><br />");

			// set counter
			$i = 0;

			// intialize the $query var
			$start_query = "INSERT INTO forums_permissions (";

			foreach($_POST['edit_perms'] as $option_key => $option_value) {
				// check to make sure we don't input the "set_form"
				if($option_key != "set_form") {
					// should we use comma?
					if($i == 0) {
						$comma = "";
					} else {
						$comma = ",";
					}

					// form the insert query...
					$key_query .= $comma;
					$key_query .= $option_key;

					// increment $i
					$i++;
				}
			} 

			$middle_query .= ",forumid,usergroupid) VALUES (";

			// reset i;
			$i = 0;

			foreach($_POST['edit_perms'] as $option_key => $option_value) {
				// check to make sure we don't input the "set_form"
				if($option_key != "set_form") {
					// show we use comma?
					if($i == 0) {
						$comma = "";
					} else {
						$comma = ",";
					}

					// form the insert query...
					$value_query .= $comma;
					$value_query .= "'".$option_value."'";

					// increment $i
					$i++;
				}
			}

			// finish off the query statement
			$end_query .= ",'".$foruminfo[$_GET['forumid']]['forumid']."','".$usergroupinfo[$_GET['usergroupid']]['usergroupid']."')";

			//print($start_query.$key_query.$middle_query.$value_query.$end_query);

			// before we run this query.. don't forget about inheritance! we have to use a special function to make sure we get inheritance right!
			addInheritedPermissions($foruminfo[$_GET['forumid']]['forumid'],$usergroupinfo[$_GET['usergroupid']]['usergroupid']);

			// update the DB
			query($start_query.$key_query.$middle_query.$value_query.$end_query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Thank you for adding permissions. You will now be redirected back.&uri=forum.php?do=permission");
		}

		// do header
		admin_header("wtcBB Admin Panel - Forums - Edit Permissions");

		construct_title($usergroupinfo[$_GET['usergroupid']]['name']." <span class=\"small\">(id: ".$usergroupinfo[$_GET['usergroupid']]['usergroupid'].")</span>");

		construct_table("options","edit_perms","perm_submit",1);


		// FORUM VIEWING PERMISSIONS \\
		construct_header("Forum Viewing Permissions",2);

		construct_input(1,"Can view message board","","edit_perms","can_view_board",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can view others' threads","","edit_perms","can_view_threads",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(1,"Can see deletion notices","","edit_perms","can_view_deletion",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can search forums","","edit_perms","can_search",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(1,"Can download attachments","","edit_perms","can_attachments",1,0,$usergroupinfo[$_GET['usergroupid']]);


		// POST / THREAD / ATTACHMENTS Permissions \\
		construct_header("Post/Thread/Attachments Permissions",2);

		construct_input(1,"Can post threads","","edit_perms","can_post_threads",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can reply to own threads","","edit_perms","can_reply_own",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(1,"Can reply to others' threads","","edit_perms","can_reply_others",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can edit own posts","","edit_perms","can_edit_own",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(1,"Can delete own posts","","edit_perms","can_delete_own",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can open/close own threads","","edit_perms","can_close_own",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(1,"Can delete own threads","","edit_perms","can_delete_threads_own",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can permanently delete own posts/threads","This setting will automatically override the above setting. If this is set to yes, any users belonging to this usergroup will be able to view deletion notices, and permanently delete threads/posts no matter what the previous setting is set to.","edit_perms","can_perm_delete",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(1,"Can upload attachments","","edit_perms","can_upload_attachments",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Has Flood Check Immunity","","edit_perms","flood_immunity",1,0,$usergroupinfo[$_GET['usergroupid']]);


		// POLL PERMISSIONS \\
		construct_header("Poll Permissions",2);

		construct_input(1,"Can make polls","","edit_perms","can_post_polls",0,0,$usergroupinfo[$_GET['usergroupid']]);

		construct_input(2,"Can vote on polls","","edit_perms","can_vote_polls",1,0,$usergroupinfo[$_GET['usergroupid']]);


		construct_footer(2,"perm_submit");
		construct_table_END(1);

		// do footer
		admin_footer();

		exit;
	}


	// do header
	admin_header("wtcBB Admin Panel - Edit Forum Permissions");

	construct_title("Edit Forum Permissions");

	construct_table("options","color_key","colorKey_submit");
	construct_header("Forums' Permission's Color Key",1);

		print("\t<tr>\n");
			print("\t\t<td class=\"desc1_bottom\">\n");
				print("<span style=\"color: #000000; font-weight: bold;\">Normal:</span> Default permissions; based on usergroup settings.<br />\n");
				print("<span style=\"color: #BB0000; font-weight: bold;\">Custom:<span style=\"font-weight: normal;\"> You have specified a different permissions setting than the default.</span></span><br />\n");
				print("<span style=\"color: #00285B; font-weight: bold;\">Inherited:<span style=\"font-weight: normal;\"> These permissions have been inherited from custom permissions set in a parent forum.</span></span><br />\n");
			print("\t\t</td>\n");
		print("\t</tr>\n");
	
	print("\t<tr><td class=\"footer\" colspan=\"1\">&nbsp;</td></tr>\n");

	construct_table_END();

	print("<br /><br />");

	construct_table("options","forum_perms","forumPerms_submit");
	construct_header("Forums' Permissions",1);

	loopForumPermissions();

	print("\t<tr><td class=\"footer\" colspan=\"1\" style=\"border-top: none;\">&nbsp;</td></tr>\n");
	construct_table_END();

	// do footer
	admin_footer();
}


// ##### DO DELETE MODERATOR ##### \\

else if($_GET['do'] == "delete_moderator") {
	// make sure we have valid moderatorid...
	$validitity = query("SELECT * FROM moderators WHERE moderatorid = '".$_GET['id']."' LIMIT 1");

	// uh oh!
	if(!mysql_num_rows($validitity)) {
		construct_error("Sorry, the moderator you are trying to delete does not exist.");
		exit;
	}

	// alright..make a construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// run function...
			query("DELETE FROM moderators WHERE moderatorid = '".$_GET['id']."' LIMIT 1");

			redirect("thankyou.php?message=Thank you for deleting the moderator. You will now be redirected back to the \"Edit Forums\" page.&uri=forum.php?do=edit");
		}

		// no...
		else {
			redirect("forum.php?do=edit");
		}
	}
	
	// do a confirm page...
	construct_confirm();
}

// ##### DO EDIT MODERATOR ##### \\

else if($_GET['do'] == "edit_moderator") {
	// whoops.. doesn't exist!
	if(!is_array($modinfo[$_GET['forumid']][$_GET['userid']])) {
		construct_error("Sorry, no moderator with this id or forumid exists.");
		exit;
	}

	$moderatorinfo = $modinfo[$_GET['forumid']][$_GET['userid']];

	// make sure form is set
	if($_POST['edit_mod']['set_form']) {
		// firstly.. make sure we are dealing with a proper username...
		$check_username = query("SELECT * FROM user_info WHERE username = '".$_POST['edit_mod']['mod_username']."' LIMIT 1");

		// uh oh!
		if(!mysql_num_rows($check_username)) {
			construct_error("You have entered an invalid username. Please <a href=\"javascript:history.back();\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// now check to see if the forum this mod is being assigned to has this mod already... make sure modid is different though!
		$check_uniqueness = query("SELECT * FROM moderators LEFT JOIN user_info ON moderators.userid = user_info.userid WHERE user_info.username = '".$_POST['edit_mod']['mod_username']."' AND moderators.forumid = '".$_POST['edit_mod']['forumid']."' AND moderators.moderatorid != '".$moderatorinfo['moderatorid']."'");

		if(mysql_num_rows($check_uniqueness)) {
			construct_error("Sorry, you cannot have two <em>different</em> moderators with the same username moderating the same forum. Please <a href=\"javascript:history.back();\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// intiate counter
		$x = 1;

		// start query
		$query = "UPDATE moderators SET ";

		// start formation
		foreach($_POST['edit_mod'] as $option_key => $option_value) {
			if($option_key != "set_form" AND $option_key != "mod_username") {
				// get comma
				if($x == 1) {
					$comma = "";
				} else {
					$comma = " , ";
				}

				$query .= $comma.$option_key." = '".$option_value."'";
				$x++;
			}
		}

		// end query
		$query .= " WHERE moderatorid = '".$_GET['userid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing <em>".$_POST['edit_mod']['username']."</em>. You will now be redirect back to the \"Edit Forums\" page.&uri=forum.php?do=edit");
	}


	// do header
	admin_header("wtcBB Admin Panel - Forums - Edit Moderator");

	construct_title("Edit Moderator");

	print("\n\n<div style=\"text-align: center; margin-top: 10px; margin-bottom: 0px;\"><div style=\"text-align: left; width: 90%; margin-bottom: 0px;\">\n");
	print("\t<a href=\"forum.php?do=delete_moderator&id=".$moderatorinfo['moderatorid']."\">Delete this moderator</a>\n");
	print("</div></div>\n\n");

	construct_table("options","edit_mod","mod_submit",1);

	construct_header("Edit Moderator <em>".$moderatorinfo['username']."</em>",2);

	construct_select(1,"Forum","","edit_mod","forumid","",0,0,0,2);

	construct_text(2,"Moderator Username","","edit_mod","mod_username",$moderatorinfo['username'],0);
	
	construct_input(1, "Moderate All Sub-Forums", "", "edit_mod", "recurse", 1, 0, $moderatorinfo);

	// POST / THREAD / ATTACHMENTS Permissions \\
	construct_header("Post/Thread/Attachments Permissions",2);

	construct_input(1,"Can Edit Posts","","edit_mod","can_edit",0,0,$moderatorinfo);

	construct_input(2,"Can Move Threads","","edit_mod","can_move",0,0,$moderatorinfo);

	construct_input(1,"Can Delete Posts","","edit_mod","can_delete",0,0,$moderatorinfo);

	construct_input(2,"Can Permanently Delete Posts","","edit_mod","can_permanently_delete",0,0,$moderatorinfo);

	construct_input(1,"Can Edit Threads","","edit_mod","can_edit_threads",0,0,$moderatorinfo);

	construct_input(2,"Can Open/Close Threads","","edit_mod","can_openClose_threads",0,0,$moderatorinfo);

	construct_input(1,"Can Edit Polls","","edit_mod","can_edit_polls",1,0,$moderatorinfo);


	// FORUM PERMISSIONS \\
	construct_header("Forum Permissions",2);

	construct_input(1,"Can Post Announcements","","edit_mod","can_post_announcements",0,0,$moderatorinfo);

	construct_input(2,"Can Mass Move Threads","","edit_mod","can_massmove_threads",0,0,$moderatorinfo);

	construct_input(1,"Can Mass Prune Threads","","edit_mod","can_massprune_threads",1,0,$moderatorinfo);


	// USER PERMISSIONS \\
	construct_header("User Permissions",2);

	construct_input(2,"Can View IP Addresss","","edit_mod","can_view_ip",0,0,$moderatorinfo);

	construct_input(1,"Can Ban Users","","edit_mod","can_ban",0,0,$moderatorinfo);

	construct_input(2,"Can Restore Banned Users","","edit_mod","can_restore",0,0,$moderatorinfo);

	construct_input(1,"Can Edit User Signatures","","edit_mod","can_edit_sigs",0,0,$moderatorinfo);

	construct_input(2,"Can Edit User Avatars","","edit_mod","can_edit_avatar",1,0,$moderatorinfo);


	// EMAIL PREFERENCES \\
	construct_header("Email Preferences",2);

	construct_input(1,"Receive an email when new thread is created","","edit_mod","receive_email_thread",0,0,$moderatorinfo);

	construct_input(2,"Receive an email when a new post is made","","edit_mod","receive_email_post",1,0,$moderatorinfo);

	construct_footer(2,"admin_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD MODERATOR ##### \\

else if($_GET['do'] == "add_moderator") {
	// make sure form is set
	if($_POST['add_mod']['set_form']) {
		// firstly run query to get userinfo of user they entered
		$userinfo_check = query("SELECT * FROM user_info WHERE username = '".$_POST['add_mod']['mod_username']."' LIMIT 1");

		// make sure the user exits...
		if(!mysql_num_rows($userinfo_check)) {
			construct_error("Sorry, the user you entered does not exist. Please <a href=\"javascript:history.back();\">click here</a> to go back, or user the back button on your browser.");
			exit;
		}

		// now check to see if the forum this mod is being assigned to has this mod already... no need to worry about modid.. it doesn't exist yet!
		$check_uniqueness = query("SELECT * FROM moderators LEFT JOIN user_info ON moderators.userid = user_info.userid WHERE user_info.username = '".$_POST['add_mod']['mod_username']."' AND moderators.forumid = '".$_POST['add_mod']['forumid']."'");

		if(mysql_num_rows($check_uniqueness)) {
			construct_error("Sorry, you cannot have two <em>different</em> moderators with the same username moderating the same forum. Please <a href=\"javascript:history.back();\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// array.. make sure user exists
		if(mysql_num_rows($userinfo_check)) {
			$userinfo = mysql_fetch_array($userinfo_check);
		}

		// intiate counter
		$x = 1;

		// start query
		$query = "INSERT INTO moderators (userid,";

		// time to form insert query... (first part...)
		foreach($_POST['add_mod'] as $option_key => $option_value) {
			if($option_key != "set_form" AND $option_key != "usergroupid" AND $option_key != "mod_username") {
				// comma?
				if($x == 1) {
					$comma = "";
				} else {
					$comma = ",";
				}

				$query .= $comma.$option_key;
				$x++;
			}
		}

		$query .= ") VALUES ('".$userinfo['userid']."',";

		// reset counter
		$x = 1;

		// second part of query
		foreach($_POST['add_mod'] as $option_key => $option_value) {
			if($option_key != "set_form" AND $option_key != "usergroupid" AND $option_key != "mod_username") {
				// comma?
				if($x == 1) {
					$comma = "";
				} else {
					$comma = ",";
				}

				$query .= $comma."'".$option_value."'";
				$x++;
			}
		}

		// finish query
		$query .= ")";

		//print($query);

		// now.. what if they chose to change the usergroup?
		if($_POST['add_mod']['usergroupid'] != "none") {
			// if the user they are trying to change is undeletable... ERROR!
			if(isUndeletable($userinfo['userid'])) {
				construct_error("Sorry, you cannot change the usergroup of that of an undeletable user. You still can however, assign this user to a moderator position, just set the usergroup option to \"Do Not Change Current Usergroup\". <a href=\"javascript:history.back();\">Click here</a> to go back, or use the back button on your browser.");
				exit;
			}

			$change_usergroup = "UPDATE user_info SET usergroupid = '".$_POST['add_mod']['usergroupid']."' WHERE userid = '".$userinfo['userid']."'";

			//print($change_usergroup);

			// otherwise we are good to change usergroup... run query
			query($change_usergroup);
		}

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding a moderator You will now be redirect back to the \"Edit Forums\" page.&uri=forum.php?do=edit");
	}


	// do header
	admin_header("wtcBB Admin Panel - Forums - Add Moderator");

	construct_title("Add Moderator");

	construct_table("options","add_mod","mod_submit",1);

	construct_header("Add Moderator",2);

	construct_select(1,"Forum","","add_mod","forumid","",0,0,0,2);

	construct_text(2,"Moderator Username","","add_mod","mod_username");
	
	construct_input(1, "Moderate All Sub-Forums", "", "add_mod", "recurse", 0, 0, $moderatorinfo);

	construct_select_begin(2,"Usergroup","If you want to make this user part of a \"Moderator\" usergroup, then you can select that usergroup that this user will be changed to, upon this user being added as a moderator of this forum.","add_mod","usergroupid",1);

		// get all usergroups
		$usergroup_select = query("SELECT * FROM usergroups ORDER BY name ASC");

		print("<option value=\"none\" selected=\"selected\">Do Not Change Current Usergroup</option>\n");
		
		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			print("<option value=\"".$usergroup['usergroupid']."\">".$usergroup['name']."</option>\n");
		}

		print("</select>\n");

	construct_select_end(2,1);

	// POST / THREAD / ATTACHMENTS Permissions \\
	construct_header("Post/Thread/Attachments Permissions",2);

	construct_input(1,"Can Edit Posts","","add_mod","can_edit",0,1);

	construct_input(2,"Can Move Threads","","add_mod","can_move",0,1);

	construct_input(1,"Can Delete Posts","","add_mod","can_delete",0,1);

	construct_input(2,"Can Permanently Delete Posts","","add_mod","can_permanently_delete",0,2);

	construct_input(1,"Can Edit Threads","","add_mod","can_edit_threads",0,1);

	construct_input(2,"Can Open/Close Threads","","add_mod","can_openClose_threads",0,1);

	construct_input(1,"Can Edit Polls","","add_mod","can_edit_polls",1,1);


	// FORUM PERMISSIONS \\
	construct_header("Forum Permissions",2);

	construct_input(1,"Can Post Announcements","","add_mod","can_post_announcements",0,1);

	construct_input(2,"Can Mass Move Threads","","add_mod","can_massmove_threads",0,2);

	construct_input(1,"Can Mass Prune Threads","","add_mod","can_massprune_threads",1,2);


	// USER PERMISSIONS \\
	construct_header("User Permissions",2);

	construct_input(2,"Can View IP Addresss","","add_mod","can_view_ip",0,1);

	construct_input(1,"Can Ban Users","","add_mod","can_ban",0,2);

	construct_input(2,"Can Restore Banned Users","","add_mod","can_restore",0,2);

	construct_input(1,"Can Edit User Signatures","","add_mod","can_edit_sigs",0,1);

	construct_input(2,"Can Edit User Avatars","","add_mod","can_edit_avatar",1,1);


	// EMAIL PREFERENCES \\
	construct_header("Email Preferences",2);

	construct_input(1,"Receive an email when new thread is created","","add_mod","receive_email_thread",0,2);

	construct_input(2,"Receive an email when a new post is made","","add_mod","receive_email_post",1,2);

	construct_footer(2,"admin_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD FORUM ##### \\

else if($_GET['do'] == "add") {
	// time to insert into the db...
	if($_POST['add_forum']['set_form']) {
		// do a select to get info about cat. parent
		if($_POST['add_forum']['category_parent'] != -1) {
			// set the depth..
			$depth = $foruminfo[$_POST['add_forum']['category_parent']]['depth'] + 1;
		}

		else if($_POST['add_forum']['category_parent'] == -1 AND !$_POST['add_forum']['is_category']) {
			// must be level 1.. 
			$depth = 1;
		}

		else if($_POST['add_forum']['category_parent'] == -1 AND $_POST['add_forum']['is_category']) {
			// must be a level one category...
			$depth = 1;
		}

		// set counter
		$i = 0;

		// intialize the $query var
		$query = "INSERT INTO forums (date_made,depth,";

		foreach($_POST['add_forum'] as $option_key => $option_value) {
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

		$query .= ") VALUES (".time().",'".$depth."',";

		// reset counter...
		$i = 0;

		foreach($_POST['add_forum'] as $option_key => $option_value) {
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

		if($_POST['add_forum']['category_parent'] != -1) {
			if(empty($foruminfo[$_POST['add_forum']['category_parent']]['childlist'])) {
				$childs = mysql_insert_id();
			}

			else {
				$childs = mysql_insert_id().",".$foruminfo[$_POST['add_forum']['category_parent']]['childlist'];
			}

			// form update query...
			$query = "UPDATE forums SET childlist = '".$childs."' WHERE forumid = '".$_POST['add_forum']['category_parent']."'";

			// run query...
			query($query);

			/* print("<br /><br />");

			print($query);

			print("<br /><br />"); */
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding a forum. You will now be redirected to the edit forums page.&uri=forum.php?do=edit");

		/* print("<br /><br />");

		print($query);

		print("<br /><br />"); */
	}

	// do header
	admin_header("wtcBB Admin Panel - Add Forum");

	construct_title("Add Forum");

	construct_table("options","add_forum","forum_submit",1);
	construct_header("Add Forum",2);

	construct_text(1,"Title","Put here the title of the forum. It may include HTML.","add_forum","forum_name","");
	construct_textarea(2,"Description","Put the description of the forum here. It will be shown right under the title, and HTML is allowed.","add_forum","forum_description","");
	construct_text(1,"Display Order","Put here the display order number in which you want this forum to be displayed. Set to \"0\" to not display this forum.","add_forum","display_order","1");
	construct_text(2,"Default View Age","Put here the cut off in which to display threads in this forum. Any threads made after this cut off will not be shown. It's recommended to leave this as 0, and set a default via wtcBB Options. That way, the user can choose how many threads are displayed as well.","add_forum","default_view","0");
	construct_text(1,"Link Redirect","If you want this forum to actually act as a link to somewhere else, enter the link here. Leave this field empty for it to take no effect.","add_forum","link_redirect");
	construct_text(2,"Password","If you want this to be a password protected forum, enter the password here. If it is left blank, it will not require a password. Even though it is password protected, it will still use permissions set for this forum as well.","add_forum","fpassword");
	construct_select(1,"Parent Forum","Select here the category in which you want this to be a sub-category of.","add_forum","category_parent","",0,1,0,2);

	construct_header("Style Options",2);

	construct_select_begin(1,"Default Style for this Forum","Select a default style for this forum.","add_forum","default_style");
			// get styles...
			$checkStyles = query("SELECT * FROM styles ORDER BY display_order, title");
			
			print("<option value=\"0\" selected=\"selected\">Use Forum Default</option>\n");

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				print("<option value=\"".$styleinfo['styleid']."\">".$styleinfo['title']."</option>\n");
			}

	construct_select_end(1);

	construct_input(2,"Override default user style?","Set this to yes to override a user\'s selected style, and use the one you have selected here.","add_forum","override_user_style",1,2);

	construct_header("Forum Options",2);
	construct_input(1,"Is Category?","Set this to <b>No</b> for it to be a regular forum, or set it to <b>Yes</b> to have it serve as a category.","add_forum","is_category",0,2);
	construct_input(2,"Is Active?","If you set this to <b>No</b>, this forum will not appear.","add_forum","is_active",0,1);
	construct_input(1,"Is Open?","Set this to <b>No</b> to prevent any new posts from being made.","add_forum","is_open",1,1);

	construct_header("Forum Features",2);
	construct_input(1,"Allow HTML","Setting this to yes will allow HTML in this forum. <b>It is strongly recommended that this is kept disabled.</b>","add_forum","allow_html",0,2);
	construct_input(2,"Allow wtcBB Code","Setting this to yes will allow wtcBB Code in this forum.","add_forum","allow_wtcBB",0,1);
	construct_input(1,"Allow [img] code","Setting this to yes will allow uses to post images using the [img] code.","add_forum","allow_img",0,1);
	construct_input(2,"Allow Smilies","Setting this to yes will allow users to use smilies in their posts.","add_forum","allow_smilies",0,1);
	construct_input(1,"Allow Post Icons","Setting this to yes will allow users to use post icons in their threads and posts.","add_forum","allow_posticons",0,1);
	construct_input(2,"Count Posts","Setting this to yes will count posts made in this forum by users towards their post count.","add_forum","count_posts",0,1);
	construct_input(1,"Show on Forum Jump","Setting this to yes will show this forum, and all sub-forums on the forum jump menu.","add_forum","show_on_forumjump",1,1);

	construct_footer(2,"forum_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO EDIT FORUM ##### \\

// let's add the forums.. shall we?
else if($_GET['do'] == "edit") {

	// make sure we have a valid forum...
	if($_GET['id']) {
		// make sure it's valid...
		if(!is_array($foruminfo[$_GET['id']])) {
			construct_error("Invalid FORUM ID");
			exit;
		}

		// otherwise we are good to go
		else {
			// do we want to delete?
			if($_GET['action'] == "delete") {
				// make sure form is set..
				if($_POST['confirm']['set_form']) {
					// yes...
					if($_POST['confirm']['yes_no']) {
						// run function...
						delete_forum($_GET['id']);

						redirect("thankyou.php?message=Thank you for deleting the forum. You will now be redirected back.&uri=forum.php?do=edit");
					}

					// no...
					else {
						redirect("forum.php?do=edit");
					}
				}
				
				// do a confirm page...
				construct_confirm();
			}

			// otherwise we aren't deleting.. we are editing
			else {
				// run select query to get current forum information...
				$forum_options = $foruminfo[$_GET['id']];

				// alright time to update the DB...
				if($_POST['forum_update']['set_form']) {
					// make sure we aren't making a forum a parent of itself...
					if($_POST['forum_update']['category_parent'] == $forum_options['forumid']) {
						construct_error("Sorry, you cannot make a forum a parent of itself. <a href=\"javascript:history.back()\">Click here</a> to go back.");

						exit;
					}

					// errrmmm.. make sure we aren't parenting this forum to itself or to a child.. big problems!
					else if(checkChilds($forum_options['forumid'],$_POST['forum_update']['category_parent'])) {
						construct_error("Sorry, you cannot parent this forum to a child. <a href=\"javascript:history.back()\">Go back.</a>");

						exit;
					}

					// otherwise.. we are good to go...
					else {

						// do a select to get info about cat. parent
						if($_POST['forum_update']['category_parent'] != -1) {
							// set the depth..
							$depth = $foruminfo[$_POST['forum_update']['category_parent']]['depth'] + 1;
						}

						else if($_POST['forum_update']['category_parent'] == -1 AND !$_POST['forum_update']['is_category']) {
							// must be level 1.. 
							$depth = 1;
						}

						else if($_POST['forum_update']['category_parent'] == -1 AND $_POST['forum_update']['is_category']) {
							// must be a level one category...
							$depth = 1;
						}

						// set counter
						$i = 0;

						// intialize the $query var
						$query = "UPDATE forums SET";

						foreach($_POST['forum_update'] as $option_key => $option_value) {
							if($option_key != "set_form") {
								// look for comma
								if($i > 0) {
									$comma = " , ";
								} else {
									$comma = " ";
									$i++;
								}

								$query .= $comma.$option_key." = '".addslashes(htmlspecialchars($option_value))."'";
							}
						}

						$query .= " WHERE forumid = '".$_GET['id']."'";

						// update just depth first...
						query("UPDATE forums SET depth = '".$depth."' WHERE forumid = '".$_GET['id']."'");

						// we have to go through and change quite a few things if they change the parent category... do this before update!!
						if($_POST['forum_update']['category_parent'] != $forum_options['category_parent']) {

							// first let's update the parent's childlist...
							if($_POST['forum_update']['category_parent'] != -1) {
								$direct_parent1 = query("SELECT * FROM forums WHERE forumid = '".$_POST['forum_update']['category_parent']."' LIMIT 1",1);

								// just add this to the childlist.. make sure we know when to use a comma...
								if(empty($direct_parent1['childlist'])) {
									$new_childlist1 = $forum_options['forumid'];
								} else {
									$new_childlist1 = $direct_parent1['childlist'].",".$forum_options['forumid'];
								}

								// now update the db with the new childlist...
								query("UPDATE forums SET childlist = '".$new_childlist1."' WHERE forumid = '".$direct_parent1['forumid']."'");
							}
							
							// use same method of deleting id from childlist as when we delete a forum from "functions_admin.php"
							$childlist = explode(",",$foruminfo[$forum_options['category_parent']]['childlist']);

							// intiate counter so we know when to not use a comma...
							$counter = 1;

							foreach($childlist as $key => $value) {
								// find the id...
								if($value == $forum_options['forumid']) {
									// unset value for this loop...
									unset($value);
								}

								// otherwise.. we are good to go... reconstruct childlist!
								else {
									if($counter == 1) {
										$new_childlist .= $value;
										$counter++;
									}

									else {
										$new_childlist .= ",".$value;
									}
								}
							}

							// update the childlist...
							query("UPDATE forums SET childlist = '".$new_childlist."' WHERE forumid = '".$forum_options['category_parent']."'");

							// don't forget... now we need to update any and all childs of this forum with a depth.. only if it doesn't match
							if($depth != $forum_options['depth']) {
								update_depth($forum_options['forumid']);
							}

							/*print("<br /><br />");

							print("UPDATE forums SET childlist = '".$new_childlist."' WHERE forumid = '".$direct_parent['forumid']."'");

							print("<br /><br />");*/

						}

						// update the DB
						query($query);

						// redirect to thankyou page...
						redirect("thankyou.php?message=Thank you for editing <em>".$_POST['forum_update']['forum_name']."</em>. You will now be redirected back.&uri=forum.php?do=edit");

						/*print("<br /><br />");

						print($query);

						print("<br /><br />");*/
					}
				}

				// do header
				admin_header("wtcBB Admin Panel - Edit Forum");

				construct_title("Edit <em>".$forum_options['forum_name']."</em> <span class=\"small\">(forumid: ".$forum_options['forumid'].")</span>");

				construct_table("options","forum_update","forum_submit",1);
				construct_header("Edit Forum",2);

				construct_text(1,"Title","Put here the title of the forum. It may include HTML.","forum_update","forum_name",$forum_options['forum_name']);
				construct_textarea(2,"Description","Put the description of the forum here. It will be shown right under the title, and HTML is allowed.","forum_update","forum_description",$forum_options['forum_description']);
				construct_text(1,"Display Order","Put here the display order number in which you want this forum to be displayed. Set to \"0\" to not display this forum.","forum_update","display_order",$forum_options['display_order']);
				construct_text(2,"Default View Age","Put here the cut off in which to display threads in this forum. Any threads made after this cut off will not be shown. It's recommended to leave this as 0, and set a default via wtcBB Options. That way, the user can choose how many threads are displayed as well.","forum_update","default_view",$forum_options['default_view']);
				construct_text(1,"Link Redirect","If you want this forum to actually act as a link to somewhere else, enter the link here. Leave this field empty for it to take no effect.","forum_update","link_redirect",$forum_options['link_redirect']);
				construct_text(2,"Password","If you want this to be a password protected forum, enter the password here. If it is left blank, it will not require a password. Even though it is password protected, it will still use permissions set for this forum as well.","forum_update","fpassword",$forum_options['fpassword']);
				construct_select(1,"Parent Forum","Select here the category in which you want this to be a sub-category of.","forum_update","category_parent","",0,1,0,2);

				construct_header("Style Options",2);


				construct_select_begin(1,"Default Style for this Forum","Select a default style for this forum.","forum_update","default_style");
						// get styles...
						$checkStyles = query("SELECT * FROM styles ORDER BY display_order, title");
						
						if($forum_options['original_style'] == 0) {
							print("<option value=\"0\" selected=\"selected\">Use Forum Default</option>\n");
						} else {
							print("<option value=\"0\">Use Forum Default</option>\n");
						}

						// loop through styles
						while($styleinfo = mysql_fetch_array($checkStyles)) {
							if($forum_options['original_style'] == $styleinfo['styleid']) {
								$selected = " selected=\"selected\"";
							} else {
								$selected = "";
							}

							print("<option value=\"".$styleinfo['styleid']."\"".$selected.">".$styleinfo['title']."</option>\n");
						}

				construct_select_end(1);


				construct_input(2,"Override default user style?","Set this to yes to override a user\'s selected style, and use the one you have selected here.","forum_update","override_user_style",1,0,$forum_options);

				construct_header("Forum Options",2);
				construct_input(1,"Is Category?","Set this to <b>No</b> for it to be a regular forum, or set it to <b>Yes</b> to have it serve as a category.","forum_update","is_category",0,0,$forum_options);
				construct_input(2,"Is Active?","If you set this to <b>No</b>, this forum will not appear.","forum_update","is_active",0,0,$forum_options);
				construct_input(1,"Is Open?","Set this to <b>No</b> to prevent any new posts from being made.","forum_update","is_open",1,0,$forum_options);

				construct_header("Forum Features",2);
				construct_input(1,"Allow HTML","Setting this to yes will allow HTML in this forum. <b>It is strongly recommended that this is kept disabled.</b>","forum_update","allow_html",0,0,$forum_options);
				construct_input(2,"Allow wtcBB Code","Setting this to yes will allow wtcBB Code in this forum.","forum_update","allow_wtcBB",0,0,$forum_options);
				construct_input(1,"Allow [img] code","Setting this to yes will allow uses to post images using the [img] code.","forum_update","allow_img",0,0,$forum_options);
				construct_input(2,"Allow Smilies","Setting this to yes will allow users to use smilies in their posts.","forum_update","allow_smilies",0,0,$forum_options);
				construct_input(1,"Allow Post Icons","Setting this to yes will allow users to use post icons in their threads and posts.","forum_update","allow_posticons",0,0,$forum_options);
				construct_input(2,"Count Posts","Setting this to yes will count posts made in this forum by users towards their post count.","forum_update","count_posts",0,0,$forum_options);
				construct_input(1,"Show on Forum Jump","Setting this to yes will show this forum, and all sub-forums on the forum jump menu.","forum_update","show_on_forumjump",1,0,$forum_options);

				construct_footer(2,"forum_submit");
				construct_table_END(1);

				// do footer...
				admin_footer();
			}
		}
	}

	// otherwise we are DISPLAYING the forums to edit...
	else {
		// do update display order
		if($_POST['update_forum']['set_form']) {
			foreach($_POST['update_forum_order'] as $key => $value) {
				// run query
				query("UPDATE forums SET display_order = '".$value."' WHERE forumid = '".$key."'");

				// redirect to thankyou page...
				redirect("thankyou.php?message=Thank you for updating the forum display order. You will now be redirected back.&uri=forum.php?do=edit");
			}
		}

		// do header
		admin_header("wtcBB Admin Panel - Edit Forums");

		construct_title("Edit Forums");

		// run the query..
		$run_query = query("SELECT * FROM forums");

		// make sure we have forums.. if not just return a message...
		if(!mysql_num_rows($run_query)) {
			print("<blockquote style=\"width: 90%; text-align: left;\">\n");
			print("<br />No forums found in the database.");
			print("\n</blockquote>");
		}

		// ah finally.. we can display the forums...
		else {
			print("\n\n<br />\n\n");

			construct_table("options","update_forum","forum_submit",1);
			construct_header("Edit Forums",4);

			loop_forums();

			print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"4\"><button type=\"button\" onClick=\"location.href='forum.php?do=add'\" ".$submitbg.">Add New Forum</button> &nbsp;&nbsp;&nbsp; <button type=\"submit\" ".$submitbg.">Save Display Order</button></td></tr>\n");
			construct_table_END(1);
		}

		// do footer...
		admin_footer();
	}
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}


?>