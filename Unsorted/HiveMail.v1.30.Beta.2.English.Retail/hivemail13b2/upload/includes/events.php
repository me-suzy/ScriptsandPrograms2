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
// | $RCSfile: events.php,v $ - $Revision: 1.19 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
$events = array();

// ############################################################################
// POP Events
$events[101] = array('Could not connect to server', 'Could not connect to $server on port $port');
$events[102] = array('Authorization failed', 'Authorization failed on $server using $username : $password');
$events[103] = array('Command failed', '$cmd could not be run');

// ############################################################################
// MIME Events
$events[201] = array('Message not delivered (no space)', 'Message (subject: $subject, from: $from) could not be delivered to $username because they have no space remaining.');
$events[202] = array('Auto-forward by $owner', '$owner auto-forwarded a message (subject $subject) from $from to $fwdaddr');
$events[203] = array('Message not delivered (no user)', 'Message (subject: $subject, from: $from, recipients: $recips) could not be delivered to all recepients as some do not exist.');
$events[204] = array('Auto-response sent', 'Auto-response sent from $owner to $rcpt');
$events[205] = array('Message not delivered (too big)', 'Message (subject: $subject, from: $from) could not be delivered as it was too large.');

// ############################################################################
// Template Events
$events[301] = array('Template not found', '$templatename could not be found');
$events[302] = array('Template ($templatename) not cached', '$templatename was not cached on $filename');

// ############################################################################
// User events
$events[401] = array('User ($user) logged in', '$ip logged in as $user');
$events[402] = array('User ($user) logged out', '$ip logged out as $user');
$events[403] = array('Failed user log in (incorrect $reason)', '$ip tried to login as $user. The $reason supplied was incorrect.');

// ############################################################################
// SMTP events
$events[501] = array('Mail sent!','Mail sent from $from ($ip) to $to');
$events[502] = array('SMTP error','While trying to send mail from $from to $to the following error(s) occured:<br />$smtp_errors');

// ############################################################################
// Database events
$events[601] = array('Database error', 'Database error:\n\n$msg\n\nMySQL error: $error\nMySQL error number: $errno\nScript: $script');

// ############################################################################
// Message storage events
$events[701] = array('Message file creation failed', 'Message (subject: $subject, from: $from) could not be delivered to its recepients as the data file could not be created.');
$events[702] = array('Message file deletion failed', 'The file $file.dat could not be deleted from the $path directory.');

$translated_events['server'] = 'Server';
$translated_events['port'] = 'Port';
$translated_events['user'] = 'Username';
$translated_events['password'] = 'Password';
$translated_events['subject'] = 'Subject';
$translated_events['from'] = 'From';
$translated_events['owner'] = 'Owner';
$translated_events['template'] = 'Template';
$translated_events['script'] = 'Script';
$translated_events['ip'] = 'IP';
$translated_events['reason'] = 'Reason';
$translated_events['to'] = 'To';
$translated_events['smtp_errors'] = 'SMTP Errors';
$translated_events['msg'] = 'Message';
$translated_events['error'] = 'Error';
$translated_events['error_no'] = 'Error No';
$translated_events['file'] = 'File';
$translated_events['path'] = 'Directory';
$translated_events['fail_recips'] = 'Failed recepients';
$translated_events['cmd'] = 'Command';
$translated_events['serv_resp'] = 'Server Response';

?>