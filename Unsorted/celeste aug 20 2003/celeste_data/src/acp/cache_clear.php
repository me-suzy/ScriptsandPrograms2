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

if(!empty($_POST['ca']))
{
  
  $dir = dir(DATA_PATH.'/cache');
  $dir->read();$dir->read(); // pass '.' and '..'
  while($cacheName = $dir->read()) {
    unlink(DATA_PATH.'/cache/'.$cacheName);
  } // end of while

  acp_success_redirect('You have deleted all the cache successfully', $_SERVER['PHP_SELF'].'?prog=cache::clear');
}
elseif(!empty($_POST['acpSubmit']))
{
  $cache_to_delete =& array_merge($_POST['cacheList']['tr'], $_POST['cacheList']['tp'], $_POST['cacheList']['ot'], $_POST['cacheList']['fs']);
  foreach($cache_to_delete as $cacheName) {
    unlink(DATA_PATH.'/cache/'.$cacheName.'.tmp');
  }

  acp_success_redirect('You have deleted the selected cache successfully', $_SERVER['PHP_SELF'].'?prog=cache::clear');

}
else
{
  $cacheList = array( 'tr'=>array(), 'tp'=>array(), 'fs'=>array(), 'ot'=>array() );
  // tr = forum nav bar
  // tp = template cache
  // fs = forum search - forum list
  // ot = other

  $dir = dir(DATA_PATH.'/cache');
  $dir->read();$dir->read(); // pass '.' and '..'
  while($cacheName = $dir->read()) {
    $cacheName =& str_replace('.tmp', '', $cacheName);
    switch(substr($cacheName, 0, 2)) {
      case 'tr': $cacheList['tr'][] = $cacheName; break;
      case 'FS': $cacheList['fs'][] = $cacheName; break;
      case 'av': $cacheList['ot'][] = $cacheName; break;
      default:   $cacheList['tp'][] = $cacheName; break;
    }

  } // end of while


  $acp->newFrm('Clear All');
  $acp->setFrmBtn('Clear All', 'ca');

  $acp->newFrm('Clear Cache');
  $acp->setFrmBtn('Delete Selected');

  $acp->newTbl('Forum Nav Bars', 'tr');
  foreach($cacheList['tr'] as $cacheName) {
    $acp->newRow($cacheName, $acp->frm->frmAnOp('delete_cache[tr]["'.$cacheName.'"]'));
  }

  $acp->newTbl('Forum Lists', 'fs');
  foreach($cacheList['fs'] as $cacheName) {
    $acp->newRow($cacheName, $acp->frm->frmAnOp('delete_cache[fs]["'.$cacheName.'"]'));
  }

  $acp->newTbl('Templates', 'tp');
  foreach($cacheList['tp'] as $cacheName) {
    $acp->newRow($cacheName, $acp->frm->frmAnOp('delete_cache[tp]["'.$cacheName.'"]'));
  }

  $acp->newTbl('Others', 'ot');
  foreach($cacheList['ot'] as $cacheName) {
    $acp->newRow($cacheName, $acp->frm->frmAnOp('delete_cache[ot]["'.$cacheName.'"]'));
  }

}