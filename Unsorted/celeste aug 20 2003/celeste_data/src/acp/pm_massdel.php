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

$conditions = buildPMSearchConditions();

if(!empty($_POST['acpSubmit'])) {

  $query_con = buildPMSearchQueryConditions($conditions);

  $DB->update("DELETE FROM celeste_pmessage ".str_replace(' p.', ' ', $query_con));
  acp_success_redirect('You have deleted all matched PMs successfully', $_SERVER['PHP_SELF'].'?prog=pm::view');

} else {

  $acp->newFrm('Mass Delete PMs');
  $acp->setFrmBtn('Mass Delete', 'acpSubmit', 'onClick="return confirm(\'Are you sure to perform this action?\nBe careful, This action cannot be undone!\')"');

  /**
   * build search form
   */
  buildPMSearchForm($conditions);

}
