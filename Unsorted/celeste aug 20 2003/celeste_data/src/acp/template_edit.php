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


/*
if(!isset($_GET['tm']) || ($temp=&$DB->result('select template from '.SET_TEMPLATE_TABLE.' where name="'.$_GET['tm'].'"'))===NULL ) {
  acp_exception( 'Invalid Access' );
}
*/
if(substr($_GET['tm'], 0, 2) == 'm:') {
  // mail template
  $temp = readfromfile(DATA_PATH.'/email/'.str_replace('m:', '', $_GET['tm']).'.tpl');
} else {
  if(!isset($_GET['tm']) || ($temp=&$DB->result('select template from '.SET_TEMPLATE_TABLE.' where name="'.$_GET['tm'].'"'))===NULL ) {
    acp_exception( 'Invalid Access' );
  }
}


if(!empty($_POST['acpSubmit'])) {

  if ( empty($_POST['content']) ) {
    acp_exception('Invalid Template Element Content');
  }

  if(substr($_GET['tm'], 0, 2) == 'm:') {
    writetofile(DATA_PATH.'/email/'.str_replace('m:', '', $_GET['tm']).'.tpl', slashesDecode($_POST['content']) );
  } else {
    $DB->update("UPDATE ".SET_TEMPLATE_TABLE." SET
      template = '".slashesencode($_POST['content'])."'
        WHERE name = '".$_GET['tm']."'");
  }

  
  /***
   * update cache
   */
  $cacheIdString = 's:'.strlen($_GET['tm']).':"'.$_GET['tm'].'";';
  $cacheDir = dir(DATA_PATH.'/cache');
  $cacheDir->read();$cacheDir->read();
  while($cacheName = $cacheDir->read()) {
    $cType = substr($cacheName, 0, 2);
    if($cType == 'FS' || $cType == 'tr') continue;

    if(substr(readfromfile(DATA_PATH.'/cache/'.$cacheName), $cacheIdString) !== NULL)
      unlink(DATA_PATH.'/cache/'.$cacheName);

  }


  acp_success_redirect('Template Element Edited successfully', 'prog=app::editor');


} else {
  $acp->newFrm('Template Editor');
  $acp->setFrmBtn();


  /**
   * build output
   */
  $acp->newTbl('Edit');

  $acp->newRow('Template Element Name', $_GET['tm']);
  $acp->newRow('Template Element Content', $acp->frm->frmTextarea('content',  htmlspecialchars($temp), 30));



}


?>