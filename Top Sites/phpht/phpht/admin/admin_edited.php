<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                  admin/admin_edited.php file                 */
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
      if($HTTP_POST_VARS['password'] !== $HTTP_POST_VARS['password2']) {
         include('page_header_admin.php');
         $display = "Your passwords dont match";
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

      if(!$HTTP_POST_VARS['email']) {
         include('page_header_admin.php');
         $display = "Some fields were left blank";
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

      if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $HTTP_POST_VARS['email'])) {
         include('page_header_admin.php');
         $display = "That is not a valid email address.";
         $template->getFile(array(
                            'error' => 'admin/error.tpl')
         );
         $template->addVar("error", array(
                            'DISPLAY' => $display)
         );
         $template->parse("error");
         include('page_footer_admin.php');
         exit();
      }

      $sql = "SELECT password FROM ".$prefix."_users WHERE userid='$HTTP_POST_VARS[userid]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $password = $row['password'];
      }

      if($HTTP_POST_VARS['password'] == "") {
         $db_password = $password;
      } else {
         $db_password = md5($_POST['password']);
      }

      $sql = "UPDATE ".$prefix."_users SET email='$HTTP_POST_VARS[email]', password='$db_password' WHERE userid='$HTTP_POST_VARS[userid]'";
      $result = $db->query($sql);

      if(!$result) {
         include('page_header_admin.php');
         $display = "Could not edit the admin.";
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
         $display = "The admin has been edited.";
         $link = "admin.php";
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