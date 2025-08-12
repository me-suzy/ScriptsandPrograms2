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

if (!is_object($post)) {
  celeste_exception_handle('invalid_id');
}
 
import('attachment');
$att = new attach($post->getProperty('attachmentid'));
if (empty($att->attachmentid)) celeste_exception_handle('invalid_id');

$filetype =& $att->getProperty('filetype');
if (preg_match('/^image/', $filetype)) {
  // show it directly
  $att->hit();
  $att->output(1);

} else {
  // show form first
  $rating = $att->getProperty('rating');
  if ( empty($_POST['download']) && empty($_POST['openinline']) ) {
    $t->preload('attach_dl');
    $t->retrieve();

    $root =& $t->get('attach_dl');
    $t->setRoot($root);

    $t->set('pagetitle', SET_ATTACHMENT_DOWN_TITLE);
    $header=& $t->get('header');
    $header->set('pagetitle', SET_ATTACHMENT_DOWN_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_ATTACHMENT_DOWN_TITLE);
    $root->set('filename', $att->getProperty('filename'));
    $root->set('counter', $att->getProperty('counter'));
    $root->set('rating', $att->getProperty('rating'));
  } else {
  	$att->hit();
  	if ($rating>0 && SET_ATTACH_DL_PAY_RATING) {
  	  $DB->update('update celeste_user set totalrating=totalrating-'.$rating.' where userid=\''.$userid.'\'');
  	  $DB->update('update celeste_user set totalrating=totalrating+'.$rating.' where userid=\''.$post->getProperty('userid').'\'');
  	}
  	$att->output( empty($_POST['openinline']) ? 0 : 1);
  }
}
?>