<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                  admin/reset_stats.php file                  */
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
$phpht_real_path = "./../";
include($phpht_real_path . 'common.php');

if($HTTP_COOKIE_VARS['loged'] == 'yes') {
   if($HTTP_COOKIE_VARS['user_level'] == '1') {
      if($HTTP_POST_VARS['submit']) {
         $sql = "UPDATE ".$prefix."_links SET hits_in='0', hits_out='0'";
         $result = $db->query($sql);

         if(!$result) {
            include('page_header_admin.php');
            $display = "Could not reset the stats.";
            $template->getFile(array(
                               'error' => 'admin/error.tpl')
            );
            $template->add_vars(array(
                               'L_ERROR' => $lang['error'],
                               'DISPLAY' => $display)
            );
            $template->parse("error");
            include('page_footer_admin.php');
            exit();
          } else {
            include('page_header_admin.php');
            $display = "The stats have been reset.";
            $link = "index.php";
            $template->getFile(array(
                               'success' => 'admin/success.tpl')
            );
            $template->add_vars(array(
                               'L_SUCCESS' => $lang['success'],
                               'DISPLAY' => $display,
                               'LINK' => $link)
            );
            $template->parse("success");
            include('page_footer_admin.php');
            exit();
          }
       } else {
          include('page_header_admin.php');
          $display = "You have to click the reset button to reset the stats.";
          $template->getFile(array(
                             'error' => 'admin/error.tpl')
          );
          $template->add_vars(array(
                             'L_ERROR' => $lang['error'],
                             'DISPLAY' => $display)
          );
          $template->parse("error");
          include('page_footer_admin.php');
          exit();
       }
   } else {
      include('page_header_admin.php');
      $display = "You do not have permission to view this page";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include('page_footer_admin.php');
      exit();
   }
} else {
      include('page_header_admin.php');
      $display = "You are not logged in Please do so <a href=\"../admin.php\">Here</a>.";
      $template->getFile(array(
                         'error' => 'admin/error.tpl')
      );
      $template->add_vars(array(
                         'L_ERROR' => $lang['error'],
                         'DISPLAY' => $display)
      );
      $template->parse("error");
      include('page_footer_admin.php');
      exit();
}
?>