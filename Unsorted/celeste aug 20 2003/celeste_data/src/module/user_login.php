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

if($celeste->login)
{
  // log out // celeste_exception_handle('login_already');
  if (empty($_POST['submit']))
  {
    $t->preload('logout');
    $t->retrieve();

    $root =& $t->get('logout');
    $t->setRoot($root);

    $t->set('pagetitle', SET_LOGOUT_PAGE_TITLE);
    $header=& $t->get('header');
    $header->set('pagetitle', SET_LOGOUT_PAGE_TITLE);
    $header->set('nav', '<a class=nav href="index.php">'.SET_TITLE.'</a>&nbsp;&#187; '.SET_LOGOUT_PAGE_TITLE);

  }
  else
  {
    $celeste->unsetCookie('userid');
    $celeste->unsetCookie('password');
    $celeste->unsetCookie('lastvisit_'.$userid);
    $celeste->unsetCookie('thisvisit_'.$userid);
    //$DB->upadte('update celeste_useronline ******* where userid=\''.$userid.'\'');
    celeste_success_redirect('logout');
  }
  
} else {
  import('login');
  celeste_login();
}
?>