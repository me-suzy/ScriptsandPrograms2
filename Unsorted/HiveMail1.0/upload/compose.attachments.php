<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: compose.attachments.php,v $
// | $Date: 2002/11/11 21:51:41 $
// | $Revision: 1.14 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('LOAD_MINI_TEMPLATES', true);
$templatesused = 'compose_manageattach_attachbit,compose_manageattach';
require_once('./global.php');

// ############################################################################
$draft = getinfo('draft', $draftid);
$data = unserialize($draft['data']);
$updatedata = false;
$usermessage = '';

// Delete attachments if need be
if (is_array($data['attach'])) {
	foreach ($data['attach'] as $number => $attachdata) {
		$deletevar = 'delete'.$number;
		if (!empty($$deletevar)) {
			unset($data['attach']["$number"]);
			$updatedata = true;
			$usermessage = 'The attachment was <b>successfully removed</b>.';
		}
	}
}

// And upload new attachments
if (!empty($upload)) {
	$usermessage = 'The attachment was <b>not added</b> due to an error. Please try again.';

	$_FILES['attachment']['name'] = strtolower($_FILES['attachment']['name']);
	$extension = getextension($_FILES['attachment']['name']);

	if (is_uploaded_file($_FILES['attachment']['tmp_name'])) {
		if ($safeupload) {
			$path = $tmppath.'/'.$_FILES['attachment']['name'];
			move_uploaded_file($_FILES['attachment']['tmp_name'], $path);
			$_FILES['attachment']['tmp_name'] = $path;
		}
	
		$filesize = filesize($_FILES['attachment']['tmp_name']);
		if ($filesize == $_FILES['attachment']['size'] and strstr($_FILES['attachment']['tmp_name'], '..') == '') {
			$filenum = fopen($_FILES['attachment']['tmp_name'], 'rb');
			$filestuff = fread($filenum, $filesize);
			fclose($filenum);
			unlink($_FILES['attachment']['tmp_name']);

			// Make sure the attachment doesn't already exist
			$dupe = false;
			if (is_array($data['attach'])) {
				foreach ($data['attach'] as $number => $attachdata) {
					if ($attachdata['filename'] == $_FILES['attachment']['name'] and $attachdata['data'] == $filestuff) {
						$usermessage = 'The attachment was <b>not added</b> as it is already attached to this message.';
						$dupe = true;
						break;
					}
				}
			}

			// And add its data to the array...
			if (!$dupe) {
				$data['attach'][] = array(
					'filename' => $_FILES['attachment']['name'],
					'type' => $_FILES['attachment']['type'],
					'size' => $filesize,		// Let's put this here instead of calculating it every time
					'data' => $filestuff
				);
				$updatedata = true;
				$usermessage = 'The attachment was <b>successfully added</b>.';
			}
		}
	}
}

// Update the dateline so the data won't expire
// (But only if it's not a draft)
// And also update the data if it was altered
$DB_site->query("
	UPDATE draft
	SET draftid = draftid".
	iif($draft['dateline'] > 0, ",dateline = ".TIMENOW).
	iif($updatedata, ",data = '".addslashes(serialize($data))."'")."
	WHERE draftid = $draftid
");

// Show the attachment list
if (is_array($data['attach']) and sizeof($data['attach'])>0) {
	$attachlist = '';
	foreach ($data['attach'] as $number => $attachdata) {
		eval(makeeval('attachlist', 'compose_manageattach_attachbit', 1));
	}
} else {
	$attachlist = '	No attachments.';
}

eval(makeeval('echo', 'compose_manageattach'));

?>