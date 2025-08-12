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

import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Open / Close Your Celeste');
  $acp->setFrmBtn();

  $acp->newTbl();
  $acp->newRow('Close', $acp->frm->frmAnOp('SET_BOARD_CLOSE', SET_BOARD_CLOSE));
  $acp->newRow('Close Message',
               $acp->frm->frmText('SET_BOARD_CLOSE_MESSAGE', SET_BOARD_CLOSE_MESSAGE, 60));


} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );
  $m->set('SET_BOARD_CLOSE', intval($_POST['SET_BOARD_CLOSE']), 0);
  $m->set('SET_BOARD_CLOSE_MESSAGE', $_POST['SET_BOARD_CLOSE_MESSAGE']);
  $m->save();

  if(intval($_POST['SET_BOARD_CLOSE']) == 0) {
    acp_success_redirect('You have opened the board successfully', 'prog=global::oc');
  } else {
    acp_success_redirect('You have closed the board successfully', 'prog=global::oc');
  }
}
