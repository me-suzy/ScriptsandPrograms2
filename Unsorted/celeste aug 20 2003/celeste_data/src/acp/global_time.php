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

  $acp->newFrm('Date/Time Settings in Your Celeste');
  $acp->setFrmBtn();

  $acp->newTbl('Time Zone');
  $acp->newRow('Server Time', '<b>'.date('Y-n-j G:i').'</b>, '.date('T( O )').' ');
  $acp->newRow('Current Time', $acp->frm->frmText('current_time', date('Y-n-j G:i', time()+SET_TIME_ZONE_OFFSET), 25), '* Format: "YYYY-MM-DD Hour:Minute"');

  $acp->newTbl('Date/Time Format');
  $acp->newRow('Date Format',
                $acp->frm->frmText('SET_DATE_FORMAT', SET_DATE_FORMAT, 30),
                '* Refer to <a href="http://www.php.net/manual/en/function.date.php" target="_blank">PHP Manual</a> For Definition');

  $acp->newRow('Time Format',
                $acp->frm->frmText('SET_TIME_FORMAT', SET_TIME_FORMAT, 30),
                '* Refer to <a href="http://www.php.net/manual/en/function.date.php" target="_blank">PHP Manual</a> For Definition');


} else {

  $remove_timestamp = getTs($_POST['current_time']);
  if( -1 == $remove_timestamp ) {
    acp_exception('Please input a valid time');
  }

  $m = new modify_setting( DATA_PATH.'/settings/config.global.php' );

  /**
   * caculate time zone offset
   */
  $server_timestamp = time();
  $server_timestamp = $server_timestamp - $server_timestamp%60;
  $timezoneoffset   = $remove_timestamp - $server_timestamp;

  $m->set('SET_TIME_ZONE_OFFSET', $timezoneoffset, 0);
  $m->set('SET_DATE_FORMAT', $_POST['SET_DATE_FORMAT']);
  $m->set('SET_TIME_FORMAT', $_POST['SET_TIME_FORMAT']);
  $m->save();

  acp_success_redirect('You have updated the settings successfully', 'prog=global::time');

}
