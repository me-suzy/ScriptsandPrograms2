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

class exception{
 
/**
 * celeste error handle
 * 
 * @param exceptionLevel
 *  - 0 = high priority, load from template(file)
 *  - 1 = load from template(db)
 *
 * usage:
 *  celeste_exception_handle( 'close', 0, set_board_close_msg );
 *  celeste_exception_handle( 'forum_not_exists' );
 */
  function exception($exceptionType, $exceptionLevel = 1) {
    // echo "Debugging ---- " . $exceptionType; exit;
    if($exceptionLevel) {
      global $t, $celeste, $thisprogs;
      global $DB;
      //print $exceptionType;

      $t->preload('exception');
      $t->preload('exception_'.$exceptionType);
      $t->retrieve();
      $root =& $t->get('exception');
      $t->setRoot($root);
      $root->set('msg', $t->getException($exceptionType));
      $DB->disconnect();
      $header=&$t->get('header');
      $header->set('pagetitle', 'Error');
      $root->set('header', $header->parse());
      $root->set('adminemail', SET_ADMIN_EMAIL);
      $t->pparse();

    } else {
      global $DB;
      include DATA_PATH.'/exception_handle/'.$exceptionType.'.php';
      @$DB->disconnect();
    }
    exit;
  } // end of function 'celeste_exception_handle'
}