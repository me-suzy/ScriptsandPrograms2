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
// | $RCSfile: read.rename.php,v $ - $Revision: 1.3 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'read_renamesubject,redirect_subjectrenamed';
require_once('./global.php');
require_once('./includes/functions_mime.php');
define('LOAD_MINI_TEMPLATES', true);

// ############################################################################
// Verify message
$mail = getinfo('message', $messageid);

// ############################################################################
// Show the rename screen
if ($_POST['cmd'] != 'update') {
	decode_subject($mail['subject']);

	// That was quick
	eval(makeeval('echo', 'read_renamesubject'));
}

// ############################################################################
// Update the message
if ($_POST['cmd'] == 'update') {
	//get_source($mail);
	encode_subject($subject);

	// If subject was changed, update the stuff
	if ($subject != $mail['subject']) {
		$DB_site->query("
			UPDATE hive_message
			SET subject = '".addslashes($subject)."'
			WHERE messageid = $messageid AND userid = $hiveuser[userid]
		");
	}

	// Close window
	?><script language="JavaScript" type="text/javascript">
	<!--
	window.close();
	//-->
	</script><?php
	exit;
}

?>