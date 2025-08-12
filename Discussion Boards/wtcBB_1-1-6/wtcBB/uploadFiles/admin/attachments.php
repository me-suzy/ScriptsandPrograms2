<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //ADMIN PANEL ATTACHMENTS\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Attachments";
$permissions = "attachments";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

// delete ext
if($_GET['do'] == "delete") {
	// make sure it's a proper id...
	$getExtension = query("SELECT COUNT(*) AS good FROM attachment_storage WHERE storageid = '".$_GET['id']."'", 1);

	// uh oh...
	if(!$getExtension['good']) {
		construct_error("No attachment extension with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}
	
	// construct confirm...
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete post icon query...
			query("DELETE FROM attachment_storage WHERE storageid = '".$_GET['id']."'");

			redirect("thankyou.php?message=You have successfully deleted the attachment extension. You will now be redirected back to the \"Attachment Extensions Manager\".&uri=attachments.php?do=ext");
		}

		// no...
		else {
			redirect("attachments.php?do=ext");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete this attachment extension? It cannot be undone!");
}
	

// do extension edit..
if($_GET['do'] == "edit") {
	// make sure it's a proper id...
	$getExtension = query("SELECT * FROM attachment_storage WHERE storageid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(mysql_num_rows($getExtension) == 0) {
		construct_error("No attachment extension with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$extinfo = mysql_fetch_array($getExtension);

	// update db...
	if($_POST['editExt']['set_form']) {
		// run query
		$update = query("UPDATE attachment_storage SET ext = '".addslashes($_POST['editExt']['ext'])."' , max_filesize = '".addslashes($_POST['editExt']['max_filesize'])."' , max_width = '".addslashes($_POST['editExt']['max_width'])."' , max_height = '".addslashes($_POST['editExt']['max_height'])."' , enabled = '".addslashes($_POST['editExt']['enabled'])."' , mime_type = '".addslashes($_POST['editExt']['mime_type'])."' WHERE storageid = '".$_GET['id']."'");

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for editing the <em>".$extinfo['ext']."</em> extension. You will now be redirected to the Attachment Extensions.&uri=attachments.php?do=ext");
	}

	// do header
	admin_header("wtcBB Admin Panel - Attachments - Edit Extension");

	construct_title("Edit Extension");

	construct_table("options","editExt","editExt_submit",1);

	construct_header("Edit Extension",2);

	construct_text(1,"Extension","","editExt","ext",$extinfo['ext']);

	construct_text(2,"Max Filesize","In bytes <br />Set to <strong>0</strong> to not have a limit.","editExt","max_filesize",$extinfo['max_filesize']);

	construct_text(1,"Max Width","Set to <strong>0</strong> to not have a limit.","editExt","max_width",$extinfo['max_width']);

	construct_text(2,"Max Height","Set to <strong>0</strong> to not have a limit.","editExt","max_height",$extinfo['max_height']);

	construct_input(1,"Enabled","If this is disabled, members will not be allowed to upload this attachment type.","editExt","enabled",0,0,$extinfo);

	construct_textarea(2,"Mime Type","This is the mime type of the attachment. (ie: <strong>image/gif</strong>)","editExt","mime_type",$extinfo['mime_type'],1);

	construct_footer(2,"upload_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// do attachment extensions
if($_GET['do'] == "ext") {
	if($_POST['placement2']['set_form']) {
		// if options are same, print error
		if($bboptions['general_attachments'] == $_POST['placement2']['general_attachments']) {
			construct_error("You already have this placement, make sure to chose a placement for attachments that isn't currently instated. <a href=\"javascript:history.back();\">Go back.</a>");
			exit;
		}

		// store them in the file system
		if($_POST['placement2']['general_attachments'] == 0) {
			// query for attachments
			$allAttachments = query("SELECT * FROM attachments ORDER BY attachmentname");

			// if rows, loop
			if(mysql_num_rows($allAttachments) > 0) {
				while($attach = mysql_fetch_array($allAttachments)) {
					$theFile = "../".$attach['attachmenturl'];
					$opener = @fopen($theFile,"wb");
					@fwrite($opener, $attach['contents']);
					@fclose($opener);
				}

				// update URI's.. and get rid of contents
				query("UPDATE attachments SET contents = null");
			}
		}

		// put into db
		else {
			// basically, just delete everything in attachments folder...
			// as the attachments in the DB are ready
			if($handle = @opendir("../attachments")) {
				// get rid of "." and ".."
				@readdir($handle);
				@readdir($handle);

				while($file = @readdir($handle)) { 
					// update URI's.. and contents
					query("UPDATE attachments SET contents = '".addslashes(file_get_contents("../attachments/".$file))."' WHERE attachmenturl = 'attachments/".$file."'");

					// attempt to delete
					@unlink("../attachments/".$file);
				}

				@closedir($handle); 
			}
		}

		// update to new
		query("UPDATE wtcBBoptions SET general_attachments = '".$_POST['placement2']['general_attachments']."'");

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have successfully changed the placement of attachments. You will now be redirected to the Attachment Extensions.&uri=attachments.php?do=ext");
	}
	

	// do header
	admin_header("wtcBB Admin Panel - Attachment Extensions");

	construct_title("Attachment Extensions");

	construct_table("options","placement2","attach_submit2",1);

	construct_header("Attachment Placement",2);

	construct_input(1,"Attachments in database?","Disabling this, will put all attachments into the directory <strong>attachments</strong>. Make sure that directory is chmodded appropriate before proceeding. If you enable this, all files in that directory will be deleted, and transferred into the database.","placement2","general_attachments",1,0,$bboptions);

	construct_footer(2,"attach_submit2");

	construct_table_END(1);

	// make sure there are attachments...
	$getExtensions = query("SELECT * FROM attachment_storage ORDER BY ext");

	// uh oh...
	if(mysql_num_rows($getExtensions) == 0) {
		construct_error("No attachment extensions exist. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	print("\n\n<br /><br />\n\n");

	construct_table("options","attach","attach_submit");

	construct_header("Attachment Extensions",6);

	print("\n\n\t<tr>\n");

			print("\t\t<td class=\"cat\">\n");
			print("\t\t\tExtension\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tMax Filesize <span class=\"small\">(bytes)</span>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tMax Height\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tMax Width\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tEnabled\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tOptions\n");
			print("\t\t</td>\n\n");

		print("\t</tr>\n\n");

		while($extinfo = mysql_fetch_array($getExtensions)) {
			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<strong>".$extinfo['ext']."</strong>\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$extinfo['max_filesize']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$extinfo['max_height']."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$extinfo['max_width']."\n");
				print("\t\t</td>\n");

				// get enabled text...
				if($extinfo['enabled'] == 1) {
					$enabledText = "Yes";
				} else {
					$enabledText = "No";
				}

				print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t".$enabledText."\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<form action=\"\" style=\"margin: 0px; padding: 0px;\" method=\"post\">\n");
						print("\t\t\t<button type=\"button\" onclick=\"location.href='attachments.php?do=edit&id=".$extinfo['storageid']."';\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button> <button type=\"button\" onclick=\"location.href='attachments.php?do=delete&id=".$extinfo['storageid']."'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Delete</button>\n");
					print("\t\t\t</form>\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");
		}

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"6\"><button type=\"button\" onclick=\"location.href='attachments.php?do=add_ext';\" ".$submitbg.">Add Extension</button></td></tr>\n");

	construct_table_END();

	// do footer
	admin_footer();
}

// do add attachment extension
if($_GET['do'] == "add_ext") {
	// put everythin into the DB
	if($_POST['addExt']['set_form']) {
		// run the insert query...
		$insert = query("INSERT INTO attachment_storage (ext,max_filesize,max_width,max_height,enabled,mime_type) VALUES ('".addslashes($_POST['addExt']['ext'])."','".addslashes($_POST['addExt']['max_filesize'])."','".addslashes($_POST['addExt']['max_width'])."','".addslashes($_POST['addExt']['max_height'])."','".$_POST['addExt']['enabled']."','".addslashes($_POST['addExt']['mime_type'])."')");

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for adding an extension. You will now be redirected to the Attachment Extensions.&uri=attachments.php?do=ext");
	}

	// do header
	admin_header("wtcBB Admin Panel - Attachments - Add Extension");

	construct_title("Add Extension");

	construct_table("options","addExt","addExt_submit",1);

	construct_header("Add Extension",2);

	construct_text(1,"Extension","","addExt","ext");

	construct_text(2,"Max Filesize","In bytes. <br />Set to <strong>0</strong> to not have a limit.","addExt","max_filesize",100000);

	construct_text(1,"Max Width","Set to <strong>0</strong> to not have a limit.","addExt","max_width");

	construct_text(2,"Max Height","Set to <strong>0</strong> to not have a limit.","addExt","max_height");

	construct_input(1,"Enabled","If this is disabled, members will not be allowed to upload this attachment type.","addExt","enabled",0,1);

	construct_textarea(2,"Mime Type","This is the mime type of the attachment. (ie: <strong>image/gif</strong>)","addExt","mime_type","",1);

	construct_footer(2,"upload_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

?>