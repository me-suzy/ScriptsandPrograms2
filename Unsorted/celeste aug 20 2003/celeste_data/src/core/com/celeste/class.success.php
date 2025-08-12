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
class success{
	
 function success($successType, $redirectPra = '') {
	global $t,$celeste,$DB;
    
	if(SET_DISPLAY_REDIRECT_PAGE) {
      $t->preload('success');
      $t->preload('success_'.$successType);
      $t->retrieve();
      $DB->disconnect();
      $root =& $t->get('success');
      $t->setRoot($root);
      $root->set('msg', $t->getString('success_'.$successType));
      $root->set('time', SET_FORWARD_TIME);
      $root->set('url', 'index.php?'.$redirectPra);
      $t->pparse();
    
	} else {
      $DB->disconnect();
	  redirect($redirectUrl);
	}
    exit;
  }

}