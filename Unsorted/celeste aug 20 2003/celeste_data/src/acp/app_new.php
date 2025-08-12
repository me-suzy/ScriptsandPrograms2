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


if(!empty($_POST['acpSubmit'])) {

  $name = slashesencode($_POST['name']);
  if ( empty($name) ) {
    acp_exception('Invalid Template Element Name');
  }
  if ( empty($_POST['content']) ) {
    acp_exception('Invalid Template Element Content');
  }
  if ($DB->result('select count(*) from '.SET_TEMPLATE_TABLE.' where name="'.$name.'"')>0) {
    acp_exception('Invalid Template Element Name');
  }

  $DB->update("INSERT INTO ".SET_TEMPLATE_TABLE." SET
    template = '".slashesencode($_POST['content'])."', name='".$name."'");


  acp_success_redirect('Template Element Added successfully', 'prog=app::editor');


} else {
  $acp->newFrm('Template Editor');
  $acp->setFrmBtn();


  /**
   * build output
   */
  $acp->newTbl('Add new Template Element');

  $acp->newRow('Template Element Name', $acp->frm->frmText('name', ''));
  $acp->newRow('Template Element Content', $acp->frm->frmTextarea('content', '', 30));


}


?>