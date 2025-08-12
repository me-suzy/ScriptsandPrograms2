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

function compare_title($t1, $t2) {
  if($t1['post'] > $t2['post']) return 1;
  elseif($t1['post'] == $t2['post']) return 0;
  else return -1;
}

include( DATA_PATH.'/settings/title.inc.php' );
import('modify_setting');

if(empty($_POST['acpSubmit'])) {

  $acp->newFrm('Smile Tags');
  $acp->setFrmBtn();

  $acp->newTbl('User Titles/Ranks');
  $acp->newMenuRow('Title', 'Min number of posts', 'Title Image', 'Image Preview');

  $index = 0;
  foreach($titleSetting as $t) {
    $acp->newRow2($acp->frm->frmText('titleSetting['.$index.'][title]', $t['title'], 20), $acp->frm->frmText('titleSetting['.$index.'][post]', $t['post'], 20), $acp->frm->frmText('titleSetting['.$index.'][image]', $t['image'], 20), "<img src='".$t['image']."' border=0>");
    $index++;
  }

  $acp->newTbl('New Title/Rank');
  $acp->newMenuRow('Title', 'Min number of posts', 'Title Image');
  $acp->newRow2($acp->frm->frmText('titleSetting['.$index.'][title]', '', 20), $acp->frm->frmText('titleSetting['.$index.'][post]', 0, 20), $acp->frm->frmText('titleSetting['.$index.'][image]', '', 20));

  $acp->newTbl('User Group Title Images');
  $acp->newMenuRow('User Group', 'Title Image', 'Image Preview');
  $rs = $DB->query("SELECT usergroupid, title, groupname FROM celeste_usergroup ORDER BY usergroupid ASC");
  while($t = $rs->fetch() ) {
    if(isset($groupLevelImage[ $t['usergroupid'] ])) {
      $img = $groupLevelImage[ $t['usergroupid'] ];
      $pq_img = "<img src='".$img."' border=0>";
    } else {
      $img = '';
      $pq_img = '';
    }
    $acp->newRow2($t['title'].' [ '.$t['groupname'].' ] ', $acp->frm->frmText('groupLevelImage['.$t['usergroupid'].']', $img, 20), $pq_img);
  }

} else {

  $index = 0;
  $titleSetting = array();
  foreach($_POST['titleSetting'] as $t) {
    if(!empty($t['title'])) {
      $titleSetting[(int)$index]['post'] = $t['post'];
      $titleSetting[(int)$index]['title'] = $t['title'];
      $titleSetting[(int)$index]['image'] = $t['image'];
    }
    $index++;
  }
  uasort($titleSetting, 'compare_title');

  $groupLevelImage = array();
  foreach($_POST['groupLevelImage'] as $groupid => $t) {
    if(!empty($t)) {
      $groupLevelImage[(int)$groupid] = $t;
    }
  }

  $m = new modify_setting( DATA_PATH.'/settings/title.inc.php' );
  $m->cover();
  $m->setArray('titleSetting', $titleSetting);
  $m->setArray('groupLevelImage', $groupLevelImage);
  $m->save();

  acp_success_redirect('You have updated the user titles/ranks successfully', 'prog=user::title');

}
