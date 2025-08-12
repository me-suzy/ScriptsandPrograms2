<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //FRONT END - ANNOUNCEMENTS\\ ############# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./includes/functions_forums.php");
include("./includes/functions_messages.php");
include("./includes/functions_bbcode.php");
include("./global.php");

// get thread display stylesheet
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_threaddisplay")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_forumhome")."\";");
eval("\$stylesheets_sub .= \"".getTemplate("stylesheets_messages")."\";");

// if no css file.. get internetl block!
if($bboptions['css_in_file'] == 0) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// if ID show global and forum-specific
if($_GET['f']) {
	$forumid = $_GET['f'];

	check_wtcBB_forumPassword($forumid);

	// select from DB
	$getAnnouncement = query("SELECT * FROM announcements LEFT JOIN user_info ON user_info.userid = announcements.userid WHERE start_date < '".time()."' AND end_date > '".time()."' AND forum = '".$forumid."' OR forum = -1 ORDER BY announcements.date_addedUpdated DESC");

	// if no announcements exist.. spit out error
	if(!mysql_num_rows($getAnnouncement)) {
		doError(
			"There are no announcements existing in the database with the given criteria.",
			"Error Viewing Announcements",
			"No Announcements exist."
		);
	}

	// make sure forum is active...
	if(!isActive($forumid) OR $foruminfo[$forumid]['is_category'] ) {
		doError(
			"The forum that this announcement is located in, is not active or is a category.",
			"Error Viewing Announcements",
			"Forum is not active or is a category."
		);
	}

	// check permissions...
	if(!$forumPerms[$forumid]['can_view_board']) {
		doError(
			"perms",
			"Error Viewing Forum"
		);
	}

	// create nav bar array
	$navbarArr = getForumNav($forumid);

	// reverse it... if array exists
	if(is_array($navbarArr)) {
		$navbarArr = array_reverse($navbarArr);
	}

	// add to it...
	$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
	$navbarArr['Announcements'] = "#";

	$navbarText = getNavbarLinks($navbarArr);

	// deal with sessions
	$sessionInclude = doSessions("Viewing Announcements for ".$foruminfo[$forumid]['forum_name'],"Viewing Announcements");
	include("./includes/sessions.php");

	// forum name var..
	$forumName = $foruminfo[$forumid]['forum_name'];

	// now loop through announcements
	while($announceinfo = mysql_fetch_array($getAnnouncement)) {
		$startDate = processDate($bboptions['date_formatted'],$announceinfo['start_date']);
		$endDate = processDate($bboptions['date_formatted'],$announceinfo['end_date']);
		$updatedDate = processDate($bboptions['date_formatted'],$announceinfo['date_addedUpdated']);
		$updatedTime = processDate($bboptions['date_time_format'],$announceinfo['date_addedUpdated']);
		$registered = processDate($bboptions['date_register_format'],$announceinfo['date_joined']);

		unset($quickinfo);

		// get username
		$theUsername = getHTMLUsername($announceinfo);

		// get custom title
		$theCT = getCustomTitle($announceinfo);
		
		// avatar
		if($announceinfo['avatar_url'] != "none") {
			eval("\$theAV = \"".getTemplate("announcements_avatar")."\";");
		} else {
			$theAV = "";
		}

		// posts
		// get posts per day...
		$postsPerDay = substr($announceinfo['posts'] / ((time() - $announceinfo['date_joined']) / 86400),0,6);
		
		$subTitle = "Posts:";
		$subValue = $announceinfo['posts']." (".$postsPerDay." Per Day)";
		if($announceinfo['userid'] != 0) eval("\$quickinfo .= \"".getTemplate("announcements_quickinfo")."\";");

		// get location
		if(!empty($announceinfo['locationUser'])) {
			$subTitle = "Location:";
			$subValue = $announceinfo['locationUser'];
			eval("\$quickinfo .= \"".getTemplate("announcements_quickinfo")."\";");
		} else {
			$location = "";
		}

		// join date...
		$subTitle = "Join Date:";
		$subValue = $registered;
		if($announceinfo['userid'] != 0) eval("\$quickinfo .= \"".getTemplate("announcements_quickinfo")."\";");

		$announceinfo['message'] = parseMessage($announceinfo['message'],$announceinfo['parse_bbcode'],$announceinfo['parse_smilies'],1,(!$userinfo['allow_html']),true,true,$announceinfo['username']);

		// now we're going to grab the postLinks...
		// PM links...
		if($announceinfo['use_pm'] == 1 AND $announceinfo['userid'] != 0 AND $bboptions['personal_enabled'] == 1) {
			eval("\$pmLink = \"".getTemplate("announcements_postlinks_pm")."\";");
		}

		if($announceinfo['receive_emails'] == 1 AND $bboptions['enable_user_email'] == 1 AND $announceinfo['userid'] != 0) {
			eval("\$emailLink = \"".getTemplate("announcements_postlinks_email")."\";");
		}

		if(!empty($announceinfo['homepage']) AND $announceinfo['userid'] != 0) {
			eval("\$homepageLink = \"".getTemplate("announcements_postlinks_homepage")."\";");
		}

		eval("\$profileLink = \"".getTemplate("announcements_postlinks_profile")."\";");

		// get the online status...
		eval("\$onlineOffline = \"".getTemplate(fetchOnlineStatus($announceinfo['userid']))."\";");

		// i guess we have to do individual updates here..
		query("UPDATE announcements SET views = views + 1 WHERE announcementid = '".$announceinfo['announcementid']."'");

		// finally.. fetch the template...
		eval("\$announcebits .= \"".getTemplate("announcements_bit")."\";");
	}

	// now get the announcement template
	eval("\$announcement = \"".getTemplate("announcements")."\";");

	// intialize templates
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit it out
	printTemplate($header);
	printTemplate($announcement);
	printTemplate($footer);
}

// just put up an invalid ID error...
else {
	// deal with sessions
	$sessionInclude = doSessions("Error Viewing Announcements <img src=\"".$colors['images_folder']."/error.gif\" alt=\"Error\" />","No Announcements exist.");
	include("./includes/sessions.php");

	// create nav bar array
	$navbarArr = Array(
		"Error Viewing Announcements " => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	// intialize templates
	eval("\$header = \"".getTemplate("header")."\";");
	eval("\$footer = \"".getTemplate("footer")."\";");

	// spit out content
	printTemplate($header);
	printStandardError("error_standard","There are no announcements existing in the database with the given criteria.");
	printTemplate($footer);

	exit;
}

?>