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
// | $RCSfile: compose.draft.php,v $ - $Revision: 1.12 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'redirect_draftsaved,redirect_draftdeleted';
require_once('./global.php');

// ############################################################################
$draft = getinfo('draft', $draftid);

// Is it already a draft or are we to make it one?
if ($draft['dateline'] == 0 and empty($updatedraft)) {
	$DB_site->query("
		DELETE FROM hive_draft
		WHERE draftid = $draftid
	");
	rebuild_drafts_cache();

	eval(makeredirect("redirect_draftdeleted", INDEX_FILE));
} else {
	if ($data['html']) {
		$data['message'] = $message;
	}

	$DB_site->query("
		UPDATE hive_draft
		SET dateline = 0, data = '".addslashes(base64_encode(serialize($data)))."'
		WHERE draftid = $draftid
	");
	rebuild_drafts_cache();

	eval(makeredirect("redirect_draftsaved", INDEX_FILE));
}

?>