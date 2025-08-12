<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //ADMIN PANEL USER\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Users";
$permissions = "users";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

// build the templates
$templateinfo = buildTemplateArr();

// set $is_user to know it's the user file...
$is_user = true;


// ##### DO CHANGE AVATAR ##### \\

if($_GET['do'] == "change_avatar") {
	// make sure we have a valid userid...
	$checkUserid = query("SELECT * FROM user_info WHERE userid = '".$_GET['userid']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkUserid)) {
		construct_error("Sorry, there is no user matching the given USERID.");
		exit;
	}

	// get userinfo...
	$userinfo = mysql_fetch_array($checkUserid);

	// make sure this user isn't undeletable.. if so.. present error and exit!
	if(isUndeletable($userinfo['userid'])) {
		construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
		exit;
	}

	if($_POST['avatar_manager']['set_form']) {
		// custom or not?
		if($_POST['avatar_manager']['avatar'] == "custom") {
			// custom avatar.. just put the URL given into the DB...
			$update_query = "UPDATE user_info SET avatar_url = '".addslashes($_POST['avatar_manager']['custom_url'])."' WHERE userid = '".$userinfo['userid']."'";

			//print($update_query);

			// run query
			query($update_query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Avatar changed successfully. You will now be redirected to ".$userinfo['username']."\'s user info page.&uri=user.php?do=editSTEVEid=".$userinfo['userid']);
		}

		// delete!
		else if($_POST['avatar_manager']['avatar'] == "delete") {
			// we aren't deleting anything from the DB.. just updating the avatar field to "none"
			$update_query = "UPDATE user_info SET avatar_url = 'none' WHERE userid = '".$userinfo['userid']."'";

			//print($update_query);

			// run query
			query($update_query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Avatar deleted successfully. You will now be redirected to ".$userinfo['username']."\'s user info page.&uri=user.php?do=editSTEVEid=".$userinfo['userid']);
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
			redirect("thankyou.php?message=Avatar changed successfully. You will now be redirected to ".$userinfo['username']."\'s user info page.&uri=user.php?do=editSTEVEid=".$userinfo['userid']);
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

			$pageNumbers .= "<button type=\"button\" onClick=\"location.href='user.php?do=change_avatar&userid=".$_GET['userid']."&per_page=".$perpage."&start=".$starting_count."';\" style=\"padding: 1px;\" ".$submitbg.$disabled.">".$x."</button>&nbsp; ";
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

			if(!($x % 2)) {
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
			if(!$modulus) {
				print("\n\t</tr>\n\n");
				print("\n\t<tr>\n\n");
			}
		}

		print("\t<tr><td class=\"footer\" colspan=\"".$colspan."\" style=\"border-left: none; border-right: none;\">".$pageNumbers." &nbsp;&nbsp;&nbsp; Avatars Per Page: <input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"per_page\" value=\"".$perpage."\" /> <button type=\"button\" onClick=\"location.href='user.php?do=change_avatar&userid=".$_GET['userid']."&per_page=' + document.avatar_manager.per_page.value;\" style=\"margin-bottom: 1px;\" ".$submitbg.">Go</button></td></tr>\n");

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

else if($_GET['do'] == "restore") {
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

			redirect("thankyou.php?message=You have successfully restored <em>".$userinfo['username']."</em>. You will now be redirected back to the view banned users page.&uri=user.php?do=view_banned");
		}

		// no...
		else {
			redirect("user.php?do=view_banned");
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

		while($userinfo = mysql_fetch_array($userinfo_query)) {
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
					print("\t\t\t<button type=\"button\" onClick=\"location.href='user.php?do=restore&id=".$userinfo['userid']."';\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Restore</button> <button type=\"button\" onClick=\"location.href='user.php?do=edit&id=".$userinfo['userid']."'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button> <button type=\"button\" onClick=\"location.href='user.php?do=edit&id=".$userinfo['userid']."&action=delete'\" ".$submitbg." style=\"margin: 0px;\">Delete</button>\n");
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
else if($_GET['do'] == "ban") {
	// do the form stuff
	if($_POST['ban_user']['set_form']) {
		// make sure they have entered in a valid username
		$username_query = query("SELECT * FROM user_info WHERE username = '".$_POST['ban_user']['username']."' LIMIT 1");
		$userinfo = mysql_fetch_array($username_query);

		if(!mysql_num_rows($username_query)) {
			construct_error("You have entered an invalid username. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		else if(isUndeletable($userinfo['userid'])) {
			construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// we are good to go! 
		else {
			redirect("user.php?do=ban&ban_username=".$_POST['ban_user']['username']."&ban_usergroup=".$_POST['ban_user']['usergroupid']);
		}
	}

	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// run query to ban user
			query("UPDATE user_info SET usergroupid = '".$_GET['ban_usergroup']."' WHERE username = '".$_GET['ban_username']."'");

			redirect("thankyou.php?message=You have successfully banned <em>".$_GET['ban_username']."</em>. You will now be redirected back to the user ban page.&uri=user.php?do=ban");
		}

		// no...
		else {
			redirect("user.php?do=ban");
		}
	}

	if($_GET['ban_username'] AND $_GET['ban_usergroup']) {	
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

// ##### DO SEND E-MAIL ##### \\
else if($_GET['do'] == "email") {
	// translate to unix timestamp with mktime
	$_POST['email_user']['birthday'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);
	$_POST['email_user']['date_joined'] = mktime(0,0,0,$_POST['month1'],$_POST['day1'],$_POST['year1']);
	$_POST['email_user']['lastvisit'] = mktime($_POST['hour2'],$_POST['minute2'],0,$_POST['month2'],$_POST['day2'],$_POST['year2']);
	$_POST['email_user']['lastactivity'] = mktime($_POST['hour3'],$_POST['minute3'],0,$_POST['month3'],$_POST['day3'],$_POST['year3']);
	$_POST['email_user']['lastpost'] = mktime($_POST['hour4'],$_POST['minute4'],0,$_POST['month4'],$_POST['day4'],$_POST['year4']);

	// different form.. this is the second page and more!
	if($_POST['next_page']['set_form']) {
		// get current page
		$currentPage = $_POST['email_user']['page_num'];

		$start = $_POST['start'];

		// get start and end
		$start += $_POST['email_user']['num_of_emails'];
		$end = $_POST['email_user']['num_of_emails'];

		// form query...
		$total_query = "SELECT * FROM user_info WHERE ".$_POST['search_by']." ORDER BY username LIMIT ".$start.", ".$end;

		// run the query
		$userinfo_query = query($total_query);

		// let's do some math depending upon the email options now shall we? (oh i love math.. no really i do :))
		// find total users.. can't use query before, b/c that has a limit... :(
		$total_users_query = query("SELECT * FROM user_info WHERE ".$_POST['search_by']." ORDER BY username");
		$total_users = mysql_num_rows($total_users_query);

		// do header
		admin_header("wtcBB Admin Panel - Users - Emailing");

		construct_title("Emailing");

		// get users... if we are on the last page... just use total users...
		if($currentPage == $_POST['num_of_pages']) {
			$end2 = $total_users;
		} else {
			$end2 = $start + $_POST['email_user']['num_of_emails'];
		}

		print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");

		print("Showing users ".($start+1)." to ".$end2." of ".$total_users."<br /><br />");

		//print($total_query."<br /><br />");

		while($userinfo = mysql_fetch_array($userinfo_query)) {
			// test or live?
			if($_POST['email_user']['test_email']) {
				// testing
				$confirmation = "...testing...OK";
				print("<strong>".$userinfo['username']."</strong>".$confirmation."<br />");
			} else {
				// live
				// make some variables
				eval("\$username = \"".addslashes($userinfo['username'])."\";");
				eval("\$userid = \"".addslashes($userinfo['userid'])."\";");
				eval("\$email = \"".addslashes($userinfo['email'])."\";");

				eval("\$messaging = \"".addslashes($_POST['email_user']['message'])."\";");

				eval("\$mailing_check = \"".mail($userinfo['email'],$_POST['email_user']['subject'],$messaging,"From: ".$_POST['email_user']['from'])."\";");
				if($mailing_check) {
					// confirmation message
					$confirmation = "...sending...OK";

					// print stuff
					print("<strong>".$userinfo['username']."</strong>".$confirmation."<br />");
				} else {
					// FAILED!
					$confirmation = "...sending...<strong style=\"color: #BB0000;\">ERROR</strong>";
					print("<strong>".$userinfo['username']."</strong>".$confirmation."<br />");
				}
			}
		}

		print("\n\n</div></div>\n\n");

		// increment page...
		$_POST['email_user']['page_num']++;

		// last page?
		if($_POST['num_of_pages'] == $currentPage) {
			construct_title("Emails sent successfully... &nbsp;&nbsp;&nbsp;<button type=\"button\" onClick=\"location.href='user.php?do=email';\" ".$submitbg.">Go Back</button>");
		}

		else {
			// transer information through forms...
			print("\n\n<form method=\"post\" action=\"\" style=\"margin: 0px;\" name=\"email_user\">\n");
				print("<input type=\"hidden\" name=\"next_page[set_form]\" value=\"1\" />\n");
				print("<input type=\"hidden\" name=\"search_by\" value=\"".$_POST['search_by']."\" />\n");
				print("<input type=\"hidden\" name=\"email_user[page_num]\" value=\"".$_POST['email_user']['page_num']."\" />\n");
				print("<input type=\"hidden\" name=\"email_user[subject]\" value=\"".$_POST['email_user']['subject']."\" />\n");
				print("<input type=\"hidden\" name=\"email_user[message]\" value=\"".$_POST['email_user']['message']."\" />\n");
				print("<input type=\"hidden\" name=\"email_user[from]\" value=\"".$_POST['email_user']['from']."\" />\n");
				print("<input type=\"hidden\" name=\"email_user[test_email]\" value=\"".$_POST['email_user']['test_email']."\" />\n");
				print("<input type=\"hidden\" name=\"email_user[num_of_emails]\" value=\"".$_POST['email_user']['num_of_emails']."\" />\n");
				print("<input type=\"hidden\" name=\"num_of_pages\" value=\"".$_POST['num_of_pages']."\" />\n");
				print("<input type=\"hidden\" name=\"start\" value=\"".$start."\" />\n");
			construct_title("<button type=\"submit\" style=\"margin-bottom: 1px;\" ".$submitbg.">Next Page</button></form>");
		}

		// do footer
		admin_footer();

		// exit!
		exit;
	}

	// only the FIRST page of results!
	if($_POST['email_user']['set_form']) {
		// get current page
		$currentPage = 1;

		$end = $_POST['email_user']['num_of_emails'];

		// intiate counter
		$x = 1;

		// let's loop through the email_user to get the fields...
		foreach($_POST['email_user'] as $option_key => $option_value) {
			if($option_key == "set_form") {
				continue;
			}

			// make sure we are dealing with a non-empty variable...
			if(($option_key != "test_email" AND $option_value == '0') OR (!empty($option_value) AND $option_value != '-1' AND $option_key != "message" AND $option_key != "from" AND $option_key != "subject" AND $option_key != "test_email" AND $option_key != "num_of_emails" AND $option_key != "page_num")) {

				if($x != 1) {
					$beginning = " AND ";
				} else {
					$beginning = " ";
				}

				// if it's the username, email address, parent email, usertitle, AIM, YAHOO, MSN, biography, location, occupation, or interests...
				if($option_key == "username" OR $option_key == "email" OR $option_key == "parent_email" OR $option_key == "usertitle" OR $option_key == "aim" OR $option_key == "msn" OR $option_key == "yahoo" OR $option_key == "biography" OR $option_key == "locationUser" OR $option_key == "interests" OR $option_key == "occupation") {
					$_POST['search_by'] .= $beginning.$option_key." LIKE '%".$option_value."%'";
					$x++;
				}

				else if($option_key == "usergroupid") {
					if($option_value != "all") {
						$_POST['search_by'] .= $beginning.$option_key." = '".$option_value."'";
						$x++;
					}
				}

				else if($option_value == "either") {
					$_POST['search_by'] .= $beginning."(".$option_key." = '1' OR ".$option_key." = '0')";
					$x++;
				}

				else {
					$_POST['search_by'] .= $beginning.$option_key." = '".$option_value."'";
					$x++;
				}
			}
		}

		$_POST['search_by'] .= " AND admin_send_email = 1";

		$total_query = "SELECT * FROM user_info WHERE ".$_POST['search_by']." ORDER BY username LIMIT 0, ".$_POST['email_user']['num_of_emails'];

		//print($total_query);

		// run the query
		$userinfo_query = query($total_query);

		if(!$_POST['search_by']) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// uh oh! no users found!
		else if(!mysql_num_rows($userinfo_query)) {
			construct_error("Sorry, no users were found with that criteria. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		else {
			// let's do some math depending upon the email options now shall we? (oh i love math.. no really i do :))
			// find total users.. can't use query before, b/c that has a limit... :(
			$total_users_query = query("SELECT * FROM user_info WHERE ".$_POST['search_by']." ORDER BY username");
			$total_users = mysql_num_rows($total_users_query);

			// if we only need one page.. then yay!
			if($total_users <= $_POST['email_user']['num_of_emails']) {
				$num_of_pages = 1;
			}

			// otherwise.. more than one page
			else {
				// find $num_of_pages by modulus
				$modulus = $total_users % $_POST['email_user']['num_of_emails'];
				
				$num_of_pages = $total_users / $_POST['email_user']['num_of_emails'];

				// set the type to int... no decimals!
				settype($num_of_pages,int);

				// now if our modulus is over 0.. then we add one page to the num_of_pages...
				if($modulus) {
					$num_of_pages++;
				}
			}

			// do header
			admin_header("wtcBB Admin Panel - Users - Emailing");

			construct_title("Emailing");

			// if there is in fact only one page...
			if($num_of_pages == 1) {
				$end2 = $total_users;
			}

			// otherwise there are more pages...
			else {
				$end2 = $_POST['email_user']['num_of_emails'];
			}

			print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");

			print("Showing users 1 to ".$end2." of ".$total_users."<br /><br />");

			while($userinfo = mysql_fetch_array($userinfo_query)) {
				// test or live?
				if($_POST['email_user']['test_email']) {
					// testing
					$confirmation = "...testing...OK";
					print("<strong>".$userinfo['username']."</strong>".$confirmation."<br />");
				} else {
					// live
					// make some variables
					eval("\$username = \"".addslashes($userinfo['username'])."\";");
					eval("\$userid = \"".addslashes($userinfo['userid'])."\";");
					eval("\$email = \"".addslashes($userinfo['email'])."\";");

					eval("\$messaging = \"".addslashes($_POST['email_user']['message'])."\";");

					eval("\$mailing_check = \"".mail($userinfo['email'],$_POST['email_user']['subject'],$messaging,"From: ".$_POST['email_user']['from'])."\";");
					if($mailing_check) {
						// confirmation message
						$confirmation = "...sending...OK";

						// print stuff
						print("<strong>".$userinfo['username']."</strong>".$confirmation."<br />");
					} else {
						// FAILED!
						$confirmation = "...sending...<strong style=\"color: #BB0000;\">ERROR</strong>";
						print("<strong>".$userinfo['username']."</strong>".$confirmation."<br />");
					}
				}
			}

			print("\n\n</div></div>\n\n");

			// increment page...
			$_POST['email_user']['page_num']++;

			// last page?
			if($num_of_pages == $currentPage) {
				construct_title("Emails sent successfully... &nbsp;&nbsp;&nbsp;<button type=\"button\" onClick=\"location.href='user.php?do=email';\" ".$submitbg.">Go Back</button>");
			}

			else {
				// transer information through forms...
				print("<form method=\"post\" action=\"\" style=\"margin: 0px;\" name=\"email_user\">\n");
					print("<input type=\"hidden\" name=\"next_page[set_form]\" value=\"1\" />\n");
					print("<input type=\"hidden\" name=\"search_by\" value=\"".$_POST['search_by']."\" />\n");
					print("<input type=\"hidden\" name=\"email_user[page_num]\" value=\"".$_POST['email_user']['page_num']."\" />\n");
					print("<input type=\"hidden\" name=\"email_user[subject]\" value=\"".$_POST['email_user']['subject']."\" />\n");
					print("<input type=\"hidden\" name=\"email_user[message]\" value=\"".$_POST['email_user']['message']."\" />\n");
					print("<input type=\"hidden\" name=\"email_user[from]\" value=\"".$_POST['email_user']['from']."\" />\n");
					print("<input type=\"hidden\" name=\"email_user[test_email]\" value=\"".$_POST['email_user']['test_email']."\" />\n");
					print("<input type=\"hidden\" name=\"email_user[num_of_emails]\" value=\"".$_POST['email_user']['num_of_emails']."\" />\n");
					print("<input type=\"hidden\" name=\"num_of_pages\" value=\"".$_POST['num_of_pages']."\" />\n");
				construct_title("<button type=\"submit\" ".$submitbg.">Next Page</button></form>");
			}

			// do footer
			admin_footer();

			// exit!
			exit;
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Send Email");

	construct_title("Send Email");

	construct_table("options","email_user","email_submit",1);

	construct_header("Send Email Options",2);

	construct_input(1,"Test e-mail only?","","email_user","test_email",0,1);

	construct_text(2,"Number of email to send at once","","email_user","num_of_emails","250",0);

	construct_text(1,"From","","email_user","from",$bboptions['details_contact'],0);

	construct_text(2,"Subject","","email_user","subject","Re: ",0);

	construct_textarea(1,"Message","You can use the following variables in this textarea, which will be shown accordingly to the user's who receive this email. <br /><br /><strong>\$userid</strong> - Show the user id. <br /> <strong>\$username</strong> - Shows the username. <br /> <strong>\$email</strong> - Shows the email of the user.","email_user","message","",1);

	construct_footer(2,"email_submit");

	construct_table_END(0);

	print("\n\n<br /><br />\n\n");
	print("<input type=\"hidden\" name=\"email_user[page_num]\" value=\"1\" />\n\n");

	
	// EMAIL CRITERIA

	construct_table("options","email_user","email_criteria_submit",0);
	construct_header("Search for User",2);

	construct_text(1,"Username","Enter here the username of the new user you are creating.","email_user","username","");

	construct_text(2,"E-mail Address","Enter here the e-mail address of the new user you are creating.","email_user","email","");

	construct_text(1,"Parent E-mail Address","Enter here the e-mail address of the parent of this user.","email_user","parent_email","");

	construct_text(2,"User Title","Input here the user title in which will be shown in this user's profile, and below this user's username in posts.","email_user","usertitle","");

	construct_select_begin(1,"Usergroup","","email_user","usergroupid");

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
			<label for="is_coppa1"><input type="radio" name="email_user[is_coppa]" id="is_coppa1" value="1" /> Yes</label>
			<label for="is_coppa2"><input type="radio" name="email_user[is_coppa]" id="is_coppa2" value="0" /> No</label>
			<label for="is_coppa3"><input type="radio" name="email_user[is_coppa]" id="is_coppa3" value="either" checked="checked" /> Either</label>
		</td>
	</tr>
	<?php

	construct_text(1,"Homepage","","email_user","homepage","");

	construct_text(2,"AOL Instant Messenger Handle","","email_user","aim","");

	construct_text(1,"MSN Handle","","email_user","msn","");

	construct_text(2,"Yahoo Messenger Handle","","email_user","yahoo","");

	construct_text(1,"ICQ Handle","","email_user","icq","");


	construct_select_begin(1,"Birthday","","email_user","birthday",0,1);

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


	construct_text(2,"IP Address","","email_user","user_ip_address","");

	construct_text(1,"Post Count","","email_user","posts","");

	construct_text(2,"Referrer","","email_user","referral_username");

	construct_text(1,"Referrals","","email_user","referrals","",1);

	
	
	// ##### TIME OPTIONS ##### \\

	construct_header("Time Options",2);

	construct_select_begin(2,"Join Date","","email_user","date_joined",0,1);

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


	construct_select_begin(1,"Last Visit","","email_user","lastvisit",0,1);

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
		$hours = date("H");
		print("<input type=\"text\" name=\"hour2\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Minute<br />\n");

		// get minutes
		$minutes = date("i");
		print("<input type=\"text\" name=\"minute2\" size=\"2\" class=\"text\" value=\"\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Last Activity","","email_user","lastactivity",0,1);

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


	construct_select_begin(1,"Last Post","","email_user","lastpost",1,1);

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

	construct_text(1,"Biography","","email_user","biography");
	construct_text(2,"Location","","email_user","locationUser");
	construct_text(1,"Interests","","email_user","interests");
	construct_text(2,"Occupation","","email_user","occupation","",1);

	?>
	<tr>
		<td class="footer" colspan="2"><pre><button type="submit" id="user_submit" <?php print($submitbg); ?>>Search</button>  <button type="reset" <?php print($submitbg); ?>>Reset</button></pre></td>
	</tr>
	<?php

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD USER ##### \\

else if($_GET['do'] == "add") {
	// get birthday...
	if($_POST['month'] AND $_POST['day']) {
		if(!$_POST['year']) {
			$_POST['year'] = "0000";
		}

		// just make sure nothing is above 31... no biggy
		if($_POST['day'] > 31) {
			$_POST['day'] = 31;
		}

		$_POST['add_user']['birthday'] = $_POST['month']."-".$_POST['day']."-".$_POST['year'];
	}

	else {
		$_POST['add_user']['birthday'] = "";
	}
	
	// translate to unix timestamp with mktime
	$_POST['add_user']['date_joined'] = mktime(date('G'),date('i'),date('s'),$_POST['month1'],$_POST['day1'],$_POST['year1']);
	$_POST['add_user']['lastvisit'] = mktime($_POST['hour2'],$_POST['minute2'],0,$_POST['month2'],$_POST['day2'],$_POST['year2']);
	$_POST['add_user']['lastactivity'] = mktime($_POST['hour3'],$_POST['minute3'],0,$_POST['month3'],$_POST['day3'],$_POST['year3']);
	$_POST['add_user']['lastpost'] = mktime($_POST['hour4'],$_POST['minute4'],0,$_POST['month4'],$_POST['day4'],$_POST['year4']);

	// going to update information...
	if($_POST['add_user']['set_form']) {
		// check username first...
		$check_username = query("SELECT * FROM user_info WHERE username = '".addslashes($_POST['add_user']['username'])."' LIMIT 1");

		if(mysql_num_rows($check_username)) {
			construct_error("Sorry, you cannot have two users with the same username. <a href=\"javascript:history.back()\">Click here</a> to go back.");
			exit;
		}

		else if(!$_POST['add_user']['username']) {
			construct_error("Sorry, you cannot leave the username field empty. <a href=\"javascript:history.back()\">Click here</a> to go back.");

			exit;
		}

		else if(!$_POST['add_user']['password']) {
			construct_error("You must enter a password for the user. <a href=\"javascript:history.back()\">Click here</a> to go back.");

			exit;
		}

		else {
			// encrypt password...
			$_POST['add_user']['password'] = md5($_POST['add_user']['password']);

			// unset the usertitle var
			unset($usertitle);

			// lets get the usertitle...
			if(!$_POST['add_user']['usertitle_option']) {
				// we're going to get the usergroup's title...
				$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$_POST['add_user']['usergroupid']."' LIMIT 1",1);

				if($usergroupinfo['usertitle']) {
					$usertitle = $usergroupinfo['usertitle'];
				}

				// otherwise.. we must get the user title from the usertitles
				else {
					$firstUsertitle = query("SELECT * FROM usertitles ORDER BY minimumposts ASC LIMIT 1");

					if(mysql_num_rows($firstUsertitle)) {
						$firstUsertitle_arr = mysql_fetch_array($firstUsertitle);
						$usertitle = $firstUsertitle_arr['title'];
					} else {
						$usertitle = '';
					}
				}

				// if it's empty... set to "Registered Member"
				if(empty($usertitle)) {
					$usertitle = "Registered Member";
				}
			}

			// use the usertitle given.. HTML allowed
			else if($_POST['add_user']['usertitle_option'] == 1) {
				// use the title given...
				$usertitle = $_POST['add_user']['usertitle'];

				if(!$usertitle) {
					$usertitle = "Registered Member";
				}
			}

			// otherwise.. we're using the one provided
			// but trash HTML
			else {
				$usertitle = htmlspecialchars($_POST['add_user']['usertitle']);

				if(!$usertitle) {
					$usertitle = "Registered Member";
				}
			}

			// set counter
			$i = 0;

			// intialize the $query var
			$query = "INSERT INTO user_info (usertitle,";

			foreach($_POST['add_user'] as $option_key => $option_value) {
				if($option_key != "set_form" AND $option_key != "usertitle") {
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

			$query .= ") VALUES ('".addslashes($usertitle)."',";

			// reset counter...
			$i = 0;

			foreach($_POST['add_user'] as $option_key => $option_value) {
				if($option_key != "set_form" AND $option_key != "usertitle") {
					// look for comma
					if($i > 0) {
						$comma = ",";
					} else {
						$comma = "";
						$i++;
					}

					if($option_key != "signature" AND $option_key != "username_html_begin" AND $option_key != "username_html_end") {
						$option_value = htmlspecialchars($option_value);
					}

					$query .= $comma."'".addslashes($option_value)."'";
				}
			}

			$query .= ")";

			// update the DB
			query($query);

			$theUserid = mysql_insert_id();

			// update with hash
			query("UPDATE user_info SET useridHash = '".md5($theUserid)."' WHERE userid = '".$theUserid."'");

			// redirect to thankyou page...
			redirect("thankyou.php?message=Thank you for adding <em>".$_POST['add_user']['username']."</em>. You will now be redirected to ".$_POST['add_user']['username']."\'s user info page.&uri=user.php?do=editSTEVEid=".$theUserid);

			/*print("<br /><br />");

			print($query);

			print("<br /><br />");*/
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Users - Add User");

	construct_title("Add User");

	construct_table("options","add_user","user_submit",1);
	construct_header("Add User",2);

	construct_text(1,"Username","Enter here the username of the new user you are creating.","add_user","username","");

	construct_text(2,"Password","Enter here the password of the new user you are creating.","add_user","password","");

	construct_text(1,"E-mail Address","Enter here the e-mail address of the new user you are creating.","add_user","email","");

	construct_text(2,"Parent E-mail Address","Enter here the e-mail address of the parent of this user.","add_user","parent_email","");

	construct_text(1,"Username HTML (begin)","This HTML will display <i>before</i> this user's username everywhere on the message board. This will override any HTML for this user's usergroup. Leave blank to let the usergroup settings take effect.","add_user","username_html_begin","");

	construct_text(2,"Username HTML (end)","Same as above, except this will come <i>after</i> this user's username everywhere on the message board. Leave blank to let the usergroup settings take effect.","add_user","username_html_end","");

	construct_text(1,"User Title","Input here the user title in which will be shown in this user's profile, and below this user's username in posts.","add_user","usertitle","");

	construct_select_begin(2,"User Title Options","Select here the way in which you wish this user's user title to be formatted. If you select \"No\", this user's user title will be reset to the default for this user's respecting usergroup. Selecting \"Yes\" will use this user title, and will parse HTML, and selecting \"Yes, but don't parse HTML\" will display this user title, and will not format any HTML.","add_user","usertitle_option");

		$items = "No,Yes,Yes!@#$ but don't parse HTML";

		$option_select = split(",",$items);

		foreach($option_select as $option_key => $option_value) {
			if($option_key == 2) {
				$check_select = " selected=\"selected\"";

				// kill two birds with one stone...
				$option_value = str_replace("!@#$",",",$option_value);
			} 
			
			else {
				$check_select = "";
			}

			print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
		}
		
	construct_select_end(2);


	construct_select_begin(1,"Usergroup","Select the usergroup in which you wish for this user to belong to.","add_user","usergroupid");

		// get all usergroups
		$usergroup_select = query("SELECT * FROM usergroups ORDER BY name ASC");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			if($usergroup['usergroupid'] == $bboptions['usergroup_redirect']) {
				$selected_usergroup = " selected=\"selected\"";
			} else {
				$selected_usergroup = "";
			}
			
			print("<option value=\"".$usergroup['usergroupid']."\"".$selected_usergroup.">".$usergroup['name']."</option>\n");
		}

	construct_select_end(1);


	construct_input(2,"COPPA User","Select yes to make this user a COPPA user, or select no to make this user not a COPPA user.","add_user","is_coppa",0,2);

	construct_text(1,"Homepage","","add_user","homepage","");

	construct_text(2,"AOL Instant Messenger Handle","","add_user","aim","");

	construct_text(1,"MSN Handle","","add_user","msn","");

	construct_text(2,"Yahoo Messenger Handle","","add_user","yahoo","");

	construct_text(1,"ICQ Handle","","add_user","icq","");

	construct_textarea(2,"Signature","This is this user's signature. It will appear under every post they make.","add_user","signature","");


	construct_select_begin(1,"Birthday","","add_user","birthday",0,1);

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


	construct_text(2,"IP Address","","add_user","user_ip_address","");

	construct_text(1,"Post Count","","add_user","posts","0");

	construct_text(2,"Referrer","","add_user","referral_username");

	construct_text(1,"Referrals","","add_user","referrals");

	construct_text(2,"Warning Level","","add_user","warn");

	construct_text(1,"Default Font","","add_user","default_font");

	construct_text(2,"Default Color","","add_user","default_color");

	construct_text(1,"Default Size","","add_user","default_size","",1);

	
	
	// ##### TIME OPTIONS ##### \\

	construct_header("Time Options",2);

	construct_select_begin(2,"Default time zone offset","This is the default time zone offset for guests and new users. Do not take into daylight savings time, instead look at the below option.","add_user","date_timezone");

		$items = "(GMT -12:00)*(GMT -11:00)*(GMT -10:00)*(GMT -9:00)*(GMT -8:00)*(GMT -7:00)*(GMT -6:00)*(GMT -5:00)*(GMT -4:00)*(GMT -3:00)*(GMT -2:00)*(GMT -1:00)*(GMT) *(GMT +1:00)*(GMT +2:00)*(GMT +3:00)*(GMT +4:00)*(GMT +5:00)*(GMT +6:00)*(GMT +7:00)*(GMT +8:00)*(GMT +9:00)*(GMT +10:00)*(GMT +11:00)*(GMT +12:00)";

		$option_select = split("\*",$items);

		foreach($option_select as $option_key => $option_value) {
			$option_key -= 12;

			if($option_key == $bboptions['date_timezone']) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");		
		}

	construct_select_end(2);

	construct_input(1,"Enable DST?","Enabling DST will add one hour.","add_user","dst");

	construct_select_begin(2,"Default thread view age","This is the default thread view age for this user. This will cut off any threads older than this time period.","add_user","date_default_thread_age");

		$items = "Use Forum Default,Show threads from the last day,Show threads from the last two days,Show threads from the last week,Show threads from the last two weeks,Show threads from the last month,Show threads from the last 45 days,Show threads from the last two months,Show threads from the last 75 days,Show threads from the last 100 days,Show threads from the last six months,Show threads from the last year,Show all threads";

		// do default thread view age for the options...
		$option_select = split(",",$items);

		foreach($option_select as $option_key => $option_value) {
			if($option_value == "Use Forum Default") {
				$option_key = -1;
			}

			if($option_key == $bboptions['date_default_thread_age']) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
		}

	construct_select_end(2);

	construct_select_begin(1,"Default posts per page","This will be how many posts per page this user will see. What is currently selected is the forum default.","add_user","view_posts");

		// split the user settable posts per page...
		$option_select = split(",",$bboptions['user_set_max_posts']);

		// default option... and "selected"
		print("<option value=\"-1\" selected=\"selected\">Use Forum Default</option>\n");

		foreach($option_select as $option_key => $option_value) {
			print("<option value=\"".$option_value."\">".$option_value."</option>\n");
		}

	construct_select_end(1);


	construct_select_begin(2,"Join Date","","add_user","date_joined",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month1\">\n");
		construct_select_months();
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day1\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td></tr></table>\n");

	construct_select_end(2,1);


	construct_select_begin(1,"Last Visit","","add_user","lastvisit",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month2\">\n");
		construct_select_months();
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day2\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Hour<br />\n");

		// get hours
		$hours = date("h");
		print("<input type=\"text\" name=\"hour2\" size=\"2\" class=\"text\" value=\"".$hours."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Minute<br />\n");

		// get minutes
		$minutes = date("i");
		print("<input type=\"text\" name=\"minute2\" size=\"2\" class=\"text\" value=\"".$minutes."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(1,1);


	construct_select_begin(2,"Last Activity","","add_user","lastactivity",0,1);

		print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Month<br />\n");

		print("<select name=\"month3\">\n");
		construct_select_months();
		print("</select>\n\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Day<br />\n");

		// get current date.. 
		$current_date = date("d");

		print("<input type=\"text\" name=\"day3\" class=\"text\" value=\"".$current_date."\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Year<br />\n");

		// get full year...
		$full_year = date("Y");

		print("<input type=\"text\" name=\"year3\" size=\"2\" class=\"text\" value=\"".$full_year."\" style=\"padding: 1px; width: 30px;\" />\n");
		print("</td>\n");


		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Hour<br />\n");

		// get hours
		$hours = date("h");
		print("<input type=\"text\" name=\"hour3\" size=\"2\" class=\"text\" value=\"".$hours."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td>\n");

		print("<td style=\"text-align: left; font-size: 8pt;\">\n");
		print("Minute<br />\n");

		// get minutes
		$minutes = date("i");
		print("<input type=\"text\" name=\"minute3\" size=\"2\" class=\"text\" value=\"".$minutes."\" style=\"padding: 1px; width: 30px;\" />\n");

		print("</td></tr></table>\n");

	construct_select_end(2,1);


	construct_select_begin(1,"Last Post","","add_user","lastpost",1,1);

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

	construct_text(1,"Biography","","add_user","biography");
	construct_text(2,"Location","","add_user","locationUser");
	construct_text(1,"Interests","","add_user","interests");
	construct_text(2,"Occupation","","add_user","occupation","",1);


	// ##### FORUM OPTIONS ##### \\

	construct_header("Forum Options",2);

	construct_input(1,"Invisible Mode","This will hide this user to everyone but forum staff.","add_user","invisible",0,2);

	construct_input(2,"Receive Admin Emails","Selecting yes here will allow this user to receive any emails that the administrator sends to this user.","add_user","admin_send_email",0,1);

	construct_input(1,"Allow Users to Email this User","Selecting yes here will allow this user to be emailed using the forum\'s secure email form from other users.","add_user","receive_emails",0,1);

	construct_input(2,"Receive Personal Messages","Selecting yes here will allow this user to receive personal messages. Whether or not this user can <i>send</i> personal messages is not dependant on this option.","add_user","use_pm",0,1);

	construct_input(1,"Recieve PM e-mail notification","Enabling this will send this user an email everytime a personal message is sent to this user.","add_user","send_email_pm",0,2);

	construct_input(2,"Receive Popup PM Notification","Enabling this will give this user a popup window on the message board if a new PM has been received.","add_user","popup_pm",0,2);

	construct_input(1,"Display Signatures","Disabling this will disallow this user to view anyone's signature.","add_user","view_signature",0,1);

	construct_input(2,"Display Avatars","Disabling this will disallow this user to view anyone's avatar.","add_user","view_avatar",0,1);

	construct_input(1,"Display Attachments","Disabling this will disallow this user to view any attachments.","add_user","view_attachment",0,1);

	construct_input(2,"Allow HTML","Enabling this will allow this user to override the forum default, and use HTML anywhere on the forum (this includes signatures!). It is <b>strongly</b> recommended that you leave this to disabled.","add_user","allow_html",0,2);

	construct_input(1,"Enable Toolbar","If this is disabled, you will not be able to make use of the toolbar when posting.","add_user","toolbar",0,1);

	construct_input(2,"Ban Signature","Selecting yes will disable the use of this user's signature.","add_user","ban_sig",0,2);

	construct_input(1,"Automatic Thread Subscription","Setting this to yes will automatically subscribe this user to each thread that this user makes, or replies in.","add_user","auto_threadsubscription",0,2);


	construct_select_begin(2,"Post Display Order","This determines the option in which posts are displayed inside a thread. The default is \"Oldest First.\"","add_user","display_order");

		print("<option value=\"0\" selected=\"selected\">Oldest First</option>\n");
		print("<option value=\"1\">Newest First</option>\n");

	construct_select_end(2);


	construct_select_begin(1,"Default Style for this User","Select a default style for this user.","add_user","style_id",1);
			// get styles...
			$checkStyles = query("SELECT * FROM styles ORDER BY display_order, title");
			
			print("<option value=\"0\" selected=\"selected\">Use Forum Default</option>\n");

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				print("<option value=\"".$styleinfo['styleid']."\">".$styleinfo['title']."</option>\n");
			}

	construct_select_end(1,1);

	construct_footer(2,"user_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}

else if($_GET['do'] == "search") {
	// translate to unix timestamp with mktime
	/*$search_user['birthday'] = mktime(0,0,0,$month,$day,$year);
	$search_user['date_joined'] = mktime(0,0,0,$month1,$day1,$year1);
	$search_user['lastvisit'] = mktime($hour2,$minute2,0,$month2,$day2,$year2);
	$search_user['lastactivity'] = mktime($hour3,$minute3,0,$month3,$day3,$year3);
	$search_user['lastpost'] = mktime($hour4,$minute4,0,$month4,$day4,$year4);*/

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

		//print($total_query); exit;

		if(!$search_by) {
			construct_error("Sorry, you must enter something in a field. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}
		
		// run the query
		$userinfo_query = query($total_query);

		// uh oh! no users found!
		if(!mysql_num_rows($userinfo_query)) {
			construct_error("Sorry, no users were found with that criteria. Please <a href=\"javascript:history.back()\">click here</a> to go back, or use the back button on your browser.");
			exit;
		}

		// if only one match is found.. just redirect right to that user's info...
		else if(mysql_num_rows($userinfo_query)) {
			// just get the id...
			$user_id = mysql_fetch_array($userinfo_query);
			redirect("user.php?do=edit&id=".$user_id['userid']);
		}

		else {
			// do header
			admin_header("wtcBB Admin Panel - Users - Select User to Edit");

			construct_title("Select a User to Edit");

			construct_table("options","userinfo_form","userinfo_submit");
			construct_header("Search Results: ".mysql_num_rows($userinfo_query)." Users Found",6);

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

			while($userinfo = mysql_fetch_array($userinfo_query)) {
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

	/*construct_header("Time Options",2);

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

	construct_select_end(1,1);*/


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
else if($_GET['do'] == "edit") {
	// get userinfo...
	$userinfo_query = query("SELECT * FROM user_info WHERE userid = '".addslashes($_GET['id'])."' LIMIT 1");
	$userinfo = mysql_fetch_array($userinfo_query);

	if(!mysql_num_rows($userinfo_query)) {
		construct_error("Sorry, the user you are trying to edit does not exist.");
		exit;
	}
	
	// edit.. or delete?
	if($_GET['id'] AND !$_GET['action']) {
		// get birthday...
		if($_POST['month'] AND $_POST['day']) {
			if($_POST['year']) {
				$_POST['year'] = "0000";
			}

			// just make sure nothing is above 31... no biggy
			if($_POST['day'] > 31) {
				$_POST['day'] = 31;
			}

			$_POST['edit_user']['birthday'] = $_POST['month']."-".$_POST['day']."-".$_POST['year'];
		}

		else {
			$_POST['edit_user']['birthday'] = "";
		}

		// fix date joined bug....
		$joined_hours = date("H",$userinfo['date_joined']);
		$joined_minutes = date("i",$userinfo['date_joined']);
		$joined_seconds = date("s",$userinfo['date_joined']);

		// translate to unix timestamp with mktime
		$_POST['edit_user']['date_joined'] = mktime($joined_hours,$joined_minutes,$joined_seconds,$_POST['month1'],$_POST['day1'],$_POST['year1']);
		$_POST['edit_user']['lastvisit'] = mktime($_POST['hour2'],$_POST['minute2'],0,$_POST['month2'],$_POST['day2'],$_POST['year2']);
		$_POST['edit_user']['lastactivity'] = mktime($_POST['hour3'],$_POST['minute3'],0,$_POST['month3'],$_POST['day3'],$_POST['year3']);
		$_POST['edit_user']['lastpost'] = mktime($_POST['hour4'],$_POST['minute4'],0,$_POST['month4'],$_POST['day4'],$_POST['year4']);

		// time to update the db
		if($_POST['edit_user']['set_form']) {
			// wait.. make sure we aren't editing an undeletable user!
			if(isUndeletable($userinfo['userid'])) {
				construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file.");
				exit;
			}

			// no duplication of username
			if(mysql_num_rows(query("SELECT * FROM user_info WHERE username = '".addslashes($_POST['edit_user']['username'])."' AND userid != '".$userinfo['userid']."' LIMIT 1"))) {
				construct_error("You cannot have two users with the same username. <a href=\"javascript:history.back();\">Go back.</a>");
				exit;
			}

			// no duplication of email
			if(mysql_num_rows(query("SELECT * FROM user_info WHERE email = '".$_POST['edit_user']['email']."' AND userid != '".$userinfo['userid']."' LIMIT 1"))) {
				construct_error("You cannot have two users with the same email address. <a href=\"javascript:history.back();\">Go back.</a>");
				exit;
			}

			// unset the usertitle var
			unset($usertitle);

			// lets get the usertitle...
			if(!$_POST['edit_user']['usertitle_option']) {
				// we're going to get the usergroup's title...
				$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$_POST['edit_user']['usergroupid']."' LIMIT 1",1);

				if($usergroupinfo['usertitle']) {
					$usertitle = $usergroupinfo['usertitle'];
				}

				// otherwise.. we must get the user title from the usertitles
				else {
					if(is_array($usertitles)) {
						// loop through user titles
						foreach($usertitles as $counter => $arr) {
							// make sure we have the next counter
							// if not.. simply give the usertitle the current.. there's nothin left!
							if(!is_array($usertitles[($counter + 1)])) {
								$usertitle = $arr['title'];
								break;
							}

							// now we compare...
							if($userinfo['posts'] >= $arr['minimumposts'] AND $userinfo['posts'] < $usertitles[($counter + 1)]['minimumposts']) {
								// yay!
								$usertitle = $arr['title'];
								break;
							}
						}
					}
				}

				// if it's empty... set to "Registered Member"
				if(!$usertitle) {
					$usertitle = "Registered Member";
				}
			}

			// use the usertitle given.. HTML allowed
			else if($_POST['edit_user']['usertitle_option'] == 1) {
				// use the title given...
				$usertitle = $_POST['edit_user']['usertitle'];

				if(!$usertitle) {
					$usertitle = "Registered Member";
				}
			}

			// otherwise.. we're using the over provided
			// but trash HTML
			else {
				$usertitle = htmlspecialchars($_POST['edit_user']['usertitle']);

				if(!$usertitle) {
					$usertitle = "Registered Member";
				}
			}

			// intialize beginning of query
			$query = "UPDATE user_info SET";

			// intialize counter
			$x = 1;

			foreach($_POST['edit_user'] as $option_key => $option_value) {
				if($option_key != "set_form" AND $option_key != "usertitle") {
					if($_POST['edit_user']['password'] AND $option_key == "password") {
						// encrypt password
						$option_value = md5($option_value);

						// changing password, so get rid of vB salt
						query("UPDATE user_info SET vBsalt = null WHERE userid = '".$userinfo['userid']."'");

						if($x == 1) {
							$comma = "";
						} else {
							$comma = ",";
						}

						$query .= " ".$comma." ".$option_key." = '".htmlspecialchars(addslashes($option_value))."'";
					}

					if($option_key != "password") {
						if($x == 1) {
							$comma = "";
						} else {
							$comma = ",";
						}

						if($option_key != "signature" AND $option_key != "username_html_begin" AND $option_key != "username_html_end") {
							$option_value = htmlspecialchars($option_value);
						}

						$query .= " ".$comma." ".$option_key." = '".addslashes($option_value)."'";
					}

					$x++;
				}
			}

			$query .= " , usertitle = '".addslashes($usertitle)."' WHERE userid = '".$userinfo['userid']."'";

			//print($query);

			// run query
			query($query);

			$uri = "user.php?do=editSTEVEid=".$userinfo['userid'];

			redirect("thankyou.php?message=Thank you for editing <i>".$_POST['edit_user']['username']."</i>. You will now be redirected back to ".$_POST['edit_user']['username']."\'s user info page.&uri=".$uri);
		}

		// do header
		admin_header("wtcBB Admin Panel - Users - Edit User \"".$userinfo['username']."\"");

		construct_title("Edit User <em>".$userinfo['username']."</em> <span class=\"small\">(id: ".$userinfo['userid'].")</span>");

		print('<div style="width: 90%; margin-left: auto; margin-right: auto;">');

		// show a few quick links...
		if($userinfo['usergroupid'] == 3 OR $userinfo['is_coppa'] == 1) {
			print('<p style="margin-bottom: 0; margin-top: 0;"><a href="user.php?do=edit&amp;id='.$userinfo['userid'].'&amp;action=resend">Resend Validation Email</a></p>');
		}

		print('<p style="margin-bottom: 0; margin-top: 0;"><a href="user.php?do=edit&amp;id='.$userinfo['userid'].'&amp;action=delete">Delete User</a></p>');
		print('<p style="margin-bottom: 0; margin-top: 0;"><a href="./../profile.php?u='.$userinfo['userid'].'">View Public Profile</a></p>');
		print('</div>');

		construct_table("options","edit_user","user_submit",1);
		construct_header("General Information",2);

		construct_text(1,"Username","Enter here the username of the new user you are creating.","edit_user","username",$userinfo['username']);

		construct_text(2,"Password","Enter here the password of the new user you are creating.","edit_user","password","");

		construct_text(1,"E-mail Address","Enter here the e-mail address of the new user you are creating.","edit_user","email",$userinfo['email']);

		construct_text(2,"Parent E-mail Address","Enter here the e-mail address of the parent of this user.","edit_user","parent_email",$userinfo['parent_email']);

		construct_text(1,"Username HTML (begin)","This HTML will display <i>before</i> this user's username everywhere on the message board. This will override any HTML for this user's usergroup. Leave blank to let the usergroup settings take effect.","edit_user","username_html_begin",$userinfo['username_html_begin']);

		construct_text(2,"Username HTML (end)","Same as above, except this will come <i>after</i> this user's username everywhere on the message board. Leave blank to let the usergroup settings take effect.","edit_user","username_html_end",$userinfo['username_html_end']);

		construct_text(1,"User Title","Input here the user title in which will be shown in this user's profile, and below this user's username in posts.","edit_user","usertitle",htmlspecialchars(stripslashes($userinfo['usertitle'])));

		construct_select_begin(2,"User Title Options","Select here the way in which you wish this user's user title to be formatted. If you select \"No\", this user's user title will be reset to the default for this user's respecting usergroup. Selecting \"Yes\" will use this user title, and will parse HTML, and selecting \"Yes, but don't parse HTML\" will display this user title, and will not format any HTML.","edit_user","usertitle_option");

			$items = "No,Yes,Yes!@#$ but don't parse HTML";

			$option_select = split(",",$items);

			foreach($option_select as $option_key => $option_value) {

				// just touch up the don't parse HTML selection..
				$option_value = str_replace("!@#$",",",$option_value);

				if($option_key == $userinfo['usertitle_option']) {
					$check_select = " selected=\"selected\"";					
				} 
				
				else {
					$check_select = "";
				}

				print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
			}
			
		construct_select_end(2);


		construct_select_begin(1,"Usergroup","Select the usergroup in which you wish for this user to belong to.","edit_user","usergroupid");

			// get all usergroups
			$usergroup_select = query("SELECT * FROM usergroups ORDER BY name ASC");

			// loop
			while($usergroup = mysql_fetch_array($usergroup_select)) {
				if($usergroup['usergroupid'] == $userinfo['usergroupid']) {
					$selected_usergroup = " selected=\"selected\"";
				} else {
					$selected_usergroup = "";
				}
				
				print("<option value=\"".$usergroup['usergroupid']."\"".$selected_usergroup.">".$usergroup['name']."</option>\n");
			}

		construct_select_end(1);


		construct_input(2,"COPPA User","Select yes to make this user a COPPA user, or select no to make this user not a COPPA user.","edit_user","is_coppa",0,0,$userinfo);

		construct_text(1,"Homepage","","edit_user","homepage",$userinfo['homepage']);

		construct_text(2,"AOL Instant Messenger Handle","","edit_user","aim",$userinfo['aim']);

		construct_text(1,"MSN Handle","","edit_user","msn",$userinfo['msn']);

		construct_text(2,"Yahoo Messenger Handle","","edit_user","yahoo",$userinfo['yahoo']);

		construct_text(1,"ICQ Handle","","edit_user","icq",$userinfo['icq']);

		construct_textarea(2,"Signature","This is this user's signature. It will appear under every post they make.","edit_user","signature",htmlspecialchars($userinfo['signature']));


		construct_select_begin(1,"Birthday","","edit_user","birthday",0,1);
			
			// separate the birthday.. this is unique..
			$birthday = explode("-",$userinfo['birthday']);

			if($birthday[2] == "0000") {
				$birthday[2] = "";
			}

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month\">\n");

			if(empty($userinfo['birthday'])) {
				construct_select_months(0,1,0,1);
			}

			else {
				construct_select_months(0,1,0,0,"birthday");
			}

			print("</select>\n\n");

			print("</td>\n");


			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");
			print("<input type=\"text\" name=\"day\" class=\"text\" value=\"".$birthday[1]."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");


			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");
			print("<input type=\"text\" name=\"year\" size=\"2\" class=\"text\" value=\"".$birthday[2]."\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n\n");

		construct_select_end(1,1);


		construct_text(2,"IP Address","","edit_user","user_ip_address",$userinfo['user_ip_address']);

		construct_text(1,"Post Count","","edit_user","posts",$userinfo['posts']);

		construct_text(2,"Referrer","","edit_user","referral_username",$userinfo['referral_username']);

		construct_text(1,"Referrals","","edit_user","referrals",$userinfo['referrals']);

		// get prefix
		$check = substr($userinfo['avatar_url'],0,7);

		if($check == "http://") {
			$prefix = "";
		} else {
			$prefix = "../";
		}

		?>
		<tr>
			<td class="desc2">
				<b>Avatar</b> <br /> <span class="small"></span>
			</td>

			<td class="input2">
				<?php 
				if($userinfo['avatar_url'] != "none") { 
					print("<img src=\"".$prefix.$userinfo['avatar_url']."\" alt=\"".$userinfo['avatar_url']."\" style=\"border: none;\" />&nbsp;&nbsp;&nbsp; \n");
				}
				?>
				<button type="button" onClick="location.href='user.php?do=change_avatar&userid=<?php print($userinfo['userid']); ?>';" <?php print($submitbg); ?>>Change Avatar</button>
			</td>
		</tr>
		<?php

		construct_text(1,"Warning Level","","edit_user","warn",$userinfo['warn']);

		construct_text(2,"Default Font","","edit_user","default_font",$userinfo['default_font']);

		construct_text(1,"Default Color","","edit_user","default_color",$userinfo['default_color']);

		construct_text(2,"Default Size","","edit_user","default_size",$userinfo['default_size'],1);

		
		
		// ##### TIME OPTIONS ##### \\

		construct_header("Time Options",2);

		construct_select_begin(2,"Default time zone offset","This is the default time zone offset for guests and new users. Do not take into daylight savings time, instead look at the below option.","edit_user","date_timezone");

			$items = "(GMT -12:00)*(GMT -11:00)*(GMT -10:00)*(GMT -9:00)*(GMT -8:00)*(GMT -7:00)*(GMT -6:00)*(GMT -5:00)*(GMT -4:00)*(GMT -3:00)*(GMT -2:00)*(GMT -1:00)*(GMT) *(GMT +1:00)*(GMT +2:00)*(GMT +3:00)*(GMT +4:00)*(GMT +5:00)*(GMT +6:00)*(GMT +7:00)*(GMT +8:00)*(GMT +9:00)*(GMT +10:00)*(GMT +11:00)*(GMT +12:00)";

			$option_select = split("\*",$items);

			foreach($option_select as $option_key => $option_value) {
				$option_key -= 12;

				if($option_key == $userinfo['date_timezone']) {
					$check_select = " selected=\"selected\"";
				} else {
					$check_select = "";
				}

				print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");		
			}

		construct_select_end(2);

		construct_input(1,"Enable DST?","Enabling DST will add one hour.","edit_user","dst",0,0,$userinfo);

		construct_select_begin(2,"Default thread view age","This is the default thread view age for this user. This will cut off any threads older than this time period.","edit_user","date_default_thread_age");

			$items = "Use Forum Default,Show threads from the last day,Show threads from the last two days,Show threads from the last week,Show threads from the last two weeks,Show threads from the last month,Show threads from the last 45 days,Show threads from the last two months,Show threads from the last 75 days,Show threads from the last 100 days,Show threads from the last six months,Show threads from the last year,Show all threads";

			// do default thread view age for the options...
			$option_select = split(",",$items);

			foreach($option_select as $option_key => $option_value) {
				if($option_value == "Use Forum Default") {
					$option_key = -1;
				}

				if($option_key == $userinfo['date_default_thread_age']) {
					$check_select = " selected=\"selected\"";
				} else {
					$check_select = "";
				}

				print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
			}

		construct_select_end(2);

		construct_select_begin(1,"Default posts per page","This will be how many posts per page this user will see. What is currently selected is the forum default.","edit_user","view_posts");

			// split the user settable posts per page...
			$option_select = split(",",$bboptions['user_set_max_posts']);

			// do default and "selected"
			if($userinfo['view_posts'] == -1) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"-1\"".$check_select.">Use Forum Default</option>\n");

			foreach($option_select as $option_key => $option_value) {
				if($option_value == $userinfo['view_posts']) {
					$check_select = " selected=\"selected\"";
				} else {
					$check_select = "";
				}

				print("<option value=\"".$option_value."\"".$check_select.">".$option_value."</option>\n");
			}

		construct_select_end(1);


		construct_select_begin(2,"Join Date","","edit_user","date_joined",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month1\">\n");
			construct_select_months(0,1,0,0,"date_joined");
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");

			// get day
			$userinfo_date = date("d",$userinfo['date_joined']);

			print("<input type=\"text\" name=\"day1\" class=\"text\" value=\"".$userinfo_date."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");

			// get full year...
			$userinfo_year = date("Y",$userinfo['date_joined']);

			print("<input type=\"text\" name=\"year1\" size=\"2\" class=\"text\" value=\"".$userinfo_year."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td></tr></table>\n");

		construct_select_end(2,1);


		construct_select_begin(1,"Last Visit","","edit_user","lastvisit",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month2\">\n");
			construct_select_months(0,1,0,0,"lastvisit");
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");

			// get day
			$userinfo_date = date("d",$userinfo['lastvisit']);

			print("<input type=\"text\" name=\"day2\" class=\"text\" value=\"".$userinfo_date."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");

			// get full year...
			$userinfo_year = date("Y",$userinfo['lastvisit']);

			print("<input type=\"text\" name=\"year2\" size=\"2\" class=\"text\" value=\"".$userinfo_year."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");


			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Hour<br />\n");

			// get hours
			$userinfo_hours = date("H",$userinfo['lastvisit']);
			print("<input type=\"text\" name=\"hour2\" size=\"2\" class=\"text\" value=\"".$userinfo_hours."\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Minute<br />\n");

			// get minutes
			$userinfo_minutes = date("i",$userinfo['lastvisit']);
			print("<input type=\"text\" name=\"minute2\" size=\"2\" class=\"text\" value=\"".$userinfo_minutes."\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(1,1);


		construct_select_begin(2,"Last Activity","","edit_user","lastactivity",0,1);

			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");

			print("<select name=\"month3\">\n");
			construct_select_months(0,1,0,0,"lastactivity");
			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");

			// get day
			$userinfo_date = date("d",$userinfo['lastactivity']);

			print("<input type=\"text\" name=\"day3\" class=\"text\" value=\"".$userinfo_date."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");

			// get full year...
			$userinfo_year = date("Y",$userinfo['lastactivity']);

			print("<input type=\"text\" name=\"year3\" size=\"2\" class=\"text\" value=\"".$userinfo_year."\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");


			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Hour<br />\n");

			// get hours
			$userinfo_hours = date("H",$userinfo['lastactivity']);
			print("<input type=\"text\" name=\"hour3\" size=\"2\" class=\"text\" value=\"".$userinfo_hours."\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Minute<br />\n");

			// get minutes
			$userinfo_minutes = date("i",$userinfo['lastactivity']);
			print("<input type=\"text\" name=\"minute3\" size=\"2\" class=\"text\" value=\"".$userinfo_minutes."\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(2,1);


		construct_select_begin(1,"Last Post","","edit_user","lastpost",1,1);

			// if the user has not posted... unset the following variables.. to avoid weird dates.. otherwise fill them with new ones...
			if($userinfo['lastpost'] == -1) {
				unset($userinfo_date);
				unset($userinfo_year);
				unset($userinfo_hours);
				unset($userinfo_minutes);
			}

			else {
				// get day
				$userinfo_date = date("d",$userinfo['lastpost']);

				// get year
				$userinfo_year = date("Y",$userinfo['lastpost']);

				// get hours
				$userinfo_hours = date("H",$userinfo['lastpost']);

				// get minutes
				$userinfo_minutes = date("i",$userinfo['lastpost']);
			}
			
			print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n<tr>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Month<br />\n");
			print("<select name=\"month4\">\n");

			if($userinfo['lastpost'] == -1) {
				construct_select_months(0,1,0,1);
			}

			else {
				construct_select_months(0,1,0,0,"lastpost");
			}

			print("</select>\n\n");

			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Day<br />\n");
			print("<input type=\"text\" name=\"day4\" value=\"".$userinfo_date."\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Year<br />\n");
			print("<input type=\"text\" name=\"year4\" value=\"".$userinfo_year."\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");
			
			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Hour<br />\n");
			print("<input type=\"text\" name=\"hour4\" value=\"".$userinfo_hours."\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");
			print("</td>\n");

			print("<td style=\"text-align: left; font-size: 8pt;\">\n");
			print("Minute<br />\n");
			print("<input type=\"text\" name=\"minute4\" value=\"".$userinfo_minutes."\" size=\"2\" class=\"text\" style=\"padding: 1px; width: 30px;\" />\n");

			print("</td></tr></table>\n");

		construct_select_end(1,1);


		// ##### PROFILE FIELD OPTIONS ##### \\

		construct_header("Profile Field Options",2);

		construct_text(1,"Biography","","edit_user","biography",$userinfo['biography']);
		construct_text(2,"Location","","edit_user","locationUser",$userinfo['locationUser']);
		construct_text(1,"Interests","","edit_user","interests",$userinfo['interests']);
		construct_text(2,"Occupation","","edit_user","occupation",$userinfo['occupation'],1);


		// ##### FORUM OPTIONS ##### \\

		construct_header("Forum Options",2);

		construct_input(1,"Invisible Mode","This will hide this user to everyone but forum staff.","edit_user","invisible",0,0,$userinfo);

		construct_input(2,"Receive Admin Emails","Selecting yes here will allow this user to receive any emails that the administrator sends to this user.","edit_user","admin_send_email",0,0,$userinfo);

		construct_input(1,"Allow Users to Email this User","Selecting yes here will allow this user to be emailed using the forum\'s secure email form from other users.","edit_user","receive_emails",0,0,$userinfo);

		construct_input(2,"Receive Personal Messages","Selecting yes here will allow this user to receive personal messages. Whether or not this user can <i>send</i> personal messages is not dependant on this option.","edit_user","use_pm",0,0,$userinfo);

		construct_input(1,"Recieve PM e-mail notification","Enabling this will send this user an email everytime a personal message is sent to this user.","edit_user","send_email_pm",0,0,$userinfo);

		construct_input(2,"Receive Popup PM Notification","Enabling this will give this user a popup window on the message board if a new PM has been received.","edit_user","popup_pm",0,0,$userinfo);

		construct_input(1,"Display Signatures","Disabling this will disallow this user to view anyone's signature.","edit_user","view_signature",0,0,$userinfo);

		construct_input(2,"Display Avatars","Disabling this will disallow this user to view anyone's avatar.","edit_user","view_avatar",0,0,$userinfo);

		construct_input(1,"Display Attachments","Disabling this will disallow this user to view any attachments.","edit_user","view_attachment",0,0,$userinfo);

		construct_input(2,"Allow HTML","Enabling this will allow this user to override the forum default, and use HTML anywhere on the forum (this includes signatures!). It is <b>strongly</b> recommended that you leave this to disabled.","edit_user","allow_html",0,0,$userinfo);

		construct_input(1,"Enable Toolbar","If this is disabled, you will not be able to make use of the toolbar when posting.","edit_user","toolbar",0,0,$userinfo);

		construct_input(2,"Ban Signature","Selecting yes will disable the use of this user's signature.","edit_user","ban_sig",0,0,$userinfo);

		construct_input(1,"Automatic Thread Subscription","Setting this to yes will automatically subscribe this user to each thread that this user makes, or replies in.","edit_user","auto_threadsubscription",0,0,$userinfo);


		construct_select_begin(2,"Post Display Order","This determines the option in which posts are displayed inside a thread. The default is \"Oldest First.\"","edit_user","display_order");

			if($userinfo['display_order'] == "ASC") {
				$isSelected = ' selected="selected"';
				$isSelected2 = '';
			} else {
				$isSelected2 = ' selected="selected"';
				$isSelected = '';
			}

			print("<option value=\"ASC\"".$isSelected.">Oldest First</option>\n");
			print("<option value=\"DESC\"".$isSelected2.">Newest First</option>\n");

		construct_select_end(2);


		construct_select_begin(1,"Default Style for this User","Select a default style for this user.","edit_user","style_id",1);
			// get styles...
			$checkStyles = query("SELECT * FROM styles ORDER BY display_order, title");
			
			if($userinfo['style_id'] == 0) {
				print("<option value=\"0\" selected=\"selected\">Use Forum Default</option>\n");
			} else {
				print("<option value=\"0\">Use Forum Default</option>\n");
			}

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				// get selected
				if($userinfo['style_id'] == $styleinfo['styleid']) {
					$selected = " selected=\"selected\"";
				} else {
					$selected = "";
				}

				print("<option value=\"".$styleinfo['styleid']."\"".$selected.">".$styleinfo['title']."</option>\n");
			}

		construct_select_end(1,1);


		construct_footer(2,"user_submit");
		construct_table_END(1);

		// do footer
		admin_footer();
	}

	// delete... or resend validation email?
	else {
		if($_GET['action'] == "delete") {
			// wait.. make sure we aren't deleting an undeletable user!
			if(isUndeletable($userinfo['userid'])) {
				construct_error("This user is protected by the \$uneditable_user variable in the <strong>config.php</strong> file.");
				exit;
			}

			// make sure form is set..
			if($_POST['confirm']['set_form']) {
				// yes...
				if($_POST['confirm']['yes_no']) {
					// what if moderator? find all moderator entries with corresponding ID and delete... no if's, and's, or's, or but's about it!
					query("DELETE FROM moderators WHERE userid = '".$_GET['id']."' LIMIT 1");
					
					// start delete user
					query("DELETE FROM user_info WHERE userid = '".$_GET['id']."' LIMIT 1");

					redirect("thankyou.php?message=You have successfully deleted <em>".$userinfo['username']."</em>. You will now be redirected back to the user search page.&uri=user.php?do=search");
				}

				// no...
				else {
					redirect("user.php?do=edit&id=".$userinfo['userid']);
				}
			}
			
			// do a confirm page...
			construct_confirm("Are you sure you want to delete ".$userinfo['username']."? You cannot undo this.");
		}

		else {
			// resend validation email...
			// get hash
			$useridHash = $userinfo['useridHash'];

			// if coppa send to parent...
			if($userinfo['is_coppa'] == 1) {
				eval("\$message = \"".getTemplate("mail_coppaActivation")."\";");
				mail($userinfo['parent_email'],"wtcBB Mailer - Parent Consent Email",$message,"From: ".$bboptions['details_contact']);
			}

			// normal activation
			else {
				eval("\$message = \"".getTemplate("mail_activation")."\";");
				mail($userinfo['email'],"wtcBB Mailer - Activation Email",$message,"From: ".$bboptions['details_contact']);
			}

			redirect("thankyou.php?message=You have successfully resent a validation email to ".$userinfo['username'].". You will now be redirected back to the user search page.&uri=user.php?do=search");
		}
	}
}


// ##### PRUNE USERS ##### \\

else if($_GET['do'] == "prune") {
	if($_POST['move_prune']['set_form']) {		
		// translate to UNIX timestamp
		$_POST['move_prune']['date_joined'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);

		// intiate counter
		$x = 1;

		// let's loop through the move_prune to get the fields...
		foreach($_POST['move_prune'] as $option_key => $option_value) {
			// only form query if it isn't set_form OR orderby
			if($option_key != "set_form" AND $option_key != "orderby") {
				// make sure we are dealing with a non-empty variable...
				if($x != 1) {
					$beginning = " AND ";
				} else {
					$beginning = " ";
				}

				// if it's the username use LIKE
				if($option_key == "username") {
					$search_by .= $beginning.$option_key." LIKE '%".addslashes($option_value)."%'";
					$x++;
				}

				else if($option_key == "usergroupid") {
					if($option_value != "all") {
						$search_by .= $beginning.$option_key." = '".$option_value."'";
						$x++;
					}
				}

				else if($option_key == "date_joined") {
					if($option_value != -1) {
						$search_by .= $beginning.$option_key." < '".$option_value."'";
						$x++;
					}
				}

				else if($option_key == "posts") {
					if($option_value != 0) {
						$search_by .= $beginning.$option_key." < '".$option_value."'";
						$x++;
					}
				}
			}
		}

		// might need to do DESCENDING..
		if($_POST['move_prune']['orderby'] == "posts") {
			$_POST['move_prune']['orderby'] = "posts DESC";
		}

		// uh oh!
		if(!$search_by) {
			construct_error("You must enter criteria for the search to work.");
			exit;
		}

		$total_query = "SELECT * FROM user_info WHERE".$search_by." ORDER BY ".$_POST['move_prune']['orderby'];

		//print($total_query."<br /><br />");

		// run query
		$userinfo_select = query($total_query);

		// what if no users found?
		if(!mysql_num_rows($userinfo_select)) {
			construct_error("Sorry no users could be found matching your criteria. Please <a href=\"user.php?do=prune\">click here</a> to go back.");
			exit;
		}

		// do header
		admin_header("wtcBB Admin Panel - Users - Prune/Move Users");

		construct_title("Search Results: ".mysql_num_rows($userinfo_select)." Users Found");

		construct_table("options","results","userinfo_submit",1);
		construct_header("Search Results: ".mysql_num_rows($userinfo_select)." Users Found",7);

		print("\n\n\t<tr>\n");

			print("\t\t<td class=\"cat\">\n");
				print("<input type=\"checkbox\" name=\"check_all\" id=\"check_all\" value=\"checking_all\" title=\"Check All\" onclick=\"checkAll(this.form);\" />\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"cat2\">\n");
				print("\t\t\tUser\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"cat2\">\n");
				print("\t\t\tUsergroup\n");
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

		while($userinfo = mysql_fetch_array($userinfo_select)) {
			print("\t<tr>\n");
				// find usergroup
				$usergroupinfo = query("SELECT * FROM usergroups WHERE usergroupid = '".$userinfo['usergroupid']."' LIMIT 1",1);

				// if guest, continue
				if($usergroupinfo['usergroupid'] == 1) {
					continue;
				}

				// moderator?
				$moderators = query("SELECT * FROM moderators WHERE userid = '".$userinfo['userid']."' LIMIT 1");

				if(mysql_num_rows($moderators)) {
					$is_moderator = true;
				} else {
					$is_moderator = false;
				}

				print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; width: 5%; padding: 5px;\">\n");
					// differentiate depening upon user background...
					if($usergroupinfo['is_admin'] OR $usergroupinfo['is_super_moderator'] OR $is_moderator OR $_COOKIE['wtcBB_adminUsername'] == $userinfo['username'] OR isUndeletable($userinfo['userid'])) {
						print("<button type=\"button\" onclick=\"pruneAlert();\" style=\"margin-left: 2px;\" ".$submitbg.">*</button>\n");
					} else {
						print("<input type=\"checkbox\" name=\"results[".$userinfo['username']."]\" id=\"num\" value=\"1\" />\n");
					}
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; border-left: none; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a> <span class=\"small\">(id: ".$userinfo['userid'].")</span>\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$usergroupinfo['name']." <span class=\"small\">(id: ".$userinfo['usergroupid'].")</span>\n");
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
					print("\t\t\t<button type=\"button\" onClick=\"location.href='user.php?do=edit&id=".$userinfo['userid']."'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button> <button type=\"button\" onClick=\"location.href='user.php?do=edit&id=".$userinfo['userid']."&action=delete'\" ".$submitbg." style=\"margin: 0px;\">Delete</button>\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"7\">");
		print("<label for=\"deletion\"><input type=\"radio\" name=\"results[choice]\" value=\"delete\" id=\"deletion\" /> Delete</label>");
		print("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
		print("<label for=\"moving\"><input type=\"radio\" name=\"results[choice]\" value=\"move\" id=\"moving\" /> Move To:</label>&nbsp;&nbsp;");
			print("<select name=\"results[move_to]\">");
				// get all usergroups
				$usergroup_select = query("SELECT * FROM usergroups ORDER BY name ASC");

				// loop
				while($usergroup = mysql_fetch_array($usergroup_select)) {
					if($usergroup['usergroupid'] == $bboptions['usergroup_redirect']) {
						$selected_usergroup = " selected=\"selected\"";
					} else {
						$selected_usergroup = "";
					}

					print("<option value=\"".$usergroup['usergroupid']."\"".$selected_usergroup.">".$usergroup['name']."</option>\n");
				}
			print("</select>");	
		print("<input type=\"hidden\" name=\"results[special]\" value=\"".$total_query."\" />");
		print("<button type=\"submit\" style=\"margin: 0px; margin-left: 4px; margin-bottom: 1px;\" ".$submitbg.">Go</button>");
		print("</td></tr>\n");
		construct_table_END();

		// do footer
		admin_footer();

		exit;
	}

	if($_POST['results']['set_form']) {
		// figure out if we are moving it or deleting it
		if($_POST['results']['choice'] == "delete") {
			$movePrune = "delete";
		} else {
			$movePrune = "move";
		}

		// do a confirm page...
		construct_confirm("Are you sure you want to ".$movePrune." these users? You cannot undo this!","user.php?do=prune&action=delete_move",1);
		exit;
	}

	if($_GET['action'] == "delete_move") {
		// run query 
		$userinfo_select = query($_POST['confirm']['special2']);

		// split the users checked...
		$_POST['confirm']['checked_users'] = split(",",$_POST['confirm']['checked_users']);

		// yes...
		if($_POST['confirm']['yes_no']) {
			// if we want to move the users...
			if($_POST['confirm']['special3'] == "move") {
				// loop through the checked users to update
				foreach($_POST['confirm']['checked_users'] as $option_key => $option_value) {
					// form query
					$update_query = "UPDATE user_info SET usergroupid = '".$_POST['confirm']['special4']."' WHERE username = '".addslashes($option_value)."'";
					//print($update_query);
				
					// run query
					query($update_query);
				}
			}
			
			// otherwise it's deletion
			else {
				// loop through the checked users to update
				foreach($_POST['confirm']['checked_users'] as $option_key => $option_value) {
					// form query
					$update_query = "DELETE FROM user_info WHERE username = '".addslashes($option_value)."' LIMIT 1";
					//print($update_query);

					// run query
					query($update_query);
				}
			}

			redirect("thankyou.php?message=You have successfully ".$_POST['confirm']['special3']."d the users. You will now be redirected back to the prune user page.&uri=user.php?do=prune");
		}

		// no...
		else {
			redirect("user.php?do=prune");
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Users - Prune Users");

	construct_title("Prune Users");

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
	print("You can use this option to either mass move or mass prune users, specified by a certain condition. After this page is submitted, you will be brought to another page where you can selectively delete or move the users of your choice. Remember, if you delete them, the action cannot be undone!");
	print("</div></div>\n\n<br />\n\n");

	construct_table("options","move_prune","user_delete",1);
	construct_header("Prune/Move Multiple Users",2);


	construct_select_begin(2,"Usergroup","","move_prune","usergroupid");

		// get all usergroups
		$usergroup_select = query("SELECT * FROM usergroups ORDER BY name ASC");

		print("<option value=\"all\">All Usergroups</option>\n");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			print("<option value=\"".$usergroup['usergroupid']."\">".$usergroup['name']."</option>\n");
		}

	construct_select_end(2);


	construct_select_begin(1,"User joined before","","move_prune","date_joined",0,1);

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


	construct_text(2,"User has less than <b>X</b> amount of posts","","move_prune","posts","0");


	construct_select_begin(1,"Order By","","move_prune","orderby",1);

		print("<option value=\"username\" selected=\"selected\">Username</option>\n");
		print("<option value=\"date_joined\">Join Date</option>\n");
		print("<option value=\"posts\">Post Count</option>\n");
		print("<option value=\"userid\">User Id</option>\n");
	
	construct_select_end(1);


	construct_footer(2,"user_delete");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### SEARCH IP ADDRESS ##### \\

else if($_GET['do'] == "search_ip") {
	// do header
	admin_header("wtcBB Admin Panel - Users - Search IP Addresses");

	construct_title("Search IP Addresses");

	if($_POST['ip_search']['set_form']) {
		// IP address
		if($_POST['ip_search']['ip_addy']) {
			// do the search
			$search_query = query("SELECT * FROM logged_ips WHERE ip_address LIKE '".$_POST['ip_search']['ip_addy']."%' ORDER BY username");

			if(!mysql_num_rows($search_query)) {
				print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
				print("No users were found matching your criteria<br /><br />\n");
				print("</div></div>\n\n\n");
			}

			else {
				construct_table("options","userinfo_form","userinfo_submit");

				construct_header("Search Results: ".mysql_num_rows($search_query)." Users Found",2);

				print("\n\n\t<tr>\n");

					print("\t\t<td class=\"cat\">\n");
					print("\t\t\tUser\n");
					print("\t\t</td>\n\n");

					print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tIP Address\n");
					print("\t\t</td>\n\n");

				print("\t</tr>\n\n");

				while($userinfo = mysql_fetch_array($search_query)) {
					print("\t<tr>\n");

						print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
							print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
						print("\t\t</td>\n");

						print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
							print("\t\t\t".$userinfo['ip_address']."&nbsp;\n");
						print("\t\t</td>\n");

					print("\t</tr>\n\n");
				}

				print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\">&nbsp;</td></tr>\n");

				construct_table_END();
			}
		}

		print("\n\n<br /><br />\n\n");

		if($_POST['ip_search']['username_search']) {
			// do the search
			$search_query = query("SELECT * FROM logged_ips WHERE username LIKE '".$_POST['ip_search']['username_search']."%' ORDER BY username");

			if(!mysql_num_rows($search_query)) {
				print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
				print("No users were found matching your criteria<br /><br />\n");
				print("</div></div>\n\n\n");
			}

			else {
				construct_table("options","userinfo_form","userinfo_submit");

				construct_header("Search Results: ".mysql_num_rows($search_query)." Users Found",2);

				print("\n\n\t<tr>\n");

					print("\t\t<td class=\"cat\">\n");
					print("\t\t\tUser\n");
					print("\t\t</td>\n\n");

					print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tIP Address\n");
					print("\t\t</td>\n\n");

				print("\t</tr>\n\n");


				while($userinfo = mysql_fetch_array($search_query)) {
					print("\t<tr>\n");

						print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
							print("\t\t\t<a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
						print("\t\t</td>\n");

						print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
							print("\t\t\t".$userinfo['ip_address']."&nbsp;\n");
						print("\t\t</td>\n");

					print("\t</tr>\n\n");
				}

				print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\">&nbsp;</td></tr>\n");
				construct_table_END();
			}
		}
	}

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
	print("You can use this feature to find users that have logged on under an IP address, or find an IP address of a user.");
	print("</div></div>\n\n<br />\n\n");

	construct_table("options","ip_search","user_ip",1);
	construct_header("Search By IP Address/Username",2);

	construct_text(1,"IP Address","You may enter a partial IP address.","ip_search","ip_addy",$ip_search['ip_addy']);

	construct_text(2,"Username","","ip_search","username_search",$ip_search['username_search'],1);

	construct_footer(2,"user_ip");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>