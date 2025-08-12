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

define('LOG_DEFAULT_LIMIT', 100);
$limit = intval(getParam('limit'));
if(!$limit) $limit = LOG_DEFAULT_LIMIT;

  $acp->newFrm('View Logs');
  //$acp->setFrmBtn();

  if(!($type = intval(getParam('type')))) $type = 1;

  $acp->newTbl('Options');
  $acp->newRow('Log Type', $acp->frm->get($acp->frm->frmList('type', $type, 'All', 'Login / Request Password', 'Moderator Management', 'Admin Management'))." &nbsp; &nbsp; ".$acp->frm->get($acp->frm->frmBtn('Go', 'selecttype', 'submit')) );
  $acp->newRow('Limit Result', $acp->frm->frmText('limit', $limit));

  $con = '';
  switch($type) {
    case 2: $con = 'WHERE action like \'user%\'';break;
    case 3: $con = 'WHERE action like \'mod%\'';break;
    case 4: $con = 'WHERE action like \'acp%\'';break;
    default: break;

  }

  $acp->newTbl('Logs');
  $acp->newRow2( 'User Name', 'Action', 'Time', 'IP Address' );  
  $rs = $DB->query("SELECT * FROM celeste_log ".$con." ORDER BY logid DESC", 0, $limit);

  while($ut = $rs->fetch() ) {

    $acp->newRow2( $ut['username'], $ut['action'], getTime($ut['time']), $ut['ipaddress'] );  

  } // end of while
