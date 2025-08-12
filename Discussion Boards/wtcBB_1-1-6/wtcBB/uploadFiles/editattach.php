<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############# //FRONT END -EDIT ATTACHMENTS\\ ############# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./includes/functions_messages.php");
include("./global.php");

// get message stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// i query for threadinfo ($threadStuff) in the global.php for 
// style purposes ;)

// only do these checks if it isn't PM
if(!$_GET['pm']) {
	if(!$forumid) {
		$forumid = $_GET['f'];
	}

	// if no hash
	// if there's no rows...
	if(!$_GET['p'] AND !$_GET['hash']) {
		doError(
			"Sorry, there was an error processing your attachments.",
			"Error Editing Attachments",
			"No Hash"
		);
	}

	// if there's no rows...
	if(!mysql_num_rows($threadStuff) AND $_GET['t']) {
		doError(
			"There is no thread found in the database with the corresponding link.",
			"Error Editing Attachments",
			"Thread Doesn't Exist"
		);
	}

	// get moderator perms...
	$moderator = hasModPermissions($forumid);

	// make sure forum is active...
	if(!is_array($foruminfo[$forumid]) OR !isActive($forumid) OR $foruminfo[$forumid]['is_category'] OR !$foruminfo[$forumid]['is_open'] OR $foruminfo[$forumid]['link_redirect']) {
		doError(
			"The forum that this thread is located in, is not active, is a category, is not open, or not a valid forum.",
			"Error Editing Attachments",
			"Forum is not active or is a category"
		);
	}

	// make sure thread isn't deleted...
	// we can't let anyone do this, it messes up "last post" and such.. -_-
	if($threadinfo['deleted_thread'] AND $_GET['t']) {
		doError(
			"You may not edit attachments in a deleted thread.",
			"Error Editing Attachments",
			"Thread is deleted"
		);
	}

	// this next one is going to check permissions...
	if(!$userinfo['userid'] OR !$bboptions['allow_attachments'] OR !$bboptions['attachments_per_post'] OR !$forumPerms[$forumid]['can_upload_attachments'] OR $userinfo['is_coppa'] OR !$forumPerms[$forumid]['can_view_board'] OR ($threadinfo['closed'] AND !$moderator) OR ($userinfo['userid'] != $threadinfo['thread_starter'] AND !$forumPerms[$forumid]['can_view_threads'] AND $_GET['t'])) {
		doError(
			"perms",
			"Error Editing Attachments"
		);
	}
}

else {
	// this next one is going to check permissions...
	if(!$userinfo['userid'] OR !$bboptions['allow_attachments'] OR !$bboptions['attachments_per_post'] OR $userinfo['is_coppa']) {
		doError(
			"perms",
			"Error Editing Attachments"
		);
	}
}

// get current attachments
// if we have postid we're editing, different query!
// take PM into consideration too!
if(!$_GET['pm']) {
	if($_GET['p']) {
		$currAttachs = query("SELECT * FROM attachments WHERE attachmentthread = '".addslashes($_GET['t'])."' AND attachmentpost = '".$_GET['p']."' ORDER BY attachmentname");
		$deleteURI = "editattach.php?t=".$_GET['t']."&amp;p=".$_GET['p'];
	} else {
		$currAttachs = query("SELECT * FROM attachments WHERE attachmenthash = '".addslashes($_GET['hash'])."' ORDER BY attachmentname");

		if($_GET['f']) {
			$deleteURI = "editattach.php?f=".htmlspecialchars($_GET['f'])."&amp;hash=".htmlspecialchars($_GET['hash']);
		} else {
			$deleteURI = "editattach.php?t=".htmlspecialchars($_GET['t'])."&amp;hash=".htmlspecialchars($_GET['hash']);
		}
	}
}

// PM!
else {
	$currAttachs = query("SELECT * FROM attachments WHERE attachmenthash = '".addlashes($_GET['hash'])."' AND isPM = 1");
	$deleteURI = "editattach.php?pm=yes&amp;hash=".addslashes($_GET['hash']);
}

// delete?
if($_GET['delete']) {
	// select
	$selectAttach = query("SELECT * FROM attachments WHERE attachmentid = '".addslashes($_GET['delete'])."' LIMIT 1");

	if(!mysql_num_rows($selectAttach)) {
		doError(
			"Sorry, that attachment does not exist.",
			"Error Deleting Attachments",
			"Attachment Doesn't Exist"
		);
	}

	$theAttachmentInfo = mysql_fetch_array($selectAttach);

	query("DELETE FROM attachments WHERE attachmentid = '".addslashes($_GET['delete'])."' LIMIT 1");

	// if file system, unlink
	if(!$bboptions['general_attachments']) {
		@unlink($theAttachmentInfo['attachmenturl']);
	}

	// refresh
	if($_SERVER['QUERY_STRING']) {
		$_QUERY_STRING_2 = preg_replace("|&delete=[0-9]*|","",$_SERVER['QUERY_STRING']);
		header("Location: ".$_SERVER['PHP_SELF']."?".$_QUERY_STRING_2);
	} else {
		header("Location: ".$_SERVER['PHP_SELF']);
	}
}

// upload attachments?
if($_POST['goSubmit']) {
	$failed = false;

	// set some vars
	$name = $_FILES['fupload']['name'];
	$tmp_name = $_FILES['fupload']['tmp_name'];
	$mime = $_FILES['fupload']['type'];
	$size = $_FILES['fupload']['size'];

	// get extension...
	$getExt = split('\.', $name);
	$fileExt = $getExt[1];

	// loop through extensions, to see if we have a valid one..
	$validExt = false;

	foreach($attachtext as $storageid => $arr) {
		if($arr['mime_type'] == $mime AND $arr['ext'] == $fileExt) {
			$validExt = $storageid;

			// break out.. we don't need this anymore..
			break;
		}
	}

	// if it's still false, then invalid extension.. but keep moving on!
	if(!$validExt) {
		$theError = printStandardError("error_standard","Sorry, the attachment was not recognized as a valid attachment.",0);
		$failed = true;
		@unlink($tmp_name);
	}

	// too many attachments
	else if(mysql_num_rows($currAttachs) >= $bboptions['attachments_per_post'] AND $bboptions['attachments_per_post'] != 0) {
		$theError = printstandardError("error_standard","Sorry, you may only upload a maximum of ".$bboptions['attachments_per_post']." attachments per post.",0);
		$failed = true;
		@unlink($tmp_name);
	}

	else {
		// is it uploaded?
		if(is_uploaded_file($tmp_name)) {
			// if it's an image, we can check the width and height...
			if(eregi("image",$mime) AND !$failed) {
				$theSizeArr = getimagesize($tmp_name);

				if(($attachtext[$validExt]['max_width'] AND $theSizeArr[0] > $attachtext[$validExt]['max_width']) OR ($attachtext[$validExt]['max_height'] AND $theSizeArr[1] > $attachtext[$validExt]['max_height'])) {
					$theError = printStandardError("error_standard","The pixel dimensions of your attachment do not meet the restrictions of this message board.",0);
					$failed = true;
					@unlink($tmp_name);
				}
			}

			// check the file size...
			if($attachtext[$validExt]['max_filesize'] AND $size > $attachtext[$validExt]['max_filesize'] AND !$failed) {
				$theError = printStandardError("error_standard","The file size of your attachment does not meet the restrictions of this message board.",0);
				$failed = true;
				@unlink($tmp_name);
			}

			if(!$failed) {
				// finally, add attachment info into the DB...
				if($_GET['p']) {
					query("INSERT INTO attachments (size,attachmenturl,attachmentname,attachmentthread,attachmentpost,userid,mime) VALUES ('".$size."','attachments/".$name."','".$name."','".$_GET['t']."','".$_GET['p']."','".$userinfo['userid']."','".$mime."')");

					$insertID = mysql_insert_id();
					$theExt = $attachtext[$validExt]['ext'];

					// we need to do this because we didn't have the ID before...
					if($bboptions['general_attachments']) {
						query("UPDATE attachments SET frontURI = CONCAT('attachment.php?id=',attachmentid) , attachmenturl = CONCAT('attachments/',attachmentid,'.wtcbb') , contents = '".addslashes(file_get_contents($tmp_name))."' WHERE attachmentid = '".$insertID."'");
					} else {
						query("UPDATE attachments SET frontURI = CONCAT('attachment.php?id=',attachmentid) , attachmenturl = CONCAT('attachments/',attachmentid,'.wtcbb') , contents = NULL WHERE attachmentid = '".$insertID."'");
					}
				}

				else {
					// add isPM field if appropriate
					if($_GET['pm']) {
						$pmField = ",isPM";
						$pmValue = ",1";
					} else {
						$pmField = "";
						$pmValue = "";
					}

					query("INSERT INTO attachments (size,attachmenturl,attachmentname,userid,mime,attachmenthash".$pmField.") VALUES ('".$size."','attachments/".$name."','".$name."','".$userinfo['userid']."','".$mime."','".addslashes($_GET['hash'])."'".$pmValue.")");

					$insertID = mysql_insert_id();
					$theExt = $attachtext[$validExt]['ext'];

					// we need to do this because we didn't have the ID before...
					if($bboptions['general_attachments']) {
						query("UPDATE attachments SET frontURI = CONCAT('attachment.php?id=',attachmentid) , attachmenturl = CONCAT('attachments/',attachmentid,'.wtcbb') , contents = '".addslashes(file_get_contents($tmp_name))."' WHERE attachmentid = '".$insertID."'");
					} else {
						query("UPDATE attachments SET frontURI = CONCAT('attachment.php?id=',attachmentid) , attachmenturl = CONCAT('attachments/',attachmentid,'.wtcbb') , contents = NULL WHERE attachmentid = '".$insertID."'");
					}
				}

				// should we put the file in the attachments folder?
				if(!$bboptions['general_attachments']) {
					$checking_upload = @move_uploaded_file($tmp_name,"attachments/".$insertID.".wtcbb");
				}

				@unlink($tmp_name);

				// refresh
				if($_SERVER['QUERY_STRING']) {
					header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
				} else {
					header("Location: ".$_SERVER['PHP_SELF']);
				}
			}
		}
	}
}

// get the upload form
if(mysql_num_rows($currAttachs) < $bboptions['attachments_per_post'] OR !$bboptions['attachments_per_post']) {
	$validExts = "";
	foreach($attachtext as $storageid => $arr) {
		$validExts .= ", ".$arr['ext'];
	}

	$validExts = preg_replace("|^,|","",$validExts);

	eval("\$uploadForm = \"".getTemplate("editAttach_uploadForm")."\";");
} else {
	$uploadForm = "";
}

// get the current attachments
if(mysql_num_rows($currAttachs)) {
	$attachmentBits = "";

	while($attaching = mysql_fetch_array($currAttachs)) {
		eval("\$attachmentBits .= \"".getTemplate("editAttach_bits")."\";");
	}

	if(!empty($attachmentBits)) {
		eval("\$attachmentHeader = \"".getTemplate("editAttach_header")."\";");
	}
} else {
	$attachmentHeader = "";
	$attachmentBits = "";
}

// intialize templates
eval("\$editAttach = \"".getTemplate("editAttach")."\";");

printTemplate($editAttach);

// wrrrrrrrap it up!
wrapUp();

?>