<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############# //ADMIN PANEL WARNING SYSTEM\\ ############## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Warning System";
$permissions = "warn";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO DELETE WARN TYPE ##### \\
if($_GET['do'] == "delete") {
	// check the warn typeid..
	$checkType = query("SELECT * FROM warn_type WHERE typeid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkType)) {
		construct_error("Sorry, no warn type was found matching the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// start delete warn type
			query("DELETE FROM warn_type WHERE typeid = '".$_GET['id']."' LIMIT 1");

			redirect("thankyou.php?message=You have successfully deleted the warn type. You will now be redirected back to the Warn Type Manger.&uri=warn.php?do=edit");
		}

		// no...
		else {
			redirect("warn.php?do=edit");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete this warn type? You cannot undo this.");
}

// ##### DO RESET WARNING LEVELS ##### \\
if($_GET['do'] == "reset") {
	// construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// reset to 0
			query("UPDATE user_info SET warn = 0;");

			redirect("thankyou.php?message=You have successfully reset all users' warning levels to zero. You will now be redirected back to the Warn Type Manger.&uri=warn.php?do=edit");
		}

		// no...
		else {
			redirect("warn.php?do=edit");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to reset <strong>all</strong> users' warning levels to zero? You cannot undo this. (This will not unban users that were automatically banned by the warning system.)");
}



// ##### DO EDIT WARN TYPR ##### \\
else if($_GET['do'] == "edit" AND $_GET['id']) {
	// check the warn typeid..
	$checkType = query("SELECT * FROM warn_type WHERE typeid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkType)) {
		construct_error("Sorry, no warn type was found matching the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$warninfo = mysql_fetch_array($checkType);

	if($_POST['edit_warn']['set_form']) {
		// form query by hand.. easier
		$query = "UPDATE warn_type SET name = '".addslashes($_POST['edit_warn']['name'])."' , warnPoints = '".addslashes($_POST['edit_warn']['warnPoints'])."' WHERE typeid = '".$warninfo['typeid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing the warn type. You will now be redirected to the Warn Type Manager.&uri=warn.php?do=edit");
	}

	// do header
	admin_header("wtcBB Admin Panel - Warn Types - Edit Warn Type");

	construct_title("Edit Warn Type");

	construct_table("options","edit_warn","warn_submit",1);

	construct_header("Edit Warn Type",2);

	construct_text(1,"Name","","edit_warn","name",htmlspecialchars($warninfo['name']));

	construct_text(2,"Warn Points","This is the amount of warn points given to a user if this is supplied as a reason for warning.","edit_warn","warnPoints",$warninfo['warnPoints'],1);

	construct_footer(2,"warn_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO VIEW WARNINGS ##### \\
else if($_GET['do'] == "view") {
	// construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// get warninfo...
			$warninfo = query("SELECT warn.* , user_info.warn , warn_type.* FROM warn LEFT JOIN warn_type ON warn.typeid = warn_type.typeid LEFT JOIN user_info ON user_info.userid = warn.userid WHERE warnid = '".$_GET['id']."'",1);

			$newLevel = $warninfo['warn'] - $warninfo['warnPoints'];

			if($newLevel < 0) {
				$newLevel = 0;
			}

			query("UPDATE user_info SET warn = '".$newLevel."' WHERE userid = '".$warninfo['userid']."'");
			query("DELETE FROM warn WHERE warnid = '".$warninfo['warnid']."'");

			redirect("thankyou.php?message=You have successfully undone the warning. You will now be redirected back to the Warning Log.&uri=warn.php?do=view");
		}

		// no...
		else {
			redirect("warn.php?do=view");
		}
	}

	// undo warn?
	if($_GET['id']) {
		if(!mysql_num_rows(query("SELECT * FROM warn WHERE warnid = '".$_GET['id']."'"))) {
			construct_error("Sorry, the warning you are trying to undo does not exist. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		else {
			// do a confirm page...
			construct_confirm("Are you sure you want to undo the warning? This cannot be undone.");
			exit;
		}
	}

	if(!$_POST['search']['username'] AND !$_POST['search']['warnedBy']) {
		$findWarnings = query("SELECT * FROM warn LEFT JOIN user_info ON user_info.userid = warn.userid LEFT JOIN warn_type ON warn_type.typeid = warn.typeid ORDER BY warn.warnDate DESC");
	}

	else {
		$conditions = '';

		if($_POST['search']['username']) {
			$conditions = " user_info.username = '".$_POST['search']['username']."'AND";
		}

		if($_POST['search']['warnedBy']) {
			$getId_q = query("SELECT userid FROM user_info WHERE username = '".$_POST['search']['warnedBy']."'");

			if(mysql_num_rows($getId_q)) {
				$getId = mysql_fetch_array($getId_q);
				$conditions .= " warn.whoWarned = '".$getId['userid']."'";
			}
		}

		$conditions = preg_replace('#AND$#isU','',$conditions);

		$findWarnings = query("SELECT * FROM warn LEFT JOIN user_info ON user_info.userid = warn.userid LEFT JOIN warn_type ON warn_type.typeid = warn.typeid WHERE".$conditions." ORDER BY warn.warnDate DESC");
	}

	if(!mysql_num_rows($findWarnings)) {
		construct_error("Sorry, no warnings have been logged with the criteria specified. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// do header
	admin_header("wtcBB Admin Panel - Warning Log");

	construct_title("Warning Log");

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
	print("<a href=\"warn.php?do=reset\">Reset all users' warning levels to zero.</a>");
	print("</div></div>\n\n<br />\n\n");

	construct_table("options","warn_form","warn_submit",1);
	construct_header("Warning Log",8);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tUsername\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tWarned By\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tWarn Points\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tReason\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tNotes\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tPost\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tDate\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($warninfo = mysql_fetch_array($findWarnings)) {
		$warnedBy = query("SELECT username FROM user_info WHERE userid = '".$warninfo['whoWarned']."'",1);

		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: left; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>".$warninfo['username']."</strong> <br /> <span class=\"small\">Warning Level: ".$warninfo['warn']."</span>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$warnedBy['username']."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$warninfo['warnPoints']."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$warninfo['name']."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$warninfo['note']."&nbsp;\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"./../thread.php?p=".$warninfo['postid']."\">View Post</a>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".date('m-d-Y \a\\t h:i A',$warninfo['warnDate'])."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t" . '<a href="warn.php?do=view&amp;id=' . $warninfo['warnid'] . '">Undo Warn</a> - ');
				
				if($warninfo['usergroupid'] == $bboptions['autoBanGroup']) {
					print('<a href="user.php?do=restore&amp;id=' . $warninfo['userid'] . '">Restore</a>' . "\n");
				}

				else {
					print('<a href="user.php?do=ban&amp;ban_username=' . $warninfo['username'] . '&amp;ban_usergroup=' . $bboptions['autoBanGroup'] . '">Ban</a>' . "\n");
				}
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"8\">&nbsp;</td></tr>\n");
	construct_table_END(1);


	construct_table("options","search_warn","search_warn_submit",1);

	construct_header("Search Warning Log",2);

	construct_text(1,"Username","","search","username",htmlspecialchars($_POST['search']['username']));

	construct_text(2,"Warned By","","search","warnedBy",htmlspecialchars($_POST['search']['warnedBy']),1);

	construct_footer(2,"search_warn_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO WARN TYPE MANAGER ##### \\

else if($_GET['do'] == "edit") {
	// select warn types...
	$select_warnTypes = query("SELECT * FROM warn_type ORDER BY warnPoints DESC");

	// uh oh...
	if(!mysql_num_rows($select_warnTypes)) {
		construct_error("Sorry, no warn types exist.");
		exit;
	}
	
	// do header
	admin_header("wtcBB Admin Panel - Warn Types - Select Warn Type to Edit");

	construct_title("Select a Warn Type to Edit");

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
	print("<a href=\"warn.php?do=reset\">Reset all users' warning levels to zero.</a>");
	print("</div></div>\n\n<br />\n\n");

	construct_table("options","warn_form","warn_submit",1);
	construct_header("All Warn Types",3);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tWarn Type Name\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tWarn Points\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($warninfo = mysql_fetch_array($select_warnTypes)) {
		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: left; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>".$warninfo['name']."</strong>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$warninfo['warnPoints']."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<button type=\"button\" onclick=\"location.href='warn.php?do=edit&id=".$warninfo['typeid']."';\" style=\"margin-right: 4px;\" ".$submitbg.">Edit</button> <button type=\"button\" onclick=\"location.href='warn.php?do=delete&id=".$warninfo['typeid']."';\" ".$submitbg.">Delete</button>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"3\"><button type=\"button\" onclick=\"location.href='warn.php?do=add';\" ".$submitbg.">Add Warn Type</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD WARN TYPE ##### \\

else if($_GET['do'] == "add") {
	if($_POST['add_warn']['set_form']) {
		// form query by hand.. meh.. easier.. only two fields
		$query = "INSERT INTO warn_type (name,warnPoints) VALUES ('".addslashes($_POST['add_warn']['name'])."','".addslashes($_POST['add_warn']['warnPoints'])."')";

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding a new warn type. You will now be redirected to the Warn Type Manager.&uri=warn.php?do=edit");
	}

	// do header
	admin_header("wtcBB Admin Panel - Warning System - Add Warn Type");

	construct_title("Add Warn Type");

	construct_table("options","add_warn","warn_submit",1);

	construct_header("Add Warn Type",2);

	construct_text(1,"Name","","add_warn","name");

	construct_text(2,"Amount of Warn Points","This is the amount of warn points given to a user if this is supplied as a reason for warning.","add_warn","warnPoints","",1);

	construct_footer(2,"warn_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}