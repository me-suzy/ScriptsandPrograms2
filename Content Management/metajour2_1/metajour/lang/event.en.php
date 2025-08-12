<?php
/**
 * @package METAjour
 * @subpackage lang
 */

$LANG['name'] = 'Events';
$LANG['label_name'] = 'Name';
$LANG['label_content'] = 'Message';
$LANG['label_subject'] = 'Subject';
$LANG['label_triggertype'] = 'Table';
$LANG['label_triggerevent'] = 'On action';
$LANG['label_msgtype1'] = 'Send message as';
$LANG['label_msgdest1'] = 'To';
$LANG['label_msgtype2'] = 'Send message as';
$LANG['label_msgdest2'] = 'To';

$LANG['option_triggerevent']['create'] = 'Create';
$LANG['option_triggerevent']['update'] = 'Update';
$LANG['option_triggerevent']['delete'] = 'Delete';
$LANG['option_triggerevent']['requestapproval'] = 'Request approval';
$LANG['option_triggerevent']['approvepublish'] = 'Approve and publish';

$LANG['option_msgtype1']['0'] = '[NONE]';
$LANG['option_msgtype1']['1'] = 'Internal message';
$LANG['option_msgtype1']['2'] = 'Email (if email is set on user)';

$LANG['option_msgdest1']['0'] = '[NONE]';
$LANG['option_msgdest1']['1'] = 'OWNER OF RECORD';
$LANG['option_msgdest1']['2'] = 'OWNER OF OWNER OF RECORD';
$LANG['option_msgdest1']['3'] = 'CHECKEDBY ON RECORD';
$LANG['option_msgdest1']['4'] = 'CURRENT USER';
$LANG['option_msgdest1']['5'] = 'OWNER OF CURRENT USER';
$LANG['option_msgdest1']['6'] = 'USERS WITH ACCESS TO RECORD';

$LANG['option_msgtype2']['0'] = '[NONE]';
$LANG['option_msgtype2']['1'] = 'Internal message';
$LANG['option_msgtype2']['2'] = 'Email (if email is set on user)';
?>