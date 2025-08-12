<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //ADMIN PANEL SMILIES\\ ################# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Smilies";
$permissions = "smilies";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO UPLOAD SMILIES ##### \\
if($_GET['do'] == "upload_smilie") {
	if($_POST['upload_smilie']['set_form']) {
		// set a few variables
		$name = $_FILES['fupload']['name'];
		$tmp_name = $_FILES['fupload']['tmp_name'];

		// check to make sure we don't have this same smiley already uploaded...
		if(file_exists("../".$_POST['upload_smilie']['filepath']."/".$name)) {
			construct_error("Sorry, a smiley with that filename in the file path you specified already exists.");
			exit;
		}

		if(is_uploaded_file($tmp_name)) {
			// first we need to move the file.. if it fails we kill the script, and the database isn't touched :)
			$checking_upload = move_uploaded_file($tmp_name,"../".$_POST['upload_smilie']['filepath']."/".$name) OR die("Could not move uploaded file.");

			// file is uploaded.. insert info into the database...
			$query = "INSERT INTO smilies (title,filepath,display_order,replacement) VALUES ('".addslashes($_POST['upload_smilie']['title'])."','".$_POST['upload_smilie']['filepath']."/".$name."','".addslashes($_POST['upload_smilie']['display_order'])."','".addslashes($_POST['upload_smilie']['replacement'])."')";

			//print($query);

			// run query
			query($query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=Thank you for uploading a smiley. You will now be redirected to the Smiley Manager.&uri=smilies.php?do=manager");
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Smilies - Upload Smiley");

	construct_title("Upload Smilie");

	?>
	<form method="post" action="" name="upload_smilie" enctype="multipart/form-data" style="margin: 0;">
	<br /><input type="hidden" name="upload_smilie[set_form]" value="1" />

	<table border="0" cellspacing="0" cellpadding="4" class="options">
	<?php

	construct_header("Upload Smilie",2);

	construct_text(1,"Title","","upload_smilie","title");

	construct_text(2,"Replacement","","upload_smilie","replacement");

	print("\t<tr>\n");
		print("\t\t<td class=\"desc1\">\n");
			print("\t\t\t<strong>File Name</strong>\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"input1\">\n");
			print("\t\t\t<input type=\"file\" name=\"fupload\" class=\"text\" style=\"width: 85%;\" />\n");
		print("\t\t</td>\n");
	print("\t</tr>\n\n");

	construct_text(2,"File Path","This file path must be readable <strong>and</strong> writeable by your server. Usually this is a \"0777\" chmod setting.","upload_smilie","filepath","images/smilies");

	construct_text(1,"Display Order","","upload_smilie","display_order","1",1);

	construct_footer(2,"upload_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO DELETE SMILEY ##### \\
else if($_GET['do'] == "delete") {
	// make sure we have a valid smiley
	$checkSmilie = query("SELECT * FROM smilies WHERE smilieid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkSmilie)) {
		construct_error("Sorry, no smiley with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// put into array
	$smilieinfo = mysql_fetch_array($checkSmilie);

	// construct confirm...
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete smilie query...
			query("DELETE FROM smilies WHERE smilieid = '".$_GET['id']."'");

			redirect("thankyou.php?message=You have successfully deleted the smiley. You will now be redirected back to the \"Smiley Manager\".&uri=smilies.php?do=manager");
		}

		// no...
		else {
			redirect("smilies.php?do=manager");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete the smiley entitled <strong>".$smilieinfo['title']."</strong>? It cannot be undone!");
}

// ##### DO EDIT SMILEY ##### \\
else if($_GET['do'] == "edit") {
	// make sure we have a valid smiley
	$checkSmiley = query("SELECT * FROM smilies WHERE smilieid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkSmiley)) {
		construct_error("Sorry, no smilie with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// put into array
	$smilieinfo = mysql_fetch_array($checkSmiley);

	if($_POST['edit_smilie']['set_form']) {
		// intiate counter
		$x = 1;

		// start query
		$query = "UPDATE smilies SET";

		// loop through form array
		foreach($_POST['edit_smilie'] as $key => $value) {
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
		$query .= " WHERE smilieid = '".$smilieinfo['smilieid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing the smiley. You will now be redirected to the Smiley Manager.&uri=smilies.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Smilies - Edit Smiley");

	construct_title("Edit Smiley");

	construct_table("options","edit_smilie","smiley_submit",1);

	construct_header("Edit Smilie <span class=\"small\">(id: ".$smilieinfo['smilieid'].")</span>",2);

	construct_text(1,"Title","","edit_smilie","title",$smilieinfo['title']);

	construct_text(2,"File Path","","edit_smilie","filepath",$smilieinfo['filepath']);

	construct_text(1,"Replacement","","edit_smilie","replacement",$smilieinfo['replacement']);

	construct_text(2,"Display Order","","edit_smilie","display_order",$smilieinfo['display_order'],1);

	construct_footer(2,"smiley_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO SMILEY MANAGER ##### \\
else if($_GET['do'] == "manager") {
	// saving display order!
	if($_POST['smilie_manager']['set_form']) {
		// loop through each key
		foreach($smilie_manager as $key => $value) {
			// make sure we aren't doing set_form
			if($key != "set_form") {
				// update smiley table with new display order...
				$query = "UPDATE smilies SET display_order = '".addslashes($value)."' WHERE smilieid = '".$key."'";

				//print($query);

				// run query
				query($query);
			}
		}

		// redirect to manager
		redirect("smilies.php?do=manager");
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

	// run query to find smilies
	$find_smilies = query("SELECT * FROM smilies ORDER BY display_order LIMIT ".$start_count.", ".$perpage."");
	//print("SELECT * FROM smilies LIMIT ".$start_count.", ".$perpage." ORDER BY display_order");

	// make sure there are smilies...
	if(!mysql_num_rows($find_smilies)) {
		construct_error("Sorry, no smilies exist");
		exit;
	}

	// find colspan...
	if(mysql_num_rows($find_smilies) < 4) {
		$colspan = mysql_num_rows($find_smilies);
	} else {
		$colspan = 4;
	}

	// time to get the pagenumbers... first find smilies total
	$smiley_total = query("SELECT * FROM smilies ORDER BY display_order");

	$numSmilies = mysql_num_rows($smiley_total);

	// get the number of pages...
	$numberPages = $numSmilies / $perpage;

	// set that to int.. get rid of decimals
	settype($numberPages,int);

	// if the mod isn't ZERO.. then we add a page
	if(($numSmilies % $perpage) != 0) {
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

			$pageNumbers .= "<button type=\"button\" onClick=\"location.href='smilies.php?do=manager&per_page=".$perpage."&start=".$starting_count."';\" style=\"padding: 1px;\" ".$submitbg.$disabled.">".$x."</button>&nbsp; ";
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Smilies - Smiley Manager");

	construct_title("Smiley Manager");

	?>
	<form method="post" action="" name="smilie_manager" style="margin: 0px;">
	<br /><input type="hidden" name="smilie_manager[set_form]" value="1" />

	<table border="0" cellspacing="0" cellpadding="4" class="options" style="background-color: #F8F8F8; border-left: 1px solid #000000; border-right: 1px solid #000000;">

	<tr>
		<td class="header" colspan="<?php print($colspan); ?>" style="border-left: none; border-right: none;">Smiley Manager</td>
	</tr>

	<?php

	// loop through smilies
	for($x = 1; $smilieinfo = mysql_fetch_array($find_smilies); $x++) {
		// get prefix
		$check = substr($smilieinfo['filepath'],0,7);

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
			print("\t\t\t<strong>".$smilieinfo['title']."</strong><br /><br />\n");
			print("\t\t\t<img src=\"".$prefix.$smilieinfo['filepath']."\" alt=\"".$smilieinfo['title']."\" style=\"border: none;\" /><br />\n");
			print("\t\t\t<strong style=\"color: #bb0000;\">".$smilieinfo['replacement']."</strong><br />\n\n");
			print("\t\t\t<button type=\"button\" onClick=\"location.href='smilies.php?do=edit&id=".$smilieinfo['smilieid']."';\" style=\"margin-bottom: 1px;\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <button type=\"button\" onClick=\"location.href='smilies.php?do=delete&id=".$smilieinfo['smilieid']."';\" style=\"margin-bottom: 1px;\" ".$submitbg.">Delete</button> &nbsp;<input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"smilie_manager[".$smilieinfo['smilieid']."]\" value=\"".$smilieinfo['display_order']."\" />\n");
		print("\t\t</td>\n");

		// mod the counter by 5.. to see if we need a new row..
		$modulus = $x % 4;

		// if mod is 0.. than new row!
		if(!$modulus) {
			print("\n\t</tr>\n\n");
			print("\n\t<tr>\n\n");
		}
	}

	print("\t<tr><td class=\"footer\" colspan=\"".$colspan."\" style=\"border-left: none; border-right: none;\"><button type=\"submit\" ".$submitbg.">Save Display Order</button> &nbsp;&nbsp;&nbsp; ".$pageNumbers." &nbsp;&nbsp;&nbsp; Smilies Per Page: <input type=\"text\" class=\"text\" style=\"width: 20px; padding: 1px;\" name=\"per_page\" value=\"".$perpage."\" /> <button type=\"button\" onClick=\"location.href='smilies.php?do=manager&per_page=' + document.smilie_manager.per_page.value;\" style=\"margin-bottom: 1px;\" ".$submitbg.">Go</button></td></tr>\n");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD (MULTIPLE) SMILIES ##### \\
else if($_GET['do'] == "add_smiley") {
	// lets do the single smiley first...
	if($_POST['add_smiley']['set_form']) {
		// make sure we aren't adding one with the same filepath...
		$checkFilepath = query("SELECT * FROM smilies WHERE filepath = '".$_POST['add_smiley']['filepath']."' LIMIT 1");

		// uh oh...
		if(mysql_num_rows($checkFilepath)) {
			construct_error("Sorry, you cannot have two smilies with the same file path.");
			exit;
		}

		// make query by hand.. easier
		$query = "INSERT INTO smilies (title,filepath,display_order,replacement) VALUES ('".addslashes($_POST['add_smiley']['title'])."','".$_POST['add_smiley']['filepath']."','".addslashes($_POST['add_smiley']['display_order'])."','".addslashes($_POST['add_smiley']['replacement'])."')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding a smiley. You will now be redirected to the Smiley Manager.&uri=smilies.php?do=manager");
	}

	// what about multiple smilies?
	if($_POST['add_many_smilies']['set_form']) {
		// before we do anything... make sure our filepath is valid
		$check = substr($_POST['add_many_smilies']['filepath'],0,7);

		if($check == "http://") {
			// error!
			construct_error("You must enter a valid file path, meaning you cannot start it with \"http://\".");
			exit;
		} 

		// alright we're safe... loop through given directory
		if($handle = opendir("../".$_POST['add_many_smilies']['filepath'])) {
			// get rid of "." and ".."
			readdir($handle);
			readdir($handle);

			// do header
			admin_header("wtcBB Admin Panel - Smilies - Add Multiple Smilies");

			construct_title("Add Multiple Smilies");

			construct_table("options","multiple_smilies","mulitplesmilies_submit",1);
			construct_header("Add Multiple Smilies",5);

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
					print("\t\t\tReplacement\n");
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
					$check = query("SELECT * FROM smilies WHERE filepath = '".$_POST['add_many_smilies']['filepath']."/".$file."' LIMIT 1");

					if(!mysql_num_rows($check)) {
						print("\t<tr>\n");
							print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; width: 5%; padding: 5px;\">\n");
								print("<input type=\"checkbox\" name=\"results[".$file."]\" id=\"num\" value=\"1\" />\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc1\" style=\"text-align: center; border-left: none; white-space: nowrap; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<img src=\"../".$_POST['add_many_smilies']['filepath']."/".$file."\" alt=\"".$file."\" style=\"border: none;\" /> <br /> <strong>".$file."</strong>\n");
							print("\t\t</td>\n");

							// get title...
							$file_title = explode(".",$file);

							print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<input type=\"text\" class=\"text\" name=\"title[".$file."]\" value=\"".$file_title[0]."\" />\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<input type=\"text\" class=\"text\" name=\"replace[".$file."]\" value=\"\" />\n");
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

			print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"5\">");
			print("<input type=\"hidden\" name=\"multiple_smilies[filepath_e]\" value=\"".$_POST['add_many_smilies']['filepath']."\" />\n");
			print("<button type=\"submit\" ".$submitbg.">Add Smilies</button>");
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

	// insert multiple smilies!
	if($_POST['multiple_smilies']['set_form']) {
		if(!is_array($_POST['results'])) {
			construct_error("Sorry, you did not select any smilies to add. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// loop through results array
		foreach($_POST['results'] as $key => $value) {
			// make sure value is 1!
			if($value) {
				// get FULL filepath
				$fullFilepath = $_POST['multiple_smilies']['filepath_e']."/".$key;

				// form query
				$query = "INSERT INTO smilies (title,filepath,display_order,replacement) VALUES ('".addslashes($_POST['title'][$key])."','".$fullFilepath."','".addslashes($_POST['displayOrder'][$key])."','".addslashes($_POST['replace'][$key])."')";

				//print($query);

				// run query
				query($query);

				// redirect to thankyou page...
				redirect("thankyou.php?message=Thank you for adding smilies. You will now be redirected to the Smiley Manager.&uri=smilies.php?do=manager");
			}
		}
	}


	// do header
	admin_header("wtcBB Admin Panel - Smilies - Add Smiley");

	construct_title("Add Smiley");

	construct_table("options","add_smiley","smiley_submit",1);

	construct_header("Add Smiley",2);

	construct_text(1,"Title","","add_smiley","title");

	construct_text(2,"File Path","","add_smiley","filepath","http://");

	construct_text(1,"Replacement","","add_smiley","replacement");

	construct_text(2,"Display Order","","add_smiley","display_order","1",1);

	construct_footer(2,"smiley_submit");

	construct_table_END(1);


	print("<br /><br />");


	construct_table("options","add_many_smilies","smiley2_submit",1);

	construct_header("Add Multiple Smilies",2);

	construct_text(1,"File Path","","add_many_smilies","filepath","images/smilies",1);

	construct_footer(2,"smiley2_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>