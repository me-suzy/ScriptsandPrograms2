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
// | $RCSfile: compose.attachments.php,v $ - $Revision: 1.14 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('LOAD_MINI_TEMPLATES', true);
$templatesused = 'compose_manageattach_attachbit,compose_manageattach';
require_once('./global.php');

// ############################################################################
$draft = getinfo('draft', $draftid);
$data = unserialize_base64($draft['data']);
unset($draft['data']);
$updatedata = false;
$usermessage = '';

// Delete attachments if need be
if (is_array($data['attach'])) {
	reset($data['attach']);
	while (list($number, ) = each($data['attach'])) {
		$deletevar = 'delete'.$number;
		if (!empty($$deletevar)) {
			unset($data['attach']["$number"]);
			$updatedata = true;
			eval(makeeval('usermessage', 'compose_manageattach_removed'));
		}
	}
}

// Get current attachments size
$currentsize = 0;
if (is_array($data['attach']) and sizeof($data['attach']) > 0) {
	reset($data['attach']);
	while (list($number, ) = each($data['attach'])) {
		$currentsize += $data['attach'][$number]['size'];
	}
}

// And upload new attachments
if (!empty($upload)) {
	eval(makeeval('usermessage', 'compose_manageattach_error'));

	$_FILES['attachment']['name'] = strtolower($_FILES['attachment']['name']);
	$extension = getextension($_FILES['attachment']['name']);

	if (is_uploaded_file($_FILES['attachment']['tmp_name'])) {
		if (getop('safeupload')) {
			$path = getop('tmppath', true).'/'.$_FILES['attachment']['name'];
			move_uploaded_file($_FILES['attachment']['tmp_name'], $path);
			$_FILES['attachment']['tmp_name'] = $path;
		}
	
		$filesize = filesize($_FILES['attachment']['tmp_name']);
		if ($hiveuser['maxattach'] > 0 and $filesize > (($hiveuser['maxattach'] * 1024 * 1024) - $currentsize)) {
			eval(makeeval('usermessage', 'compose_manageattach_error_toobig'));
		} elseif ($filesize == $_FILES['attachment']['size'] and strstr($_FILES['attachment']['tmp_name'], '..') == '') {
			$fp = fopen($_FILES['attachment']['tmp_name'], 'rb');
			$filedata = fread($fp, $filesize);
			fclose($fp);
			@unlink($_FILES['attachment']['tmp_name']);

			// Make sure the attachment doesn't already exist
			$dupe = false;
			if (is_array($data['attach'])) {
				reset($data['attach']);
				while (list($number, ) = each($data['attach'])) {
					if ($data['attach'][$number]['filename'] == $_FILES['attachment']['name'] and $data['attach'][$number]['data'] == $filedata) {
						eval(makeeval('usermessage', 'compose_manageattach_error_duplicate'));
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
					'data' => $filedata
				);
				$updatedata = true;
				eval(makeeval('usermessage', 'compose_manageattach_added'));
			}
		}
	}
}

// Update the dateline so the data won't expire
// (But only if it's not a draft)
// And also update the data if it was altered
$DB_site->query("
	UPDATE hive_draft
	SET draftid = draftid".
	iif($draft['dateline'] > 0, ",dateline = ".TIMENOW).
	iif($updatedata, ",data = '".addslashes(base64_encode(serialize($data)))."'")."
	WHERE draftid = $draftid
");

// Show the attachment list
$attachlist = '';
if (is_array($data['attach'])) {
	reset($data['attach']);
	while (list($number, ) = each($data['attach'])) {
		$attachdata = &$data['attach'][$number];
		eval(makeeval('attachlist', 'compose_manageattach_attachbit', 1));
	}
}

eval(makeeval('echo', 'compose_manageattach'));

?>