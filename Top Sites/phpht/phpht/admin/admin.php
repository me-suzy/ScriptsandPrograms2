<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                       admin.php file                         */
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
      $sql = "SELECT * FROM ".$prefix."_users WHERE user_level='1' ORDER BY 'username' DESC";
      $result = $db->query($sql);

      $admin = "";
      while($row = $db->fetch($result)) {
            $userid = $row['userid'];
            $user = $row['username'];
            $activated = $row['activated'];
            $email = $row['email'];

            if($activated == '0') {
               $activated = "No";
            } else
            if($activated == '1') {
               $activated = "Yes";
            }

            $template->add_block_vars("admins", array(
                                       'USERID' => $userid,
                                       'USER' => $user,
                                       'ACTIVATED' => $activated,
                                       'EMAIL' => $email)
            );
      }
      include('page_header_admin.php');
      $template->getFile(array(
                         'admin' => 'admin/admin.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_ADD' => $lang['add_admin'],
                         'L_USERNAME' => $lang['username'],
                         'L_EMAIL' => $lang['email'],
                         'L_ACT' => $lang['activated'],
                         'L_ACTION' => $lang['action'],
                         'L_PASSWORD' => $lang['password'],
                         'L_PASSWORD2' => $lang['password2'],)
      );
      $template->parse("admin");
      include('page_footer_admin.php');
      exit();
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