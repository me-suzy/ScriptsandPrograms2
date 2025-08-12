<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                       logout.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
$phpht_real_path = "./";
include($phpht_real_path . 'common.php');

if(!isset($HTTP_GET_VARS['logmeout'])) {
   include($phpht_real_path . 'includes/page_header.php');
   $display = "Are you sure you want to logout.<br><center><a href=\"logout.php?logmeout\">Yes</a> | <a href=\"javascript:history.back()\">No</a></center>";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['error'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phpht_real_path . 'includes/page_footer.php');
} else { 
   setcookie("loged","no",time()-3600,$dir);
   setcookie("username","",time()-3600,$dir);
   setcookie("user_level","",time()-3600,$dir);
   setcookie("userid","",time()-3600,$dir);
   $session = uniqid('logout_');
   if(!session_is_registered('user_level')) {
      include($phpht_real_path . 'includes/page_header.php');
      $display = "You were just logged you.";
      $link = "index.php";
      $template->getFile(array(
                         'success' => 'success.tpl')
      );
      $template->add_vars(array(
                         'L_SUCCESS' => $lang['success'],
                         'DISPLAY' => $display,
                         'LINK' => $link)
      );
      $template->parse("success");
      include($phpht_real_path . 'includes/page_footer.php');
      exit();
   }
}  
?>