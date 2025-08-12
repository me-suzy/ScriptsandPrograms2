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
include( DATA_PATH.'/settings/acp_shortcut.inc.php');
include( DATA_PATH.'/src/acp/acp_modules.php' );
import('modify_setting');

if(empty($_POST['acpSubmit']))
{

  $acp->newFrm('Set ACP Short Cuts');
  $acp->setFrmBtn();

  foreach($acp_group as $groupid => $group)
  {
    $acp->newTbl($group);

    foreach($acp_category[$groupid] as $cateid => $category)
    {
      $acp->newRow('<font color=#330099>'.$category.'</a>');

      foreach($acp_module[$groupid][$cateid] as $prog => $module)
      {
        $iden_string = $groupid.'|'.$cateid.'|'.$prog;
        $acp->newRow('<a href='.$_SERVER['PHP_SELF'].'?prog='.$prog.'>'.$module.'</a>',
                    $acp->frm->frmAnOp('acp_shortcut['.$iden_string.']', $acpShortcuts[$iden_string]) );

      } // end of acp_module

    } // end of acp_cate

  } // end of acp_group

}
else
{

  $acpShortcuts = array();

  foreach($_POST['acp_shortcut'] as $iden_string => $shortcut) {
    if($shortcut) {

      $acpShortcuts[$iden_string] = 1;

    }

  }

  $m = new modify_setting( DATA_PATH.'/settings/acp_shortcut.inc.php' );
  $m->cover();
  $m->setArray('acpShortcuts', $acpShortcuts);
  $m->save();

  acp_success_redirect('You have updated the shortcut settings successfully.', $_SERVER['PHP_SELF'].'?prog=welcome');


}