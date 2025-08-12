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

include( DATA_PATH.'/settings/censoredusername.inc.php' );
import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  $cunames = join("\n", $CensoredUnames);

  $acp->newFrm('Censored User Names');
  $acp->setFrmBtn();
  $acp->newTbl('Censored Words in User Names');
  $acp->newRow('Censored Words in User Names', $acp->frm->frmTextarea('cunames', $cunames), '* user names containing any censored word will not be accepted by registration system<br>* quoted word match the exact user name. e.g. "money" will only match <b>money</b> but not <b>I_love_money</b>');

} else {

  /**
   * update censored usernames
   */
  $CensoredUnames = array();
  $_POST['cunames'] = slashesdecode(getParam('cunames'));
  $cunames = explode("\n", $_POST['cunames']);
  foreach($cunames as $cuname) {
    $cuname = trim($cuname);
    if('' != $cuname)
      $CensoredUnames[] = $cuname;
  }

  $m = new modify_setting( DATA_PATH.'/settings/censoredusername.inc.php' );
  $m->cover();
  $m->setArray('CensoredUnames', $CensoredUnames);
  $m->save();

  acp_success_redirect('You have updated Censored Words in User Names successfully', 'prog=ban::uname');

}
