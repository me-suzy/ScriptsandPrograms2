<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //ADMIN PANEL LOGS\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Usertitles";
$permissions = "usertitles";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO DELETE USERTITLE ##### \\
if($_GET['do'] == "delete_usertitle") {
	// check the usertitleid..
	$checkUsertitle = query("SELECT * FROM usertitles WHERE usertitleid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkUsertitle)) {
		construct_error("Sorry, no usertitle was found matching the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// start delete usertitle
			query("DELETE FROM usertitles WHERE usertitleid = '".$_GET['id']."' LIMIT 1");

			redirect("thankyou.php?message=You have successfully deleted the usertitle. You will now be redirected back to the Usertitle Manger.&uri=usertitle.php?do=manager");
		}

		// no...
		else {
			redirect("usertitle.php?do=manager");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete this usertitle? You cannot undo this.");
}

// ##### DO EDIT USERTITLE ##### \\
else if($_GET['do'] == "edit_usertitle") {
	// check the usertitleid..
	$checkUsertitle = query("SELECT * FROM usertitles WHERE usertitleid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkUsertitle)) {
		construct_error("Sorry, no usertitle was found matching the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$usertitleinfo = mysql_fetch_array($checkUsertitle);

	if($_POST['edit_usertitle']['set_form']) {
		// form query by hand.. easier
		$query = "UPDATE usertitles SET title = '".addslashes($_POST['edit_usertitle']['title'])."' , minimumposts = '".addslashes($_POST['edit_usertitle']['minimumposts'])."' WHERE usertitleid = '".$usertitleinfo['usertitleid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing the usertitle. You will now be redirected to the Usertitle Manager.&uri=usertitle.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Usertitles - Edit Usertitle");

	construct_title("Edit Usertitle");

	construct_table("options","edit_usertitle","usertitle_submit",1);

	construct_header("Edit Usertitle",2);

	construct_text(1,"Title","","edit_usertitle","title",htmlspecialchars($usertitleinfo['title']));

	construct_text(2,"Minimum Posts","","edit_usertitle","minimumposts",$usertitleinfo['minimumposts'],1);

	construct_footer(2,"usertitle_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO USERTITLE MANAGER ##### \\

else if($_GET['do'] == "manager") {
	// select usertitles...
	$select_usertitles = query("SELECT * FROM usertitles ORDER BY minimumposts");

	// uh oh...
	if(!mysql_num_rows($select_usertitles)) {
		construct_error("Sorry, no usertitles exist.");
		exit;
	}
	
	// do header
	admin_header("wtcBB Admin Panel - Usertitles - Select Usertitle to Edit");

	construct_title("Select a Usertitle to Edit");

	construct_table("options","usertitle_form","usertitle_submit",1);
	construct_header("All Usertitles",3);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tUsertitle\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tMinimum Posts\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($usertitleinfo = mysql_fetch_array($select_usertitles)) {
		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: left; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>".$usertitleinfo['title']."</strong>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$usertitleinfo['minimumposts']."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<button type=\"button\" onClick=\"location.href='usertitle.php?do=edit_usertitle&id=".$usertitleinfo['usertitleid']."';\" style=\"margin-right: 4px;\" ".$submitbg.">Edit</button> <button type=\"button\" onClick=\"location.href='usertitle.php?do=delete_usertitle&id=".$usertitleinfo['usertitleid']."';\" ".$submitbg.">Delete</button>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"3\"><button type=\"button\" onclick=\"location.href='usertitle.php?do=add_usertitle';\" ".$submitbg.">Add New Usertitle</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO ADD USERTITLE ##### \\

else if($_GET['do'] == "add_usertitle") {
	if($_POST['add_usertitle']['set_form']) {
		// form query by hand.. meh.. easier.. only two fields
		$query = "INSERT INTO usertitles (title,minimumposts) VALUES ('".addslashes($_POST['add_usertitle']['title'])."','".addslashes($_POST['add_usertitle']['minimumposts'])."')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding a new usertitle. You will now be redirected to the Usertitle Manager.&uri=usertitle.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Usertitles - Add Usertitle");

	construct_title("Add Usertitle");

	construct_table("options","add_usertitle","usertitle_submit",1);

	construct_header("Add Usertitle",2);

	construct_text(1,"Title","","add_usertitle","title");

	construct_text(2,"Minimum Posts","","add_usertitle","minimumposts","",1);

	construct_footer(2,"usertitle_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}