<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //ADMIN PANEL AVATARS\\ ################# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Avatars";
$permissions = "avatars";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO UPLOAD AVATARS ##### \\
if($_GET['do'] == "upload_avatar") {
	if($_POST['upload_avatar']['set_form']) {
		// set a few variables
		$name = $_FILES['fupload']['name'];
		$tmp_name = $_FILES['fupload']['tmp_name'];

		// check to make sure we don't have this same avatar already uploaded...
		if(file_exists("../".$_POST['upload_avatar']['filepath']."/".$name)) {
			construct_error("Sorry, an avatar with that filename in the file path you specified already exists.");
			exit;
		}

		if(is_uploaded_file($tmp_name)) {
			// first we need to move the file.. if it fails we kill the script, and the database isn't touched :)
			$checking_upload = move_uploaded_file($tmp_name,"../".$_POST['upload_avatar']['filepath']."/".$name) or DIE("Could not move uploaded file.");

			// file is uploaded.. insert info into the database...
			$query = "INSERT INTO avatars (title,filepath,display_order,filename) VALUES ('".addslashes($_POST['upload_avatar']['title'])."','".addslashes($upload_avatar['filepath'])."/".$name."','".addslashes($_POST['upload_avatar']['display_order'])."','".$name."')";

			//print($query);

			// run query
			query($query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Thank you for uploading an avatar. You will now be redirected to the Avatar Manager.&uri=avatar.php?do=manager");
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Avatars - Upload Avatar");

	construct_title("Upload Avatar");

	?>
	<form method="post" action="" name="upload_avatar" enctype="multipart/form-data" style="margin: 0px;">
	<br /><input type="hidden" name="upload_avatar[set_form]" value="1" />

	<table border="0" cellspacing="0" cellpadding="4" class="options">
	<?php

	construct_header("Upload Avatar",2);

	construct_text(1,"Title","","upload_avatar","title");

	print("\t<tr>\n");
		print("\t\t<td class=\"desc2\">\n");
			print("\t\t\t<strong>File Name</strong>\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"input2\">\n");
			print("\t\t\t<input type=\"file\" name=\"fupload\" class=\"text\" style=\"width: 85%;\" />\n");
		print("\t\t</td>\n");
	print("\t</tr>\n\n");

	construct_text(1,"File Path","This file path must be readable <strong>and</strong> writeable by your server. Usually this is a \"0777\" chmod setting.","upload_avatar","filepath","avatars");

	construct_text(2,"Display Order","","upload_avatar","display_order","1",1);

	construct_footer(2,"upload_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO DELETE AVATAR ##### \\
else if($_GET['do'] == "delete") {
	// make sure we have a valid avatar
	$checkAvatar = mysql_query("SELECT * FROM avatars WHERE avatarid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkAvatar)) {
		construct_error("Sorry, no avatar with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// put into array
	$avatarinfo = mysql_fetch_array($checkAvatar);

	// construct confirm...
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// before we delete avatar.. we also have to go through the user_info table and delete all match avatars...
			query("UPDATE user_info SET avatar_url = 'none' WHERE avatar_url = '".$avatarinfo['filepath']."'");

			// delete avatar query...
			query("DELETE FROM avatars WHERE avatarid = '".$_GET['id']."'");

			redirect("thankyou.php?message=You have successfully deleted the avatar. You will now be redirected back to the \"Avatar Manager\".&uri=avatar.php?do=manager");
		}

		// no...
		else {
			redirect("avatar.php?do=manager");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete the avatar entitled <strong>".$avatarinfo['title']."</strong>? It cannot be undone!");
}

// ##### DO EDIT AVATAR ##### \\
else if($_GET['do'] == "edit") {
	// make sure we have a valid avatar
	$checkAvatar = mysql_query("SELECT * FROM avatars WHERE avatarid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkAvatar)) {
		construct_error("Sorry, no avatar with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// put into array
	$avatarinfo = mysql_fetch_array($checkAvatar);

	if($_POST['edit_avatar']['set_form']) {
		// intiate counter
		$x = 1;

		// start query
		$query = "UPDATE avatars SET";

		// loop through form array
		foreach($_POST['edit_avatar'] as $key => $value) {
			// only if it isn't set form
			if($key != "set_form") {
				// get comma
				if($x == 1) {
					$comma = " ";
				} else {
					$comma = " , ";
				}

				// form more of the query
				$query .= $comma.$key." = '".addslashes($value)."'";

				// increment counter
				$x++;
			}
		}

		// finish off query
		$query .= " WHERE avatarid = '".$avatarinfo['avatarid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing the avatar. You will now be redirected to the Avatar Manager.&uri=avatar.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Avatars - Edit Avatar");

	construct_title("Edit Avatar");

	construct_table("options","edit_avatar","avatar_submit",1);

	construct_header("Edit Avatar <span class=\"small\">(id: ".$avatarinfo['avatarid'].")</span>",2);

	construct_text(1,"Title","","edit_avatar","title",$avatarinfo['title']);

	construct_text(2,"File Path","","edit_avatar","filepath",$avatarinfo['filepath']);

	construct_text(1,"Display Order","","edit_avatar","display_order",$avatarinfo['display_order'],1);

	construct_footer(2,"avatar_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO AVATAR MANAGER ##### \\
else if($_GET['do'] == "manager") {
	// saving display order!
	if($_POST['avatar_manager']['set_form']) {
		// loop through each key
		foreach($_POST['avatar_manager'] as $key => $value) {
			// make sure we aren't doing set_form
			if($key != "set_form") {
				// update avatar table with new display order...
				$query = "UPDATE avatars SET display_order = '".$value."' WHERE avatarid = '".$key."'";

				//print($query);

				// run query
				query($query);
			}
		}

		// redirect to manager
		redirect("avatar.php?do=manager");
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
	$find_avs = mysql_query("SELECT * FROM avatars ORDER BY display_order LIMIT ".$start_count.", ".$perpage."");
	//print("SELECT * FROM avatars LIMIT ".$start_count.", ".$perpage." ORDER BY display_order");

	// make sure there are avatars...
	if(!mysql_num_rows($find_avs)) {
		construct_error("Sorry, no avatars exist");
		exit;
	}

	// find colspan...
	if(mysql_num_rows($find_avs) < 4) {
		$colspan = mysql_num_rows($find_avs);
	} else {
		$colspan = 4;
	}

	// time to get the pagenumbers... first find avatars total
	$avatar_total = mysql_query("SELECT * FROM avatars ORDER BY display_order");

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

			$pageNumbers .= "<button type=\"button\" onClick=\"location.href='avatar.php?do=manager&per_page=".$perpage."&start=".$starting_count."';\" style=\"padding: 1px;\" ".$submitbg.$disabled.">".$x."</button>&nbsp; ";
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Avatars - Avatar Manager");

	construct_title("Avatar Manager");

	?>
	<form method="post" action="" name="avatar_manager" style="margin: 0px;">
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

		print("\t\t<td style=\"background-color: ".$backgroundColor."; text-align: center; white-space: nowrap; padding-bottom: 10px; padding-top: 10px;\">\n");
			print("\t\t\t<strong>".$avatarinfo['title']."</strong><br /><br />\n");
			print("\t\t\t<img src=\"".$prefix.$avatarinfo['filepath']."\" alt=\"".$avatarinfo['title']."\" style=\"border: none;\" /><br /><br />\n");
			print("\t\t\t<button type=\"button\" onClick=\"location.href='avatar.php?do=edit&id=".$avatarinfo['avatarid']."';\" style=\"margin-bottom: 1px;\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='avatar.php?do=delete&id=".$avatarinfo['avatarid']."';\" style=\"margin-bottom: 1px;\" ".$submitbg.">Delete</button> &nbsp;<input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"avatar_manager[".$avatarinfo['avatarid']."]\" value=\"".$avatarinfo['display_order']."\" />\n");
		print("\t\t</td>\n");

		// mod the counter by 5.. to see if we need a new row..
		$modulus = $x % 4;

		// if mod is 0.. than new row!
		if(!$modulus) {
			print("\n\t</tr>\n\n");
			print("\n\t<tr>\n\n");
		}
	}

	print("\t<tr><td class=\"footer\" colspan=\"".$colspan."\" style=\"border-left: none; border-right: none;\"><button type=\"submit\" ".$submitbg.">Save Display Order</button> &nbsp;&nbsp;&nbsp; ".$pageNumbers." &nbsp;&nbsp;&nbsp; Avatars Per Page: <input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"per_page\" value=\"".$perpage."\" /> <button type=\"button\" onClick=\"location.href='avatar.php?do=manager&per_page=' + document.avatar_manager.per_page.value;\" style=\"margin-bottom: 1px;\" ".$submitbg.">Go</button></td></tr>\n");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD (MULTIPLE) AVATARS ##### \\
else if($_GET['do'] == "add_avatar") {
	// lets do the single avatar first...
	if($_POST['add_avatar']['set_form']) {
		// make sure we aren't adding one with the same filepath...
		$checkFilepath = mysql_query("SELECT * FROM avatars WHERE filepath = '".$_POST['add_avatar']['filepath']."' LIMIT 1");

		// uh oh...
		if(mysql_num_rows($checkFilepath)) {
			construct_error("Sorry, you cannot have two avatars with the same file path.");
			exit;
		}

		// find the filename...
		$split_string = explode("/",$_POST['add_avatar']['filepath']);

		// get last index
		$last_index = (count($split_string) - 1);

		// get the filename
		$getFilename = $split_string[$last_index];

		// make query by hand.. easier
		$query = "INSERT INTO avatars (title,filepath,display_order,filename) VALUES ('".addslashes($_POST['add_avatar']['title'])."','".$_POST['add_avatar']['filepath']."','".addslashes($_POST['add_avatar']['display_order'])."','".$getFilename."')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding an avatar. You will now be redirected to the Avatar Manager.&uri=avatar.php?do=manager");
	}

	// what about multiple avatars?
	if($_POST['add_many_avatars']['set_form']) {
		// before we do anything... make sure our filepath is valid
		$check = substr($_POST['add_many_avatars']['filepath'],0,7);

		if($check == "http://") {
			// error!
			construct_error("You must enter a valid file path, meaning you cannot start it with \"http://\".");
			exit;
		} 

		// alright we're safe... loop through given directory
		if($handle = opendir("../".$_POST['add_many_avatars']['filepath'])) {
			// get rid of "." and ".."
			readdir($handle);
			readdir($handle);

			// do header
			admin_header("wtcBB Admin Panel - Avatars - Add Multiple Avatars");

			construct_title("Add Multiple Avatars");

			construct_table("options","multiple_avs","mulitpleavs_submit",1);
			construct_header("Add Multiple Avatars",4);

			print("\n\n\t<tr>\n");

				print("\t\t<td class=\"cat\">\n");
					print("<input type=\"checkbox\" name=\"check_all\" id=\"check_all\" value=\"checking_all\" title=\"Check All\" onclick=\"checkAll(this.form);\" />\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tImage\n");
				print("\t\t</td>\n\n");

				print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tTitle\n");
				print("\t\t</td>\n\n");

				print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tDisplay Order\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");

			// intiate counter
			$x = 1;

			while($file = readdir($handle)) { 
				$extension = strtolower(strrchr($file,"."));
				if($extension == ".gif" OR $extension == ".jpg" OR $extension == ".jpeg" OR $extension == ".jpe" OR $extension == ".png" OR $extension == ".bmp") {
					// check DB to see if something with this filename already exists.. if so.. no go!
					$check = mysql_query("SELECT * FROM avatars WHERE filepath = '".$_POST['add_many_avatars']['filepath']."/".$file."' LIMIT 1");

					if(!mysql_num_rows($check)) {
						print("\t<tr>\n");
							print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; width: 5%; padding: 5px;\">\n");
								print("<input type=\"checkbox\" name=\"results[".$file."]\" id=\"num\" value=\"1\" />\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc1\" style=\"text-align: center; border-left: none; white-space: nowrap; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<img src=\"../".$_POST['add_many_avatars']['filepath']."/".$file."\" alt=\"".$file."\" style=\"border: none;\" /> <br /> <strong>".$file."</strong>\n");
							print("\t\t</td>\n");

							// get title...
							$file_title = explode(".",$file);

							print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<input type=\"text\" class=\"text\" name=\"title[".$file."]\" value=\"".$file_title[0]."\" />\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<input type=\"text\" class=\"text\" style=\"width: 20px;\" name=\"displayOrder[".$file."]\" value=\"".$x."\" />\n");
							print("\t\t</td>\n");
						print("\t</tr>\n\n");

						// increment counter 
						$x++;
					}
				}
			}

			print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"4\">");
			print("<input type=\"hidden\" name=\"multiple_avs[filepath_e]\" value=\"".$_POST['add_many_avatars']['filepath']."\" />\n");
			print("<button type=\"submit\" ".$submitbg.">Add Avatars</button>");
			print("</td></tr>\n");
			construct_table_END(1);

			// do footer
			admin_footer();

			closedir($handle); 
		}

		// otherwise, we couldn't open dir.. error!
		else {
			construct_error("Sorry, we could not open the desired filepath.");
			exit;
		}

		exit;
	}

	// insert multiple av's!
	if($_POST['multiple_avs']['set_form']) {
		if(!is_array($_POST['results'])) {
			construct_error("Sorry, you did not select any avatars to add. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// loop through results array
		foreach($_POST['results'] as $key => $value) {
			// make sure value is 1!
			if($value) {
				// get FULL filepath
				$fullFilepath = $_POST['multiple_avs']['filepath_e']."/".$key;

				// form query
				$query = "INSERT INTO avatars (title,filepath,display_order,filename) VALUES ('".$_POST['title'][$key]."','".$fullFilepath."','".$_POST['displayOrder'][$key]."','".$key."')";

				//print($query);

				// run query
				query($query);

				// redirect to thankyou page...
				redirect("thankyou.php?message=Thank you for adding avatars. You will now be redirected to the Avatar Manager.&uri=avatar.php?do=manager");
			}
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Avatars - Add Avatar");

	construct_title("Add Avatar");

	construct_table("options","add_avatar","avatar_submit",1);

	construct_header("Add Avatar",2);

	construct_text(1,"Title","","add_avatar","title");

	construct_text(2,"File Path","","add_avatar","filepath","http://");

	construct_text(1,"Display Order","","add_avatar","display_order","1",1);

	construct_footer(2,"avatar_submit");

	construct_table_END(1);


	print("<br /><br />");


	construct_table("options","add_many_avatars","avatar2_submit",1);

	construct_header("Add Multiple Avatars",2);

	construct_text(1,"File Path","","add_many_avatars","filepath","avatars",1);

	construct_footer(2,"avatar2_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>