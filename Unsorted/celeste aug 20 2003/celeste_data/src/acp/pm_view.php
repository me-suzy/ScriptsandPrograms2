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

/**
 * init
 */
$asc = intval(getParam('asc')) ? 1 : 0;
$sortBy  = intval(getParam('sortBy'));
if(0==$sortBy)$sortBy = 3;
switch($sortBy) {
  case 1: $sortField = 'u1.username';break;
  case 2: $sortField = 'u2.username';break;
  case 3: $sortField = 'p.sentdate';break;
  default:$sortField = 'p.sentdate';
}

  $acp->newFrm('View PMs');
  $acp->setFrmBtn('Search');

/**
 * get condition
 */
$conditions =& buildPMSearchConditions();

$displayList = 0;
foreach($conditions as $k => $a) if($a && !('haveread'==$k && 1==$a) && !('box'==$k && 1==$a)) $displayList = 1;
if($displayList) {
  /**
   * build list
   */
  $total_pms = $DB->result("SELECT count(*) FROM celeste_pmessage");
  $matched_pms = $DB->result("SELECT count(*) FROM celeste_pmessage p LEFT JOIN celeste_user u1 ON(senderid=u1.userid) LEFT JOIN celeste_user u2 ON(recieverid=u2.userid) ".buildPMSearchQueryConditions($conditions));

  $rs = $DB->query("SELECT p.msgid, p.sentdate, p.box, SUBSTRING(p.content, 1, 64) AS preview, u1.username sender, u2.username reciever, p.title FROM celeste_pmessage p LEFT JOIN celeste_user u1 ON(senderid=u1.userid) LEFT JOIN celeste_user u2 ON(recieverid=u2.userid) ".buildPMSearchQueryConditions($conditions)." ORDER BY ".$sortField.($asc ? ' ASC' : ' DESC'));

  $acp->newTbl('PMs '.$matched_pms.' / '.$total_pms);
  $acp->newMenuRow("Title / Preview", 'Sender', 'Reciever', 'Sent Date', 'Box');
  while($pm = $rs->fetch()) {
    $acp->newRow2("<a href='".$_SERVER['PHP_SELF']."?prog=pm::edit&msgid=".$pm['msgid']."' title='Preview: \n".str_replace("'", '', $pm['preview'])." '>".$pm['title']."</a>", $pm['sender'], $pm['reciever'], getTime($pm['sentdate']), ($pm['box']=='in' ? 'Inbox' : 'Outbox'));
  }
  $rs->free();

}

/**
 * build search form
 */
buildPMSearchForm($conditions);
$acp->newTbl('Display Preferences');
$acp->newRow('Sort by', $acp->frm->frmList('sortBy', $sortBy, 'Sender\'s User Name', 'Reciever\'s User Name', 'Sent Date'));
$acp->newRow('In Ascending ?', $acp->frm->frmAnOp('asc', $asc));
