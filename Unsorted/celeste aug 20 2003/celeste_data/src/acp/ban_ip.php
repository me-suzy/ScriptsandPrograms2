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

include( DATA_PATH.'/settings/banned_ip.inc.php' );
import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  if (is_array($banned_ip) && count($banned_ip)>0) 
    $banned_ips = join("\n", $banned_ip);
  else $banned_ips = '';

  $acp->newFrm('Ban IP Address');
  $acp->setFrmBtn();
  $acp->newTbl('Ban IP Address');
  $acp->newRow('Enable Ban IP ?', $acp->frm->frmAnOp('SET_BAN_IP', SET_BAN_IP));
  $acp->newRow('Banned IP Address', $acp->frm->frmTextarea('banned_ips', $banned_ips), '* Example1: 123.213.132.32<br>* Example2: 123.213.132.');

} else {

  /**
   * enable/disable
   */
  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );
  $m->set('SET_BAN_IP', intval($_POST['SET_BAN_IP']), 0);
  $m->save();
  unset($m);

  /**
   * update banned ip file
   */
  $banned_ip = array();
  $_POST['banned_ips'] = str_replace("'", '', $_POST['banned_ips']);
  $_POST['banned_ips'] = str_replace('"', '', $_POST['banned_ips']);
  $ips = explode("\n", $_POST['banned_ips']);
  foreach($ips as $ip) {
    $ip = trim($ip);
    if('' != $ip)
      $banned_ip[] = $ip;
  }

  $m = new modify_setting( DATA_PATH.'/settings/banned_ip.inc.php' );
  $m->cover();
  $m->setArray('banned_ip', $banned_ip);
  $m->save();

  acp_success_redirect('You have updated the banned IP addresses successfully', 'prog=ban::ip');

}
