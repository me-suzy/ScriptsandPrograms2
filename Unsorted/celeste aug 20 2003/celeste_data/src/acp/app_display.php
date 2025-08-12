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

  $acp->newFrm('Appearance Settings');
  $acp->setFrmBtn();

  /**
   * Redirect Page
   */
  $acp->newTbl('Redirect Page', 'redirect');
  $acp->newRow('Enable Redirect Page',
                $acp->frm->frmAnOp('SET_DISPLAY_REDIRECT_PAGE', SET_DISPLAY_REDIRECT_PAGE));

  $acp->newRow('Forward Time',
                $acp->frm->frmText('SET_FORWARD_TIME', SET_FORWARD_TIME, 25),
                '* Time to wait for auto-redirection');

  /**
   * online list
   */
  $acp->newTbl('Online List', 'onlineList');
  $acp->newRow('Display Online User List',
                $acp->frm->frmAnOp('SET_DISPLAY_INDEX_ONLINELIST', SET_DISPLAY_INDEX_ONLINELIST),
                '( In Forum Index Page )');
  $acp->newRow('Display Online User List',
                $acp->frm->frmAnOp('SET_DISPLAY_FORUM_ONLINELIST', SET_DISPLAY_FORUM_ONLINELIST),
                '( In Topic List Page )');
  $acp->newRow('Number of users each line', $acp->frm->frmText('SET_ONLINE_PER_LINE', SET_ONLINE_PER_LINE, 25));

} else {

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );

  $m->set('SET_DISPLAY_REDIRECT_PAGE', intval($_POST['SET_DISPLAY_REDIRECT_PAGE']), 0);
  $m->set('SET_FORWARD_TIME', intval($_POST['SET_FORWARD_TIME']), 0);

  $m->set('SET_DISPLAY_INDEX_ONLINELIST', intval($_POST['SET_DISPLAY_INDEX_ONLINELIST']), 0);
  $m->set('SET_DISPLAY_FORUM_ONLINELIST', intval($_POST['SET_DISPLAY_FORUM_ONLINELIST']), 0);
  $m->set('SET_ONLINE_PER_LINE', intval($_POST['SET_ONLINE_PER_LINE']), 0);

  $m->save();
  acp_success_redirect('You have updated the settings successfully', 'prog=app::display');

}
