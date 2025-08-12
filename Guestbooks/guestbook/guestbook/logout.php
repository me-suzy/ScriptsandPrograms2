<?php
/****************************************************************/
/*                       phphg Guestbook                        */
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
$phphg_real_path = "./";
include($phphg_real_path . 'common.php');

if(!isset($HTTP_GET_VARS['logmeout'])) {
   include($phphg_real_path . 'includes/page_header.php');
   $display = "<center>Are you sure you want to logout</center><br><a href=\"logout.php?logmeout\">Yes</a> | <a href=\"javascript:history.back()\">No</a></center>";
   $template->getFile(array(
                      'error' => 'error.tpl')
   );
   $template->add_vars(array(
                      'L_ERROR' => $lang['adlogout'],
                      'DISPLAY' => $display)
   );
   $template->parse("error");
   include($phphg_real_path . 'includes/page_footer.php');
} else {
   setcookie("loged","no",time()-3600,$script_path);
   setcookie("username","",time()-3600,$script_path);
   setcookie("user_level","",time()-3600,$script_path);
   if(!session_is_registered('user_level')) {
      $display = "Your are now logged out.";
      $link = "index.php";
      include($phphg_real_path . 'includes/page_header.php');
      $template->getFile(array(
                         'success' => 'success.tpl')
      );
      $template->add_vars(array(
                         'L_SUCCESS' => $lang['success'],
                         'DISPLAY' => $display,
                         'LINK' => $link)
      );
      $template->parse("success");
      include($phphg_real_path . 'includes/page_footer.php');
   }
}
?>