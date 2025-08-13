<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: compose.draft.php,v $
// | $Date: 2002/10/30 14:39:47 $
// | $Revision: 1.11 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_draftsaved,redirect_draftdeleted';
require_once('./global.php');

// ############################################################################
$draft = getinfo('draft', $draftid);

// Is it already a draft or are we to make it one?
if ($draft['dateline'] == 0 and !empty($draft)) {
	$DB_site->query("
		DELETE FROM draft
		WHERE draftid = $draftid
	");

	eval(makeredirect("redirect_draftdeleted", "index.php"));
} else {
	if ($data['html']) {
		$data['message'] = $message;
	}

	$DB_site->query("
		UPDATE draft
		SET dateline = 0, data = '".addslashes(serialize($data))."'
		WHERE draftid = $draftid
	");

	eval(makeredirect("redirect_draftsaved", "index.php"));
}

?>