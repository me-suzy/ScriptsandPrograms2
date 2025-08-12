<?php
// We need some common functions
require("includes/functions.inc.php");

// Get some variables
$phpvoter = $HTTP_POST_VARS['phpvoter'];
$action = $HTTP_GET_VARS['action'];
if (!$action || $action == "") {
	$action = $HTTP_POST_VARS['action'];
}
$voteid = $HTTP_GET_VARS['voteid'];
if (!$voteid || $voteid == "") {
	$voteid = $HTTP_POST_VARS['voteid'];
}

// ************************************************************************

// OK, now let's start the program and see what to do.
if ($action == "list") {
	showheader();
	print listvotes();
} elseif ($action == "shortlist") {
	print listvotes();
} elseif ($action == "vote") {
	$unique = (($config['unique'] == "yes") || ($config['unique'] == 1)) ? true : false;
	if (($unique && !hasalreadyvoted()) || !$unique) {
		saveresult($voteid, $phpvoter, $config['unique']);
	} else {
		$linkvote = linktoactivevote();
		if ($linkvote != "") {
			showheader();
			print ("{$template['pre_error_header']}{$lang['sorry']}{$template['post_error_header']}\n{$lang['alreadyvoted']}\n");
			print " {$template['pre_error_string']}<a href=\"$linkvote\">{$lang['viewresults']}</a>{$template['post_error_string']}\n";
		} else {
			show_error($lang['no_votes_in_db']);
		}
	}
} elseif ($action == "show") {
	showheader();
	showvote($voteid);
} elseif ($action == "viewvote") {
	print letsvote("active");
} elseif ($action == "save") {
	savevote();
} else {
	// Show the question if the user hasn't voted else the results is shown.
	if (hasalreadyvoted()) {
		showheader();
		showvote("active", 1);
	} else {
		showheader();
		print $lang['info'];
		print letsvote("active", "", "showcomment");
	}
}

if ($config['debug']) {
	print $strError;
}

if ($action != "viewvote" && $action != "shortlist") {
        $tmplarr['oldquestions'] = listvotes();
        print getTemplate('footer', $tmplarr);
}
?>
