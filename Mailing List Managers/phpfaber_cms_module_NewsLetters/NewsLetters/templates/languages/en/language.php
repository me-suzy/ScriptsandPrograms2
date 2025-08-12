<?php

// -----------------------------------------------------------------------------
//
// phpFaber CMS v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber CMS.
// To obtain a license for using phpFaber CMS, please register at
// http://www.phpfaber.com/i/products/cms/
//
// 12:21 AM 09/23/2005
//
// -----------------------------------------------------------------------------

if (!defined('INDEX_INCLUDED')) die('<code>You cannot access this file directly..</code>');

$_LNG['_HEADER_ML_MANAGE'] = 'Manage Mailing Lists';
$_LNG['_HEADER_ML_ADD'] = 'Add Mailing List';
$_LNG['_HEADER_ML_EDIT'] = 'Edit Mailing List';
$_LNG['_HEADER_SUBS_MANAGE'] = 'Manage Subscribers';
$_LNG['_HEADER_NL_ADD'] = 'Create Email';
$_LNG['_HEADER_NL_LOG'] = 'Newsletters Log';
$_LNG['_HEADER_NL_VIEW'] = 'Newsletters Log Record (# %s )';

$_LNG['_SETT_SUBSCRIBERS_NUM'] = 'Number of subscribers per page';
$_LNG['_SETT_NL_NUM'] = 'Number of newsletters per page';
$_LNG['_SETT_EN_M_CRON'] = 'Notify admin via e-mail about mails sent';

$_LNG['_TBL_ML_NAME'] = 'Name';
$_LNG['_TBL_ML_DESCR'] = 'Description';
$_LNG['_TBL_ML_BOTTXT'] = 'Bottom text';
$_LNG['_TBL_ML_UNSUB'] = 'unsubscribe info';
$_LNG['_TBL_ML_DETAILS'] = 'Details';
$_LNG['_TBL_ML_EMAILS'] = 'Subscribers';

$_LNG['_TBL_ML_ACTION'] = 'Action';

$_LNG['_TBL_SUBS_NAME'] = 'Name';
$_LNG['_TBL_SUBS_EMAIL'] = 'Email';

$_LNG['_TBL_NL_MLIST'] = 'Mailing List';
$_LNG['_TBL_NL_SUBSCR'] = 'subscribers';
$_LNG['_TBL_NL_DATE'] = 'Date';
$_LNG['_TBL_NL_DATENOTE'] = 'E-mails will be sent by cron job automaticly on this date';
$_LNG['_TBL_NL_SUBJECT'] = 'Subject';
$_LNG['_TBL_NL_SUBJECTNOTE'] = 'You can use <b>&#35;&#35;unsub_url&#35;&#35;</b> tag in content for unsubscribe link';
$_LNG['_TBL_NL_HTML'] = 'HTML Message';
$_LNG['_TBL_NL_CONTENT'] = 'Content';

$_LNG['_TBL_NLLOG_SHOWNL'] = 'Show Newsletters';
$_LNG['_TBL_NLLOG_DETAILS'] = 'Details';
$_LNG['_TBL_NLLOG_VIEW'] = 'View';
$_LNG['_TBL_NLLOG_MLNAME'] = 'Mailing list Name';
$_LNG['_TBL_NLLOG_DATE'] = 'Date';
$_LNG['_TBL_NLLOG_HTML'] = 'HTML';
$_LNG['_TBL_NLLOG_SUBJECT'] = 'Subject';
$_LNG['_TBL_NLLOG_AMOUNT'] = 'Amount';
$_LNG['_TBL_NLLOG_CONTENT'] = 'Content';
$_LNG['_TBL_NLLOG_STATUS'] = 'Status';
$_LNG['_TBL_NLLOG_CLIKE'] = 'CREATE LIKE';
$_LNG['_TBL_NLLOG_SUBM_MAILNOW'] = 'Mail Now';
$_LNG['_TBL_NLLOG_SUBM_CLIKE'] = 'Create Like';
$_LNG['_TBL_NLLOG_STARTMAIL'] = 'Start mailing';
$_LNG['_TBL_NLLOG_DELETE'] = 'Delete newsletter';
$_LNG['_TBL_NLLOG_STATUS_P'] = 'pending';
$_LNG['_TBL_NLLOG_STATUS_I'] = 'in progress';
$_LNG['_TBL_NLLOG_STATUS_S'] = 'sent';
$_LNG['_TBL_NLLOG_STATUS_U'] = 'unknown';

$_LNG['_MSG_ER_NLLOG_MAIL_WARN1'] = '<b>WARNING:</b> Cannot execute <b>cron.php</b>. Please setup Cron Job to execute it daily';
$_LNG['_MSG_ER_NLLOG_MAIL_WARN2'] = 'ERROR: exec() error: %s';

$_LNG['_CMD_ML_EDIT'] = 'Edit mailing list';
$_LNG['_CMD_ML_DELETE'] = 'Delete mailing list';
$_LNG['_MSG_ML_CONFIRM_DELETE'] = 'Confirm delete this mailing list';
$_LNG['_MSG_ML_NO'] = 'There are no mailing lists on the site.';
$_LNG['_MSG_ML_UNSUB_MSG'] = 'To subscribe or unsubscribe via the World Wide Web, visit ##unsub_url##';
$_LNG['_MSG_ML_UNSUB_NOTE'] = 'Note: use ##unsub_url## tag for unsubscribe link.';
$_LNG['_MSG_ML_UPDATED'] = 'Mailing list has been updated';

$_LNG['_MSG_ERR_ML_NAME_REQ'] = 'Mailing list name cannot be empty';

$_LNG['_MSG_SUBS_CONFIRM_DELETE'] = 'Confirm delete selected subscribers ?';
$_LNG['_MSG_SUBS_DELETED'] = 'E-mails deleted successfully';
$_LNG['_MSG_SUBS_ADDED'] = 'E-mail added successfully';
$_LNG['_MSG_SUBS_WRONGEMAIL'] = 'Invalid e-mail pattern';

$_LNG['_CRON_NL_TOTSENT'] = 'NewsLetter #%s (%s sent) : %s';
$_LNG['_CRON_NL_TOTSENT_'] = 'NewsLetter #%s, mails sent';

$_LNG['_TBL_U_ML'] = 'Mailing List';
$_LNG['_TBL_U_ML_NAME'] = 'Name';
$_LNG['_TBL_U_ML_EMAIL'] = 'E-mail';
$_LNG['_TBL_U_ML_SUBS'] = 'Subscribe';
$_LNG['_TBL_U_ML_UNSUBS'] = 'Unsubscribe';
$_LNG['_MSG_U_NL_CONFIRM'] = 'Thank you. We have sent you email containing instructions how to confirm your subscription.';
$_LNG['_MSG_U_NL_UNSUBSCRIBED'] = 'Your email address <b>##email##</b> has been removed from our
mailing list <b>##list_name##</b>.';
$_LNG['_MSG_U_NL_CONFIRM_ERR'] = 'Subscription confirmation error. Invalid confirmation code.';
$_LNG['_MSG_U_NL_SUBSCRIBED'] = 'Thank you. Your email address <b>##email##</b> has been added to
our mailing list <b>##list_name##</b>.';

$_LNG['_MSG_ML_SUB_CONFIRM'] = 'Mailing List subscription confirmation';
$_LNG['_MSG_ML_ADDED'] = 'You have been added in Mailing List';
$_LNG['_MSG_ML_ALREADY'] = 'Your email address already exists in our mailing list';
$_LNG['_MSG_ML_NOEMAIL'] = 'Your email address is not found in our mailing list';
$_LNG['_MSG_REQ_EMAIL'] = 'E-mail is required';

?>
