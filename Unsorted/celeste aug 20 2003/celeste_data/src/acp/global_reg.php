<?php
/**
 * Project Source File
 * Celeste V2003
 * Jun 28, 2003
 * Celeste Dev Team - Lvxing / Xinshi
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Registration Settings in Your Celeste');
  $acp->setFrmBtn();

  $acp->newTbl('Enable Registration');
  $acp->newRow('Enable Registration',
                $acp->frm->frmAnOp('SET_ENABLE_REG', SET_ENABLE_REG),
                '* Guest cannot register if closed' );

  $acp->newTbl('Account Activation');
  $acActivat = $acp->frm->frmList('SET_REG_METHOD', SET_REG_METHOD+1,
                                  'Immediately Activated',
                                  'Send Activate Link By Email',
                                  'Send Password By Email',
                                  'Activate By Admin');
  $acp->newRow('Account Activation Mode', $acActivat, '* If set to Send Activate Link By Email or Send Password By Email, the sendmail function MUST be enabled.');

  $acp->newTbl('Registration Options');
  $acp->newRow('Allow Duplicated Email ?',
                $acp->frm->frmAnOp('SET_ALLOW_DUPE_EMAIL', SET_ALLOW_DUPE_EMAIL),
                '* Duplicated Email in multi accounts is allowed if set to "yes"');

  $acp->newRow('Allow logged in users to register ?',
                $acp->frm->frmAnOp('SET_ALLOW_MULTI_REG', SET_ALLOW_MULTI_REG));

} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );
  $m->set('SET_ENABLE_REG', intval($_POST['SET_ENABLE_REG']), 0);
  $m->set('SET_REG_METHOD', intval($_POST['SET_REG_METHOD'])-1, 0);
  $m->set('SET_ALLOW_DUPE_EMAIL', intval($_POST['SET_ALLOW_DUPE_EMAIL']), 0);
  $m->set('SET_ALLOW_MULTI_REG', intval($_POST['SET_ALLOW_MULTI_REG']), 0);
  $m->save();

  acp_success_redirect('You have updated the settings successfully', 'prog=global::reg');

}
