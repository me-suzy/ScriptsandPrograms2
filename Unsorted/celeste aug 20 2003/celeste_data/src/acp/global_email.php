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

  $acp->newFrm('Email Settings in Your Celeste');
  $acp->setFrmBtn();

  $acp->newTbl('Enable Email Function');
  $acp->newRow('Enable ?', $acp->frm->frmAnOp('SET_ENABLE_EMAIL', SET_ENABLE_EMAIL));
  $acp->newRow('Enable Email Cache ?', $acp->frm->frmAnOp('SET_DELAY_SENDMAIL', SET_DELAY_SENDMAIL), '* If set to yes, emails are saved to a temp dir instead of sent immediately. Later admin can use the Send Mail module in ACP to send them.');

  $acp->newTbl('Email Settings');
  $acp->newRow('Admin Email', $acp->frm->frmText('SET_ADMIN_EMAIL', SET_ADMIN_EMAIL, 60));

  $acp->newRow('Email Sender',
                $acp->frm->frmText('SET_EMAIL_SENDER', SET_EMAIL_SENDER, 60),
                '* Display in emails sent by celeste');

  $acp->newRow('Board Email',
                $acp->frm->frmText('SET_BOARD_EMAIL', SET_BOARD_EMAIL, 60),
                '* Display in emails sent by celeste');


} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );
  $m->set('SET_ENABLE_EMAIL', intval($_POST['SET_ENABLE_EMAIL']), 0);
  $m->set('SET_DELAY_SENDMAIL', intval($_POST['SET_DELAY_SENDMAIL']), 0);

  $m->set('SET_ADMIN_EMAIL', $_POST['SET_ADMIN_EMAIL']);
  $m->set('SET_EMAIL_SENDER', $_POST['SET_EMAIL_SENDER']);
  $m->set('SET_BOARD_EMAIL', $_POST['SET_BOARD_EMAIL']);
  $m->save();

  acp_success_redirect('You have updated the settings successfully', 'prog=global::email');

}
