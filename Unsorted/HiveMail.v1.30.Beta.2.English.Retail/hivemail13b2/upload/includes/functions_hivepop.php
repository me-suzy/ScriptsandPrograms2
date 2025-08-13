<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_hivepop.php,v $ - $Revision: 1.6 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Tries to the save the message files 5 times, if someone else is trying to save
function hivepop_savemsg($message, $datafile, $userid = 0) {
	$msgs = hivepop_listmsgs($userid);
	$lastmsg = intval(array_pop($msgs));
	for ($i = 1; $i < 5; $i++) {
		$filepath = hivepop_folder($userid).'/'.($lastmsg + $i);
		if (writetofile("$filepath.mail", $message)) {
			if (empty($datafile) or writetofile("$filepath.mail", $datafile)) {
				return true;
			} else {
				@unlink($mailpath);
			}
		}
	}
	return false;
}

// ############################################################################
// Deletes the message and data files from the mailbox
function hivepop_deletemsg($msgnum, $userid = 0) {
	intme($msgnum);
	$mailpath = hivepop_folder($userid).'/'.$msgnum;

	if (@unlink($mailpath.'.data') and @unlink($mailpath.'.mail')) {
		return true;
	}
	return false;
}

// ############################################################################
// Returns list of messages in the user's mailbox
function hivepop_listmsgs($userid = 0, $overridecache = false) {
	global $hiveuser;
	static $_hivepop_scan_cache = array();

	if (intme($userid) == 0) {
		$userid = $hiveuser['userid'];
	}

	if ($overridecache or !isset($_hivepop_scan_cache[$userid])) {
		$_hivepop_scan_cache[$userid] = scandir(hivepop_folder($userid));
	}
	return $_hivepop_scan_cache[$userid];
}
	
// ############################################################################
// Return the path to the user's mailbox
function hivepop_folder($userid = 0) {
	global $hiveuser;

	if (intme($userid) == 0) {
		$userid = $hiveuser['userid'];
	}
	return getop('hivepop_path', true).'/'.$userid;
}

?>