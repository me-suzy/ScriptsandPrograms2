<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################ //ADMIN PANEL POST ICONS\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Post Icons";
$permissions = "post_icons";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO UPLOAD POST ICONS ##### \\
if($_GET['do'] == "upload_post_icon") {
	if($_POST['upload_post_icon']['set_form']) {
		// set a few variables
		$name = $_FILES['fupload']['name'];
		$tmp_name = $_FILES['fupload']['tmp_name'];

		// check to make sure we don't have this same post icon already uploaded...
		if(file_exists("../".$_POST['upload_post_icon']['filepath']."/".$name)) {
			construct_error("Sorry, a post icon with that filename in the file path you specified already exists.");
			exit;
		}

		if(is_uploaded_file($tmp_name)) {
			// first we need to move the file.. if it fails we kill the script, and the database isn't touched :)
			$checking_upload = move_uploaded_file($tmp_name,"../".$_POST['upload_post_icon']['filepath']."/".$name) or DIE("Could not move uploaded file.");

			// file is uploaded.. insert info into the database...
			$query = "INSERT INTO post_icons (title,filepath,display_order) VALUES ('".addslashes($_POST['upload_post_icon']['title'])."','".$_POST['upload_post_icon']['filepath']."/".$name."','".addslashes($_POST['upload_post_icon']['display_order'])."')";

			//print($query);

			// run query
			query($query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Thank you for uploading a post icon. You will now be redirected to the Post Icon Manager.&uri=post_icons.php?do=manager");
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Post Icons - Upload Post Icon");

	construct_title("Upload Post Icon");

	?>
	<form method="post" action="" name="upload_post_icon" enctype="multipart/form-data" style="margin: 0px;">
	<br /><input type="hidden" name="upload_post_icon[set_form]" value="1" />

	<table border="0" cellspacing="0" cellpadding="4" class="options">
	<?php

	construct_header("Upload Post Icon",2);

	construct_text(1,"Title","","upload_post_icon","title");

	print("\t<tr>\n");
		print("\t\t<td class=\"desc2\">\n");
			print("\t\t\t<strong>File Name</strong>\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"input2\">\n");
			print("\t\t\t<input type=\"file\" name=\"fupload\" class=\"text\" style=\"width: 85%;\" />\n");
		print("\t\t</td>\n");
	print("\t</tr>\n\n");

	construct_text(1,"File Path","This file path must be readable <strong>and</strong> writeable by your server. Usually this is a \"0777\" chmod setting.","upload_post_icon","filepath","images/post_icons");

	construct_text(2,"Display Order","","upload_post_icon","display_order","1",1);

	construct_footer(2,"upload_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO DELETE POST ICON ##### \\
else if($_GET['do'] == "delete") {
	// make sure we have a valid post icon
	$checkPostIcon = query("SELECT * FROM post_icons WHERE post_iconid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkPostIcon)) {
		construct_error("Sorry, no post icon with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// put into array
	$post_iconinfo = mysql_fetch_array($checkPostIcon);

	// construct confirm...
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete post icon query...
			query("DELETE FROM post_icons WHERE post_iconid = '".$_GET['id']."'");

			redirect("thankyou.php?message=You have successfully deleted the post icon. You will now be redirected back to the \"Post Icon Manager\".&uri=post_icons.php?do=manager");
		}

		// no...
		else {
			redirect("post_icons.php?do=manager");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete the post icon entitled <strong>".$post_iconinfo['title']."</strong>? It cannot be undone!");
}

// ##### DO EDIT POST ICON ##### \\
else if($_GET['do'] == "edit") {
	// make sure we have a valid post icon
	$checkPostIcon = query("SELECT * FROM post_icons WHERE post_iconid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkPostIcon)) {
		construct_error("Sorry, no post icon with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// put into array
	$post_iconinfo = mysql_fetch_array($checkPostIcon);

	if($_POST['edit_post_icon']['set_form']) {
		// intiate counter
		$x = 1;

		// start query
		$query = "UPDATE post_icons SET";

		// loop through form array
		foreach($_POST['edit_post_icon'] as $key => $value) {
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
		$query .= " WHERE post_iconid = '".$post_iconinfo['post_iconid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing the post icon. You will now be redirected to the Post Icon Manager.&uri=post_icons.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Post Icons - Edit Post Icons");

	construct_title("Edit Post Icon");

	construct_table("options","edit_post_icon","post_icon_submit",1);

	construct_header("Edit Post Icon <span class=\"small\">(id: ".$post_iconinfo['post_iconid'].")</span>",2);

	construct_text(1,"Title","","edit_post_icon","title",$post_iconinfo['title']);

	construct_text(2,"File Path","","edit_post_icon","filepath",$post_iconinfo['filepath']);

	construct_text(1,"Display Order","","edit_post_icon","display_order",$post_iconinfo['display_order'],1);

	construct_footer(2,"post_icon_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO POST ICON MANAGER ##### \\
else if($_GET['do'] == "manager") {
	// saving display order!
	if($_POST['post_icon_manager']['set_form']) {
		// loop through each key
		foreach($_POST['post_icon_manager'] as $key => $value) {
			// make sure we aren't doing set_form
			if($key != "set_form") {
				// update post icon table with new display order...
				$query = "UPDATE post_icons SET display_order = '".addslashes($value)."' WHERE post_iconid = '".$key."'";

				//print($query);

				// run query
				query($query);
			}
		}

		// redirect to manager
		redirect("post_icons.php?do=manager");
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

	// run query to find post icons
	$find_post_icons = query("SELECT * FROM post_icons ORDER BY display_order LIMIT ".$start_count.", ".$perpage."");
	//print("SELECT * FROM post_icons LIMIT ".$start_count.", ".$perpage." ORDER BY display_order");

	// make sure there are post icons...
	if(!mysql_num_rows($find_post_icons)) {
		construct_error("Sorry, no post icons exist");
		exit;
	}

	// find colspan...
	if(mysql_num_rows($find_post_icons) < 4) {
		$colspan = mysql_num_rows($find_post_icons);
	} else {
		$colspan = 4;
	}

	// time to get the pagenumbers... first find post icons total
	$post_icon_total = query("SELECT * FROM post_icons ORDER BY display_order");

	$numpost_icon = mysql_num_rows($post_icon_total);

	// get the number of pages...
	$numberPages = $numpost_icon / $perpage;

	// set that to int.. get rid of decimals
	settype($numberPages,int);

	// if the mod isn't ZERO.. then we add a page
	if(($numpost_icon % $perpage) != 0) {
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

			$pageNumbers .= "<button type=\"button\" onClick=\"location.href='post_icons.php?do=manager&per_page=".$perpage."&start=".$starting_count."';\" style=\"padding: 1px;\" ".$submitbg.$disabled.">".$x."</button>&nbsp; ";
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Post Icons - Post Icon Manager");

	construct_title("Post Icon Manager");

	?>
	<form method="post" action="" name="post_icon_manager" style="margin: 0px;">
	<br /><input type="hidden" name="post_icon_manager[set_form]" value="1" />

	<table border="0" cellspacing="0" cellpadding="4" class="options" style="background-color: #F8F8F8; border-left: 1px solid #000000; border-right: 1px solid #000000;">

	<tr>
		<td class="header" colspan="<?php print($colspan); ?>" style="border-left: none; border-right: none;">Post Icon Manager</td>
	</tr>

	<?php

	// loop through post icons
	for($x = 1; $post_iconinfo = mysql_fetch_array($find_post_icons); $x++) {
		// get prefix
		$check = substr($post_iconinfo['filepath'],0,7);

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
			print("\t\t\t<strong>".$post_iconinfo['title']."</strong><br /><br />\n");
			print("\t\t\t<img src=\"".$prefix.$post_iconinfo['filepath']."\" alt=\"".$post_iconinfo['title']."\" style=\"border: none;\" /><br /><br />\n");
			print("\t\t\t<button type=\"button\" onClick=\"location.href='post_icons.php?do=edit&id=".$post_iconinfo['post_iconid']."';\" style=\"margin-bottom: 1px;\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='post_icons.php?do=delete&id=".$post_iconinfo['post_iconid']."';\" style=\"margin-bottom: 1px;\" ".$submitbg.">Delete</button> &nbsp;<input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"post_icon_manager[".$post_iconinfo['post_iconid']."]\" value=\"".$post_iconinfo['display_order']."\" />\n");
		print("\t\t</td>\n");

		// mod the counter by 5.. to see if we need a new row..
		$modulus = $x % 4;

		// if mod is 0.. than new row!
		if(!$modulus) {
			print("\n\t</tr>\n\n");
			print("\n\t<tr>\n\n");
		}
	}

	print("\t<tr><td class=\"footer\" colspan=\"".$colspan."\" style=\"border-left: none; border-right: none;\"><button type=\"submit\" ".$submitbg.">Save Display Order</button> &nbsp;&nbsp;&nbsp; ".$pageNumbers." &nbsp;&nbsp;&nbsp; Post Icons Per Page: <input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"per_page\" value=\"".$perpage."\" /> <button type=\"button\" onClick=\"location.href='post_icons.php?do=manager&per_page=' + document.post_icon_manager.per_page.value;\" style=\"margin-bottom: 1px;\" ".$submitbg.">Go</button></td></tr>\n");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD (MULTIPLE) POST ICONS ##### \\
else if($_GET['do'] == "add_post_icon") {
	// lets do the single post icon first...
	if($_POST['add_post_icon']['set_form']) {
		// make sure we aren't adding one with the same filepath...
		$checkFilepath = query("SELECT * FROM post_icons WHERE filepath = '".$_POST['add_post_icon']['filepath']."' LIMIT 1");

		// uh oh...
		if(mysql_num_rows($checkFilepath)) {
			construct_error("Sorry, you cannot have two post icons with the same file path.");
			exit;
		}

		// make query by hand.. easier
		$query = "INSERT INTO post_icons (title,filepath,display_order) VALUES ('".addslashes($_POST['add_post_icon']['title'])."','".$_POST['add_post_icon']['filepath']."','".addslashes($_POST['add_post_icon']['display_order'])."')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding a post icon. You will now be redirected to the Post Icon Manager.&uri=post_icons.php?do=manager");
	}

	// what about multiple post icons?
	if($_POST['add_many_post_icons']['set_form']) {
		// before we do anything... make sure our filepath is valid
		$check = substr($add_many_post_icons['filepath'],0,7);

		if($check == "http://") {
			// error!
			construct_error("You must enter a valid file path, meaning you cannot start it with \"http://\".");
			exit;
		} 

		// alright we're safe... loop through given directory
		if($handle = opendir("../".$_POST['add_many_post_icons']['filepath'])) {
			// get rid of "." and ".."
			readdir($handle);
			readdir($handle);

			// do header
			admin_header("wtcBB Admin Panel - Post Icons - Add Multiple Post Icons");

			construct_title("Add Multiple Post Icons");

			construct_table("options","multiple_post_icons","mulitplepost_icons_submit",1);
			construct_header("Add Multiple Post Icons",4);

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
					$check = query("SELECT * FROM post_icons WHERE filepath = '".$_POST['add_many_post_icons']['filepath']."/".$file."' LIMIT 1");

					if(!mysql_num_rows($check)) {
						print("\t<tr>\n");
							print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; width: 5%; padding: 5px;\">\n");
								print("<input type=\"checkbox\" name=\"results[".$file."]\" id=\"num\" value=\"1\" />\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc1\" style=\"text-align: center; border-left: none; white-space: nowrap; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<img src=\"../".$_POST['add_many_post_icons']['filepath']."/".$file."\" alt=\"".$file."\" style=\"border: none;\" /> <br /> <strong>".$file."</strong>\n");
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
			print("<input type=\"hidden\" name=\"multiple_post_icons[filepath_e]\" value=\"".$_POST['add_many_post_icons']['filepath']."\" />\n");
			print("<button type=\"submit\" ".$submitbg.">Add Post Icons</button>");
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

	// insert multiple post_icons!
	if($_POST['multiple_post_icons']['set_form']) {
		if(!is_array($_POST['results'])) {
			construct_error("Sorry, you did not select any post icons to add. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// loop through results array
		foreach($_POST['results'] as $key => $value) {
			// make sure value is 1!
			if($value) {
				// get FULL filepath
				$fullFilepath = $_POST['multiple_post_icons']['filepath_e']."/".$key;

				// form query
				$query = "INSERT INTO post_icons (title,filepath,display_order) VALUES ('".addslashes($_POST['title'][$key])."','".$fullFilepath."','".addslashes($_POST['displayOrder'][$key])."')";

				//print($query);

				// run query
				query($query);

				// redirect to thankyou page...
				redirect("thankyou.php?message=Thank you for adding post icons. You will now be redirected to the Post Icon Manager.&uri=post_icons.php?do=manager");
			}
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Post Icons - Add Post Icons");

	construct_title("Add Post Icons");

	construct_table("options","add_post_icon","post_icon_submit",1);

	construct_header("Add Post Icon",2);

	construct_text(1,"Title","","add_post_icon","title");

	construct_text(2,"File Path","","add_post_icon","filepath","http://");

	construct_text(1,"Display Order","","add_post_icon","display_order","1",1);

	construct_footer(2,"post_icon_submit");

	construct_table_END(1);


	print("<br /><br />");


	construct_table("options","add_many_post_icons","post_icon2_submit",1);

	construct_header("Add Multiple Post Icons",2);

	construct_text(1,"File Path","","add_many_post_icons","filepath","images/post_icons",1);

	construct_footer(2,"post_icon2_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>