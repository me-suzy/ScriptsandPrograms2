<?php
/****************************************************************/
/*                       phpht Topsites                         */
/*                   admin/edit_admin.php file                  */
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
      $sql = "SELECT * FROM ".$prefix."_users WHERE userid='$HTTP_GET_VARS[userid]'";
      $result = $db->query($sql);

      while($row = $db->fetch($result)) {
            $userid = $row['userid'];
            $user = $row['username'];
            $email = $row['email'];
            
            $user = $user;
            $email = "<input type=\"text\" name=\"email\" id=\"email\" value=\"$email\">";
            $hidden = "<input type=\"hidden\" name=\"userid\" value=\"$userid\">";
      }
      include('page_header_admin.php');
      $template->getFile(array(
                         'edit_admin' => 'admin/edit_admin.tpl')
      );
      $template->add_vars(array(
                         'L_NAV' => $lang['navigation'],
                         'L_EDIT' => $lang['edit_admin'],
                         'L_USERNAME' => $lang['username'],
                         'L_EMAIL' => $lang['email'],
                         'L_PASSWORD' => $lang['password'],
                         'L_PASSWORD2' => $lang['password2'],

                         'USER' => $user,
                         'EMAIL' => $email,
                         'HIDDEN' => $hidden)
      );
      $template->parse("edit_admin");
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