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
import('attachment');

if(!empty($_POST['delete_submit'])) {

  if(!is_array($_POST['delAtt'])) {
    acp_success_redirect('The selected attachments have been deleted successfully.', 'acp.php?prog=att::list');
  }

  $attach_to_delete = array();
  foreach($_POST['delAtt'] as $id => $d) {
    if($d && is_int($id)) {
      $attach_to_delete[] = $id;
    }
  }
  if(count($attach_to_delete)>0) {
    $con = "WHERE attachmentid IN ('".join("','", $attach_to_delete)."')";
    $rs = $DB->query("SELECT attachmentid, filename, direct_output FROM celeste_attachment ".$con);
    while($ut = $rs->fetch()) {
      unlink(attach::sgetName($ut['attachmentid']));
      if($ut['direct_output'])
        unlink('./direct_output/attachments/ATT'.$ut['attachmentid'].'_'.$ut['filename']);
    }
    $rs->free();
    unset($rs);

    $DB->update("DELETE FROM celeste_attachment ".$con);
    $DB->update("UPDATE celeste_post SET attachmentid = 0 ".$con);
  }

  acp_success_redirect('The selected attachments have been deleted successfully.', 'acp.php?prog=att::list');

} else {

  /**
   * init
   */
  $filesPP = intval(getParam('filesPP'));
  if($filesPP < 1 || $filesPP > 100) $filesPP = 20;
  $desc = intval(getParam('desc')) ? 1 : 0;
  $sortField = getParam('sortByDate') ? 'posttime' : 'filename';
  $page = isset($_GET['page']) ? $_GET['page']: 1;

  $rawconditions = array();
  $rawconditions['filename'] =& trim(getParam('filename'));
  $rawconditions['filesPP'] =& $filesPP;
  $rawconditions['desc'] =& $desc;
  $rawconditions['sortByDate'] = getParam('sortByDate');
  $rawconditions['images'] = intval(getParam('images'));

  $rawconditions['date_min'] = intval(getParam('date_min'));
  $rawconditions['date_max'] = intval(getParam('date_max'));
  $rawconditions['hits_min'] = intval(getParam('hits_min'));
  $rawconditions['hits_max'] = intval(getParam('hits_max'));

  $acp->newFrm('Attachments');
  $acp->setFrmBtn('Delete Selected Attachments', 'delete_submit', 'onClick="return confirm(\'Are you sure to delete the selected attachments?\nBe careful, this action cannot be undone.\')"');

  /**
   * query
   */
  $conditions = array();
  if($rawconditions['filename']) $conditions[] = 'a.filename like \'%'.slashesencode($rawconditions['filename']).'%\'';

  // date
  if($rawconditions['date_min']>0)
    $conditions[] = 'p.posttime > \''.date('Y-m-d', $celeste->timestamp-$rawconditions['date_min']*3600*24).'\'';
  if($rawconditions['date_max']>0)
    $conditions[] = 'p.posttime < \''.date('Y-m-d', $celeste->timestamp-$rawconditions['date_max']*3600*24).'\'';

  // hits
  if($rawconditions['hits_min']>0)
    $conditions[] = 'a.counter > '.$celeste->timestamp-$rawconditions['hits_min'];
  if($rawconditions['hits_max']>0)
    $conditions[] = 'a.counter < '.$celeste->timestamp-$rawconditions['hits_max'];


  $conditions = join(' AND ', $conditions);
  if(!empty($conditions)) $conditions = ' WHERE '.$conditions;

  $total_files = $DB->result('SELECT count(*) FROM celeste_attachment');
  $matched_files = $DB->result('SELECT count(*) FROM celeste_attachment a LEFT JOIN celeste_post p USING(attachmentid) '.$conditions);

  $rs = $DB->query(
    'SELECT a.*, p.postid, p.username, p.userid, p.title posttitle, p.posttime, f.title forumname
      FROM celeste_attachment a LEFT JOIN celeste_post p USING(attachmentid)
        LEFT JOIN celeste_topic t USING(topicid) LEFT JOIN celeste_forum f USING(forumid) '.
      $conditions.' ORDER BY '.$sortField.' '.($desc?'DESC':'ASC'), ($page-1)*$filesPP, $filesPP);

  $acp->newTbl('Files Found');
  $acp->newRow('<center>Found <b>'.$matched_files.'</b> matched files in all the <b>'.$total_files.'</b> attachments. No. '.(($page-1)*$filesPP+1).' To No. '.min($page*$filesPP, $total_files).'<br>[<b>P</b>] = Public Attachments</center>');

  $acp->newMenuRow('File Name', 'User Name', 'Forum', 'Post Title', 'File Size', 'Date', 'Hits', 'DEL');

  ////////////// display matched files ///////////////
  while($ut = $rs->fetch()) {
    $ut['filesize'] = @filesize(attach::sgetName($ut['attachmentid'])) OR $ut['filesize'] = '<font color=#FF0000><b>NOT EXIST</b></font>';
    if(is_int($ut['filesize'])) {
      if($ut['filesize'] > 1024)
        $ut['filesize'] = (string)round($ut['filesize']/1024). ' KB';
      else
        $ut['filesize'].= ' Bytes';
    }

    if($ut['direct_output']) {
      $ut['app_name'] = "<a href='./direct_output/attachments/ATT".$ut['attachmentid'].'_'.$ut['filename']."' target=_blank>".$ut['filename']."</a> [<b>P</b>]";
    } else {
      $ut['app_name'] = "<a href='index.php?prog=attach::dl&pid=".$ut['postid']."' target=_blank>".$ut['filename']."</a>";
    }

    $acp->newRow2(
      $ut['app_name'],
      $ut['userid']!=0 ?
        "<a href='acp.php?prog=user::edit&uid=".$ut['userid']."' target=_blank>".$ut['username']."</a>" :
        'Guest',
      $ut['forumname'],
      "<a href='index.php?prog=topic::threaded&pid=".$ut['postid']."' target=_blank>".$ut['posttitle']."</a>",
      $ut['filesize'], getTime($ut['posttime']), (string)$ut['counter'],
      "<input type='checkbox' name='delAtt[".$ut['attachmentid']."]'>");
  }
  ////////////// end of display matched files ///////////////
  /**
   * display pages
   */
  buildPageNav($page, $matched_files, $filesPP, 'att::list', $rawconditions);
  /*
  if($matched_files > $filesPP) {
    $pagenav = '';
    $appendix = '';
    foreach($rawconditions as $key => $value) {
      $appendix .= $key.'='.urlencode($value).'&';
    }

    $pagenav .= "&nbsp; <a href='acp.php?prog=att::list&page=1&".$appendix."'>|&lt;&lt;</a>";

    for($i = max(1, $page - 5); $i <= min(ceil($matched_files / $filesPP), $page+5); $i++) {
      $pagenav .= " <a href='acp.php?prog=att::list&page=$i&".$appendix."'><b>$i</b></a> ";
    }

    $pagenav .= "&nbsp; <a href='acp.php?prog=att::list&page=".ceil($matched_files / $filesPP)."&".$appendix."'>|&gt;&gt;</a>";

    $acp->newRow('<center>'.$pagenav.'</center>');
    unset($pagenav);
    unset($appendix);
  }
  */

  $acp->newFrm('Search Attachments');
  $acp->setFrmBtn('Search');
  /**
   * build search form
   */
  $acp->newTbl('Search', 'display');
  $acp->newRow('File Name contains', $acp->frm->frmText('filename', $rawconditions['filename']));
  $acp->newRow('A image file ?', $acp->frm->frmAnOp('images', $rawconditions['images']));

  $acp->newRow('Hits from',
    $acp->frm->frmSpan('hits', '', $rawconditions['hits_min'], $rawconditions['hits_max']));

  $acp->newRow('Uploaded from',
    $acp->frm->frmDateSpan('date', $rawconditions['date_min'], $rawconditions['date_max']));

  $acp->newRow('Number of files per page', $acp->frm->frmText('filesPP', $filesPP, 10));
  $acp->newRow('Sort By Date ?', $acp->frm->frmAnOp('sortByDate', $rawconditions['sortByDate']));
  $acp->newRow('In Descending ?', $acp->frm->frmAnOp('desc', $desc));


}
