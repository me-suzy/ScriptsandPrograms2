<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */
include(DATA_PATH.'/settings/acp_shortcut.inc.php');
include(DATA_PATH.'/src/acp/acp_modules.php');

$acp->newFrm('Welcome to Celeste Admin Control Panel');
$acp->newRow('Welcome to the Celeste Forums Administration tool.');
$acp->newRow('Celeste Version: 2003 '.SET_VERSION);
$acp->newRow('Please choose options from left menu column.');

/************************
 * check tasks
 */
  $mailcounter = 0;
  $d = dir(DATA_PATH.'/mailbox');
  $d->read();$d->read(); // '.', '..'
  while($mail = $d->read()) 
    if(substr($mail, -4)!='.tmp')$mailcounter++;
  
  $users_to_activate = $DB->result("SELECT count(*) FROM celeste_user_inactive");
  unset($d);

/**
 * shortcuts
 */
$acp->newTbl('Short Cuts [ <a href='.$_SERVER['PHP_SELF'].'?prog=shortcut>Custom</a> ]');
if($mailcounter) {
  $acp->newRow('<a href='.$_SERVER['PHP_SELF'].'?prog=user::mail::send>Email -> Send Mails</a>', (string)$mailcounter);
}
if($users_to_activate) {
  $acp->newRow('<a href='.$_SERVER['PHP_SELF'].'?prog=user::act>User Manager -> Activate Users</a>', (string)$users_to_activate);
}

$tmp_counter = 0;
foreach($acpShortcuts as $iden_string => $tmp) {
  list($groupid, $cateid, $prog) = explode('|', $iden_string);

  if(++$tmp_counter % 2 == 0) {
    $acp->newRow2($row1_module, '<a href='.$_SERVER['PHP_SELF'].'?prog='.$prog.'>'.$acp_category[$groupid][$cateid].' -> '.$acp_module[$groupid][$cateid][$prog].'</a>');
    unset($row1_module);
  } else {
    $row1_module = '<a href='.$_SERVER['PHP_SELF'].'?prog='.$prog.'>'.$acp_category[$groupid][$cateid].' -> '.$acp_module[$groupid][$cateid][$prog].'</a>';
  }
}
if(!empty($row1_module))
   $acp->newRow2($row1_module, '&nbsp;');

$acp->newTbl('Variables');
$acp->newRow('PHP Version', phpversion());
$acp->newRow('HTTP Server', $_SERVER['SERVER_SOFTWARE']);
$acp->newRow('Board Status', SET_BOARD_CLOSE ? 'Closed Due To: '.SET_BOARD_CLOSE_MESSAGE : 'Open');
$acp->newRow('Safe Mode', ini_get('safe_mode')!='On' ? 'Off' : '<font color=#FF0000>On</font>');
$acp->newRow('ZLib Extension Module', extension_loaded('zlib') ? 'Loaded' : 'Not Loaded');
$acp->newRow('Max Upload File Size', ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'Disabled');
$acp->newRow('Max Execution Time', '<b>'.ini_get('max_execution_time').'</b> seconds');

// mail
if(ini_get('sendmail_path')) $mailsending = 'Unix Sendmail, Path: '.ini_get('sendmail_path');
elseif(ini_get('SMTP')) $mailsending = 'SMTP, Server: '.ini_get('SMTP');
else $mailsending = 'Disabled';
$acp->newRow('Mail Sending Mode', $mailsending);

$acp->newTbl('Links');
$acp->newRow('<a href=http://www.celestesoft.com target=_blank>Celeste Home</a>');
$acp->newRow('<a href=http://www.php.net target=_blank>PHP Official Site</a>');

$acp->newTbl('Software Development');
$acp->newRow('<a href=mailto:a@xinshi.org>Y10K</a>, <a href=mailto:lvxing@fastboard.org>Lvxing</a>');

