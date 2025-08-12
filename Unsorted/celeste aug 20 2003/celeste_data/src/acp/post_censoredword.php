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

include( DATA_PATH.'/settings/censoredword.inc.php' );
import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Censored Words');
  $acp->setFrmBtn();

  $acp->newTbl('Edit Censored Words');
  $acp->newRow('Original Words', 'Replacement');

  $index = 0;
  if(isset($CensoredWords)) {
    foreach($CensoredWords as $key => $original) {
      $index++;
      $acp->newRow($acp->frm->get($acp->frm->frmText('original['.$index.']', $original, 40, 60)),
                   $acp->frm->get($acp->frm->frmText('replacement['.$index.']', $ReplaceToWords[$key], 40, 60)));
    }
  } else {
    $acp->newRow('<center>There is no censored words defined before.</center>');
  }

  $acp->newTbl('Add new censored words');
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('original['.$index.']', '', 40, 60)),
               $acp->frm->get($acp->frm->frmText('replacement['.$index.']', '', 40, 60)));
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('original['.$index.']', '', 40, 60)),
               $acp->frm->get($acp->frm->frmText('replacement['.$index.']', '', 40, 60)));
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('original['.$index.']', '', 40, 60)),
               $acp->frm->get($acp->frm->frmText('replacement['.$index.']', '', 40, 60)));
  $index++;
  $acp->newRow($acp->frm->get($acp->frm->frmText('original['.$index.']', '', 40, 60)),
               $acp->frm->get($acp->frm->frmText('replacement['.$index.']', '', 40, 60)));

} else {

  $original = array();
  $replacement = array();
  foreach($_POST['original'] as $k => $v) {
    if($v) {
      $original[] = slashesdecode($v);
      $replacement[] = slashesdecode($_POST['replacement'][$k]);
    }
  }

  $m = new modify_setting( DATA_PATH.'/settings/censoredword.inc.php' );
  $m->cover();
  $m->setArray('CensoredWords', $original);
  $m->setArray('ReplaceToWords', $replacement);
  $m->save();

  acp_success_redirect('You have updated the censored words settings successfully', 'prog=post::censoredword');

}
